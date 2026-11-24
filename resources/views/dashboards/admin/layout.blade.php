<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page_title', 'لوحة التحكم') | مدير النظام - EduYemen</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Tajawal:wght@400;500;700;800;900&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script>
        (function() {
            var userDbTheme = @json(auth()->check() ? (auth()->user()->theme_mode ?? 'light') : null);
            var localTheme = localStorage.getItem('eduyemen_theme');
            var targetTheme = 'light';
            if (userDbTheme === 'dark' || (!userDbTheme && localTheme === 'dark')) {
                targetTheme = 'dark';
            }
            document.documentElement.setAttribute('data-theme', targetTheme);
        })();
    </script>

    <style>
        :root {
            /* الهوية البصرية الرسمية لمنصة EduYemen المتطابقة مع صفحات الطالب والجامعة */
            --primary:         #0b3d6b;          /* الكحلي الأكاديمي الأساسي */
            --primary-dark:    #072b4f;          /* الكحلي الملكي العميق */
            --primary-light:   #1565a8;          /* الكحلي المضيء */
            --primary-subtle:  #eaf2f9;          /* خلفيات الكحلي الخفيفة */
            --accent:          #c59b27;          /* الذهبي الفاخر المعتمد */
            --accent-light:    #f0c842;          /* الذهبي المشع */
            --accent-subtle:   #fdf8eb;          /* خلفيات ذهبية ناعمة */
            --accent-crimson:  #C91521;          /* القرمزي للتنبيهات */
            --teal:            #2b8a85;          /* التيل الإداري */
            --teal-light:      #e8f5f4;
            --success:         #1a8754;
            --success-light:   #dcfce7;
            --warning:         #d97706;
            --warning-light:   #fef3c7;
            --danger:          #dc2626;
            --danger-light:    #fee2e2;
            --info:            #0ea5e9;
            --info-light:      #e0f2fe;

            --surface:         #ffffff;
            --surface-alt:     #f8fafc;
            --canvas:          #f4f6fb;          /* لون خلفية صفحات EduYemen الرسمية */
            --border:          #e2e8f0;
            --border-light:    #edf2f7;
            --ink:             #1e293b;
            --ink-muted:       #64748b;
            --ink-light:       #94a3b8;

            --sidebar-w:       270px;
            --header-h:        70px;
            --radius-sm:       8px;
            --radius-md:       14px;
            --radius-lg:       20px;
            --radius-xl:       28px;
            --shadow-sm:       0 2px 8px rgba(11, 61, 107, 0.05);
            --shadow-md:       0 6px 20px rgba(11, 61, 107, 0.08);
            --shadow-lg:       0 12px 36px rgba(11, 61, 107, 0.12);
        }

        /* ========================================================
           الوضع الليلي الشامل (Dark Mode) في لوحة تحكم الإدارة
           ======================================================== */
        html[data-theme="dark"] {
            --canvas:         #09111e;
            --surface:        #111c33;
            --surface-alt:    #172440;
            --border:         #1e2f50;
            --border-light:   #243860;
            --ink:            #f8fafc;
            --ink-muted:      #94a3b8;
            --ink-light:      #64748b;
            --primary-subtle: rgba(11, 61, 107, 0.45);
            --accent-subtle:  rgba(197, 155, 39, 0.2);
            color-scheme: dark;
        }

        html[data-theme="dark"] body {
            background: var(--canvas) !important;
            color: var(--ink) !important;
        }

        html[data-theme="dark"] .admin-card,
        html[data-theme="dark"] .quick-link-card,
        html[data-theme="dark"] .admin-table-container,
        html[data-theme="dark"] .modal-content,
        html[data-theme="dark"] .admin-dropdown,
        html[data-theme="dark"] .stat-card,
        html[data-theme="dark"] .chart-card,
        html[data-theme="dark"] .filter-bar,
        html[data-theme="dark"] .bg-white {
            background: var(--surface) !important;
            border-color: var(--border) !important;
            color: var(--ink) !important;
        }

        html[data-theme="dark"] .admin-header {
            background: var(--surface) !important;
            border-color: var(--border) !important;
        }

        html[data-theme="dark"] .header-breadcrumb,
        html[data-theme="dark"] .header-date-badge {
            color: var(--ink) !important;
            background: var(--surface-alt) !important;
            border-color: var(--border) !important;
        }

        html[data-theme="dark"] .header-search input {
            background: #0d1629 !important;
            color: #f8fafc !important;
            border-color: var(--border) !important;
        }

        html[data-theme="dark"] .admin-table th {
            background: var(--surface-alt) !important;
            color: var(--ink) !important;
            border-color: var(--border) !important;
        }

        html[data-theme="dark"] .admin-table td {
            border-color: var(--border) !important;
            color: var(--ink) !important;
            background: transparent !important;
        }

        html[data-theme="dark"] .admin-table tr:hover td {
            background: rgba(255, 255, 255, 0.05) !important;
        }

        html[data-theme="dark"] .admin-form-input,
        html[data-theme="dark"] .admin-form-select,
        html[data-theme="dark"] input:not([type="checkbox"]):not([type="radio"]),
        html[data-theme="dark"] select,
        html[data-theme="dark"] textarea {
            background: #0d1629 !important;
            color: #f8fafc !important;
            border-color: #273554 !important;
        }

        html[data-theme="dark"] .text-dark,
        html[data-theme="dark"] strong.text-dark,
        html[data-theme="dark"] h1, html[data-theme="dark"] h2,
        html[data-theme="dark"] h3, html[data-theme="dark"] h4,
        html[data-theme="dark"] h5, html[data-theme="dark"] h6 {
            color: #f8fafc !important;
        }

        html[data-theme="dark"] .text-muted {
            color: #94a3b8 !important;
        }

        html[data-theme="dark"] .text-secondary {
            color: #cbd5e1 !important;
        }

        html[data-theme="dark"] .badge-light,
        html[data-theme="dark"] .badge.bg-light {
            background: #182442 !important;
            color: #cbd5e1 !important;
            border-color: #273554 !important;
        }

        .header-theme-toggle {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--surface-alt);
            color: var(--ink);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .header-theme-toggle:hover {
            border-color: var(--accent);
            color: var(--accent);
            transform: scale(1.05);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Cairo', 'Tajawal', system-ui, -apple-system, sans-serif;
            font-size: 14.5px;
            font-weight: 600;
            line-height: 1.6;
            color: #0f172a;
            background: var(--canvas);
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        a { text-decoration: none; color: inherit; }
        button, select { cursor: pointer; font: inherit; }
        input, textarea, select { font: inherit; }
        img { max-width: 100%; display: block; }

        /* ========== SIDEBAR ========== */
        .admin-sidebar {
            position: fixed;
            top: 0; right: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: linear-gradient(180deg, #072b4f 0%, #0b3d6b 55%, #082847 100%);
            display: flex;
            flex-direction: column;
            z-index: 900;
            transition: transform .3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: none !important; /* Firefox: إخفاء السكرول بار بالكامل */
            -ms-overflow-style: none !important; /* IE / Edge */
            box-shadow: -4px 0 25px rgba(7, 43, 79, 0.25);
            border-left: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* Chrome, Safari, Opera: إلغاء ظهور السكرول بار تماماً مع الحفاظ على التمرير المرن */
        .admin-sidebar::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }

        .sidebar-brand {
            padding: 16px 18px 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
            position: relative;
        }

        .sidebar-brand::after {
            content: '';
            position: absolute;
            bottom: -1px;
            right: 20px;
            left: 20px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(197, 155, 39, 0.6), transparent);
        }

        .sidebar-brand-logo {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.28), 0 0 0 2px rgba(197, 155, 39, 0.45);
            flex-shrink: 0;
            overflow: hidden;
            transition: transform .25s ease;
        }

        .sidebar-brand-logo:hover {
            transform: scale(1.05);
        }

        .sidebar-brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .sidebar-brand-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sidebar-brand-title {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            margin: 0;
            line-height: 1.1;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }

        .brand-name-gold {
            color: #f0c842;
            font-weight: 800;
            text-shadow: 0 2px 10px rgba(240, 200, 66, 0.4);
        }

        .brand-name-white {
            color: #ffffff;
            font-weight: 800;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.25);
        }

        .sidebar-brand-text span {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
            display: inline-block;
        }

        .sidebar-admin-info {
            padding: 12px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.03);
        }

        .admin-avatar {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, #ffffff 0%, #e2e8f0 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: var(--primary);
            font-weight: 800;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.85);
        }

        .admin-info-text strong {
            display: block;
            font-size: 13.5px;
            font-weight: 700;
            color: #fff;
            line-height: 1.3;
        }

        .admin-info-text small {
            font-size: 11px;
            color: var(--accent-light);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .sidebar-section-label {
            padding: 16px 20px 8px;
            font-size: 10.5px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.45);
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0 12px;
        }

        .sidebar-nav li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9.5px 14px;
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.75);
            font-size: 13.5px;
            font-weight: 600;
            transition: all .22s ease;
            position: relative;
            margin-bottom: 2px;
        }

        .sidebar-nav li a i {
            width: 24px;
            text-align: center;
            font-size: 16px;
            flex-shrink: 0;
            color: #f0c842; /* لون ذهبي فاخر ومميز لكافة الأيقونات */
            filter: drop-shadow(0 1px 4px rgba(240, 200, 66, 0.4));
            transition: transform .2s ease, filter .2s ease, color .2s ease;
        }

        .sidebar-nav li a:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            transform: translateX(-3px);
        }

        .sidebar-nav li a:hover i {
            transform: scale(1.18);
            color: #ffd859;
            filter: drop-shadow(0 2px 8px rgba(240, 200, 66, 0.7));
        }

        .sidebar-nav li.active a {
            background: linear-gradient(135deg, var(--accent) 0%, #aa841e 100%);
            color: #ffffff;
            font-weight: 800;
            box-shadow: 0 6px 18px rgba(197, 155, 39, 0.35);
        }

        .sidebar-nav li.active a i {
            color: #ffffff;
            filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.3));
        }

        .sidebar-nav li a .nav-badge {
            margin-right: auto;
            background: #ef4444;
            color: #fff;
            font-size: 11px;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 20px;
            min-width: 20px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(239, 68, 68, 0.4);
        }

        .sidebar-nav li a .nav-badge.gold {
            background: #ffffff;
            color: var(--primary-dark);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .sidebar-bottom {
            margin-top: auto;
            padding: 12px 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.15);
            flex-shrink: 0;
        }

        .sidebar-bottom a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 12px;
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.65);
            font-size: 13px;
            font-weight: 600;
            transition: all .2s;
        }

        .sidebar-bottom a:hover { background: rgba(255, 255, 255, 0.08); color: #fff; }
        .sidebar-bottom a i { width: 20px; text-align: center; }

        /* ========== HEADER ========== */
        .admin-header {
            position: fixed;
            top: 0;
            left: 0;
            right: var(--sidebar-w);
            height: var(--header-h);
            background: linear-gradient(90deg, #072b4f 0%, #0a3560 50%, #0c3e72 100%);
            border-bottom: 2px solid rgba(197, 155, 39, 0.45);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            gap: 18px;
            z-index: 800;
            box-shadow: 0 4px 20px rgba(7, 43, 79, 0.22);
            color: #ffffff;
        }

        .header-left-part {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-shrink: 0;
        }

        .header-toggle {
            display: none;
            width: 40px; height: 40px;
            border: 1px solid rgba(255, 255, 255, 0.22);
            background: rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-sm);
            align-items: center;
            justify-content: center;
            font-size: 17px;
            color: #ffffff;
            transition: all .2s;
        }

        .header-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--accent);
            color: #f0c842;
        }

        .header-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13.5px;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
        }

        .header-breadcrumb strong {
            color: #ffffff;
            font-weight: 800;
        }

        .header-breadcrumb i {
            color: #f0c842;
        }

        .header-center-part {
            flex: 1;
            display: flex;
            justify-content: center;
            padding: 0 20px;
        }

        .header-search {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 8px 18px;
            font-size: 13.5px;
            color: #ffffff;
            transition: all .25s ease;
            cursor: text;
            width: 100%;
            max-width: 440px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.15);
        }

        .header-search:focus-within {
            background: #ffffff;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(197, 155, 39, 0.25), 0 4px 12px rgba(0,0,0,0.12);
        }

        .header-search input {
            border: none;
            background: none;
            outline: none;
            color: #ffffff;
            width: 100%;
            font-size: 13px;
        }

        .header-search:focus-within input {
            color: var(--ink);
        }

        .header-search:focus-within input::placeholder {
            color: var(--ink-muted);
        }

        .header-search input::placeholder {
            color: rgba(255, 255, 255, 0.65);
        }

        .header-search i {
            color: rgba(255, 255, 255, 0.7);
            transition: color .2s;
        }

        .header-search:focus-within i {
            color: var(--primary);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .header-date-badge {
            font-size: 12.5px;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.22);
            padding: 6px 14px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }

        .header-date-badge i {
            color: #f0c842;
        }
            font-weight: 700;
            border: 1px solid rgba(11, 61, 107, 0.1);
        }

        .header-icon-btn {
            width: 36px; height: 36px;
            border: 1px solid var(--border);
            background: var(--surface);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ink-muted);
            font-size: 15px;
            transition: all .2s;
            position: relative;
        }

        .header-icon-btn:hover { border-color: var(--admin-blue); color: var(--admin-blue); }

        .notif-dot {
            position: absolute;
            top: 6px; left: 6px;
            width: 8px; height: 8px;
            background: var(--admin-red);
            border-radius: 50%;
            border: 2px solid var(--surface);
        }

        /* ========== MAIN CONTENT ========== */
        .admin-main {
            margin-right: var(--sidebar-w);
            margin-top: var(--header-h);
            padding: 28px 32px;
            min-height: calc(100vh - var(--header-h));
            background: var(--canvas);
            box-sizing: border-box;
            transition: all .25s ease;
        }

        /* ========== PAGE HEADER ========== */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 16px;
            flex-wrap: wrap;
        }

        .page-header-title h1 {
            font-size: 22px;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 4px;
            letter-spacing: -0.3px;
        }

        .page-header-title p {
            font-size: 13.5px;
            color: var(--ink-muted);
            font-weight: 500;
        }

        .page-header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* ========== CARDS ========== */
        .card {
            background: var(--surface);
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            transition: box-shadow .25s ease, border-color .25s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
            border-color: rgba(11, 61, 107, 0.15);
        }

        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            background: #ffffff;
        }

        .card-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            color: var(--accent);
            font-size: 16px;
        }

        .card-body { padding: 24px; }
        .card-body-np { padding: 0; }

        /* ========== TABLE ========== */
        .admin-table-wrap { overflow-x: auto; }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }

        .admin-table thead th {
            padding: 13px 18px;
            background: var(--surface-alt);
            color: var(--primary-dark);
            font-weight: 800;
            font-size: 12.5px;
            text-align: right;
            white-space: nowrap;
            border-bottom: 1px solid var(--border);
        }

        .admin-table tbody tr {
            border-bottom: 1px solid var(--border-light);
            transition: background .15s ease;
        }

        .admin-table tbody tr:last-child { border-bottom: none; }
        .admin-table tbody tr:hover { background: #f8fafc; }

        .admin-table tbody td {
            padding: 14px 18px;
            color: var(--ink);
            vertical-align: middle;
        }

        /* ========== BADGES ========== */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 11px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-green   { background: #dcfce7; color: #15803d; border: 1px solid rgba(22, 101, 52, 0.15); }
        .badge-red     { background: #fee2e2; color: #b91c1c; border: 1px solid rgba(185, 28, 28, 0.15); }
        .badge-yellow  { background: #fef3c7; color: #b45309; border: 1px solid rgba(180, 83, 9, 0.15); }
        .badge-blue    { background: #e0f2fe; color: #0369a1; border: 1px solid rgba(3, 105, 161, 0.15); }
        .badge-primary { background: var(--primary-subtle); color: var(--primary); border: 1px solid rgba(11, 61, 107, 0.15); }
        .badge-accent  { background: var(--accent-subtle); color: #8a6a12; border: 1px solid rgba(197, 155, 39, 0.25); }
        .badge-gray    { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }
        .badge-purple  { background: #ede9fe; color: #6d28d9; border: 1px solid rgba(109, 40, 217, 0.15); }
        .badge-teal    { background: #ccfbf1; color: #0f766e; border: 1px solid rgba(15, 118, 110, 0.15); }

        /* ========== BUTTONS ========== */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 700;
            border: none;
            transition: all .2s ease;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(11, 61, 107, 0.25);
        }
        .btn-primary:hover {
            box-shadow: 0 6px 18px rgba(11, 61, 107, 0.35);
            transform: translateY(-1px);
        }

        .btn-accent {
            background: linear-gradient(135deg, var(--accent) 0%, #aa841e 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(197, 155, 39, 0.3);
        }
        .btn-accent:hover {
            box-shadow: 0 6px 18px rgba(197, 155, 39, 0.4);
            transform: translateY(-1px);
        }

        .btn-success { background: var(--success); color: #fff; }
        .btn-success:hover { background: #157347; transform: translateY(-1px); }

        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: #b91c1c; transform: translateY(-1px); }

        .btn-warning { background: var(--warning); color: #fff; }
        .btn-warning:hover { background: #b45309; transform: translateY(-1px); }

        .btn-ghost {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--ink-muted);
        }

        .btn-ghost:hover {
            border-color: var(--primary-light);
            color: var(--primary);
            background: var(--primary-subtle);
        }

        .btn-sm { padding: 6px 12px; font-size: 12.5px; gap: 6px; border-radius: 8px; }
        .btn-xs { padding: 4px 9px; font-size: 11.5px; gap: 5px; border-radius: 6px; }
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--surface);
            border-radius: var(--radius-md);
            padding: 20px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: flex-start;
            gap: 14px;
            transition: box-shadow .2s, transform .2s;
        }

        .stat-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 46px; height: 46px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .stat-icon.blue   { background: #eff6ff; color: var(--admin-blue); }
        .stat-icon.green  { background: #f0fdf4; color: var(--admin-green); }
        .stat-icon.orange { background: #fff7ed; color: var(--admin-gold); }
        .stat-icon.purple { background: #faf5ff; color: var(--admin-purple); }
        .stat-icon.red    { background: #fef2f2; color: var(--admin-red); }
        .stat-icon.teal   { background: #ecfeff; color: var(--admin-teal); }

        .stat-info {}

        .stat-value {
            font-size: 26px;
            font-weight: 800;
            color: var(--ink);
            line-height: 1.1;
        }

        .stat-label {
            font-size: 12px;
            color: var(--ink-muted);
            font-weight: 500;
            margin-top: 2px;
        }

        .stat-change {
            font-size: 11px;
            font-weight: 600;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .stat-change.up   { color: var(--admin-green); }
        .stat-change.down { color: var(--admin-red); }

        /* ========== CARDS ========== */
        .card {
            background: var(--surface);
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .card-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--ink);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-title i { color: var(--admin-blue); }

        .card-body { padding: 20px; }
        .card-body-np { padding: 0; }

        /* ========== GRID LAYOUT ========== */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }
        .grid-7-5 { display: grid; grid-template-columns: 7fr 5fr; gap: 20px; }
        .grid-5-7 { display: grid; grid-template-columns: 5fr 7fr; gap: 20px; }

        /* ========== TABLE ========== */
        .admin-table-wrap { overflow-x: auto; }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }

        .admin-table thead th {
            padding: 11px 14px;
            background: var(--surface-alt);
            color: var(--ink-muted);
            font-weight: 700;
            font-size: 12px;
            text-align: right;
            white-space: nowrap;
            border-bottom: 1px solid var(--border);
        }

        .admin-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background .15s;
        }

        .admin-table tbody tr:last-child { border-bottom: none; }
        .admin-table tbody tr:hover { background: var(--surface-alt); }

        .admin-table tbody td {
            padding: 12px 14px;
            color: var(--ink);
            vertical-align: middle;
        }

        /* ========== BADGES ========== */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-green   { background: #dcfce7; color: #15803d; }
        .badge-red     { background: #fee2e2; color: #dc2626; }
        .badge-yellow  { background: #fef9c3; color: #a16207; }
        .badge-blue    { background: #dbeafe; color: #1d4ed8; }
        .badge-gray    { background: #f1f5f9; color: #64748b; }
        .badge-purple  { background: #ede9fe; color: #7c3aed; }
        .badge-orange  { background: #ffedd5; color: #c2410c; }
        .badge-teal    { background: #cffafe; color: #0e7490; }

        /* ========== BUTTONS ========== */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 600;
            border: none;
            transition: all .2s;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-primary { background: var(--admin-blue); color: #fff; }
        .btn-primary:hover { background: #1740a8; box-shadow: 0 4px 10px rgba(26,86,219,.3); }

        .btn-success { background: var(--admin-green); color: #fff; }
        .btn-success:hover { background: #16a34a; }

        .btn-danger { background: var(--admin-red); color: #fff; }
        .btn-danger:hover { background: #dc2626; }

        .btn-warning { background: var(--admin-gold); color: #fff; }
        .btn-warning:hover { background: #d97706; }

        .btn-ghost {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--ink-muted);
        }

        .btn-ghost:hover { border-color: var(--admin-blue); color: var(--admin-blue); background: #eff6ff; }

        .btn-sm { padding: 5px 10px; font-size: 12px; gap: 5px; }
        .btn-xs { padding: 3px 8px; font-size: 11px; gap: 4px; }
        .btn-icon { padding: 7px; }

        /* ========== FILTERS BAR ========== */
        .filters-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .filter-select, .filter-input {
            padding: 7px 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            background: var(--surface);
            color: var(--ink);
            font-size: 13px;
            outline: none;
            transition: border-color .2s;
        }

        .filter-select:focus, .filter-input:focus { border-color: var(--admin-blue); }

        .filter-search {
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 6px 12px;
            background: var(--surface);
        }

        .filter-search input {
            border: none;
            outline: none;
            color: var(--ink);
            font-size: 13px;
            background: none;
            width: 180px;
        }

        .filter-search i { color: var(--ink-light); font-size: 13px; }

        /* ========== PAGINATION ========== */
        .pagination-wrap {
            padding: 14px 20px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            font-size: 13px;
            color: var(--ink-muted);
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 4px;
            list-style: none;
        }

        .pagination a, .pagination span {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            border: 1px solid var(--border);
            color: var(--ink-muted);
            transition: all .2s;
        }

        .pagination a:hover { border-color: var(--admin-blue); color: var(--admin-blue); }
        .pagination .active span { background: var(--admin-blue); color: #fff; border-color: var(--admin-blue); }

        /* ========== MODAL ========== */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(13,27,42,.55);
            backdrop-filter: blur(3px);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            pointer-events: none;
            transition: opacity .25s;
        }

        .modal-overlay.open {
            opacity: 1;
            pointer-events: all;
        }

        .modal {
            background: var(--surface);
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 620px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 60px rgba(0,0,0,.2);
            transform: scale(.95) translateY(10px);
            transition: transform .25s;
        }

        .modal-overlay.open .modal { transform: scale(1) translateY(0); }

        .modal-lg { max-width: 800px; }
        .modal-sm { max-width: 440px; }

        .modal-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-shrink: 0;
        }

        .modal-header h3 {
            font-size: 16px;
            font-weight: 700;
            color: var(--ink);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-header h3 i { color: var(--admin-blue); }

        .modal-close {
            width: 32px; height: 32px;
            border: none;
            background: var(--surface-alt);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: var(--ink-muted);
            transition: all .2s;
        }

        .modal-close:hover { background: #fee2e2; color: var(--admin-red); }

        .modal-body {
            padding: 24px;
            overflow-y: auto;
            flex: 1;
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            flex-shrink: 0;
        }

        /* ========== FORM ========== */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }
        .form-full { grid-column: 1 / -1; }

        .form-group { display: flex; flex-direction: column; gap: 5px; }

        .form-label {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--ink);
        }

        .form-label .req { color: var(--admin-red); }

        .form-control {
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            background: var(--surface);
            color: var(--ink);
            font-size: 13px;
            outline: none;
            transition: all .2s;
            width: 100%;
        }

        .form-control:focus { border-color: var(--admin-blue); box-shadow: 0 0 0 3px rgba(26,86,219,.1); }

        textarea.form-control { resize: vertical; min-height: 80px; }

        .form-hint {
            font-size: 11px;
            color: var(--ink-muted);
        }

        .form-section-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--ink);
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ========== ALERT / TOAST ========== */
        .toast-container {
            position: fixed;
            bottom: 24px;
            left: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 18px;
            background: var(--surface);
            border-radius: var(--radius-md);
            box-shadow: 0 8px 24px rgba(0,0,0,.14);
            border-right: 4px solid;
            font-size: 13.5px;
            min-width: 280px;
            max-width: 360px;
            animation: slideIn .3s ease;
        }

        .toast.success { border-color: var(--admin-green); }
        .toast.error   { border-color: var(--admin-red); }
        .toast.info    { border-color: var(--admin-blue); }

        .toast-icon { font-size: 16px; flex-shrink: 0; margin-top: 1px; }
        .toast.success .toast-icon { color: var(--admin-green); }
        .toast.error   .toast-icon { color: var(--admin-red); }
        .toast.info    .toast-icon { color: var(--admin-blue); }

        .toast-title { font-weight: 700; color: var(--ink); line-height: 1.3; }
        .toast-msg   { font-size: 12.5px; color: var(--ink-muted); margin-top: 2px; }

        @keyframes slideIn {
            from { transform: translateX(-20px); opacity: 0; }
            to   { transform: translateX(0);     opacity: 1; }
        }

        /* ========== EMPTY STATE ========== */
        .empty-state {
            text-align: center;
            padding: 50px 24px;
            color: var(--ink-muted);
        }

        .empty-state i {
            font-size: 48px;
            color: var(--ink-light);
            margin-bottom: 16px;
        }

        .empty-state h4 { font-size: 16px; font-weight: 700; color: var(--ink); margin-bottom: 6px; }
        .empty-state p  { font-size: 13px; }

        /* ========== SIDEBAR OVERLAY (mobile) ========== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 850;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1024px) {
            :root { --sidebar-w: 240px; }
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(100%);
            }

            .admin-sidebar.open { transform: translateX(0); }

            .admin-header {
                right: 0 !important;
                width: 100% !important;
                left: 0 !important;
            }

            .admin-main {
                margin-right: 0 !important;
                width: 100% !important;
                max-width: 100vw !important;
                padding: 16px 12px !important;
                box-sizing: border-box !important;
            }

            .header-toggle { display: flex; }

            .sidebar-overlay { display: block; opacity: 0; pointer-events: none; transition: opacity .3s; }
            .sidebar-overlay.open { opacity: 1; pointer-events: all; }

            .grid-2, .grid-3, .grid-7-5, .grid-5-7 { grid-template-columns: 1fr; }
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
            .form-grid, .form-grid-3 { grid-template-columns: 1fr; }
            .header-search { display: none; }

            .col-hide-mobile { display: none !important; }
            .col-show-mobile { display: table-cell !important; }
            th.col-show-mobile, td.col-show-mobile { display: table-cell !important; }
        }

        @media (min-width: 769px) {
            .col-show-mobile { display: none !important; }
            th.col-show-mobile, td.col-show-mobile { display: none !important; }
        }

        @media (max-width: 480px) {
            .stat-grid { grid-template-columns: 1fr; }
        }

        /* ========== UTILITY ========== */
        .text-muted  { color: var(--ink-muted); }
        .text-sm     { font-size: 12px; }
        .text-xs     { font-size: 11px; }
        .fw-bold     { font-weight: 700; }
        .fw-800      { font-weight: 800; }
        .mb-0        { margin-bottom: 0; }
        .mb-1        { margin-bottom: 6px; }
        .mb-2        { margin-bottom: 12px; }
        .mb-3        { margin-bottom: 18px; }
        .mb-4        { margin-bottom: 24px; }
        .mt-auto     { margin-top: auto; }
        .d-flex      { display: flex; }
        .align-center { align-items: center; }
        .gap-1       { gap: 6px; }
        .gap-2       { gap: 12px; }
        .gap-3       { gap: 18px; }
        .w-100       { width: 100%; }
        .truncate    { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        /* Spinner */
        .spinner {
            width: 18px; height: 18px;
            border: 2px solid rgba(255,255,255,.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .6s linear infinite;
            display: inline-block;
        }

        @keyframes spin { to { transform: rotate(360deg); } }
    </style>

    @yield('extra_styles')
    @yield('styles')
    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-logo">
            <img src="{{ asset('logo.png') }}" 
                 onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';" 
                 alt="EduYemen Logo">
        </div>
        <div class="sidebar-brand-text">
            <h2 dir="ltr" class="sidebar-brand-title" style="direction:ltr;unicode-bidi:isolate;font-family:'Plus Jakarta Sans',system-ui,sans-serif;">
                <span class="brand-name-white">Edu</span><span class="brand-name-gold">Yemen</span>
            </h2>
            <span>منصة الجامعات اليمنية</span>
        </div>
    </div>

    <div class="sidebar-admin-info">
        <div class="admin-avatar">
            {{ mb_substr(Auth::user()->username ?? 'A', 0, 1) }}
        </div>
        <div class="admin-info-text">
            <strong>{{ Auth::user()->username ?? 'المدير' }}</strong>
            <small>مدير النظام</small>
        </div>
    </div>

    <!-- قائمة التنقل الجانبية الموحدة الواضحة دون عناوين أقسام مشتتة -->
    <ul class="sidebar-nav" style="margin-top:6px;">
        <!-- 1. الرئيسية (لوحة التحكم سابقاً) -->
        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" title="الانتقال إلى الرئيسية">
                <i class="fa-solid fa-house-chimney"></i>
                <span>الرئيسية</span>
            </a>
        </li>

        <!-- 2. المستخدمون والصلاحيات -->
        <li class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
            <a href="{{ route('admin.users') }}" title="إدارة المستخدمين والصلاحيات">
                <i class="fa-solid fa-users-gear"></i>
                <span>المستخدمون والصلاحيات</span>
            </a>
        </li>

        <!-- 3. كآفة الجامعات (الجامعات المسجلة سابقاً) -->
        <li class="{{ request()->routeIs('admin.universities') ? 'active' : '' }}">
            <a href="{{ route('admin.universities') }}" title="دليل وإدارة كآفة الجامعات">
                <i class="fa-solid fa-building-columns"></i>
                <span>كآفة الجامعات</span>
            </a>
        </li>

        <!-- 4. رقابة وحوكمة المحتوى -->
        <li class="{{ request()->routeIs('admin.moderation*') ? 'active' : '' }}">
            <a href="{{ route('admin.moderation') }}" title="الرقابة المركزية وحجب المحتوى المخالف">
                <i class="fa-solid fa-shield-halved"></i>
                <span>رقابة وحوكمة المحتوى</span>
                @php $activeQuarantineCount = \App\Models\ContentModeration::activeQuarantine()->count(); @endphp
                @if($activeQuarantineCount > 0)
                    <span class="nav-badge" style="background:#ef4444; color:#fff;">{{ $activeQuarantineCount }}</span>
                @endif
            </a>
        </li>

        <!-- 5. إدارة الإعلانات (الإعلانات الجامعية سابقاً) -->
        <li class="{{ request()->routeIs('admin.advertisements') ? 'active' : '' }}">
            <a href="{{ route('admin.advertisements') }}" title="مراجعة واعتماد إدارة الإعلانات الجامعية">
                <i class="fa-solid fa-bullhorn"></i>
                <span>إدارة الإعلانات</span>
                @php $pendingAdsCount = \App\Models\Advertisement::where('review_status','قيد المراجعة')->count(); @endphp
                @if($pendingAdsCount > 0)
                    <span class="nav-badge">{{ $pendingAdsCount }}</span>
                @endif
            </a>
        </li>

        <!-- 6. متابعة طلبات الالتحاق -->
        <li class="{{ request()->routeIs('admin.requests') ? 'active' : '' }}">
            <a href="{{ route('admin.requests') }}" title="مركز تدفق ومراقبة طلبات الالتحاق">
                <i class="fa-solid fa-graduation-cap"></i>
                <span>متابعة طلبات الالتحاق</span>
                @php $pendingReqs = \App\Models\RegRequest::where('status','قيد المراجعة')->count(); @endphp
                @if($pendingReqs > 0)
                    <span class="nav-badge">{{ $pendingReqs }}</span>
                @endif
            </a>
        </li>

        <!-- 7. إدارة الإحالات (مفصولة) -->
        <li class="{{ request()->routeIs('admin.referrals') ? 'active' : '' }}">
            <a href="{{ route('admin.referrals') }}" title="إدارة مسارات ورموز إحالات الطلاب">
                <i class="fa-solid fa-diagram-project"></i>
                <span>إدارة الإحالات</span>
            </a>
        </li>

        <!-- 8. إدارة العمولات (مفصولة) -->
        <li class="{{ request()->routeIs('admin.commissions') ? 'active' : '' }}">
            <a href="{{ route('admin.commissions') }}" title="إدارة وصرف العمولات المالية والمكافآت">
                <i class="fa-solid fa-sack-dollar"></i>
                <span>إدارة العمولات</span>
                @php $pendingComm = \App\Models\StudentReferral::where('ref_status','مكتملة')->whereNull('commission_paid_at')->count(); @endphp
                @if($pendingComm > 0)
                    <span class="nav-badge gold">{{ $pendingComm }}</span>
                @endif
            </a>
        </li>

        <!-- 9. التقارير والإحصائيات -->
        <li class="{{ request()->routeIs('admin.reports') ? 'active' : '' }}">
            <a href="{{ route('admin.reports') }}" title="التقارير والمؤشرات الإحصائية العامة">
                <i class="fa-solid fa-chart-line"></i>
                <span>التقارير والإحصائيات</span>
            </a>
        </li>

        <!-- 10. الإعدادات العامة -->
        <li class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
            <a href="{{ route('admin.settings') }}" title="إعدادات وضوابط المنظومة">
                <i class="fa-solid fa-sliders"></i>
                <span>الإعدادات العامة</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-bottom">
        <a href="{{ route('home') }}" target="_blank">
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
            <span>الموقع العام</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" style="margin-top:4px;">
            @csrf
            <button type="submit" style="width:100%;background:none;border:none;padding:0;text-align:right;">
                <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" style="color:rgba(255,255,255,.45);">
                    <i class="fa-solid fa-power-off"></i>
                    <span>تسجيل الخروج</span>
                </a>
            </button>
        </form>
    </div>
</aside>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- Header -->
<header class="admin-header">
    <div class="header-left-part">
        <button class="header-toggle" id="sidebarToggle" onclick="toggleSidebar()">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div class="header-breadcrumb">
            <i class="fa-solid fa-gauge-high"></i>
            @yield('breadcrumb', '<strong>لوحة التحكم</strong>')
        </div>
    </div>

    <div class="header-center-part">
        <div class="header-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="بحث سريع في النظام (جامعة، إعلان، طالب، مستخدم)..." id="adminQuickSearch">
        </div>
    </div>

    <div class="header-actions">
        {{-- زر تبديل الوضع الليلي / النهاري --}}
        <button type="button" class="header-theme-toggle" id="adminThemeBtn" onclick="toggleAdminTheme()" title="تبديل الوضع الليلي والنهاري" aria-label="تبديل المظهر">
            <i class="fa-solid fa-moon" id="adminThemeIcon"></i>
        </button>

        <div class="header-date-badge">
            <i class="fa-regular fa-clock"></i>
            <span id="currentTime">--:--</span>
        </div>
    </div>
</header>

<!-- Main Content -->
<main class="admin-main">
    @if(session('success'))
        <div class="toast-container" id="sessionToastContainer">
            <div class="toast success" id="sessionToast">
                <i class="fa-solid fa-circle-check toast-icon"></i>
                <div>
                    <div class="toast-title">تمت العملية بنجاح</div>
                    <div class="toast-msg">{{ session('success') }}</div>
                </div>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="toast-container" id="sessionToastContainerErr">
            <div class="toast error" id="sessionToastErr">
                <i class="fa-solid fa-circle-xmark toast-icon"></i>
                <div>
                    <div class="toast-title">حدث خطأ</div>
                    <div class="toast-msg">{{ session('error') }}</div>
                </div>
            </div>
        </div>
    @endif

    @yield('content')
</main>

<!-- Toast Container for JS notifications -->
<div class="toast-container" id="toastContainer"></div>

<script>
    // Sidebar toggle
    function toggleSidebar() {
        document.getElementById('adminSidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('open');
    }

    function closeSidebar() {
        document.getElementById('adminSidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('open');
    }

    // Theme mode toggle
    function updateAdminThemeIcon(theme) {
        const icon = document.getElementById('adminThemeIcon');
        if (icon) {
            icon.className = (theme === 'dark') ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
            icon.style.color = (theme === 'dark') ? '#f0c842' : '';
        }
    }

    function toggleAdminTheme() {
        const cur = document.documentElement.getAttribute('data-theme') || 'light';
        const next = (cur === 'dark') ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', next);
        localStorage.setItem('eduyemen_theme', next);
        updateAdminThemeIcon(next);

        fetch('{{ route("user.theme") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ theme_mode: next })
        }).catch(err => console.log('Theme saved locally'));
    }

    document.addEventListener('DOMContentLoaded', function() {
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
        updateAdminThemeIcon(currentTheme);
    });

    // Clock
    function updateClock() {
        const now = new Date();
        document.getElementById('currentTime').textContent =
            now.toLocaleTimeString('ar-YE', { hour: '2-digit', minute: '2-digit' });
    }
    updateClock();
    setInterval(updateClock, 10000);

    // Toast notification
    function showToast(message, type = 'info', title = '') {
        const container = document.getElementById('toastContainer');
        const icons = { success: 'fa-circle-check', error: 'fa-circle-xmark', info: 'fa-circle-info' };
        const titles = { success: 'تمت العملية بنجاح', error: 'حدث خطأ', info: 'تنبيه' };

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <i class="fa-solid ${icons[type] || icons.info} toast-icon"></i>
            <div>
                <div class="toast-title">${title || titles[type] || 'تنبيه'}</div>
                <div class="toast-msg">${message}</div>
            </div>
        `;

        container.appendChild(toast);
        setTimeout(() => toast.remove(), 4500);
    }

    // Auto-dismiss session toasts
    setTimeout(() => {
        document.getElementById('sessionToast')?.remove();
        document.getElementById('sessionToastContainer')?.remove();
        document.getElementById('sessionToastErr')?.remove();
        document.getElementById('sessionToastContainerErr')?.remove();
    }, 4000);

    // Global CSRF fetch helper
    async function adminFetch(url, method = 'POST', body = null) {
        const opts = {
            method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        };
        if (body && !(body instanceof FormData)) {
            opts.headers['Content-Type'] = 'application/json';
            opts.body = JSON.stringify(body);
        } else if (body) {
            opts.body = body;
        }
        const res = await fetch(url, opts);
        return res.json();
    }

    // Open / close modals
    function openModal(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.add('open');
            el.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.remove('open');
            el.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    // Close modal on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) closeModal(this.id);
        });
    });
</script>

@yield('scripts')
@stack('scripts')
</body>
</html>
