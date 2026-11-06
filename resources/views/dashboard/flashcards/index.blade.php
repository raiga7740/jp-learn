@extends('dashboard.layout')

@php($pageTitle = 'Mode Belajar Flashcard')
@php($pageSubtitle = 'Latih daya ingat huruf, kosakata, dan kanji dengan metode Spaced Repetition.')

@section('styles')
    .flashcard-hero {
        position: relative;
        overflow: hidden;
        margin-bottom: 26px;
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
        margin-bottom: 16px;
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
        margin-bottom: 8px;
    }
    .deck-desc {
        color: var(--muted);
        font-size: 13px;
        line-height: 1.55;
        margin-bottom: 20px;
        flex: 1;
    }
    .deck-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding-top: 16px;
        border-top: 1px solid var(--line);
    }
    .deck-count {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 700;
        color: var(--ink);
    }
    .deck-count svg {
        color: var(--red);
    }
    .deck-actions {
        display: flex;
        gap: 8px;
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
    }
@endsection

@section('content')
    <div class="flashcard-hero">
        <span class="eyebrow">METODE SPURT MEMORY</span>
        <h2>Latih Daya Ingat dengan Flashcard Interaktif</h2>
        <p>Gunakan kartu bolak-balik untuk menguji pemanggilan memori aktif. Dilengkapi pelafalan suara audio native Jepang dan pengelompokan kartu otomatis agar belajar lebih efisien.</p>
    </div>

    <div class="flashcard-stats">
        <div class="fc-stat">
            <span class="fc-stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
            </span>
            <div>
                <strong>{{ count($decks) }} Deck</strong>
                <span>Materi Siap Latihan</span>
            </div>
        </div>

        <div class="fc-stat">
            <span class="fc-stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
            </span>
            <div>
                <strong>{{ $totalCards ?? $decks->sum('card_count') }} Kartu</strong>
                <span>Total Kartu Hafalan</span>
            </div>
        </div>

        <div class="fc-stat">
            <span class="fc-stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                </svg>
            </span>
            <div>
                <strong>Audio Native</strong>
                <span>Pelafalan Suara Asli Jepang</span>
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
                <strong>Smart Queue</strong>
                <span>Ulangi Kartu yang Belum Hafal</span>
            </div>
        </div>
    </div>

    <div class="section-head">
        <div>
            <h2>Pilih Deck Flashcard</h2>
            <p>Pilih topik yang ingin kamu latih hari ini. Sesi belajar dapat diulang kapan saja.</p>
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

                <div class="deck-footer">
                    <div class="deck-count">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                        <span>{{ $deck['card_count'] }} Kartu</span>
                    </div>

                    <div class="deck-actions">
                        <a href="{{ route('flashcards.show', $deck['slug']) }}" class="button" style="padding: 9px 16px; font-size: 13px;">
                            Mulai Latihan
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
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
                <span>Belum Hafal (Masuk antrean ulang)</span>
            </div>
            <div class="shortcut-item">
                <span class="kbd">→</span> atau <span class="kbd">2</span>
                <span>Sudah Hafal</span>
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

