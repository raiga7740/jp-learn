@extends('dashboard.layout')

@php
    $pageTitle = 'Hasil Ujian JLPT N5: ' . ($exam->is_passed ? 'LULUS (合格)' : 'BELUM LULUS');
    $pageSubtitle = 'Evaluasi skor resmi, analisis per seksi, dan pembahasan kunci jawaban.';
@endphp

@section('styles')
    .result-wrapper {
        max-width: 960px;
        margin: 0 auto;
    }

    /* ── Hero Result Score Card ── */
    .result-hero {
        border-radius: 24px;
        padding: 38px 32px;
        color: #fff;
        margin-bottom: 28px;
        text-align: center;
        box-shadow: 0 20px 48px -14px rgba(23, 20, 58, .25);
        position: relative;
        overflow: hidden;
    }
    .result-hero.passed {
        background: linear-gradient(135deg, #124029 0%, #1f6b45 100%);
    }
    .result-hero.failed {
        background: linear-gradient(135deg, #421b24 0%, #6d2030 100%);
    }

    .result-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 18px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 900;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 16px;
    }
    .result-hero.passed .result-status-badge {
        background: rgba(255, 255, 255, .2);
        color: #d3f9d8;
    }
    .result-hero.failed .result-status-badge {
        background: rgba(255, 255, 255, .2);
        color: #ffc9c9;
    }

    .result-hero-title {
        font-size: 30px;
        font-weight: 900;
        margin-bottom: 8px;
    }
    .result-hero-subtitle {
        font-size: 14.5px;
        color: rgba(255, 255, 255, .82);
        max-width: 580px;
        margin: 0 auto 24px;
        line-height: 1.5;
    }

    /* Big Score Gauge */
    .result-score-main {
        font-size: 64px;
        font-weight: 900;
        line-height: 1;
        margin-bottom: 4px;
        letter-spacing: -.02em;
    }
    .result-score-max {
        font-size: 18px;
        color: rgba(255, 255, 255, .7);
        font-weight: 700;
        margin-bottom: 22px;
    }

    .result-meta-bar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        font-size: 13px;
        font-weight: 700;
        color: rgba(255, 255, 255, .85);
        flex-wrap: wrap;
        margin-bottom: 26px;
    }
    .result-actions {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .btn-cert {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 12px;
        background: #fff;
        color: #1b5e20;
        font-size: 14px;
        font-weight: 900;
        text-decoration: none;
        box-shadow: 0 6px 18px rgba(0, 0, 0, .2);
        transition: .2s;
    }
    .btn-cert:hover {
        transform: translateY(-2px);
        background: #f1fbf3;
    }

    .btn-retry-exam {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--red-2), var(--red));
        color: #fff !important;
        font-size: 14px;
        font-weight: 800;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-family: inherit;
        box-shadow: 0 8px 20px -4px rgba(0, 0, 0, .4);
        transition: all .2s ease;
    }
    .result-hero.passed .btn-retry-exam {
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .3);
        box-shadow: none;
    }
    .btn-retry-exam:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px -4px rgba(0, 0, 0, .5);
        color: #fff !important;
    }

    .btn-history-exam {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 12px;
        background: rgba(255, 255, 255, .15);
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, .32);
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        transition: all .2s ease;
        backdrop-filter: blur(8px);
    }
    .btn-history-exam:hover {
        background: rgba(255, 255, 255, .25);
        border-color: rgba(255, 255, 255, .55);
        color: #ffffff !important;
        transform: translateY(-2px);
    }

    /* ── Section Breakdown Grid ── */
    .section-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }
    .section-card {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 18px;
        padding: 20px;
        box-shadow: var(--shadow-sm);
        text-align: center;
    }
    .section-card h3 {
        font-size: 13px;
        font-weight: 800;
        color: var(--muted);
        text-transform: uppercase;
        margin-bottom: 8px;
    }
    .section-card strong {
        font-size: 26px;
        font-weight: 900;
        color: var(--ink);
        display: block;
        margin-bottom: 4px;
    }
    .section-card span {
        font-size: 11.5px;
        color: var(--muted);
        font-weight: 700;
    }

    /* ── Review Question Cards ── */
    .review-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .review-title {
        font-size: 18px;
        font-weight: 900;
        color: var(--ink);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .review-card {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 18px;
        padding: 22px;
        margin-bottom: 18px;
        box-shadow: var(--shadow-sm);
    }
    .review-card.correct {
        border-left: 5px solid #2b8a3e;
    }
    .review-card.wrong {
        border-left: 5px solid #c92a2a;
    }

    .review-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        gap: 10px;
        flex-wrap: wrap;
    }
    .review-badge {
        font-size: 12px;
        font-weight: 900;
        padding: 3px 10px;
        border-radius: 6px;
    }
    .review-badge.correct {
        background: #d3f9d8;
        color: #2b8a3e;
    }
    .review-badge.wrong {
        background: #ffe3e3;
        color: #c92a2a;
    }

    .review-question {
        font-family: 'Noto Sans JP', sans-serif;
        font-size: 17px;
        font-weight: 800;
        color: var(--ink);
        margin-bottom: 14px;
        line-height: 1.5;
    }

    .review-options {
        display: grid;
        gap: 8px;
        margin-bottom: 16px;
    }
    .review-opt {
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid var(--line);
        font-size: 13.5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .review-opt.is-correct-key {
        border-color: #8ce99a;
        background: #ebfbee;
        color: #2b8a3e;
        font-weight: 800;
    }
    .review-opt.is-wrong-chosen {
        border-color: #ffa8a8;
        background: #fff5f5;
        color: #c92a2a;
        font-weight: 800;
    }

    .review-explanation {
        background: #fbf7f9;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 13px;
        color: #3b2834;
        line-height: 1.5;
        border: 1px dashed var(--line);
    }
    .review-explanation strong {
        color: var(--red);
        display: block;
        margin-bottom: 4px;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    @media (max-width: 640px) {
        .result-hero { padding: 28px 20px; }
        .result-score-main { font-size: 48px; }
        .section-grid { grid-template-columns: 1fr; }
    }
@endsection

@section('content')
<div class="result-wrapper">
    <!-- Hero Result Score Banner -->
    <div class="result-hero {{ $exam->is_passed ? 'passed' : 'failed' }}">
        <div class="result-status-badge">
            @if($exam->is_passed)
                <span style="display:inline-flex; align-items:center; gap:6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    合格 · LULUS RESMI JLPT N5
                </span>
            @else
                <span style="display:inline-flex; align-items:center; gap:6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    不合格 · BELUM MENCAPAI SKOR KELULUSAN
                </span>
            @endif
        </div>

        <h1 class="result-hero-title">
            {{ $exam->is_passed ? 'おめでとうございます! (Selamat!)' : '次はがんばりましょう! (Tetap Semangat!)' }}
        </h1>
        <p class="result-hero-subtitle">
            @if($exam->is_passed)
                Kamu telah berhasil melewati standar penilaian resmi ujian kemampuan bahasa Jepang (JLPT) tingkat N5 dengan predikat Grade {{ $exam->grade }}!
            @else
                Kamu memperoleh skor {{ $exam->score }}/180 (dibutuhkan minimal 80 poin dan ambang batas masing-masing seksi untuk lulus). Terus pelajari materi yang keliru dan coba kembali!
            @endif
        </p>

        <div class="result-score-main">{{ $exam->score }}</div>
        <div class="result-score-max">DARI TOTAL 180 POIN (Grade: {{ $exam->grade }})</div>

        <div class="result-meta-bar">
            <span style="display:inline-flex; align-items:center; gap:5px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                {{ $exam->created_at->format('d M Y, H:i') }}
            </span>
            <span style="display:inline-flex; align-items:center; gap:5px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                Waktu: {{ gmdate('i:s', $exam->time_taken) }}
            </span>
            <span style="display:inline-flex; align-items:center; gap:5px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                No: <code>{{ $exam->certificate_no }}</code>
            </span>
            <span style="display:inline-flex; align-items:center; gap:5px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
                Benar: {{ $exam->correct_answers }} dari {{ $exam->total_questions }} Soal
            </span>
        </div>

        <div class="result-actions">
            @if($exam->is_passed)
                <a href="{{ route('jlpt.certificate', $exam->id) }}" class="btn-cert">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    Lihat & Cetak Sertifikat Kelulusan
                </a>
            @endif

            <button type="button" class="btn-retry-exam js-open-exam-modal">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="1 4 1 10 7 10"></polyline>
                    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                </svg>
                Ulangi Ujian Simulasi
            </button>

            <a href="{{ route('jlpt.index') }}" class="btn-history-exam">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="12 8 12 12 14 14"></polyline>
                    <path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5"></path>
                </svg>
                Daftar Riwayat Ujian
            </a>
        </div>
    </div>

    <!-- Section Breakdown Grid -->
    <div class="section-grid">
        <div class="section-card">
            <h3>文字・語彙 (Huruf & Kosakata)</h3>
            <strong style="color:var(--navy);">{{ $exam->score_vocabulary }} / 72</strong>
            <span>Standar Lulus: Min 19 Poin</span>
        </div>
        <div class="section-card">
            <h3>文法 (Tata Bahasa / Bunpou)</h3>
            <strong style="color:var(--blue);">{{ $exam->score_grammar }} / 72</strong>
            <span>Standar Lulus: Min 19 Poin</span>
        </div>
        <div class="section-card">
            <h3>読解 (Pemahaman Bacaan)</h3>
            <strong style="color:var(--red);">{{ $exam->score_reading }} / 36</strong>
            <span>Standar Lulus: Min 12 Poin</span>
        </div>
    </div>

    <!-- Question by Question Review -->
    <div class="review-header">
        <h2 class="review-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--red);">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
            </svg>
            Pembahasan Jawaban Seluruh Soal
        </h2>
        <span style="font-size:13px; font-weight:700; color:var(--muted);">30 Soal Terbahas</span>
    </div>

    @php
        $userAnswers = $exam->answers ?? [];
    @endphp

    @foreach($questions as $q)
        @php
            $ansData = $userAnswers[$q['id']] ?? null;
            $submitted = $ansData['submitted'] ?? null;
            $isCorrect = $ansData['is_correct'] ?? false;
        @endphp

        <div class="review-card {{ $isCorrect ? 'correct' : 'wrong' }}">
            <div class="review-card-top">
                <div style="display:flex; align-items:center; gap:8px;">
                    <strong style="font-size:14px; color:var(--ink);">Soal Nomor {{ $q['id'] }}</strong>
                    <span style="font-size:12px; color:var(--muted);">({{ $q['section_label'] }} · {{ $q['mondai'] }})</span>
                </div>
                <span class="review-badge {{ $isCorrect ? 'correct' : 'wrong' }}" style="display:inline-flex; align-items:center; gap:5px;">
                    @if($isCorrect)
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Benar (+6 Poin)
                    @else
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        Salah / Kosong (0 Poin)
                    @endif
                </span>
            </div>

            @if($q['passage'])
                <div style="background:#faf8fc; border:1px solid #e5dfee; border-radius:12px; padding:14px 16px; font-family:'Noto Sans JP', sans-serif; font-size:13.5px; line-height:1.7; margin-bottom:12px; white-space:pre-line;">
                    {!! nl2br(e($q['passage'])) !!}
                </div>
            @endif

            <div class="review-question">
                {!! $q['question'] !!}
            </div>

            <!-- Options Breakdown -->
            <div class="review-options">
                @foreach($q['options'] as $optIdx => $optTxt)
                    @php
                        $isKey = ($optIdx === $q['correct_answer']);
                        $isUser = ($submitted !== null && $optIdx === (int) $submitted);
                    @endphp

                    <div class="review-opt {{ $isKey ? 'is-correct-key' : ($isUser ? 'is-wrong-chosen' : '') }}">
                        <strong style="width:20px;">{{ $optIdx + 1 }}.</strong>
                        <span>{{ $optTxt }}</span>
                        @if($isKey)
                            <span style="margin-left:auto; font-weight:800; font-size:11.5px; color:#2b8a3e; display:inline-flex; align-items:center; gap:4px;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                KUNCI BENAR
                            </span>
                        @elseif($isUser)
                            <span style="margin-left:auto; font-weight:800; font-size:11.5px; color:#c92a2a; display:inline-flex; align-items:center; gap:4px;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                JAWABAN KAMU
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Detailed Explanation Box -->
            <div class="review-explanation">
                <strong style="display:flex; align-items:center; gap:6px; margin-bottom:6px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--gold);">
                        <path d="M9 18h6"></path>
                        <path d="M10 22h4"></path>
                        <path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"></path>
                    </svg>
                    Pembahasan & Penjelasan:
                </strong>
                {{ $q['explanation'] }}
            </div>
        </div>
    @endforeach
</div>

@include('dashboard.jlpt.partials.start-modal')
@endsection

