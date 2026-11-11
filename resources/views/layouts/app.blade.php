<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'منصة EduYemen - البوابة الموحدة للجامعات اليمنية')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            /* الألوان الرسمية المعتمدة لمنصة EduYemen */
            --primary: #0b3d6b;          /* الكحلي الأكاديمي الأساسي */
            --primary-dark: #072b4f;     /* الكحلي الملكي العميق */
            --primary-light: #1565a8;    /* الكحلي المضيء */
            --accent: #c59b27;           /* الذهبي الفاخر */
            --accent-light: #f0c842;     /* الذهبي المشع */
            --accent-crimson: #C91521;   /* القرمزي */
            --success: #1a8754;
            --info: #0ea5e9;
            --light-bg: #f4f6fb;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 2px 8px rgba(11, 61, 107, 0.06);
            --shadow-md: 0 4px 20px rgba(11, 61, 107, 0.09);
            --shadow-lg: 0 8px 40px rgba(11, 61, 107, 0.14);
            --radius: 12px;
            --navbar-height: 64px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Cairo', 'Tajawal', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-size: 15px;
            line-height: 1.7;
            /* leave room for fixed navbar */
            padding-top: var(--navbar-height);
        }

        @if(request()->routeIs('login') || request()->routeIs('register'))
        body {
            padding-top: 0 !important;
            overflow-x: hidden;
        }
        @endif

        /* =========================================================
           NAVBAR (Desktop)
        ========================================================= */
        .main-navbar {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            box-shadow: 0 3px 16px rgba(0,0,0,0.25);
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1060;
            height: var(--navbar-height);
        }

        /* Brand logo image - transparent emblem */
        .brand-logo-img {
            width: 38px;
            height: 38px;
            object-fit: contain;
            background: transparent;
            filter: drop-shadow(0 2px 6px rgba(0,0,0,0.25));
        }

        /* Edu white / Yemen gold */
        .brand-edu   { color: #ffffff;  font-weight: 800; }
        .brand-yemen { color: var(--accent-light); font-weight: 800; }
        .brand-text  { font-size: 1.45rem; letter-spacing: -0.5px; line-height: 1; }
        .brand-sub   { font-size: 0.67rem; color: rgba(255,255,255,0.55); display: block; line-height: 1.2; }

        /* Nav links – desktop */
        .main-navbar .nav-link {
            color: rgba(255,255,255,0.82) !important;
            font-weight: 600;
            font-size: 0.88rem;
            padding: 0.45rem 0.9rem !important;
            border-radius: 8px;
            transition: all .2s;
        }
        .main-navbar .nav-link:hover,
        .main-navbar .nav-link.active {
            color: #fff !important;
            background: rgba(255,255,255,0.13);
        }

        .badge-deferred {
            background: rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.5);
            font-size: 0.6rem;
            padding: .15rem .4rem;
            border-radius: 4px;
            border: 1px solid rgba(255,255,255,0.15);
        }

        /* Auth buttons – desktop */
        
        /* Nav Profile Avatar Button */
        .btn-nav-profile-avatar {
            background: rgba(255, 255, 255, 0.12);
            border: 1.5px solid rgba(255, 255, 255, 0.32);
            color: #fff !important;
            padding: 3px 8px 3px 4px;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }
        .btn-nav-profile-avatar:hover,
        .btn-nav-profile-avatar[aria-expanded="true"] {
            background: rgba(255, 255, 255, 0.22);
            border-color: rgba(255, 255, 255, 0.55);
            box-shadow: 0 2px 8px rgba(0,0,0,0.18);
            transform: translateY(-1px);
        }
        .nav-avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent, #c59b27), #e5b93d);
            color: #07213c;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.2);
        }
        .nav-avatar-chevron {
            font-size: 0.72rem;
            opacity: 0.85;
            margin-left: 2px;
        }
        .btn-mobile-user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent, #c59b27), #e5b93d);
            color: #07213c;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            border: 1.5px solid rgba(255,255,255,0.4);
            text-decoration: none;
        }

        .btn-nav-login {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.28);
            color: #fff !important;
            font-weight: 600;
            font-size: 0.84rem;
            padding: .38rem 1rem;
            border-radius: 8px;
            transition: all .2s;
        }
        .btn-nav-login:hover { background: rgba(255,255,255,0.2); }

        .btn-nav-register {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
            border: none;
            color: #1a1a1a !important;
            font-weight: 700;
            font-size: 0.84rem;
            padding: .38rem 1.1rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(197,155,39,.35);
            transition: all .2s;
        }
        .btn-nav-register:hover {
            background: linear-gradient(135deg,#a88118 0%,var(--accent) 100%);
            box-shadow: 0 4px 14px rgba(197,155,39,.5);
            transform: translateY(-1px);
        }

        /* Hamburger – visible only on mobile */
        .navbar-toggler-custom {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.28);
            border-radius: 8px;
            padding: .3rem .6rem;
            color: #fff;
            font-size: 1.5rem;
            display: none;   /* shown in mobile CSS below */
            cursor: pointer;
            line-height: 1;
        }

        /* =========================================================
           MOBILE & DESKTOP NAVBAR CONTROLS
        ========================================================= */
        .mobile-auth-area {
            display: none;
            margin-right: auto;
            align-items: center;
        }

        .btn-mobile-login {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
            color: #1a1a1a !important;
            font-weight: 700;
            font-size: 0.78rem;
            padding: 0.32rem 0.75rem;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            text-decoration: none;
            box-shadow: 0 2px 6px rgba(197, 155, 39, 0.35);
            transition: all 0.2s ease;
        }
        .btn-mobile-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(197, 155, 39, 0.5);
            color: #1a1a1a !important;
        }

        .btn-mobile-user {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff !important;
            font-weight: 600;
            font-size: 0.76rem;
            padding: 0.3rem 0.65rem;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-mobile-user:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff !important;
        }

        @media (max-width: 991.98px) {
            .desktop-nav,
            .desktop-auth { display: none !important; }
            .mobile-auth-area { display: inline-flex !important; }
        }

        @media (max-width: 576px) {
            :root {
                --navbar-height: 58px;
            }
            .brand-text {
                font-size: 1.25rem;
            }
            .brand-sub {
                display: none !important; /* إخفاء النص الفرعي لإفساح المجال وتفادي التزاحم على شاشات الهواتف */
            }
            .brand-logo-img {
                width: 34px;
                height: 34px;
            }
            .btn-hamburger-menu {
                width: 36px;
                height: 36px;
            }
            .main-navbar .container {
                padding-left: 12px;
                padding-right: 12px;
            }
        }

        /* =========================================================
           FLASH MESSAGES
        ========================================================= */
        .flash-container {
            position: fixed;
            top: calc(var(--navbar-height) + 10px);
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            width: min(92%, 580px);
        }

        /* =========================================================
           CONTENT / FOOTER
        ========================================================= */
        .content-wrapper { flex: 1 0 auto; }

        .card { border-radius: var(--radius) !important; transition: transform .2s, box-shadow .2s; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg) !important; }

        /* Buttons */
        .btn { font-family: 'Cairo','Tajawal',sans-serif; font-weight: 600; }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border: none;
            box-shadow: 0 2px 8px rgba(11,61,107,.25);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            box-shadow: 0 4px 16px rgba(11,61,107,.35);
        }
        .btn-accent {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
            border: none; color: #1a1a1a;
            box-shadow: 0 2px 8px rgba(197,155,39,.3);
        }
        .btn-accent:hover {
            background: linear-gradient(135deg,#a88118 0%,var(--accent) 100%);
            color: #1a1a1a;
        }

        /* شريط التنقل الخاص بصفحة عرض الجامعة */
        .btn-nav-back-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #f1f5f9;
            color: var(--primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 1.05rem;
            transition: all 0.2s ease;
            border: 1.5px solid var(--border-color);
            flex-shrink: 0;
        }
        .btn-nav-back-circle:hover {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
        }
        .btn-nav-profile-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #f8fafc;
            color: var(--primary);
            border: 1.5px solid var(--border-color);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
            flex-shrink: 0;
        }
        .btn-nav-profile-icon:hover {
            background: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
        }
        .uni-header-switcher {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f8fafc;
            border: 1.5px solid rgba(197, 155, 39, 0.45);
            padding: 4px 12px;
            border-radius: 10px;
            max-width: 320px;
        }
        .uni-header-select {
            border: none;
            background: transparent;
            font-weight: 700;
            font-size: 0.84rem;
            color: var(--primary);
            outline: none;
            cursor: pointer;
            box-shadow: none !important;
            padding: 2px 4px;
        }
        @media (max-width: 767.98px) {
            .uni-header-switcher {
                max-width: 175px;
                padding: 3px 6px;
                gap: 4px;
            }
            .uni-header-select {
                font-size: 0.74rem;
            }
            .btn-nav-back-circle, .btn-nav-profile-icon {
                width: 34px;
                height: 34px;
                font-size: 0.95rem;
            }
        }

        /* Badges */
        .badge-gov     { background: #1e40af; color: white; }
        .badge-private { background: #065f46; color: white; }
        .badge-active  { background: #166534; color: white; }

        /* =========================================================
           UNIVERSITY CARD ACTIONS (No-wrap guaranteed row)
        ========================================================= */
        .univ-card-footer-actions {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: space-between !important;
            flex-wrap: nowrap !important; /* منع النزول لسطر جديد نهائياً */
            gap: 8px !important;
            padding: 0.55rem 0.85rem !important;
            background: #ffffff !important;
            border-top: 1px solid rgba(226, 232, 240, 0.8) !important;
            box-sizing: border-box !important;
            width: 100% !important;
        }

        .btn-univ-view {
            flex: 1 1 auto !important;
            min-width: 0 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px !important;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 0.84rem !important;
            line-height: 1.2 !important;
            padding: 0.5rem 0.75rem !important;
            border-radius: 9px !important;
            border: none !important;
            text-decoration: none !important;
            white-space: nowrap !important;
            box-shadow: 0 2px 6px rgba(11, 61, 107, 0.18) !important;
            transition: all 0.2s ease !important;
        }

        .btn-univ-view:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%) !important;
            color: #ffffff !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(11, 61, 107, 0.28) !important;
        }

        .btn-univ-view-text {
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        .btn-univ-view i {
            flex-shrink: 0 !important;
            font-size: 0.95rem !important;
            transition: transform 0.2s ease !important;
        }

        .btn-univ-view:hover i {
            transform: translateX(-3px) !important;
        }

        .btn-univ-compare {
            flex: 0 0 auto !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 5px !important;
            background: #f8fafc !important;
            color: var(--primary) !important;
            border: 1.5px solid #cbd5e1 !important;
            font-weight: 700 !important;
            font-size: 0.82rem !important;
            line-height: 1.2 !important;
            padding: 0.48rem 0.75rem !important;
            border-radius: 9px !important;
            text-decoration: none !important;
            white-space: nowrap !important;
            transition: all 0.2s ease !important;
        }

        .btn-univ-compare:hover {
            background: rgba(197, 155, 39, 0.12) !important;
            border-color: var(--accent) !important;
            color: #926d11 !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 2px 8px rgba(197, 155, 39, 0.2) !important;
        }

        .btn-univ-compare i {
            flex-shrink: 0 !important;
            font-size: 0.95rem !important;
        }

        .btn-univ-compare-text {
            white-space: nowrap !important;
        }

        /* شاشات الهواتف (< 768px) */
        @media (max-width: 767.98px) {
            .univ-card-footer-actions {
                gap: 6px !important;
                padding: 0.45rem 0.65rem !important;
            }
            .btn-univ-view {
                font-size: 0.78rem !important;
                padding: 0.45rem 0.55rem !important;
                gap: 4px !important;
            }
            .btn-univ-compare {
                font-size: 0.78rem !important;
                padding: 0.45rem 0.6rem !important;
                gap: 4px !important;
            }
        }

        /* شاشات الهواتف الصغيرة (< 400px) */
        @media (max-width: 400px) {
            .univ-card-footer-actions {
                gap: 4px !important;
                padding: 0.4rem 0.45rem !important;
            }
            .btn-univ-view {
                font-size: 0.72rem !important;
                padding: 0.4rem 0.45rem !important;
                gap: 3px !important;
            }
            .btn-univ-compare {
                font-size: 0.72rem !important;
                padding: 0.4rem 0.5rem !important;
                gap: 3px !important;
            }
            .btn-univ-view i,
            .btn-univ-compare i {
                font-size: 0.85rem !important;
            }
        }

        /* شاشات صغيرة جداً (< 340px) */
        @media (max-width: 340px) {
            .btn-univ-view {
                font-size: 0.68rem !important;
                padding: 0.35rem 0.4rem !important;
            }
            .btn-univ-compare {
                font-size: 0.68rem !important;
                padding: 0.35rem 0.45rem !important;
            }
        }

        /* Inputs */
        .form-control, .form-select {
            font-family: 'Cairo','Tajawal',sans-serif;
            border-color: var(--border-color);
            border-radius: 8px;
            padding: .55rem .85rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(11,61,107,.12);
        }

        /* Utilities */
        .text-primary   { color: var(--primary) !important; }
        .bg-primary     { background-color: var(--primary) !important; }
        .text-accent    { color: var(--accent) !important; }
        .rounded-4      { border-radius: var(--radius) !important; }
        .shadow-sm      { box-shadow: var(--shadow-sm) !important; }
        .shadow         { box-shadow: var(--shadow-md) !important; }
        .shadow-lg      { box-shadow: var(--shadow-lg) !important; }

        /* Footer */
        .site-footer {
            background: linear-gradient(135deg, #062238 0%, var(--primary-dark) 100%);
            color: #cbd5e1;
            margin-top: auto;
            border-top: 3px solid var(--accent);
        }
        .site-footer h5, .site-footer h6 { color: #fff; }
        .site-footer a { color: #94a3b8; text-decoration: none; transition: color .2s; }
        .site-footer a:hover { color: var(--accent-light); }
        .footer-divider { border-color: rgba(255,255,255,0.1); }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 3px; }

        /* Section header */
        .section-label {
            font-size: .78rem; font-weight: 700; letter-spacing: 1.5px;
            text-transform: uppercase; color: var(--accent); margin-bottom: .5rem;
        }
        .section-title { font-size: 1.7rem; font-weight: 800; color: var(--text-dark); }
        .section-header { position: relative; padding-bottom: .6rem; margin-bottom: 1.5rem; }
        .section-header::after {
            content: '';
            position: absolute; bottom: 0; right: 0;
            width: 60px; height: 3px;
            background: linear-gradient(90deg, var(--accent), var(--accent-light));
            border-radius: 3px;
        }

        /* Hamburger Button */
        .btn-hamburger-menu {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 8px;
            color: #ffffff;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-left: 6px;
            flex-shrink: 0;
        }
        .btn-hamburger-menu:hover {
            background: rgba(197, 155, 39, 0.25);
            border-color: var(--accent);
            color: var(--accent-light);
        }

        html, body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
        }

        /* ====== SIDEBAR DRAWER STYLES (متجاوبة تماماً مع شاشات الهواتف) ====== */
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(6, 17, 30, 0.65);
            z-index: 10040;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, visibility 0.25s ease;
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }
        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .sidebar-drawer {
            position: fixed !important;
            top: 0 !important;
            bottom: 0 !important;
            right: 0 !important;
            left: auto !important;
            width: min(300px, 84vw) !important;
            max-width: 86vw !important;
            height: 100vh !important;
            height: 100dvh !important;
            background: linear-gradient(175deg, #071c30 0%, #0b2238 60%, #0a253f 100%) !important;
            color: #FFFFFF !important;
            z-index: 10050 !important;
            display: flex !important;
            flex-direction: column !important;
            box-shadow: -5px 0 35px rgba(0, 0, 0, 0.5) !important;
            transform: translateX(105%) !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.25s ease, visibility 0.3s !important;
            direction: rtl !important;
            text-align: right !important;
            overflow: hidden !important;
        }
        .sidebar-drawer.open {
            transform: translateX(0) !important;
            visibility: visible !important;
            opacity: 1 !important;
            pointer-events: auto !important;
        }
        .sidebar-header {
            padding: 16px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }
        .sidebar-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #FFFFFF;
            display: flex;
            align-items: center;
            gap: 4px;
            direction: ltr;
        }
        .sidebar-title span {
            color: var(--accent);
        }
        .btn-close-sidebar {
            background: rgba(255, 255, 255, 0.1);
            color: #FFFFFF;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-close-sidebar:hover {
            background: rgba(220, 53, 69, 0.8);
            border-color: transparent;
            color: #FFFFFF;
        }
        .sidebar-nav-list {
            list-style: none;
            margin: 0;
            padding: 10px 8px;
            display: flex;
            flex-direction: column;
            gap: 3px;
            flex: 1 1 auto;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior: contain;
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
        }
        .sidebar-nav-list::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
        .sidebar-nav-item a,
        .sidebar-nav-item button {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9px 12px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.88);
            background: transparent;
            border: none;
            text-align: right;
            text-decoration: none;
            transition: all 0.18s ease;
            cursor: pointer;
            min-height: 42px;
        }
        .sidebar-nav-item a:hover,
        .sidebar-nav-item button:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #FFFFFF;
        }
        .sidebar-nav-item.active a {
            background: rgba(197, 155, 39, 0.18);
            color: #FFFFFF;
            font-weight: 700;
            border-right: 3px solid var(--accent);
        }
        .sidebar-nav-item a svg,
        .sidebar-nav-item button svg {
            color: var(--accent) !important;
            stroke: var(--accent) !important;
            flex-shrink: 0;
            width: 19px;
            height: 19px;
        }

        /* عنصر دليل الجامعات: رابط رئيسي وبجانبه زر القائمة المنسدلة */
        .sidebar-univ-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            border-radius: 10px;
            transition: all 0.18s ease;
        }
        .sidebar-univ-row:hover {
            background: rgba(255, 255, 255, 0.08);
        }
        .sidebar-univ-main-link {
            flex: 1 1 auto;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9px 12px;
            font-size: 0.9rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.88);
            text-decoration: none;
            min-height: 42px;
        }
        .sidebar-univ-main-link:hover {
            color: #ffffff;
        }
        /* زر السهم: بمساحة حجم الأيقونة فقط دون إطار أو خلفية حتى لا يزاحم النص الرئيسي */
        .btn-toggle-univ-subdrawer {
            width: auto !important;
            height: auto !important;
            min-width: 0 !important;
            min-height: 0 !important;
            padding: 6px 8px !important;
            margin: 0 !important;
            background: transparent !important;
            border: none !important;
            color: rgba(255, 255, 255, 0.6) !important;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: color 0.15s ease, transform 0.2s ease;
            flex-shrink: 0;
            font-size: 0.85rem;
            line-height: 1;
        }
        .btn-toggle-univ-subdrawer:hover,
        .btn-toggle-univ-subdrawer.active {
            background: transparent !important;
            color: var(--accent, #c59b27) !important;
        }
        .btn-toggle-univ-subdrawer.active i {
            transform: rotate(180deg);
        }

        /* القائمة الجانبية العصرية: الصفحة والشريط العلوي ثابتان تماماً دون أي إزاحة أو تحرك يزعج المستخدم */
        .app-page-wrapper {
            position: relative;
            width: 100%;
            min-height: 100vh;
            background-color: inherit;
        }

        /* عند فتح القائمة، يتم إخفاء زر الهامبرغر بسلاسة من الشريط العلوي لمنع التكرار */
        body.sidebar-open .main-navbar .btn-hamburger-menu {
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
            transform: scale(0.85);
            transition: opacity 0.2s ease, visibility 0.2s, transform 0.2s ease;
        }

        .btn-back-subdrawer {
            background: transparent;
            border: none;
            color: var(--primary, #072644);
            font-size: 1.1rem;
            cursor: pointer;
            padding: 0;
            line-height: 1;
        }

        /* القائمة الجانبية الفرعية لعرض الجامعات (تصميم رسمي عصري ومخفية تماماً حتى النقر) */
        .sidebar-sub-drawer {
            display: none !important;
            position: fixed !important;
            top: 0 !important;
            bottom: 0 !important;
            right: 300px !important;
            width: 260px !important;
            max-width: 82vw !important;
            height: 100vh !important;
            height: 100dvh !important;
            background: #ffffff !important;
            color: #1e293b !important;
            z-index: 10055 !important;
            flex-direction: column !important;
            box-shadow: -8px 0 28px rgba(7, 38, 68, 0.18) !important;
            border-left: 1px solid #cbd5e1 !important;
            border-right: 2px solid var(--accent, #c59b27) !important;
            direction: rtl !important;
            text-align: right !important;
            overflow: hidden !important;
        }
        .sidebar-sub-drawer.open {
            display: flex !important;
            animation: fastSubDrawerIn 0.14s ease-out;
        }
        @keyframes fastSubDrawerIn {
            from {
                opacity: 0;
                transform: translateX(12px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .sub-drawer-header {
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #e2e8f0;
            background: #f8fafc;
            flex-shrink: 0;
        }
        .sub-drawer-title {
            font-size: 0.92rem;
            font-weight: 800;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .btn-close-subdrawer {
            background: #e2e8f0;
            border: none;
            color: #475467;
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .btn-close-subdrawer:hover {
            background: #ef4444;
            color: #ffffff;
        }
        .sub-drawer-body {
            padding: 12px 10px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1 1 auto;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            background: #ffffff;
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
        }
        .sub-drawer-body::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
        .sub-drawer-univ-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            font-size: 0.86rem;
            font-weight: 700;
            color: #1e293b;
            text-decoration: none;
            border-radius: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: background 0.12s ease, border-color 0.12s ease, color 0.12s ease;
        }
        .sub-drawer-univ-link:hover {
            background: #eef4ff;
            border-color: #93c5fd;
            color: var(--primary);
            transform: translateX(-2px);
        }
        .sub-drawer-univ-link i {
            color: var(--primary);
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        /* على شاشات الهواتف والأجهزة اللوحية (< 768px) تظهر القائمة الفرعية كشريحة متراكبة سلسة داخل القائمة */
        @media (max-width: 767.98px) {
            .sidebar-sub-drawer {
                right: 0 !important;
                width: min(300px, 84vw) !important;
                z-index: 10060 !important;
                box-shadow: -5px 0 35px rgba(0, 0, 0, 0.4) !important;
            }
        }

        .sidebar-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.09);
            margin: 6px 10px;
            flex-shrink: 0;
        }
        .sidebar-footer-auth {
            padding: 12px 14px max(14px, env(safe-area-inset-bottom));
            border-top: 1.5px solid var(--accent);
            background-color: #051422;
            flex-shrink: 0;
        }
        .sidebar-btn-login {
            width: 100%;
            height: 40px;
            margin-bottom: 7px;
            background: linear-gradient(135deg, var(--accent) 0%, #a07a18 100%);
            color: #101828 !important;
            font-size: 0.88rem;
            font-weight: 700;
            border-radius: 9px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.15s ease;
            box-shadow: 0 2px 8px rgba(197, 155, 39, 0.3);
        }
        .sidebar-btn-login:hover {
            transform: translateY(-1px);
            color: #101828 !important;
            background: linear-gradient(135deg, var(--accent-light) 0%, var(--accent) 100%);
        }
        .sidebar-btn-register {
            width: 100%;
            height: 38px;
            color: #FFFFFF !important;
            border: 1px solid rgba(255, 255, 255, 0.28);
            background: rgba(255, 255, 255, 0.06);
            font-size: 0.86rem;
            font-weight: 600;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .sidebar-btn-register:hover {
            background: rgba(255, 255, 255, 0.14);
            color: #FFFFFF !important;
        }
        .sidebar-user-card {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(197, 155, 39, 0.3);
            border-radius: 8px;
            padding: 8px 12px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .sidebar-user-name {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--accent);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .sidebar-btn-logout {
            width: 100%;
            height: 36px;
            color: #FCA5A5;
            border: 1px solid rgba(240, 68, 56, 0.4);
            background: rgba(240, 68, 56, 0.08);
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .sidebar-btn-logout:hover {
            background: rgba(240, 68, 56, 0.2);
            color: #FFFFFF;
        }

        /* Table */
        .table th { font-weight: 700; font-size: .85rem; background: #f8fafc; }

        /* =========================================================
           الوضع الليلي (Dark Mode) المعتمد لمنصة EduYemen
        ========================================================= */
        [data-theme="dark"] {
            --light-bg: #0b1324;
            --text-dark: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: #1e293b;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.35);
            --shadow-md: 0 4px 20px rgba(0, 0, 0, 0.45);
            --shadow-lg: 0 8px 40px rgba(0, 0, 0, 0.55);
            background-color: #0b1324 !important;
            color: #f8fafc !important;
        }

        [data-theme="dark"] body {
            background-color: #0b1324 !important;
            color: #f8fafc !important;
        }

        [data-theme="dark"] .bg-white,
        [data-theme="dark"] .card,
        [data-theme="dark"] .modal-content,
        [data-theme="dark"] .dropdown-menu,
        [data-theme="dark"] .sidebar-drawer,
        [data-theme="dark"] .univ-sub-drawer,
        [data-theme="dark"] .compare-form-card,
        [data-theme="dark"] .compare-table-wrap,
        [data-theme="dark"] .spec-compare-section {
            background-color: #131d36 !important;
            color: #f8fafc !important;
            border-color: #1e293b !important;
        }

        [data-theme="dark"] .bg-light,
        [data-theme="dark"] .table-light,
        [data-theme="dark"] .spec-select-box,
        [data-theme="dark"] .card-header.bg-light {
            background-color: #182442 !important;
            color: #cbd5e1 !important;
            border-color: #1e293b !important;
        }

        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select {
            background-color: #0d1629 !important;
            color: #f8fafc !important;
            border-color: #273554 !important;
        }

        [data-theme="dark"] .form-control:focus,
        [data-theme="dark"] .form-select:focus {
            background-color: #0f1a30 !important;
            color: #ffffff !important;
            border-color: var(--accent) !important;
        }

        [data-theme="dark"] .table {
            color: #f1f5f9 !important;
            border-color: #1e293b !important;
        }

        [data-theme="dark"] .table tbody td,
        [data-theme="dark"] .table thead th {
            border-color: #1e293b !important;
            background-color: transparent !important;
        }

        [data-theme="dark"] .compare-table th:first-child,
        [data-theme="dark"] .compare-table td:first-child {
            background: #131d36 !important;
            color: #cbd5e1 !important;
            border-left: 1px solid #1e293b !important;
        }

        [data-theme="dark"] .compare-table thead th:first-child {
            background: #182442 !important;
        }

        [data-theme="dark"] .text-dark {
            color: #f8fafc !important;
        }

        [data-theme="dark"] .text-secondary,
        [data-theme="dark"] .text-muted {
            color: #94a3b8 !important;
        }

        [data-theme="dark"] .border,
        [data-theme="dark"] .border-bottom,
        [data-theme="dark"] .border-top {
            border-color: #1e293b !important;
        }
    </style>
    <script>
        (function() {
            var userDbTheme = @json(auth()->check() ? (auth()->user()->theme_mode ?? 'light') : null);
            var localTheme = localStorage.getItem('eduyemen_theme');
            
            // الوضع النهاري (الضوئي) هو المظهر الافتراضي والتلقائي لكافة صفحات المنصة ولكل المستخدمين والزوار على جميع الأجهزة
            var targetTheme = 'light';
            
            // لا يتم اعتماد الوضع الليلي إلا إذا كان محدداً ومحفوظاً صراحة في بيانات المستخدم
            if (userDbTheme === 'dark') {
                targetTheme = 'dark';
            } else if (!userDbTheme && localTheme === 'dark') {
                // إذا لم يكن مسجل دخول وسبق له اختيار الوضع الليلي في المتصفح يدوياً
                targetTheme = 'dark';
            }
            
            if (userDbTheme) {
                localStorage.setItem('eduyemen_theme', userDbTheme);
            } else if (!localTheme || localTheme === 'auto') {
                localStorage.setItem('eduyemen_theme', 'light');
            }

            document.documentElement.setAttribute('data-theme', targetTheme);
            document.documentElement.setAttribute('data-theme-setting', targetTheme);
        })();
    </script>
    @yield('styles')
</head>
<body>

<div id="appPageWrapper" class="app-page-wrapper">

@if(!request()->routeIs('login') && !request()->routeIs('register'))
@if(request()->routeIs('universities.show'))
{{-- =====================================================================
     NAVBAR FOR UNIVERSITY SHOW PAGE (شريط تنقل صفحة الجامعة المخصص)
===================================================================== --}}
<nav class="main-navbar navbar" aria-label="شريط تنقل صفحة الجامعة">
    <div class="container d-flex align-items-center justify-content-between gap-2" style="height:var(--navbar-height);">
        
        {{-- Right: Hamburger Menu (Sidebar) + Back Button + Logo --}}
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn-hamburger-menu" onclick="toggleSidebar()" title="فتح/إغلاق القائمة الجانبية" aria-label="فتح/إغلاق القائمة الجانبية">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('universities.index') }}" 
               onclick="if(window.history.length > 1) { window.history.back(); return false; }" 
               class="btn-nav-back-circle" 
               title="الرجوع للصفحة السابقة" 
               aria-label="الرجوع للصفحة السابقة">
                <i class="bi bi-chevron-right"></i>
            </a>
            <a class="navbar-brand d-flex align-items-center gap-2 text-decoration-none flex-shrink-0" href="{{ route('home') }}">
                <img src="/images/logo.png"
                     onerror="this.onerror=null;this.src='/images/eduyemen-logo.svg';"
                     class="brand-logo-img"
                     alt="EduYemen Logo">
                <div class="d-none d-sm-block">
                    <span class="brand-text">
                        <span class="brand-edu">Edu</span><span class="brand-yemen">Yemen</span>
                    </span>
                </div>
            </a>
        </div>

        {{-- Center: University Switcher --}}
        <div class="mx-auto text-center">
            @if(isset($allUniversities) && $allUniversities->isNotEmpty())
            <div class="uni-header-switcher">
                <i class="bi bi-buildings-fill text-warning"></i>
                <span class="d-none d-md-inline fw-bold text-primary" style="font-size:0.8rem;">اختيار الجامعة:</span>
                <select class="uni-header-select" onchange="if(this.value) window.location.href='/universities/' + this.value">
                    <optgroup label="الجامعات الحكومية">
                        @foreach($allUniversities->where('university_type', 'حكومية') as $uGov)
                        <option value="{{ $uGov->university_id }}" {{ (isset($university) && $uGov->university_id == $university->university_id) ? 'selected' : '' }}>
                            {{ $uGov->university_name }}
                        </option>
                        @endforeach
                    </optgroup>
                    <optgroup label="الجامعات الأهلية والدولية">
                        @foreach($allUniversities->where('university_type', '!=', 'حكومية') as $uPriv)
                        <option value="{{ $uPriv->university_id }}" {{ (isset($university) && $uPriv->university_id == $university->university_id) ? 'selected' : '' }}>
                            {{ $uPriv->university_name }}
                        </option>
                        @endforeach
                    </optgroup>
                </select>
            </div>
            @endif
        </div>

        {{-- Left: User Profile / Account --}}
        <div class="d-flex align-items-center gap-2">
            @auth
                <div class="dropdown">
                    <button class="btn btn-nav-profile-icon" type="button" data-bs-toggle="dropdown" title="حسابي">
                        <i class="bi bi-person-circle fs-5"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2" style="min-width:180px;">
                        <li class="px-3 py-2 border-bottom">
                            <div class="fw-bold text-dark">
                                @if(Auth::user()->isStudent() && Auth::user()->student)
                                    {{ Auth::user()->student->full_name }}
                                @else
                                    {{ Auth::user()->username }}
                                @endif
                            </div>
                            <span class="badge bg-warning text-dark" style="font-size:.7rem;">
                                @if(Auth::user()->isAdmin()) مدير النظام
                                @elseif(Auth::user()->isUniversity()) حساب جامعة
                                @else حساب طالب ({{ '@' . Auth::user()->username }}) @endif
                            </span>
                        </li>
                        @if(Auth::user()->isAdmin())
                            <li><a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2 text-primary"></i>لوحة التحكم</a></li>
                        @elseif(Auth::user()->isUniversity())
                            <li><a class="dropdown-item py-2" href="{{ route('university.dashboard') }}"><i class="bi bi-building me-2 text-primary"></i>لوحة الجامعة</a></li>
                        @else
                            <li><a class="dropdown-item py-2" href="{{ route('student.dashboard') }}"><i class="bi bi-person-circle me-2 text-primary"></i>الملف الشخصي</a></li>
                        @endif
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>تسجيل الخروج
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-nav-profile-icon" title="تسجيل الدخول / الحساب">
                    <i class="bi bi-person fs-5"></i>
                </a>
            @endauth
        </div>

    </div>
</nav>
@else
{{-- =====================================================================
     STANDARD NAVBAR
===================================================================== --}}
<nav class="main-navbar navbar" aria-label="شريط التنقل الرئيسي">
    <div class="container d-flex align-items-center justify-content-between gap-2" style="height:var(--navbar-height);">

        {{-- Brand Group with Hamburger Button --}}
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn-hamburger-menu" onclick="toggleSidebar()" title="فتح/إغلاق القائمة الجانبية" aria-label="فتح/إغلاق القائمة الجانبية">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
            <a class="navbar-brand d-flex align-items-center gap-2 text-decoration-none flex-shrink-0" href="{{ route('home') }}">
                {{-- شعار المنصة المعتمد الشفاف --}}
                <img src="/images/logo.png"
                     onerror="this.onerror=null;this.src='/images/eduyemen-logo.svg';"
                     class="brand-logo-img"
                     alt="EduYemen Logo">
                <div>
                    <span class="brand-text">
                        <span class="brand-edu">Edu</span><span class="brand-yemen">Yemen</span>
                    </span>
                    <span class="brand-sub">البوابة الموحدة للجامعات اليمنية</span>
                </div>
            </a>
        </div>

        {{-- Desktop nav links --}}
        <div class="desktop-nav d-flex align-items-center gap-1 ms-3 ms-md-4">
            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                <i class="bi bi-house me-1"></i>الرئيسية
            </a>
            <a class="nav-link {{ request()->routeIs('universities.*') ? 'active' : '' }}" href="{{ route('universities.index') }}">
                <i class="bi bi-buildings me-1"></i>دليل الجامعات
            </a>
            <a class="nav-link {{ request()->routeIs('compare') ? 'active' : '' }}" href="{{ route('compare') }}">
                <i class="bi bi-arrow-left-right me-1"></i>مقارنة
            </a>
            <a class="nav-link" href="{{ route('ai.advisor') }}">
                <i class="bi bi-robot me-1"></i>المساعد الذكي
                <span class="badge-deferred ms-1">قريباً</span>
            </a>
        </div>

        {{-- Desktop auth --}}
        <div class="desktop-auth d-flex align-items-center gap-2 ms-auto">
            @auth
                <div class="dropdown">
                    <button class="btn btn-nav-profile-avatar" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="الملف الشخصي">
                        <span class="nav-avatar-circle">
                            <i class="bi bi-person-fill"></i>
                        </span>
                        <i class="bi bi-chevron-down nav-avatar-chevron"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2" style="min-width:180px;">
                        @if(Auth::user()->isAdmin())
                            <li><a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}"><i class="bi bi-person-circle me-2 text-primary"></i>الملف الشخصي</a></li>
                        @elseif(Auth::user()->isUniversity())
                            <li><a class="dropdown-item py-2" href="{{ route('university.dashboard') }}"><i class="bi bi-person-circle me-2 text-primary"></i>الملف الشخصي</a></li>
                        @else
                            <li><a class="dropdown-item py-2" href="{{ route('student.dashboard') }}"><i class="bi bi-person-circle me-2 text-primary"></i>الملف الشخصي</a></li>
                        @endif
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>تسجيل الخروج
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-nav-login">
                    <i class="bi bi-box-arrow-in-left me-1"></i>دخول
                </a>
                <a href="{{ route('register') }}" class="btn btn-nav-register">
                    <i class="bi bi-person-plus me-1"></i>حساب جديد
                </a>
            @endauth
        </div>

        {{-- Mobile Auth Action (خيارات تسجيل الدخول/الحساب في شاشة الهاتف) --}}
        <div class="mobile-auth-area">
            @auth
                @php
                    $mobileProfileRoute = Auth::user()->isAdmin() ? route('admin.dashboard') : (Auth::user()->isUniversity() ? route('university.dashboard') : route('student.dashboard'));
                @endphp
                <a href="{{ $mobileProfileRoute }}" class="btn-mobile-user-avatar" title="الملف الشخصي">
                    <i class="bi bi-person-fill"></i>
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-mobile-login">
                    <i class="bi bi-person"></i>
                    <span>دخول</span>
                </a>
            @endauth
        </div>

    </div>
</nav>
@endif
@endif

{{-- =====================================================================
     FLASH MESSAGES
===================================================================== --}}
@if(!request()->routeIs('login') && !request()->routeIs('register') && (session('success') || session('error') || session('info') || (isset($errors) && $errors->any())))
<div class="flash-container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow d-flex align-items-center rounded-3 border-0" role="alert">
            <i class="bi bi-check-circle-fill fs-5 me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow d-flex align-items-center rounded-3 border-0" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show shadow d-flex align-items-center rounded-3 border-0" role="alert">
            <i class="bi bi-info-circle-fill fs-5 me-2"></i>
            <div>{{ session('info') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow rounded-3 border-0" role="alert">
            <div class="fw-bold mb-1"><i class="bi bi-shield-exclamation me-1"></i>يرجى تصحيح الأخطاء التالية:</div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li class="small">{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>
@endif

{{-- =====================================================================
     MAIN CONTENT
===================================================================== --}}
<div class="content-wrapper">
    @yield('content')
</div>

@if(!request()->routeIs('login') && !request()->routeIs('register'))
{{-- =====================================================================
     FOOTER
===================================================================== --}}
<footer class="site-footer pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <img src="/images/logo.png"
                         onerror="this.onerror=null;this.src='/images/eduyemen-logo.svg';"
                         style="width:40px;height:40px;object-fit:contain;background:transparent;filter:drop-shadow(0 2px 8px rgba(0,0,0,0.3));"
                         alt="EduYemen">
                    <span style="font-size:1.1rem;font-weight:800;">
                        <span style="color:#fff;">Edu</span><span style="color:var(--accent-light);">Yemen</span>
                    </span>
                </div>
                <p class="small lh-lg" style="color:#94a3b8;max-width:300px;">
                    المنصة الإلكترونية المركزية الموحدة التي تربط الطلاب بالجامعات الحكومية والأهلية في الجمهورية اليمنية.
                </p>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-sm btn-outline-secondary rounded-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="btn btn-sm btn-outline-secondary rounded-3"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="btn btn-sm btn-outline-secondary rounded-3"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-3 col-6">
                <h6 class="fw-bold mb-3">روابط سريعة</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ route('home') }}"><i class="bi bi-chevron-left me-1"></i>الرئيسية</a></li>
                    <li class="mb-2"><a href="{{ route('universities.index') }}"><i class="bi bi-chevron-left me-1"></i>دليل الجامعات</a></li>
                    <li class="mb-2"><a href="{{ route('compare') }}"><i class="bi bi-chevron-left me-1"></i>مقارنة الجامعات</a></li>
                    <li class="mb-2"><a href="{{ route('ai.advisor') }}"><i class="bi bi-chevron-left me-1"></i>المساعد الذكي</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-3 col-6">
                <h6 class="fw-bold mb-3">للطلاب</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="{{ route('register') }}"><i class="bi bi-chevron-left me-1"></i>إنشاء حساب</a></li>
                    <li class="mb-2"><a href="{{ route('login') }}"><i class="bi bi-chevron-left me-1"></i>تسجيل الدخول</a></li>
                    <li class="mb-2"><a href="{{ route('universities.index') }}"><i class="bi bi-chevron-left me-1"></i>ابحث عن جامعة</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-6">
                <h6 class="fw-bold mb-3">تواصل معنا</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2 d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt-fill text-accent"></i>
                        <span>الجمهورية اليمنية — أمانة العاصمة صنعاء</span>
                    </li>
                    <li class="mb-2 d-flex align-items-center gap-2">
                        <i class="bi bi-envelope-fill text-accent"></i>
                        <a href="mailto:info@eduyemen.ye">info@eduyemen.ye</a>
                    </li>
                    <li class="mb-2 d-flex align-items-center gap-2">
                        <i class="bi bi-telephone-fill text-accent"></i>
                        <span dir="ltr">+967 1 234 567</span>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="footer-divider">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-2">
            <p class="small mb-1 mb-md-0" style="color:#64748b;">
                &copy; {{ date('Y') }} منصة <strong style="color:#fff;">EduYemen</strong> — جميع الحقوق محفوظة
            </p>
            <p class="small mb-0" style="color:#64748b;">مشروع تخرج جامعي — الجمهورية اليمنية</p>
        </div>
    </div>
</footer>
@endif
</div> <!-- /#appPageWrapper -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Auto-dismiss flash after 5s
    setTimeout(() => {
        document.querySelectorAll('.flash-container .alert').forEach(el => {
            try { new bootstrap.Alert(el).close(); } catch(e) {}
        });
    }, 5000);
</script>
@if(!request()->routeIs('login') && !request()->routeIs('register'))
    @if(Auth::check() && Auth::user()->isUniversity())
        @include('partials.sidebar_drawer_university')
    @else
        @include('partials.sidebar_drawer')
    @endif
    
@endif

<script>
    function toggleSidebar() {
        const drawer = document.getElementById('sidebarDrawer');
        if (drawer && drawer.classList.contains('open')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    }

    function openSidebar() {
        const drawer = document.getElementById('sidebarDrawer');
        const overlay = document.getElementById('sidebarOverlay');
        if (drawer) drawer.classList.add('open');
        if (overlay) overlay.classList.add('active');
        document.body.classList.add('sidebar-open');
    }

    function closeSidebar() {
        const drawer = document.getElementById('sidebarDrawer');
        const overlay = document.getElementById('sidebarOverlay');
        const subDrawer = document.getElementById('univSubDrawer');
        if (drawer) drawer.classList.remove('open');
        if (overlay) overlay.classList.remove('active');
        if (subDrawer) subDrawer.classList.remove('open');
        const btn = document.querySelector('.btn-toggle-univ-subdrawer');
        if (btn) btn.classList.remove('active');
        document.body.classList.remove('sidebar-open');
    }

    // فتح وإغلاق القائمة الجانبية الفرعية للجامعات بجانب القائمة الرئيسية
    function toggleUnivSubDrawer(event) {
        if (event) event.stopPropagation();
        const subDrawer = document.getElementById('univSubDrawer');
        const btn = document.querySelector('.btn-toggle-univ-subdrawer');
        if (!subDrawer) return;

        const isOpen = subDrawer.classList.contains('open');
        if (isOpen) {
            subDrawer.classList.remove('open');
            if (btn) btn.classList.remove('active');
        } else {
            subDrawer.classList.add('open');
            if (btn) btn.classList.add('active');
        }
    }

    function closeUnivSubsidebar(event) {
        if (event) event.stopPropagation();
        const subDrawer = document.getElementById('univSubDrawer');
        const btn = document.querySelector('.btn-toggle-univ-subdrawer');
        if (subDrawer) subDrawer.classList.remove('open');
        if (btn) btn.classList.remove('active');
    }

    // إغلاق بمفتاح Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeSidebar();
    });

    // تبديل وتطبيق المظهر وتخزينه
    window.applyEduYemenTheme = function(themeMode) {
        var effective = (themeMode === 'dark') ? 'dark' : 'light';
        localStorage.setItem('eduyemen_theme', themeMode);
        document.documentElement.setAttribute('data-theme', effective);
        document.documentElement.setAttribute('data-theme-setting', themeMode);
    };

    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            // المظهر الافتراضي للمنصة هو النهاري دائماً ولا يتأثر بالنظام الداكن للجهاز
            if (document.documentElement.getAttribute('data-theme-setting') !== 'dark') {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        });
    }
</script>

@yield('scripts')
</body>
</html>
