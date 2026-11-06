@extends('dashboard.layout')

@php($pageTitle = 'Flashcard: ' . $material->title)
@php($pageSubtitle = 'Uji pemanggilan memori aktif dengan Spaced Repetition System (SRS).')

@section('styles')
    .fc-player-wrap {
        max-width: 860px;
        margin: 0 auto;
    }

    /* ── Top Session Bar ── */
    .session-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }
    .session-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 10px;
        border: 1px solid var(--line);
        background: #fff;
        color: var(--ink);
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: .18s;
    }
    .session-back-btn:hover {
        border-color: var(--red);
        color: var(--red);
        background: var(--red-soft);
    }
    .session-title-group {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .session-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--ink);
    }
    .session-tools {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .tool-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 10px;
        border: 1px solid var(--line);
        background: #fff;
        color: var(--ink);
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: .18s;
        user-select: none;
    }
    .tool-btn:hover {
        background: #fdf2f4;
        border-color: var(--red);
    }
    .tool-btn.active {
        background: var(--red-soft);
        color: var(--red);
        border-color: var(--red);
    }

    /* ── Progress Indicators ── */
    .fc-progress-card {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 14px;
        padding: 14px 20px;
        margin-bottom: 22px;
        box-shadow: var(--shadow);
    }
    .fc-progress-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 800;
        flex-wrap: wrap;
        gap: 8px;
    }
    .fc-progress-counters {
        display: flex;
        align-items: center;
        gap: 14px;
        font-size: 12px;
        font-weight: 700;
    }
    .count-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 2px 8px;
        border-radius: 999px;
    }
    .count-pill.mastered {
        background: var(--green-soft);
        color: var(--green);
    }
    .count-pill.repeat {
        background: var(--gold-soft);
        color: var(--gold);
    }

    /* ── 3D Card Stage ── */
    .flashcard-stage {
        perspective: 1200px;
        margin-bottom: 24px;
        cursor: pointer;
        user-select: none;
    }
    .flashcard-3d {
        position: relative;
        width: 100%;
        min-height: 380px;
        border-radius: 24px;
        transform-style: preserve-3d;
        transition: transform 0.55s cubic-bezier(0.2, 0.85, 0.4, 1.15);
        box-shadow: 0 20px 48px -18px rgba(32,16,28,.14);
    }
    .flashcard-3d.is-flipped {
        transform: rotateY(180deg);
    }

    .fc-face {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        border-radius: 24px;
        padding: 32px 32px 28px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        border: 2px solid var(--line);
        box-sizing: border-box;
    }
    .flashcard-stage:hover .fc-face {
        border-color: rgba(196,16,46,.4);
    }

    /* Front side */
    .fc-front {
        background: linear-gradient(175deg, #ffffff 60%, #fff6f7 100%);
    }
    .fc-front-tag {
        padding: 4px 12px;
        border-radius: 999px;
        background: var(--red-soft);
        color: var(--red);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    /* SRS Box Indicator Pill */
    .fc-srs-tag {
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 10.5px;
        font-weight: 800;
        letter-spacing: .05em;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .fc-srs-tag.box-0 { background: #f1ecf4; color: #7a6e82; }
    .fc-srs-tag.box-1 { background: #ffe8cc; color: #d9480f; }
    .fc-srs-tag.box-2 { background: #fff3bf; color: #f59f00; }
    .fc-srs-tag.box-3 { background: #c3fae8; color: #0ca678; }
    .fc-srs-tag.box-4, .fc-srs-tag.box-5 { background: #d3f9d8; color: #2b8a3e; }

    .fc-front-center {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: auto;
        text-align: center;
        gap: 16px;
    }
    .fc-main-japanese {
        font-family: 'Noto Sans JP', 'Hiragino Sans', sans-serif;
        font-weight: 900;
        font-size: 78px;
        line-height: 1.15;
        color: var(--ink);
        letter-spacing: .02em;
        transition: transform .2s ease;
    }
    .fc-main-japanese.long-text {
        font-size: 38px;
    }
    .fc-audio-btn {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        border: none;
        background: var(--red-soft);
        color: var(--red);
        cursor: pointer;
        display: grid;
        place-items: center;
        transition: all .2s;
        box-shadow: 0 4px 12px -4px rgba(196,16,46,.3);
    }
    .fc-audio-btn:hover {
        transform: scale(1.12);
        background: var(--red);
        color: #fff;
    }
    .fc-audio-btn.pulse {
        animation: pulseAudio .6s ease;
    }
    @keyframes pulseAudio {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
    .fc-front-hint {
        color: var(--muted);
        font-size: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Back side */
    .fc-back {
        transform: rotateY(180deg);
        background: linear-gradient(175deg, #ffffff 60%, #f4f6fc 100%);
    }
    .fc-back-tag {
        padding: 4px 12px;
        border-radius: 999px;
        background: var(--blue-soft);
        color: var(--blue);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }
    .fc-back-center {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: auto;
        text-align: center;
        width: 100%;
        max-width: 620px;
        gap: 10px;
    }
    .fc-back-japanese-small {
        font-family: 'Noto Sans JP', sans-serif;
        font-size: 26px;
        font-weight: 800;
        color: var(--red);
        margin-bottom: 2px;
    }
    .fc-back-meaning {
        font-size: 28px;
        font-weight: 900;
        color: var(--ink);
        line-height: 1.3;
    }
    .fc-back-romaji {
        font-size: 17px;
        font-weight: 700;
        color: var(--blue);
        letter-spacing: .02em;
    }
    .fc-back-sub {
        font-size: 13.5px;
        color: var(--muted);
        font-weight: 600;
        margin-top: 4px;
        padding: 6px 14px;
        border-radius: 10px;
        background: rgba(0,0,0,.03);
    }
    .fc-back-extra {
        margin-top: 10px;
        padding: 10px 16px;
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 12px;
        font-size: 13px;
        color: #443540;
        line-height: 1.5;
        width: 100%;
    }
    .fc-back-hint {
        color: var(--muted);
        font-size: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* ── Action Buttons Below Card ── */
    .fc-actions {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        gap: 14px;
        align-items: center;
    }
    .btn-fc {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 14px 22px;
        border-radius: 14px;
        font-size: 14.5px;
        font-weight: 800;
        cursor: pointer;
        transition: all .18s ease;
        border: 1px solid transparent;
        user-select: none;
    }
    .btn-fc-repeat {
        background: #fff;
        border-color: #f0c3cb;
        color: #c4102e;
        box-shadow: 0 4px 14px -6px rgba(196,16,46,.18);
    }
    .btn-fc-repeat:hover {
        background: #fdf2f4;
        border-color: #c4102e;
        transform: translateY(-1px);
    }
    .btn-fc-flip {
        background: #fff;
        border-color: var(--line);
        color: var(--ink);
        box-shadow: 0 4px 14px -6px rgba(0,0,0,.08);
        padding: 14px 28px;
    }
    .btn-fc-flip:hover {
        background: #f9f8fa;
        border-color: #a89bb0;
    }
    .btn-fc-master {
        background: linear-gradient(135deg, #1b7340, #135c32);
        color: #fff;
        box-shadow: 0 6px 18px -6px rgba(27,115,64,.4);
    }
    .btn-fc-master:hover {
        background: linear-gradient(135deg, #20874b, #176d3c);
        transform: translateY(-1px);
        box-shadow: 0 8px 22px -6px rgba(27,115,64,.5);
    }

    /* ── Keyboard shortcut footer ── */
    .fc-keyboard-tip {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        margin-top: 22px;
        font-size: 11.5px;
        color: var(--muted);
        flex-wrap: wrap;
    }
    .fc-keyboard-tip kbd {
        padding: 2px 6px;
        border-radius: 4px;
        background: #ede9f0;
        border: 1px solid #d3cad7;
        font-family: monospace;
        font-weight: 700;
        color: var(--ink);
    }

    /* ── Completion Modal / Screen ── */
    .fc-completed-card {
        display: none;
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 24px;
        padding: 44px 36px;
        text-align: center;
        box-shadow: 0 24px 50px -20px rgba(184,20,53,.2);
        animation: scaleUp .35s cubic-bezier(0.2, 0.85, 0.4, 1.15);
    }
    @keyframes scaleUp {
        from { opacity: 0; transform: scale(.94); }
        to { opacity: 1; transform: scale(1); }
    }
    .fc-completed-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        border-radius: 50%;
        background: var(--green-soft);
        color: var(--green);
        display: grid;
        place-items: center;
        box-shadow: 0 10px 24px -8px rgba(31,91,69,.35);
    }
    .fc-completed-title {
        font-size: 26px;
        font-weight: 900;
        color: var(--ink);
        margin-bottom: 8px;
    }
    .fc-completed-desc {
        color: var(--muted);
        font-size: 14px;
        max-width: 520px;
        margin: 0 auto 28px;
        line-height: 1.6;
    }
    .fc-stats-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        max-width: 540px;
        margin: 0 auto 32px;
    }
    .fc-summary-stat {
        padding: 16px;
        border-radius: 16px;
        background: #fff;
        border: 1px solid var(--line);
    }
    .fc-summary-stat strong {
        display: block;
        font-size: 24px;
        font-weight: 900;
        color: var(--ink);
        margin-bottom: 4px;
    }
    .fc-summary-stat span {
        font-size: 11.5px;
        color: var(--muted);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
    }
    .fc-completed-actions {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    @media (max-width: 640px) {
        .flashcard-3d { min-height: 340px; }
        .fc-main-japanese { font-size: 60px; }
        .fc-main-japanese.long-text { font-size: 30px; }
        .fc-actions { grid-template-columns: 1fr; }
        .btn-fc-flip { order: -1; }
        .fc-stats-grid { grid-template-columns: 1fr; }
    }
@endsection

@section('content')
<div class="fc-player-wrap">
    <!-- Session Header Navigation -->
    <div class="session-bar">
        <div class="session-title-group">
            <a href="{{ route('flashcards.index') }}" class="session-back-btn" title="Kembali ke daftar deck">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Deck
            </a>
            <span class="session-title">{{ $material->title }}</span>
            <span class="badge blue">{{ strtoupper($material->level) }}</span>
            @if(($filterMode ?? 'all') === 'due')
                <span class="badge" style="display:inline-flex; align-items:center; gap:5px; background:#ffe8cc; color:#d9480f;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                    </svg>
                    Kartu Jatuh Tempo
                </span>
            @elseif(($filterMode ?? 'all') === 'daily_review')
                <span class="badge" style="display:inline-flex; align-items:center; gap:5px; background:#e6fcf5; color:#0ca678;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    Review Harian
                </span>
            @endif
        </div>

        <div class="session-tools">
            <button type="button" class="tool-btn" id="toggleShuffleBtn" title="Acak urutan kartu">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="16 3 21 3 21 8"></polyline>
                    <line x1="4" y1="20" x2="21" y2="3"></line>
                    <polyline points="21 16 21 21 16 21"></polyline>
                    <line x1="15" y1="15" x2="21" y2="21"></line>
                    <line x1="4" y1="4" x2="9" y2="9"></line>
                </svg>
                <span id="shuffleLabel">Acak</span>
            </button>

            <button type="button" class="tool-btn active" id="toggleAudioAutoBtn" title="Otomatis putar suara saat buka kartu">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                </svg>
                <span>Auto-Audio</span>
            </button>
        </div>
    </div>

    @if(empty($cards))
        <!-- Empty State (e.g. When no cards are due) -->
        <div class="fc-completed-card" style="display: block;">
            <div class="fc-completed-icon" style="background: #e6fcf5; color: #0ca678;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <h2 class="fc-completed-title">Tidak Ada Kartu Jatuh Tempo!</h2>
            <p class="fc-completed-desc">Semua kartu dalam kategori ini sudah kamu pelajari dan belum memasuki jadwal review Spaced Repetition berikutnya. Bagus sekali!</p>

            <div class="fc-completed-actions">
                @if(isset($material->slug) && $material->slug !== 'review-harian')
                    <a href="{{ route('flashcards.show', $material->slug) }}" class="button">
                        Pelajari Semua Kartu
                    </a>
                @endif
                <a href="{{ route('flashcards.index') }}" class="button ghost">
                    Kembali ke Daftar Deck
                </a>
            </div>
        </div>
    @else
        <!-- Active Flashcard Study Area -->
        <div id="studyArea">
            <!-- Progress Bar -->
            <div class="fc-progress-card">
                <div class="fc-progress-header">
                    <span id="cardCounterText">Kartu 1 dari {{ count($cards) }}</span>
                    <div class="fc-progress-counters">
                        <span class="count-pill mastered" title="Sudah dihafal dalam sesi ini">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span id="masteredCount">0</span> Hafal
                        </span>
                        <span class="count-pill repeat" title="Perlu diulang dalam antrean">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="23 4 23 10 17 10"></polyline>
                                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                            </svg>
                            <span id="repeatCount">0</span> Ulang
                        </span>
                    </div>
                </div>
                <div class="progress-track">
                    <div class="progress-fill" id="sessionProgressFill" style="width: 0%"></div>
                </div>
            </div>

            <!-- 3D Card Stage -->
            <div class="flashcard-stage" id="cardStage" title="Klik untuk membalik kartu (Spasi)">
                <div class="flashcard-3d" id="card3d">
                    <!-- Front Side -->
                    <div class="fc-face fc-front">
                        <div style="display:flex; justify-content:space-between; width:100%; align-items:center;">
                            <span class="fc-front-tag" id="cardCategoryFront">Kategori</span>
                            <span class="fc-srs-tag box-0" id="cardSrsFront">Baru</span>
                        </div>

                        <div class="fc-front-center">
                            <div class="fc-main-japanese" id="cardJapaneseFront">...</div>
                            <button type="button" class="fc-audio-btn" id="audioBtnFront" title="Dengarkan pelafalan (Tekan A)">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="fc-front-hint">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="23 4 23 10 17 10"></polyline>
                                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                            </svg>
                            Klik kartu atau tekan [Spasi] untuk melihat arti & cara baca
                        </div>
                    </div>

                    <!-- Back Side -->
                    <div class="fc-face fc-back">
                        <div style="display:flex; justify-content:space-between; width:100%; align-items:center;">
                            <div style="display:flex; gap:8px; align-items:center;">
                                <span class="fc-back-tag" id="cardCategoryBack">Jawaban</span>
                                <span class="fc-srs-tag box-0" id="cardSrsBack">Baru</span>
                            </div>
                            <button type="button" class="fc-audio-btn" id="audioBtnBack" title="Dengarkan pelafalan (Tekan A)" style="width:36px;height:36px">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="fc-back-center">
                            <div class="fc-back-japanese-small" id="cardJapaneseBack">...</div>
                            <div class="fc-back-meaning" id="cardMeaningBack">...</div>
                            <div class="fc-back-romaji" id="cardRomajiBack">...</div>
                            <div class="fc-back-sub" id="cardSubBack">...</div>
                            <div class="fc-back-extra" id="cardExtraBack" style="display:none;"></div>
                        </div>

                        <div class="fc-back-hint">
                            Tentukan apakah kamu sudah hafal atau masih perlu diulang
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Controls -->
            <div class="fc-actions">
                <button type="button" class="btn-fc btn-fc-repeat" id="btnRepeat" title="Belum hafal (Panah Kiri / 1)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Belum Hafal (←)
                </button>

                <button type="button" class="btn-fc btn-fc-flip" id="btnFlip" title="Balik kartu (Spasi)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="17 1 21 5 17 9"></polyline>
                        <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                        <polyline points="7 23 3 19 7 15"></polyline>
                        <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                    </svg>
                    Balik Kartu (Spasi)
                </button>

                <button type="button" class="btn-fc btn-fc-master" id="btnMaster" title="Sudah hafal (Panah Kanan / 2)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Sudah Hafal (→)
                </button>
            </div>

            <!-- Keyboard Guide -->
            <div class="fc-keyboard-tip">
                <span>Pintasan: <kbd>Spasi</kbd> Balik</span>
                <span><kbd>←</kbd> atau <kbd>1</kbd> Belum Hafal</span>
                <span><kbd>→</kbd> atau <kbd>2</kbd> Sudah Hafal</span>
                <span><kbd>A</kbd> Putar Suara</span>
            </div>
        </div>

        <!-- Completed Screen -->
        <div class="fc-completed-card" id="completedCard">
            <div class="fc-completed-icon">
                <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>

            <h2 class="fc-completed-title">おめでとうございます! (Selamat!)</h2>
            <p class="fc-completed-desc">Kamu telah menyelesaikan seluruh kartu dalam sesi ini! Progres Spaced Repetition berhasil diperbarui ke profilmu.</p>

            <div class="fc-stats-grid">
                <div class="fc-summary-stat">
                    <strong id="statTotalStudied">{{ count($cards) }}</strong>
                    <span>Total Kartu</span>
                </div>
                <div class="fc-summary-stat">
                    <strong id="statMasteredCount" style="color:var(--green)">0</strong>
                    <span>Langsung Hafal</span>
                </div>
                <div class="fc-summary-stat">
                    <strong id="statRepeatCount" style="color:var(--gold)">0</strong>
                    <span>Sempat Diulang</span>
                </div>
            </div>

            <div class="fc-completed-actions">
                <button type="button" class="button" id="btnRestartAll">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="1 4 1 10 7 10"></polyline>
                        <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                    </svg>
                    Ulangi Semua Kartu
                </button>

                <button type="button" class="button ghost" id="btnRestartRepeats" style="display:none;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 4 23 10 17 10"></polyline>
                        <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                    </svg>
                    Ulangi Yang Belum Hafal (<span id="repeatActionCount">0</span>)
                </button>

                @if(isset($material->slug) && $material->slug !== 'review-harian')
                    <a href="{{ route('material.show', $material->slug) }}" class="button ghost">
                        Lihat Materi Lengkap
                    </a>
                @endif

                <a href="{{ route('flashcards.index') }}" class="button ghost">
                    Pilih Deck Lain
                </a>
            </div>
        </div>

        <script>
        (function() {
            // Raw card data from backend
            const rawCards = @json($cards);

            if (!rawCards || rawCards.length === 0) {
                return;
            }

            // State
            let queue = [...rawCards];
            let totalInitial = rawCards.length;
            let completedCount = 0;
            let masteredDirectlyCount = 0;
            let repeatedIds = new Set();
            let currentCardIndex = 0;
            let isFlipped = false;
            let isAutoAudio = true;
            let isShuffled = false;

            // Elements
            const studyArea = document.getElementById('studyArea');
            const completedCard = document.getElementById('completedCard');
            const cardStage = document.getElementById('cardStage');
            const card3d = document.getElementById('card3d');

            const cardCategoryFront = document.getElementById('cardCategoryFront');
            const cardSrsFront = document.getElementById('cardSrsFront');
            const cardJapaneseFront = document.getElementById('cardJapaneseFront');
            const cardCategoryBack = document.getElementById('cardCategoryBack');
            const cardSrsBack = document.getElementById('cardSrsBack');
            const cardJapaneseBack = document.getElementById('cardJapaneseBack');
            const cardMeaningBack = document.getElementById('cardMeaningBack');
            const cardRomajiBack = document.getElementById('cardRomajiBack');
            const cardSubBack = document.getElementById('cardSubBack');
            const cardExtraBack = document.getElementById('cardExtraBack');

            const audioBtnFront = document.getElementById('audioBtnFront');
            const audioBtnBack = document.getElementById('audioBtnBack');

            const cardCounterText = document.getElementById('cardCounterText');
            const masteredCountElem = document.getElementById('masteredCount');
            const repeatCountElem = document.getElementById('repeatCount');
            const progressFill = document.getElementById('sessionProgressFill');

            const btnRepeat = document.getElementById('btnRepeat');
            const btnFlip = document.getElementById('btnFlip');
            const btnMaster = document.getElementById('btnMaster');

            const toggleShuffleBtn = document.getElementById('toggleShuffleBtn');
            const toggleAudioAutoBtn = document.getElementById('toggleAudioAutoBtn');
            const shuffleLabel = document.getElementById('shuffleLabel');

            const btnRestartAll = document.getElementById('btnRestartAll');
            const btnRestartRepeats = document.getElementById('btnRestartRepeats');
            const repeatActionCount = document.getElementById('repeatActionCount');
            const statTotalStudied = document.getElementById('statTotalStudied');
            const statMasteredCount = document.getElementById('statMasteredCount');
            const statRepeatCount = document.getElementById('statRepeatCount');

            // Web Speech API for Native Japanese Voice
            function speakJapanese(text) {
                if (!('speechSynthesis' in window)) return;
                try {
                    window.speechSynthesis.cancel();
                    const utterance = new SpeechSynthesisUtterance(text);
                    utterance.lang = 'ja-JP';
                    utterance.rate = 0.85;

                    // Trigger visual pulse on buttons
                    [audioBtnFront, audioBtnBack].forEach(b => {
                        if (b) {
                            b.classList.remove('pulse');
                            void b.offsetWidth;
                            b.classList.add('pulse');
                        }
                    });

                    window.speechSynthesis.speak(utterance);
                } catch (e) {
                    console.warn('Speech synthesis error:', e);
                }
            }

            // Save SRS progress to database
            function saveCardSrs(card, result) {
                if (!card.card_key || !card.material_id) return;
                fetch("{{ route('flashcards.save-card') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        material_id: card.material_id,
                        card_key: card.card_key,
                        result: result
                    })
                }).catch(err => console.warn('SRS save error:', err));
            }

            // Shuffle Array
            function shuffle(array) {
                const arr = [...array];
                for (let i = arr.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    [arr[i], arr[j]] = [arr[j], arr[i]];
                }
                return arr;
            }

            // Render Current Card
            function renderCard() {
                if (queue.length === 0) {
                    showCompletion();
                    return;
                }

                const card = queue[0];
                isFlipped = false;
                card3d.classList.remove('is-flipped');

                // Front
                cardCategoryFront.textContent = card.category || 'Materi';
                cardJapaneseFront.textContent = card.front;
                if (card.front && card.front.length > 5) {
                    cardJapaneseFront.classList.add('long-text');
                } else {
                    cardJapaneseFront.classList.remove('long-text');
                }

                // SRS Badge
                const box = card.box || 0;
                const statusLabel = card.status_label || (box === 0 ? 'Baru' : 'Box ' + box);
                [cardSrsFront, cardSrsBack].forEach(el => {
                    if (el) {
                        el.textContent = statusLabel;
                        el.className = 'fc-srs-tag box-' + box;
                    }
                });

                // Back
                cardCategoryBack.textContent = card.category || 'Jawaban';
                cardJapaneseBack.textContent = card.front;
                cardMeaningBack.textContent = card.back_main;
                cardRomajiBack.textContent = card.back_sub || '';
                
                if (card.type === 'kanji') {
                    cardSubBack.textContent = card.back_sub;
                    cardSubBack.style.display = 'block';
                } else {
                    cardSubBack.style.display = 'none';
                }

                if (card.extra) {
                    cardExtraBack.innerHTML = '<strong>Contoh Penggunaan:</strong> ' + escapeHtml(card.extra);
                    cardExtraBack.style.display = 'block';
                } else {
                    cardExtraBack.style.display = 'none';
                }

                // Update progress counters
                const remainingInDeck = queue.length;
                const currentProgressPct = Math.round((completedCount / totalInitial) * 100);
                cardCounterText.textContent = `Sisa Antrean: ${remainingInDeck} kartu (Total: ${totalInitial})`;
                masteredCountElem.textContent = masteredDirectlyCount;
                repeatCountElem.textContent = repeatedIds.size;
                progressFill.style.width = Math.min(100, currentProgressPct) + '%';

                // Auto audio play
                if (isAutoAudio && card.audio) {
                    setTimeout(() => {
                        speakJapanese(card.audio);
                    }, 250);
                }
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // Flip Card Action
            function flipCard() {
                isFlipped = !isFlipped;
                card3d.classList.toggle('is-flipped', isFlipped);
            }

            // Mastered Action (Known)
            function onMastered() {
                if (queue.length === 0) return;
                const card = queue.shift();
                completedCount++;

                // Save to SRS backend
                saveCardSrs(card, 'mastered');

                if (!repeatedIds.has(card.id)) {
                    masteredDirectlyCount++;
                }

                renderCard();
            }

            // Repeat Action (Not yet known -> move to back of queue)
            function onRepeat() {
                if (queue.length === 0) return;
                const card = queue.shift();
                repeatedIds.add(card.id);

                // Save to SRS backend
                saveCardSrs(card, 'repeat');

                // Put at end of queue
                queue.push(card);

                renderCard();
            }

            // Completion View
            function showCompletion() {
                studyArea.style.display = 'none';
                completedCard.style.display = 'block';

                if (typeof window.reportActivity === 'function') {
                    window.reportActivity('flashcard_session');
                }

                statTotalStudied.textContent = totalInitial;
                statMasteredCount.textContent = masteredDirectlyCount;
                statRepeatCount.textContent = repeatedIds.size;

                if (repeatedIds.size > 0) {
                    repeatActionCount.textContent = repeatedIds.size;
                    btnRestartRepeats.style.display = 'inline-flex';
                } else {
                    btnRestartRepeats.style.display = 'none';
                }
            }

            // Restart Session with All Cards
            function restartAll() {
                queue = isShuffled ? shuffle(rawCards) : [...rawCards];
                totalInitial = rawCards.length;
                completedCount = 0;
                masteredDirectlyCount = 0;
                repeatedIds.clear();

                completedCard.style.display = 'none';
                studyArea.style.display = 'block';
                renderCard();
            }

            // Restart Session with only cards that were repeated
            function restartRepeats() {
                const repeatCards = rawCards.filter(c => repeatedIds.has(c.id));
                if (repeatCards.length === 0) return;

                queue = isShuffled ? shuffle(repeatCards) : [...repeatCards];
                totalInitial = repeatCards.length;
                completedCount = 0;
                masteredDirectlyCount = 0;
                repeatedIds.clear();

                completedCard.style.display = 'none';
                studyArea.style.display = 'block';
                renderCard();
            }

            // Event Listeners
            if (cardStage) {
                cardStage.addEventListener('click', (e) => {
                    if (e.target.closest('.fc-audio-btn')) return;
                    flipCard();
                });
            }

            if (btnFlip) btnFlip.addEventListener('click', flipCard);
            if (btnMaster) btnMaster.addEventListener('click', onMastered);
            if (btnRepeat) btnRepeat.addEventListener('click', onRepeat);

            if (audioBtnFront) {
                audioBtnFront.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (queue.length > 0) speakJapanese(queue[0].audio);
                });
            }

            if (audioBtnBack) {
                audioBtnBack.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (queue.length > 0) speakJapanese(queue[0].audio);
                });
            }

            // Toggle Shuffle
            if (toggleShuffleBtn) {
                toggleShuffleBtn.addEventListener('click', () => {
                    isShuffled = !isShuffled;
                    toggleShuffleBtn.classList.toggle('active', isShuffled);
                    if (isShuffled) {
                        queue = shuffle(queue);
                        shuffleLabel.textContent = 'Diacak';
                    } else {
                        shuffleLabel.textContent = 'Acak';
                    }
                    renderCard();
                });
            }

            // Toggle Auto Audio
            if (toggleAudioAutoBtn) {
                toggleAudioAutoBtn.addEventListener('click', () => {
                    isAutoAudio = !isAutoAudio;
                    toggleAudioAutoBtn.classList.toggle('active', isAutoAudio);
                });
            }

            // End of session buttons
            if (btnRestartAll) btnRestartAll.addEventListener('click', restartAll);
            if (btnRestartRepeats) btnRestartRepeats.addEventListener('click', restartRepeats);

            // Keyboard Shortcuts
            document.addEventListener('keydown', (e) => {
                if (['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) return;

                if (e.code === 'Space') {
                    e.preventDefault();
                    flipCard();
                } else if (e.code === 'ArrowLeft' || e.key === '1') {
                    e.preventDefault();
                    onRepeat();
                } else if (e.code === 'ArrowRight' || e.key === '2') {
                    e.preventDefault();
                    onMastered();
                } else if (e.key === 'a' || e.key === 'A') {
                    e.preventDefault();
                    if (queue.length > 0) speakJapanese(queue[0].audio);
                } else if (e.key === 'r' || e.key === 'R') {
                    e.preventDefault();
                    if (toggleShuffleBtn) toggleShuffleBtn.click();
                }
            });

            // Start
            renderCard();
        })();
        </script>
    @endif
</div>
@endsection
