<?php

namespace App\Http\Controllers;

use App\Models\FlashcardProgress;
use App\Models\Material;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FlashcardController extends Controller
{
    /**
     * Tampilkan daftar deck flashcard yang tersedia beserta status Spaced Repetition (SRS).
     */
    public function index(): View
    {
        $user = Auth::user();

        $materials = Material::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        // Ambil semua progres SRS milik user
        $allProgress = FlashcardProgress::query()
            ->where('user_id', $user->id)
            ->get()
            ->groupBy('material_id');

        $now = Carbon::now();

        $totalCardsOverall    = 0;
        $totalMasteredOverall = 0;
        $totalDueOverall      = 0;
        $totalLearnedOverall  = 0;

        $decks = $materials->map(function (Material $material) use ($allProgress, $now, &$totalCardsOverall, &$totalMasteredOverall, &$totalDueOverall, &$totalLearnedOverall): array {
            $cardCount = $this->calculateCardCount($material);
            $totalCardsOverall += $cardCount;

            $progressForMaterial = $allProgress->get($material->id, collect());

            $learnedCount  = $progressForMaterial->count();
            $masteredCount = $progressForMaterial->where('box', '>=', 3)->count();
            $dueCount      = $progressForMaterial->filter(fn (FlashcardProgress $p) => $p->next_review_at <= $now)->count();

            $totalLearnedOverall  += $learnedCount;
            $totalMasteredOverall += $masteredCount;
            $totalDueOverall      += $dueCount;

            $masteryPercent = $cardCount > 0 ? (int) round(($masteredCount / $cardCount) * 100) : 0;

            return [
                'material'        => $material,
                'title'           => $material->title,
                'slug'            => $material->slug,
                'category'        => $material->category,
                'description'     => $material->description,
                'card_count'      => $cardCount,
                'learned_count'   => $learnedCount,
                'mastered_count'  => $masteredCount,
                'due_count'       => $dueCount,
                'mastery_percent' => $masteryPercent,
            ];
        });

        return view('dashboard.flashcards.index', [
            'user'                 => $user,
            'decks'                => $decks,
            'totalCards'           => $totalCardsOverall,
            'totalMasteredOverall' => $totalMasteredOverall,
            'totalDueOverall'      => $totalDueOverall,
            'totalLearnedOverall'  => $totalLearnedOverall,
        ]);
    }

    /**
     * Tampilkan sesi belajar flashcard interaktif untuk materi tertentu.
     */
    public function show(Material $material, Request $request): View
    {
        $user  = Auth::user();
        $cards = $this->extractCards($material);

        // Ambil riwayat SRS untuk material ini
        $progressMap = FlashcardProgress::query()
            ->where('user_id', $user->id)
            ->where('material_id', $material->id)
            ->get()
            ->keyBy('card_key');

        $now = Carbon::now();
        $filterMode = $request->query('mode', 'all'); // 'all', 'due', 'unlearned'

        // Suntikkan metadata SRS ke tiap kartu
        $cardsWithSrs = array_map(function (array $card) use ($progressMap, $now, $material): array {
            $p = $progressMap->get($card['card_key']);

            $box = $p ? $p->box : 0;
            $isDue = $p ? ($p->next_review_at <= $now) : true;
            $statusLabel = match ($box) {
                0 => 'Baru',
                1 => 'Belajar',
                2 => 'Mengingat',
                3 => 'Fasih',
                4, 5 => 'Mastered',
                default => 'Baru',
            };

            return array_merge($card, [
                'material_id'  => $material->id,
                'box'          => $box,
                'is_due'       => $isDue,
                'status_label' => $statusLabel,
                'review_count' => $p?->review_count ?? 0,
                'fail_count'   => $p?->fail_count ?? 0,
            ]);
        }, $cards);

        // Filter kartu jika user memilih mode khusus
        if ($filterMode === 'due') {
            $cardsWithSrs = array_values(array_filter($cardsWithSrs, fn (array $c) => $c['box'] > 0 && $c['is_due']));
        } elseif ($filterMode === 'unlearned') {
            $cardsWithSrs = array_values(array_filter($cardsWithSrs, fn (array $c) => $c['box'] === 0));
        }

        $totalInDeck    = count($cards);
        $masteredInDeck = $progressMap->where('box', '>=', 3)->count();
        $dueInDeck      = $progressMap->filter(fn (FlashcardProgress $p) => $p->next_review_at <= $now)->count();

        return view('dashboard.flashcards.show', [
            'user'           => $user,
            'material'       => $material,
            'cards'          => $cardsWithSrs,
            'filterMode'     => $filterMode,
            'totalInDeck'    => $totalInDeck,
            'masteredInDeck' => $masteredInDeck,
            'dueInDeck'      => $dueInDeck,
        ]);
    }

    /**
     * Sesi Review Harian SRS (Mengumpulkan kartu jatuh tempo dari seluruh deck).
     */
    public function dailyReview(): View
    {
        $user = Auth::user();
        $now  = Carbon::now();

        // Cari kartu-kartu yang sudah jatuh tempo
        $dueRecords = FlashcardProgress::query()
            ->where('user_id', $user->id)
            ->where('next_review_at', '<=', $now)
            ->with('material')
            ->orderBy('box') // Dahulukan box yang paling rendah
            ->take(60)
            ->get();

        $cards = [];

        foreach ($dueRecords as $record) {
            $material = $record->material;
            if (!$material || !$material->is_active) {
                continue;
            }

            $extracted = $this->extractCards($material);
            $matchedCard = collect($extracted)->firstWhere('card_key', $record->card_key);

            if ($matchedCard) {
                $statusLabel = match ($record->box) {
                    1 => 'Belajar',
                    2 => 'Mengingat',
                    3 => 'Fasih',
                    default => 'Mastered',
                };

                $cards[] = array_merge($matchedCard, [
                    'material_id'  => $material->id,
                    'box'          => $record->box,
                    'is_due'       => true,
                    'status_label' => $statusLabel,
                    'review_count' => $record->review_count,
                    'fail_count'   => $record->fail_count,
                ]);
            }
        }

        // Dummy Material instance untuk header view
        $fakeMaterial = new Material([
            'title'       => 'Review Harian Spaced Repetition',
            'slug'        => 'review-harian',
            'level'       => 'SRS',
            'description' => 'Sesi review khusus kartu-kartu yang jatuh tempo diulang hari ini.',
        ]);

        return view('dashboard.flashcards.show', [
            'user'           => $user,
            'material'       => $fakeMaterial,
            'cards'          => $cards,
            'filterMode'     => 'daily_review',
            'totalInDeck'    => count($cards),
            'masteredInDeck' => collect($cards)->where('box', '>=', 3)->count(),
            'dueInDeck'      => count($cards),
        ]);
    }

    /**
     * Simpan hasil kartu secara real-time via AJAX (Spaced Repetition System).
     */
    public function saveCardProgress(Request $request): JsonResponse
    {
        $request->validate([
            'material_id' => ['required', 'integer'],
            'card_key'    => ['required', 'string', 'max:64'],
            'result'      => ['required', 'string', 'in:mastered,repeat'],
        ]);

        $user       = Auth::user();
        $materialId = (int) $request->input('material_id');
        $cardKey    = $request->input('card_key');
        $result     = $request->input('result');

        $now = Carbon::now();

        $progress = FlashcardProgress::firstOrNew([
            'user_id'     => $user->id,
            'material_id' => $materialId,
            'card_key'    => $cardKey,
        ]);

        if ($result === 'mastered') {
            // Naikkan Leitner box (maksimum 5)
            $newBox = $progress->exists ? min(5, $progress->box + 1) : 2;
            $intervalDays = FlashcardProgress::BOX_INTERVALS[$newBox] ?? 3;

            $progress->box              = $newBox;
            $progress->review_count     = $progress->review_count + 1;
            $progress->next_review_at   = $now->copy()->addDays($intervalDays);
            $progress->last_reviewed_at = $now;
        } else {
            // Jika belum hafal, reset box kembali ke 1 agar segera diulang
            $progress->box              = 1;
            $progress->fail_count       = $progress->fail_count + 1;
            $progress->review_count     = $progress->review_count + 1;
            $progress->next_review_at   = $now->copy()->addHours(6); // Jadwalkan ulang dalam 6 jam
            $progress->last_reviewed_at = $now;
        }

        $progress->save();

        return response()->json([
            'status'         => 'ok',
            'box'            => $progress->box,
            'is_mastered'    => $progress->isMastered(),
            'next_review_at' => $progress->next_review_at->toIso8601String(),
        ]);
    }

    /**
     * Hitung jumlah kartu dalam materi.
     */
    private function calculateCardCount(Material $material): int
    {
        $content = $material->content ?? [];

        if (isset($content['sections']) && is_array($content['sections'])) {
            $total = 0;
            foreach ($content['sections'] as $sec) {
                if (isset($sec['characters']) && is_array($sec['characters'])) {
                    $total += count($sec['characters']);
                }
            }
            return $total;
        }

        if (isset($content['words']) && is_array($content['words'])) {
            return count($content['words']);
        }

        if (isset($content['kanji']) && is_array($content['kanji'])) {
            return count($content['kanji']);
        }

        if (isset($content['patterns']) && is_array($content['patterns'])) {
            return count($content['patterns']);
        }

        return 0;
    }

    /**
     * Ekstraksi dan normalisasi seluruh item menjadi kartu flashcard seragam dengan card_key unik.
     *
     * @return array<int, array{id: int, card_key: string, front: string, back_main: string, back_sub: string, category: string, audio: string, type: string, extra?: string}>
     */
    private function extractCards(Material $material): array
    {
        $content = $material->content ?? [];
        $cards   = [];
        $id      = 1;

        if ($material->category === 'hiragana' || $material->category === 'katakana') {
            foreach ($content['sections'] ?? [] as $section) {
                $sectionTitle = $section['title'] ?? 'Karakter';
                foreach ($section['characters'] ?? [] as $char) {
                    $cardKey = $material->category . '_' . $char['kana'];
                    $cards[] = [
                        'id'        => $id++,
                        'card_key'  => $cardKey,
                        'front'     => $char['kana'],
                        'back_main' => $char['romaji'],
                        'back_sub'  => $sectionTitle,
                        'category'  => $sectionTitle,
                        'audio'     => $char['kana'],
                        'type'      => 'kana',
                    ];
                }
            }
        } elseif ($material->category === 'vocabulary') {
            foreach ($content['words'] ?? [] as $word) {
                $cardKey = 'vocab_' . $word['japanese'];
                $cards[] = [
                    'id'        => $id++,
                    'card_key'  => $cardKey,
                    'front'     => $word['japanese'],
                    'back_main' => $word['meaning'],
                    'back_sub'  => $word['romaji'],
                    'category'  => $word['category'] ?? 'Kosakata',
                    'audio'     => $word['japanese'],
                    'type'      => 'vocab',
                ];
            }
        } elseif ($material->category === 'kanji') {
            foreach ($content['kanji'] ?? [] as $k) {
                $cardKey = 'kanji_' . $k['kanji'];
                $cards[] = [
                    'id'        => $id++,
                    'card_key'  => $cardKey,
                    'front'     => $k['kanji'],
                    'back_main' => $k['meaning'],
                    'back_sub'  => 'Kun: ' . $k['kun'] . '  •  On: ' . $k['on'],
                    'category'  => $k['category'] ?? 'Kanji N5',
                    'audio'     => $k['kanji'],
                    'type'      => 'kanji',
                ];
            }
        } elseif ($material->category === 'grammar') {
            foreach ($content['patterns'] ?? [] as $p) {
                $cardKey = 'grammar_' . md5($p['pattern']);
                $cards[] = [
                    'id'        => $id++,
                    'card_key'  => $cardKey,
                    'front'     => $p['pattern'],
                    'back_main' => $p['meaning'],
                    'back_sub'  => $p['romaji'],
                    'category'  => 'Pola Kalimat N5',
                    'extra'     => $p['example'] ?? '',
                    'audio'     => $p['example'] ?? $p['pattern'],
                    'type'      => 'grammar',
                ];
            }
        }

        return $cards;
    }
}
