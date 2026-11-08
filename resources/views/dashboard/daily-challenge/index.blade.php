@extends('dashboard.layout')

@php
    $pageTitle = 'Tantangan Harian (Daily Challenge)';
    $pageSubtitle = 'Selesaikan 3 misi kilat setiap hari untuk mengasah ingatan dan menjaga api streak belajarmu.';
@endphp

@section('styles')
    /* ── Daily Challenge Arena Styles ── */
    .challenge-wrap {
        max-width: 860px;
        margin: 0 auto;
    }

    /* ── Hero Banner ── */
    .challenge-hero {
        background: linear-gradient(135deg, #181926 0%, #2a1b24 100%);
        border-radius: 22px;
        padding: 28px 32px;
        color: #fff;
        margin-bottom: 22px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 16px 36px -12px rgba(24, 25, 38, .35);
    }
    .challenge-hero::after {
        content: '日課';
        position: absolute;
        right: 18px;
        bottom: -22px;
        font-family: 'Noto Sans JP', sans-serif;
        font-size: 110px;
        font-weight: 900;
        color: rgba(255, 255, 255, .035);
        pointer-events: none;
        line-height: 1;
        user-select: none;
    }
    .hero-top-badges {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }
    .badge-hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .12);
        color: #ffd8a8;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .06em;
        text-transform: uppercase;
    }
    .badge-hero-flame {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 999px;
        background: rgba(247, 103, 7, .2);
        border: 1px solid rgba(247, 103, 7, .4);
        color: #ff922b;
        font-size: 11px;
        font-weight: 800;
    }
    .challenge-hero h2 {
        font-size: 23px;
        font-weight: 900;
        margin: 0 0 6px;
        line-height: 1.3;
    }
    .challenge-hero p {
        color: rgba(255, 255, 255, .75);
        font-size: 13.5px;
        line-height: 1.55;
        margin: 0;
        max-width: 600px;
    }

    /* ── Meta Tracker Bar ── */
    .challenge-meta-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        background: #fff;
        padding: 12px 20px;
        border-radius: 16px;
        border: 1px solid var(--line);
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .02);
    }
    .meta-theme-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .theme-icon-box {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: #fde8ec;
        color: var(--red);
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }
    .theme-texts strong {
        display: block;
        font-size: 13.5px;
        color: var(--ink);
        font-weight: 800;
    }
    .theme-texts span {
        font-size: 11.5px;
        color: var(--muted);
    }
    .meta-countdown {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        border-radius: 10px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        font-size: 12px;
        color: var(--ink);
        font-weight: 700;
    }

    /* ── Stepper Navigation ── */
    .stepper-nav {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 22px;
    }
    .step-pill {
        padding: 12px 16px;
        border-radius: 14px;
        background: #fff;
        border: 1.5px solid var(--line);
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all .2s ease;
    }
    .step-pill.active {
        border-color: var(--red);
        background: #fff8f6;
        box-shadow: 0 4px 14px -4px rgba(196, 16, 46, .25);
    }
    .step-pill.completed {
        border-color: #20c997;
        background: #f4fcf8;
    }
    .step-number-circle {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #f1eae6;
        color: var(--muted);
        font-size: 11px;
        font-weight: 800;
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }
    .step-pill.active .step-number-circle {
        background: var(--red);
        color: #fff;
    }
    .step-pill.completed .step-number-circle {
        background: #20c997;
        color: #fff;
    }
    .step-pill-texts {
        overflow: hidden;
    }
    .step-pill-label {
        font-size: 10.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: var(--muted);
        display: block;
    }
    .step-pill-title {
        font-size: 12.5px;
        font-weight: 800;
        color: var(--ink);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }

    /* ── Main Challenge Card ── */
    .challenge-card {
        background: #fff;
        border-radius: 22px;
        border: 1px solid var(--line);
        padding: 30px 32px;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, .04);
        position: relative;
    }

    /* Target Prompt Box */
    .mission-prompt-box {
        text-align: center;
        padding: 26px 20px 22px;
        border-radius: 18px;
        background: #faf7f5;
        border: 1px solid var(--line);
        margin-bottom: 24px;
        position: relative;
    }
    .mission-badge-type {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 999px;
        background: #fff;
        border: 1px solid var(--line);
        color: var(--red);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 12px;
    }
    .mission-prompt-text {
        font-size: 14px;
        color: var(--muted);
        font-weight: 600;
        margin-bottom: 10px;
    }
    .mission-target-word {
        font-size: 32px;
        font-weight: 900;
        color: var(--ink);
        font-family: 'Noto Sans JP', sans-serif;
        line-height: 1.3;
        margin: 0 0 6px;
    }
    .mission-target-romaji {
        font-size: 13px;
        color: var(--muted);
        font-weight: 600;
    }
    .btn-sound-small {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 999px;
        background: #fff;
        border: 1px solid var(--line);
        color: var(--ink);
        font-size: 11.5px;
        font-weight: 700;
        cursor: pointer;
        margin-top: 10px;
        transition: all .15s ease;
    }
    .btn-sound-small:hover {
        background: #f1eae6;
        border-color: var(--red);
        color: var(--red);
    }

    /* Options Grid */
    .options-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 24px;
    }
    .option-card-btn {
        background: #fff;
        border: 2px solid #e9ecef;
        border-radius: 14px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        cursor: pointer;
        transition: all .18s ease;
        text-align: left;
        width: 100%;
        color: var(--ink);
        position: relative;
    }
    .option-card-btn:hover:not(:disabled) {
        border-color: var(--red);
        background: #fff8f6;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(196, 16, 46, .1);
    }
    .option-letter {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: #f1eae6;
        color: var(--ink);
        font-size: 13px;
        font-weight: 900;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        transition: all .15s ease;
    }
    .option-card-btn:hover:not(:disabled) .option-letter {
        background: var(--red);
        color: #fff;
    }
    .option-text {
        font-size: 14.5px;
        font-weight: 700;
        flex: 1;
        line-height: 1.4;
    }
    .option-status-icon {
        width: 22px;
        height: 22px;
        flex-shrink: 0;
        display: none;
    }

    /* Option Selected States */
    .option-card-btn.correct {
        border-color: #20c997 !important;
        background: #e6fcf5 !important;
        color: #087f5b !important;
    }
    .option-card-btn.correct .option-letter {
        background: #20c997 !important;
        color: #fff !important;
    }
    .option-card-btn.correct .option-status-icon.correct {
        display: block;
        color: #20c997;
    }

    .option-card-btn.incorrect {
        border-color: #fa5252 !important;
        background: #fff5f5 !important;
        color: #c92a2a !important;
    }
    .option-card-btn.incorrect .option-letter {
        background: #fa5252 !important;
        color: #fff !important;
    }
    .option-card-btn.incorrect .option-status-icon.incorrect {
        display: block;
        color: #fa5252;
    }

    .option-card-btn:disabled {
        cursor: default;
        transform: none !important;
    }

    /* Explanation Banner */
    .explanation-box {
        padding: 16px 20px;
        border-radius: 14px;
        background: #faf7f5;
        border: 1px solid var(--line);
        margin-bottom: 22px;
        display: none;
        animation: fadeInSlide .25s ease-out;
    }
    @keyframes fadeInSlide {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .explanation-head {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13.5px;
        font-weight: 800;
        margin-bottom: 6px;
    }
    .explanation-box.correct {
        background: #e6fcf5;
        border-color: #b2f2bb;
        color: #087f5b;
    }
    .explanation-box.incorrect {
        background: #fff5f5;
        border-color: #ffc9c9;
        color: #c92a2a;
    }
    .explanation-body {
        font-size: 13px;
        line-height: 1.55;
        color: #495057;
        margin: 0;
    }

    /* Actions Footer */
    .challenge-actions-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        padding-top: 16px;
        border-top: 1px solid var(--line);
    }
    .btn-action-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--red) 0%, var(--red-2, #e8234f) 100%);
        border: 1px solid var(--red);
        color: #fff !important;
        font-size: 13.5px;
        font-weight: 800;
        cursor: pointer;
        transition: all .18s ease;
        box-shadow: 0 4px 14px -2px rgba(196, 16, 46, .4);
    }
    .btn-action-primary:hover:not(:disabled) {
        background: linear-gradient(135deg, var(--red-hover, #a00d25) 0%, var(--red) 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 18px -2px rgba(196, 16, 46, .5);
    }
    .btn-action-primary:disabled {
        background: #f1eae6 !important;
        color: #a89a94 !important;
        border-color: #e0d5cf !important;
        box-shadow: none !important;
        cursor: not-allowed;
    }
    .btn-action-primary svg { stroke: currentColor; }

    /* ── Completed All Screen ── */
    .challenge-completed-screen {
        text-align: center;
        padding: 40px 24px;
        display: none;
    }
    .trophy-glow-badge {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ffa94d, #f76707);
        display: grid;
        place-items: center;
        color: #fff;
        margin: 0 auto 20px;
        box-shadow: 0 12px 28px -6px rgba(247, 103, 7, .5);
    }
    .challenge-completed-screen h3 {
        font-size: 24px;
        font-weight: 900;
        color: var(--ink);
        margin: 0 0 8px;
    }
    .challenge-completed-screen p {
        font-size: 14px;
        color: var(--muted);
        max-width: 520px;
        margin: 0 auto 26px;
        line-height: 1.6;
    }
    .summary-stats-deck {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        max-width: 480px;
        margin: 0 auto 30px;
    }
    .summary-stat-cell {
        padding: 16px 12px;
        border-radius: 14px;
        background: #faf7f5;
        border: 1px solid var(--line);
    }
    .summary-stat-cell strong {
        display: block;
        font-size: 20px;
        font-weight: 900;
        color: var(--ink);
        margin-bottom: 3px;
    }
    .summary-stat-cell span {
        font-size: 11px;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    @media (max-width: 680px) {
        .options-grid { grid-template-columns: 1fr; }
        .stepper-nav { grid-template-columns: 1fr; }
        .challenge-hero { padding: 22px 20px; }
        .challenge-card { padding: 22px 18px; }
        .summary-stats-deck { grid-template-columns: 1fr; }
    }
@endsection

@section('content')
<div class="content-body" style="padding-bottom: 36px;">
    <div class="challenge-wrap">
        <!-- 1. Hero Banner -->
        <div class="challenge-hero">
            <div class="hero-top-badges">
                <span class="badge-hero-pill">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"></circle>
                        <circle cx="12" cy="12" r="5"></circle>
                    </svg>
                    Tantangan Harian
                </span>
                <span class="badge-hero-flame">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2s5 4.5 5 10a5 5 0 0 1-10 0c0-2 1-3.5 2-4.5.3 1.6 1.2 2.4 2 2.5C11 7 10 4 12 2z"/>
                    </svg>
                    <span id="streakHeroBadge">Streak: {{ $gami['streak'] ?? 0 }} Hari</span>
                </span>
            </div>
            <h2>Tantangan Harian &bull; {{ $challengeData['date_formatted'] }}</h2>
            <p>
                Asah ingatanmu dalam 2 menit! Selesaikan 3 misi kilat hari ini untuk mengklaim <strong>+30 XP Bonus</strong> dan mengamankan api streak belajarmu.
            </p>
        </div>

        <!-- 2. Meta Tracker Bar -->
        <div class="challenge-meta-bar">
            <div class="meta-theme-info">
                <div class="theme-icon-box">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </div>
                <div class="theme-texts">
                    <strong>Fokus Hari Ini: {{ $challengeData['theme'] }}</strong>
                    <span>{{ $challengeData['subtitle'] }}</span>
                </div>
            </div>

            <div class="meta-countdown" id="countdownBox" title="Tantangan baru akan reset pada 00:00 WIB">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span id="countdownTimer">Reset: Menghitung...</span>
            </div>
        </div>

        <!-- 3. Stepper Navigation -->
        <div class="stepper-nav" id="stepperNav">
            <div class="step-pill active" id="stepPill_0">
                <div class="step-number-circle">1</div>
                <div class="step-pill-texts">
                    <span class="step-pill-label">Misi 1</span>
                    <span class="step-pill-title">Kosakata N5</span>
                </div>
            </div>
            <div class="step-pill" id="stepPill_1">
                <div class="step-number-circle">2</div>
                <div class="step-pill-texts">
                    <span class="step-pill-label">Misi 2</span>
                    <span class="step-pill-title">Kanji N5</span>
                </div>
            </div>
            <div class="step-pill" id="stepPill_2">
                <div class="step-number-circle">3</div>
                <div class="step-pill-texts">
                    <span class="step-pill-label">Misi 3</span>
                    <span class="step-pill-title">Tata Bahasa</span>
                </div>
            </div>
        </div>

        <!-- 4. Main Challenge Card Arena -->
        <div class="challenge-card">
            <!-- Active Mission View -->
            <div id="missionActiveView">
                <div class="mission-prompt-box">
                    <span class="mission-badge-type" id="missionBadge">Misi 1 · Kosakata</span>
                    <div class="mission-prompt-text" id="missionPrompt">Pilihlah arti kata berikut:</div>
                    <h3 class="mission-target-word" id="missionTargetWord">図書館</h3>
                    <div class="mission-target-romaji" id="missionRomaji">Toshokan</div>
                    <button type="button" class="btn-sound-small" id="btnAudioPrompt" title="Dengarkan pengucapan audio">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                            <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                        </svg>
                        <span>Dengarkan</span>
                    </button>
                </div>

                <!-- 4 Options Grid -->
                <div class="options-grid" id="optionsGrid">
                    <!-- Injected dynamically -->
                </div>

                <!-- Explanation Box -->
                <div class="explanation-box" id="explanationBox">
                    <div class="explanation-head" id="explanationHead">
                        <!-- Injected -->
                    </div>
                    <p class="explanation-body" id="explanationBody"></p>
                </div>

                <!-- Actions Footer -->
                <div class="challenge-actions-row">
                    <div style="font-size: 13px; color: var(--muted); font-weight: 700;">
                        Progres: <strong style="color:var(--ink);" id="missionCounterText">Misi 1 dari 3</strong>
                    </div>
                    <button type="button" class="btn-action-primary" id="btnNextMission" disabled>
                        <span id="btnNextMissionLabel">Lanjut ke Misi 2</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Completed All View -->
            <div class="challenge-completed-screen" id="missionCompletedView">
                <div class="trophy-glow-badge">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                        <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                        <path d="M4 22h16"></path>
                        <path d="M10 14.66V17c0 .55-.45 1-1 1H7"></path>
                        <path d="M14 14.66V17c0 .55.45 1 1 1h2"></path>
                        <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
                    </svg>
                </div>
                <h3>Tantangan Hari Ini Tuntas!</h3>
                <p id="completedHeroDesc">
                    Kerja bagus! Kamu telah menyelesaikan ketiga misi harian hari ini, mengklaim <strong>+30 XP Bonus</strong>, dan menjaga api streak belajarmu tetap menyala.
                </p>

                <div class="summary-stats-deck">
                    <div class="summary-stat-cell">
                        <strong style="color:var(--red);">+30 XP</strong>
                        <span>Bonus Diklaim</span>
                    </div>
                    <div class="summary-stat-cell">
                        <strong style="color:#f76707;" id="finalStreakText">{{ $gami['streak'] ?? 1 }} Hari</strong>
                        <span>Streak Aktif</span>
                    </div>
                    <div class="summary-stat-cell">
                        <strong style="color:#087f5b;" id="finalScoreRatio">3 / 3</strong>
                        <span>Misi Tuntas</span>
                    </div>
                </div>

                <div style="display:flex; justify-content:center; gap:12px; flex-wrap:wrap;">
                    <a href="{{ route('dashboard') }}" class="btn-action-primary" style="text-decoration:none;">
                        <span>Kembali ke Dashboard</span>
                    </a>
                    <a href="{{ route('sentence-builder.index') }}" class="button" style="text-decoration:none; background:#f1eae6; color:var(--ink); border:1px solid var(--line);">
                        <span>Coba Susun Kalimat</span>
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

    // ── Dataset from Controller ──
    const CHALLENGE_DATA = @json($challengeData);
    const DAILY_STATUS   = @json($dailyStatus);
    const CSRF_TOKEN     = '{{ csrf_token() }}';
    const COMPLETE_URL   = '{{ route("daily-challenge.complete") }}';

    const MISSIONS = CHALLENGE_DATA.missions || [];
    let currentMissionIndex = 0;
    let answeredMissions = [false, false, false];
    let correctCount = 0;
    let isCurrentAnswered = false;

    // ── DOM References ──
    const missionActiveView     = document.getElementById('missionActiveView');
    const missionCompletedView  = document.getElementById('missionCompletedView');
    const missionBadge          = document.getElementById('missionBadge');
    const missionPrompt         = document.getElementById('missionPrompt');
    const missionTargetWord     = document.getElementById('missionTargetWord');
    const missionRomaji         = document.getElementById('missionRomaji');
    const btnAudioPrompt        = document.getElementById('btnAudioPrompt');
    const optionsGrid           = document.getElementById('optionsGrid');
    const explanationBox        = document.getElementById('explanationBox');
    const explanationHead       = document.getElementById('explanationHead');
    const explanationBody       = document.getElementById('explanationBody');
    const missionCounterText    = document.getElementById('missionCounterText');
    const btnNextMission        = document.getElementById('btnNextMission');
    const btnNextMissionLabel   = document.getElementById('btnNextMissionLabel');
    const countdownTimer        = document.getElementById('countdownTimer');
    const finalStreakText       = document.getElementById('finalStreakText');
    const streakHeroBadge       = document.getElementById('streakHeroBadge');

    // ── Countdown Timer Setup ──
    let secondsLeft = Math.max(0, Math.floor(Number(CHALLENGE_DATA.seconds_to_reset) || 0));
    function updateCountdown() {
        if (secondsLeft <= 0) {
            countdownTimer.textContent = 'Reset: Siap diperbarui!';
            return;
        }
        const hours = Math.floor(secondsLeft / 3600);
        const mins  = Math.floor((secondsLeft % 3600) / 60);
        const secs  = Math.floor(secondsLeft % 60);
        countdownTimer.textContent = `Reset: ${String(hours).padStart(2, '0')}:${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        secondsLeft--;
    }
    setInterval(updateCountdown, 1000);
    updateCountdown();

    // ── Audio Speech Synthesis ──
    function speakJapanese(text) {
        if (!('speechSynthesis' in window)) return;
        try {
            window.speechSynthesis.cancel();
            const utter = new SpeechSynthesisUtterance(text);
            utter.lang = 'ja-JP';
            utter.rate = 0.88;
            window.speechSynthesis.speak(utter);
        } catch (e) {
            // Speech not available
        }
    }

    // ── Load Mission ──
    function loadMission(idx) {
        if (idx >= MISSIONS.length) {
            finishAllMissions();
            return;
        }

        const mission = MISSIONS[idx];
        currentMissionIndex = idx;
        isCurrentAnswered = false;

        // Reset Stepper
        for (let i = 0; i < 3; i++) {
            const pill = document.getElementById(`stepPill_${i}`);
            if (pill) {
                pill.classList.toggle('active', i === idx);
                pill.classList.toggle('completed', answeredMissions[i]);
            }
        }

        // Fill texts
        missionBadge.textContent = mission.badge || `Misi ${idx + 1}`;
        missionPrompt.textContent = mission.prompt;
        missionTargetWord.textContent = mission.target_word;
        missionRomaji.textContent = mission.romaji;
        missionCounterText.textContent = `Misi ${idx + 1} dari 3`;

        btnNextMission.disabled = true;
        if (idx === MISSIONS.length - 1) {
            btnNextMissionLabel.textContent = 'Selesaikan Tantangan & Klaim XP!';
        } else {
            btnNextMissionLabel.textContent = `Lanjut ke Misi ${idx + 2}`;
        }

        explanationBox.style.display = 'none';
        explanationBox.className = 'explanation-box';

        // Render Options
        optionsGrid.innerHTML = '';
        const letters = ['A', 'B', 'C', 'D'];

        mission.options.forEach((optText, optIdx) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'option-card-btn';
            btn.innerHTML = `
                <div class="option-letter">${letters[optIdx]}</div>
                <div class="option-text">${optText}</div>
                <svg class="option-status-icon correct" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <svg class="option-status-icon incorrect" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
            `;

            btn.addEventListener('click', () => chooseOption(optIdx, mission));
            optionsGrid.appendChild(btn);
        });
    }

    // ── Handle Option Selection ──
    function chooseOption(selectedIdx, mission) {
        if (isCurrentAnswered) return;
        isCurrentAnswered = true;
        answeredMissions[currentMissionIndex] = true;

        const isCorrect = selectedIdx === mission.correct;
        if (isCorrect) correctCount++;

        const optionButtons = optionsGrid.querySelectorAll('.option-card-btn');
        optionButtons.forEach((btn, idx) => {
            btn.disabled = true;
            if (idx === mission.correct) {
                btn.classList.add('correct');
            } else if (idx === selectedIdx && !isCorrect) {
                btn.classList.add('incorrect');
            }
        });

        // Show Explanation
        explanationBox.style.display = 'block';
        explanationBox.className = `explanation-box ${isCorrect ? 'correct' : 'incorrect'}`;

        explanationHead.innerHTML = isCorrect ? `
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <span>Jawaban Benar! (+10 XP)</span>
        ` : `
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            <span>Jawaban Belum Tepat</span>
        `;

        explanationBody.textContent = mission.explanation;

        // Enable next button
        btnNextMission.disabled = false;

        // Update current pill status
        const currentPill = document.getElementById(`stepPill_${currentMissionIndex}`);
        if (currentPill) currentPill.classList.add('completed');
    }

    // ── Finish All 3 Missions ──
    function finishAllMissions() {
        missionActiveView.style.display = 'none';
        missionCompletedView.style.display = 'block';

        // Post completion to server
        fetch(COMPLETE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ completed: true })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (data.streak) {
                    finalStreakText.textContent = `${data.streak} Hari`;
                    streakHeroBadge.textContent = `Streak: ${data.streak} Hari`;
                }
                // Show notification if available
                if (typeof window.showToast === 'function') {
                    window.showToast('Tantangan Harian Selesai! +30 XP berhasil diklaim.', 'success');
                }
            }
        })
        .catch(() => {
            // Offline fallback
        });
    }

    // ── Event Listeners ──
    btnAudioPrompt.addEventListener('click', () => {
        const mission = MISSIONS[currentMissionIndex];
        if (mission && mission.audio_text) {
            speakJapanese(mission.audio_text);
        }
    });

    btnNextMission.addEventListener('click', () => {
        currentMissionIndex++;
        loadMission(currentMissionIndex);
    });

    // ── Check if Already Completed Today ──
    if (DAILY_STATUS.is_completed) {
        missionActiveView.style.display = 'none';
        missionCompletedView.style.display = 'block';
        document.getElementById('completedHeroDesc').innerHTML = 'Kamu sudah menyelesaikan tantangan hari ini dan mengamankan streak belajarmu. Sampai jumpa di tantangan besok!';
        for (let i = 0; i < 3; i++) {
            const pill = document.getElementById(`stepPill_${i}`);
            if (pill) pill.classList.add('completed');
        }
    } else {
        loadMission(0);
    }
})();
</script>
@endsection

