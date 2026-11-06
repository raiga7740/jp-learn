<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Dashboard' }} — JP Learn</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans+JP:wght@500;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #fff8f6;
            --card: #ffffff;
            --ink: #1a0a0e;
            --muted: #9a6878;
            --line: #f0d5d0;
            --red: #c4102e;
            --red-2: #e8234f;
            --red-soft: #fde8ec;
            --navy: #17143a;
            --navy-2: #242050;
            --green: #1f5b45;
            --green-soft: #e6f4ee;
            --gold: #c8800a;
            --gold-soft: #fef6e0;
            --blue: #2d3a8c;
            --blue-soft: #e8ecf8;
            --sidebar: 260px;
            --radius: 18px;
            --shadow: 0 12px 34px -24px rgba(180,10,40,.36);
            --shadow-sm: 0 4px 16px -6px rgba(60,20,40,.12);
            --shadow-lg: 0 20px 45px -15px rgba(23,20,58,.22);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            overflow-x: hidden;
            color: var(--ink);
            background: var(--bg);
            font: 14px/1.55 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 100;
            width: var(--sidebar);
            padding: 24px 16px;
            color: #fff;
            background: var(--navy);
            display: flex;
            flex-direction: column;
            transition: transform .3s cubic-bezier(.4,0,.2,1);
        }
        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            color: #fff;
            font-size: 17px;
            font-weight: 800;
            text-decoration: none;
            letter-spacing: -.02em;
        }
        .brand-mark {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            border-radius: 11px;
            background: linear-gradient(135deg, var(--red-2), var(--red));
            font: 900 18px 'Noto Sans JP', sans-serif;
            box-shadow: 0 8px 20px -6px rgba(196,16,46,.8);
            flex-shrink: 0;
        }
        .brand em { color: #ff6b8a; font-style: normal; }
        .sidebar-close-btn {
            display: none;
            background: transparent;
            border: none;
            color: rgba(255,255,255,.6);
            cursor: pointer;
            padding: 6px;
            border-radius: 8px;
            transition: .2s;
        }
        .sidebar-close-btn:hover { color: #fff; background: rgba(255,255,255,.1); }

        .nav-label {
            margin: 18px 10px 8px;
            color: rgba(255,255,255,.36);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .14em;
            text-transform: uppercase;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            margin-bottom: 3px;
            padding: 10px 14px;
            border: 1px solid transparent;
            border-radius: 12px;
            color: rgba(255,255,255,.62);
            font-weight: 600;
            font-size: 13.5px;
            text-decoration: none;
            transition: all .2s ease;
        }
        .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,.08);
        }
        .nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, rgba(179,21,59,.42), rgba(212,42,82,.24));
            border-color: rgba(255,107,138,.24);
        }
        .nav-link.active::before {
            content: '';
            position: absolute;
            inset: 18% auto 18% 0;
            width: 3.5px;
            border-radius: 3px;
            background: var(--red-2);
        }
        .nav-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }
        .nav-icon svg {
            display: block;
            width: 18px;
            height: 18px;
        }

        .sidebar-footer {
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid rgba(255,255,255,.1);
        }
        .user-card {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 11px 12px;
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 13px;
            background: rgba(255,255,255,.06);
        }
        .avatar {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            vertical-align: middle;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--red), #9b1dff);
            color: #ffffff;
            font-size: 14px;
            font-weight: 800;
            line-height: 1;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(0,0,0,.25);
            user-select: none;
        }
        .user-card-info { min-width: 0; flex: 1; }
        .user-card strong {
            display: block;
            font-size: 13px;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .user-card-role {
            display: block;
            color: #ff9aad;
            font-size: 11px;
            font-weight: 600;
        }
        .logout {
            width: 100%;
            margin-top: 10px;
            padding: 9px 12px;
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 10px;
            background: transparent;
            color: rgba(255,255,255,.7);
            cursor: pointer;
            font: 600 12.5px 'Inter', sans-serif;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: .2s;
        }
        .logout:hover {
            color: #fff;
            border-color: rgba(255,107,138,.6);
            background: rgba(196,16,46,.18);
        }

        /* ── Sidebar Overlay (Mobile Drawer) ── */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15,12,32,.55);
            backdrop-filter: blur(4px);
            z-index: 90;
            opacity: 0;
            transition: opacity .25s ease;
        }
        body.sidebar-open .sidebar-backdrop {
            display: block;
            opacity: 1;
        }

        /* ── Main Layout ── */
        .main {
            min-width: 0;
            min-height: 100vh;
            margin-left: var(--sidebar);
            display: flex;
            flex-direction: column;
        }

        /* ── Topbar / Navbar ── */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 14px 36px;
            border-bottom: 1px solid var(--line);
            background: rgba(255,248,246,.98);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }
        .sidebar-toggle-btn {
            display: none;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            padding: 0;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #fff;
            color: var(--ink);
            cursor: pointer;
            flex-shrink: 0;
            transition: .2s;
        }
        .sidebar-toggle-btn:hover {
            border-color: var(--red);
            color: var(--red);
        }
        .topbar-title { min-width: 0; }
        .topbar h1 {
            color: var(--red);
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -.02em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .topbar p {
            color: var(--muted);
            font-size: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-shrink: 0;
        }
        .date {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: #fff;
            color: #66404f;
            font-size: 12px;
            font-weight: 600;
        }
        .date svg { color: var(--red); }

        /* ── Gamifikasi: chip topbar ── */
        .gami-chips { display: flex; align-items: center; gap: 8px; }
        .gami-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 12px;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: #fff;
            font-size: 12px;
            font-weight: 800;
            text-decoration: none;
            white-space: nowrap;
        }
        .gami-chip.streak { color: #d9480f; }
        .gami-chip.streak.off { color: var(--muted); }
        .gami-chip.xp { color: var(--blue); }
        .gami-chip svg { flex-shrink: 0; }

        /* ── Gamifikasi: kartu ringkasan ── */
        .gami-card {
            display: grid;
            grid-template-columns: 1fr 1.15fr;
            gap: 16px;
            margin-bottom: 26px;
        }
        .gami-panel {
            padding: 22px 24px;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: var(--card);
            box-shadow: var(--shadow);
        }
        .gami-panel-title {
            margin-bottom: 14px;
            color: var(--muted);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
        }
        .gami-level-row { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
        .gami-level-badge {
            display: grid;
            place-items: center;
            width: 60px;
            height: 60px;
            border-radius: 18px;
            background: linear-gradient(135deg, #2d3a8c, #5b6bd6);
            color: #fff;
            font-size: 24px;
            font-weight: 900;
            box-shadow: 0 10px 22px -10px rgba(45,58,140,.8);
            flex-shrink: 0;
        }
        .gami-level-name { color: var(--ink); font-size: 15px; font-weight: 800; line-height: 1.3; }
        .gami-level-xp { margin-top: 2px; color: var(--muted); font-size: 12.5px; font-weight: 600; }
        .gami-xp-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
            color: var(--muted);
            font-size: 11.5px;
            font-weight: 700;
        }
        .gami-streak-head { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; }
        .gami-flame {
            display: grid;
            place-items: center;
            width: 56px;
            height: 56px;
            border-radius: 18px;
            background: #f3ecee;
            color: #b8a6ad;
            flex-shrink: 0;
        }
        .gami-flame.on {
            background: linear-gradient(135deg, #ffa94d, #f76707);
            color: #fff;
            box-shadow: 0 10px 22px -10px rgba(247,103,7,.85);
        }
        .gami-streak-number { color: var(--ink); font-size: 26px; font-weight: 900; line-height: 1.1; }
        .gami-streak-number small { margin-left: 4px; color: var(--muted); font-size: 13px; font-weight: 700; }
        .gami-streak-hint { margin-top: 3px; color: var(--muted); font-size: 12.5px; font-weight: 600; line-height: 1.4; }
        .week-strip { display: grid; grid-template-columns: repeat(7, 1fr); gap: 6px; }
        .week-day { text-align: center; color: var(--muted); font-size: 10.5px; font-weight: 700; }
        .week-dot {
            display: grid;
            place-items: center;
            width: 30px;
            height: 30px;
            margin: 5px auto 0;
            border-radius: 50%;
            background: #f3ecee;
            color: transparent;
        }
        .week-dot.active { background: linear-gradient(135deg, #ffa94d, #f76707); color: #fff; }
        .week-dot.today { outline: 2px solid var(--red); outline-offset: 2px; }
        .gami-meta-row {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px solid var(--line);
            color: var(--muted);
            font-size: 12px;
            font-weight: 600;
        }
        .gami-meta-row strong { color: var(--ink); font-weight: 800; }

        /* ── Gamifikasi: heatmap aktivitas ── */
        .heatmap-scroll { overflow-x: auto; padding-bottom: 6px; }
        .heatmap { display: flex; gap: 4px; width: max-content; }
        .heat-col { display: flex; flex-direction: column; gap: 4px; }
        .heat-month { height: 14px; color: var(--muted); font-size: 10px; font-weight: 700; }
        .heat-cell { width: 15px; height: 15px; border-radius: 4px; background: #f3ecee; }
        .heat-cell.lv1 { background: #cfe9d8; }
        .heat-cell.lv2 { background: #8fcfa6; }
        .heat-cell.lv3 { background: #4fae76; }
        .heat-cell.lv4 { background: #1f7a4a; }
        .heat-cell.future { visibility: hidden; }
        .heat-legend {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 12px;
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
        }
        .heat-legend .heat-cell { width: 12px; height: 12px; }

        /* ── Profile Dropdown in Topbar ── */
        .profile-menu {
            position: relative;
        }
        .profile-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 5px 12px 5px 6px;
            border: 1.5px solid var(--line);
            border-radius: 999px;
            background: #fff;
            color: var(--ink);
            cursor: pointer;
            font-family: inherit;
            transition: all .2s ease;
        }
        .profile-btn:hover,
        .profile-menu.open .profile-btn {
            border-color: var(--red);
            box-shadow: 0 4px 14px -4px rgba(196,16,46,.25);
        }
        .profile-btn .avatar {
            width: 32px;
            height: 32px;
            font-size: 12px;
        }
        .profile-btn-info {
            text-align: left;
            line-height: 1.25;
        }
        .profile-btn-name {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: var(--ink);
            max-width: 120px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .profile-btn-role {
            display: block;
            font-size: 10.5px;
            font-weight: 600;
            color: var(--red);
        }
        .profile-chevron {
            color: var(--muted);
            transition: transform .2s ease;
            display: inline-flex;
        }
        .profile-menu.open .profile-chevron {
            transform: rotate(180deg);
            color: var(--red);
        }
        .profile-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 250px;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            padding: 8px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(6px);
            transition: all .2s cubic-bezier(.4,0,.2,1);
            z-index: 60;
        }
        .profile-menu.open .profile-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .dropdown-header {
            padding: 12px 14px;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--bg);
            border-radius: 10px;
            margin-bottom: 6px;
        }
        .dropdown-header .avatar {
            width: 38px;
            height: 38px;
            font-size: 14px;
        }
        .dropdown-header-info { min-width: 0; flex: 1; }
        .dropdown-name {
            font-size: 13px;
            font-weight: 800;
            color: var(--ink);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .dropdown-email {
            font-size: 11px;
            color: var(--muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 4px;
        }
        .dropdown-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            background: var(--red-soft);
            color: var(--red);
            font-size: 10px;
            font-weight: 800;
        }
        .dropdown-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 10px;
            color: #4b3d44;
            font-size: 12.5px;
            font-weight: 600;
            text-decoration: none;
            transition: .18s;
        }
        .dropdown-item:hover {
            color: var(--red);
            background: var(--red-soft);
        }
        .dropdown-item svg {
            color: var(--muted);
            transition: .18s;
        }
        .dropdown-item:hover svg {
            color: var(--red);
        }
        .dropdown-divider {
            height: 1px;
            background: var(--line);
            margin: 6px 0;
        }
        .dropdown-logout-btn {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 10px;
            border: none;
            background: transparent;
            color: #b01a33;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: .18s;
            text-align: left;
        }
        .dropdown-logout-btn:hover {
            background: #fee7eb;
            color: var(--red);
        }
        .dropdown-logout-btn svg { color: #b01a33; }

        /* ── Mobile Navigation Bar (Quick Tabs) ── */
        .mobile-nav {
            display: none;
        }

        /* ── Content Container ── */
        .content {
            width: 100%;
            padding: 28px 36px 48px;
            flex: 1;
        }

        /* ── Shared Component Styles ── */
        .flash {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 22px;
            padding: 12px 18px;
            border: 1px solid #aed8c0;
            border-radius: 14px;
            background: var(--green-soft);
            color: var(--green);
            font-weight: 600;
            font-size: 13.5px;
            animation: fadeIn .3s ease;
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: none; } }

        .hero {
            position: relative;
            overflow: hidden;
            margin-bottom: 26px;
            padding: 30px 32px;
            border-radius: var(--radius);
            color: #fff;
            background: linear-gradient(130deg, #8b0020, #c4102e 54%, #e8234f);
            box-shadow: 0 20px 48px -24px rgba(196,16,46,.7);
        }
        .hero::after {
            content: 'あ';
            position: absolute;
            right: 22px;
            bottom: -78px;
            color: rgba(255,255,255,.09);
            font: 900 200px/1 'Noto Sans JP', sans-serif;
            pointer-events: none;
            user-select: none;
        }
        .hero > * { position: relative; z-index: 1; }
        .eyebrow {
            display: inline-block;
            margin-bottom: 9px;
            color: #ffe0e6;
            font-size: 10.5px;
            font-weight: 800;
            letter-spacing: .13em;
            text-transform: uppercase;
        }
        .hero h2 {
            margin-bottom: 6px;
            font: 900 26px 'Noto Sans JP', sans-serif;
            letter-spacing: -.02em;
        }
        .hero p {
            max-width: 620px;
            color: rgba(255,255,255,.88);
            font-size: 14px;
            line-height: 1.6;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 26px;
        }
        .stat {
            display: flex;
            align-items: center;
            gap: 14px;
            min-height: 94px;
            padding: 16px 18px;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: var(--card);
            box-shadow: var(--shadow);
            transition: transform .2s ease, border-color .2s ease;
        }
        .stat:hover {
            transform: translateY(-2px);
            border-color: rgba(196,16,46,.25);
        }
        .stat-icon {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border-radius: 13px;
            background: var(--red-soft);
            color: var(--red);
            font-size: 20px;
            flex-shrink: 0;
        }
        .stat-icon svg {
            display: block;
            width: 20px;
            height: 20px;
        }
        .stat:nth-child(2) .stat-icon { background: var(--green-soft); color: var(--green); }
        .stat:nth-child(3) .stat-icon { background: var(--gold-soft); color: var(--gold); }
        .stat:nth-child(4) .stat-icon { background: var(--blue-soft); color: var(--blue); }
        .stat strong {
            display: block;
            font-size: 24px;
            letter-spacing: -.04em;
            line-height: 1.1;
            font-weight: 800;
        }
        .stat span {
            color: var(--muted);
            font-size: 12px;
            font-weight: 500;
        }

        .section {
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: var(--card);
            box-shadow: var(--shadow);
        }
        .section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 18px 22px;
            border-bottom: 1px solid var(--line);
        }
        .section-head h2 { font-size: 16px; font-weight: 800; }
        .section-head p { color: var(--muted); font-size: 12px; }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 10px 16px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--red), var(--red-2));
            color: #fff;
            font-size: 12.5px;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 18px -10px var(--red);
            transition: all .2s ease;
        }
        .button:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 22px -8px var(--red);
        }
        .button svg {
            width: 14px;
            height: 14px;
            transition: transform .2s ease;
        }
        .button:hover svg {
            transform: translateX(2px);
        }
        .button.ghost {
            border: 1px solid var(--line);
            background: #fff;
            color: #6d4252;
            box-shadow: none;
        }
        .button.ghost:hover {
            border-color: var(--red);
            color: var(--red);
        }

        .empty {
            padding: 48px 24px;
            color: var(--muted);
            text-align: center;
        }
        .empty strong {
            display: block;
            margin-bottom: 5px;
            color: var(--ink);
            font-size: 15px;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 999px;
            background: var(--red-soft);
            color: var(--red);
            font-size: 10.5px;
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
        }
        .badge.green { background: var(--green-soft); color: var(--green); }
        .badge.blue  { background: var(--blue-soft);  color: var(--blue); }

        .progress-track {
            width: 100%;
            height: 8px;
            overflow: hidden;
            border-radius: 999px;
            background: #f8e9e5;
        }
        .progress-fill {
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, var(--red), var(--red-2));
            transition: width .8s cubic-bezier(.4,0,.2,1);
        }

        /* ── Responsive Media Queries ── */
        @media (max-width: 900px) {
            .sidebar {
                transform: translateX(-100%);
            }
            body.sidebar-open .sidebar {
                transform: translateX(0);
                box-shadow: 0 0 50px rgba(0,0,0,.5);
            }
            .sidebar-close-btn { display: inline-flex; }
            .sidebar-toggle-btn { display: inline-flex; }
            .main { margin-left: 0; }
            .topbar { padding: 12px 22px; }
            .content { padding: 22px 22px 40px; }

            .mobile-nav {
                position: sticky;
                top: 61px;
                z-index: 35;
                display: flex;
                gap: 8px;
                overflow-x: auto;
                padding: 10px 22px;
                border-bottom: 1px solid var(--line);
                background: rgba(255,248,246,.96);
                backdrop-filter: blur(10px);
                scrollbar-width: none;
            }
            .mobile-nav::-webkit-scrollbar { display: none; }
            .mobile-nav a {
                flex: 0 0 auto;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 7px 14px;
                border: 1px solid var(--line);
                border-radius: 999px;
                color: #704657;
                background: #fff;
                font-size: 12px;
                font-weight: 700;
                text-decoration: none;
                transition: .2s;
            }
            .mobile-nav a svg { width: 14px; height: 14px; }
            .mobile-nav a.active {
                color: #fff;
                border-color: var(--red);
                background: linear-gradient(135deg, var(--red), var(--red-2));
                box-shadow: 0 4px 12px -4px var(--red);
            }
            .stats { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 600px) {
            .topbar { padding: 10px 16px; gap: 10px; }
            .topbar p, .date { display: none; }
            .gami-chip .gami-chip-label { display: none; }
            .gami-chip { padding: 7px 10px; }
            .profile-btn-info { display: none; }
            .profile-btn { padding: 4px; border-radius: 50%; }
            .profile-chevron { display: none; }
            .mobile-nav { padding: 8px 16px; top: 57px; }
            .content { padding: 18px 16px 36px; }
            .hero { padding: 22px 20px; }
            .hero h2 { font-size: 20px; }
            .stats { grid-template-columns: repeat(2, 1fr); gap: 10px; }
            .stat { padding: 14px; min-height: 80px; gap: 10px; }
            .stat strong { font-size: 20px; }
            .section-head { align-items: flex-start; flex-direction: column; }
        }

        @media (max-width: 760px) {
            .gami-card { grid-template-columns: 1fr; }
        }

        @yield('styles')
    </style>
</head>
<body>
    @php
        $currentUser = $user ?? Auth::user();
        $initial = strtoupper(mb_substr($currentUser->name ?? 'U', 0, 1));
        $gamiStats = $gami ?? app(\App\Services\GamificationService::class)->stats($currentUser);
    @endphp

    <!-- Mobile Drawer Backdrop -->
    <div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar" aria-label="Navigasi sidebar">
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="brand">
                <span class="brand-mark">あ</span>
                <span>JP <em>Learn</em></span>
            </a>
            <button type="button" class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="Tutup menu">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <span class="nav-label">Menu</span>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
            </span>
            Dashboard
        </a>

        <span class="nav-label">Belajar</span>
        <a href="{{ route('dashboard.materials') }}" class="nav-link {{ request()->routeIs('dashboard.materials') || request()->routeIs('material.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
            </span>
            Materi
        </a>
        <a href="{{ route('flashcards.index') }}" class="nav-link {{ request()->routeIs('flashcards.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                </svg>
            </span>
            Flashcard
        </a>
        <a href="{{ route('writing.index') }}" class="nav-link {{ request()->routeIs('writing.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 19l7-7 3 3-7 7-3-3z"></path>
                    <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path>
                    <path d="M2 2l7.586 7.586"></path>
                    <circle cx="11" cy="11" r="2"></circle>
                </svg>
            </span>
            Latihan Menulis
        </a>
        <a href="{{ route('conversation.index') }}" class="nav-link {{ request()->routeIs('conversation.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
            </span>
            Percakapan
        </a>
        <a href="{{ route('dictionary.index') }}" class="nav-link {{ request()->routeIs('dictionary.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    <polygon points="12 8 13.5 11 17 11.5 14.5 14 15 17.5 12 16 9 17.5 9.5 14 7 11.5 10.5 11 12 8"></polygon>
                </svg>
            </span>
            Kamus & Kosakata
        </a>
        <a href="{{ route('dashboard.quizzes') }}" class="nav-link {{ request()->routeIs('dashboard.quizzes') || request()->routeIs('quiz.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </span>
            Quiz & Latihan
        </a>
        <a href="{{ route('jlpt.index') }}" class="nav-link {{ request()->routeIs('jlpt.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </span>
            Simulasi JLPT N5
        </a>
        <a href="{{ route('dashboard.progress') }}" class="nav-link {{ request()->routeIs('dashboard.progress') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
            </span>
            Progres Belajar
        </a>

        <span class="nav-label">Navigasi Luar</span>
        <a href="{{ route('landing') }}" class="nav-link">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polygon points="12 8 8 12 12 16 12 8"></polygon>
                    <line x1="16" y1="12" x2="12" y2="12"></line>
                </svg>
            </span>
            Beranda Utama
        </a>

        <span class="nav-label">Akun</span>
        <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <span class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </span>
            Edit Profil
        </a>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="avatar">{{ $initial }}</div>
                <div class="user-card-info">
                    <strong>{{ $currentUser->name }}</strong>
                    <span class="user-card-role">Pemula (N5)</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout" type="submit">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="main">
        <header class="topbar">
            <div class="topbar-left">
                <button type="button" class="sidebar-toggle-btn" id="sidebarToggleBtn" aria-label="Buka menu navigasi">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <div class="topbar-title">
                    <h1>{{ $pageTitle ?? 'Dashboard Belajar' }}</h1>
                    <p>{{ $pageSubtitle ?? 'Lanjutkan perjalanan belajar bahasa Jepangmu.' }}</p>
                </div>
            </div>

            <div class="topbar-right">
                <div class="gami-chips">
                    <a href="{{ route('dashboard.progress') }}" class="gami-chip streak {{ $gamiStats['streak'] > 0 ? '' : 'off' }}" title="Streak belajar harian">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2s5 4.5 5 10a5 5 0 0 1-10 0c0-2 1-3.5 2-4.5.3 1.6 1.2 2.4 2 2.5C11 7 10 4 12 2z"/></svg>
                        {{ $gamiStats['streak'] }}<span class="gami-chip-label"> hari</span>
                    </a>
                    <a href="{{ route('dashboard.progress') }}" class="gami-chip xp" title="Level {{ $gamiStats['level'] }} · {{ $gamiStats['title'] }}">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        {{ number_format($gamiStats['xp']) }}<span class="gami-chip-label"> XP</span>
                    </a>
                </div>

                <span class="date">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    {{ now()->locale('id')->translatedFormat('l, j F Y') }}
                </span>

                <!-- Profile Dropdown in Navbar -->
                <div class="profile-menu" id="profileMenu">
                    <button type="button" class="profile-btn" id="profileBtn" aria-haspopup="true" aria-expanded="false">
                        <div class="avatar">{{ $initial }}</div>
                        <span class="profile-btn-info">
                            <span class="profile-btn-name">{{ $currentUser->name }}</span>
                            <span class="profile-btn-role">Pemula (N5)</span>
                        </span>
                        <span class="profile-chevron">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </span>
                    </button>

                    <div class="profile-dropdown" id="profileDropdown" role="menu">
                        <div class="dropdown-header">
                            <div class="avatar">{{ $initial }}</div>
                            <div class="dropdown-header-info">
                                <div class="dropdown-name">{{ $currentUser->name }}</div>
                                <div class="dropdown-email">{{ $currentUser->email }}</div>
                                <span class="dropdown-badge">Pemula (N5)</span>
                            </div>
                        </div>

                        <ul class="dropdown-list">
                            <li>
                                <a href="{{ route('profile.show') }}" class="dropdown-item" role="menuitem">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    Edit Profil
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard') }}" class="dropdown-item" role="menuitem">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                    </svg>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard.materials') }}" class="dropdown-item" role="menuitem">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                    Materi Belajar
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('flashcards.index') }}" class="dropdown-item" role="menuitem">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                    </svg>
                                    Flashcard Belajar
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('writing.index') }}" class="dropdown-item" role="menuitem">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 19l7-7 3 3-7 7-3-3z"></path>
                                        <path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path>
                                    </svg>
                                    Latihan Menulis Huruf
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('conversation.index') }}" class="dropdown-item" role="menuitem">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                    Percakapan Situasional
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dictionary.index') }}" class="dropdown-item" role="menuitem">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                    Kamus & Kosakata Favorit
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard.quizzes') }}" class="dropdown-item" role="menuitem">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                    </svg>
                                    Quiz & Latihan
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('jlpt.index') }}" class="dropdown-item" role="menuitem">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    Simulasi Ujian JLPT N5
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard.progress') }}" class="dropdown-item" role="menuitem">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="20" x2="18" y2="10"></line>
                                        <line x1="12" y1="20" x2="12" y2="4"></line>
                                        <line x1="6" y1="20" x2="6" y2="14"></line>
                                    </svg>
                                    Progres Belajar
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('landing') }}" class="dropdown-item" role="menuitem">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polygon points="12 8 8 12 12 16 12 8"></polygon>
                                        <line x1="16" y1="12" x2="12" y2="12"></line>
                                    </svg>
                                    Beranda Utama
                                </a>
                            </li>
                        </ul>

                        <div class="dropdown-divider"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-logout-btn" role="menuitem">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                Keluar dari Akun
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Mobile Quick Tabs -->
        <nav class="mobile-nav" aria-label="Navigasi belajar cepat">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                Dashboard
            </a>
            <a href="{{ route('dashboard.materials') }}" class="{{ request()->routeIs('dashboard.materials') || request()->routeIs('material.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                Materi
            </a>
            <a href="{{ route('flashcards.index') }}" class="{{ request()->routeIs('flashcards.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                Flashcard
            </a>
            <a href="{{ route('writing.index') }}" class="{{ request()->routeIs('writing.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l7-7 3 3-7 7-3-3z"></path><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path></svg>
                Menulis
            </a>
            <a href="{{ route('conversation.index') }}" class="{{ request()->routeIs('conversation.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                Percakapan
            </a>
            <a href="{{ route('dictionary.index') }}" class="{{ request()->routeIs('dictionary.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                Kamus
            </a>
            <a href="{{ route('dashboard.quizzes') }}" class="{{ request()->routeIs('dashboard.quizzes') || request()->routeIs('quiz.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                Quiz
            </a>
            <a href="{{ route('jlpt.index') }}" class="{{ request()->routeIs('jlpt.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                JLPT N5
            </a>
            <a href="{{ route('dashboard.progress') }}" class="{{ request()->routeIs('dashboard.progress') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                Progres
            </a>
        </nav>

        <div class="content">
            @if(session('success'))
            <div class="flash">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        // Profile Dropdown Toggle
        (() => {
            const profileMenu = document.getElementById('profileMenu');
            const profileBtn = document.getElementById('profileBtn');

            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const isOpen = profileMenu.classList.toggle('open');
                    profileBtn.setAttribute('aria-expanded', isOpen);
                });

                document.addEventListener('click', (e) => {
                    if (!profileMenu.contains(e.target)) {
                        profileMenu.classList.remove('open');
                        profileBtn.setAttribute('aria-expanded', 'false');
                    }
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        profileMenu.classList.remove('open');
                        profileBtn.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            // Mobile Sidebar Drawer Toggle
            const toggleBtn = document.getElementById('sidebarToggleBtn');
            const closeBtn = document.getElementById('sidebarCloseBtn');
            const backdrop = document.getElementById('sidebarBackdrop');

            const openSidebar = () => document.body.classList.add('sidebar-open');
            const closeSidebar = () => document.body.classList.remove('sidebar-open');

            if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);
        })();
    </script>
    @include('dashboard.partials.xp-toast')
    @yield('scripts')
</body>
</html>