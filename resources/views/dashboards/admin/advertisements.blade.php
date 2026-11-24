@extends('dashboards.admin.layout')

@section('page_title', 'إدارة ومراجعة الحملات الإعلانية')

@section('extra_styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    :root {
        --navy:            #0b3d6b;
        --navy-dark:       #072b4f;
        --navy-light:      #1565a8;
        --accent:          #c59b27;
        --accent-light:    #f0c842;
        --admin-green:     #059669;
        --admin-amber:     #d97706;
        --admin-red:       #dc2626;
    }

    /* تطبيق الخط العربي العصري الجريء الواضح Cairo / Tajawal دون المساس بالأيقونات */
    .admin-ads-root,
    .admin-ads-root button,
    .admin-ads-root input,
    .admin-ads-root select,
    .admin-ads-root textarea,
    .admin-ads-header,
    .admin-ad-card,
    .inspection-modal-dialog,
    .pending-showcase-card {
        font-family: 'Cairo', 'Tajawal', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
    }

    /* حماية وتأكيد ظهور جميع أيقونات Font Awesome بكافة فئاتها */
    .fa, .fas, .far, .fal, .fad, .fab, .fa-solid, .fa-regular, .fa-brands, [class*="fa-"] {
        font-family: 'Font Awesome 6 Free', 'Font Awesome 6 Brands', 'FontAwesome' !important;
        font-style: normal !important;
        font-variant: normal !important;
        text-rendering: auto !important;
        line-height: 1 !important;
        -webkit-font-smoothing: antialiased !important;
    }
    .fa-solid, .fas {
        font-weight: 900 !important;
    }
    .fa-regular, .far {
        font-weight: 400 !important;
    }
    .fa-brands, .fab {
        font-weight: 400 !important;
        font-family: 'Font Awesome 6 Brands' !important;
    }

    /* ====== HEADER BANNER (مطابق لأسلوب صفحة الجامعة) ====== */
    .admin-ads-header {
        background: linear-gradient(135deg, #0b3d6b 0%, #072b4f 100%);
        border-radius: var(--radius-md);
        color: #ffffff;
        padding: 26px 30px;
        margin-bottom: 26px;
        box-shadow: 0 10px 30px rgba(7, 43, 79, 0.18);
        position: relative;
        overflow: hidden;
    }
    .admin-ads-header::after {
        content: '';
        position: absolute;
        left: -30px;
        bottom: -40px;
        width: 220px;
        height: 220px;
        background: radial-gradient(circle, rgba(228, 183, 91, 0.12) 0%, transparent 70%);
        pointer-events: none;
    }
    .header-badge-tag {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: var(--accent-light);
        font-weight: 700;
        background: rgba(197, 155, 39, 0.16);
        border: 1px solid rgba(197, 155, 39, 0.35);
        padding: 4px 14px;
        border-radius: 20px;
        margin-bottom: 8px;
    }
    .header-title-text {
        font-size: 23px;
        font-weight: 800;
        color: #ffffff;
        margin: 0 0 6px;
        letter-spacing: -0.3px;
    }
    .header-desc-text {
        font-size: 13.5px;
        color: #cbd5e1;
        margin: 0;
        max-width: 680px;
        line-height: 1.6;
    }

    /* بطاقتي ملخص الوظيفة في الحاوية الأولى (بانتظار المراجعة والمنشورة حالياً فقط) - دائماً بطاقتين في صف واحد */
    .header-summary-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 16px;
        margin-top: 22px;
        padding-top: 18px;
        border-top: 1px solid rgba(255, 255, 255, 0.14);
    }
    .summary-hero-card {
        border-radius: 14px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        backdrop-filter: blur(8px);
        transition: all 0.25s ease;
    }
    .summary-hero-card:hover {
        transform: translateY(-2px);
    }
    .summary-hero-card.pending {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.22) 0%, rgba(180, 83, 9, 0.32) 100%);
        border: 1.5px solid rgba(245, 158, 11, 0.55);
        box-shadow: 0 8px 24px rgba(245, 158, 11, 0.18);
    }
    .summary-hero-card.active {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.22) 0%, rgba(5, 150, 105, 0.32) 100%);
        border: 1.5px solid rgba(16, 185, 129, 0.55);
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.18);
    }
    .summary-hero-info {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }
    .summary-hero-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    .summary-hero-icon.pending {
        background: rgba(245, 158, 11, 0.3);
        color: #fef08a;
        border: 1px solid rgba(245, 158, 11, 0.6);
    }
    .summary-hero-icon.active {
        background: rgba(16, 185, 129, 0.3);
        color: #a7f3d0;
        border: 1px solid rgba(16, 185, 129, 0.6);
    }
    .summary-hero-title {
        font-size: 14px;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 2px;
        line-height: 1.3;
    }
    .summary-hero-sub {
        font-size: 11.5px;
        color: rgba(255, 255, 255, 0.78);
        line-height: 1.4;
    }
    .summary-hero-num {
        font-size: 32px;
        font-weight: 900;
        line-height: 1;
        letter-spacing: -0.5px;
        flex-shrink: 0;
    }
    .summary-hero-num.pending {
        color: #fde047;
        text-shadow: 0 0 16px rgba(253, 224, 71, 0.45);
    }
    .summary-hero-num.active {
        color: #6ee7b7;
        text-shadow: 0 0 16px rgba(110, 231, 183, 0.45);
    }

    /* على الشاشات الصغيرة: تظل البطاقتان في صف واحد متناسق (2 في الصف) */
    @media (max-width: 768px) {
        .header-summary-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 8px !important;
            margin-top: 14px;
            padding-top: 14px;
        }
        .summary-hero-card {
            padding: 10px 12px;
            gap: 8px;
            flex-direction: column;
            align-items: stretch;
            justify-content: center;
        }
        .summary-hero-info {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
        }
        .summary-hero-icon {
            width: 32px;
            height: 32px;
            font-size: 15px;
            border-radius: 8px;
        }
        .summary-hero-title {
            font-size: 11px;
            font-weight: 800;
            margin: 0;
            line-height: 1.25;
        }
        .summary-hero-sub {
            display: none;
        }
        .summary-hero-num {
            font-size: 22px;
            align-self: flex-start;
            margin-top: 2px;
        }
    }

    /* ====== بطاقة مراجعة الإعلانات العاجلة في الربع العلوي (مركز الشاشة) ====== */
    .pending-showcase-card {
        max-width: 860px;
        margin: 0 auto 30px;
        background: #ffffff;
        border-radius: 18px;
        border: 2px solid #f59e0b;
        box-shadow: 0 16px 42px rgba(245, 158, 11, 0.14), 0 4px 16px rgba(0,0,0,0.06);
        overflow: hidden;
        position: relative;
        animation: focusPulse 2.8s infinite ease-in-out;
    }
    @keyframes focusPulse {
        0%, 100% { box-shadow: 0 16px 42px rgba(245, 158, 11, 0.14); }
        50% { box-shadow: 0 18px 48px rgba(245, 158, 11, 0.22); }
    }
    .pending-showcase-header {
        background: linear-gradient(135deg, #d97706, #b45309);
        color: #ffffff;
        padding: 12px 22px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .pending-badge-pulsing {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 800;
    }
    .pending-pulse-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #ffffff;
        display: inline-block;
        box-shadow: 0 0 10px #ffffff;
        animation: blinkDot 1.4s infinite;
    }
    @keyframes blinkDot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(0.7); }
    }
    .pending-stepper-controls {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-step-nav {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.35);
        color: #ffffff;
        width: 30px;
        height: 30px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-step-nav:hover:not(:disabled) {
        background: rgba(255, 255, 255, 0.35);
    }
    .btn-step-nav:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* محتوى بطاقة المراجعة العاجلة */
    .pending-showcase-body {
        padding: 22px 26px;
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 22px;
        align-items: center;
    }
    @media (max-width: 768px) {
        .pending-showcase-body {
            grid-template-columns: 1fr;
        }
    }
    .pending-media-preview-box {
        width: 100%;
        height: 165px;
        border-radius: 12px;
        background: #0f172a;
        overflow: hidden;
        position: relative;
        box-shadow: 0 4px 14px rgba(0,0,0,0.12);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .pending-media-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .pending-media-preview-box video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pending-action-bar {
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        padding: 14px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    /* ====== بطاقة النجاح عند عدم وجود إعلانات معلقة ====== */
    .all-reviewed-card {
        max-width: 820px;
        margin: 0 auto 30px;
        background: #ffffff;
        border-radius: 16px;
        border: 1.5px solid #a7f3d0;
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.08);
        padding: 22px 28px;
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .all-reviewed-icon {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        background: #ecfdf5;
        color: #10b981;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        flex-shrink: 0;
    }

    /* ====== شريط الفلاتر والبحث المتطور الفاخر ====== */
    .admin-filter-bar {
        background: #ffffff;
        border-radius: 16px;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 6px 24px rgba(11, 61, 107, 0.06);
        padding: 16px 20px;
        margin-bottom: 26px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        position: relative;
    }
    .filter-inputs-group {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
        flex: 1;
    }
    .search-input-wrap {
        position: relative;
        flex: 1.5;
        min-width: 260px;
    }
    .search-input-wrap .search-icon {
        position: absolute;
        top: 50%;
        right: 14px;
        transform: translateY(-50%);
        color: #0b3d6b;
        font-size: 15px;
        pointer-events: none;
        z-index: 2;
    }
    .search-input-wrap input {
        width: 100%;
        height: 44px;
        padding: 0 42px 0 38px;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        font-size: 13.5px;
        outline: none;
        transition: all 0.25s ease;
        background: #f8fafc;
        color: #1e293b;
    }
    .search-input-wrap input:focus {
        border-color: #0b3d6b;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(11, 61, 107, 0.1);
    }
    /* حاوية مخصصة لكل قائمة منسدلة مع أيقونتها الفخمة */
    .filter-select-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        min-width: 170px;
        flex: 1;
    }
    .filter-select-wrapper .select-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
        pointer-events: none;
        z-index: 2;
    }
    .filter-select-wrapper select {
        width: 100%;
        height: 44px;
        padding: 0 36px 0 28px;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        background: #f8fafc;
        outline: none;
        cursor: pointer;
        transition: all 0.2s ease;
        appearance: none;
        -webkit-appearance: none;
    }
    .filter-select-wrapper::after {
        content: '\f107';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        pointer-events: none;
        font-size: 12px;
    }
    .filter-select-wrapper select:hover {
        border-color: #94a3b8;
        background: #ffffff;
    }
    .filter-select-wrapper select:focus {
        border-color: #0b3d6b;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(11, 61, 107, 0.08);
    }

    /* زر إلغاء الفلترة الفاخر */
    .btn-reset-filters {
        height: 44px;
        padding: 0 16px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1.5px solid #fecaca;
        background: #fef2f2;
        color: #b91c1c;
        font-size: 12.5px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .btn-reset-filters:hover {
        background: #fee2e2;
        border-color: #f87171;
        transform: translateY(-1px);
    }

    /* مبدل طريقة العرض (بطاقات / جدول) */
    .view-switch-btns {
        display: inline-flex;
        background: #f1f5f9;
        border-radius: 10px;
        padding: 4px;
        gap: 3px;
        border: 1px solid #e2e8f0;
    }
    .btn-view-toggle {
        padding: 7px 16px;
        border: none;
        background: transparent;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 700;
        color: #64748b;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: all 0.2s;
    }
    .btn-view-toggle:hover {
        color: #0b3d6b;
    }
    .btn-view-toggle.active {
        background: #ffffff;
        color: #0b3d6b;
        box-shadow: 0 2px 8px rgba(11, 61, 107, 0.12);
        border: 1px solid rgba(197, 155, 39, 0.45);
    }
    .btn-view-toggle.active i {
        color: #c59b27;
    }

    @media (max-width: 900px) {
        .admin-filter-bar {
            padding: 12px 14px;
            gap: 12px;
        }
        /* على الشاشات الصغيرة: كل عنصرين في صف واحد بالتساوي */
        .filter-inputs-group {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px !important;
            width: 100% !important;
            flex: 1 1 100% !important;
        }
        .search-input-wrap,
        .filter-select-wrapper {
            min-width: 0 !important;
            width: 100% !important;
            flex: none !important;
            grid-column: span 1 !important;
        }
        .search-input-wrap input {
            height: 40px;
            font-size: 11.5px;
            padding: 0 34px 0 24px;
            border-radius: 8px;
        }
        .search-input-wrap .search-icon {
            right: 10px;
            font-size: 13px;
        }
        .filter-select-wrapper select {
            height: 40px;
            font-size: 11px;
            padding: 0 28px 0 16px;
            border-radius: 8px;
        }
        .filter-select-wrapper .select-icon {
            right: 8px;
            font-size: 12px;
        }
        .filter-select-wrapper::after {
            left: 8px;
            font-size: 10px;
        }
        .btn-reset-filters {
            grid-column: span 2 !important;
            height: 38px;
            font-size: 12px;
            justify-content: center;
            border-radius: 8px;
        }
        .view-switch-btns {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px;
            border-radius: 8px;
        }
        .btn-view-toggle {
            justify-content: center;
            padding: 7px 10px;
            font-size: 12px;
        }
    }

    /* ====== شبكة بطاقات الإعلانات (3 بطاقات في الصف بالشاشات الكبيرة) ====== */
    .admin-ads-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 22px;
        margin-bottom: 30px;
    }
    @media (max-width: 1100px) {
        .admin-ads-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 680px) {
        .admin-ads-grid {
            grid-template-columns: 1fr;
        }
    }

    .admin-ad-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(16, 42, 67, 0.04);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: all 0.25s ease;
        position: relative;
    }
    .admin-ad-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(16, 42, 67, 0.1);
        border-color: #cbd5e1;
    }

    /* أعلى البطاقة: الوسائط الترويجية */
    .ad-card-top-media {
        height: 180px;
        width: 100%;
        background: #0f172a;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .ad-card-top-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .ad-card-top-media video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* شارات الرأس على الوسائط */
    .top-media-badge-pkg {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(15, 23, 42, 0.85);
        color: var(--accent-light);
        border: 1px solid rgba(240, 200, 66, 0.4);
        font-size: 11px;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 20px;
        backdrop-filter: blur(4px);
    }
    .top-media-badge-review {
        position: absolute;
        top: 10px;
        left: 10px;
        font-size: 11px;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 20px;
        backdrop-filter: blur(4px);
    }
    .badge-rev-pending { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .badge-rev-approved { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
    .badge-rev-edit { background: #ffedd5; color: #c2410c; border: 1px solid #fed7aa; }
    .badge-rev-rejected { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }

    /* جسم البطاقة */
    .admin-ad-card-body {
        padding: 16px 18px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .univ-identity-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f1f5f9;
    }
    .univ-logo-thumb {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        object-fit: contain;
        background: #fff;
        border: 1px solid var(--border);
        flex-shrink: 0;
    }
    .univ-name-text {
        font-size: 12.5px;
        font-weight: 800;
        color: var(--navy);
        line-height: 1.3;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .ad-card-title-text {
        font-size: 14.5px;
        font-weight: 800;
        color: var(--ink);
        line-height: 1.4;
        margin-bottom: 6px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 40px;
    }
    .ad-card-desc-text {
        font-size: 12px;
        color: var(--ink-muted);
        line-height: 1.5;
        margin-bottom: 14px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 36px;
    }

    /* قسم البيانات المالية والحسابات */
    .ad-financial-grid {
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 10px 12px;
        margin-bottom: 12px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        font-size: 11.5px;
    }
    .ad-financial-col span {
        display: block;
        color: var(--ink-muted);
        font-size: 10.5px;
        margin-bottom: 2px;
    }
    .ad-financial-col strong {
        color: var(--navy);
        font-weight: 700;
    }

    /* تاريخ ووقت الانتهاء بالساعة والدقيقة */
    .expiry-timeline-box {
        background: #faf5ff;
        border: 1px solid #e9d5ff;
        border-radius: 8px;
        padding: 8px 12px;
        margin-bottom: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
    }
    .expiry-time-val {
        font-size: 12px;
        font-weight: 800;
        color: #6b21a8;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .time-status-pill {
        font-size: 10.5px;
        padding: 2px 8px;
        border-radius: 12px;
        font-weight: 700;
    }
    .time-status-pill.active {
        background: #dcfce7;
        color: #15803d;
    }
    .time-status-pill.expired {
        background: #fee2e2;
        color: #b91c1c;
    }
    .time-status-pill.pending {
        background: #fef3c7;
        color: #92400e;
    }

    /* أسفل البطاقة: أزرار الإجراءات واضحة بألوان عالية التباين دائماً */
    .ad-card-actions-dock {
        margin-top: auto;
        padding-top: 14px;
        border-top: 1.5px solid #edf2f7;
        display: flex;
        gap: 7px;
        align-items: center;
    }
    .btn-inspect-ad {
        flex: 1;
        background: linear-gradient(135deg, #0b3d6b 0%, #072b4f 100%);
        color: #ffffff !important;
        font-weight: 800;
        font-size: 12.5px;
        padding: 9px 12px;
        border-radius: 9px;
        border: 1px solid rgba(197, 155, 39, 0.45);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        transition: all 0.2s ease;
        box-shadow: 0 3px 10px rgba(11, 61, 107, 0.2);
    }
    .btn-inspect-ad:hover {
        background: linear-gradient(135deg, #1565a8, #0b3d6b);
        border-color: #f0c842;
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(11, 61, 107, 0.3);
    }
    .btn-inspect-ad i {
        color: #f0c842 !important;
        font-size: 13px;
    }

    /* أزرار الإجراءات السريعة على البطاقة ذات الألوان الصريحة والوضوح التام */
    .btn-card-action-approve {
        background: #059669 !important;
        color: #ffffff !important;
        border: 1.5px solid #047857 !important;
        border-radius: 9px;
        padding: 8px 11px;
        font-size: 13px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 7px rgba(5, 150, 105, 0.28);
        transition: all 0.2s ease;
    }
    .btn-card-action-approve:hover {
        background: #047857 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(5, 150, 105, 0.4);
    }

    .btn-card-action-edit {
        background: #d97706 !important;
        color: #ffffff !important;
        border: 1.5px solid #b45309 !important;
        border-radius: 9px;
        padding: 8px 11px;
        font-size: 13px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 7px rgba(217, 119, 6, 0.28);
        transition: all 0.2s ease;
    }
    .btn-card-action-edit:hover {
        background: #b45309 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(217, 119, 6, 0.4);
    }

    .btn-card-action-reject {
        background: #dc2626 !important;
        color: #ffffff !important;
        border: 1.5px solid #b91c1c !important;
        border-radius: 9px;
        padding: 8px 11px;
        font-size: 13px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 7px rgba(220, 38, 38, 0.28);
        transition: all 0.2s ease;
    }
    .btn-card-action-reject:hover {
        background: #b91c1c !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(220, 38, 38, 0.4);
    }

    .btn-card-action-delete {
        background: #f8fafc !important;
        color: #dc2626 !important;
        border: 1.5px solid #fca5a5 !important;
        border-radius: 9px;
        padding: 8px 11px;
        font-size: 13px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(220, 38, 38, 0.08);
        transition: all 0.2s ease;
    }
    .btn-card-action-delete:hover {
        background: #fee2e2 !important;
        border-color: #dc2626 !important;
        color: #b91c1c !important;
        transform: translateY(-1px);
    }

    /* ====== النافذة الاستثنائية لمعاينة وتدقيق الإعلان (Supreme Inspection Modal) ====== */
    .inspection-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(7, 27, 47, 0.78);
        backdrop-filter: blur(8px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 18px;
    }
    .inspection-modal-dialog {
        background: #ffffff;
        border-radius: 20px;
        width: 100%;
        max-width: 1040px;
        max-height: 92vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.45);
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
        animation: supremeModalIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes supremeModalIn {
        from { opacity: 0; transform: scale(0.94) translateY(18px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .inspection-modal-header {
        background: linear-gradient(135deg, #072b4f, #0b3d6b);
        color: #ffffff;
        padding: 16px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid var(--accent);
        flex-shrink: 0;
    }
    .inspection-modal-body {
        padding: 24px;
        overflow-y: auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 26px;
        background: #f8fafc;
    }
    @media (max-width: 860px) {
        .inspection-modal-body {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }
    .inspection-modal-footer {
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        padding: 10px 20px;
        min-height: 52px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        flex-shrink: 0;
    }

    /* بطاقة محاكاة العرض الحي للطلاب داخل النافذة */
    .student-preview-shell {
        background: #ffffff;
        border-radius: 16px;
        border: 1.5px solid #cbd5e1;
        box-shadow: 0 10px 30px rgba(16, 42, 67, 0.08);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    .student-preview-media-wrap {
        height: 220px;
        background: #0f172a;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .student-preview-media-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .student-preview-media-wrap video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .gallery-thumbnails-reel {
        display: flex;
        gap: 8px;
        padding: 8px 12px;
        background: #091322;
        overflow-x: auto;
    }
    .gallery-thumb-item {
        width: 52px;
        height: 40px;
        border-radius: 6px;
        object-fit: cover;
        cursor: pointer;
        opacity: 0.65;
        border: 1.5px solid transparent;
        transition: all 0.2s;
    }
    .gallery-thumb-item:hover, .gallery-thumb-item.active {
        opacity: 1;
        border-color: var(--accent);
        transform: scale(1.05);
    }

    /* ملف التدقيق المالي والإداري */
    .dossier-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        padding: 16px 18px;
        margin-bottom: 14px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }
    .dossier-card-title {
        font-size: 13px;
        font-weight: 800;
        color: var(--navy);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f5f9;
    }

    /* أزرار الإجراءات المتوهجة */
    .btn-action-glow-approve {
        background: linear-gradient(135deg, #10b981, #059669);
        color: #ffffff;
        font-weight: 800;
        padding: 10px 22px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13.5px;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
        transition: all 0.2s;
    }
    .btn-action-glow-approve:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(16, 185, 129, 0.45);
    }
    .btn-action-warning-edit {
        background: #d97706;
        color: #ffffff;
        font-weight: 800;
        padding: 10px 18px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        transition: all 0.2s;
    }
    .btn-action-warning-edit:hover {
        background: #b45309;
    }
    .btn-action-danger-reject {
        background: #dc2626;
        color: #ffffff;
        font-weight: 800;
        padding: 10px 18px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        transition: all 0.2s;
    }
    .btn-action-danger-reject:hover {
        background: #b91c1c;
    }
</style>
@endsection

@section('breadcrumb')
    <span>الرئيسية /</span>
    <strong>إدارة الحملات الإعلانية</strong>
@endsection

@section('content')
<div class="admin-ads-root">

    <!-- =============================================================== -->
    <!-- رأس الصفحة: ترويسة ملكية كحلية بلمسات ذهبية متناسقة مع المنصة   -->
    <!-- =============================================================== -->
    <div class="admin-ads-header">
        <div style="position:relative;z-index:2;">
            <div class="header-badge-tag">
                <i class="fa-solid fa-rectangle-ad"></i>
                <span>الإشراف المركزي والتحقق المالي</span>
            </div>
            <h1 class="header-title-text">إدارة ومراجعة الحملات الإعلانية الممولة</h1>
            <p class="header-desc-text">
                مراجعة وتدقيق إعلانات الجامعات، مطابقة سندات التحويل البنكية، واعتماد النشر في الصفحة الرئيسية ودليل الجامعات مع ضبط مواعيد الانتهاء بالساعة والدقيقة.
            </p>

            <!-- بطاقتي ملخص الوظيفة: بانتظار المراجعة والمنشورة حالياً فقط بتصميم تنفيذي فاخر -->
            <div class="header-summary-grid">
                <!-- 1. بطاقة بانتظار المراجعة -->
                <div class="summary-hero-card pending">
                    <div class="summary-hero-info">
                        <div class="summary-hero-icon pending">
                            <i class="fa-solid fa-hourglass-half"></i>
                        </div>
                        <div>
                            <div class="summary-hero-title">بانتظار المراجعة والاعتماد</div>
                        </div>
                    </div>
                    <div class="summary-hero-num pending" id="headerPendingCount">{{ $stats['pending'] }}</div>
                </div>

                <!-- 2. بطاقة المنشورة حالياً -->
                <div class="summary-hero-card active">
                    <div class="summary-hero-info">
                        <div class="summary-hero-icon active">
                            <i class="fa-solid fa-tower-broadcast"></i>
                        </div>
                        <div>
                            <div class="summary-hero-title">المنشورة حالياً على المنصة</div>
                        </div>
                    </div>
                    <div class="summary-hero-num active">{{ $stats['active'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- =============================================================== -->
    <!-- الربع العلوي: بطاقة الإعلانات التي تحتاج مراجعة في منتصف الشاشة   -->
    <!-- =============================================================== -->
    @php
        $pendingList = isset($pendingAds) ? $pendingAds : $advertisements->where('review_status', 'قيد المراجعة');
    @endphp

    @if($pendingList->isNotEmpty())
        <div class="pending-showcase-card" id="pendingShowcaseCard">
            <!-- شريط رأس بطاقة المراجعة -->
            <div class="pending-showcase-header">
                <div class="pending-badge-pulsing">
                    <span class="pending-pulse-dot"></span>
                    <span>طلب يحتاج قرارك واعتمادك الفوري</span>
                    <span style="background:rgba(255,255,255,0.22);padding:2px 8px;border-radius:12px;font-size:11.5px;margin-right:6px;">
                        إعلان <span id="currentPendingNum">1</span> من {{ $pendingList->count() }}
                    </span>
                </div>

                @if($pendingList->count() > 1)
                    <div class="pending-stepper-controls">
                        <button type="button" class="btn-step-nav" onclick="prevPendingAd()" id="btnPrevPending" title="الإعلان السابق">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                        <span style="font-size:11.5px;font-weight:700;" id="pendingStepIndicator">1 / {{ $pendingList->count() }}</span>
                        <button type="button" class="btn-step-nav" onclick="nextPendingAd()" id="btnNextPending" title="الإعلان التالي">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                    </div>
                @endif
            </div>

            <!-- محتوى الإعلان المعلق الحالي -->
            <div class="pending-showcase-body" id="pendingShowcaseBody">
                <!-- وسائط الإعلان المعلق -->
                <div class="pending-media-preview-box" id="pendingBoxMedia" style="width:230px;min-width:230px;height:165px;border-radius:12px;background:#0f172a;overflow:hidden;position:relative;display:flex;align-items:center;justify-content:center;">
                    @php $firstPending = $pendingList->first(); @endphp
                    @if($firstPending->media_type === 'video' && $firstPending->formattedVideoUrl())
                        <video src="{{ $firstPending->formattedVideoUrl() }}" autoplay muted loop playsinline style="width:100%;height:100%;object-fit:cover;display:block;"></video>
                        <div style="position:absolute;inset:0;background:rgba(0,0,0,0.25);pointer-events:none;display:flex;align-items:center;justify-content:center;color:#fff;">
                            <i class="fa-solid fa-circle-play" style="font-size:32px;color:var(--accent);opacity:0.85;"></i>
                        </div>
                    @elseif($firstPending->firstMediaUrl())
                        <img src="{{ $firstPending->firstMediaUrl() }}" alt="{{ $firstPending->adv_title }}" style="width:100%;height:100%;max-height:165px;object-fit:cover;display:block;" />
                    @else
                        <div style="color:#94a3b8;font-size:13px;display:flex;flex-direction:column;align-items:center;gap:6px;">
                            <i class="fa-solid fa-image" style="font-size:28px;"></i>
                            <span>لا توجد صورة</span>
                        </div>
                    @endif
                </div>

                <!-- تفاصيل الإعلان المعلق -->
                <div>
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                        <img src="{{ $firstPending->university?->logo_url ? asset($firstPending->university->logo_url) : asset('logo.png') }}" alt="{{ $firstPending->university?->university_name }}" style="width:30px;height:30px;border-radius:6px;object-fit:contain;background:#fff;border:1px solid #cbd5e1;" onerror="this.src='/logo.png';" id="pendingBoxUniLogo" />
                        <div>
                            <div style="font-size:13px;font-weight:800;color:var(--navy);" id="pendingBoxUniName">{{ $firstPending->university?->university_name }}</div>
                            <div style="font-size:11px;color:var(--ink-muted);">تاريخ التقديم: <span id="pendingBoxDate">{{ $firstPending->created_at ? $firstPending->created_at->format('Y/m/d - h:i A') : '—' }}</span></div>
                        </div>
                        <span class="badge" style="margin-right:auto;background:#fef3c7;color:#b45309;border:1px solid #fde68a;font-weight:800;font-size:11px;" id="pendingBoxPkg">
                            {{ $firstPending->getPackageLabel() }}
                        </span>
                    </div>

                    <h3 style="font-size:16px;font-weight:800;color:var(--ink);margin:0 0 6px;line-height:1.4;" id="pendingBoxTitle">
                        {{ $firstPending->adv_title }}
                    </h3>
                    <p style="font-size:12.5px;color:var(--ink-muted);line-height:1.6;margin:0 0 12px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;" id="pendingBoxContent">
                        {{ $firstPending->adv_content }}
                    </p>

                    <!-- شريط الدفع والسند السريع -->
                    <div style="background:#f1f5f9;border-radius:8px;padding:8px 12px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;font-size:12px;">
                        <div>
                            <span style="color:var(--ink-muted);">وسيلة التحويل:</span>
                            <strong style="color:var(--navy);" id="pendingBoxPayMethod">{{ $firstPending->pay_method ?: 'حوالة بنكية' }}</strong>
                        </div>
                        <div>
                            <span style="color:var(--ink-muted);">رقم المرجع:</span>
                            <strong style="font-family:monospace;color:var(--navy);" id="pendingBoxPayRef">{{ $firstPending->pay_reference ?: '—' }}</strong>
                        </div>
                        <div>
                            <span style="color:var(--ink-muted);">المبلغ:</span>
                            <strong style="color:#10b981;font-size:13px;" id="pendingBoxAmount">{{ number_format($firstPending->amount ?? 0, 0) }} ر.ي</strong>
                        </div>
                        @if($firstPending->receipt_url)
                            <button type="button" class="btn btn-xs" onclick="openReceiptLightbox('{{ asset(ltrim($firstPending->receipt_url, '/')) }}')" style="background:#fff;border:1px solid #cbd5e1;border-radius:6px;padding:4px 8px;font-size:11px;font-weight:700;color:var(--navy);cursor:pointer;display:inline-flex;align-items:center;gap:4px;">
                                <i class="fa-solid fa-receipt" style="color:var(--teal);"></i> معاينة السند
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- أزرار الإجراءات السريعة للمراجعة -->
            <div class="pending-action-bar">
                <div style="display:flex;gap:8px;">
                    <button type="button" class="btn btn-action-glow-approve" id="btnPendingApprove" onclick="approveAd(currentPendingAdId)">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>اعتماد ونشر فوري</span>
                    </button>
                    <button type="button" class="btn btn-action-warning-edit" id="btnPendingEdit" onclick="openRequestEditModal(currentPendingAdId)">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>طلب تعديل</span>
                    </button>
                    <button type="button" class="btn btn-action-danger-reject" id="btnPendingReject" onclick="openRejectAdModal(currentPendingAdId)">
                        <i class="fa-solid fa-ban"></i>
                        <span>رفض</span>
                    </button>
                </div>

                <div>
                    <button type="button" class="btn btn-inspect-ad" onclick="viewAd(currentPendingAdId)" style="padding:10px 18px;font-size:13px;">
                        <i class="fa-solid fa-expand"></i>
                        <span>فحص كامل في النافذة المطورة</span>
                    </button>
                </div>
            </div>
        </div>
    @else
        <!-- بطاقة طمأنينة: كل الإعلانات مراجعة -->
        <div class="all-reviewed-card">
            <div class="all-reviewed-icon">
                <i class="fa-solid fa-circle-check" style="font-size:28px;"></i>
            </div>
            <div>
                <h3 style="font-size:16px;font-weight:800;color:#065f46;margin:0;">
                    كافة الإعلانات مدققة ومراجعة بنجاح!
                </h3>
            </div>
        </div>
    @endif

    <!-- =============================================================== -->
    <!-- شريط البحث والتصفية وعرض البطاقات/الجدول (جزئية النتائج)        -->
    <!-- =============================================================== -->
    <div class="admin-filter-bar" id="adFilterAndResultsSection">
        <form method="GET" action="{{ route('admin.advertisements') }}" class="filter-inputs-group" id="adminAdsFilterForm">
            <!-- حقل البحث الأنيق مع أيقونة البحث ومسح فوري -->
            <div class="search-input-wrap">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" name="search" id="adminAdsSearchInput" placeholder="بحث باسم الجامعة أو عنوان الإعلان..." value="{{ request('search') }}" autocomplete="off" />
                @if(request('search'))
                    <button type="button" onclick="clearAdminSearch()" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:#ef4444;cursor:pointer;padding:4px;display:flex;align-items:center;justify-content:center;z-index:2;" title="مسح نص البحث">
                        <i class="fa-solid fa-circle-xmark" style="font-size:16px;"></i>
                    </button>
                @endif
            </div>

            <!-- قائمة فلتر حالة المراجعة مع أيقونة معتمدة -->
            <div class="filter-select-wrapper">
                <i class="fa-solid fa-clipboard-check select-icon" style="color:#d97706;"></i>
                <select name="review_status" onchange="submitFilterWithScroll(this)">
                    <option value="">كل حالات المراجعة</option>
                    <option value="قيد المراجعة" {{ request('review_status')=='قيد المراجعة' ? 'selected' : '' }}>قيد المراجعة</option>
                    <option value="معتمد"         {{ request('review_status')=='معتمد'         ? 'selected' : '' }}>معتمد</option>
                    <option value="مطلوب تعديل"   {{ request('review_status')=='مطلوب تعديل'   ? 'selected' : '' }}>مطلوب تعديل</option>
                    <option value="مرفوض"         {{ request('review_status')=='مرفوض'         ? 'selected' : '' }}>مرفوض</option>
                </select>
            </div>

            <!-- قائمة فلتر حالة النشر مع أيقونة بث -->
            <div class="filter-select-wrapper">
                <i class="fa-solid fa-tower-broadcast select-icon" style="color:#10b981;"></i>
                <select name="status" onchange="submitFilterWithScroll(this)">
                    <option value="">كل حالات النشر</option>
                    <option value="نشط"   {{ request('status')=='نشط'   ? 'selected' : '' }}>نشط حالياً</option>
                    <option value="منتهي" {{ request('status')=='منتهي' ? 'selected' : '' }}>منتهي الصلاحية</option>
                    <option value="متوقف" {{ request('status')=='متوقف' ? 'selected' : '' }}>متوقف</option>
                </select>
            </div>

            <!-- قائمة فلتر الباقات مع أيقونة التاج -->
            <div class="filter-select-wrapper">
                <i class="fa-solid fa-crown select-icon" style="color:#c59b27;"></i>
                <select name="package" onchange="submitFilterWithScroll(this)">
                    <option value="">كل الباقات الإعلانية</option>
                    <option value="اقتصادية" {{ request('package')=='اقتصادية' ? 'selected' : '' }}>الباقة الاقتصادية</option>
                    <option value="مميزة"    {{ request('package')=='مميزة'    ? 'selected' : '' }}>الباقة المميزة ⭐</option>
                    <option value="شاملة"    {{ request('package')=='شاملة'    ? 'selected' : '' }}>الباقة الشاملة 👑</option>
                </select>
            </div>

            @if(request()->anyFilled(['search','review_status','status','package']))
                <a href="{{ route('admin.advertisements') }}" class="btn-reset-filters" title="إعادة ضبط الفلاتر">
                    <i class="fa-solid fa-rotate-left"></i> إلغاء الفلترة
                </a>
            @endif
        </form>

        <!-- مبدل العرض: بطاقات / جدول -->
        <div class="view-switch-btns">
            <button type="button" class="btn-view-toggle active" id="btnViewCards" onclick="switchView('cards')">
                <i class="fa-solid fa-table-cells-large"></i>
                <span>عرض البطاقات</span>
            </button>
            <button type="button" class="btn-view-toggle" id="btnViewTable" onclick="switchView('table')">
                <i class="fa-solid fa-table-list"></i>
                <span>عرض الجدول</span>
            </button>
        </div>
    </div>

    <!-- =============================================================== -->
    <!-- 1. عرض البطاقات (الافتراضي - 3 بطاقات في الصف بالشاشات الكبيرة)  -->
    <!-- =============================================================== -->
    <div id="cardsViewContainer">
        <div class="admin-ads-grid" style="display:grid;grid-template-columns:repeat(auto-fill, minmax(320px, 1fr));gap:22px;margin-bottom:30px;">
            @forelse($advertisements as $ad)
                @php
                    $isExpired = $ad->expires_at ? \Carbon\Carbon::parse($ad->expires_at)->isPast() : false;
                    $expiresCarbon = $ad->expires_at ? \Carbon\Carbon::parse($ad->expires_at) : null;
                @endphp
                <div class="admin-ad-card" id="ad-card-{{ $ad->adv_id }}">
                    <!-- وسائط الإعلان العلوية -->
                    <div class="ad-card-top-media" style="height:180px;width:100%;background:#0f172a;position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center;">
                        @if($ad->media_type === 'video' && $ad->formattedVideoUrl())
                            <video src="{{ $ad->formattedVideoUrl() }}" autoplay muted loop playsinline style="width:100%;height:100%;object-fit:cover;display:block;"></video>
                            <div style="position:absolute;inset:0;background:rgba(0,0,0,0.25);pointer-events:none;display:flex;align-items:center;justify-content:center;color:#fff;">
                                <i class="fa-solid fa-circle-play" style="font-size:32px;color:var(--accent);opacity:0.85;"></i>
                            </div>
                        @elseif($ad->firstMediaUrl())
                            <img src="{{ $ad->firstMediaUrl() }}" alt="{{ $ad->adv_title }}" style="width:100%;height:100%;max-height:180px;object-fit:cover;display:block;" />
                        @else
                            <div style="color:#94a3b8;font-size:12px;display:flex;flex-direction:column;align-items:center;gap:6px;">
                                <i class="fa-solid fa-image" style="font-size:28px;"></i>
                                <span>لا توجد وسائط</span>
                            </div>
                        @endif

                        <!-- شارة الباقة -->
                        <span class="top-media-badge-pkg">
                            {{ $ad->getPackageLabel() }}
                        </span>

                        <!-- شارة المراجعة -->
                        <span class="top-media-badge-review {{ match($ad->review_status ?? 'قيد المراجعة') {
                            'معتمد'       => 'badge-rev-approved',
                            'مرفوض'       => 'badge-rev-rejected',
                            'مطلوب تعديل' => 'badge-rev-edit',
                            default       => 'badge-rev-pending',
                        } }}">
                            {{ $ad->review_status ?? 'قيد المراجعة' }}
                        </span>
                    </div>

                    <!-- جسم البطاقة -->
                    <div class="admin-ad-card-body">
                        <!-- هوية الجامعة -->
                        <div class="univ-identity-row">
                            <img src="{{ $ad->university?->logo_url ? asset($ad->university->logo_url) : asset('logo.png') }}" alt="{{ $ad->university?->university_name }}" class="univ-logo-thumb" onerror="this.src='/logo.png';" />
                            <div style="overflow:hidden;flex:1;">
                                <div class="univ-name-text">{{ $ad->university?->university_name ?? 'الجامعة' }}</div>
                                <div style="font-size:11px;color:var(--ink-muted);">#{{ $ad->adv_id }} • مدة {{ $ad->duration ?: '2' }} أيام</div>
                            </div>
                            <span class="badge {{ $ad->status == 'نشط' ? 'badge-green' : ($ad->status == 'منتهي' ? 'badge-orange' : 'badge-gray') }}" style="font-size:11px;font-weight:700;">
                                {{ $ad->status ?: 'متوقف' }}
                            </span>
                        </div>

                        <!-- عنوان الإعلان ومحتواه -->
                        <h4 class="ad-card-title-text" title="{{ $ad->adv_title }}">
                            {{ $ad->adv_title }}
                        </h4>
                        <p class="ad-card-desc-text">
                            {{ $ad->adv_content }}
                        </p>

                        <!-- بيانات السداد والتحويل المالي -->
                        <div class="ad-financial-grid">
                            <div class="ad-financial-col">
                                <span>المبلغ المسدد:</span>
                                <strong style="color:var(--admin-green);">{{ $ad->amount ? number_format($ad->amount, 0) . ' ر.ي' : '—' }}</strong>
                            </div>
                            <div class="ad-financial-col">
                                <span>طريقة التحويل:</span>
                                <strong>{{ $ad->pay_method ?: 'حوالة بنكية' }}</strong>
                            </div>
                            <div class="ad-financial-col" style="grid-column:1/-1;display:flex;justify-content:space-between;align-items:center;border-top:1px solid #e2e8f0;padding-top:6px;margin-top:2px;">
                                <div>
                                    <span style="display:inline;">رقم المرجع:</span>
                                    <strong style="font-family:monospace;">{{ $ad->pay_reference ?: '—' }}</strong>
                                </div>
                                @if($ad->receipt_url)
                                    <button type="button" class="btn btn-xs" onclick="openReceiptLightbox('{{ asset(ltrim($ad->receipt_url, '/')) }}')" style="background:#fff;border:1px solid #cbd5e1;border-radius:4px;padding:2px 6px;font-size:10.5px;color:var(--teal);cursor:pointer;">
                                        <i class="fa-solid fa-receipt"></i> السند
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- تاريخ ووقت الانتهاء بالساعة والدقيقة بدقة كاملة -->
                        <div class="expiry-timeline-box">
                            <div>
                                <div style="font-size:10.5px;color:#7e22ce;font-weight:700;margin-bottom:2px;">
                                    <i class="fa-regular fa-clock"></i> تاريخ ووقت الانتهاء:
                                </div>
                                <div class="expiry-time-val">
                                    @if($expiresCarbon)
                                        <span>{{ $expiresCarbon->format('Y/m/d') }}</span>
                                        <span style="font-size:11px;background:#f3e8ff;padding:1px 6px;border-radius:4px;color:#581c87;direction:ltr;">
                                            {{ $expiresCarbon->format('h:i A') }}
                                        </span>
                                    @else
                                        <span style="color:var(--ink-muted);font-weight:normal;font-size:11.5px;">يبدأ فور الاعتماد</span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                @if($expiresCarbon)
                                    @if($isExpired)
                                        <span class="time-status-pill expired">
                                            <i class="fa-solid fa-circle-exclamation"></i> منتهي
                                        </span>
                                    @else
                                        <span class="time-status-pill active" title="متبقي حتى انتهاء الحملة">
                                            <i class="fa-solid fa-hourglass-start"></i> متبقي {{ $expiresCarbon->diffForHumans(null, true) }}
                                        </span>
                                    @endif
                                @else
                                    <span class="time-status-pill pending">بانتظار النشر</span>
                                @endif
                            </div>
                        </div>

                        <!-- الإجراءات المتاحة على البطاقة: أزرار واضحة ذات تباين عالٍ دائماً -->
                        <div class="ad-card-actions-dock">
                            <button type="button" class="btn-inspect-ad" onclick="viewAd({{ $ad->adv_id }})">
                                <i class="fa-solid fa-expand"></i>
                                <span>معاينة وتدقيق</span>
                            </button>

                            @if(($ad->review_status ?? 'قيد المراجعة') === 'قيد المراجعة')
                                <button type="button" class="btn-card-action-approve" onclick="approveAd({{ $ad->adv_id }})" title="اعتماد ونشر فوري">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                                <button type="button" class="btn-card-action-edit" onclick="openRequestEditModal({{ $ad->adv_id }})" title="طلب تعديل من الجامعة">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button type="button" class="btn-card-action-reject" onclick="openRejectAdModal({{ $ad->adv_id }})" title="رفض الإعلان">
                                    <i class="fa-solid fa-ban"></i>
                                </button>
                            @elseif($ad->review_status === 'معتمد')
                                <button type="button" class="btn-card-action-pause" onclick="togglePauseAd({{ $ad->adv_id }})" id="btnCardPause_{{ $ad->adv_id }}" title="{{ $ad->status === 'متوقف مؤقتا' ? 'استئناف عرض الإعلان' : 'إيقاف عرض الإعلان مؤقتاً' }}" style="{{ $ad->status === 'متوقف مؤقتا' ? 'background:#059669;color:#fff;' : 'background:#d97706;color:#fff;' }};width:34px;height:34px;border-radius:8px;border:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;font-size:13px;transition:all 0.2s;">
                                    <i class="fa-solid {{ $ad->status === 'متوقف مؤقتا' ? 'fa-play' : 'fa-pause' }}"></i>
                                </button>
                            @endif

                            <button type="button" class="btn-card-action-delete" onclick="confirmDeleteAd({{ $ad->adv_id }})" title="حذف الإعلان">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:50px 20px;background:#fff;border-radius:14px;border:1px solid var(--border);">
                    <i class="fa-solid fa-rectangle-ad" style="font-size:42px;color:#cbd5e1;margin-bottom:12px;"></i>
                    <h4 style="font-size:16px;font-weight:800;color:var(--navy);margin-bottom:6px;">لا توجد أي إعلانات تطابق خيارات البحث</h4>
                    <p style="font-size:13px;color:var(--ink-muted);margin:0;">جرب تغيير خيارات التصفية أو إلغاء كلمات البحث.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- =============================================================== -->
    <!-- 2. عرض الجدول (بديل مرن لمن يفضل الجداول)                      -->
    <!-- =============================================================== -->
    <div id="tableViewContainer" style="display:none;">
        <div class="card" style="border-radius:14px;overflow:hidden;box-shadow:var(--shadow-sm);margin-bottom:30px;">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الجامعة</th>
                            <th>عنوان الإعلان</th>
                            <th>الباقة / المدة</th>
                            <th>المبلغ</th>
                            <th>طريقة التحويل</th>
                            <th>حالة المراجعة</th>
                            <th>حالة النشر</th>
                            <th>تاريخ ووقت الانتهاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($advertisements as $ad)
                        @php
                            $exp = $ad->expires_at ? \Carbon\Carbon::parse($ad->expires_at) : null;
                        @endphp
                        <tr id="ad-table-row-{{ $ad->adv_id }}">
                            <td style="color:var(--ink-muted);font-weight:700;">{{ $ad->adv_id }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <img src="{{ $ad->university?->logo_url ? asset($ad->university->logo_url) : asset('logo.png') }}" style="width:26px;height:26px;border-radius:6px;object-fit:contain;border:1px solid #cbd5e1;" onerror="this.src='/logo.png';" />
                                    <strong style="font-size:13px;color:var(--navy);">{{ $ad->university?->university_name ?? '—' }}</strong>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight:700;font-size:13px;max-width:240px;" class="truncate">{{ $ad->adv_title }}</div>
                            </td>
                            <td>
                                <span class="badge" style="background:#e0f2fe;color:#0369a1;font-weight:700;font-size:11px;">
                                    {{ $ad->package }} ({{ $ad->duration }} يوم)
                                </span>
                            </td>
                            <td>
                                <strong style="color:var(--admin-green);">{{ $ad->amount ? number_format($ad->amount, 0) . ' ر.ي' : '—' }}</strong>
                            </td>
                            <td>
                                <div style="font-size:12px;">{{ $ad->pay_method ?: 'حوالة' }}</div>
                                @if($ad->pay_reference)
                                    <div style="font-size:11px;color:var(--ink-muted);font-family:monospace;">#{{ $ad->pay_reference }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ match($ad->review_status ?? 'قيد المراجعة') {
                                    'معتمد'       => 'badge-green',
                                    'مرفوض'       => 'badge-red',
                                    'مطلوب تعديل' => 'badge-orange',
                                    default       => 'badge-yellow',
                                } }}">
                                    {{ $ad->review_status ?? 'قيد المراجعة' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $ad->status == 'نشط' ? 'badge-green' : ($ad->status == 'منتهي' ? 'badge-orange' : 'badge-gray') }}">
                                    {{ $ad->status ?: 'متوقف' }}
                                </span>
                            </td>
                            <td>
                                @if($exp)
                                    <div style="font-size:12px;font-weight:700;color:#6b21a8;">
                                        {{ $exp->format('Y/m/d') }}
                                        <span style="font-size:11px;background:#f3e8ff;padding:1px 5px;border-radius:4px;direction:ltr;">
                                            {{ $exp->format('h:i A') }}
                                        </span>
                                    </div>
                                    <div style="font-size:10.5px;color:{{ $exp->isPast() ? '#b91c1c' : '#15803d' }};">
                                        {{ $exp->isPast() ? 'منتهي' : 'متبقي ' . $exp->diffForHumans(null, true) }}
                                    </div>
                                @else
                                    <span style="color:var(--ink-muted);font-size:11.5px;">—</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;gap:5px;align-items:center;">
                                    <button class="btn btn-xs" onclick="viewAd({{ $ad->adv_id }})" title="معاينة وتدقيق" style="background:#0b3d6b;color:#fff;border-radius:6px;padding:4px 8px;border:none;">
                                        <i class="fa-solid fa-expand" style="color:#f0c842;"></i>
                                    </button>
                                    @if(($ad->review_status ?? 'قيد المراجعة') === 'قيد المراجعة')
                                        <button class="btn btn-xs" onclick="approveAd({{ $ad->adv_id }})" title="اعتماد ونشر" style="background:#059669;color:#fff;border-radius:6px;padding:4px 8px;border:none;">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                        <button class="btn btn-xs" onclick="openRequestEditModal({{ $ad->adv_id }})" title="طلب تعديل" style="background:#d97706;color:#fff;border-radius:6px;padding:4px 8px;border:none;">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button class="btn btn-xs" onclick="openRejectAdModal({{ $ad->adv_id }})" title="رفض الإعلان" style="background:#dc2626;color:#fff;border-radius:6px;padding:4px 8px;border:none;">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-xs" onclick="confirmDeleteAd({{ $ad->adv_id }})" title="حذف" style="background:#fee2e2;color:#dc2626;border-radius:6px;padding:4px 8px;border:1px solid #fca5a5;">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" style="text-align:center;padding:30px;">لا توجد إعلانات مسجلة</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- الترقيم والصفحات -->
    @if($advertisements->hasPages())
        <div class="pagination-wrap" style="margin-bottom:30px;">
            <span>عرض {{ $advertisements->firstItem() }}–{{ $advertisements->lastItem() }} من {{ $advertisements->total() }} إعلان</span>
            {{ $advertisements->links('pagination::simple-tailwind') }}
        </div>
    @endif

</div>

<!-- =============================================================== -->
<!-- النافذة المطورة الأفضل من نوعها: فحص وتدقيق الإعلان المالي والحي -->
<!-- =============================================================== -->
<div class="inspection-modal-overlay" id="supremeInspectionModal" style="display:none;">
    <div class="inspection-modal-dialog">
        <!-- ترويسة النافذة -->
        <div class="inspection-modal-header">
            <div style="display:flex;align-items:center;gap:12px;">
                <img id="modalUniLogo" src="/logo.png" alt="الجامعة" style="width:38px;height:38px;border-radius:10px;background:#fff;border:1.5px solid var(--accent);object-fit:contain;padding:2px;" />
                <div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <h3 style="font-size:16px;font-weight:800;color:#fff;margin:0;" id="modalUniName">جامعة صنعاء</h3>
                        <span class="badge" style="background:rgba(228,183,91,0.25);color:var(--accent-light);border:1px solid rgba(228,183,91,0.5);font-size:11px;" id="modalPackageTag">الباقة المميزة ⭐</span>
                    </div>
                    <div style="font-size:11.5px;color:#cbd5e1;">معرف الحملة الإعلانية: <strong style="font-family:monospace;color:#fff;" id="modalAdIdText">#12</strong></div>
                </div>
            </div>

            <div style="display:flex;align-items:center;gap:10px;">
                <span class="badge" id="modalReviewStatusBadge" style="font-size:12px;padding:5px 14px;border-radius:20px;font-weight:800;">قيد المراجعة</span>
                <button type="button" onclick="closeSupremeModal()" style="background:rgba(255,255,255,0.15);border:none;color:#fff;font-size:18px;width:34px;height:34px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all 0.2s;">&times;</button>
            </div>
        </div>

        <!-- جسم النافذة (تقسيم متوازن وأنيق 50/50 بين المحاكاة الحية والتدقيق المالي) -->
        <div class="inspection-modal-body" id="modalBodyWrap">
            <!-- 1. العمود الأيمن: محاكاة بطاقة الإعلان الحية للطلاب + الجدول الزمني الدقيق -->
            <div style="display:flex;flex-direction:column;gap:16px;">
                <div>
                    <div style="font-size:12px;font-weight:700;color:var(--ink-muted);margin-bottom:8px;display:flex;align-items:center;gap:6px;">
                        <i class="fa-solid fa-eye" style="color:var(--primary);"></i>
                        <span>معاينة الإعلان كما يظهر حالياً للطلاب في المنصة:</span>
                    </div>

                    <div class="student-preview-shell" style="background:linear-gradient(145deg, #072b4f 0%, #0b3d6b 100%);border:1px solid rgba(197,155,39,0.45);border-radius:18px;overflow:hidden;box-shadow:0 12px 36px rgba(0,0,0,0.32);color:#fff;">
                        <!-- وسائط الإعلان -->
                        <div class="student-preview-media-wrap" style="height:210px;width:100%;background:#071d33;position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center;">
                            <img id="modalLiveImg" src="/images/default-ad.jpg" alt="معاينة" style="width:100%;height:100%;object-fit:cover;display:block;" onerror="this.src='/logo.png';" />
                            <video id="modalLiveVideo" controls playsinline style="display:none;width:100%;height:100%;object-fit:cover;"></video>
                            <span style="position:absolute;top:10px;right:10px;background:rgba(10,27,42,0.85);color:#f0c842;border:1px solid #c59b27;font-size:11px;font-weight:800;padding:3px 10px;border-radius:20px;z-index:3;">
                                <i class="fa-solid fa-star"></i> إعلان ممول
                            </span>
                            
                            <!-- عناصر التنقل بين صور الإعلان في حال وجود أكثر من صورة -->
                            <div id="modalMediaNavControls" style="display:none;position:absolute;inset:0;pointer-events:none;align-items:center;justify-content:space-between;padding:0 8px;z-index:2;">
                                <button type="button" onclick="prevModalImg()" style="pointer-events:auto;background:rgba(0,0,0,0.65);border:none;color:#fff;width:32px;height:32px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background 0.2s;" title="الصورة السابقة"><i class="fa-solid fa-chevron-right"></i></button>
                                <button type="button" onclick="nextModalImg()" style="pointer-events:auto;background:rgba(0,0,0,0.65);border:none;color:#fff;width:32px;height:32px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background 0.2s;" title="الصورة التالية"><i class="fa-solid fa-chevron-left"></i></button>
                            </div>
                            <span id="modalImgCounterTag" style="display:none;position:absolute;bottom:8px;left:10px;background:rgba(0,0,0,0.72);color:#fff;font-size:11px;font-weight:700;padding:2px 8px;border-radius:4px;z-index:3;"></span>
                        </div>

                        <!-- شريط مصغرات معرض صور الإعلان -->
                        <div class="gallery-thumbnails-reel" id="modalGalleryReel" style="display:none;padding:8px 12px;background:rgba(0,0,0,0.25);gap:6px;overflow-x:auto;"></div>

                        <!-- محتوى البطاقة للطلاب مطابق للرئيسية بدون أي أزرار تفاعلية -->
                        <div style="padding:16px 18px 20px;">
                            <div style="display:inline-flex;align-items:center;gap:6px;background:rgba(197,155,39,0.18);border:1px solid rgba(197,155,39,0.45);color:#f0c842;padding:2px 10px;border-radius:50px;font-size:11.5px;font-weight:700;margin-bottom:8px;">
                                <i class="fa-solid fa-graduation-cap"></i>
                                <span id="modalLiveUniTitle">اسم الجامعة</span>
                            </div>

                            <h4 id="modalLiveAdTitle" style="font-size:15px;font-weight:800;color:#ffffff;line-height:1.4;margin:0 0 6px;">
                                عنوان الإعلان
                            </h4>

                            <p id="modalLiveAdDesc" style="font-size:12.5px;color:rgba(255,255,255,0.85);line-height:1.6;margin:0;">
                                تفاصيل الإعلان...
                            </p>
                        </div>
                    </div>
                </div>

                <!-- التوقيت وتاريخ الانتهاء بالساعة والدقيقة بدقة كاملة -->
                <div class="dossier-card" style="margin-bottom:0;">
                    <div class="dossier-card-title">
                        <i class="fa-regular fa-calendar-check" style="color:#6b21a8;"></i>
                        <span>الجدول الزمني وموعد الانتهاء الدقيق (ساعة ودقيقة)</span>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:10px;font-size:12.5px;">
                        <div style="display:flex;justify-content:space-between;border-bottom:1px solid #f1f5f9;padding-bottom:6px;">
                            <span style="color:var(--ink-muted);">تاريخ ووقت التقديم:</span>
                            <strong style="color:var(--navy);" id="modalDossierCreated">—</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;border-bottom:1px solid #f1f5f9;padding-bottom:6px;">
                            <span style="color:var(--ink-muted);">تاريخ ووقت النشر:</span>
                            <strong style="color:var(--navy);" id="modalDossierPublished">لم يُنشر بعد</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;background:#faf5ff;border:1px solid #e9d5ff;border-radius:8px;padding:8px 12px;">
                            <div>
                                <span style="font-size:11px;color:#7e22ce;display:block;font-weight:700;">تاريخ ووقت الانتهاء بالساعة والدقيقة:</span>
                                <strong style="font-size:14px;color:#581c87;font-weight:900;" id="modalDossierExpires">—</strong>
                            </div>
                            <span class="badge" id="modalDossierRemainingBadge" style="font-size:11px;font-weight:800;">—</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. العمود الأيسر: ملف التدقيق المالي والإداري الشامل -->
            <div style="display:flex;flex-direction:column;gap:16px;">
                <!-- البطاقة المالية وسند الدفع (منفصلة تماماً عن وسائط الإعلان) -->
                <div class="dossier-card" style="margin-bottom:0;">
                    <div class="dossier-card-title">
                        <i class="fa-solid fa-file-invoice-dollar" style="color:var(--teal);"></i>
                        <span>بيانات السداد وسند إثبات التحويل البنكي</span>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;font-size:12.5px;margin-bottom:14px;">
                        <div>
                            <span style="display:block;font-size:11px;color:var(--ink-muted);">المبلغ المطلوب والمسدد:</span>
                            <strong style="font-size:16px;color:#10b981;font-weight:900;" id="modalDossierAmount">9,000 ر.ي</strong>
                        </div>
                        <div>
                            <span style="display:block;font-size:11px;color:var(--ink-muted);">وسيلة التحويل المستخدمة:</span>
                            <strong style="color:var(--navy);font-weight:800;" id="modalDossierMethod">حوالة بنك الكريمي</strong>
                        </div>
                        <div style="grid-column:1/-1;background:#f1f5f9;border-radius:8px;padding:8px 12px;display:flex;justify-content:space-between;align-items:center;">
                            <div>
                                <span style="font-size:11px;color:var(--ink-muted);display:block;">رقم الحوالة / المرجع:</span>
                                <strong style="font-family:monospace;font-size:14px;color:var(--navy);" id="modalDossierRef">—</strong>
                            </div>
                            <button type="button" class="btn btn-xs" onclick="copyRefFromModal()" style="background:#fff;border:1px solid #cbd5e1;border-radius:6px;padding:4px 10px;font-size:11px;font-weight:700;cursor:pointer;">
                                <i class="fa-regular fa-copy"></i> نسخ
                            </button>
                        </div>
                    </div>

                    <!-- معاينة صورة سند الدفع منفصلة تماماً عن وسائط الإعلان -->
                    <div>
                        <div style="font-size:11.5px;font-weight:700;color:var(--ink-muted);margin-bottom:6px;">صورة سند التحويل البنكي المرفقة:</div>
                        <div id="modalReceiptPreviewContainer" style="height:140px;border-radius:8px;border:1.5px dashed #cbd5e1;background:#f8fafc;display:flex;align-items:center;justify-content:center;overflow:hidden;position:relative;cursor:pointer;" onclick="if(modalCurrentReceiptUrl) openReceiptLightbox(modalCurrentReceiptUrl)">
                            <img id="modalReceiptImg" src="" alt="سند الدفع" style="max-height:100%;max-width:100%;object-fit:contain;display:none;" />
                            <div id="modalReceiptEmptyText" style="display:none;color:#94a3b8;font-size:12px;font-weight:600;align-items:center;gap:6px;">
                                <i class="fa-solid fa-receipt"></i> لا يوجد سند سداد مرفق
                            </div>
                            <div id="modalReceiptZoomBadge" style="display:none;position:absolute;bottom:6px;left:6px;background:rgba(0,0,0,0.7);color:#fff;font-size:10.5px;padding:2px 8px;border-radius:4px;">
                                <i class="fa-solid fa-magnifying-glass-plus"></i> انقر للتكبير
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الملاحظات الإدارية -->
                <div class="dossier-card" id="modalNotesCard" style="display:none;margin-bottom:0;">
                    <div class="dossier-card-title">
                        <i class="fa-solid fa-comment-dots" style="color:#d97706;"></i>
                        <span>سجل الملاحظات والتواصل الإداري</span>
                    </div>
                    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:10px 14px;font-size:12.5px;color:#92400e;line-height:1.6;" id="modalDossierNotes">
                    </div>
                </div>
            </div>
        </div>

        <!-- شريط إجراءات النافذة (Footer) - أزرار الإجراءات مخفية افتراضياً لمنع أي وميض لحظي -->
        <div class="inspection-modal-footer">
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                <!-- أزرار المراجعة (تظهر فقط عند قيد المراجعة) -->
                <button type="button" class="btn btn-action-glow-approve" id="modalBtnApprove" onclick="approveAdFromModal()" style="display:none;">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>اعتماد ونشر الإعلان فوراً</span>
                </button>
                <button type="button" class="btn btn-action-warning-edit" id="modalBtnRequestEdit" onclick="openRequestEditModal(viewingAdId)" style="display:none;">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>طلب تعديل من الجامعة</span>
                </button>
                <button type="button" class="btn btn-action-danger-reject" id="modalBtnReject" onclick="openRejectAdModal(viewingAdId)" style="display:none;">
                    <i class="fa-solid fa-ban"></i>
                    <span>رفض الإعلان</span>
                </button>

                <!-- زر الإيقاف المؤقت والاستئناف للإعلانات المعتمدة -->
                <button type="button" class="btn" id="modalBtnTogglePause" onclick="togglePauseAdFromModal()" style="display:none;padding:9px 18px;border-radius:8px;font-weight:700;font-size:13px;border:none;cursor:pointer;align-items:center;gap:8px;transition:all 0.2s;">
                    <i class="fa-solid fa-pause" id="modalPauseIcon"></i>
                    <span id="modalPauseText">إيقاف مؤقت</span>
                </button>
            </div>

            <div style="display:flex;gap:10px;align-items:center;">
                <button type="button" class="btn btn-ghost" onclick="confirmDeleteAd(viewingAdId)" style="color:#ef4444;border:1px solid #fecaca;padding:8px 14px;border-radius:8px;font-weight:700;font-size:12.5px;">
                    <i class="fa-solid fa-trash"></i> حذف
                </button>
                <button type="button" class="btn btn-ghost" onclick="closeSupremeModal()" style="border:1px solid #cbd5e1;padding:8px 20px;border-radius:8px;font-weight:700;font-size:13px;background:#fff;">
                    إغلاق النافذة
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =============================================================== -->
<!-- نافذة تكبير سند الدفع (Receipt Lightbox)                         -->
<!-- =============================================================== -->
<div class="modal-overlay" id="receiptLightboxModal" style="display:none;z-index:10000;" onclick="closeReceiptLightbox()">
    <div style="max-width:750px;width:100%;text-align:center;position:relative;" onclick="event.stopPropagation()">
        <img id="lightboxReceiptImg" src="" alt="سند الدفع المكبر" style="max-height:85vh;max-width:100%;border-radius:12px;box-shadow:0 25px 60px rgba(0,0,0,0.5);border:2px solid #fff;" />
        <div style="margin-top:12px;">
            <button type="button" class="btn btn-sm btn-ghost" onclick="closeReceiptLightbox()" style="background:#fff;border-radius:8px;padding:6px 18px;font-weight:700;">
                <i class="fa-solid fa-xmark"></i> إغلاق السند
            </button>
            <a id="lightboxDownloadBtn" href="#" target="_blank" class="btn btn-sm" style="background:var(--navy);color:#fff;border-radius:8px;padding:6px 18px;font-weight:700;text-decoration:none;margin-right:8px;">
                <i class="fa-solid fa-download"></i> فتح السند الأصلي
            </a>
        </div>
    </div>
</div>

<!-- =============================================================== -->
<!-- نوافذ الإجراءات (رفض / تعديل / حذف)                             -->
<!-- =============================================================== -->

<!-- 1. نافذة رفض الإعلان -->
<div class="modal-overlay" id="rejectAdModal" style="display:none;">
    <div class="modal modal-sm" style="border-radius:16px;">
        <div class="modal-header" style="background:#fef2f2;border-bottom:1px solid #fee2e2;">
            <h3 style="color:#b91c1c;"><i class="fa-solid fa-circle-xmark"></i> رفض الحملة الإعلانية</h3>
            <button class="modal-close" onclick="closeModal('rejectAdModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <p style="font-size:13px;color:var(--ink-muted);margin-bottom:12px;">
                يرجى كتابة سبب الرفض بوضوح ليتم إرساله للجامعة وتوثيقه في النظام:
            </p>
            <div class="form-group">
                <label class="form-label" style="font-weight:700;">سبب الرفض <span class="req">*</span></label>
                <textarea id="rejectAdReason" class="form-control" rows="4" placeholder="مثال: تعذر التحقق من سند الإيداع البنكي المرفق، يرجى التواصل مع الإدارة..." style="font-size:13px;border-radius:8px;"></textarea>
            </div>
        </div>
        <div class="modal-footer" style="background:#f8fafc;">
            <button class="btn btn-ghost" onclick="closeModal('rejectAdModal')">إلغاء</button>
            <button class="btn btn-danger" onclick="submitRejectAd()" id="rejectAdBtn" style="font-weight:700;border-radius:8px;">
                <i class="fa-solid fa-ban"></i> تأكيد الرفض
            </button>
        </div>
    </div>
</div>

<!-- 2. نافذة طلب تعديل الإعلان -->
<div class="modal-overlay" id="requestEditAdModal" style="display:none;">
    <div class="modal modal-sm" style="border-radius:16px;">
        <div class="modal-header" style="background:#fffbeb;border-bottom:1px solid #fde68a;">
            <h3 style="color:#b45309;"><i class="fa-solid fa-pen-to-square"></i> طلب تعديل من الجامعة</h3>
            <button class="modal-close" onclick="closeModal('requestEditAdModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <p style="font-size:13px;color:var(--ink-muted);margin-bottom:12px;">
                سيتم إعادة الإعلان إلى لوحة تحكم الجامعة مع تدوين الملاحظات التالية لتصحيحها وإعادة إرسالها:
            </p>
            <div class="form-group">
                <label class="form-label" style="font-weight:700;">الملاحظات والتعديلات المطلوبة <span class="req">*</span></label>
                <textarea id="requestEditReason" class="form-control" rows="4" placeholder="مثال: يرجى رفع صورة السند بوضوح أعلى، أو مراجعة تاريخ بدء التنسيق في العنوان..." style="font-size:13px;border-radius:8px;"></textarea>
            </div>
        </div>
        <div class="modal-footer" style="background:#f8fafc;">
            <button class="btn btn-ghost" onclick="closeModal('requestEditAdModal')">إلغاء</button>
            <button class="btn btn-warning" onclick="submitRequestEditAd()" id="requestEditAdBtn" style="background:#d97706;color:#fff;font-weight:700;border-radius:8px;">
                <i class="fa-solid fa-paper-plane"></i> إرسال الملاحظات
            </button>
        </div>
    </div>
</div>

<!-- 3. نافذة تأكيد الحذف -->
<div class="modal-overlay" id="deleteAdModal" style="display:none;">
    <div class="modal modal-sm" style="border-radius:16px;">
        <div class="modal-header" style="background:#fef2f2;border-bottom:1px solid #fee2e2;">
            <h3 style="color:#b91c1c;"><i class="fa-solid fa-triangle-exclamation"></i> تأكيد حذف الإعلان</h3>
            <button class="modal-close" onclick="closeModal('deleteAdModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body" style="padding:22px;">
            <p style="font-size:14px;color:var(--ink);margin:0;line-height:1.6;">
                هل أنت متأكد من رغبتك في حذف هذا الإعلان بشكل نهائي من قاعدة البيانات؟ لا يمكن التراجع عن هذا الإجراء.
            </p>
        </div>
        <div class="modal-footer" style="background:#f8fafc;">
            <button class="btn btn-ghost" onclick="closeModal('deleteAdModal')">إلغاء</button>
            <button class="btn btn-danger" onclick="submitDeleteAd()" id="deleteAdBtn" style="font-weight:700;border-radius:8px;">
                <i class="fa-solid fa-trash"></i> تأكيد الحذف
            </button>
        </div>
    </div>
</div>

@endsection

@php
    $pendingFormatted = $pendingList->map(function($p) {
        return [
            'id'              => $p->adv_id,
            'title'           => $p->adv_title,
            'content'         => $p->adv_content,
            'package_label'   => $p->getPackageLabel(),
            'amount'          => $p->amount ? number_format($p->amount, 0) : '0',
            'pay_method'      => $p->pay_method ?: 'حوالة بنكية',
            'pay_reference'   => $p->pay_reference ?: '—',
            'created_at'      => $p->created_at ? $p->created_at->format('Y/m/d - h:i A') : '—',
            'receipt_url'     => $p->receipt_url ? asset(ltrim($p->receipt_url, '/')) : null,
            'media_type'      => $p->media_type,
            'first_media'     => $p->firstMediaUrl(),
            'video_url'       => $p->formattedVideoUrl(),
            'university_name' => $p->university?->university_name ?? 'الجامعة',
            'university_logo' => $p->university?->logo_url ? asset($p->university->logo_url) : asset('logo.png'),
        ];
    })->values()->all();
@endphp

@section('scripts')
<script>
    // قائمة الإعلانات المعلقة في الربع العلوي
    const pendingAdsData = @json($pendingFormatted);

    let currentPendingIndex = 0;
    let currentPendingAdId = pendingAdsData.length > 0 ? pendingAdsData[0].id : null;
    let viewingAdId = null;
    let modalCurrentReceiptUrl = null;

    document.addEventListener('DOMContentLoaded', function() {
        if (pendingAdsData.length > 0) {
            renderPendingShowcase(0);
        }

        // سحبة خفيفة وسلسة إلى جزئية النتائج عند البحث أو الفلترة بدلاً من البقاء بأعلى الصفحة
        const shouldScrollToResults = {{ request()->anyFilled(['search', 'review_status', 'status', 'package', 'page']) ? 'true' : 'false' }} ||
                                     sessionStorage.getItem('admin_scroll_to_results') === 'true';

        if (shouldScrollToResults) {
            sessionStorage.removeItem('admin_scroll_to_results');
            const targetSection = document.getElementById('adFilterAndResultsSection');
            if (targetSection) {
                setTimeout(() => {
                    const headerOffset = 85; // مراعاة الهيدر الإداري الثابت
                    const elementPosition = targetSection.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: Math.max(0, offsetPosition),
                        behavior: 'smooth'
                    });
                }, 120);
            }
        }

        // التصفية التلقائية الفورية لحقل البحث بدون زر تصفية
        const searchInput = document.getElementById('adminAdsSearchInput');
        const filterForm = document.getElementById('adminAdsFilterForm');
        let searchDebounceTimer = null;

        if (searchInput && filterForm) {
            // استعادة التركيز إذا تم التحديث نتيجة الكتابة في البحث
            if (sessionStorage.getItem('admin_search_focused') === 'true') {
                sessionStorage.removeItem('admin_search_focused');
                searchInput.focus();
                const val = searchInput.value;
                searchInput.value = '';
                searchInput.value = val;
            }

            searchInput.addEventListener('input', function() {
                clearTimeout(searchDebounceTimer);
                searchDebounceTimer = setTimeout(() => {
                    sessionStorage.setItem('admin_search_focused', 'true');
                    sessionStorage.setItem('admin_scroll_to_results', 'true');
                    filterForm.submit();
                }, 500);
            });

            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchDebounceTimer);
                    sessionStorage.setItem('admin_search_focused', 'true');
                    sessionStorage.setItem('admin_scroll_to_results', 'true');
                    filterForm.submit();
                }
            });
        }
    });

    function submitFilterWithScroll(el) {
        sessionStorage.setItem('admin_scroll_to_results', 'true');
        el.form ? el.form.submit() : document.getElementById('adminAdsFilterForm')?.submit();
    }

    function clearAdminSearch() {
        sessionStorage.setItem('admin_scroll_to_results', 'true');
        const input = document.getElementById('adminAdsSearchInput');
        if (input) {
            input.value = '';
            document.getElementById('adminAdsFilterForm')?.submit();
        }
    }

    // تبديل عرض الإعلان المعلق في الربع العلوي
    function renderPendingShowcase(idx) {
        if (!pendingAdsData || pendingAdsData.length === 0) return;
        currentPendingIndex = idx;
        const item = pendingAdsData[idx];
        currentPendingAdId = item.id;

        document.getElementById('currentPendingNum').textContent = (idx + 1);
        const stepInd = document.getElementById('pendingStepIndicator');
        if (stepInd) stepInd.textContent = `${idx + 1} / ${pendingAdsData.length}`;

        document.getElementById('pendingBoxUniName').textContent = item.university_name;
        document.getElementById('pendingBoxDate').textContent = item.created_at;
        document.getElementById('pendingBoxPkg').textContent = item.package_label;
        document.getElementById('pendingBoxTitle').textContent = item.title;
        document.getElementById('pendingBoxContent').textContent = item.content;
        document.getElementById('pendingBoxPayMethod').textContent = item.pay_method;
        document.getElementById('pendingBoxPayRef').textContent = item.pay_reference;
        document.getElementById('pendingBoxAmount').textContent = item.amount + ' ر.ي';
        document.getElementById('pendingBoxUniLogo').src = item.university_logo;

        // الوسائط
        const mediaBox = document.getElementById('pendingBoxMedia');
        if (item.media_type === 'video' && item.video_url) {
            mediaBox.innerHTML = `
                <video src="${item.video_url}" autoplay muted loop playsinline style="width:100%;height:100%;object-fit:cover;"></video>
                <div style="position:absolute;bottom:8px;right:8px;background:rgba(0,0,0,0.65);padding:3px 8px;border-radius:6px;font-size:11px;color:#fff;display:flex;align-items:center;gap:4px;pointer-events:none;">
                    <i class="fa-solid fa-play" style="color:var(--accent);"></i>
                    <span>تشغيل تلقائي</span>
                </div>
            `;
        } else if (item.first_media) {
            mediaBox.innerHTML = `<img src="${item.first_media}" alt="${item.title}" style="width:100%;height:100%;object-fit:cover;" />`;
        } else {
            mediaBox.innerHTML = `
                <div style="color:#94a3b8;font-size:13px;display:flex;flex-direction:column;align-items:center;gap:6px;">
                    <i class="fa-solid fa-image" style="font-size:28px;"></i>
                    <span>لا توجد صورة</span>
                </div>
            `;
        }

        // تحديث حالة أزرار التنقل
        const btnPrev = document.getElementById('btnPrevPending');
        const btnNext = document.getElementById('btnNextPending');
        if (btnPrev) btnPrev.disabled = (idx === 0);
        if (btnNext) btnNext.disabled = (idx === pendingAdsData.length - 1);
    }

    function prevPendingAd() {
        if (currentPendingIndex > 0) {
            renderPendingShowcase(currentPendingIndex - 1);
        }
    }

    function nextPendingAd() {
        if (currentPendingIndex < pendingAdsData.length - 1) {
            renderPendingShowcase(currentPendingIndex + 1);
        }
    }

    // تبديل العرض بين البطاقات والجدول
    function switchView(type) {
        const cardsWrap = document.getElementById('cardsViewContainer');
        const tableWrap = document.getElementById('tableViewContainer');
        const btnCards = document.getElementById('btnViewCards');
        const btnTable = document.getElementById('btnViewTable');

        if (type === 'cards') {
            cardsWrap.style.display = 'block';
            tableWrap.style.display = 'none';
            btnCards.classList.add('active');
            btnTable.classList.remove('active');
        } else {
            cardsWrap.style.display = 'none';
            tableWrap.style.display = 'block';
            btnCards.classList.remove('active');
            btnTable.classList.add('active');
        }
    }

    // =============================================================
    // النافذة الاستثنائية لمعاينة وتدقيق الإعلان (Supreme Modal)
    // =============================================================
    let modalCurrentImages = [];
    let modalCurrentImageIndex = 0;

    async function viewAd(adId) {
        viewingAdId = adId;
        const modal = document.getElementById('supremeInspectionModal');
        
        // إخفاء جميع الأزرار الإجرائية مبدئياً لمنع أي وميض لحظي
        const btnApprove = document.getElementById('modalBtnApprove');
        const btnReqEdit = document.getElementById('modalBtnRequestEdit');
        const btnReject = document.getElementById('modalBtnReject');
        const btnTogglePause = document.getElementById('modalBtnTogglePause');
        if (btnApprove) btnApprove.style.display = 'none';
        if (btnReqEdit) btnReqEdit.style.display = 'none';
        if (btnReject) btnReject.style.display = 'none';
        if (btnTogglePause) btnTogglePause.style.display = 'none';

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        try {
            const res = await fetch(`/admin/advertisements/${adId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await res.json();
            if (!data.success || !data.ad) {
                showToast('تعذر جلب تفاصيل الإعلان.', 'error');
                closeSupremeModal();
                return;
            }

            const ad = data.ad;
            modalCurrentReceiptUrl = ad.receipt_url;

            // ترويسة النافذة
            document.getElementById('modalUniName').textContent = ad.university_name;
            document.getElementById('modalUniLogo').src = ad.university_logo;
            document.getElementById('modalPackageTag').textContent = ad.package_label || ad.package;
            document.getElementById('modalAdIdText').textContent = '#' + ad.id;

            const revBadge = document.getElementById('modalReviewStatusBadge');
            if (ad.review_status === 'معتمد' && ad.status === 'متوقف مؤقتا') {
                revBadge.textContent = 'متوقف مؤقتاً';
                revBadge.className = 'badge badge-orange';
            } else {
                revBadge.textContent = ad.review_status || 'قيد المراجعة';
                revBadge.className = 'badge ' + (ad.review_status === 'معتمد' ? 'badge-green' : (ad.review_status === 'مرفوض' ? 'badge-red' : (ad.review_status === 'مطلوب تعديل' ? 'badge-orange' : 'badge-yellow')));
            }

            // 1. العمود الأيمن: محاكاة بطاقة الطالب
            document.getElementById('modalLiveUniTitle').textContent = ad.university_name;
            document.getElementById('modalLiveAdTitle').textContent = ad.title;
            document.getElementById('modalLiveAdDesc').textContent = ad.content;

            const liveImg = document.getElementById('modalLiveImg');
            const liveVid = document.getElementById('modalLiveVideo');
            const galleryReel = document.getElementById('modalGalleryReel');
            const navControls = document.getElementById('modalMediaNavControls');
            const counterTag = document.getElementById('modalImgCounterTag');

            if (ad.media_type === 'video' && ad.video_url) {
                liveImg.style.display = 'none';
                if (navControls) navControls.style.display = 'none';
                if (counterTag) counterTag.style.display = 'none';
                if (galleryReel) galleryReel.style.display = 'none';

                liveVid.style.display = 'block';
                liveVid.src = ad.video_url;
                liveVid.load();
            } else {
                if (liveVid) {
                    liveVid.pause();
                    liveVid.style.display = 'none';
                }
                liveImg.style.display = 'block';

                modalCurrentImages = (ad.media_files && ad.media_files.length > 0) ? ad.media_files : (ad.first_media ? [ad.first_media] : ['/images/default-ad.jpg']);
                modalCurrentImageIndex = 0;
                setModalImageIndex(0);

                if (modalCurrentImages.length > 1) {
                    if (navControls) navControls.style.display = 'flex';
                    if (counterTag) counterTag.style.display = 'block';
                    if (galleryReel) {
                        galleryReel.style.display = 'flex';
                        galleryReel.innerHTML = modalCurrentImages.map((url, i) => `
                            <img src="${url}" class="gallery-thumb-item ${i === 0 ? 'active' : ''}" onclick="setModalImageIndex(${i})" style="width:50px;height:42px;object-fit:cover;border-radius:6px;cursor:pointer;border:2px solid ${i === 0 ? '#f0c842' : 'transparent'};" />
                        `).join('');
                    }
                } else {
                    if (navControls) navControls.style.display = 'none';
                    if (counterTag) counterTag.style.display = 'none';
                    if (galleryReel) galleryReel.style.display = 'none';
                }
            }

            // 2. العمود الأيسر: ملف التدقيق المالي والإداري
            document.getElementById('modalDossierAmount').textContent = Number(ad.amount || 0).toLocaleString('ar-YE') + ' ر.ي';
            document.getElementById('modalDossierMethod').textContent = ad.pay_method || 'حوالة بنكية';
            document.getElementById('modalDossierRef').textContent = ad.pay_reference || '—';

            // سند الدفع (منفصل ومستقل تماماً عن وسائط الإعلان)
            const receiptImg = document.getElementById('modalReceiptImg');
            const receiptEmptyText = document.getElementById('modalReceiptEmptyText');
            const receiptZoomBadge = document.getElementById('modalReceiptZoomBadge');
            if (ad.receipt_url) {
                receiptImg.src = ad.receipt_url;
                receiptImg.style.display = 'block';
                if (receiptEmptyText) receiptEmptyText.style.display = 'none';
                if (receiptZoomBadge) receiptZoomBadge.style.display = 'block';
            } else {
                receiptImg.style.display = 'none';
                if (receiptEmptyText) receiptEmptyText.style.display = 'flex';
                if (receiptZoomBadge) receiptZoomBadge.style.display = 'none';
            }

            // التوقيت بالساعة والدقيقة
            document.getElementById('modalDossierCreated').textContent = ad.created_at_time || ad.created_at || '—';
            document.getElementById('modalDossierPublished').textContent = ad.published_at_time || (ad.published_at ? ad.published_at : 'لم يُنشر بعد');

            const expTextEl = document.getElementById('modalDossierExpires');
            const expBadgeEl = document.getElementById('modalDossierRemainingBadge');

            if (ad.expires_at_time) {
                expTextEl.textContent = ad.expires_at_time;
                if (ad.is_expired) {
                    expBadgeEl.textContent = 'منتهي الصلاحية';
                    expBadgeEl.className = 'badge badge-red';
                } else {
                    expBadgeEl.textContent = ad.expires_remaining || 'نشط';
                    expBadgeEl.className = 'badge badge-green';
                }
            } else {
                expTextEl.textContent = `يبدأ فور الاعتماد (${ad.duration || 2} أيام)`;
                expBadgeEl.textContent = 'معلق';
                expBadgeEl.className = 'badge badge-yellow';
            }

            // الملاحظات
            const notesCard = document.getElementById('modalNotesCard');
            const notesBox = document.getElementById('modalDossierNotes');
            if (ad.notes) {
                notesCard.style.display = 'block';
                notesBox.textContent = ad.notes;
            } else {
                notesCard.style.display = 'none';
            }

            // إظهار الإجراءات المحددة بحسب الحالة الدقيقة بعد التحقق التام دون أي وميض
            if (ad.review_status === 'قيد المراجعة' || !ad.review_status) {
                if (btnApprove) btnApprove.style.display = 'inline-flex';
                if (btnReqEdit) btnReqEdit.style.display = 'inline-flex';
                if (btnReject) btnReject.style.display = 'inline-flex';
            } else if (ad.review_status === 'معتمد') {
                if (btnTogglePause) {
                    btnTogglePause.style.display = 'inline-flex';
                    updatePauseButtonState(ad.status);
                }
            }

        } catch (e) {
            console.error('viewAd error:', e);
            showToast('حدث خطأ أثناء تحميل بيانات الإعلان.', 'error');
        }
    }

    function setModalImageIndex(idx) {
        if (!modalCurrentImages || modalCurrentImages.length === 0) return;
        modalCurrentImageIndex = (idx + modalCurrentImages.length) % modalCurrentImages.length;
        const currentUrl = modalCurrentImages[modalCurrentImageIndex];
        const liveImg = document.getElementById('modalLiveImg');
        if (liveImg) liveImg.src = currentUrl;
        const counterTag = document.getElementById('modalImgCounterTag');
        if (counterTag) counterTag.textContent = `صورة ${modalCurrentImageIndex + 1} من ${modalCurrentImages.length}`;
        const thumbs = document.querySelectorAll('.gallery-thumb-item');
        thumbs.forEach((thumb, i) => {
            thumb.style.border = (i === modalCurrentImageIndex) ? '2px solid #f0c842' : '2px solid transparent';
            thumb.classList.toggle('active', i === modalCurrentImageIndex);
        });
    }

    function nextModalImg() {
        setModalImageIndex(modalCurrentImageIndex + 1);
    }

    function prevModalImg() {
        setModalImageIndex(modalCurrentImageIndex - 1);
    }

    function updatePauseButtonState(status) {
        const btn = document.getElementById('modalBtnTogglePause');
        const icon = document.getElementById('modalPauseIcon');
        const text = document.getElementById('modalPauseText');
        if (!btn) return;

        if (status === 'متوقف مؤقتا') {
            btn.style.background = '#059669';
            btn.style.color = '#ffffff';
            if (icon) icon.className = 'fa-solid fa-play';
            if (text) text.textContent = 'استئناف عرض الإعلان';
        } else {
            btn.style.background = '#d97706';
            btn.style.color = '#ffffff';
            if (icon) icon.className = 'fa-solid fa-pause';
            if (text) text.textContent = 'إيقاف الإعلان مؤقتاً';
        }
    }

    async function togglePauseAd(adId) {
        try {
            const res = await fetch(`/admin/advertisements/${adId}/toggle-pause`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => window.location.reload(), 900);
            } else {
                showToast(data.message || 'تعذر تغيير حالة الإعلان.', 'error');
            }
        } catch (e) {
            showToast('حدث خطأ أثناء الاتصال بالخادم.', 'error');
        }
    }

    async function togglePauseAdFromModal() {
        if (!viewingAdId) return;
        try {
            const res = await fetch(`/admin/advertisements/${viewingAdId}/toggle-pause`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message, 'success');
                updatePauseButtonState(data.new_status);
                const revBadge = document.getElementById('modalReviewStatusBadge');
                if (revBadge) {
                    if (data.new_status === 'متوقف مؤقتا') {
                        revBadge.textContent = 'متوقف مؤقتاً';
                        revBadge.className = 'badge badge-orange';
                    } else {
                        revBadge.textContent = 'معتمد ونشط';
                        revBadge.className = 'badge badge-green';
                    }
                }
                const cardBtn = document.getElementById(`btnCardPause_${viewingAdId}`);
                if (cardBtn) {
                    if (data.new_status === 'متوقف مؤقتا') {
                        cardBtn.style.background = '#059669';
                        cardBtn.title = 'استئناف عرض الإعلان';
                        cardBtn.innerHTML = '<i class="fa-solid fa-play"></i>';
                    } else {
                        cardBtn.style.background = '#d97706';
                        cardBtn.title = 'إيقاف عرض الإعلان مؤقتاً';
                        cardBtn.innerHTML = '<i class="fa-solid fa-pause"></i>';
                    }
                }
                setTimeout(() => window.location.reload(), 1100);
            } else {
                showToast(data.message || 'تعذر تغيير حالة الإعلان.', 'error');
            }
        } catch (e) {
            showToast('حدث خطأ أثناء الاتصال بالخادم.', 'error');
        }
    }

    function selectModalGalleryImg(url, el) {
        document.getElementById('modalLiveImg').src = url;
        document.querySelectorAll('.gallery-thumb-item').forEach(thumb => thumb.classList.remove('active'));
        el.classList.add('active');
    }

    function closeSupremeModal() {
        document.getElementById('supremeInspectionModal').style.display = 'none';
        document.body.style.overflow = '';
        const vid = document.getElementById('modalLiveVideo');
        if (vid) {
            vid.pause();
            vid.src = '';
        }
        // إخفاء الأزرار فوراً لضمان عدم حدوث أي وميض عند الفتح مجدداً
        const btnApprove = document.getElementById('modalBtnApprove');
        const btnReqEdit = document.getElementById('modalBtnRequestEdit');
        const btnReject = document.getElementById('modalBtnReject');
        const btnTogglePause = document.getElementById('modalBtnTogglePause');
        if (btnApprove) btnApprove.style.display = 'none';
        if (btnReqEdit) btnReqEdit.style.display = 'none';
        if (btnReject) btnReject.style.display = 'none';
        if (btnTogglePause) btnTogglePause.style.display = 'none';
    }

    function copyRefFromModal() {
        const text = document.getElementById('modalDossierRef').textContent.trim();
        if (text && text !== '—') {
            navigator.clipboard.writeText(text).then(() => {
                showToast('تم نسخ رقم الحوالة بنجاح!', 'success');
            });
        }
    }

    // =============================================================
    // تكبير سند الدفع (Receipt Lightbox)
    // =============================================================
    function openReceiptLightbox(url) {
        if (!url) return;
        document.getElementById('lightboxReceiptImg').src = url;
        document.getElementById('lightboxDownloadBtn').href = url;
        openModal('receiptLightboxModal');
    }

    function closeReceiptLightbox() {
        closeModal('receiptLightboxModal');
    }

    // =============================================================
    // إجراءات الاعتماد والرفض وطلب التعديل والحذف
    // =============================================================
    async function approveAd(adId) {
        if (!confirm('هل أنت متأكد من اعتماد هذا الإعلان ونشره فوراً على المنصة؟')) return;

        try {
            const data = await adminFetch(`/admin/advertisements/${adId}/approve`, 'POST');
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'تعذر اعتماد الإعلان.', 'error');
            }
        } catch (e) {
            showToast('حدث خطأ أثناء الاتصال بالخادم.', 'error');
        }
    }

    async function approveAdFromModal() {
        if (viewingAdId) {
            await approveAd(viewingAdId);
            closeSupremeModal();
        }
    }

    function openRejectAdModal(adId) {
        viewingAdId = adId;
        document.getElementById('rejectAdReason').value = '';
        openModal('rejectAdModal');
    }

    async function submitRejectAd() {
        const reason = document.getElementById('rejectAdReason').value.trim();
        if (!reason) {
            showToast('يرجى توضيح سبب الرفض.', 'error');
            return;
        }

        const btn = document.getElementById('rejectAdBtn');
        btn.innerHTML = '<span class="spinner"></span> جارٍ الرفض...';
        btn.disabled = true;

        try {
            const data = await adminFetch(`/admin/advertisements/${viewingAdId}/reject`, 'POST', { reason });
            if (data.success) {
                closeModal('rejectAdModal');
                closeSupremeModal();
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'تعذر رفض الإعلان.', 'error');
            }
        } catch (e) {
            showToast('حدث خطأ في الاتصال.', 'error');
        } finally {
            btn.innerHTML = '<i class="fa-solid fa-ban"></i> تأكيد الرفض';
            btn.disabled = false;
        }
    }

    function openRequestEditModal(adId) {
        viewingAdId = adId;
        document.getElementById('requestEditReason').value = '';
        openModal('requestEditAdModal');
    }

    async function submitRequestEditAd() {
        const reason = document.getElementById('requestEditReason').value.trim();
        if (!reason) {
            showToast('يرجى كتابة الملاحظات والتعديلات المطلوبة.', 'error');
            return;
        }

        const btn = document.getElementById('requestEditAdBtn');
        btn.innerHTML = '<span class="spinner"></span> جارٍ الإرسال...';
        btn.disabled = true;

        try {
            const data = await adminFetch(`/admin/advertisements/${viewingAdId}/request-edit`, 'POST', { reason });
            if (data.success) {
                closeModal('requestEditAdModal');
                closeSupremeModal();
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'تعذر إرسال طلب التعديل.', 'error');
            }
        } catch (e) {
            showToast('حدث خطأ في الاتصال.', 'error');
        } finally {
            btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> إرسال الملاحظات';
            btn.disabled = false;
        }
    }

    function confirmDeleteAd(adId) {
        viewingAdId = adId;
        openModal('deleteAdModal');
    }

    async function submitDeleteAd() {
        const btn = document.getElementById('deleteAdBtn');
        btn.innerHTML = '<span class="spinner"></span> جارٍ الحذف...';
        btn.disabled = true;

        try {
            const data = await adminFetch(`/admin/advertisements/${viewingAdId}`, 'DELETE');
            if (data.success) {
                closeModal('deleteAdModal');
                closeSupremeModal();
                document.getElementById(`ad-card-${viewingAdId}`)?.remove();
                document.getElementById(`ad-table-row-${viewingAdId}`)?.remove();
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'تعذر حذف الإعلان.', 'error');
            }
        } catch (e) {
            showToast('حدث خطأ في الاتصال.', 'error');
        } finally {
            btn.innerHTML = '<i class="fa-solid fa-trash"></i> تأكيد الحذف';
            btn.disabled = false;
        }
    }
</script>
@endsection
