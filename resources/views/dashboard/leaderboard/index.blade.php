@extends('dashboard.layout')

@php
    $pageTitle = 'Papan Peringkat (Leaderboard)';
    $pageSubtitle = 'Pantau peringkat belajarmu, bersaing sehat bersama komunitas pembelajar, dan raih posisi puncak!';
@endphp

@section('styles')
    /* ── Leaderboard Wrapper & Hero ── */
    .lb-wrap {
        max-width: 920px;
        margin: 0 auto;
    }

    .lb-hero {
        background: linear-gradient(135deg, #181926 0%, #291823 100%);
        border-radius: 22px;
        padding: 30px 32px;
        color: #fff;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 16px 36px -12px rgba(24, 25, 38, .35);
    }
    .lb-hero::after {
        content: '順位表';
        position: absolute;
        right: 20px;
        bottom: -22px;
        font-family: 'Noto Sans JP', sans-serif;
        font-size: 110px;
        font-weight: 900;
        color: rgba(255, 255, 255, .035);
        pointer-events: none;
        line-height: 1;
        user-select: none;
    }

    .lb-hero-badges {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }
    .lb-hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .12);
        color: #ffd8a8;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .06em;
        text-transform: uppercase;
    }
    .lb-hero-title {
        font-size: 26px;
        font-weight: 900;
        margin: 0 0 6px 0;
        letter-spacing: -.02em;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .lb-hero-sub {
        font-size: 14px;
        color: rgba(255, 255, 255, .78);
        margin: 0 0 20px 0;
        max-width: 620px;
        line-height: 1.55;
    }

    /* User Quick Stats in Hero */
    .lb-user-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        padding-top: 18px;
        border-top: 1px solid rgba(255, 255, 255, .1);
    }
    .lb-user-stat-card {
        background: rgba(255, 255, 255, .06);
        border: 1px solid rgba(255, 255, 255, .08);
        border-radius: 14px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        backdrop-filter: blur(8px);
    }
    .lb-stat-icon-wrap {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, .12);
        color: #ffd8a8;
        flex-shrink: 0;
    }
    .lb-stat-text {
        display: flex;
        flex-direction: column;
    }
    .lb-stat-label {
        font-size: 11px;
        color: rgba(255, 255, 255, .65);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
    }
    .lb-stat-value {
        font-size: 16px;
        font-weight: 800;
        color: #fff;
    }

    /* ── Tab Switcher ── */
    .lb-tabs {
        display: flex;
        gap: 8px;
        background: var(--card-bg, #ffffff);
        padding: 6px;
        border-radius: 16px;
        border: 1px solid var(--border, #e5e7eb);
        margin-bottom: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .03);
    }
    .lb-tab-btn {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        color: var(--text-muted, #64748b);
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all .2s ease;
    }
    .lb-tab-btn:hover {
        color: var(--text, #1e293b);
        background: rgba(0, 0, 0, .03);
    }
    .lb-tab-btn.active {
        background: linear-gradient(135deg, #e8234f 0%, #c4183c 100%);
        color: #fff;
        box-shadow: 0 4px 14px rgba(232, 35, 79, .3);
    }
    .lb-tab-btn svg {
        width: 17px;
        height: 17px;
        stroke-width: 2.3;
    }

    /* ── Tab Pane Visibility ── */
    .lb-pane {
        display: none;
        animation: fadeInPane .28s ease;
    }
    .lb-pane.active {
        display: block;
    }
    @keyframes fadeInPane {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ── Top 3 Podium Area ── */
    .podium-container {
        margin: 36px auto 30px;
        max-width: 680px;
        padding: 0 10px;
    }
    .podium-grid {
        display: flex;
        align-items: flex-end;
        justify-content: center;
        gap: 16px;
    }
    .podium-step {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
    }
    .podium-step.rank-1 { order: 2; z-index: 3; }
    .podium-step.rank-2 { order: 1; z-index: 2; }
    .podium-step.rank-3 { order: 3; z-index: 1; }

    /* Podium User Floating Card */
    .podium-avatar-wrap {
        position: relative;
        margin-bottom: 12px;
        display: inline-flex;
    }
    .podium-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: 800;
        color: #fff;
        box-shadow: 0 8px 20px rgba(0, 0, 0, .14);
        border: 3px solid #fff;
    }
    .podium-step.rank-1 .podium-avatar {
        width: 82px;
        height: 82px;
        font-size: 28px;
        border-color: #f59e0b;
        box-shadow: 0 10px 28px rgba(245, 158, 11, .38);
    }
    .podium-step.rank-2 .podium-avatar {
        border-color: #94a3b8;
    }
    .podium-step.rank-3 .podium-avatar {
        border-color: #d97706;
    }

    .podium-crown {
        position: absolute;
        top: -24px;
        left: 50%;
        transform: translateX(-50%);
        color: #f59e0b;
        filter: drop-shadow(0 3px 6px rgba(245, 158, 11, .5));
        animation: floatCrown 2.5s ease-in-out infinite;
    }
    @keyframes floatCrown {
        0%, 100% { transform: translateX(-50%) translateY(0); }
        50% { transform: translateX(-50%) translateY(-4px); }
    }

    .podium-badge-rank {
        position: absolute;
        bottom: -6px;
        right: -4px;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 900;
        color: #fff;
        border: 2px solid #fff;
    }
    .podium-step.rank-1 .podium-badge-rank {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        width: 30px;
        height: 30px;
        font-size: 15px;
    }
    .podium-step.rank-2 .podium-badge-rank {
        background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);
    }
    .podium-step.rank-3 .podium-badge-rank {
        background: linear-gradient(135deg, #b45309 0%, #78350f 100%);
    }

    .podium-name {
        font-size: 14px;
        font-weight: 800;
        color: var(--text, #1e293b);
        margin: 0 0 2px 0;
        max-width: 130px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .podium-step.rank-1 .podium-name {
        font-size: 16px;
        max-width: 160px;
    }

    .podium-sub {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-muted, #64748b);
        margin-bottom: 8px;
    }

    /* Pedestal Base */
    .pedestal-base {
        width: 100%;
        border-radius: 16px 16px 8px 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 12px 8px;
        transition: transform .2s ease;
    }
    .pedestal-base:hover {
        transform: translateY(-2px);
    }
    .podium-step.rank-1 .pedestal-base {
        height: 130px;
        background: linear-gradient(180deg, rgba(245, 158, 11, .18) 0%, rgba(245, 158, 11, .06) 100%);
        border: 2px solid rgba(245, 158, 11, .45);
        border-bottom: 4px solid #f59e0b;
    }
    .podium-step.rank-2 .pedestal-base {
        height: 100px;
        background: linear-gradient(180deg, rgba(148, 163, 184, .18) 0%, rgba(148, 163, 184, .06) 100%);
        border: 2px solid rgba(148, 163, 184, .45);
        border-bottom: 4px solid #94a3b8;
    }
    .podium-step.rank-3 .pedestal-base {
        height: 80px;
        background: linear-gradient(180deg, rgba(217, 119, 6, .18) 0%, rgba(217, 119, 6, .06) 100%);
        border: 2px solid rgba(217, 119, 6, .45);
        border-bottom: 4px solid #d97706;
    }

    .pedestal-number {
        font-size: 32px;
        font-weight: 900;
        line-height: 1;
        opacity: .35;
    }
    .podium-step.rank-1 .pedestal-number {
        color: #f59e0b;
        font-size: 40px;
        opacity: .5;
    }
    .podium-step.rank-2 .pedestal-number {
        color: #64748b;
    }
    .podium-step.rank-3 .pedestal-number {
        color: #b45309;
    }

    .pedestal-score {
        margin-top: 4px;
        font-size: 13px;
        font-weight: 800;
        color: var(--text, #1e293b);
        padding: 3px 8px;
        border-radius: 6px;
        background: var(--card-bg, #fff);
        border: 1px solid var(--border, #e5e7eb);
    }
    .podium-step.rank-1 .pedestal-score {
        font-size: 14px;
        border-color: rgba(245, 158, 11, .4);
        color: #b45309;
    }

    /* ── Leaderboard Table Rows (Ranks 4 - 10) ── */
    .lb-card {
        background: var(--card-bg, #ffffff);
        border-radius: 18px;
        border: 1px solid var(--border, #e5e7eb);
        padding: 8px 12px;
        margin-bottom: 20px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, .03);
    }
    .lb-card-header {
        padding: 12px 14px;
        font-size: 13px;
        font-weight: 800;
        color: var(--text-muted, #64748b);
        text-transform: uppercase;
        letter-spacing: .05em;
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid var(--border, #f1f5f9);
    }

    .lb-row {
        display: flex;
        align-items: center;
        padding: 12px 14px;
        border-radius: 12px;
        transition: all .2s ease;
        gap: 14px;
        margin-bottom: 4px;
    }
    .lb-row:hover {
        background: rgba(0, 0, 0, .02);
    }
    .lb-row.is-self {
        background: linear-gradient(90deg, rgba(232, 35, 79, .08) 0%, rgba(232, 35, 79, .02) 100%);
        border: 1px solid rgba(232, 35, 79, .25);
    }

    .lb-row-rank {
        width: 36px;
        text-align: center;
        flex-shrink: 0;
    }
    .rank-number {
        font-size: 14px;
        font-weight: 800;
        color: var(--text-muted, #64748b);
    }
    .rank-number.highlight {
        color: #e8234f;
        font-size: 16px;
    }

    .lb-row-avatar {
        flex-shrink: 0;
    }
    .avatar-initial {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 800;
        font-size: 15px;
        background: linear-gradient(135deg, #475569 0%, #334155 100%);
    }

    .lb-row-info {
        flex: 1;
        min-width: 0;
    }
    .lb-row-name {
        font-size: 14px;
        font-weight: 800;
        color: var(--text, #1e293b);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .badge-self {
        font-size: 10px;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 999px;
        background: #e8234f;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: .04em;
    }
    .lb-row-sub {
        font-size: 12px;
        color: var(--text-muted, #64748b);
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 2px;
    }
    .lb-lvl {
        font-weight: 700;
        color: #e8234f;
    }
    .lb-dot {
        opacity: .5;
    }
    .lb-streak {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        color: #ea580c;
        font-weight: 700;
    }
    .lb-streak svg {
        width: 12px;
        height: 12px;
    }

    .lb-row-score {
        text-align: right;
        flex-shrink: 0;
    }
    .score-val {
        font-size: 15px;
        font-weight: 900;
        color: var(--text, #1e293b);
        letter-spacing: -.01em;
    }

    /* ── Pinned User Card (When outside top 10) ── */
    .lb-self-pinned {
        background: linear-gradient(135deg, #fff 0%, #fff7ed 100%);
        border: 2px dashed rgba(232, 35, 79, .35);
        border-radius: 16px;
        padding: 16px 20px;
        margin-top: 18px;
    }
    .lb-self-pinned-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 800;
        color: #e8234f;
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-bottom: 8px;
    }
    .lb-self-pinned-badge svg {
        width: 15px;
        height: 15px;
    }
    .lb-self-pinned-hint {
        font-size: 12px;
        color: var(--text-muted, #64748b);
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px solid rgba(0, 0, 0, .06);
    }

    /* ── Empty State ── */
    .lb-empty-box {
        text-align: center;
        padding: 48px 24px;
        background: var(--card-bg, #fff);
        border-radius: 18px;
        border: 1px solid var(--border, #e5e7eb);
    }
    .lb-empty-icon {
        width: 58px;
        height: 58px;
        margin: 0 auto 16px;
        border-radius: 50%;
        background: rgba(232, 35, 79, .1);
        color: #e8234f;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .lb-empty-box h3 {
        font-size: 17px;
        font-weight: 800;
        color: var(--text, #1e293b);
        margin: 0 0 6px 0;
    }
    .lb-empty-box p {
        font-size: 13px;
        color: var(--text-muted, #64748b);
        margin: 0 0 20px 0;
        max-width: 440px;
        margin-left: auto;
        margin-right: auto;
    }

    /* ── Responsive Mobile ── */
    @media (max-width: 640px) {
        .lb-hero {
            padding: 22px 18px;
        }
        .lb-hero-title {
            font-size: 21px;
        }
        .lb-tabs {
            flex-direction: column;
            gap: 4px;
        }
        .lb-tab-btn {
            padding: 10px 14px;
            font-size: 13px;
        }
        .podium-grid {
            gap: 8px;
        }
        .podium-avatar {
            width: 50px;
            height: 50px;
            font-size: 18px;
        }
        .podium-step.rank-1 .podium-avatar {
            width: 66px;
            height: 66px;
            font-size: 22px;
        }
        .podium-name {
            font-size: 12px;
            max-width: 90px;
        }
        .podium-step.rank-1 .podium-name {
            font-size: 13px;
            max-width: 110px;
        }
        .pedestal-score {
            font-size: 11px;
            padding: 2px 4px;
        }
        .podium-step.rank-1 .pedestal-base {
            height: 105px;
        }
        .podium-step.rank-2 .pedestal-base {
            height: 80px;
        }
        .podium-step.rank-3 .pedestal-base {
            height: 65px;
        }
    }
@endsection

@section('content')
<div class="lb-wrap">
    <!-- Hero Banner -->
    <div class="lb-hero">
        <div class="lb-hero-badges">
            <span class="lb-hero-pill">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                    <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                    <path d="M4 22h16"></path>
                    <path d="M18 2H6v7a6 6 0 0 0 12 0V2z"></path>
                </svg>
                Papan Peringkat Komunitas
            </span>
        </div>

        <h1 class="lb-hero-title">
            <span>Papan Peringkat (リーダーボード)</span>
        </h1>
        <p class="lb-hero-sub">
            Lihat para pembelajar teraktif, adu ketekunan streak harian, dan buktikan siapa yang paling tekun mendalami bahasa Jepang!
        </p>

        <!-- Current User Snapshot -->
        <div class="lb-user-summary">
            <div class="lb-user-stat-card">
                <div class="lb-stat-icon-wrap">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                        <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                        <path d="M4 22h16"></path>
                        <path d="M18 2H6v7a6 6 0 0 0 12 0V2z"></path>
                    </svg>
                </div>
                <div class="lb-stat-text">
                    <span class="lb-stat-label">Peringkat XP Pekan Ini</span>
                    <span class="lb-stat-value">#{{ $currentUserStats['weekly']['rank'] }} ({{ $currentUserStats['weekly']['score_label'] }})</span>
                </div>
            </div>

            <div class="lb-user-stat-card">
                <div class="lb-stat-icon-wrap">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </div>
                <div class="lb-stat-text">
                    <span class="lb-stat-label">Level & Gelar Kamu</span>
                    <span class="lb-stat-value">Lv. {{ $currentUserStats['level'] }} · {{ Str::before($currentUserStats['title'], '·') }}</span>
                </div>
            </div>

            <div class="lb-user-stat-card">
                <div class="lb-stat-icon-wrap">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v6m0 8v6M4.93 4.93l4.24 4.24m5.66 5.66l4.24 4.24M2 12h6m8 0h6M4.93 19.07l4.24-4.24m5.66-5.66l4.24-4.24"></path>
                    </svg>
                </div>
                <div class="lb-stat-text">
                    <span class="lb-stat-label">Streak Disiplin</span>
                    <span class="lb-stat-value">{{ $currentUserStats['streak']['score_label'] }} (Rank #{{ $currentUserStats['streak']['rank'] }})</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Switcher Tabs -->
    <div class="lb-tabs" role="tablist">
        <button type="button" class="lb-tab-btn active" id="tab-weekly" onclick="switchTab('weekly')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            XP Pekan Ini (7 Hari)
        </button>
        <button type="button" class="lb-tab-btn" id="tab-streak" onclick="switchTab('streak')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"></path>
            </svg>
            Streak Juara (Hari)
        </button>
        <button type="button" class="lb-tab-btn" id="tab-alltime" onclick="switchTab('alltime')">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
            </svg>
            Sepanjang Masa (All-Time)
        </button>
    </div>

    <!-- PANE 1: Weekly XP -->
    <div class="lb-pane active" id="pane-weekly">
        @if($weeklyLeaderboard->isEmpty())
            <div class="lb-empty-box">
                <div class="lb-empty-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                <h3>Belum Ada Aktivitas Pekan Ini</h3>
                <p>Jadilah pembelajar pertama yang mengumpulkan XP pekan ini dengan menyelesaikan materi atau tantangan harian!</p>
                <a href="{{ route('daily-challenge.index') }}" class="btn-arena primary" style="display:inline-flex; align-items:center; gap:8px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                    </svg>
                    Buka Tantangan Harian
                </a>
            </div>
        @else
            <!-- Top 3 Podium -->
            @include('dashboard.leaderboard.partials.podium', ['items' => $weeklyLeaderboard, 'unit' => 'XP'])

            <!-- Ranks 4-10 -->
            @include('dashboard.leaderboard.partials.list', [
                'items' => $weeklyLeaderboard,
                'currentUserStat' => $currentUserStats['weekly'],
                'userStats' => $currentUserStats,
                'unit' => 'XP'
            ])
        @endif
    </div>

    <!-- PANE 2: Streak Leaderboard -->
    <div class="lb-pane" id="pane-streak">
        @if($streakLeaderboard->isEmpty())
            <div class="lb-empty-box">
                <div class="lb-empty-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"></path>
                    </svg>
                </div>
                <h3>Belum Ada Data Rantai Streak</h3>
                <p>Belajarlah setiap hari secara konsisten untuk membangun dan mempertahankan api streak belajarmu.</p>
            </div>
        @else
            <!-- Top 3 Podium -->
            @include('dashboard.leaderboard.partials.podium', ['items' => $streakLeaderboard, 'unit' => 'Hari'])

            <!-- Ranks 4-10 -->
            @include('dashboard.leaderboard.partials.list', [
                'items' => $streakLeaderboard,
                'currentUserStat' => $currentUserStats['streak'],
                'userStats' => $currentUserStats,
                'unit' => 'Hari'
            ])
        @endif
    </div>

    <!-- PANE 3: All-Time Hall of Fame -->
    <div class="lb-pane" id="pane-alltime">
        @if($allTimeLeaderboard->isEmpty())
            <div class="lb-empty-box">
                <div class="lb-empty-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </div>
                <h3>Belum Ada Pembelajar</h3>
                <p>Mulai belajarmu sekarang dan jadilah legenda pertama di Aula Kehormatan JP Learn!</p>
            </div>
        @else
            <!-- Top 3 Podium -->
            @include('dashboard.leaderboard.partials.podium', ['items' => $allTimeLeaderboard, 'unit' => 'XP'])

            <!-- Ranks 4-10 -->
            @include('dashboard.leaderboard.partials.list', [
                'items' => $allTimeLeaderboard,
                'currentUserStat' => $currentUserStats['all_time'],
                'userStats' => $currentUserStats,
                'unit' => 'XP'
            ])
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    function switchTab(tabKey) {
        // Toggle tab buttons
        document.querySelectorAll('.lb-tab-btn').forEach(function(btn) {
            btn.classList.remove('active');
        });
        const activeBtn = document.getElementById('tab-' + tabKey);
        if (activeBtn) {
            activeBtn.classList.add('active');
        }

        // Toggle panes
        document.querySelectorAll('.lb-pane').forEach(function(pane) {
            pane.classList.remove('active');
        });
        const activePane = document.getElementById('pane-' + tabKey);
        if (activePane) {
            activePane.classList.add('active');
        }
    }
</script>
@endsection

