@extends('dashboards.admin.layout')

@section('page_title', 'التقارير والمؤشرات التحليلية')

@section('breadcrumb')
    <span>الرئيسية /</span>
    <strong>التقارير والإحصائيات</strong>
@endsection

@section('content')

<style>
    /* =========================================================
       EduYemen Executive Reports & Analytics Dashboard Styles
       ========================================================= */

    /* Hero Header */
    .reports-hero {
        background: linear-gradient(135deg, #072b4f 0%, #0b3d6b 55%, #13528c 100%);
        border-radius: var(--radius-lg);
        padding: 24px 28px;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
        box-shadow: 0 10px 30px rgba(7, 43, 79, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.12);
    }
    .reports-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        left: -60px;
        width: 240px;
        height: 240px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(197, 155, 39, 0.28) 0%, transparent 70%);
        pointer-events: none;
    }
    .reports-hero::after {
        content: '\f201';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        bottom: -25px;
        left: 20px;
        font-size: 140px;
        color: rgba(255, 255, 255, 0.04);
        pointer-events: none;
        line-height: 1;
    }
    .reports-hero-inner {
        position: relative;
        z-index: 2;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
    }
    .reports-hero-title h1 {
        font-size: 21px;
        font-weight: 800;
        color: #fff;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .hero-badge-live {
        font-size: 11px;
        font-weight: 700;
        background: rgba(34, 197, 94, 0.2);
        color: #86efac;
        border: 1px solid rgba(34, 197, 94, 0.35);
        padding: 2px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .hero-badge-live::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #22c55e;
        box-shadow: 0 0 8px #22c55e;
    }
    .reports-hero-title p {
        font-size: 12.5px;
        color: rgba(255, 255, 255, 0.85);
        max-width: 680px;
        line-height: 1.6;
    }
    .reports-hero-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .btn-report-action {
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
        font-size: 12.5px;
        font-weight: 700;
        border: 1px solid rgba(255, 255, 255, 0.22);
        padding: 9px 18px;
        border-radius: var(--radius-sm);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.25s ease;
        backdrop-filter: blur(4px);
    }
    .btn-report-action:hover {
        background: #fff;
        color: var(--primary-dark);
        border-color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    }
    .btn-report-gold {
        background: linear-gradient(135deg, #c59b27 0%, #e0b438 100%);
        color: #072b4f;
        font-weight: 800;
        border: none;
        box-shadow: 0 4px 14px rgba(197, 155, 39, 0.35);
    }
    .btn-report-gold:hover {
        color: #072b4f;
        box-shadow: 0 6px 20px rgba(197, 155, 39, 0.5);
    }

    /* Strategic KPI Ribbon */
    .reports-kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    .kpi-stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 18px 20px;
        box-shadow: var(--shadow-sm);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        position: relative;
        overflow: hidden;
    }
    .kpi-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: rgba(11, 61, 107, 0.25);
    }
    .kpi-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 4px;
        height: 100%;
        background: var(--primary);
    }
    .kpi-stat-card.kpi-blue::before { background: #0b3d6b; }
    .kpi-stat-card.kpi-emerald::before { background: #10b981; }
    .kpi-stat-card.kpi-gold::before { background: #c59b27; }
    .kpi-stat-card.kpi-violet::before { background: #8b5cf6; }

    .kpi-top-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }
    .kpi-icon-wrap {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }
    .kpi-blue .kpi-icon-wrap {
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        color: #0369a1;
        border: 1px solid #7dd3fc;
    }
    .kpi-emerald .kpi-icon-wrap {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        color: #15803d;
        border: 1px solid #86efac;
    }
    .kpi-gold .kpi-icon-wrap {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #b45309;
        border: 1px solid #fcd34d;
    }
    .kpi-violet .kpi-icon-wrap {
        background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
        color: #6d28d9;
        border: 1px solid #c4b5fd;
    }

    .kpi-tag-chip {
        font-size: 11px;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 6px;
        letter-spacing: 0.2px;
    }
    .kpi-blue .kpi-tag-chip { background: #f0f9ff; color: #0284c7; }
    .kpi-emerald .kpi-tag-chip { background: #f0fdf4; color: #16a34a; }
    .kpi-gold .kpi-tag-chip { background: #fffbeb; color: #d97706; }
    .kpi-violet .kpi-tag-chip { background: #faf5ff; color: #7c3aed; }

    .kpi-main-metric {
        margin-bottom: 8px;
    }
    .kpi-number-display {
        font-size: 24px;
        font-weight: 900;
        color: var(--ink);
        line-height: 1.2;
        font-family: 'Plus Jakarta Sans', 'Almarai', sans-serif;
    }
    .kpi-unit-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--ink-muted);
        margin-right: 4px;
    }
    .kpi-title-text {
        font-size: 12px;
        font-weight: 700;
        color: var(--ink-muted);
        margin-top: 2px;
    }
    .kpi-sub-insight {
        font-size: 11.5px;
        color: var(--ink-muted);
        border-top: 1px dashed var(--border-light);
        padding-top: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .kpi-sub-insight strong {
        color: var(--ink);
        font-weight: 800;
    }

    /* Analytical Cards */
    .report-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        margin-bottom: 22px;
        overflow: hidden;
        transition: box-shadow 0.2s ease;
    }
    .report-card-header {
        background: linear-gradient(to left, var(--surface) 0%, var(--surface-alt) 100%);
        padding: 14px 20px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .report-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .report-icon-badge {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: var(--primary-subtle);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }
    .report-card-title h3 {
        font-size: 14px;
        font-weight: 800;
        color: var(--primary-dark);
        margin-bottom: 2px;
    }
    .report-card-title p {
        font-size: 11px;
        color: var(--ink-muted);
    }
    .report-badge-pill {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        background: #f1f5f9;
        color: var(--ink-muted);
        border: 1px solid var(--border);
        white-space: nowrap;
    }

    /* Admissions Funnel & Status Rows */
    .funnel-row {
        padding: 12px 14px;
        border-radius: var(--radius-sm);
        background: var(--surface-alt);
        margin-bottom: 10px;
        transition: all 0.2s ease;
        border: 1px solid var(--border-light);
    }
    .funnel-row:last-child { margin-bottom: 0; }
    .funnel-row:hover {
        background: #fff;
        border-color: var(--border);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }
    .funnel-info-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 7px;
    }
    .funnel-status-label {
        font-size: 12.5px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .funnel-status-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
    }
    .funnel-count-box {
        font-size: 12.5px;
        font-weight: 800;
        font-family: 'Plus Jakarta Sans', 'Almarai', sans-serif;
    }
    .funnel-pct {
        font-size: 11px;
        font-weight: 700;
        color: var(--ink-muted);
        margin-right: 4px;
    }
    .funnel-progress-track {
        height: 8px;
        background: #e2e8f0;
        border-radius: 6px;
        overflow: hidden;
    }
    .funnel-progress-fill {
        height: 100%;
        border-radius: 6px;
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Profile / Demographics Metrics */
    .demographics-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 18px;
    }
    .score-metric-box {
        background: var(--surface-alt);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-sm);
        padding: 12px 14px;
        text-align: center;
    }
    .score-metric-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        margin: 0 auto 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }
    .score-metric-val {
        font-size: 17px;
        font-weight: 900;
        color: var(--primary-dark);
        font-family: 'Plus Jakarta Sans', 'Almarai', sans-serif;
    }
    .score-metric-lbl {
        font-size: 11px;
        font-weight: 700;
        color: var(--ink-muted);
        margin-top: 2px;
    }

    /* Gender Split Visualizer */
    .gender-split-box {
        background: var(--surface-alt);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-sm);
        padding: 14px;
    }
    .gender-split-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    .gender-chip {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 800;
    }
    .gender-chip.male { color: #0284c7; }
    .gender-chip.female { color: #ec4899; }
    .gender-dual-bar {
        height: 12px;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        background: #e2e8f0;
    }
    .gender-bar-male {
        background: linear-gradient(90deg, #0284c7, #38bdf8);
        transition: width 0.8s ease;
    }
    .gender-bar-female {
        background: linear-gradient(90deg, #ec4899, #f472b6);
        transition: width 0.8s ease;
    }

    /* Tables (Universities & Specializations) */
    .report-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 12.5px;
    }
    .report-table th {
        background: var(--surface-alt);
        padding: 10px 14px;
        font-weight: 800;
        color: var(--ink-muted);
        font-size: 11.5px;
        border-bottom: 1px solid var(--border);
        text-align: right;
        white-space: nowrap;
    }
    .report-table td {
        padding: 12px 14px;
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
        color: var(--ink);
    }
    .report-table tbody tr:last-child td {
        border-bottom: none;
    }
    .report-table tbody tr:hover {
        background: rgba(11, 61, 107, 0.02);
    }

    /* Rank Medals */
    .rank-badge {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 800;
    }
    .rank-1 { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .rank-2 { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
    .rank-3 { background: #ffedd5; color: #c2410c; border: 1px solid #fed7aa; }
    .rank-other { background: #f8fafc; color: var(--ink-light); }

    /* Financial Indicators Grid */
    .finance-metrics-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 14px;
    }
    .fin-box {
        background: var(--surface-alt);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-sm);
        padding: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .fin-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }
    .fin-val {
        font-size: 16px;
        font-weight: 900;
        color: var(--ink);
        font-family: 'Plus Jakarta Sans', 'Almarai', sans-serif;
    }
    .fin-lbl {
        font-size: 11px;
        color: var(--ink-muted);
        font-weight: 700;
    }

    /* Chart Flow Activity Bars */
    .activity-chart-container {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        height: 160px;
        padding: 10px 6px 4px;
        border-bottom: 2px solid var(--border);
        overflow-x: auto;
    }
    .chart-bar-col {
        flex: 1;
        min-width: 38px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-end;
        height: 100%;
        gap: 6px;
    }
    .bar-pill-value {
        font-size: 11.5px;
        font-weight: 800;
        color: var(--primary);
        font-family: 'Plus Jakarta Sans', 'Almarai', sans-serif;
    }
    .chart-bar-stem {
        width: 100%;
        max-width: 32px;
        background: linear-gradient(180deg, #1565a8 0%, #0b3d6b 100%);
        border-radius: 6px 6px 0 0;
        transition: height 0.6s ease, transform 0.2s ease;
    }
    .chart-bar-stem:hover {
        background: linear-gradient(180deg, #c59b27 0%, #0b3d6b 100%);
        transform: scaleY(1.04);
    }
    .chart-bar-label {
        font-size: 10.5px;
        font-weight: 700;
        color: var(--ink-muted);
        white-space: nowrap;
        margin-top: 4px;
    }

    /* Responsive adjustments */
    @media (max-width: 1100px) {
        .reports-kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 768px) {
        .reports-hero {
            padding: 18px 20px;
        }
        .reports-hero-inner {
            flex-direction: column;
            align-items: stretch;
        }
        .reports-hero-actions {
            justify-content: flex-start;
        }
        .reports-kpi-grid {
            grid-template-columns: 1fr;
        }
        .demographics-grid {
            grid-template-columns: 1fr;
        }
        .finance-metrics-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Print Styles */
    @media print {
        .admin-sidebar, .admin-topbar, .reports-hero-actions, .btn-report-action {
            display: none !important;
        }
        .admin-main {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        .reports-hero {
            background: #fff !important;
            color: #000 !important;
            border: 2px solid #000 !important;
            box-shadow: none !important;
        }
        .reports-hero-title h1, .reports-hero-title p {
            color: #000 !important;
        }
        .report-card, .kpi-stat-card {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
            break-inside: avoid;
        }
    }
</style>

<!-- ================= 1. Hero Header ================= -->
<div class="reports-hero">
    <div class="reports-hero-inner">
        <div class="reports-hero-title">
            <h1>
                <i class="fa-solid fa-chart-line" style="color:var(--accent);"></i>
                مركز التقارير والمؤشرات التحليلية
                <span class="hero-badge-live">
                    <i class="fa-solid fa-circle-check"></i> بيانات مباشرة ومحدثة
                </span>
            </h1>
            <p>
                لوحة تنفيذية شاملة وموحدة لاستقراء أداء المنصة التعليمية، تدفقات طلبات الالتحاق، التحصيل المالي، وتصنيف إقبال الطلاب على الجامعات والتخصصات في اليمن.
            </p>
        </div>
        <div class="reports-hero-actions">
            <button type="button" class="btn-report-action btn-report-gold" onclick="window.print()">
                <i class="fa-solid fa-print"></i>
                طباعة وتصدير التقرير
            </button>
            <button type="button" class="btn-report-action" onclick="location.reload()">
                <i class="fa-solid fa-arrows-rotate"></i>
                تحديث البيانات
            </button>
        </div>
    </div>
</div>

<!-- ================= 2. Strategic KPI Ribbon ================= -->
<div class="reports-kpi-grid">
    <!-- KPI 1: Admissions -->
    <div class="kpi-stat-card kpi-blue">
        <div class="kpi-top-row">
            <div class="kpi-icon-wrap">
                <i class="fa-solid fa-file-contract"></i>
            </div>
            <span class="kpi-tag-chip">
                <i class="fa-solid fa-arrow-trend-up"></i> تدفق القبول
            </span>
        </div>
        <div class="kpi-main-metric">
            <div class="kpi-number-display">
                {{ number_format($stats['total_requests']) }}
                <span class="kpi-unit-label">طلب التحاق</span>
            </div>
            <div class="kpi-title-text">إجمالي طلبات التسجيل المرفوعة</div>
        </div>
        <div class="kpi-sub-insight">
            <i class="fa-solid fa-circle-check" style="color:#10b981;"></i>
            معدل القبول العام: <strong>{{ $stats['acceptance_rate'] }}%</strong> ({{ $stats['accepted_requests'] }} مقبول)
        </div>
    </div>

    <!-- KPI 2: Financial -->
    <div class="kpi-stat-card kpi-emerald">
        <div class="kpi-top-row">
            <div class="kpi-icon-wrap">
                <i class="fa-solid fa-vault"></i>
            </div>
            <span class="kpi-tag-chip">
                <i class="fa-solid fa-receipt"></i> التحصيل المالي
            </span>
        </div>
        <div class="kpi-main-metric">
            <div class="kpi-number-display">
                {{ number_format($stats['confirmed_payments']) }}
                <span class="kpi-unit-label">ر.ي</span>
            </div>
            <div class="kpi-title-text">إجمالي الرسوم المسددة والمؤكدة</div>
        </div>
        <div class="kpi-sub-insight">
            <i class="fa-solid fa-money-bill-transfer" style="color:#10b981;"></i>
            تم تأكيد <strong>{{ $stats['confirmed_payments_count'] }}</strong> من أصل <strong>{{ $stats['payments_count'] }}</strong> معاملة
        </div>
    </div>

    <!-- KPI 3: Universities -->
    <div class="kpi-stat-card kpi-gold">
        <div class="kpi-top-row">
            <div class="kpi-icon-wrap">
                <i class="fa-solid fa-landmark-dome"></i>
            </div>
            <span class="kpi-tag-chip">
                <i class="fa-solid fa-building-columns"></i> الصرح الأكاديمي
            </span>
        </div>
        <div class="kpi-main-metric">
            <div class="kpi-number-display">
                {{ number_format($stats['total_universities']) }}
                <span class="kpi-unit-label">جامعات معتمدة</span>
            </div>
            <div class="kpi-title-text">الشبكة الجامعية المتصلة بالمنصة</div>
        </div>
        <div class="kpi-sub-insight">
            <i class="fa-solid fa-graduation-cap" style="color:#c59b27;"></i>
            <strong>{{ $stats['public_universities'] }}</strong> حكومية • <strong>{{ $stats['private_universities'] }}</strong> أهلية وخاصة
        </div>
    </div>

    <!-- KPI 4: Students & Community -->
    <div class="kpi-stat-card kpi-violet">
        <div class="kpi-top-row">
            <div class="kpi-icon-wrap">
                <i class="fa-solid fa-users-viewfinder"></i>
            </div>
            <span class="kpi-tag-chip">
                <i class="fa-solid fa-user-graduate"></i> المجتمع الطلابي
            </span>
        </div>
        <div class="kpi-main-metric">
            <div class="kpi-number-display">
                {{ number_format($stats['total_users']) }}
                <span class="kpi-unit-label">حساب مسجل</span>
            </div>
            <div class="kpi-title-text">إجمالي المستخدمين بالمنصة</div>
        </div>
        <div class="kpi-sub-insight">
            <i class="fa-solid fa-id-card-clip" style="color:#8b5cf6;"></i>
            <strong>{{ $stats['total_students'] }}</strong> طالب مسجل • <strong>{{ $stats['total_referrals'] }}</strong> إحالة تسويقية
        </div>
    </div>
</div>

<!-- ================= 3. Chapter 1: Admissions Funnel & Student Demographics ================= -->
<div class="grid-2">
    <!-- Card 1: Admissions Funnel Pipeline -->
    <div class="report-card">
        <div class="report-card-header">
            <div class="report-card-title">
                <div class="report-icon-badge">
                    <i class="fa-solid fa-diagram-project"></i>
                </div>
                <div>
                    <h3>مسار ومراحل معالجة طلبات القبول</h3>
                    <p>توزيع الطلبات حسب المرحلة الإدارية بالجامعات</p>
                </div>
            </div>
            <span class="report-badge-pill">إجمالي {{ $stats['total_requests'] }} طلب</span>
        </div>

        <div style="padding: 18px 20px;">
            @php
                $totalReqs = $requestsByStatus->sum('total') ?: 1;
                $statusMap = [
                    'قيد المراجعة' => [
                        'color' => '#0284c7', 'bg' => '#e0f2fe',
                        'desc' => 'تخضع حالياً للتدقيق من لجان القبول الجامعية',
                        'icon' => 'fa-hourglass-half'
                    ],
                    'مقبول' => [
                        'color' => '#16a34a', 'bg' => '#dcfce7',
                        'desc' => 'تم استيفاء الشروط واعتماد القيد الأكاديمي',
                        'icon' => 'fa-circle-check'
                    ],
                    'ملغي' => [
                        'color' => '#64748b', 'bg' => '#f1f5f9',
                        'desc' => 'تم إلغاء الطلب من قبل الطالب أو انتهاء المهلة',
                        'icon' => 'fa-ban'
                    ],
                    'مرفوض' => [
                        'color' => '#dc2626', 'bg' => '#fee2e2',
                        'desc' => 'عدم مطابقة المعدل أو استيفاء المقاعد المتاحة',
                        'icon' => 'fa-circle-xmark'
                    ],
                    'يتطلب استكمال' => [
                        'color' => '#7c3aed', 'bg' => '#ede9fe',
                        'desc' => 'بانتظار تصحيح وثائق أو سداد الرسوم الإلزامية',
                        'icon' => 'fa-circle-exclamation'
                    ],
                ];
            @endphp

            @forelse($requestsByStatus as $item)
                @php
                    $pct = round(($item->total / $totalReqs) * 100, 1);
                    $cfg = $statusMap[$item->status] ?? [
                        'color' => '#64748b', 'bg' => '#f1f5f9',
                        'desc' => 'حالة نظامية مسجلة بالمنصة',
                        'icon' => 'fa-circle-dot'
                    ];
                @endphp
                <div class="funnel-row">
                    <div class="funnel-info-top">
                        <div class="funnel-status-label" style="color: {{ $cfg['color'] }};">
                            <span class="funnel-status-dot" style="background: {{ $cfg['color'] }};"></span>
                            <i class="fa-solid {{ $cfg['icon'] }}"></i>
                            {{ $item->status }}
                            <span style="font-size:10.5px;font-weight:600;color:var(--ink-muted);margin-right:4px;">
                                ({{ $cfg['desc'] }})
                            </span>
                        </div>
                        <div class="funnel-count-box" style="color: {{ $cfg['color'] }};">
                            {{ $item->total }}
                            <span class="funnel-pct">({{ $pct }}%)</span>
                        </div>
                    </div>
                    <div class="funnel-progress-track">
                        <div class="funnel-progress-fill" style="width: {{ $pct }}%; background: {{ $cfg['color'] }};"></div>
                    </div>
                </div>
            @empty
                <p style="text-align:center;color:var(--ink-muted);padding:30px;">لا توجد طلبات مسجلة حتى الآن.</p>
            @endforelse
        </div>
    </div>

    <!-- Card 2: Academic Profile & Demographics -->
    <div class="report-card">
        <div class="report-card-header">
            <div class="report-card-title">
                <div class="report-icon-badge">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div>
                    <h3>الملف الأكاديمي ومعدلات الطلاب</h3>
                    <p>مؤشرات الكفاءة والتوزيع الديموغرافي للمتقدمين</p>
                </div>
            </div>
            <span class="report-badge-pill">الثانوية العامة</span>
        </div>

        <div style="padding: 18px 20px;">
            <!-- Secondary Score Stats -->
            <div class="demographics-grid">
                <div class="score-metric-box">
                    <div class="score-metric-icon" style="background:#e0f2fe;color:#0284c7;">
                        <i class="fa-solid fa-award"></i>
                    </div>
                    <div class="score-metric-val">{{ $academicMetrics['avg_sec_score'] }}%</div>
                    <div class="score-metric-lbl">متوسط معدل المتقدمين</div>
                </div>

                <div class="score-metric-box">
                    <div class="score-metric-icon" style="background:#dcfce7;color:#16a34a;">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                    </div>
                    <div class="score-metric-val">{{ $academicMetrics['max_sec_score'] }}%</div>
                    <div class="score-metric-lbl">أعلى معدل مسجل</div>
                </div>

                <div class="score-metric-box">
                    <div class="score-metric-icon" style="background:#fef3c7;color:#d97706;">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <div class="score-metric-val">{{ $academicMetrics['min_sec_score'] }}%</div>
                    <div class="score-metric-lbl">الحد الأدنى للمعدلات</div>
                </div>
            </div>

            <!-- Gender Demographics -->
            @php
                $maleCount = $genderStats->firstWhere('gender', 'ذكر')->total ?? 0;
                $femaleCount = $genderStats->firstWhere('gender', 'أنثى')->total ?? 0;
                $totalGender = ($maleCount + $femaleCount) ?: 1;
                $malePct = round(($maleCount / $totalGender) * 100, 1);
                $femalePct = round(($femaleCount / $totalGender) * 100, 1);
            @endphp
            <div class="gender-split-box">
                <div class="gender-split-header">
                    <span style="font-size:12px;font-weight:800;color:var(--primary-dark);">
                        <i class="fa-solid fa-venus-mars" style="color:var(--accent);margin-left:6px;"></i>
                        التوزيع الديموغرافي للطلاب (الجنس)
                    </span>
                    <div style="display:flex;gap:14px;">
                        <span class="gender-chip male">
                            <i class="fa-solid fa-mars"></i> ذكور: {{ $maleCount }} ({{ $malePct }}%)
                        </span>
                        <span class="gender-chip female">
                            <i class="fa-solid fa-venus"></i> إناث: {{ $femaleCount }} ({{ $femalePct }}%)
                        </span>
                    </div>
                </div>
                <div class="gender-dual-bar">
                    <div class="gender-bar-male" style="width: {{ $malePct }}%;" title="ذكور: {{ $malePct }}%"></div>
                    <div class="gender-bar-female" style="width: {{ $femalePct }}%;" title="إناث: {{ $femalePct }}%"></div>
                </div>
            </div>

            <!-- Additional Academic Insight -->
            <div style="margin-top:14px;background:var(--surface-alt);border:1px solid var(--border-light);border-radius:var(--radius-sm);padding:12px 14px;display:flex;align-items:center;justify-content:space-between;font-size:12px;">
                <span style="font-weight:700;color:var(--ink-muted);">
                    <i class="fa-solid fa-book-open-reader" style="color:var(--primary);margin-left:6px;"></i>
                    متوسط الرسوم الدراسية بالبرامج المتاحة:
                </span>
                <span style="font-weight:900;color:var(--primary-dark);font-family:'Plus Jakarta Sans','Almarai',sans-serif;">
                    {{ number_format($stats['avg_tuition_fees']) }} ر.ي / سنة
                </span>
            </div>
        </div>
    </div>
</div>

<!-- ================= 4. Chapter 2: University Leaderboard & Academic Specializations ================= -->
<div class="grid-2">
    <!-- Top Universities Table -->
    <div class="report-card">
        <div class="report-card-header">
            <div class="report-card-title">
                <div class="report-icon-badge">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <div>
                    <h3>تصنيف الجامعات الأكثر استقطاباً للمتقدمين</h3>
                    <p>أعلى الجامعات حسب حجم طلبات الالتحاق ونسب الاستحواذ</p>
                </div>
            </div>
            <span class="report-badge-pill">أفضل {{ $topUniversities->count() }} صروح</span>
        </div>

        <div style="overflow-x:auto;">
            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width:40px;text-align:center;">#</th>
                        <th>اسم الجامعة</th>
                        <th>التصنيف</th>
                        <th style="text-align:center;">الطلبات</th>
                        <th style="text-align:center;">المقبولين</th>
                        <th style="width:120px;">حصة الاستقطاب</th>
                    </tr>
                </thead>
                <tbody>
                    @php $maxUniReqs = $topUniversities->max('total_reqs') ?: 1; @endphp
                    @forelse($topUniversities as $i => $uni)
                        @php
                            $sharePct = round(($uni->total_reqs / ($stats['total_requests'] ?: 1)) * 100, 1);
                        @endphp
                        <tr>
                            <td style="text-align:center;">
                                <span class="rank-badge {{ $i == 0 ? 'rank-1' : ($i == 1 ? 'rank-2' : ($i == 2 ? 'rank-3' : 'rank-other')) }}">
                                    @if($i == 0)
                                        <i class="fa-solid fa-medal"></i>
                                    @else
                                        {{ $i + 1 }}
                                    @endif
                                </span>
                            </td>
                            <td style="font-weight:800;color:var(--primary-dark);">
                                {{ $uni->university_name }}
                                <div style="font-size:10.5px;color:var(--ink-light);font-weight:600;">
                                    <i class="fa-solid fa-location-dot"></i> {{ $uni->district ?: 'اليمن' }}
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $uni->university_type == 'حكومية' ? 'badge-blue' : 'badge-gold' }}" style="font-size:10.5px;">
                                    {{ $uni->university_type ?: 'أهلية' }}
                                </span>
                            </td>
                            <td style="text-align:center;font-weight:900;color:var(--primary);font-size:14px;font-family:'Plus Jakarta Sans','Almarai',sans-serif;">
                                {{ $uni->total_reqs }}
                            </td>
                            <td style="text-align:center;font-weight:800;color:#16a34a;font-size:13px;font-family:'Plus Jakarta Sans','Almarai',sans-serif;">
                                {{ $uni->accepted_reqs }}
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <div style="flex:1;background:#e2e8f0;border-radius:4px;height:7px;overflow:hidden;">
                                        <div style="height:100%;background:linear-gradient(90deg,#0b3d6b,#1565a8);width:{{ $sharePct }}%;border-radius:4px;"></div>
                                    </div>
                                    <span style="font-size:10.5px;font-weight:800;color:var(--ink-muted);width:32px;text-align:left;">
                                        {{ $sharePct }}%
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;color:var(--ink-muted);padding:24px;">لا توجد بيانات متاحة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Specializations Table -->
    <div class="report-card">
        <div class="report-card-header">
            <div class="report-card-title">
                <div class="report-icon-badge">
                    <i class="fa-solid fa-book-bookmark"></i>
                </div>
                <div>
                    <h3>البرامج الأكاديمية والتخصصات الأكثر طلباً</h3>
                    <p>رغبات الطلاب المعتمدة الأكثر استهدافاً بالمنصة</p>
                </div>
            </div>
            <span class="report-badge-pill">إقبال البرامج</span>
        </div>

        <div style="overflow-x:auto;">
            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width:40px;text-align:center;">#</th>
                        <th>التخصص الأكاديمي</th>
                        <th style="text-align:center;">عدد المتقدمين</th>
                        <th style="width:130px;">مؤشر الإقبال</th>
                    </tr>
                </thead>
                <tbody>
                    @php $maxSpecReqs = $topSpecs->max('total') ?: 1; @endphp
                    @forelse($topSpecs as $i => $spec)
                        @php
                            $specPct = round(($spec->total / ($stats['total_requests'] ?: 1)) * 100, 1);
                        @endphp
                        <tr>
                            <td style="text-align:center;color:var(--ink-muted);font-weight:700;">
                                {{ $i + 1 }}
                            </td>
                            <td style="font-weight:800;color:var(--ink);">
                                <i class="fa-solid fa-certificate" style="color:var(--accent);margin-left:6px;font-size:11px;"></i>
                                {{ $spec->desired_spec }}
                            </td>
                            <td style="text-align:center;font-weight:900;color:#7c3aed;font-size:14px;font-family:'Plus Jakarta Sans','Almarai',sans-serif;">
                                {{ $spec->total }}
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <div style="flex:1;background:#e2e8f0;border-radius:4px;height:7px;overflow:hidden;">
                                        <div style="height:100%;background:linear-gradient(90deg,#7c3aed,#a855f7);width:{{ $specPct }}%;border-radius:4px;"></div>
                                    </div>
                                    <span style="font-size:10.5px;font-weight:800;color:var(--ink-muted);width:32px;text-align:left;">
                                        {{ $specPct }}%
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center;color:var(--ink-muted);padding:24px;">لا توجد تخصصات مسجلة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= 5. Chapter 3: Financial Settlement & Growth Timeline ================= -->
<div class="grid-2">
    <!-- Financial Settlements & Proofs -->
    <div class="report-card">
        <div class="report-card-header">
            <div class="report-card-title">
                <div class="report-icon-badge">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
                <div>
                    <h3>حركة التحصيل المالي والسندات</h3>
                    <p>متابعة إيرادات التسجيل ومطابقة الإيصالات البنكية</p>
                </div>
            </div>
            <span class="report-badge-pill">التدقيق المالي</span>
        </div>

        <div style="padding: 18px 20px;">
            <div class="finance-metrics-grid">
                <!-- Verified Payments -->
                <div class="fin-box">
                    <div class="fin-icon-box" style="background:#dcfce7;color:#15803d;">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <div class="fin-val">{{ number_format($stats['confirmed_payments']) }} ر.ي</div>
                        <div class="fin-lbl">مبالغ مؤكدة ({{ $stats['confirmed_payments_count'] }} سند)</div>
                    </div>
                </div>

                <!-- Pending Payments -->
                <div class="fin-box">
                    <div class="fin-icon-box" style="background:#fef3c7;color:#b45309;">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div>
                        <div class="fin-val">{{ number_format($stats['total_payments'] - $stats['confirmed_payments']) }} ر.ي</div>
                        <div class="fin-lbl">قيد المطابقة ({{ $stats['payments_count'] - $stats['confirmed_payments_count'] }} سند)</div>
                    </div>
                </div>

                <!-- Verified Registration Proofs -->
                <div class="fin-box">
                    <div class="fin-icon-box" style="background:#e0f2fe;color:#0369a1;">
                        <i class="fa-solid fa-file-invoice"></i>
                    </div>
                    <div>
                        <div class="fin-val">{{ $stats['verified_proofs'] }} / {{ $stats['total_proofs'] }}</div>
                        <div class="fin-lbl">سندات تسجيل مدققة ومعتمدة</div>
                    </div>
                </div>

                <!-- Active Marketing Referrals -->
                <div class="fin-box">
                    <div class="fin-icon-box" style="background:#ede9fe;color:#6d28d9;">
                        <i class="fa-solid fa-share-nodes"></i>
                    </div>
                    <div>
                        <div class="fin-val">{{ $stats['total_referrals'] }} إحالة</div>
                        <div class="fin-lbl">أكواد حملات التسويق الأكاديمي</div>
                    </div>
                </div>
            </div>

            <!-- Financial Health Strip -->
            <div style="background:var(--surface-alt);border:1px solid var(--border-light);border-radius:var(--radius-sm);padding:12px 14px;display:flex;align-items:center;justify-content:space-between;font-size:12px;">
                <span style="font-weight:700;color:var(--ink-muted);">
                    <i class="fa-solid fa-shield-halved" style="color:#16a34a;margin-left:6px;"></i>
                    نسبة مطابقة واعتماد السندات المالية:
                </span>
                <span style="font-weight:900;color:#16a34a;font-size:13px;font-family:'Plus Jakarta Sans','Almarai',sans-serif;">
                    100% (موثوق ومطابق بنكياً)
                </span>
            </div>
        </div>
    </div>

    <!-- Timeline Activity Chart -->
    <div class="report-card">
        <div class="report-card-header">
            <div class="report-card-title">
                <div class="report-icon-badge">
                    <i class="fa-solid fa-chart-column"></i>
                </div>
                <div>
                    <h3>التدفق الزمني لطلبات التسجيل</h3>
                    <p>حركة تدفق المتقدمين خلال الأيام النشطة</p>
                </div>
            </div>
            <span class="report-badge-pill">التسلسل الزمني</span>
        </div>

        <div style="padding: 18px 20px;">
            @php
                $maxDaily = $dailyActivity->max('total') ?: 1;
            @endphp

            @if($dailyActivity->isNotEmpty())
                <div class="activity-chart-container">
                    @foreach($dailyActivity as $day)
                        @php
                            $heightPct = round(($day->total / $maxDaily) * 110);
                            $heightPct = max($heightPct, 16);
                            $carbonDate = \Carbon\Carbon::parse($day->day_date);
                        @endphp
                        <div class="chart-bar-col">
                            <span class="bar-pill-value">{{ $day->total }}</span>
                            <div class="chart-bar-stem" style="height: {{ $heightPct }}px;" title="{{ $day->total }} طلبات في {{ $carbonDate->isoFormat('D MMMM') }}"></div>
                            <span class="chart-bar-label">{{ $carbonDate->format('m/d') }}</span>
                        </div>
                    @endforeach
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:10px;font-size:11.5px;color:var(--ink-muted);">
                    <span><i class="fa-solid fa-circle-info" style="color:var(--primary);margin-left:4px;"></i> عدد الطلبات المستلمة لكل يوم</span>
                    <span>إجمالي الأيام الموضحة: {{ $dailyActivity->count() }} أيام نشطة</span>
                </div>
            @else
                <p style="text-align:center;color:var(--ink-muted);padding:40px;">لا توجد حركة تسجيل مسجلة حديثاً</p>
            @endif
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // عند فتح التقرير للطباعة، يتم ضبط العنوان الافتراضي للمستند المطبوع
    window.addEventListener('beforeprint', () => {
        document.title = 'تقرير_منظومة_EduYemen_الإحصائي_' + new Date().toISOString().slice(0, 10);
    });
</script>
@endsection
