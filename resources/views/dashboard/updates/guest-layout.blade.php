<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.seo', [
        'title' => ($pageTitle ?? 'Info Pembaruan & Versi') . ' — JP Learn',
        'description' => 'Catatan rilis fitur, versi aplikasi, riwayat pembaruan sistem, dan peta jalan (roadmap) pengembangan platform belajar bahasa Jepang JP Learn.',
        'keywords' => 'catatan rilis jp learn, update fitur jp learn, roadmap belajar bahasa jepang, versi aplikasi jp learn',
        'canonical' => route('updates.index'),
        'ogType' => 'website',
    ])
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans+JP:wght@500;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #fff8f6;
            --card: #ffffff;
            --ink: #1a0a0e;
            --ink-2: #4b4766;
            --muted: #9a6878;
            --line: #f0d5d0;
            --red: #c4102e;
            --red-hover: #a00d25;
            --red-2: #e8234f;
            --red-soft: #fde8ec;
            --navy: #17143a;
            --navy-2: #242050;
            --green: #1f5b45;
            --green-soft: #e6f4ee;
            --gold: #c8800a;
            --gold-soft: #fef6e0;
            --blue: #2d3a8c;
            --blue-soft: #e8ecf8;
            --radius: 18px;
            --shadow: 0 12px 34px -24px rgba(180,10,40,.36);
            --shadow-sm: 0 4px 16px -6px rgba(60,20,40,.12);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            color: var(--ink);
            background: var(--bg);
            font: 14px/1.55 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
            display: flex;
            flex-direction: column;
        }

        /* ── Guest Navigation Bar ── */
        .guest-nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255, 248, 246, 0.95);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--line);
            padding: 12px 24px;
        }
        .guest-nav-inner {
            max-width: 1140px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .guest-nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .btn-link {
            text-decoration: none;
            color: var(--ink-2);
            font-size: 13.5px;
            font-weight: 600;
            padding: 8px 14px;
            border-radius: 9px;
            transition: .2s;
        }
        .btn-link:hover {
            color: var(--red);
            background: rgba(196, 16, 46, 0.06);
        }
        .btn-primary-sm {
            text-decoration: none;
            background: linear-gradient(135deg, var(--red), var(--red-2));
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            padding: 8px 16px;
            border-radius: 10px;
            box-shadow: 0 4px 12px -2px rgba(196, 16, 46, 0.4);
            transition: .2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-primary-sm:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 18px -4px rgba(196, 16, 46, 0.55);
        }

        /* ── Main Container ── */
        .guest-content {
            flex: 1;
            max-width: 1140px;
            width: 100%;
            margin: 0 auto;
            padding: 28px 24px 60px;
        }

        /* ── Footer ── */
        .guest-footer {
            background: var(--navy);
            color: #c8c3e6;
            padding: 36px 24px;
            border-top: 1px solid rgba(255,255,255,.08);
            margin-top: auto;
        }
        .guest-footer-inner {
            max-width: 1140px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            font-size: 12.5px;
        }
        .guest-footer a {
            color: #e6b8c4;
            text-decoration: none;
            transition: .2s;
        }
        .guest-footer a:hover {
            color: #ffffff;
            text-decoration: underline;
        }
        @yield('styles')
    </style>
</head>
<body>
    <header class="guest-nav">
        <div class="guest-nav-inner">
            <a href="{{ route('landing') }}" class="brand" title="JP Learn">
                <img src="{{ asset('images/logo.png') }}" alt="JP Learn" style="height: 38px; width: auto; object-fit: contain;">
            </a>

            <div class="guest-nav-actions">
                <a href="{{ route('landing') }}" class="btn-link">Beranda</a>
                <a href="{{ route('login') }}" class="btn-link">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary-sm">
                    Daftar Akun Gratis
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <main class="guest-content">
        @yield('content')
    </main>

    <footer class="guest-footer">
        <div class="guest-footer-inner">
            <div>
                <strong>JP Learn</strong> — Platform Pembelajaran Bahasa Jepang Interaktif Modern
            </div>
            <div>
                <a href="{{ route('landing') }}">Beranda</a> • 
                <a href="{{ route('login') }}">Masuk</a> • 
                <a href="{{ route('register') }}">Daftar</a> • 
                <a href="{{ route('updates.index') }}">Catatan Rilis (v2.4.0)</a>
            </div>
        </div>
    </footer>

    @include('dashboard.partials.whats-new-modal')
    @yield('scripts')
</body>
</html>
