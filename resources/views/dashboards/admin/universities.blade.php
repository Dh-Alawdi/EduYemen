@extends('dashboards.admin.layout')

@section('page_title', 'كآفة الجامعات والاعتماد الأكاديمي')

@section('breadcrumb')
    <span>الرئيسية /</span>
    <strong>كآفة الجامعات</strong>
@endsection

@section('content')

<style>
    /* ==========================================================
       EduYemen Universities Management Styling
       الهوية الأكاديمية: الكحلي الداكن + الذهبي الفاخر
       ========================================================== */
    .univ-hero-card {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 70%, #155e9c 100%);
        border-radius: var(--radius-lg);
        padding: 24px 28px;
        color: #ffffff;
        box-shadow: 0 8px 24px rgba(7, 43, 79, 0.18);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.14);
        margin-bottom: 22px;
    }

    .univ-hero-card::before {
        content: '';
        position: absolute;
        top: -60px;
        left: -60px;
        width: 190px;
        height: 190px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(197, 155, 39, 0.28) 0%, transparent 70%);
        pointer-events: none;
    }

    .univ-hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .univ-hero-title {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .univ-hero-icon {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        background: linear-gradient(135deg, #ffffff 0%, #e2e8f0 100%);
        color: var(--primary);
        font-size: 22px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.18);
        border: 2px solid rgba(255, 255, 255, 0.85);
        flex-shrink: 0;
    }

    .univ-hero-text h1 {
        font-size: 20px;
        font-weight: 900;
        color: #ffffff;
        margin-bottom: 4px;
        letter-spacing: -0.3px;
    }

    .univ-hero-text p {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.82);
        margin: 0;
        font-weight: 500;
    }

    /* ==========================================================
       1. TWIN KPI CARDS (2 CARDS) WITH LUXURY 3D MOTION
       بطاقتان فقط: 1. إجمالي الجامعات  2. تفصيل الحالات (نشطة، معلقة، محظورة)
       ========================================================== */
    .univ-kpi-twin-grid {
        display: grid;
        grid-template-columns: 1fr 1.35fr;
        gap: 16px;
        margin-bottom: 22px;
    }
    @media (max-width: 860px) {
        .univ-kpi-twin-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
    }

    /* حركة عائمة واقعية للبطاقات */
    @keyframes kpiFloatCard {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-4.5px); }
        100% { transform: translateY(0px); }
    }
    @keyframes kpiPulseRing {
        0%, 100% { transform: scale(1); opacity: 0.35; }
        50% { transform: scale(1.12); opacity: 0.8; }
    }

    /* بطاقة 1: إجمالي الجامعات - تصميم كحلي ذهبي فاخر ثلاثي الأبعاد */
    .kpi-twin-card-total {
        background: linear-gradient(135deg, #072b4f 0%, #0d3b66 60%, #155e9c 100%);
        border-radius: 18px;
        padding: 20px 24px;
        color: #ffffff;
        border: 1.5px solid rgba(240, 200, 66, 0.45);
        box-shadow: 0 10px 26px -6px rgba(7, 43, 79, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.08) inset;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        animation: kpiFloatCard 4.8s ease-in-out infinite;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .kpi-twin-card-total:hover {
        transform: translateY(-6px) scale(1.008);
        box-shadow: 0 16px 36px -6px rgba(7, 43, 79, 0.45), 0 0 22px rgba(240, 200, 66, 0.3);
    }
    .kpi-total-icon-badge {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(240, 200, 66, 0.28) 0%, rgba(240, 200, 66, 0.08) 100%);
        border: 1.5px solid #f0c842;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f0c842;
        font-size: 26px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
        flex-shrink: 0;
        position: relative;
    }
    .kpi-total-icon-badge::after {
        content: '';
        position: absolute;
        inset: -5px;
        border-radius: 20px;
        border: 1.5px dashed rgba(240, 200, 66, 0.5);
        animation: kpiPulseRing 3s ease-in-out infinite;
        pointer-events: none;
    }
    .kpi-total-content h3 {
        font-size: 28px;
        font-weight: 900;
        color: #ffffff;
        margin: 0;
        line-height: 1.1;
        letter-spacing: -0.5px;
    }
    .kpi-total-content .kpi-total-label {
        font-size: 13.5px;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 700;
        margin-top: 4px;
        display: block;
    }
    .kpi-total-content .kpi-total-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: rgba(240, 200, 66, 0.16);
        border: 1px solid rgba(240, 200, 66, 0.4);
        color: #f0c842;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        margin-top: 8px;
    }

    /* بطاقة 2: الحالات الثلاث بتصميم كبسولات ثلاثية الأبعاد */
    .kpi-twin-card-status {
        background: #ffffff;
        border-radius: 18px;
        padding: 18px 22px;
        border: 1.5px solid #e2e8f0;
        box-shadow: 0 10px 26px -6px rgba(15, 23, 42, 0.08);
        position: relative;
        overflow: hidden;
        animation: kpiFloatCard 5.2s ease-in-out infinite 0.6s;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .kpi-twin-card-status:hover {
        transform: translateY(-5px) scale(1.008);
        box-shadow: 0 16px 36px -6px rgba(15, 23, 42, 0.12);
        border-color: #cbd5e1;
    }
    .kpi-status-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    .kpi-status-title {
        font-size: 13.5px;
        font-weight: 800;
        color: #072b4f;
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .kpi-status-capsules-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }
    @media (max-width: 576px) {
        .kpi-status-capsules-grid {
            gap: 7px;
        }
    }
    .kpi-status-capsule {
        border-radius: 14px;
        padding: 12px 10px;
        text-align: center;
        transition: all 0.2s ease;
        border: 1.5px solid transparent;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    }
    .kpi-status-capsule:hover {
        transform: translateY(-2px);
    }
    .capsule-active {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border-color: #86efac;
        color: #166534;
    }
    .capsule-pending {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border-color: #fde68a;
        color: #92400e;
    }
    .capsule-banned {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border-color: #fca5a5;
        color: #991b1b;
    }
    .capsule-icon-wrap {
        font-size: 15px;
        margin-bottom: 4px;
    }
    .capsule-number {
        font-size: 20px;
        font-weight: 900;
        line-height: 1.1;
        font-family: inherit;
    }
    .capsule-label {
        font-size: 11px;
        font-weight: 700;
        margin-top: 3px;
    }

    /* ==========================================================
       2. CENTERED SLEEK FILTER BAR WITH SMART RESPONSIVENESS
       شريط تصفية وبحث في منتصف الصفحة بأبعاد أصغر وأوضح
       ========================================================== */
    .filter-center-wrap {
        max-width: 880px;
        margin: 0 auto 24px auto;
        padding: 0 10px;
    }
    .filter-card-sleek {
        background: #ffffff;
        border-radius: 16px;
        border: 1.5px solid #cbd5e1;
        box-shadow: 0 6px 20px -3px rgba(15, 23, 42, 0.07);
        padding: 12px 16px;
        transition: all 0.2s ease;
    }
    .filter-card-sleek:hover {
        border-color: #0d3b66;
        box-shadow: 0 10px 26px -4px rgba(13, 59, 102, 0.12);
    }
    .filter-form-layout {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }
    .filter-input-wrap {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }
    .filter-input-icon {
        position: absolute;
        right: 12px;
        color: #94a3b8;
        font-size: 13.5px;
        pointer-events: none;
        transition: color 0.2s;
    }
    .filter-input-wrap:focus-within .filter-input-icon {
        color: #0d3b66;
    }
    .filter-col-search {
        flex: 1.3;
        min-width: 220px;
    }
    .filter-col-search input {
        width: 100%;
        height: 38px;
        padding: 6px 36px 6px 32px;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        font-size: 12.5px;
        background: #f8fafc;
        color: #1e293b;
        transition: all 0.2s ease;
    }
    .filter-col-search input:focus {
        background: #ffffff;
        border-color: #0d3b66;
        outline: none;
        box-shadow: 0 0 0 3px rgba(13, 59, 102, 0.12);
    }
    .filter-clear-x {
        position: absolute;
        left: 10px;
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: 12px;
        padding: 2px 4px;
    }
    .filter-col-select {
        flex: 1;
        min-width: 150px;
    }
    .filter-col-select select {
        width: 100%;
        height: 38px;
        padding: 6px 34px 6px 10px;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        font-size: 12px;
        background: #f8fafc;
        color: #1e293b;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .filter-col-select select:focus {
        background: #ffffff;
        border-color: #0d3b66;
        outline: none;
        box-shadow: 0 0 0 3px rgba(13, 59, 102, 0.12);
    }
    .filter-col-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .btn-filter-action {
        height: 38px;
        padding: 0 16px;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        white-space: nowrap;
    }
    .btn-filter-primary {
        background: linear-gradient(135deg, #072b4f 0%, #0d3b66 100%);
        color: #ffffff;
        border: 1px solid #f0c842;
        box-shadow: 0 2px 6px rgba(7, 43, 79, 0.2);
    }
    .btn-filter-primary:hover {
        background: #072b4f;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(7, 43, 79, 0.3);
    }
    .btn-filter-reset {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #cbd5e1;
        text-decoration: none;
    }
    .btn-filter-reset:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* في الشاشات الصغيرة: صف 1 (بحث + حالات)، صف 2 (نوع + تصفية) */
    @media (max-width: 768px) {
        .filter-form-layout {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 10px !important;
        }
        .filter-col-search {
            grid-column: 1 / 2 !important;
            min-width: 0 !important;
        }
        .filter-col-status {
            grid-column: 2 / 3 !important;
            min-width: 0 !important;
        }
        .filter-col-type {
            grid-column: 1 / 2 !important;
            min-width: 0 !important;
        }
        .filter-col-actions {
            grid-column: 2 / 3 !important;
            display: flex !important;
            gap: 6px !important;
            width: 100% !important;
        }
        .filter-col-actions .btn-filter-action {
            flex: 1 !important;
            justify-content: center !important;
            padding: 0 8px !important;
        }
    }

    /* ==========================================================
       3. RESPONSIVE MODAL FIELDS DISTRIBUTION (نافذة إضافة الجامعة)
       الشاشات الكبيرة: حقلان إلى 3 في الصف.
       الشاشات الصغيرة: الحقول القصيرة حقلان في السطر، والحقول التي تحتاج تعبئة سطر منفرد
       ========================================================== */
    .modal-row-identity {
        display: grid;
        grid-template-columns: 1.3fr 1fr 0.8fr;
        gap: 12px;
    }
    .modal-row-geo {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    .modal-row-contact {
        display: grid;
        grid-template-columns: 1fr 1fr 1.2fr;
        gap: 12px;
    }
    .modal-row-auth {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    @media (max-width: 768px) {
        /* الهوية: الاسم سطر كامل، والنوع + سنة التأسيس في سطر واحد */
        .modal-row-identity {
            grid-template-columns: 1fr 1fr !important;
            gap: 10px !important;
        }
        .modal-identity-name {
            grid-column: 1 / -1 !important;
        }
        /* الموقع: المحافظة سطر كامل، والعنوان سطر كامل */
        .modal-row-geo {
            grid-template-columns: 1fr !important;
            gap: 10px !important;
        }
        /* التواصل: الهاتف + الواتساب في سطر واحد (حقلان)، والبريد في سطر كامل */
        .modal-row-contact {
            grid-template-columns: 1fr 1fr !important;
            gap: 10px !important;
        }
        .modal-contact-email {
            grid-column: 1 / -1 !important;
        }
        /* بيانات الدخول: اسم المستخدم + كلمة المرور يبقيان حقلين في سطر واحد */
        .modal-row-auth {
            grid-template-columns: 1fr 1fr !important;
            gap: 10px !important;
        }
    }

    /* University Row Design */
    .univ-logo-box {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2px;
        flex-shrink: 0;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }
    .univ-logo-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    /* تلوين صفوف الجدول حسب حالة الجامعة بمرونة وهدوء */
    .admin-table tbody tr.row-active {
        background-color: #f2fbf5 !important;
    }
    .admin-table tbody tr.row-active:hover {
        background-color: #e6f7ec !important;
    }
    .admin-table tbody tr.row-inactive {
        background-color: #fdf4f4 !important;
    }
    .admin-table tbody tr.row-inactive:hover {
        background-color: #fae8e8 !important;
    }

    /* تقليل المسافات والإزاحات المتباعدة داخل خلايا الجدول */
    .admin-table thead th {
        padding: 10px 14px !important;
        font-size: 12.5px !important;
    }
    .admin-table tbody td {
        padding: 8px 14px !important;
        font-size: 13px !important;
    }

    /* شبكة أزرار الإجراءات في صفين 2x2 داخل الخلية للحد من اتساع الشاشة */
    .actions-grid-cell {
        display: grid;
        grid-template-columns: repeat(2, 30px);
        gap: 5px;
        justify-content: center;
        align-items: center;
        width: fit-content;
    }

    .action-btn-pill {
        width: 30px;
        height: 30px;
        border-radius: 7px;
        border: 1px solid var(--border);
        background: #ffffff;
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        transition: all 0.16s ease;
        cursor: pointer;
        text-decoration: none;
    }
    .action-btn-pill:hover {
        background: var(--primary-subtle);
        border-color: var(--primary-light);
        color: var(--primary-dark);
        transform: translateY(-1px);
    }
    .action-btn-pill.danger {
        color: var(--danger);
    }
    .action-btn-pill.danger:hover {
        background: #fee2e2;
        border-color: #fca5a5;
        color: #dc2626;
    }

    /* زر الخريطة الأنيق */
    .map-link-btn {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 700;
        color: var(--primary);
        background: #ffffff;
        border: 1px solid rgba(11, 61, 107, 0.2);
        padding: 2px 7px;
        border-radius: 6px;
        margin-top: 3px;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .map-link-btn:hover {
        background: var(--primary);
        color: #ffffff;
        border-color: var(--primary);
    }

    /* أزرار الكحلي الموحدة */
    .btn-navy {
        background: var(--primary) !important;
        color: #ffffff !important;
        border: 1px solid var(--primary-dark) !important;
        box-shadow: 0 2px 6px rgba(11, 61, 107, 0.2);
    }
    .btn-navy:hover {
        background: var(--primary-dark) !important;
        box-shadow: 0 4px 10px rgba(11, 61, 107, 0.3);
    }

    /* ==========================================================
       MODAL: تصميم عصري وفخم لنوافذ الإدخال والعرض
       ========================================================== */
    .form-row-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 12px;
    }
    .form-row-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 12px;
    }
    @media (max-width: 768px) {
        .form-row-grid-3, .form-row-grid-2 {
            grid-template-columns: 1fr;
            gap: 10px;
        }
    }

    .form-section-card {
        background: #ffffff;
        border: 1px solid var(--border-light);
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 14px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.02);
    }

    .form-section-head {
        font-size: 13px;
        font-weight: 800;
        color: var(--primary-dark);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 7px;
        border-bottom: 1px dashed var(--border-light);
        padding-bottom: 6px;
    }

    .form-section-head i {
        color: var(--accent);
        font-size: 14px;
    }

    /* Tab Switchers inside View Modal */
    .modal-tab-nav {
        display: flex;
        border-bottom: 2px solid var(--border-light);
        gap: 6px;
        margin-bottom: 16px;
    }
    .modal-tab-btn {
        padding: 8px 16px;
        border: none;
        background: transparent;
        font-weight: 700;
        font-size: 13px;
        color: var(--ink-muted);
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .modal-tab-btn:hover {
        color: var(--primary);
    }
    .modal-tab-btn.active {
        color: var(--primary);
        border-bottom-color: var(--accent);
        font-weight: 800;
    }

    /* ==========================================================
       Add University Modal Premium Redesign Styling
       ========================================================== */
    .univ-modal-header-hero {
        background: linear-gradient(135deg, #072b4f 0%, #0d3b66 60%, #155e9c 100%);
        padding: 22px 28px;
        border-bottom: 3px solid #f0c842;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        color: #fff;
    }
    .univ-modal-icon-badge {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(240, 200, 66, 0.28) 0%, rgba(240, 200, 66, 0.12) 100%);
        border: 1.5px solid #f0c842;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f0c842;
        font-size: 22px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        flex-shrink: 0;
    }
    .univ-modal-title {
        color: #ffffff;
        font-size: 18px;
        font-weight: 800;
        margin: 0;
        letter-spacing: -0.2px;
    }
    .univ-modal-subtitle {
        color: rgba(255, 255, 255, 0.85);
        font-size: 12.5px;
        margin-top: 4px;
        margin-bottom: 0;
    }
    .univ-modal-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px 20px;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        transition: all 0.2s ease;
    }
    .univ-modal-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
    }
    .univ-card-step-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 1px dashed #e2e8f0;
    }
    .univ-card-step-badge {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #072b4f;
        color: #f0c842;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 800;
        border: 1.5px solid #f0c842;
        margin-left: 8px;
        flex-shrink: 0;
    }
    .univ-field-label {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .univ-field-label i {
        color: #0d3b66;
        font-size: 12px;
    }
    .univ-input-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }
    .univ-input-wrap .univ-input-icon {
        position: absolute;
        right: 12px;
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
        transition: color 0.2s;
    }
    .univ-input-wrap .form-control {
        padding-right: 36px;
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        font-size: 13px;
        height: 42px;
        transition: all 0.2s ease;
        background: #ffffff;
    }
    .univ-input-wrap textarea.form-control {
        height: auto;
        padding-right: 36px;
    }
    .univ-input-wrap:focus-within .univ-input-icon {
        color: #0d3b66;
    }
    .univ-input-wrap:focus-within .form-control {
        border-color: #0d3b66;
        box-shadow: 0 0 0 3px rgba(13, 59, 102, 0.12);
    }
    /* Logo Dropzone & Preview */
    .univ-logo-upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 14px 18px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        gap: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .univ-logo-upload-zone:hover {
        border-color: #0d3b66;
        background: #f1f5f9;
    }
    .univ-logo-thumb-preview {
        width: 54px;
        height: 54px;
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }
    .univ-logo-thumb-preview img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    /* Account Box */
    .univ-account-card {
        background: linear-gradient(135deg, #f0f7ff 0%, #fefce8 100%);
        border: 1.5px solid #f0c842;
        border-radius: 14px;
        padding: 18px 20px;
        margin-bottom: 16px;
        box-shadow: 0 4px 16px rgba(240, 200, 66, 0.15);
    }
</style>

<!-- ============================================================
     1. HERO HEADER: هوية إدارة الجامعات والاعتماد
     ============================================================ -->
<div class="univ-hero-card">
    <div class="univ-hero-content">
        <div class="univ-hero-title">
            <div class="univ-hero-icon">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            <div class="univ-hero-text">
                <h1>إدارة الجامعات والاعتماد الأكاديمي</h1>
                <p>إدخال وتحديث بيانات الجامعات والكليات والتخصصات، ومتابعة سير طلبات التسجيل ونشاط المنصة.</p>
            </div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <button class="btn btn-navy" onclick="openModal('addUniversityModal')" style="background:linear-gradient(135deg, #072b4f 0%, #0d3b66 100%);border:1px solid #f0c842;box-shadow:0 4px 14px rgba(7,43,79,0.25);font-weight:800;">
                <i class="fa-solid fa-plus-circle" style="color:#f0c842;font-size:15px;"></i>
                <span>إضافة جامعة جديدة</span>
            </button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost" style="background:rgba(255,255,255,0.15);color:#fff;border-color:rgba(255,255,255,0.3);">
                <i class="fa-solid fa-house" style="color:#f0c842;"></i>
                <span>الرئيسية</span>
            </a>
        </div>
    </div>
</div>




<!-- ============================================================
     2. TWIN KPI SUMMARY CARDS: بطاقتان واقعيتان بتصميم ثلاثي الأبعاد وحركة جذابة
     ============================================================ -->
<div class="univ-kpi-twin-grid">
    <!-- بطاقة 1: إجمالي الجامعات المعتمدة -->
    <div class="kpi-twin-card-total">
        <div class="kpi-total-content">
            <h3>{{ $stats['total'] }}</h3>
            <span class="kpi-total-label">إجمالي الجامعات المعتمدة</span>
            <span class="kpi-total-badge">
                <i class="fa-solid fa-certificate"></i> صروح أكاديمية رسمية في المنصة
            </span>
        </div>
        <div class="kpi-total-icon-badge">
            <i class="fa-solid fa-building-columns"></i>
        </div>
    </div>

    <!-- بطاقة 2: تفصيل الحالات (النشطة، المعلقة، المحظورة) -->
    <div class="kpi-twin-card-status">
        <div class="kpi-status-head">
            <span class="kpi-status-title">
                <i class="fa-solid fa-chart-pie" style="color:#0d3b66;"></i>
                مؤشرات الحالة التشغيلية والاعتماد
            </span>
            <span style="font-size:11.5px;color:#64748b;font-weight:700;">تحديث فوري</span>
        </div>
        <div class="kpi-status-capsules-grid">
            <!-- 1. نشطة ومفعلة -->
            <div class="kpi-status-capsule capsule-active" title="جامعات تعمل بصورة طبيعية وظاهرة للطلاب">
                <div class="capsule-icon-wrap"><i class="fa-solid fa-circle-check"></i></div>
                <div class="capsule-number">{{ $stats['active'] }}</div>
                <div class="capsule-label">نشطة ومفعلة</div>
            </div>
            <!-- 2. معلقة مؤقتاً -->
            <div class="kpi-status-capsule capsule-pending" title="جامعات موقوفة مؤقتاً عن استقبال الطلبات">
                <div class="capsule-icon-wrap"><i class="fa-solid fa-circle-pause"></i></div>
                <div class="capsule-number">{{ $stats['pending'] }}</div>
                <div class="capsule-label">معلقة مؤقتاً</div>
            </div>
            <!-- 3. محظورة -->
            <div class="kpi-status-capsule capsule-banned" title="جامعات محظورة ومحجوبة عن المنصة">
                <div class="capsule-icon-wrap"><i class="fa-solid fa-ban"></i></div>
                <div class="capsule-number">{{ $stats['banned'] }}</div>
                <div class="capsule-label">محظورة</div>
            </div>
        </div>
    </div>
</div>


<!-- ============================================================
     3. CENTERED SLEEK FILTERS & SEARCH: شريط مدمج متمركز بالصفحة مع دعم ذكي للشاشات الصغيرة
     ============================================================ -->
<div class="filter-center-wrap" id="filterSection">
    <div class="filter-card-sleek">
        <form method="GET" action="{{ route('admin.universities') }}" id="filterForm" class="filter-form-layout">
            <!-- 1. شريط البحث المدمج -->
            <div class="filter-col-search">
                <div class="filter-input-wrap">
                    <i class="fa-solid fa-magnifying-glass filter-input-icon"></i>
                    <input type="text" name="search" id="univSearchInput" 
                           placeholder="ابحث باسم الجامعة أو المحافظة..." 
                           value="{{ request('search') }}">
                    @if(request('search'))
                        <button type="button" class="filter-clear-x" onclick="document.getElementById('univSearchInput').value='';document.getElementById('filterForm').submit();" title="إلغاء البحث">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    @endif
                </div>
            </div>

            <!-- 2. قائمة كل الحالات -->
            <div class="filter-col-status filter-col-select">
                <div class="filter-input-wrap">
                    <i class="fa-solid fa-toggle-on filter-input-icon"></i>
                    <select name="status">
                        <option value="">كل حالات التشغيل</option>
                        <option value="نشطة"   {{ request('status')=='نشطة'   ? 'selected' : '' }}>🟢 جامعات نشطة</option>
                        <option value="معلقة"  {{ request('status')=='معلقة'  ? 'selected' : '' }}>🟡 جامعات معلقة</option>
                        <option value="محظورة" {{ request('status')=='محظورة' ? 'selected' : '' }}>🔴 جامعات محظورة</option>
                    </select>
                </div>
            </div>

            <!-- 3. قائمة اختيار النوع -->
            <div class="filter-col-type filter-col-select">
                <div class="filter-input-wrap">
                    <i class="fa-solid fa-shapes filter-input-icon"></i>
                    <select name="type">
                        <option value="">كافة أنواع الجامعات</option>
                        <option value="حكومية" {{ request('type')=='حكومية' ? 'selected' : '' }}>🏛️ جامعة حكومية</option>
                        <option value="خاصة"   {{ request('type')=='خاصة'   ? 'selected' : '' }}>🏢 جامعة خاصة</option>
                        <option value="أهلية"  {{ request('type')=='أهلية'  ? 'selected' : '' }}>🏫 جامعة أهلية</option>
                    </select>
                </div>
            </div>

            <!-- 4. أزرار الفلترة والإلغاء -->
            <div class="filter-col-actions">
                <button type="submit" class="btn-filter-action btn-filter-primary">
                    <i class="fa-solid fa-filter"></i>
                    <span>تصفية</span>
                </button>
                @if(request()->anyFilled(['search','status','type']))
                    <a href="{{ route('admin.universities') }}" class="btn-filter-action btn-filter-reset" title="استعادة العرض الكامل">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span>إلغاء</span>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- ============================================================
     4. UNIVERSITIES TABLE
     ============================================================ -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fa-solid fa-list-check"></i>
            سجل الجامعات المعتمدة
        </div>
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('admin.moderation') }}" class="btn btn-sm" 
               style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;font-weight:700;display:inline-flex;align-items:center;gap:7px;border-radius:8px;padding:6px 12px;cursor:pointer;text-decoration:none;">
                <i class="fa-solid fa-shield-halved" style="color:#dc2626;"></i>
                <span>مركز رقابة وحوكمة المحتوى</span>
                <span class="badge" id="quarantinedTopBadge" style="background:#dc2626;color:#fff;font-size:10.5px;padding:1px 7px;border-radius:10px;">{{ $quarantinedCount ?? 0 }}</span>
            </a>
            <span class="badge badge-primary">{{ $universities->total() }} جامعة</span>
        </div>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:26%;">الجامعة</th>
                    <th class="col-hide-mobile" style="width:24%;">الموقع الجغرافي</th>
                    <th class="col-hide-mobile" style="width:20%;">الكليات والتخصصات</th>
                    <th class="col-hide-mobile" style="width:16%;">طلبات التسجيل</th>
                    <th style="width:14%;text-align:center;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($universities as $univ)
                @php
                    $isRowActive = ($univ->status === 'نشطة');
                    $mapSearchQuery = urlencode($univ->university_name . ' ' . ($univ->district ?: 'صنعاء') . ' اليمن');
                    $mapUrl = ($univ->latitude && $univ->longitude) 
                        ? "https://www.google.com/maps?q={$univ->latitude},{$univ->longitude}"
                        : "https://www.google.com/maps/search/?api=1&query={$mapSearchQuery}";
                @endphp
                <tr id="univ-row-{{ $univ->university_id }}" class="{{ $isRowActive ? 'row-active' : 'row-inactive' }}">
                    <!-- اسم الجامعة + نوعها بدلاً من سنة التأسيس -->
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="univ-logo-box">
                                <img src="{{ $univ->logo_url }}" alt="{{ $univ->university_name }}" onerror="this.src='{{ asset('logo.png') }}'">
                            </div>
                            <div>
                                <div style="font-weight:800;color:var(--primary-dark);font-size:13.5px;line-height:1.3;">{{ $univ->university_name }}</div>
                                <div style="margin-top:2px;">
                                    <span class="badge {{ $univ->university_type=='حكومية' ? 'badge-primary' : ($univ->university_type=='خاصة' ? 'badge-purple' : 'badge-accent') }}" style="font-size:10.5px;padding:1px 6px;">
                                        جامعة {{ $univ->university_type }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- الموقع الجغرافي + رابط الخريطة -->
                    <td class="col-hide-mobile">
                        <div style="font-size:12.5px;font-weight:700;color:var(--ink);line-height:1.3;">
                            <i class="fa-solid fa-location-dot" style="color:var(--accent);font-size:11px;"></i>
                            {{ $univ->district ?: 'اليمن' }}
                        </div>
                        <div style="font-size:11px;color:var(--ink-muted);line-height:1.2;margin-top:1px;">
                            {{ Str::limit($univ->address, 35) }}
                        </div>
                        <div>
                            <a href="{{ $mapUrl }}" target="_blank" class="map-link-btn" title="فتح الموقع على خرائط جوجل">
                                <i class="fa-solid fa-map-location-dot"></i>
                                <span>الموقع على الخريطة</span>
                                <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:8px;"></i>
                            </a>
                        </div>
                    </td>

                    <!-- الكليات والتخصصات -->
                    <td class="col-hide-mobile">
                        <div style="font-size:12.5px;">
                            <span style="font-weight:800;color:var(--primary-dark);">{{ $univ->colleges_count }}</span>
                            <span style="font-size:11.5px;color:var(--ink-muted);">كلية</span>
                            <span style="margin:0 3px;color:var(--border);">|</span>
                            <span style="font-weight:800;color:var(--teal);">{{ $univ->specializations_count }}</span>
                            <span style="font-size:11.5px;color:var(--ink-muted);">تخصص</span>
                        </div>
                        @if($univ->phone)
                            <div style="font-size:11px;color:var(--ink-muted);margin-top:2px;">
                                <i class="fa-solid fa-phone" style="font-size:9px;"></i> {{ $univ->phone }}
                            </div>
                        @endif
                    </td>

                    <!-- طلبات التسجيل -->
                    <td class="col-hide-mobile">
                        <a href="{{ route('admin.requests', ['university_id' => $univ->university_id]) }}" title="عرض الطلبات" style="font-weight:800;color:var(--primary);font-size:13px;display:inline-flex;align-items:center;gap:5px;">
                            <i class="fa-solid fa-file-signature" style="font-size:11px;"></i>
                            <span>{{ $univ->reg_requests_count }} طلب</span>
                        </a>
                    </td>

                    <!-- الإجراءات في صفين (2x2) لتفادي اتساع الشاشة -->
                    <td style="text-align:center;">
                        <div class="actions-grid-cell" style="margin:0 auto;">
                            <!-- صف 1: عرض تفاصيل + تعديل -->
                            <button class="action-btn-pill" onclick="viewUniversity({{ $univ->university_id }})" title="عرض الملف والنشاط">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button class="action-btn-pill" onclick="openEditUniversityModal({{ $univ->university_id }})" title="تعديل بيانات الجامعة">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <!-- صف 2: تغيير حالة + زيارة البروفايل أو الحذف -->
                            <button class="action-btn-pill" onclick="openStatusModal({{ $univ->university_id }}, '{{ $univ->status }}')" title="تغيير حالة التشغيل">
                                <i class="fa-solid fa-toggle-on"></i>
                            </button>
                            <button class="action-btn-pill danger" onclick="confirmDeleteUniv({{ $univ->university_id }}, '{{ addslashes($univ->university_name) }}')" title="حذف الجامعة">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="fa-solid fa-building-columns"></i>
                            <h4>لا توجد جامعات مطابقة للبحث</h4>
                            <p>يمكنك تسجيل جامعة جديدة مباشرة بالضغط على "تسجيل جامعة جديدة".</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($universities->hasPages())
    <div class="pagination-wrap">
        <span>عرض {{ $universities->firstItem() }}–{{ $universities->lastItem() }} من {{ $universities->total() }}</span>
        {{ $universities->links('pagination::simple-tailwind') }}
    </div>
    @endif
</div>


<!-- ============================================================
     MODAL 1: إضافة جامعة جديدة بتصميم بصري فاخر وحقول فائقة الوضوح
     ============================================================ -->
<div class="modal-overlay" id="addUniversityModal">
    <div class="modal modal-lg" style="border-radius:18px;overflow:hidden;border:1px solid rgba(240,200,66,0.3);box-shadow:0 25px 60px -15px rgba(10,25,47,0.35);max-width:920px;max-height:90vh;display:flex;flex-direction:column;">
        
        <!-- Premium Modal Header -->
        <div class="univ-modal-header-hero" style="flex-shrink:0;">
            <div style="display:flex;align-items:center;gap:14px;">
                <div class="univ-modal-icon-badge">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
                <div>
                    <h3 class="univ-modal-title">إضافة واعتماد جامعة جديدة</h3>
                    <p class="univ-modal-subtitle">تسجيل صرح أكاديمي جديد في المنصة الوطنية EduYemen وتوليد صلاحيات الدخول المؤسسي</p>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal('addUniversityModal')" style="background:rgba(255,255,255,0.15);color:#fff;border-radius:50%;width:34px;height:34px;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;transition:all 0.2s;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="modal-body" style="background:#f8fafc;padding:22px;flex:1 1 auto;min-height:0;overflow-y:auto;">
            <form id="addUniversityForm" enctype="multipart/form-data">
                @csrf

                <!-- بطاقة 1: البيانات التأسيسية والهوية الأكاديمية -->
                <div class="univ-modal-card">
                    <div class="univ-card-step-title">
                        <div style="display:flex;align-items:center;">
                            <span class="univ-card-step-badge">١</span>
                            <span style="font-size:14px;font-weight:800;color:#072b4f;">الهوية والبيانات التأسيسية الأكاديمية</span>
                        </div>
                        <span style="font-size:11.5px;color:#64748b;background:#f1f5f9;padding:3px 8px;border-radius:6px;font-weight:600;">البيانات الأساسية</span>
                    </div>

                    <!-- صف 1: اسم الجامعة (سطر مستقل بالموبايل)، النوع وسنة التأسيس (حقلان بسطر واحد بالموبايل، و3 حقول بالديسكتوب) -->
                    <div class="modal-row-identity">
                        <div class="form-group modal-identity-name">
                            <label class="univ-field-label">
                                <i class="fa-solid fa-signature"></i>
                                اسم الجامعة الرسمي <span class="req">*</span>
                            </label>
                            <div class="univ-input-wrap">
                                <i class="fa-solid fa-building-columns univ-input-icon"></i>
                                <input type="text" name="university_name" class="form-control" placeholder="مثال: جامعة صنعاء" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="univ-field-label">
                                <i class="fa-solid fa-layer-group"></i>
                                نوع الجامعة <span class="req">*</span>
                            </label>
                            <div class="univ-input-wrap">
                                <i class="fa-solid fa-shapes univ-input-icon"></i>
                                <select name="university_type" class="form-control" required style="cursor:pointer;">
                                    <option value="">اختر نوع الجامعة...</option>
                                    <option value="حكومية">🏛️ جامعة حكومية</option>
                                    <option value="خاصة">🏢 جامعة خاصة</option>
                                    <option value="أهلية">🏫 جامعة أهلية</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="univ-field-label">
                                <i class="fa-solid fa-calendar-days"></i>
                                سنة التأسيس
                            </label>
                            <div class="univ-input-wrap">
                                <i class="fa-solid fa-clock-rotate-left univ-input-icon"></i>
                                <input type="number" name="year_establ" class="form-control" placeholder="مثال: 1970" min="1900" max="2030">
                            </div>
                        </div>
                    </div>

                    <!-- شعار الجامعة ونبذة تعريفية (حقلان بالديسكتوب، ومنفردان بالموبايل) -->
                    <div class="form-row-grid-2" style="margin-top:12px;align-items:flex-start;">
                        <div class="form-group">
                            <label class="univ-field-label">
                                <i class="fa-solid fa-image"></i>
                                شعار الجامعة الرسمي (Logo)
                            </label>
                            <div class="univ-logo-upload-zone" onclick="document.getElementById('univLogoInput').click()">
                                <div class="univ-logo-thumb-preview" id="univLogoPreview">
                                    <i class="fa-solid fa-cloud-arrow-up" style="color:#94a3b8;font-size:20px;"></i>
                                </div>
                                <div style="flex:1;">
                                    <div style="font-size:12.5px;font-weight:700;color:#0f172a;" id="univLogoFilename">اضغط لرفع الشعار أو اسحبه هنا</div>
                                    <div style="font-size:11px;color:#64748b;margin-top:2px;">صيغ مدعومة: PNG, JPG, SVG بحد أقصى 3MB</div>
                                </div>
                                <button type="button" class="btn btn-xs" style="background:#072b4f;color:#fff;border-radius:6px;font-size:11px;pointer-events:none;">تصفح</button>
                                <input type="file" id="univLogoInput" name="logo" accept="image/png,image/jpg,image/jpeg,image/svg+xml" style="display:none;" onchange="handleLogoPreview(this)">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="univ-field-label">
                                <i class="fa-solid fa-align-right"></i>
                                نبذة تعريفية ورسالة الجامعة
                            </label>
                            <div class="univ-input-wrap">
                                <i class="fa-solid fa-pen-nib univ-input-icon" style="top:20px;"></i>
                                <textarea name="description" class="form-control" rows="2" placeholder="وصف موجز عن الجامعة..." style="resize:vertical;"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- بطاقة 2: الموقع الجغرافي والانتشار -->
                <div class="univ-modal-card">
                    <div class="univ-card-step-title">
                        <div style="display:flex;align-items:center;">
                            <span class="univ-card-step-badge">٢</span>
                            <span style="font-size:14px;font-weight:800;color:#072b4f;">الموقع الجغرافي والمقر الرئيسي</span>
                        </div>
                        <span style="font-size:11.5px;color:#64748b;background:#f1f5f9;padding:3px 8px;border-radius:6px;font-weight:600;">البيانات الجغرافية</span>
                    </div>

                    <!-- المحافظة والعنوان (حقلان بالديسكتوب، ومنفردان بالموبايل) -->
                    <div class="modal-row-geo">
                        <div class="form-group">
                            <label class="univ-field-label">
                                <i class="fa-solid fa-map-location-dot"></i>
                                المحافظة / المديرية <span class="req">*</span>
                            </label>
                            <div class="univ-input-wrap">
                                <i class="fa-solid fa-location-dot univ-input-icon"></i>
                                <input type="text" name="district" class="form-control" placeholder="مثال: أمانة العاصمة، تعز، عدن، حضرموت..." required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="univ-field-label">
                                <i class="fa-solid fa-road"></i>
                                العنوان والموقع التفصيلي <span class="req">*</span>
                            </label>
                            <div class="univ-input-wrap">
                                <i class="fa-solid fa-map-pin univ-input-icon"></i>
                                <input type="text" name="address" class="form-control" placeholder="الشارع، الحي، المعلم القريب..." required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top:12px;">
                        <label class="univ-field-label">
                            <i class="fa-solid fa-crosshairs"></i>
                            إحداثيات الخريطة (Latitude & Longitude)
                            <span style="font-size:11px;font-weight:normal;color:#64748b;margin-right:auto;">(حقلان بسطر واحد بالأرقام)</span>
                        </label>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <div class="univ-input-wrap">
                                <i class="fa-solid fa-arrows-up-down univ-input-icon"></i>
                                <input type="number" step="any" name="latitude" class="form-control" placeholder="خط العرض: 15.3694">
                            </div>
                            <div class="univ-input-wrap">
                                <i class="fa-solid fa-arrows-left-right univ-input-icon"></i>
                                <input type="number" step="any" name="longitude" class="form-control" placeholder="خط الطول: 44.1910">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- بطاقة 3: قنوات التواصل والاعتماد الرقمي -->
                <div class="univ-modal-card">
                    <div class="univ-card-step-title">
                        <div style="display:flex;align-items:center;">
                            <span class="univ-card-step-badge">٣</span>
                            <span style="font-size:14px;font-weight:800;color:#072b4f;">قنوات التواصل والاعتماد الرقمي</span>
                        </div>
                        <span style="font-size:11.5px;color:#64748b;background:#f1f5f9;padding:3px 8px;border-radius:6px;font-weight:600;">بيانات الاتصال</span>
                    </div>

                    <!-- الهاتف + الواتساب (حقلان بسطر واحد بالموبايل) + البريد (سطر كامل بالموبايل، و3 حقول بالديسكتوب) -->
                    <div class="modal-row-contact">
                        <div class="form-group">
                            <label class="univ-field-label">
                                <i class="fa-solid fa-phone"></i>
                                الهاتف الرسمي للجامعة
                            </label>
                            <div class="univ-input-wrap">
                                <i class="fa-solid fa-phone-volume univ-input-icon"></i>
                                <input type="text" name="phone" class="form-control" placeholder=": 01-123456 " dir="ltr" style="text-align:right;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="univ-field-label">
                                <i class="fa-brands fa-whatsapp" style="color:#10b981;"></i>
                                هاتف شؤون الطلاب/واتساب
                            </label>
                            <div class="univ-input-wrap">
                                <i class="fa-solid fa-mobile-screen-button univ-input-icon"></i>
                                <input type="text" name="contact_phone" class="form-control" placeholder="7xxxxxxxx" dir="ltr" style="text-align:right;">
                            </div>
                        </div>

                        <div class="form-group modal-contact-email">
                            <label class="univ-field-label">
                                <i class="fa-solid fa-envelope"></i>
                                البريد الإلكتروني الرسمي
                            </label>
                            <div class="univ-input-wrap">
                                <i class="fa-solid fa-at univ-input-icon"></i>
                                <input type="email" name="email" class="form-control" placeholder="admission@univ.edu.ye" dir="ltr" style="text-align:right;">
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top:12px;">
                        <label class="univ-field-label">
                            <i class="fa-solid fa-globe"></i>
                            الموقع الإلكتروني الرسمي للجامعة
                        </label>
                        <div class="univ-input-wrap">
                            <i class="fa-solid fa-link univ-input-icon"></i>
                            <input type="url" name="website" class="form-control" placeholder="https://www.univ.edu.ye" dir="ltr" style="text-align:right;">
                        </div>
                    </div>
                </div>

                <!-- بطاقة 4: الهيكل الأكاديمي الأولي (الكليات والتخصصات) -->
                <div class="univ-modal-card">
                    <div class="univ-card-step-title">
                        <div style="display:flex;align-items:center;">
                            <span class="univ-card-step-badge">٤</span>
                            <div>
                                <span style="font-size:14px;font-weight:800;color:#072b4f;">الهيكل الأكاديمي الأولي (الكليات والتخصصات)</span>
                                <div style="font-size:11.5px;color:#64748b;margin-top:2px;">يمكنك إضافة الكليات وتخصصاتها الآن أو ترك إدارتها للجامعة لاحقاً</div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-navy btn-sm" onclick="addCollegeField()" style="background:#072b4f;color:#f0c842;border:1px solid #f0c842;font-size:12px;font-weight:700;">
                            <i class="fa-solid fa-plus-circle"></i> إضافة كلية جديدة
                        </button>
                    </div>

                    <div id="collegesContainer" style="display:flex;flex-direction:column;gap:12px;">
                        <!-- يتم حقن الكليات ديناميكياً هنا -->
                    </div>
                </div>

                <!-- بطاقة 5: بيانات الدخول الإداري الأولية للجامعة -->
                <div class="univ-account-card">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;padding-bottom:10px;border-bottom:1.5px dashed rgba(240,200,66,0.5);">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:34px;height:34px;border-radius:10px;background:#072b4f;color:#f0c842;display:flex;align-items:center;justify-content:center;font-size:16px;">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <div>
                                <strong style="font-size:14.5px;color:#072b4f;">تحديد بيانات الدخول الأولية لحساب الجامعة</strong>
                                <div style="font-size:11.5px;color:#64748b;margin-top:2px;">حساب إداري آمن يُمكّن الجامعة من إدارة طلبات التسجيل والتخصصات فوراً</div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-ghost btn-xs" onclick="generateRandomUnivCredentials()" style="background:#ffffff;border:1px solid #cbd5e1;font-size:11px;font-weight:700;color:#072b4f;padding:4px 10px;border-radius:8px;">
                            <i class="fa-solid fa-wand-magic-sparkles" style="color:#d97706;"></i> توليد تلقائي
                        </button>
                    </div>

                    <!-- اسم المستخدم وكلمة المرور (حقلان بسطر واحد بالشاشة الكبيرة والصغيرة) -->
                    <div class="modal-row-auth">
                        <div class="form-group">
                            <label class="univ-field-label">
                                <i class="fa-solid fa-user-shield"></i>
                                اسم المستخدم للدخول <span class="req">*</span>
                            </label>
                            <div class="univ-input-wrap">
                                <i class="fa-solid fa-user univ-input-icon"></i>
                                <input type="text" name="username" id="newUnivUsername" class="form-control" placeholder="مثال: univ_sanaa" required minlength="4" dir="ltr" style="text-align:right;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="univ-field-label">
                                <i class="fa-solid fa-key"></i>
                                كلمة المرور الأولية للحساب <span class="req">*</span>
                            </label>
                            <div class="univ-input-wrap">
                                <i class="fa-solid fa-lock univ-input-icon"></i>
                                <input type="password" name="password" id="newUnivPassword" class="form-control" placeholder="6 أحرف أو أرقام على الأقل" required minlength="6" dir="ltr" style="text-align:right;padding-left:36px;">
                                <button type="button" onclick="togglePasswordVisibility('newUnivPassword', this)" style="position:absolute;left:10px;background:none;border:none;color:#64748b;cursor:pointer;font-size:13px;" title="إظهار / إخفاء كلمة المرور">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Premium Modal Footer (دائماً ظاهر وثابت في أسفل النافذة عند فتحها) -->
        <div class="modal-footer" style="background:#ffffff;padding:14px 24px;border-top:1.5px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;box-shadow:0 -4px 12px rgba(0,0,0,0.03);">
            <button class="btn btn-ghost" onclick="closeModal('addUniversityModal')" style="font-weight:700;padding:9px 18px;border-radius:10px;">
                <i class="fa-solid fa-xmark"></i> إلغاء
            </button>
            <button class="btn btn-navy" onclick="submitAddUniversity()" id="addUnivBtn" style="background:linear-gradient(135deg, #072b4f 0%, #0d3b66 100%);color:#fff;border:1.5px solid #f0c842;padding:10px 24px;border-radius:10px;font-size:13.5px;font-weight:800;box-shadow:0 4px 15px rgba(7,43,79,0.25);display:flex;align-items:center;gap:8px;cursor:pointer;">
                <i class="fa-solid fa-check" style="color:#f0c842;"></i> اعتماد وحفظ الجامعة وإنشاء الحساب
            </button>
        </div>
    </div>
</div>





<!-- ============================================================
     MODAL: تسليم وثيقة الاعتماد وبيانات الدخول للجامعة بنجاح
     ============================================================ -->
<div class="modal-overlay" id="univSuccessCredentialsModal">
    <div class="modal modal-md" style="border-radius:18px;overflow:hidden;border:1.5px solid #f0c842;box-shadow:0 25px 60px -15px rgba(10,25,47,0.4);max-width:580px;">
        <div style="background:linear-gradient(135deg, #072b4f 0%, #0d3b66 60%, #155e9c 100%);padding:22px 24px;border-bottom:3px solid #f0c842;color:#fff;text-align:center;position:relative;">
            <div style="width:56px;height:56px;border-radius:50%;background:rgba(240,200,66,0.2);border:2px solid #f0c842;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-size:26px;color:#f0c842;box-shadow:0 4px 14px rgba(0,0,0,0.2);">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h3 style="margin:0;font-size:18px;font-weight:800;color:#ffffff;">تم اعتماد الجامعة وإنشاء الحساب بنجاح</h3>
            <p style="margin:5px 0 0;font-size:13px;color:rgba(255,255,255,0.85);" id="scUnivNameTitle">جامعة جديدة</p>
        </div>

        <div class="modal-body" style="background:#f8fafc;padding:22px;">
            <!-- حالة إرسال البريد الإلكتروني -->
            <div id="scEmailStatusBox" style="margin-bottom:16px;"></div>

            <!-- بطاقة بيانات الدخول الرسمية -->
            <div style="background:linear-gradient(135deg, #f0f7ff 0%, #fefce8 100%);border:1.5px solid #f0c842;border-radius:14px;padding:18px;box-shadow:0 4px 14px rgba(240,200,66,0.12);margin-bottom:18px;">
                <div style="font-size:13.5px;font-weight:800;color:#072b4f;margin-bottom:14px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px dashed rgba(240,200,66,0.6);padding-bottom:8px;">
                    <span style="display:flex;align-items:center;gap:6px;">
                        <i class="fa-solid fa-key" style="color:#d97706;"></i> بيانات الدخول الرسمية للبوابة
                    </span>
                    <span style="font-size:11px;background:#072b4f;color:#f0c842;padding:2px 8px;border-radius:6px;font-weight:700;">حساب معتمد</span>
                </div>

                <div style="display:flex;flex-direction:column;gap:10px;font-size:13px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;background:#fff;padding:8px 12px;border-radius:8px;border:1px solid #e2e8f0;">
                        <span style="color:#64748b;font-weight:600;"><i class="fa-solid fa-link" style="margin-left:6px;color:#0d3b66;"></i>رابط البوابة:</span>
                        <a href="{{ url('/login') }}" id="scLoginUrl" target="_blank" style="color:#0d3b66;font-weight:700;direction:ltr;text-decoration:none;">{{ url('/login') }}</a>
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;background:#fff;padding:8px 12px;border-radius:8px;border:1px solid #e2e8f0;">
                        <span style="color:#64748b;font-weight:600;"><i class="fa-solid fa-user" style="margin-left:6px;color:#0d3b66;"></i>اسم المستخدم:</span>
                        <span id="scUsername" style="font-weight:800;color:#072b4f;font-family:Consolas, monospace;direction:ltr;font-size:14px;"></span>
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;background:#fff;padding:8px 12px;border-radius:8px;border:1px solid #e2e8f0;">
                        <span style="color:#64748b;font-weight:600;"><i class="fa-solid fa-lock" style="margin-left:6px;color:#0d3b66;"></i>كلمة المرور المؤقتة:</span>
                        <span id="scPassword" style="font-weight:800;color:#b91c1c;font-family:Consolas, monospace;direction:ltr;font-size:14px;background:#fee2e2;padding:2px 8px;border-radius:6px;"></span>
                    </div>
                </div>
            </div>

            <!-- أزرار الإرسال السريع والمشاركة -->
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;">
                <button type="button" class="btn" onclick="copyUnivCredentialsText()" style="background:#ffffff;border:1.5px solid #cbd5e1;color:#072b4f;font-weight:800;padding:10px;border-radius:10px;font-size:13px;display:flex;align-items:center;justify-content:center;gap:6px;cursor:pointer;">
                    <i class="fa-solid fa-copy" style="color:#0284c7;"></i> نسخ البيانات بنقرة
                </button>
                <a href="#" id="scWhatsAppBtn" target="_blank" class="btn" style="background:#10b981;border:1.5px solid #059669;color:#ffffff;font-weight:800;padding:10px;border-radius:10px;font-size:13px;display:flex;align-items:center;justify-content:center;gap:6px;text-decoration:none;">
                    <i class="fa-brands fa-whatsapp" style="font-size:16px;"></i> إرسال عبر واتساب
                </a>
            </div>

            <p style="font-size:11.5px;color:#64748b;margin:0;line-height:1.6;text-align:center;">
                <i class="fa-solid fa-circle-info" style="color:#0284c7;"></i>
                يمكنك الآن تسليم البيانات لمسؤول الجامعة فوراً عبر الواتساب أو نسخها، مع العلم بأنه تم إرسالها أيضاً إلى البريد المسجل.
            </p>
        </div>

        <div class="modal-footer" style="background:#ffffff;padding:14px 20px;border-top:1px solid #e2e8f0;display:flex;justify-content:center;">
            <button class="btn btn-navy" onclick="closeSuccessCredentialsModal()" style="font-weight:800;padding:9px 30px;border-radius:10px;background:#072b4f;color:#fff;cursor:pointer;">
                <i class="fa-solid fa-check"></i> تم، استعراض قائمة الجامعات
            </button>
        </div>
    </div>
</div>


<!-- ============================================================
     MODAL 2: عرض نشاط الجامعة وملفها (دون انتهاك الخصوصية)
     ============================================================ -->
<div class="modal-overlay" id="viewUniversityModal">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3><i class="fa-solid fa-building-columns" style="color:var(--accent);"></i> استعراض نشاط الجامعة وملفها الأكاديمي</h3>
            <button class="modal-close" onclick="closeModal('viewUniversityModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body" id="viewUniversityBody">
            <div style="text-align:center;padding:50px;color:var(--ink-muted);">
                <span class="spinner" style="border-top-color:var(--primary);width:32px;height:32px;border-width:3px;"></span>
                <p style="margin-top:14px;font-weight:700;">جارٍ استرداد بيانات الجامعة ونشاطها...</p>
            </div>
        </div>
        <div class="modal-footer" style="display:flex; justify-content:space-between; align-items:center; width:100%; flex-wrap:wrap; gap:10px;">
            <a href="#" id="modalDirectModerationLink" class="btn btn-sm" style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                <i class="fa-solid fa-shield-halved"></i> فحص رقابي وحجب في مركز الحوكمة
            </a>
            <div style="display:flex; gap:8px;">
                <button class="btn btn-ghost" onclick="closeModal('viewUniversityModal')">إغلاق</button>
                <button class="btn btn-navy" id="modalEditBtn" onclick="openEditFromView()">
                    <i class="fa-solid fa-pen-to-square"></i> تعديل بيانات الجامعة
                </button>
            </div>
        </div>
    </div>
</div>





<!-- ============================================================
     MODAL 3: تعديل بيانات الجامعة
     ============================================================ -->
<div class="modal-overlay" id="editUniversityModal">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3><i class="fa-solid fa-pen-to-square" style="color:var(--accent);"></i> تعديل بيانات الجامعة</h3>
            <button class="modal-close" onclick="closeModal('editUniversityModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body" style="background:#f8fafc;padding:20px;">
            <form id="editUniversityForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="editUnivId">

                <div class="form-section-card">
                    <div class="form-section-head"><i class="fa-solid fa-building-columns"></i> البيانات الأساسية</div>
                    <div class="form-row-grid-3">
                        <div class="form-group">
                            <label class="form-label">اسم الجامعة <span class="req">*</span></label>
                            <input type="text" name="university_name" id="editUnivName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">نوع الجامعة <span class="req">*</span></label>
                            <select name="university_type" id="editUnivType" class="form-control" required>
                                <option value="حكومية">جامعة حكومية</option>
                                <option value="خاصة">جامعة خاصة</option>
                                <option value="أهلية">جامعة أهلية</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">سنة التأسيس</label>
                            <input type="number" name="year_establ" id="editUnivYear" class="form-control">
                        </div>
                    </div>

                    <div class="form-row-grid-2">
                        <div class="form-group">
                            <label class="form-label">المحافظة / المديرية <span class="req">*</span></label>
                            <input type="text" name="district" id="editUnivDistrict" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">العنوان التفصيلي <span class="req">*</span></label>
                            <input type="text" name="address" id="editUnivAddress" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-row-grid-2">
                        <div class="form-group">
                            <label class="form-label">إحداثيات الخريطة</label>
                            <div style="display:flex;gap:6px;">
                                <input type="number" step="any" name="latitude" id="editUnivLat" class="form-control" placeholder="خط العرض">
                                <input type="number" step="any" name="longitude" id="editUnivLng" class="form-control" placeholder="خط الطول">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">تغيير الشعار (اختياري)</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="form-group" style="margin-top:6px;">
                        <label class="form-label">نبذة تعريفية</label>
                        <textarea name="description" id="editUnivDesc" class="form-control" rows="2"></textarea>
                    </div>
                </div>

                <div class="form-section-card">
                    <div class="form-section-head"><i class="fa-solid fa-phone"></i> بيانات التواصل الرسمية</div>
                    <div class="form-row-grid-3">
                        <div class="form-group">
                            <label class="form-label">رقم الهاتف</label>
                            <input type="text" name="phone" id="editUnivPhone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">هاتف التواصل / واتساب</label>
                            <input type="text" name="contact_phone" id="editUnivContactPhone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" id="editUnivEmail" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">الموقع الإلكتروني</label>
                        <input type="url" name="website" id="editUnivWebsite" class="form-control">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('editUniversityModal')">إلغاء</button>
            <button class="btn btn-navy" onclick="submitEditUniversity()" id="editUnivBtn">
                <i class="fa-solid fa-floppy-disk"></i> حفظ التعديلات
            </button>
        </div>
    </div>
</div>





<!-- ============================================================
     MODAL 4: تغيير حالة الجامعة (نشطة / معلقة / محظورة)
     ============================================================ -->
<div class="modal-overlay" id="statusUnivModal">
    <div class="modal modal-sm">
        <div class="modal-header">
            <h3><i class="fa-solid fa-toggle-on" style="color:var(--accent);"></i> تغيير حالة تشغيل الجامعة</h3>
            <button class="modal-close" onclick="closeModal('statusUnivModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <p style="font-size:13.5px;color:var(--ink-muted);margin-bottom:14px;">
                الحالة الحالية: <strong id="currentUnivStatus" style="color:var(--primary-dark);"></strong>
            </p>
            <div class="form-group">
                <label class="form-label">اختر الحالة المطلوبة</label>
                <select id="newUnivStatus" class="form-control">
                    <option value="نشطة">نشطة (ظاهرة بالمنصة وتقبل طلبات التسجيل)</option>
                    <option value="معلقة">معلقة (إيقاف مؤقت للطلبات)</option>
                    <option value="محظورة">محظورة (حظر الحساب وإخفاء الجامعة)</option>
                </select>
            </div>
            <p style="font-size:11.5px;color:var(--ink-muted);margin-top:10px;">
                <i class="fa-solid fa-circle-info"></i> يتلون صف الجامعة تلقائياً في الجدول بالأخضر الهادئ في حال كانت نشطة، أو بالأحمر الفاتح في حال تعليقها أو حظرها.
            </p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('statusUnivModal')">إلغاء</button>
            <button class="btn btn-navy" onclick="submitStatusChange()" id="statusUnivBtn">
                <i class="fa-solid fa-check"></i> تأكيد التغيير
            </button>
        </div>
    </div>
</div>





<!-- ============================================================
     MODAL 5: تأكيد حذف جامعة
     ============================================================ -->
<div class="modal-overlay" id="deleteUnivModal">
    <div class="modal modal-sm">
        <div class="modal-header">
            <h3 style="color:var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i> تأكيد حذف الجامعة</h3>
            <button class="modal-close" onclick="closeModal('deleteUnivModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <p style="font-size:14px;color:var(--ink);">
                هل أنت متأكد من حذف جامعة <strong id="deleteUnivName" style="color:var(--danger);"></strong> بالكامل؟
            </p>
            <div style="background:#fef2f2;border:1px solid #fee2e2;border-radius:var(--radius-sm);padding:12px;margin-top:12px;font-size:12.5px;color:#b91c1c;">
                <i class="fa-solid fa-circle-exclamation"></i>
                تحذير: سيتم حذف كافة السجلات والبيانات التابعة للجامعة.
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('deleteUnivModal')">إلغاء</button>
            <button class="btn btn-danger" onclick="submitDeleteUniv()" id="deleteUnivBtn">
                <i class="fa-solid fa-trash"></i> حذف نهائياً
            </button>
        </div>
    </div>
</div>




<!-- ============================================================
     MODAL 6: إيقاف الظهور الرقابي لعنصر محدد (حجر رقابي فوري ومستقل)
     ============================================================ -->
<div class="modal-overlay" id="quarantineActionModal" style="z-index: 2200;">
    <div class="modal modal-md">
        <div class="modal-header">
            <h3 style="color:#b91c1c;"><i class="fa-solid fa-eye-slash"></i> إيقاف ظهور عنصر رقابياً (حجب فوري)</h3>
            <button class="modal-close" onclick="closeModal('quarantineActionModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body" style="padding:20px;">
            <div style="background:#fef2f2;border:1px solid #fee2e2;border-radius:8px;padding:12px;margin-bottom:16px;">
                <div style="font-weight:800;font-size:13.5px;color:#991b1b;margin-bottom:4px;" id="quarantineItemTitleDisplay">اسم العنصر</div>
                <div style="font-size:11.5px;color:#7f1d1d;line-height:1.5;">
                    <i class="fa-solid fa-shield-halved"></i> سيتم إخفاء هذا العنصر فوراً من المنصة وواجهة الطلاب دون المساس ببقية كليات وبرامج الجامعة.
                </div>
            </div>

            <form id="quarantineActionForm">
                <input type="hidden" id="qUnivId">
                <input type="hidden" id="qItemType">
                <input type="hidden" id="qItemId">
                <input type="hidden" id="qItemTitle">

                <div class="form-group" style="margin-bottom:14px;">
                    <label class="form-label" style="font-weight:700;">سبب المخالفة / الحجب الرقابي <span style="color:var(--danger)">*</span></label>
                    <select id="qPresetReason" class="form-control" onchange="handlePresetReasonChange(this)" style="margin-bottom:8px;">
                        <option value="">-- اختر سبب المخالفة الشائع --</option>
                        <option value="برنامج أو تخصص غير مرخص رسمياً من وزارة التعليم العالي">برنامج أو تخصص غير مرخص رسمياً من وزارة التعليم العالي</option>
                        <option value="رسوم ومبالغ دراسية مضللة أو غير مطابقة للائحة المعتمدة">رسوم ومبالغ دراسية مضللة أو غير مطابقة للائحة المعتمدة</option>
                        <option value="شروط قبول أو معدلات ثانوية مخالفة لضوابط التنسيق المركزي">شروط قبول أو معدلات ثانوية مخالفة لضوابط التنسيق المركزي</option>
                        <option value="محتوى دعائي غير لائق أو ترويجات أكاديمية وهمية">محتوى دعائي غير لائق أو ترويجات أكاديمية وهمية</option>
                        <option value="custom">أخرى (كتابة سبب مخصص)...</option>
                    </select>
                    <textarea id="qViolationReason" class="form-control" rows="2" placeholder="اكتب تفاصيل سبب المخالفة..." required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight:700;">توجيهات لمدير الجامعة لمعالجة المخالفة (اختياري)</label>
                    <textarea id="qAdminGuidance" class="form-control" rows="2" placeholder="ما يجب على الجامعة تعديله أو إرفاقه لفك الحجب..."></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('quarantineActionModal')">إلغاء</button>
            <button class="btn btn-danger" onclick="submitQuarantineAction()" id="quarantineSubmitBtn">
                <i class="fa-solid fa-eye-slash"></i> تأكيد الحجب الفوري
            </button>
        </div>
    </div>
</div>




<!-- ============================================================
     MODAL 7: مركز المحتوى المحجوب رقابياً عبر المنصة
     ============================================================ -->
<div class="modal-overlay" id="moderationCenterModal" style="z-index: 2050;">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3><i class="fa-solid fa-shield-halved" style="color:var(--accent);"></i> مركز الرقابة والحجر الإداري للمحتوى</h3>
            <button class="modal-close" onclick="closeModal('moderationCenterModal')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body" style="padding:20px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:10px;">
                <p style="font-size:12.5px;color:var(--ink-muted);margin:0;">
                    قائمة بكافة التخصصات والعناصر المحجوبة من الظهور للطلاب في المنصة مع أسباب الإيقاف وتتبع استجابة الجامعات.
                </p>
                <button type="button" class="btn btn-ghost btn-sm" onclick="loadQuarantinedItems()">
                    <i class="fa-solid fa-rotate"></i> تحديث القائمة
                </button>
            </div>

            <div id="moderationCenterList" style="min-height:160px;">
                <!-- سيتم ملؤه ديناميكياً بواسطة JavaScript -->
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModal('moderationCenterModal')">إغلاق</button>
        </div>
    </div>
</div>




@endsection

@section('scripts')
<script>
    let activeUnivId = null;
    let collegeCount = 0;
    let currentLoadedUniv = null;

    // إضافة حقل كلية وتخصصات بتصميم بصري مميز
    function addCollegeField() {
        const container = document.getElementById('collegesContainer');
        const idx = collegeCount++;
        const div = document.createElement('div');
        div.id = `college-block-${idx}`;
        div.style.cssText = 'border:1.5px solid #e2e8f0;border-radius:12px;padding:14px 16px;background:#ffffff;position:relative;box-shadow:0 2px 6px rgba(0,0,0,0.03);border-right:4px solid #0d3b66;';
        div.innerHTML = `
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;border-bottom:1px dashed #f1f5f9;padding-bottom:6px;">
                <strong style="font-size:13px;color:#072b4f;display:flex;align-items:center;gap:6px;">
                    <i class="fa-solid fa-graduation-cap" style="color:#f0c842;"></i> كلية جديدة (#${idx+1})
                </strong>
                <button type="button" class="btn btn-ghost btn-xs" onclick="document.getElementById('college-block-${idx}').remove()" style="color:#ef4444;padding:3px 8px;border-radius:6px;font-size:11.5px;font-weight:700;">
                    <i class="fa-solid fa-trash-can"></i> حذف الكلية
                </button>
            </div>
            <div class="form-row-grid-2" style="margin-bottom:0;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="univ-field-label" style="font-size:12px;">
                        <i class="fa-solid fa-bookmark"></i> اسم الكلية <span class="req">*</span>
                    </label>
                    <div class="univ-input-wrap">
                        <i class="fa-solid fa-school univ-input-icon"></i>
                        <input type="text" name="colleges[${idx}][name]" class="form-control" placeholder="مثال: كلية الطب البشري أو الهندسة" required style="font-size:12.5px;">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="univ-field-label" style="font-size:12px;">
                        <i class="fa-solid fa-list-check"></i> التخصصات التابعة (تخصص في كل سطر)
                    </label>
                    <div class="univ-input-wrap">
                        <i class="fa-solid fa-tags univ-input-icon" style="top:18px;"></i>
                        <textarea name="colleges[${idx}][specs]" class="form-control" rows="1" placeholder="طب وجراحة عامة&#10;طب أسنان&#10;صيدلة سريرية" style="font-size:12px;"></textarea>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(div);
    }

    // معاينة شعار الجامعة المرفوع
    function handleLogoPreview(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const nameEl = document.getElementById('univLogoFilename');
            if (nameEl) nameEl.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewEl = document.getElementById('univLogoPreview');
                if (previewEl) previewEl.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:contain;">`;
            };
            reader.readAsDataURL(file);
        }
    }

    // إظهار / إخفاء كلمة المرور
    function togglePasswordVisibility(fieldId, btn) {
        const field = document.getElementById(fieldId);
        if (!field) return;
        if (field.type === 'password') {
            field.type = 'text';
            btn.innerHTML = '<i class="fa-solid fa-eye-slash"></i>';
        } else {
            field.type = 'password';
            btn.innerHTML = '<i class="fa-solid fa-eye"></i>';
        }
    }

    // توليد بيانات دخول أولية مقترحة
    function generateRandomUnivCredentials() {
        const univNameInput = document.querySelector('input[name="university_name"]');
        let prefix = 'univ_';
        if (univNameInput && univNameInput.value.trim()) {
            prefix += Math.floor(100 + Math.random() * 900);
        } else {
            prefix += 'adm_' + Math.floor(1000 + Math.random() * 9000);
        }
        const chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$';
        let pass = '';
        for (let i = 0; i < 10; i++) {
            pass += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        const userInput = document.getElementById('newUnivUsername');
        const passInput = document.getElementById('newUnivPassword');
        if (userInput && !userInput.value) userInput.value = prefix;
        if (passInput) {
            passInput.value = pass;
            passInput.type = 'text';
        }
        showToast('تم توليد بيانات دخول مقترحة بنجاح', 'info');
    }

    let lastCreatedUnivData = null;

    // إرسال إضافة جامعة جديدة
    async function submitAddUniversity() {
        const form = document.getElementById('addUniversityForm');
        const btn = document.getElementById('addUnivBtn');

        // جمع التخصصات
        const specsTextareas = form.querySelectorAll('textarea[name*="[specs]"]');
        specsTextareas.forEach((ta) => {
            const specs = ta.value.split('\n').filter(s => s.trim());
            ta.closest('[id^="college-block"]')?.querySelectorAll('input[name*="specializations"]').forEach(el => el.remove());
            specs.forEach((spec) => {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                const collegeNameIdx = ta.name.match(/\[(\d+)\]/)?.[1];
                hidden.name = `colleges[${collegeNameIdx}][specializations][]`;
                hidden.value = spec.trim();
                form.appendChild(hidden);
            });
        });

        const formData = new FormData(form);
        btn.innerHTML = '<span class="spinner"></span> جارٍ الحفظ والتسجيل وإرسال البريد...';
        btn.disabled = true;

        try {
            const res = await fetch('{{ route("admin.universities.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData,
            });
            const data = await res.json();

            if (data.success) {
                closeModal('addUniversityModal');
                form.reset();
                document.getElementById('collegesContainer').innerHTML = '';
                collegeCount = 0;

                lastCreatedUnivData = data;
                document.getElementById('scUnivNameTitle').textContent = data.university_name;
                document.getElementById('scUsername').textContent = data.username;
                document.getElementById('scPassword').textContent = data.raw_password;
                document.getElementById('scLoginUrl').textContent = data.login_url;
                document.getElementById('scLoginUrl').href = data.login_url;

                // حالة البريد الإلكتروني
                const emailBox = document.getElementById('scEmailStatusBox');
                if (data.email_sent) {
                    emailBox.innerHTML = `
                        <div style="background:#ecfdf5;border:1.5px solid #10b981;border-radius:10px;padding:12px 14px;color:#065f46;font-size:12.5px;display:flex;align-items:center;gap:10px;">
                            <i class="fa-solid fa-envelope-circle-check" style="color:#10b981;font-size:20px;"></i>
                            <div>
                                <strong style="font-size:13px;">تم إرسال بريد الاعتماد الرسمي تلقائياً!</strong><br>
                                <span style="font-size:11.5px;">وصلت وثيقة الدخول إلى البريد الإلكتروني: <strong>${data.email}</strong></span>
                            </div>
                        </div>
                    `;
                    showToast('تم حفظ الجامعة وإرسال بيانات الدخول إلى بريدها بنجاح!', 'success');
                } else if (data.email) {
                    emailBox.innerHTML = `
                        <div style="background:#fffbeb;border:1.5px solid #f59e0b;border-radius:10px;padding:12px 14px;color:#92400e;font-size:12.5px;display:flex;align-items:center;gap:10px;">
                            <i class="fa-solid fa-triangle-exclamation" style="color:#f59e0b;font-size:20px;"></i>
                            <div>
                                <strong style="font-size:13px;">تنبيه: تم حفظ الجامعة بنجاح وتعذر تسليم البريد</strong><br>
                                <span style="font-size:11.5px;">يرجى نسخ البيانات وتزويد مسؤول الجامعة بها عبر واتساب.</span>
                            </div>
                        </div>
                    `;
                    showToast('تم حفظ الجامعة ولكن تعذر تسليم البريد؛ استخدم الواتساب لتسليم البيانات.', 'warning');
                } else {
                    emailBox.innerHTML = `
                        <div style="background:#f1f5f9;border:1px solid #cbd5e1;border-radius:10px;padding:10px 14px;color:#475569;font-size:12px;display:flex;align-items:center;gap:8px;">
                            <i class="fa-solid fa-circle-info" style="color:#64748b;"></i>
                            <span>لم يتم تحديد بريد إلكتروني للجامعة؛ يرجى نسخ البيانات أو إرسالها عبر الواتساب.</span>
                        </div>
                    `;
                    showToast('تمت إضافة الجامعة بنجاح؛ يرجى تسليم بيانات الدخول للجامعة.', 'success');
                }

                // تجهيز رسالة ورابط الواتساب المباشر
                const waBtn = document.getElementById('scWhatsAppBtn');
                let rawPhone = data.phone || '';
                let cleanPhone = rawPhone.replace(/\D/g, '');
                if (cleanPhone.startsWith('0')) cleanPhone = cleanPhone.substring(1);
                if (!cleanPhone.startsWith('967') && cleanPhone.length === 9) cleanPhone = '967' + cleanPhone;

                const waMsg = `الأخوة الأعزاء في إدارة *${data.university_name}* المحترمون،\nتحية طيبة وبعد،،\nتم اعتماد جامعتكم رسمياً في *منصة EduYemen* للقبول والتنسيق الجامعي.\n\nبيانات الدخول الأولية لحساب الجامعة:\n🔗 رابط البوابة: ${data.login_url}\n👤 اسم المستخدم: ${data.username}\n🔑 كلمة المرور المؤقتة: ${data.raw_password}\n\nيرجى تسجيل الدخول وتغيير كلمة المرور فوراً من الإعدادات. خالص التحية والتقدير.`;

                if (cleanPhone) {
                    waBtn.href = `https://api.whatsapp.com/send?phone=${cleanPhone}&text=${encodeURIComponent(waMsg)}`;
                } else {
                    waBtn.href = `https://api.whatsapp.com/send?text=${encodeURIComponent(waMsg)}`;
                }

                openModal('univSuccessCredentialsModal');
            } else {
                const errors = data.errors ? Object.values(data.errors).flat().join('<br>') : (data.message || 'حدث خطأ أثناء الإضافة.');
                showToast(errors, 'error');
            }
        } catch (e) {
            showToast('حدث خطأ في الاتصال بالخادم.', 'error');
        } finally {
            btn.innerHTML = '<i class="fa-solid fa-check" style="color:#f0c842;"></i> اعتماد وحفظ الجامعة وإنشاء الحساب';
            btn.disabled = false;
        }
    }

    // نسخ بيانات الدخول إلى الحافظة
    function copyUnivCredentialsText() {
        if (!lastCreatedUnivData) return;
        const text = `بيانات اعتماد ودخول ${lastCreatedUnivData.university_name} - منصة EduYemen:\nرابط البوابة: ${lastCreatedUnivData.login_url}\nاسم المستخدم: ${lastCreatedUnivData.username}\nكلمة المرور المؤقتة: ${lastCreatedUnivData.raw_password}`;
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                showToast('تم نسخ بيانات الدخول إلى الحافظة بنجاح!', 'success');
            }).catch(() => {
                promptCopyFallback(text);
            });
        } else {
            promptCopyFallback(text);
        }
    }

    function promptCopyFallback(text) {
        window.prompt('اضغط Ctrl+C لنسخ بيانات الدخول:', text);
    }

    // إغلاق نافذة التسليم وتحديث الصفحة
    function closeSuccessCredentialsModal() {
        closeModal('univSuccessCredentialsModal');
        setTimeout(() => location.reload(), 400);
    }

    // استعراض تفاصيل الجامعة ونشاطها الكامل (مع مراعاة الخصوصية)
    async function viewUniversity(univId) {
        activeUnivId = univId;
        openModal('viewUniversityModal');
        const modLink = document.getElementById('modalDirectModerationLink');
        if (modLink) modLink.href = `/admin/moderation?university_id=${univId}`;
        const body = document.getElementById('viewUniversityBody');
        body.innerHTML = `<div style="text-align:center;padding:50px;color:var(--ink-muted);"><span class="spinner" style="border-top-color:var(--primary);width:32px;height:32px;border-width:3px;"></span><p style="margin-top:14px;font-weight:700;">جارٍ استرداد بيانات الجامعة ونشاطها...</p></div>`;

        try {
            const data = await adminFetch(`/admin/universities/${univId}`, 'GET');
            if (data.success) {
                const u = data.university;
                currentLoadedUniv = u;

                const mapSearchQuery = encodeURIComponent(u.name + ' ' + (u.district || 'صنعاء') + ' اليمن');
                const mapUrl = (u.latitude && u.longitude)
                    ? `https://www.google.com/maps?q=${u.latitude},${u.longitude}`
                    : `https://www.google.com/maps/search/?api=1&query=${mapSearchQuery}`;

                body.innerHTML = `
                    <!-- Header Profile -->
                    <div style="display:flex;align-items:center;gap:16px;margin-bottom:18px;padding-bottom:16px;border-bottom:1px solid var(--border);">
                        <div style="width:64px;height:64px;border-radius:12px;overflow:hidden;background:#fff;border:1px solid var(--border);padding:3px;box-shadow:var(--shadow-sm);flex-shrink:0;">
                            <img src="${u.logo_url}" alt="" style="width:100%;height:100%;object-fit:contain;" onerror="this.src='{{ asset('logo.png') }}'">
                        </div>
                        <div style="flex:1;">
                            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                                <h3 style="font-size:17px;font-weight:900;color:var(--primary-dark);margin:0;">${u.name}</h3>
                                <div style="display:flex;gap:6px;">
                                    <span class="badge ${u.type=='حكومية'?'badge-primary':'badge-accent'}">${u.type}</span>
                                    <span class="badge ${u.status=='نشطة'?'badge-green':'badge-red'}">${u.status}</span>
                                </div>
                            </div>
                            <div style="font-size:12px;color:var(--ink-muted);margin-top:3px;">
                                <i class="fa-solid fa-location-dot" style="color:var(--accent);"></i> ${u.district || 'اليمن'}
                                ${u.year_establ ? ` • تأسست عام ${u.year_establ}` : ''}
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Bar -->
                    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:16px;">
                        <div style="background:var(--primary-subtle);border-radius:8px;padding:10px;text-align:center;">
                            <div style="font-size:20px;font-weight:900;color:var(--primary);">${u.colleges_count}</div>
                            <div style="font-size:11px;font-weight:700;color:var(--primary-dark);">كليات معتمدة</div>
                        </div>
                        <div style="background:#f5f3ff;border-radius:8px;padding:10px;text-align:center;">
                            <div style="font-size:20px;font-weight:900;color:#7c3aed;">${u.specs_count}</div>
                            <div style="font-size:11px;font-weight:700;color:#5b21b6;">تخصص أكاديمي</div>
                        </div>
                        <div style="background:#ecfdf5;border-radius:8px;padding:10px;text-align:center;">
                            <div style="font-size:20px;font-weight:900;color:#059669;">${u.requests_count}</div>
                            <div style="font-size:11px;font-weight:700;color:#065f46;">طلبات التحاق</div>
                        </div>
                        <div style="background:var(--accent-subtle);border-radius:8px;padding:10px;text-align:center;">
                            <div style="font-size:20px;font-weight:900;color:#b45309;">${u.ads_count}</div>
                            <div style="font-size:11px;font-weight:700;color:#92400e;">إعلانات منشورة</div>
                        </div>
                    </div>

                    <!-- Tabs Inside Modal -->
                    <div class="modal-tab-nav">
                        <button class="modal-tab-btn active" onclick="switchModalTab('info', this)"><i class="fa-solid fa-circle-info"></i> بيانات الجامعة</button>
                        <button class="modal-tab-btn" onclick="switchModalTab('colleges', this)"><i class="fa-solid fa-graduation-cap"></i> الكليات والتخصصات (${u.colleges.length})</button>
                        <button class="modal-tab-btn" onclick="switchModalTab('requests', this)"><i class="fa-solid fa-file-signature"></i> نشاط طلبات التسجيل</button>
                    </div>

                    <!-- Tab 1: Info -->
                    <div id="modalTab-info" class="modal-tab-content">
                        <div class="form-row-grid-2" style="margin-bottom:12px;">
                            <div><div style="font-size:11px;color:var(--ink-muted);font-weight:700;">الموقع / المحافظة</div><div style="font-weight:700;font-size:13px;">${u.district||'—'}</div></div>
                            <div>
                                <div style="font-size:11px;color:var(--ink-muted);font-weight:700;">العنوان والموقع على الخريطة</div>
                                <div style="font-size:13px;">${u.address||'—'}</div>
                                <a href="${mapUrl}" target="_blank" class="map-link-btn">
                                    <i class="fa-solid fa-map-location-dot"></i> فتح الموقع في خرائط Google
                                </a>
                            </div>
                            <div><div style="font-size:11px;color:var(--ink-muted);font-weight:700;">رقم الهاتف الرسمي</div><div style="font-size:13px;">${u.phone||'—'}</div></div>
                            <div><div style="font-size:11px;color:var(--ink-muted);font-weight:700;">هاتف شؤون الطلاب</div><div style="font-size:13px;">${u.contact_phone||'—'}</div></div>
                            <div><div style="font-size:11px;color:var(--ink-muted);font-weight:700;">البريد الإلكتروني</div><div style="font-size:13px;">${u.email||'—'}</div></div>
                            <div><div style="font-size:11px;color:var(--ink-muted);font-weight:700;">الموقع الإلكتروني</div><div style="font-size:13px;"><a href="${u.website}" target="_blank" style="color:var(--primary);text-decoration:underline;">${u.website||'—'}</a></div></div>
                        </div>
                        ${u.description ? `<div style="font-size:11px;color:var(--ink-muted);font-weight:700;margin-bottom:4px;">نبذة تعريفية</div><div style="font-size:12.5px;line-height:1.7;background:#fff;border:1px solid var(--border-light);padding:10px;border-radius:8px;">${u.description}</div>` : ''}
                    </div>

                    <!-- Tab 2: Colleges & Specs with Visibility & Quarantine Controls -->
                    <div id="modalTab-colleges" class="modal-tab-content" style="display:none;">
                        ${u.colleges.length > 0 ? `
                        <div style="display:flex;flex-direction:column;gap:12px;">
                            ${u.colleges.map(c => `
                            <div style="border:1px solid var(--border);border-radius:10px;padding:12px 14px;background:#fff;box-shadow:0 1px 3px rgba(0,0,0,0.03);">
                                <div style="font-weight:800;font-size:13.5px;color:var(--primary-dark);margin-bottom:8px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px dashed var(--border-light);padding-bottom:6px;">
                                    <div style="display:flex;align-items:center;gap:6px;">
                                        <i class="fa-solid fa-building" style="color:var(--accent);"></i>
                                        <span>${c.name}</span>
                                    </div>
                                    <span style="font-size:11px;color:var(--ink-muted);font-weight:600;">(${c.specializations.length} تخصص)</span>
                                </div>
                                <div style="display:flex;flex-direction:column;gap:6px;">
                                    ${c.specializations.length > 0 ? c.specializations.map(s => `
                                        <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 12px;border-radius:8px;border:1px solid ${s.is_quarantined ? '#fca5a5' : 'var(--border-light)'};background:${s.is_quarantined ? '#fef2f2' : '#f8fafc'};gap:10px;flex-wrap:wrap;">
                                            <div style="display:flex;align-items:center;gap:8px;min-width:0;flex:1;">
                                                <i class="fa-solid fa-book-open" style="color:${s.is_quarantined ? '#dc2626' : 'var(--primary)'};font-size:12px;flex-shrink:0;"></i>
                                                <span style="font-weight:700;font-size:12.5px;color:var(--ink);">${s.name}</span>
                                                ${s.is_quarantined ? `
                                                    <span class="badge" style="background:#fee2e2;color:#991b1b;font-size:10px;border:1px solid #f87171;display:inline-flex;align-items:center;gap:4px;">
                                                        <i class="fa-solid fa-eye-slash"></i> محجوب رقابياً: ${s.violation_reason || 'مخالفة معايير'}
                                                    </span>` : `
                                                    <span class="badge badge-green" style="font-size:10px;display:inline-flex;align-items:center;gap:4px;">
                                                        <i class="fa-solid fa-circle-check"></i> معروض للطلاب
                                                    </span>`}
                                            </div>
                                            <div style="flex-shrink:0;">
                                                ${s.is_quarantined ? `
                                                    <button type="button" class="btn btn-sm" style="background:#10b981;color:#fff;font-size:11px;padding:4px 9px;border-radius:6px;font-weight:700;display:inline-flex;align-items:center;gap:5px;cursor:pointer;" 
                                                            onclick="resolveQuarantine(${s.moderation_id}, ${s.id})">
                                                        <i class="fa-solid fa-eye"></i> فك الحجب والاعتماد
                                                    </button>` : `
                                                    <button type="button" class="btn btn-sm" style="background:#ef4444;color:#fff;font-size:11px;padding:4px 9px;border-radius:6px;font-weight:700;display:inline-flex;align-items:center;gap:5px;cursor:pointer;" 
                                                            onclick="openQuarantineModal(${s.id})">
                                                        <i class="fa-solid fa-eye-slash"></i> إيقاف الظهور
                                                    </button>`}
                                            </div>
                                        </div>
                                    `).join('') : '<span style="font-size:11.5px;color:var(--ink-muted);padding:6px;">لا توجد تخصصات مسجلة بعد لهذه الكلية.</span>'}
                                </div>
                            </div>`).join('')}
                        </div>` : '<div class="empty-state" style="padding:20px;"><p>لا توجد كليات مسجلة بعد.</p></div>'}
                    </div>

                    <!-- Tab 3: Recent Requests Activity -->
                    <div id="modalTab-requests" class="modal-tab-content" style="display:none;">
                        ${u.recent_requests.length > 0 ? `
                        <table class="admin-table" style="font-size:12px;">
                            <thead>
                                <tr><th>#</th><th>اسم الطالب</th><th>التخصص المطلوب</th><th>الحالة</th><th>التاريخ</th></tr>
                            </thead>
                            <tbody>
                                ${u.recent_requests.map(r => `
                                <tr>
                                    <td style="color:var(--ink-muted);">${r.req_id}</td>
                                    <td style="font-weight:700;">${r.student}</td>
                                    <td style="color:var(--primary);font-weight:700;">${r.spec}</td>
                                    <td><span class="badge ${r.status=='مقبول'?'badge-green':(r.status=='مرفوض'?'badge-red':'badge-yellow')}">${r.status}</span></td>
                                    <td style="color:var(--ink-muted);">${r.date}</td>
                                </tr>`).join('')}
                            </tbody>
                        </table>` : '<div class="empty-state" style="padding:20px;"><p>لم يُسجل أي طلاب في هذه الجامعة حتى الآن.</p></div>'}
                    </div>
                `;
            }
        } catch (e) {
            body.innerHTML = `<p style="color:var(--danger);text-align:center;padding:30px;">حدث خطأ في تحميل البيانات.</p>`;
        }
    }

    // التنقل بين تبويبات المودال
    function switchModalTab(tabKey, btn) {
        document.querySelectorAll('.modal-tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.modal-tab-content').forEach(c => c.style.display = 'none');
        btn.classList.add('active');
        document.getElementById(`modalTab-${tabKey}`).style.display = 'block';
    }

    // فتح مودال التعديل من داخل استعراض الجامعة
    function openEditFromView() {
        if (!currentLoadedUniv) return;
        closeModal('viewUniversityModal');
        openEditUniversityModal(currentLoadedUniv.id);
    }

    // فتح مودال تعديل بيانات الجامعة
    async function openEditUniversityModal(univId) {
        activeUnivId = univId;
        openModal('editUniversityModal');
        document.getElementById('editUnivId').value = univId;

        try {
            const data = await adminFetch(`/admin/universities/${univId}`, 'GET');
            if (data.success) {
                const u = data.university;
                document.getElementById('editUnivName').value = u.name || '';
                document.getElementById('editUnivType').value = u.type || 'خاصة';
                document.getElementById('editUnivDistrict').value = u.district || '';
                document.getElementById('editUnivAddress').value = u.address || '';
                document.getElementById('editUnivDesc').value = u.description || '';
                document.getElementById('editUnivYear').value = u.year_establ || '';
                document.getElementById('editUnivPhone').value = u.phone || '';
                document.getElementById('editUnivContactPhone').value = u.contact_phone || '';
                document.getElementById('editUnivEmail').value = u.email || '';
                document.getElementById('editUnivWebsite').value = u.website || '';
                document.getElementById('editUnivLat').value = u.latitude || '';
                document.getElementById('editUnivLng').value = u.longitude || '';
            }
        } catch (e) {
            showToast('تعذر جلب بيانات الجامعة للتعديل.', 'error');
        }
    }

    // حفظ تعديلات الجامعة
    async function submitEditUniversity() {
        const form = document.getElementById('editUniversityForm');
        const btn = document.getElementById('editUnivBtn');
        const univId = document.getElementById('editUnivId').value;
        const formData = new FormData(form);

        btn.innerHTML = '<span class="spinner"></span> جارٍ الحفظ...';
        btn.disabled = true;

        try {
            const res = await fetch(`/admin/universities/${univId}/update`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData,
            });
            const data = await res.json();
            if (data.success) {
                closeModal('editUniversityModal');
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1200);
            } else {
                showToast(data.message || 'حدث خطأ أثناء التحديث.', 'error');
            }
        } catch (e) {
            showToast('حدث خطأ في الاتصال بالخادم.', 'error');
        } finally {
            btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> حفظ التعديلات';
            btn.disabled = false;
        }
    }

    // فتح مودال تغيير حالة الجامعة
    function openStatusModal(univId, currentStatus) {
        activeUnivId = univId;
        document.getElementById('currentUnivStatus').textContent = currentStatus;
        document.getElementById('newUnivStatus').value = currentStatus;
        openModal('statusUnivModal');
    }

    // تأكيد تغيير حالة الجامعة
    async function submitStatusChange() {
        const btn = document.getElementById('statusUnivBtn');
        const newStatus = document.getElementById('newUnivStatus').value;
        btn.innerHTML = '<span class="spinner"></span> جارٍ التحديث...';
        btn.disabled = true;

        try {
            const data = await adminFetch(`/admin/universities/${activeUnivId}/toggle-status`, 'POST', { status: newStatus });
            if (data.success) {
                closeModal('statusUnivModal');
                showToast(data.message, 'success');
                const row = document.getElementById(`univ-row-${activeUnivId}`);
                if (row) {
                    if (data.new_status === 'نشطة') {
                        row.className = 'row-active';
                    } else {
                        row.className = 'row-inactive';
                    }
                }
            } else {
                showToast(data.message, 'error');
            }
        } catch (e) {
            showToast('حدث خطأ في الاتصال.', 'error');
        } finally {
            btn.innerHTML = '<i class="fa-solid fa-check"></i> تأكيد التغيير';
            btn.disabled = false;
        }
    }

    // فتح مودال الحذف
    function confirmDeleteUniv(univId, univName) {
        activeUnivId = univId;
        document.getElementById('deleteUnivName').textContent = univName;
        openModal('deleteUnivModal');
    }

    // تأكيد حذف الجامعة
    async function submitDeleteUniv() {
        const btn = document.getElementById('deleteUnivBtn');
        btn.innerHTML = '<span class="spinner"></span> جارٍ الحذف...';
        btn.disabled = true;

        try {
            const data = await adminFetch(`/admin/universities/${activeUnivId}`, 'DELETE');
            if (data.success) {
                closeModal('deleteUnivModal');
                document.getElementById(`univ-row-${activeUnivId}`)?.remove();
                showToast(data.message, 'success');
            } else {
                showToast(data.message, 'error');
            }
        } catch (e) {
            showToast('حدث خطأ في الاتصال.', 'error');
        } finally {
            btn.innerHTML = '<i class="fa-solid fa-trash"></i> حذف نهائياً';
            btn.disabled = false;
        }
    }

    /* ============================================================
       وظائف الرقابة المستقلة على المحتوى والظهور (Content Moderation)
       ============================================================ */
    let cachedQuarantinedList = [];

    function openQuarantineModal(specId) {
        if (!currentLoadedUniv || !currentLoadedUniv.colleges) return;
        let targetSpec = null;
        let targetCollege = null;
        for (const c of currentLoadedUniv.colleges) {
            const found = c.specializations.find(s => s.id === specId);
            if (found) {
                targetSpec = found;
                targetCollege = c;
                break;
            }
        }
        if (!targetSpec) return;

        document.getElementById('qUnivId').value = currentLoadedUniv.id;
        document.getElementById('qItemType').value = 'specialization';
        document.getElementById('qItemId').value = targetSpec.id;
        document.getElementById('qItemTitle').value = targetSpec.name;
        document.getElementById('quarantineItemTitleDisplay').innerText = targetSpec.name + ' (' + targetCollege.name + ' - ' + currentLoadedUniv.name + ')';
        document.getElementById('qPresetReason').value = '';
        document.getElementById('qViolationReason').value = '';
        document.getElementById('qAdminGuidance').value = '';
        openModal('quarantineActionModal');
    }

    function handlePresetReasonChange(select) {
        if (select.value && select.value !== 'custom') {
            document.getElementById('qViolationReason').value = select.value;
        } else if (select.value === 'custom') {
            document.getElementById('qViolationReason').value = '';
            document.getElementById('qViolationReason').focus();
        }
    }

    async function submitQuarantineAction() {
        const univId    = document.getElementById('qUnivId').value;
        const itemType  = document.getElementById('qItemType').value;
        const itemId    = document.getElementById('qItemId').value;
        const itemTitle = document.getElementById('qItemTitle').value;
        const reason    = document.getElementById('qViolationReason').value.trim();
        const guidance  = document.getElementById('qAdminGuidance').value.trim();

        if (!reason) {
            showToast('يرجى تدوين أو اختيار سبب المخالفة للحجب.', 'error');
            return;
        }

        const btn = document.getElementById('quarantineSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner"></span> جارٍ الحجب...';

        try {
            const res = await adminFetch('/admin/moderations/quarantine', 'POST', {
                university_id: univId,
                item_type: itemType,
                item_id: itemId,
                item_title: itemTitle,
                violation_reason: reason,
                admin_guidance: guidance,
            });

            if (res.success) {
                showToast(res.message, 'success');
                closeModal('quarantineActionModal');
                if (activeUnivId) {
                    viewUniversity(activeUnivId);
                }
                updateQuarantinedBadgeCount();
            } else {
                showToast(res.message || 'حدث خطأ أثناء الحجب.', 'error');
            }
        } catch (e) {
            showToast('فشل تنفيذ إجراء الحجب.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-eye-slash"></i> تأكيد الحجب الفوري';
        }
    }

    async function resolveQuarantine(moderationId, specRef) {
        let title = 'هذا العنصر';
        if (typeof specRef === 'string') {
            title = specRef;
        } else if (typeof specRef === 'number' && currentLoadedUniv && currentLoadedUniv.colleges) {
            for (const c of currentLoadedUniv.colleges) {
                const found = c.specializations.find(s => s.id === specRef);
                if (found) { title = found.name; break; }
            }
        }

        if (!confirm('هل أنت متأكد من فك الحجب الرقابي عن "' + title + '" وإعادة تفعيل ظهوره للطلاب في المنصة؟')) {
            return;
        }

        try {
            const res = await adminFetch('/admin/moderations/' + moderationId + '/resolve', 'POST');
            if (res.success) {
                showToast(res.message, 'success');
                if (activeUnivId) {
                    viewUniversity(activeUnivId);
                }
                updateQuarantinedBadgeCount();
                if (document.getElementById('moderationCenterModal')?.classList.contains('open')) {
                    loadQuarantinedItems();
                }
            } else {
                showToast(res.message || 'فشل فك الحجب.', 'error');
            }
        } catch (e) {
            showToast('حدث خطأ أثناء فك الحجب.', 'error');
        }
    }

    function resolveQuarantineFromList(moderationId) {
        const item = cachedQuarantinedList.find(m => m.moderation_id === moderationId);
        const title = item ? item.item_title : 'هذا العنصر';
        resolveQuarantine(moderationId, title);
    }

    async function updateQuarantinedBadgeCount() {
        try {
            const res = await adminFetch('/admin/moderations/quarantined', 'GET');
            if (res.success) {
                const badge = document.getElementById('quarantinedTopBadge');
                if (badge) badge.innerText = res.count;
            }
        } catch (e) {}
    }

    function openModerationCenterModal() {
        openModal('moderationCenterModal');
        loadQuarantinedItems();
    }

    async function loadQuarantinedItems() {
        const list = document.getElementById('moderationCenterList');
        list.innerHTML = `<div style="text-align:center;padding:40px;color:var(--ink-muted);"><span class="spinner" style="border-top-color:var(--primary);width:28px;height:28px;border-width:3px;"></span><p style="margin-top:10px;font-size:12px;">جارٍ استرجاع سجل المحتوى المحجوب...</p></div>`;

        try {
            const res = await adminFetch('/admin/moderations/quarantined', 'GET');
            if (res.success) {
                cachedQuarantinedList = res.items || [];
                const badge = document.getElementById('quarantinedTopBadge');
                if (badge) badge.innerText = res.count;

                if (cachedQuarantinedList.length === 0) {
                    list.innerHTML = `
                        <div class="empty-state" style="padding:40px 20px;">
                            <i class="fa-solid fa-shield-check" style="font-size:36px;color:var(--admin-green);"></i>
                            <h4 style="margin-top:10px;">لا يوجد أي محتوى محجوب حالياً</h4>
                            <p style="font-size:12px;color:var(--ink-muted);">كافة كليات وتخصصات الجامعات في المنصة تعمل وتظهر للطلاب وفق المعايير المعتمدة دون مخالفات نشطة.</p>
                        </div>
                    `;
                    return;
                }

                list.innerHTML = `
                    <div class="admin-table-wrap">
                        <table class="admin-table" style="font-size:12px;">
                            <thead>
                                <tr>
                                    <th>الجامعة</th>
                                    <th>العنصر المحجوب</th>
                                    <th>سبب المخالفة</th>
                                    <th>تاريخ الحجب</th>
                                    <th style="text-align:center;">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${cachedQuarantinedList.map(m => `
                                    <tr>
                                        <td><div style="font-weight:700;color:var(--primary-dark);">${m.university_name}</div></td>
                                        <td>
                                            <div style="font-weight:700;">${m.item_title}</div>
                                            <span class="badge badge-gray" style="font-size:9.5px;">${m.item_type === 'specialization' ? 'تخصص أكاديمي' : m.item_type}</span>
                                        </td>
                                        <td>
                                            <div style="color:#b91c1c;font-weight:700;">${m.violation_reason}</div>
                                            ${m.admin_guidance ? `<div style="font-size:11px;color:var(--ink-muted);margin-top:2px;"><strong>توجيه:</strong> ${m.admin_guidance}</div>` : ''}
                                        </td>
                                        <td style="color:var(--ink-muted);font-size:11.5px;">${m.suspended_at}</td>
                                        <td style="text-align:center;">
                                            <button type="button" class="btn btn-sm" style="background:#10b981;color:#fff;font-size:11px;padding:4px 10px;border-radius:6px;font-weight:700;"
                                                    onclick="resolveQuarantineFromList(${m.moderation_id})">
                                                <i class="fa-solid fa-check"></i> فك الحجب والاعتماد
                                            </button>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            }
        } catch (e) {
            list.innerHTML = `<p style="color:var(--danger);text-align:center;padding:20px;">حدث خطأ في تحميل السجل.</p>`;
        }
    }

    // التنقل السلس بين حقول نافذة إضافة الجامعة باستخدام Enter و الأسهم و Tab
    function setupModalKeyboardNavigation() {
        const form = document.getElementById('addUniversityForm');
        if (!form) return;

        form.addEventListener('keydown', function(e) {
            const target = e.target;
            if (!target) return;

            const isTextarea = target.tagName === 'TEXTAREA';
            const isSelect = target.tagName === 'SELECT';
            const isInput = target.tagName === 'INPUT' && target.type !== 'file' && target.type !== 'submit';

            if (!isInput && !isSelect && !isTextarea) return;

            // قائمة الحقول القابلة للتركيز بالترتيب
            const focusable = Array.from(form.querySelectorAll('input:not([type="hidden"]):not([disabled]):not([type="file"]), select:not([disabled]), textarea:not([disabled])'));
            const idx = focusable.indexOf(target);

            if (e.key === 'Enter') {
                if (isTextarea) return; // السماح بإنشاء سطر داخل النبذة أو التخصصات
                e.preventDefault();
                if (idx > -1 && idx + 1 < focusable.length) {
                    focusable[idx + 1].focus();
                } else {
                    document.getElementById('addUnivBtn')?.focus();
                }
            } else if (e.key === 'ArrowDown') {
                if (isSelect || isTextarea) return; // نترك السهم لقوائم select ومربعات النص
                if (idx > -1 && idx + 1 < focusable.length) {
                    e.preventDefault();
                    focusable[idx + 1].focus();
                }
            } else if (e.key === 'ArrowUp') {
                if (isSelect || isTextarea) return;
                if (idx > 0) {
                    e.preventDefault();
                    focusable[idx - 1].focus();
                }
            }
        });
    }

    // فتح نافذة إضافة جامعة تلقائياً عند التوجيه أو انسحاب الصفحة لنتائج الفلترة
    document.addEventListener('DOMContentLoaded', function() {
        // تهيئة التنقل السلس عبر لوحة المفاتيح
        setupModalKeyboardNavigation();

        const urlParams = new URLSearchParams(window.location.search);

        // 1. فتح نافذة إضافة الجامعة فوراً إذا كان الرابط يحمل action=add
        if (urlParams.get('action') === 'add' || window.location.hash === '#add' || window.location.hash === '#addUniversityModal') {
            openModal('addUniversityModal');
            if (window.history && window.history.replaceState) {
                const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                window.history.replaceState({path: cleanUrl}, '', cleanUrl);
            }
        }

        // 2. انسحاب الشاشة بسلاسة إلى شريط الفلترة وبداية النتائج عند تطبيق أي تصفية أو بحث
        const hasSearch = urlParams.has('search') && urlParams.get('search').trim() !== '';
        const hasStatus = urlParams.has('status') && urlParams.get('status').trim() !== '';
        const hasType = urlParams.has('type') && urlParams.get('type').trim() !== '';

        if (hasSearch || hasStatus || hasType) {
            const filterSec = document.getElementById('filterSection');
            if (filterSec) {
                setTimeout(() => {
                    const rect = filterSec.getBoundingClientRect();
                    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    // وضع شريط الفلترة والأزرار في أعلى الشاشة الظاهرة مع ظهور جدول النتائج مباشرة تحته
                    const targetTop = scrollTop + rect.top - 16;
                    window.scrollTo({
                        top: Math.max(0, targetTop),
                        behavior: 'smooth'
                    });
                }, 280);
            }
        }
    });
</script>
@endsection
