<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — JP Learn</title>
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
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            color: var(--ink);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            background:
                radial-gradient(900px 500px at 20% 5%, #fde4e4 0%, transparent 55%),
                radial-gradient(700px 500px at 90% 90%, #fbeee3 0%, transparent 55%),
                var(--bg);
            -webkit-font-smoothing: antialiased;
            padding: 24px;
        }
        .card {
            width: 100%; max-width: 440px;
            background: #fff; border: 1px solid var(--line);
            border-radius: 24px; box-shadow: 0 20px 50px -25px rgba(60,20,40,.25);
            padding: 44px 40px 40px;
        }
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
        .heading { margin-bottom: 28px; }
        .heading h1 { font-size: 22px; font-weight: 800; letter-spacing: -.03em; }
        .heading p { font-size: 13px; color: var(--muted); margin-top: 6px; }
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
            background: none; border: none; cursor: pointer; color: var(--muted); font-size: 16px; padding: 4px;
        }
        .error { font-size: 11px; color: var(--red); margin-top: 5px; }
        .alert { background: var(--red-soft); border: 1px solid #f5c6d0; border-radius: 10px; padding: 10px 14px; font-size: 12px; color: var(--red); margin-bottom: 18px; }
        .hint { font-size: 11px; color: var(--muted); margin-top: 5px; }

        /* Strength bar */
        .strength-bar { height: 4px; border-radius: 4px; background: var(--line); margin-top: 8px; overflow: hidden; }
        .strength-fill { height: 100%; width: 0; border-radius: 4px; transition: width .3s, background .3s; }

        .btn-submit {
            width: 100%; padding: 13px; border-radius: 12px; border: none; cursor: pointer;
            background: linear-gradient(135deg, var(--red), var(--red-2)); color: #fff;
            font: 600 15px 'Inter', sans-serif;
            box-shadow: 0 10px 24px -10px var(--red); transition: .25s; margin-top: 4px;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 16px 30px -10px var(--red); }
        .terms { font-size: 11px; color: var(--muted); text-align: center; margin-top: 14px; }
        .terms a { color: var(--red); text-decoration: none; }
        .divider { display: flex; align-items: center; gap: 10px; margin: 20px 0; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--line); }
        .divider span { font-size: 11px; color: var(--muted); }
        .alt { text-align: center; font-size: 13px; color: var(--muted); }
        .alt a { color: var(--red); font-weight: 600; text-decoration: none; }
        .alt a:hover { text-decoration: underline; }
        .kj { position: fixed; left: -30px; top: 50%; transform: translateY(-50%); font-family: 'Noto Sans JP', sans-serif;
              font-size: 280px; color: rgba(179,21,59,.04); pointer-events: none; user-select: none; }
        @media (max-width: 480px) { .card { padding: 32px 24px; } }
    </style>
</head>
<body>
    <span class="kj" aria-hidden="true">学</span>

    <div class="card">
        <a href="{{ route('landing') }}" class="brand">
            <span class="brand-mark">あ</span>
            JP<em>Learn</em>
        </a>

        <div class="heading">
            <h1>Buat akun gratis</h1>
            <p>Mulai belajar huruf Jepang hari ini.</p>
        </div>

        @if($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="reg-form">
            @csrf

            <div class="field">
                <label for="name">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       placeholder="Nama kamu" required autofocus>
                @error('name') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                       placeholder="kamu@email.com" required>
                @error('email') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="password">Kata Sandi</label>
                <div class="input-wrap">
                    <input type="password" name="password" id="password"
                           placeholder="Min. 8 karakter" required minlength="8">
                    <button type="button" class="eye-btn" id="eye1" aria-label="Tampilkan sandi">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                <div class="strength-bar"><div class="strength-fill" id="strength-fill"></div></div>
                @error('password') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="field">
                <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                <div class="input-wrap">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           placeholder="Ulangi kata sandi" required>
                    <button type="button" class="eye-btn" id="eye2" aria-label="Tampilkan sandi">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">Daftar Sekarang →</button>
            <p class="terms">Dengan mendaftar kamu setuju dengan <a href="#">Syarat & Ketentuan</a> kami.</p>
        </form>

        <div class="divider"><span>atau</span></div>
        <p class="alt">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
    </div>

    <script>
        // Toggle password visibility
        [['eye1','password'],['eye2','password_confirmation']].forEach(([btnId, inputId]) => {
            const btn = document.getElementById(btnId);
            const inp = document.getElementById(inputId);
            btn.addEventListener('click', () => {
                const show = inp.type === 'password';
                inp.type = show ? 'text' : 'password';
                btn.innerHTML = show
                    ? '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>'
                    : '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
            });
        });

        // Password strength
        const pwd = document.getElementById('password');
        const fill = document.getElementById('strength-fill');
        const colors = ['#e74c3c','#e67e22','#f1c40f','#2ecc71'];
        pwd.addEventListener('input', () => {
            let s = 0;
            if (pwd.value.length >= 8)  s++;
            if (/[A-Z]/.test(pwd.value)) s++;
            if (/[0-9]/.test(pwd.value)) s++;
            if (/[^A-Za-z0-9]/.test(pwd.value)) s++;
            fill.style.width = (s * 25) + '%';
            fill.style.background = colors[s - 1] || 'transparent';
        });
    </script>
</body>
</html>
