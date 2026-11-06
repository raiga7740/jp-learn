@extends('dashboard.layout')

@php($pageTitle = 'Percakapan Situasional (Kaiwa)')
@php($pageSubtitle = 'Simulasi dialog percakapan bahasa Jepang dalam situasi nyata sehari-hari.')

@section('styles')
    .kaiwa-hero {
        position: relative;
        overflow: hidden;
        margin-bottom: 26px;
        padding: 30px 32px;
        border-radius: var(--radius);
        color: #fff;
        background: linear-gradient(130deg, #1b3557, #1e4a7a 50%, #20639b);
        box-shadow: 0 20px 48px -24px rgba(30,74,122,.65);
    }
    .kaiwa-hero::after {
        content: '💬';
        position: absolute;
        right: 28px;
        bottom: -24px;
        font-size: 130px;
        opacity: .18;
        pointer-events: none;
        user-select: none;
    }
    .kaiwa-hero h2 {
        font: 900 25px 'Noto Sans JP', sans-serif;
        margin-bottom: 8px;
        letter-spacing: -.02em;
    }
    .kaiwa-hero p {
        max-width: 620px;
        color: rgba(255,255,255,.9);
        font-size: 13.5px;
        line-height: 1.6;
    }

    .kaiwa-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 26px;
    }
    .k-stat {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 18px;
        border: 1px solid var(--line);
        border-radius: var(--radius);
        background: var(--card);
        box-shadow: var(--shadow);
    }
    .k-stat-icon {
        display: grid;
        width: 44px;
        height: 44px;
        place-items: center;
        border-radius: 12px;
        background: var(--blue-soft);
        color: var(--blue);
        flex-shrink: 0;
    }
    .k-stat:nth-child(2) .k-stat-icon { background: var(--green-soft); color: var(--green); }
    .k-stat:nth-child(3) .k-stat-icon { background: var(--red-soft); color: var(--red); }
    .k-stat:nth-child(4) .k-stat-icon { background: var(--gold-soft); color: var(--gold); }
    .k-stat strong {
        display: block;
        font-size: 20px;
        font-weight: 900;
        color: var(--ink);
        line-height: 1.2;
    }
    .k-stat span {
        font-size: 12px;
        color: var(--muted);
        font-weight: 600;
    }

    .scenarios-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
    }
    .scenario-card {
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
    .scenario-card:hover {
        transform: translateY(-3px);
        border-color: rgba(32,99,155,.35);
        box-shadow: 0 16px 36px -16px rgba(32,99,155,.25);
    }
    .scenario-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 14px;
    }
    .scenario-icon {
        width: 52px;
        height: 52px;
        display: grid;
        place-items: center;
        border-radius: 16px;
        font-size: 28px;
        background: #f0f4f9;
        flex-shrink: 0;
        box-shadow: 0 6px 14px -6px rgba(0,0,0,.12);
    }
    .scenario-badges {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .scenario-jp-title {
        font-family: 'Noto Sans JP', sans-serif;
        font-size: 13px;
        font-weight: 800;
        color: var(--red);
        margin-bottom: 4px;
    }
    .scenario-title {
        font-size: 17px;
        font-weight: 900;
        color: var(--ink);
        margin-bottom: 8px;
        line-height: 1.35;
    }
    .scenario-desc {
        color: var(--muted);
        font-size: 13px;
        line-height: 1.55;
        margin-bottom: 18px;
        flex: 1;
    }

    .speakers-preview {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 12px;
        background: #fbf9fc;
        border: 1px solid var(--line);
        margin-bottom: 18px;
        font-size: 12px;
    }
    .speaker-pill {
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 700;
        color: var(--ink);
    }
    .speaker-avatar-mini {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        font-size: 11px;
        font-weight: 800;
    }

    .scenario-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding-top: 16px;
        border-top: 1px solid var(--line);
    }
    .dialogue-count {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 700;
        color: var(--muted);
    }

    @media (max-width: 900px) {
        .kaiwa-stats { grid-template-columns: repeat(2, 1fr); }
        .scenarios-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 540px) {
        .kaiwa-stats { grid-template-columns: 1fr; }
    }
@endsection

@section('content')
    <div class="kaiwa-hero">
        <span class="eyebrow" style="color: #c7e3ff;">SIMULASI PERCAKAPAN NYATA (会話)</span>
        <h2>Latih Refleks Percakapan Bahasa Jepang</h2>
        <p>Masuki situasi nyata seperti berkenalan, memesan makanan di restoran, belanja di konbini, hingga bertanya arah jalan. Dengarkan pelafalan audio native dan latih pilihan respon yang sopan.</p>
    </div>

    <div class="kaiwa-stats">
        <div class="k-stat">
            <span class="k-stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
            </span>
            <div>
                <strong>{{ count($scenarios) }} Skenario</strong>
                <span>Situasi Sehari-hari</span>
            </div>
        </div>

        <div class="k-stat">
            <span class="k-stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                </svg>
            </span>
            <div>
                <strong>Audio Native</strong>
                <span>Pelafalan per Kalimat</span>
            </div>
        </div>

        <div class="k-stat">
            <span class="k-stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 20h9"></path>
                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                </svg>
            </span>
            <div>
                <strong>Roleplay Quiz</strong>
                <span>Pilih Respon Tepat</span>
            </div>
        </div>

        <div class="k-stat">
            <span class="k-stat-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 14 14"></polyline>
                </svg>
            </span>
            <div>
                <strong>Level N5</strong>
                <span>Dasar & Pemula</span>
            </div>
        </div>
    </div>

    <div class="section-head">
        <div>
            <h2>Pilih Topik Percakapan</h2>
            <p>Pilih situasi yang ingin kamu latih. Setiap topik dilengkapi kuis respon pilihan ganda interaktif.</p>
        </div>
    </div>

    <div class="scenarios-grid">
        @foreach($scenarios as $item)
            <article class="scenario-card">
                <div class="scenario-card-top">
                    <div class="scenario-icon">
                        {{ $item['icon'] }}
                    </div>
                    <div class="scenario-badges">
                        <span class="badge">{{ $item['category'] }}</span>
                        <span class="badge blue">{{ $item['level'] }}</span>
                    </div>
                </div>

                <div class="scenario-jp-title">{{ $item['japanese_title'] }}</div>
                <h3 class="scenario-title">{{ $item['title'] }}</h3>
                <p class="scenario-desc">{{ $item['description'] }}</p>

                <!-- Speakers preview -->
                <div class="speakers-preview">
                    <div class="speaker-pill">
                        <span class="speaker-avatar-mini" style="background: {{ $item['speakers']['left']['bg'] }}; color: {{ $item['speakers']['left']['color'] }}">
                            {{ $item['speakers']['left']['avatar'] }}
                        </span>
                        <span>{{ explode(' ', $item['speakers']['left']['name'])[0] }}</span>
                    </div>
                    <span style="color:var(--muted)">↔</span>
                    <div class="speaker-pill">
                        <span class="speaker-avatar-mini" style="background: {{ $item['speakers']['right']['bg'] }}; color: {{ $item['speakers']['right']['color'] }}">
                            {{ $item['speakers']['right']['avatar'] }}
                        </span>
                        <span>{{ explode(' ', $item['speakers']['right']['name'])[0] }}</span>
                    </div>
                </div>

                <div class="scenario-footer">
                    <div class="dialogue-count">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <span>{{ count($item['dialogues']) }} Balon Dialog</span>
                    </div>

                    <a href="{{ route('conversation.show', $item['slug']) }}" class="button" style="padding: 9px 16px; font-size: 13px;">
                        Mulai Simulasi
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                        </svg>
                    </a>
                </div>
            </article>
        @endforeach
    </div>
@endsection

