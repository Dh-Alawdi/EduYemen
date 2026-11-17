@extends('dashboards.admin.layout')

@section('page_title', 'إدارة المستخدمين والحسابات والصلاحيات')

@section('breadcrumb')
    <span>الرئيسية /</span>
    <span>إدارة النظام /</span>
    <strong>المستخدمون والصلاحيات</strong>
@endsection

@section('content')

<style>
    /* ==========================================================
       EduYemen Users & Accounts Management Styling - V2.0
       الهوية البصرية الأكاديمية: الكحلي الملكي الداكن + الذهبي الفاخر
       ========================================================== */

    /* 1. Hero Card (بطاقة العنوان المدمجة) */
    .users-hero-card {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 65%, #155e9c 100%);
        border-radius: var(--radius-lg);
        padding: 20px 26px;
        color: #ffffff;
        box-shadow: 0 10px 28px rgba(7, 43, 79, 0.18);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.18);
        margin-bottom: 20px;
    }

    /* أيقونة مائية واضحة في الخلفية */
    .users-hero-card::after {
        content: '\f4fe'; /* fa-users-gear */
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        left: 20px;
        bottom: -25px;
        font-size: 130px;
        color: rgba(255, 255, 255, 0.12); /* لون أيقونة خلفية واضح وجذاب */
        pointer-events: none;
        z-index: 1;
        transition: transform .3s ease;
    }

    .users-hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .users-hero-main {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .users-hero-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, #ffffff 0%, #e2e8f0 100%);
        color: var(--primary);
        font-size: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.9);
        flex-shrink: 0;
    }

    .users-hero-titles h1 {
        font-size: 20px;
        font-weight: 800;
        margin: 0;
        color: #ffffff;
        letter-spacing: -0.3px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* بيانات الإجمالي والنشاط المدمجة في بطاقة العنوان */
    .hero-embedded-stats {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 8px;
        flex-wrap: wrap;
    }

    .hero-stat-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #ffffff;
        backdrop-filter: blur(6px);
    }

    .hero-stat-pill.pill-gold {
        background: rgba(197, 155, 39, 0.25);
        border-color: rgba(240, 200, 66, 0.5);
        color: #fde047;
    }

    .hero-stat-pill.pill-cyan {
        background: rgba(14, 165, 233, 0.22);
        border-color: rgba(56, 189, 248, 0.45);
        color: #7dd3fc;
    }

    /* أزرار الإجراءات في الهيدر (مصغرة وزرين على الأقل في الصف) */
    .users-hero-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .btn-hero-compact {
        font-size: 12.5px;
        font-weight: 700;
        padding: 8px 16px;
        border-radius: 10px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
        transition: all .2s ease;
        text-decoration: none;
        white-space: nowrap;
    }

    .btn-hero-compact-primary {
        background: linear-gradient(135deg, #c59b27 0%, #e4b75b 100%);
        color: #072b4f;
        box-shadow: 0 4px 14px rgba(197, 155, 39, 0.35);
    }

    .btn-hero-compact-primary:hover {
        transform: translateY(-2px);
        color: #072b4f;
        box-shadow: 0 6px 18px rgba(197, 155, 39, 0.45);
    }

    .btn-hero-compact-secondary {
        background: rgba(255, 255, 255, 0.16);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(8px);
    }

    .btn-hero-compact-secondary:hover {
        background: rgba(255, 255, 255, 0.26);
        color: #ffffff;
        transform: translateY(-2px);
    }

    /* 2. مؤشرات الحالة التشغيلية (3 بطاقات في صف واحد على كافة الشاشات) */
    .kpi-trio-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }

    .kpi-trio-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 12px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: var(--shadow-sm);
        transition: all .2s ease;
        text-decoration: none;
        color: inherit;
        position: relative;
        overflow: hidden;
    }

    .kpi-trio-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        border-color: var(--primary-light);
    }

    .kpi-trio-card.active-card {
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(11, 61, 107, 0.15);
        background: #fbfdff;
    }

    .kpi-trio-info {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
    }

    .kpi-trio-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
        transition: transform .2s ease;
    }

    .kpi-trio-card:hover .kpi-trio-icon {
        transform: scale(1.05);
    }

    .kpi-trio-univ .kpi-trio-icon { background: #eff6ff; color: #1d4ed8; }
    .kpi-trio-admin .kpi-trio-icon { background: #faf5ff; color: #7e22ce; }
    .kpi-trio-student .kpi-trio-icon { background: #f0fdfa; color: #0d9488; }

    .kpi-trio-text h4 {
        font-size: 13px;
        font-weight: 700;
        color: var(--ink-muted);
        margin: 0 0 3px;
        white-space: nowrap;
    }

    .kpi-trio-text .kpi-number {
        font-size: 20px;
        font-weight: 900;
        color: var(--ink);
        font-family: 'Plus Jakarta Sans', 'Almarai', sans-serif;
        line-height: 1;
    }

    .kpi-trio-arrow {
        color: var(--ink-light);
        font-size: 12px;
        transition: transform .2s;
        margin-right: auto;
    }

    .kpi-trio-card:hover .kpi-trio-arrow {
        transform: translateX(-4px);
        color: var(--primary);
    }

    /* التجاوب في الشاشات الصغيرة: البقاء في صف واحد مع تصغير الخط والحجم */
    @media (max-width: 768px) {
        .kpi-trio-grid {
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 6px !important;
            margin-bottom: 14px !important;
        }

        .kpi-trio-card {
            padding: 8px 6px !important;
            border-radius: 10px !important;
            text-align: center;
            justify-content: center;
        }

        .kpi-trio-info {
            gap: 6px !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .kpi-trio-icon {
            width: 28px !important;
            height: 28px !important;
            font-size: 12px !important;
            border-radius: 7px !important;
        }

        .kpi-trio-text h4 {
            font-size: 11px !important;
            margin: 0 0 2px !important;
            white-space: nowrap !important;
            letter-spacing: -0.2px;
        }

        .kpi-trio-text .kpi-number {
            font-size: 15px !important;
        }

        .kpi-trio-arrow {
            display: none !important;
        }
    }

    /* 3. حاوية البحث والفلترة المدمجة (Search Bar First, then Buttons) */
    .search-filter-panel {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 18px 20px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 22px;
    }

    /* شريط البحث المطور في الأعلى */
    .modern-search-bar-wrap {
        margin-bottom: 14px;
    }

    .search-form-flex {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f8fafc;
        border: 1.5px solid var(--border);
        border-radius: 12px;
        padding: 4px 6px 4px 14px;
        transition: all .2s ease;
    }

    .search-form-flex:focus-within {
        background: #ffffff;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(11, 61, 107, 0.1);
    }

    .search-form-flex i.search-leading-icon {
        color: var(--ink-muted);
        font-size: 15px;
    }

    .search-form-flex input {
        flex: 1;
        border: none;
        background: transparent;
        padding: 9px 8px;
        font-size: 13.5px;
        font-family: inherit;
        color: var(--ink);
        outline: none;
    }

    .btn-search-submit {
        background: var(--primary);
        color: #ffffff;
        border: none;
        border-radius: 8px;
        padding: 7px 18px;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background .15s;
    }

    .btn-search-submit:hover {
        background: var(--primary-dark);
    }

    .btn-search-clear {
        background: transparent;
        border: none;
        color: var(--ink-muted);
        cursor: pointer;
        padding: 6px 8px;
        font-size: 13px;
        display: none;
    }

    .btn-search-clear:hover {
        color: var(--danger);
    }

    /* مصفوفة أزرار الفلترة الأنيقة تحت البحث */
    .filter-matrix-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 4px;
        scrollbar-width: thin;
    }

    .btn-filter-pill {
        padding: 8px 14px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        color: var(--ink-muted);
        background: var(--surface-alt);
        border: 1px solid var(--border);
        display: inline-flex;
        align-items: center;
        gap: 7px;
        text-decoration: none;
        white-space: nowrap;
        transition: all .15s ease;
        flex-shrink: 0;
    }

    .btn-filter-pill:hover {
        color: var(--primary);
        border-color: var(--primary-light);
        background: #ffffff;
    }

    .btn-filter-pill.active {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
        color: #ffffff;
        border-color: var(--primary-dark);
        box-shadow: 0 4px 12px rgba(7, 43, 79, 0.18);
    }

    .btn-filter-pill .pill-counter {
        background: rgba(0, 0, 0, 0.06);
        padding: 1px 7px;
        border-radius: 20px;
        font-size: 11px;
    }

    .btn-filter-pill.active .pill-counter {
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff;
    }

    .btn-filter-reset {
        margin-right: auto;
        padding: 7px 12px;
        border-radius: 8px;
        font-size: 11.5px;
        font-weight: 700;
        color: var(--danger);
        background: #fef2f2;
        border: 1px solid #fecaca;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    /* 4. شريط الإجراءات الجماعية */
    .bulk-actions-floating-strip {
        background: linear-gradient(135deg, #072b4f, #0b3d6b);
        color: #ffffff;
        border-radius: var(--radius-md);
        padding: 10px 18px;
        margin-bottom: 16px;
        display: none;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        box-shadow: 0 8px 22px rgba(7, 43, 79, 0.22);
        border: 1px solid rgba(228, 183, 91, 0.4);
    }

    /* 5. جدول المستخدمين المطور */
    .users-table-container {
        background: var(--surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .users-table-header-strip {
        padding: 14px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        background: #f8fafc;
        border-bottom: 1px solid var(--border-light);
    }

    .users-table-main {
        width: 100%;
        border-collapse: collapse;
        text-align: right;
    }

    .users-table-main th {
        background: #f8fafc;
        padding: 12px 16px;
        font-size: 12px;
        font-weight: 800;
        color: var(--ink-muted);
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    .users-table-main td {
        padding: 13px 16px;
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
        font-size: 13px;
        color: var(--ink);
    }

    /* تظليل الصفوف حسب حالة الحساب */
    tr.user-row-active {
        background: rgba(22, 163, 74, 0.035) !important;
        border-right: 4px solid #16a34a !important;
    }

    tr.user-row-suspended {
        background: rgba(220, 38, 38, 0.045) !important;
        border-right: 4px solid #dc2626 !important;
    }

    tr.user-row-active:hover {
        background: rgba(22, 163, 74, 0.075) !important;
    }

    tr.user-row-suspended:hover {
        background: rgba(220, 38, 38, 0.08) !important;
    }

    /* هوية المستخدم والأفاتار */
    .user-avatar-initial {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-weight: 800;
        font-size: 15px;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .user-display-name-only {
        font-size: 13.5px;
        font-weight: 800;
        color: var(--ink);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* نوع المستخدم كنص عادي لطيف مع أيقونة */
    .user-role-text-line {
        font-size: 11.5px;
        font-weight: 600;
        color: var(--ink-muted);
        margin-top: 3px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .user-role-text-line i {
        font-size: 11px;
    }

    .user-role-text-line.role-admin { color: #7e22ce; }
    .user-role-text-line.role-university { color: #1d4ed8; }
    .user-role-text-line.role-student { color: #0d9488; }

    /* شبكة الإجراءات في الشاشات الكبيرة (2 buttons per row) */
    .desktop-actions-grid {
        display: grid;
        grid-template-columns: repeat(2, 32px);
        gap: 5px;
        justify-content: center;
    }

    .btn-table-action-sq {
        width: 32px;
        height: 30px;
        border-radius: 8px;
        border: 1px solid var(--border);
        background: #ffffff;
        color: var(--ink-muted);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        cursor: pointer;
        transition: all .15s ease;
        text-decoration: none;
    }

    .btn-table-action-sq:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    }

    .btn-table-action-sq.act-view:hover { color: #0284c7; border-color: #bae6fd; background: #f0f9ff; }
    .btn-table-action-sq.act-edit:hover { color: #d97706; border-color: #fde68a; background: #fffbeb; }
    .btn-table-action-sq.act-key:hover { color: #c59b27; border-color: #fef08a; background: #fefce8; }
    .btn-table-action-sq.act-toggle:hover { color: #16a34a; border-color: #bbf7d0; background: #f0fdf4; }
    .btn-table-action-sq.act-delete:hover { color: #dc2626; border-color: #fecaca; background: #fef2f2; }

    /* زر الإجراءات الموحد في الشاشات الصغيرة */
    .mobile-actions-single-btn {
        display: none;
        background: var(--surface-alt);
        border: 1px solid var(--border);
        color: var(--primary);
        font-size: 12px;
        font-weight: 700;
        padding: 6px 12px;
        border-radius: 8px;
        cursor: pointer;
        align-items: center;
        gap: 6px;
    }

    .mobile-actions-single-btn:hover {
        background: #ffffff;
        border-color: var(--primary-light);
    }

    @media (max-width: 768px) {
        .desktop-actions-grid { display: none !important; }
        .mobile-actions-single-btn { display: inline-flex !important; }
        .col-hide-mobile { display: none !important; }
    }

    /* أسفل الجدول والتنقل بين كمية المستخدمين والترقيم المخصص */
    .custom-pagination-toolbar {
        padding: 14px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        background: #fafcff;
        border-top: 1px solid var(--border-light);
    }

    .per-page-container {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .per-page-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--ink-muted);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .per-page-btn-group {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: var(--surface);
        padding: 2px 4px;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .btn-page-amount {
        padding: 4px 9px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 800;
        color: var(--ink-muted);
        text-decoration: none;
        transition: all .15s ease;
        line-height: 1.2;
    }

    .btn-page-amount:hover {
        color: var(--primary);
        background: rgba(11, 61, 107, 0.06);
    }

    .btn-page-amount.active {
        background: var(--primary);
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(11, 61, 107, 0.25);
    }

    .pagination-nav-container {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .pagination-summary-text {
        font-size: 12px;
        color: var(--ink-muted);
    }

    .pagination-summary-text strong {
        color: var(--ink);
    }

    .pagination-pages-pills {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .page-nav-pill {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        color: var(--primary);
        background: var(--surface);
        border: 1px solid var(--border);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all .15s ease;
    }

    .page-nav-pill:hover:not(.disabled) {
        background: var(--primary);
        color: #ffffff;
        border-color: var(--primary);
        box-shadow: 0 2px 8px rgba(11, 61, 107, 0.2);
    }

    .page-nav-pill.disabled {
        opacity: 0.45;
        cursor: not-allowed;
        color: var(--ink-light);
        background: var(--surface-alt);
    }

    .page-num-pill {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12.5px;
        font-weight: 800;
        font-family: 'Plus Jakarta Sans', 'Almarai', sans-serif;
        color: var(--ink);
        background: var(--surface);
        border: 1px solid var(--border);
        text-decoration: none;
        transition: all .15s ease;
    }

    .page-num-pill:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: rgba(11, 61, 107, 0.04);
    }

    .page-num-pill.active {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
        color: #ffffff;
        border-color: var(--primary-dark);
        box-shadow: 0 4px 10px rgba(7, 43, 79, 0.25);
    }

    .page-num-dots {
        color: var(--ink-muted);
        font-size: 13px;
        padding: 0 4px;
        letter-spacing: 2px;
    }

    @media (max-width: 768px) {
        .custom-pagination-toolbar {
            flex-direction: column;
            align-items: center;
            gap: 12px;
            padding: 12px 10px;
        }

        .pagination-nav-container {
            flex-direction: column;
            gap: 8px;
            width: 100%;
            text-align: center;
        }

        .pagination-pages-pills {
            width: 100%;
            justify-content: center;
            flex-wrap: wrap;
        }
    }

    /* نافذة الإجراءات الموحدة للشاشات الصغيرة */
    .mobile-action-card-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: var(--surface);
        text-decoration: none;
        color: var(--ink);
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background .15s;
        margin-bottom: 8px;
        width: 100%;
        text-align: right;
    }

    .mobile-action-card-item:hover {
        background: var(--surface-alt);
    }

    .mobile-action-card-item .m-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }

    /* Custom Checkbox */
    .custom-check {
        width: 17px;
        height: 17px;
        accent-color: var(--primary);
        cursor: pointer;
    }
</style>

{{-- 1. بطاقة العنوان المدمجة (Merged Hero Card) --}}
<div class="users-hero-card">
    <div class="users-hero-content">
        <div class="users-hero-main">
            <div class="users-hero-icon-box">
                <i class="fa-solid fa-users-gear"></i>
            </div>
            <div class="users-hero-titles">
                <h1>إدارة المستخدمين والحسابات والصلاحيات</h1>
                {{-- إجمالي الحسابات والنشطون مدمجة مع العنوان --}}
                <div class="hero-embedded-stats">
                    <span class="hero-stat-pill pill-gold">
                        <i class="fa-solid fa-users"></i>
                        <span>إجمالي الحسابات المسجلة: <strong>{{ number_format($stats['total']) }}</strong></span>
                    </span>
                    <span class="hero-stat-pill pill-cyan" title="عدد الحسابات التي قامت بتسجيل الدخول خلال آخر 7 أيام فعلياً">
                        <i class="fa-solid fa-bolt"></i>
                        <span><strong>{{ number_format($stats['recent_active']) }}</strong> سجلوا الدخول خلال آخر 7 أيام</span>
                    </span>
                </div>
            </div>
        </div>

        {{-- أزرار الإجراءات في الهيدر (زرين في الصف الواحد بحجم مصغر) --}}
        <div class="users-hero-actions">
            <button type="button" class="btn-hero-compact btn-hero-compact-primary" onclick="openCreateUserModal()">
                <i class="fa-solid fa-user-plus"></i>
                <span>إضافة مستخدم</span>
            </button>
            <a href="{{ route('admin.users.export', request()->query()) }}" class="btn-hero-compact btn-hero-compact-secondary" title="تصدير بيانات المستخدمين">
                <i class="fa-solid fa-file-arrow-down"></i>
                <span>تصدير CSV</span>
            </a>
        </div>
    </div>
</div>

{{-- 2. مؤشرات الحالة التشغيلية (3 بطاقات فقط: جامعات، مدراء، طلاب) --}}
@php
    $activeFilterType = request('type');
@endphp
<div class="kpi-trio-grid">
    {{-- 1. الجامعات --}}
    <a href="{{ route('admin.users', array_merge(request()->except(['type', 'page']), ['type' => 'university'])) }}" class="kpi-trio-card kpi-trio-univ {{ $activeFilterType === 'university' ? 'active-card' : '' }}" title="تصفية الجامعات">
        <div class="kpi-trio-info">
            <div class="kpi-trio-icon">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <div class="kpi-trio-text">
                <h4>الجامعات</h4>
                <div class="kpi-number">{{ number_format($stats['universities']) }}</div>
            </div>
        </div>
        <div class="kpi-trio-arrow"><i class="fa-solid fa-chevron-left"></i></div>
    </a>

    {{-- 2. الإدارة --}}
    <a href="{{ route('admin.users', array_merge(request()->except(['type', 'page']), ['type' => 'admin'])) }}" class="kpi-trio-card kpi-trio-admin {{ $activeFilterType === 'admin' ? 'active-card' : '' }}" title="تصفية الإدارة">
        <div class="kpi-trio-info">
            <div class="kpi-trio-icon">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div class="kpi-trio-text">
                <h4>الإدارة</h4>
                <div class="kpi-number">{{ number_format($stats['admins']) }}</div>
            </div>
        </div>
        <div class="kpi-trio-arrow"><i class="fa-solid fa-chevron-left"></i></div>
    </a>

    {{-- 3. الطلاب --}}
    <a href="{{ route('admin.users', array_merge(request()->except(['type', 'page']), ['type' => 'student'])) }}" class="kpi-trio-card kpi-trio-student {{ $activeFilterType === 'student' ? 'active-card' : '' }}" title="تصفية الطلاب">
        <div class="kpi-trio-info">
            <div class="kpi-trio-icon">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div class="kpi-trio-text">
                <h4>الطلاب</h4>
                <div class="kpi-number">{{ number_format($stats['students']) }}</div>
            </div>
        </div>
        <div class="kpi-trio-arrow"><i class="fa-solid fa-chevron-left"></i></div>
    </a>
</div>

{{-- 3. حاوية البحث ومصفوفة أزرار الفلترة المدمجة (Search First, then Buttons) --}}
<div class="search-filter-panel" id="search-filter-section">
    {{-- أولاً: شريط البحث المطور --}}
    <div class="modern-search-bar-wrap">
        <form method="GET" action="{{ route('admin.users') }}" id="usersSearchForm">
            {{-- الاحتفاظ بالفلاتر النشطة عند البحث --}}
            @if(request('type')) <input type="hidden" name="type" value="{{ request('type') }}"> @endif
            @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
            @if(request('security')) <input type="hidden" name="security" value="{{ request('security') }}"> @endif
            @if(request('per_page')) <input type="hidden" name="per_page" value="{{ request('per_page') }}"> @endif

            <div class="search-form-flex">
                <i class="fa-solid fa-magnifying-glass search-leading-icon"></i>
                <input type="text" name="search" id="usersSearchInput" placeholder="بحث سريع بالاسم الكامل، الهاتف، البريد الإلكتروني..." value="{{ request('search') }}" autocomplete="off">
                <button type="button" class="btn-search-clear" id="btnSearchClear" onclick="clearSearchField()" title="مسح حقل البحث">
                    <i class="fa-solid fa-circle-xmark"></i>
                </button>
                <button type="submit" class="btn-search-submit">
                    <i class="fa-solid fa-search"></i>
                    <span>بحث</span>
                </button>
            </div>
        </form>
    </div>

    {{-- ثانياً: مصفوفة أزرار الفلترة الأنيقة تحت شريط البحث مباشرة بأيقونات حديثة --}}
    @php
        $currType = request('type');
        $currStatus = request('status');
        $currSecurity = request('security');
    @endphp
    <div class="filter-matrix-wrap">
        {{-- كافة الحسابات --}}
        <a href="{{ route('admin.users', request()->only(['per_page', 'search'])) }}" class="btn-filter-pill {{ empty($currType) && empty($currStatus) && empty($currSecurity) ? 'active' : '' }}">
            <i class="fa-solid fa-layer-group"></i>
            <span>كافة الحسابات</span>
            <span class="pill-counter">{{ $stats['total'] }}</span>
        </a>

        {{-- مدراء النظام --}}
        <a href="{{ route('admin.users', array_merge(request()->only(['per_page', 'search']), ['type' => 'admin'])) }}" class="btn-filter-pill {{ $currType === 'admin' ? 'active' : '' }}">
            <i class="fa-solid fa-user-shield"></i>
            <span>مدراء النظام</span>
            <span class="pill-counter">{{ $stats['admins'] }}</span>
        </a>

        {{-- مسؤولو الجامعات --}}
        <a href="{{ route('admin.users', array_merge(request()->only(['per_page', 'search']), ['type' => 'university'])) }}" class="btn-filter-pill {{ $currType === 'university' ? 'active' : '' }}">
            <i class="fa-solid fa-building-columns"></i>
            <span>مسؤولو الجامعات</span>
            <span class="pill-counter">{{ $stats['universities'] }}</span>
        </a>

        {{-- الطلاب المسجلين --}}
        <a href="{{ route('admin.users', array_merge(request()->only(['per_page', 'search']), ['type' => 'student'])) }}" class="btn-filter-pill {{ $currType === 'student' ? 'active' : '' }}">
            <i class="fa-solid fa-graduation-cap"></i>
            <span>الطلاب المسجلين</span>
            <span class="pill-counter">{{ $stats['students'] }}</span>
        </a>

        {{-- الحسابات المعلقة --}}
        <a href="{{ route('admin.users', array_merge(request()->only(['per_page', 'search']), ['status' => 'suspended'])) }}" class="btn-filter-pill {{ $currStatus === 'suspended' ? 'active' : '' }}">
            <i class="fa-solid fa-user-lock"></i>
            <span>الحسابات المعلقة</span>
            <span class="pill-counter">{{ $stats['suspended'] }}</span>
        </a>

        {{-- كلمات مرور مؤقتة --}}
        <a href="{{ route('admin.users', array_merge(request()->only(['per_page', 'search']), ['security' => 'temp_pass'])) }}" class="btn-filter-pill {{ $currSecurity === 'temp_pass' ? 'active' : '' }}">
            <i class="fa-solid fa-key"></i>
            <span>كلمات مرور مؤقتة</span>
            <span class="pill-counter">{{ $stats['must_change_password'] }}</span>
        </a>

        {{-- زر إلغاء التصفية إن وجد فلتر مفعل --}}
        @if(request()->anyFilled(['search', 'type', 'status', 'security']))
            <a href="{{ route('admin.users') }}" class="btn-filter-reset" title="إعادة تعيين كافة الفلاتر والبحث">
                <i class="fa-solid fa-rotate-left"></i>
                إلغاء التصفية
            </a>
        @endif
    </div>
</div>

{{-- 4. شريط الإجراءات الجماعية العائم --}}
<div class="bulk-actions-floating-strip" id="bulkActionsStrip">
    <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700;">
        <i class="fa-solid fa-check-double" style="color: var(--accent);"></i>
        <span>تم تحديد <strong id="bulkSelectedNumber" style="color: #fde047; font-size: 14px;">0</strong> حساب</span>
    </div>
    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
        <button type="button" class="btn btn-xs" onclick="executeBulkAction('activate')" style="background: #15803d; color: #fff; font-weight: 700; border: none; border-radius: 6px; padding: 6px 12px;">
            <i class="fa-solid fa-circle-check me-1"></i> تنشيط
        </button>
        <button type="button" class="btn btn-xs" onclick="executeBulkAction('suspend')" style="background: #d97706; color: #fff; font-weight: 700; border: none; border-radius: 6px; padding: 6px 12px;">
            <i class="fa-solid fa-ban me-1"></i> تعليق
        </button>
        <button type="button" class="btn btn-xs" onclick="executeBulkAction('delete')" style="background: #dc2626; color: #fff; font-weight: 700; border: none; border-radius: 6px; padding: 6px 12px;">
            <i class="fa-solid fa-trash-can me-1"></i> حذف
        </button>
        <button type="button" class="btn btn-ghost btn-xs" onclick="clearBulkSelection()" style="color: #cbd5e1; border-color: rgba(255,255,255,0.2);">
            إلغاء
        </button>
    </div>
</div>

{{-- 5. جدول سجل الحسابات والمستخدمين (User Records Table) --}}
<div class="users-table-container" id="users-data-table-section">
    <div class="users-table-header-strip">
        <div style="font-size: 14px; font-weight: 800; color: var(--primary-dark); display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-address-book"></i>
            <span>سجل الحسابات والبيانات</span>
            <span style="font-size: 11.5px; font-weight: 600; color: var(--ink-muted);">
                (معروض {{ $users->firstItem() ?? 0 }} إلى {{ $users->lastItem() ?? 0 }} من أصل {{ $users->total() }})
            </span>
        </div>
        <div style="font-size: 11.5px; color: var(--ink-muted);">
            <i class="fa-solid fa-circle-info me-1" style="color: var(--primary);"></i>
            الصف الأخضر: حساب نشط ومفعل | الصف الوردي: حساب معلق
        </div>
    </div>

    @if($users->isEmpty())
        <div style="text-align: center; padding: 50px 20px;">
            <div style="width: 65px; height: 65px; border-radius: 18px; background: rgba(11, 61, 107, 0.08); color: var(--primary); display: inline-flex; align-items: center; justify-content: center; font-size: 28px; margin-bottom: 14px;">
                <i class="fa-solid fa-user-xmark"></i>
            </div>
            <h3 style="font-size: 16px; font-weight: 800; color: var(--ink); margin-bottom: 6px;">لا توجد حسابات تطابق معايير البحث أو التصفية</h3>
            <p style="font-size: 13px; color: var(--ink-muted); max-width: 420px; margin: 0 auto 16px;">
                جرب تغيير كلمة البحث أو الضغط على زر إلغاء التصفية لعرض كافة الحسابات.
            </p>
            <a href="{{ route('admin.users') }}" class="btn btn-primary" style="font-size: 12.5px; padding: 8px 18px;">
                <i class="fa-solid fa-rotate-left me-1"></i> عرض كافة الحسابات
            </a>
        </div>
    @else
        <div style="overflow-x: auto;">
            <table class="users-table-main">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" class="custom-check" id="masterCheckbox" onchange="toggleMasterCheck(this)" title="تحديد الكل في هذه الصفحة">
                        </th>
                        <th>المستخدم والبيانات الشخصية</th>
                        <th class="col-hide-mobile">معلومات الاتصال</th>
                        <th class="col-hide-mobile">آخر ظهور وتاريخ الانضمام</th>
                        <th style="text-align: center; width: 100px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        @php
                            $displayName = match($u->user_type) {
                                'admin'      => $u->admin?->full_name ?: 'مسؤول نظام',
                                'university' => $u->university?->university_name ?: 'صرح جامعي',
                                'student'    => $u->student?->full_name ?: 'طالب مسجل',
                                default      => 'مستخدم منصة',
                            };
                            $initial = mb_substr($displayName, 0, 1, 'utf-8');
                            $gradient = match($u->user_type) {
                                'admin'      => 'linear-gradient(135deg, #7c3aed, #4c1d95)',
                                'university' => 'linear-gradient(135deg, #0b3d6b, #1d4ed8)',
                                'student'    => 'linear-gradient(135deg, #0d9488, #0f766e)',
                                default      => 'linear-gradient(135deg, #64748b, #334155)',
                            };
                            $rowClass = $u->account_status === 'active' ? 'user-row-active' : 'user-row-suspended';
                            $roleIcon = match($u->user_type) {
                                'admin'      => 'fa-solid fa-user-shield',
                                'university' => 'fa-solid fa-building-columns',
                                'student'    => 'fa-solid fa-graduation-cap',
                                default      => 'fa-solid fa-user',
                            };
                            $roleLabel = match($u->user_type) {
                                'admin'      => 'مدير نظام',
                                'university' => 'مسؤول جامعة',
                                'student'    => 'طالب مسجل',
                                default      => $u->user_type,
                            };
                            $roleCss = match($u->user_type) {
                                'admin'      => 'role-admin',
                                'university' => 'role-university',
                                'student'    => 'role-student',
                                default      => '',
                            };
                        @endphp
                        <tr id="user-row-{{ $u->user_id }}" class="{{ $rowClass }}">
                            {{-- صندوق الاختيار --}}
                            <td style="text-align: center;">
                                @if($u->user_id !== Auth::id())
                                    <input type="checkbox" class="custom-check single-user-check" value="{{ $u->user_id }}" onchange="handleSingleCheck(this)">
                                @else
                                    <i class="fa-solid fa-lock" style="color: #cbd5e1; font-size: 12px;" title="حسابك الإداري الحالي محمي"></i>
                                @endif
                            </td>

                            {{-- المستخدم والبيانات الشخصية (بدون اسم الدخول username، فقط الاسم + الدور كنص عادي) --}}
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div class="user-avatar-initial" style="background: {{ $gradient }};">
                                        {{ $initial }}
                                    </div>
                                    <div>
                                        <div class="user-display-name-only">
                                            <span>{{ $displayName }}</span>
                                            @if($u->user_id === Auth::id())
                                                <span class="badge" style="background: rgba(11, 61, 107, 0.1); color: var(--primary); font-size: 9.5px; padding: 2px 7px; border-radius: 12px;">
                                                    أنت
                                                </span>
                                            @endif
                                            @if($u->must_change_password)
                                                <span class="badge" style="background: #fef3c7; color: #b45309; font-size: 9.5px; padding: 1px 6px; border-radius: 4px;" title="يلزمه تغيير كلمة المرور">
                                                    <i class="fa-solid fa-key" style="font-size: 8px;"></i> مؤقتة
                                                </span>
                                            @endif
                                        </div>
                                        {{-- نوع المستخدم في عمود البيانات الشخصية كنص عادي لطيف مع أيقونة --}}
                                        <div class="user-role-text-line {{ $roleCss }}">
                                            <i class="{{ $roleIcon }}"></i>
                                            <span>{{ $roleLabel }}</span>
                                            <span style="color: var(--ink-light); font-size: 10px; margin-right: 4px;">#{{ $u->user_id }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- معلومات الاتصال --}}
                            <td class="col-hide-mobile">
                                @if($u->phone)
                                    <div style="font-size: 12px; display: flex; align-items: center; gap: 6px;">
                                        <i class="fa-solid fa-phone" style="font-size: 10px; color: var(--primary);"></i>
                                        <a href="tel:{{ $u->phone }}" dir="ltr" style="font-family: monospace; color: var(--ink); text-decoration: none; font-weight: 700;">{{ $u->phone }}</a>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $u->phone) }}" target="_blank" style="color: #16a34a; font-size: 11px;" title="محادثة واتساب">
                                            <i class="fa-brands fa-whatsapp"></i>
                                        </a>
                                    </div>
                                @else
                                    <span style="color: var(--ink-light); font-size: 11.5px;">— لا يوجد هاتف —</span>
                                @endif

                                @if($u->email)
                                    <div style="font-size: 11.5px; color: var(--ink-muted); margin-top: 3px; display: flex; align-items: center; gap: 6px;">
                                        <i class="fa-regular fa-envelope" style="font-size: 10px;"></i>
                                        <a href="mailto:{{ $u->email }}" dir="ltr" style="color: var(--ink-muted); text-decoration: none;">{{ $u->email }}</a>
                                    </div>
                                @endif
                            </td>

                            {{-- آخر ظهور وتاريخ الانضمام --}}
                            <td class="col-hide-mobile">
                                <div style="font-size: 12px; font-weight: 600; color: var(--ink); display: flex; align-items: center; gap: 5px;" title="{{ $u->last_login ? \Carbon\Carbon::parse($u->last_login)->format('Y-m-d H:i') : '' }}">
                                    <i class="fa-regular fa-clock" style="font-size: 11px; color: var(--ink-muted);"></i>
                                    <span>{{ $u->last_login ? \Carbon\Carbon::parse($u->last_login)->diffForHumans() : 'لم يسجل دخول بعد' }}</span>
                                </div>
                                <div style="font-size: 11px; color: var(--ink-muted); margin-top: 2px;">
                                    انضم: {{ $u->created_at ? $u->created_at->format('Y-m-d') : '—' }}
                                </div>
                            </td>

                            {{-- عمود الإجراءات: في الشاشات الكبيرة سطرين (زرين لكل سطر)، وفي الصغيرة زر واحد فقط يفتح نافذة لكافة الإجراءات --}}
                            <td style="text-align: center;">
                                {{-- 1. الشاشات الكبيرة: كل إجراءين في سطر واحد --}}
                                <div class="desktop-actions-grid">
                                    {{-- السطر 1: معاينة + تعديل --}}
                                    <button type="button" class="btn-table-action-sq act-view" onclick="openViewUserModal({{ $u->user_id }})" title="معاينة الملف الشامل">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn-table-action-sq act-edit" onclick="openEditUserModal({{ $u->user_id }})" title="تعديل بيانات الحساب">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    {{-- السطر 2: كلمة المرور + تبديل الحالة أو الحذف --}}
                                    <button type="button" class="btn-table-action-sq act-key" onclick="openResetPasswordModal({{ $u->user_id }}, '{{ addslashes($displayName) }}')" title="إعادة تعيين كلمة المرور">
                                        <i class="fa-solid fa-key"></i>
                                    </button>

                                    @if($u->user_id !== Auth::id())
                                        <button type="button" class="btn-table-action-sq act-toggle" onclick="toggleUserStatusDirect({{ $u->user_id }})" title="{{ $u->account_status === 'active' ? 'تعليق الحساب' : 'تنشيط الحساب' }}">
                                            <i class="fa-solid {{ $u->account_status === 'active' ? 'fa-pause' : 'fa-play' }}"></i>
                                        </button>
                                    @else
                                        <span class="btn-table-action-sq" style="opacity: 0.3; cursor: not-allowed;" title="محمي">
                                            <i class="fa-solid fa-lock"></i>
                                        </span>
                                    @endif
                                </div>

                                {{-- 2. الشاشات الصغيرة: زر واحد فقط (أيقونة العين) يفتح نافذة متكاملة لكافة الإجراءات --}}
                                <button type="button" class="mobile-actions-single-btn" onclick="openMobileActionsSheet({{ $u->user_id }}, '{{ addslashes($displayName) }}', '{{ $roleLabel }}', '{{ $u->account_status }}')" title="كافة إجراءات الحساب">
                                    <i class="fa-solid fa-eye"></i>
                                    <span>إجراءات</span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- أسفل الجدول: أزرار كمية المستخدمين وترقيم الصفحات المطور --}}
        <div class="custom-pagination-toolbar">
            {{-- 1. اختيار كمية المستخدمين المعروضة (10 / 15 / 25 / 50 / 100) --}}
            <div class="per-page-container">
                <span class="per-page-label">
                    <i class="fa-solid fa-list-ol"></i>
                    <span>عرض بالصفحة:</span>
                </span>
                <div class="per-page-btn-group">
                    @php
                        $currentPerPage = (int)request('per_page', 15);
                    @endphp
                    @foreach([10, 15, 25, 50, 100] as $amount)
                        <a href="{{ route('admin.users', array_merge(request()->except(['per_page', 'page']), ['per_page' => $amount])) }}" class="btn-page-amount {{ $currentPerPage === $amount ? 'active' : '' }}">
                            {{ $amount }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- 2. ترقيم الصفحات وأزرار التنقل الصريحة بدون أي نصوص إنجليزية أو تشوهات --}}
            @if($users->total() > 0)
                <div class="pagination-nav-container">
                    <div class="pagination-summary-text">
                        معروض <strong>{{ $users->firstItem() }}</strong> إلى <strong>{{ $users->lastItem() }}</strong> من أصل <strong>{{ number_format($users->total()) }}</strong> حساب
                    </div>

                    @if($users->hasPages())
                        <div class="pagination-pages-pills">
                            {{-- زر السابق --}}
                            @if($users->onFirstPage())
                                <span class="page-nav-pill disabled" title="الصفحة الأولى">
                                    <i class="fa-solid fa-chevron-right"></i>
                                    <span>السابق</span>
                                </span>
                            @else
                                <a href="{{ $users->previousPageUrl() }}" class="page-nav-pill" title="الصفحة السابقة">
                                    <i class="fa-solid fa-chevron-right"></i>
                                    <span>السابق</span>
                                </a>
                            @endif

                            {{-- أرقام الصفحات الذكية --}}
                            @php
                                $currentPage = $users->currentPage();
                                $lastPage = $users->lastPage();
                                $start = max(1, $currentPage - 2);
                                $end = min($lastPage, $currentPage + 2);
                            @endphp

                            @if($start > 1)
                                <a href="{{ $users->url(1) }}" class="page-num-pill {{ $currentPage == 1 ? 'active' : '' }}">1</a>
                                @if($start > 2)
                                    <span class="page-num-dots">...</span>
                                @endif
                            @endif

                            @for($p = $start; $p <= $end; $p++)
                                <a href="{{ $users->url($p) }}" class="page-num-pill {{ $currentPage == $p ? 'active' : '' }}">{{ $p }}</a>
                            @endfor

                            @if($end < $lastPage)
                                @if($end < $lastPage - 1)
                                    <span class="page-num-dots">...</span>
                                @endif
                                <a href="{{ $users->url($lastPage) }}" class="page-num-pill {{ $currentPage == $lastPage ? 'active' : '' }}">{{ $lastPage }}</a>
                            @endif

                            {{-- زر التالي --}}
                            @if($users->hasMorePages())
                                <a href="{{ $users->nextPageUrl() }}" class="page-nav-pill" title="الصفحة التالية">
                                    <span>التالي</span>
                                    <i class="fa-solid fa-chevron-left"></i>
                                </a>
                            @else
                                <span class="page-nav-pill disabled" title="الصفحة الأخيرة">
                                    <span>التالي</span>
                                    <i class="fa-solid fa-chevron-left"></i>
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>

{{-- ==========================================================
     النوافذ التفاعلية المنبثقة (MODALS)
     ========================================================== --}}

{{-- 1. نافذة كافة الإجراءات الموحدة للشاشات الصغيرة (Mobile Quick Actions Sheet) --}}
<div class="modal-overlay" id="mobileActionsModal">
    <div class="modal" style="max-width: 380px; border-radius: var(--radius-lg); overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.25); border: 1px solid var(--border);">
        <div class="modal-header" style="background: linear-gradient(135deg, #072b4f, #0b3d6b); color: #fff; padding: 16px 20px; border-bottom: 2px solid var(--accent);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(228, 183, 91, 0.2); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 16px;">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <div>
                    <h3 style="color: #fff; font-size: 15px; font-weight: 800; margin: 0;" id="mActionTitle">إجراءات الحساب</h3>
                    <p style="color: #cbd5e1; font-size: 11px; margin: 2px 0 0;" id="mActionSub">اختر الإجراء المطلوب تنفيذه</p>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal('mobileActionsModal')" style="color: #cbd5e1; font-size: 20px; background: none; border: none; cursor: pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="modal-body" style="padding: 18px;">
            <div id="mActionCardSummary" style="background: var(--surface-alt); border: 1px solid var(--border); border-radius: 10px; padding: 12px; margin-bottom: 14px;"></div>

            <button type="button" class="mobile-action-card-item" onclick="triggerMobileAction('view')">
                <div class="m-icon" style="background: #e0f2fe; color: #0284c7;"><i class="fa-solid fa-eye"></i></div>
                <div>
                    <div>معاينة الملف الشامل والصلاحيات</div>
                    <div style="font-size: 11px; color: var(--ink-muted); font-weight: normal;">عرض السجل الأكاديمي والبيانات بالتفصيل</div>
                </div>
            </button>

            <button type="button" class="mobile-action-card-item" onclick="triggerMobileAction('edit')">
                <div class="m-icon" style="background: #fef3c7; color: #d97706;"><i class="fa-solid fa-pen-to-square"></i></div>
                <div>
                    <div>تعديل بيانات الحساب</div>
                    <div style="font-size: 11px; color: var(--ink-muted); font-weight: normal;">تحديث الاسم، الهاتف، والبريد</div>
                </div>
            </button>

            <button type="button" class="mobile-action-card-item" onclick="triggerMobileAction('password')">
                <div class="m-icon" style="background: #fef9c3; color: #ca8a04;"><i class="fa-solid fa-key"></i></div>
                <div>
                    <div>إعادة تعيين كلمة المرور</div>
                    <div style="font-size: 11px; color: var(--ink-muted); font-weight: normal;">توليد كلمة سر جديدة وتحديثها</div>
                </div>
            </button>

            <button type="button" class="mobile-action-card-item" id="mActionToggleBtn" onclick="triggerMobileAction('toggle')">
                <div class="m-icon" style="background: #dcfce7; color: #16a34a;"><i class="fa-solid fa-toggle-on"></i></div>
                <div>
                    <div id="mActionToggleLabel">تبديل حالة الحساب</div>
                    <div style="font-size: 11px; color: var(--ink-muted); font-weight: normal;">تنشيط أو تعليق الحساب مؤقتاً</div>
                </div>
            </button>

            <button type="button" class="mobile-action-card-item" id="mActionDeleteBtn" onclick="triggerMobileAction('delete')" style="border-color: #fecaca;">
                <div class="m-icon" style="background: #fee2e2; color: #dc2626;"><i class="fa-solid fa-trash-can"></i></div>
                <div>
                    <div style="color: #dc2626;">حذف الحساب نهائياً</div>
                    <div style="font-size: 11px; color: var(--ink-muted); font-weight: normal;">إزالة الحساب من النظام بشكل لا رجعة فيه</div>
                </div>
            </button>
        </div>
    </div>
</div>

{{-- 2. نافذة إنشاء مستخدم جديد --}}
<div class="modal-overlay" id="createUserModal">
    <div class="modal modal-lg" style="border-radius: var(--radius-lg); box-shadow: 0 20px 60px rgba(0,0,0,0.2); overflow: hidden; border: 1px solid var(--border);">
        <div class="modal-header" style="background: linear-gradient(135deg, #072b4f, #0b3d6b); color: #fff; padding: 18px 24px; border-bottom: 2px solid var(--accent);">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(228, 183, 91, 0.2); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <h3 style="color: #fff; font-size: 16px; font-weight: 800; margin: 0;">إنشاء حساب مستخدم جديد</h3>
                    <p style="color: #cbd5e1; font-size: 11.5px; margin: 2px 0 0;">إضافة مستخدم جديد للنظام وتعيين الصلاحيات</p>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal('createUserModal')" style="color: #cbd5e1; font-size: 20px; background: none; border: none; cursor: pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="createUserForm" onsubmit="submitCreateUser(event)">
            @csrf
            <div class="modal-body" style="padding: 22px; max-height: calc(85vh - 130px); overflow-y: auto;">
                <div id="createUserAlert" style="display: none; padding: 10px 14px; border-radius: 8px; margin-bottom: 16px; font-size: 12.5px;"></div>

                {{-- اختيار الدور والصلاحية --}}
                <label style="font-weight: 800; font-size: 12.5px; color: var(--primary-dark); margin-bottom: 8px; display: block;">
                    اختر نوع الحساب والصلاحية الممنوحة <span style="color: var(--danger);">*</span>
                </label>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 16px;">
                    <div class="role-radio-card" id="card_role_admin" onclick="selectCreateRole('admin')" style="border: 2px solid var(--border); border-radius: 10px; padding: 12px 10px; cursor: pointer; text-align: center; background: var(--surface-alt);">
                        <div style="width: 38px; height: 38px; border-radius: 8px; margin: 0 auto 6px; display: flex; align-items: center; justify-content: center; font-size: 16px; background: #f3e8ff; color: #7e22ce;">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <div style="font-size: 12px; font-weight: 800; color: var(--ink);">مدير نظام</div>
                    </div>

                    <div class="role-radio-card" id="card_role_university" onclick="selectCreateRole('university')" style="border: 2px solid var(--border); border-radius: 10px; padding: 12px 10px; cursor: pointer; text-align: center; background: var(--surface-alt);">
                        <div style="width: 38px; height: 38px; border-radius: 8px; margin: 0 auto 6px; display: flex; align-items: center; justify-content: center; font-size: 16px; background: #dbeafe; color: #1d4ed8;">
                            <i class="fa-solid fa-building-columns"></i>
                        </div>
                        <div style="font-size: 12px; font-weight: 800; color: var(--ink);">مسؤول جامعة</div>
                    </div>

                    <div class="role-radio-card selected" id="card_role_student" onclick="selectCreateRole('student')" style="border: 2px solid var(--primary); border-radius: 10px; padding: 12px 10px; cursor: pointer; text-align: center; background: #f0fdfa;">
                        <div style="width: 38px; height: 38px; border-radius: 8px; margin: 0 auto 6px; display: flex; align-items: center; justify-content: center; font-size: 16px; background: #ccfbf1; color: #0d9488;">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div style="font-size: 12px; font-weight: 800; color: var(--ink);">طالب مسجل</div>
                    </div>
                </div>
                <input type="hidden" name="user_type" id="create_user_type" value="student">

                {{-- حقول الجامعة عند اختيار حساب جامعي --}}
                <div id="createUniversityFields" style="display: none; background: #f0f7ff; border: 1px dashed #93c5fd; border-radius: 10px; padding: 14px; margin-bottom: 16px;">
                    <div style="font-size: 12.5px; font-weight: 800; color: #1e40af; margin-bottom: 8px;">
                        <i class="fa-solid fa-link me-1"></i> ربط الحساب بصرح جامعي
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                        <div>
                            <label class="form-label" style="font-weight: 700; font-size: 12px;">ربط بجامعة مسجلة (اختياري)</label>
                            <select name="existing_university_id" id="create_existing_university_id" class="form-control" style="font-size: 12px;">
                                <option value="">-- إنشاء صرح جامعي جديد بالاسم أدناه --</option>
                                @foreach($universitiesList as $univ)
                                    <option value="{{ $univ->university_id }}">
                                        {{ $univ->university_name }} {{ $univ->user_id ? '(مرتبطة)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label" style="font-weight: 700; font-size: 12px;">نوع الجامعة (عند الإنشاء الجديد)</label>
                            <select name="university_type" class="form-control" style="font-size: 12px;">
                                <option value="أهلية">جامعة أهلية</option>
                                <option value="حكومية">جامعة حكومية</option>
                                <option value="خاصة">جامعة خاصة</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- الحقول الأساسية --}}
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px;">
                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 12.5px;">اسم المستخدم (Username) <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="username" class="form-control" placeholder="مثال: ahmed_ali" required minlength="4" maxlength="50" style="font-family: monospace; font-size: 12.5px;" dir="ltr">
                    </div>

                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 12.5px;" id="labelFullNameCreate">الاسم الكامل للطالب</label>
                        <input type="text" name="full_name" id="create_full_name" class="form-control" placeholder="الاسم الرباعي أو اسم الجامعة" style="font-size: 12.5px;">
                    </div>

                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 12.5px;">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" placeholder="user@example.com" style="font-size: 12.5px;" dir="ltr">
                    </div>

                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 12.5px;">رقم الهاتف</label>
                        <input type="text" name="phone" class="form-control" placeholder="771234567" style="font-size: 12.5px;" dir="ltr">
                    </div>

                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 12.5px;">حالة الحساب <span style="color: var(--danger);">*</span></label>
                        <select name="account_status" class="form-control" required style="font-size: 12.5px;">
                            <option value="active" selected>نشط ومفعل</option>
                            <option value="suspended">موقوف / معلق</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                            <label class="form-label" style="font-weight: 700; font-size: 12.5px; margin: 0;">كلمة المرور <span style="color: var(--danger);">*</span></label>
                            <button type="button" onclick="generateAndSetPassword('create_password')" style="background: none; border: none; color: var(--accent); font-size: 11px; font-weight: 800; cursor: pointer;">
                                <i class="fa-solid fa-wand-magic-sparkles"></i> توليد كلمة قوية
                            </button>
                        </div>
                        <input type="password" name="password" id="create_password" class="form-control" required minlength="6" placeholder="لا تقل عن 6 أحرف" style="font-size: 12.5px;" dir="ltr">
                    </div>
                </div>

                <div style="background: var(--surface-alt); border: 1px dashed var(--border); border-radius: 8px; padding: 12px; margin-top: 12px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; margin: 0; user-select: none;">
                        <input type="checkbox" name="must_change_password" id="create_must_change" value="1" checked class="custom-check">
                        <span style="font-size: 12.5px; font-weight: 700; color: var(--ink);">إلزام المستخدم بتغيير كلمة المرور فور أول تسجيل دخول</span>
                    </label>
                </div>
            </div>

            <div class="modal-footer" style="padding: 14px 22px; background: var(--surface-alt); border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-ghost" onclick="closeModal('createUserModal')">إلغاء</button>
                <button type="submit" class="btn btn-primary" id="btnSubmitCreateUser" style="font-weight: 800; font-size: 12.5px; padding: 8px 20px;">
                    <i class="fa-solid fa-check me-1"></i> حفظ وإنشاء الحساب
                </button>
            </div>
        </form>
    </div>
</div>

{{-- 3. نافذة تعديل بيانات الحساب --}}
<div class="modal-overlay" id="editUserModal">
    <div class="modal modal-lg" style="border-radius: var(--radius-lg); box-shadow: 0 20px 60px rgba(0,0,0,0.2); overflow: hidden; border: 1px solid var(--border);">
        <div class="modal-header" style="background: linear-gradient(135deg, #072b4f, #0b3d6b); color: #fff; padding: 18px 24px; border-bottom: 2px solid var(--accent);">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(228, 183, 91, 0.2); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="fa-solid fa-user-pen"></i>
                </div>
                <div>
                    <h3 style="color: #fff; font-size: 16px; font-weight: 800; margin: 0;">تعديل بيانات الحساب والصلاحيات</h3>
                    <p style="color: #cbd5e1; font-size: 11.5px; margin: 2px 0 0;" id="editModalSub">تحديث البيانات والرتبة</p>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal('editUserModal')" style="color: #cbd5e1; font-size: 20px; background: none; border: none; cursor: pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="editUserForm" onsubmit="submitEditUser(event)">
            @csrf
            <input type="hidden" id="edit_user_id" name="user_id">
            <div class="modal-body" style="padding: 22px; max-height: calc(85vh - 130px); overflow-y: auto;">
                <div id="editUserAlert" style="display: none; padding: 10px 14px; border-radius: 8px; margin-bottom: 16px; font-size: 12.5px;"></div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px;">
                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 12.5px;">نوع الحساب والدور <span style="color: var(--danger);">*</span></label>
                        <select name="user_type" id="edit_user_type" class="form-control" required style="font-size: 12.5px;" onchange="handleEditRoleChange(this.value)">
                            <option value="student">طالب مسجل (Student)</option>
                            <option value="university">مسؤول جامعة (University)</option>
                            <option value="admin">مدير نظام كامل (Admin)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 12.5px;">حالة الحساب <span style="color: var(--danger);">*</span></label>
                        <select name="account_status" id="edit_account_status" class="form-control" required style="font-size: 12.5px;">
                            <option value="active">نشط ومفعل</option>
                            <option value="suspended">موقوف / معلق</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 12.5px;">اسم المستخدم (Username) <span style="color: var(--danger);">*</span></label>
                        <input type="text" name="username" id="edit_username" class="form-control" required style="font-family: monospace; font-size: 12.5px;" dir="ltr">
                    </div>

                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 12.5px;">الاسم الكامل / اسم الصرح</label>
                        <input type="text" name="full_name" id="edit_full_name" class="form-control" style="font-size: 12.5px;">
                    </div>

                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 12.5px;">البريد الإلكتروني</label>
                        <input type="email" name="email" id="edit_email" class="form-control" style="font-size: 12.5px;" dir="ltr">
                    </div>

                    <div class="form-group">
                        <label class="form-label" style="font-weight: 700; font-size: 12.5px;">رقم الهاتف</label>
                        <input type="text" name="phone" id="edit_phone" class="form-control" style="font-size: 12.5px;" dir="ltr">
                    </div>

                    <div class="form-group col-span-full" id="editUniversityWrap" style="grid-column: 1 / -1; display: none;">
                        <label class="form-label" style="font-weight: 700; font-size: 12.5px;">ربط بجامعة مسجلة</label>
                        <select name="existing_university_id" id="edit_existing_university_id" class="form-control" style="font-size: 12.5px;">
                            <option value="">-- الاحتفاظ بالسجل الحالي --</option>
                            @foreach($universitiesList as $univ)
                                <option value="{{ $univ->university_id }}">
                                    {{ $univ->university_name }} #{{ $univ->university_id }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-span-full" style="grid-column: 1 / -1;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                            <label class="form-label" style="font-weight: 700; font-size: 12.5px; margin: 0;">كلمة مرور جديدة (اختياري)</label>
                            <button type="button" onclick="generateAndSetPassword('edit_password')" style="background: none; border: none; color: var(--accent); font-size: 11px; font-weight: 800; cursor: pointer;">
                                <i class="fa-solid fa-wand-magic-sparkles"></i> توليد كلمة قوية
                            </button>
                        </div>
                        <input type="password" name="password" id="edit_password" class="form-control" minlength="6" placeholder="اتركه فارغاً للإبقاء على كلمة السر الحالية" style="font-size: 12.5px;" dir="ltr">
                    </div>
                </div>

                <div style="background: var(--surface-alt); border: 1px dashed var(--border); border-radius: 8px; padding: 12px; margin-top: 12px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; margin: 0; user-select: none;">
                        <input type="checkbox" name="must_change_password" id="edit_must_change_password" value="1" class="custom-check">
                        <span style="font-size: 12.5px; font-weight: 700; color: var(--ink);">إلزام المستخدم بتغيير كلمة المرور عند تسجيل الدخول القادم</span>
                    </label>
                </div>
            </div>

            <div class="modal-footer" style="padding: 14px 22px; background: var(--surface-alt); border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-ghost" onclick="closeModal('editUserModal')">إلغاء</button>
                <button type="submit" class="btn btn-primary" id="btnSubmitEditUser" style="font-weight: 800; font-size: 12.5px; padding: 8px 20px;">
                    <i class="fa-solid fa-floppy-disk me-1"></i> حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</div>

{{-- 4. نافذة الملف الشامل للمستخدم والصلاحيات --}}
<div class="modal-overlay" id="viewUserModal">
    <div class="modal modal-lg" style="border-radius: var(--radius-lg); box-shadow: 0 20px 60px rgba(0,0,0,0.2); overflow: hidden; border: 1px solid var(--border);">
        <div class="modal-header" style="background: linear-gradient(135deg, #072b4f, #0b3d6b); color: #fff; padding: 18px 24px; border-bottom: 2px solid var(--accent);">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(228, 183, 91, 0.2); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 18px;">
                    <i class="fa-solid fa-id-card-clip"></i>
                </div>
                <div>
                    <h3 style="color: #fff; font-size: 16px; font-weight: 800; margin: 0;">الملف الشامل للمستخدم والصلاحيات</h3>
                    <p style="color: #cbd5e1; font-size: 11.5px; margin: 2px 0 0;">عرض البيانات التفصيلية والصلاحيات الممنوحة</p>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal('viewUserModal')" style="color: #cbd5e1; font-size: 20px; background: none; border: none; cursor: pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="modal-body" id="viewUserModalBody" style="padding: 22px; max-height: calc(85vh - 130px); overflow-y: auto;">
            <div style="text-align: center; padding: 35px; color: var(--ink-muted);">
                <i class="fa-solid fa-spinner fa-spin" style="font-size: 24px; color: var(--primary);"></i>
                <p style="margin-top: 10px; font-weight: 700;">جاري تحميل بيانات المستخدم...</p>
            </div>
        </div>

        <div class="modal-footer" style="padding: 14px 22px; background: var(--surface-alt); border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
            <div id="viewUserModalActions"></div>
            <button type="button" class="btn btn-ghost" onclick="closeModal('viewUserModal')">إغلاق</button>
        </div>
    </div>
</div>

{{-- 5. نافذة إعادة تعيين كلمة المرور --}}
<div class="modal-overlay" id="resetPasswordModal">
    <div class="modal" style="max-width: 440px; border-radius: var(--radius-lg); box-shadow: 0 20px 60px rgba(0,0,0,0.2); overflow: hidden; border: 1px solid var(--border);">
        <div class="modal-header" style="background: linear-gradient(135deg, #072b4f, #0b3d6b); color: #fff; padding: 16px 20px; border-bottom: 2px solid var(--accent);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(228, 183, 91, 0.2); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 16px;">
                    <i class="fa-solid fa-key"></i>
                </div>
                <div>
                    <h3 style="color: #fff; font-size: 15px; font-weight: 800; margin: 0;">إعادة تعيين كلمة المرور</h3>
                    <p style="color: #cbd5e1; font-size: 11px; margin: 2px 0 0;" id="resetPasswordSub">تحديث كلمة السر للحساب</p>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal('resetPasswordModal')" style="color: #cbd5e1; font-size: 20px; background: none; border: none; cursor: pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="modal-body" style="padding: 20px;">
            <div style="background: #f0f7ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 10px 12px; margin-bottom: 14px; font-size: 12px; color: #1e40af;">
                <i class="fa-solid fa-shield-halved me-1"></i> تعيين كلمة مرور جديدة للحساب:
                <strong id="resetUsername"></strong>
            </div>

            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <label class="form-label" style="font-weight: 700; font-size: 12.5px; margin: 0;">كلمة المرور الجديدة</label>
                    <button type="button" onclick="generateAndSetPassword('newPasswordInput')" style="background: none; border: none; color: var(--accent); font-size: 11px; font-weight: 800; cursor: pointer;">
                        <i class="fa-solid fa-wand-magic-sparkles"></i> توليد كلمة قوية
                    </button>
                </div>
                <input type="text" id="newPasswordInput" class="form-control" minlength="6" placeholder="أدخل كلمة المرور الجديدة" style="font-family: monospace; font-size: 12.5px;" dir="ltr">
            </div>

            <div style="background: var(--surface-alt); border: 1px dashed var(--border); border-radius: 8px; padding: 10px; margin-top: 12px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; margin: 0; user-select: none;">
                    <input type="checkbox" id="reset_must_change" checked class="custom-check">
                    <span style="font-size: 12px; font-weight: 700; color: var(--ink);">إلزام المستخدم بتغييرها فور الدخول</span>
                </label>
            </div>
        </div>

        <div class="modal-footer" style="padding: 12px 20px; background: var(--surface-alt); border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 8px;">
            <button type="button" class="btn btn-ghost" onclick="closeModal('resetPasswordModal')">إلغاء</button>
            <button type="button" class="btn btn-primary" id="resetPassBtn" onclick="submitResetPassword()" style="font-weight: 800; font-size: 12.5px;">
                <i class="fa-solid fa-check me-1"></i> حفظ كلمة المرور
            </button>
        </div>
    </div>
</div>

{{-- 6. نافذة تأكيد الحذف --}}
<div class="modal-overlay" id="deleteUserModal">
    <div class="modal" style="max-width: 420px; border-radius: var(--radius-lg); box-shadow: 0 20px 60px rgba(0,0,0,0.25); overflow: hidden; border: 1px solid var(--border);">
        <div class="modal-body" style="padding: 26px 20px; text-align: center;">
            <div style="width: 58px; height: 58px; border-radius: 16px; background: #fee2e2; color: #dc2626; display: inline-flex; align-items: center; justify-content: center; font-size: 26px; margin-bottom: 14px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 style="font-size: 17px; font-weight: 800; color: var(--ink); margin-bottom: 6px;">تأكيد حذف الحساب نهائياً</h3>
            <p style="font-size: 12.5px; color: var(--ink-muted); line-height: 1.5; margin-bottom: 12px;">
                هل أنت متأكد تماماً من رغبتك في حذف الحساب <strong id="deleteUsername" style="color: #b91c1c;"></strong>؟
                <br>
                <span style="font-size: 11px; color: #ef4444;">هذا الإجراء نهائي ولا يمكن التراجع عنه.</span>
            </p>
            <div style="display: flex; justify-content: center; gap: 8px; margin-top: 18px;">
                <button type="button" class="btn btn-ghost" onclick="closeModal('deleteUserModal')">إلغاء</button>
                <button type="button" class="btn btn-danger" id="deleteUserBtn" onclick="submitDeleteUser()" style="font-weight: 800; font-size: 12.5px; padding: 8px 20px;">
                    <i class="fa-solid fa-trash-can me-1"></i> نعم، حذف الحساب
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let activeUserId = null;
    let activeUserName = '';
    let selectedUserIds = new Set();

    // تشغيل أولي وسلوك التمرير الذكي (Smart Scroll Behavior)
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('usersSearchInput');
        const clearBtn = document.getElementById('btnSearchClear');

        if (searchInput && clearBtn) {
            if (searchInput.value.trim() !== '') clearBtn.style.display = 'block';

            searchInput.addEventListener('input', () => {
                clearBtn.style.display = searchInput.value.trim() !== '' ? 'block' : 'none';
            });
        }

        // سحب الصفحة تلقائياً لتكون حاوية البحث والفلترة في أعلى العرض والبيانات وسط الصفحة
        const urlParams = new URLSearchParams(window.location.search);
        const hasFilterOrSearch = urlParams.has('search') || urlParams.has('type') || urlParams.has('status') || urlParams.has('security') || urlParams.has('page') || urlParams.has('per_page');

        if (hasFilterOrSearch) {
            setTimeout(() => {
                const searchSection = document.getElementById('search-filter-section');
                if (searchSection) {
                    const headerOffset = 75; // خصم ارتفاع الهيدر الثابت
                    const elementPosition = searchSection.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: Math.max(0, offsetPosition),
                        behavior: 'smooth'
                    });
                }
            }, 150);
        }
    });

    // مسح حقل البحث
    function clearSearchField() {
        const searchInput = document.getElementById('usersSearchInput');
        if (searchInput) {
            searchInput.value = '';
            document.getElementById('usersSearchForm').submit();
        }
    }

    // نسخ نصوص للحافظة
    function copyText(text, successMsg = 'تم النسخ بنجاح') {
        if (!text) return;
        navigator.clipboard.writeText(text).then(() => {
            showToast(successMsg, 'success');
        }).catch(() => {
            showToast('تعذر النسخ للحافظة', 'error');
        });
    }

    // توليد كلمة مرور قوية
    function generateStrongPassword(length = 10) {
        const chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789!@#$%';
        let pass = '';
        for (let i = 0; i < length; i++) {
            pass += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return pass;
    }

    function generateAndSetPassword(inputId) {
        const generated = generateStrongPassword(10);
        const input = document.getElementById(inputId);
        if (input) {
            input.value = generated;
            input.type = 'text';
            copyText(generated, 'تم توليد كلمة مرور قوية ونسخها: ' + generated);
        }
    }

    // اختيار الدور في نافذة الإضافة
    function selectCreateRole(role) {
        document.getElementById('create_user_type').value = role;
        document.querySelectorAll('.role-radio-card').forEach(c => {
            c.style.borderColor = 'var(--border)';
            c.style.background = 'var(--surface-alt)';
        });
        const selected = document.getElementById(`card_role_${role}`);
        if (selected) {
            selected.style.borderColor = 'var(--primary)';
            selected.style.background = '#f0fdfa';
        }

        const univFields = document.getElementById('createUniversityFields');
        const labelFullName = document.getElementById('labelFullNameCreate');

        if (role === 'university') {
            univFields.style.display = 'block';
            labelFullName.textContent = 'اسم الجامعة الرسمي';
        } else if (role === 'admin') {
            univFields.style.display = 'none';
            labelFullName.textContent = 'الاسم الكامل لمسؤول النظام';
        } else {
            univFields.style.display = 'none';
            labelFullName.textContent = 'الاسم الكامل للطالب';
        }
    }

    function handleEditRoleChange(role) {
        const univWrap = document.getElementById('editUniversityWrap');
        if (univWrap) {
            univWrap.style.display = role === 'university' ? 'block' : 'none';
        }
    }

    // فتح نافذة إنشاء مستخدم جديد
    function openCreateUserModal() {
        const form = document.getElementById('createUserForm');
        form.reset();
        document.getElementById('createUserAlert').style.display = 'none';
        selectCreateRole('student');
        openModal('createUserModal');
    }

    // إرسال إنشاء مستخدم جديد
    async function submitCreateUser(e) {
        e.preventDefault();
        const form = e.target;
        const btn = document.getElementById('btnSubmitCreateUser');
        const alert = document.getElementById('createUserAlert');
        const origText = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جاري الإنشاء...';
        alert.style.display = 'none';

        const formData = new FormData(form);
        const payload = Object.fromEntries(formData.entries());
        payload.must_change_password = document.getElementById('create_must_change').checked ? 1 : 0;

        try {
            const data = await adminFetch('{{ route("admin.users.store") }}', 'POST', payload);
            if (data.success) {
                showToast(data.message || 'تم إنشاء حساب المستخدم بنجاح.', 'success');
                closeModal('createUserModal');
                setTimeout(() => window.location.reload(), 600);
            } else {
                let msg = data.message || 'تعذر إنشاء الحساب.';
                if (data.errors) {
                    const first = Object.values(data.errors)[0];
                    if (Array.isArray(first)) msg = first[0];
                }
                alert.style.display = 'block';
                alert.style.background = '#fef2f2';
                alert.style.color = '#b91c1c';
                alert.style.border = '1px solid #fecaca';
                alert.innerHTML = `<i class="fa-solid fa-circle-exclamation me-1"></i> ${escapeHtml(msg)}`;
                showToast(msg, 'error');
            }
        } catch (err) {
            showToast('حدث خطأ في الاتصال بالخادم.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = origText;
        }
    }

    // فتح نافذة تعديل المستخدم
    async function openEditUserModal(userId) {
        const form = document.getElementById('editUserForm');
        form.reset();
        document.getElementById('editUserAlert').style.display = 'none';
        openModal('editUserModal');

        try {
            const data = await adminFetch(`/admin/users/${userId}`, 'GET');
            if (data.success && data.user) {
                const u = data.user;
                document.getElementById('edit_user_id').value = u.user_id;
                document.getElementById('edit_username').value = u.username;
                document.getElementById('edit_full_name').value = u.raw_full_name || '';
                document.getElementById('edit_email').value = u.email === '—' ? '' : u.email;
                document.getElementById('edit_phone').value = u.phone === '—' ? '' : u.phone;
                document.getElementById('edit_user_type').value = u.user_type;
                document.getElementById('edit_account_status').value = u.account_status;
                document.getElementById('edit_must_change_password').checked = Boolean(u.must_change_password);

                document.getElementById('editModalSub').textContent = `تعديل الحساب (ID: #${u.user_id})`;

                handleEditRoleChange(u.user_type);
                if (u.university_id) {
                    const univSelect = document.getElementById('edit_existing_university_id');
                    if (univSelect) univSelect.value = u.university_id;
                }
            } else {
                showToast(data.message || 'تعذر تحميل بيانات المستخدم.', 'error');
                closeModal('editUserModal');
            }
        } catch (e) {
            showToast('حدث خطأ أثناء جلب بيانات المستخدم.', 'error');
            closeModal('editUserModal');
        }
    }

    // إرسال تعديل المستخدم
    async function submitEditUser(e) {
        e.preventDefault();
        const form = e.target;
        const userId = document.getElementById('edit_user_id').value;
        const btn = document.getElementById('btnSubmitEditUser');
        const alert = document.getElementById('editUserAlert');
        const origText = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جاري الحفظ...';
        alert.style.display = 'none';

        const formData = new FormData(form);
        const payload = Object.fromEntries(formData.entries());
        payload.must_change_password = document.getElementById('edit_must_change_password').checked ? 1 : 0;

        try {
            const data = await adminFetch(`/admin/users/${userId}/update`, 'POST', payload);
            if (data.success) {
                showToast(data.message || 'تم تحديث بيانات المستخدم بنجاح.', 'success');
                closeModal('editUserModal');
                setTimeout(() => window.location.reload(), 600);
            } else {
                let msg = data.message || 'تعذر حفظ التعديلات.';
                if (data.errors) {
                    const first = Object.values(data.errors)[0];
                    if (Array.isArray(first)) msg = first[0];
                }
                alert.style.display = 'block';
                alert.style.background = '#fef2f2';
                alert.style.color = '#b91c1c';
                alert.style.border = '1px solid #fecaca';
                alert.innerHTML = `<i class="fa-solid fa-circle-exclamation me-1"></i> ${escapeHtml(msg)}`;
                showToast(msg, 'error');
            }
        } catch (err) {
            showToast('حدث خطأ في الاتصال بالخادم.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = origText;
        }
    }

    // تبديل حالة الحساب مباشرة بنقرة واحدة
    async function toggleUserStatusDirect(userId) {
        try {
            const data = await adminFetch(`/admin/users/${userId}/toggle-status`, 'POST');
            if (data.success) {
                showToast(data.message, 'success');
                const row = document.getElementById(`user-row-${userId}`);
                if (row) {
                    if (data.new_status === 'active') {
                        row.className = 'user-row-active';
                    } else {
                        row.className = 'user-row-suspended';
                    }
                }
            } else {
                showToast(data.message, 'error');
            }
        } catch (e) {
            showToast('تعذر تغيير حالة الحساب.', 'error');
        }
    }

    // فتح نافذة عرض التفاصيل الكاملة
    async function openViewUserModal(userId) {
        const body = document.getElementById('viewUserModalBody');
        const actions = document.getElementById('viewUserModalActions');

        body.innerHTML = `
            <div style="text-align: center; padding: 35px; color: var(--ink-muted);">
                <i class="fa-solid fa-spinner fa-spin" style="font-size: 26px; color: var(--primary);"></i>
                <p style="margin-top: 10px; font-weight: 700;">جاري تحميل الملف الشامل...</p>
            </div>
        `;
        actions.innerHTML = '';
        openModal('viewUserModal');

        try {
            const data = await adminFetch(`/admin/users/${userId}`, 'GET');
            if (data.success && data.user) {
                const u = data.user;
                const roleBadge = u.user_type === 'admin'
                    ? '<span class="badge" style="background:#f3e8ff; color:#7e22ce;"><i class="fa-solid fa-user-shield me-1"></i> مدير نظام</span>'
                    : (u.user_type === 'university'
                        ? '<span class="badge" style="background:#dbeafe; color:#1d4ed8;"><i class="fa-solid fa-building-columns me-1"></i> مسؤول جامعة</span>'
                        : '<span class="badge" style="background:#ccfbf1; color:#0f766e;"><i class="fa-solid fa-graduation-cap me-1"></i> طالب مسجل</span>');

                const statusBadge = u.account_status === 'active'
                    ? '<span class="badge" style="background:#dcfce7; color:#15803d;"><i class="fa-solid fa-circle-check me-1"></i> نشط ومفعل</span>'
                    : '<span class="badge" style="background:#fee2e2; color:#b91c1c;"><i class="fa-solid fa-ban me-1"></i> موقوف / معلق</span>';

                let permissionsHtml = '';
                if (u.permissions && u.permissions.length > 0) {
                    permissionsHtml = `
                        <div style="margin-top: 16px;">
                            <h4 style="font-size: 13px; font-weight: 800; color: var(--primary-dark); margin-bottom: 8px;">
                                <i class="fa-solid fa-key me-1" style="color: var(--accent);"></i> الصلاحيات الممنوحة
                            </h4>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 8px;">
                                ${u.permissions.map(p => `
                                    <div style="background: var(--surface-alt); border: 1px solid var(--border); border-radius: 8px; padding: 8px 12px; display: flex; align-items: flex-start; gap: 8px;">
                                        <i class="fa-solid ${p.icon || 'fa-check'}" style="color: var(--primary); margin-top: 3px; font-size: 12px;"></i>
                                        <div>
                                            <div style="font-size: 12px; font-weight: 700; color: var(--ink);">${escapeHtml(p.name)}</div>
                                            <div style="font-size: 10.5px; color: var(--ink-muted); margin-top: 2px;">${escapeHtml(p.desc)}</div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `;
                }

                let linkedHtml = '';
                if (u.extra) {
                    if (u.user_type === 'student') {
                        linkedHtml = `
                            <div style="background: #f0fdfa; border: 1px solid #99f6e4; border-radius: 10px; padding: 12px; margin-top: 14px;">
                                <h4 style="font-size: 12.5px; font-weight: 800; color: #0f766e; margin-bottom: 8px;">
                                    <i class="fa-solid fa-graduation-cap me-1"></i> بيانات الطالب الأكاديمية
                                </h4>
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 10px; font-size: 12px;">
                                    <div><span style="color: var(--ink-muted); display: block; font-size: 10.5px;">المدينة:</span> <strong>${escapeHtml(u.extra.city)}</strong></div>
                                    <div><span style="color: var(--ink-muted); display: block; font-size: 10.5px;">الجنس:</span> <strong>${escapeHtml(u.extra.gender)}</strong></div>
                                    <div><span style="color: var(--ink-muted); display: block; font-size: 10.5px;">الطلبات:</span> <span class="badge" style="background:#0d9488; color:#fff;">${u.extra.requests_count} طلب</span></div>
                                </div>
                            </div>
                        `;
                    } else if (u.user_type === 'university') {
                        linkedHtml = `
                            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 12px; margin-top: 14px;">
                                <h4 style="font-size: 12.5px; font-weight: 800; color: #1e40af; margin-bottom: 8px;">
                                    <i class="fa-solid fa-building-columns me-1"></i> بيانات الصرح الجامعي
                                </h4>
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 10px; font-size: 12px;">
                                    <div><span style="color: var(--ink-muted); display: block; font-size: 10.5px;">الجامعة:</span> <strong>${escapeHtml(u.extra.university_name)}</strong></div>
                                    <div><span style="color: var(--ink-muted); display: block; font-size: 10.5px;">الموقع:</span> <strong>${escapeHtml(u.extra.district)}</strong></div>
                                    <div><span style="color: var(--ink-muted); display: block; font-size: 10.5px;">الكليات:</span> <span class="badge" style="background:#2563eb; color:#fff;">${u.extra.colleges_count} كلية</span></div>
                                </div>
                            </div>
                        `;
                    }
                }

                body.innerHTML = `
                    <div style="display: flex; align-items: center; gap: 14px; padding-bottom: 14px; border-bottom: 1px solid var(--border-light);">
                        <div style="width: 52px; height: 52px; border-radius: 14px; background: linear-gradient(135deg, #072b4f, #0b3d6b); color: var(--accent); display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 800;">
                            ${escapeHtml(u.display_name.substring(0, 1))}
                        </div>
                        <div>
                            <h3 style="font-size: 17px; font-weight: 800; color: var(--ink); margin: 0;">${escapeHtml(u.display_name)}</h3>
                            <div style="display: flex; align-items: center; gap: 8px; margin-top: 4px;">
                                <span style="font-size: 11px; color: var(--ink-muted);">ID: #${u.user_id}</span>
                                ${roleBadge}
                                ${statusBadge}
                            </div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-top: 14px; font-size: 12.5px;">
                        <div style="background: var(--surface-alt); border-radius: 8px; padding: 10px; border: 1px solid var(--border-light);">
                            <span style="color: var(--ink-muted); display: block; font-size: 10.5px;">البريد الإلكتروني:</span>
                            <strong dir="ltr">${escapeHtml(u.email)}</strong>
                        </div>
                        <div style="background: var(--surface-alt); border-radius: 8px; padding: 10px; border: 1px solid var(--border-light);">
                            <span style="color: var(--ink-muted); display: block; font-size: 10.5px;">رقم الهاتف:</span>
                            <strong dir="ltr">${escapeHtml(u.phone)}</strong>
                        </div>
                        <div style="background: var(--surface-alt); border-radius: 8px; padding: 10px; border: 1px solid var(--border-light);">
                            <span style="color: var(--ink-muted); display: block; font-size: 10.5px;">تاريخ الإنشاء:</span>
                            <strong>${escapeHtml(u.created_at_formatted)}</strong>
                        </div>
                        <div style="background: var(--surface-alt); border-radius: 8px; padding: 10px; border: 1px solid var(--border-light);">
                            <span style="color: var(--ink-muted); display: block; font-size: 10.5px;">آخر تسجيل دخول:</span>
                            <strong>${escapeHtml(u.last_login_formatted)}</strong>
                        </div>
                    </div>

                    ${linkedHtml}
                    ${permissionsHtml}
                `;

                actions.innerHTML = `
                    <button type="button" class="btn btn-outline btn-xs" onclick="closeModal('viewUserModal'); openEditUserModal(${u.user_id});">
                        <i class="fa-solid fa-pen-to-square me-1"></i> تعديل
                    </button>
                    <button type="button" class="btn btn-outline btn-xs" onclick="closeModal('viewUserModal'); openResetPasswordModal(${u.user_id}, '${escapeHtml(u.display_name)}');">
                        <i class="fa-solid fa-key me-1"></i> كلمة المرور
                    </button>
                `;
            }
        } catch (e) {
            body.innerHTML = `<div class="alert alert-danger">حدث خطأ في الاتصال بالخادم.</div>`;
        }
    }

    // فتح نافذة كلمة المرور
    function openResetPasswordModal(userId, name) {
        activeUserId = userId;
        document.getElementById('resetUsername').textContent = name;
        document.getElementById('newPasswordInput').value = '';
        document.getElementById('reset_must_change').checked = true;
        openModal('resetPasswordModal');
    }

    // تأكيد تعيين كلمة المرور
    async function submitResetPassword() {
        const pass = document.getElementById('newPasswordInput').value.trim();
        const mustChange = document.getElementById('reset_must_change').checked;

        if (!pass || pass.length < 6) {
            showToast('كلمة المرور يجب ألا تقل عن 6 أحرف أو أرقام.', 'error');
            return;
        }

        const btn = document.getElementById('resetPassBtn');
        const origText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جاري الحفظ...';

        try {
            const data = await adminFetch(`/admin/users/${activeUserId}/reset-password`, 'POST', {
                new_password: pass,
                must_change_password: mustChange ? 1 : 0
            });
            if (data.success) {
                closeModal('resetPasswordModal');
                showToast(data.message, 'success');
            } else {
                showToast(data.message, 'error');
            }
        } catch (e) {
            showToast('حدث خطأ في الاتصال بالخادم.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = origText;
        }
    }

    // فتح نافذة تأكيد الحذف
    function confirmDeleteUser(userId, name) {
        activeUserId = userId;
        document.getElementById('deleteUsername').textContent = name;
        openModal('deleteUserModal');
    }

    // تأكيد الحذف
    async function submitDeleteUser() {
        const btn = document.getElementById('deleteUserBtn');
        const origText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جاري الحذف...';

        try {
            const data = await adminFetch(`/admin/users/${activeUserId}`, 'DELETE');
            if (data.success) {
                closeModal('deleteUserModal');
                document.getElementById(`user-row-${activeUserId}`)?.remove();
                showToast(data.message, 'success');
            } else {
                showToast(data.message, 'error');
            }
        } catch (e) {
            showToast('حدث خطأ أثناء حذف الحساب.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = origText;
        }
    }

    // ==========================================================
    // نافذة الإجراءات الموحدة للشاشات الصغيرة (Mobile Action Sheet)
    // ==========================================================
    let mobileActionUserData = null;

    function openMobileActionsSheet(userId, name, role, status) {
        mobileActionUserData = { userId, name, role, status };
        document.getElementById('mActionTitle').textContent = name;
        document.getElementById('mActionSub').textContent = `نوع الحساب: ${role}`;

        const isSelf = userId === {{ Auth::id() }};
        const toggleBtn = document.getElementById('mActionToggleBtn');
        const deleteBtn = document.getElementById('mActionDeleteBtn');
        const toggleLabel = document.getElementById('mActionToggleLabel');

        if (status === 'active') {
            toggleLabel.textContent = 'تعليق وتوقيف الحساب';
        } else {
            toggleLabel.textContent = 'تنشيط وتفعيل الحساب';
        }

        if (isSelf) {
            toggleBtn.style.display = 'none';
            deleteBtn.style.display = 'none';
        } else {
            toggleBtn.style.display = 'flex';
            deleteBtn.style.display = 'flex';
        }

        const summaryBox = document.getElementById('mActionCardSummary');
        summaryBox.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px;">
                <span><strong>${escapeHtml(name)}</strong></span>
                <span class="badge ${status === 'active' ? 'badge-green' : 'badge-red'}">
                    ${status === 'active' ? 'نشط ومفعل' : 'موقوف'}
                </span>
            </div>
        `;

        openModal('mobileActionsModal');
    }

    function triggerMobileAction(action) {
        if (!mobileActionUserData) return;
        const { userId, name } = mobileActionUserData;
        closeModal('mobileActionsModal');

        setTimeout(() => {
            if (action === 'view') {
                openViewUserModal(userId);
            } else if (action === 'edit') {
                openEditUserModal(userId);
            } else if (action === 'password') {
                openResetPasswordModal(userId, name);
            } else if (action === 'toggle') {
                toggleUserStatusDirect(userId);
            } else if (action === 'delete') {
                confirmDeleteUser(userId, name);
            }
        }, 200);
    }

    // ==========================================================
    // التحديد الجماعي (Bulk Selection)
    // ==========================================================
    function toggleMasterCheck(master) {
        const checkboxes = document.querySelectorAll('.single-user-check');
        checkboxes.forEach(cb => {
            cb.checked = master.checked;
            handleSingleCheck(cb);
        });
    }

    function handleSingleCheck(cb) {
        const id = parseInt(cb.value);
        if (cb.checked) {
            selectedUserIds.add(id);
        } else {
            selectedUserIds.delete(id);
        }
        updateBulkStrip();
    }

    function clearBulkSelection() {
        selectedUserIds.clear();
        document.querySelectorAll('.single-user-check').forEach(cb => cb.checked = false);
        const master = document.getElementById('masterCheckbox');
        if (master) master.checked = false;
        updateBulkStrip();
    }

    function updateBulkStrip() {
        const strip = document.getElementById('bulkActionsStrip');
        const count = document.getElementById('bulkSelectedNumber');
        if (selectedUserIds.size > 0) {
            strip.style.display = 'flex';
            count.textContent = selectedUserIds.size;
        } else {
            strip.style.display = 'none';
        }
    }

    async function executeBulkAction(action) {
        if (selectedUserIds.size === 0) return;

        if (action === 'delete') {
            if (!confirm(`هل أنت متأكد من رغبتك في حذف ${selectedUserIds.size} حساب نهائياً؟`)) return;
        }

        try {
            const data = await adminFetch('{{ route("admin.users.bulk") }}', 'POST', {
                user_ids: Array.from(selectedUserIds),
                action: action
            });

            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => window.location.reload(), 600);
            } else {
                showToast(data.message, 'error');
            }
        } catch (e) {
            showToast('حدث خطأ في تطبيق الإجراء الجماعي.', 'error');
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }
</script>

@endsection
