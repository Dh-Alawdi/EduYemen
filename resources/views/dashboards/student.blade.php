@extends('layouts.app')

@section('title', 'الملف الشخصي ولوحة التحكم — EduYemen')

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=Almarai:wght@300;400;700;800&display=swap" rel="stylesheet">

<style>
    /* ========================================================
       تطبيق الخط الحديث فائق الوضوح (IBM Plex Sans Arabic) لصفحات الطالب
       ======================================================== */
    body,
    .student-dashboard-wrap,
    .student-dashboard-wrap input,
    .student-dashboard-wrap button,
    .student-dashboard-wrap select,
    .student-dashboard-wrap textarea,
    .modal,
    .modal input,
    .modal button,
    .modal select,
    .modal textarea {
        font-family: 'IBM Plex Sans Arabic', 'Almarai', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        letter-spacing: normal;
    }

    /* حماية خط الأيقونات والرموز البرمجية من التداخل */
    .bi, [class*="bi-"], [class^="bi-"] {
        font-family: "bootstrap-icons" !important;
    }
    .fa, .fa-solid, .fa-regular, .fa-brands, [class*="fa-"] {
        font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands" !important;
    }
    .font-monospace, code, pre, .font-mono {
        font-family: var(--bs-font-monospace, monospace) !important;
    }

    /* ========================================================
       تصميم لوحة تحكم وملف الطالب الشخصي — الهوية الرسمية EduYemen
       ======================================================== */
    .student-dashboard-wrap {
        max-width: 1060px;
        margin: 0 auto;
    }

    /* كرت هوية الطالب الرئيسية (Header Profile Card) */
    .student-id-card {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 70%, var(--primary-light) 100%);
        border-radius: 18px;
        padding: 1.6rem;
        color: #ffffff;
        box-shadow: 0 6px 24px rgba(11, 61, 107, 0.16);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .student-id-card::before {
        content: '';
        position: absolute;
        top: -60px;
        left: -60px;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(197, 155, 39, 0.25) 0%, transparent 70%);
        pointer-events: none;
    }

    .student-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ffffff 0%, #e2e8f0 100%);
        color: var(--primary);
        font-size: 1.85rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
        border: 3px solid rgba(255, 255, 255, 0.85);
        flex-shrink: 0;
    }

    /* شارة الإشعارات غير المقروءة */
    .notif-unread-badge {
        background-color: #dc2626 !important;
        color: #ffffff !important;
        font-size: 0.7rem !important;
        padding: 0.2rem 0.45rem !important;
        border-radius: 50rem !important;
        font-weight: 800;
        box-shadow: 0 2px 6px rgba(220, 38, 38, 0.35);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* ========================================================
       1. القائمة العمودية للشاشات الكبيرة (Desktop Vertical Nav)
       ======================================================== */
    .student-vnav-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 12px rgba(11, 61, 107, 0.05);
        padding: 0.6rem;
    }

    .student-vnav-item {
        width: 100%;
        text-align: right;
        border: none;
        background: transparent;
        color: var(--text-dark);
        font-weight: 700;
        font-size: 0.9rem;
        padding: 11px 14px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.2s ease;
        cursor: pointer;
        margin-bottom: 3px;
        text-decoration: none;
    }

    .student-vnav-item i {
        font-size: 1.15rem;
        transition: transform 0.2s ease;
    }

    .student-vnav-item:hover {
        background: rgba(11, 61, 107, 0.06);
        color: var(--primary);
    }

    .student-vnav-item:hover i {
        transform: scale(1.1);
    }

    .student-vnav-item.active {
        background: var(--primary);
        color: #ffffff !important;
        box-shadow: 0 3px 10px rgba(11, 61, 107, 0.22);
    }

    .student-vnav-item.active i {
        color: #ffffff !important;
    }

    /* حاويات المحتوى المقابلة للقائمة العمودية في الشاشات الكبيرة */
    .student-content-pane {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 12px rgba(11, 61, 107, 0.05);
        padding: 1.6rem;
    }

    /* ========================================================
       2. القوائم المنسدلة في شاشة الهاتف (Mobile Accordion System)
       ======================================================== */
    .mobile-accordion-wrapper {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .mobile-accordion-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 8px rgba(11, 61, 107, 0.04);
        overflow: hidden;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .mobile-accordion-card.open {
        border-color: var(--primary);
        box-shadow: 0 4px 16px rgba(11, 61, 107, 0.08);
    }

    .mobile-accordion-header {
        width: 100%;
        background: #ffffff;
        border: none;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        font-weight: 700;
        font-size: 0.92rem;
        color: var(--text-dark);
        transition: all 0.2s ease;
        text-align: right;
    }

    .mobile-accordion-card.open .mobile-accordion-header {
        background: #f8fafc;
        color: var(--primary);
        border-bottom: 1px solid var(--border-color);
    }

    .mobile-chevron {
        font-size: 0.95rem;
        transition: transform 0.25s ease;
        color: var(--text-muted);
    }

    .mobile-accordion-card.open .mobile-chevron {
        transform: rotate(180deg);
        color: var(--primary);
    }

    .mobile-accordion-body {
        padding: 1.1rem;
        background: #ffffff;
    }

    /* بطاقة الإحالة الطلابية لجامعة محددة */
    .univ-referral-card {
        background: linear-gradient(135deg, #0b3d6b 0%, #072b4f 100%);
        border: 1.5px solid var(--accent);
        border-radius: 14px;
        padding: 1.25rem;
        color: #ffffff;
        box-shadow: 0 4px 18px rgba(197, 155, 39, 0.18);
        position: relative;
    }

    .referral-code-badge {
        font-size: 1.4rem;
        letter-spacing: 1.5px;
        font-weight: 900;
        color: var(--accent-light);
        font-family: monospace;
        background: rgba(0, 0, 0, 0.28);
        padding: 5px 14px;
        border-radius: 8px;
        display: inline-block;
        border: 1px dashed rgba(197, 155, 39, 0.6);
    }

    /* كروت خيارات الثيمات والمظهر */
    .theme-select-card {
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 1.1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        background: #ffffff;
    }

    .theme-select-card:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
    }

    .theme-select-card.active {
        border-color: var(--primary);
        background: rgba(11, 61, 107, 0.05);
        box-shadow: 0 3px 12px rgba(11, 61, 107, 0.15);
    }

    /* كروت اختيار نغمات الإشعارات */
    .tone-select-item {
        border: 1.5px solid var(--border-color);
        border-radius: 10px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
        transition: all 0.15s ease;
        background: #ffffff;
    }

    .tone-select-item:hover {
        border-color: var(--primary);
        background: #f8fafc;
    }

    .tone-select-item.selected {
        border-color: var(--primary);
        background: rgba(11, 61, 107, 0.05);
    }

    /* كرت حالة طلب التسجيل */
    .req-compact-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.1rem;
        transition: all 0.2s ease;
    }

    .req-compact-card:hover {
        border-color: var(--primary);
        box-shadow: 0 3px 14px rgba(11, 61, 107, 0.06);
    }

    /* رابط الجامعة النصي البسيط في رأس البطاقة */
    .univ-simple-title-link {
        color: #1e293b;
        font-weight: 700;
        text-decoration: none;
        font-size: 0.96rem;
        transition: color 0.15s ease;
    }

    .univ-simple-title-link:hover {
        color: var(--primary);
        text-decoration: underline;
    }

    /* كروت وتنبيهات تنظيم الملف الشخصي الحديث */
    .profile-section-card {
        background: #fbfcfe;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.25rem;
        margin-bottom: 1.25rem;
        transition: all 0.2s ease;
    }
    .profile-section-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
    }
    .profile-section-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 0.6rem;
        border-bottom: 1px dashed #e2e8f0;
    }
    .profile-completeness-wrap {
        background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
        border: 1px solid #dbeafe;
        border-radius: 14px;
        padding: 1.15rem;
        margin-bottom: 1.5rem;
    }
    .profile-completeness-progress {
        height: 10px;
        border-radius: 8px;
        background-color: #e2e8f0;
        overflow: hidden;
    }
    .profile-completeness-bar {
        background: linear-gradient(90deg, #10b981 0%, #0284c7 100%);
        height: 100%;
        border-radius: 8px;
        transition: width 0.5s ease;
    }
    .academic-glance-badge {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        text-align: center;
    }
    .academic-glance-badge .label {
        font-size: 0.75rem;
        color: #64748b;
        margin-bottom: 2px;
    }
    .academic-glance-badge .val {
        font-size: 1rem;
        font-weight: 800;
        color: #0f172a;
    }

    /* كرت وتنبيه استكمال الطلب */
    .req-needs-completion-card {
        border: 2px solid #f59e0b !important;
        background: #fffdf7 !important;
        box-shadow: 0 4px 16px rgba(245, 158, 11, 0.12) !important;
    }
    .completion-alert-banner {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border: 1.5px solid #fde68a;
        border-radius: 14px;
        padding: 1.1rem;
        margin-bottom: 1.25rem;
    }
    .completion-note-highlight {
        background: #ffffff;
        border: 1.5px dashed #f59e0b;
        border-radius: 10px;
        padding: 0.85rem 1rem;
        color: #78350f;
        font-size: 0.88rem;
        line-height: 1.6;
    }

    /* عناصر الإشعارات */
    .notification-item {
        padding: 0.85rem 0.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }
    .notification-item:last-child { border-bottom: none; }

    /* التجاوب مع شاشات الهواتف */
    @media (max-width: 767.98px) {
        .student-id-card {
            padding: 1.1rem;
        }
        .student-avatar {
            width: 54px;
            height: 54px;
            font-size: 1.35rem;
        }
        .student-content-pane {
            padding: 1.1rem;
        }
    }

    /* ========================================================
       تصميم وهيكلية نافذة سداد الرسوم واثبات العملية الموحدة
       ======================================================== */
    /* تثبيت النافذة المنبثقة فوق كامل الصفحة بما في ذلك الشريط العلوي */
    #studentPaymentModal.modal {
        z-index: 100050 !important;
        overscroll-behavior: contain !important;
    }
    .modal-backdrop {
        z-index: 100040 !important;
    }
    body.modal-open,
    html.modal-open {
        overflow: hidden !important;
    }

    /* هيكلية مرنة لضمان ظهور أزرار الإجراءات السفلية دوماً دون الحاجة لتصغير الصفحة */
    #studentPaymentModal .modal-dialog {
        max-height: calc(100vh - 2.5rem);
        height: auto;
        margin: 1.25rem auto;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    @media (max-width: 576.98px) {
        #studentPaymentModal .modal-dialog {
            max-height: calc(100vh - 1rem);
            margin: 0.5rem auto;
            width: calc(100% - 1rem);
        }
    }

    #studentPaymentModal .modal-content {
        max-height: calc(100vh - 2.5rem);
        display: flex;
        flex-direction: column;
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.5);
        overflow: hidden;
    }
    @media (max-width: 576.98px) {
        #studentPaymentModal .modal-content {
            max-height: calc(100vh - 1rem);
            border-radius: 14px;
        }
    }

    #studentPaymentModal #studentPaymentForm {
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        min-height: 0;
        overflow: hidden;
        margin: 0;
    }

    #studentPaymentModal .modal-header {
        flex-shrink: 0;
        background: linear-gradient(135deg, #091325 0%, #0f2552 45%, #1e3a8a 100%);
        border-bottom: 2.5px solid #f59e0b;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        padding: 0.9rem 1.4rem;
    }

    .sp-modal-icon-badge {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.25), rgba(30, 58, 138, 0.45));
        border: 1.5px solid rgba(245, 158, 11, 0.45);
        display: grid;
        place-items: center;
        color: #fbbf24;
        font-size: 1.35rem;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
        flex-shrink: 0;
    }

    #studentPaymentModal .modal-body {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto !important;
        overscroll-behavior: contain !important;
        -webkit-overflow-scrolling: touch;
        padding: 1.15rem 1.4rem !important;
        background: #f8fafc;
    }

    [data-theme="dark"] #studentPaymentModal .modal-body {
        background: #0b1120 !important;
    }

    #studentPaymentModal .modal-footer {
        flex-shrink: 0;
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        padding: 0.75rem 1.4rem;
        position: relative;
        z-index: 10;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.04);
    }

    [data-theme="dark"] #studentPaymentModal .modal-footer {
        background: #0f172a !important;
        border-top-color: #1e293b !important;
    }

    .sp-payment-fee-summary-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 16px;
    }

    .sp-pay-instruction-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        transition: all 0.25s ease;
    }

    .sp-instruction-step-num {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #2563eb;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.84rem;
        font-weight: 700;
        flex-shrink: 0;
        box-shadow: 0 2px 4px rgba(37,99,235,0.2);
    }

    .sp-instruction-step-num.completed {
        background: #16a34a !important;
        box-shadow: 0 2px 4px rgba(22,163,74,0.2);
    }

    .sp-pay-methods-pills-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .sp-pay-method-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 8px 18px;
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        border-radius: 50px;
        color: #1e293b;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        outline: none;
    }

    .sp-pay-method-pill:hover {
        border-color: #2563eb;
        background: #eff6ff;
        color: #1d4ed8;
        transform: translateY(-2px);
    }

    .sp-pay-method-pill.active {
        background: #2563eb;
        border-color: #2563eb;
        color: #ffffff;
    }

    .sp-selected-method-details-card {
        background: #f8fafc;
        border: 1.5px solid #bfdbfe;
        border-radius: 10px;
        padding: 12px 14px;
        animation: spFadeInDetails 0.22s ease-out;
    }

    @keyframes spFadeInDetails {
        from { opacity: 0; transform: translateY(4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .sp-account-card-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
    }

    .sp-voucher-form-card {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #ffffff;
    }

    .sp-upload-voucher-box {
        border: 1.5px dashed #cbd5e1;
        border-radius: 6px;
        background: #f8fafc;
        transition: border-color 0.2s;
    }

    .sp-upload-voucher-box:hover {
        border-color: #2563eb;
    }

    /* تكامل الوضع الليلي في ملف الطالب الشخصي */
    [data-theme="dark"] .student-vnav-card,
    [data-theme="dark"] .student-content-pane,
    [data-theme="dark"] .mobile-accordion-card,
    [data-theme="dark"] .mobile-accordion-header,
    [data-theme="dark"] .mobile-accordion-body,
    [data-theme="dark"] .profile-section-card,
    [data-theme="dark"] .req-compact-card,
    [data-theme="dark"] .theme-select-card,
    [data-theme="dark"] .tone-select-item,
    [data-theme="dark"] .sp-pay-instruction-card,
    [data-theme="dark"] .sp-voucher-form-card {
        background: #131d36 !important;
        border-color: #1e2f50 !important;
        color: #f8fafc !important;
    }

    [data-theme="dark"] .mobile-accordion-card.open .mobile-accordion-header {
        background: #182442 !important;
        color: var(--accent-light) !important;
        border-color: #1e2f50 !important;
    }

    [data-theme="dark"] .student-vnav-item {
        color: #cbd5e1 !important;
    }
    [data-theme="dark"] .student-vnav-item:hover {
        background: #182442 !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .profile-completeness-wrap,
    [data-theme="dark"] .academic-glance-badge,
    [data-theme="dark"] .sp-payment-fee-summary-card,
    [data-theme="dark"] .sp-selected-method-details-card,
    [data-theme="dark"] .sp-account-card-box,
    [data-theme="dark"] .sp-upload-voucher-box {
        background: #182442 !important;
        border-color: #273554 !important;
        color: #cbd5e1 !important;
    }

    [data-theme="dark"] .sp-pay-method-pill {
        background: #182442 !important;
        color: #cbd5e1 !important;
        border-color: #273554 !important;
    }
    [data-theme="dark"] .sp-pay-method-pill:hover,
    [data-theme="dark"] .sp-pay-method-pill.active {
        background: #2563eb !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .univ-simple-title-link {
        color: #93c5fd !important;
    }
    [data-theme="dark"] .univ-simple-title-link:hover {
        color: var(--accent-light) !important;
    }

    [data-theme="dark"] .profile-section-title {
        color: #f8fafc !important;
        border-color: #273554 !important;
    }
</style>
@endsection

@section('content')
<div class="container py-4 student-dashboard-wrap">

    {{-- رسائل التنبيه والنجاح --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
        <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>يرجى تصحيح الأخطاء التالية:</div>
        <ul class="mb-0 small ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- ====== 1. البطاقة الأولى: هوية ومعلومات المستخدم ====== --}}
    <div class="student-id-card mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="student-avatar">
                    {{ mb_substr($student->full_name ?? $user->username, 0, 1) }}
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h4 class="fw-bold mb-0 text-white">{{ $student->full_name }}</h4>
                        <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size:0.75rem;">
                            <i class="bi bi-patch-check-fill me-1"></i>طالب مسجل
                        </span>
                    </div>
                    <div class="small text-white-50 mt-1 d-flex align-items-center gap-3 flex-wrap">
                        <span><i class="bi bi-person me-1"></i>{{ $user->username }}@</span>
                        <span><i class="bi bi-telephone me-1"></i>{{ $user->phone ?? 'لا يوجد هاتف' }}</span>
                        @if($user->email)
                        <span><i class="bi bi-envelope me-1"></i>{{ $user->email }}</span>
                        @endif
                        @if($student->city)
                        <span><i class="bi bi-geo-alt me-1"></i>{{ $student->city }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- شريط الإحصائيات المختصر المعدل بدقة --}}
        <div class="row g-2 mt-3 pt-3 border-top border-white border-opacity-10 text-center">
            <div class="col-4 col-md-3">
                <div class="small text-white-50">طلبات التسجيل</div>
                <div class="fw-bold fs-6 text-white">{{ $requests->count() }} طلب</div>
            </div>
            <div class="col-4 col-md-3">
                <div class="small text-white-50">رموز الإحالة الصادرة</div>
                <div class="fw-bold fs-6 {{ $referrals->count() > 0 ? 'text-warning' : 'text-white-50' }}">
                    {{ $referrals->count() }} {{ $referrals->count() == 1 ? 'رمز' : 'رموز' }}
                </div>
            </div>
            <div class="col-4 col-md-3">
                <div class="small text-white-50">حالة الحساب</div>
                <div class="fw-bold fs-6 text-white">نشط ومعتمد</div>
            </div>
            <div class="col-12 col-md-3">
                <div class="small text-white-50">المظهر النشط</div>
                <div class="fw-bold fs-6 text-warning" id="activeThemeTextBadge">
                    {{ $user->theme_mode === 'dark' ? 'الوضع الليلي' : 'الوضع النهاري' }}
                </div>
            </div>
        </div>
    </div>

    {{-- حساب عدد الإشعارات غير المقروءة مبدئياً --}}
    @php
        $activeTab = request('tab', session('tab', 'profile'));
        $unreadCount = max(1, $requests->count() + $referrals->count());
    @endphp

    {{-- ====== 2. العرض في الشاشات الكبيرة (Desktop: قائمة عمودية يميناً وبجانبها المحتوى) ====== --}}
    <div class="d-none d-lg-block">
        <div class="row g-4">
            {{-- القائمة العمودية في يمين الصفحة (RTL) --}}
            <div class="col-lg-3 col-md-4">
                <div class="student-vnav-card">
                    <button type="button" class="student-vnav-item {{ $activeTab === 'profile' ? 'active' : '' }}" onclick="switchDesktopTab('profile')">
                        <i class="bi bi-person-vcard text-primary"></i>
                        <span>البيانات الشخصية</span>
                    </button>
                    <button type="button" class="student-vnav-item {{ $activeTab === 'requests' ? 'active' : '' }}" onclick="switchDesktopTab('requests')">
                        <i class="bi bi-file-earmark-check text-primary"></i>
                        <span>طلبات التسجيل</span>
                        @if($requests->count() > 0)
                        <span class="badge bg-primary-subtle text-primary border ms-auto" style="font-size:0.72rem;">{{ $requests->count() }}</span>
                        @endif
                    </button>
                    <button type="button" class="student-vnav-item {{ $activeTab === 'referral' ? 'active' : '' }}" onclick="switchDesktopTab('referral')">
                        <i class="bi bi-qr-code text-primary"></i>
                        <span>رموز الإحالة الصادرة</span>
                        @if($referrals->count() > 0)
                        <span class="badge bg-warning text-dark ms-auto" style="font-size:0.72rem;">{{ $referrals->count() }}</span>
                        @endif
                    </button>
                    <button type="button" class="student-vnav-item {{ $activeTab === 'notifications' ? 'active' : '' }}" onclick="switchDesktopTab('notifications')">
                        <i class="bi bi-bell text-primary"></i>
                        <span>الإشعارات والأصوات</span>
                        <span class="notif-unread-badge ms-auto" id="notifBadgeDesktop">{{ $unreadCount }}</span>
                    </button>
                    <button type="button" class="student-vnav-item {{ $activeTab === 'appearance' ? 'active' : '' }}" onclick="switchDesktopTab('appearance')">
                        <i class="bi bi-palette text-primary"></i>
                        <span>المظهر والثيمات</span>
                    </button>
                    <button type="button" class="student-vnav-item {{ $activeTab === 'security' ? 'active' : '' }}" onclick="switchDesktopTab('security')">
                        <i class="bi bi-shield-lock text-primary"></i>
                        <span>الأمان وكلمة المرور</span>
                    </button>
                </div>
            </div>

            {{-- المحتوى المقابل للتبويب النشط على اليسار --}}
            <div class="col-lg-9 col-md-8" id="desktopPaneContainer">
                {{-- سيتم وضع الحاويات هنا تلقائياً في الشاشات الكبيرة --}}
            </div>
        </div>
    </div>

    {{-- ====== 3. العرض في شاشة الهاتف (Mobile: قوائم منسدلة مغلقة في البداية تفتح إعداداتها تحتها مباشرة) ====== --}}
    <div class="d-lg-none mobile-accordion-wrapper mb-4">

        {{-- القائمة 1: البيانات الشخصية --}}
        <div class="mobile-accordion-card" id="mobileCard_profile">
            <button type="button" class="mobile-accordion-header" onclick="toggleMobileAccordion('profile')">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-person-vcard text-primary fs-5"></i>
                    <span>البيانات الشخصية</span>
                </div>
                <i class="bi bi-chevron-down mobile-chevron"></i>
            </button>
            <div class="mobile-accordion-collapse" id="mobileCollapse_profile" style="display: none;">
                <div class="mobile-accordion-body" id="slot_mobile_profile">
                    {{-- توضع حاوية البيانات الشخصية هنا عند العرض بالهاتف --}}
                </div>
            </div>
        </div>

        {{-- القائمة 2: طلبات التسجيل --}}
        <div class="mobile-accordion-card" id="mobileCard_requests">
            <button type="button" class="mobile-accordion-header" onclick="toggleMobileAccordion('requests')">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-check text-primary fs-5"></i>
                    <span>طلبات التسجيل</span>
                    @if($requests->count() > 0)
                    <span class="badge bg-primary-subtle text-primary border" style="font-size:0.72rem;">{{ $requests->count() }}</span>
                    @endif
                </div>
                <i class="bi bi-chevron-down mobile-chevron"></i>
            </button>
            <div class="mobile-accordion-collapse" id="mobileCollapse_requests" style="display: none;">
                <div class="mobile-accordion-body" id="slot_mobile_requests">
                    {{-- توضع حاوية طلبات التسجيل هنا عند العرض بالهاتف --}}
                </div>
            </div>
        </div>

        {{-- القائمة 3: رموز الإحالة الصادرة --}}
        <div class="mobile-accordion-card" id="mobileCard_referral">
            <button type="button" class="mobile-accordion-header" onclick="toggleMobileAccordion('referral')">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-qr-code text-primary fs-5"></i>
                    <span>رموز الإحالة الصادرة</span>
                    @if($referrals->count() > 0)
                    <span class="badge bg-warning text-dark" style="font-size:0.72rem;">{{ $referrals->count() }}</span>
                    @endif
                </div>
                <i class="bi bi-chevron-down mobile-chevron"></i>
            </button>
            <div class="mobile-accordion-collapse" id="mobileCollapse_referral" style="display: none;">
                <div class="mobile-accordion-body" id="slot_mobile_referral">
                    {{-- توضع حاوية رموز الإحالة هنا عند العرض بالهاتف --}}
                </div>
            </div>
        </div>

        {{-- القائمة 4: الإشعارات والأصوات --}}
        <div class="mobile-accordion-card" id="mobileCard_notifications">
            <button type="button" class="mobile-accordion-header" onclick="toggleMobileAccordion('notifications')">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-bell text-primary fs-5"></i>
                    <span>الإشعارات والأصوات</span>
                    <span class="notif-unread-badge" id="notifBadgeMobile">{{ $unreadCount }}</span>
                </div>
                <i class="bi bi-chevron-down mobile-chevron"></i>
            </button>
            <div class="mobile-accordion-collapse" id="mobileCollapse_notifications" style="display: none;">
                <div class="mobile-accordion-body" id="slot_mobile_notifications">
                    {{-- توضع حاوية الإشعارات هنا عند العرض بالهاتف --}}
                </div>
            </div>
        </div>

        {{-- القائمة 5: المظهر والثيمات --}}
        <div class="mobile-accordion-card" id="mobileCard_appearance">
            <button type="button" class="mobile-accordion-header" onclick="toggleMobileAccordion('appearance')">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-palette text-primary fs-5"></i>
                    <span>المظهر والثيمات</span>
                </div>
                <i class="bi bi-chevron-down mobile-chevron"></i>
            </button>
            <div class="mobile-accordion-collapse" id="mobileCollapse_appearance" style="display: none;">
                <div class="mobile-accordion-body" id="slot_mobile_appearance">
                    {{-- توضع حاوية المظهر والثيمات هنا عند العرض بالهاتف --}}
                </div>
            </div>
        </div>

        {{-- القائمة 6: الأمان وكلمة المرور --}}
        <div class="mobile-accordion-card" id="mobileCard_security">
            <button type="button" class="mobile-accordion-header" onclick="toggleMobileAccordion('security')">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-shield-lock text-primary fs-5"></i>
                    <span>الأمان وكلمة المرور</span>
                </div>
                <i class="bi bi-chevron-down mobile-chevron"></i>
            </button>
            <div class="mobile-accordion-collapse" id="mobileCollapse_security" style="display: none;">
                <div class="mobile-accordion-body" id="slot_mobile_security">
                    {{-- توضع حاوية الأمان وتغيير كلمة المرور هنا عند العرض بالهاتف --}}
                </div>
            </div>
        </div>

    </div>

    {{-- ====== الحاويات الفعلية للمحتوى (Unique Content Panes) ====== --}}
    <div id="allPanesMasterSource" style="display: none;">

        {{-- ---------------- الحاوية 1: البيانات الشخصية والملف التعريفي المنظم ---------------- --}}
        <div id="pane_profile" class="student-content-pane">
            @php
                $profScore = 0;
                if ($student->full_name) $profScore += 20;
                if ($user->phone) $profScore += 20;
                if ($user->email) $profScore += 15;
                if ($student->gender) $profScore += 15;
                if ($student->birth_date) $profScore += 15;
                if ($student->city || $student->address) $profScore += 15;
                $completeness = min(100, $profScore);

                // استخراج المؤهل الأكاديمي من أحدث طلب تسجيل مسجل إن وجد
                $latestReq = $requests->first();
                $academicSecAvg = $latestReq ? $latestReq->sec_avg : ($student->high_school_avg ?? null);
                $academicSchool = $latestReq ? $latestReq->secondary_school : ($student->high_school_name ?? 'ثانوية عامة');
                $academicSeatNo = $latestReq ? $latestReq->seat_number : ($student->high_school_seat_no ?? '—');
                $academicYear = $latestReq ? $latestReq->grad_year : ($student->grad_year ?? 2024);
                $academicSection = $latestReq ? $latestReq->sec_section : 'علمي';
            @endphp

            {{-- شريط اكتمال الملف الشخصي التفاعلي --}}
            <div class="profile-completeness-wrap shadow-sm">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-patch-check-fill text-primary fs-5"></i>
                        <span class="fw-bold text-dark fs-6">جاهزية الملف الشخصي:</span>
                        <span class="badge {{ $completeness >= 80 ? 'bg-success' : 'bg-warning text-dark' }} px-2.5 py-1">
                            {{ $completeness }}% مكتمل
                        </span>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap small">
                        <span class="badge bg-light text-secondary border font-monospace">@ {{ $user->username }}</span>
                        <span class="badge bg-primary-subtle text-primary border">طالب جامعي</span>
                    </div>
                </div>
                <div class="profile-completeness-progress">
                    <div class="profile-completeness-bar" style="width: {{ $completeness }}%;"></div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2 small text-muted">
                    <span>{{ $completeness >= 90 ? 'كافة البيانات الأساسية مكتملة وموثقة للتقديم.' : 'يرجى استكمال الحقول الإلزامية لتسهيل إجراءات القبول.' }}</span>
                    <span class="fw-bold text-primary">{{ $completeness }}/100</span>
                </div>
            </div>

            <form action="{{ route('student.profile.update') }}" method="POST">
                @csrf

                {{-- القسم الأول: بيانات الهوية الشخصية --}}
                <div class="profile-section-card shadow-sm">
                    <div class="profile-section-title">
                        <i class="bi bi-person-vcard text-primary fs-5"></i>
                        <span>1. بيانات الهوية الشخصية والأساسية</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">الاسم الكامل (الرباعي وفق الهوية) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="bi bi-person"></i></span>
                                <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name', $student->full_name) }}" required placeholder="الاسم الرباعي الكامل">
                            </div>
                            @error('full_name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-bold">الجنس</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="bi bi-gender-ambiguous"></i></span>
                                <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                    <option value="">اختر الجنس...</option>
                                    <option value="ذكر" {{ old('gender', $student->gender) == 'ذكر' ? 'selected' : '' }}>ذكر</option>
                                    <option value="أنثى" {{ old('gender', $student->gender) == 'أنثى' ? 'selected' : '' }}>أنثى</option>
                                </select>
                            </div>
                            @error('gender')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-bold">تاريخ الميلاد</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="bi bi-calendar-event"></i></span>
                                <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date', $student->birth_date ? $student->birth_date->format('Y-m-d') : '') }}">
                            </div>
                            @error('birth_date')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- القسم الثاني: بيانات التواصل المباشر والإشعارات --}}
                <div class="profile-section-card shadow-sm">
                    <div class="profile-section-title">
                        <i class="bi bi-telephone-inbound text-primary fs-5"></i>
                        <span>2. قنوات التواصل والتنبيهات المعتمدة</span>
                        <span class="badge bg-light text-muted border ms-auto small" style="font-size:0.72rem;">ضرورية لاستلام قرارات القبول</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">رقم الهاتف الأساسي <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="phone" class="form-control font-monospace @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" required placeholder="77XXXXXXX">
                            </div>
                            <small class="text-muted" style="font-size:0.75rem;">يُستخدم للتواصل المباشر مع الجامعة وإرسال إشعارات القبول.</small>
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">البريد الإلكتروني</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" placeholder="name@example.com">
                            </div>
                            <small class="text-muted" style="font-size:0.75rem;">تصلك عليه الإشعارات والرسائل الرسمية الصادرة من المنصة والجامعات.</small>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- القسم الثالث: العنوان ومقر الإقامة --}}
                <div class="profile-section-card shadow-sm">
                    <div class="profile-section-title">
                        <i class="bi bi-geo-alt text-primary fs-5"></i>
                        <span>3. مقر السكن والإقامة الحالية</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">المحافظة / المدينة</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="bi bi-building"></i></span>
                                <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $student->city) }}" placeholder="صنعاء، عدن، تعز...">
                            </div>
                            @error('city')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-7">
                            <label class="form-label small fw-bold">العنوان السكني التفصيلي</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-secondary"><i class="bi bi-pin-map"></i></span>
                                <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $student->address) }}" placeholder="المديرية، الحي، الشارع الرئيسي">
                            </div>
                            @error('address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- القسم الرابع: المؤهل الأكاديمي المعتمد للثانوية العامة --}}
                <div class="profile-section-card shadow-sm">
                    <div class="profile-section-title d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-mortarboard text-primary fs-5"></i>
                            <span>4. السجل الأكاديمي لشهادة الثانوية العامة</span>
                        </div>
                        <span class="badge bg-success-subtle text-success border small" style="font-size:0.72rem;">
                            <i class="bi bi-shield-check me-1"></i>موثق من الوزارة
                        </span>
                    </div>
                    <div class="row g-2 mt-1">
                        <div class="col-6 col-md-3">
                            <div class="academic-glance-badge">
                                <div class="label">النسبة المئوية</div>
                                <div class="val text-primary">{{ $academicSecAvg ? number_format($academicSecAvg, 2) . '%' : 'قيد التدقيق' }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="academic-glance-badge">
                                <div class="label">رقم الجلوس</div>
                                <div class="val font-monospace">{{ $academicSeatNo }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="academic-glance-badge">
                                <div class="label">القسم والمسار</div>
                                <div class="val">{{ $academicSection }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="academic-glance-badge">
                                <div class="label">سنة التخرج</div>
                                <div class="val">{{ $academicYear }}</div>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="p-2.5 rounded-3 bg-light border small text-muted d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div><i class="bi bi-building-check me-1 text-primary"></i>المدرسة: <strong>{{ $academicSchool }}</strong></div>
                                <span class="text-secondary" style="font-size:0.75rem;">يتم التحقق والمزامنة تلقائياً مع نظام وزارة التربية والتعليم عند تقديم أي طلب.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top flex-wrap gap-2">
                    <span class="text-muted small">
                        <i class="bi bi-info-circle me-1"></i>تحديث البيانات الشخصية لا يؤثر على الطلبات السابقة التي تم قبولها نهائياً.
                    </span>
                    <button type="submit" class="btn btn-primary fw-bold px-4 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2">
                        <i class="bi bi-check2-circle fs-5"></i>
                        <span>حفظ وتحديث البيانات</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- ---------------- الحاوية 2: طلبات التسجيل وضوابطها ---------------- --}}
        <div id="pane_requests" class="student-content-pane">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-file-earmark-text text-primary me-2"></i>طلبات التسجيل المقدمة</h5>
                    <p class="text-muted small mb-0">متابعة حالة طلبات التحاقك بالجامعات ومراجعة قرارات لجان القبول</p>
                </div>
                <a href="{{ route('student.apply') }}" class="btn btn-primary btn-sm fw-bold px-3 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span>تقديم طلب تسجيل جديد</span>
                </a>
            </div>

            {{-- إشعار وتنبيه بارز في حال وجود طلبات تتطلب استكمال --}}
            @php
                $needsCompletionReqs = $requests->where('status', 'يتطلب استكمال');
            @endphp
            @if($needsCompletionReqs->count() > 0)
            <div class="completion-alert-banner shadow-sm mb-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="text-warning fs-3 lh-1 mt-1">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                            <h6 class="fw-bold text-dark mb-0">
                                تنبيه عاجل: لديك ({{ $needsCompletionReqs->count() }}) طلب تسجيل بحاجة لاستكمال وثائق أو بيانات!
                            </h6>
                            <span class="badge bg-warning text-dark fw-bold px-2.5 py-1">مطلوب إجراء من الطالب</span>
                        </div>
                        <p class="small text-muted mb-2">
                            قامت لجان القبول والتسجيل بمراجعة طلبك وطلبت استكمال نواقص معينة لإتمام القبول. يرجى مراجعة ملاحظات الجامعة واستكمالها فوراً:
                        </p>
                        @foreach($needsCompletionReqs as $compReq)
                        <div class="p-2.5 rounded-3 bg-white border border-warning-subtle mb-2">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                                <span class="fw-bold text-dark small">
                                    <i class="bi bi-building me-1 text-primary"></i>{{ $compReq->university->university_name }} — {{ $compReq->desired_spec }} (طلب رقم #{{ $compReq->req_id }})
                                </span>
                                <a href="{{ route('student.apply', ['req_id' => $compReq->req_id]) }}" class="btn btn-warning btn-sm fw-bold px-3 py-1 shadow-sm" style="font-size:0.78rem;">
                                    <i class="bi bi-pencil-square me-1"></i>استكمال النواقص وإعادة الإرسال الآن
                                </a>
                            </div>
                            <div class="completion-note-highlight mt-1">
                                <i class="bi bi-chat-quote-fill me-1 text-warning"></i>
                                <strong>ملاحظة وتوجيهات الجامعة للاستكمال:</strong>
                                <span class="fw-bold text-dark">{{ $compReq->rejection_reason ?: 'يرجى فتح الاستمارة ومراجعة الوثائق وإعادة رفع ما يلزم.' }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if($requests->count() > 0)
            <div class="row g-3">
                @foreach($requests as $req)
                @php
                    $isDraft = ($req->status === 'مسودة');
                    $isAccepted = ($req->status === 'مقبول');
                    $isRejected = ($req->status === 'مرفوض');
                    $isNeedsCompletion = ($req->status === 'يتطلب استكمال');
                    $isPending = ($req->status === 'قيد المراجعة');

                    // حالة السداد المحسوبة بدقة وفق أحدث دورة بيانات
                    $latestPayment = $req->latestPayment ?? ($req->payments ? $req->payments->sortByDesc('payment_id')->first() : null);
                    $isReceiptRejected = ($latestPayment && $latestPayment->ver_status === 'مرفوض') || ($req->payment_status === 'سند مرفوض');
                    $isPaid = (!$isReceiptRejected && (in_array($req->payment_status, ['مسدد ومعتمد', 'قيد التحقق']) || ($latestPayment && in_array($latestPayment->ver_status, ['معتمد ومؤكد', 'قيد التحقق']))));
                    $isUnpaid = (!$isPaid && !$isReceiptRejected);
                @endphp

                <div class="col-12" id="reqCard_{{ $req->req_id }}">
                    <div class="req-compact-card {{ $isNeedsCompletion ? 'req-needs-completion-card' : '' }}">
                        
                        {{-- رأس البطاقة: رقم المعاملة + اسم الجامعة كرابط نصي بسيط + التخصص + وسم الحالة --}}
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-2 mb-2 border-bottom">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="badge bg-light text-secondary border font-monospace">#{{ $req->req_id }}</span>
                                
                                {{-- رابط الجامعة: اسم الجامعة في رأس البطاقة رابطاً نصياً بسيطاً ينقل إلى صفحتها بدون زخرفة أو تلوين مفرط --}}
                                <a href="{{ route('universities.show', $req->university_id) }}" class="univ-simple-title-link" title="الانتقال إلى صفحة الجامعة">
                                    {{ $req->university->university_name }}
                                </a>

                                <span class="badge bg-primary-subtle text-primary border">{{ $req->desired_spec }}</span>

                                {{-- الطلبات المسودة (Draft): يظهر وسم "مسودة" بجانب اسم التخصص أو الجامعة، والنقر عليه ينقل مباشرة لاستكمال وإرسال الطلب --}}
                                @if($isDraft)
                                    <a href="{{ route('student.apply', ['req_id' => $req->req_id]) }}" class="badge bg-secondary text-white text-decoration-none px-2.5 py-1" title="انقر هنا مباشرة لاستكمال ومتابعة إرسال الطلب">
                                        <i class="bi bi-pencil-square me-1"></i>مسودة (انقر للاستكمال)
                                    </a>
                                @endif

                                @if($req->is_replacement)
                                    <span class="badge bg-secondary-subtle text-secondary border" title="طلب بديل تم تقديمه بعد إلغاء طلب سابق خلال 24 ساعة">
                                        <i class="bi bi-arrow-repeat me-1"></i>طلب بديل
                                    </span>
                                @endif
                            </div>

                            {{-- شارات الحالة وفق القواعد المحددة بدقة دون تشتيت أو تعارض --}}
                            <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                @if($isAccepted)
                                    {{-- الطلبات المقبولة: تظهر شارة "مقبول" فقط، وتُخفى أي ملاحظات دفع سابقة أو أزرار غير لازمة --}}
                                    <span class="badge bg-success px-3 py-1.5 fw-bold" style="font-size:0.8rem;">
                                        <i class="bi bi-check-circle-fill me-1"></i>مقبول
                                    </span>

                                @elseif($isRejected)
                                    {{-- الطلبات المرفوضة: تظهر شارة "مرفوض" فقط --}}
                                    <span class="badge bg-danger px-3 py-1.5 fw-bold" style="font-size:0.8rem;">
                                        <i class="bi bi-x-circle-fill me-1"></i>مرفوض
                                    </span>

                                @elseif($isNeedsCompletion)
                                    {{-- الطلبات التي تتطلب استكمال: تظهر شارة "يتطلب استكمال" فقط - لا يظهر أبداً وسم "قيد المراجعة" --}}
                                    <span class="badge bg-warning text-dark fw-bold px-3 py-1.5" style="font-size:0.8rem;">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>يتطلب استكمال
                                    </span>
                                    @if($isPaid)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1" style="font-size:0.75rem;">
                                            <i class="bi bi-check-circle-fill me-1"></i>تم الدفع
                                        </span>
                                    @elseif($isReceiptRejected)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1" style="font-size:0.75rem;">
                                            <i class="bi bi-exclamation-circle-fill me-1"></i>سند مرفوض
                                        </span>
                                    @endif

                                @elseif($isPending)
                                    {{-- الطلبات قيد المراجعة: إذا كان السند مرفوضاً تظهر شارة واضحة بدلاً من "قيد المراجعة" --}}
                                    @if($isReceiptRejected)
                                        <span class="badge bg-danger text-white px-3 py-1.5 fw-bold" style="font-size:0.8rem;">
                                            <i class="bi bi-arrow-clockwise me-1"></i>مطلوب إعادة رفع السند
                                        </span>
                                    @else
                                        <span class="badge bg-primary text-white px-3 py-1.5 fw-bold" style="font-size:0.8rem;">
                                            <i class="bi bi-clock-history me-1"></i>قيد المراجعة
                                        </span>
                                        @if($isPaid)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1" style="font-size:0.75rem;">
                                                <i class="bi bi-check-circle-fill me-1"></i>تم الدفع
                                            </span>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>

                        {{-- تفاصيل الطلب والأزرار الإجرائية --}}
                        <div class="row g-2 small text-muted align-items-center">
                            {{-- تاريخ التقديم --}}
                            <div class="col-md-3">
                                <i class="bi bi-calendar3 me-1 text-primary"></i>تاريخ التقديم: <strong>{{ $req->req_date ? $req->req_date->format('Y-m-d') : '—' }}</strong>
                            </div>

                            {{-- عدد الوثائق المرفقة: يُلغى عرضه في الطلبات المسودة لعدم اكتمال الطلب بعد --}}
                            @if(!$isDraft)
                            <div class="col-md-3">
                                <i class="bi bi-paperclip me-1 text-info"></i>الوثائق: <strong>{{ $req->regDocs->count() }} مستند</strong>
                            </div>
                            @endif

                            {{-- أزرار الإجراءات المحددة بدقة دون تشتيت وإلغاء الأزرار الزائدة والمكررة --}}
                            <div class="{{ $isDraft ? 'col-md-9' : 'col-md-6' }} text-md-end d-flex justify-content-md-end gap-1.5 flex-wrap align-items-center">
                                
                                {{-- 1. زر استكمال النواقص للطلبات التي تتطلب استكمال --}}
                                @if($isNeedsCompletion)
                                    <a href="{{ route('student.apply', ['req_id' => $req->req_id]) }}" class="btn btn-warning btn-xs py-1 px-2.5 fw-bold" style="font-size:0.75rem;">
                                        <i class="bi bi-pencil-square me-1"></i>استكمال النواقص
                                    </a>
                                @endif

                                {{-- 2. زر سداد الرسوم (يظهر فقط إذا كانت الرسوم غير مسددة، ولا يظهر هنا إذا كان السند مرفوضاً لأن زر إعادة الرفع موجود مع الملاحظة بالأسفل مباشرة منعاً للتكرار) --}}
                                @if(!$isDraft && !$isAccepted && !$isRejected && $isUnpaid)
                                    <button type="button" class="btn btn-success btn-xs py-1 px-2.5 fw-bold" style="font-size:0.75rem;" onclick="openStudentPaymentModal({{ $req->req_id }}, '{{ addslashes($req->university->university_name) }}', '{{ addslashes($req->desired_spec) }}', {{ $req->university_id }})">
                                        <i class="bi bi-cash-stack me-1"></i>سداد الرسوم
                                    </button>
                                @endif

                                {{-- 3. زر إلغاء الطلب (متاح للطلبات النشطة القابلة للإلغاء مع استثناء الطلب الثاني خلال 24 ساعة) --}}
                                @if(in_array($req->status, ['قيد المراجعة', 'يتطلب استكمال', 'مسودة']) && $req->can_cancel && !$req->is_replacement)
                                    <button type="button" class="btn btn-outline-danger btn-xs py-1 px-2 fw-bold" style="font-size:0.75rem;" onclick="cancelStudentRequest({{ $req->req_id }}, '{{ addslashes($req->desired_spec) }}', '{{ addslashes($req->university->university_name) }}')" title="إلغاء الطلب نهائياً">
                                        <i class="bi bi-trash3 me-1"></i>إلغاء الطلب
                                    </button>
                                @endif

                            </div>

                            {{-- ملاحظة الرفض المالي بوضوح مع الزر الوحيد المعتمد لإعادة رفع السند منعاً للتكرار --}}
                            @if($isReceiptRejected)
                            <div class="col-12 mt-2 pt-2 border-top">
                                <div class="alert alert-danger py-2 px-3 small mb-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-exclamation-triangle-fill text-danger fs-5 flex-shrink-0"></i>
                                        <div>
                                            <strong class="text-danger d-block">ملاحظة التدقيق المالي للسند:</strong>
                                            <span class="text-dark">{{ ($latestPayment && $latestPayment->ver_result) ? $latestPayment->ver_result : 'تم رفض سند السداد السابق من قِبل الإدارة المالية، يرجى إعادة رفع إشعار تحويل صالح ومطابق.' }}</span>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-xs py-1.5 px-3 fw-bold flex-shrink-0" onclick="openStudentPaymentModal({{ $req->req_id }}, '{{ addslashes($req->university->university_name) }}', '{{ addslashes($req->desired_spec) }}', {{ $req->university_id }})">
                                        <i class="bi bi-arrow-clockwise me-1"></i>إعادة رفع السند
                                    </button>
                                </div>
                            </div>
                            @endif

                            {{-- الملاحظات المطلوبة للاستكمال في الطلبات التي تتطلب استكمال --}}
                            @if($isNeedsCompletion)
                            <div class="col-12 mt-2 pt-2 border-top">
                                <div class="completion-note-highlight">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="text-warning-emphasis fw-bold"><i class="bi bi-exclamation-diamond-fill me-1"></i>الملاحظات المطلوبة للاستكمال:</span>
                                        <span class="badge bg-warning text-dark">مطلوب استكمال</span>
                                    </div>
                                    <div class="text-dark fw-bold">{{ $req->rejection_reason ?: 'يرجى مراجعة البيانات واستكمال الوثائق الناقصة.' }}</div>
                                    <div class="mt-2">
                                        <a href="{{ route('student.apply', ['req_id' => $req->req_id]) }}" class="btn btn-warning btn-sm fw-bold px-3">
                                            <i class="bi bi-pencil-square me-1"></i>فتح الاستمارة واستكمال النواقص الآن
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- الطلبات المرفوضة: عرض سبب الرفض الأكاديمي في الأسفل إن وُجد --}}
                            @if($isRejected && $req->rejection_reason)
                            <div class="col-12 mt-2 pt-2 border-top">
                                <div class="alert alert-danger border-danger mb-0 py-2 px-3 small">
                                    <i class="bi bi-info-circle-fill me-1"></i><strong>سبب الرفض الأكاديمي:</strong> {{ $req->rejection_reason }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-4 text-center">
                <div class="display-5 text-muted mb-2"><i class="bi bi-inbox text-primary"></i></div>
                <h6 class="fw-bold text-dark mb-1">لم تقم بتقديم أي طلب تسجيل حتى الآن</h6>
                <p class="text-muted small mb-3">يمكنك البدء الآن بتقديم طلب تسجيل رسمي أو استعراض دليل الجامعات</p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('student.apply') }}" class="btn btn-primary btn-sm fw-bold px-4 py-2">
                        <i class="bi bi-pencil-square me-1"></i>تقديم طلب تسجيل الآن
                    </a>
                    <a href="{{ route('universities.index') }}" class="btn btn-outline-secondary btn-sm fw-bold px-3 py-2">
                        <i class="bi bi-buildings me-1"></i>تصفح دليل الجامعات
                    </a>
                </div>
            </div>
            @endif
        </div>

        {{-- ---------------- الحاوية 3: رموز الإحالة الصادرة (خاصة بالجامعات) ---------------- --}}
        <div id="pane_referral" class="student-content-pane">
            <div class="mb-3 pb-2 border-bottom">
                <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-qr-code text-primary me-2"></i>رموز الإحالة المعتمدة الصادرة</h5>
                <p class="text-muted small mb-0">رموز الإحالة الرسمية الصادرة لحسابك للحضور الشخصي ومراجعة مسؤول القبول والتسجيل في كل جامعة</p>
            </div>

            @if($referrals->count() > 0)
            <div class="row g-3">
                @foreach($referrals as $ref)
                <div class="col-md-6">
                    <div class="univ-referral-card">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-building text-warning fs-5"></i>
                                <span class="fw-bold text-white small">{{ $ref->university->university_name }}</span>
                            </div>
                            <span class="badge bg-success px-2 py-1" style="font-size:0.7rem;">
                                <i class="bi bi-check-circle me-1"></i>{{ $ref->status }}
                            </span>
                        </div>

                        <div class="text-center my-3">
                            <div class="text-white-50 small mb-1" style="font-size:0.75rem;">رمز الإحالة الخاص بالجامعة:</div>
                            <div class="referral-code-badge" id="refCode_{{ $ref->referral_id }}">{{ $ref->referral_code }}</div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top border-white border-opacity-10">
                            <span class="small text-white-50" style="font-size:0.75rem;">
                                تاريخ الإصدار: {{ $ref->created_at ? $ref->created_at->format('Y-m-d') : 'معتمد' }}
                            </span>
                            <button type="button" class="btn btn-warning btn-sm fw-bold px-3 text-dark" onclick="copySpecificReferralCode('refCode_{{ $ref->referral_id }}')">
                                <i class="bi bi-clipboard-check me-1"></i>نسخ الرمز
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-4 text-center">
                <div class="display-5 text-muted mb-2"><i class="bi bi-ticket-perforated text-warning"></i></div>
                <h6 class="fw-bold text-dark mb-1">لم يتم إصدار أي رمز إحالة لحسابك حتى الآن</h6>
                <p class="text-muted small mb-3">
                    يتم إصدار رمز الإحالة لجامعة محددة عند زيارة صفحة تلك الجامعة والنقر على "طلب إحالة للحضور الشخصي" ليتم تفعيل الرمز لدى مسؤول التسجيل في الجامعة بناءً على بياناتك.
                </p>
                <a href="{{ route('universities.index') }}" class="btn btn-primary btn-sm fw-bold px-3">
                    <i class="bi bi-buildings me-1"></i>تصفح دليل الجامعات واطلب إحالة
                </a>
            </div>
            @endif
        </div>

        {{-- ---------------- الحاوية 4: الإشعارات والأصوات ---------------- --}}
        <div id="pane_notifications" class="student-content-pane">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-bell-fill text-primary me-2"></i>الإشعارات وأصوات التنبيه</h5>
                    <p class="text-muted small mb-0">التحكم في الإشعارات ونغمة التنبيه المفضلة</p>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" id="notificationMuteToggle" {{ $user->notifications_muted ? 'checked' : '' }} onchange="saveNotificationSettings()">
                        <label class="form-check-label small fw-bold" for="notificationMuteToggle">كتم الإشعارات</label>
                    </div>

                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" id="notificationSoundToggle" {{ $user->notification_sound ? 'checked' : '' }} onchange="saveNotificationSettings()">
                        <label class="form-check-label small fw-bold" for="notificationSoundToggle">صوت التنبيه</label>
                    </div>
                </div>
            </div>

            {{-- قسم تخصيص نغمات الإشعارات المختلفة --}}
            <div class="mb-4">
                <label class="form-label small fw-bold text-secondary mb-2">
                    <i class="bi bi-music-note-beamed text-primary me-1"></i>اختيار نغمة التنبيه المفضلة:
                </label>
                @php
                    $currentTone = $user->notification_tone ?? 'chime';
                @endphp
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="tone-select-item {{ $currentTone === 'chime' ? 'selected' : '' }}" onclick="selectNotificationTone('chime')">
                            <div class="d-flex align-items-center gap-2">
                                <input type="radio" name="toneRadio" value="chime" {{ $currentTone === 'chime' ? 'checked' : '' }}>
                                <span class="small fw-bold text-dark">نغمة رنين كلاسيكية (Chime)</span>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-xs py-1 px-2 rounded-pill" onclick="event.stopPropagation(); playToneSound('chime');">
                                <i class="bi bi-play-circle-fill me-1"></i>استماع
                            </button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="tone-select-item {{ $currentTone === 'bell' ? 'selected' : '' }}" onclick="selectNotificationTone('bell')">
                            <div class="d-flex align-items-center gap-2">
                                <input type="radio" name="toneRadio" value="bell" {{ $currentTone === 'bell' ? 'checked' : '' }}>
                                <span class="small fw-bold text-dark">نغمة تنبيه ناعمة (Soft Bell)</span>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-xs py-1 px-2 rounded-pill" onclick="event.stopPropagation(); playToneSound('bell');">
                                <i class="bi bi-play-circle-fill me-1"></i>استماع
                            </button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="tone-select-item {{ $currentTone === 'chord' ? 'selected' : '' }}" onclick="selectNotificationTone('chord')">
                            <div class="d-flex align-items-center gap-2">
                                <input type="radio" name="toneRadio" value="chord" {{ $currentTone === 'chord' ? 'checked' : '' }}>
                                <span class="small fw-bold text-dark">نغمة إنجاز وترية (Upbeat Chord)</span>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-xs py-1 px-2 rounded-pill" onclick="event.stopPropagation(); playToneSound('chord');">
                                <i class="bi bi-play-circle-fill me-1"></i>استماع
                            </button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="tone-select-item {{ $currentTone === 'pluck' ? 'selected' : '' }}" onclick="selectNotificationTone('pluck')">
                            <div class="d-flex align-items-center gap-2">
                                <input type="radio" name="toneRadio" value="pluck" {{ $currentTone === 'pluck' ? 'checked' : '' }}>
                                <span class="small fw-bold text-dark">نغمة نقر هادئة (Gentle Pluck)</span>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-xs py-1 px-2 rounded-pill" onclick="event.stopPropagation(); playToneSound('pluck');">
                                <i class="bi bi-play-circle-fill me-1"></i>استماع
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- سجل الإشعارات --}}
            <div>
                <label class="form-label small fw-bold text-secondary mb-2">
                    <i class="bi bi-clock-history text-primary me-1"></i>سجل الإشعارات المستلمة:
                </label>
                <div class="notifications-list">
                    @if($requests->count() > 0)
                        @foreach($requests as $r)
                        <div class="notification-item">
                            <div class="text-primary fs-5 mt-1"><i class="bi bi-file-earmark-check-fill"></i></div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark small">{{ $r->university->university_name }}</span>
                                    <span class="text-muted" style="font-size:0.75rem;">{{ $r->req_date->diffForHumans() }}</span>
                                </div>
                                <p class="mb-0 small text-muted">
                                    طلبك للالتحاق بتخصص <strong>{{ $r->desired_spec }}</strong> حالته: 
                                    <span class="badge {{ $r->status === 'مقبول' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $r->status }}</span>
                                </p>
                            </div>
                        </div>
                        @endforeach
                    @endif

                    @if($referrals->count() > 0)
                        @foreach($referrals as $ref)
                        <div class="notification-item">
                            <div class="text-warning fs-5 mt-1"><i class="bi bi-qr-code"></i></div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark small">تم إصدار رمز إحالة لـ {{ $ref->university->university_name }}</span>
                                    <span class="text-muted" style="font-size:0.75rem;">{{ $ref->created_at ? $ref->created_at->diffForHumans() : 'معتمد' }}</span>
                                </div>
                                <p class="mb-0 small text-muted">
                                    رمز الإحالة الخاص بك هو <strong>{{ $ref->referral_code }}</strong>، يمكنك إبرازه لمسؤول القبول.
                                </p>
                            </div>
                        </div>
                        @endforeach
                    @endif

                    <div class="notification-item">
                        <div class="text-success fs-5 mt-1"><i class="bi bi-shield-check"></i></div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-dark small">منصة EduYemen الموحدة</span>
                                <span class="text-muted" style="font-size:0.75rem;">نظام</span>
                            </div>
                            <p class="mb-0 small text-muted">
                                حسابك نشط ومؤهل للتقديم على الجامعات واستعراض كافة التخصصات والخدمات المتاحة.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ---------------- الحاوية 5: المظهر والثيمات ---------------- --}}
        <div id="pane_appearance" class="student-content-pane">
            <div class="mb-3 pb-2 border-bottom">
                <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-palette-fill text-primary me-2"></i>تخصيص المظهر ووضع الشاشة</h5>
            </div>

            <div class="row g-3">
                {{-- خيار الوضع النهاري (الافتراضي المعتمد للمنصة) --}}
                <div class="col-md-6">
                    <div class="theme-select-card {{ ($user->theme_mode === 'light' || !$user->theme_mode || $user->theme_mode === 'auto') ? 'active' : '' }}" id="themeCard_light" onclick="selectStudentTheme('light')">
                        <div class="fs-1 text-warning mb-2"><i class="bi bi-sun-fill"></i></div>
                        <h6 class="fw-bold text-dark mb-1">الوضع النهاري (Light)</h6>
                        <p class="text-muted small mb-0">واجهة مشرقة واضحة ومعتمدة لجميع صفحات المنصة (الافتراضي)</p>
                    </div>
                </div>

                {{-- خيار الوضع الليلي --}}
                <div class="col-md-6">
                    <div class="theme-select-card {{ $user->theme_mode === 'dark' ? 'active' : '' }}" id="themeCard_dark" onclick="selectStudentTheme('dark')">
                        <div class="fs-1 text-primary mb-2"><i class="bi bi-moon-stars-fill"></i></div>
                        <h6 class="fw-bold text-dark mb-1">الوضع الليلي (Dark)</h6>
                        <p class="text-muted small mb-0">واجهة داكنة مخصصة للاستخدام الليلي</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ---------------- الحاوية 6: الأمان وتغيير كلمة المرور ---------------- --}}
        <div id="pane_security" class="student-content-pane">
            <div class="mb-3 pb-2 border-bottom">
                <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-shield-lock-fill text-primary me-2"></i>تغيير كلمة المرور وتأمين الحساب</h5>
                <p class="text-muted small mb-0">يجب تأكيد كلمة المرور الحالية قبل اعتماد كلمة المرور الجديدة</p>
            </div>

            <form action="{{ route('student.password.change') }}" method="POST" style="max-width: 520px;">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">كلمة المرور الحالية <span class="text-danger">*</span></label>
                    <input type="password" name="current_password" class="form-control" required placeholder="أدخل كلمة مرورك الحالية">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">كلمة المرور الجديدة <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" required minlength="6" placeholder="على الأقل 6 أحرف أو أرقام">
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">تأكيد كلمة المرور الجديدة <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" required minlength="6" placeholder="أعد إدخال كلمة المرور الجديدة">
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="bi bi-key-fill me-1"></i>تحديث كلمة المرور
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>

{{-- Modal: سداد رسوم التسجيل واثبات العملية للطالب المتطابقة تماماً مع خطوات التقديم الأساسية --}}
<div class="modal fade" id="studentPaymentModal" tabindex="-1" aria-labelledby="studentPaymentModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header text-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="sp-modal-icon-badge">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <h5 class="modal-title fw-bold text-white mb-0" id="studentPaymentModalLabel" style="font-size:1.1rem; letter-spacing: -0.2px;">
                                سداد رسوم التسجيل واثبات العملية
                            </h5>
                            <span class="badge" style="background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.35); font-size:0.7rem; font-weight:600; padding: 3px 8px; border-radius: 20px;">
                                <i class="bi bi-shield-lock-fill me-1"></i>بوابة السداد المعتمدة
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <small class="text-white-50" id="spModalSubtitle" style="font-size:0.78rem;">طلب #...</small>
                            <span class="text-white-50" style="font-size:0.7rem;">•</span>
                            <span class="text-white-50" style="font-size: 0.75rem;">توثيق الإشعار البنكي</span>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="background-color: rgba(255,255,255,0.12); padding: 0.55rem; border-radius: 50%; opacity: 0.9;"></button>
            </div>

            <form id="studentPaymentForm" onsubmit="submitStudentPaymentProof(event)" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="spModalReqId" value="">
                <input type="hidden" name="amount" id="sp_amount" value="5000">

                <div class="modal-body">

                    {{-- الحاوية العلوية: ملخص التخصص والجامعة وقيمة الرسوم --}}
                    <div class="sp-payment-fee-summary-card mb-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-receipt text-primary fs-4"></i>
                                <div>
                                    <span class="text-muted d-block small" style="font-size:0.75rem;">التخصص والجامعة:</span>
                                    <strong class="text-dark" id="spModalSpecNameDisplay">تخصص عام</strong>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="text-muted d-block small" style="font-size:0.75rem;">قيمة الرسوم:</span>
                                <span class="badge bg-success fs-6 font-monospace" id="spModalFeeBadge">5,000 ر.ي</span>
                            </div>
                        </div>
                    </div>

                    {{-- =========================================================
                         التعليمة 1: اختيار الحساب البنكي أو المحفظة المناسبة لك
                         ========================================================= --}}
                    <div class="sp-pay-instruction-card mb-3" id="spPayInstructionStep1">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="sp-instruction-step-num" id="spInstBadge1">١</span>
                                <strong class="text-dark" style="font-size:0.88rem;">اختر الحساب البنكي أو المحفظة المناسبة لك:</strong>
                            </div>
                            <span class="badge bg-light text-muted border small py-1 px-2.5 rounded-pill" id="spInstStatus1" style="font-size:0.72rem;">
                                الخطوة الأولى
                            </span>
                        </div>

                        {{-- مساحة التعليمة الأولى: التبديل في نفس المكان بين الكبسولات وتفاصيل المحفظة --}}
                        <div>
                            {{-- تنبيه مختصر وواضح عند عرض وسائل الدفع --}}
                            <div class="alert alert-primary-subtle border border-primary-subtle py-1.5 px-3 small rounded-2 mb-2 d-flex align-items-center gap-2">
                                <i class="bi bi-info-circle-fill text-primary fs-6 flex-shrink-0"></i>
                                <span class="small"><strong>تنبيه:</strong> حوّل الرسوم حصراً إلى أحد الحسابات المعتمدة أدناه وبنفس المبلغ المحدد دون زيادة أو نقصان.</span>
                            </div>

                            {{-- العرض 1: الكبسولات البيضاوية لطرق الدفع --}}
                            <div id="spUnivPayMethodsWrap">
                                <div class="text-muted small mb-2" style="font-size:0.78rem;">
                                    <i class="bi bi-hand-index-thumb text-primary me-1"></i>انقر على وسيلة الدفع التي تود السداد عبرها:
                                </div>
                                <div class="sp-pay-methods-pills-wrap" id="spUnivPayMethodsContainer">
                                    <div class="text-center py-2 text-muted small w-100">جارٍ تحميل وسائل الدفع...</div>
                                </div>
                            </div>

                            {{-- العرض 2: تفاصيل طريقة الدفع عند الضغط على الكبسولة (في نفس المكان تماماً) --}}
                            <div id="spSelectedMethodDetailsBox" class="sp-selected-method-details-card d-none">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2 pb-2 border-bottom">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill fw-bold" id="spDetailMethodName">محفظة جيب</span>
                                        <span class="text-muted small fw-medium" id="spDetailAccountName">باسم: الجامعة</span>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-outline-secondary btn-sm px-2.5 py-0.5 rounded-pill fw-bold" style="font-size:0.75rem;" onclick="spBackToMethodSelection()" title="تغيير الطريقة أو الرجوع لاختيار وسيلة أخرى">
                                            <i class="bi bi-arrow-right me-1"></i>تغيير الطريقة
                                        </button>
                                    </div>
                                </div>

                                <div class="sp-account-card-box p-2.5 d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <div>
                                        <span class="text-muted d-block small mb-0.5" id="spDetailAccountTypeLabel" style="font-size:0.75rem;">رقم المحفظة / الحساب للتحويل:</span>
                                        <div class="fs-4 fw-bold font-monospace text-primary" id="spDetailAccountNum" dir="ltr">00000000</div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-primary btn-sm px-3 py-1.5 rounded-pill fw-bold shadow-sm" id="spBtnCopyAccount" onclick="spCopyActiveAccountAndAdvance()">
                                            <i class="bi bi-clipboard me-1"></i><span id="spBtnCopyText">نسخ رقم المحفظة</span>
                                        </button>
                                    </div>
                                </div>

                                {{-- تنبيه تأكيدي فوري ومختصر عند اختيار وسيلة الدفع --}}
                                <div class="alert alert-warning-subtle border border-warning-subtle py-1.5 px-3 small rounded-2 mt-2 mb-0 d-flex align-items-center gap-2">
                                    <i class="bi bi-exclamation-triangle-fill text-warning fs-6 flex-shrink-0"></i>
                                    <span class="small"><strong>تأكيد:</strong> يُعتمد السند فقط إذا حُوّل إلى نفس هذا الحساب المحدد.</span>
                                </div>

                                {{-- تنبيه نجاح النسخ والانتقال للتعليمة التالية --}}
                                <div class="alert alert-success py-1.5 px-3 small d-none mt-2 mb-0 rounded-3 border-success-subtle d-flex align-items-center gap-2" id="spCopySuccessMsg">
                                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                    <span><strong>تم النسخ بنجاح!</strong> يمكنك الآن الانتقال للتعليمة التالية لإتمام التحويل وتوثيق العملية.</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- =========================================================
                         التعليمة 2: قم بتحويل الرسوم الموضحة أعلاه
                         ========================================================= --}}
                    <div class="sp-pay-instruction-card mb-3 d-none" id="spPayInstructionStep2">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1.5">
                            <div class="d-flex align-items-center gap-2">
                                <span class="sp-instruction-step-num" id="spInstBadge2">٢</span>
                                <strong class="text-dark" style="font-size:0.88rem;">قم بتحويل الرسوم الموضحة أعلاه:</strong>
                            </div>
                            <div>
                                <span class="badge bg-success-subtle text-success border border-success-subtle font-monospace px-2.5 py-1 fw-bold fs-6">5,000 ر.ي</span>
                            </div>
                        </div>

                        {{-- الملاحظة المعتمدة تحت التعليمة --}}
                        <div class="alert alert-light border small text-muted py-2 px-3 rounded-2 mt-1 mb-0 d-flex align-items-start gap-2">
                            <i class="bi bi-info-circle text-primary fs-5 mt-0.5 flex-shrink-0"></i>
                            <div>
                                يفضل كتابة اسم الطالب أو رقم المعاملة في خانة غرض/بيان العملية لتسهيل مطابقة السداد.
                            </div>
                        </div>
                    </div>

                    {{-- =========================================================
                         التعليمة 3: وثق بيانات إتمام العملية
                         ========================================================= --}}
                    <div class="sp-pay-instruction-card mb-2 d-none" id="spPayInstructionStep3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="sp-instruction-step-num" id="spInstBadge3">٣</span>
                                <strong class="text-dark" style="font-size:0.88rem;">وثّق بيانات إتمام العملية:</strong>
                            </div>
                            <span class="badge bg-light text-muted border small py-1 px-2.5 rounded-pill" id="spInstStatus3" style="font-size:0.72rem;">
                                بيانات السند المعتمد
                            </span>
                        </div>

                        {{-- نموذج توثيق السند والبيانات المطلوبة --}}
                        <div class="sp-voucher-form-card p-3 shadow-sm rounded-3">
                            <div class="row g-2.5">
                                {{-- 1. وسيلة التحويل المعتمدة --}}
                                <div class="col-md-6 col-12">
                                    <label class="form-label small fw-bold text-dark mb-1">وسيلة التحويل المعتمدة <span class="text-danger">*</span></label>
                                    <div class="p-2 border rounded-2 bg-light d-flex align-items-center justify-content-between" style="min-height: 38px;">
                                        <div class="d-flex align-items-center gap-2 overflow-hidden">
                                            <i class="bi bi-shield-check text-success fs-5 flex-shrink-0"></i>
                                            <div class="text-truncate">
                                                <strong class="d-block text-dark small text-truncate" id="spProofMethodDisplay">لم يتم اختيار وسيلة بعد</strong>
                                                <span class="text-muted text-truncate d-block" style="font-size:0.72rem;" id="spProofAccountDisplay">اختر الوسيلة من التعليمة الأولى</span>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-outline-secondary btn-sm px-2 py-0.5 rounded-pill fw-bold flex-shrink-0" style="font-size:0.72rem;" onclick="spBackToMethodSelection()" title="تغيير وسيلة الدفع">
                                            <i class="bi bi-pencil-square me-1"></i>تغيير
                                        </button>
                                    </div>
                                    <input type="hidden" name="method_id" id="sp_method_id" value="" required>
                                </div>

                                {{-- 2. رقم العملية / الحوالة / السند --}}
                                <div class="col-md-6 col-12">
                                    <label for="sp_reference" class="form-label small fw-bold text-dark mb-1">رقم العملية / الحوالة / السند <span class="text-danger">*</span></label>
                                    <input type="text" name="reference" id="sp_reference" class="form-control form-control-sm font-monospace" placeholder="أدخل رقم العملية أو الإشعار" dir="ltr" required>
                                </div>

                                {{-- 3. تاريخ التحويل --}}
                                <div class="col-md-6 col-12">
                                    <label for="sp_pay_date" class="form-label small fw-bold text-dark mb-1">تاريخ التحويل <span class="text-danger">*</span></label>
                                    <input type="date" name="pay_date" id="sp_pay_date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                                </div>

                                {{-- 4. ملاحظات إضافية (اختياري) --}}
                                <div class="col-md-6 col-12">
                                    <label for="sp_notes" class="form-label small text-muted mb-1">ملاحظات إضافية (اختياري)</label>
                                    <input type="text" name="notes" id="sp_notes" class="form-control form-control-sm" placeholder="اسم المودع أو أي تفاصيل إضافية">
                                </div>

                                {{-- 5. صورة إشعار أو سند السداد --}}
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-dark mb-1">صورة إشعار أو سند السداد <span class="text-danger">*</span></label>
                                    <div class="sp-upload-voucher-box p-2.5 d-flex align-items-center justify-content-between flex-wrap gap-2" id="sp_card_payment_proof">
                                        <input type="file" name="proof_file" id="sp_proof_file" class="d-none" accept=".pdf,.jpg,.jpeg,.png" required onchange="onSpFilePicked(this)">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-file-earmark-arrow-up text-primary fs-5"></i>
                                            <span class="small text-muted" id="sp_status_payment_proof">لم يتم اختيار صورة السند</span>
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm py-1 px-3 fw-bold rounded-pill" onclick="document.getElementById('sp_proof_file').click();">
                                            <i class="bi bi-upload me-1"></i>اختيار الملف
                                        </button>
                                    </div>
                                    <small class="text-muted d-block mt-1" style="font-size:0.72rem;">الصيغ المقبولة: JPG, PNG, PDF (الحد الأقصى 5MB)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3.5 py-1.5 fw-bold rounded-pill" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>إلغاء
                    </button>
                    <button type="submit" class="btn btn-success btn-sm px-4 py-1.5 fw-bold rounded-pill shadow-sm" id="btnSubmitPaymentModal">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i>رفع وتأكيد السند
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: معاينة سند السداد --}}
<div class="modal fade" id="receiptPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header bg-dark text-white py-2 px-3">
                <h6 class="modal-title mb-0 small"><i class="bi bi-file-earmark-image me-1"></i>معاينة سند السداد: <span id="rpReferenceText" class="font-monospace"></span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-2 text-center bg-light" id="rpModalBody" style="min-height:300px; max-height:80vh; overflow:auto;">
            </div>
            <div class="modal-footer py-1.5 px-3 bg-white justify-content-between">
                <a href="#" id="rpDownloadBtn" download class="btn btn-sm btn-primary py-1 px-3">
                    <i class="bi bi-download me-1"></i>تنزيل الملف
                </a>
                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-3" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // التبويب النشط الحالي على الشاشات الكبيرة
    let currentDesktopTab = '{{ $activeTab }}';
    const allTabKeys = ['profile', 'requests', 'referral', 'notifications', 'appearance', 'security'];

    // فحص وتطبيق حالة قراءة الإشعارات
    function checkNotificationsReadState() {
        if (localStorage.getItem('student_notifications_read') === 'true') {
            const badgeDesktop = document.getElementById('notifBadgeDesktop');
            const badgeMobile = document.getElementById('notifBadgeMobile');
            if (badgeDesktop) badgeDesktop.style.display = 'none';
            if (badgeMobile) badgeMobile.style.display = 'none';
        }
    }

    // إخفاء شارة الإشعارات عند فتح التبويب وتسجيلها كمقروءة
    function markNotificationsAsRead() {
        localStorage.setItem('student_notifications_read', 'true');
        const badgeDesktop = document.getElementById('notifBadgeDesktop');
        const badgeMobile = document.getElementById('notifBadgeMobile');
        if (badgeDesktop) badgeDesktop.style.display = 'none';
        if (badgeMobile) badgeMobile.style.display = 'none';
    }

    // تنظيم وتوزيع الحاويات بحسب حجم الشاشة (هاتف مقابل ديسكتوب)
    function organizeResponsivePanes() {
        const isMobile = window.innerWidth < 992;
        const desktopContainer = document.getElementById('desktopPaneContainer');

        allTabKeys.forEach(key => {
            const pane = document.getElementById('pane_' + key);
            if (!pane) return;

            if (isMobile) {
                const mobileSlot = document.getElementById('slot_mobile_' + key);
                if (mobileSlot && pane.parentElement !== mobileSlot) {
                    mobileSlot.appendChild(pane);
                    pane.style.display = 'block'; // ظهور الحاوية داخل فتحة الأكورديون
                }
            } else {
                if (desktopContainer && pane.parentElement !== desktopContainer) {
                    desktopContainer.appendChild(pane);
                }
                // في الشاشات الكبيرة: عرض التبويب النشط فقط
                pane.style.display = (key === currentDesktopTab) ? 'block' : 'none';
            }
        });
    }

    // تبديل التبويب في الشاشات الكبيرة (Desktop)
    function switchDesktopTab(tabName) {
        currentDesktopTab = tabName;
        document.querySelectorAll('.student-vnav-item').forEach(b => b.classList.remove('active'));

        const activeBtn = Array.from(document.querySelectorAll('.student-vnav-item')).find(b => b.getAttribute('onclick') && b.getAttribute('onclick').includes(tabName));
        if (activeBtn) activeBtn.classList.add('active');

        allTabKeys.forEach(key => {
            const pane = document.getElementById('pane_' + key);
            if (pane) {
                pane.style.display = (key === tabName) ? 'block' : 'none';
            }
        });

        // إذا تم فتح تبويب الإشعارات، تمييزها كمقروءة وإخفاء الرقم
        if (tabName === 'notifications') {
            markNotificationsAsRead();
        }

        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        window.history.replaceState({}, '', url);
    }

    // فتح وإغلاق القوائم المنسدلة في شاشة الهاتف (Mobile Accordion)
    function toggleMobileAccordion(sectionKey) {
        const collapseEl = document.getElementById('mobileCollapse_' + sectionKey);
        const cardEl = document.getElementById('mobileCard_' + sectionKey);
        if (!collapseEl || !cardEl) return;

        const isCurrentlyOpen = (collapseEl.style.display !== 'none');

        if (isCurrentlyOpen) {
            collapseEl.style.display = 'none';
            cardEl.classList.remove('open');
        } else {
            collapseEl.style.display = 'block';
            cardEl.classList.add('open');

            // إذا فتح الطالب قائمة الإشعارات في الهاتف، إخفاء الرقم فورياً
            if (sectionKey === 'notifications') {
                markNotificationsAsRead();
            }

            // تمرير خفيف ومريح لعرض القائمة المفتوحة إن لزم
            setTimeout(() => {
                cardEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 50);
        }
    }

    // نسخ رمز إحالة محدد لجامعة
    function copySpecificReferralCode(elementId) {
        const codeEl = document.getElementById(elementId);
        if (!codeEl) return;
        const code = codeEl.textContent.trim();

        navigator.clipboard.writeText(code).then(() => {
            alert('تم نسخ رمز الإحالة بنجاح: ' + code);
        }).catch(err => {
            prompt('انسخ رمز الإحالة:', code);
        });
    }

    // تغيير وتحديد المظهر (ليلي / ضوئي / تلقائي)
    function selectStudentTheme(mode) {
        document.querySelectorAll('.theme-select-card').forEach(c => c.classList.remove('active'));
        const activeCard = document.getElementById('themeCard_' + mode);
        if (activeCard) activeCard.classList.add('active');

        window.applyEduYemenTheme(mode);

        const badge = document.getElementById('activeThemeTextBadge');
        if (badge) {
            badge.textContent = mode === 'dark' ? 'الوضع الليلي' : 'الوضع النهاري';
        }

        fetch('{{ route("student.theme.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ theme_mode: mode })
        }).catch(err => console.log('Theme saved locally'));
    }

    // اختيار نغمة التنبيه المفضلة
    function selectNotificationTone(tone) {
        document.querySelectorAll('.tone-select-item').forEach(item => item.classList.remove('selected'));
        const selectedRadio = document.querySelector(`input[name="toneRadio"][value="${tone}"]`);
        if (selectedRadio) {
            selectedRadio.checked = true;
            selectedRadio.closest('.tone-select-item').classList.add('selected');
        }

        playToneSound(tone);
        saveNotificationSettings(tone);
    }

    // حفظ تفضيلات الإشعارات (كتم / صوت / نغمة)
    function saveNotificationSettings(customTone) {
        const isMuted = document.getElementById('notificationMuteToggle').checked;
        const hasSound = document.getElementById('notificationSoundToggle').checked;
        const selectedRadio = document.querySelector('input[name="toneRadio"]:checked');
        const tone = customTone || (selectedRadio ? selectedRadio.value : 'chime');

        fetch('{{ route("student.notifications.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                notifications_muted: isMuted ? 1 : 0,
                notification_sound: hasSound ? 1 : 0,
                notification_tone: tone
            })
        });
    }

    // تشغيل نغمات الإشعارات المتعددة باستخدام Web Audio API
    function playToneSound(tone) {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const now = ctx.currentTime;

            if (tone === 'bell') {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(659.25, now);
                gain.gain.setValueAtTime(0.2, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.8);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(now);
                osc.stop(now + 0.8);
            } else if (tone === 'chord') {
                [523.25, 659.25, 783.99].forEach((freq, idx) => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'triangle';
                    osc.frequency.setValueAtTime(freq, now + (idx * 0.08));
                    gain.gain.setValueAtTime(0.14, now + (idx * 0.08));
                    gain.gain.exponentialRampToValueAtTime(0.001, now + (idx * 0.08) + 0.5);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start(now + (idx * 0.08));
                    osc.stop(now + (idx * 0.08) + 0.5);
                });
            } else if (tone === 'pluck') {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(440, now);
                osc.frequency.exponentialRampToValueAtTime(880, now + 0.15);
                gain.gain.setValueAtTime(0.22, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.35);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(now);
                osc.stop(now + 0.35);
            } else {
                const osc1 = ctx.createOscillator();
                const osc2 = ctx.createOscillator();
                const gain = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(587.33, now);
                osc1.frequency.setValueAtTime(880, now + 0.12);
                gain.gain.setValueAtTime(0.18, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.55);
                osc1.connect(gain);
                gain.connect(ctx.destination);
                osc1.start(now);
                osc1.stop(now + 0.55);
            }
        } catch (e) {
            console.log('Audio playback not supported');
        }
    }

    // إلغاء طلب تسجيل من قِبل الطالب مع فحص الضوابط والتأكيد
    function cancelStudentRequest(reqId, specName, univName) {
        if (window.Swal) {
            Swal.fire({
                title: 'تأكيد إلغاء الطلب',
                html: `
                    <p class="mb-2">هل أنت متأكد من رغبتك في إلغاء طلب الالتحاق بتخصص <strong>"${specName}"</strong> لدى <strong>${univName}</strong>؟</p>
                    <div class="alert alert-warning text-start small mb-0 py-2 px-3 border border-warning-subtle" style="font-size:0.8rem; line-height:1.5;">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        <strong>ضوابط المنصة:</strong> في حال إلغاء هذا الطلب، ستتمكن من التقديم على طلب بديل لنفس الجامعة، ولكن <strong>لن تتمكن من إلغاء الطلب الثاني</strong> إذا تم تقديمه خلال 24 ساعة.
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="bi bi-trash3-fill me-1"></i>نعم، إلغاء الطلب الآن',
                cancelButtonText: 'تراجع وإبقاء الطلب'
            }).then((result) => {
                if (result.isConfirmed) {
                    executeCancelRequest(reqId);
                }
            });
        } else {
            if (confirm(`هل أنت متأكد من رغبتك في إلغاء طلب الالتحاق بتخصص "${specName}" لدى ${univName}؟\nتنبيه: لن تتمكن من إلغاء الطلب الثاني البديل في حال تقديمه خلال 24 ساعة.`)) {
                executeCancelRequest(reqId);
            }
        }
    }

    function executeCancelRequest(reqId) {
        fetch(`/student/requests/${reqId}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(async res => {
            const data = await res.json().catch(() => ({}));
            if (!res.ok) {
                throw new Error(data.message || 'حدث خطأ أثناء محاولة إلغاء الطلب.');
            }
            return data;
        })
        .then(data => {
            if (window.Swal) {
                Swal.fire({
                    icon: 'success',
                    title: 'تم إلغاء الطلب بنجاح',
                    text: data.message || 'تم إلغاء طلب التسجيل بنجاح. يمكنك الآن التقديم على تخصص آخر بديل.',
                    confirmButtonColor: '#1e3a8a',
                    confirmButtonText: 'حسناً'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                alert(data.message || 'تم إلغاء طلب التسجيل بنجاح.');
                window.location.reload();
            }
        })
        .catch(err => {
            if (window.Swal) {
                Swal.fire({
                    icon: 'error',
                    title: 'تعذر إلغاء الطلب',
                    text: err.message,
                    confirmButtonColor: '#1e3a8a'
                });
            } else {
                alert(err.message);
            }
        });
    }

    // التهيئة عند تحميل المستند ومراقبة تغيير حجم الشاشة
    document.addEventListener('DOMContentLoaded', function() {
        organizeResponsivePanes();
        checkNotificationsReadState();

        // منع تأثر أو تمرير الصفحة الخلفية نهائياً عند فتح نافذة سداد الرسوم
        const paymentModalEl = document.getElementById('studentPaymentModal');
        if (paymentModalEl) {
            paymentModalEl.addEventListener('show.bs.modal', function () {
                document.documentElement.classList.add('modal-open');
                document.body.classList.add('modal-open');
                document.body.style.overflow = 'hidden';
                document.documentElement.style.overflow = 'hidden';
            });
            paymentModalEl.addEventListener('hidden.bs.modal', function () {
                document.documentElement.classList.remove('modal-open');
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.documentElement.style.overflow = '';
            });
            paymentModalEl.addEventListener('wheel', function(e) {
                const modalBody = paymentModalEl.querySelector('.modal-body');
                if (modalBody) {
                    const isInside = modalBody.contains(e.target) || e.target === modalBody;
                    if (!isInside) {
                        e.preventDefault();
                    } else {
                        // منع تسرب التمرير (scroll chaining) إلى خلفية الصفحة عند الوصول لأعلى أو أسفل الحاوية
                        const scrollTop = modalBody.scrollTop;
                        const scrollHeight = modalBody.scrollHeight;
                        const height = modalBody.clientHeight;
                        const delta = e.deltaY;
                        if ((delta > 0 && scrollTop + height >= scrollHeight - 1) || (delta < 0 && scrollTop <= 0)) {
                            e.preventDefault();
                        }
                    }
                }
            }, { passive: false });
        }

        // في الشاشة الصغيرة: تكون القوائم مغلقة مبدئياً إلا إذا كان هناك تبويب مطلوب فتحه أو رسالة نجاح/أخطاء
        if (window.innerWidth < 992) {
            allTabKeys.forEach(key => {
                const collapseEl = document.getElementById('mobileCollapse_' + key);
                const cardEl = document.getElementById('mobileCard_' + key);
                @if(request('tab') || session('success') || $errors->any())
                    const shouldOpen = (key === '{{ $activeTab }}');
                    if (collapseEl) collapseEl.style.display = shouldOpen ? 'block' : 'none';
                    if (cardEl) {
                        if (shouldOpen) cardEl.classList.add('open');
                        else cardEl.classList.remove('open');
                    }
                @else
                    if (collapseEl) collapseEl.style.display = 'none';
                    if (cardEl) cardEl.classList.remove('open');
                @endif
            });
        }
    });

    window.addEventListener('resize', function() {
        organizeResponsivePanes();
    });

    // متغيرات إدارة سداد الرسوم في ملف الطالب
    let currentSpUnivMethods = [];
    let currentSpSelectedMethodId = null;
    let currentSpActiveAccountNum = '';

    function getSpShortMethodName(name) {
        if (!name) return 'حساب معتمد';
        if (name.includes('كريمي')) return 'بنك الكريمي';
        if (name.includes('ون كاش') || name.includes('one cash') || name.includes('onecash')) return 'محفظة ون كاش';
        if (name.includes('تضامن')) return 'بنك التضامن';
        if (name.includes('جيب')) return 'محفظة جيب';
        if (name.includes('جوالي')) return 'محفظة جوالي';
        if (name.includes('موبايل')) return 'موبايل موني';
        if (name.includes('كويت')) return 'بنك اليمن والكويت';
        return name;
    }

    // فتح نافذة سداد الرسوم للطلب وتوحيدها بالكامل مع شاشة التقديم الأساسية
    function openStudentPaymentModal(reqId, univName, specName, univId) {
        document.getElementById('spModalReqId').value = reqId;
        document.getElementById('spModalSubtitle').textContent = `الطلب #${reqId} لدى ${univName} (${specName})`;
        document.getElementById('spModalSpecNameDisplay').textContent = `${specName} - ${univName}`;

        // إعادة ضبط الحقول والحالات
        currentSpUnivMethods = [];
        currentSpSelectedMethodId = null;
        currentSpActiveAccountNum = '';

        document.getElementById('sp_method_id').value = '';
        document.getElementById('sp_reference').value = '';
        document.getElementById('sp_notes').value = '';
        document.getElementById('sp_proof_file').value = '';
        const statusEl = document.getElementById('sp_status_payment_proof');
        if (statusEl) {
            statusEl.textContent = 'لم يتم اختيار صورة السند';
            statusEl.classList.remove('text-success', 'fw-bold');
        }

        document.getElementById('spProofMethodDisplay').textContent = 'لم يتم اختيار وسيلة بعد';
        document.getElementById('spProofAccountDisplay').textContent = 'اختر الوسيلة من التعليمة الأولى';

        document.getElementById('spUnivPayMethodsWrap').classList.remove('d-none');
        document.getElementById('spSelectedMethodDetailsBox').classList.add('d-none');
        document.getElementById('spCopySuccessMsg').classList.add('d-none');

        document.getElementById('spPayInstructionStep2').classList.add('d-none');
        document.getElementById('spPayInstructionStep3').classList.add('d-none');

        // إعادة ضبط وسم الخطوة 1
        const instBadge1 = document.getElementById('spInstBadge1');
        const instStatus1 = document.getElementById('spInstStatus1');
        if (instBadge1) {
            instBadge1.textContent = '١';
            instBadge1.classList.remove('completed');
        }
        if (instStatus1) {
            instStatus1.textContent = 'الخطوة الأولى';
            instStatus1.className = 'badge bg-light text-muted border small py-1 px-2.5 rounded-pill';
        }

        const pillsContainer = document.getElementById('spUnivPayMethodsContainer');
        pillsContainer.innerHTML = '<div class="text-center py-2 text-muted small w-100"><span class="spinner-border spinner-border-sm me-1"></span> جارٍ تحميل وسائل الدفع...</div>';

        // جلب وسائل الدفع الخاصة بالجامعة
        fetch(`/api/universities/${univId}/payment-methods`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(res => {
            let methods = (res.success && res.payment_methods) ? res.payment_methods : [];
            if (methods.length === 0) {
                methods = [
                    { method_id: 1, method_name: 'بنك الكريمي', account_number: '300124589', method_type: 'حساب بنكي', account_name: 'حساب تحصيل - ' + univName },
                    { method_id: 2, method_name: 'محفظة ون كاش', account_number: '777000111', method_type: 'محفظة إلكترونية', account_name: 'حساب رسوم - ' + univName },
                    { method_id: 3, method_name: 'بنك التضامن', account_number: '102938475', method_type: 'حساب بنكي', account_name: 'إدارة القبول والتسجيل - ' + univName },
                    { method_id: 4, method_name: 'محفظة جيب', account_number: '711223344', method_type: 'محفظة إلكترونية', account_name: 'رسوم التنسيق - ' + univName }
                ];
            }
            currentSpUnivMethods = methods;
            pillsContainer.innerHTML = '';

            currentSpUnivMethods.forEach(pm => {
                const fullName = (pm.method_name && pm.method_name.trim()) ? pm.method_name : (pm.method_type || (pm.account_name ? pm.account_name.split('-')[0].trim() : 'حساب بنكي معتمد'));
                const shortName = getSpShortMethodName(fullName);

                let iconClass = 'bi-bank';
                const nameLower = fullName.toLowerCase();
                if (nameLower.includes('محفظ') || nameLower.includes('كاش') || nameLower.includes('جوالي') || nameLower.includes('جيب') || nameLower.includes('موبايل')) {
                    iconClass = 'bi-wallet2';
                }

                const pill = document.createElement('button');
                pill.type = 'button';
                pill.className = 'sp-pay-method-pill';
                pill.id = 'spPayMethodPill_' + pm.method_id;
                pill.innerHTML = `<i class="bi ${iconClass}"></i><span>${escapeHtml(shortName)}</span>`;
                pill.onclick = function() {
                    spSelectPaymentMethod(pm.method_id);
                };
                pillsContainer.appendChild(pill);
            });
        })
        .catch(() => {
            pillsContainer.innerHTML = '<div class="text-danger small text-center py-2 w-100">تعذر تحميل بيانات وسائل الدفع.</div>';
        });

        const modalEl = document.getElementById('studentPaymentModal');
        if (window.bootstrap && bootstrap.Modal) {
            const m = bootstrap.Modal.getOrCreateInstance(modalEl);
            m.show();
        } else {
            $(modalEl).modal('show');
        }
    }

    // عند النقر على إحدى كبسولات طرق الدفع
    function spSelectPaymentMethod(methodId) {
        const pm = currentSpUnivMethods.find(m => String(m.method_id) === String(methodId));
        if (!pm) return;

        currentSpSelectedMethodId = pm.method_id;
        currentSpActiveAccountNum = pm.account_number || '';

        // تفعيل الكبسولة
        document.querySelectorAll('.sp-pay-method-pill').forEach(btn => btn.classList.remove('active'));
        const activeBtn = document.getElementById('spPayMethodPill_' + methodId);
        if (activeBtn) activeBtn.classList.add('active');

        // التبديل في نفس المكان لعرض التفاصيل
        document.getElementById('spUnivPayMethodsWrap').classList.add('d-none');
        document.getElementById('spSelectedMethodDetailsBox').classList.remove('d-none');

        const fullName = (pm.method_name && pm.method_name.trim()) ? pm.method_name : (pm.method_type || (pm.account_name ? pm.account_name.split('-')[0].trim() : 'حساب بنكي معتمد'));
        const isWallet = fullName.toLowerCase().includes('محفظ') || fullName.toLowerCase().includes('كاش') || fullName.toLowerCase().includes('جيب') || fullName.toLowerCase().includes('جوالي');

        document.getElementById('spDetailMethodName').textContent = fullName;
        document.getElementById('spDetailAccountName').textContent = 'باسم: ' + (pm.account_name || 'الجامعة');
        document.getElementById('spDetailAccountNum').textContent = pm.account_number;
        document.getElementById('spDetailAccountTypeLabel').textContent = isWallet ? 'رقم المحفظة المعتمد للتحويل:' : 'رقم الحساب البنكي المعتمد للتحويل:';
        document.getElementById('spBtnCopyText').textContent = isWallet ? 'نسخ رقم المحفظة' : 'نسخ رقم الحساب';

        // ربط الحقل وبطاقة العرض في التعليمة 3
        document.getElementById('sp_method_id').value = pm.method_id;
        document.getElementById('spProofMethodDisplay').textContent = fullName;
        document.getElementById('spProofAccountDisplay').textContent = `رقم الحساب: ${pm.account_number} (${pm.account_name || 'حساب رسمي معتمد'})`;

        document.getElementById('spCopySuccessMsg').classList.add('d-none');

        // إظهار التعليمتين 2 و 3
        document.getElementById('spPayInstructionStep2').classList.remove('d-none');
        document.getElementById('spPayInstructionStep3').classList.remove('d-none');
    }

    // الرجوع من تفاصيل الحساب إلى عرض قائمة الكبسولات
    function spBackToMethodSelection() {
        document.getElementById('spSelectedMethodDetailsBox').classList.add('d-none');
        const wrap = document.getElementById('spUnivPayMethodsWrap');
        wrap.classList.remove('d-none');
        wrap.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    // نسخ رقم الحساب والانتقال للخطوات التالية
    function spCopyActiveAccountAndAdvance() {
        if (!currentSpActiveAccountNum) return;

        const btn = document.getElementById('spBtnCopyAccount');
        const btnText = document.getElementById('spBtnCopyText');
        const origText = btnText ? btnText.textContent : 'نسخ الرقم';

        const notifySuccess = () => {
            if (btn) {
                btn.classList.add('btn-success', 'text-white');
                btn.classList.remove('btn-primary');
                if (btnText) btnText.textContent = 'تم النسخ بنجاح!';
                setTimeout(() => {
                    btn.classList.remove('btn-success', 'text-white');
                    btn.classList.add('btn-primary');
                    if (btnText) btnText.textContent = origText;
                }, 2500);
            }

            const copyMsg = document.getElementById('spCopySuccessMsg');
            if (copyMsg) copyMsg.classList.remove('d-none');

            // تمييز التعليمة 1 كمكتملة
            const instBadge1 = document.getElementById('spInstBadge1');
            const instStatus1 = document.getElementById('spInstStatus1');
            if (instBadge1) {
                instBadge1.innerHTML = '<i class="bi bi-check-lg"></i>';
                instBadge1.classList.add('completed');
            }
            if (instStatus1) {
                instStatus1.textContent = 'تم تحديد الحساب والنسخ';
                instStatus1.className = 'badge bg-success-subtle text-success border border-success-subtle small py-1 px-2.5 rounded-pill';
            }

            // إظهار التعليمة 2 والتعليمة 3 بسلاسة
            const step2 = document.getElementById('spPayInstructionStep2');
            const step3 = document.getElementById('spPayInstructionStep3');
            if (step2) step2.classList.remove('d-none');
            if (step3) {
                step3.classList.remove('d-none');
                step3.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        };

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(currentSpActiveAccountNum).then(notifySuccess).catch(() => {
                fallbackSpCopy(currentSpActiveAccountNum, notifySuccess);
            });
        } else {
            fallbackSpCopy(currentSpActiveAccountNum, notifySuccess);
        }
    }

    function fallbackSpCopy(text, cb) {
        const tempInput = document.createElement('input');
        tempInput.style.position = 'absolute';
        tempInput.style.left = '-9999px';
        tempInput.value = text;
        document.body.appendChild(tempInput);
        tempInput.select();
        try {
            document.execCommand('copy');
            if (cb) cb();
        } catch (e) {
            alert('رقم الحساب: ' + text);
        }
        document.body.removeChild(tempInput);
    }

    // عند اختيار ملف السند
    function onSpFilePicked(input) {
        const statusEl = document.getElementById('sp_status_payment_proof');
        if (input.files && input.files.length > 0) {
            const file = input.files[0];
            const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
            if (file.size > 5 * 1024 * 1024) {
                alert('حجم الملف كبير جداً (' + sizeMB + 'MB). الحد الأقصى المسموح 5MB.');
                input.value = '';
                statusEl.textContent = 'لم يتم اختيار صورة السند';
                statusEl.classList.remove('text-success', 'fw-bold');
                return;
            }
            statusEl.textContent = `✓ تم اختيار: ${file.name} (${sizeMB} MB)`;
            statusEl.classList.add('text-success', 'fw-bold');
        } else {
            statusEl.textContent = 'لم يتم اختيار صورة السند';
            statusEl.classList.remove('text-success', 'fw-bold');
        }
    }

    // رفع سند السداد وتأكيد الدفع
    function submitStudentPaymentProof(e) {
        e.preventDefault();
        const reqId = document.getElementById('spModalReqId').value;
        const form = document.getElementById('studentPaymentForm');
        const submitBtn = document.getElementById('btnSubmitPaymentModal');
        const origText = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جارٍ الرفع والتوثيق...';

        const formData = new FormData(form);

        fetch(`/student/requests/${reqId}/payment`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(async r => {
            const data = await r.json().catch(() => ({}));
            if (!r.ok) throw new Error(data.message || 'فشل رفع سند السداد.');
            return data;
        })
        .then(data => {
            const modalEl = document.getElementById('studentPaymentModal');
            if (window.bootstrap && bootstrap.Modal) {
                const m = bootstrap.Modal.getInstance(modalEl);
                if (m) m.hide();
            } else {
                $(modalEl).modal('hide');
            }

            if (window.Swal) {
                Swal.fire({
                    icon: 'success',
                    title: 'تم رفع سند السداد بنجاح!',
                    text: data.message || 'تم تسجيل بيانات وسند السداد المالي بنجاح، وسيتم تدقيقه من الإدارة المالية للجامعة.',
                    confirmButtonColor: '#1e3a8a',
                    confirmButtonText: 'حسناً'
                }).then(() => location.reload());
            } else {
                alert(data.message || 'تم رفع سند السداد بنجاح.');
                location.reload();
            }
        })
        .catch(err => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = origText;
            if (window.Swal) {
                Swal.fire({ icon: 'error', title: 'خطأ', text: err.message, confirmButtonColor: '#1e3a8a' });
            } else {
                alert(err.message);
            }
        });
    }

    // معاينة سند السداد
    function viewPaymentReceipt(url, reference) {
        const body = document.getElementById('rpModalBody');
        const refText = document.getElementById('rpReferenceText');
        const dlBtn = document.getElementById('rpDownloadBtn');

        if (refText) refText.textContent = reference ? `#${reference}` : '';
        if (dlBtn) dlBtn.href = url;

        const ext = url.split('.').pop().toLowerCase();
        if (ext === 'pdf') {
            body.innerHTML = `<iframe src="${url}" style="width:100%; height:500px; border:none; border-radius:8px;"></iframe>`;
        } else {
            body.innerHTML = `<img src="${url}" class="img-fluid rounded shadow-sm" style="max-height:550px; object-fit:contain;" alt="سند السداد">`;
        }

        const modalEl = document.getElementById('receiptPreviewModal');
        if (window.bootstrap && bootstrap.Modal) {
            const m = bootstrap.Modal.getOrCreateInstance(modalEl);
            m.show();
        } else {
            $(modalEl).modal('show');
        }
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/[&<>"']/g, function(m) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
        });
    }

</script>
@endsection
