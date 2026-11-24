@extends('dashboards.admin.layout')

@section('page_title', 'التقارير والإحصائيات')

@section('breadcrumb')
    <span>الرئيسية /</span>
    <strong>التقارير والإحصائيات</strong>
@endsection

@section('content')

<style>
    /* =========================================================
       EduYemen Clean Reports & Analytics Styles
       ========================================================= */

    /* Top Action Bar (Replacing heavy hero container) */
    .reports-action-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 1.5px solid #e2e8f0;
    }

    .reports-header-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .reports-header-title h1 {
        font-size: 20px;
        font-weight: 800;
        color: #0f2942;
        margin: 0;
    }

    .hero-badge-live {
        font-size: 11px;
        font-weight: 700;
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
        padding: 3px 9px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .hero-badge-live::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #10b981;
    }

    .reports-header-buttons {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-report-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .btn-report-print {
        background: #0f2942;
        color: #ffffff;
        border-color: #0f2942;
    }

    .btn-report-print:hover {
        background: #1e3a8a;
        color: #ffffff;
    }

    .btn-report-refresh {
        background: #f1f5f9;
        color: #334155;
        border-color: #cbd5e1;
    }

    .btn-report-refresh:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* Section Navigator Bar (ما التقرير الذي تريد استعراضه؟) */
    .reports-navigator-bar {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 22px;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }

    .nav-bar-label {
        font-size: 13.5px;
        font-weight: 800;
        color: #0b3d6b;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-bottom: 12px;
        text-align: center;
        width: 100%;
    }

    .nav-bar-pills {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        flex-wrap: wrap;
    }

    .nav-pill-btn {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px 14px;
        font-size: 12.5px;
        font-weight: 700;
        color: #334155;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        flex: 1;
        min-height: 40px;
        box-sizing: border-box;
        white-space: nowrap;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
    }

    .nav-pill-btn:hover {
        background: #0b3d6b;
        color: #ffffff;
        border-color: #0b3d6b;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(11, 61, 107, 0.15);
    }

    .nav-pill-btn:hover i {
        color: #ffffff !important;
    }

    /* Minimalist Statistical Counter Strip (3 بطاقات في صف واحد دائماً سواء بالشاشات الكبيرة أو الصغيرة) */
    .reports-summary-strip {
        display: grid;
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 14px;
        margin-bottom: 24px;
    }

    .summary-strip-item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }

    .strip-item-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .strip-item-info {
        flex: 1;
        min-width: 0;
    }

    .strip-item-label {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 2px;
    }

    .strip-item-value {
        font-size: 20px;
        font-weight: 900;
        color: #0f2942;
        line-height: 1.2;
    }

    .strip-item-sub {
        font-size: 11px;
        color: #94a3b8;
        font-weight: 600;
        margin-top: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Section Cards */
    .report-section-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .report-section-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        background: #fafbfd;
    }

    .report-section-title-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .report-section-title-wrap h3 {
        font-size: 15px;
        font-weight: 800;
        color: #0f2942;
        margin: 0;
    }

    .report-section-link {
        font-size: 12.5px;
        font-weight: 700;
        color: #0b3d6b;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 6px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        transition: all 0.15s ease;
    }

    .report-section-link:hover {
        background: #0b3d6b;
        color: #ffffff;
        border-color: #0b3d6b;
    }

    .report-section-body {
        padding: 18px 20px;
    }

    /* Admissions Funnel Rows (حالتان في كل صف سواء بالشاشات الكبيرة أو الصغيرة) */
    .funnel-list {
        display: grid !important;
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 12px !important;
    }

    /* عندما تكون البطاقة الثالثة منفردة (أو أي بطاقة فردية أخيرة)، تتمركز في منتصف الحاوية تماماً */
    .funnel-list .funnel-row.funnel-row-centered,
    .funnel-list .funnel-row:nth-child(3):last-child,
    .funnel-list .funnel-row:nth-child(odd):last-child {
        grid-column: 1 / -1 !important;
        width: 100% !important;
        max-width: calc(50% - 6px) !important;
        margin-left: auto !important;
        margin-right: auto !important;
    }

    .funnel-row {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 16px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 10px;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .funnel-row:hover {
        border-color: #cbd5e1;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .funnel-row-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        width: 100%;
        flex-wrap: wrap;
    }

    .funnel-status-badge {
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .funnel-row-stats {
        display: flex;
        align-items: baseline;
        gap: 5px;
    }

    .funnel-row-count {
        font-size: 15px;
        font-weight: 900;
        line-height: 1;
    }

    .funnel-row-pct {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
    }

    .funnel-desc {
        font-size: 11.5px;
        color: #64748b;
        line-height: 1.4;
        margin: 0;
    }

    /* Tables (Clean, responsive, no overflow) */
    .report-table-wrapper {
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        text-align: right;
    }

    .report-table th {
        background: #f8fafc;
        padding: 11px 14px;
        font-weight: 800;
        color: #475569;
        font-size: 12px;
        border-bottom: 1.5px solid #e2e8f0;
        white-space: nowrap;
    }

    .report-table td {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: #1e293b;
        word-break: break-word;
    }

    .report-table tbody tr:hover {
        background-color: #fafbfd;
    }

    .rank-number {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11.5px;
        font-weight: 800;
        background: #f1f5f9;
        color: #334155;
    }

    .rank-1 { background: #fef3c7; color: #b45309; }
    .rank-2 { background: #f1f5f9; color: #475569; }
    .rank-3 { background: #ffedd5; color: #c2410c; }

    /* Fee Payments Receipts Grid (حركة سداد رسوم التسجيل) */
    .receipts-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
    }

    .receipt-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .receipt-box-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .receipt-box-val {
        font-size: 18px;
        font-weight: 900;
        color: #0f2942;
        line-height: 1.2;
    }

    .receipt-box-lbl {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        margin-top: 2px;
    }

    /* Timeline Activity Chart */
    .activity-chart-container {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 8px;
        height: 160px;
        padding: 10px 0 0 0;
        border-bottom: 2px solid #e2e8f0;
    }

    .chart-bar-col {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        height: 100%;
        justify-content: flex-end;
    }

    .bar-pill-value {
        font-size: 11px;
        font-weight: 800;
        color: #0b3d6b;
        margin-bottom: 4px;
    }

    .chart-bar-stem {
        width: 100%;
        max-width: 28px;
        background: #0b3d6b;
        border-radius: 4px 4px 0 0;
        transition: height 0.3s ease;
    }

    .chart-bar-stem:hover {
        background: #1e3a8a;
    }

    .chart-bar-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        margin-top: 6px;
        white-space: nowrap;
    }

    /* Responsive Queries */
    @media (max-width: 992px) {
        .reports-summary-strip {
            grid-template-columns: repeat(3, 1fr) !important;
        }
        .receipts-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .reports-action-header {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }

        .reports-header-buttons {
            width: 100%;
        }

        .btn-report-btn {
            flex: 1;
            justify-content: center;
        }

        /* شريط التنقل على الشاشات الصغيرة: العنوان في بداية الحاوية وبجانبه الزر الأول، ثم زرين في كل صف تالي مع تناسق الأحجام */
        .reports-navigator-bar {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            padding: 12px 14px;
            align-items: stretch;
        }

        .nav-bar-label {
            grid-column: 1 / 2;
            grid-row: 1 / 2;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            text-align: right;
            margin-bottom: 0;
            font-size: 11.5px;
            font-weight: 800;
            color: #0b3d6b;
            padding: 4px 6px;
            line-height: 1.35;
        }

        .nav-bar-pills {
            display: contents; /* يفرغ الحاوية لتشارك الأزرار مباشرة في الشبكة المكونة من عمودين */
        }

        .nav-pill-btn {
            width: 100% !important;
            max-width: none !important;
            padding: 8px 6px !important;
            font-size: 11px !important;
            min-height: 40px !important;
            text-align: center;
            justify-content: center;
            border-radius: 8px !important;
            box-sizing: border-box;
            white-space: normal;
        }

        /* الصف الأول: الزر الأول بجانب العنوان */
        .nav-pill-first {
            grid-column: 2 / 3;
            grid-row: 1 / 2;
        }

        /* الصف الثاني: زران */
        .nav-bar-pills .nav-pill-btn:nth-child(2) {
            grid-column: 1 / 2;
            grid-row: 2 / 3;
        }
        .nav-bar-pills .nav-pill-btn:nth-child(3) {
            grid-column: 2 / 3;
            grid-row: 2 / 3;
        }

        /* الصف الثالث: زران */
        .nav-bar-pills .nav-pill-btn:nth-child(4) {
            grid-column: 1 / 2;
            grid-row: 3 / 4;
        }
        .nav-bar-pills .nav-pill-btn:nth-child(5) {
            grid-column: 2 / 3;
            grid-row: 3 / 4;
        }

        /* مسار وحالات طلبات التسجيل بالشاشات الصغيرة: حالتان في كل صف، والبطاقة الثالثة الفردية تتوسط الحاوية */
        .funnel-list {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 8px !important;
        }

        .funnel-list .funnel-row.funnel-row-centered,
        .funnel-list .funnel-row:nth-child(3):last-child,
        .funnel-list .funnel-row:nth-child(odd):last-child {
            grid-column: 1 / -1 !important;
            width: 100% !important;
            max-width: calc(50% - 4px) !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }

        .funnel-row {
            padding: 10px 10px;
            gap: 8px;
        }

        .funnel-row-top {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }

        .funnel-status-badge {
            padding: 3px 7px;
            font-size: 11px;
        }

        .funnel-row-count {
            font-size: 13.5px;
        }

        .funnel-row-pct {
            font-size: 10.5px;
        }

        .funnel-desc {
            font-size: 10px;
            line-height: 1.35;
        }

        /* الحاوية في صف واحد على الشاشات الصغيرة أيضاً */
        .reports-summary-strip {
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 6px !important;
            margin-bottom: 18px !important;
        }

        .summary-strip-item {
            padding: 8px 6px !important;
            flex-direction: column !important;
            text-align: center !important;
            justify-content: center !important;
            align-items: center !important;
            gap: 5px !important;
        }

        .strip-item-icon {
            width: 32px !important;
            height: 32px !important;
            font-size: 13px !important;
            border-radius: 8px !important;
        }

        .strip-item-info {
            width: 100% !important;
        }

        .strip-item-label {
            font-size: 10.5px !important;
            font-weight: 700 !important;
            margin-bottom: 2px !important;
            white-space: nowrap !important;
        }

        .strip-item-value {
            font-size: 15px !important;
            font-weight: 900 !important;
            line-height: 1.1 !important;
        }

        .strip-item-sub {
            font-size: 9px !important;
            margin-top: 2px !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            display: block !important;
        }
            font-size: 17px;
        }

        .receipts-grid {
            grid-template-columns: repeat(2, 1fr) !important; /* بطاقتين في كل صف بالشاشات الصغيرة */
            gap: 10px;
        }

        .receipt-box {
            padding: 12px 10px;
            gap: 10px;
        }

        .receipt-box-icon {
            width: 36px;
            height: 36px;
            font-size: 15px;
        }

        .receipt-box-val {
            font-size: 15px;
        }

        .receipt-box-lbl {
            font-size: 11px;
        }

        .report-table-wrapper {
            overflow-x: hidden;
        }

        .report-table {
            font-size: 11.5px;
        }

        .report-table th, .report-table td {
            padding: 8px 6px;
        }

        .col-mobile-hide {
            display: none !important;
        }
    }

    /* =========================================================
       طباعة التقرير كتقرير حقيقي ورسمي (Real Print Document)
       ========================================================= */
    @media print {
        @page {
            size: A4 portrait;
            margin: 15mm 12mm 15mm 12mm;
        }

        body {
            background: #ffffff !important;
            color: #000000 !important;
            font-family: 'Cairo', sans-serif !important;
            font-size: 10pt !important;
        }

        /* إخفاء عناصر التحكم والتنقل */
        .admin-sidebar, 
        .admin-topbar, 
        .reports-action-header, 
        .reports-navigator-bar, 
        .report-section-link,
        .btn-report-btn {
            display: none !important;
        }

        .admin-main, .admin-content, .content-wrapper {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            box-shadow: none !important;
        }

        /* ترويسة التقرير الرسمية للطباعة */
        .print-official-header {
            display: block !important;
            border-bottom: 2px solid #0f2942;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .print-official-header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .print-official-header h2 {
            margin: 0 0 4px 0;
            font-size: 15pt;
            font-weight: 800;
            color: #0f2942;
        }

        .print-official-header p {
            margin: 0;
            font-size: 9pt;
            color: #475569;
        }

        .report-section-card {
            border: 1px solid #cbd5e1 !important;
            box-shadow: none !important;
            margin-bottom: 16px !important;
            page-break-inside: avoid;
        }

        .report-section-header {
            background: #f1f5f9 !important;
            border-bottom: 1px solid #cbd5e1 !important;
            padding: 10px 14px !important;
        }

        .report-section-header h3 {
            color: #0f2942 !important;
            font-size: 11pt !important;
        }

        .reports-summary-strip {
            gap: 10px !important;
            margin-bottom: 16px !important;
            page-break-inside: avoid;
        }

        .summary-strip-item {
            border: 1px solid #cbd5e1 !important;
            box-shadow: none !important;
            padding: 10px 12px !important;
        }

        .receipts-grid {
            gap: 10px !important;
            page-break-inside: avoid;
        }

        .receipt-box {
            border: 1px solid #cbd5e1 !important;
            box-shadow: none !important;
        }

        .report-table th {
            background: #f1f5f9 !important;
            color: #000000 !important;
            border-bottom: 1px solid #000000 !important;
            padding: 6px 8px !important;
        }

        .report-table td {
            border-bottom: 1px solid #e2e8f0 !important;
            padding: 6px 8px !important;
        }

        .print-footer-info {
            display: block !important;
            text-align: center;
            font-size: 8pt;
            color: #64748b;
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px solid #cbd5e1;
        }
    }
</style>

<!-- ترويسة الطباعة الرسمية (تظهر فقط عند الطباعة الورقية/PDF) -->
<div class="print-official-header" style="display: none;">
    <div class="print-official-header-flex">
        <div>
            <h2>الجمهورية اليمنية — منصة EduYemen المركزية</h2>
            <p>تقرير المؤشرات التحليلية وإحصائيات التسجيل والقبول الأكاديمي</p>
        </div>
        <div style="text-align: left; font-size: 9pt; color: #475569;">
            <div><strong>تاريخ الاستخراج:</strong> {{ date('Y-m-d H:i') }}</div>
            <div><strong>الجهة:</strong> إدارة النظام المركزية</div>
        </div>
    </div>
</div>

<!-- ================= 1. شريط التحكم العلوي ================= -->
<div class="reports-action-header">
    <div class="reports-header-title">
        <h1>التقارير والإحصائيات</h1>
        <span class="hero-badge-live">
            بيانات مباشرة ومحدثة
        </span>
    </div>
    <div class="reports-header-buttons">
        <button type="button" class="btn-report-btn btn-report-print" onclick="window.print()">
            <i class="fa-solid fa-print"></i>
            <span>طباعة التقرير</span>
        </button>
        <button type="button" class="btn-report-btn btn-report-refresh" onclick="location.reload()">
            <i class="fa-solid fa-arrows-rotate"></i>
            <span>تحديث البيانات</span>
        </button>
    </div>
</div>

<!-- ================= 2. شريط الانتقال السريع بين التقارير ================= -->
<div class="reports-navigator-bar">
    <div class="nav-bar-label">
        <i class="fa-solid fa-compass" style="color: #c59b27; font-size: 15px;"></i>
        <span>ما التقرير الذي تريد استعراضه؟</span>
    </div>
    <div class="nav-bar-pills">
        <a href="#section-pipeline" class="nav-pill-btn nav-pill-first">
            <i class="fa-solid fa-route" style="color: #0284c7;"></i>
            <span>مسار طلبات التسجيل</span>
        </a>
        <a href="#section-universities" class="nav-pill-btn">
            <i class="fa-solid fa-building-columns" style="color: #0b3d6b;"></i>
            <span>الجامعات الأكثر نشاطاً</span>
        </a>
        <a href="#section-specializations" class="nav-pill-btn">
            <i class="fa-solid fa-graduation-cap" style="color: #7c3aed;"></i>
            <span>التخصصات الأكثر طلباً</span>
        </a>
        <a href="#section-payments" class="nav-pill-btn">
            <i class="fa-solid fa-file-invoice-dollar" style="color: #10b981;"></i>
            <span>حركة سداد رسوم التسجيل</span>
        </a>
        <a href="#section-timeline" class="nav-pill-btn">
            <i class="fa-solid fa-chart-line" style="color: #d97706;"></i>
            <span>التدفق الزمني للتسجيل</span>
        </a>
    </div>
</div>

<!-- ================= 3. شريط المؤشرات الرقمية المباشرة (3 بطاقات في صف واحد دائماً) ================= -->
<div class="reports-summary-strip">
    <div class="summary-strip-item">
        <div class="strip-item-icon" style="background: #eff6ff; color: #0b3d6b;">
            <i class="fa-solid fa-file-signature"></i>
        </div>
        <div class="strip-item-info">
            <div class="strip-item-label">طلبات التسجيل</div>
            <div class="strip-item-value">{{ number_format($stats['total_requests']) }}</div>
            <div class="strip-item-sub">{{ $stats['accepted_requests'] }} طلب مقبول ({{ $stats['acceptance_rate'] }}%)</div>
        </div>
    </div>

    <div class="summary-strip-item">
        <div class="strip-item-icon" style="background: #fef9c3; color: #b45309;">
            <i class="fa-solid fa-building-columns"></i>
        </div>
        <div class="strip-item-info">
            <div class="strip-item-label">الجامعات المسجلة</div>
            <div class="strip-item-value">{{ number_format($stats['total_universities']) }}</div>
            <div class="strip-item-sub">{{ $stats['public_universities'] }} حكومية <span style="color:#cbd5e1; margin: 0 4px;">|</span> {{ $stats['private_universities'] }} أهلية</div>
        </div>
    </div>

    <div class="summary-strip-item">
        <div class="strip-item-icon" style="background: #f5f3ff; color: #6d28d9;">
            <i class="fa-solid fa-user-graduate"></i>
        </div>
        <div class="strip-item-info">
            <div class="strip-item-label">الطلاب المسجلون</div>
            <div class="strip-item-value">{{ number_format($stats['total_students']) }}</div>
            <div class="strip-item-sub">إجمالي حسابات الطلاب بالمنصة</div>
        </div>
    </div>
</div>

<!-- ================= 4. مسار وحالات طلبات التسجيل ================= -->
<div class="report-section-card" id="section-pipeline">
    <div class="report-section-header">
        <div class="report-section-title-wrap">
            <i class="fa-solid fa-route" style="color: #0b3d6b; font-size: 16px;"></i>
            <h3>مسار وحالات طلبات التسجيل</h3>
        </div>
        <a href="{{ route('admin.requests') }}" class="report-section-link">
            <span>متابعة طلبات الالتحاق</span>
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    </div>
    <div class="report-section-body">
        <div class="funnel-list">
            @php
                $activeStatuses = $requestsByStatus->where('status', '!=', 'ملغي')->values();
                $totalReqs = $activeStatuses->sum('total') ?: 1;
                $totalActiveStatuses = $activeStatuses->count();
                $statusMap = [
                    'قيد المراجعة' => [
                        'color' => '#0284c7', 'bg' => '#e0f2fe', 'border' => '#0284c7',
                        'desc' => 'بانتظار تدقيق لجان القبول الجامعية',
                        'icon' => 'fa-clock-rotate-left'
                    ],
                    'مقبول' => [
                        'color' => '#16a34a', 'bg' => '#dcfce7', 'border' => '#16a34a',
                        'desc' => 'تم استيفاء الشروط واعتماد القبول',
                        'icon' => 'fa-circle-check'
                    ],
                    'يتطلب استكمال' => [
                        'color' => '#d97706', 'bg' => '#fef3c7', 'border' => '#d97706',
                        'desc' => 'بانتظار استكمال وثائق أو سداد الرسوم',
                        'icon' => 'fa-circle-exclamation'
                    ],
                    'مرفوض' => [
                        'color' => '#dc2626', 'bg' => '#fee2e2', 'border' => '#dc2626',
                        'desc' => 'عدم مطابقة المعدل أو استيفاء المقاعد',
                        'icon' => 'fa-circle-xmark'
                    ],
                ];
            @endphp

            @forelse($activeStatuses as $index => $item)
                @php
                    $pct = round(($item->total / $totalReqs) * 100, 1);
                    $cfg = $statusMap[$item->status] ?? [
                        'color' => '#475569', 'bg' => '#f1f5f9', 'border' => '#cbd5e1',
                        'desc' => 'حالة مسجلة بالمنظومة',
                        'icon' => 'fa-circle'
                    ];
                    // إذا كانت البطاقة الثالثة فردية، تتمركز في منتصف الحاوية تماماً
                    $isCentered = ($index === 2 && $totalActiveStatuses === 3) || ($index === ($totalActiveStatuses - 1) && ($totalActiveStatuses % 2 !== 0));
                @endphp
                <div class="funnel-row {{ $isCentered ? 'funnel-row-centered' : '' }}" style="border-right: 4px solid {{ $cfg['border'] }};">
                    <div class="funnel-row-top">
                        <span class="funnel-status-badge" style="background: {{ $cfg['bg'] }}; color: {{ $cfg['color'] }};">
                            <i class="fa-solid {{ $cfg['icon'] }}"></i>
                            {{ $item->status }}
                        </span>
                        <div class="funnel-row-stats" style="color: {{ $cfg['color'] }};">
                            <span class="funnel-row-count">{{ number_format($item->total) }} طلب</span>
                            <span class="funnel-row-pct">({{ $pct }}%)</span>
                        </div>
                    </div>
                    <p class="funnel-desc">{{ $cfg['desc'] }}</p>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align:center; color:#64748b; padding: 20px;">
                    <i class="fa-solid fa-inbox" style="font-size: 28px; color: #cbd5e1; margin-bottom: 8px; display: block;"></i>
                    لا توجد طلبات تسجيل مسجلة حتى الآن.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- ================= 5. تصنيف الجامعات الأكثر استقطاباً ================= -->
<div class="report-section-card" id="section-universities">
    <div class="report-section-header">
        <div class="report-section-title-wrap">
            <i class="fa-solid fa-trophy" style="color: #0b3d6b; font-size: 16px;"></i>
            <h3>تصنيف الجامعات الأكثر استقطاباً للمتقدمين</h3>
        </div>
        <a href="{{ route('admin.universities') }}" class="report-section-link">
            <span>إدارة الجامعات</span>
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    </div>
    <div class="report-section-body" style="padding: 0;">
        <div class="report-table-wrapper">
            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 45px; text-align: center;">#</th>
                        <th>اسم الجامعة</th>
                        <th>النوع</th>
                        <th style="text-align: center;">إجمالي الطلبات</th>
                        <th style="text-align: center;" class="col-mobile-hide">المقبولين</th>
                        <th style="text-align: center; width: 110px;">حصة الاستقطاب</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topUniversities as $i => $uni)
                        @php
                            $sharePct = round(($uni->total_reqs / ($stats['total_requests'] ?: 1)) * 100, 1);
                        @endphp
                        <tr>
                            <td style="text-align: center;">
                                <span class="rank-number {{ $i == 0 ? 'rank-1' : ($i == 1 ? 'rank-2' : ($i == 2 ? 'rank-3' : '')) }}">
                                    {{ $i + 1 }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $uni->university_name }}</strong>
                                <div style="font-size: 11px; color: #64748b;">{{ $uni->district ?: 'اليمن' }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $uni->university_type == 'حكومية' ? 'badge-blue' : 'badge-gold' }}" style="font-size: 11px; padding: 3px 8px;">
                                    {{ $uni->university_type ?: 'أهلية' }}
                                </span>
                            </td>
                            <td style="text-align: center; font-weight: 800; color: #0b3d6b; font-size: 14px;">
                                {{ number_format($uni->total_reqs) }}
                            </td>
                            <td style="text-align: center; font-weight: 700; color: #16a34a;" class="col-mobile-hide">
                                {{ number_format($uni->accepted_reqs) }}
                            </td>
                            <td style="text-align: center; font-weight: 800; color: #0f2942; font-size: 13px;">
                                {{ $sharePct }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #64748b; padding: 24px;">لا توجد بيانات متاحة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= 6. البرامج الأكاديمية والتخصصات الأكثر طلباً ================= -->
<div class="report-section-card" id="section-specializations">
    <div class="report-section-header">
        <div class="report-section-title-wrap">
            <i class="fa-solid fa-graduation-cap" style="color: #0b3d6b; font-size: 16px;"></i>
            <h3>البرامج الأكاديمية والتخصصات الأكثر طلباً</h3>
        </div>
        <a href="{{ route('admin.requests') }}" class="report-section-link">
            <span>تصفية حسب التخصص</span>
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    </div>
    <div class="report-section-body" style="padding: 0;">
        <div class="report-table-wrapper">
            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 45px; text-align: center;">#</th>
                        <th>التخصص الأكاديمي</th>
                        <th style="text-align: center;">عدد المتقدمين</th>
                        <th style="text-align: center; width: 110px;">مؤشر الإقبال</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topSpecs as $i => $spec)
                        @php
                            $specPct = round(($spec->total / ($stats['total_requests'] ?: 1)) * 100, 1);
                        @endphp
                        <tr>
                            <td style="text-align: center; color: #64748b; font-weight: 700;">
                                {{ $i + 1 }}
                            </td>
                            <td>
                                <strong>{{ $spec->desired_spec }}</strong>
                            </td>
                            <td style="text-align: center; font-weight: 800; color: #6d28d9; font-size: 14px;">
                                {{ number_format($spec->total) }}
                            </td>
                            <td style="text-align: center; font-weight: 800; color: #0f2942; font-size: 13px;">
                                {{ $specPct }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #64748b; padding: 24px;">لا توجد تخصصات مسجلة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= 7. حركة سداد رسوم التسجيل (مع عرض بطاقتين بالصف على الجوال) ================= -->
<div class="report-section-card" id="section-payments">
    <div class="report-section-header">
        <div class="report-section-title-wrap">
            <i class="fa-solid fa-file-invoice-dollar" style="color: #10b981; font-size: 16px;"></i>
            <h3>حركة سداد رسوم التسجيل</h3>
        </div>
        <a href="{{ route('admin.requests', ['status' => 'قيد المراجعة']) }}" class="report-section-link">
            <span>مراجعة سندات الطلبات</span>
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    </div>
    <div class="report-section-body">
        <div class="receipts-grid">
            <!-- المؤكدة -->
            <div class="receipt-box">
                <div class="receipt-box-icon" style="background: #ecfdf5; color: #16a34a;">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <div class="receipt-box-val">{{ number_format($stats['confirmed_payments_count']) }}</div>
                    <div class="receipt-box-lbl">سندات مؤكدة ومطابقة</div>
                </div>
            </div>

            <!-- قيد المراجعة والتدقيق -->
            <div class="receipt-box">
                <div class="receipt-box-icon" style="background: #fffbeb; color: #d97706;">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>
                <div>
                    <div class="receipt-box-val">{{ number_format(max(0, $stats['payments_count'] - $stats['confirmed_payments_count'])) }}</div>
                    <div class="receipt-box-lbl">سندات قيد المراجعة</div>
                </div>
            </div>

            <!-- سندات التسجيل المدققة -->
            <div class="receipt-box">
                <div class="receipt-box-icon" style="background: #eff6ff; color: #0284c7;">
                    <i class="fa-solid fa-stamp"></i>
                </div>
                <div>
                    <div class="receipt-box-val">{{ number_format($stats['verified_proofs']) }}</div>
                    <div class="receipt-box-lbl">سندات تسجيل معتمدة</div>
                </div>
            </div>

            <!-- إجمالي السندات المرفوعة -->
            <div class="receipt-box">
                <div class="receipt-box-icon" style="background: #f5f3ff; color: #7c3aed;">
                    <i class="fa-solid fa-folder-closed"></i>
                </div>
                <div>
                    <div class="receipt-box-val">{{ number_format($stats['payments_count']) }}</div>
                    <div class="receipt-box-lbl">إجمالي السندات المرفوعة</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= 8. التدفق الزمني لطلبات التسجيل ================= -->
<div class="report-section-card" id="section-timeline">
    <div class="report-section-header">
        <div class="report-section-title-wrap">
            <i class="fa-solid fa-chart-line" style="color: #0b3d6b; font-size: 16px;"></i>
            <h3>التدفق الزمني لطلبات التسجيل</h3>
        </div>
        <span style="font-size: 12px; color: #64748b; font-weight: 600;">
            إجمالي الأيام النشطة: {{ $dailyActivity->count() }} يوم
        </span>
    </div>
    <div class="report-section-body">
        @php
            $maxDaily = $dailyActivity->max('total') ?: 1;
        @endphp

        @if($dailyActivity->isNotEmpty())
            <div class="activity-chart-container">
                @foreach($dailyActivity as $day)
                    @php
                        $heightPct = round(($day->total / $maxDaily) * 115);
                        $heightPct = max($heightPct, 16);
                        $carbonDate = \Carbon\Carbon::parse($day->day_date);
                    @endphp
                    <div class="chart-bar-col">
                        <span class="bar-pill-value">{{ $day->total }}</span>
                        <div class="chart-bar-stem" style="height: {{ $heightPct }}px;" title="{{ $day->total }} طلب في {{ $carbonDate->format('Y-m-d') }}"></div>
                        <span class="chart-bar-label">{{ $carbonDate->format('m/d') }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p style="text-align: center; color: #64748b; margin: 20px 0;">لا توجد حركة تسجيل مسجلة حديثاً</p>
        @endif
    </div>
</div>

<!-- تذييل الطباعة الرسمي (يظهر فقط في الطباعة) -->
<div class="print-footer-info" style="display: none;">
    تم إنشاء هذا التقرير تلقائياً عبر منصة EduYemen للجامعات اليمنية — جميع البيانات موثقة ومطابقة لقواعد البيانات المركزية.
</div>

@endsection

@section('scripts')
<script>
    // تهيئة اسم الملف المطبوع عند فتح نافذة الطباعة
    window.addEventListener('beforeprint', () => {
        document.title = 'تقرير_منظومة_EduYemen_الإحصائي_' + new Date().toISOString().slice(0, 10);
    });

    // تمرير سلس عند النقر على روابط شريط الانتقال
    document.querySelectorAll('.nav-pill-btn').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetEl = document.querySelector(targetId);
            if (targetEl) {
                targetEl.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
</script>
@endsection
