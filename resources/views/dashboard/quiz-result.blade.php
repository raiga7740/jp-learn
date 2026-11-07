<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Quiz: {{ $quiz->title }} — JP Learn</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,800;1,900&family=Noto+Sans+JP:wght@500;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #fdf6f2; --bg-soft: #faeee8; --card: #ffffff;
            --ink: #1c1838; --ink-2: #4b4766; --muted: #8a849c; --line: #efe2dc;
            --red: #b3153b; --red-2: #d42a52; --red-soft: #fbe3e8;
            --navy: #17143a; --navy-2: #2a1f55;
            --green: #1f5b45; --green-soft: #e6f4ee;
            --gold: #f2b544; --gold-soft: #fef6e0;
            --blue: #2d3a8c; --blue-soft: #e8ecf8;
            --radius: 18px; --shadow: 0 24px 56px -20px rgba(60,20,40,.24); --shadow-sm: 0 4px 16px -6px rgba(60,20,40,.14);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif; color: var(--ink);
            background: var(--bg); min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            display: flex; flex-direction: column;
        }
        .serif { font-family: 'Noto Sans JP', sans-serif; }

        /* Topbar */
        .topbar {
            background: rgba(253,246,242,.96); backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--line); padding: 12px 36px;
            display: flex; align-items: center; justify-content: space-between; gap: 14px;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-left { display: flex; align-items: center; gap: 14px; min-width: 0; }
        .back-btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 8px 15px; border-radius: 10px;
            border: 1px solid var(--line); background: var(--card);
            color: var(--ink-2); font-size: 12.5px; font-weight: 600;
            text-decoration: none; transition: .2s; flex-shrink: 0;
        }
        .back-btn:hover { border-color: var(--red); color: var(--red); }
        .topbar-title {
            font-size: 14px; font-weight: 700; color: var(--ink-2);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .topbar-user {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 4px 12px 4px 5px; border-radius: 999px;
            border: 1px solid var(--line); background: #fff;
            color: var(--ink); text-decoration: none; font-size: 12.5px; font-weight: 700;
            flex-shrink: 0; transition: .2s;
        }
        .topbar-user:hover { border-color: var(--red); }
        .topbar-avatar {
            width: 28px; height: 28px; border-radius: 50%;
            background: linear-gradient(135deg, var(--red), #9b1dff);
            color: #fff;
            display: inline-flex; align-items: center; justify-content: center;
            text-align: center; line-height: 1;
            font-size: 11px; font-weight: 800; user-select: none;
        }

        /* Confetti canvas */
        #confetti { position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 0; }

        /* Container */
        .result-outer {
            flex: 1; display: flex; flex-direction: column; align-items: center;
            padding: 36px 20px 80px; position: relative; z-index: 1;
        }
        .result-wrapper {
            width: min(780px, 100%);
            display: flex; flex-direction: column; gap: 24px;
        }
        .result-card {
            background: var(--card); border: 1px solid var(--line);
            border-radius: 24px; width: 100%;
            box-shadow: var(--shadow); overflow: hidden;
        }

        /* Header */
        .result-head {
            background: radial-gradient(600px 260px at 100% 0%, #3a1d4f, transparent 60%),
                        linear-gradient(135deg, var(--navy), #0f0c2b);
            padding: 40px 36px 32px; text-align: center; position: relative; overflow: hidden;
        }
        .result-head::after {
            content: '試';
            position: absolute; right: 30px; bottom: -40px;
            font-family: 'Noto Sans JP', sans-serif; font-size: 160px; font-weight: 900;
            color: rgba(255,255,255,.04); pointer-events: none; line-height: 1;
        }
        .result-emoji { margin-bottom: 14px; position: relative; z-index: 1; }
        .result-head h1 {
            font-size: 26px; font-weight: 900; color: #fff; letter-spacing: -.02em;
            margin-bottom: 6px; position: relative; z-index: 1;
        }
        .result-head p { font-size: 13.5px; color: #bdb7dc; position: relative; z-index: 1; }

        /* Score ring */
        .score-section { padding: 32px 36px 20px; display: flex; flex-direction: column; align-items: center; }
        .rank-pill {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 12px; font-weight: 700; padding: 5px 16px; border-radius: 999px;
            margin-bottom: 22px;
        }
        .rank-pill.excellent { background: var(--green-soft); color: var(--green); border: 1px solid #a8d5bc; }
        .rank-pill.good      { background: var(--gold-soft); color: #c8800a; border: 1px solid #f2c97a; }
        .rank-pill.average   { background: var(--blue-soft); color: var(--blue); border: 1px solid #b0bae8; }
        .rank-pill.poor      { background: var(--red-soft); color: var(--red); border: 1px solid #f2b0be; }

        .ring-wrap { position: relative; width: 140px; height: 140px; margin-bottom: 18px; }
        .ring-wrap svg { transform: rotate(-90deg); }
        .ring-track { fill: none; stroke: var(--bg-soft); stroke-width: 9; }
        .ring-fill {
            fill: none; stroke-width: 9; stroke-linecap: round;
            stroke-dasharray: 408; /* 2*π*65 */
            transition: stroke-dashoffset 1.5s cubic-bezier(.4,0,.2,1);
        }
        .ring-fill.excellent { stroke: url(#grad-green); }
        .ring-fill.good      { stroke: var(--gold); }
        .ring-fill.average   { stroke: var(--blue); }
        .ring-fill.poor      { stroke: var(--red); }
        .ring-center { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); text-align: center; }
        .ring-pct { font-size: 32px; font-weight: 900; letter-spacing: -.04em; line-height: 1; }
        .ring-pct.excellent { color: var(--green); }
        .ring-pct.good      { color: #c8800a; }
        .ring-pct.average   { color: var(--blue); }
        .ring-pct.poor      { color: var(--red); }
        .ring-lbl { font-size: 10px; color: var(--muted); font-weight: 700; }

        /* Stats row */
        .stats-row {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;
            padding: 0 36px 20px;
        }
        .stat-box {
            background: var(--bg-soft); border: 1px solid var(--line);
            border-radius: 14px; padding: 16px 12px; text-align: center;
        }
        .stat-box-val { font-size: 26px; font-weight: 900; letter-spacing: -.03em; }
        .stat-box-val.green { color: var(--green); }
        .stat-box-val.red   { color: var(--red); }
        .stat-box-val.gold  { color: #c8800a; }
        .stat-box-lbl { font-size: 11.5px; color: var(--muted); margin-top: 3px; font-weight: 600; }

        /* Message */
        .result-message {
            margin: 0 36px 22px;
            background: var(--bg-soft); border-radius: 12px;
            padding: 16px 20px; border-left: 3px solid var(--red);
            font-size: 13px; color: var(--ink-2); line-height: 1.65;
        }
        .result-message strong { color: var(--ink); }

        /* Actions */
        .result-actions {
            display: flex; gap: 12px; padding: 0 36px 32px;
        }
        .btn {
            flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 7px;
            padding: 12px 20px; border-radius: 12px;
            font-size: 13.5px; font-weight: 700; text-decoration: none;
            cursor: pointer; font-family: inherit; border: none; transition: .22s; text-align: center;
        }
        .btn-ghost { background: var(--card); border: 1px solid var(--line); color: var(--ink-2); }
        .btn-ghost:hover { border-color: var(--navy); color: var(--navy); }
        .btn-red { background: linear-gradient(135deg, var(--red), var(--red-2)); color: #fff; box-shadow: 0 8px 20px -8px var(--red); }
        .btn-red:hover { transform: translateY(-1px); box-shadow: 0 12px 28px -10px var(--red); }

        /* ── Pembahasan / Question Review Section ── */
        .review-card {
            background: var(--card); border: 1px solid var(--line);
            border-radius: 24px; box-shadow: var(--shadow-sm); overflow: hidden;
        }
        .review-head {
            padding: 24px 32px; border-bottom: 1.5px solid var(--line);
            display: flex; align-items: center; justify-content: space-between; gap: 16px;
            flex-wrap: wrap; background: #fffdfd;
        }
        .review-title-wrap { min-width: 0; }
        .review-title {
            font-size: 17px; font-weight: 800; color: var(--navy);
            display: flex; align-items: center; gap: 9px; letter-spacing: -.01em;
        }
        .review-subtitle { font-size: 12.5px; color: var(--muted); margin-top: 3px; }

        .filter-tabs {
            display: flex; gap: 6px; background: var(--bg-soft);
            padding: 4px; border-radius: 10px; border: 1px solid var(--line);
        }
        .filter-btn {
            border: none; background: transparent; padding: 6px 14px;
            border-radius: 8px; font-size: 12px; font-weight: 700;
            color: var(--muted); cursor: pointer; transition: all .2s;
            font-family: inherit;
        }
        .filter-btn:hover { color: var(--ink); }
        .filter-btn.active {
            background: #fff; color: var(--ink);
            box-shadow: 0 2px 8px -2px rgba(60,20,40,.12);
        }
        .filter-btn.active.tab-correct { color: var(--green); }
        .filter-btn.active.tab-wrong   { color: var(--red); }

        .review-list { padding: 24px 32px; display: flex; flex-direction: column; gap: 20px; }

        .q-card {
            background: #fff; border: 1.5px solid var(--line);
            border-radius: 16px; padding: 22px 24px;
            transition: all .2s; display: flex; flex-direction: column; gap: 16px;
        }
        .q-card.is-correct { border-color: #c0e6d2; }
        .q-card.is-wrong   { border-color: #f7c3cc; }

        .q-top {
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
        }
        .q-num {
            font-size: 12px; font-weight: 800; color: var(--muted);
            letter-spacing: .06em; text-transform: uppercase;
        }
        .q-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 12px; border-radius: 999px;
            font-size: 11.5px; font-weight: 700;
        }
        .q-badge.correct { background: var(--green-soft); color: var(--green); border: 1px solid #b2deca; }
        .q-badge.wrong   { background: var(--red-soft); color: var(--red); border: 1px solid #f9c0cb; }

        .q-main {
            display: flex; align-items: center; gap: 18px;
        }
        .q-jp {
            width: 72px; height: 72px; border-radius: 14px;
            background: var(--bg-soft); border: 1px solid var(--line);
            display: grid; place-items: center; flex-shrink: 0;
            font-family: 'Noto Sans JP', sans-serif; font-size: 36px; font-weight: 900;
            color: var(--navy); line-height: 1;
        }
        .q-text-wrap { flex: 1; min-width: 0; }
        .q-text { font-size: 15px; font-weight: 700; color: var(--ink); line-height: 1.4; }
        .q-hint { font-size: 12px; color: var(--muted); margin-top: 4px; }

        /* Options grid in review */
        .q-options {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px;
        }
        .q-opt {
            padding: 12px 14px; border-radius: 12px;
            border: 1.5px solid var(--line); background: var(--bg);
            font-size: 13px; font-weight: 600; color: var(--ink-2);
            display: flex; align-items: center; justify-content: space-between; gap: 10px;
        }
        .q-opt-val { font-family: 'Noto Sans JP', sans-serif; font-weight: 700; font-size: 14px; color: var(--ink); }
        .q-opt-tag {
            font-size: 10px; font-weight: 700; padding: 2px 7px;
            border-radius: 5px; letter-spacing: .02em; white-space: nowrap;
        }

        /* Statuses of options */
        .q-opt.user-correct {
            background: #eafaf1; border-color: #5ec993; color: var(--green);
        }
        .q-opt.user-correct .q-opt-val { color: var(--green); }
        .q-opt.user-correct .q-opt-tag { background: #d0f3df; color: var(--green); }

        .q-opt.user-wrong {
            background: #fff0f2; border-color: #f79aa9; color: var(--red);
        }
        .q-opt.user-wrong .q-opt-val { color: var(--red); text-decoration: line-through; }
        .q-opt.user-wrong .q-opt-tag { background: #fddde3; color: var(--red); }

        .q-opt.actual-answer {
            background: #f1fcf5; border-color: #3bb273; border-style: dashed;
        }
        .q-opt.actual-answer .q-opt-val { color: var(--green); font-weight: 800; }
        .q-opt.actual-answer .q-opt-tag { background: #d0f3df; color: var(--green); font-weight: 800; }

        .q-summary {
            padding: 10px 14px; border-radius: 10px; font-size: 12.5px;
            display: flex; align-items: center; gap: 8px; line-height: 1.5;
        }
        .q-summary.correct { background: #f2fbf6; color: var(--green); border-left: 3px solid var(--green); }
        .q-summary.wrong   { background: #fff5f6; color: var(--red); border-left: 3px solid var(--red); }
        .q-summary strong { color: var(--ink); }

        @media (max-width: 640px) {
            .topbar { padding: 10px 16px; }
            .topbar-user .name { display: none; }
            .result-outer { padding: 20px 12px 60px; }
            .result-head { padding: 30px 20px 24px; }
            .result-head h1 { font-size: 22px; }
            .score-section { padding: 24px 16px 16px; }
            .stats-row { padding: 0 16px 16px; gap: 8px; }
            .stat-box { padding: 12px 6px; }
            .stat-box-val { font-size: 22px; }
            .result-message { margin: 0 16px 18px; padding: 14px 16px; font-size: 12px; }
            .result-actions { padding: 0 16px 24px; flex-direction: column-reverse; gap: 8px; }
            .result-actions .btn { width: 100%; }
            .review-head { padding: 18px 18px; }
            .review-list { padding: 16px 16px; gap: 14px; }
            .q-card { padding: 16px 16px; }
            .q-main { flex-direction: column; align-items: flex-start; gap: 12px; }
            .q-options { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<canvas id="confetti"></canvas>

<!-- Topbar -->
<header class="topbar">
    <div class="topbar-left">
        <a href="{{ route('dashboard.quizzes') }}" class="back-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Daftar Quiz
        </a>
        <span class="topbar-title">{{ $quiz->title }}</span>
    </div>
    <a href="{{ route('dashboard') }}" class="topbar-user" title="Buka Dashboard">
        <span class="topnav-avatar topbar-avatar">{{ strtoupper(mb_substr(Auth::user()->name ?? 'U', 0, 1)) }}</span>
        <span class="name">{{ Auth::user()->name ?? 'Dashboard' }}</span>
    </a>
</header>

@php
    $grade = $score >= 80 ? 'excellent' : ($score >= 60 ? 'good' : ($score >= 40 ? 'average' : 'poor'));
    $title = $score >= 80 ? 'Luar Biasa!' : ($score >= 60 ? 'Bagus Sekali!' : ($score >= 40 ? 'Terus Semangat!' : 'Jangan Menyerah!'));
    $wrong = max(0, $total - $correct);
    $dashOffset = 408 - (408 * $score / 100);
@endphp

<div class="result-outer">
    <div class="result-wrapper">

        <!-- Score Overview Card -->
        <div class="result-card">

            <!-- Header -->
            <div class="result-head">
                <div class="result-emoji">
                    @if($score >= 80)
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#f2b544" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                    @elseif($score >= 60)
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#6edba3" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    @elseif($score >= 40)
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#ff8098" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
                    @else
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#9ea8e8" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                    @endif
                </div>
                <h1>{{ $title }}</h1>
                <p>Kamu telah menyelesaikan <strong>"{{ $quiz->title }}"</strong></p>
            </div>

            <!-- Score ring -->
            <div class="score-section">
                <span class="rank-pill {{ $grade }}">
                    @if($score >= 80)
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg> Sempurna
                    @elseif($score >= 60)
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg> Bagus
                    @elseif($score >= 40)
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg> Cukup
                    @else
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg> Perlu Belajar
                    @endif
                </span>

                <div class="ring-wrap">
                    <svg width="140" height="140" viewBox="0 0 140 140">
                        <defs>
                            <linearGradient id="grad-green" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:{{ $grade === 'excellent' ? '#1f5b45' : '#b3153b' }}"/>
                                <stop offset="100%" style="stop-color:{{ $grade === 'excellent' ? '#06b6d4' : '#d42a52' }}"/>
                            </linearGradient>
                        </defs>
                        <circle class="ring-track" cx="70" cy="70" r="65"/>
                        <circle class="ring-fill {{ $grade }}" id="ring-fill"
                                cx="70" cy="70" r="65"
                                style="stroke-dashoffset: {{ $dashOffset }}"/>
                    </svg>
                    <div class="ring-center">
                        <div class="ring-pct {{ $grade }}">{{ $score }}%</div>
                        <div class="ring-lbl">SKOR AKHIR</div>
                    </div>
                </div>
            </div>

            <!-- Stats row -->
            <div class="stats-row">
                <div class="stat-box">
                    <div class="stat-box-val green">{{ $correct }}</div>
                    <div class="stat-box-lbl">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#1f5b45" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;margin-right:3px"><polyline points="20 6 9 17 4 12"/></svg>
                        Benar
                    </div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-val red">{{ $wrong }}</div>
                    <div class="stat-box-lbl">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#b3153b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;margin-right:3px"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        Salah
                    </div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-val gold">{{ $total }}</div>
                    <div class="stat-box-lbl">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#c8800a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;margin-right:3px"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                        Total Soal
                    </div>
                </div>
            </div>

            <!-- Feedback Message -->
            <div class="result-message">
                @if($score >= 80)
                    <strong>Luar biasa!</strong>
                    Kamu menguasai materi ini dengan sangat baik. Lihat pembahasan di bawah untuk melihat rincian setiap jawabanmu.
                @elseif($score >= 60)
                    <strong>Bagus!</strong>
                    Kamu sudah memahami sebagian besar materi. Periksa soal-soal yang salah di bawah ini untuk memperbaiki pemahamanmu.
                @elseif($score >= 40)
                    <strong>Terus berlatih!</strong>
                    Masih ada {{ $wrong }} soal yang perlu diperbaiki. Pelajari kunci jawaban pada daftar pembahasan di bawah ini.
                @else
                    <strong>Jangan berkecil hati!</strong>
                    Cermati daftar jawaban benar di bawah ini dan ulangi membaca materi sebelum mencoba lagi.
                @endif
            </div>

            <!-- Action buttons -->
            <div class="result-actions">
                <a href="{{ route('quiz.show', $quiz->id) }}" class="btn btn-ghost">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.18-8.5"/></svg>
                    Ulangi Quiz
                </a>
                <a href="{{ route('dashboard.quizzes') }}" class="btn btn-red">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Kembali ke Daftar Quiz
                </a>
            </div>

        </div>{{-- .result-card --}}

        <!-- ── Detailed Review & Answers Breakdown ── -->
        @if(!empty($review))
        <div class="review-card" id="reviewSection">
            <div class="review-head">
                <div class="review-title-wrap">
                    <div class="review-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--navy)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        Pembahasan & Kunci Jawaban
                    </div>
                    <div class="review-subtitle">
                        Periksa rincian setiap pertanyaan untuk melihat jawabanmu dan kunci jawaban yang benar.
                    </div>
                </div>

                <!-- Filter Tabs -->
                <div class="filter-tabs" role="tablist">
                    <button type="button" class="filter-btn active" onclick="setReviewFilter('all', this)">Semua ({{ count($review) }})</button>
                    <button type="button" class="filter-btn tab-correct" onclick="setReviewFilter('correct', this)" style="display:inline-flex; align-items:center; gap:5px;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Benar ({{ $correct }})
                    </button>
                    <button type="button" class="filter-btn tab-wrong" onclick="setReviewFilter('wrong', this)" style="display:inline-flex; align-items:center; gap:5px;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        Salah ({{ $wrong }})
                    </button>
                </div>
            </div>

            <div class="review-list" id="reviewList">
                @foreach($review as $idx => $item)
                @php
                    $isCorrect   = $item['is_correct'];
                    $userAnswer  = $item['user_answer'];
                    $correctAns  = $item['correct_answer'];
                    $options     = $item['options'] ?? [];
                @endphp
                <div class="q-card {{ $isCorrect ? 'is-correct' : 'is-wrong' }}" data-status="{{ $isCorrect ? 'correct' : 'wrong' }}">
                    <div class="q-top">
                        <span class="q-num">Pertanyaan #{{ $idx + 1 }}</span>
                        @if($isCorrect)
                            <span class="q-badge correct">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                Jawaban Benar
                            </span>
                        @else
                            <span class="q-badge wrong">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                {{ $userAnswer === null ? 'Tidak Dijawab' : 'Jawaban Salah' }}
                            </span>
                        @endif
                    </div>

                    <div class="q-main">
                        @if(!empty($item['question_jp']))
                        <div class="q-jp">{{ $item['question_jp'] }}</div>
                        @endif
                        <div class="q-text-wrap">
                            <div class="q-text">{{ $item['question'] }}</div>
                            @if(!empty($item['romaji']) || !empty($item['meaning']))
                            <div class="q-hint">
                                @if(!empty($item['romaji'])) Romaji: <strong>{{ $item['romaji'] }}</strong> @endif
                                @if(!empty($item['meaning'])) • Arti: <strong>{{ $item['meaning'] }}</strong> @endif
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Options comparison -->
                    <div class="q-options">
                        @foreach($options as $opt)
                        @php
                            $optStr = (string) $opt;
                            $isUserChoice = ($userAnswer !== null && (string)$userAnswer === $optStr);
                            $isRightKey   = ((string)$correctAns === $optStr);

                            $optClass = '';
                            $tagText  = null;
                            $tagIcon  = null;

                            if ($isUserChoice && $isRightKey) {
                                $optClass = 'user-correct';
                                $tagText  = 'Pilihanmu (Benar)';
                                $tagIcon  = 'check';
                            } elseif ($isUserChoice && !$isRightKey) {
                                $optClass = 'user-wrong';
                                $tagText  = 'Pilihanmu (Salah)';
                                $tagIcon  = 'x';
                            } elseif ($isRightKey) {
                                $optClass = 'actual-answer';
                                $tagText  = 'Jawaban Benar';
                                $tagIcon  = 'star';
                            }
                        @endphp
                        <div class="q-opt {{ $optClass }}">
                            <span class="q-opt-val">{{ $opt }}</span>
                            @if($tagText)
                            <span class="q-opt-tag" style="display:inline-flex; align-items:center; gap:4px;">
                                @if($tagIcon === 'check')
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                @elseif($tagIcon === 'x')
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                @elseif($tagIcon === 'star')
                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                @endif
                                {{ $tagText }}
                            </span>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Summary Note -->
                    @if($isCorrect)
                    <div class="q-summary correct">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>Tepat sekali! Pilihan <strong>{{ $userAnswer }}</strong> adalah jawaban yang benar.</span>
                    </div>
                    @else
                    <div class="q-summary wrong">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span>
                            @if($userAnswer === null)
                                Kamu tidak menjawab soal ini. Jawaban yang benar adalah <strong>{{ $correctAns }}</strong>.
                            @else
                                Kamu memilih <strong>{{ $userAnswer }}</strong>. Jawaban yang benar seharusnya <strong>{{ $correctAns }}</strong>.
                            @endif
                        </span>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>{{-- .review-card --}}
        @endif

    </div>{{-- .result-wrapper --}}
</div>{{-- .result-outer --}}

<script>
    // Animate score ring
    document.addEventListener('DOMContentLoaded', () => {
        const ring   = document.getElementById('ring-fill');
        const target = {{ $dashOffset }};
        if (ring) {
            ring.style.strokeDashoffset = 408;
            setTimeout(() => { ring.style.strokeDashoffset = target; }, 200);
        }
    });

    // Filter review questions
    function setReviewFilter(filter, btn) {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');

        const cards = document.querySelectorAll('#reviewList .q-card');
        cards.forEach(card => {
            const status = card.getAttribute('data-status');
            if (filter === 'all' || status === filter) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Confetti for scores >= 60
    @if($score >= 60)
    const cv = document.getElementById('confetti');
    if (cv) {
        const cx = cv.getContext('2d');
        cv.width = window.innerWidth; cv.height = window.innerHeight;
        const parts = Array.from({ length: 90 }, () => ({
            x: Math.random() * cv.width,
            y: Math.random() * cv.height - cv.height,
            w: Math.random() * 9 + 4, h: Math.random() * 5 + 3,
            color: ['#b3153b','#f2b544','#1f5b45','#2d3a8c','#9b1dff'][Math.floor(Math.random()*5)],
            speed: Math.random() * 2.5 + 1,
            angle: Math.random() * 360, spin: (Math.random()-.5)*5,
            drift: (Math.random()-.5)*1.5,
            opacity: 1, decay: Math.random()*.005+.003,
        }));
        let alive = true;
        function draw() {
            if (!alive) return;
            cx.clearRect(0, 0, cv.width, cv.height);
            parts.forEach(p => {
                p.y += p.speed; p.x += p.drift;
                p.angle += p.spin; p.opacity -= p.decay;
                if (p.y > cv.height) { p.y = -15; p.opacity = 1; }
                cx.save();
                cx.globalAlpha = Math.max(0, p.opacity);
                cx.translate(p.x, p.y); cx.rotate(p.angle * Math.PI / 180);
                cx.fillStyle = p.color; cx.fillRect(-p.w/2, -p.h/2, p.w, p.h);
                cx.restore();
            });
            requestAnimationFrame(draw);
        }
        draw();
        setTimeout(() => { alive = false; cx.clearRect(0, 0, cv.width, cv.height); }, 3500);
    }
    @endif
</script>
@include('dashboard.partials.xp-toast')
</body>
</html>
