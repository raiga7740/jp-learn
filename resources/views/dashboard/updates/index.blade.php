@extends(Auth::check() ? 'dashboard.layout' : 'dashboard.updates.guest-layout')

@section('styles')
    /* ── Updates Page Core Styles ── */
    .up-hero {
        position: relative;
        overflow: hidden;
        margin-bottom: 28px;
        padding: 36px 36px 38px;
        border-radius: var(--radius, 18px);
        color: #ffffff !important;
        background: linear-gradient(135deg, #17143a 0%, #242050 55%, #631226 100%) !important;
        box-shadow: 0 18px 45px -15px rgba(23, 20, 58, 0.45);
        border: 1px solid rgba(255, 255, 255, 0.12);
    }
    .up-hero::after {
        content: '新';
        position: absolute;
        right: 18px;
        bottom: -70px;
        color: rgba(255, 255, 255, 0.05);
        font: 900 240px/1 'Noto Sans JP', sans-serif;
        pointer-events: none;
        user-select: none;
    }
    .up-hero > * { position: relative; z-index: 1; }
    .up-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.16) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        color: #ffffff !important;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 14px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
    }
    .up-eyebrow-dot {
        display: inline-block;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #ff4d6d;
        box-shadow: 0 0 8px #ff4d6d;
        flex-shrink: 0;
    }
    .up-hero h1 {
        font: 900 28px/1.25 'Noto Sans JP', 'Inter', sans-serif;
        letter-spacing: -.02em;
        margin-bottom: 10px;
        color: #ffffff !important;
    }
    .up-hero p.lead {
        max-width: 720px;
        color: rgba(255, 255, 255, 0.92) !important;
        font-size: 14.5px;
        line-height: 1.65;
        margin-bottom: 0;
    }
    .up-hero-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 24px;
        padding-top: 4px;
    }
    .up-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 20px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--red, #c4102e), var(--red-2, #e8234f));
        color: #ffffff !important;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        border: none;
        cursor: pointer;
        box-shadow: 0 8px 20px -4px rgba(196, 16, 46, 0.7);
        transition: all .2s ease;
    }
    .up-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 26px -4px rgba(196, 16, 46, 0.85);
        color: #ffffff !important;
    }
    .up-btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 18px;
        border-radius: 12px;
        background: #ffffff;
        border: 1.5px solid rgba(255, 255, 255, 0.95);
        color: #17143a !important;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
        transition: all .2s ease;
    }
    .up-btn-secondary:hover {
        transform: translateY(-2px);
        background: #fff0f3;
        color: #c4102e !important;
        border-color: #ffd4dc;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.18);
    }
    .up-btn-secondary svg {
        color: #c4102e;
        transition: transform .2s ease;
    }
    .up-btn-secondary:hover svg {
        transform: scale(1.1);
    }

    /* ── Status Bar ── */
    .up-status-strip {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 12px 18px;
        border-radius: 14px;
        background: #ffffff;
        border: 1px solid var(--line, #f0d5d0);
        margin-bottom: 22px;
        font-size: 12.5px;
        box-shadow: 0 4px 16px -6px rgba(60, 20, 40, 0.08);
        flex-wrap: wrap;
    }
    .up-status-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .up-status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
        animation: pulse-dot 2s infinite;
        flex-shrink: 0;
    }
    @keyframes pulse-dot {
        0%, 100% { box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2); }
        50% { box-shadow: 0 0 0 7px rgba(16, 185, 129, 0.1); }
    }
    .up-status-right {
        display: flex;
        align-items: center;
        gap: 16px;
        color: var(--muted, #9a6878);
        font-size: 12px;
    }

    /* ── Metrics Grid ── */
    .up-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 26px;
    }
    .up-stat-card {
        padding: 16px 18px;
        border-radius: var(--radius, 18px);
        background: #ffffff;
        border: 1px solid var(--line, #f0d5d0);
        box-shadow: 0 8px 24px -12px rgba(60, 20, 40, 0.1);
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .up-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }
    .up-stat-red   { background: #fde8ec; color: #c4102e; }
    .up-stat-blue  { background: #e8ecf8; color: #2d3a8c; }
    .up-stat-green { background: #e6f4ee; color: #1f5b45; }
    .up-stat-gold  { background: #fef6e0; color: #c8800a; }
    .up-stat-card strong {
        display: block;
        font-size: 20px;
        font-weight: 800;
        color: var(--ink, #1a0a0e);
        line-height: 1.15;
    }
    .up-stat-card span {
        display: block;
        font-size: 11.5px;
        color: var(--muted, #9a6878);
        font-weight: 500;
    }

    /* ── Announcement Banner ── */
    .up-announcement {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px 20px;
        border-radius: 14px;
        background: linear-gradient(135deg, #fff2f4, #fff8f6);
        border: 1.5px solid #f8cbd4;
        margin-bottom: 24px;
        box-shadow: 0 4px 14px -6px rgba(196, 16, 46, 0.15);
    }
    .up-ann-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #c4102e;
        color: #fff;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .up-ann-content { flex: 1; min-width: 0; }
    .up-ann-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 4px;
        flex-wrap: wrap;
    }
    .up-ann-badge {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        padding: 2px 7px;
        border-radius: 999px;
        background: #c4102e;
        color: #fff;
    }
    .up-ann-title {
        font-size: 13.5px;
        font-weight: 800;
        color: #1a0a0e;
    }
    .up-ann-body {
        font-size: 12.5px;
        color: #63434f;
        line-height: 1.5;
    }

    /* ── Filter Bar ── */
    .up-filter-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }
    .up-tab-group {
        display: flex;
        gap: 6px;
        background: #ffffff;
        padding: 4px;
        border-radius: 12px;
        border: 1px solid var(--line, #f0d5d0);
        overflow-x: auto;
        scrollbar-width: none;
    }
    .up-tab-group::-webkit-scrollbar { display: none; }
    .up-tab-btn {
        padding: 7px 14px;
        border-radius: 8px;
        border: none;
        background: transparent;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--muted, #9a6878);
        cursor: pointer;
        transition: all .2s;
        text-decoration: none;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .up-tab-btn:hover {
        color: var(--ink, #1a0a0e);
        background: #fff8f6;
    }
    .up-tab-btn.active {
        background: linear-gradient(135deg, var(--red, #c4102e), var(--red-2, #e8234f));
        color: #ffffff;
        box-shadow: 0 4px 10px -2px rgba(196, 16, 46, 0.4);
    }
    .up-search-form {
        position: relative;
        min-width: 260px;
    }
    .up-search-input {
        width: 100%;
        padding: 9px 14px 9px 38px;
        border-radius: 12px;
        border: 1px solid var(--line, #f0d5d0);
        background: #ffffff;
        font-size: 13px;
        color: var(--ink, #1a0a0e);
        outline: none;
        transition: border-color .2s;
        font-family: inherit;
    }
    .up-search-input:focus {
        border-color: var(--red, #c4102e);
        box-shadow: 0 0 0 3px rgba(196, 16, 46, 0.1);
    }
    .up-search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted, #9a6878);
        pointer-events: none;
    }

    /* ── Timeline of Releases ── */
    .up-timeline {
        position: relative;
        padding-left: 36px;
        margin-bottom: 40px;
    }
    .up-timeline::before {
        content: '';
        position: absolute;
        top: 14px;
        bottom: 24px;
        left: 13px;
        width: 2px;
        background: linear-gradient(180deg, var(--red, #c4102e) 0%, #f0d5d0 100%);
    }

    .up-release-card {
        position: relative;
        background: #ffffff;
        border: 1px solid var(--line, #f0d5d0);
        border-radius: var(--radius, 18px);
        padding: 24px 26px;
        margin-bottom: 24px;
        box-shadow: 0 10px 28px -12px rgba(60, 20, 40, 0.1);
        transition: transform .2s, border-color .2s;
    }
    .up-release-card:hover {
        transform: translateY(-2px);
        border-color: #e2b6bf;
    }
    .up-release-card.is-latest {
        border: 2px solid var(--red, #c4102e);
        box-shadow: 0 14px 34px -10px rgba(196, 16, 46, 0.22);
    }
    .up-timeline-node {
        position: absolute;
        left: -36px;
        top: 24px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #ffffff;
        border: 2.5px solid var(--red, #c4102e);
        display: grid;
        place-items: center;
        color: var(--red, #c4102e);
        box-shadow: 0 0 0 4px rgba(196, 16, 46, 0.12);
        z-index: 2;
    }
    .up-release-card.is-latest .up-timeline-node {
        background: var(--red, #c4102e);
        color: #ffffff;
        box-shadow: 0 0 0 5px rgba(196, 16, 46, 0.25);
    }

    .up-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }
    .up-version-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .up-version-badge {
        font-size: 15px;
        font-weight: 900;
        letter-spacing: -.02em;
        color: var(--ink, #1a0a0e);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .up-pill-badge {
        font-size: 10.5px;
        font-weight: 800;
        padding: 3px 9px;
        border-radius: 999px;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
    .up-pill-latest {
        background: #fde8ec;
        color: #c4102e;
        border: 1px solid #f7b8c4;
    }
    .up-pill-major {
        background: #e8ecf8;
        color: #2d3a8c;
        border: 1px solid #cad4f3;
    }
    .up-release-date {
        font-size: 12px;
        color: var(--muted, #9a6878);
        font-weight: 500;
    }
    .up-release-title {
        font-size: 17px;
        font-weight: 800;
        color: var(--ink, #1a0a0e);
        margin-bottom: 8px;
        line-height: 1.35;
    }
    .up-release-summary {
        font-size: 13.5px;
        color: #55333f;
        line-height: 1.6;
        margin-bottom: 16px;
    }

    /* Highlights Pills */
    .up-highlights-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .up-hl-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 8px;
        background: #fff8f6;
        border: 1px solid #eed6d2;
        font-size: 11.5px;
        font-weight: 700;
        color: #7a3a4b;
    }

    /* Changes Sections */
    .up-changes-group {
        display: flex;
        flex-direction: column;
        gap: 16px;
        padding-top: 14px;
        border-top: 1px dashed var(--line, #f0d5d0);
    }
    .up-category-block {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .up-category-title {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }
    .up-cat-features     { color: #1f5b45; }
    .up-cat-improvements { color: #2d3a8c; }
    .up-cat-fixes        { color: #c8800a; }

    .up-item-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding-left: 4px;
    }
    .up-change-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 8px 12px;
        border-radius: 10px;
        background: #faf6f5;
        border: 1px solid #f2e2df;
    }
    .up-item-bullet {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        margin-top: 7px;
        flex-shrink: 0;
    }
    .up-b-green  { background: #1f5b45; }
    .up-b-blue   { background: #2d3a8c; }
    .up-b-gold   { background: #c8800a; }
    .up-change-body { flex: 1; min-width: 0; }
    .up-change-body strong {
        display: block;
        font-size: 13px;
        color: var(--ink, #1a0a0e);
        font-weight: 700;
        margin-bottom: 2px;
    }
    .up-change-body p {
        font-size: 12.5px;
        color: #63434f;
        line-height: 1.5;
        margin: 0;
    }

    /* ── Roadmap Section ── */
    .up-section-box {
        background: #ffffff;
        border: 1px solid var(--line, #f0d5d0);
        border-radius: var(--radius, 18px);
        padding: 28px 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px -15px rgba(60, 20, 40, 0.1);
    }
    .up-section-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }
    .up-section-head h2 {
        font-size: 20px;
        font-weight: 800;
        color: var(--ink, #1a0a0e);
        letter-spacing: -.02em;
    }
    .up-section-head p {
        font-size: 13px;
        color: var(--muted, #9a6878);
    }
    .up-roadmap-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    .up-roadmap-card {
        padding: 18px 20px;
        border-radius: 14px;
        border: 1px solid #ebd9d5;
        background: #fffbfa;
        display: flex;
        flex-direction: column;
        gap: 10px;
        transition: transform .2s, border-color .2s;
    }
    .up-roadmap-card:hover {
        transform: translateY(-2px);
        border-color: #d698a6;
    }
    .up-road-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }
    .up-road-badge {
        font-size: 10px;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 999px;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
    .up-road-target {
        font-size: 11px;
        font-weight: 600;
        color: var(--muted, #9a6878);
    }
    .up-roadmap-card h3 {
        font-size: 14.5px;
        font-weight: 800;
        color: var(--ink, #1a0a0e);
        line-height: 1.35;
    }
    .up-roadmap-card p {
        font-size: 12.5px;
        color: #63434f;
        line-height: 1.55;
        margin: 0;
        flex: 1;
    }
    .up-road-phase {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11.5px;
        font-weight: 700;
        color: #7a3a4b;
        background: #ffffff;
        padding: 6px 12px;
        border-radius: 8px;
        border: 1px solid #ebd9d5;
        margin-top: 4px;
    }

    /* ── FAQ Accordion ── */
    .up-faq-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .up-faq-item {
        background: #fffbfa;
        border: 1px solid var(--line, #f0d5d0);
        border-radius: 12px;
        overflow: hidden;
    }
    .up-faq-summary {
        padding: 14px 18px;
        font-size: 13.5px;
        font-weight: 700;
        color: var(--ink, #1a0a0e);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        user-select: none;
    }
    .up-faq-summary::-webkit-details-marker { display: none; }
    .up-faq-content {
        padding: 0 18px 14px;
        font-size: 13px;
        color: #63434f;
        line-height: 1.6;
    }

    /* ── Responsive ── */
    @media (max-width: 900px) {
        .up-stats-grid { grid-template-columns: repeat(2, 1fr); }
        .up-roadmap-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 600px) {
        .up-hero { padding: 24px 20px; }
        .up-hero h1 { font-size: 22px; }
        .up-stats-grid { grid-template-columns: 1fr; }
        .up-timeline { padding-left: 20px; }
        .up-timeline::before { left: 4px; }
        .up-timeline-node { left: -22px; width: 22px; height: 22px; }
        .up-release-card { padding: 18px 16px; }
        .up-filter-bar { flex-direction: column; align-items: stretch; }
        .up-search-form { min-width: 100%; }
    }
@endsection

@section('content')
<div class="up-container">

    <!-- Flash Status Notification -->
    @if(session('status'))
        <div style="margin-bottom: 20px; padding: 14px 18px; border-radius: 12px; background: #e6f4ee; border: 1.5px solid #a3d9c1; color: #1f5b45; font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <!-- 1. Hero Header Banner -->
    <section class="up-hero" style="background: linear-gradient(135deg, #17143a 0%, #242050 55%, #631226 100%); color: #ffffff; padding: 36px 36px 40px; margin-bottom: 28px; border-radius: 20px;">
        <span class="up-eyebrow" style="background: rgba(255, 255, 255, 0.16); border: 1.5px solid rgba(255, 255, 255, 0.32); color: #ffffff; margin-bottom: 14px;">
            <span class="up-eyebrow-dot"></span>
            Pusat Catatan Rilis & Versi Sistem
        </span>
        <h1 style="color: #ffffff; margin-bottom: 12px; font-weight: 900;">Info Pembaruan & Versi JP Learn</h1>
        <p class="lead" style="color: rgba(255, 255, 255, 0.92); line-height: 1.65; max-width: 740px;">
            Selamat datang di pusat transparansi pembaruan platform! Di sini Anda dapat melihat perkembangan fitur baru, penyempurnaan kecerdasan AI Kenji Sensei, optimasi modul simulasi JLPT N5, serta arah peta jalan (roadmap) masa depan.
        </p>
        <div class="up-hero-actions" style="margin-top: 26px; padding-top: 4px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <button type="button" class="up-btn-primary" onclick="openWhatsNewModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
                Lihat Sorotan Terkini ({{ $currentMeta['current_version'] }})
            </button>

            <a href="#roadmapSection" class="up-btn-secondary">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
                Peta Jalan (Roadmap)
            </a>
        </div>
    </section>

    <!-- 2. System Status Bar -->
    <div class="up-status-strip">
        <div class="up-status-left">
            <div class="up-status-dot"></div>
            <strong>Status Sistem:</strong>
            <span style="color: #059669; font-weight: 700;">{{ $currentMeta['system_status']['label'] }}</span>
            <span style="color: #9a6878;">•</span>
            <span style="color: #63434f;">AI Engine: <strong>{{ $currentMeta['system_status']['ai_engine'] }}</strong></span>
        </div>
        <div class="up-status-right">
            <span>Versi Aktif: <strong style="color: var(--ink, #1a0a0e);">{{ $currentMeta['current_version'] }}</strong></span>
            <span>Diperiksa: {{ $currentMeta['system_status']['last_checked'] }}</span>
        </div>
    </div>

    <!-- 3. Key Summary Metrics Grid -->
    <div class="up-stats-grid">
        <div class="up-stat-card">
            <div class="up-stat-icon up-stat-red">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
            </div>
            <div>
                <strong>{{ $currentMeta['current_version'] }}</strong>
                <span>Versi Stabil Terkini</span>
            </div>
        </div>

        <div class="up-stat-card">
            <div class="up-stat-icon up-stat-blue">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 14 14"></polyline>
                </svg>
            </div>
            <div>
                <strong>{{ $stats['total_releases'] }} Rilis</strong>
                <span>Total Iterasi Versi</span>
            </div>
        </div>

        <div class="up-stat-card">
            <div class="up-stat-icon up-stat-green">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
            </div>
            <div>
                <strong>{{ $stats['total_features_count'] }}+ Fitur</strong>
                <span>Modul & Alat Belajar</span>
            </div>
        </div>

        <div class="up-stat-card">
            <div class="up-stat-icon up-stat-gold">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
            <div>
                <strong>{{ $stats['latest_release_date'] }}</strong>
                <span>Tanggal Rilis Terakhir</span>
            </div>
        </div>
    </div>

    <!-- 4. Pinned Announcements (If Any) -->
    @if(!empty($announcements))
        @foreach($announcements as $ann)
            <div class="up-announcement">
                <div class="up-ann-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </div>
                <div class="up-ann-content">
                    <div class="up-ann-header">
                        <span class="up-ann-badge">{{ $ann['badge'] }}</span>
                        <h2 class="up-ann-title">{{ $ann['title'] }}</h2>
                        <span style="font-size: 11.5px; color: var(--muted, #9a6878);">• {{ $ann['date'] }}</span>
                    </div>
                    <p class="up-ann-body">{{ $ann['content'] }}</p>
                </div>
            </div>
        @endforeach
    @endif

    <!-- 5. Interactive Filter Tabs & Search Bar -->
    <div class="up-filter-bar">
        <div class="up-tab-group">
            <a href="{{ route('updates.index') }}" class="up-tab-btn {{ $activeCategory === 'all' && empty($searchQuery) ? 'active' : '' }}">
                Semua Riwayat ({{ $stats['total_releases'] }})
            </a>
            <a href="{{ route('updates.index', ['kategori' => 'features']) }}" class="up-tab-btn {{ $activeCategory === 'features' ? 'active' : '' }}">
                <span style="display:inline-block; width:7px; height:7px; border-radius:50%; background:#10b981;"></span>
                Fitur Baru
            </a>
            <a href="{{ route('updates.index', ['kategori' => 'improvements']) }}" class="up-tab-btn {{ $activeCategory === 'improvements' ? 'active' : '' }}">
                <span style="display:inline-block; width:7px; height:7px; border-radius:50%; background:#3b82f6;"></span>
                Peningkatan & AI
            </a>
            <a href="{{ route('updates.index', ['kategori' => 'fixes']) }}" class="up-tab-btn {{ $activeCategory === 'fixes' ? 'active' : '' }}">
                <span style="display:inline-block; width:7px; height:7px; border-radius:50%; background:#f59e0b;"></span>
                Perbaikan Bug
            </a>
            <a href="#roadmapSection" class="up-tab-btn">
                <span style="display:inline-block; width:7px; height:7px; border-radius:50%; background:#8b5cf6;"></span>
                Roadmap Masa Depan
            </a>
        </div>

        <form action="{{ route('updates.index') }}" method="GET" class="up-search-form">
            @if($activeCategory && $activeCategory !== 'all')
                <input type="hidden" name="kategori" value="{{ $activeCategory }}">
            @endif
            <svg class="up-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" name="q" value="{{ $searchQuery }}" class="up-search-input" placeholder="Cari fitur, materi, JLPT, AI..." aria-label="Cari riwayat pembaruan">
        </form>
    </div>

    <!-- Search / Filter Notice if active -->
    @if(!empty($searchQuery) || ($activeCategory !== 'all'))
        <div style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; font-size: 13px; color: #55333f; padding: 10px 14px; background: #fff; border-radius: 10px; border: 1px solid var(--line, #f0d5d0);">
            <span>
                Menampilkan hasil untuk: 
                @if(!empty($searchQuery))
                    kata kunci <strong>"{{ $searchQuery }}"</strong>
                @endif
                @if($activeCategory !== 'all')
                    kategori <strong>"{{ ucfirst($activeCategory) }}"</strong>
                @endif
                ({{ count($versions) }} rilis ditemukan)
            </span>
            <a href="{{ route('updates.index') }}" style="color: var(--red, #c4102e); text-decoration: none; font-weight: 700;">Reset Filter ×</a>
        </div>
    @endif

    <!-- 6. Timeline of Releases -->
    <div class="up-timeline">
        @forelse($versions as $index => $release)
            @php
                $isLatest = ($index === 0 && empty($searchQuery) && $activeCategory === 'all');
            @endphp
            <article class="up-release-card {{ $isLatest ? 'is-latest' : '' }}" id="version-{{ Str::slug($release['version']) }}">
                <div class="up-timeline-node">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                </div>

                <div class="up-card-top">
                    <div class="up-version-meta">
                        <span class="up-version-badge">
                            {{ $release['version'] }}
                        </span>
                        @if($isLatest)
                            <span class="up-pill-badge up-pill-latest">Rilis Terkini</span>
                        @else
                            <span class="up-pill-badge up-pill-major">{{ $release['badge'] ?? 'Pembaruan' }}</span>
                        @endif
                        <span class="up-release-date">
                            <svg style="vertical-align: -2px; margin-right: 2px;" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            {{ $release['release_date'] }}
                        </span>
                    </div>

                    @if(!empty($release['codename']))
                        <span style="font-size: 11.5px; font-weight: 700; color: #8a6878; background: #fff8f6; padding: 3px 10px; border-radius: 6px; border: 1px solid #eddcd9;">
                            Edisi: {{ $release['codename'] }}
                        </span>
                    @endif
                </div>

                <h3 class="up-release-title">{{ $release['title'] }}</h3>
                <p class="up-release-summary">{{ $release['summary'] }}</p>

                <!-- Highlights Badges -->
                @if(!empty($release['highlights']))
                    <div class="up-highlights-row">
                        @foreach($release['highlights'] as $highlight)
                            <span class="up-hl-pill">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                {{ $highlight }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <!-- Itemized Changes -->
                <div class="up-changes-group">
                    <!-- Fitur Baru -->
                    @if(!empty($release['changes']['fitur_baru']))
                        <div class="up-category-block">
                            <span class="up-category-title up-cat-features">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                </svg>
                                Fitur Baru Ditambahkan ({{ count($release['changes']['fitur_baru']) }})
                            </span>
                            <div class="up-item-list">
                                @foreach($release['changes']['fitur_baru'] as $change)
                                    <div class="up-change-item">
                                        <div class="up-item-bullet up-b-green"></div>
                                        <div class="up-change-body">
                                            <strong>{{ $change['title'] }}</strong>
                                            <p>{{ $change['desc'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Peningkatan & Performa -->
                    @if(!empty($release['changes']['peningkatan']))
                        <div class="up-category-block">
                            <span class="up-category-title up-cat-improvements">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                </svg>
                                Peningkatan & Optimasi ({{ count($release['changes']['peningkatan']) }})
                            </span>
                            <div class="up-item-list">
                                @foreach($release['changes']['peningkatan'] as $change)
                                    <div class="up-change-item">
                                        <div class="up-item-bullet up-b-blue"></div>
                                        <div class="up-change-body">
                                            <strong>{{ $change['title'] }}</strong>
                                            <p>{{ $change['desc'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Perbaikan Bug -->
                    @if(!empty($release['changes']['perbaikan']))
                        <div class="up-category-block">
                            <span class="up-category-title up-cat-fixes">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                                Perbaikan Sistem & Bug Fixes ({{ count($release['changes']['perbaikan']) }})
                            </span>
                            <div class="up-item-list">
                                @foreach($release['changes']['perbaikan'] as $change)
                                    <div class="up-change-item">
                                        <div class="up-item-bullet up-b-gold"></div>
                                        <div class="up-change-body">
                                            <strong>{{ $change['title'] }}</strong>
                                            <p>{{ $change['desc'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </article>
        @empty
            <div style="text-align: center; padding: 48px 24px; background: #fff; border-radius: 16px; border: 1px dashed var(--line, #f0d5d0);">
                <div style="width: 52px; height: 52px; border-radius: 50%; background: #fff8f6; border: 1.5px solid #f0d5d0; color: #c4102e; display: grid; place-items: center; margin: 0 auto 14px;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <h3 style="font-size: 16px; font-weight: 800; margin-bottom: 6px;">Tidak Ada Catatan Rilis yang Cocok</h3>
                <p style="font-size: 13px; color: var(--muted, #9a6878); margin-bottom: 16px;">
                    Coba ubah kata kunci pencarian atau reset filter untuk melihat seluruh versi.
                </p>
                <a href="{{ route('updates.index') }}" class="up-btn-primary" style="display: inline-flex;">
                    Tampilkan Semua Versi
                </a>
            </div>
        @endforelse
    </div>

    <!-- 7. Roadmap (Fitur Masa Depan) Section -->
    <section class="up-section-box" id="roadmapSection">
        <div class="up-section-head">
            <div>
                <span class="up-category-title" style="color: #7c3aed; margin-bottom: 4px;">PETA JALAN PENGEMBANGAN</span>
                <h2>Apa yang Sedang & Akan Kami Kembangkan?</h2>
                <p>JP Learn terus bertransformasi. Berikut adalah inovasi yang sedang dipersiapkan untuk rilis mendatang:</p>
            </div>
        </div>

        <div class="up-roadmap-grid">
            @foreach($roadmap as $item)
                <div class="up-roadmap-card">
                    <div class="up-road-top">
                        <span class="up-road-badge {{ $item['badge_class'] }}">
                            {{ $item['status_label'] }}
                        </span>
                        <span class="up-road-target">{{ $item['target'] }}</span>
                    </div>
                    <h3>{{ $item['title'] }}</h3>
                    <p>{{ $item['desc'] }}</p>
                    <div class="up-road-phase">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                        <span>{{ $item['phase'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- 8. FAQ Accordion Section -->
    <section class="up-section-box">
        <div class="up-section-head">
            <div>
                <span class="up-category-title" style="color: #c8800a; margin-bottom: 4px;">TANYA JAWAB</span>
                <h2>Pertanyaan Umum Seputar Pembaruan</h2>
                <p>Informasi seputar cara kerja update, keamanan data akun, dan akses fitur baru.</p>
            </div>
        </div>

        <div class="up-faq-list">
            @foreach($faq as $item)
                <details class="up-faq-item">
                    <summary class="up-faq-summary">
                        <span>{{ $item['question'] }}</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </summary>
                    <div class="up-faq-content">
                        {{ $item['answer'] }}
                    </div>
                </details>
            @endforeach
        </div>
    </section>

</div>

@endsection
