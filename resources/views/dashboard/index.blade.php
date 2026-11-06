@extends('dashboard.layout')

@php($pageTitle = 'Dashboard Belajar')
@php($pageSubtitle = 'Ringkasan belajar dan capaian kamu hari ini.')

@section('styles')
    .hero-progress {
        margin-top: 18px;
        background: rgba(255,255,255,.14);
        padding: 14px 18px;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,.18);
        max-width: 540px;
    }
    .hero-progress-labels {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        font-size: 12px;
        font-weight: 600;
        color: rgba(255,255,255,.9);
    }
    .hero-progress-pct {
        font-weight: 800;
        color: #fff;
    }
    .hero-progress .progress-track {
        height: 6px;
        background: rgba(0,0,0,.22);
    }
    .hero-progress .progress-fill {
        background: linear-gradient(90deg, #fff, #ffd2da);
    }

    .overview {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
    }
    .overview-card {
        display: flex;
        flex-direction: column;
        padding: 24px;
        border: 1px solid var(--line);
        border-radius: var(--radius);
        background: var(--card);
        box-shadow: var(--shadow);
        transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
    }
    .overview-card:hover {
        transform: translateY(-3px);
        border-color: rgba(196,16,46,.3);
        box-shadow: 0 16px 36px -18px rgba(180,10,40,.28);
    }
    .overview-card .symbol {
        display: grid;
        width: 48px;
        height: 48px;
        margin-bottom: 16px;
        place-items: center;
        border-radius: 14px;
        background: var(--red-soft);
        color: var(--red);
        font: 900 22px 'Noto Sans JP', sans-serif;
        flex-shrink: 0;
    }
    .overview-card .symbol svg {
        width: 22px;
        height: 22px;
    }
    .overview-card:nth-child(2) .symbol {
        background: var(--blue-soft);
        color: var(--blue);
    }
    .overview-card:nth-child(3) .symbol {
        background: var(--green-soft);
        color: var(--green);
    }
    .overview-card h2 {
        font-size: 17px;
        font-weight: 800;
        margin-bottom: 8px;
        color: var(--ink);
    }
    .overview-card p {
        flex: 1;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.6;
        margin-bottom: 20px;
    }
    .overview-card .button {
        align-self: flex-start;
    }

    @media (max-width: 960px) {
        .overview {
            grid-template-columns: 1fr;
        }
        .overview-card {
            padding: 20px;
        }
    }
@endsection

@section('content')
    <section class="hero">
        <span class="eyebrow">いらっしゃいませ · Selamat Datang</span>
        <h2>Halo, {{ ($user ?? Auth::user())->name }}!</h2>
        <p>Kamu sudah menyelesaikan {{ $completedCount }} dari {{ $totalMaterials }} materi pembelajaran. Terus tingkatkan kemampuan bahasa Jepangmu setiap hari!</p>

        <div class="hero-progress">
            <div class="hero-progress-labels">
                <span>Progres Belajar Keseluruhan</span>
                <span class="hero-progress-pct">{{ $overallProgress }}% Selesai</span>
            </div>
            <div class="progress-track">
                <div class="progress-fill" style="width: {{ $overallProgress }}%"></div>
            </div>
        </div>
    </section>

    <section class="stats" aria-label="Ringkasan belajar">
        <div class="stat">
            <span class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
            </span>
            <div>
                <strong>{{ $totalMaterials }}</strong>
                <span>Total materi</span>
            </div>
        </div>

        <div class="stat">
            <span class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </span>
            <div>
                <strong>{{ $completedCount }}</strong>
                <span>Materi selesai</span>
            </div>
        </div>

        <div class="stat">
            <span class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </span>
            <div>
                <strong>{{ $totalQuizzesTaken }}</strong>
                <span>Quiz dikerjakan</span>
            </div>
        </div>

        <div class="stat">
            <span class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
            </span>
            <div>
                <strong>{{ $avgScore }}%</strong>
                <span>Rata-rata skor</span>
            </div>
        </div>
    </section>

    <section class="overview">
        <article class="overview-card">
            <span class="symbol">あ</span>
            <h2>Materi Belajar</h2>
            <p>Pelajari Hiragana, Katakana, Kanji dasar, kosakata penting, dan pola tata bahasa secara terstruktur.</p>
            <a class="button" href="{{ route('dashboard.materials') }}">
                Buka Materi
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </article>

        <article class="overview-card">
            <span class="symbol" style="background: rgba(224, 86, 36, 0.12); color: #e05624;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
            </span>
            <h2>Mode Flashcard</h2>
            <p>Hafalkan huruf Kana, Kanji N5, dan kosakata lebih cepat dengan kartu bolak-balik interaktif dan audio.</p>
            <a class="button" href="{{ route('flashcards.index') }}">
                Mulai Flashcard
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </article>

        <article class="overview-card">
            <span class="symbol" style="background: rgba(142, 68, 173, 0.12); color: #8e44ad;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 19l7-7 3 3-7 7-3-3z"></path>
                    <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path>
                    <path d="M2 2l7.586 7.586"></path>
                    <circle cx="11" cy="11" r="2"></circle>
                </svg>
            </span>
            <h2>Latihan Menulis</h2>
            <p>Asah ketrampilan menulis goresan Hiragana, Katakana, dan Kanji secara interaktif langsung di kanvas digital.</p>
            <a class="button" href="{{ route('writing.index') }}">
                Mulai Menulis
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </article>

        <article class="overview-card">
            <span class="symbol">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </span>
            <h2>Quiz & Latihan</h2>
            <p>Uji pemahaman dan kecepatan refleks membaca huruf melalui latihan interaktif dengan timer.</p>
            <a class="button" href="{{ route('dashboard.quizzes') }}">
                Buka Quiz
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </article>

        <article class="overview-card">
            <span class="symbol">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
            </span>
            <h2>Progres Belajar</h2>
            <p>Pantau status penyelesaian setiap modul belajar dan analisis riwayat skor quiz kamu.</p>
            <a class="button" href="{{ route('dashboard.progress') }}">
                Lihat Progres
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </article>
    </section>
@endsection