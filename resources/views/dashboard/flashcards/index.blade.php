@extends('dashboard.layout')

@php($pageTitle = 'Mode Belajar Flashcard')
@php($pageSubtitle = 'Sistem Pengulangan Berkala (Spaced Repetition System) untuk memori jangka panjang.')

@section('styles')
    .flashcard-hero {
        position: relative;
        overflow: hidden;
        margin-bottom: 22px;
        padding: 30px 32px;
        border-radius: var(--radius);
        color: #fff;
        background: linear-gradient(130deg, #7a0c23, #b81435 55%, #df234d);
        box-shadow: 0 20px 48px -24px rgba(184,20,53,.65);
    }
    .flashcard-hero::after {
        content: '🎴';
        position: absolute;
        right: 28px;
        bottom: -24px;
        font-size: 130px;
        opacity: .18;
        pointer-events: none;
        user-select: none;
    }
    .flashcard-hero h2 {
        font: 900 25px 'Noto Sans JP', sans-serif;
        margin-bottom: 8px;
        letter-spacing: -.02em;
    }
    .flashcard-hero p {
        max-width: 620px;
        color: rgba(255,255,255,.9);
        font-size: 13.5px;
        line-height: 1.6;
    }

    /* ── SRS Review Today Banner ── */
    .srs-banner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 20px 24px;
        border-radius: var(--radius);
        background: linear-gradient(135deg, #fff9db, #fff3bf);
        border: 2px solid #ffd43b;
        box-shadow: 0 12px 28px -12px rgba(245,159,0,.3);
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .srs-banner.all-done {
        background: linear-gradient(135deg, #e6fcf5, #c3fae8);
        border-color: #63e6be;
        box-shadow: 0 12px 28px -12px rgba(18,184,134,.25);
    }
    .srs-banner-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .srs-banner-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: grid;
        place-items: center;
        font-size: 24px;
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,.08);
        flex-shrink: 0;
    }
    .srs-banner-title {
        font-size: 16px;
        font-weight: 900;
        color: #2b1d0c;
        margin-bottom: 2px;
    }
    .srs-banner.all-done .srs-banner-title {
        color: #08402b;
    }
    .srs-banner-desc {
        font-size: 13px;
        color: #614a27;
        line-height: 1.45;
    }
    .srs-banner.all-done .srs-banner-desc {
        color: #1a6345;
    }

    .flashcard-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 26px;
    }
    .fc-stat {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 18px;
        border: 1px solid var(--line);
        border-radius: var(--radius);
        background: var(--card);
        box-shadow: var(--shadow);
    }
    .fc-stat-icon {
        display: grid;
        width: 44px;
        height: 44px;
        place-items: center;
        border-radius: 12px;
        background: var(--red-soft);
        color: var(--red);
        flex-shrink: 0;
    }
    .fc-stat:nth-child(2) .fc-stat-icon { background: var(--blue-soft); color: var(--blue); }
    .fc-stat:nth-child(3) .fc-stat-icon { background: var(--green-soft); color: var(--green); }
    .fc-stat:nth-child(4) .fc-stat-icon { background: var(--gold-soft); color: var(--gold); }
    .fc-stat strong {
        display: block;
        font-size: 20px;
        font-weight: 900;
        color: var(--ink);
        line-height: 1.2;
    }
    .fc-stat span {
        font-size: 12px;
        color: var(--muted);
        font-weight: 600;
    }

    .deck-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 18px;
    }
    .deck-card {
        display: flex;
        flex-direction: column;
        padding: 24px;
        border: 1px solid var(--line);
        border-radius: var(--radius);
        background: var(--card);
        box-shadow: var(--shadow);
        transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
        position: relative;
        overflow: hidden;
    }
    .deck-card:hover {
        transform: translateY(-3px);
        border-color: rgba(196,16,46,.32);
        box-shadow: 0 16px 36px -16px rgba(180,10,40,.24);
    }
    .deck-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 14px;
    }
    .deck-icon {
        width: 52px;
        height: 52px;
        display: grid;
        place-items: center;
        border-radius: 14px;
        font: 900 24px 'Noto Sans JP', sans-serif;
        background: var(--red-soft);
        color: var(--red);
        flex-shrink: 0;
        box-shadow: 0 6px 16px -6px rgba(196,16,46,.3);
    }
    .deck-card:nth-child(2) .deck-icon { background: var(--blue-soft); color: var(--blue); box-shadow: 0 6px 16px -6px rgba(45,58,140,.3); }
    .deck-card:nth-child(3) .deck-icon { background: var(--green-soft); color: var(--green); box-shadow: 0 6px 16px -6px rgba(31,91,69,.3); }
    .deck-card:nth-child(4) .deck-icon { background: var(--gold-soft); color: var(--gold); box-shadow: 0 6px 16px -6px rgba(166,104,8,.3); }
    .deck-card:nth-child(5) .deck-icon { background: #ede7f6; color: #5e35b1; box-shadow: 0 6px 16px -6px rgba(94,53,177,.3); }

    .deck-badges {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }
    .deck-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--ink);
        margin-bottom: 6px;
    }
    .deck-desc {
        color: var(--muted);
        font-size: 13px;
        line-height: 1.55;
        margin-bottom: 16px;
        flex: 1;
    }

    /* SRS Mastery Bar per Deck */
    .deck-srs-progress {
        margin-bottom: 16px;
        padding: 10px 12px;
        background: #fbf9fb;
        border-radius: 12px;
        border: 1px solid var(--line);
    }
    .srs-progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
        font-size: 11.5px;
        font-weight: 700;
    }
    .srs-mastery-text {
        color: var(--ink);
    }
    .srs-due-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        color: #d9480f;
        font-weight: 800;
    }

    .deck-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding-top: 14px;
        border-top: 1px solid var(--line);
        flex-wrap: wrap;
    }
    .deck-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        width: 100%;
        justify-content: flex-end;
    }

    .shortcuts-guide {
        margin-top: 32px;
        padding: 20px 24px;
        border-radius: var(--radius);
        background: #fff;
        border: 1px solid var(--line);
        box-shadow: var(--shadow);
    }
    .shortcuts-title {
        font-size: 13px;
        font-weight: 800;
        color: var(--ink);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .shortcuts-list {
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
    }
    .shortcut-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12.5px;
        color: var(--muted);
    }
    .kbd {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 6px;
        background: #f0ecf2;
        border: 1px solid #d5ccd8;
        border-bottom-width: 2px;
        color: var(--ink);
        font-family: monospace;
        font-size: 11px;
        font-weight: 700;
    }

    @media (max-width: 900px) {
        .flashcard-stats { grid-template-columns: repeat(2, 1fr); }
        .deck-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 540px) {
        .flashcard-stats { grid-template-columns: 1fr; }
        .srs-banner { flex-direction: column; align-items: flex-start; }
    }
@endsection

@section('content')
    <div class="flashcard-hero">
        <span class="eyebrow">SPACED REPETITION SYSTEM (SRS)</span>
        <h2>Latih Daya Ingat Permanen dengan Flashcard SRS</h2>
        <p>Setiap kartu yang kamu hafal akan dijadwalkan ulang secara otomatis pada interval waktu optimal (1 hari, 3 hari, 7 hari, dst) agar tersimpan kuat di memori jangka panjang.</p>
    </div>

    <!-- SRS Daily Review Action Banner -->
    @if(($totalDueOverall ?? 0) > 0)
        <div class="srs-banner">
            <div class="srs-banner-left">
                <div class="srs-banner-icon">⚡</div>
                <div>
                    <div class="srs-banner-title">Ada {{ $totalDueOverall }} kartu jatuh tempo hari ini!</div>
                    <div class="srs-banner-desc">Waktunya mengulang kartu-kartu ini sebelum kamu mulai melupakannya.</div>
                </div>
            </div>
            <a href="{{ route('flashcards.daily-review') }}" class="button" style="background:#e8590c; box-shadow: 0 8px 20px -6px rgba(232,89,12,.5); font-size: 13.5px;">
                Mulai Review Harian ({{ $totalDueOverall }})
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                </svg>
            </a>
        </div>
    @else
        <div class="srs-banner all-done">
            <div class="srs-banner-left">
                <div class="srs-banner-icon">✨</div>
                <div>
                    <div class="srs-banner-title">Semua kartu hafalanmu masih segar!</div>
                    <div class="srs-banner-desc">Tidak ada kartu yang jatuh tempo saat ini. Kamu bisa lanjut menghafal deck baru di bawah ini.</div>
                </div>
            </div>
            <span class="badge green" style="padding: 6px 14px; font-size: 12px;">SRS Terpenuhi ✓</span>
        </div>
    @endif

    <div class="flashcard-stats">
        <div class="fc-stat">
            <span class="fc-stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
            </span>
            <div>
                <strong>{{ $totalCards ?? $decks->sum('card_count') }} Kartu</strong>
                <span>Total Kartu Tersedia</span>
            </div>
        </div>

        <div class="fc-stat">
            <span class="fc-stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </span>
            <div>
                <strong>{{ $totalLearnedOverall ?? 0 }} Kartu</strong>
                <span>Pernah Dipelajari</span>
            </div>
        </div>

        <div class="fc-stat">
            <span class="fc-stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </span>
            <div>
                <strong>{{ $totalMasteredOverall ?? 0 }} Kartu</strong>
                <span>Hafal Kuat (Box 3+)</span>
            </div>
        </div>

        <div class="fc-stat">
            <span class="fc-stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </span>
            <div>
                <strong>{{ $totalDueOverall ?? 0 }} Kartu</strong>
                <span>Jatuh Tempo Hari Ini</span>
            </div>
        </div>
    </div>

    <div class="section-head">
        <div>
            <h2>Pilih Deck Flashcard</h2>
            <p>Pilih topik yang ingin kamu latih. Progres hafalan tersimpan otomatis ke akunmu.</p>
        </div>
    </div>

    <div class="deck-grid">
        @foreach($decks as $deck)
            @php($mat = $deck['material'])
            <article class="deck-card">
                <div class="deck-card-top">
                    <div class="deck-icon">
                        {{ match($deck['category']) {
                            'hiragana' => 'あ',
                            'katakana' => 'ア',
                            'kanji' => '漢',
                            'vocabulary' => '語',
                            'grammar' => '文',
                            default => '🎴'
                        } }}
                    </div>
                    <div class="deck-badges">
                        <span class="badge">{{ ucfirst($deck['category']) }}</span>
                        <span class="badge blue">{{ strtoupper($mat->level) }}</span>
                    </div>
                </div>

                <h3 class="deck-title">{{ $deck['title'] }}</h3>
                <p class="deck-desc">{{ $deck['description'] }}</p>

                <!-- SRS Mastery Progress Bar -->
                <div class="deck-srs-progress">
                    <div class="srs-progress-header">
                        <span class="srs-mastery-text">Hafal: <strong>{{ $deck['mastered_count'] }}</strong> / {{ $deck['card_count'] }} ({{ $deck['mastery_percent'] }}%)</span>
                        @if($deck['due_count'] > 0)
                            <span class="srs-due-badge">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2s5 4.5 5 10a5 5 0 0 1-10 0c0-2 1-3.5 2-4.5.3 1.6 1.2 2.4 2 2.5C11 7 10 4 12 2z"/></svg>
                                {{ $deck['due_count'] }} Review
                            </span>
                        @endif
                    </div>
                    <div class="progress-track" style="height: 6px;">
                        <div class="progress-fill" style="width: {{ $deck['mastery_percent'] }}%; background: linear-gradient(90deg, #10b981, #059669);"></div>
                    </div>
                </div>

                <div class="deck-footer">
                    <div class="deck-actions">
                        @if($deck['due_count'] > 0)
                            <a href="{{ route('flashcards.show', [$deck['slug'], 'mode' => 'due']) }}" class="button" style="background:#e8590c; padding: 8px 14px; font-size: 12.5px;">
                                Review ({{ $deck['due_count'] }})
                            </a>
                        @endif

                        <a href="{{ route('flashcards.show', $deck['slug']) }}" class="button {{ $deck['due_count'] > 0 ? 'ghost' : '' }}" style="padding: 8px 14px; font-size: 12.5px;">
                            {{ $deck['mastery_percent'] > 0 ? 'Lanjut Belajar' : 'Mulai Deck' }}
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="5 3 19 12 5 21 5 3"></polygon>
                            </svg>
                        </a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <div class="shortcuts-guide">
        <div class="shortcuts-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect>
                <path d="M6 8h.001M10 8h.001M14 8h.001M18 8h.001M8 12h.001M12 12h.001M16 12h.001M7 16h10"></path>
            </svg>
            Panduan Pintasan Keyboard (Saat Sesi Latihan)
        </div>
        <div class="shortcuts-list">
            <div class="shortcut-item">
                <span class="kbd">Spasi</span>
                <span>Balik Kartu</span>
            </div>
            <div class="shortcut-item">
                <span class="kbd">←</span> atau <span class="kbd">1</span>
                <span>Belum Hafal (SRS Box di-reset ke Box 1)</span>
            </div>
            <div class="shortcut-item">
                <span class="kbd">→</span> atau <span class="kbd">2</span>
                <span>Sudah Hafal (Naik Leitner Box)</span>
            </div>
            <div class="shortcut-item">
                <span class="kbd">A</span>
                <span>Putar Audio Pelafalan</span>
            </div>
            <div class="shortcut-item">
                <span class="kbd">R</span>
                <span>Acak Urutan Kartu</span>
            </div>
        </div>
    </div>
@endsection
