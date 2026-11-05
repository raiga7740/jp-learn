@extends('dashboard.layout')

@php($pageTitle = 'Quiz & Latihan')
@php($pageSubtitle = 'Uji pemahaman dan kecepatan refleks membaca huruf.')

@section('styles')
    .quiz-list {
        display: grid;
        gap: 14px;
        padding: 16px;
    }
    .quiz {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 20px;
        align-items: center;
        padding: 22px 24px;
        border: 1px solid var(--line);
        border-radius: 16px;
        background: #fffafa;
        transition: all .2s ease;
    }
    .quiz:hover {
        border-color: rgba(196,16,46,.3);
        background: #fff;
        box-shadow: 0 10px 24px -12px rgba(180,10,40,.2);
        transform: translateY(-1px);
    }
    .quiz-info {
        min-width: 0;
    }
    .quiz h3 {
        margin: 8px 0 6px;
        font-size: 16.5px;
        font-weight: 800;
        color: var(--ink);
    }
    .quiz p {
        max-width: 720px;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.55;
    }
    .quiz-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-top: 14px;
        color: #865769;
        font-size: 12px;
        font-weight: 600;
        flex-wrap: wrap;
    }
    .quiz-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .quiz-meta-item svg {
        color: var(--red);
        width: 14px;
        height: 14px;
        flex-shrink: 0;
    }
    .quiz-action {
        display: flex;
        align-items: center;
    }
    .quiz-action .button {
        min-width: 110px;
    }

    @media (max-width: 650px) {
        .quiz-list {
            padding: 12px;
            gap: 12px;
        }
        .quiz {
            grid-template-columns: 1fr;
            gap: 16px;
            padding: 16px;
        }
        .quiz-action .button {
            width: 100%;
        }
    }
@endsection

@section('content')
    <section class="hero">
        <span class="eyebrow">Latihan · クイズ</span>
        <h2>Quiz & Uji Pemahaman</h2>
        <p>Kerjakan latihan untuk mengukur pemahaman materi dan kecepatan membaca huruf. Skor serta riwayat latihanmu akan tersimpan rapi pada halaman progres.</p>
    </section>

    <section class="section">
        <div class="section-head">
            <div>
                <h2>Quiz Tersedia</h2>
                <p>Pilih topik quiz yang ingin kamu kerjakan</p>
            </div>
            <span class="badge blue">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                {{ $quizzes->count() }} Quiz
            </span>
        </div>

        @if($quizzes->isEmpty())
            <div class="empty">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:12px">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                <strong>Belum ada quiz</strong>
                Quiz akan tersedia setelah modul materi ditambahkan.
            </div>
        @else
            <div class="quiz-list">
                @foreach($quizzes as $quiz)
                    <article class="quiz">
                        <div class="quiz-info">
                            <span class="badge">{{ ucfirst($quiz->category) }}</span>
                            <h3>{{ $quiz->title }}</h3>
                            <p>{{ $quiz->description ?: 'Uji pemahaman dan daya ingatmu pada materi pembelajaran ini.' }}</p>

                            <div class="quiz-meta">
                                <span class="quiz-meta-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    {{ max(1, (int) ceil($quiz->time_limit / 60)) }} Menit
                                </span>

                                <span class="quiz-meta-item">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                    </svg>
                                    {{ $quiz->questions_count }} Soal
                                </span>

                                @if($quiz->material)
                                    <span class="quiz-meta-item">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                        </svg>
                                        {{ $quiz->material->title }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="quiz-action">
                            <a href="{{ route('quiz.show', $quiz) }}" class="button">
                                Mulai Quiz
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection