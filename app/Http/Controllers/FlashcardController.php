<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FlashcardController extends Controller
{
    /**
     * Tampilkan daftar deck flashcard yang tersedia.
     */
    public function index(): View
    {
        $user = Auth::user();

        $materials = Material::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $decks = $materials->map(function (Material $material): array {
            $cardCount = $this->calculateCardCount($material);

            return [
                'material'    => $material,
                'title'       => $material->title,
                'slug'        => $material->slug,
                'category'    => $material->category,
                'description' => $material->description,
                'card_count'  => $cardCount,
            ];
        });

        $totalCards = $decks->sum('card_count');

        return view('dashboard.flashcards.index', [
            'user'       => $user,
            'decks'      => $decks,
            'totalCards' => $totalCards,
        ]);
    }

    /**
     * Tampilkan sesi belajar flashcard interaktif untuk materi tertentu.
     */
    public function show(Material $material): View
    {
        $user  = Auth::user();
        $cards = $this->extractCards($material);

        return view('dashboard.flashcards.show', [
            'user'     => $user,
            'material' => $material,
            'cards'    => $cards,
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
     * Ekstraksi dan normalisasi seluruh item menjadi kartu flashcard seragam.
     *
     * @return array<int, array{id: int, front: string, back_main: string, back_sub: string, category: string, audio: string, type: string, extra?: string}>
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
                    $cards[] = [
                        'id'        => $id++,
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
                $cards[] = [
                    'id'        => $id++,
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
                $cards[] = [
                    'id'        => $id++,
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
                $cards[] = [
                    'id'        => $id++,
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

