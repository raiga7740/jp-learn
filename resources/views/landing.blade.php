<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JP Learn — Kuasai Huruf Jepang dengan Metode Flashcard Adaptif</title>
    <meta name="description" content="Kuasai 46 Hiragana, 46 Katakana, dan ratusan Kanji dengan flashcard adaptif, spaced repetition, dan audio penutur asli.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,800;1,900&family=Noto+Sans+JP:wght@500;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #fdf6f2;
            --bg-soft: #faeee8;
            --card: #ffffff;
            --ink: #1c1838;
            --ink-2: #4b4766;
            --muted: #8a849c;
            --line: #efe2dc;
            --red: #b3153b;
            --red-2: #d42a52;
            --red-soft: #fbe3e8;
            --navy: #17143a;
            --navy-2: #2a1f55;
            --green: #1f5b45;
            --gold: #f2b544;
            --radius: 18px;
            --shadow: 0 20px 50px -25px rgba(60, 20, 40, .25);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(900px 500px at 50% -10%, #fde4e4 0%, transparent 60%),
                radial-gradient(700px 500px at 100% 40%, #fbeee3 0%, transparent 60%),
                var(--bg);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        .serif { font-family: 'Noto Sans JP', sans-serif; }
        .container { width: min(1120px, 92%); margin: 0 auto; }
        section { padding: 70px 0; }
        .eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 11px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase;
            color: var(--red);
        }
        .eyebrow::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: var(--red); }
        .btn {
            display: inline-flex; align-items: center; gap: 8px; cursor: pointer;
            padding: 12px 22px; border-radius: 10px; font-weight: 600; font-size: 14px;
            border: 1px solid transparent; text-decoration: none; transition: .25s ease;
        }
        .btn-red { background: linear-gradient(135deg, var(--red), var(--red-2)); color: #fff; box-shadow: 0 10px 24px -10px var(--red); }
        .btn-red:hover { transform: translateY(-2px); box-shadow: 0 16px 30px -10px var(--red); }
        .btn-ghost { background: #fff; color: var(--ink); border-color: var(--line); }
        .btn-ghost:hover { border-color: var(--red); color: var(--red); }
        .btn-navy { background: var(--navy); color: #fff; }
        .btn-navy:hover { background: var(--navy-2); }
        .btn-green { background: var(--green); color: #fff; }
        .btn-green:hover { filter: brightness(1.15); }
        .btn-sm { padding: 8px 14px; font-size: 12px; border-radius: 8px; }
        .reveal { opacity: 0; transform: translateY(24px); transition: .8s cubic-bezier(.2,.7,.2,1); }
        .reveal.in { opacity: 1; transform: none; }

        /* HERO */
        .hero { text-align: center; padding: 70px 0 40px; }
        .pill {
            display: inline-flex; align-items: center; gap: 8px; padding: 6px 14px; border-radius: 999px;
            background: #fff; border: 1px solid var(--line); font-size: 11px; letter-spacing: .14em;
            font-weight: 600; text-transform: uppercase; color: var(--ink-2);
        }
        .pill .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--red); animation: pulse 2s infinite; }
        @keyframes pulse { 50% { box-shadow: 0 0 0 6px rgba(179,21,59,.15); } }
        .hero h1 { font-family: 'Noto Sans JP', sans-serif; font-size: clamp(32px, 5vw, 54px); line-height: 1.15; margin: 22px 0 0; font-weight: 700; }
        .hero h1 em {
            display: block; font-family: 'Inter', sans-serif; font-style: italic; color: var(--red);
            font-weight: 900; font-size: 1.2em; letter-spacing: -0.02em; margin-top: 10px;
            text-shadow: 0 4px 14px rgba(179, 21, 59, .25), 0 1px 2px rgba(60, 20, 40, .15);
        }
        .hero p.lead { max-width: 560px; margin: 18px auto 28px; color: var(--ink-2); font-size: 15px; }
        .hero-cta { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .stats {
            display: inline-grid; grid-template-columns: repeat(3, 1fr); margin-top: 36px;
            background: #fff; border: 1px solid var(--line); border-radius: 14px; box-shadow: var(--shadow);
        }
        .stats div { padding: 14px 34px; }
        .stats div + div { border-left: 1px solid var(--line); }
        .stats b { display: block; font-family: 'Noto Sans JP', sans-serif; font-size: 20px; }
        .stats span { font-size: 11px; color: var(--muted); }

        /* DEMO */
        .demo {
            position: relative; overflow: hidden; margin-top: 40px; background: linear-gradient(180deg, #fbefe9, #f8ebe4);
            border: 1px solid var(--line); border-radius: 24px; padding: 28px; box-shadow: var(--shadow);
        }
        .demo::after {
            content: '書'; position: absolute; right: 20px; bottom: -30px; font-family: 'Noto Sans JP', sans-serif;
            font-size: 180px; color: rgba(179,21,59,.06); pointer-events: none;
        }
        .demo-head { display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; text-align: left; }
        .demo-head h3 { font-family: 'Noto Sans JP', sans-serif; font-size: 20px; line-height: 1.25; }
        .tabs { display: flex; gap: 4px; background: #fff; padding: 4px; border-radius: 10px; border: 1px solid var(--line); }
        .tab { padding: 7px 14px; border: none; background: transparent; border-radius: 7px; font: 500 12px 'Inter'; color: var(--muted); cursor: pointer; transition: .2s; }
        .tab.active { background: var(--bg-soft); color: var(--ink); box-shadow: inset 0 0 0 1px var(--line); }
        .card-stage { perspective: 1200px; width: min(440px, 100%); margin: 28px auto 0; height: 300px; cursor: pointer; }
        .flash { position: relative; width: 100%; height: 100%; transition: transform .7s cubic-bezier(.3,.7,.2,1); transform-style: preserve-3d; }
        .flash.flipped { transform: rotateY(180deg); }
        .face {
            position: absolute; inset: 0; backface-visibility: hidden; border-radius: 16px;
            background: radial-gradient(400px 200px at 30% 0%, #2c2560, var(--navy));
            color: #fff; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 14px;
            box-shadow: 0 30px 60px -25px rgba(23,20,58,.7);
        }
        .face small { position: absolute; top: 16px; left: 18px; font-size: 10px; letter-spacing: .14em; color: #a9a3d1; text-transform: uppercase; }
        .glyph {
            width: 110px; height: 110px; display: grid; place-items: center; border-radius: 14px;
            background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.12);
            font-family: 'Noto Sans JP', sans-serif; font-size: 62px;
        }
        .face .hint { width: 55%; height: 10px; border-radius: 10px; background: rgba(255,255,255,.1); }
        .face.back { transform: rotateY(180deg); }
        .face.back b { font-size: 34px; font-family: 'Inter', sans-serif; }
        .demo-foot { display: flex; justify-content: space-between; align-items: center; margin-top: 22px; flex-wrap: wrap; gap: 12px; position: relative; z-index: 1; }
        .pager { display: flex; align-items: center; gap: 10px; font-size: 12px; color: var(--muted); }
        .icon-btn { width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--line); background: #fff; cursor: pointer; display: grid; place-items: center; transition: .2s; }
        .icon-btn:hover { border-color: var(--red); color: var(--red); }
        .grade { display: flex; gap: 8px; }

        /* PILLARS */
        .split-head { display: flex; justify-content: space-between; align-items: flex-end; gap: 30px; flex-wrap: wrap; margin-bottom: 30px; }
        .split-head h2 { font-family: 'Noto Sans JP', sans-serif; font-size: 28px; margin-top: 8px; }
        .split-head p { max-width: 380px; color: var(--ink-2); font-size: 13px; }
        .pillars { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; align-items: stretch; }
        .pillar {
            background: #fff; border: 1px solid var(--line); border-radius: var(--radius); padding: 26px;
            display: flex; flex-direction: column; transition: .3s;
        }
        .pillar:hover { transform: translateY(-6px); box-shadow: var(--shadow); }
        .pillar .ico { width: 36px; height: 36px; border-radius: 10px; display: grid; place-items: center; background: var(--red-soft); color: var(--red); font-size: 16px; }
        .pillar .tag { margin-top: 22px; font-size: 10px; letter-spacing: .14em; color: var(--red); font-weight: 700; text-transform: uppercase; }
        .pillar h3 { font-family: 'Noto Sans JP', sans-serif; font-size: 18px; margin: 6px 0 10px; }
        .pillar p { font-size: 13px; color: var(--ink-2); flex: 1; }
        .pillar .foot { display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--line); margin-top: 22px; padding-top: 14px; font-size: 11px; color: var(--muted); }
        .pillar.dark { background: radial-gradient(400px 260px at 100% 0%, #3a1d4f, var(--navy)); color: #fff; border: none; transform: translateY(-14px); box-shadow: 0 30px 60px -25px rgba(23,20,58,.8); }
        .pillar.dark:hover { transform: translateY(-20px); }
        .pillar.dark .ico { background: rgba(255,255,255,.1); color: #fff; }
        .pillar.dark .tag { color: #f28aa3; }
        .pillar.dark p { color: #c8c3e6; }
        .pillar.dark .foot { border-color: rgba(255,255,255,.12); color: #a9a3d1; }
        .curve { width: 100%; height: 50px; margin-top: 14px; }
        .curve path { stroke-dasharray: 400; stroke-dashoffset: 400; animation: draw 2.4s ease forwards infinite alternate; }
        @keyframes draw { to { stroke-dashoffset: 0; } }

        /* MATRIX */
        .matrix { background: #fff; border: 1px solid var(--line); border-radius: 24px; padding: 30px; box-shadow: var(--shadow); }
        .matrix-head { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 14px; margin-bottom: 22px; }
        .matrix-head .chip { display: inline-block; font-size: 10px; padding: 3px 10px; border-radius: 999px; background: var(--red-soft); color: var(--red); font-weight: 600; letter-spacing: .06em; }
        .matrix-head h2 { font-family: 'Noto Sans JP', sans-serif; font-size: 24px; margin-top: 8px; }
        .legend { display: flex; gap: 14px; font-size: 11px; color: var(--ink-2); }
        .legend i { display: inline-block; width: 7px; height: 7px; border-radius: 50%; margin-right: 5px; }
        .grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; }
        .cell {
            position: relative; aspect-ratio: 3.2/1; border-radius: 10px; background: var(--bg-soft); border: 1px solid var(--line);
            display: grid; place-items: center; font-family: 'Noto Sans JP', sans-serif; font-size: 22px; cursor: pointer; transition: .25s;
        }
        .cell:hover { background: var(--navy); color: #fff; transform: scale(1.04); }
        .cell::after { content: ''; position: absolute; top: 8px; right: 8px; width: 6px; height: 6px; border-radius: 50%; background: var(--st); }
        .cell .ro { position: absolute; bottom: 4px; left: 10px; font: 500 10px 'Inter'; color: var(--muted); opacity: 0; transition: .2s; }
        .cell:hover .ro { opacity: 1; color: #c8c3e6; }
        .matrix-foot { display: flex; justify-content: space-between; align-items: center; margin-top: 22px; flex-wrap: wrap; gap: 12px; font-size: 12px; color: var(--muted); }
        .matrix-tabs { display: inline-flex; margin-bottom: 18px; }
        .cell.empty { background: transparent; border: 1px dashed var(--line); cursor: default; pointer-events: none; }
        .cell.empty::after { display: none; }

        /* TESTIMONIALS */
        .center-head { text-align: center; margin-bottom: 36px; }
        .center-head h2 { font-family: 'Noto Sans JP', sans-serif; font-size: 28px; margin-top: 8px; line-height: 1.3; }
        .testis { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; }
        .testi { background: #fff; border: 1px solid var(--line); border-radius: var(--radius); padding: 24px; transition: .3s; }
        .testi:hover { transform: translateY(-5px); box-shadow: var(--shadow); }
        .stars { color: var(--red); letter-spacing: 2px; font-size: 13px; }
        .testi p { font-size: 13px; color: var(--ink-2); margin: 12px 0 18px; font-style: italic; }
        .who { display: flex; align-items: center; gap: 10px; border-top: 1px solid var(--line); padding-top: 14px; }
        .avatar { width: 34px; height: 34px; border-radius: 50%; display: grid; place-items: center; color: #fff; font-weight: 700; font-size: 13px; }
        .who b { display: block; font-size: 13px; }
        .who span { font-size: 11px; color: var(--muted); }

        /* CTA */
        .cta {
            position: relative; overflow: hidden; border-radius: 26px; padding: 56px 48px;
            background: radial-gradient(600px 300px at 100% 0%, #4a1c46, transparent 60%), linear-gradient(135deg, var(--navy), #0f0c2b);
            color: #fff; box-shadow: 0 40px 80px -30px rgba(23,20,58,.7);
        }
        .cta::after { content: '道'; position: absolute; right: 40px; bottom: -40px; font-family: 'Noto Sans JP', sans-serif; font-size: 220px; color: rgba(255,255,255,.06); }
        .cta .pill { background: rgba(255,255,255,.08); border-color: rgba(255,255,255,.15); color: #d9d4f2; }
        .cta h2 { font-family: 'Noto Sans JP', sans-serif; font-size: clamp(28px, 4vw, 40px); line-height: 1.2; margin: 18px 0 12px; max-width: 520px; }
        .cta p { color: #bdb7dc; max-width: 520px; font-size: 14px; margin-bottom: 26px; }
        .cta .btn-ghost { background: transparent; color: #fff; border-color: rgba(255,255,255,.25); }
        /* FOOTER */
        .site-footer {
            background: var(--navy); color: #c8c3e6;
            padding: 64px 0 0; margin-top: 0;
        }
        .footer-grid {
            display: grid; grid-template-columns: 1.8fr 1fr 1fr 1fr; gap: 48px;
            padding-bottom: 48px; border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .footer-brand .brand { margin-bottom: 16px; display: inline-flex; }
        .footer-brand .brand-mark { box-shadow: 0 8px 18px -8px rgba(179,21,59,.5); }
        .footer-brand p { font-size: 13px; line-height: 1.7; color: #9893b8; max-width: 260px; }
        .footer-col h4 {
            font-size: 11px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase;
            color: #fff; margin-bottom: 16px;
        }
        .footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 10px; }
        .footer-col a {
            font-size: 13px; color: #9893b8; text-decoration: none; transition: color .2s;
        }
        .footer-col a:hover { color: #fff; }
        .socials { display: flex; gap: 10px; margin-top: 20px; }
        .social-btn {
            width: 36px; height: 36px; border-radius: 10px; display: grid; place-items: center;
            background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
            color: #c8c3e6; text-decoration: none; font-size: 15px; transition: .25s;
        }
        .social-btn:hover { background: rgba(179,21,59,.3); border-color: rgba(179,21,59,.5); color: #fff; }
        .footer-bottom {
            display: flex; justify-content: space-between; align-items: center;
            padding: 20px 0; font-size: 12px; color: #6b6589; gap: 16px; flex-wrap: wrap;
        }
        .footer-bottom a { color: #9893b8; text-decoration: none; }
        .footer-bottom a:hover { color: #fff; }
        .footer-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.08);
            border-radius: 999px; padding: 4px 12px; font-size: 11px; color: #9893b8;
        }
        .footer-badge span { color: var(--red); font-size: 14px; }
        @media (max-width: 860px) {
            .footer-grid { grid-template-columns: 1fr 1fr; row-gap: 36px; }
            .footer-brand { grid-column: 1 / -1; }
            .footer-brand p { max-width: 100%; }
        }
        @media (max-width: 480px) {
            .footer-grid { grid-template-columns: 1fr; }
            .footer-bottom { flex-direction: column; text-align: center; }
        }

        /* NAVBAR */
        section[id], .demo { scroll-margin-top: 90px; }
        .nav {
            position: sticky; top: 0; z-index: 50; padding: 14px 0;
            transition: padding .3s ease, background .3s ease, box-shadow .3s ease, border-color .3s ease;
            border-bottom: 1px solid transparent;
        }
        .nav.scrolled {
            padding: 10px 0; background: rgba(253, 246, 242, .78);
            backdrop-filter: blur(14px) saturate(160%); -webkit-backdrop-filter: blur(14px) saturate(160%);
            border-color: var(--line); box-shadow: 0 10px 30px -20px rgba(60, 20, 40, .35);
        }
        .nav-inner { display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .brand { display: flex; align-items: center; gap: 10px; text-decoration: none; color: var(--ink); font-weight: 800; font-size: 17px; letter-spacing: -.02em; }
        .brand-mark {
            width: 36px; height: 36px; border-radius: 10px; display: grid; place-items: center;
            background: linear-gradient(135deg, var(--red), var(--red-2)); color: #fff;
            font-family: 'Noto Sans JP', sans-serif; font-size: 18px; font-weight: 700;
            box-shadow: 0 8px 18px -8px var(--red); transition: transform .4s cubic-bezier(.3,.7,.2,1.4);
        }
        .brand:hover .brand-mark { transform: rotate(-8deg) scale(1.06); }
        .brand span em { font-style: normal; color: var(--red); }
        .nav-links { display: flex; align-items: center; gap: 4px; list-style: none; padding: 5px; border-radius: 999px; background: rgba(255,255,255,.6); border: 1px solid var(--line); }
        .nav-links a {
            display: block; padding: 7px 16px; border-radius: 999px; text-decoration: none;
            font-size: 13px; font-weight: 500; color: var(--ink-2); transition: .25s;
        }
        .nav-links a:hover { color: var(--red); }
        .nav-links a.active { background: #fff; color: var(--ink); box-shadow: 0 4px 12px -6px rgba(60,20,40,.3); }
        .nav-actions { display: flex; align-items: center; gap: 8px; }
        .nav-login { font-size: 13px; font-weight: 600; color: var(--ink); text-decoration: none; padding: 8px 12px; transition: .2s; }
        .nav-login:hover { color: var(--red); }
        .nav-toggle { display: none; width: 40px; height: 40px; border-radius: 10px; border: 1px solid var(--line); background: #fff; cursor: pointer; position: relative; }
        .nav-toggle span, .nav-toggle span::before, .nav-toggle span::after {
            position: absolute; left: 11px; width: 18px; height: 2px; border-radius: 2px; background: var(--ink); content: ''; transition: .3s;
        }
        .nav-toggle span { top: 19px; }
        .nav-toggle span::before { left: 0; top: -6px; }
        .nav-toggle span::after { left: 0; top: 6px; }
        .nav.open .nav-toggle span { background: transparent; }
        .nav.open .nav-toggle span::before { top: 0; transform: rotate(45deg); }
        .nav.open .nav-toggle span::after { top: 0; transform: rotate(-45deg); }

        @media (max-width: 860px) {
            .nav-toggle { display: block; }
            .nav-links {
                position: absolute; top: calc(100% + 6px); left: 4%; right: 4%; flex-direction: column; align-items: stretch;
                border-radius: 16px; padding: 8px; background: rgba(255,255,255,.96); box-shadow: var(--shadow);
                opacity: 0; transform: translateY(-8px); pointer-events: none; transition: .25s;
            }
            .nav.open .nav-links { opacity: 1; transform: none; pointer-events: auto; }
            .nav-links a { padding: 12px 16px; border-radius: 10px; }
        }

        @media (max-width: 860px) {
            .pillars, .testis { grid-template-columns: 1fr; }
            .pillar.dark { transform: none; }
            .grid { grid-template-columns: repeat(5, 1fr); gap: 6px; }
            .cell { aspect-ratio: 1.4/1; font-size: 18px; }
            .stats div { padding: 12px 18px; }
            .cta { padding: 40px 26px; }
        }
    </style>
</head>
<body>
<header class="nav" id="navbar">
    <nav class="container nav-inner" aria-label="Navigasi utama">
        <a href="#" class="brand" id="nav-brand"><span class="brand-mark">あ</span><span>JP<em>Learn</em></span></a>
        <ul class="nav-links" id="nav-links">
            <li><a href="#demo" id="nav-demo">Demo</a></li>
            <li><a href="#fitur" id="nav-fitur">Fitur</a></li>
            <li><a href="#matrix" id="nav-matrix">Matriks</a></li>
            <li><a href="#testimoni" id="nav-testimoni">Testimoni</a></li>
        </ul>
        <div class="nav-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-red btn-sm" id="nav-dashboard" style="display:inline-flex;align-items:center;gap:6px">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-red btn-sm" id="nav-login">Masuk</a>
            @endauth
            <button class="nav-toggle" id="nav-toggle" aria-label="Buka menu" aria-expanded="false"><span></span></button>
        </div>
    </nav>
</header>
<main>
    <!-- HERO -->
    <section class="hero">
        <div class="container">
            <span class="pill reveal"><span class="dot"></span> Platform Belajar Huruf Jepang #1</span>
            <h1 class="reveal">Kuasai Huruf Jepang dengan <em>Metode Flashcard Adaptif</em></h1>
            <p class="lead reveal">Hafal 46 Hiragana, 46 Katakana, dan ratusan Kanji lebih cepat. Sistem kami menyesuaikan jadwal latihan dengan daya ingatmu, lengkap dengan audio penutur asli.</p>
            <div class="hero-cta reveal">
                <a href="#demo" class="btn btn-red" id="btn-start">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="5 3 19 12 5 21 5 3"/></svg> Mulai Belajar Gratis</a>
                <a href="#matrix" class="btn btn-ghost" id="btn-matrix">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg> Lihat Matriks Huruf</a>
            </div>
            <div class="stats reveal">
                <div><b>46</b><span>Hiragana</span></div>
                <div><b>98%</b><span>Retensi Ingatan</span></div>
                <div><b>15m</b><span>Latihan / Hari</span></div>
            </div>

            <!-- DEMO -->
            <div class="demo reveal" id="demo">
                <div class="demo-head">
                    <div>
                        <span class="eyebrow">Demo Interaktif</span>
                        <h3>Coba Kartu Hafalan<br>Sekarang</h3>
                    </div>
                    <div class="tabs" role="tablist">
                        <button class="tab active" data-set="hira" id="tab-hira">Hiragana</button>
                        <button class="tab" data-set="kata" id="tab-kata">Katakana</button>
                        <button class="tab" data-set="kanji" id="tab-kanji">Kanji Dasar</button>
                    </div>
                </div>
                <div class="card-stage" id="card-stage">
                    <div class="flash" id="flash">
                        <div class="face front">
                            <small id="card-label">Hiragana · 1/5</small>
                            <div class="glyph" id="glyph">あ</div>
                            <div class="hint"></div>
                        </div>
                        <div class="face back">
                            <small>Jawaban</small>
                            <b id="answer">a</b>
                            <span style="font-size:12px;color:#a9a3d1" id="meaning">vokal "a"</span>
                        </div>
                    </div>
                </div>
                <div class="demo-foot">
                    <div class="pager">
                        <button class="icon-btn" id="prev" aria-label="Sebelumnya">‹</button>
                        <span id="counter">1 / 46</span>
                        <button class="icon-btn" id="next" aria-label="Berikutnya">›</button>
                    </div>
                    <div class="grade">
                        <button class="btn btn-ghost btn-sm" id="btn-again">Ulangi</button>
                        <button class="btn btn-ghost btn-sm" id="btn-hard">Sulit</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PILLARS -->
    <section id="fitur">
        <div class="container">
            <div class="split-head reveal">
                <div>
                    <span class="eyebrow">Metodologi Kami</span>
                    <h2>Belajar Tanpa Lupa: 3 Pilar Utama</h2>
                </div>
                <p>Dirancang berdasarkan riset kognitif untuk memastikan setiap huruf yang kamu pelajari tersimpan di memori jangka panjang.</p>
            </div>
            <div class="pillars">
                <article class="pillar reveal">
                    <div class="ico">◧</div>
                    <span class="tag">Pilar 01</span>
                    <h3>Mnemonik Asosiasi Visual</h3>
                    <p>Setiap karakter dilengkapi gambar ilustrasi dan cerita singkat yang menghubungkan bentuk huruf dengan bunyinya, sehingga lebih mudah diingat.</p>
                    <div class="foot"><span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;margin-right:4px"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg> 92 ilustrasi mnemonik</span><span>→</span></div>
                </article>
                <article class="pillar dark reveal">
                    <div class="ico">◎</div>
                    <span class="tag">Pilar 02</span>
                    <h3>Spaced Repetition (SRS)</h3>
                    <p>Algoritma kami menjadwalkan ulang kartu tepat sebelum kamu lupa. Kartu yang sulit muncul lebih sering, kartu yang mudah dijeda lebih lama.</p>
                    <svg class="curve" viewBox="0 0 300 50" preserveAspectRatio="none">
                        <path d="M0 40 C 40 5, 60 45, 100 25 S 160 10, 200 30 S 260 5, 300 15" fill="none" stroke="#e8436b" stroke-width="2.5"/>
                    </svg>
                    <div class="foot"><span>Kurva Ingatan</span><span style="color:#7ee0b0">↑ Optimal</span></div>
                </article>
                <article class="pillar reveal">
                    <div class="ico">♪</div>
                    <span class="tag">Pilar 03</span>
                    <h3>Uji Refleks Cepat &amp; Audio</h3>
                    <p>Latih kecepatan mengenali huruf dengan kuis berwaktu dan dengarkan pelafalan asli dari penutur Jepang untuk setiap karakter.</p>
                    <div class="foot"><span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;margin-right:4px"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/></svg> Audio penutur asli</span><span style="color:var(--red)"><svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" stroke="none"><circle cx="12" cy="12" r="10"/></svg></span></div>
                </article>
            </div>
        </div>
    </section>

    <!-- MATRIX -->
    <section id="matrix">
        <div class="container">
            <div class="matrix reveal">
                <div class="matrix-head">
                    <div>
                        <span class="chip" id="matrix-chip">Hiragana</span> <small style="color:var(--muted);font-size:11px">· 46 huruf lengkap (gojūon)</small>
                        <h2>Pratinjau Matriks Huruf &amp; Status Penguasaan</h2>
                    </div>
                    <div class="legend">
                        <span><i style="background:var(--red)"></i>Dikuasai</span>
                        <span><i style="background:var(--gold)"></i>Dipelajari</span>
                        <span><i style="background:#c9c3d6"></i>Baru</span>
                    </div>
                </div>
                <div class="tabs matrix-tabs" role="tablist">
                    <button class="tab active" data-mset="hira" id="mtab-hira">Hiragana ひらがな</button>
                    <button class="tab" data-mset="kata" id="mtab-kata">Katakana カタカナ</button>
                </div>
                <div class="grid" id="grid"></div>
                <div class="matrix-foot">
                    <span>Klik huruf untuk melihat romaji. Status diperbarui otomatis setiap sesi latihan.</span>
                    <div style="display:flex;gap:8px">
                        <button class="btn btn-ghost btn-sm" id="btn-shuffle"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;margin-right:4px"><polyline points="16 3 21 3 21 8"/><line x1="4" y1="20" x2="21" y2="3"/><polyline points="21 16 21 21 16 21"/><line x1="15" y1="15" x2="21" y2="21"/></svg> Acak Urutan</button>
                        <a href="#demo" class="btn btn-navy btn-sm" id="btn-practice">Mulai Latihan Matriks →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section id="testimoni">
        <div class="container">
            <div class="center-head reveal">
                <span class="eyebrow">Kisah Sukses</span>
                <h2>Dicintai Oleh Ratusan Pembelajar<br>Otodidak</h2>
            </div>
            <div class="testis">
                <article class="testi reveal">
                    <div class="stars">
                        <svg width="70" height="14" viewBox="0 0 70 14" fill="#b3153b" xmlns="http://www.w3.org/2000/svg">
                            <text x="0" y="12" font-size="13" font-family="serif" letter-spacing="2">★★★★★</text>
                        </svg>
                    </div>
                    <p>"Dalam dua minggu saya sudah hafal semua Hiragana. Mnemonik visualnya benar-benar membantu, rasanya seperti main game."</p>
                    <div class="who"><div class="avatar" style="background:#b3153b">RA</div><div><b>Rina Aulia</b><span>Mahasiswi, Bandung</span></div></div>
                </article>
                <article class="testi reveal">
                    <div class="stars">
                        <svg width="70" height="14" viewBox="0 0 70 14" fill="#b3153b" xmlns="http://www.w3.org/2000/svg">
                            <text x="0" y="12" font-size="13" font-family="serif" letter-spacing="2">★★★★★</text>
                        </svg>
                    </div>
                    <p>"Sistem pengulangannya pintar. Huruf yang sering saya lupa muncul lagi di waktu yang pas. Persiapan JLPT N5 jadi jauh lebih ringan."</p>
                    <div class="who"><div class="avatar" style="background:#2a1f55">DP</div><div><b>Dimas Pratama</b><span>Karyawan, Jakarta</span></div></div>
                </article>
                <article class="testi reveal">
                    <div class="stars">
                        <svg width="70" height="14" viewBox="0 0 70 14" fill="#b3153b" xmlns="http://www.w3.org/2000/svg">
                            <text x="0" y="12" font-size="13" font-family="serif" letter-spacing="2">★★★★★</text>
                        </svg>
                    </div>
                    <p>"Audio penutur aslinya jernih sekali. Sekarang saya bisa membaca menu dan papan nama saat liburan ke Tokyo kemarin!"</p>
                    <div class="who"><div class="avatar" style="background:#1f5b45">SN</div><div><b>Sari Nurhaliza</b><span>Desainer, Surabaya</span></div></div>
                </article>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section id="daftar">
        <div class="container">
            <div class="cta reveal">
                <span class="pill"><span class="dot"></span> Gratis Selamanya untuk Hiragana</span>
                <h2>Mulai Perjalanan Bahasa Jepang Anda Hari Ini</h2>
                <p>Hanya 15 menit sehari untuk membangun fondasi yang kuat. Bergabunglah dengan ribuan pembelajar lainnya dan rasakan kemajuannya sejak sesi pertama.</p>
                <div class="hero-cta" style="justify-content:flex-start">
                    <a href="#demo" class="btn btn-red" id="btn-cta-start">Daftar Sekarang →</a>
                    <a href="#matrix" class="btn btn-ghost" id="btn-cta-curriculum">Lihat Kurikulum</a>
                </div>
            </div>
        </div>
    </section>
</main>
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">

            <!-- Brand -->
            <div class="footer-brand">
                <a href="{{ route('landing') }}" class="brand">
                    <span class="brand-mark">あ</span>
                    <span style="color:#fff;font-weight:800">JP<em style="font-style:normal;color:var(--red)">Learn</em></span>
                </a>
                <p>Platform belajar huruf Jepang berbasis flashcard adaptif. Kuasai Hiragana, Katakana, dan Kanji lebih cepat dengan metode spaced repetition.</p>
                <div class="socials">
                    <a href="#" class="social-btn" aria-label="Twitter">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.259 5.63 5.905-5.63Zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="#" class="social-btn" aria-label="Instagram">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    </a>
                    <a href="#" class="social-btn" aria-label="YouTube">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.95C5.12 20 12 20 12 20s6.88 0 8.59-.47a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="currentColor" stroke="none"/></svg>
                    </a>
                    <a href="#" class="social-btn" aria-label="Discord">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057c.002.022.015.043.034.055a19.9 19.9 0 0 0 5.993 3.03.077.077 0 0 0 .084-.028 14.09 14.09 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Belajar -->
            <div class="footer-col">
                <h4>Belajar</h4>
                <ul>
                    <li><a href="#demo">Demo Flashcard</a></li>
                    <li><a href="#matrix">Matriks Huruf</a></li>
                    <li><a href="#fitur">Fitur</a></li>
                    <li><a href="{{ route('register') }}">Daftar Gratis</a></li>
                    <li><a href="{{ route('login') }}">Masuk</a></li>
                </ul>
            </div>

            <!-- Huruf -->
            <div class="footer-col">
                <h4>Huruf</h4>
                <ul>
                    <li><a href="#matrix">Hiragana (46)</a></li>
                    <li><a href="#matrix">Katakana (46)</a></li>
                    <li><a href="#">Kanji Dasar</a></li>
                    <li><a href="#">Dakuten & Handakuten</a></li>
                    <li><a href="#">Kombinasi (You-on)</a></li>
                </ul>
            </div>

            <!-- Info -->
            <div class="footer-col">
                <h4>Info</h4>
                <ul>
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Kontak</a></li>
                </ul>
            </div>

        </div>

        <!-- Bottom bar -->
        <div class="footer-bottom">
            <span>© {{ date('Y') }} JPLearn. Seluruh hak cipta dilindungi.</span>
            <div style="display:flex;gap:20px">
                <a href="#">Privasi</a>
                <a href="#">Syarat</a>
                <a href="#">Cookie</a>
            </div>
            <span class="footer-badge"><span><svg width="12" height="12" viewBox="0 0 24 24" fill="#b3153b" stroke="none"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></span> Dibuat untuk Pembelajar Bahasa Jepang</span>
        </div>
    </div>
</footer>

<script>
    // Navbar: scroll state, mobile toggle, active link
    (() => {
        const nav = document.getElementById('navbar');
        const toggle = document.getElementById('nav-toggle');
        const links = [...document.querySelectorAll('.nav-links a')];
        const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 10);
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
        toggle.addEventListener('click', () => {
            const open = nav.classList.toggle('open');
            toggle.setAttribute('aria-expanded', open);
        });
        links.forEach(a => a.addEventListener('click', () => { nav.classList.remove('open'); toggle.setAttribute('aria-expanded', false); }));
        const targets = links.map(a => document.querySelector(a.getAttribute('href'))).filter(Boolean);
        const io = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (!e.isIntersecting) return;
                links.forEach(a => a.classList.toggle('active', a.getAttribute('href') === '#' + e.target.id));
            });
        }, { rootMargin: '-45% 0px -50% 0px' });
        targets.forEach(t => io.observe(t));
    })();

    // Tabel gojūon lengkap (_ = slot kosong)
    const ROMA = ['a','i','u','e','o','ka','ki','ku','ke','ko','sa','shi','su','se','so','ta','chi','tsu','te','to',
        'na','ni','nu','ne','no','ha','hi','fu','he','ho','ma','mi','mu','me','mo','ya','','yu','','yo',
        'ra','ri','ru','re','ro','wa','','','','wo','n','','','',''];
    const HIRA = [...'あいうえおかきくけこさしすせそたちつてとなにぬねのはひふへほまみむめもや_ゆ_よらりるれろわ___をん____'];
    const KATA = [...'アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤ_ユ_ヨラリルレロワ___ヲン____'];
    const table = chars => chars.map((c, i) => c === '_' ? null : [c, ROMA[i]]);
    const kanaTable = { hira: table(HIRA), kata: table(KATA) };
    const rowName = r => r === 'n' ? 'huruf N' : r.length === 1 ? `vokal "${r}"` : `baris ${r[0].toUpperCase()}`;
    const toCards = t => t.filter(Boolean).map(([c, r]) => [c, r, rowName(r)]);

    const sets = {
        hira: toCards(kanaTable.hira),
        kata: toCards(kanaTable.kata),
        kanji: [['日','nichi / hi','matahari, hari'],['月','getsu / tsuki','bulan'],['山','san / yama','gunung'],['水','sui / mizu','air'],['人','jin / hito','orang']]
    };
    const names = { hira: 'Hiragana', kata: 'Katakana', kanji: 'Kanji' };
    let set = 'hira', idx = 0;
    const flash = document.getElementById('flash');
    function render() {
        const c = sets[set][idx];
        flash.classList.remove('flipped');
        setTimeout(() => {
            glyph.textContent = c[0]; answer.textContent = c[1]; meaning.textContent = c[2];
            counter.textContent = `${idx + 1} / ${sets[set].length}`;
            document.getElementById('card-label').textContent = `${names[set]} · ${idx + 1}/${sets[set].length}`;
        }, 150);
    }
    const step = d => { idx = (idx + d + sets[set].length) % sets[set].length; render(); };
    document.getElementById('card-stage').onclick = () => flash.classList.toggle('flipped');
    prev.onclick = () => step(-1); next.onclick = () => step(1);
    ['btn-again','btn-hard'].forEach(id => document.getElementById(id).onclick = () => step(1));
    document.querySelectorAll('.demo .tab').forEach(t => t.onclick = () => {
        document.querySelectorAll('.demo .tab').forEach(x => x.classList.remove('active'));
        t.classList.add('active'); set = t.dataset.set; idx = 0; render();
    });

    const st = ['var(--red)','var(--red)','var(--gold)','#c9c3d6'];
    const grid = document.getElementById('grid');
    let mset = 'hira';
    function drawGrid(list) {
        grid.innerHTML = list.map((k, i) => k
            ? `<div class="cell" style="--st:${st[(i * 7) % 4]}">${k[0]}<span class="ro">${k[1]}</span></div>`
            : `<div class="cell empty" aria-hidden="true"></div>`).join('');
    }
    drawGrid(kanaTable[mset]);
    document.querySelectorAll('.matrix-tabs .tab').forEach(t => t.onclick = () => {
        document.querySelectorAll('.matrix-tabs .tab').forEach(x => x.classList.remove('active'));
        t.classList.add('active'); mset = t.dataset.mset;
        document.getElementById('matrix-chip').textContent = mset === 'hira' ? 'Hiragana' : 'Katakana';
        drawGrid(kanaTable[mset]);
    });
    document.getElementById('btn-shuffle').onclick = () => drawGrid(kanaTable[mset].filter(Boolean).sort(() => Math.random() - .5));

    const io = new IntersectionObserver(es => es.forEach(e => e.isIntersecting && e.target.classList.add('in')), { threshold: .12 });
    document.querySelectorAll('.reveal').forEach(el => io.observe(el));
</script>
</body>
</html>
