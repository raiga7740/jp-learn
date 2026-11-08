@extends('dashboard.layout')

@php($pageTitle = 'Latihan Menulis Huruf')
@php($pageSubtitle = 'Asah ketrampilan menulis goresan Hiragana, Katakana, dan Kanji N5 secara interaktif.')

@section('styles')
    .writing-wrapper {
        display: grid;
        grid-template-columns: minmax(360px, 460px) 1fr;
        gap: 24px;
        align-items: start;
    }

    /* ── Canvas Studio ── */
    .studio-card {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: var(--shadow);
        position: sticky;
        top: 80px;
    }
    .studio-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 16px;
    }
    .studio-char-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .studio-badge {
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .06em;
    }
    .studio-badge.hiragana { background: var(--red-soft); color: var(--red); }
    .studio-badge.katakana { background: var(--blue-soft); color: var(--blue); }
    .studio-badge.kanji    { background: var(--gold-soft); color: var(--gold); }

    .studio-target-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--ink);
    }
    .studio-target-sub {
        font-size: 12px;
        color: var(--muted);
        font-weight: 600;
    }

    .studio-audio-btn {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 1px solid var(--line);
        background: #fff;
        color: var(--red);
        cursor: pointer;
        display: grid;
        place-items: center;
        transition: all .2s;
    }
    .studio-audio-btn:hover {
        background: var(--red);
        color: #fff;
        border-color: var(--red);
        transform: scale(1.08);
    }

    /* ── Canvas Stage (Tianzige Practice Box) ── */
    .canvas-stage-wrap {
        position: relative;
        width: 100%;
        max-width: 410px;
        aspect-ratio: 1 / 1;
        margin: 0 auto 16px;
        border-radius: 20px;
        border: 2px solid #dfd6e2;
        background: #ffffff;
        box-shadow: inset 0 2px 10px rgba(0,0,0,.04), 0 10px 24px -10px rgba(32,16,28,.1);
        overflow: hidden;
        user-select: none;
        touch-action: none;
    }

    /* Traditional Grid Lines (Tianzige style) */
    .grid-lines {
        position: absolute;
        inset: 0;
        pointer-events: none;
        background:
            /* Center horizontal line */
            linear-gradient(to bottom, transparent calc(50% - 0.5px), rgba(210,195,215,.6) calc(50% - 0.5px), rgba(210,195,215,.6) calc(50% + 0.5px), transparent calc(50% + 0.5px)),
            /* Center vertical line */
            linear-gradient(to right, transparent calc(50% - 0.5px), rgba(210,195,215,.6) calc(50% - 0.5px), rgba(210,195,215,.6) calc(50% + 0.5px), transparent calc(50% + 0.5px));
    }
    .grid-lines::after {
        content: '';
        position: absolute;
        inset: 0;
        /* Diagonal dashed guide lines */
        background-image:
            linear-gradient(45deg, transparent 49.5%, rgba(225,215,230,.5) 49.5%, rgba(225,215,230,.5) 50.5%, transparent 50.5%),
            linear-gradient(-45deg, transparent 49.5%, rgba(225,215,230,.5) 49.5%, rgba(225,215,230,.5) 50.5%, transparent 50.5%);
        pointer-events: none;
    }

    /* Watermark / Ghost Character Overlay */
    .char-watermark {
        position: absolute;
        inset: 0;
        display: grid;
        place-items: center;
        font-family: 'Noto Sans JP', 'Hiragino Sans', sans-serif;
        font-size: 240px;
        font-weight: 400;
        color: rgba(180, 160, 190, 0.28);
        pointer-events: none;
        user-select: none;
        transition: opacity .2s ease;
        line-height: 1;
    }
    .char-watermark.hidden {
        opacity: 0;
    }

    #drawingCanvas {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        cursor: crosshair;
        z-index: 5;
    }

    /* ── Canvas Controls & Tools ── */
    .canvas-tools {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .tools-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        flex-wrap: wrap;
    }
    .brush-sizes {
        display: flex;
        align-items: center;
        gap: 6px;
        background: #f4eff6;
        padding: 4px 6px;
        border-radius: 10px;
    }
    .btn-brush {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        background: transparent;
        cursor: pointer;
        display: grid;
        place-items: center;
        transition: .18s;
    }
    .btn-brush:hover { background: #e5dde9; }
    .btn-brush.active {
        background: #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,.1);
    }
    .btn-brush span {
        border-radius: 50%;
        background: var(--ink);
        display: block;
    }

    .color-picker {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .btn-color {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        border: 2px solid #fff;
        outline: 1px solid var(--line);
        cursor: pointer;
        transition: transform .18s;
    }
    .btn-color:hover { transform: scale(1.15); }
    .btn-color.active {
        outline: 2px solid var(--red);
        transform: scale(1.18);
    }

    .action-btn-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
    }
    .btn-tool {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 9px 8px;
        border-radius: 10px;
        border: 1px solid var(--line);
        background: #fff;
        color: var(--ink);
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all .18s;
        text-align: center;
    }
    .btn-tool:hover {
        background: #fbf5f7;
        border-color: var(--red);
        color: var(--red);
    }
    .btn-tool.active {
        background: var(--red-soft);
        border-color: var(--red);
        color: var(--red);
    }

    .canvas-nav-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 4px;
    }
    .btn-nav-char {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 800;
        border: 1px solid var(--line);
        background: #fff;
        color: var(--ink);
        cursor: pointer;
        transition: all .18s;
    }
    .btn-nav-char:hover {
        border-color: var(--red);
        color: var(--red);
        background: var(--red-soft);
    }

    /* Feedback Banner */
    .feedback-box {
        margin-top: 12px;
        padding: 10px 14px;
        border-radius: 12px;
        background: #fbf8fc;
        border: 1px dashed #d9cedd;
        font-size: 12px;
        color: var(--ink);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ── Accuracy Checker ── */
    .btn-check-accuracy {
        width: 100%;
        margin-top: 10px;
        padding: 12px 16px;
        border-radius: 12px;
        border: none;
        background: linear-gradient(135deg, var(--red), var(--red-2));
        color: #fff;
        font-size: 13.5px;
        font-weight: 800;
        letter-spacing: .02em;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 8px 20px -6px rgba(196,16,46,.55);
        transition: transform .18s, box-shadow .18s;
    }
    .btn-check-accuracy:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px -6px rgba(196,16,46,.65);
    }
    .btn-check-accuracy:active {
        transform: none;
    }

    .accuracy-card {
        margin-top: 12px;
        padding: 16px;
        border-radius: 14px;
        border: 1px solid var(--line);
        background: #faf8fb;
        animation: fadeInAcc .25s ease;
    }
    @keyframes fadeInAcc {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .acc-top {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 12px;
    }
    .acc-score-wrap {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #fff;
        border: 3px solid var(--green);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,.06);
    }
    .acc-score-wrap.good { border-color: #2b8a3e; color: #2b8a3e; }
    .acc-score-wrap.medium { border-color: #f59f00; color: #f59f00; }
    .acc-score-wrap.poor { border-color: #c4102e; color: #c4102e; }

    .acc-score-val {
        font-size: 16px;
        font-weight: 900;
        line-height: 1;
    }
    .acc-score-label {
        font-size: 9px;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        margin-top: 2px;
    }

    .acc-meta {
        flex: 1;
    }
    .acc-status-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        margin-bottom: 4px;
    }
    .acc-status-badge.good { background: #d3f9d8; color: #2b8a3e; }
    .acc-status-badge.medium { background: #fff3bf; color: #d9480f; }
    .acc-status-badge.poor { background: #ffe3e3; color: #c92a2a; }

    .acc-status-desc {
        font-size: 11.5px;
        color: var(--ink);
        line-height: 1.4;
    }

    .acc-bars {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 12px;
        padding: 10px 12px;
        background: #fff;
        border-radius: 10px;
        border: 1px solid var(--line);
    }
    .acc-bar-item {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }
    .acc-bar-label {
        display: flex;
        justify-content: space-between;
        font-size: 11px;
        color: var(--muted);
    }
    .acc-bar-label strong {
        color: var(--ink);
    }
    .acc-bar-track {
        height: 6px;
        background: #eee6f0;
        border-radius: 999px;
        overflow: hidden;
    }
    .acc-bar-fill {
        height: 100%;
        border-radius: 999px;
        background: var(--green);
        transition: width .4s ease;
    }

    .acc-actions {
        display: flex;
        gap: 8px;
    }
    .acc-btn-retry, .acc-btn-next {
        flex: 1;
        padding: 8px 12px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: .18s;
        border: none;
    }
    .acc-btn-retry {
        background: #fff;
        border: 1px solid var(--line);
        color: var(--ink);
    }
    .acc-btn-retry:hover {
        background: #fbf5f7;
        border-color: var(--red);
        color: var(--red);
    }
    .acc-btn-next {
        background: var(--navy);
        color: #fff;
    }
    .acc-btn-next:hover {
        background: var(--navy-2);
        transform: translateY(-1px);
    }

    /* ── Right Column: Character Library & Tabs ── */
    .library-card {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: var(--shadow);
    }
    .library-header {
        margin-bottom: 18px;
    }
    .library-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
        border-bottom: 2px solid var(--line);
        padding-bottom: 12px;
        overflow-x: auto;
    }
    .lib-tab {
        padding: 8px 18px;
        border-radius: 10px;
        border: 1px solid transparent;
        background: transparent;
        color: var(--muted);
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        transition: .18s;
        white-space: nowrap;
    }
    .lib-tab:hover {
        color: var(--ink);
        background: rgba(0,0,0,.04);
    }
    .lib-tab.active {
        background: var(--red-soft);
        color: var(--red);
        border-color: rgba(196,16,46,.2);
    }

    .search-filter-wrap {
        position: relative;
        margin-bottom: 18px;
    }
    .search-filter-wrap input {
        width: 100%;
        padding: 10px 14px 10px 38px;
        border-radius: 12px;
        border: 1px solid var(--line);
        background: #fff;
        font-size: 13px;
        color: var(--ink);
        outline: none;
        transition: border-color .18s, box-shadow .18s;
    }
    .search-filter-wrap input:focus {
        border-color: var(--red);
        box-shadow: 0 0 0 3px rgba(196,16,46,.12);
    }
    .search-filter-wrap svg {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
    }

    /* Grid of character cards */
    .char-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(58px, 1fr));
        gap: 8px;
        max-height: 540px;
        overflow-y: auto;
        padding-right: 4px;
    }
    .char-grid::-webkit-scrollbar {
        width: 6px;
    }
    .char-grid::-webkit-scrollbar-thumb {
        background: #ded5e3;
        border-radius: 999px;
    }
    .char-tile {
        aspect-ratio: 1 / 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        border: 1px solid var(--line);
        background: #fff;
        cursor: pointer;
        transition: all .16s ease;
        padding: 4px;
        user-select: none;
    }
    .char-tile:hover {
        border-color: var(--red);
        background: #fff7f8;
        transform: translateY(-2px);
    }
    .char-tile.active {
        border-color: var(--red);
        background: var(--red-soft);
        box-shadow: 0 4px 12px -4px rgba(196,16,46,.35);
    }
    .char-tile-char {
        font-family: 'Noto Sans JP', sans-serif;
        font-size: 22px;
        font-weight: 800;
        color: var(--ink);
        line-height: 1.2;
    }
    .char-tile.active .char-tile-char {
        color: var(--red);
    }
    .char-tile-romaji {
        font-size: 9.5px;
        font-weight: 700;
        color: var(--muted);
        text-transform: lowercase;
        margin-top: 2px;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        padding: 0 2px;
        text-align: center;
    }
    .char-tile.active .char-tile-romaji {
        color: var(--red);
    }

    @media (max-width: 900px) {
        .writing-wrapper {
            grid-template-columns: 1fr;
        }
        .studio-card {
            position: static;
        }
    }
@endsection

@section('content')
<div class="writing-wrapper">
    <!-- Left Column: The Interactive Drawing Studio -->
    <div class="studio-card">
        <div class="studio-header">
            <div class="studio-char-info">
                <span class="studio-badge hiragana" id="activeBadge">Hiragana</span>
                <div>
                    <div class="studio-target-title" id="activeTitle">Karakter: あ (a)</div>
                    <div class="studio-target-sub" id="activeSub">Garis Vokal Dasar</div>
                </div>
            </div>

            <button type="button" class="studio-audio-btn" id="btnAudio" title="Dengarkan pelafalan (Tekan A)">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                </svg>
            </button>
        </div>

        <!-- Canvas Stage with Tianzige grid and watermark -->
        <div class="canvas-stage-wrap" id="canvasWrap">
            <div class="grid-lines"></div>
            <div class="char-watermark" id="charWatermark">あ</div>
            <canvas id="drawingCanvas"></canvas>
        </div>

        <!-- Canvas Tools & Controls -->
        <div class="canvas-tools">
            <div class="tools-row">
                <!-- Brush Size -->
                <div class="brush-sizes" title="Ukuran Kuas">
                    <button type="button" class="btn-brush" data-size="6" title="Kuas Tipis">
                        <span style="width: 6px; height: 6px;"></span>
                    </button>
                    <button type="button" class="btn-brush active" data-size="12" title="Kuas Sedang">
                        <span style="width: 10px; height: 10px;"></span>
                    </button>
                    <button type="button" class="btn-brush" data-size="20" title="Kuas Tebal">
                        <span style="width: 15px; height: 15px;"></span>
                    </button>
                </div>

                <!-- Ink Colors -->
                <div class="color-picker" title="Warna Tinta">
                    <button type="button" class="btn-color active" data-color="#1c141c" style="background: #1c141c;" title="Tinta Hitam Tradisional"></button>
                    <button type="button" class="btn-color" data-color="#c4102e" style="background: #c4102e;" title="Tinta Merah Shodo"></button>
                    <button type="button" class="btn-color" data-color="#1d4ed8" style="background: #1d4ed8;" title="Tinta Biru"></button>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="action-btn-row">
                <button type="button" class="btn-tool" id="btnToggleGuide" title="Tampilkan / Sembunyikan Bayangan Huruf">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    Bayangan
                </button>

                <button type="button" class="btn-tool" id="btnUndo" title="Batalkan goresan terakhir">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="1 4 1 10 7 10"></polyline>
                        <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                    </svg>
                    Undo
                </button>

                <button type="button" class="btn-tool" id="btnClear" title="Bersihkan kanvas">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    Hapus
                </button>

                <button type="button" class="btn-tool" id="btnDownload" title="Simpan hasil tulisan sebagai gambar">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Simpan
                </button>
            </div>

            <!-- Periksa Akurasi Button -->
            <button type="button" class="btn-check-accuracy" id="btnCheckAccuracy">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                Periksa Akurasi Tulisan
            </button>

            <!-- Accuracy Result Card -->
            <div class="accuracy-card" id="accuracyCard" style="display: none;">
                <div class="acc-top">
                    <div class="acc-score-wrap" id="accScoreWrap">
                        <span class="acc-score-val" id="accScoreVal">0%</span>
                        <span class="acc-score-label">Akurasi</span>
                    </div>
                    <div class="acc-meta">
                        <div class="acc-status-badge" id="accStatusBadge">Sempurna</div>
                        <div class="acc-status-desc" id="accStatusDesc">Bentuk tulisan sangat presisi dan mirip dengan panduan.</div>
                    </div>
                </div>

                <div class="acc-bars">
                    <div class="acc-bar-item">
                        <div class="acc-bar-label">
                            <span>Kelengkapan Bentuk (Coverage)</span>
                            <strong id="accCoverageVal">0%</strong>
                        </div>
                        <div class="acc-bar-track"><div class="acc-bar-fill" id="accCoverageFill" style="width: 0%;"></div></div>
                    </div>
                    <div class="acc-bar-item">
                        <div class="acc-bar-label">
                            <span>Kerapian & Presisi (Precision)</span>
                            <strong id="accPrecisionVal">0%</strong>
                        </div>
                        <div class="acc-bar-track"><div class="acc-bar-fill" id="accPrecisionFill" style="width: 0%;"></div></div>
                    </div>
                </div>

                <div class="acc-actions">
                    <button type="button" class="acc-btn-retry" id="btnAccRetry">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="1 4 1 10 7 10"></polyline>
                            <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                        </svg>
                        Coba Lagi
                    </button>
                    <button type="button" class="acc-btn-next" id="btnAccNext">
                        Lanjut Huruf Berikutnya &rarr;
                    </button>
                </div>
            </div>

            <!-- Next & Previous character -->
            <div class="canvas-nav-row">
                <button type="button" class="btn-nav-char" id="btnPrevChar">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                    Sebelumnya
                </button>
                <button type="button" class="btn-nav-char" id="btnNextChar">
                    Berikutnya
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </div>

            <!-- Feedback tip -->
            <div class="feedback-box">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--red);flex-shrink:0;">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <span>Gunakan garis panduan kotak untuk menjaga proporsi dan keseimbangan bentuk huruf.</span>
            </div>
        </div>
    </div>

    <!-- Right Column: Character Selection Library -->
    <div class="library-card">
        <div class="library-header">
            <h2 style="font-size: 18px; font-weight: 800; color: var(--ink); margin-bottom: 4px;">Pilih Karakter untuk Ditulis</h2>
            <p style="font-size: 13px; color: var(--muted);">Pilih salah satu huruf di bawah untuk langsung berlatih di kanvas.</p>
        </div>

        <!-- Library Tabs -->
        <div class="library-tabs">
            <button type="button" class="lib-tab active" data-tab="hiragana">Hiragana ({{ count($hiraganaChars) }})</button>
            <button type="button" class="lib-tab" data-tab="katakana">Katakana ({{ count($katakanaChars) }})</button>
            <button type="button" class="lib-tab" data-tab="kanji">Kanji N5 ({{ count($kanjiChars) }})</button>
        </div>

        <!-- Filter Input -->
        <div class="search-filter-wrap">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" id="charSearchInput" placeholder="Cari huruf atau romaji (contoh: ka, sa, air, satu)...">
        </div>

        <!-- Character Grid Container -->
        <div class="char-grid" id="charGrid">
            <!-- Rendered dynamically by JavaScript -->
        </div>
    </div>
</div>

<script>
(function() {
    // Character datasets passed from backend
    const datasets = {
        hiragana: @json($hiraganaChars),
        katakana: @json($katakanaChars),
        kanji: @json($kanjiChars)
    };

    // State
    let currentTab = 'hiragana';
    let currentIndex = 0;
    let brushSize = 12;
    let brushColor = '#1c141c';
    let isGuideVisible = true;

    // History for Undo
    let strokeHistory = [];

    // Penanda bahwa karakter saat ini sudah dicoba ditulis (untuk XP)
    let hasDrawn = false;

    function commitPractice() {
        if (!hasDrawn) return;
        hasDrawn = false;
        if (typeof window.reportActivity === 'function') {
            window.reportActivity('writing_practice');
        }
    }

    // DOM Elements
    const canvasWrap = document.getElementById('canvasWrap');
    const canvas = document.getElementById('drawingCanvas');
    const ctx = canvas.getContext('2d');
    const watermark = document.getElementById('charWatermark');

    const activeBadge = document.getElementById('activeBadge');
    const activeTitle = document.getElementById('activeTitle');
    const activeSub = document.getElementById('activeSub');
    const btnAudio = document.getElementById('btnAudio');

    const btnToggleGuide = document.getElementById('btnToggleGuide');
    const btnUndo = document.getElementById('btnUndo');
    const btnClear = document.getElementById('btnClear');
    const btnDownload = document.getElementById('btnDownload');
    const btnPrevChar = document.getElementById('btnPrevChar');
    const btnNextChar = document.getElementById('btnNextChar');

    // Accuracy Checker Elements
    const btnCheckAccuracy = document.getElementById('btnCheckAccuracy');
    const accuracyCard = document.getElementById('accuracyCard');
    const accScoreVal = document.getElementById('accScoreVal');
    const accScoreWrap = document.getElementById('accScoreWrap');
    const accStatusBadge = document.getElementById('accStatusBadge');
    const accStatusDesc = document.getElementById('accStatusDesc');
    const accCoverageVal = document.getElementById('accCoverageVal');
    const accCoverageFill = document.getElementById('accCoverageFill');
    const accPrecisionVal = document.getElementById('accPrecisionVal');
    const accPrecisionFill = document.getElementById('accPrecisionFill');
    const btnAccRetry = document.getElementById('btnAccRetry');
    const btnAccNext = document.getElementById('btnAccNext');

    const libTabs = document.querySelectorAll('.lib-tab');
    const charSearchInput = document.getElementById('charSearchInput');
    const charGrid = document.getElementById('charGrid');

    const brushButtons = document.querySelectorAll('.btn-brush');
    const colorButtons = document.querySelectorAll('.btn-color');

    // Canvas Sizing with Retina / High-DPI support
    let dpr = window.devicePixelRatio || 1;
    function resizeCanvas() {
        const rect = canvasWrap.getBoundingClientRect();
        const displayWidth = rect.width;
        const displayHeight = rect.height;

        // Save current content if any
        let tempCanvas = null;
        if (canvas.width > 0 && canvas.height > 0) {
            tempCanvas = document.createElement('canvas');
            tempCanvas.width = canvas.width;
            tempCanvas.height = canvas.height;
            tempCanvas.getContext('2d').drawImage(canvas, 0, 0);
        }

        canvas.width = displayWidth * dpr;
        canvas.height = displayHeight * dpr;
        ctx.scale(dpr, dpr);

        if (tempCanvas) {
            ctx.drawImage(tempCanvas, 0, 0, tempCanvas.width / dpr, tempCanvas.height / dpr);
        }
    }

    // Drawing variables
    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;

    function saveState() {
        if (strokeHistory.length > 25) strokeHistory.shift();
        strokeHistory.push(ctx.getImageData(0, 0, canvas.width, canvas.height));
    }

    function getPointerPos(e) {
        const rect = canvas.getBoundingClientRect();
        return {
            x: e.clientX - rect.left,
            y: e.clientY - rect.top
        };
    }

    function startDrawing(e) {
        isDrawing = true;
        hasDrawn = true;
        saveState();
        const pos = getPointerPos(e);
        lastX = pos.x;
        lastY = pos.y;

        // Draw dot on click
        ctx.beginPath();
        ctx.arc(lastX, lastY, brushSize / 2, 0, Math.PI * 2);
        ctx.fillStyle = brushColor;
        ctx.fill();
    }

    function draw(e) {
        if (!isDrawing) return;
        const pos = getPointerPos(e);

        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(pos.x, pos.y);
        ctx.strokeStyle = brushColor;
        ctx.lineWidth = brushSize;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        ctx.stroke();

        lastX = pos.x;
        lastY = pos.y;
    }

    function stopDrawing() {
        isDrawing = false;
    }

    // Touch & Pointer listeners
    canvas.addEventListener('pointerdown', startDrawing);
    canvas.addEventListener('pointermove', draw);
    canvas.addEventListener('pointerup', stopDrawing);
    canvas.addEventListener('pointercancel', stopDrawing);
    canvas.addEventListener('pointerleave', stopDrawing);

    // Undo Action
    function undo() {
        if (strokeHistory.length > 0) {
            const prevState = strokeHistory.pop();
            ctx.putImageData(prevState, 0, 0);
        } else {
            clearCanvas();
        }
    }

    function resetAccuracyCard() {
        if (accuracyCard) {
            accuracyCard.style.display = 'none';
        }
    }

    function clearCanvas() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        strokeHistory = [];
        resetAccuracyCard();
    }

    // Web Speech API
    function speakJapanese(text) {
        if (!('speechSynthesis' in window)) return;
        try {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'ja-JP';
            utterance.rate = 0.85;
            window.speechSynthesis.speak(utterance);
        } catch (e) {
            console.warn('Speech error:', e);
        }
    }

    // Render Character on Studio
    function setTargetCharacter(tab, index) {
        const list = datasets[tab];
        if (!list || list.length === 0) return;

        commitPractice();

        if (index < 0) index = list.length - 1;
        if (index >= list.length) index = 0;

        currentTab = tab;
        currentIndex = index;
        const item = list[index];

        // Update Studio Header
        activeBadge.textContent = ucfirst(item.type);
        activeBadge.className = 'studio-badge ' + item.type;
        if (item.type === 'kanji') {
            activeTitle.textContent = `Kanji: ${item.char} (${item.meaning || ''})`;
            activeSub.textContent = `Kun: ${item.kun || '-'} • On: ${item.on || '-'} • ${item.category || item.group || ''}`;
        } else {
            activeTitle.textContent = `Karakter: ${item.char} (${item.romaji || ''})`;
            activeSub.textContent = item.meaning || item.group || '';
        }

        // Update watermark
        watermark.textContent = item.char;
        if (item.type === 'kanji') {
            watermark.style.fontSize = '210px';
        } else {
            watermark.style.fontSize = '240px';
        }

        // Clear canvas for fresh practice
        clearCanvas();

        // Highlight active tile in grid
        document.querySelectorAll('.char-tile').forEach((tile, idx) => {
            if (parseInt(tile.dataset.index, 10) === index && tile.dataset.tab === tab) {
                tile.classList.add('active');
                tile.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                tile.classList.remove('active');
            }
        });
    }

    function ucfirst(str) {
        return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
    }

    // Render Library Grid based on Tab and Filter
    function renderGrid() {
        const list = datasets[currentTab] || [];
        const query = (charSearchInput.value || '').trim().toLowerCase();
        charGrid.innerHTML = '';

        list.forEach((item, idx) => {
            const matchesQuery = !query || 
                item.char.toLowerCase().includes(query) ||
                (item.label && item.label.toLowerCase().includes(query)) ||
                (item.romaji && item.romaji.toLowerCase().includes(query)) ||
                (item.meaning && item.meaning.toLowerCase().includes(query)) ||
                (item.kun && item.kun.toLowerCase().includes(query)) ||
                (item.on && item.on.toLowerCase().includes(query)) ||
                (item.group && item.group.toLowerCase().includes(query));

            if (!matchesQuery) return;

            const tile = document.createElement('div');
            tile.className = 'char-tile' + (idx === currentIndex ? ' active' : '');
            tile.dataset.tab = currentTab;
            tile.dataset.index = idx;

            const subLabel = item.label || item.meaning || item.romaji || '';
            const tileTooltip = item.type === 'kanji'
                ? `${item.char} — ${item.meaning || ''} (Kun: ${item.kun || '-'}, On: ${item.on || '-'})`
                : `${item.char} (${item.romaji || ''})`;

            tile.title = tileTooltip;
            tile.innerHTML = `
                <span class="char-tile-char">${item.char}</span>
                <span class="char-tile-romaji" title="${escapeHtml(subLabel)}">${escapeHtml(subLabel)}</span>
            `;

            tile.addEventListener('click', () => {
                setTargetCharacter(currentTab, idx);
            });

            charGrid.appendChild(tile);
        });

        if (charGrid.children.length === 0) {
            charGrid.innerHTML = `
                <div style="grid-column: 1 / -1; padding: 32px 16px; text-align: center; color: var(--muted); font-size: 13px;">
                    Tidak ada karakter yang cocok dengan "${escapeHtml(query)}"
                </div>
            `;
        }
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    // Tool Button Listeners
    btnToggleGuide.addEventListener('click', () => {
        isGuideVisible = !isGuideVisible;
        watermark.classList.toggle('hidden', !isGuideVisible);
        btnToggleGuide.classList.toggle('active', isGuideVisible);
    });

    btnUndo.addEventListener('click', undo);
    btnClear.addEventListener('click', clearCanvas);

    // Download Drawing as PNG
    btnDownload.addEventListener('click', () => {
        commitPractice();
        const item = datasets[currentTab][currentIndex];
        const exportCanvas = document.createElement('canvas');
        exportCanvas.width = canvas.width;
        exportCanvas.height = canvas.height;
        const expCtx = exportCanvas.getContext('2d');

        // White background
        expCtx.fillStyle = '#ffffff';
        expCtx.fillRect(0, 0, exportCanvas.width, exportCanvas.height);

        // Watermark subtle
        if (isGuideVisible) {
            expCtx.font = (item.type === 'kanji' ? '210px' : '240px') + " 'Noto Sans JP', sans-serif";
            expCtx.fillStyle = 'rgba(215, 200, 225, 0.35)';
            expCtx.textAlign = 'center';
            expCtx.textBaseline = 'middle';
            expCtx.fillText(item.char, exportCanvas.width / 2, exportCanvas.height / 2);
        }

        // Draw user strokes
        expCtx.drawImage(canvas, 0, 0);

        // Download link
        const link = document.createElement('a');
        link.download = `tulisan-${item.char}-${Date.now()}.png`;
        link.href = exportCanvas.toDataURL('image/png');
        link.click();
    });

    // Navigation Buttons
    btnPrevChar.addEventListener('click', () => {
        setTargetCharacter(currentTab, currentIndex - 1);
    });
    btnNextChar.addEventListener('click', () => {
        setTargetCharacter(currentTab, currentIndex + 1);
    });

    // Audio Playback
    btnAudio.addEventListener('click', () => {
        const item = datasets[currentTab][currentIndex];
        if (item) speakJapanese(item.char);
    });

    // Accuracy Evaluation System
    function checkDrawingAccuracy() {
        const item = datasets[currentTab][currentIndex];
        if (!item) return;

        const sampleSize = 100;

        // 1. Off-screen canvas for reference character
        const refCanvas = document.createElement('canvas');
        refCanvas.width = sampleSize;
        refCanvas.height = sampleSize;
        const refCtx = refCanvas.getContext('2d');

        refCtx.fillStyle = '#ffffff';
        refCtx.fillRect(0, 0, sampleSize, sampleSize);

        const fontSize = (item.type === 'kanji' ? 66 : 72);
        refCtx.font = `900 ${fontSize}px "Noto Sans JP", "Hiragino Kaku Gothic ProN", "Yu Gothic", sans-serif`;
        refCtx.textAlign = 'center';
        refCtx.textBaseline = 'middle';
        refCtx.fillStyle = '#000000';
        refCtx.fillText(item.char, sampleSize / 2, sampleSize / 2);

        const refImgData = refCtx.getImageData(0, 0, sampleSize, sampleSize).data;

        // 2. Off-screen canvas for user's drawing
        const userCanvas = document.createElement('canvas');
        userCanvas.width = sampleSize;
        userCanvas.height = sampleSize;
        const userCtx = userCanvas.getContext('2d');

        userCtx.fillStyle = '#ffffff';
        userCtx.fillRect(0, 0, sampleSize, sampleSize);
        userCtx.drawImage(canvas, 0, 0, sampleSize, sampleSize);

        const userImgData = userCtx.getImageData(0, 0, sampleSize, sampleSize).data;

        // 3. Build binary masks
        const refMask = new Uint8Array(sampleSize * sampleSize);
        const userMask = new Uint8Array(sampleSize * sampleSize);
        let totalRefPixels = 0;
        let totalUserPixels = 0;

        for (let y = 0; y < sampleSize; y++) {
            for (let x = 0; x < sampleSize; x++) {
                const idx = (y * sampleSize + x) * 4;

                // Reference ink (dark pixels on white background)
                const rR = refImgData[idx];
                const rG = refImgData[idx + 1];
                const rB = refImgData[idx + 2];
                const rLum = 0.299 * rR + 0.587 * rG + 0.114 * rB;
                if (rLum < 180) {
                    refMask[y * sampleSize + x] = 1;
                    totalRefPixels++;
                }

                // User ink (strokes drawn by user)
                const uR = userImgData[idx];
                const uG = userImgData[idx + 1];
                const uB = userImgData[idx + 2];
                const uA = userImgData[idx + 3];
                const uLum = 0.299 * uR + 0.587 * uG + 0.114 * uB;
                if (uA > 50 && uLum < 235) {
                    userMask[y * sampleSize + x] = 1;
                    totalUserPixels++;
                }
            }
        }

        // Empty canvas check
        if (totalUserPixels < 25) {
            displayAccuracyResult({
                score: 0,
                coverage: 0,
                precision: 0,
                status: 'bad',
                title: 'Kanvas Masih Kosong',
                desc: 'Silakan tulis huruf di atas kanvas terlebih dahulu sebelum memeriksa akurasi.'
            });
            return;
        }

        // 4. Reference dilation (tolerance zone for brush width and minor shifts, radius = 3px)
        const radRef = 3;
        const dilatedRef = new Uint8Array(sampleSize * sampleSize);
        for (let y = 0; y < sampleSize; y++) {
            for (let x = 0; x < sampleSize; x++) {
                if (refMask[y * sampleSize + x] === 1) {
                    for (let dy = -radRef; dy <= radRef; dy++) {
                        const ny = y + dy;
                        if (ny < 0 || ny >= sampleSize) continue;
                        for (let dx = -radRef; dx <= radRef; dx++) {
                            const nx = x + dx;
                            if (nx < 0 || nx >= sampleSize) continue;
                            if (dx * dx + dy * dy <= radRef * radRef) {
                                dilatedRef[ny * sampleSize + nx] = 1;
                            }
                        }
                    }
                }
            }
        }

        // 5. User stroke dilation (radius = 4px) to detect coverage of reference strokes
        const radUser = 4;
        const dilatedUser = new Uint8Array(sampleSize * sampleSize);
        for (let y = 0; y < sampleSize; y++) {
            for (let x = 0; x < sampleSize; x++) {
                if (userMask[y * sampleSize + x] === 1) {
                    for (let dy = -radUser; dy <= radUser; dy++) {
                        const ny = y + dy;
                        if (ny < 0 || ny >= sampleSize) continue;
                        for (let dx = -radUser; dx <= radUser; dx++) {
                            const nx = x + dx;
                            if (nx < 0 || nx >= sampleSize) continue;
                            if (dx * dx + dy * dy <= radUser * radUser) {
                                dilatedUser[ny * sampleSize + nx] = 1;
                            }
                        }
                    }
                }
            }
        }

        // 6. Compute Precision (percentage of user ink that falls inside the reference tolerance zone)
        let hitUserPixels = 0;
        for (let i = 0; i < sampleSize * sampleSize; i++) {
            if (userMask[i] === 1 && dilatedRef[i] === 1) {
                hitUserPixels++;
            }
        }
        const precision = totalUserPixels > 0 ? (hitUserPixels / totalUserPixels) : 0;

        // 7. Compute Coverage (percentage of reference glyph covered by user's strokes)
        let coveredRefPixels = 0;
        for (let i = 0; i < sampleSize * sampleSize; i++) {
            if (refMask[i] === 1 && dilatedUser[i] === 1) {
                coveredRefPixels++;
            }
        }
        const coverage = totalRefPixels > 0 ? (coveredRefPixels / totalRefPixels) : 0;

        // 8. Scribble penalty (if user filled the canvas with wild scribbles)
        let penalty = 0;
        const excessRatio = totalUserPixels / Math.max(1, totalRefPixels);
        if (excessRatio > 2.2) {
            penalty = Math.min(0.45, (excessRatio - 2.2) * 0.14);
        }

        // 9. Weighted composite score
        let rawScore = ((coverage * 0.58) + (precision * 0.42) - penalty) * 105;
        let score = Math.max(0, Math.min(100, Math.round(rawScore)));

        if (coverage < 0.25) {
            score = Math.min(score, 30);
        }

        // 10. Grade feedback
        let status = 'bad';
        let title = 'Kurang Tepat';
        let desc = 'Bentuk tulisan belum sesuai. Coba aktifkan fitur "Bayangan" dan ikuti garis panduannya.';

        if (score >= 85) {
            status = 'good';
            title = 'Sempurna! ✨';
            desc = 'Luar biasa! Goresanmu sangat rapi, proporsional, dan sangat mirip dengan bentuk karakter aslinya.';
        } else if (score >= 70) {
            status = 'good';
            title = 'Sangat Bagus!';
            desc = 'Bentuk tulisan sudah jelas terbaca dan polanya tepat. Pertahankan kerapian goresanmu!';
        } else if (score >= 50) {
            status = 'medium';
            title = 'Cukup Baik';
            desc = 'Bentuk dasar sudah terlihat, namun beberapa goresan keluar jalur atau belum lengkap.';
        }

        displayAccuracyResult({
            score: score,
            coverage: Math.round(coverage * 100),
            precision: Math.round(precision * 100),
            status: status,
            title: title,
            desc: desc
        });

        // XP reward for solid attempts
        if (score >= 70) {
            commitPractice();
        }
    }

    function displayAccuracyResult(res) {
        if (!accuracyCard) return;

        accuracyCard.style.display = 'block';

        accScoreVal.textContent = res.score + '%';
        accScoreWrap.className = 'acc-score-wrap ' + res.status;

        accStatusBadge.textContent = res.title;
        accStatusBadge.className = 'acc-status-badge ' + res.status;
        accStatusDesc.textContent = res.desc;

        accCoverageVal.textContent = res.coverage + '%';
        accCoverageFill.style.width = res.coverage + '%';

        accPrecisionVal.textContent = res.precision + '%';
        accPrecisionFill.style.width = res.precision + '%';

        // Smooth scroll to view result if not fully visible
        accuracyCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    // Accuracy Button Listeners
    if (btnCheckAccuracy) {
        btnCheckAccuracy.addEventListener('click', checkDrawingAccuracy);
    }
    if (btnAccRetry) {
        btnAccRetry.addEventListener('click', () => {
            clearCanvas();
            resetAccuracyCard();
        });
    }
    if (btnAccNext) {
        btnAccNext.addEventListener('click', () => {
            setTargetCharacter(currentTab, currentIndex + 1);
        });
    }

    // Brush Sizes
    brushButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            brushButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            brushSize = parseInt(btn.dataset.size, 10);
        });
    });

    // Colors
    colorButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            colorButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            brushColor = btn.dataset.color;
        });
    });

    // Tabs Switcher
    libTabs.forEach(tabBtn => {
        tabBtn.addEventListener('click', () => {
            libTabs.forEach(t => t.classList.remove('active'));
            tabBtn.classList.add('active');
            currentTab = tabBtn.dataset.tab;
            currentIndex = 0;
            charSearchInput.value = '';
            renderGrid();
            setTargetCharacter(currentTab, 0);
        });
    });

    // Search Input
    charSearchInput.addEventListener('input', renderGrid);

    // Keyboard Shortcuts
    document.addEventListener('keydown', (e) => {
        if (['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) return;

        if (e.key === 'a' || e.key === 'A') {
            btnAudio.click();
        } else if (e.code === 'ArrowLeft') {
            e.preventDefault();
            btnPrevChar.click();
        } else if (e.code === 'ArrowRight') {
            e.preventDefault();
            btnNextChar.click();
        } else if (e.code === 'KeyZ' && (e.ctrlKey || e.metaKey)) {
            e.preventDefault();
            undo();
        } else if (e.code === 'KeyC') {
            clearCanvas();
        }
    });

    // Init
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();
    renderGrid();
    setTargetCharacter('hiragana', 0);
})();
</script>
@endsection

