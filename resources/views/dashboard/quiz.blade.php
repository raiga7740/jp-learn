<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $quiz->title }} — JP Learn</title>
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
            --radius: 18px; --shadow: 0 20px 50px -25px rgba(60,20,40,.22); --shadow-sm: 0 4px 16px -6px rgba(60,20,40,.14);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif; color: var(--ink);
            background: var(--bg-soft); min-height: 100vh; -webkit-font-smoothing: antialiased;
        }
        .serif { font-family: 'Noto Sans JP', sans-serif; }

        /* Topbar */
        .topbar {
            background: rgba(253,246,242,.94); backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--line); padding: 12px 36px;
            display: flex; align-items: center; justify-content: space-between; gap: 14px;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-left { display: flex; align-items: center; gap: 14px; min-width: 0; }
        .back-btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 8px 15px; border-radius: 9px;
            border: 1px solid var(--line); background: var(--card);
            color: var(--ink-2); font-size: 12.5px; font-weight: 600;
            text-decoration: none; transition: .2s; flex-shrink: 0;
        }
        .back-btn:hover { border-color: var(--red); color: var(--red); }
        .quiz-title-bar {
            font-size: 15px; font-weight: 800; color: var(--ink);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 0;
        }
        .quiz-title-bar span { font-weight: 400; color: var(--muted); font-size: 12px; margin-left: 6px; }

        /* Timer */
        .timer {
            display: flex; align-items: center; gap: 7px;
            background: var(--card); border: 1px solid var(--line);
            padding: 7px 16px; border-radius: 999px; box-shadow: var(--shadow-sm);
            flex-shrink: 0;
        }
        .timer span:first-child { font-size: 13px; }
        .timer-val { font-size: 14px; font-weight: 900; letter-spacing: .06em; color: var(--navy); font-variant-numeric: tabular-nums; }
        .timer-val.urgent { color: var(--red); animation: blink 1s infinite; }
        @keyframes blink { 0%,100% { opacity: 1; } 50% { opacity: .4; } }

        /* Quiz layout */
        .quiz-wrap { max-width: 680px; margin: 0 auto; padding: 36px 20px 120px; }

        /* Dots & counter */
        .q-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .q-dots { display: flex; gap: 5px; flex-wrap: wrap; }
        .q-dot {
            width: 9px; height: 9px; border-radius: 50%;
            background: var(--line); border: 1px solid var(--line);
            cursor: pointer; transition: all .25s;
        }
        .q-dot.answered { background: var(--red); border-color: var(--red); box-shadow: 0 0 0 3px var(--red-soft); }
        .q-dot.current  { background: transparent; border-color: var(--red); transform: scale(1.4); }
        .q-counter { font-size: 12.5px; color: var(--muted); font-weight: 600; }

        /* Progress bar */
        .q-progress-bar { height: 5px; background: var(--line); border-radius: 999px; margin-bottom: 28px; overflow: hidden; }
        .q-progress-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--red), var(--red-2)); transition: width .4s; }

        /* Question card */
        .question-card {
            background: var(--card); border: 1px solid var(--line);
            border-radius: var(--radius); padding: 32px;
            box-shadow: var(--shadow-sm);
            animation: fadeUp .3s ease;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: none; }
        }
        .q-num {
            font-size: 10px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase;
            color: var(--red); margin-bottom: 14px;
            display: flex; align-items: center; gap: 6px;
        }
        .q-num::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: var(--red); }
        .q-text { font-size: 17px; font-weight: 700; margin-bottom: 18px; line-height: 1.6; color: var(--ink); }
        .q-kana {
            font-family: 'Noto Sans JP', sans-serif; font-size: 80px; font-weight: 900;
            text-align: center; line-height: 1; color: var(--navy);
            background: radial-gradient(300px at 50% 50%, #fbeee8, var(--bg-soft));
            border: 1px solid var(--line); border-radius: 16px;
            padding: 24px; margin-bottom: 24px;
        }

        /* Options */
        .options { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .opt-btn {
            display: flex; align-items: center; gap: 12px;
            padding: 14px 18px; border-radius: 12px;
            background: var(--bg); border: 1.5px solid var(--line);
            color: var(--ink); font-size: 14px; font-weight: 600;
            font-family: inherit; cursor: pointer; text-align: left;
            transition: all .2s;
        }
        .opt-btn:hover:not(:disabled) {
            border-color: var(--red);
            background: var(--red-soft);
            color: var(--red);
        }
        .opt-btn.selected {
            border-color: var(--navy);
            background: var(--blue-soft);
            color: var(--navy);
        }
        .opt-letter {
            width: 28px; height: 28px; border-radius: 8px; flex-shrink: 0;
            background: var(--card); border: 1px solid var(--line);
            display: grid; place-items: center;
            font-size: 11px; font-weight: 800; color: var(--muted);
            transition: all .2s;
        }
        .opt-btn.selected .opt-letter { background: var(--navy); border-color: var(--navy); color: #fff; }
        .opt-btn:hover:not(:disabled) .opt-letter { background: var(--red); border-color: var(--red); color: #fff; }

        /* Navigation */
        .q-nav { display: flex; align-items: center; justify-content: space-between; margin-top: 20px; gap: 10px; }
        .nav-btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 10px 22px; border-radius: 10px;
            font-size: 13px; font-weight: 700;
            cursor: pointer; font-family: inherit; transition: .22s;
        }
        .btn-prev { background: var(--card); border: 1px solid var(--line); color: var(--ink-2); }
        .btn-prev:hover { border-color: var(--navy); color: var(--navy); }
        .btn-next { background: linear-gradient(135deg, var(--red), var(--red-2)); border: none; color: #fff; box-shadow: 0 6px 18px -6px var(--red); }
        .btn-next:hover { transform: translateY(-1px); box-shadow: 0 10px 24px -8px var(--red); }
        .btn-next:disabled { opacity: .4; cursor: not-allowed; transform: none; }

        /* Submit box */
        .submit-box {
            background: var(--card); border: 1px solid var(--line);
            border-radius: var(--radius); padding: 28px;
            text-align: center; margin-top: 20px; display: none;
            box-shadow: var(--shadow-sm);
        }
        .submit-box.show { display: block; animation: fadeUp .3s ease; }
        .submit-box h3 { font-size: 18px; font-weight: 900; margin-bottom: 6px; color: var(--ink); }
        .submit-box p { font-size: 13px; color: var(--muted); margin-bottom: 16px; }
        .submit-counter {
            display: inline-block; background: var(--bg-soft);
            border: 1px solid var(--line); border-radius: 8px;
            padding: 7px 16px; font-size: 13px; color: var(--ink-2); margin-bottom: 18px;
        }
        .submit-counter strong { color: var(--red); }
        .btn-submit {
            padding: 14px 36px; border-radius: 12px;
            background: linear-gradient(135deg, var(--red), var(--red-2)); color: #fff;
            font-size: 15px; font-weight: 800; border: none; cursor: pointer;
            font-family: inherit; box-shadow: 0 8px 24px -8px var(--red);
            transition: .22s; display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 14px 32px -10px var(--red); }

        @media (max-width: 600px) {
            .topbar { padding: 10px 14px; gap: 8px; }
            .quiz-wrap { padding: 20px 12px 100px; }
            .quiz-title-bar span { display: none; }
            .options { grid-template-columns: 1fr; }
            .question-card { padding: 20px 16px; }
            .q-kana { font-size: 56px; padding: 16px; }
            .timer { padding: 6px 12px; }
            .timer-val { font-size: 13px; }
        }
    </style>
</head>
<body>

<header class="topbar">
    <div class="topbar-left">
        <a href="{{ route('dashboard.quizzes') }}" class="back-btn" onclick="return confirm('Yakin ingin keluar? Progres quiz saat ini tidak akan tersimpan.')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Keluar
        </a>
        <div class="quiz-title-bar">
            {{ $quiz->title }}
            <span>{{ $quiz->questions->count() }} soal</span>
        </div>
    </div>
    <div class="timer">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <span class="timer-val" id="timer">{{ gmdate('i:s', $quiz->time_limit) }}</span>
    </div>
</header>

<form method="POST" action="{{ route('quiz.submit', $quiz->id) }}" id="quiz-form">
    @csrf
    <input type="hidden" name="time_taken" id="time-taken" value="0">

    <div class="quiz-wrap">

        <!-- Dots & counter -->
        <div class="q-header">
            <div class="q-dots">
                @foreach($quiz->questions as $i => $q)
                <div class="q-dot {{ $i === 0 ? 'current' : '' }}" id="dot-{{ $i }}" onclick="goTo({{ $i }})"></div>
                @endforeach
            </div>
            <div class="q-counter">Soal <span id="cur-num">1</span> / {{ $quiz->questions->count() }}</div>
        </div>
        <div class="q-progress-bar">
            <div class="q-progress-fill" id="q-progress" style="width: {{ 100/$quiz->questions->count() }}%"></div>
        </div>

        <!-- Questions -->
        @foreach($quiz->questions as $i => $question)
        <div class="question-card" id="qcard-{{ $i }}" style="{{ $i > 0 ? 'display:none' : '' }}">
            <div class="q-num">Pertanyaan {{ $i + 1 }}</div>
            <div class="q-text">{{ $question->question }}</div>

            @if($question->question_jp)
            <div class="q-kana serif">{{ $question->question_jp }}</div>
            @endif

            <div class="options">
                @php $letters = ['A','B','C','D']; @endphp
                @foreach($question->options as $oi => $option)
                <button type="button" class="opt-btn"
                    id="opt-{{ $i }}-{{ $oi }}"
                    data-q="{{ $i }}"
                    onclick="pick({{ $i }}, '{{ addslashes($option) }}', this)">
                    <span class="opt-letter">{{ $letters[$oi] ?? ($oi+1) }}</span>
                    {{ $option }}
                </button>
                @endforeach
            </div>
            <input type="hidden" name="answers[{{ $question->id }}]" id="ans-{{ $i }}" value="">
        </div>
        @endforeach

        <!-- Nav -->
        <div class="q-nav" id="q-nav">
            <button type="button" class="nav-btn btn-prev" onclick="prev()" id="btn-prev" style="visibility:hidden">← Sebelumnya</button>
            <button type="button" class="nav-btn btn-next" onclick="next()" id="btn-next">Selanjutnya →</button>
        </div>

        <!-- Submit -->
        <div class="submit-box" id="submit-box">
            <h3>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;margin-right:6px"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Siap Mengumpulkan?
            </h3>
            <p>Pastikan semua soal sudah dijawab sebelum mengirim.</p>
            <div class="submit-counter"><strong id="ans-count">0</strong> / {{ $quiz->questions->count() }} soal dijawab</div><br>
            <button type="button" class="btn-submit" onclick="submitNow()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                Kumpulkan Jawaban
            </button>
        </div>

    </div>
</form>

<script>
    const totalQ   = {{ $quiz->questions->count() }};
    let cur        = 0;
    let answers    = {};
    let timeLeft   = {{ $quiz->time_limit }};
    const start    = Date.now();

    // Timer
    const timerEl = document.getElementById('timer');
    const ti = setInterval(() => {
        timeLeft--;
        const m = Math.floor(timeLeft / 60), s = timeLeft % 60;
        timerEl.textContent = `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
        timerEl.classList.toggle('urgent', timeLeft <= 30);
        if (timeLeft <= 0) { clearInterval(ti); submitNow(); }
    }, 1000);

    function show(idx) {
        document.querySelectorAll('.question-card').forEach((el, i) => el.style.display = i === idx ? 'block' : 'none');
        document.querySelectorAll('.q-dot').forEach((d, i) => {
            d.classList.remove('current');
            d.classList.toggle('answered', !!answers[i]);
            if (i === idx) d.classList.add('current');
        });
        document.getElementById('cur-num').textContent = idx + 1;
        document.getElementById('q-progress').style.width = ((idx+1)/totalQ*100) + '%';
        document.getElementById('btn-prev').style.visibility = idx > 0 ? 'visible' : 'hidden';
        document.getElementById('btn-next').style.display    = idx < totalQ-1 ? 'inline-flex' : 'none';
        document.getElementById('submit-box').classList.toggle('show', idx === totalQ-1);
        updateCount();
    }
    function next() { if (cur < totalQ-1) { cur++; show(cur); } }
    function prev() { if (cur > 0) { cur--; show(cur); } }
    function goTo(i) { cur = i; show(i); }

    function pick(qIdx, val, btn) {
        document.querySelectorAll(`[data-q="${qIdx}"]`).forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        answers[qIdx] = val;
        document.getElementById('ans-' + qIdx).value = val;
        document.getElementById('dot-' + qIdx).classList.add('answered');
        updateCount();
        if (qIdx < totalQ-1) setTimeout(() => { cur = qIdx+1; show(cur); }, 380);
    }
    function updateCount() {
        const el = document.getElementById('ans-count');
        if (el) el.textContent = Object.keys(answers).length;
    }
    function submitNow() {
        clearInterval(ti);
        document.getElementById('time-taken').value = Math.round((Date.now()-start)/1000);
        document.getElementById('quiz-form').submit();
    }
    show(0);
</script>
</body>
</html>
