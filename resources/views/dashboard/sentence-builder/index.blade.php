@extends('dashboard.layout')

@php
    $pageTitle = 'Susun Kalimat (Sentence Builder)';
    $pageSubtitle = 'Latih pemahaman pola tata bahasa & partikel bahasa Jepang dengan menyusun kepingan kata ke urutan yang tepat.';
@endphp

@section('styles')
    /* ── Sentence Builder Arena Styles ── */
    .builder-wrap {
        max-width: 920px;
        margin: 0 auto;
    }

    /* ── Hero Banner ── */
    .builder-hero {
        background: linear-gradient(135deg, #181926 0%, #2a1f33 100%);
        border-radius: 22px;
        padding: 30px 32px 26px;
        color: #fff;
        margin-bottom: 22px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 18px 40px -12px rgba(24, 25, 38, .4);
    }
    .builder-hero::after {
        content: '文作り';
        position: absolute;
        right: 18px;
        bottom: -25px;
        font-family: 'Noto Sans JP', sans-serif;
        font-size: 120px;
        font-weight: 900;
        color: rgba(255, 255, 255, .03);
        pointer-events: none;
        line-height: 1;
        user-select: none;
    }
    .builder-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .14);
        color: #ffd8a8;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 12px;
    }
    .builder-hero h2 {
        font-size: 24px;
        font-weight: 900;
        margin: 0 0 8px;
        line-height: 1.25;
        letter-spacing: -.02em;
    }
    .builder-hero p {
        color: rgba(255, 255, 255, .72);
        font-size: 13.5px;
        line-height: 1.55;
        margin: 0;
        max-width: 620px;
    }

    /* ── Control Bar & Level Tabs ── */
    .builder-controls {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 20px;
        padding: 12px 18px;
        background: #fff;
        border-radius: 16px;
        border: 1px solid var(--line);
        box-shadow: 0 2px 10px rgba(0, 0, 0, .02);
    }
    .level-tabs-row {
        display: flex;
        align-items: center;
        gap: 6px;
        overflow-x: auto;
        padding-bottom: 2px;
    }
    .level-tab-btn {
        padding: 7px 14px;
        border-radius: 10px;
        border: 1px solid var(--line);
        background: #faf7f5;
        color: var(--ink);
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all .18s ease;
        white-space: nowrap;
    }
    .level-tab-btn:hover {
        background: #fff;
        border-color: var(--red);
        color: var(--red);
    }
    .level-tab-btn.active {
        background: linear-gradient(135deg, var(--red) 0%, var(--red-2, #e8234f) 100%);
        border-color: var(--red);
        color: #ffffff !important;
        box-shadow: 0 4px 12px -2px rgba(196, 16, 46, .35);
    }

    .toggles-group {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .btn-toggle-option {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 10px;
        background: #faf7f5;
        border: 1px solid var(--line);
        color: var(--muted);
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: .15s ease;
    }
    .btn-toggle-option:hover {
        background: #fff;
        color: var(--ink);
    }
    .btn-toggle-option.active {
        background: #e6fcf5;
        border-color: #63e6be;
        color: #087f5b;
        font-weight: 700;
    }

    /* ── Main Arena Card ── */
    .arena-card {
        background: #fff;
        border-radius: 22px;
        border: 1px solid var(--line);
        padding: 28px 30px;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, .04);
        position: relative;
    }

    /* Top Stats & Progress in Arena */
    .arena-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 22px;
        gap: 12px;
    }
    .arena-progress-wrap {
        flex: 1;
        max-width: 320px;
    }
    .arena-progress-text {
        display: flex;
        justify-content: space-between;
        font-size: 11.5px;
        font-weight: 800;
        color: var(--muted);
        margin-bottom: 6px;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
    .arena-progress-track {
        height: 7px;
        background: #f1eae6;
        border-radius: 999px;
        overflow: hidden;
    }
    .arena-progress-fill {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, #1098ad, #20c997);
        border-radius: 999px;
        transition: width .3s ease;
    }
    .arena-stats-badges {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .badge-stat {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
    }
    .badge-stat.score {
        background: #fff3bf;
        color: #d9480f;
        border: 1px solid #ffe066;
    }
    .badge-stat.combo {
        background: #fee7eb;
        color: var(--red);
        border: 1px solid #ffd4dc;
        transition: transform .2s ease;
    }
    .badge-stat.combo.bump {
        transform: scale(1.15);
    }

    /* Target Indonesian Sentence */
    .target-box {
        text-align: center;
        padding: 22px 24px;
        background: #faf7f5;
        border-radius: 16px;
        border: 1px solid var(--line);
        margin-bottom: 26px;
        position: relative;
    }
    .target-category-pill {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 999px;
        background: #fff;
        border: 1px solid var(--line);
        color: var(--red);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .06em;
        text-transform: uppercase;
        margin-bottom: 10px;
    }
    .target-sentence {
        font-size: 21px;
        font-weight: 800;
        color: var(--ink);
        line-height: 1.4;
        margin: 0;
    }
    .target-hint-text {
        margin-top: 8px;
        font-size: 12px;
        color: var(--muted);
        display: none;
    }

    /* Answer Construction Area (Slot Zona) */
    .dropzone-label {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--muted);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .answer-dropzone {
        min-height: 78px;
        border: 2px dashed #dcd3ce;
        border-radius: 18px;
        padding: 12px 14px;
        background: #fff;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        margin-bottom: 24px;
        transition: all .2s ease;
        position: relative;
    }
    .answer-dropzone.has-tiles {
        border-color: #adb5bd;
        background: #fdfdfd;
    }
    .answer-dropzone.correct {
        border-color: #20c997;
        background: #e6fcf5;
    }
    .answer-dropzone.incorrect {
        border-color: #fa5252;
        background: #fff5f5;
        animation: shakeError .3s cubic-bezier(.36, .07, .19, .97) both;
    }
    .dropzone-empty-tip {
        color: #adb5bd;
        font-size: 13px;
        font-style: italic;
        width: 100%;
        text-align: center;
        pointer-events: none;
        user-select: none;
    }

    @keyframes shakeError {
        10%, 90% { transform: translate3d(-2px, 0, 0); }
        20%, 80% { transform: translate3d(3px, 0, 0); }
        30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
        40%, 60% { transform: translate3d(4px, 0, 0); }
    }

    /* Word Tiles (Kepingan Kata) */
    .word-tile {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 9px 16px;
        border-radius: 13px;
        background: #fff;
        border: 1.5px solid #d4c8c2;
        box-shadow: 0 4px 10px rgba(0, 0, 0, .03), 0 2px 0 #d4c8c2;
        cursor: pointer;
        user-select: none;
        transition: all .16s ease;
        position: relative;
    }
    .word-tile:hover:not(.disabled) {
        transform: translateY(-2px);
        border-color: var(--red);
        box-shadow: 0 6px 14px rgba(196, 16, 46, .12), 0 2px 0 var(--red);
    }
    .word-tile:active:not(.disabled) {
        transform: translateY(1px);
        box-shadow: 0 1px 0 #d4c8c2;
    }
    .word-tile.in-slot {
        background: linear-gradient(135deg, #fff 0%, #faf8f7 100%);
        border-color: var(--red);
        border-width: 2px;
        box-shadow: 0 4px 12px rgba(196, 16, 46, .15);
    }
    .word-tile.disabled {
        opacity: .35;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
        background: #f1eae6;
        border-color: #e5deda;
    }
    .word-tile-romaji {
        font-size: 10px;
        color: var(--muted);
        margin-bottom: 2px;
        font-weight: 600;
        letter-spacing: .02em;
    }
    .word-tile-text {
        font-size: 18px;
        font-weight: 800;
        color: var(--ink);
        font-family: 'Noto Sans JP', sans-serif;
        line-height: 1.2;
    }

    /* Tile Pool Area */
    .tile-pool-box {
        margin-bottom: 28px;
    }
    .pool-label {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--muted);
        margin-bottom: 12px;
    }
    .tiles-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: center;
        padding: 18px 20px;
        background: #faf7f5;
        border-radius: 18px;
        border: 1px solid var(--line);
        min-height: 90px;
        align-items: center;
    }

    /* Action Buttons Row */
    .arena-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        padding-top: 14px;
        border-top: 1px solid var(--line);
    }
    .actions-left, .actions-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-arena {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: .18s ease;
        border: none;
    }
    .btn-arena.secondary {
        background: #f1eae6;
        color: var(--ink);
        border: 1px solid var(--line);
    }
    .btn-arena.secondary:hover {
        background: #e8e0dc;
    }
    .btn-arena.primary {
        background: linear-gradient(135deg, var(--red) 0%, var(--red-2, #e8234f) 100%);
        color: #ffffff !important;
        border: 1px solid var(--red);
        box-shadow: 0 4px 14px -2px rgba(196, 16, 46, .4);
    }
    .btn-arena.primary:hover:not(:disabled) {
        background: linear-gradient(135deg, var(--red-hover, #a00d25) 0%, var(--red) 100%);
        border-color: var(--red-hover, #a00d25);
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px -2px rgba(196, 16, 46, .5);
    }
    .btn-arena.primary:disabled {
        background: #f1eae6 !important;
        color: #a89a94 !important;
        border: 1px solid #e0d5cf !important;
        box-shadow: none !important;
        cursor: not-allowed;
        transform: none !important;
        opacity: 0.9 !important;
    }
    .btn-arena.primary svg {
        stroke: #ffffff;
    }
    .btn-arena.primary:disabled svg {
        stroke: #a89a94 !important;
    }

    /* Explanation & Feedback Card */
    .feedback-card {
        margin-top: 24px;
        padding: 20px 24px;
        border-radius: 16px;
        display: none;
        animation: fadeInSlide .25s ease-out;
    }
    @keyframes fadeInSlide {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .feedback-card.success {
        background: #e6fcf5;
        border: 1px solid #b2f2bb;
        color: #087f5b;
    }
    .feedback-card.error {
        background: #fff5f5;
        border: 1px solid #ffc9c9;
        color: #c92a2a;
    }
    .feedback-card.error .btn-audio-pronounce {
        border-color: #ffc9c9;
        color: #c92a2a;
    }
    .feedback-card.error .btn-audio-pronounce:hover {
        background: #ffe3e3;
    }
    .feedback-card.error .feedback-note {
        color: #5c2020;
    }
    .feedback-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }
    .feedback-title {
        font-size: 15px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-audio-pronounce {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 999px;
        background: #fff;
        border: 1px solid #b2f2bb;
        color: #087f5b;
        font-size: 11.5px;
        font-weight: 700;
        cursor: pointer;
        transition: .15s ease;
    }
    .btn-audio-pronounce:hover {
        background: #d3f9d8;
    }
    .feedback-full-sentence {
        font-size: 16px;
        font-weight: 800;
        color: #2b2b2b;
        margin: 4px 0 8px;
        font-family: 'Noto Sans JP', sans-serif;
    }
    .feedback-note {
        font-size: 13px;
        color: #495057;
        line-height: 1.55;
        margin: 0;
    }
    .feedback-actions {
        margin-top: 16px;
        display: flex;
        justify-content: flex-end;
    }

    /* Round Finished Screen */
    .round-finished-box {
        text-align: center;
        padding: 40px 20px;
        display: none;
    }
    .finished-trophy {
        width: 72px;
        height: 72px;
        margin: 0 auto 16px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ffd43b, #f59f00);
        display: grid;
        place-items: center;
        color: #fff;
        box-shadow: 0 10px 24px -6px rgba(245, 159, 0, .4);
    }
    .round-finished-box h3 {
        font-size: 24px;
        font-weight: 900;
        color: var(--ink);
        margin: 0 0 8px;
    }
    .round-finished-box p {
        font-size: 14px;
        color: var(--muted);
        margin: 0 0 24px;
    }
    .round-summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        max-width: 440px;
        margin: 0 auto 28px;
    }
    .summary-item {
        padding: 14px;
        border-radius: 14px;
        background: #faf7f5;
        border: 1px solid var(--line);
    }
    .summary-item strong {
        display: block;
        font-size: 20px;
        font-weight: 900;
        color: var(--ink);
        margin-bottom: 2px;
    }
    .summary-item span {
        font-size: 11px;
        color: var(--muted);
        font-weight: 700;
        text-transform: uppercase;
    }

    @media (max-width: 640px) {
        .builder-hero { padding: 22px 20px; }
        .builder-hero h2 { font-size: 20px; }
        .arena-card { padding: 20px 18px; }
        .target-sentence { font-size: 17px; }
        .word-tile { padding: 7px 12px; }
        .word-tile-text { font-size: 16px; }
        .round-summary-grid { grid-template-columns: 1fr; }
        .arena-actions { flex-direction: column; align-items: stretch; }
        .actions-left, .actions-right { width: 100%; justify-content: space-between; }
        .btn-arena { flex: 1; justify-content: center; }
    }
@endsection

@section('content')
<div class="content-body" style="padding-bottom: 30px;">
    <div class="builder-wrap">
        <!-- 1. Hero Banner -->
        <div class="builder-hero">
            <span class="builder-hero-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                    <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                    <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                </svg>
                Mini-Game Edukasi &bull; JLPT N5
            </span>
            <h2>Susun Kalimat (Sentence Builder)</h2>
            <p>
                Asah insting tata bahasa Jepangmu! Susun kepingan kata dan partikel yang diacak ke urutan yang benar sesuai struktur kalimat bahasa Indonesia yang diberikan.
            </p>
        </div>

        <!-- 2. Controls & Filter Bar -->
        <div class="builder-controls">
            <div class="level-tabs-row" role="tablist">
                <button type="button" class="level-tab-btn active" data-level="all">Semua Level</button>
                <button type="button" class="level-tab-btn" data-level="pemula">Level 1: Pemula</button>
                <button type="button" class="level-tab-btn" data-level="menengah">Level 2: Menengah</button>
                <button type="button" class="level-tab-btn" data-level="mahir">Level 3: Mahir</button>
            </div>

            <div class="toggles-group">
                <button type="button" class="btn-toggle-option active" id="btnToggleRomaji" title="Tampilkan cara baca romaji di atas kanji">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="2" y1="12" x2="22" y2="12"></line>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                    </svg>
                    <span>Romaji: ON</span>
                </button>
                <button type="button" class="btn-toggle-option active" id="btnToggleSound" title="Aktifkan efek suara dan audio pelafalan">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                        <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                    </svg>
                    <span>Audio: ON</span>
                </button>
            </div>
        </div>

        <!-- 3. Main Arena Card -->
        <div class="arena-card">
            <!-- Active Challenge Screen -->
            <div id="challengeScreen">
                <!-- Arena Top Stats -->
                <div class="arena-header">
                    <div class="arena-progress-wrap">
                        <div class="arena-progress-text">
                            <span id="progressStepText">Tantangan 1 / 10</span>
                            <span id="progressPercentText">10%</span>
                        </div>
                        <div class="arena-progress-track">
                            <div class="arena-progress-fill" id="progressFillBar" style="width: 10%;"></div>
                        </div>
                    </div>

                    <div class="arena-stats-badges">
                        <span class="badge-stat score" id="scoreBadge">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="7"></circle>
                                <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                            </svg>
                            <span id="scoreText">0 XP</span>
                        </span>
                        <span class="badge-stat combo" id="comboBadge" style="display:none;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                            </svg>
                            <span id="comboText">Combo x1</span>
                        </span>
                    </div>
                </div>

                <!-- Target Sentence Card -->
                <div class="target-box">
                    <span class="target-category-pill" id="targetCategory">Pola SOV & Partikel は</span>
                    <h3 class="target-sentence" id="targetIndonesian">Saya membaca buku di perpustakaan.</h3>
                    <div class="target-hint-text" id="targetHintText">
                        <strong>Petunjuk:</strong> <span id="hintDetail">Perhatikan partikel tempat aksi!</span>
                    </div>
                </div>

                <!-- Answer Slot Area -->
                <div class="dropzone-label">
                    <span>Zona Susunan Kalimat Anda</span>
                    <small style="color:var(--muted); font-weight:600;">(Ketuk kepingan untuk melepas kembali)</small>
                </div>
                <div class="answer-dropzone" id="answerDropzone">
                    <span class="dropzone-empty-tip" id="dropzoneTip">Ketuk potongan kata di bawah untuk menyusun kalimat di sini...</span>
                </div>

                <!-- Tile Pool Area -->
                <div class="tile-pool-box">
                    <div class="pool-label">Pilihan Potongan Kata (Ketuk untuk Memilih):</div>
                    <div class="tiles-grid" id="tilePool">
                        <!-- Tiles dynamically injected -->
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="arena-actions">
                    <div class="actions-left">
                        <button type="button" class="btn-arena secondary" id="btnResetSlot" title="Lepas semua kepingan kata">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2.5 2v6h6M21.5 22v-6h-6"></path>
                                <path d="M22 11.5A10 10 0 0 0 3.2 7.2M2 12.5a10 10 0 0 0 18.8 4.2"></path>
                            </svg>
                            <span>Reset Slot</span>
                        </button>
                        <button type="button" class="btn-arena secondary" id="btnShowHint" title="Buka petunjuk kalimat">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                            <span>Bantuan</span>
                        </button>
                    </div>

                    <div class="actions-right">
                        <button type="button" class="btn-arena primary" id="btnCheckAnswer" disabled>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span>Periksa Jawaban</span>
                        </button>
                    </div>
                </div>

                <!-- Explanation & Feedback Card -->
                <div class="feedback-card" id="feedbackCard">
                    <div class="feedback-head">
                        <div class="feedback-title" id="feedbackTitle">
                            <!-- Icon and Status injected -->
                        </div>
                        <button type="button" class="btn-audio-pronounce" id="btnAudioPronounce" title="Dengarkan pelafalan kalimat">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                                <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                            </svg>
                            <span>Dengarkan</span>
                        </button>
                    </div>
                    <div class="feedback-full-sentence" id="feedbackSentence">私は図書館で本を読みます。</div>
                    <p class="feedback-note" id="feedbackNote">Penjelasan tata bahasa.</p>
                    <div class="feedback-actions" style="display:flex; gap:10px; justify-content:flex-end; flex-wrap:wrap;">
                        <button type="button" class="btn-arena secondary" id="btnRetryChallenge" style="display:none;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2.5 2v6h6M21.5 22v-6h-6"></path>
                                <path d="M22 11.5A10 10 0 0 0 3.2 7.2M2 12.5a10 10 0 0 0 18.8 4.2"></path>
                            </svg>
                            <span>Coba Lagi</span>
                        </button>
                        <button type="button" class="btn-arena primary" id="btnNextChallenge">
                            <span id="btnNextText">Lanjut ke Soal Berikutnya</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Round Finished Screen -->
            <div class="round-finished-box" id="roundFinishedScreen">
                <div class="finished-trophy">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                        <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                        <path d="M4 22h16"></path>
                        <path d="M10 14.66V17c0 .55-.45 1-1 1H7"></path>
                        <path d="M14 14.66V17c0 .55.45 1 1 1h2"></path>
                        <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
                    </svg>
                </div>
                <h3>Omedetou Gozaimasu! (おめでとうございます！)</h3>
                <p>Kamu telah menyelesaikan seluruh rangkaian tantangan susun kalimat di babak ini!</p>

                <div class="round-summary-grid">
                    <div class="summary-item">
                        <strong id="finalScoreText">120 XP</strong>
                        <span>Total Poin XP</span>
                    </div>
                    <div class="summary-item">
                        <strong id="finalAccuracyText">100%</strong>
                        <span>Akurasi Jawaban</span>
                    </div>
                    <div class="summary-item">
                        <strong id="finalMaxComboText">x5</strong>
                        <span>Kombo Maksimal</span>
                    </div>
                </div>

                <div style="display:flex; justify-content:center; gap:12px; flex-wrap:wrap;">
                    <button type="button" class="btn-arena secondary" id="btnReplayRound">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2.5 2v6h6M21.5 22v-6h-6"></path>
                            <path d="M22 11.5A10 10 0 0 0 3.2 7.2M2 12.5a10 10 0 0 0 18.8 4.2"></path>
                        </svg>
                        <span>Mainkan Lagi</span>
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn-arena primary" style="text-decoration:none;">
                        <span>Kembali ke Dashboard</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function() {
    'use strict';

    // ── Raw Challenges Dataset from Controller ──
    const RAW_CHALLENGES = @json($challenges);

    // ── Game State ──
    let selectedLevel = 'all';
    let isRomajiActive = true;
    let isAudioActive = true;

    let activeChallenges = [];
    let currentIndex = 0;
    let currentChallenge = null;

    let placedTiles = []; // array of tile objects: { id, text, romaji, originalIndex }
    let availableTiles = []; // pool tiles

    let earnedXp = 0;
    let currentCombo = 0;
    let maxCombo = 0;
    let correctAnswersCount = 0;
    let totalAttempts = 0;

    // ── DOM References ──
    const challengeScreen = document.getElementById('challengeScreen');
    const roundFinishedScreen = document.getElementById('roundFinishedScreen');

    const progressStepText = document.getElementById('progressStepText');
    const progressPercentText = document.getElementById('progressPercentText');
    const progressFillBar = document.getElementById('progressFillBar');

    const scoreText = document.getElementById('scoreText');
    const comboBadge = document.getElementById('comboBadge');
    const comboText = document.getElementById('comboText');

    const targetCategory = document.getElementById('targetCategory');
    const targetIndonesian = document.getElementById('targetIndonesian');
    const targetHintText = document.getElementById('targetHintText');
    const hintDetail = document.getElementById('hintDetail');

    const answerDropzone = document.getElementById('answerDropzone');
    const dropzoneTip = document.getElementById('dropzoneTip');
    const tilePool = document.getElementById('tilePool');

    const btnResetSlot = document.getElementById('btnResetSlot');
    const btnShowHint = document.getElementById('btnShowHint');
    const btnCheckAnswer = document.getElementById('btnCheckAnswer');

    const feedbackCard = document.getElementById('feedbackCard');
    const feedbackTitle = document.getElementById('feedbackTitle');
    const feedbackSentence = document.getElementById('feedbackSentence');
    const feedbackNote = document.getElementById('feedbackNote');
    const btnAudioPronounce = document.getElementById('btnAudioPronounce');
    const btnNextChallenge = document.getElementById('btnNextChallenge');

    const btnToggleRomaji = document.getElementById('btnToggleRomaji');
    const btnToggleSound = document.getElementById('btnToggleSound');
    const levelTabs = document.querySelectorAll('.level-tab-btn');

    const finalScoreText = document.getElementById('finalScoreText');
    const finalAccuracyText = document.getElementById('finalAccuracyText');
    const finalMaxComboText = document.getElementById('finalMaxComboText');
    const btnReplayRound = document.getElementById('btnReplayRound');

    // ── Audio Pronunciation (Web Speech Synthesis) ──
    function speakJapanese(text) {
        if (!isAudioActive || !('speechSynthesis' in window)) return;
        try {
            window.speechSynthesis.cancel();
            const utter = new SpeechSynthesisUtterance(text);
            utter.lang = 'ja-JP';
            utter.rate = 0.88;
            window.speechSynthesis.speak(utter);
        } catch (e) {
            // Ignore if speech synthesis not permitted
        }
    }

    // ── Shuffle Array Utility ──
    function shuffleArray(arr) {
        const copy = [...arr];
        for (let i = copy.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [copy[i], copy[j]] = [copy[j], copy[i]];
        }
        return copy;
    }

    // ── Initialize Game Round ──
    function startRound() {
        // Filter challenges by level
        if (selectedLevel === 'all') {
            activeChallenges = shuffleArray(RAW_CHALLENGES);
        } else {
            activeChallenges = shuffleArray(RAW_CHALLENGES.filter(c => c.level === selectedLevel));
        }

        // Limit to 10 questions per session for good bite-sized learning
        activeChallenges = activeChallenges.slice(0, 10);
        if (activeChallenges.length === 0) {
            activeChallenges = RAW_CHALLENGES.slice(0, 5);
        }

        currentIndex = 0;
        earnedXp = 0;
        currentCombo = 0;
        maxCombo = 0;
        correctAnswersCount = 0;
        totalAttempts = 0;

        updateStatsUI();
        challengeScreen.style.display = 'block';
        roundFinishedScreen.style.display = 'none';

        loadChallenge(currentIndex);
    }

    // ── Load Single Challenge ──
    function loadChallenge(index) {
        if (index >= activeChallenges.length) {
            finishRound();
            return;
        }

        currentChallenge = activeChallenges[index];
        placedTiles = [];
        totalAttempts++;

        // Reset UI Elements
        feedbackCard.style.display = 'none';
        feedbackCard.className = 'feedback-card';
        answerDropzone.className = 'answer-dropzone';
        btnCheckAnswer.disabled = true;
        btnResetSlot.disabled = false;
        targetHintText.style.display = 'none';
        const btnRetry = document.getElementById('btnRetryChallenge');
        if (btnRetry) btnRetry.style.display = 'none';
        const btnNextText = document.getElementById('btnNextText');
        if (btnNextText) btnNextText.textContent = 'Lanjut ke Soal Berikutnya';

        // Update progress
        const currentNum = index + 1;
        const totalNum = activeChallenges.length;
        const percent = Math.round((currentNum / totalNum) * 100);

        progressStepText.textContent = `Tantangan ${currentNum} / ${totalNum}`;
        progressPercentText.textContent = `${percent}%`;
        progressFillBar.style.width = `${percent}%`;

        // Update target text
        targetCategory.textContent = currentChallenge.category || currentChallenge.level_label;
        targetIndonesian.textContent = currentChallenge.indonesian;
        hintDetail.textContent = currentChallenge.note || 'Perhatikan urutan partikel dan predikat di akhir kalimat.';

        // Prepare pool tiles (correct tiles + distractors shuffled)
        const combined = [
            ...currentChallenge.correct_tiles.map(t => ({ text: t, isCorrect: true })),
            ...(currentChallenge.distractor_tiles || []).map(t => ({ text: t, isCorrect: false }))
        ];

        const shuffled = shuffleArray(combined);
        availableTiles = shuffled.map((item, idx) => ({
            id: 'tile_' + idx,
            text: item.text,
            isUsed: false
        }));

        renderTiles();
    }

    // ── Render Dropzone and Pool Tiles ──
    function renderTiles() {
        // 1. Render Dropzone
        answerDropzone.innerHTML = '';
        if (placedTiles.length === 0) {
            dropzoneTip.style.display = 'block';
            answerDropzone.appendChild(dropzoneTip);
            answerDropzone.classList.remove('has-tiles');
            btnCheckAnswer.disabled = true;
        } else {
            dropzoneTip.style.display = 'none';
            answerDropzone.classList.add('has-tiles');
            btnCheckAnswer.disabled = false;

            placedTiles.forEach((tile, slotIdx) => {
                const btn = createTileButton(tile, true, slotIdx);
                answerDropzone.appendChild(btn);
            });
        }

        // 2. Render Pool
        tilePool.innerHTML = '';
        availableTiles.forEach((tile) => {
            const btn = createTileButton(tile, false);
            if (tile.isUsed) {
                btn.classList.add('disabled');
            }
            tilePool.appendChild(btn);
        });
    }

    // ── Helper Create Tile DOM Button ──
    function createTileButton(tile, inSlot = false, slotIdx = 0) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = `word-tile ${inSlot ? 'in-slot' : ''}`;

        btn.innerHTML = `
            <span class="word-tile-text">${tile.text}</span>
        `;

        if (inSlot) {
            btn.title = 'Ketuk untuk melepas dari slot';
            btn.addEventListener('click', () => removeTileFromSlot(slotIdx));
        } else {
            btn.title = 'Ketuk untuk memasukkan ke slot';
            btn.addEventListener('click', () => {
                if (!tile.isUsed) {
                    addTileToSlot(tile);
                }
            });
        }

        return btn;
    }

    // ── Tile Interactions ──
    function addTileToSlot(tile) {
        tile.isUsed = true;
        placedTiles.push(tile);
        renderTiles();
    }

    function removeTileFromSlot(slotIdx) {
        const [removed] = placedTiles.splice(slotIdx, 1);
        if (removed) {
            const poolItem = availableTiles.find(t => t.id === removed.id);
            if (poolItem) poolItem.isUsed = false;
        }
        renderTiles();
    }

    function resetSlot() {
        placedTiles = [];
        availableTiles.forEach(t => t.isUsed = false);
        renderTiles();
    }

    // ── Check Answer ──
    function checkAnswer() {
        if (!currentChallenge || placedTiles.length === 0) return;

        const assembledAnswer = placedTiles.map(t => t.text).join('');
        const expectedAnswer = currentChallenge.correct_tiles.join('');

        if (assembledAnswer === expectedAnswer) {
            // JAWABAN BENAR
            correctAnswersCount++;
            currentCombo++;
            if (currentCombo > maxCombo) maxCombo = currentCombo;

            // XP calculation with combo multiplier
            const basePoints = 10;
            const comboBonus = Math.min(currentCombo * 2, 10);
            const totalEarned = basePoints + comboBonus;
            earnedXp += totalEarned;

            updateStatsUI(true);

            answerDropzone.className = 'answer-dropzone correct';
            feedbackCard.className = 'feedback-card success';
            feedbackCard.style.display = 'block';

            feedbackTitle.innerHTML = `
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span>Tepat Sekali! (+${totalEarned} XP)</span>
            `;

            feedbackSentence.textContent = currentChallenge.japanese_full;
            feedbackNote.innerHTML = `
                <strong>Arti:</strong> ${currentChallenge.indonesian}<br>
                <strong>Romaji:</strong> <em>${currentChallenge.romaji}</em><br>
                ${currentChallenge.note ? '<div style="margin-top:6px; padding-top:6px; border-top:1px dashed #b2f2bb;">' + currentChallenge.note + '</div>' : ''}
            `;

            speakJapanese(currentChallenge.japanese_full);

            // Button states for correct answer
            const btnRetry = document.getElementById('btnRetryChallenge');
            if (btnRetry) btnRetry.style.display = 'none';
            const btnNextText = document.getElementById('btnNextText');
            if (btnNextText) btnNextText.textContent = 'Lanjut ke Soal Berikutnya';

            // Disable tiles interaction after correct answer
            btnCheckAnswer.disabled = true;
            btnResetSlot.disabled = true;

        } else {
            // JAWABAN SALAH
            currentCombo = 0;
            updateStatsUI();

            answerDropzone.className = 'answer-dropzone incorrect';
            feedbackCard.className = 'feedback-card error';
            feedbackCard.style.display = 'block';

            feedbackTitle.innerHTML = `
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                <span>Susunan Kalimat Belum Tepat</span>
            `;

            feedbackSentence.textContent = 'Ayo coba periksa kembali urutan partikel dan kata kerjanya!';
            feedbackNote.textContent = 'Dalam bahasa Jepang, kata kerja selalu berada di paling akhir kalimat. Ketuk kepingan di slot untuk mengubah posisinya.';

            // Option to retry or skip
            const btnRetry = document.getElementById('btnRetryChallenge');
            if (btnRetry) btnRetry.style.display = 'inline-flex';
            const btnNextText = document.getElementById('btnNextText');
            if (btnNextText) btnNextText.textContent = 'Lewati Soal Ini';

            setTimeout(() => {
                answerDropzone.classList.remove('incorrect');
            }, 600);
        }
    }

    // ── Update Stats UI ──
    function updateStatsUI(comboBump = false) {
        scoreText.textContent = `${earnedXp} XP`;

        if (currentCombo >= 2) {
            comboBadge.style.display = 'inline-flex';
            comboText.textContent = `Combo x${currentCombo}!`;
            if (comboBump) {
                comboBadge.classList.add('bump');
                setTimeout(() => comboBadge.classList.remove('bump'), 220);
            }
        } else {
            comboBadge.style.display = 'none';
        }
    }

    // ── Finish Round & Report Gamification ──
    function finishRound() {
        challengeScreen.style.display = 'none';
        roundFinishedScreen.style.display = 'block';

        const totalQ = activeChallenges.length;
        const accuracy = totalQ > 0 ? Math.round((correctAnswersCount / totalQ) * 100) : 0;

        finalScoreText.textContent = `${earnedXp} XP`;
        finalAccuracyText.textContent = `${accuracy}% (${correctAnswersCount}/${totalQ})`;
        finalMaxComboText.textContent = `x${maxCombo}`;

        // Lapor aktivitas ke backend gamifikasi jika berhasil
        if (typeof window.reportActivity === 'function' && correctAnswersCount > 0) {
            window.reportActivity('sentence_builder_complete');
        }
    }

    // ── Event Listeners ──
    btnResetSlot.addEventListener('click', resetSlot);

    const btnRetry = document.getElementById('btnRetryChallenge');
    if (btnRetry) {
        btnRetry.addEventListener('click', () => {
            feedbackCard.style.display = 'none';
            btnCheckAnswer.disabled = (placedTiles.length === 0);
        });
    }

    btnShowHint.addEventListener('click', () => {
        targetHintText.style.display = targetHintText.style.display === 'none' ? 'block' : 'none';
    });

    btnCheckAnswer.addEventListener('click', checkAnswer);

    btnNextChallenge.addEventListener('click', () => {
        btnResetSlot.disabled = false;
        currentIndex++;
        loadChallenge(currentIndex);
    });

    btnAudioPronounce.addEventListener('click', () => {
        if (currentChallenge) {
            speakJapanese(currentChallenge.japanese_full);
        }
    });

    btnReplayRound.addEventListener('click', startRound);

    // Level Filter Tabs
    levelTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            levelTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            selectedLevel = tab.dataset.level;
            startRound();
        });
    });

    // Toggle Sound
    btnToggleSound.addEventListener('click', () => {
        isAudioActive = !isAudioActive;
        btnToggleSound.classList.toggle('active', isAudioActive);
        btnToggleSound.querySelector('span').textContent = isAudioActive ? 'Audio: ON' : 'Audio: OFF';
    });

    // Toggle Romaji
    btnToggleRomaji.addEventListener('click', () => {
        isRomajiActive = !isRomajiActive;
        btnToggleRomaji.classList.toggle('active', isRomajiActive);
        btnToggleRomaji.querySelector('span').textContent = isRomajiActive ? 'Romaji: ON' : 'Romaji: OFF';
        document.querySelectorAll('.word-tile-romaji').forEach(el => {
            el.style.display = isRomajiActive ? 'block' : 'none';
        });
    });

    // ── Start First Round on Page Load ──
    startRound();
})();
</script>
@endsection

