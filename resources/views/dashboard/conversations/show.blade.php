@extends('dashboard.layout')

@php($pageTitle = 'Percakapan: ' . $scenario['title'])
@php($pageSubtitle = 'Simulasi dialog interaktif dengan audio pelafalan dan kuis respon.')

@section('styles')
    .kaiwa-container {
        max-width: 860px;
        margin: 0 auto;
    }

    /* ── Top Navigation Bar ── */
    .kaiwa-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .btn-back-kaiwa {
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
    .btn-back-kaiwa:hover {
        border-color: var(--red);
        color: var(--red);
        background: var(--red-soft);
    }
    .kaiwa-controls {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .kaiwa-toggle-btn {
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
    .kaiwa-toggle-btn:hover {
        background: #fdf2f4;
        border-color: var(--red);
    }
    .kaiwa-toggle-btn.active {
        background: var(--red-soft);
        color: var(--red);
        border-color: var(--red);
    }

    /* ── Character Context Banner ── */
    .characters-banner {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        align-items: center;
        gap: 16px;
        padding: 16px 20px;
        background: #fff;
        border: 1px solid var(--line);
        border-radius: var(--radius);
        margin-bottom: 24px;
        box-shadow: var(--shadow);
    }
    .char-profile {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .char-profile.right {
        flex-direction: row-reverse;
        text-align: right;
    }
    .char-avatar-lg {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        font-size: 18px;
        font-weight: 900;
        flex-shrink: 0;
        box-shadow: 0 4px 12px -4px rgba(0,0,0,.15);
    }
    .char-name-lg {
        font-size: 14px;
        font-weight: 800;
        color: var(--ink);
    }
    .char-role-lg {
        font-size: 11.5px;
        color: var(--muted);
        font-weight: 600;
    }
    .vs-badge {
        font-size: 12px;
        font-weight: 800;
        color: var(--muted);
        padding: 4px 10px;
        border-radius: 999px;
        background: #f4eff6;
    }

    /* ── Interactive Chat Flow ── */
    .chat-box {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-bottom: 28px;
    }

    .chat-row {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        max-width: 85%;
        animation: messageFade .35s ease;
    }
    @keyframes messageFade {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: none; }
    }
    .chat-row.left {
        align-self: flex-start;
    }
    .chat-row.right {
        align-self: flex-end;
        flex-direction: row-reverse;
    }

    .bubble-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        font-size: 14px;
        font-weight: 900;
        flex-shrink: 0;
        box-shadow: 0 2px 8px -2px rgba(0,0,0,.12);
    }
    .bubble-content {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .bubble-speaker-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--muted);
        padding: 0 4px;
    }
    .chat-row.right .bubble-speaker-label {
        text-align: right;
    }

    .bubble-card {
        padding: 14px 18px;
        border-radius: 18px;
        position: relative;
        background: #ffffff;
        border: 1px solid var(--line);
        box-shadow: 0 4px 14px -6px rgba(0,0,0,.06);
        transition: all .2s;
    }
    .chat-row.left .bubble-card {
        border-top-left-radius: 4px;
    }
    .chat-row.right .bubble-card {
        border-top-right-radius: 4px;
        background: #fbf5f7;
        border-color: #f1cfd7;
    }
    .bubble-card.is-speaking {
        box-shadow: 0 0 0 3px rgba(196,16,46,.3);
        border-color: var(--red);
    }

    .bubble-japanese {
        font-family: 'Noto Sans JP', sans-serif;
        font-size: 17px;
        font-weight: 700;
        color: var(--ink);
        line-height: 1.5;
        margin-bottom: 4px;
    }
    .bubble-romaji {
        font-size: 12.5px;
        color: var(--blue);
        font-weight: 600;
        line-height: 1.4;
        transition: opacity .18s;
    }
    .bubble-romaji.hidden {
        display: none;
    }
    .bubble-indonesian {
        font-size: 13px;
        color: #4b3e47;
        line-height: 1.45;
        margin-top: 4px;
        padding-top: 6px;
        border-top: 1px dashed rgba(0,0,0,.08);
        transition: opacity .18s;
    }
    .bubble-indonesian.hidden {
        display: none;
    }

    .bubble-play-btn {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: none;
        background: var(--red-soft);
        color: var(--red);
        cursor: pointer;
        display: grid;
        place-items: center;
        margin-top: 8px;
        transition: all .18s;
    }
    .bubble-play-btn:hover {
        background: var(--red);
        color: #fff;
        transform: scale(1.1);
    }

    /* ── Roleplay Quiz Card in Chat ── */
    .roleplay-quiz-card {
        width: 100%;
        max-width: 600px;
        margin: 12px auto;
        padding: 20px 24px;
        border-radius: 20px;
        background: #fff;
        border: 2px dashed #e4b5c1;
        box-shadow: 0 10px 28px -12px rgba(196,16,46,.18);
        animation: popIn .3s cubic-bezier(0.2, 0.85, 0.4, 1.15);
    }
    @keyframes popIn {
        from { opacity: 0; transform: scale(.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .rp-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 3px 10px;
        border-radius: 999px;
        background: var(--gold-soft);
        color: var(--gold);
        font-size: 11px;
        font-weight: 800;
        margin-bottom: 10px;
    }
    .rp-prompt {
        font-size: 14px;
        font-weight: 800;
        color: var(--ink);
        margin-bottom: 14px;
    }
    .rp-options {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .rp-option-btn {
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid var(--line);
        background: #fff;
        text-align: left;
        cursor: pointer;
        transition: all .18s;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .rp-option-btn:hover {
        border-color: var(--red);
        background: #fffafa;
    }
    .rp-option-btn.correct {
        border-color: var(--green);
        background: var(--green-soft);
    }
    .rp-option-btn.wrong {
        border-color: var(--red);
        background: var(--red-soft);
    }
    .rp-opt-text {
        font-family: 'Noto Sans JP', sans-serif;
        font-size: 14.5px;
        font-weight: 800;
        color: var(--ink);
    }
    .rp-opt-romaji {
        font-size: 11.5px;
        color: var(--muted);
    }
    .rp-feedback {
        margin-top: 12px;
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 600;
        display: none;
    }
    .rp-feedback.success {
        display: block;
        background: var(--green-soft);
        color: var(--green);
        border: 1px solid #b7dfca;
    }
    .rp-feedback.error {
        display: block;
        background: var(--red-soft);
        color: var(--red);
        border: 1px solid #f2c0ca;
    }

    /* ── Key Phrases Card ── */
    .phrases-card {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: var(--shadow);
        margin-top: 32px;
    }
    .phrases-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--ink);
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .phrases-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 12px;
    }
    .phrase-item {
        padding: 12px 14px;
        border-radius: 12px;
        background: #fff;
        border: 1px solid var(--line);
        display: flex;
        flex-direction: column;
        gap: 3px;
    }
    .phrase-jp {
        font-family: 'Noto Sans JP', sans-serif;
        font-size: 15px;
        font-weight: 800;
        color: var(--ink);
    }
    .phrase-romaji {
        font-size: 11.5px;
        color: var(--blue);
        font-weight: 700;
    }
    .phrase-meaning {
        font-size: 12px;
        color: var(--muted);
    }

    @media (max-width: 640px) {
        .characters-banner { grid-template-columns: 1fr; gap: 10px; }
        .char-profile.right { flex-direction: row; text-align: left; }
        .vs-badge { display: none; }
        .chat-row { max-width: 95%; }
    }
@endsection

@section('content')
<div class="kaiwa-container">
    <!-- Top Navigation Bar -->
    <div class="kaiwa-topbar">
        <a href="{{ route('conversation.index') }}" class="btn-back-kaiwa" title="Kembali ke daftar skenario">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Daftar Topik
        </a>

        <div class="kaiwa-controls">
            <button type="button" class="kaiwa-toggle-btn active" id="btnToggleRomaji" title="Sembunyikan / Tampilkan Romaji">
                <span>Romaji</span>
            </button>

            <button type="button" class="kaiwa-toggle-btn active" id="btnToggleIndonesian" title="Sembunyikan / Tampilkan Terjemahan">
                <span>Terjemahan</span>
            </button>

            <button type="button" class="kaiwa-toggle-btn" id="btnAutoplay" title="Putar percakapan otomatis dari awal">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                </svg>
                <span id="autoplayLabel">Putar Dialog</span>
            </button>
        </div>
    </div>

    <!-- Character Context Banner -->
    <div class="characters-banner">
        <div class="char-profile left">
            <div class="char-avatar-lg" style="background: {{ $scenario['speakers']['left']['bg'] }}; color: {{ $scenario['speakers']['left']['color'] }}">
                {{ $scenario['speakers']['left']['avatar'] }}
            </div>
            <div>
                <div class="char-name-lg">{{ $scenario['speakers']['left']['name'] }}</div>
                <div class="char-role-lg">{{ $scenario['speakers']['left']['role'] }}</div>
            </div>
        </div>

        <span class="vs-badge">Percakapan</span>

        <div class="char-profile right">
            <div class="char-avatar-lg" style="background: {{ $scenario['speakers']['right']['bg'] }}; color: {{ $scenario['speakers']['right']['color'] }}">
                {{ $scenario['speakers']['right']['avatar'] }}
            </div>
            <div>
                <div class="char-name-lg">{{ $scenario['speakers']['right']['name'] }}</div>
                <div class="char-role-lg">{{ $scenario['speakers']['right']['role'] }}</div>
            </div>
        </div>
    </div>

    <!-- Chat Stream Flow -->
    <div class="chat-box" id="chatBox">
        @foreach($scenario['dialogues'] as $idx => $d)
            @php($sp = $scenario['speakers'][$d['speaker']])
            <div class="chat-row {{ $d['speaker'] }}" id="dialogue-row-{{ $d['id'] }}">
                <div class="bubble-avatar" style="background: {{ $sp['bg'] }}; color: {{ $sp['color'] }}">
                    {{ $sp['avatar'] }}
                </div>

                <div class="bubble-content">
                    <span class="bubble-speaker-label">{{ $d['character_name'] }}</span>

                    <div class="bubble-card" id="card-{{ $d['id'] }}">
                        <div class="bubble-japanese">{{ $d['japanese'] }}</div>
                        <div class="bubble-romaji">{{ $d['romaji'] }}</div>
                        <div class="bubble-indonesian">{{ $d['indonesian'] }}</div>

                        <button type="button" class="bubble-play-btn" data-audio="{{ $d['audio'] }}" data-id="{{ $d['id'] }}" title="Dengarkan pelafalan">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                                <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Roleplay Choice Card if attached --}}
                    @if(isset($d['quiz']))
                        <div class="roleplay-quiz-card" id="quiz-card-{{ $d['id'] }}">
                            <div class="rp-badge">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                                Giliran Kamu Memilih Respon
                            </div>
                            <div class="rp-prompt">{{ $d['quiz']['prompt'] }}</div>

                            <div class="rp-options">
                                @foreach($d['quiz']['options'] as $oIdx => $opt)
                                    <button type="button" 
                                            class="rp-option-btn" 
                                            data-correct="{{ $opt['is_correct'] ? 'true' : 'false' }}"
                                            data-expl="{{ $opt['explanation'] }}"
                                            data-quiz-id="{{ $d['id'] }}">
                                        <span class="rp-opt-text">{{ $opt['text'] }}</span>
                                        <span class="rp-opt-romaji">{{ $opt['romaji'] }}</span>
                                    </button>
                                @endforeach
                            </div>

                            <div class="rp-feedback" id="quiz-feedback-{{ $d['id'] }}"></div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Key Phrases Card -->
    <div class="phrases-card">
        <div class="phrases-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
            </svg>
            Ungkapan Kunci (Key Vocabulary & Expressions)
        </div>
        <div class="phrases-list">
            @foreach($scenario['key_phrases'] as $kp)
                <div class="phrase-item">
                    <span class="phrase-jp">{{ $kp['japanese'] }}</span>
                    <span class="phrase-romaji">{{ $kp['romaji'] }}</span>
                    <span class="phrase-meaning">{{ $kp['meaning'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
(function() {
    const rawDialogues = @json($scenario['dialogues']);

    let isRomajiVisible = true;
    let isIndoVisible = true;
    let isAutoplayRunning = false;
    let autoplayIndex = 0;

    const btnToggleRomaji = document.getElementById('btnToggleRomaji');
    const btnToggleIndonesian = document.getElementById('btnToggleIndonesian');
    const btnAutoplay = document.getElementById('btnAutoplay');
    const autoplayLabel = document.getElementById('autoplayLabel');

    // Toggle Romaji
    btnToggleRomaji.addEventListener('click', () => {
        isRomajiVisible = !isRomajiVisible;
        btnToggleRomaji.classList.toggle('active', isRomajiVisible);
        document.querySelectorAll('.bubble-romaji').forEach(el => {
            el.classList.toggle('hidden', !isRomajiVisible);
        });
    });

    // Toggle Indonesian
    btnToggleIndonesian.addEventListener('click', () => {
        isIndoVisible = !isIndoVisible;
        btnToggleIndonesian.classList.toggle('active', isIndoVisible);
        document.querySelectorAll('.bubble-indonesian').forEach(el => {
            el.classList.toggle('hidden', !isIndoVisible);
        });
    });

    // Speech Synthesis
    function speak(text, onEndCallback) {
        if (!('speechSynthesis' in window)) {
            if (onEndCallback) onEndCallback();
            return;
        }
        try {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'ja-JP';
            utterance.rate = 0.88;

            if (onEndCallback) {
                utterance.onend = onEndCallback;
                utterance.onerror = onEndCallback;
            }

            window.speechSynthesis.speak(utterance);
        } catch (e) {
            console.warn('Speech error:', e);
            if (onEndCallback) onEndCallback();
        }
    }

    // Play button on each bubble
    document.querySelectorAll('.bubble-play-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const audioText = btn.dataset.audio;
            const bubbleId = btn.dataset.id;
            const card = document.getElementById('card-' + bubbleId);

            // Highlight card
            document.querySelectorAll('.bubble-card').forEach(c => c.classList.remove('is-speaking'));
            if (card) card.classList.add('is-speaking');

            speak(audioText, () => {
                if (card) card.classList.remove('is-speaking');
            });
        });
    });

    // Autoplay conversation
    function runAutoplay() {
        if (autoplayIndex >= rawDialogues.length || !isAutoplayRunning) {
            stopAutoplay();
            return;
        }

        const d = rawDialogues[autoplayIndex];
        const card = document.getElementById('card-' + d.id);

        document.querySelectorAll('.bubble-card').forEach(c => c.classList.remove('is-speaking'));
        if (card) {
            card.classList.add('is-speaking');
            card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        speak(d.audio, () => {
            if (card) card.classList.remove('is-speaking');
            if (isAutoplayRunning) {
                autoplayIndex++;
                setTimeout(runAutoplay, 800);
            }
        });
    }

    function stopAutoplay() {
        isAutoplayRunning = false;
        autoplayIndex = 0;
        btnAutoplay.classList.remove('active');
        autoplayLabel.textContent = 'Putar Dialog';
        document.querySelectorAll('.bubble-card').forEach(c => c.classList.remove('is-speaking'));
    }

    btnAutoplay.addEventListener('click', () => {
        if (isAutoplayRunning) {
            stopAutoplay();
            if ('speechSynthesis' in window) window.speechSynthesis.cancel();
        } else {
            isAutoplayRunning = true;
            autoplayIndex = 0;
            btnAutoplay.classList.add('active');
            autoplayLabel.textContent = 'Berhenti ⏹';
            runAutoplay();
        }
    });

    // Roleplay Quiz options handler
    document.querySelectorAll('.rp-option-btn').forEach(optBtn => {
        optBtn.addEventListener('click', () => {
            const isCorrect = optBtn.dataset.correct === 'true';
            const expl = optBtn.dataset.expl;
            const quizId = optBtn.dataset.quizId;
            const feedbackEl = document.getElementById('quiz-feedback-' + quizId);
            const parent = optBtn.closest('.rp-options');

            parent.querySelectorAll('.rp-option-btn').forEach(b => {
                b.classList.remove('correct', 'wrong');
            });

            if (isCorrect) {
                optBtn.classList.add('correct');
                feedbackEl.className = 'rp-feedback success';
                feedbackEl.innerHTML = `<strong>Jawaban Tepat! 🎉</strong> ${expl}`;

                // Play celebration voice
                const optText = optBtn.querySelector('.rp-opt-text')?.textContent;
                if (optText) speak(optText);
            } else {
                optBtn.classList.add('wrong');
                feedbackEl.className = 'rp-feedback error';
                feedbackEl.innerHTML = `<strong>Kurang Tepat:</strong> ${expl}`;
            }
        });
    });
})();
</script>
@endsection

