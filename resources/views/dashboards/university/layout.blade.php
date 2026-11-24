<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page_title', 'لوحة تحكم الجامعة') | {{ $university->university_name ?? 'الجامعة' }} - EduYemen</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script>
        (function() {
            var userDbTheme = @json(auth()->check() ? (auth()->user()->theme_mode ?? 'light') : null);
            var localTheme = localStorage.getItem('eduyemen_theme');
            var targetTheme = (userDbTheme === 'dark' || (!userDbTheme && localTheme === 'dark')) ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', targetTheme);
        })();
    </script>

    <style>
        :root {
            --navy-dark: #0a1b2a;
            --navy: #102a43;
            --navy-light: #183b5c;
            --blue-accent: #1e6091;
            --teal: #2b8a85;
            --teal-hover: #226f6b;
            --teal-light: #e8f5f4;
            --teal-subtle: #d0ebe9;
            --gold: #e4b75b;
            --gold-dark: #b88a28;
            --gold-light: #fdf8eb;
            --ink: #1a2b3c;
            --ink-muted: #627d98;
            --surface: #ffffff;
            --surface-subtle: #f8fafc;
            --canvas: #f1f5f9;
            --border: #d9e2ec;
            --border-light: #e2e8f0;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 18px;
            --shadow-sm: 0 2px 6px rgba(16, 42, 67, 0.05);
            --shadow-md: 0 6px 18px rgba(16, 42, 67, 0.08);
            --shadow-lg: 0 14px 30px rgba(16, 42, 67, 0.12);
            --header-height: 70px;
            --sidebar-width: 270px;
            --sidebar-collapsed-width: 80px;
        }

        html[data-theme="dark"] {
            --navy-dark: #07131e;
            --navy: #0e2034;
            --navy-light: #15304e;
            --ink: #f8fafc;
            --ink-muted: #94a3b8;
            --surface: #111c33;
            --surface-subtle: #172440;
            --canvas: #09111e;
            --border: #1e2f50;
            --border-light: #243860;
            color-scheme: dark;
        }

        html[data-theme="dark"] body {
            background: var(--canvas) !important;
            color: var(--ink) !important;
        }

        html[data-theme="dark"] .dashboard-card,
        html[data-theme="dark"] .quick-card,
        html[data-theme="dark"] .table-card,
        html[data-theme="dark"] .tab-pane-card,
        html[data-theme="dark"] .bg-white {
            background: var(--surface) !important;
            border-color: var(--border) !important;
            color: var(--ink) !important;
        }

        html[data-theme="dark"] .table {
            color: var(--ink) !important;
            border-color: var(--border) !important;
        }

        html[data-theme="dark"] .table th {
            background: var(--surface-subtle) !important;
            color: var(--ink) !important;
            border-color: var(--border) !important;
        }

        html[data-theme="dark"] .table td {
            border-color: var(--border) !important;
            color: var(--ink) !important;
        }

        html[data-theme="dark"] .text-dark,
        html[data-theme="dark"] strong.text-dark {
            color: #f8fafc !important;
        }

        html[data-theme="dark"] .form-control,
        html[data-theme="dark"] .form-select,
        html[data-theme="dark"] input,
        html[data-theme="dark"] select,
        html[data-theme="dark"] textarea {
            background-color: #0d1629 !important;
            color: #f8fafc !important;
            border-color: #273554 !important;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            min-height: 100vh;
            background-color: var(--canvas);
            color: var(--ink);
            font-family: 'Almarai', 'IBM Plex Sans Arabic', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        button, input, select, textarea { 
            font-family: 'Almarai', 'IBM Plex Sans Arabic', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif; 
        }
        button, select { cursor: pointer; }
        a { color: inherit; text-decoration: none; }

        svg.ico {
            width: 18px;
            height: 18px;
            display: inline-block;
            flex-shrink: 0;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            vertical-align: middle;
        }

        /* ------------------------------------------
           TOP HEADER
        ------------------------------------------ */
        .top-header {
            position: fixed;
            z-index: 1000;
            inset: 0 0 auto;
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            color: #fff;
            background: linear-gradient(110deg, #102a43, #163d5a 65%, #1a4b68);
            border-bottom: 1px solid rgba(228,183,91,.58);
            box-shadow: 0 6px 24px rgba(12,35,55,.15);
        }
        .header-right-side {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .brand-container {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: #fff;
            text-decoration: none;
        }
        .brand-logo-img {
            width: 44px;
            height: 44px;
            object-fit: contain;
            border-radius: 12px;
            background: rgba(255,255,255,.08);
            padding: 3px;
            border: 1px solid rgba(228,183,91,.75);
        }
        .brand-copy { display: flex; flex-direction: column; line-height: 1.35; }
        .brand-name { font-size: 19px; font-weight: 800; letter-spacing: .15px; color: #fff; }

        /* ط´ط±ظٹط· ط§ظ„ط¨ط­ط« ظپظٹ ط§ظ„طھط±ظˆظٹط³ط© */
        .search-box { position: relative; width: 100%; max-width: 440px; margin: 0 20px; }
        .search-box input {
            width: 100%; height: 44px;
            padding: 0 46px 0 44px;
            border: 1px solid rgba(255,255,255,.19);
            border-radius: 14px;
            outline: none;
            color: #fff;
            background: rgba(255,255,255,.105);
            transition: .2s ease;
        }
        .search-box input::placeholder { color: #cfdae2; }
        .search-box input:focus { border-color: var(--gold); background: rgba(255,255,255,.16); box-shadow: 0 0 0 3px rgba(228,183,91,.15); }
        .search-box .search-icon { position: absolute; right: 17px; top: 50%; transform: translateY(-50%); color: var(--gold); }
        .search-clear {
            position: absolute; left: 10px; top: 50%; transform: translateY(-50%);
            border: 0; color: #d7e2e8; background: transparent; cursor: pointer; display: none;
        }

        /* ط¥ط¬ط±ط§ط،ط§طھ ط§ظ„طھط±ظˆظٹط³ط© */
        .header-actions { display: flex; align-items: center; gap: 12px; }
        .btn-preview-student {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff !important;
            padding: 8px 16px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 12.5px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .btn-preview-student:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
            filter: brightness(1.05);
        }
        .status-indicator-pill {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }
        .status-indicator-pill.status-open {
            background: rgba(16, 185, 129, 0.15);
            color: #6ee7b7;
            border: 1px solid #10b981;
        }
        .status-indicator-pill.status-closed {
            background: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
            border: 1px solid #ef4444;
        }
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
        }
        .btn-icon.notif-btn {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-icon.notif-btn:hover { background: rgba(255, 255, 255, 0.16); }
        .notif-badge {
            position: absolute;
            top: -4px;
            left: -4px;
            background: #ef4444;
            color: #fff;
            font-size: 10px;
            font-weight: 800;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--navy);
        }
        .btn-logout {
            display: inline-flex; align-items: center; gap: 9px;
            padding: 9px 15px; border: 1px solid rgba(255,255,255,.24); border-radius: 12px;
            color: #fff; background: rgba(255,255,255,.07); cursor: pointer; font-weight: 700; font-size: 13px;
            transition: .2s ease;
        }
        .btn-logout i { color: var(--gold); }
        .btn-logout:hover { background: rgba(255,255,255,.16); border-color: rgba(228,183,91,.75); }

        /* ------------------------------------------
           SIDEBAR (ط§ظ„ظ‚ط§ط¦ظ…ط© ط§ظ„ط¬ط§ظ†ط¨ظٹط© ط§ظ„ط£طµظ„ظٹط©)
        ------------------------------------------ */
        .sidebar {
            position: fixed; z-index: 990; top: var(--header-height); right: 0;
            width: var(--sidebar-width); height: calc(100vh - var(--header-height));
            padding: 16px 12px 20px; overflow-y: auto;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE and Edge */
            color: #e8f0f5; background: linear-gradient(180deg, #102a43, #12334d 68%, #102a43);
            border-left: 1px solid rgba(255,255,255,.08);
            box-shadow: -12px 0 35px rgba(16,42,67,.09);
            transition: transform .28s ease, width .28s ease;
        }
        .sidebar::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
        .sidebar.collapsed { transform: translateX(100%); }
        body.sidebar-collapsed .sidebar { transform: translateX(100%); }
        body.sidebar-collapsed .main-content-layout { margin-right: 0 !important; }

        .sidebar-user-card {
            padding: 6px 10px 16px;
            border-bottom: 1px solid rgba(255,255,255,.1);
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }
        .user-avatar-wrap {
            width: 44px; height: 44px; flex-shrink: 0;
            border-radius: 12px; overflow: hidden; background: #ffffff;
            border: 1.5px solid rgba(228,183,91,.6); padding: 2px;
        }
        .user-avatar-img { width: 100%; height: 100%; object-fit: contain; }
        .user-name {
            color: #ffffff; font-size: 14.5px; font-weight: 800; line-height: 1.4;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .sidebar-caption { padding: 0 14px 10px; color: #89a5b8; font-size: 11px; font-weight: 800; letter-spacing: 1px; }
        .sidebar-nav { list-style: none; display: grid; gap: 4px; padding: 0; margin: 0; }
        .sidebar-nav li a {
            display: flex; align-items: center; gap: 13px; min-height: 44px; padding: 9px 14px;
            border: 1px solid transparent; border-radius: 12px; color: #cfdae2;
            text-decoration: none; font-size: 13.5px; font-weight: 600; transition: .18s ease;
        }
        .sidebar-nav li a i { width: 20px; color: #d9ae56; text-align: center; font-size: 15px; }
        .sidebar-nav li a:hover { background: rgba(255,255,255,.075); color: #fff; transform: translateX(-2px); }
        .sidebar-nav li.active a {
            color: #fff; background: linear-gradient(90deg, rgba(43,138,133,.22), rgba(255,255,255,.08));
            border-color: rgba(228,183,91,.25); box-shadow: inset -3px 0 var(--gold);
        }

        .sidebar-toggle-btn {
            width: 38px; height: 38px; display: grid; place-items: center;
            border: 1px solid rgba(228,183,91,.48); border-radius: 11px;
            color: var(--gold); background: rgba(255,255,255,.08); cursor: pointer;
            box-shadow: 0 4px 12px rgba(7,25,39,.18);
            transition: all .2s ease;
        }
        .sidebar-toggle-btn:hover { color: #fff; background: rgba(255,255,255,.18); transform: translateY(-1px); }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 980;
            backdrop-filter: blur(2px);
        }

        /* ------------------------------------------
           MAIN CONTENT LAYOUT
        ------------------------------------------ */
        .main-content-layout {
            margin-top: var(--header-height);
            margin-right: var(--sidebar-width);
            min-height: calc(100vh - var(--header-height));
            display: flex;
            flex-direction: column;
            transition: margin-right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .content-body-wrap {
            flex: 1;
            padding: 24px;
            max-width: 1440px;
            width: 100%;
            margin: 0 auto;
        }

        /* ------------------------------------------
           CARD BOX & COMMON UI
        ------------------------------------------ */
        .card-box {
            background: #ffffff;
            border-radius: var(--radius-md);
            padding: 24px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            transition: all 0.2s ease;
        }
        .card-box:hover {
            box-shadow: var(--shadow-md);
        }
        .card-box-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border-light);
        }
        .header-icon-badge {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .header-icon-badge svg { width: 22px; height: 22px; }
        .card-box-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 2px;
        }
        .card-box-desc {
            font-size: 12.5px;
            color: var(--ink-muted);
        }

        .tab-header-banner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 22px;
            padding: 18px 24px;
            background: #ffffff;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }
        .btn-back-crumb {
            background: none;
            border: none;
            color: var(--teal);
            font-weight: 700;
            font-size: 12.5px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 4px;
            padding: 0;
            cursor: pointer;
        }
        .btn-back-crumb:hover { text-decoration: underline; }
        .tab-main-title {
            font-size: 20px;
            font-weight: 900;
            color: var(--navy);
            line-height: 1.3;
        }
        .tab-subtitle {
            font-size: 13px;
            color: var(--ink-muted);
            margin-top: 2px;
        }

        /* ------------------------------------------
           1. SUMMARY & INTRO SECTION (ط§ظ„طھطµظ…ظٹظ… ط§ظ„ط£طµظ„ظٹ)
        ------------------------------------------ */
        .dashboard-summary {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(0, 2fr);
            align-items: stretch;
            gap: 16px;
            margin-bottom: 24px;
        }

        .summary-welcome {
            min-width: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 20px 22px;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: linear-gradient(120deg, #ffffff, #edf5f7);
            box-shadow: 0 5px 16px rgba(16, 42, 67, .035);
        }
        .summary-welcome-content { min-width: 0; }
        .summary-welcome h2 {
            margin-bottom: 5px;
            color: var(--navy);
            font-size: clamp(16px, 1.4vw, 20px);
            font-weight: 800;
            line-height: 1.5;
        }
        .summary-welcome p {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }
        .summary-welcome-icon {
            width: 48px;
            height: 48px;
            flex: 0 0 auto;
            display: grid;
            place-items: center;
            border: 1px solid rgba(228, 183, 91, .35);
            border-radius: 15px;
            color: var(--gold);
            background: var(--navy);
            font-size: 21px;
        }

        #universityDashboardDateTime {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 14px;
            direction: rtl;
        }
        #universityDashboardDateTime span {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 5px 10px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 600;
            line-height: 1.6;
        }
        #universityDashboardDate {
            color: #2E497F;
            background: rgba(46, 73, 127, 0.07);
            border: 1px solid rgba(46, 73, 127, 0.12);
        }
        #universityDashboardTime {
            color: #8A6A25;
            background: rgba(185, 149, 67, 0.12);
            border: 1px solid rgba(185, 149, 67, 0.25);
            white-space: nowrap;
        }
        #universityDashboardDateTime i { color: #B99543; font-size: 14px; }

        .summary-stats {
            min-width: 0;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }
        .summary-stat {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border: 1px solid var(--line);
            border-radius: 16px;
            color: inherit;
            text-decoration: none;
            background: var(--surface);
            box-shadow: 0 5px 16px rgba(16, 42, 67, .035);
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }
        .summary-stat:hover {
            transform: translateY(-2px);
            border-color: rgba(43, 138, 133, .35);
            box-shadow: var(--shadow);
        }
        .summary-stat-icon {
            width: 42px;
            height: 42px;
            flex: 0 0 auto;
            display: grid;
            place-items: center;
            border-radius: 12px;
            font-size: 17px;
        }
        .stat-blue { color: #5275a2; background: #edf2f9; }
        .stat-gold { color: #b8812f; background: #fbf3e3; }
        .stat-teal { color: var(--teal); background: #e9f5f3; }
        .stat-purple { color: #7863a3; background: #f1edfa; }
        .summary-stat-info { min-width: 0; flex: 1; display: flex; align-items: center; }
        .summary-stat-title { color: var(--navy); font-size: 13.5px; font-weight: 800; }
        .summary-stat-arrow { flex: 0 0 auto; color: var(--teal); font-size: 13px; transition: transform .2s ease; }
        .summary-stat:hover .summary-stat-arrow { transform: translateX(-3px); }

        /* ط§ظ„ظ‚ط³ظ… ط§ظ„ط¹ظ„ظˆظٹ: ط§ظ„طھط±ط­ظٹط¨ ظˆظ‚ط³ظ… ط­ظˆظ„ ظˆط¨ط·ط§ظ‚ط§طھ ط§ظ„طھط­ظƒظ… */
        .overview-grid-layout {
            display: grid;
            gap: 20px;
            margin-bottom: 30px;
            align-items: stretch;
            transition: grid-template-columns 0.28s ease;
        }
        @media (min-width: 993px) {
            body:not(.sidebar-collapsed) .overview-grid-layout {
                grid-template-columns: minmax(0, 1.15fr) minmax(0, 1.05fr);
            }
            body.sidebar-collapsed .overview-grid-layout {
                grid-template-columns: 1fr 1fr;
            }
        }
        @media (max-width: 992px) {
            .overview-grid-layout { grid-template-columns: 1fr !important; }
        }

        .overview-intro-column {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .overview-cards-column {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* ط§ظ„ظ‚ط³ظ… ط§ظ„ط¹ظ„ظˆظٹ ط§ظ„ط¹ظ…ظˆط¯ظٹ: ط­ظˆظ„ + ط®ط¯ظ…ط§طھ ط§ظ„ظ†ط¸ط§ظ… (ط¯ط¹ظ… ظ„ظ„طھظˆط§ظپظ‚) */
        .upper-vertical-section {
            display: grid;
            gap: 24px;
            margin-bottom: 34px;
            align-items: stretch;
            transition: grid-template-columns 0.28s ease;
        }
        @media (min-width: 993px) {
            body:not(.sidebar-collapsed) .upper-vertical-section {
                grid-template-columns: calc(60vw - var(--sidebar-width) - 40px) 1fr;
            }
            body.sidebar-collapsed .upper-vertical-section {
                grid-template-columns: 1fr 1fr;
            }
        }
        @media (max-width: 992px) {
            .upper-vertical-section { grid-template-columns: 1fr !important; }
        }

        .platform-intro-block {
            position: relative; isolation: isolate; overflow: hidden;
            height: 100%; min-height: 280px; padding: clamp(24px, 3vw, 36px);
            display: flex; flex-direction: column; justify-content: space-between; gap: 20px;
            color: #fff; border-radius: 24px;
            background: radial-gradient(circle at 12% 10%, rgba(255,255,255,.13), transparent 30%), linear-gradient(115deg, #173f5f 0%, #14566f 56%, #247b7d 100%);
            box-shadow: 0 18px 38px rgba(23,63,95,.17);
        }
        .platform-intro-block::before, .platform-intro-block::after { content: ''; position: absolute; z-index: -1; border: 1px solid rgba(255,255,255,.11); border-radius: 50%; }
        .platform-intro-block::before { width: 290px; height: 290px; left: -70px; top: -170px; }
        .platform-intro-block::after { width: 190px; height: 190px; left: 125px; bottom: -140px; }
        
        .intro-header-wrapper { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; }
        .intro-copy { flex: 1; min-width: 0; }
        .intro-title { margin-bottom: 10px; font-size: clamp(19px, 2vw, 24px); line-height: 1.45; font-weight: 800; }
        .intro-description { color: #e1edf1; font-size: 15px; line-height: 1.85; }
        .c1 { border: none; border-top: 1px solid rgba(255,255,255,0.2); margin: 12px 0; }

        .intro-mark-link {
            flex: 0 0 auto; width: 76px; height: 76px; display: grid; place-items: center;
            border: 1px solid rgba(255,255,255,.32); border-radius: 20px;
            background: rgba(255,255,255,.12); box-shadow: inset 0 0 0 6px rgba(255,255,255,.04);
            text-decoration: none; overflow: hidden;
            transition: transform 0.25s ease, border-color 0.25s ease;
        }
        .intro-mark-link:hover { transform: scale(1.05); border-color: var(--gold); }
        .intro-mark-link img { width: 100%; height: 100%; object-fit: contain; padding: 6px; }
        .intro-meta { display: flex; align-items: center; flex-wrap: wrap; gap: 16px; margin-top: 15px; color: #e2eff1; font-size: 12px; }
        .intro-meta span { display: inline-flex; align-items: center; gap: 7px; }
        .intro-meta i { color: var(--gold); }

        .services-container { display: flex; flex-direction: column; justify-content: space-between; height: 100%; }
        .navigation-actions-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
            gap: 12px;
            flex: 1;
        }
        .nav-action-item {
            min-height: 75px; display: flex; align-items: center; gap: 14px; padding: 16px 18px;
            border: 1px solid var(--line); border-radius: 16px; color: inherit; text-decoration: none;
            background: var(--surface); box-shadow: 0 5px 16px rgba(16,42,67,.035); cursor: pointer;
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }
        .nav-action-item:hover { transform: translateY(-3px); border-color: rgba(43,138,133,.3); box-shadow: var(--shadow); }
        .nav-action-icon { width: 44px; height: 44px; display: grid; place-items: center; flex: 0 0 auto; border-radius: 12px; color: var(--teal); background: #e9f5f3; font-size: 18px; }
        .nav-action-item:nth-child(2) .nav-action-icon, .nav-action-item:nth-child(5) .nav-action-icon { color: #b8812f; background: #fbf3e3; }
        .nav-action-item:nth-child(3) .nav-action-icon { color: #5275a2; background: #edf2f9; }
        .nav-action-item:nth-child(4) .nav-action-icon { color: #7863a3; background: #f1edfa; }
        .nav-action-item:nth-child(6) .nav-action-icon, .nav-action-item.nav-action-preview .nav-action-icon { color: #0d9488; background: #e6f7f5; }
        .nav-action-item.nav-action-preview:hover .nav-action-icon { background: var(--teal); color: #fff; }
        .nav-action-content { flex: 1; min-width: 0; }
        .nav-action-title { color: var(--navy); font-size: 14px; font-weight: 800; }
        .nav-action-arrow { width: 28px; height: 28px; display: grid; place-items: center; flex: 0 0 auto; border-radius: 9px; color: var(--teal); background: #eff7f6; font-size: 11px; transition: .2s ease; }
        .nav-action-item:hover .nav-action-arrow { color: #fff; background: var(--teal); }
        .no-results { display: none; grid-column: 1/-1; padding: 20px; border: 1px dashed var(--line); border-radius: 14px; color: var(--muted); text-align: center; }

        /* ------------------------------------------
           2. UNIVERSITY PROFILE & MOBILE TWO COLUMNS
        ------------------------------------------ */
        .branding-media-grid {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 20px;
            align-items: center;
        }
        .media-upload-card {
            background: var(--surface-subtle);
            border: 1px dashed var(--border);
            border-radius: var(--radius-md);
            padding: 18px;
            display: flex;
            gap: 14px;
            align-items: center;
        }
        .media-preview-box {
            position: relative;
            border-radius: var(--radius-sm);
            overflow: hidden;
            background: #ffffff;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .logo-box { width: 80px; height: 80px; }
        .logo-image-element { width: 100%; height: 100%; object-fit: contain; }
        .campus-box { width: 140px; height: 80px; }
        .campus-image-element { width: 100%; height: 100%; object-fit: cover; }
        .campus-empty-tag {
            position: absolute;
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 4px;
        }
        .media-info-box { flex: 1; }
        .media-label { font-size: 13.5px; font-weight: 800; color: var(--navy); margin-bottom: 4px; }
        .media-hint { font-size: 11.5px; color: var(--ink-muted); margin-bottom: 10px; }
        .upload-btn-label { cursor: pointer; }

        /* ط§ظ„ط´ط¨ظƒط© ط§ظ„ظ…طھط¬ط§ظˆط¨ط©: ط­ظ‚ظ„ظٹظ† ط¨ط³ط·ط± ظˆط§ط­ط¯ ط­طھظ‰ ط¹ظ„ظ‰ ط§ظ„ظ‡ظˆط§طھظپ */
        .mobile-two-col-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        .col-span-full-mobile {
            grid-column: span 2;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .form-label {
            font-size: 13px;
            font-weight: 700;
            color: var(--navy);
        }
        .form-label.required::after {
            content: ' *';
            color: #ef4444;
        }
        .form-control {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            background: #ffffff;
            color: var(--ink);
            font-size: 13.5px;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--blue-accent);
            box-shadow: 0 0 0 3px rgba(30, 96, 145, 0.12);
        }
        .form-actions-bar {
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* ------------------------------------------
           3. COLLEGES & SPECIALIZATIONS (NO SUMMARY CONTAINER)
        ------------------------------------------ */
        .filter-selector-bar { padding: 18px 24px; }
        .filter-controls-grid {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        .filter-select-group {
            flex: 1;
            min-width: 220px;
        }
        .filter-label {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .filter-actions-group {
            display: flex;
            align-items: center;
            gap: 8px;
            align-self: flex-end;
        }

        /* ظƒط§ط±طھ ط§ظ„ظ‡ظٹط±ظˆ ط§ظ„ط£ظƒط§ط¯ظٹظ…ظٹ */
        .spec-hero-card {
            background: linear-gradient(135deg, #0f2c4a 0%, #173b5c 100%);
            border-radius: var(--radius-md);
            color: #ffffff;
            padding: 24px;
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
        }
        .spec-hero-main {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .spec-hero-badges {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            flex-wrap: wrap;
        }
        .badge-college-tag {
            background: rgba(228, 183, 91, 0.25);
            color: var(--gold);
            font-weight: 700;
            font-size: 12px;
            padding: 3px 10px;
            border-radius: 20px;
            border: 1px solid rgba(228, 183, 91, 0.4);
        }
        .badge-status-tag {
            background: rgba(16, 185, 129, 0.2);
            color: #6ee7b7;
            font-weight: 700;
            font-size: 12px;
            padding: 3px 10px;
            border-radius: 20px;
            border: 1px solid rgba(16, 185, 129, 0.4);
        }
        .badge-lang-tag {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            font-weight: 700;
            font-size: 12px;
            padding: 3px 10px;
            border-radius: 20px;
        }
        .spec-hero-title {
            font-size: 22px;
            font-weight: 900;
            margin-bottom: 6px;
        }
        .spec-hero-desc {
            font-size: 13px;
            color: #cbd5e1;
            max-width: 750px;
            line-height: 1.5;
        }
        .spec-hero-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .spec-hero-metrics {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
            background: rgba(0, 0, 0, 0.25);
            padding: 14px 18px;
            border-radius: var(--radius-sm);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .hero-metric-item {
            text-align: center;
            border-left: 1px solid rgba(255, 255, 255, 0.1);
        }
        .hero-metric-item:last-child { border-left: none; }
        .hero-metric-item .metric-label {
            font-size: 11.5px;
            color: #94a3b8;
            display: block;
            margin-bottom: 2px;
        }
        .hero-metric-item .metric-val {
            font-size: 16px;
            font-weight: 800;
            color: #ffffff;
        }

        /* ط´ط±ظٹط· ط§ظ„طھط¨ظˆظٹط¨ط§طھ ط§ظ„ظپط±ط¹ظٹط© Segmented Control */
        .subtabs-navigation-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            padding: 8px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            margin-bottom: 20px;
            box-shadow: var(--shadow-sm);
            overflow-x: auto;
        }
        .subtab-nav-btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            background: transparent;
            border: none;
            border-radius: var(--radius-sm);
            color: var(--ink);
            font-size: 13.5px;
            font-weight: 700;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .subtab-nav-btn:hover {
            background: var(--surface-subtle);
            color: var(--navy);
        }
        .subtab-nav-btn.active {
            background: var(--navy);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(16, 42, 67, 0.12);
        }
        .subtab-pill-badge {
            background: rgba(255, 255, 255, 0.25);
            padding: 2px 7px;
            border-radius: 12px;
            font-size: 11px;
        }
        .subtab-nav-btn:not(.active) .subtab-pill-badge {
            background: var(--surface-subtle);
            color: var(--navy);
            border: 1px solid var(--border);
        }

        .subtab-pane { display: none; }
        .subtab-pane.active { display: block; }

        /* ط¹ظ†ط§طµط± ط§ظ„ظˆط«ط§ط¦ظ‚ ظˆط§ظ„ط®ط·ط· ط§ظ„ط¯ظٹظ†ط§ظ…ظٹظƒظٹط© */
        .doc-item-row, .plan-item-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            background: var(--surface-subtle);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-sm);
            margin-bottom: 8px;
            transition: all 0.2s ease;
        }
        .doc-item-row:hover, .plan-item-row:hover {
            background: #ffffff;
            border-color: var(--border);
            box-shadow: var(--shadow-sm);
        }
        .doc-icon-badge, .plan-icon-badge {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-sm);
            background: #ffffff;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .doc-info-body, .plan-info-body { flex: 1; }
        .doc-name, .plan-name { font-size: 13.5px; font-weight: 700; color: var(--navy); display: block; }
        .doc-description, .plan-meta { font-size: 12px; color: var(--ink-muted); }

        /* ------------------------------------------
           MODERN TABLES
        ------------------------------------------ */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        .modern-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .modern-table th {
            background: var(--surface-subtle);
            color: var(--navy);
            font-weight: 800;
            padding: 12px 14px;
            text-align: right;
            border-bottom: 2px solid var(--border);
            white-space: nowrap;
        }
        .modern-table td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--border-light);
            vertical-align: middle;
            color: var(--ink);
        }
        .modern-table tbody tr:hover {
            background: #f8fafc;
        }

        /* ط£ط²ط±ط§ط± ط§ظ„ظپظ„طھط±ط© */
        .status-filter-pills {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .filter-pill-btn {
            background: var(--surface-subtle);
            border: 1px solid var(--border);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12.5px;
            font-weight: 700;
            color: var(--ink);
            transition: all 0.2s ease;
        }
        .filter-pill-btn.active {
            background: var(--navy);
            color: #fff;
            border-color: var(--navy);
        }
        .requests-filter-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }
        .table-search-box {
            position: relative;
            min-width: 260px;
        }
        .table-search-box svg {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
        .table-search-box input {
            padding-right: 34px;
        }

        /* ------------------------------------------
           BUTTONS & PILLS
        ------------------------------------------ */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            font-weight: 700;
            border: 1px solid transparent;
            transition: all 0.2s ease;
            text-align: center;
        }
        .btn-sm { padding: 6px 12px; font-size: 12.5px; }
        .btn-xs { padding: 4px 8px; font-size: 11.5px; }
        .btn-md { padding: 10px 20px; font-size: 14px; }
        .btn-lg { padding: 12px 24px; font-size: 15px; }

        .btn-navy {
            background-color: var(--navy);
            color: #ffffff !important;
            border-color: var(--navy);
        }
        .btn-navy:hover {
            background-color: var(--navy-light);
            box-shadow: 0 4px 10px rgba(16, 42, 67, 0.2);
        }

        .btn-gold {
            background-color: var(--gold);
            color: var(--navy-dark) !important;
            border-color: var(--gold);
        }
        .btn-gold:hover {
            background-color: var(--gold-dark);
            color: #ffffff !important;
        }

        .btn-teal {
            background-color: var(--teal);
            color: #ffffff !important;
            border-color: var(--teal);
        }
        .btn-teal:hover {
            background-color: var(--teal-hover);
        }

        .btn-outline-navy {
            background: transparent;
            border-color: var(--navy);
            color: var(--navy) !important;
        }
        .btn-outline-navy:hover {
            background: var(--navy);
            color: #ffffff !important;
        }

        .btn-outline-danger {
            background: transparent;
            border-color: #ef4444;
            color: #ef4444 !important;
        }
        .btn-outline-danger:hover {
            background: #ef4444;
            color: #ffffff !important;
        }

        .btn-secondary-light {
            background: var(--surface-subtle);
            border: 1px solid var(--border);
            color: var(--navy);
        }
        .btn-secondary-light:hover {
            background: #ffffff;
            border-color: var(--navy);
        }

        .btn-white {
            background: #ffffff;
            color: var(--navy) !important;
        }

        .btn-danger-soft {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
        }
        .btn-danger-soft:hover {
            background: #ef4444;
            color: #ffffff;
        }

        .status-pill {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 700;
        }
        .pill-success, .status-pill-success { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
        .pill-danger, .status-pill-danger { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .pill-warning, .status-pill-warning { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
        .pill-gold { background: var(--gold-light); color: var(--gold-dark); border: 1px solid var(--gold); }
        .pill-secondary { background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; }

        /* ظƒط±ظˆطھ ظˆط³ط§ط¦ظ„ ط§ظ„ط¯ظپط¹ */
        .payment-cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
        .payment-method-card {
            background: var(--surface-subtle);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 18px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .payment-method-card.pm-active {
            border-color: var(--teal);
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(43, 138, 133, 0.08);
        }
        .pm-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        .pm-type-badge {
            background: var(--navy);
            color: #fff;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }
        .pm-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 6px;
        }
        .pm-account-number {
            font-size: 17px;
            font-weight: 800;
            color: var(--teal);
            letter-spacing: 1px;
            margin-bottom: 6px;
            font-family: monospace;
        }
        .pm-account-holder { font-size: 12px; color: var(--ink-muted); margin-bottom: 4px; }
        .pm-instructions { font-size: 11.5px; color: var(--ink); background: #f8fafc; padding: 6px 10px; border-radius: 6px; }
        .pm-footer {
            margin-top: 14px;
            padding-top: 10px;
            border-top: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .pm-status-label { font-size: 11px; font-weight: 700; }

        /* ظƒط±ظˆطھ ط§ظ„ط¥ط¹ظ„ط§ظ†ط§طھ */
        .announcements-list-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        .announcement-item-card {
            background: var(--surface-subtle);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-sm);
            padding: 16px;
            border-right: 4px solid var(--teal);
        }
        .ann-item-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .ann-category-badge {
            background: var(--teal-light);
            color: var(--teal-hover);
            font-weight: 700;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 12px;
        }
        .ann-date { font-size: 11px; color: var(--ink-muted); }
        .ann-title { font-size: 14.5px; font-weight: 800; color: var(--navy); margin-bottom: 6px; }
        .ann-content { font-size: 12.5px; color: var(--ink); line-height: 1.6; }

        /* ظƒط±ظˆطھ ط§ظ„طھظ‚ط§ط±ظٹط± */
        .reports-metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }
        .report-metric-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 18px;
            box-shadow: var(--shadow-sm);
        }
        .metric-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            color: var(--ink-muted);
            font-weight: 700;
            margin-bottom: 8px;
        }
        .metric-value {
            font-size: 26px;
            font-weight: 900;
            color: var(--navy);
            margin-bottom: 4px;
        }
        .metric-desc { font-size: 11.5px; color: var(--ink-muted); }

        /* ط§ظ„ط¥ط´ط¹ط§ط±ط§طھ */
        .notifications-feed-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .notif-feed-item {
            background: #ffffff;
            border: 1px solid var(--border-light);
            border-radius: var(--radius-sm);
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .notif-feed-item:hover {
            border-color: var(--teal);
            box-shadow: var(--shadow-sm);
        }
        .notif-feed-item.notif-unread {
            border-right: 4px solid var(--gold);
            background: #fffdf5;
        }
        .notif-icon-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .notif-content-body { flex: 1; }
        .notif-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 4px;
        }
        .notif-title { font-size: 13.5px; color: var(--navy); }
        .notif-time { font-size: 11px; color: var(--ink-muted); }
        .notif-summary { font-size: 12px; color: var(--ink); }

        /* ط§ظ„طھط­ظ‚ظ‚ ظ…ظ† ط§ظ„ط¥ط­ط§ظ„ط© */
        .referral-verify-card {
            background: linear-gradient(135deg, #ffffff 0%, #fdfbf7 100%);
            border: 2px solid var(--gold);
        }
        .verify-input-group {
            display: flex;
            gap: 12px;
            align-items: center;
            max-width: 600px;
            margin: 10px 0;
        }
        .code-input-wrapper {
            position: relative;
            flex: 1;
        }
        .code-input-wrapper .code-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gold-dark);
        }
        .code-input {
            padding-right: 40px;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 1.5px;
            border-color: var(--gold);
        }
        .verification-result-box {
            margin-top: 16px;
            padding: 14px 18px;
            border-radius: var(--radius-sm);
            font-size: 13px;
        }
        .verification-result-box.success {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }
        .verification-result-box.error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }
        .result-success-content, .result-error-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .result-icon { font-size: 24px; }
        .referral-code-pill {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 800;
            color: var(--navy);
        }

        /* ط³ظˆظٹطھط´ ط§ظ„طھط¨ط¯ظٹظ„ Toggle Switch */
        .switch-toggle {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }
        .switch-toggle.switch-lg { width: 52px; height: 28px; }
        .switch-toggle input { opacity: 0; width: 0; height: 0; }
        .slider-round {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background-color: #cbd5e1;
            transition: .3s;
            border-radius: 34px;
        }
        .slider-round:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .3s;
            border-radius: 50%;
        }
        .switch-toggle.switch-lg .slider-round:before {
            height: 22px;
            width: 22px;
        }
        .switch-toggle input:checked + .slider-round {
            background-color: var(--teal);
        }
        .switch-toggle input:checked + .slider-round:before {
            transform: translateX(20px);
        }
        .switch-toggle.switch-lg input:checked + .slider-round:before {
            transform: translateX(24px);
        }
        .settings-toggle-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px;
            background: var(--surface-subtle);
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-light);
        }
        .toggle-title { font-size: 14px; color: var(--navy); display: block; margin-bottom: 2px; }
        .toggle-desc { font-size: 12px; color: var(--ink-muted); margin: 0; }

        /* ------------------------------------------
           MODALS
        ------------------------------------------ */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(10, 27, 42, 0.65);
            backdrop-filter: blur(4px);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .modal-container {
            background: #ffffff;
            border-radius: var(--radius-md);
            width: 100%;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
            overflow: hidden;
            animation: modalPop 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        @keyframes modalPop {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .modal-md { max-width: 540px; }
        .modal-lg { max-width: 800px; }
        .modal-xl { max-width: 1060px; width: 95vw; }
        .modal-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--surface-subtle);
        }
        .modal-title { font-size: 16px; font-weight: 800; color: var(--navy); }
        .modal-subtitle { font-size: 12px; color: var(--ink-muted); }
        .btn-close-modal {
            background: none;
            border: none;
            font-size: 24px;
            color: var(--ink-muted);
            cursor: pointer;
            line-height: 1;
        }
        .modal-body {
            padding: 20px 24px;
            overflow-y: auto;
            flex: 1;
        }
        .modal-footer {
            padding: 14px 24px;
            border-top: 1px solid var(--border-light);
            background: var(--surface-subtle);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }
        .review-modal-footer-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            gap: 16px;
            flex-wrap: wrap;
        }
        .review-footer-actions-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .review-footer-tools-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-inline-start: auto;
        }

        /* طھظپط§طµظٹظ„ ط§ظ„ط·ظ„ط¨ ط¯ط§ط®ظ„ ط§ظ„ظ†ط§ظپط°ط© ظˆظ…ط­ط·ط© ط§ظ„طھط¯ظ‚ظٹظ‚ ط§ظ„ط£ظƒط§ط¯ظٹظ…ظٹط© */
        .review-station-wrap {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .review-top-card {
            background: linear-gradient(135deg, #072644 0%, #0b3d6b 100%);
            color: #ffffff;
            border-radius: var(--radius-sm);
            padding: 18px 22px;
            box-shadow: 0 4px 15px rgba(7, 38, 68, 0.12);
        }
        .btn-ministry-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #ffffff !important;
            font-weight: 800;
            font-size: 13.5px;
            padding: 8px 18px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.38);
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid rgba(255, 255, 255, 0.35);
        }
        .btn-ministry-badge:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(217, 119, 6, 0.5);
            color: #ffffff !important;
        }
        .review-grid-layout {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 16px;
        }
        @media (max-width: 991px) {
            .review-grid-layout {
                grid-template-columns: 1fr;
            }
        }
        .review-panel-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: var(--radius-sm);
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }
        .review-panel-header {
            padding: 11px 16px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13.5px;
            font-weight: 700;
            color: var(--navy);
        }
        .review-panel-body {
            padding: 16px;
        }
        .academic-highlight-box {
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            border-radius: 8px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }
        .academic-score-pill {
            text-align: center;
            background: #ffffff;
            padding: 6px 14px;
            border-radius: 8px;
            border: 1px solid #bbf7d0;
            box-shadow: 0 2px 5px rgba(16, 185, 129, 0.08);
        }
        .clean-data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 13px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            background: #ffffff;
            margin-bottom: 4px;
        }
        .clean-data-table tr td {
            padding: 9px 12px;
            border-bottom: 1px solid #edf2f7;
            vertical-align: middle;
        }
        .clean-data-table tr:last-child td {
            border-bottom: none;
        }
        .clean-data-table .label-col {
            background: #f1f5f9;
            color: #334155;
            font-weight: 700;
            font-size: 12px;
            width: 24%;
            white-space: nowrap;
            border-left: 1px solid #e2e8f0;
            border-right: 3px solid #2563eb;
            letter-spacing: -0.2px;
            user-select: none;
        }
        .clean-data-table .label-col i {
            color: #1e3a8a;
            font-size: 13.5px;
            margin-inline-end: 4px;
        }
        .clean-data-table .val-col {
            background: #ffffff;
            color: #0f172a;
            font-weight: 600;
            font-size: 13px;
        }
        .clean-data-table tr:hover .label-col {
            background: #e2e8f0;
            color: #1e293b;
        }
        .clean-data-table tr:hover .val-col {
            background: #f8fafc;
        }
        .docs-review-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .doc-review-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            transition: all 0.18s ease;
        }
        .doc-review-item:hover {
            background: #ffffff;
            border-color: #cbd5e1;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .doc-icon-badge {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: #e0f2fe;
            color: #0369a1;
            display: grid;
            place-items: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        /* ------------------------------------------
           FOOTER
        ------------------------------------------ */
        .dashboard-footer {
            background: #ffffff;
            border-top: 1px solid var(--border);
            padding: 18px 24px;
            margin-top: 40px;
        }
        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1440px;
            margin: 0 auto;
            font-size: 12.5px;
            color: var(--ink-muted);
            flex-wrap: wrap;
            gap: 12px;
        }
        .footer-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .system-status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #059669;
            font-weight: 700;
        }
        .status-pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            animation: pulse 1.8s infinite;
        }

        /* ------------------------------------------
           TOAST ALERTS
        ------------------------------------------ */
        .toast-container-fixed {
            position: fixed;
            bottom: 24px;
            left: 24px;
            z-index: 3000;
            display: flex;
            flex-direction: column;
            gap: 8px;
            pointer-events: none;
        }
        .toast-pill-alert {
            pointer-events: auto;
            background: var(--navy);
            color: #ffffff;
            padding: 12px 20px;
            border-radius: 30px;
            box-shadow: var(--shadow-lg);
            font-size: 13.5px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .toast-pill-alert.toast-show {
            opacity: 1;
            transform: translateY(0);
        }
        .toast-pill-alert.toast-success { background: #065f46; border: 1px solid #10b981; }
        .toast-pill-alert.toast-error { background: #991b1b; border: 1px solid #ef4444; }
        .toast-pill-alert.toast-warning { background: #92400e; border: 1px solid #f59e0b; }
        .toast-pill-alert.toast-info { background: var(--navy-dark); border: 1px solid var(--gold); color: var(--gold); }

        .tab-pane-content {
            display: none;
            width: 100%;
        }
        .tab-pane-content.active {
            display: block;
        }

        .fade-in-tab {
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ------------------------------------------
           RESPONSIVE RULES (MOBILE FIRST PRESERVATION)
        ------------------------------------------ */
        @media (max-width: 1200px) {
            .dashboard-summary { grid-template-columns: 1fr; }
            .reports-metrics-grid { grid-template-columns: repeat(2, 1fr); }
            .payment-cards-grid { grid-template-columns: repeat(2, 1fr); }
            .spec-hero-metrics { grid-template-columns: repeat(3, 1fr); }
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(100%);
                width: 280px;
            }
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            .sidebar-overlay.active {
                display: block;
            }
            .main-content-layout {
                margin-right: 0 !important;
            }
            .upper-vertical-section {
                grid-template-columns: 1fr !important;
            }
            .branding-media-grid {
                grid-template-columns: 1fr;
            }
            .announcements-list-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 680px) {
            .top-header { padding: 0 14px; }
            .search-box { display: none; }
            .univ-name-truncated { display: none; }
            .preview-btn-label { display: none; }
            .logout-label { display: none; }
            .content-body-wrap { padding: 14px; }
            .dashboard-summary { gap: 12px; margin-bottom: 20px; }
            .summary-welcome { padding: 16px; }
            .summary-stat { gap: 8px; padding: 11px 9px; }
            .navigation-actions-list { grid-template-columns: 1fr; gap: 11px; }
            .nav-action-item { min-height: 70px; padding: 12px 14px; gap: 12px; }
            .nav-action-icon { width: 40px; height: 40px; border-radius: 10px; font-size: 16px; }
            
            /* ط§ظ„ط­ظپط§ط¸ ط§ظ„طµط§ط±ظ… ط¹ظ„ظ‰ ط¹ط±ط¶ ط­ظ‚ظ„ظٹظ† ط¨ط³ط·ط± ظˆط§ط­ط¯ ط¹ظ„ظ‰ ط§ظ„ظ‡ط§طھظپ */
            .mobile-two-col-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 10px !important;
            }
            .mobile-two-col-grid .form-group label {
                font-size: 11.5px !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .mobile-two-col-grid .form-control {
                padding: 7px 10px !important;
                font-size: 12px !important;
            }
            .col-span-full-mobile {
                grid-column: span 2 !important;
            }

            .stats-overview-grid { grid-template-columns: 1fr; }
            .payment-cards-grid { grid-template-columns: 1fr; }
            .reports-metrics-grid { grid-template-columns: 1fr; }
            .intro-features-list { grid-template-columns: 1fr; }
            .quick-services-grid { grid-template-columns: 1fr; }
            .spec-hero-metrics { grid-template-columns: repeat(2, 1fr); }
            .details-grid { grid-template-columns: 1fr; }
            .docs-preview-grid { grid-template-columns: 1fr; }

            .welcome-content { padding: 20px; }
            .welcome-title { font-size: 20px; }
            .digital-time-display { font-size: 28px; }
            .card-box { padding: 16px; }
        }

        /* -------------------------------------------------------------
           طھط­ط³ظٹظ† ط§ط³طھط¬ط§ط¨ط© ظ†ط§ظپط°ط© ظ…ط±ط§ط¬ط¹ط© ط·ظ„ط¨ ط§ظ„طھط³ط¬ظٹظ„ ط¹ظ„ظ‰ ط§ظ„ظ‡ظˆط§طھظپ ظˆط§ظ„ط´ط§ط´ط§طھ ط§ظ„طµط؛ظٹط±ط©
           (طھطھط­ظˆظ„ ط¥ظ„ظ‰ ط´ط§ط´ط© ظƒط§ظ…ظ„ط© ظ…طھط¬ط§ظˆط¨ط© ط¨ط¯ظ„ط§ظ‹ ظ…ظ† ظ†ط§ظپط°ط© ظ…ظ†ط¨ط«ظ‚ط© ظ…ط­طµظˆط±ط©)
        ------------------------------------------------------------- */
        @media (max-width: 768px) {
            #requestDetailsModal.modal-overlay {
                padding: 0 !important;
                background: #071728 !important;
                align-items: stretch !important;
                justify-content: stretch !important;
                z-index: 9999 !important;
            }
            #requestDetailsModal .modal-container {
                max-width: 100vw !important;
                width: 100vw !important;
                height: 100vh !important;
                max-height: 100vh !important;
                border-radius: 0 !important;
                border: none !important;
                display: flex !important;
                flex-direction: column !important;
                animation: slideUpModal 0.22s cubic-bezier(0.16, 1, 0.3, 1) !important;
            }
            @keyframes slideUpModal {
                from { transform: translateY(100%); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
            #requestDetailsModal .modal-header {
                padding: 12px 14px !important;
                position: sticky !important;
                top: 0 !important;
                z-index: 50 !important;
                background: #ffffff !important;
                border-bottom: 2px solid #e2e8f0 !important;
                box-shadow: 0 2px 6px rgba(0,0,0,0.04) !important;
            }
            #requestDetailsModal .modal-title {
                font-size: 14.5px !important;
            }
            #requestDetailsModal .btn-close-modal {
                width: 36px;
                height: 36px;
                border-radius: 50%;
                background: #f1f5f9;
                display: grid;
                place-items: center;
                font-size: 20px;
                color: #0f172a;
            }
            #requestDetailsModal .modal-body {
                padding: 12px 12px 24px 12px !important;
                flex: 1 1 auto !important;
                overflow-y: auto !important;
                -webkit-overflow-scrolling: touch !important;
            }
            #requestDetailsModal .modal-footer {
                position: sticky !important;
                bottom: 0 !important;
                z-index: 50 !important;
                padding: 10px 14px !important;
                background: #ffffff !important;
                border-top: 1px solid #e2e8f0 !important;
                box-shadow: 0 -4px 14px rgba(0,0,0,0.08) !important;
            }
            .review-modal-footer-wrap {
                display: flex !important;
                flex-direction: column !important;
                gap: 8px !important;
                width: 100% !important;
            }
            .review-footer-actions-group {
                display: grid !important;
                grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)) !important;
                gap: 8px !important;
                width: 100% !important;
            }
            .review-footer-actions-group .btn {
                width: 100% !important;
                padding: 8px 6px !important;
                font-size: 11.5px !important;
                font-weight: 700 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                white-space: nowrap !important;
            }
            .review-footer-tools-group {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                gap: 8px !important;
                width: 100% !important;
                margin-inline-start: 0 !important;
            }
            .review-footer-tools-group .btn {
                width: 100% !important;
                padding: 7px 10px !important;
                font-size: 12px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            .review-grid-layout {
                grid-template-columns: 1fr !important;
                gap: 12px !important;
            }
            .review-top-card {
                padding: 14px !important;
            }
            .academic-highlight-box {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 10px !important;
            }
            .academic-score-pill {
                width: 100% !important;
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                padding: 8px 12px !important;
            }
        }

        @media (max-width: 600px) {
            /* طھظ†ط¸ظٹظ… ط¹ط±ط¶ ط®ظ„ط§ظٹط§ ط§ظ„ط¬ط¯ط§ظˆظ„ ط¹ظ„ظ‰ ط§ظ„ظ‡ظˆط§طھظپ ط§ظ„ط°ظƒظٹط© ط¨ط­ظٹط« طھط¸ظ‡ط± ظƒظ„ ظ‚ظٹظ…ط© ظ…ط¹ ط¹ظ†ظˆط§ظ†ظ‡ط§ ط¨ظˆط¶ظˆط­ طھط§ظ… ط¯ظˆظ† طھط¯ط§ط®ظ„ */
            .clean-data-table {
                border-radius: 6px;
            }
            .clean-data-table tr {
                display: flex;
                flex-wrap: wrap;
                border-bottom: 1px solid #e2e8f0;
            }
            .clean-data-table tr:last-child {
                border-bottom: none;
            }
            .clean-data-table tr td {
                padding: 8px 10px;
                border-bottom: none;
            }
            .clean-data-table .label-col {
                width: 40% !important;
                flex: 0 0 40% !important;
                border-right: 3px solid #2563eb !important;
                border-left: 1px solid #e2e8f0 !important;
                font-size: 11.5px !important;
                white-space: normal !important;
                display: flex;
                align-items: center;
            }
            .clean-data-table .val-col {
                width: 60% !important;
                flex: 0 0 60% !important;
                font-size: 12.5px !important;
                word-break: break-word;
                display: flex;
                align-items: center;
                flex-wrap: wrap;
            }
            .clean-data-table .val-col[colspan="3"] {
                width: 60% !important;
                flex: 0 0 60% !important;
            }
        }

        /* ط£ظ„ظˆط§ظ† ظ…ط³ط§ط¹ط¯ط© */
        .bg-blue-subtle { background: #e0f2fe; }
        .text-blue { color: #0369a1; }
        .bg-teal-subtle { background: var(--teal-light); }
        .text-teal { color: var(--teal); }
        .bg-gold-subtle { background: var(--gold-light); }
        .text-gold { color: var(--gold-dark); }
        .bg-purple-subtle { background: #f3e8ff; }
        .text-purple { color: #7e22ce; }
        .bg-green-subtle { background: #ecfdf5; }
        .text-green { color: #059669; }
        .bg-orange-subtle { background: #fff7ed; }
        .text-orange { color: #ea580c; }
        .bg-danger-subtle { background: #fef2f2; }
        .text-danger { color: #dc2626; }
        .text-navy { color: var(--navy); }
        .font-semibold { font-weight: 600; }
        .font-bold { font-weight: 700; }
        .d-none { display: none !important; }
        .cursor-pointer { cursor: pointer; }
    </style>
    @stack('styles')
    @yield('styles')
</head>
<body class="@yield('body_class')">

    <!-- 1. الشريط العلوي المشترك للوحة الجامعة -->
    @include('dashboards.university.partials.header')

    <!-- 2. القائمة الجانبية للتنقل -->
    @include('dashboards.university.partials.sidebar')

    <!-- 3. المحتوى الرئيسي -->
    <main class="main-content-layout">
        <div class="content-body-wrap">
            @yield('content')
        </div>

        <!-- 4. تذييل لوحة التحكم (Footer) -->
        @include('dashboards.university.partials.footer')
    </main>

    <!-- 5. جميع النوافذ المنبثقة (Modals) -->
    @include('dashboards.university.modals.modals')

    <!-- 6. سكربتات التفاعل الحي -->
    @include('dashboards.university.scripts')

    @stack('scripts')
    @yield('scripts')
</body>
</html>
