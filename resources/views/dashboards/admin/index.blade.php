@extends('dashboards.admin.layout')

@section('page_title', 'الرئيسية')

@section('breadcrumb')
    <span>الرئيسية /</span>
    <strong>لوحة القيادة والمتابعة العامة</strong>
@endsection

@section('content')

<style>
    /* ==========================================================
       1. UNIFIED NATIONAL SHOWCASE HEADER (الحاوية الأولى)
       لوحة القيادة العامة والترحيب بمدير النظام
       ========================================================== */
    .admin-showcase-header {
        background: linear-gradient(135deg, #072b4f 0%, #0b3d6b 55%, #082847 100%);
        border-radius: var(--radius-lg);
        padding: 26px 30px;
        color: #ffffff;
        box-shadow: 0 10px 28px rgba(7, 43, 79, 0.16);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(197, 155, 39, 0.35);
        border-right: 5px solid var(--accent);
        margin-bottom: 24px;
    }

    .showcase-header-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .showcase-brand-group {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .showcase-logo-box {
        width: 68px;
        height: 68px;
        border-radius: 16px;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px;
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.26), 0 0 0 2px rgba(197, 155, 39, 0.5);
        flex-shrink: 0;
        overflow: hidden;
        transition: transform .25s ease;
    }

    .showcase-logo-box:hover {
        transform: scale(1.05);
    }

    .showcase-logo-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .showcase-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(197, 155, 39, 0.2);
        color: #f0c842;
        border: 1px solid rgba(197, 155, 39, 0.4);
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        width: fit-content;
        margin-bottom: 3px;
    }

    .showcase-system-name {
        font-size: 26px;
        font-weight: 900;
        margin: 1px 0;
        display: flex;
        align-items: center;
        gap: 2px;
        letter-spacing: -0.3px;
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
    }

    .showcase-sub {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.85);
        margin: 0;
        font-weight: 500;
    }

    .btn-hero {
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(8px);
        padding: 8px 16px;
        border-radius: 12px;
        font-size: 12.5px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.22s ease;
    }

    .btn-hero:hover {
        background: #ffffff;
        color: var(--primary-dark);
        border-color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    }

    .btn-hero-accent {
        background: linear-gradient(135deg, var(--accent) 0%, #aa841e 100%);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.35);
        padding: 8px 18px;
        border-radius: 12px;
        font-size: 12.5px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 6px 18px rgba(197, 155, 39, 0.4);
        transition: all 0.22s ease;
    }

    .btn-hero-accent:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(197, 155, 39, 0.55);
        background: linear-gradient(135deg, #d4a72d 0%, #b88d22 100%);
        color: #ffffff;
    }

    .hero-action-buttons {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .hero-action-buttons {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 10px !important;
            width: 100% !important;
        }
        .hero-action-buttons > :nth-child(1),
        .hero-action-buttons > :nth-child(2) {
            grid-column: span 1 !important;
            justify-content: center !important;
            text-align: center !important;
        }
        .hero-action-buttons > :nth-child(3) {
            grid-column: 1 / -1 !important;
            justify-self: center !important; /* في منتصف الصف الثاني وليس بدايته */
            margin: 0 auto !important;
            display: inline-flex !important;
            justify-content: center !important;
            width: fit-content !important;
        }
    }

    /* ==========================================================
       3D PRESENTATION THEATER FOR ADMIN RESPONSIBILITIES
       (مسرح العرض التقديمي ثلاثي الأبعاد لمهام مدير النظام في قسم حول)
       عروض متحركة تجذب الانتباه - حركات ثلاثية أبعاد - شكل ولون مميز عن الروابط
       ========================================================== */
    .showcase-presentation-theater {
        background: linear-gradient(135deg, rgba(4, 18, 36, 0.85) 0%, rgba(9, 32, 60, 0.75) 100%);
        border: 1px solid rgba(240, 200, 66, 0.35);
        border-radius: 18px;
        padding: 16px 20px 14px 20px;
        margin-bottom: 22px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.28), inset 0 1px 0 rgba(255, 255, 255, 0.15);
        perspective: 1200px;
        touch-action: pan-y;
        user-select: none;
    }

    .showcase-presentation-theater::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, #f0c842, #38bdf8, #f0c842, transparent);
    }

    /* شريط النقاط في أسفل الحاوية */
    .stage-bottom-bar {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 12px;
        padding-top: 4px;
    }

    .stage-indicators {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .stage-dot {
        width: 14px;
        height: 6px;
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.25);
        cursor: pointer;
        transition: all 0.28s ease;
    }

    .stage-dot:hover {
        background: rgba(240, 200, 66, 0.65);
        transform: scale(1.15);
    }

    .stage-dot.active {
        width: 32px;
        background: #f0c842;
        box-shadow: 0 0 10px rgba(240, 200, 66, 0.7);
    }

    /* منطقة عرض البطاقة */
    .stage-viewport {
        position: relative;
        min-height: 110px;
        display: flex;
        align-items: center;
    }

    /* تصميم البطاقة ثلاثية الأبعاد - لون وشكل متميز تماماً عن الروابط */
    .presentation-card {
        width: 100%;
        display: none;
        background: linear-gradient(135deg, rgba(6, 24, 48, 0.96) 0%, rgba(13, 42, 77, 0.92) 50%, rgba(8, 26, 52, 0.98) 100%);
        border: 1.5px solid rgba(240, 200, 66, 0.5);
        border-radius: 16px;
        padding: 16px 22px;
        position: relative;
        box-shadow: 0 16px 38px rgba(0, 0, 0, 0.4), inset 0 1px 1px rgba(255, 255, 255, 0.25), 0 0 24px rgba(240, 200, 66, 0.15);
        transform-style: preserve-3d;
        overflow: hidden;
    }

    /* حركة الظهور والانتقال الثلاثي الأبعاد */
    .presentation-card.active {
        display: block;
        animation: pcardEnter3D 0.75s cubic-bezier(0.2, 0.9, 0.2, 1) forwards, pcardFloatingPresentation 5.5s ease-in-out infinite 0.75s;
    }

    .presentation-card.exit-stage {
        display: block;
        animation: pcardExit3D 0.55s cubic-bezier(0.8, 0.05, 0.8, 0.2) forwards;
    }

    @keyframes pcardEnter3D {
        0% {
            opacity: 0;
            transform: perspective(1200px) rotateY(-30deg) rotateX(15deg) translate3d(70px, 15px, -120px) scale(0.92);
            filter: blur(8px);
        }
        70% {
            opacity: 1;
            transform: perspective(1200px) rotateY(3deg) rotateX(-2deg) translate3d(-4px, 0, 8px) scale(1.02);
            filter: blur(0);
        }
        100% {
            opacity: 1;
            transform: perspective(1200px) rotateY(0deg) rotateX(0deg) translate3d(0, 0, 0) scale(1);
        }
    }

    @keyframes pcardFloatingPresentation {
        0%, 100% {
            transform: perspective(1200px) translateY(0px) rotateX(0deg) rotateY(0deg);
        }
        50% {
            transform: perspective(1200px) translateY(-5px) rotateX(2.5deg) rotateY(-1.8deg);
        }
    }

    @keyframes pcardExit3D {
        0% {
            opacity: 1;
            transform: perspective(1200px) rotateY(0deg) translate3d(0, 0, 0) scale(1);
            filter: blur(0);
        }
        100% {
            opacity: 0;
            transform: perspective(1200px) rotateY(35deg) rotateX(-12deg) translate3d(-90px, -10px, -160px) scale(0.88);
            filter: blur(8px);
        }
    }

    .pcard-inner {
        display: flex;
        align-items: center;
        gap: 18px;
        position: relative;
        z-index: 2;
    }

    .pcard-3d-sphere {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
        position: relative;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35);
        transform-style: preserve-3d;
        animation: sphereOrbit3D 6s ease-in-out infinite;
    }

    @keyframes sphereOrbit3D {
        0%, 100% { transform: rotateY(0deg) scale(1); }
        50% { transform: rotateY(180deg) scale(1.08); }
    }

    .card-style-1 .pcard-3d-sphere { background: linear-gradient(135deg, #f59e0b, #d97706); color: #ffffff; }
    .card-style-2 .pcard-3d-sphere { background: linear-gradient(135deg, #10b981, #059669); color: #ffffff; }
    .card-style-3 .pcard-3d-sphere { background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: #ffffff; }
    .card-style-4 .pcard-3d-sphere { background: linear-gradient(135deg, #8b5cf6, #6d28d9); color: #ffffff; }

    .pcard-body {
        flex: 1;
        min-width: 0;
    }

    .pcard-tag {
        font-size: 11px;
        font-weight: 800;
        color: #f0c842;
        margin-bottom: 2px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .pcard-title {
        font-size: 15px;
        font-weight: 900;
        color: #ffffff;
        margin: 0 0 3px 0;
        letter-spacing: -0.2px;
    }

    .pcard-desc {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.85);
        margin: 0;
        line-height: 1.4;
    }

    .pcard-pill-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #ffffff;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        flex-shrink: 0;
        white-space: nowrap;
    }

    @media (max-width: 768px) {
        .pcard-inner {
            flex-wrap: wrap;
            gap: 12px;
        }
        .pcard-pill-status {
            display: none;
        }
        .pcard-title {
            font-size: 13.5px;
        }
        .pcard-desc {
            font-size: 11px;
        }
    }

    /* ==========================================================
       SYSTEM LIVE KPI CARDS (بطاقات معلومات النظام في الحاوية الأولى)
       بطاقتين في صف واحد على شاشات الهاتف
       ========================================================== */
    .showcase-cards-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 18px;
    }

    @media (max-width: 1100px) {
        .showcase-cards-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 640px) {
        .showcase-cards-grid {
            grid-template-columns: repeat(2, 1fr) !important; /* بطاقتين في صف واحد على شاشة الهاتف */
            gap: 10px;
        }
        .showcase-kpi-card {
            padding: 12px 12px;
        }
        .showcase-card-num {
            font-size: 22px !important;
        }
        .showcase-card-title {
            font-size: 11.5px !important;
        }
        .showcase-card-footer {
            font-size: 10px !important;
        }
        .showcase-card-icon {
            width: 30px !important;
            height: 30px !important;
            font-size: 13px !important;
        }
    }

    .showcase-kpi-card {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: var(--radius-md);
        padding: 15px 18px;
        transition: transform .2s ease, background .2s ease, border-color .2s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        color: #ffffff;
        text-decoration: none;
    }

    .showcase-kpi-card:hover {
        transform: translateY(-3px);
        background: rgba(255, 255, 255, 0.14);
        border-color: rgba(197, 155, 39, 0.5);
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.2);
    }

    .showcase-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
    }

    .showcase-card-title {
        font-size: 13px;
        font-weight: 700;
        color: rgba(255, 255, 255, 0.88);
    }

    .showcase-card-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: rgba(197, 155, 39, 0.2);
        border: 1px solid rgba(197, 155, 39, 0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f0c842;
        font-size: 14px;
    }

    .showcase-card-num {
        font-size: 26px;
        font-weight: 900;
        color: #ffffff;
        line-height: 1.1;
        margin-bottom: 8px;
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
    }

    .showcase-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 11.5px;
        color: rgba(255, 255, 255, 0.75);
        padding-top: 8px;
        border-top: 1px dashed rgba(255, 255, 255, 0.15);
    }

    .showcase-telemetry {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
        padding-top: 14px;
        border-top: 1px solid rgba(255, 255, 255, 0.12);
        font-size: 12px;
        color: rgba(255, 255, 255, 0.8);
    }

    .showcase-telemetry i {
        color: #f0c842;
    }

    .showcase-telemetry strong {
        color: #ffffff;
    }

    /* ==========================================================
       2. ACTION NOTICE STRIP (المهام العاجلة بانتظار القرار)
       ========================================================== */
    .action-strip {
        background: #ffffff;
        border-radius: var(--radius-md);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 24px;
    }

    .action-strip-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .action-badge-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .action-badge-warning { background: #fffbeb; color: #d97706; border: 1px solid #fef3c7; }
    .action-badge-danger  { background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2; }
    .action-badge-info    { background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; }

    /* ==========================================================
       3. QUICK ACTION TILES (بطاقات الروابط السريعة)
       شكل وطابع بصري مختلف كلياً عن بطاقات معلومات النظام
       ========================================================== */
    .quick-access-section {
        margin-bottom: 24px;
    }

    .quick-access-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 12px;
    }

    @media (max-width: 1100px) {
        .quick-access-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 640px) {
        .quick-access-grid {
            grid-template-columns: repeat(2, 1fr) !important; /* بطاقتين في الصف على الهاتف */
            gap: 8px;
        }
        .quick-access-grid .quick-action-tile:nth-child(5) {
            grid-column: 1 / -1; /* البطاقة الخامسة تمتد بكامل العرض في المنتصف */
        }
    }

    .quick-action-tile {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 12px 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        color: var(--primary-dark);
        box-shadow: 0 2px 6px rgba(11, 61, 107, 0.04);
        transition: all 0.22s cubic-bezier(0.2, 0.8, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .quick-action-tile::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        height: 3px;
        background: transparent;
        transition: background 0.22s ease;
    }

    .quick-action-tile.tile-navy::before   { background: var(--primary); }
    .quick-action-tile.tile-amber::before  { background: #d97706; }
    .quick-action-tile.tile-gold::before   { background: var(--accent); }
    .quick-action-tile.tile-blue::before   { background: #2563eb; }
    .quick-action-tile.tile-purple::before { background: #7c3aed; }
    .quick-action-tile.tile-slate::before  { background: #475569; }

    .quick-action-tile:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(11, 61, 107, 0.1);
        border-color: var(--primary-light);
    }

    .quick-tile-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }

    .tile-navy .quick-tile-icon   { background: var(--primary-subtle); color: var(--primary); }
    .tile-amber .quick-tile-icon  { background: #fef3c7; color: #b45309; }
    .tile-gold .quick-tile-icon   { background: var(--accent-subtle); color: #8a6a12; }
    .tile-blue .quick-tile-icon   { background: #eff6ff; color: #1d4ed8; }
    .tile-purple .quick-tile-icon { background: #f5f3ff; color: #7c3aed; }
    .tile-slate .quick-tile-icon  { background: #f1f5f9; color: #475569; }

    .quick-tile-info {
        display: flex;
        flex-direction: column;
        min-width: 0;
        flex: 1;
    }

    .quick-tile-info strong {
        font-size: 12px;
        font-weight: 800;
        color: var(--primary-dark);
        line-height: 1.35;
        white-space: normal;
        word-break: normal;
    }

    .quick-tile-info small {
        font-size: 10.5px;
        color: var(--ink-muted);
        line-height: 1.25;
        margin-top: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .quick-tile-arrow {
        font-size: 10px;
        color: var(--ink-light);
        transition: transform 0.2s ease, color 0.2s ease;
        flex-shrink: 0;
    }

    .quick-action-tile:hover .quick-tile-arrow {
        transform: translateX(-3px);
        color: var(--primary);
    }

    /* ==========================================================
       4. OPERATIONAL WIDGET WITH FLOATING TITLE & INTEGRATED TABS
       (مؤشرات النظام - ترويسة عائمة في المنتصف وقائمة تنقل مدمجة بدون فجوة بصرية)
       ========================================================== */
    .ops-unified-widget {
        position: relative;
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: var(--radius-lg, 16px);
        box-shadow: 0 4px 20px rgba(11, 61, 107, 0.06);
        margin-top: 36px; /* مسافة كافية لبروز نصف الترويسة العائمة للأعلى خارج الحاوية */
        margin-bottom: 28px;
        overflow: visible;
    }

    /* العنوان في ترويسة الحاوية في المنتصف: نصف في الحاوية من الداخل ونصف إلى الخارج من الأعلى */
    .ops-floating-title {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translate(-50%, -50%);
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: #ffffff;
        padding: 8px 26px;
        border-radius: 30px;
        font-size: 14.5px;
        font-weight: 800;
        box-shadow: 0 6px 18px rgba(11, 61, 107, 0.25);
        border: 3.5px solid #ffffff;
        display: inline-flex;
        align-items: center;
        gap: 9px;
        z-index: 10;
        white-space: nowrap;
        letter-spacing: -0.2px;
    }

    .ops-nav-bar-integrated {
        padding: 26px 20px 14px 20px;
        background: linear-gradient(to bottom, #ffffff, #fcfdfd);
        border-bottom: 1px solid var(--border-light);
        border-radius: 16px 16px 0 0;
    }

    .ops-tabs-strip {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        max-width: 860px;
        margin: 0 auto;
        background: #f1f5f9;
        padding: 6px;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
    }

    .ops-tab-item {
        background: transparent;
        border: 1px solid transparent;
        border-radius: 10px;
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.22s cubic-bezier(0.2, 0.8, 0.2, 1);
        cursor: pointer;
        text-decoration: none;
        user-select: none;
        outline: none;
        font-family: inherit;
    }

    .ops-tab-item:hover:not(.active) {
        background: #ffffff;
        color: var(--primary);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        border-color: #cbd5e1;
    }

    .ops-tab-item.active {
        background: linear-gradient(135deg, var(--primary), #0d4b82);
        color: #ffffff;
        border-color: var(--primary);
        box-shadow: 0 4px 14px rgba(11, 61, 107, 0.26);
    }

    .ops-tab-item.active i {
        color: var(--accent);
    }

    .ops-tab-counter {
        background: #e2e8f0;
        color: #475569;
        font-size: 11px;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 10px;
        transition: all 0.2s;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .ops-tab-item.active .ops-tab-counter {
        background: rgba(255, 255, 255, 0.24);
        color: #ffffff;
    }

    @media (max-width: 640px) {
        .ops-tabs-strip {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            padding: 6px;
            max-width: 100%;
        }
        .ops-tab-item {
            padding: 9px 8px;
            font-size: 11px;
            gap: 6px;
        }
        /* في الشاشة الصغيرة: عنصرين بالصف الأول والعنصر الثالث تحتهما في المنتصف */
        .ops-tab-item:nth-child(1) {
            grid-column: 1 / 2;
        }
        .ops-tab-item:nth-child(2) {
            grid-column: 2 / 3;
        }
        .ops-tab-item:nth-child(3) {
            grid-column: 1 / -1;
            width: 85%;
            margin: 0 auto;
            justify-self: center;
        }
        .ops-floating-title {
            font-size: 13px;
            padding: 6px 18px;
        }
    }

    /* دمج محتوى الحاوية مع قائمة التنقل مباشرة وبدون أي فجوة بصرية */
    .ops-unified-content .ops-pane {
        border: none !important;
        box-shadow: none !important;
        margin-bottom: 0 !important;
        background: transparent !important;
        border-radius: 0 0 16px 16px !important;
    }

    .ops-unified-content .card-header {
        background: #fbfcfd;
        border-bottom: 1px solid var(--border-light);
        padding: 12px 20px;
    }

    /* ==========================================================
       5. ADAPTIVE CONTAINER SIZING & SCROLLBAR (التحكم الذكي بالحجم)
       صغيرة مع العناصر القليلة، وتتسع لـ 6 عناصر، وسكرول بار مع الزيادة
       ========================================================== */
    .adaptive-scroll-wrapper {
        max-height: 420px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }

    .adaptive-scroll-wrapper::-webkit-scrollbar {
        width: 6px;
    }

    .adaptive-scroll-wrapper::-webkit-scrollbar-track {
        background: transparent;
    }

    .adaptive-scroll-wrapper::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .adaptive-scroll-wrapper::-webkit-scrollbar-thumb:hover {
        background: var(--accent);
    }

    .adaptive-table-scroll {
        max-height: 380px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }

    .adaptive-table-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .adaptive-table-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    .adaptive-table-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .adaptive-table-scroll::-webkit-scrollbar-thumb:hover {
        background: var(--accent);
    }

    .adaptive-table-scroll table thead th {
        position: sticky;
        top: 0;
        background: var(--surface-alt);
        z-index: 5;
    }

    /* Animated Tab Panes */
    .ops-pane {
        animation: fadeInPane 0.25s ease-in-out;
    }

    @keyframes fadeInPane {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<!-- ============================================================
     1. لوحة القيادة العامة والترحيب بمدير النظام (الحاوية الأولى)
     ============================================================ -->
<div class="admin-showcase-header">
    <div class="showcase-header-top">
        <div class="showcase-brand-group">
            <div class="showcase-logo-box">
                <img src="{{ asset('logo.png') }}" 
                     onerror="this.onerror=null; this.src='{{ asset('images/logo.png') }}';" 
                     alt="EduYemen Logo">
            </div>
            <div>
                <div class="showcase-badge">
                    <i class="fa-solid fa-shield-halved"></i>
                    المنظومة الوطنية الموحدة
                </div>
                <h1 class="showcase-system-name" dir="ltr" style="direction:ltr;unicode-bidi:isolate;font-family:'Plus Jakarta Sans',system-ui,sans-serif;">
                    <span style="color:#f0c842;font-weight:900;">Edu</span><span style="color:#ffffff;font-weight:900;">Yemen</span>
                </h1>
                <p class="showcase-sub">المنظومة الوطنية الموحدة للتنسيق والقبول المركزي للجامعات اليمنية</p>
            </div>
        </div>

        <div class="hero-action-buttons">
            <a href="{{ route('admin.universities', ['action' => 'add']) }}" class="btn-hero-accent">
                <i class="fa-solid fa-circle-plus"></i>
                <span>إضافة جامعة جديدة</span>
            </a>
            <a href="{{ route('admin.requests') }}" class="btn-hero">
                <i class="fa-solid fa-clipboard-check"></i>
                <span>متابعة الطلبات</span>
            </a>
            <a href="{{ route('admin.reports') }}" class="btn-hero">
                <i class="fa-solid fa-chart-pie"></i>
                <span>التقارير الشاملة</span>
            </a>
        </div>
    </div>

    <!-- مسرح العرض التقديمي ثلاثي الأبعاد لمهام مدير النظام (حركات 3D تفاعلية - شكل ولون متميز عن الروابط) -->
    <div class="showcase-presentation-theater" id="adminPresentationStage">
        <!-- مساحة مسرح العرض ثلاثي الأبعاد -->
        <div class="stage-viewport" id="stageViewport">
            <!-- البطاقة 1: اعتماد الجامعات -->
            <div class="presentation-card card-style-1 active" data-index="0">
                <div class="pcard-inner">
                    <div class="pcard-3d-sphere">
                        <i class="fa-solid fa-landmark-dome"></i>
                    </div>
                    <div class="pcard-body">
                        <div class="pcard-tag">
                            <i class="fa-solid fa-building-columns"></i>
                            اعتماد وتوثيق المؤسسات الجامعية
                        </div>
                        <h3 class="pcard-title">فحص التراخيص ومعايير الاعتماد الأكاديمي</h3>
                        <p class="pcard-desc">مطابقة معايير الجامعات وربط كلياتها وتخصصاتها بالبوابة المركزية.</p>
                    </div>
                    <div class="pcard-pill-status">
                        <i class="fa-solid fa-certificate" style="color:#f0c842;"></i>
                        <span>حوكمة أكاديمية مركزية</span>
                    </div>
                </div>
            </div>

            <!-- البطاقة 2: تدقيق الإعلانات والمدفوعات -->
            <div class="presentation-card card-style-2" data-index="1">
                <div class="pcard-inner">
                    <div class="pcard-3d-sphere">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <div class="pcard-body">
                        <div class="pcard-tag">
                            <i class="fa-solid fa-bullhorn"></i>
                            تدقيق الحملات والمدفوعات
                        </div>
                        <h3 class="pcard-title">مراجعة الإعلانات والتحقق المالي</h3>
                        <p class="pcard-desc">فحص سندات الإيداع المالي ورسوم الباقات قبل نشر الإعلانات رسمياً.</p>
                    </div>
                    <div class="pcard-pill-status">
                        <i class="fa-solid fa-shield-check" style="color:#10b981;"></i>
                        <span>رقابة مالية وترويجية</span>
                    </div>
                </div>
            </div>

            <!-- البطاقة 3: القبول والمفاضلة -->
            <div class="presentation-card card-style-3" data-index="2">
                <div class="pcard-inner">
                    <div class="pcard-3d-sphere">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </div>
                    <div class="pcard-body">
                        <div class="pcard-tag">
                            <i class="fa-solid fa-users-gear"></i>
                            المفاضلة والقبول المركزي
                        </div>
                        <h3 class="pcard-title">متابعة تدفق طلبات الالتحاق والعمولات</h3>
                        <p class="pcard-desc">الإشراف على معايير القبول ونسب المفاضلة وصرف مكافآت الإحالات بنجاح.</p>
                    </div>
                    <div class="pcard-pill-status">
                        <i class="fa-solid fa-bolt-lightning" style="color:#38bdf8;"></i>
                        <span>معالجة آنية ولحظية</span>
                    </div>
                </div>
            </div>

            <!-- البطاقة 4: أمان الحسابات -->
            <div class="presentation-card card-style-4" data-index="3">
                <div class="pcard-inner">
                    <div class="pcard-3d-sphere">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <div class="pcard-body">
                        <div class="pcard-tag">
                            <i class="fa-solid fa-lock"></i>
                            أمان المنظومة والحسابات
                        </div>
                        <h3 class="pcard-title">حوكمة الحسابات وحماية خصوصية البيانات</h3>
                        <p class="pcard-desc">ضبط الصلاحيات القيادية وحماية خصوصية بيانات الطلاب والجامعات.</p>
                    </div>
                    <div class="pcard-pill-status">
                        <i class="fa-solid fa-shield-halved" style="color:#c084fc;"></i>
                        <span>أمان وتشفير قيادي</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- نقاط التنقل السفلية -->
        <div class="stage-bottom-bar">
            <div class="stage-indicators" id="stageIndicators">
                <span class="stage-dot active" onclick="jumpToStageCard(0)" title="المهمة 1"></span>
                <span class="stage-dot" onclick="jumpToStageCard(1)" title="المهمة 2"></span>
                <span class="stage-dot" onclick="jumpToStageCard(2)" title="المهمة 3"></span>
                <span class="stage-dot" onclick="jumpToStageCard(3)" title="المهمة 4"></span>
            </div>
        </div>
    </div>

    <!-- بطاقات معلومات النظام (Live KPIs) - تظهر بطاقتين بالصف على شاشات الهاتف -->
    <div class="showcase-cards-grid">
        <!-- الجامعات المعتمدة -->
        <a href="{{ route('admin.universities') }}" class="showcase-kpi-card">
            <div class="showcase-card-head">
                <span class="showcase-card-title">منظومة الجامعات</span>
                <div class="showcase-card-icon">
                    <i class="fa-solid fa-landmark-dome"></i>
                </div>
            </div>
            <div class="showcase-card-num">{{ $stats['total_universities'] }}</div>
            <div class="showcase-card-footer">
                <span>{{ $stats['active_universities'] }} جامعة معتمدة ونشطة</span>
                <i class="fa-solid fa-arrow-left"></i>
            </div>
        </a>

        <!-- طلبات الالتحاق والقبول -->
        <a href="{{ route('admin.requests') }}" class="showcase-kpi-card">
            <div class="showcase-card-head">
                <span class="showcase-card-title">طلبات الالتحاق</span>
                <div class="showcase-card-icon">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
            </div>
            <div class="showcase-card-num">{{ number_format($stats['total_requests']) }}</div>
            <div class="showcase-card-footer">
                <span>{{ $stats['pending_requests'] }} طلب قيد مراجعة القبول</span>
                <i class="fa-solid fa-arrow-left"></i>
            </div>
        </a>

        <!-- الإعلانات والتدقيق المالي -->
        <a href="{{ route('admin.advertisements') }}" class="showcase-kpi-card">
            <div class="showcase-card-head">
                <span class="showcase-card-title">إدارة الإعلانات</span>
                <div class="showcase-card-icon">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
            </div>
            <div class="showcase-card-num">{{ $stats['total_ads'] }}</div>
            <div class="showcase-card-footer">
                <span>{{ $stats['pending_ads'] > 0 ? $stats['pending_ads'] . ' بانتظار الاعتماد المالي' : 'جميع الإعلانات معتمدة' }}</span>
                <i class="fa-solid fa-arrow-left"></i>
            </div>
        </a>

        <!-- الطلاب والإحالات -->
        <a href="{{ route('admin.referrals') }}" class="showcase-kpi-card">
            <div class="showcase-card-head">
                <span class="showcase-card-title">إدارة الإحالات</span>
                <div class="showcase-card-icon">
                    <i class="fa-solid fa-diagram-project"></i>
                </div>
            </div>
            <div class="showcase-card-num">{{ number_format($stats['total_students']) }}</div>
            <div class="showcase-card-footer">
                <span>{{ $pendingCommissions }} عمولة مستحقة الصرف</span>
                <i class="fa-solid fa-arrow-left"></i>
            </div>
        </a>
    </div>

    <div class="showcase-telemetry">
        <div>
            <i class="fa-solid fa-circle-check" style="color:#10b981;"></i>
            <span>حالة النظام: <strong>متصل ومستقر</strong></span>
        </div>
        <div>
            <i class="fa-solid fa-database"></i>
            <span>قاعدة البيانات: <strong>متزامنة لحظياً</strong></span>
        </div>
        <div>
            <i class="fa-solid fa-calendar-check"></i>
            <span>الإصدار المعتمد: <strong>Edition 2026</strong></span>
        </div>
        <div>
            <i class="fa-solid fa-headset"></i>
            <span>الدعم الفني للإدارة: <strong>جاهز 24/7</strong></span>
        </div>
    </div>
</div>

<!-- ============================================================
     2. المهام العاجلة بانتظار اتخاذ القرار (الحاوية الثانية)
     ============================================================ -->
@if($pendingAds > 0 || $pendingCommissions > 0 || $stats['pending_requests'] > 0)
<div class="action-strip">
    <div style="display:flex;align-items:center;gap:12px;">
        <span style="font-weight:800;font-size:14px;color:var(--primary-dark);display:flex;align-items:center;gap:8px;">
            <i class="fa-solid fa-bell-concierge" style="color:var(--accent);font-size:16px;"></i>
            المهام العاجلة بانتظار اتخاذ القرار:
        </span>
    </div>

    <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
        @if($pendingAds > 0)
        <a href="{{ route('admin.advertisements', ['review_status' => 'قيد المراجعة']) }}" class="action-strip-item" style="text-decoration:none;">
            <div class="action-badge-icon action-badge-warning"><i class="fa-solid fa-bullhorn"></i></div>
            <div>
                <div style="font-weight:700;font-size:13px;color:#92400e;">{{ $pendingAds }} إعلان بانتظار الاعتماد</div>
                <div style="font-size:11px;color:var(--ink-muted);">راجع سندات الإيداع ومحتوى الإعلان</div>
            </div>
            <i class="fa-solid fa-chevron-left" style="font-size:11px;color:#b45309;"></i>
        </a>
        @endif

        @if($pendingCommissions > 0)
        <a href="{{ route('admin.referrals', ['status' => 'مكتملة']) }}" class="action-strip-item" style="text-decoration:none;">
            <div class="action-badge-icon action-badge-danger"><i class="fa-solid fa-hand-holding-dollar"></i></div>
            <div>
                <div style="font-weight:700;font-size:13px;color:#991b1b;">{{ $pendingCommissions }} عمولة إحالة مستحقة</div>
                <div style="font-size:11px;color:var(--ink-muted);">تأكيد تسجيل الطلاب وصرف المبالغ</div>
            </div>
            <i class="fa-solid fa-chevron-left" style="font-size:11px;color:#dc2626;"></i>
        </a>
        @endif

        @if($stats['pending_requests'] > 0)
        <a href="{{ route('admin.requests', ['status' => 'قيد المراجعة']) }}" class="action-strip-item" style="text-decoration:none;">
            <div class="action-badge-icon action-badge-info"><i class="fa-solid fa-clock-rotate-left"></i></div>
            <div>
                <div style="font-weight:700;font-size:13px;color:#1e40af;">{{ $stats['pending_requests'] }} طلب تسجيل قيد المعالجة</div>
                <div style="font-size:11px;color:var(--ink-muted);">متابعة لجان القبول بالجامعات</div>
            </div>
            <i class="fa-solid fa-chevron-left" style="font-size:11px;color:#2563eb;"></i>
        </a>
        @endif
    </div>
</div>
@endif

<!-- ============================================================
     3. روابط الإجراءات السريعة (الحاوية الثالثة)
     بطاقات إجرائية تفاعلية مميزة ومختلفة كلياً عن بطاقات معلومات النظام
     ============================================================ -->
<div class="quick-access-section">
    <div style="font-size:13.5px;font-weight:800;color:var(--primary-dark);margin-bottom:12px;display:flex;align-items:center;gap:8px;">
        <i class="fa-solid fa-compass" style="color:var(--accent);"></i>
        روابط الإجراءات السريعة لأقسام الإدارة:
    </div>
    <div class="quick-access-grid">
        <a href="{{ route('admin.universities') }}" class="quick-action-tile tile-navy">
            <div class="quick-tile-icon"><i class="fa-solid fa-landmark-dome"></i></div>
            <div class="quick-tile-info">
                <strong>إدارة شؤون الجامعات</strong>
                <small>الاعتماد والربط الأكاديمي</small>
            </div>
            <i class="fa-solid fa-chevron-left quick-tile-arrow"></i>
        </a>

        <a href="{{ route('admin.advertisements') }}" class="quick-action-tile tile-amber">
            <div class="quick-tile-icon"><i class="fa-solid fa-bullhorn"></i></div>
            <div class="quick-tile-info">
                <strong>مراجعة الإعلانات الجامعية</strong>
                <small>تدقيق الحملات والمدفوعات</small>
            </div>
            <i class="fa-solid fa-chevron-left quick-tile-arrow"></i>
        </a>

        <a href="{{ route('admin.referrals') }}" class="quick-action-tile tile-gold">
            <div class="quick-tile-icon"><i class="fa-solid fa-hand-holding-dollar"></i></div>
            <div class="quick-tile-info">
                <strong>متابعة الإحالات</strong>
                <small>صرف مكافآت التسجيل</small>
            </div>
            <i class="fa-solid fa-chevron-left quick-tile-arrow"></i>
        </a>

        <a href="{{ route('admin.requests') }}" class="quick-action-tile tile-blue">
            <div class="quick-tile-icon"><i class="fa-solid fa-clipboard-check"></i></div>
            <div class="quick-tile-info">
                <strong>طلبات التسجيل والالتحاق</strong>
                <small>تدفق القبول والمفاضلة</small>
            </div>
            <i class="fa-solid fa-chevron-left quick-tile-arrow"></i>
        </a>

        <a href="{{ route('admin.settings') }}" class="quick-action-tile tile-purple">
            <div class="quick-tile-icon"><i class="fa-solid fa-sliders"></i></div>
            <div class="quick-tile-info">
                <strong>إعدادات النظام العامة</strong>
                <small>خيارات المنصة والصلاحيات</small>
            </div>
            <i class="fa-solid fa-chevron-left quick-tile-arrow"></i>
        </a>
    </div>
</div>

<!-- ============================================================
     4. حاوية مؤشرات النظام الموحدة (ترويسة عائمة وقائمة تنقل مدمجة)
     ============================================================ -->
<div class="ops-unified-widget" id="opsUnifiedWidget">
    <!-- الترويسة العائمة في المنتصف: نصف في الحاوية من الداخل ونصف إلى الخارج من الأعلى -->
    <div class="ops-floating-title">
        <i class="fa-solid fa-chart-pie" style="color:var(--accent);"></i>
        <span>مؤشرات النظام</span>
    </div>

    <!-- قائمة التنقل المدمجة مع الحاوية بدون فجوة بصرية -->
    <div class="ops-nav-bar-integrated">
        <div class="ops-tabs-strip" role="tablist">
            <button type="button" class="ops-tab-item active" id="tabBtnRequests" onclick="switchOpsTab('requests', this)">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span class="ops-tab-label">طلبات التسجيل الأخيرة</span>
                <span class="ops-tab-counter">{{ count($recentRequests) }}</span>
            </button>

            <button type="button" class="ops-tab-item" id="tabBtnUniversities" onclick="switchOpsTab('universities', this)">
                <i class="fa-solid fa-landmark-dome"></i>
                <span class="ops-tab-label">الجامعات المعتمدة</span>
                <span class="ops-tab-counter">{{ $stats['total_universities'] }}</span>
            </button>

            <button type="button" class="ops-tab-item" id="tabBtnActivity" onclick="switchOpsTab('activity', this)">
                <i class="fa-solid fa-chart-line"></i>
                <span class="ops-tab-label">مؤشر النشاط الأسبوعي</span>
                <span class="ops-tab-counter">7 أيام</span>
            </button>
        </div>
    </div>

    <!-- مساحة محتوى الحاوية المدمجة مع القائمة مباشرة -->
    <div class="ops-unified-content" id="opsContainersArea">
    <!-- حاوية 1: تدفق طلبات التسجيل الأخيرة (صغيرة بالحجم وتكبر حتى 6 عناصر مع سكرول بار عند الزيادة) -->
    <div class="card ops-pane" id="opsSectionRequests" style="margin-bottom:24px;">
        <div class="card-header">
            <div class="card-title">
                <i class="fa-solid fa-clock-rotate-left"></i>
                تدفق طلبات التسجيل الأخيرة
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                <span class="badge badge-primary">
                    {{ count($recentRequests) }} طلب مستلم
                </span>
                <a href="{{ route('admin.requests') }}" class="btn btn-ghost btn-sm">
                    <span>كل الطلبات</span>
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>
        </div>

        <div class="adaptive-scroll-wrapper">
            @forelse($recentRequests as $req)
            <div style="padding:13px 20px;border-bottom:1px solid var(--border-light);display:flex;align-items:center;gap:14px;transition:background .15s ease;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                <!-- رقم الطلب المعتمد -->
                <div style="min-width:54px;height:40px;padding:0 8px;border-radius:10px;background:var(--primary-subtle);border:1px solid rgba(11, 61, 107, 0.18);display:flex;flex-direction:column;align-items:center;justify-content:center;color:var(--primary);flex-shrink:0;">
                    <span style="font-size:9.5px;color:var(--ink-muted);font-weight:600;line-height:1;">طلب</span>
                    <span style="font-weight:900;font-size:13px;line-height:1.2;font-family:'Plus Jakarta Sans',sans-serif;">#{{ $req->req_id }}</span>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:800;font-size:13.5px;color:var(--primary-dark);" class="truncate">{{ $req->full_name }}</div>
                    <div style="font-size:11.5px;color:var(--ink-muted);margin-top:2px;" class="truncate">
                        <i class="fa-solid fa-landmark-dome" style="font-size:10px;color:var(--accent);margin-left:3px;"></i>
                        {{ $req->university?->university_name ?? '—' }}
                        <span style="margin:0 4px;">•</span>
                        <span style="color:var(--primary);font-weight:700;">{{ $req->desired_spec }}</span>
                    </div>
                </div>
                <div style="flex-shrink:0;text-align:left;">
                    <span class="badge {{ match($req->status) {
                        'قيد المراجعة' => 'badge-yellow',
                        'مقبول'         => 'badge-green',
                        'مرفوض'         => 'badge-red',
                        'ملغي'           => 'badge-gray',
                        default          => 'badge-primary',
                    } }}">{{ $req->status }}</span>
                    <div style="font-size:10.5px;color:var(--ink-light);margin-top:4px;">
                        {{ $req->req_date ? $req->req_date->diffForHumans() : 'مؤخراً' }}
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state" style="padding:40px 20px;">
                <i class="fa-solid fa-inbox" style="font-size:32px;color:var(--ink-light);"></i>
                <h4 style="margin-top:8px;">لا توجد طلبات جديدة</h4>
                <p>جميع طلبات التسجيل تم استيفاؤها</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- حاوية 2: الجامعات المعتمدة (مع دعم استجابة الهواتف باختصار الأعمدة وعمود عرض التفاصيل) -->
    <div class="card ops-pane" id="opsSectionUniversities" style="margin-bottom:24px;display:none;">
        <div class="card-header">
            <div class="card-title">
                <i class="fa-solid fa-landmark-dome"></i>
                الجامعات المعتمدة في المنصة
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                <span class="badge badge-primary">
                    {{ $stats['total_universities'] }} جامعة مسجلة
                </span>
                <a href="{{ route('admin.universities') }}" class="btn btn-ghost btn-sm">
                    <span>إدارة الجامعات</span>
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>
        </div>
        <div class="adaptive-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>الجامعة</th>
                        <th class="col-hide-mobile">النوع</th>
                        <th class="col-hide-mobile">إجمالي الطلبات</th>
                        <th>الحالة</th>
                        <th class="col-show-mobile" style="text-align:center;">التفاصيل</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUniversities as $univ)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:12px;">
                                <div style="width:40px;height:40px;border-radius:10px;overflow:hidden;background:#ffffff;flex-shrink:0;border:1px solid var(--border);padding:3px;box-shadow:var(--shadow-sm);">
                                    <img src="{{ $univ->logo_url }}" alt="{{ $univ->university_name }}" style="width:100%;height:100%;object-fit:contain;" onerror="this.src='{{ asset('logo.png') }}'">
                                </div>
                                <div>
                                    <div style="font-weight:800;color:var(--primary-dark);font-size:13.5px;">{{ $univ->university_name }}</div>
                                    <div style="font-size:11.5px;color:var(--ink-muted);display:flex;align-items:center;gap:4px;">
                                        <i class="fa-solid fa-location-dot" style="font-size:10px;color:var(--accent);"></i>
                                        {{ $univ->district ?: 'اليمن' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="col-hide-mobile">
                            <span class="badge {{ $univ->university_type == 'حكومية' ? 'badge-primary' : 'badge-accent' }}">
                                {{ $univ->university_type }}
                            </span>
                        </td>
                        <td class="col-hide-mobile">
                            <span style="font-weight:900;color:var(--primary);font-size:14px;font-family:'Plus Jakarta Sans',sans-serif;">{{ $univ->reg_requests_count }}</span>
                            <span style="font-size:11px;color:var(--ink-muted);">طلب</span>
                        </td>
                        <td>
                            <span class="badge {{ $univ->status == 'نشطة' ? 'badge-green' : ($univ->status == 'معلقة' ? 'badge-yellow' : 'badge-red') }}">
                                <i class="fa-solid {{ $univ->status == 'نشطة' ? 'fa-circle-check' : 'fa-circle-pause' }}" style="font-size:9px;"></i>
                                {{ $univ->status }}
                            </span>
                        </td>
                        <td class="col-show-mobile" style="text-align:center;">
                            <a href="{{ route('admin.universities') }}" class="btn btn-ghost btn-sm" style="padding:4px 8px;font-size:11px;" title="عرض كل التفاصيل">
                                <i class="fa-solid fa-eye"></i>
                                <span>عرض</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;color:var(--ink-muted);padding:40px;">
                            <i class="fa-solid fa-landmark-dome" style="font-size:28px;margin-bottom:8px;opacity:0.4;"></i>
                            <div>لا توجد جامعات مسجلة في الوقت الحالي</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- حاوية 3: مؤشر النشاط الأسبوعي والعمليات الفعلية -->
    <div class="card ops-pane" id="opsSectionActivity" style="margin-bottom:24px;display:none;">
        <div class="card-header">
            <div class="card-title">
                <i class="fa-solid fa-chart-line"></i>
                مؤشر النشاط الأسبوعي والعمليات الفعلية
            </div>
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                <span class="badge badge-primary">
                    <i class="fa-solid fa-database" style="font-size:9px;"></i> بيانات لحظية من قاعدة البيانات
                </span>
                <span style="font-size:12px;font-weight:700;color:var(--ink-muted);">آخر 7 أيام</span>
            </div>
        </div>
        <div class="card-body">
            @php
                $maxRequests = collect($weeklyActivity)->max('requests') ?: 1;
                $totalWeekRequests = collect($weeklyActivity)->sum('requests');
                $totalWeekUsers = collect($weeklyActivity)->sum('users');
                $peakDay = collect($weeklyActivity)->sortByDesc('requests')->first();
            @endphp
            
            <div style="display:grid;grid-template-columns:3fr 2fr;gap:28px;align-items:center;">
                <!-- مخطط الأعمدة الأسبوعي الفعلي -->
                <div>
                    <div style="font-size:12.5px;font-weight:800;color:var(--primary-dark);margin-bottom:14px;display:flex;align-items:center;gap:6px;">
                        <i class="fa-solid fa-chart-simple" style="color:var(--accent);"></i>
                        توزيع طلبات التسجيل اليومية خلال الأسبوع:
                    </div>
                    <div style="display:flex;flex-direction:column;gap:12px;">
                        @foreach($weeklyActivity as $day)
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="width:65px;font-size:12.5px;font-weight:700;color:var(--primary-dark);text-align:right;flex-shrink:0;">
                                {{ $day['label'] }}
                            </div>
                            <div style="flex:1;background:var(--border-light);border-radius:8px;height:14px;overflow:hidden;position:relative;">
                                <div style="height:100%;background:linear-gradient(90deg, var(--accent) 0%, var(--primary) 100%);border-radius:8px;width:{{ $maxRequests > 0 ? max(round(($day['requests']/$maxRequests)*100), ($day['requests'] > 0 ? 8 : 0)) : 0 }}%;transition:width .5s ease;"></div>
                            </div>
                            <div style="width:55px;display:flex;align-items:center;justify-content:flex-end;gap:4px;">
                                <span style="font-size:13px;font-weight:900;color:var(--primary-dark);font-family:'Plus Jakarta Sans',sans-serif;">{{ $day['requests'] }}</span>
                                <span style="font-size:10.5px;color:var(--ink-muted);">طلب</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- بطاقات تلخيص الأداء الأسبوعي -->
                <div style="background:var(--surface-alt);border:1px solid var(--border);border-radius:var(--radius-md);padding:20px;display:flex;flex-direction:column;gap:16px;">
                    <div style="font-size:13px;font-weight:800;color:var(--primary-dark);display:flex;align-items:center;gap:6px;">
                        <i class="fa-solid fa-gauge-high" style="color:var(--primary);"></i>
                        ملخص حركة النظام خلال الأسبوع:
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div style="background:#ffffff;border:1px solid var(--border-light);border-radius:10px;padding:12px;">
                            <span style="font-size:11.5px;color:var(--ink-muted);display:block;">إجمالي الطلبات المستلمة</span>
                            <strong style="font-size:22px;color:var(--primary-dark);font-family:'Plus Jakarta Sans',sans-serif;">{{ $totalWeekRequests }}</strong>
                        </div>
                        <div style="background:#ffffff;border:1px solid var(--border-light);border-radius:10px;padding:12px;">
                            <span style="font-size:11.5px;color:var(--ink-muted);display:block;">مستخدمين جدد</span>
                            <strong style="font-size:22px;color:var(--accent);font-family:'Plus Jakarta Sans',sans-serif;">{{ $totalWeekUsers }}</strong>
                        </div>
                        <div style="background:#ffffff;border:1px solid var(--border-light);border-radius:10px;padding:12px;">
                            <span style="font-size:11.5px;color:var(--ink-muted);display:block;">أعلى يوم بالطلبات</span>
                            <strong style="font-size:14px;color:var(--primary);">{{ $peakDay['label'] ?? '—' }} ({{ $peakDay['requests'] ?? 0 }})</strong>
                        </div>
                        <div style="background:#ffffff;border:1px solid var(--border-light);border-radius:10px;padding:12px;">
                            <span style="font-size:11.5px;color:var(--ink-muted);display:block;">متوسط المعالجة اليومي</span>
                            <strong style="font-size:14px;color:#10b981;font-family:'Plus Jakarta Sans',sans-serif;">{{ round($totalWeekRequests / 7, 1) }} طلب/يوم</strong>
                        </div>
                    </div>

                    <div style="font-size:11.5px;color:var(--ink-muted);line-height:1.5;border-top:1px dashed var(--border);padding-top:10px;">
                        <i class="fa-solid fa-circle-info" style="color:var(--primary-light);margin-left:4px;"></i>
                        يتم استرجاع المؤشرات مباشرة من قيود جداول طلبات التسجيل والمستخدمين في قاعدة بيانات المنصة.
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div> <!-- إغلاق ops-unified-content -->
</div> <!-- إغلاق ops-unified-widget -->

<script>
    /**
     * التنقل السلس بين مؤشرات النظام
     */
    function switchOpsTab(tabId, btn) {
        document.querySelectorAll('.ops-tab-item').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');

        const secRequests = document.getElementById('opsSectionRequests');
        const secUnivs    = document.getElementById('opsSectionUniversities');
        const secActivity = document.getElementById('opsSectionActivity');

        if (!secRequests || !secUnivs || !secActivity) return;

        if (tabId === 'requests') {
            secRequests.style.display = 'block';
            secUnivs.style.display    = 'none';
            secActivity.style.display = 'none';
        } else if (tabId === 'universities') {
            secRequests.style.display = 'none';
            secUnivs.style.display    = 'block';
            secActivity.style.display = 'none';
        } else if (tabId === 'activity') {
            secRequests.style.display = 'none';
            secUnivs.style.display    = 'none';
            secActivity.style.display = 'block';
        }
    }

    /**
     * مسرح العرض التقديمي ثلاثي الأبعاد لمهام مدير النظام
     * دوران تلقائي + حركات ثلاثية الأبعاد + توقف عند التمرير + تحكم يدوي
     */
    let currentStageIndex = 0;
    const stageCards = document.querySelectorAll('.presentation-card');
    const stageDots  = document.querySelectorAll('.stage-dot');
    let stageInterval = null;
    const STAGE_DURATION = 5500;
    let isTransitioning = false;

    function showStageCard(targetIndex) {
        if (!stageCards.length || isTransitioning) return;
        if (targetIndex === currentStageIndex && stageCards[targetIndex].classList.contains('active')) return;

        isTransitioning = true;
        const currentCard = stageCards[currentStageIndex];
        const nextCard = stageCards[targetIndex];

        // حركة خروج البطاقة الحالية
        if (currentCard) {
            currentCard.classList.remove('active');
            currentCard.classList.add('exit-stage');
        }

        setTimeout(() => {
            if (currentCard) {
                currentCard.classList.remove('exit-stage');
                currentCard.style.display = 'none';
            }

            // تفعيل وظهور البطاقة التالية بحركة ثلاثية الأبعاد
            stageCards.forEach((c, idx) => {
                if (idx !== targetIndex) c.style.display = 'none';
            });
            nextCard.style.display = 'block';
            // Force browser reflow to restart CSS animation
            void nextCard.offsetWidth;
            nextCard.classList.add('active');

            // تحديث مؤشرات النقاط
            stageDots.forEach((dot, idx) => {
                dot.classList.toggle('active', idx === targetIndex);
            });

            currentStageIndex = targetIndex;
            isTransitioning = false;
        }, 480);
    }

    function nextStageCard() {
        let nextIdx = (currentStageIndex + 1) % stageCards.length;
        showStageCard(nextIdx);
        resetStageAutoPlay();
    }

    function prevStageCard() {
        let prevIdx = (currentStageIndex - 1 + stageCards.length) % stageCards.length;
        showStageCard(prevIdx);
        resetStageAutoPlay();
    }

    function jumpToStageCard(index) {
        showStageCard(index);
        resetStageAutoPlay();
    }

    function startStageAutoPlay() {
        clearInterval(stageInterval);
        stageInterval = setInterval(() => {
            let nextIdx = (currentStageIndex + 1) % stageCards.length;
            showStageCard(nextIdx);
        }, STAGE_DURATION);
    }

    function resetStageAutoPlay() {
        clearInterval(stageInterval);
        startStageAutoPlay();
    }

    // إيقاف واستئناف الحركة عند تمرير الماوس
    const stageContainer = document.getElementById('adminPresentationStage');
    if (stageContainer) {
        stageContainer.addEventListener('mouseenter', () => {
            clearInterval(stageInterval);
        });

        stageContainer.addEventListener('mouseleave', () => {
            startStageAutoPlay();
        });

        // دعم السحب والتنقل باللمس على شاشات الهواتف والأجهزة اللوحية
        let touchStartX = 0;
        let touchEndX = 0;
        stageContainer.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        stageContainer.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            const swipeDistance = touchEndX - touchStartX;
            if (swipeDistance < -45) {
                // سحب لليسار -> البطاقة التالية
                nextStageCard();
            } else if (swipeDistance > 45) {
                // سحب لليمين -> البطاقة السابقة
                prevStageCard();
            }
        }, { passive: true });
    }

    // بدء العرض التلقائي عند التحميل
    document.addEventListener('DOMContentLoaded', () => {
        startStageAutoPlay();
    });
    // تشغيل فوري في حال تم التحميل مسبقاً
    startStageAutoPlay();
</script>

@endsection
