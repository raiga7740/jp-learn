<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\UserVocabulary;
use App\Services\DictionaryService;
use App\Services\GamificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DictionaryController extends Controller
{
    public function __construct(
        public DictionaryService $dictionaryService,
        public GamificationService $gamificationService,
    ) {
    }

    /**
     * Tampilkan halaman Kamus Mini & Buku Kosakata Favorit.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = $request->query('q', '');
        $category = $request->query('category', 'Semua');
        $tab = $request->query('tab', 'all'); // 'all' atau 'favorites'

        // Ambil semua kata favorit milik user untuk pemetaan cepat
        $userFavorites = UserVocabulary::query()
            ->where('user_id', $user->id)
            ->where('is_favorite', true)
            ->latest()
            ->get();

        $favoriteKeyMap = $userFavorites->keyBy('word_key');
        $totalFavorites = $userFavorites->count();

        // Kategori yang tersedia
        $categories = $this->dictionaryService->getCategories();

        if ($tab === 'favorites') {
            // Filter buku kosakata favorit user
            $favList = $userFavorites;

            if ($category && $category !== 'Semua') {
                $favList = $favList->filter(function (UserVocabulary $v) use ($category): bool {
                    return stripos($v->category, $category) !== false || stripos($category, $v->category) !== false;
                });
            }

            if (!empty($query)) {
                $needle = mb_strtolower(trim($query));
                $favList = $favList->filter(function (UserVocabulary $v) use ($needle): bool {
                    $matchJa = mb_stripos($v->japanese, $needle) !== false;
                    $matchRe = $v->reading ? (mb_stripos($v->reading, $needle) !== false) : false;
                    $matchRo = stripos($v->romaji, $needle) !== false;
                    $matchMe = stripos($v->meaning, $needle) !== false;
                    $matchNo = $v->notes ? (stripos($v->notes, $needle) !== false) : false;

                    return $matchJa || $matchRe || $matchRo || $matchMe || $matchNo;
                });
            }

            $entries = $favList->values();
            $totalCount = $totalFavorites;
        } else {
            // Kamus lengkap
            $allEntries = $this->dictionaryService->search($query, $category);
            $totalCount = count($allEntries);

            // Suntikkan status bookmark ke tiap entri
            $entries = array_map(function (array $item) use ($favoriteKeyMap): array {
                $fav = $favoriteKeyMap->get($item['word_key']);
                return array_merge($item, [
                    'is_bookmarked' => $fav !== null,
                    'bookmark_id'   => $fav?->id,
                    'notes'         => $fav?->notes,
                ]);
            }, $allEntries);
        }

        $allDictTotal = count($this->dictionaryService->getAllEntries());

        return view('dashboard.dictionary.index', [
            'user'           => $user,
            'entries'        => $entries,
            'tab'            => $tab,
            'query'          => $query,
            'selectedCat'    => $category,
            'categories'     => $categories,
            'totalCount'     => $totalCount,
            'totalFavorites' => $totalFavorites,
            'allDictTotal'   => $allDictTotal,
        ]);
    }

    /**
     * Toggle bookmark kata ke Buku Kosakata Favorit (AJAX).
     */
    public function toggleBookmark(Request $request): JsonResponse
    {
        $request->validate([
            'word_key'   => ['required', 'string', 'max:64'],
            'japanese'   => ['required', 'string', 'max:191'],
            'romaji'     => ['required', 'string', 'max:191'],
            'meaning'    => ['required', 'string'],
            'reading'    => ['nullable', 'string', 'max:191'],
            'category'   => ['nullable', 'string', 'max:100'],
            'example_ja' => ['nullable', 'string'],
            'example_id' => ['nullable', 'string'],
        ]);

        $user = Auth::user();
        $wordKey = $request->input('word_key');

        $existing = UserVocabulary::query()
            ->where('user_id', $user->id)
            ->where('word_key', $wordKey)
            ->first();

        $reward = null;

        if ($existing) {
            $existing->delete();
            $isFavorite = false;
            $bookmarkId = null;
        } else {
            $newVocab = UserVocabulary::create([
                'user_id'    => $user->id,
                'word_key'   => $wordKey,
                'japanese'   => $request->input('japanese'),
                'reading'    => $request->input('reading'),
                'romaji'     => $request->input('romaji'),
                'meaning'    => $request->input('meaning'),
                'category'   => $request->input('category', 'Kosakata'),
                'example_ja' => $request->input('example_ja'),
                'example_id' => $request->input('example_id'),
                'is_favorite'=> true,
            ]);

            $isFavorite = true;
            $bookmarkId = $newVocab->id;

            // Beri XP untuk aksi menyimpan kosakata
            try {
                $reward = $this->gamificationService->award($user, 'vocab_saved');
            } catch (\Throwable) {
                // Abaikan jika ada limit atau error
            }
        }

        $totalFavorites = UserVocabulary::where('user_id', $user->id)->count();

        return response()->json([
            'status'          => $isFavorite ? 'saved' : 'removed',
            'is_favorite'     => $isFavorite,
            'bookmark_id'     => $bookmarkId,
            'total_favorites' => $totalFavorites,
            'xp_reward'       => $reward,
        ]);
    }

    /**
     * Tambah kata kustom manual oleh siswa.
     */
    public function storeCustomWord(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'japanese'   => ['required', 'string', 'max:191'],
            'romaji'     => ['required', 'string', 'max:191'],
            'meaning'    => ['required', 'string'],
            'reading'    => ['nullable', 'string', 'max:191'],
            'category'   => ['nullable', 'string', 'max:100'],
            'notes'      => ['nullable', 'string'],
            'example_ja' => ['nullable', 'string'],
            'example_id' => ['nullable', 'string'],
        ]);

        $user = Auth::user();
        $wordKey = 'custom_' . md5($user->id . $request->input('japanese') . microtime());

        $vocab = UserVocabulary::create([
            'user_id'    => $user->id,
            'word_key'   => $wordKey,
            'japanese'   => $request->input('japanese'),
            'reading'    => $request->input('reading') ?: $request->input('japanese'),
            'romaji'     => $request->input('romaji'),
            'meaning'    => $request->input('meaning'),
            'category'   => $request->input('category') ?: 'Kosakata Kustom',
            'notes'      => $request->input('notes'),
            'example_ja' => $request->input('example_ja'),
            'example_id' => $request->input('example_id'),
            'is_favorite'=> true,
        ]);

        try {
            $reward = $this->gamificationService->award($user, 'vocab_saved');
        } catch (\Throwable) {
            $reward = null;
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status'    => 'ok',
                'item'      => $vocab,
                'xp_reward' => $reward,
            ]);
        }

        return redirect()
            ->route('dictionary.index', ['tab' => 'favorites'])
            ->with('success', 'Kosakata baru berhasil ditambahkan ke Buku Favorit!');
    }

    /**
     * Update catatan pribadi (notes) pada kata favorit.
     */
    public function updateNotes(Request $request, UserVocabulary $vocabulary): JsonResponse
    {
        if ($vocabulary->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $request->validate([
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $vocabulary->update([
            'notes' => $request->input('notes'),
        ]);

        return response()->json([
            'status' => 'ok',
            'notes'  => $vocabulary->notes,
        ]);
    }

    /**
     * Hapus kata dari buku kosakata favorit.
     */
    public function destroy(UserVocabulary $vocabulary, Request $request): RedirectResponse|JsonResponse
    {
        if ($vocabulary->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $vocabulary->delete();

        if ($request->wantsJson()) {
            return response()->json(['status' => 'deleted']);
        }

        return back()->with('success', 'Kosakata dihapus dari buku favorit.');
    }

    /**
     * Sesi Flashcard Interaktif khusus kosakata favorit user.
     */
    public function flashcards(Request $request): View
    {
        $user = Auth::user();

        $favorites = UserVocabulary::query()
            ->where('user_id', $user->id)
            ->where('is_favorite', true)
            ->latest()
            ->get();

        $cards = [];
        $id = 1;

        foreach ($favorites as $fav) {
            $cards[] = [
                'id'            => $id++,
                'card_key'      => $fav->word_key,
                'front'         => $fav->japanese,
                'back_main'     => $fav->meaning,
                'back_sub'      => $fav->reading ? ($fav->reading . ' (' . $fav->romaji . ')') : $fav->romaji,
                'category'      => $fav->category,
                'audio'         => $fav->reading ?: $fav->japanese,
                'type'          => 'vocab',
                'extra'         => $fav->example_ja ? ($fav->example_ja . ' — ' . $fav->example_id) : null,
                'notes'         => $fav->notes,
                'material_id'   => 0,
                'box'           => 1,
                'status_label'  => 'Favorit',
            ];
        }

        // Dummy Material untuk header view
        $fakeMaterial = new Material([
            'title'       => 'Buku Kosakata Favorit',
            'slug'        => 'kosakata-favorit',
            'level'       => 'Buku Saku',
            'description' => 'Latihan hafalan kartu khusus kumpulan kosakata yang kamu simpan dan beri bintang.',
        ]);

        return view('dashboard.dictionary.flashcards', [
            'user'           => $user,
            'material'       => $fakeMaterial,
            'cards'          => $cards,
            'totalInDeck'    => count($cards),
        ]);
    }
}

