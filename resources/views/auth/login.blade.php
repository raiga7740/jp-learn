<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — JP Learn</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,800;1,900&family=Noto+Sans+JP:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #fdf6f2;
            --bg-soft: #faeee8;
            --ink: #1c1838;
            --ink-2: #4b4766;
            --muted: #8a849c;
            --line: #efe2dc;
            --red: #b3153b;
            --red-2: #d42a52;
            --red-soft: #fbe3e8;
            --navy: #17143a;
            --radius: 16px;
            --shadow: 0 20px 50px -25px rgba(60,20,40,.25);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            color: var(--ink);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            background:
                radial-gradient(900px 500px at 80% 10%, #fde4e4 0%, transparent 55%),
                radial-gradient(700px 500px at 10% 90%, #fbeee3 0%, transparent 55%),
                var(--bg);
            -webkit-font-smoothing: antialiased;
            padding: 24px;
        }

        /* ── Card ── */
        .card {
            width: 100%; max-width: 440px;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 24px;
            box-shadow: var(--shadow);
            padding: 44px 40px 40px;
        }

        /* ── Brand ── */
        .brand {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none; color: var(--ink);
            font-weight: 800; font-size: 17px; letter-spacing: -.02em;
            margin-bottom: 32px;
        }
        .brand-mark {
            width: 36px; height: 36px; border-radius: 10px; display: grid; place-items: center;
            background: linear-gradient(135deg, var(--red), var(--red-2)); color: #fff;
            font-family: 'Noto Sans JP', sans-serif; font-size: 18px; font-weight: 700;
            box-shadow: 0 8px 18px -8px var(--red);
        }
        .brand em { font-style: normal; color: var(--red); }

        /* ── Heading ── */
        .heading { margin-bottom: 28px; }
        .heading h1 { font-size: 22px; font-weight: 800; letter-spacing: -.03em; }
        .heading p { font-size: 13px; color: var(--muted); margin-top: 6px; }

        /* ── Form ── */
        .field { margin-bottom: 16px; }
        label { display: block; font-size: 12px; font-weight: 600; color: var(--ink-2); margin-bottom: 6px; letter-spacing: .02em; }
        input[type=email], input[type=text], input[type=password] {
            width: 100%; padding: 11px 14px; border-radius: 10px;
            border: 1.5px solid var(--line); background: var(--bg);
            font: 14px 'Inter', sans-serif; color: var(--ink);
            transition: border-color .2s, box-shadow .2s; outline: none;
        }
        input:focus { border-color: var(--red); box-shadow: 0 0 0 3px rgba(179,21,59,.1); }
        .input-wrap { position: relative; }
        .eye-btn {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; color: var(--muted);
            padding: 4px; font-size: 16px; line-height: 1;
        }

        /* ── Error ── */
        .error { font-size: 11px; color: var(--red); margin-top: 5px; }
        .alert {
            background: var(--red-soft); border: 1px solid #f5c6d0; border-radius: 10px;
            padding: 10px 14px; font-size: 12px; color: var(--red); margin-bottom: 18px;
        }

        /* ── Remember / Forgot ── */
        .row-mid {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 20px;
        }
        .check-label { display: flex; align-items: center; gap: 7px; font-size: 12px; color: var(--ink-2); cursor: pointer; }
        .check-label input { accent-color: var(--red); }
        .forgot { font-size: 12px; color: var(--red); text-decoration: none; font-weight: 600; }
        .forgot:hover { text-decoration: underline; }

        /* ── Button ── */
        .btn-submit {
            width: 100%; padding: 13px; border-radius: 12px; border: none; cursor: pointer;
            background: linear-gradient(135deg, var(--red), var(--red-2)); color: #fff;
            font: 600 15px 'Inter', sans-serif; letter-spacing: .01em;
            box-shadow: 0 10px 24px -10px var(--red); transition: .25s;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 16px 30px -10px var(--red); }
        .btn-submit:active { transform: none; }

        /* ── Divider ── */
        .divider { display: flex; align-items: center; gap: 10px; margin: 20px 0; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--line); }
        .divider span { font-size: 11px; color: var(--muted); }

        /* ── Footer link ── */
        .alt { text-align: center; font-size: 13px; color: var(--muted); }
        .alt a { color: var(--red); font-weight: 600; text-decoration: none; }
        .alt a:hover { text-decoration: underline; }

        /* ── Kanji watermark ── */
        .kj { position: fixed; right: -20px; bottom: -20px; font-family: 'Noto Sans JP', sans-serif;
              font-size: 280px; color: rgba(179,21,59,.04); pointer-events: none; user-select: none; }

        @media (max-width: 480px) {
            .card { padding: 32px 24px; }
        }
    </style>
</head>
<body>
    <span class="kj" aria-hidden="true">入</span>

    <div class="card">
        <a href="{{ route('landing') }}" class="brand" style="display:inline-flex; margin-bottom:20px;">
            <img src="{{ asset('images/logo.png') }}" alt="JP Learn" style="height: 50px; width: auto; object-fit: contain;">
        </a>

        <div class="heading">
            <h1>Selamat datang kembali</h1>
            <p>Masuk untuk melanjutkan perjalanan belajarmu.</p>
        </div>

        @if($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="login-form">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                       placeholder="kamu@email.com" required autofocus>
                @error('email') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="password">Kata Sandi</label>
                <div class="input-wrap">
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
                    <button type="button" class="eye-btn" id="eye-btn" aria-label="Tampilkan sandi">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                @error('password') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="row-mid">
                <label class="check-label">
                    <input type="checkbox" name="remember"> Ingat saya
                </label>
                <a href="#" class="forgot">Lupa sandi?</a>
            </div>

            <button type="submit" class="btn-submit">Masuk →</button>
        </form>

        <div class="divider"><span>atau</span></div>

        <p class="alt">Belum punya akun? <a href="{{ route('register') }}">Daftar gratis</a></p>
    </div>

    <script>
        const eye = document.getElementById('eye-btn');
        const pwd = document.getElementById('password');
        eye.addEventListener('click', () => {
            const show = pwd.type === 'password';
            pwd.type = show ? 'text' : 'password';
            eye.innerHTML = show
                ? '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>'
                : '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
        });
    </script>
</body>
</html>
