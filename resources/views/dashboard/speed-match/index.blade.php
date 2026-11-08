@extends('dashboard.layout')

@php
    $pageTitle = 'Kana Speed Match';
    $pageSubtitle = 'Tebak cepat huruf dan kosakata Jepang dengan durasi waktu kustom.';
@endphp

@section('styles')
    /* ── Main Speed Match Page Styles ── */
    .speed-page {
        max-width: 900px;
        margin: 0 auto;
    }

    /* ── Hero Banner ── */
    .speed-hero {
        background: linear-gradient(135deg, #181335 0%, #2f1737 100%);
        border-radius: 22px;
        padding: 32px 32px 28px;
        color: #fff;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 45px -14px rgba(24, 19, 53, .45);
    }
    .speed-hero::after {
        content: '速い';
        position: absolute;
        right: 20px;
        bottom: -25px;
        font-family: 'Noto Sans JP', sans-serif;
        font-size: 130px;
        font-weight: 900;
        color: rgba(255, 255, 255, .03);
        pointer-events: none;
        line-height: 1;
        user-select: none;
    }
    .speed-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .15);
        color: #ffd8a8;
        font-size: 11.5px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 12px;
        backdrop-filter: blur(8px);
    }
    .speed-hero-title {
        font-size: 26px;
        font-weight: 900;
        margin-bottom: 8px;
        letter-spacing: -.02em;
    }
    .speed-hero-desc {
        color: rgba(255, 255, 255, .8);
        font-size: 14px;
        max-width: 620px;
        line-height: 1.55;
    }

    /* ── Setup Section Card ── */
    .setup-card {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 22px;
        padding: 28px 28px 32px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
    }
    .setup-section-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--ink);
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Category Cards Grid */
    .mode-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 28px;
    }
    .mode-card {
        border: 2px solid var(--line);
        border-radius: 16px;
        padding: 16px 14px;
        cursor: pointer;
        text-align: center;
        transition: all .2s ease;
        background: #fff;
        user-select: none;
    }
    .mode-card:hover {
        border-color: #f7a3b3;
        transform: translateY(-2px);
    }
    .mode-card.active {
        border-color: var(--red);
        background: #fff5f7;
        box-shadow: 0 6px 18px -4px rgba(196, 16, 46, .25);
    }
    .mode-icon {
        font-family: 'Noto Sans JP', sans-serif;
        font-size: 32px;
        font-weight: 900;
        color: var(--ink);
        line-height: 1;
        margin-bottom: 8px;
    }
    .mode-card.active .mode-icon {
        color: var(--red);
    }
    .mode-title {
        font-size: 13.5px;
        font-weight: 800;
        color: var(--ink);
        margin-bottom: 2px;
    }
    .mode-count {
        font-size: 11px;
        color: var(--muted);
        font-weight: 600;
    }

    /* Time Customizer Box */
    .time-customizer-box {
        background: #fff8f6;
        border: 1.5px solid #f9d8d3;
        border-radius: 18px;
        padding: 22px;
        margin-bottom: 28px;
    }
    .preset-pills {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }
    .btn-preset {
        padding: 7px 16px;
        border-radius: 999px;
        border: 1px solid var(--line);
        background: #fff;
        color: var(--ink);
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        transition: .2s;
    }
    .btn-preset:hover {
        border-color: var(--red);
        color: var(--red);
    }
    .btn-preset.active {
        background: var(--red);
        color: #fff;
        border-color: var(--red);
        box-shadow: 0 4px 12px -2px rgba(196, 16, 46, .45);
    }

    .time-slider-wrap {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .time-stepper-btn {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        border: 1.5px solid var(--line);
        background: #fff;
        color: var(--ink);
        font-size: 18px;
        font-weight: 800;
        display: grid;
        place-items: center;
        cursor: pointer;
        transition: .2s;
        flex-shrink: 0;
    }
    .time-stepper-btn:hover {
        background: var(--red-soft);
        color: var(--red);
        border-color: var(--red);
    }
    .time-slider-range {
        flex: 1;
        -webkit-appearance: none;
        appearance: none;
        height: 8px;
        border-radius: 999px;
        background: #ffd0d8;
        outline: none;
    }
    .time-slider-range::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: var(--red);
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(196, 16, 46, .5);
        transition: transform .15s;
    }
    .time-slider-range::-webkit-slider-thumb:hover {
        transform: scale(1.15);
    }
    .time-display-badge {
        min-width: 110px;
        padding: 8px 14px;
        border-radius: 12px;
        background: var(--navy);
        color: #fff;
        text-align: center;
        flex-shrink: 0;
    }
    .time-display-num {
        font-size: 22px;
        font-weight: 900;
        line-height: 1;
        display: block;
        font-variant-numeric: tabular-nums;
    }
    .time-display-unit {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        color: #ffd8a8;
        letter-spacing: .06em;
    }
    .time-hint-tag {
        margin-top: 10px;
        font-size: 12px;
        color: var(--muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .time-hint-tag strong {
        color: var(--red);
    }

    /* Start Button */
    .btn-start-game {
        width: 100%;
        height: 54px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--red-2), var(--red));
        color: #fff;
        border: none;
        font-size: 16px;
        font-weight: 800;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 8px 24px -4px rgba(196, 16, 46, .55);
        transition: all .2s cubic-bezier(.4, 0, .2, 1);
    }
    .btn-start-game:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px -4px rgba(196, 16, 46, .75);
    }

    /* ── High Score Bar ── */
    .record-banner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 16px;
        padding: 14px 20px;
        font-size: 13px;
        color: var(--muted);
        flex-wrap: wrap;
        gap: 10px;
    }
    .record-banner strong {
        color: var(--ink);
    }

    /* ══════════════════════════════════════════════
       ARENA PERMAINAN (GAMEPLAY)
    ══════════════════════════════════════════════ */
    .game-arena-wrap {
        display: none;
        animation: fadeIn .3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(.98); }
        to { opacity: 1; transform: scale(1); }
    }

    /* Game HUD Bar */
    .game-hud {
        background: var(--navy);
        border-radius: 20px;
        padding: 18px 24px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
        box-shadow: var(--shadow);
        flex-wrap: wrap;
    }
    .hud-timer-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .hud-timer-badge {
        font-size: 26px;
        font-weight: 900;
        font-variant-numeric: tabular-nums;
        color: #ffcad4;
        min-width: 70px;
    }
    .hud-timer-badge.urgent {
        color: #ff3b5c;
        animation: pulseHeart 0.6s infinite alternate;
    }
    @keyframes pulseHeart {
        from { transform: scale(1); }
        to { transform: scale(1.15); }
    }
    .hud-progress-bar-bg {
        width: 140px;
        height: 8px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .15);
        overflow: hidden;
    }
    .hud-progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #10b981, #f59e0b, #ef4444);
        width: 100%;
        transition: width .1s linear;
    }

    .hud-score-wrap {
        text-align: center;
    }
    .hud-score-val {
        font-size: 28px;
        font-weight: 900;
        line-height: 1;
        color: #fff;
    }
    .hud-score-label {
        font-size: 11px;
        color: rgba(255, 255, 255, .6);
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: .05em;
    }

    .hud-combo-badge {
        padding: 6px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .1);
        border: 1px solid rgba(255, 255, 255, .15);
        font-size: 13px;
        font-weight: 800;
        color: #ffd8a8;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: .2s;
    }
    .hud-combo-badge.fire {
        background: linear-gradient(135deg, #f59e0b, #ef4444);
        color: #fff;
        box-shadow: 0 4px 15px rgba(245, 158, 11, .5);
        transform: scale(1.05);
    }

    .btn-quit-game {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, .2);
        color: rgba(255, 255, 255, .7);
        border-radius: 10px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s;
    }
    .btn-quit-game:hover {
        background: rgba(255, 255, 255, .15);
        color: #fff;
    }

    /* Target Question Card */
    .target-card {
        background: #fff;
        border: 2px solid var(--line);
        border-radius: 24px;
        padding: 36px 20px;
        text-align: center;
        box-shadow: var(--shadow);
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }
    .target-category-pill {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 999px;
        background: var(--red-soft);
        color: var(--red);
        font-size: 11.5px;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 12px;
    }
    .target-glyph {
        font-family: 'Noto Sans JP', sans-serif;
        font-size: 96px;
        font-weight: 900;
        color: var(--ink);
        line-height: 1.1;
        margin-bottom: 8px;
        user-select: none;
        transition: transform .15s ease;
    }
    .target-card.shake {
        animation: cardShake .35s cubic-bezier(.36, .07, .19, .97) both;
        border-color: #ef4444;
    }
    @keyframes cardShake {
        10%, 90% { transform: translate3d(-2px, 0, 0); }
        20%, 80% { transform: translate3d(3px, 0, 0); }
        30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
        40%, 60% { transform: translate3d(4px, 0, 0); }
    }
    .target-hint {
        font-size: 13px;
        color: var(--muted);
        font-weight: 600;
    }

    /* Answer Choices Grid */
    .answers-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 16px;
    }
    .btn-answer {
        background: #fff;
        border: 2px solid var(--line);
        border-radius: 16px;
        padding: 18px 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 18px;
        font-weight: 800;
        color: var(--ink);
        transition: all .15s ease;
        position: relative;
        user-select: none;
    }
    .btn-answer:hover {
        border-color: #ff9aad;
        background: #fffafa;
        transform: translateY(-2px);
    }
    .btn-answer:active {
        transform: translateY(0);
    }
    .btn-answer .hotkey {
        font-size: 11px;
        font-weight: 800;
        color: var(--muted);
        background: #f1eae6;
        padding: 3px 8px;
        border-radius: 6px;
    }
    .btn-answer.correct {
        background: #10b981 !important;
        border-color: #10b981 !important;
        color: #fff !important;
        transform: scale(1.02);
    }
    .btn-answer.correct .hotkey {
        background: rgba(255, 255, 255, .3);
        color: #fff;
    }
    .btn-answer.wrong {
        background: #ef4444 !important;
        border-color: #ef4444 !important;
        color: #fff !important;
    }
    .btn-answer.wrong .hotkey {
        background: rgba(255, 255, 255, .3);
        color: #fff;
    }

    /* Keyboard Tips */
    .keyboard-hint {
        text-align: center;
        font-size: 12px;
        color: var(--muted);
        margin-top: 10px;
    }
    .keyboard-hint kbd {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 4px;
        padding: 2px 6px;
        font-size: 11px;
        font-family: inherit;
        font-weight: 700;
        color: var(--ink);
    }

    /* ══════════════════════════════════════════════
       GAME OVER MODAL
    ══════════════════════════════════════════════ */
    .gameover-overlay {
        position: fixed;
        inset: 0;
        background: rgba(14, 10, 26, 0.78);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity .25s ease, visibility .25s ease;
    }
    .gameover-overlay.show {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }
    .gameover-card {
        background: linear-gradient(155deg, #1a1438 0%, #281938 100%);
        border-radius: 24px;
        padding: 34px 28px 28px;
        max-width: 480px;
        width: 100%;
        color: #fff;
        text-align: center;
        box-shadow: 0 28px 70px -15px rgba(0, 0, 0, .65), 0 0 0 1px rgba(255, 255, 255, .1);
        transform: scale(0.92) translateY(14px);
        transition: transform .28s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
    }
    .gameover-overlay.show .gameover-card {
        transform: scale(1) translateY(0);
    }
    .gameover-trophy {
        width: 68px;
        height: 68px;
        border-radius: 22px;
        background: linear-gradient(135deg, rgba(232, 35, 79, .3), rgba(245, 158, 11, .3));
        border: 1.5px solid rgba(255, 216, 168, .4);
        color: #ffd8a8;
        display: grid;
        place-items: center;
        margin: 0 auto 16px;
        box-shadow: 0 10px 24px -6px rgba(232, 35, 79, .5);
    }
    .gameover-title {
        font-size: 24px;
        font-weight: 900;
        color: #fff;
        margin-bottom: 4px;
    }
    .gameover-rank {
        display: inline-block;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #ffd8a8;
        background: rgba(255, 255, 255, .1);
        padding: 4px 14px;
        border-radius: 999px;
        margin-bottom: 20px;
    }
    .gameover-score-box {
        background: rgba(255, 255, 255, .06);
        border: 1px solid rgba(255, 255, 255, .1);
        border-radius: 18px;
        padding: 16px 20px;
        margin-bottom: 18px;
    }
    .go-score-val {
        font-size: 42px;
        font-weight: 900;
        line-height: 1;
        color: #fff;
        display: block;
        margin-bottom: 4px;
    }
    .go-score-lbl {
        font-size: 11.5px;
        color: rgba(255, 255, 255, .6);
        font-weight: 700;
        text-transform: uppercase;
    }

    .gameover-stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 22px;
    }
    .go-stat-card {
        background: rgba(255, 255, 255, .04);
        border: 1px solid rgba(255, 255, 255, .08);
        border-radius: 12px;
        padding: 10px 8px;
    }
    .go-stat-card strong {
        display: block;
        font-size: 16px;
        font-weight: 800;
        color: #fff;
    }
    .go-stat-card span {
        font-size: 10.5px;
        color: rgba(255, 255, 255, .6);
        font-weight: 600;
    }

    .gameover-actions {
        display: flex;
        gap: 10px;
    }
    .btn-go-retry {
        flex: 1.4;
        height: 46px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--red-2), var(--red));
        color: #fff;
        border: none;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 6px 20px -4px rgba(196, 16, 46, .55);
        transition: .2s;
    }
    .btn-go-retry:hover {
        transform: translateY(-2px);
    }
    .btn-go-setup {
        flex: 1;
        height: 46px;
        border-radius: 12px;
        background: rgba(255, 255, 255, .1);
        border: 1px solid rgba(255, 255, 255, .18);
        color: #fff;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        transition: .2s;
    }
    .btn-go-setup:hover {
        background: rgba(255, 255, 255, .18);
    }

    /* Responsive */
    @media (max-width: 680px) {
        .mode-grid { grid-template-columns: repeat(2, 1fr); }
        .answers-grid { grid-template-columns: 1fr; }
        .speed-hero { padding: 22px 20px; }
        .speed-hero-title { font-size: 21px; }
        .target-glyph { font-size: 76px; }
        .game-hud { padding: 14px 16px; }
        .time-slider-wrap { flex-wrap: wrap; }
    }
@endsection

@section('content')
<div class="speed-page">

    <!-- Hero Header -->
    <div class="speed-hero">
        <div class="speed-hero-badge">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
            </svg>
            Mini Game Edukasi Refleks
        </div>
        <h1 class="speed-hero-title">Kana Speed Match</h1>
        <p class="speed-hero-desc">
            Uji kecepatan refleks dan pengenalan karakter Jepangmu. Pilih mode latihan, atur durasi waktu sesuai keinginan (3 s.d. 60 detik), dan kumpulkan poin kombo tertinggi!
        </p>
    </div>

    <!-- ══════════════════════════════════════════════
         1. SETUP SECTION (SEBELUM MULAI)
    ══════════════════════════════════════════════ -->
    <div id="gameSetupSection">
        <div class="setup-card">
            <!-- 1. Pilihan Kategori / Mode -->
            <div class="setup-section-title">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--red);">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                Langkah 1: Pilih Mode Karakter
            </div>

            <div class="mode-grid">
                <div class="mode-card active" data-mode="hiragana">
                    <div class="mode-icon">あ</div>
                    <div class="mode-title">Hiragana</div>
                    <div class="mode-count">{{ count($hiraganaList) }} Karakter</div>
                </div>
                <div class="mode-card" data-mode="katakana">
                    <div class="mode-icon">ア</div>
                    <div class="mode-title">Katakana</div>
                    <div class="mode-count">{{ count($katakanaList) }} Karakter</div>
                </div>
                <div class="mode-card" data-mode="mixed">
                    <div class="mode-icon">あ/ア</div>
                    <div class="mode-title">Campuran Kana</div>
                    <div class="mode-count">{{ count($mixedKanaList) }} Karakter</div>
                </div>
                <div class="mode-card" data-mode="kanji">
                    <div class="mode-icon">日</div>
                    <div class="mode-title">Kanji N5</div>
                    <div class="mode-count">{{ count($kanjiList) }} Karakter</div>
                </div>
            </div>

            <!-- 2. Kustomisasi Waktu Per Soal (3 s/d 60 detik) -->
            <div class="setup-section-title">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--red);">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                Langkah 2: Atur Durasi Waktu Per Soal (Minimal 3s — Maksimal 60s)
            </div>

            <div class="time-customizer-box">
                <p style="font-size:12.5px; color:var(--muted); margin:0 0 14px 0; line-height:1.5;">
                    Tentukan batas waktu berpikir untuk setiap soal. Setiap kali kamu menjawab, waktu akan otomatis <strong>direset kembali penuh</strong> untuk soal berikutnya!
                </p>

                <!-- Preset Pills -->
                <div class="preset-pills">
                    <button type="button" class="btn-preset active" data-time="5">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block; vertical-align:middle; margin-right:4px;">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                        </svg>
                        5 Detik (Refleks Kilat)
                    </button>
                    <button type="button" class="btn-preset" data-time="10">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block; vertical-align:middle; margin-right:4px;">
                            <circle cx="12" cy="12" r="10"></circle>
                            <circle cx="12" cy="12" r="6"></circle>
                            <circle cx="12" cy="12" r="2"></circle>
                        </svg>
                        10 Detik (Tantangan Cepat)
                    </button>
                    <button type="button" class="btn-preset" data-time="20">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block; vertical-align:middle; margin-right:4px;">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        20 Detik (Standar)
                    </button>
                    <button type="button" class="btn-preset" data-time="30">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block; vertical-align:middle; margin-right:4px;">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        30 Detik (Santai / Latihan)
                    </button>
                </div>

                <!-- Slider & Stepper Controls -->
                <div class="time-slider-wrap">
                    <button type="button" class="time-stepper-btn" id="btnTimeMinus" title="Kurangi 1 detik">&minus;</button>
                    <input type="range" class="time-slider-range" id="timeSlider" min="3" max="60" value="5">
                    <button type="button" class="time-stepper-btn" id="btnTimePlus" title="Tambah 1 detik">&plus;</button>

                    <div class="time-display-badge">
                        <span class="time-display-num" id="timeDisplayNum">5</span>
                        <span class="time-display-unit">Detik / Soal</span>
                    </div>
                </div>

                <div class="time-hint-tag" id="timeHintTag">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    <span>Tingkat Tantangan: <strong id="difficultyText">Super Kilat (Refleks Dewa)</strong></span>
                </div>
            </div>

            <!-- Tombol Mulai -->
            <button type="button" class="btn-start-game" id="btnStartGame">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                </svg>
                Mulai Speed Match Sekarang
            </button>
        </div>

        <!-- Rekor Terbaik Bar -->
        <div class="record-banner">
            <div style="display:inline-flex; align-items:center; gap:7px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" style="color:#f59e0b;">
                    <circle cx="12" cy="8" r="7"></circle>
                    <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                </svg>
                <span>Rekor Skor Tertinggi Kamu: <strong id="localHighScore">0 Poin</strong></span>
            </div>
            <div style="display:inline-flex; align-items:center; gap:7px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" style="color:var(--red);">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                </svg>
                <span>Kombo Streak Terbaik: <strong id="localHighStreak">0x Berurutan</strong></span>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════
         2. ARENA PERMAINAN (GAME ARENA)
    ══════════════════════════════════════════════ -->
    <div class="game-arena-wrap" id="gameArenaSection">
        <!-- HUD Bar -->
        <div class="game-hud">
            <div class="hud-timer-wrap">
                <span class="hud-timer-badge" id="hudTimerDisplay">30.0s</span>
                <div>
                    <div class="hud-progress-bar-bg">
                        <div class="hud-progress-bar-fill" id="hudTimerBar"></div>
                    </div>
                </div>
            </div>

            <div class="hud-score-wrap">
                <div class="hud-score-val" id="hudScore">0</div>
                <div class="hud-score-label">Skor Poin</div>
            </div>

            <div style="display:flex; align-items:center; gap:10px;">
                <div class="hud-combo-badge" id="hudCombo">1x Kombo</div>
                <button type="button" class="btn-quit-game" id="btnQuitGame">Keluar</button>
            </div>
        </div>

        <!-- Target Question Card -->
        <div class="target-card" id="targetCard">
            <span class="target-category-pill" id="targetCategoryPill">HIRAGANA</span>
            <div class="target-glyph" id="targetGlyph">あ</div>
            <div class="target-hint" id="targetHint">Pilih bacaan romaji yang tepat!</div>
        </div>

        <!-- 4 Choices Grid -->
        <div class="answers-grid" id="answersGrid">
            <button type="button" class="btn-answer" data-index="0">
                <span class="text">a</span>
                <span class="hotkey">1</span>
            </button>
            <button type="button" class="btn-answer" data-index="1">
                <span class="text">i</span>
                <span class="hotkey">2</span>
            </button>
            <button type="button" class="btn-answer" data-index="2">
                <span class="text">u</span>
                <span class="hotkey">3</span>
            </button>
            <button type="button" class="btn-answer" data-index="3">
                <span class="text">e</span>
                <span class="hotkey">4</span>
            </button>
        </div>

        <div class="keyboard-hint" style="display:flex; align-items:center; justify-content:center; gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--gold); flex-shrink:0;">
                <path d="M9 18h6"></path>
                <path d="M10 22h4"></path>
                <path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"></path>
            </svg>
            <span>Tips Cepat: Kamu bisa menekan angka <kbd>1</kbd>, <kbd>2</kbd>, <kbd>3</kbd>, <kbd>4</kbd> (atau huruf <kbd>A</kbd>, <kbd>S</kbd>, <kbd>D</kbd>, <kbd>F</kbd>) di keyboard untuk menjawab lebih cepat!</span>
        </div>
    </div>

</div>

<!-- ══════════════════════════════════════════════
     3. GAME OVER MODAL (HASIL AKHIR)
══════════════════════════════════════════════ -->
<div class="gameover-overlay" id="gameOverModal">
    <div class="gameover-card">
        <div class="gameover-trophy">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="8" r="7"></circle>
                <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
            </svg>
        </div>

        <h3 class="gameover-title" id="goTitle">Permainan Selesai!</h3>
        <div class="gameover-rank" id="goRankTitle">NINJA REFLEKS KANA</div>

        <div class="gameover-score-box">
            <span class="go-score-val" id="goFinalScore">0</span>
            <span class="go-score-lbl">Skor Akhir Kamu</span>
        </div>

        <div class="gameover-stats-grid">
            <div class="go-stat-card">
                <strong id="goCorrectCount" style="color:#10b981;">0</strong>
                <span>Benar</span>
            </div>
            <div class="go-stat-card">
                <strong id="goAccuracyRate">0%</strong>
                <span>Akurasi</span>
            </div>
            <div class="go-stat-card">
                <strong id="goMaxStreak" style="color:#f59e0b;">0x</strong>
                <span>Max Streak</span>
            </div>
        </div>

        <div class="gameover-actions">
            <button type="button" class="btn-go-retry" id="btnGoRetry">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="1 4 1 10 7 10"></polyline>
                    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                </svg>
                Main Lagi
            </button>
            <button type="button" class="btn-go-setup" id="btnGoSetup">
                Atur Waktu
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(() => {
    // Datasets dari Controller
    const datasets = {
        hiragana: @json($hiraganaList),
        katakana: @json($katakanaList),
        mixed:    @json($mixedKanaList),
        kanji:    @json($kanjiList)
    };

    // State Setup
    let selectedMode = 'hiragana';
    let durationSeconds = 5;

    // State Game Loop
    let timerId = null;
    let timeRemainingMs = 5000;
    let score = 0;
    let currentStreak = 0;
    let maxStreak = 0;
    let totalAnswered = 0;
    let totalCorrect = 0;
    let currentQuestion = null;
    let isProcessingAnswer = false;

    // Web Audio Synthesis (Suara Ding/Boop instan tanpa file eksternal)
    const audioCtx = (window.AudioContext || window.webkitAudioContext) ? new (window.AudioContext || window.webkitAudioContext)() : null;

    function playSound(type) {
        if (!audioCtx) return;
        try {
            if (audioCtx.state === 'suspended') audioCtx.resume();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);

            if (type === 'correct') {
                osc.type = 'sine';
                osc.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
                osc.frequency.exponentialRampToValueAtTime(880, audioCtx.currentTime + 0.12); // A5
                gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.18);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.18);
            } else if (type === 'wrong') {
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(220, audioCtx.currentTime);
                osc.frequency.linearRampToValueAtTime(140, audioCtx.currentTime + 0.15);
                gain.gain.setValueAtTime(0.25, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.2);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.2);
            } else if (type === 'gameover') {
                osc.type = 'sine';
                osc.frequency.setValueAtTime(440, audioCtx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(880, audioCtx.currentTime + 0.3);
                gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.4);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.4);
            }
        } catch (e) {
            // Audio silent fallback
        }
    }

    // DOM Elements - Setup
    const setupSection = document.getElementById('gameSetupSection');
    const arenaSection = document.getElementById('gameArenaSection');
    const gameOverModal = document.getElementById('gameOverModal');

    const modeCards = document.querySelectorAll('.mode-card');
    const presetBtns = document.querySelectorAll('.btn-preset');
    const timeSlider = document.getElementById('timeSlider');
    const timeDisplayNum = document.getElementById('timeDisplayNum');
    const btnTimeMinus = document.getElementById('btnTimeMinus');
    const btnTimePlus = document.getElementById('btnTimePlus');
    const difficultyText = document.getElementById('difficultyText');
    const btnStartGame = document.getElementById('btnStartGame');
    const localHighScore = document.getElementById('localHighScore');
    const localHighStreak = document.getElementById('localHighStreak');

    // DOM Elements - Arena
    const hudTimerDisplay = document.getElementById('hudTimerDisplay');
    const hudTimerBar = document.getElementById('hudTimerBar');
    const hudScore = document.getElementById('hudScore');
    const hudCombo = document.getElementById('hudCombo');
    const btnQuitGame = document.getElementById('btnQuitGame');
    const targetCard = document.getElementById('targetCard');
    const targetCategoryPill = document.getElementById('targetCategoryPill');
    const targetGlyph = document.getElementById('targetGlyph');
    const targetHint = document.getElementById('targetHint');
    const answerButtons = document.querySelectorAll('.btn-answer');

    // DOM Elements - Game Over
    const goTitle = document.getElementById('goTitle');
    const goFinalScore = document.getElementById('goFinalScore');
    const goRankTitle = document.getElementById('goRankTitle');
    const goCorrectCount = document.getElementById('goCorrectCount');
    const goAccuracyRate = document.getElementById('goAccuracyRate');
    const goMaxStreak = document.getElementById('goMaxStreak');
    const btnGoRetry = document.getElementById('btnGoRetry');
    const btnGoSetup = document.getElementById('btnGoSetup');

    // Update Difficulty Label
    function updateDifficultyHint(val) {
        if (val <= 5) {
            difficultyText.textContent = 'Super Kilat (Refleks Dewa)';
            difficultyText.style.color = '#ef4444';
        } else if (val <= 10) {
            difficultyText.textContent = 'Cepat & Menantang';
            difficultyText.style.color = '#f59e0b';
        } else if (val <= 20) {
            difficultyText.textContent = 'Standar & Seimbang';
            difficultyText.style.color = 'var(--red)';
        } else {
            difficultyText.textContent = 'Santai & Latihan Bebas';
            difficultyText.style.color = '#10b981';
        }
    }

    // Set Time Handler (Clamp 3 - 60)
    function setDuration(val) {
        val = parseInt(val, 10);
        if (isNaN(val)) val = 5;
        val = Math.max(3, Math.min(60, val));
        durationSeconds = val;
        timeSlider.value = val;
        timeDisplayNum.textContent = val;
        if (hudTimerDisplay) {
            hudTimerDisplay.textContent = val.toFixed(1) + 's';
        }
        updateDifficultyHint(val);

        presetBtns.forEach(btn => {
            btn.classList.toggle('active', parseInt(btn.dataset.time, 10) === val);
        });
    }

    // Load High Scores from LocalStorage
    function loadSavedRecords() {
        const savedScore = localStorage.getItem(`speed_match_score_${selectedMode}`) || 0;
        const savedStreak = localStorage.getItem(`speed_match_streak_${selectedMode}`) || 0;
        localHighScore.textContent = `${savedScore} Poin`;
        localHighStreak.textContent = `${savedStreak}x Berurutan`;
    }

    // Mode Card Selector
    modeCards.forEach(card => {
        card.addEventListener('click', () => {
            modeCards.forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            selectedMode = card.dataset.mode;
            loadSavedRecords();
        });
    });

    // Preset Time Buttons
    presetBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            setDuration(btn.dataset.time);
        });
    });

    // Slider
    timeSlider.addEventListener('input', (e) => {
        setDuration(e.target.value);
    });

    // Stepper - / +
    btnTimeMinus.addEventListener('click', () => {
        setDuration(durationSeconds - 1);
    });
    btnTimePlus.addEventListener('click', () => {
        setDuration(durationSeconds + 1);
    });

    // Start Game
    btnStartGame.addEventListener('click', startGame);

    // ══════════════════════════════════════════════
    // GAME ENGINE
    // ══════════════════════════════════════════════
    function startGame() {
        // Reset states
        score = 0;
        currentStreak = 0;
        maxStreak = 0;
        totalAnswered = 0;
        totalCorrect = 0;
        isProcessingAnswer = false;

        hudScore.textContent = '0';
        updateComboUI();

        // Switch Views
        setupSection.style.display = 'none';
        arenaSection.style.display = 'block';
        gameOverModal.classList.remove('show');

        // Generate First Question & Start Countdown
        nextQuestion();
    }

    function updateComboUI() {
        let mult = 1;
        if (currentStreak >= 10) mult = 4;
        else if (currentStreak >= 5) mult = 3;
        else if (currentStreak >= 3) mult = 2;

        if (currentStreak >= 3) {
            hudCombo.className = 'hud-combo-badge fire';
            hudCombo.innerHTML = `
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                </svg>
                <span>${mult}x Kombo (${currentStreak})</span>
            `;
        } else {
            hudCombo.className = 'hud-combo-badge';
            hudCombo.textContent = `${mult}x Kombo`;
        }
    }

    function nextQuestion() {
        clearInterval(timerId);
        isProcessingAnswer = false;

        const list = datasets[selectedMode];
        if (!list || list.length < 4) return;

        // Pick 1 target item
        const randIndex = Math.floor(Math.random() * list.length);
        const target = list[randIndex];

        // Pick 3 unique wrong answers
        const wrongAnswers = [];
        const seen = new Set([target.answer]);

        while (wrongAnswers.length < 3) {
            const candidate = list[Math.floor(Math.random() * list.length)].answer;
            if (!seen.has(candidate)) {
                seen.add(candidate);
                wrongAnswers.push(candidate);
            }
        }

        // Shuffle choices
        const choices = [target.answer, ...wrongAnswers];
        for (let i = choices.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [choices[i], choices[j]] = [choices[j], choices[i]];
        }

        currentQuestion = {
            target: target,
            choices: choices,
            correctAnswer: target.answer
        };

        // Render to UI
        targetCategoryPill.textContent = `${target.type.toUpperCase()} • SOAL #${totalAnswered + 1}`;
        targetGlyph.textContent = target.char;
        targetHint.textContent = target.type === 'Kanji N5' ? 'Pilih arti bahasa Indonesia yang tepat!' : 'Pilih bacaan romaji yang tepat!';

        answerButtons.forEach((btn, idx) => {
            btn.className = 'btn-answer';
            const choiceVal = choices[idx];
            btn.querySelector('.text').textContent = choiceVal;
            btn.dataset.answer = choiceVal;
        });

        // Start per-question countdown (resets back to full duration e.g. 5.0s)
        startQuestionCountdown();
    }

    function startQuestionCountdown() {
        clearInterval(timerId);

        const totalMs = durationSeconds * 1000;
        const questionStartTime = Date.now();
        timeRemainingMs = totalMs;

        // Reset visual display immediately
        hudTimerDisplay.textContent = durationSeconds.toFixed(1) + 's';
        hudTimerDisplay.classList.remove('urgent');

        // Instant reset bar to 100% without reverse transition lag
        hudTimerBar.style.transition = 'none';
        hudTimerBar.style.width = '100%';
        void hudTimerBar.offsetWidth; // Force reflow
        hudTimerBar.style.transition = 'width 0.08s linear';

        timerId = setInterval(() => {
            if (isProcessingAnswer) return;

            const elapsed = Date.now() - questionStartTime;
            const remaining = Math.max(0, totalMs - elapsed);
            timeRemainingMs = remaining;

            const sec = (remaining / 1000).toFixed(1);
            hudTimerDisplay.textContent = sec + 's';

            // Progress bar
            const percent = (remaining / totalMs) * 100;
            hudTimerBar.style.width = percent + '%';

            // Urgent blink when <= 35% time left or <= 2 seconds
            const urgentLimit = Math.min(2000, totalMs * 0.35);
            hudTimerDisplay.classList.toggle('urgent', remaining <= urgentLimit);

            if (remaining <= 0) {
                clearInterval(timerId);
                onQuestionTimeout();
            }
        }, 50);
    }

    function onQuestionTimeout() {
        if (isProcessingAnswer) return;
        isProcessingAnswer = true;
        totalAnswered++;

        playSound('wrong');
        currentStreak = 0;
        updateComboUI();

        hudTimerDisplay.textContent = '0.0s';
        hudTimerBar.style.width = '0%';
        hudTimerDisplay.classList.add('urgent');

        // Shake card and highlight correct answer
        targetCard.classList.remove('shake');
        void targetCard.offsetWidth;
        targetCard.classList.add('shake');

        answerButtons.forEach(btn => {
            if (btn.dataset.answer === currentQuestion.correctAnswer) {
                btn.classList.add('correct');
            }
        });

        setTimeout(() => {
            endGame('Waktu Habis!');
        }, 650);
    }

    // Handle User Answer
    function submitAnswer(chosenAnswer, clickedBtn) {
        if (isProcessingAnswer || !currentQuestion) return;
        isProcessingAnswer = true;
        clearInterval(timerId); // Pause countdown immediately upon answering
        totalAnswered++;

        const isCorrect = (chosenAnswer === currentQuestion.correctAnswer);

        if (isCorrect) {
            playSound('correct');
            totalCorrect++;
            currentStreak++;
            if (currentStreak > maxStreak) maxStreak = currentStreak;

            let mult = 1;
            if (currentStreak >= 10) mult = 4;
            else if (currentStreak >= 5) mult = 3;
            else if (currentStreak >= 3) mult = 2;

            const pointsEarned = 100 * mult;
            score += pointsEarned;
            hudScore.textContent = score;

            updateComboUI();

            if (clickedBtn) clickedBtn.classList.add('correct');

            setTimeout(() => {
                nextQuestion(); // Resets timer back to full duration!
            }, 140);
        } else {
            playSound('wrong');
            currentStreak = 0;
            updateComboUI();

            targetCard.classList.remove('shake');
            void targetCard.offsetWidth;
            targetCard.classList.add('shake');

            if (clickedBtn) clickedBtn.classList.add('wrong');

            // Highlight the true answer
            answerButtons.forEach(btn => {
                if (btn.dataset.answer === currentQuestion.correctAnswer) {
                    btn.classList.add('correct');
                }
            });

            setTimeout(() => {
                nextQuestion(); // Resets timer back to full duration!
            }, 400);
        }
    }

    // Answer Button Click Handlers
    answerButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            submitAnswer(btn.dataset.answer, btn);
        });
    });

    // Keyboard Shortcuts (1, 2, 3, 4 atau A, S, D, F)
    document.addEventListener('keydown', (e) => {
        if (arenaSection.style.display !== 'block' || isProcessingAnswer) return;

        let index = -1;
        if (e.key === '1' || e.key === 'a' || e.key === 'A') index = 0;
        else if (e.key === '2' || e.key === 's' || e.key === 'S') index = 1;
        else if (e.key === '3' || e.key === 'd' || e.key === 'D') index = 2;
        else if (e.key === '4' || e.key === 'f' || e.key === 'F') index = 3;

        if (index >= 0 && answerButtons[index]) {
            e.preventDefault();
            answerButtons[index].click();
        }
    });

    // Quit button during game
    btnQuitGame.addEventListener('click', () => {
        clearInterval(timerId);
        if (totalAnswered > 0) {
            endGame('Sesi Diakhiri');
        } else {
            arenaSection.style.display = 'none';
            setupSection.style.display = 'block';
        }
    });

    // ══════════════════════════════════════════════
    // GAME OVER & RESULTS
    // ══════════════════════════════════════════════
    function endGame(customTitle) {
        clearInterval(timerId);
        playSound('gameover');

        if (goTitle) {
            goTitle.textContent = customTitle || 'Permainan Selesai!';
        }

        const accuracy = totalAnswered > 0 ? Math.round((totalCorrect / totalAnswered) * 100) : 0;

        goFinalScore.textContent = score;
        goCorrectCount.textContent = `${totalCorrect} / ${totalAnswered}`;
        goAccuracyRate.textContent = `${accuracy}%`;
        goMaxStreak.textContent = `${maxStreak}x`;

        // Rank Evaluation
        let rank = 'PEMBELAJAR GIGIH';
        if (score >= 2000 && accuracy >= 90) {
            rank = 'SHINKANSEN REFLEKS DEWA';
        } else if (score >= 1200 && accuracy >= 80) {
            rank = 'NINJA REFLEKS KANA';
        } else if (score >= 600 && accuracy >= 70) {
            rank = 'SAMURAI PEMBELAJAR';
        }
        goRankTitle.textContent = rank;

        // Check & Save High Score
        const keyScore = `speed_match_score_${selectedMode}`;
        const keyStreak = `speed_match_streak_${selectedMode}`;
        const oldHighScore = parseInt(localStorage.getItem(keyScore) || '0', 10);
        const oldHighStreak = parseInt(localStorage.getItem(keyStreak) || '0', 10);

        if (score > oldHighScore) {
            localStorage.setItem(keyScore, score);
        }
        if (maxStreak > oldHighStreak) {
            localStorage.setItem(keyStreak, maxStreak);
        }
        loadSavedRecords();

        // Award XP if answered > 5 questions
        if (totalAnswered >= 5 && typeof window.reportActivity === 'function') {
            window.reportActivity('speed_match_complete');
        }

        gameOverModal.classList.add('show');
    }

    // Modal Actions
    btnGoRetry.addEventListener('click', () => {
        gameOverModal.classList.remove('show');
        startGame();
    });

    btnGoSetup.addEventListener('click', () => {
        gameOverModal.classList.remove('show');
        arenaSection.style.display = 'none';
        setupSection.style.display = 'block';
    });

    // Init
    setDuration(5);
    loadSavedRecords();
})();
</script>
@endsection

