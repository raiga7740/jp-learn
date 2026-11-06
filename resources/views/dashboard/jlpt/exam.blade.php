@extends('dashboard.layout')

@php
    $pageTitle = 'Pengerjaan Ujian JLPT N5';
    $pageSubtitle = 'Kerjakan seluruh 30 soal simulasi sebelum waktu habis.';
@endphp

@section('styles')
    .exam-wrapper {
        max-width: 1140px;
        margin: 0 auto;
    }

    /* ── Sticky Top Exam Action Bar ── */
    .exam-top-bar {
        position: sticky;
        top: 14px;
        z-index: 99;
        background: rgba(255, 255, 255, .96);
        backdrop-filter: blur(10px);
        border: 1.5px solid var(--line);
        border-radius: 18px;
        padding: 14px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
        box-shadow: 0 10px 30px -10px rgba(23, 20, 58, .15);
        flex-wrap: wrap;
    }
    .exam-top-info {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }
    .exam-top-title {
        font-size: 15.5px;
        font-weight: 900;
        color: var(--ink);
    }
    .exam-progress-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 999px;
        background: var(--bg);
        border: 1px solid var(--line);
        font-size: 12px;
        font-weight: 800;
        color: var(--muted);
    }

    /* Timer Countdown Clock */
    .exam-timer-box {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        border-radius: 12px;
        background: #f4edf0;
        color: var(--ink);
        font-size: 16px;
        font-weight: 900;
        letter-spacing: .04em;
        transition: all .3s;
    }
    .exam-timer-box.warning {
        background: #fff3bf;
        color: #d9480f;
        animation: pulseWarning 1s infinite alternate;
    }
    .exam-timer-box.danger {
        background: #ffe3e3;
        color: #c92a2a;
        animation: pulseDanger .6s infinite alternate;
    }
    @keyframes pulseWarning {
        from { transform: scale(1); }
        to { transform: scale(1.03); }
    }
    @keyframes pulseDanger {
        from { transform: scale(1); }
        to { transform: scale(1.06); }
    }

    .btn-submit-exam {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--red-2), var(--red));
        color: #fff;
        font-weight: 800;
        font-size: 13.5px;
        border: none;
        cursor: pointer;
        transition: all .2s;
        box-shadow: 0 4px 14px -4px rgba(196, 16, 46, .4);
    }
    .btn-submit-exam:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px -4px rgba(196, 16, 46, .6);
    }

    /* ── Main Layout: Questions vs Palette ── */
    .exam-grid {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 24px;
        align-items: start;
    }

    /* ── Question Section Cards ── */
    .exam-section-header {
        background: linear-gradient(135deg, #181335, #261f4a);
        color: #fff;
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 20px;
        margin-top: 32px;
        box-shadow: var(--shadow-sm);
    }
    .exam-section-header:first-of-type {
        margin-top: 0;
    }
    .exam-section-title {
        font-size: 17px;
        font-weight: 900;
        margin-bottom: 4px;
        color: #fff;
    }
    .exam-section-desc {
        font-size: 12.5px;
        color: rgba(255, 255, 255, .7);
        line-height: 1.4;
    }

    .question-card {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 20px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: var(--shadow-sm);
        scroll-margin-top: 100px;
        transition: border-color .2s;
    }
    .question-card.flagged {
        border-color: #ffd43b;
        background: #fffdf5;
    }
    .question-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        gap: 10px;
        flex-wrap: wrap;
    }
    .question-number-badge {
        font-size: 13.5px;
        font-weight: 900;
        color: var(--red);
        background: var(--red-soft);
        padding: 4px 12px;
        border-radius: 8px;
    }
    .btn-flag-question {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 8px;
        background: transparent;
        border: 1px solid var(--line);
        font-size: 11.5px;
        font-weight: 700;
        color: var(--muted);
        cursor: pointer;
        transition: .18s;
    }
    .btn-flag-question:hover {
        background: var(--gold-soft);
        color: var(--gold);
        border-color: var(--gold);
    }
    .btn-flag-question.active {
        background: #fff3bf;
        color: #d9480f;
        border-color: #ffd43b;
    }

    .mondai-instruction {
        font-size: 12.5px;
        font-weight: 700;
        color: var(--muted);
        margin-bottom: 14px;
        background: #fbf7f9;
        padding: 8px 12px;
        border-radius: 10px;
        line-height: 1.5;
    }

    /* Reading Passage Box */
    .passage-box {
        background: #faf8fc;
        border: 1px solid #e5dfee;
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 18px;
        font-family: 'Noto Sans JP', sans-serif;
        font-size: 14.5px;
        line-height: 1.8;
        color: var(--ink);
        white-space: pre-line;
    }

    .question-text {
        font-family: 'Noto Sans JP', 'Inter', sans-serif;
        font-size: 18px;
        font-weight: 800;
        color: var(--ink);
        line-height: 1.6;
        margin-bottom: 18px;
    }

    /* Options Radio List */
    .options-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 10px;
    }
    .option-label {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 18px;
        border: 1.5px solid var(--line);
        border-radius: 14px;
        background: #fff;
        cursor: pointer;
        transition: all .18s ease;
        user-select: none;
    }
    .option-label:hover {
        border-color: rgba(196, 16, 46, .4);
        background: #fff9fa;
    }
    .option-radio {
        accent-color: var(--red);
        width: 18px;
        height: 18px;
        cursor: pointer;
        flex-shrink: 0;
    }
    .option-label.checked {
        border-color: var(--red);
        background: var(--red-soft);
        color: var(--red);
        font-weight: 800;
    }
    .option-number {
        display: grid;
        place-items: center;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: var(--bg);
        border: 1px solid var(--line);
        font-size: 12px;
        font-weight: 800;
        color: var(--ink);
        flex-shrink: 0;
    }
    .option-label.checked .option-number {
        background: var(--red);
        color: #fff;
        border-color: var(--red);
    }
    .option-text {
        font-family: 'Noto Sans JP', sans-serif;
        font-size: 15px;
        color: inherit;
        line-height: 1.4;
    }

    /* ── Sticky Question Palette Sidebar ── */
    .palette-card {
        position: sticky;
        top: 96px;
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 20px;
        padding: 20px;
        box-shadow: var(--shadow-sm);
    }
    .palette-title {
        font-size: 14.5px;
        font-weight: 900;
        color: var(--ink);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .palette-legend {
        display: flex;
        gap: 12px;
        margin-bottom: 14px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--line);
        font-size: 11px;
        font-weight: 700;
        flex-wrap: wrap;
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 5px;
        color: var(--muted);
    }
    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 3px;
    }
    .legend-dot.unanswered { background: #e9e3e7; }
    .legend-dot.answered { background: var(--green); }
    .legend-dot.flagged { background: #f59f00; }

    .palette-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 8px;
    }
    .palette-btn {
        aspect-ratio: 1;
        display: grid;
        place-items: center;
        border-radius: 10px;
        border: 1px solid var(--line);
        background: #fbf7f9;
        font-size: 13px;
        font-weight: 800;
        color: var(--ink);
        cursor: pointer;
        text-decoration: none;
        transition: all .18s;
    }
    .palette-btn:hover {
        border-color: var(--red);
        transform: scale(1.08);
    }
    .palette-btn.answered {
        background: #d3f9d8;
        color: #2b8a3e;
        border-color: #8ce99a;
    }
    .palette-btn.flagged {
        background: #fff3bf;
        color: #d9480f;
        border-color: #ffd43b;
    }

    /* ── Confirmation Modal ── */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(18, 12, 28, .6);
        backdrop-filter: blur(4px);
        z-index: 1000;
        display: none;
        place-items: center;
        padding: 20px;
    }
    .modal-overlay.open { display: grid; }
    .modal-dialog {
        background: #fff;
        border-radius: 20px;
        max-width: 460px;
        width: 100%;
        padding: 28px;
        box-shadow: 0 24px 60px -15px rgba(0, 0, 0, .3);
        text-align: center;
    }
    .modal-icon-warn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #fff3bf;
        color: #f59f00;
        display: grid;
        place-items: center;
        margin: 0 auto 16px;
    }

    @media (max-width: 860px) {
        .exam-grid { grid-template-columns: 1fr; }
        .palette-card { display: none; }
        .exam-top-bar { top: 0; border-radius: 0; }
    }
@endsection

@section('content')
<div class="exam-wrapper">
    <!-- Top Sticky Exam Header -->
    <div class="exam-top-bar">
        <div class="exam-top-info">
            <span class="exam-top-title">Simulasi JLPT N5</span>
            <span class="exam-progress-pill">
                Terjawab: <strong id="topAnsweredCount" style="color:var(--red);">0</strong> / {{ count($questions) }}
            </span>
        </div>

        <div class="exam-timer-box" id="timerBox">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <span id="timerDisplay">30:00</span>
        </div>

        <button type="button" class="btn-submit-exam" id="btnOpenSubmitModal">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            Kumpulkan Ujian
        </button>
    </div>

    <!-- Exam Form -->
    <form action="{{ route('jlpt.submit') }}" method="POST" id="examForm">
        @csrf
        <input type="hidden" name="time_taken" id="timeTakenInput" value="0">

        <div class="exam-grid">
            <!-- Questions Column -->
            <div class="questions-column">
                @php
                    $currentSection = null;
                @endphp

                @foreach($questions as $index => $q)
                    @if($currentSection !== $q['section'])
                        @php
                            $currentSection = $q['section'];
                        @endphp
                        <div class="exam-section-header">
                            <h2 class="exam-section-title">{{ $q['section_label'] }}</h2>
                            <p class="exam-section-desc">Kerjakan bagian ini dengan teliti. Bobot per soal bernilai 6 poin.</p>
                        </div>
                    @endif

                    <div class="question-card" id="qCard-{{ $q['id'] }}">
                        <div class="question-card-top">
                            <span class="question-number-badge">Nomor {{ $q['id'] }}</span>
                            <button type="button" class="btn-flag-question" id="flagBtn-{{ $q['id'] }}" onclick="toggleFlag({{ $q['id'] }})">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                                    <line x1="4" y1="22" x2="4" y2="15"></line>
                                </svg>
                                <span>Ragu-ragu</span>
                            </button>
                        </div>

                        <div class="mondai-instruction">
                            <strong>{{ $q['mondai'] }}</strong>: {{ $q['mondai_desc'] }}
                        </div>

                        @if($q['passage'])
                            <div class="passage-box">
                                {!! nl2br(e($q['passage'])) !!}
                            </div>
                        @endif

                        <div class="question-text">
                            {!! $q['question'] !!}
                        </div>

                        <!-- Options List -->
                        <div class="options-grid">
                            @foreach($q['options'] as $optIndex => $optText)
                                <label class="option-label" id="optLabel-{{ $q['id'] }}-{{ $optIndex }}">
                                    <input type="radio" 
                                           name="answers[{{ $q['id'] }}]" 
                                           value="{{ $optIndex }}" 
                                           class="option-radio"
                                           onchange="selectOption({{ $q['id'] }}, {{ $optIndex }})">
                                    <span class="option-number">{{ $optIndex + 1 }}</span>
                                    <span class="option-text">{{ $optText }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Sticky Right Palette Sidebar -->
            <div class="palette-column">
                <div class="palette-card">
                    <div class="palette-title">
                        <span>Lembar Jawaban Digital</span>
                        <small style="color:var(--muted); font-size:12px;">30 Soal</small>
                    </div>

                    <div class="palette-legend">
                        <div class="legend-item">
                            <span class="legend-dot unanswered"></span>
                            <span>Kosong</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot answered"></span>
                            <span>Terjawab</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot flagged"></span>
                            <span>Ragu-ragu</span>
                        </div>
                    </div>

                    <div class="palette-grid">
                        @foreach($questions as $q)
                            <a href="#qCard-{{ $q['id'] }}" class="palette-btn" id="palBtn-{{ $q['id'] }}">
                                {{ $q['id'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal: Konfirmasi Kumpulkan Ujian -->
<div class="modal-overlay" id="submitConfirmModal">
    <div class="modal-dialog">
        <div class="modal-icon-warn">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>

        <h3 style="font-size:19px; font-weight:900; margin-bottom:8px; color:var(--ink);">Kumpulkan Ujian Sekarang?</h3>
        <p style="font-size:13.5px; color:var(--muted); line-height:1.5; margin-bottom:18px;">
            Kamu telah menjawab <strong id="modalAnsweredCount" style="color:var(--ink)">0</strong> dari {{ count($questions) }} soal.<br>
            <span id="modalFlaggedText" style="color:#d9480f; font-weight:700; display:none;">Ada soal yang masih kamu tandai ragu-ragu.</span>
            Jawaban yang sudah dikumpulkan tidak dapat diubah lagi.
        </p>

        <div style="display:flex; justify-content:center; gap:12px;">
            <button type="button" class="button ghost" onclick="closeSubmitModal()">Periksa Lagi</button>
            <button type="button" class="button" onclick="confirmAndSubmit()" style="background:var(--red);">Ya, Kumpulkan</button>
        </div>
    </div>
</div>

<script>
    // State
    const totalQuestions = {{ count($questions) }};
    let answeredSet = new Set();
    let flaggedSet = new Set();
    let durationSeconds = {{ $config['duration_seconds'] ?? 1800 }};
    let timeElapsed = 0;

    const timerDisplay = document.getElementById('timerDisplay');
    const timerBox = document.getElementById('timerBox');
    const topAnsweredCount = document.getElementById('topAnsweredCount');
    const examForm = document.getElementById('examForm');
    const timeTakenInput = document.getElementById('timeTakenInput');

    const submitConfirmModal = document.getElementById('submitConfirmModal');
    const btnOpenSubmitModal = document.getElementById('btnOpenSubmitModal');
    const modalAnsweredCount = document.getElementById('modalAnsweredCount');
    const modalFlaggedText = document.getElementById('modalFlaggedText');

    // Timer Countdown Loop
    const timerInterval = setInterval(() => {
        timeElapsed++;
        let remaining = durationSeconds - timeElapsed;

        if (remaining <= 0) {
            clearInterval(timerInterval);
            timeTakenInput.value = durationSeconds;
            alert('Waktu ujian telah habis! Jawaban kamu akan otomatis dikumpulkan.');
            examForm.submit();
            return;
        }

        let mins = Math.floor(remaining / 60);
        let secs = remaining % 60;
        timerDisplay.textContent = (mins < 10 ? '0' : '') + mins + ':' + (secs < 10 ? '0' : '') + secs;

        if (remaining <= 300 && remaining > 60) {
            timerBox.className = 'exam-timer-box warning';
        } else if (remaining <= 60) {
            timerBox.className = 'exam-timer-box danger';
        }
    }, 1000);

    // Option selection handler
    function selectOption(qId, optIndex) {
        answeredSet.add(qId);
        topAnsweredCount.textContent = answeredSet.size;

        // Visual selection on labels
        for (let i = 0; i < 4; i++) {
            const label = document.getElementById(`optLabel-${qId}-${i}`);
            if (label) {
                if (i === optIndex) {
                    label.classList.add('checked');
                } else {
                    label.classList.remove('checked');
                }
            }
        }

        updatePaletteButton(qId);
    }

    // Toggle Flag for Review
    function toggleFlag(qId) {
        const btn = document.getElementById(`flagBtn-${qId}`);
        const card = document.getElementById(`qCard-${qId}`);

        if (flaggedSet.has(qId)) {
            flaggedSet.delete(qId);
            if (btn) btn.classList.remove('active');
            if (card) card.classList.remove('flagged');
        } else {
            flaggedSet.add(qId);
            if (btn) btn.classList.add('active');
            if (card) card.classList.add('flagged');
        }

        updatePaletteButton(qId);
    }

    // Update state of number in palette
    function updatePaletteButton(qId) {
        const palBtn = document.getElementById(`palBtn-${qId}`);
        if (!palBtn) return;

        if (flaggedSet.has(qId)) {
            palBtn.className = 'palette-btn flagged';
        } else if (answeredSet.has(qId)) {
            palBtn.className = 'palette-btn answered';
        } else {
            palBtn.className = 'palette-btn';
        }
    }

    // Modal submit confirmation
    if (btnOpenSubmitModal) {
        btnOpenSubmitModal.addEventListener('click', () => {
            modalAnsweredCount.textContent = answeredSet.size;
            if (flaggedSet.size > 0) {
                modalFlaggedText.textContent = `Ada ${flaggedSet.size} soal yang masih kamu tandai ragu-ragu.`;
                modalFlaggedText.style.display = 'block';
            } else {
                modalFlaggedText.style.display = 'none';
            }
            submitConfirmModal.classList.add('open');
        });
    }

    function closeSubmitModal() {
        submitConfirmModal.classList.remove('open');
    }

    function confirmAndSubmit() {
        clearInterval(timerInterval);
        timeTakenInput.value = timeElapsed;
        examForm.submit();
    }
</script>
@endsection

