@extends('dashboard.layout')

@php
    $pageTitle = 'Kamus & Buku Kosakata';
    $pageSubtitle = 'Eksplorasi kosakata JLPT N5, simpan kata favorit ke buku saku pribadimu, dan catat mnemonik hafalan.';
@endphp

@section('styles')
    .dict-page {
        max-width: 1100px;
        margin: 0 auto;
    }

    /* ── Top Header Banner ── */
    .dict-header-card {
        background: linear-gradient(135deg, #1d1838 0%, #2e1c3e 100%);
        border-radius: 20px;
        padding: 28px 32px;
        color: #fff;
        margin-bottom: 24px;
        box-shadow: 0 16px 36px -12px rgba(29, 24, 56, .4);
        position: relative;
        overflow: hidden;
    }
    .dict-header-card::after {
        content: '語彙';
        position: absolute;
        right: 24px;
        bottom: -15px;
        font-family: 'Noto Sans JP', sans-serif;
        font-size: 110px;
        font-weight: 900;
        color: rgba(255, 255, 255, .04);
        pointer-events: none;
        line-height: 1;
    }
    .dict-header-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }
    .dict-header-text h1 {
        font-size: 24px;
        font-weight: 900;
        margin-bottom: 6px;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .dict-header-text p {
        color: rgba(255, 255, 255, .75);
        font-size: 13.5px;
        max-width: 580px;
        line-height: 1.5;
        margin: 0;
    }
    .dict-header-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .btn-dict-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 12px;
        font-size: 13.5px;
        font-weight: 800;
        cursor: pointer;
        text-decoration: none;
        transition: all .2s ease;
        border: none;
    }
    .btn-dict-primary {
        background: linear-gradient(135deg, var(--red-2), var(--red));
        color: #fff;
        box-shadow: 0 6px 18px -4px rgba(232, 35, 79, .5);
    }
    .btn-dict-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px -4px rgba(232, 35, 79, .65);
    }
    .btn-dict-secondary {
        background: rgba(255, 255, 255, .12);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, .2);
        backdrop-filter: blur(8px);
    }
    .btn-dict-secondary:hover {
        background: rgba(255, 255, 255, .2);
        transform: translateY(-1px);
    }

    /* ── Tab Switcher ── */
    .dict-tabs {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        border-bottom: 2px solid var(--line);
        padding-bottom: 4px;
        flex-wrap: wrap;
    }
    .dict-tab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 12px 12px 0 0;
        font-size: 14px;
        font-weight: 800;
        color: var(--muted);
        text-decoration: none;
        transition: .2s;
        border-bottom: 3px solid transparent;
        margin-bottom: -6px;
    }
    .dict-tab:hover {
        color: var(--ink);
    }
    .dict-tab.active {
        color: var(--red);
        border-bottom-color: var(--red);
        background: rgba(196, 16, 46, .05);
    }
    .dict-tab-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 2px 8px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 900;
        background: #eee;
        color: #555;
    }
    .dict-tab.active .dict-tab-pill {
        background: var(--red-soft);
        color: var(--red);
    }

    /* ── Search & Filter Box ── */
    .dict-filter-box {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 18px;
        padding: 18px 20px;
        margin-bottom: 24px;
        box-shadow: var(--shadow-sm);
    }
    .dict-search-row {
        display: flex;
        gap: 12px;
        margin-bottom: 14px;
    }
    .dict-search-input-wrap {
        position: relative;
        flex: 1;
    }
    .dict-search-input-wrap svg {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
    }
    .dict-search-input {
        width: 100%;
        padding: 12px 16px 12px 42px;
        border-radius: 12px;
        border: 1.5px solid var(--line);
        background: #fff;
        font: 600 14px 'Inter', sans-serif;
        color: var(--ink);
        outline: none;
        transition: .2s;
    }
    .dict-search-input:focus {
        border-color: var(--red);
        box-shadow: 0 0 0 3px rgba(196, 16, 46, .1);
    }
    .dict-btn-filter-submit {
        padding: 0 20px;
        border-radius: 12px;
        background: var(--navy);
        color: #fff;
        font-weight: 800;
        border: none;
        cursor: pointer;
        transition: .2s;
    }
    .dict-btn-filter-submit:hover {
        background: var(--navy-2);
    }

    /* Category Pill Selector */
    .category-pills {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 4px;
        scrollbar-width: thin;
    }
    .category-pill {
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        color: var(--ink);
        background: #f4edf0;
        border: 1px solid transparent;
        transition: .18s;
    }
    .category-pill:hover {
        background: #eae0e4;
    }
    .category-pill.active {
        background: var(--red-soft);
        color: var(--red);
        border-color: rgba(196, 16, 46, .3);
    }

    /* ── Vocabulary Cards Grid ── */
    .dict-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 18px;
        margin-bottom: 32px;
    }
    .vocab-card {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 18px;
        padding: 20px;
        box-shadow: var(--shadow-sm);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all .2s cubic-bezier(.2, .85, .4, 1);
        position: relative;
    }
    .vocab-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow);
        border-color: rgba(196, 16, 46, .25);
    }

    .vocab-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }
    .vocab-cat-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 3px 10px;
        border-radius: 999px;
        background: var(--bg);
        border: 1px solid var(--line);
        font-size: 11px;
        font-weight: 800;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    /* Bookmark Star Button */
    .btn-bookmark {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1px solid var(--line);
        background: #fff;
        color: #c5b8c0;
        cursor: pointer;
        display: grid;
        place-items: center;
        transition: all .2s ease;
        flex-shrink: 0;
    }
    .btn-bookmark:hover {
        background: var(--gold-soft);
        color: var(--gold);
        border-color: var(--gold);
        transform: scale(1.08);
    }
    .btn-bookmark.is-active {
        background: #fff8db;
        color: #f59f00;
        border-color: #ffd43b;
        box-shadow: 0 4px 10px -3px rgba(245, 159, 0, .4);
    }

    .vocab-main {
        margin-bottom: 14px;
    }
    .vocab-japanese-wrap {
        display: flex;
        align-items: baseline;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 4px;
    }
    .vocab-japanese {
        font-family: 'Noto Sans JP', sans-serif;
        font-size: 26px;
        font-weight: 900;
        color: var(--ink);
        line-height: 1.2;
    }
    .vocab-furigana {
        font-family: 'Noto Sans JP', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: var(--red);
    }
    .vocab-romaji {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 700;
        color: var(--blue);
        margin-bottom: 8px;
    }

    .vocab-meaning {
        font-size: 15.5px;
        font-weight: 800;
        color: var(--ink);
        line-height: 1.4;
        margin-bottom: 12px;
    }

    /* Audio Speaker Button */
    .btn-audio-speak {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        background: var(--red-soft);
        color: var(--red);
        cursor: pointer;
        display: grid;
        place-items: center;
        transition: all .18s;
        margin-left: auto;
    }
    .btn-audio-speak:hover {
        background: var(--red);
        color: #fff;
        transform: scale(1.12);
    }
    .btn-audio-speak.pulse {
        animation: audioPulse .4s ease;
    }
    @keyframes audioPulse {
        50% { transform: scale(1.25); }
    }

    /* Example Box */
    .vocab-example {
        background: #faf7f9;
        border-left: 3px solid var(--red-2);
        border-radius: 0 10px 10px 0;
        padding: 9px 12px;
        font-size: 12.5px;
        color: #3f3139;
        margin-top: 10px;
        display: flex;
        flex-direction: column;
        gap: 3px;
    }
    .example-ja {
        font-family: 'Noto Sans JP', sans-serif;
        font-weight: 700;
        color: var(--ink);
    }
    .example-id {
        color: var(--muted);
        font-style: italic;
    }

    /* Personal Notes on Favorite Card */
    .vocab-notes-box {
        margin-top: 12px;
        padding: 10px 12px;
        border-radius: 10px;
        background: #fff8e8;
        border: 1px dashed #e8c46c;
        font-size: 12.5px;
        color: #5c4109;
        position: relative;
    }
    .vocab-notes-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 800;
        font-size: 11.5px;
        margin-bottom: 4px;
        color: #8c620e;
        text-transform: uppercase;
        letter-spacing: .04em;
    }
    .btn-edit-note {
        background: transparent;
        border: none;
        color: #8c620e;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: underline;
        padding: 0;
    }
    .vocab-notes-text {
        line-height: 1.45;
        font-weight: 500;
    }

    .vocab-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px solid var(--line);
    }
    .btn-card-delete {
        background: transparent;
        border: none;
        color: #ba4758;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 6px;
        border-radius: 6px;
        transition: .18s;
    }
    .btn-card-delete:hover {
        background: #fde8ec;
        color: var(--red);
    }

    /* ── Empty State ── */
    .dict-empty-card {
        background: var(--card);
        border: 1.5px dashed var(--line);
        border-radius: 20px;
        padding: 48px 24px;
        text-align: center;
        margin: 20px 0;
    }
    .dict-empty-icon {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        background: var(--red-soft);
        color: var(--red);
        display: grid;
        place-items: center;
        margin: 0 auto 16px;
    }
    .dict-empty-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--ink);
        margin-bottom: 6px;
    }
    .dict-empty-desc {
        color: var(--muted);
        font-size: 13.5px;
        max-width: 440px;
        margin: 0 auto 20px;
        line-height: 1.5;
    }

    /* ── Modal Dialog ── */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(18, 12, 28, .6);
        backdrop-filter: blur(4px);
        z-index: 1000;
        display: none;
        place-items: center;
        padding: 20px;
        overflow-y: auto;
    }
    .modal-overlay.open {
        display: grid;
    }
    .modal-dialog {
        background: #fff;
        border-radius: 20px;
        max-width: 520px;
        width: 100%;
        padding: 28px;
        box-shadow: 0 24px 60px -15px rgba(0, 0, 0, .3);
        animation: modalScale .25s cubic-bezier(.2, .85, .4, 1.15);
    }
    @keyframes modalScale {
        from { opacity: 0; transform: scale(.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
    }
    .modal-title {
        font-size: 18px;
        font-weight: 900;
        color: var(--ink);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-modal-close {
        background: transparent;
        border: none;
        color: var(--muted);
        font-size: 20px;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 8px;
    }
    .btn-modal-close:hover {
        background: #f4edf0;
        color: var(--ink);
    }

    .form-group {
        margin-bottom: 14px;
    }
    .form-label {
        display: block;
        font-size: 12.5px;
        font-weight: 800;
        color: var(--ink);
        margin-bottom: 6px;
    }
    .form-input, .form-textarea, .form-select {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1.5px solid var(--line);
        font: 500 13.5px 'Inter', sans-serif;
        outline: none;
        transition: .2s;
        box-sizing: border-box;
    }
    .form-input:focus, .form-textarea:focus, .form-select:focus {
        border-color: var(--red);
        box-shadow: 0 0 0 3px rgba(196, 16, 46, .1);
    }
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    @media (max-width: 640px) {
        .dict-header-card { padding: 22px 20px; }
        .dict-header-card::after { display: none; }
        .dict-header-actions { width: 100%; }
        .btn-dict-action { flex: 1; justify-content: center; }
        .dict-grid { grid-template-columns: 1fr; }
        .form-row { grid-template-columns: 1fr; }
    }
@endsection

@section('content')
<div class="dict-page">
    <!-- Top Header Banner -->
    <div class="dict-header-card">
        <div class="dict-header-inner">
            <div class="dict-header-text">
                <h1>
                    <span style="display:inline-flex; align-items:center; vertical-align:middle; margin-right:6px;">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                    </span>
                    Kamus & Buku Kosakata
                </h1>
                <p>Cari arti kosakata, dengarkan pelafalan asli, simpan kata-kata sulit ke buku saku pribadimu, dan latih hafalanmu kapan saja!</p>
            </div>

            <div class="dict-header-actions">
                <button type="button" class="btn-dict-action btn-dict-secondary" id="btnOpenAddModal">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    + Tambah Kata Baru
                </button>

                @if($totalFavorites > 0)
                    <a href="{{ route('dictionary.flashcards') }}" class="btn-dict-action btn-dict-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                        </svg>
                        Latihan Flashcard ({{ $totalFavorites }})
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="dict-tabs">
        <a href="{{ route('dictionary.index', ['tab' => 'all', 'category' => $selectedCat]) }}" class="dict-tab {{ $tab === 'all' ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
            </svg>
            Kamus Lengkap
            <span class="dict-tab-pill">{{ $allDictTotal }}</span>
        </a>

        <a href="{{ route('dictionary.index', ['tab' => 'favorites', 'category' => $selectedCat]) }}" class="dict-tab {{ $tab === 'favorites' ? 'active' : '' }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
            </svg>
            Buku Kosakata Favorit
            <span class="dict-tab-pill" id="favCountBadge">{{ $totalFavorites }}</span>
        </a>
    </div>

    <!-- Filter & Search Box -->
    <div class="dict-filter-box">
        <form action="{{ route('dictionary.index') }}" method="GET">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <input type="hidden" name="category" value="{{ $selectedCat }}" id="selectedCatInput">

            <div class="dict-search-row">
                <div class="dict-search-input-wrap">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" name="q" value="{{ $query }}" class="dict-search-input" placeholder="Cari kosakata Jepang, romaji, atau arti bahasa Indonesia..." autofocus>
                </div>
                <button type="submit" class="dict-btn-filter-submit">Cari</button>
            </div>

            <!-- Categories pills -->
            <div class="category-pills">
                @foreach($categories as $cat)
                    <a href="{{ route('dictionary.index', ['tab' => $tab, 'category' => $cat, 'q' => $query]) }}" class="category-pill {{ $selectedCat === $cat ? 'active' : '' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </form>
    </div>

    <!-- Cards Result Grid -->
    @if(empty($entries) || count($entries) === 0)
        <div class="dict-empty-card">
            <div class="dict-empty-icon">
                @if($tab === 'favorites')
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                @else
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                @endif
            </div>

            @if($tab === 'favorites')
                <h3 class="dict-empty-title">Buku Kosakata Masih Kosong</h3>
                <p class="dict-empty-desc">Kamu belum menyimpan kosakata favorit. Buka tab <strong>Kamus Lengkap</strong> dan klik ikon bintang (⭐) pada kata yang ingin kamu simpan ke buku sakumu!</p>
                <a href="{{ route('dictionary.index', ['tab' => 'all']) }}" class="btn-dict-action btn-dict-primary" style="display:inline-flex;">
                    Jelajahi Kamus Sekarang
                </a>
            @else
                <h3 class="dict-empty-title">Kosakata Tidak Ditemukan</h3>
                <p class="dict-empty-desc">Tidak ada kata yang cocok dengan pencarian "<strong>{{ $query }}</strong>". Coba kata kunci lain atau pilih kategori Semua.</p>
                <a href="{{ route('dictionary.index', ['tab' => 'all', 'category' => 'Semua']) }}" class="btn-dict-action btn-dict-secondary" style="display:inline-flex; color:var(--ink); border-color:var(--line);">
                    Reset Pencarian
                </a>
            @endif
        </div>
    @else
        <div class="dict-grid">
            @foreach($entries as $item)
                @php
                    $isFavTab = ($tab === 'favorites');
                    $isBookmarked = $isFavTab ? true : ($item['is_bookmarked'] ?? false);
                    $bookmarkId = $isFavTab ? $item->id : ($item['bookmark_id'] ?? null);
                    $wordKey = $isFavTab ? $item->word_key : $item['word_key'];
                    $japanese = $isFavTab ? $item->japanese : $item['japanese'];
                    $reading = $isFavTab ? $item->reading : $item['reading'];
                    $romaji = $isFavTab ? $item->romaji : $item['romaji'];
                    $meaning = $isFavTab ? $item->meaning : $item['meaning'];
                    $categoryName = $isFavTab ? $item->category : $item['category'];
                    $exampleJa = $isFavTab ? $item->example_ja : ($item['example_ja'] ?? null);
                    $exampleId = $isFavTab ? $item->example_id : ($item['example_id'] ?? null);
                    $notes = $isFavTab ? $item->notes : ($item['notes'] ?? null);
                    $audioWord = $reading ?: $japanese;
                @endphp

                <div class="vocab-card" id="vocabCard-{{ $wordKey }}">
                    <div>
                        <div class="vocab-card-top">
                            <span class="vocab-cat-badge">
                                {{ $categoryName }}
                            </span>

                            <button type="button" 
                                    class="btn-bookmark {{ $isBookmarked ? 'is-active' : '' }}" 
                                    title="{{ $isBookmarked ? 'Hapus dari Buku Favorit' : 'Simpan ke Buku Favorit' }}"
                                    onclick="toggleBookmark(this, {{ json_encode([
                                        'word_key'   => $wordKey,
                                        'japanese'   => $japanese,
                                        'reading'    => $reading,
                                        'romaji'     => $romaji,
                                        'meaning'    => $meaning,
                                        'category'   => $categoryName,
                                        'example_ja' => $exampleJa,
                                        'example_id' => $exampleId,
                                    ]) }})">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="{{ $isBookmarked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                </svg>
                            </button>
                        </div>

                        <div class="vocab-main">
                            <div class="vocab-japanese-wrap">
                                <span class="vocab-japanese">{{ $japanese }}</span>
                                @if($reading && $reading !== $japanese)
                                    <span class="vocab-furigana">（{{ $reading }}）</span>
                                @endif
                                <button type="button" class="btn-audio-speak" title="Dengarkan pelafalan" onclick="speakText('{{ addslashes($audioWord) }}', this)">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                                        <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="vocab-romaji">{{ $romaji }}</div>
                            <div class="vocab-meaning">{{ $meaning }}</div>

                            @if($exampleJa)
                                <div class="vocab-example">
                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                        <span class="example-ja">{{ $exampleJa }}</span>
                                        <button type="button" style="background:none;border:none;cursor:pointer;color:var(--muted);padding:2px;" title="Dengarkan contoh kalimat" onclick="speakText('{{ addslashes($exampleJa) }}', this)">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                                                <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    @if($exampleId)
                                        <span class="example-id">{{ $exampleId }}</span>
                                    @endif
                                </div>
                            @endif

                            @if($isFavTab)
                                <!-- Personal Notes Box on Favorite Card -->
                                <div class="vocab-notes-box" id="noteBox-{{ $bookmarkId }}">
                                    <div class="vocab-notes-header">
                                        <span style="display:inline-flex; align-items:center; gap:6px;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                            Catatan Pribadi
                                        </span>
                                        <button type="button" class="btn-edit-note" onclick="openNoteModal({{ $bookmarkId }}, '{{ addslashes($japanese) }}', '{{ addslashes($notes ?? '') }}')">
                                            {{ $notes ? 'Ubah' : '+ Tambah Catatan' }}
                                        </button>
                                    </div>
                                    <div class="vocab-notes-text" id="noteText-{{ $bookmarkId }}">
                                        {{ $notes ?: 'Belum ada catatan pribadi. Klik ubah untuk menulis tips hafalan.' }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($isFavTab)
                        <div class="vocab-card-footer">
                            <span style="font-size:11.5px; color:var(--muted);">
                                Ditambahkan {{ $item->created_at ? $item->created_at->diffForHumans() : 'baru saja' }}
                            </span>
                            <form action="{{ route('dictionary.destroy', $bookmarkId) }}" method="POST" onsubmit="return confirm('Hapus kosakata ini dari daftar favorit?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-card-delete">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Modal: Tambah Kata Manual -->
<div class="modal-overlay" id="addWordModal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3 class="modal-title" style="display:flex; align-items:center; gap:8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Tambah Kosakata Baru
            </h3>
            <button type="button" class="btn-modal-close" onclick="closeAddModal()">&times;</button>
        </div>

        <form action="{{ route('dictionary.custom-word') }}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tulisan Jepang (Kanji / Kana) *</label>
                    <input type="text" name="japanese" class="form-input" placeholder="Contoh: 友達 atau ともだち" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Cara Baca (Furigana / Hiragana)</label>
                    <input type="text" name="reading" class="form-input" placeholder="Contoh: ともだち">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Romaji *</label>
                    <input type="text" name="romaji" class="form-input" placeholder="Contoh: tomodachi" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="category" class="form-select">
                        <option value="Kosakata Kustom">Kosakata Kustom</option>
                        <option value="Kata Kerja Dasar">Kata Kerja Dasar</option>
                        <option value="Kata Sifat-i">Kata Sifat-i</option>
                        <option value="Kata Sifat-na">Kata Sifat-na</option>
                        <option value="Benda & Kehidupan">Benda & Kehidupan</option>
                        <option value="Salam & Ungkapan">Salam & Ungkapan</option>
                        <option value="Makanan & Minuman">Makanan & Minuman</option>
                        <option value="Tempat & Arah">Tempat & Arah</option>
                        <option value="Keluarga & Orang">Keluarga & Orang</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Arti Bahasa Indonesia *</label>
                <input type="text" name="meaning" class="form-input" placeholder="Contoh: Teman / Kawan" required>
            </div>

            <div class="form-group">
                <label class="form-label">Contoh Kalimat Jepang (Opsional)</label>
                <input type="text" name="example_ja" class="form-input" placeholder="Contoh: ともだちに あいます。">
            </div>

            <div class="form-group">
                <label class="form-label">Arti Contoh Kalimat (Opsional)</label>
                <input type="text" name="example_id" class="form-input" placeholder="Contoh: Bertemu dengan teman.">
            </div>

            <div class="form-group">
                <label class="form-label">Catatan Pribadi / Tips Hafalan (Opsional)</label>
                <textarea name="notes" class="form-textarea" rows="2" placeholder="Tuliskan catatan khusus atau mnemonik hafalanmu di sini..."></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                <button type="button" class="btn-dict-action btn-dict-secondary" style="color:var(--ink); border-color:var(--line);" onclick="closeAddModal()">Batal</button>
                <button type="submit" class="btn-dict-action btn-dict-primary">Simpan ke Buku Favorit</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Ubah Catatan Pribadi -->
<div class="modal-overlay" id="noteModal">
    <div class="modal-dialog">
        <div class="modal-header">
            <h3 class="modal-title" style="display:flex; align-items:center; gap:8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
                Catatan untuk: <strong id="noteModalWord" style="color:var(--red);"></strong>
            </h3>
            <button type="button" class="btn-modal-close" onclick="closeNoteModal()">&times;</button>
        </div>

        <form id="noteForm" onsubmit="saveNoteAjax(event)">
            <input type="hidden" id="noteModalVocabId">
            <div class="form-group">
                <label class="form-label">Catatan / Mnemonik Pribadi</label>
                <textarea id="noteModalText" class="form-textarea" rows="4" placeholder="Tulis cara mengingat, bentuk perubahan kata, atau situasi penggunaannya..."></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:16px;">
                <button type="button" class="btn-dict-action btn-dict-secondary" style="color:var(--ink); border-color:var(--line);" onclick="closeNoteModal()">Batal</button>
                <button type="submit" class="btn-dict-action btn-dict-primary" id="btnSaveNote">Simpan Catatan</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Web Speech API Native Japanese Speaker
    function speakText(text, btnElement) {
        if (!('speechSynthesis' in window)) return;
        try {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'ja-JP';
            utterance.rate = 0.85;

            if (btnElement) {
                btnElement.classList.remove('pulse');
                void btnElement.offsetWidth;
                btnElement.classList.add('pulse');
            }

            window.speechSynthesis.speak(utterance);
        } catch (e) {
            console.warn('Speech error:', e);
        }
    }

    // Toggle Bookmark via AJAX
    function toggleBookmark(btn, data) {
        const csrf = '{{ csrf_token() }}';

        fetch("{{ route('dictionary.bookmark') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(result => {
            const svg = btn.querySelector('svg');
            const favBadge = document.getElementById('favCountBadge');

            if (result.is_favorite) {
                btn.classList.add('is-active');
                btn.title = 'Hapus dari Buku Favorit';
                if (svg) svg.setAttribute('fill', 'currentColor');

                // Jika ada reward XP
                if (result.xp_reward && typeof window.showXpToast === 'function') {
                    window.showXpToast(result.xp_reward);
                }
            } else {
                btn.classList.remove('is-active');
                btn.title = 'Simpan ke Buku Favorit';
                if (svg) svg.setAttribute('fill', 'none');

                // Jika kita berada di tab favorit, kartu bisa dihilangkan
                const currentTab = '{{ $tab }}';
                if (currentTab === 'favorites') {
                    const card = document.getElementById('vocabCard-' + data.word_key);
                    if (card) {
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.9)';
                        setTimeout(() => card.remove(), 250);
                    }
                }
            }

            if (favBadge && typeof result.total_favorites !== 'undefined') {
                favBadge.textContent = result.total_favorites;
            }
        })
        .catch(err => {
            console.error('Bookmark toggle error:', err);
        });
    }

    // Modal Add Word
    const addWordModal = document.getElementById('addWordModal');
    const btnOpenAddModal = document.getElementById('btnOpenAddModal');

    if (btnOpenAddModal) {
        btnOpenAddModal.addEventListener('click', () => {
            addWordModal.classList.add('open');
        });
    }

    function closeAddModal() {
        addWordModal.classList.remove('open');
    }

    // Modal Edit Notes
    const noteModal = document.getElementById('noteModal');
    const noteModalWord = document.getElementById('noteModalWord');
    const noteModalText = document.getElementById('noteModalText');
    const noteModalVocabId = document.getElementById('noteModalVocabId');

    function openNoteModal(vocabId, word, currentNotes) {
        noteModalVocabId.value = vocabId;
        noteModalWord.textContent = word;
        noteModalText.value = currentNotes;
        noteModal.classList.add('open');
    }

    function closeNoteModal() {
        noteModal.classList.remove('open');
    }

    function saveNoteAjax(e) {
        e.preventDefault();
        const vocabId = noteModalVocabId.value;
        const notes = noteModalText.value;
        const csrf = '{{ csrf_token() }}';

        fetch(`/kamus/${vocabId}/catatan`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ notes: notes })
        })
        .then(res => res.json())
        .then(result => {
            const noteTextElem = document.getElementById('noteText-' + vocabId);
            if (noteTextElem) {
                noteTextElem.textContent = result.notes || 'Belum ada catatan pribadi. Klik ubah untuk menulis tips hafalan.';
            }
            closeNoteModal();
        })
        .catch(err => console.error('Save note error:', err));
    }

    // Close modals on outside click
    [addWordModal, noteModal].forEach(modal => {
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.classList.remove('open');
            });
        }
    });
</script>
@endsection

