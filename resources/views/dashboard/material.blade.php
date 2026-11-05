<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $material->title }} — JP Learn</title>
    <meta name="description" content="{{ $material->description }}">
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
            --purple: #6b21a8; --purple-soft: #f3e8ff;
            --radius: 18px; --shadow: 0 20px 50px -25px rgba(60,20,40,.22); --shadow-sm: 0 4px 16px -6px rgba(60,20,40,.14);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif; color: var(--ink);
            background: var(--bg); min-height: 100vh; -webkit-font-smoothing: antialiased;
        }
        .serif { font-family: 'Noto Sans JP', sans-serif; }

        /* Nav */
        .topnav {
            background: rgba(253,246,242,.92); backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--line); padding: 12px 36px;
            display: flex; align-items: center; justify-content: space-between; gap: 16px;
            position: sticky; top: 0; z-index: 50;
        }
        .topnav-left {
            display: flex; align-items: center; gap: 14px; min-width: 0;
        }
        .back-btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 8px 15px; border-radius: 10px;
            border: 1px solid var(--line); background: var(--card);
            color: var(--ink-2); font-size: 13px; font-weight: 600;
            text-decoration: none; transition: .2s; flex-shrink: 0;
        }
        .back-btn:hover { border-color: var(--red); color: var(--red); }
        .breadcrumb {
            display: flex; align-items: center; gap: 6px; font-size: 13px; color: var(--muted);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .breadcrumb a { color: var(--ink-2); text-decoration: none; font-weight: 500; }
        .breadcrumb a:hover { color: var(--red); }
        .breadcrumb span:last-child {
            color: var(--red); font-weight: 700;
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .topnav-user {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 4px 12px 4px 5px; border-radius: 999px;
            border: 1px solid var(--line); background: #fff;
            color: var(--ink); text-decoration: none; font-size: 12.5px; font-weight: 700;
            flex-shrink: 0; transition: .2s;
        }
        .topnav-user:hover { border-color: var(--red); }
        .topnav-avatar {
            width: 28px; height: 28px; border-radius: 50%;
            background: linear-gradient(135deg, var(--red), #9b1dff);
            color: #fff;
            display: inline-flex; align-items: center; justify-content: center;
            text-align: center; line-height: 1;
            font-size: 11px; font-weight: 800; user-select: none;
        }

        /* Hero */
        .hero {
            background: radial-gradient(600px 300px at 100% 0%, #4a1c46, transparent 60%),
                        linear-gradient(135deg, var(--navy), #0f0c2b);
            padding: 44px 36px; color: #fff; position: relative; overflow: hidden;
        }
        .hero::after {
            content: attr(data-icon);
            position: absolute; right: 48px; bottom: -30px;
            font-family: 'Noto Sans JP', sans-serif; font-size: 160px; font-weight: 900;
            color: rgba(255,255,255,.05); pointer-events: none; line-height: 1;
        }
        .hero-inner { max-width: 860px; position: relative; z-index: 1; }
        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 10px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase;
            padding: 4px 13px; border-radius: 999px; margin-bottom: 16px;
        }
        .hero-eyebrow.hiragana   { background: rgba(179,21,59,.25); color: #ff8098; border: 1px solid rgba(179,21,59,.3); }
        .hero-eyebrow.katakana   { background: rgba(45,58,140,.3); color: #9ea8e8; border: 1px solid rgba(45,58,140,.3); }
        .hero-eyebrow.kanji      { background: rgba(242,181,68,.2); color: var(--gold); border: 1px solid rgba(242,181,68,.25); }
        .hero-eyebrow.vocabulary { background: rgba(31,91,69,.3); color: #6edba3; border: 1px solid rgba(31,91,69,.3); }
        .hero-eyebrow.grammar    { background: rgba(107,33,168,.3); color: #d4aaff; border: 1px solid rgba(107,33,168,.3); }

        .hero h1 { font-family: 'Noto Sans JP', sans-serif; font-size: 30px; font-weight: 700; margin-bottom: 8px; }
        .hero p { font-size: 14px; color: #bdb7dc; max-width: 560px; line-height: 1.7; }
        .hero-meta { display: flex; gap: 10px; margin-top: 18px; flex-wrap: wrap; }
        .hero-chip {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 11.5px; font-weight: 600;
            padding: 6px 14px; border-radius: 8px;
            background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12); color: #d9d4f2;
        }

        /* Progress sub-bar */
        .progress-bar-section {
            background: var(--card); border-bottom: 1px solid var(--line);
            padding: 16px 36px;
        }
        .pb-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
        .pb-row span:first-child { font-size: 12px; font-weight: 700; color: var(--ink-2); }
        .pb-row span:last-child { font-size: 12px; font-weight: 800; color: var(--red); }
        .pb-track { height: 7px; background: var(--bg-soft); border-radius: 999px; overflow: hidden; }
        .pb-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--red), var(--red-2)); transition: width 1.2s cubic-bezier(.4,0,.2,1); }

        /* Content */
        .content-wrap { max-width: 920px; margin: 0 auto; padding: 40px 36px 120px; }

        .section-block { margin-bottom: 40px; }
        .section-block-title {
            font-size: 11px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase;
            color: var(--muted); margin-bottom: 16px;
            display: flex; align-items: center; gap: 10px;
        }
        .section-block-title::after { content: ''; flex: 1; height: 1px; background: var(--line); }

        /* Kana grid */
        .kana-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 10px; }
        .kana-card {
            background: var(--card); border: 1.5px solid var(--line);
            border-radius: 14px; padding: 18px 10px; text-align: center;
            cursor: pointer; transition: all .25s;
        }
        .kana-card:hover {
            border-color: var(--red);
            box-shadow: 0 8px 24px -10px rgba(179,21,59,.25);
            transform: translateY(-4px);
        }
        .kana-card.active {
            background: var(--red-soft); border-color: var(--red);
            box-shadow: 0 8px 24px -10px rgba(179,21,59,.3);
        }
        .kana-char {
            font-family: 'Noto Sans JP', sans-serif; font-size: 42px; font-weight: 900;
            line-height: 1; color: var(--navy); margin-bottom: 8px;
            transition: transform .2s;
        }
        .kana-card:hover .kana-char, .kana-card.active .kana-char { transform: scale(1.08); }
        .kana-card.active .kana-char { color: var(--red); }
        .kana-romaji { font-size: 13px; font-weight: 800; color: var(--red); letter-spacing: .04em; }

        /* Vocab */
        .vocab-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 12px; }
        .vocab-card {
            background: var(--card); border: 1px solid var(--line);
            border-radius: 14px; padding: 20px;
            transition: all .22s; box-shadow: var(--shadow-sm);
        }
        .vocab-card:hover { border-color: var(--red); transform: translateY(-2px); box-shadow: var(--shadow); }
        .vocab-tag {
            display: inline-block; font-size: 10.5px; font-weight: 700;
            color: var(--muted); background: var(--bg-soft);
            padding: 3px 9px; border-radius: 6px; margin-bottom: 8px;
            letter-spacing: .02em;
        }
        .vocab-jp { font-family: 'Noto Sans JP', sans-serif; font-size: 26px; font-weight: 900; color: var(--navy); margin-bottom: 6px; }
        .vocab-romaji { font-size: 13px; color: var(--muted); font-weight: 600; margin-bottom: 4px; }
        .vocab-meaning { font-size: 14.5px; font-weight: 800; color: var(--red); }

        /* Kanji */
        .kanji-search-wrap {
            position: relative;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
        }
        .kanji-search-wrap svg {
            position: absolute;
            left: 14px;
            color: var(--muted);
            pointer-events: none;
        }
        .kanji-search-input {
            width: 100%;
            height: 46px;
            padding: 0 110px 0 42px;
            border: 1.5px solid var(--line);
            border-radius: 12px;
            background: var(--card);
            font-size: 13.5px;
            font-family: inherit;
            color: var(--ink);
            outline: none;
            transition: all .2s;
            box-shadow: var(--shadow-sm);
        }
        .kanji-search-input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(242,181,68,.18);
        }
        .kanji-search-badge {
            position: absolute;
            right: 12px;
            font-size: 11.5px;
            font-weight: 700;
            color: var(--muted);
            background: var(--bg-soft);
            padding: 4px 10px;
            border-radius: 999px;
            pointer-events: none;
        }
        .kanji-tag {
            display: inline-block;
            font-size: 10px;
            font-weight: 700;
            color: #92580a;
            background: var(--gold-soft);
            padding: 2px 7px;
            border-radius: 5px;
            margin-bottom: 5px;
            letter-spacing: .02em;
        }
        .kanji-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 12px; }
        .kanji-card {
            background: var(--card); border: 1px solid var(--line);
            border-radius: 14px; padding: 20px 18px;
            display: flex; gap: 14px; align-items: flex-start;
            transition: all .22s; box-shadow: var(--shadow-sm);
        }
        .kanji-card:hover { border-color: var(--gold); transform: translateY(-2px); box-shadow: var(--shadow); }
        .kanji-char { font-family: 'Noto Sans JP', sans-serif; font-size: 46px; font-weight: 900; color: var(--navy); line-height: 1; flex-shrink: 0; }
        .kanji-info { min-width: 0; flex: 1; }
        .kanji-meaning { font-size: 13.5px; font-weight: 800; color: var(--ink); margin-bottom: 6px; line-height: 1.3; }
        .kanji-read { font-size: 11px; display: flex; flex-direction: column; gap: 3px; }
        .kanji-read span { color: var(--muted); }
        .kanji-read .val { font-family: 'Noto Sans JP', sans-serif; color: var(--ink-2); margin-left: 4px; font-weight: 600; word-break: break-all; }

        /* Grammar */
        .grammar-list { display: flex; flex-direction: column; gap: 14px; }
        .grammar-card {
            background: var(--card); border: 1px solid var(--line);
            border-radius: 14px; padding: 22px 24px;
            transition: all .22s; box-shadow: var(--shadow-sm);
        }
        .grammar-card:hover { border-color: var(--navy); box-shadow: var(--shadow); }
        .grammar-pattern { font-family: 'Noto Sans JP', sans-serif; font-size: 22px; font-weight: 900; color: var(--navy); margin-bottom: 4px; }
        .grammar-romaji { font-size: 12.5px; color: var(--muted); margin-bottom: 8px; }
        .grammar-meaning { font-size: 14px; font-weight: 800; color: var(--red); margin-bottom: 10px; }
        .grammar-desc {
            font-size: 13px; color: var(--ink-2); line-height: 1.6;
            margin-bottom: 12px; background: rgba(23,20,58,.03);
            border-left: 2px solid var(--navy-2);
            padding: 8px 12px; border-radius: 6px;
        }
        .grammar-example {
            background: var(--bg-soft); border-radius: 10px; padding: 12px 16px;
            font-size: 13px; color: var(--ink-2); line-height: 1.6;
            border-left: 3px solid var(--red);
        }

        /* Sticky action bar */
        .action-bar {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: rgba(253,246,242,.92); backdrop-filter: blur(14px);
            border-top: 1px solid var(--line);
            padding: 16px 36px;
            display: flex; align-items: center; justify-content: space-between; gap: 16px;
            z-index: 40;
        }
        .action-bar-info { font-size: 13px; color: var(--ink-2); }
        .action-bar-info strong { color: var(--ink); }
        .action-btns { display: flex; gap: 10px; }
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 10px 22px; border-radius: 10px;
            font-size: 13px; font-weight: 700; cursor: pointer;
            text-decoration: none; transition: .22s; font-family: inherit; border: none;
        }
        .btn-ghost { background: var(--card); border: 1px solid var(--line); color: var(--ink-2); }
        .btn-ghost:hover { border-color: var(--navy); color: var(--navy); }
        .btn-red { background: linear-gradient(135deg, var(--red), var(--red-2)); color: #fff; box-shadow: 0 8px 20px -8px var(--red); }
        .btn-red:hover { transform: translateY(-1px); box-shadow: 0 12px 28px -10px var(--red); }
        .btn-done { background: var(--green-soft); border: 1px solid #a8d5bc; color: var(--green); }

        @media (max-width: 768px) {
            .topnav, .progress-bar-section { padding: 12px 18px; }
            .topnav-user .name { display: none; }
            .hero { padding: 32px 20px; }
            .hero h1 { font-size: 22px; }
            .content-wrap { padding: 28px 18px 140px; }
            .action-bar { padding: 12px 18px; flex-direction: column; gap: 10px; }
            .action-bar-info { text-align: center; font-size: 12px; }
            .action-btns { width: 100%; justify-content: center; }
            .action-btns .btn, .action-btns form { flex: 1; }
            .action-btns .btn { width: 100%; justify-content: center; }
            .kana-grid { grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); }
        }
        @media (max-width: 480px) {
            .breadcrumb span:nth-child(2), .breadcrumb a:first-child { display: none; }
            .action-btns { flex-direction: column; }
        }
    </style>
</head>
<body>

<!-- Nav -->
<header class="topnav">
    <div class="topnav-left">
        <a href="{{ route('dashboard.materials') }}" class="back-btn">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Materi
        </a>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <span>/</span>
            <a href="{{ route('dashboard.materials') }}">Materi</a>
            <span>/</span>
            <span>{{ $material->title }}</span>
        </div>
    </div>
    <a href="{{ route('dashboard') }}" class="topnav-user" title="Buka Dashboard">
        <span class="topnav-avatar">{{ strtoupper(mb_substr(Auth::user()->name ?? 'U', 0, 1)) }}</span>
        <span class="name">{{ Auth::user()->name ?? 'Dashboard' }}</span>
    </a>
</header>

<!-- Hero -->
<div class="hero" data-icon="{{ $material->category_icon }}">
    <div class="hero-inner">
        <div class="hero-eyebrow {{ $material->category }}">
            {{ $material->category_icon }} {{ ucfirst($material->category) }}
        </div>
        <h1>{{ $material->title }}</h1>
        <p>{{ $material->description }}</p>
        <div class="hero-meta">
            <span class="hero-chip">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ ucfirst($material->level) }}
            </span>
            <span class="hero-chip">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                {{ ucfirst($material->category) }}
            </span>
            @if($progress->last_accessed_at)
            <span class="hero-chip">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Terakhir: {{ $progress->last_accessed_at->diffForHumans() }}
            </span>
            @endif
            @if($progress->completed)
            <span class="hero-chip" style="background:rgba(31,91,69,.3);border-color:rgba(31,91,69,.3);color:#6edba3">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Selesai
            </span>
            @endif
        </div>
    </div>
</div>

<!-- Progress bar -->
<div class="progress-bar-section">
    <div class="pb-row">
        <span>Progress Materi</span>
        <span>{{ $progress->progress_percent }}%</span>
    </div>
    <div class="pb-track">
        <div class="pb-fill" id="pb-fill" style="width: 0%" data-target="{{ $progress->progress_percent }}%"></div>
    </div>
</div>

<!-- Content -->
<div class="content-wrap">

    @if($material->category === 'hiragana' || $material->category === 'katakana')
        @foreach($material->content['sections'] as $section)
        <div class="section-block">
            <div class="section-block-title">{{ $section['title'] }}</div>
            <div class="kana-grid">
                @foreach($section['characters'] as $char)
                <div class="kana-card" onclick="this.classList.toggle('active')">
                    <div class="kana-char">{{ $char['kana'] }}</div>
                    <div class="kana-romaji">{{ $char['romaji'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
        <p style="font-size:12px;color:var(--muted);text-align:center;margin-top:-20px">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;margin-right:4px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Klik kartu untuk menandainya. Hafalkan satu baris sebelum melanjutkan.
        </p>

    @elseif($material->category === 'vocabulary')
        <div class="section-block">
            <div class="section-block-title">Daftar Kosakata Dasar ({{ count($material->content['words']) }} Kosakata)</div>
            <div class="vocab-grid">
                @foreach($material->content['words'] as $word)
                <div class="vocab-card">
                    @if(!empty($word['category']))
                    <span class="vocab-tag">{{ $word['category'] }}</span>
                    @endif
                    <div class="vocab-jp">{{ $word['japanese'] }}</div>
                    <div class="vocab-romaji">{{ $word['romaji'] }}</div>
                    <div class="vocab-meaning">{{ $word['meaning'] }}</div>
                </div>
                @endforeach
            </div>
        </div>

    @elseif($material->category === 'kanji')
        <div class="section-block">
            <div class="section-block-title">Daftar Lengkap Kanji N5 ({{ count($material->content['kanji']) }} Karakter)</div>

            <div class="kanji-search-wrap">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input
                    type="text"
                    id="kanjiSearch"
                    class="kanji-search-input"
                    placeholder="Cari kanji, arti, atau bacaan (misal: air, 日, nichi, satu)..."
                    oninput="filterKanji(this.value)"
                    autocomplete="off"
                >
                <span id="kanjiCount" class="kanji-search-badge">{{ count($material->content['kanji']) }} kanji</span>
            </div>

            <div class="kanji-grid" id="kanjiGrid">
                @foreach($material->content['kanji'] as $k)
                <div class="kanji-card" data-keywords="{{ strtolower($k['kanji'] . ' ' . $k['meaning'] . ' ' . $k['kun'] . ' ' . $k['on'] . ' ' . ($k['category'] ?? '')) }}">
                    <div class="kanji-char">{{ $k['kanji'] }}</div>
                    <div class="kanji-info">
                        @if(!empty($k['category']))
                        <span class="kanji-tag">{{ $k['category'] }}</span>
                        @endif
                        <div class="kanji-meaning">{{ $k['meaning'] }}</div>
                        <div class="kanji-read">
                            <div><span>Kun:</span><span class="val">{{ $k['kun'] }}</span></div>
                            <div><span>On:</span><span class="val">{{ $k['on'] }}</span></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div id="kanjiEmpty" style="display:none;padding:40px 20px;text-align:center;color:var(--muted);background:var(--card);border-radius:14px;border:1px dashed var(--line);margin-top:10px">
                Tidak ada kanji yang cocok dengan pencarianmu.
            </div>
        </div>

    @elseif($material->category === 'grammar')
        <div class="section-block">
            <div class="section-block-title">Pola Kalimat Dasar ({{ count($material->content['patterns']) }} Pola)</div>
            <div class="grammar-list">
                @foreach($material->content['patterns'] as $pattern)
                <div class="grammar-card">
                    <div class="grammar-pattern">{{ $pattern['pattern'] }}</div>
                    <div class="grammar-romaji">{{ $pattern['romaji'] }}</div>
                    <div class="grammar-meaning">{{ $pattern['meaning'] }}</div>
                    @if(!empty($pattern['explanation']))
                    <div class="grammar-desc">{{ $pattern['explanation'] }}</div>
                    @endif
                    <div class="grammar-example">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;margin-right:4px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <strong>Contoh:</strong> {{ $pattern['example'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

<!-- Action bar -->
<div class="action-bar">
    <div class="action-bar-info">
        @if($progress->completed)
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1f5b45" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;margin-right:6px"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <strong>Selesai!</strong> Kamu sudah menyelesaikan materi ini.
        @else
            Setelah memahami semua materi, klik <strong>"Tandai Selesai"</strong> untuk mencatat progresmu.
        @endif
    </div>
    <div class="action-btns">
        @if($quiz)
        <a href="{{ route('quiz.show', $quiz->id) }}" class="btn btn-ghost">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><circle cx="12" cy="12" r="10"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            Kerjakan Quiz
        </a>
        @endif

        @if($progress->completed)
            <span class="btn btn-done">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Sudah Selesai
            </span>
        @else
            <form method="POST" action="{{ route('material.complete', $material->slug) }}">
                @csrf
                <button type="submit" class="btn btn-red">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Tandai Selesai
                </button>
            </form>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const fill = document.getElementById('pb-fill');
        if (fill) {
            const target = fill.dataset.target;
            setTimeout(() => { fill.style.width = target; }, 150);
        }
    });

    function filterKanji(q) {
        q = (q || '').trim().toLowerCase();
        const cards = document.querySelectorAll('#kanjiGrid .kanji-card');
        let visible = 0;
        cards.forEach(card => {
            const text = card.getAttribute('data-keywords') || '';
            const match = !q || text.includes(q);
            card.style.display = match ? 'flex' : 'none';
            if (match) visible++;
        });
        const badge = document.getElementById('kanjiCount');
        if (badge) badge.textContent = visible + ' kanji';
        const empty = document.getElementById('kanjiEmpty');
        if (empty) empty.style.display = visible === 0 ? 'block' : 'none';
    }
</script>
</body>
</html>
