@extends('dashboards.university.layout')

@section('page_title', 'معالج إنشاء إعلان ترويجي جديد')

@push('styles')
<style>
    /* تطبيق الخط الطبيعي لجميع عناصر معالج الإعلانات */
    .ad-wizard-container,
    .ad-wizard-container *,
    .wizard-step-pane,
    .wizard-step-pane *,
    .package-plan-card,
    .package-plan-card *,
    .payment-method-card,
    .payment-method-card *,
    .account-copy-modal-dialog,
    .account-copy-modal-dialog *,
    .stage-focus-card,
    .stage-focus-card *,
    .completed-field-card,
    .completed-field-card *,
    .ad-student-mock-card,
    .ad-student-mock-card *,
    #previewSimModal,
    #previewSimModal *,
    button, input, select, textarea {
        font-family: 'Almarai', 'IBM Plex Sans Arabic', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif !important;
    }

    /* Wizard Steps Bar */
    .wizard-stepper-wrap {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        margin: 0 auto 36px;
        max-width: 820px;
        padding: 0 10px;
    }
    .wizard-stepper-wrap::before {
        content: '';
        position: absolute;
        top: 24px;
        right: 40px;
        left: 40px;
        height: 3px;
        background: #e2e8f0;
        z-index: 1;
    }
    .stepper-progress-line {
        position: absolute;
        top: 24px;
        right: 40px;
        height: 3px;
        background: linear-gradient(90deg, var(--teal), var(--gold));
        z-index: 1;
        transition: width 0.4s ease;
    }
    .step-item {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    .step-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #cbd5e1;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }
    .step-item.active .step-circle {
        border-color: var(--teal);
        background: var(--teal);
        color: #fff;
        box-shadow: 0 4px 14px rgba(43, 138, 133, 0.35);
        transform: scale(1.06);
    }
    .step-item.completed .step-circle {
        border-color: #10b981;
        background: #10b981;
        color: #fff;
    }
    .step-label {
        font-size: 12.5px;
        font-weight: 700;
        color: #64748b;
        transition: color 0.3s ease;
        text-align: center;
        white-space: nowrap;
    }
    .step-item.active .step-label {
        color: var(--navy);
        font-weight: 800;
    }
    .step-item.completed .step-label {
        color: #10b981;
    }

    /* Packages Cards */
    .packages-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        margin-bottom: 30px;
    }
    @media(max-width: 992px) {
        .packages-grid { grid-template-columns: 1fr; }
    }
    .package-plan-card {
        background: #fff;
        border: 2px solid #e2e8f0;
        border-radius: var(--radius-lg);
        padding: 28px 24px;
        display: flex;
        flex-direction: column;
        position: relative;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
    }
    .package-plan-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        border-color: #cbd5e1;
    }
    .package-plan-card.selected {
        border-color: var(--teal);
        box-shadow: 0 8px 26px rgba(43, 138, 133, 0.2);
    }
    .package-plan-card.featured {
        border-color: #eab308;
        background: linear-gradient(180deg, #fffdfa, #ffffff);
        box-shadow: 0 6px 20px rgba(234, 179, 8, 0.15);
    }
    .package-plan-card.featured.selected {
        border-color: #d97706;
        box-shadow: 0 10px 30px rgba(217, 119, 6, 0.25);
    }
    .featured-badge-pill {
        position: absolute;
        top: -12px;
        right: 24px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        font-size: 11px;
        font-weight: 800;
        padding: 4px 12px;
        border-radius: 20px;
        box-shadow: 0 2px 8px rgba(217, 119, 6, 0.3);
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .package-header-sec {
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    .package-title-text {
        font-size: 19px;
        font-weight: 800;
        color: var(--navy);
        margin-bottom: 6px;
    }
    .package-price-wrap {
        display: flex;
        align-items: baseline;
        gap: 6px;
        margin-top: 10px;
    }
    .package-price-val {
        font-size: 28px;
        font-weight: 900;
        color: var(--navy);
    }
    .package-currency {
        font-size: 13px;
        color: var(--ink-muted);
        font-weight: 700;
    }
    .package-duration-tag {
        font-size: 11.5px;
        color: var(--teal);
        font-weight: 700;
        background: var(--teal-light);
        padding: 2px 8px;
        border-radius: 12px;
        margin-right: auto;
    }
    .package-features-list {
        list-style: none;
        padding: 0;
        margin: 0 0 24px;
        display: flex;
        flex-direction: column;
        gap: 11px;
        flex-grow: 1;
    }
    .package-features-list li {
        font-size: 13px;
        color: var(--ink);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .package-features-list li i.check-icon {
        color: #10b981;
        font-size: 13px;
        flex-shrink: 0;
    }
    .package-features-list li i.x-icon {
        color: #cbd5e1;
        font-size: 13px;
        flex-shrink: 0;
    }
    .visibility-meter-box {
        background: #f8fafc;
        border-radius: 8px;
        padding: 8px 12px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 11.5px;
        color: var(--ink-muted);
        font-weight: 600;
    }

    /* Accounts Box */
    .bank-account-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        transition: all 0.2s ease;
    }
    .bank-account-card:hover {
        border-color: var(--teal);
        background: #fbfdfd;
    }

    /* Drag & Drop Upload Zone */
    .upload-dropzone {
        border: 2px dashed #cbd5e1;
        border-radius: var(--radius-md);
        padding: 30px 20px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .upload-dropzone:hover, .upload-dropzone.dragover {
        border-color: var(--teal);
        background: var(--teal-light);
    }

    /* Preview Mock Card */
    .ad-student-mock-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(16,42,67,0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        max-width: 480px;
        margin: 0 auto;
    }
    .mock-media-wrap {
        width: 100%;
        height: 220px;
        background: #102a43;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .mock-media-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .mock-sponsor-tag {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(10,27,42,0.85);
        color: #e4b75b;
        font-size: 10.5px;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 14px;
        backdrop-filter: blur(4px);
    }

    /* Square Payment Method Cards (Single-Select) */
    .payment-methods-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 768px) {
        .payment-methods-grid {
            grid-template-columns: 1fr;
        }
    }
    .payment-method-card {
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 160px;
    }
    .payment-method-card:hover {
        transform: translateY(-3px);
        border-color: #cbd5e1;
        box-shadow: 0 8px 24px rgba(16, 42, 67, 0.08);
    }
    .payment-method-card.selected {
        border-color: var(--teal);
        background: #f0fdfa;
        box-shadow: 0 8px 26px rgba(43, 138, 133, 0.18);
        transform: translateY(-2px);
    }
    .payment-method-card .method-icon-wrap {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 12px;
        transition: transform 0.25s ease;
    }
    .payment-method-card:hover .method-icon-wrap {
        transform: scale(1.08);
    }
    .payment-method-card .select-radio-pill {
        position: absolute;
        top: 14px;
        left: 14px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        color: #fff;
        background: #fff;
        transition: all 0.2s ease;
    }
    .payment-method-card.selected .select-radio-pill {
        border-color: var(--teal);
        background: var(--teal);
    }

    /* Account Copy Modal */
    .account-copy-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(10, 27, 42, 0.72);
        backdrop-filter: blur(5px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .account-copy-modal-dialog {
        background: #ffffff;
        border-radius: 20px;
        width: 100%;
        max-width: 540px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
        border: 1px solid var(--border);
        overflow: hidden;
        animation: modalScaleIn 0.28s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes modalScaleIn {
        from { opacity: 0; transform: scale(0.93) translateY(14px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    /* Progressive Interactive Stages for Step 3 */
    .fade-slide-up {
        animation: fadeSlideUp 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(18px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .stage-focus-card {
        max-width: 720px;
        margin: 16px auto 10px;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 18px;
        padding: 34px 38px;
        box-shadow: 0 10px 32px rgba(16, 42, 67, 0.07);
        text-align: center;
        transition: all 0.3s ease;
    }
    @media (max-width: 640px) {
        .stage-focus-card {
            padding: 24px 18px;
        }
    }
    .stage-focus-card:focus-within {
        border-color: var(--teal);
        box-shadow: 0 12px 36px rgba(43, 138, 133, 0.14);
    }
    .stage-icon-circle {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: var(--teal-light);
        color: var(--teal);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin: 0 auto 14px;
    }
    .stage-card-title {
        font-size: 19px;
        font-weight: 800;
        color: var(--navy);
        margin-bottom: 6px;
    }
    .stage-card-desc {
        font-size: 13px;
        color: var(--ink-muted);
        margin: 0 auto 22px;
        max-width: 460px;
        line-height: 1.5;
    }
    .stage-large-input {
        width: 100%;
        height: 52px;
        padding: 0 18px;
        border: 2px solid #cbd5e1;
        border-radius: 12px;
        font-size: 15.5px;
        font-weight: 700;
        color: var(--navy);
        text-align: center;
        transition: all 0.25s ease;
        outline: none;
    }
    .stage-large-input:focus {
        border-color: var(--teal);
        background: #fbfdfd;
        box-shadow: 0 0 0 4px rgba(43, 138, 133, 0.15);
    }
    .stage-large-textarea {
        width: 100%;
        padding: 16px 18px;
        border: 2px solid #cbd5e1;
        border-radius: 12px;
        font-size: 14.5px;
        color: var(--navy);
        line-height: 1.7;
        transition: all 0.25s ease;
        outline: none;
        resize: vertical;
    }
    .stage-large-textarea:focus {
        border-color: var(--teal);
        background: #fbfdfd;
        box-shadow: 0 0 0 4px rgba(43, 138, 133, 0.15);
    }
    .btn-stage-action {
        background: linear-gradient(135deg, var(--teal), #0d736d);
        color: #fff;
        font-weight: 800;
        font-size: 14px;
        padding: 12px 34px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 14px rgba(43, 138, 133, 0.3);
        transition: all 0.2s ease;
    }
    .btn-stage-action:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(43, 138, 133, 0.4);
    }
    .btn-stage-action:disabled {
        opacity: 0.45;
        cursor: not-allowed;
        box-shadow: none;
    }

    /* Completed Field Header/Pill */
    .completed-field-card {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 18px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        animation: slideDownStage 0.28s ease;
    }
    @keyframes slideDownStage {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .completed-field-card:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }
    .completed-check-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #ecfdf5;
        color: #10b981;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }
    .completed-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--ink-muted);
        display: block;
    }
    .completed-val {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--navy);
    }
    .btn-edit-stage {
        background: #fff;
        border: 1px solid #cbd5e1;
        color: var(--navy);
        font-size: 11.5px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 6px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.15s ease;
    }
    .btn-edit-stage:hover {
        background: var(--navy);
        color: #fff;
        border-color: var(--navy);
    }
    .substep-dot {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 800;
        transition: all 0.2s ease;
    }
    .substep-dot.active {
        background: var(--teal);
        color: #fff;
    }
    .substep-dot.completed {
        background: #10b981;
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="wizard-container" style="max-width:1050px;margin:0 auto;padding-bottom:50px;">

    <!-- ترويسة المعالج -->
    <div style="margin-bottom:28px;">
        <a href="{{ route('university.advertisements.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:12.5px;color:var(--ink-muted);text-decoration:none;margin-bottom:6px;font-weight:600;">
            <i class="fa-solid fa-arrow-right"></i> العودة لقائمة الإعلانات
        </a>
        <h1 style="font-size:22px;font-weight:800;color:var(--navy);margin:0;">
            معالج إنشاء إعلان ترويجي جديد
        </h1>
    </div>

    <!-- شريط مراحل التقدم (Stepper) -->
    @php
        $initialStep = 1;
        if (isset($editAd) && $editAd) {
            $initialStep = 3; // مباشرة لتعديل المحتوى
        } elseif (isset($draft) && $draft) {
            $initialStep = min(4, max(1, (int)$draft->wizard_step + 1));
        }
    @endphp

    <div class="wizard-stepper-wrap">
        <div class="stepper-progress-line" id="stepperProgressLine" style="width: {{ ($initialStep - 1) * 33.33 }}%;"></div>
        
        <div class="step-item {{ $initialStep >= 1 ? ($initialStep > 1 ? 'completed' : 'active') : '' }}" id="stepIndicator-1" onclick="jumpToStep(1)">
            <div class="step-circle">
                <span class="step-num">1</span>
            </div>
            <div class="step-label">1. اختيار الباقة</div>
        </div>

        <div class="step-item {{ $initialStep >= 2 ? ($initialStep > 2 ? 'completed' : 'active') : '' }}" id="stepIndicator-2" onclick="jumpToStep(2)">
            <div class="step-circle">
                <span class="step-num">2</span>
            </div>
            <div class="step-label">2. السداد والدفع</div>
        </div>

        <div class="step-item {{ $initialStep >= 3 ? ($initialStep > 3 ? 'completed' : 'active') : '' }}" id="stepIndicator-3" onclick="jumpToStep(3)">
            <div class="step-circle">
                <span class="step-num">3</span>
            </div>
            <div class="step-label">3. محتوى الإعلان</div>
        </div>

        <div class="step-item {{ $initialStep >= 4 ? 'active' : '' }}" id="stepIndicator-4" onclick="jumpToStep(4)">
            <div class="step-circle">
                <span class="step-num">4</span>
            </div>
            <div class="step-label">4. المعاينة والإرسال</div>
        </div>
    </div>

    <!-- حقول الحالة المخفية -->
    <input type="hidden" id="currentAdId" value="{{ $draft->adv_id ?? ($editAd->adv_id ?? '') }}">
    <input type="hidden" id="selectedPackage" value="{{ $draft->package ?? ($editAd->package ?? 'مميزة') }}">
    <input type="hidden" id="selectedAmount" value="{{ $draft->amount ?? ($editAd->amount ?? 9000) }}">
    <input type="hidden" id="selectedDuration" value="{{ $draft->duration ?? ($editAd->duration ?? 4) }}">

    <!-- =============================================================== -->
    <!-- المرحلة 1: اختيار الباقة                                         -->
    <!-- =============================================================== -->
    <div class="wizard-step-pane" id="wizardStepPane-1" style="{{ $initialStep == 1 ? 'display:block;' : 'display:none;' }}">
        <div style="text-align:center;margin-bottom:28px;">
            <h2 style="font-size:18px;font-weight:800;color:var(--navy);margin-bottom:6px;">اختر الباقة الإعلانية المناسبة لجامعتك</h2>
            <p style="font-size:13px;color:var(--ink-muted);margin:0;">باقات مدروسة تضمن وصول رسالتك للطلبة المهتمين بأعلى كفاءة تسويقية</p>
        </div>

        <div class="packages-grid">
            <!-- 1. الباقة الاقتصادية -->
            <div class="package-plan-card" id="packageCard-اقتصادية" onclick="selectPackage('اقتصادية', 5000, 2)">
                <div class="package-header-sec">
                    <div style="font-size:12px;color:var(--ink-muted);font-weight:700;margin-bottom:4px;">للحملات السريعة والمحددة</div>
                    <h3 class="package-title-text">الباقة الاقتصادية</h3>
                    <div class="package-price-wrap">
                        <span class="package-price-val">5,000</span>
                        <span class="package-currency">ريال يمني</span>
                        <span class="package-duration-tag">مدة: 2 يوم (48 ساعة)</span>
                    </div>
                </div>

                <div class="visibility-meter-box">
                    <span>مستوى الظهور:</span>
                    <strong style="color:var(--navy);"><i class="fa-solid fa-signal" style="color:#10b981;"></i> ظهور عادي</strong>
                </div>

                <ul class="package-features-list">
                    <li><i class="fa-solid fa-check check-icon"></i> <span>رفع صورة إعلانية واحدة (JPG/PNG/WEBP)</span></li>
                    <li><i class="fa-solid fa-check check-icon"></i> <span>الظهور في الصفحة الرئيسية للمنصة</span></li>
                    <li><i class="fa-solid fa-check check-icon"></i> <span>رابط مباشر لصفحة تخصصات الجامعة</span></li>
                    <li><i class="fa-solid fa-xmark x-icon"></i> <span style="color:#94a3b8;">الظهور في دليل الجامعات</span></li>
                    <li><i class="fa-solid fa-xmark x-icon"></i> <span style="color:#94a3b8;">سلايدر متحرك أو فيديو</span></li>
                </ul>

                <div style="display:flex;flex-direction:column;gap:10px;margin-top:auto;">
                    <button type="button" class="btn btn-ghost" onclick="event.stopPropagation(); openPreviewModal('اقتصادية')" style="font-size:12px;font-weight:700;color:var(--navy);border:1px solid #cbd5e1;border-radius:var(--radius-sm);padding:8px 12px;background:#fff;display:inline-flex;align-items:center;justify-content:center;gap:6px;">
                        <i class="fa-solid fa-eye" style="color:var(--teal);"></i> شاهد شكل الإعلان في الموقع
                    </button>
                    <button type="button" class="btn btn-select-pkg" id="btnPkg-اقتصادية" onclick="event.stopPropagation(); proceedWithPackage('اقتصادية', 5000, 2)" style="width:100%;padding:11px 16px;border-radius:var(--radius-sm);background:var(--navy);color:#fff;font-weight:800;border:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:8px;font-size:13.5px;transition:all 0.2s ease;">
                        <span>متابعة</span>
                    </button>
                </div>
            </div>

            <!-- 2. الباقة المميزة ⭐ (الموصى بها) -->
            <div class="package-plan-card featured" id="packageCard-مميزة" onclick="selectPackage('مميزة', 9000, 4)">
                <div class="featured-badge-pill">
                    <i class="fa-solid fa-star"></i> الأكثر طلباً — موصى بها
                </div>

                <div class="package-header-sec">
                    <div style="font-size:12px;color:#d97706;font-weight:700;margin-bottom:4px;">تغطية مزدوجة ومعدل نقرات أعلى</div>
                    <h3 class="package-title-text" style="color:#92400e;">الباقة المميزة ⭐</h3>
                    <div class="package-price-wrap">
                        <span class="package-price-val" style="color:#b45309;">9,000</span>
                        <span class="package-currency">ريال يمني</span>
                        <span class="package-duration-tag" style="background:#fef3c7;color:#92400e;">مدة: 4 أيام كاملة</span>
                    </div>
                </div>

                <div class="visibility-meter-box" style="background:#fffbeb;">
                    <span>مستوى الظهور:</span>
                    <strong style="color:#b45309;"><i class="fa-solid fa-signal" style="color:#f59e0b;"></i> ظهور مضاعف ومميز</strong>
                </div>

                <ul class="package-features-list">
                    <li><i class="fa-solid fa-check check-icon"></i> <span>معرض من <strong>1 إلى 3 صور</strong> (سلايدر متحرك)</span></li>
                    <li><i class="fa-solid fa-check check-icon"></i> <span>الظهور في <strong>الصفحة الرئيسية + دليل الجامعات</strong></span></li>
                    <li><i class="fa-solid fa-check check-icon"></i> <span>شارة تمييز خاصة للإعلان</span></li>
                    <li><i class="fa-solid fa-check check-icon"></i> <span>أولوية ظهور متقدمة أمام الطلاب</span></li>
                    <li><i class="fa-solid fa-xmark x-icon"></i> <span style="color:#94a3b8;">فيديو ترويجي مصور</span></li>
                </ul>

                <div style="display:flex;flex-direction:column;gap:10px;margin-top:auto;">
                    <button type="button" class="btn btn-ghost" onclick="event.stopPropagation(); openPreviewModal('مميزة')" style="font-size:12px;font-weight:700;color:#92400e;border:1px solid #fde68a;border-radius:var(--radius-sm);padding:8px 12px;background:#fff;display:inline-flex;align-items:center;justify-content:center;gap:6px;">
                        <i class="fa-solid fa-eye" style="color:#d97706;"></i> شاهد شكل الإعلان في الموقع
                    </button>
                    <button type="button" class="btn btn-select-pkg" id="btnPkg-مميزة" onclick="event.stopPropagation(); proceedWithPackage('مميزة', 9000, 4)" style="width:100%;padding:11px 16px;border-radius:var(--radius-sm);background:linear-gradient(135deg, #d97706, #b45309);color:#fff;font-weight:800;border:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:8px;font-size:13.5px;transition:all 0.2s ease;">
                        <span>متابعة</span>
                    </button>
                </div>
            </div>

            <!-- 3. الباقة الشاملة -->
            <div class="package-plan-card" id="packageCard-شاملة" onclick="selectPackage('شاملة', 20000, 10)">
                <div class="package-header-sec">
                    <div style="font-size:12px;color:var(--teal);font-weight:700;margin-bottom:4px;">حملة ملكية بأطول مدة وأكبر وصول</div>
                    <h3 class="package-title-text">الباقة الشاملة</h3>
                    <div class="package-price-wrap">
                        <span class="package-price-val">20,000</span>
                        <span class="package-currency">ريال يمني</span>
                        <span class="package-duration-tag">مدة: 10 أيام كاملة</span>
                    </div>
                </div>

                <div class="visibility-meter-box">
                    <span>مستوى الظهور:</span>
                    <strong style="color:var(--navy);"><i class="fa-solid fa-signal" style="color:var(--teal);"></i> ظهور بارز جداً (أولوية قصوى)</strong>
                </div>

                <ul class="package-features-list">
                    <li><i class="fa-solid fa-check check-icon"></i> <span><strong>حتى 5 صور</strong> أو <strong>فيديو ترويجي 10 ثوانٍ</strong></span></li>
                    <li><i class="fa-solid fa-check check-icon"></i> <span>الظهور في <strong>الرئيسية + دليل الجامعات + أعلى القوائم</strong></span></li>
                    <li><i class="fa-solid fa-check check-icon"></i> <span>أعلى أولوية ظهور (Priority 3) طوال 10 أيام</span></li>
                    <li><i class="fa-solid fa-check check-icon"></i> <span>أزرار نداء للتسجيل المباشر والتواصل الفوري</span></li>
                    <li><i class="fa-solid fa-check check-icon"></i> <span>دعم فني وتنسيقي مع فريق المنصة</span></li>
                </ul>

                <div style="display:flex;flex-direction:column;gap:10px;margin-top:auto;">
                    <button type="button" class="btn btn-ghost" onclick="event.stopPropagation(); openPreviewModal('شاملة')" style="font-size:12px;font-weight:700;color:var(--navy);border:1px solid #cbd5e1;border-radius:var(--radius-sm);padding:8px 12px;background:#fff;display:inline-flex;align-items:center;justify-content:center;gap:6px;">
                        <i class="fa-solid fa-eye" style="color:var(--teal);"></i> شاهد شكل الإعلان في الموقع
                    </button>
                    <button type="button" class="btn btn-select-pkg" id="btnPkg-شاملة" onclick="event.stopPropagation(); proceedWithPackage('شاملة', 20000, 10)" style="width:100%;padding:11px 16px;border-radius:var(--radius-sm);background:var(--navy);color:#fff;font-weight:800;border:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:8px;font-size:13.5px;transition:all 0.2s ease;">
                        <span>متابعة</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- =============================================================== -->
    <!-- المرحلة 2: السداد والدفع                                         -->
    <!-- =============================================================== -->
    @php
        $paymentAccounts = $paymentAccounts ?? [
            'kuraimi' => [
                'name' => 'بنك الكريمي للتمويل الأصغر الإسلامي',
                'account' => '120456789',
                'beneficiary' => 'منصة إديو يمن - EduYemen للخدمات التعليمية',
            ],
            'onecash' => [
                'name' => 'محفظة ون كاش التجارية (OneCash)',
                'account' => '777000123',
                'beneficiary' => 'EduYemen - إديو يمن',
            ],
            'bank' => [
                'name' => 'بنك التضامن الإسلامي الدولي',
                'iban' => 'YE2900040000000123456789',
                'beneficiary' => 'شركة إديو يمن لتقنية المعلومات والتعليم',
            ],
        ];
        $currentPayMethod = $draft->pay_method ?? ($editAd->pay_method ?? '');
    @endphp
    <div class="wizard-step-pane" id="wizardStepPane-2" style="{{ $initialStep == 2 ? 'display:block;' : 'display:none;' }}">
        <div style="background:var(--surface);border-radius:var(--radius-md);border:1px solid var(--border);padding:26px;box-shadow:var(--shadow-sm);margin-bottom:24px;">
            <!-- ملخص الباقة المختارة -->
            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:14px;padding:16px 20px;background:linear-gradient(135deg, var(--navy), #163d5a);border-radius:var(--radius-md);color:#fff;margin-bottom:24px;">
                <div>
                    <div style="font-size:12px;color:var(--gold);font-weight:700;">الباقة الإعلانية المختارة:</div>
                    <div style="font-size:19px;font-weight:800;" id="summaryPkgName">الباقة المميزة ⭐</div>
                </div>
                <div style="display:flex;gap:20px;align-items:center;">
                    <div>
                        <div style="font-size:11px;color:#94a3b8;">المدة:</div>
                        <div style="font-size:14px;font-weight:700;" id="summaryPkgDuration">4 أيام</div>
                    </div>
                    <div style="border-right:1px solid rgba(255,255,255,0.2);height:30px;"></div>
                    <div>
                        <div style="font-size:11px;color:#94a3b8;">المبلغ المطلوب سداده:</div>
                        <div style="font-size:20px;font-weight:900;color:var(--gold);" id="summaryPkgAmount">9,000 ر.ي</div>
                    </div>
                </div>
            </div>

            <div style="margin-bottom:16px;">
                <h3 style="font-size:15px;font-weight:800;color:var(--navy);margin-bottom:6px;display:flex;align-items:center;gap:8px;">
                    <i class="fa-solid fa-building-columns" style="color:var(--teal);"></i>
                    <span>طرق الدفع المعتمدة للمنصة:</span>
                </h3>
                <p style="font-size:12.5px;color:var(--ink-muted);margin:0;">
                    اختر طريقة الدفع المناسبة لجامعتك لنسخ بيانات الحساب وإتمام التحويل:
                </p>
            </div>

            <!-- بطاقات طرق الدفع المعتمدة (بطاقات مربعة قابلة للاختيار الفردي) -->
            <div class="payment-methods-grid">
                <!-- 1. بنك الكريمي -->
                <div class="payment-method-card {{ ($currentPayMethod == 'حوالة بنك الكريمي') ? 'selected' : '' }}" id="payCard-kuraimi" onclick="openAccountModal('kuraimi')">
                    <div class="select-radio-pill"><i class="fa-solid fa-check"></i></div>
                    <div class="method-icon-wrap" style="background:#e0f2fe;color:#0369a1;">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                    </div>
                    <div style="font-size:15px;font-weight:800;color:var(--navy);margin-bottom:4px;">حوالة بنك الكريمي</div>
                    <div style="font-size:12px;color:var(--ink-muted);margin-bottom:12px;">حساب بنكي أو حوالة مميز</div>
                    <div style="font-size:11.5px;font-weight:700;color:var(--teal);background:var(--teal-light);padding:5px 12px;border-radius:20px;">
                        <i class="fa-regular fa-copy"></i> انقر لنسخ رقم الحساب
                    </div>
                </div>

                <!-- 2. محفظة ون كاش -->
                <div class="payment-method-card {{ ($currentPayMethod == 'محفظة ون كاش التجارية') ? 'selected' : '' }}" id="payCard-onecash" onclick="openAccountModal('onecash')">
                    <div class="select-radio-pill"><i class="fa-solid fa-check"></i></div>
                    <div class="method-icon-wrap" style="background:#fae8ff;color:#a21caf;">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <div style="font-size:15px;font-weight:800;color:var(--navy);margin-bottom:4px;">محفظة ون كاش (OneCash)</div>
                    <div style="font-size:12px;color:var(--ink-muted);margin-bottom:12px;">سداد فوري عبر المحفظة التجارية</div>
                    <div style="font-size:11.5px;font-weight:700;color:var(--teal);background:var(--teal-light);padding:5px 12px;border-radius:20px;">
                        <i class="fa-regular fa-copy"></i> انقر لنسخ رقم المحفظة
                    </div>
                </div>

                <!-- 3. بنك التضامن -->
                <div class="payment-method-card {{ ($currentPayMethod == 'تحويل بنكي مباشر' || $currentPayMethod == 'تحويل بنكي - بنك التضامن') ? 'selected' : '' }}" id="payCard-bank" onclick="openAccountModal('bank')">
                    <div class="select-radio-pill"><i class="fa-solid fa-check"></i></div>
                    <div class="method-icon-wrap" style="background:#dcfce7;color:#15803d;">
                        <i class="fa-solid fa-landmark"></i>
                    </div>
                    <div style="font-size:15px;font-weight:800;color:var(--navy);margin-bottom:4px;">تحويل بنكي - التضامن</div>
                    <div style="font-size:12px;color:var(--ink-muted);margin-bottom:12px;">تحويل مصرفي عبر رقم الآيبان</div>
                    <div style="font-size:11.5px;font-weight:700;color:var(--teal);background:var(--teal-light);padding:5px 12px;border-radius:20px;">
                        <i class="fa-regular fa-copy"></i> انقر لنسخ رقم الآيبان
                    </div>
                </div>
            </div>

            <!-- رسالة إرشادية عند عدم تحديد وسيلة الدفع بعد -->
            <div id="paymentProofPlaceholder" style="background:#f8fafc;border:1.5px dashed #cbd5e1;border-radius:14px;padding:24px;text-align:center;margin-bottom:20px;{{ !empty($currentPayMethod) ? 'display:none;' : '' }}">
                <i class="fa-solid fa-hand-pointer" style="font-size:26px;color:var(--teal);margin-bottom:8px;"></i>
                <div style="font-size:14px;font-weight:700;color:var(--navy);margin-bottom:4px;">اختر إحدى طرق الدفع المعتمدة أعلاه للمتابعة</div>
                <div style="font-size:12px;color:var(--ink-muted);">عند النقر على طريقة الدفع ستظهر نافذة نسخ بيانات الحساب وقسم إثبات السداد.</div>
            </div>

            <!-- قسم إثبات الدفع (يظهر بعد اختيار وسيلة الدفع ونسخ الحساب) -->
            <div id="paymentProofSection" style="{{ !empty($currentPayMethod) ? 'display:block;' : 'display:none;' }}">
                <div style="border-top:1px solid #e2e8f0;padding-top:22px;margin-top:10px;">
                    <h4 style="font-size:15px;font-weight:800;color:var(--navy);margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                        <i class="fa-solid fa-file-invoice" style="color:var(--teal);"></i>
                        <span>بيانات إثبات السداد:</span>
                    </h4>

                    <form id="paymentStepForm" enctype="multipart/form-data">
                        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));gap:20px;margin-bottom:20px;">
                            <!-- وسيلة التحويل المستخدمة (محددة تلقائياً ولا تتغير إلا باختيار طريقة أخرى) -->
                            <div>
                                <label style="display:block;font-size:13px;font-weight:700;color:var(--navy);margin-bottom:6px;">
                                    وسيلة التحويل المستخدمة <span style="color:#ef4444;">*</span>
                                </label>
                                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:#f0fdfa;border:1.5px solid #99f6e4;border-radius:var(--radius-sm);min-height:44px;">
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <i class="fa-solid fa-circle-check" style="color:var(--teal);"></i>
                                        <strong style="font-size:13.5px;color:var(--navy);" id="lockedPayMethodName">{{ $currentPayMethod ?: 'حوالة بنك الكريمي' }}</strong>
                                        <span style="font-size:11px;background:#ccfbf1;color:#0f766e;padding:2px 6px;border-radius:4px;font-weight:700;"><i class="fa-solid fa-lock"></i> محدد تلقائياً</span>
                                    </div>
                                    <button type="button" onclick="reselectPaymentMethod()" style="background:#fff;border:1px solid #cbd5e1;border-radius:6px;padding:4px 9px;font-size:11.5px;font-weight:700;color:var(--navy);cursor:pointer;">
                                        <i class="fa-solid fa-rotate-left"></i> تغيير
                                    </button>
                                </div>
                                <input type="hidden" id="payMethodSelect" value="{{ $currentPayMethod ?: 'حوالة بنك الكريمي' }}">
                            </div>

                            <!-- رقم المرجع -->
                            <div>
                                <label style="display:block;font-size:13px;font-weight:700;color:var(--navy);margin-bottom:6px;">
                                    رقم الحوالة / الإشعار البنكي / المرجع <span style="color:#ef4444;">*</span>
                                </label>
                                <input type="text" id="payReferenceInput" value="{{ $draft->pay_reference ?? '' }}" placeholder="مثال: 987654321" class="form-control" style="width:100%;height:44px;padding:0 12px;border:1px solid var(--border);border-radius:var(--radius-sm);font-size:13.5px;font-family:monospace;" />
                            </div>
                        </div>

                        <!-- رفع سند الدفع -->
                        <div style="margin-bottom:20px;">
                            <label style="display:block;font-size:13px;font-weight:700;color:var(--navy);margin-bottom:6px;">
                                رفع صورة سند إثبات الدفع (سند الحوالة / الإشعار) <span style="color:#ef4444;">*</span>
                            </label>

                            <div class="upload-dropzone" onclick="document.getElementById('receiptFileInput').click()" id="receiptDropzone">
                                <input type="file" id="receiptFileInput" accept="image/jpeg,image/png,image/webp,application/pdf" style="display:none;" onchange="handleReceiptSelect(event)" />
                                <div id="receiptUploadPlaceholder">
                                    <i class="fa-solid fa-cloud-arrow-up" style="font-size:36px;color:var(--teal);margin-bottom:10px;"></i>
                                    <div style="font-weight:700;color:var(--navy);font-size:14px;margin-bottom:4px;">انقر لاختيار السند أو اسحبه وأفلته هنا</div>
                                    <div style="font-size:12px;color:var(--ink-muted);">الصيغ المدعومة: JPG, PNG, WEBP, PDF — الحجم الأقصى: 5 ميجابايت</div>
                                </div>
                                <div id="receiptPreviewWrap" style="{{ (isset($draft) && $draft->receipt_url) ? 'display:block;' : 'display:none;' }}">
                                    <img id="receiptPreviewImg" src="{{ (isset($draft) && $draft->receipt_url) ? asset($draft->receipt_url) : '' }}" alt="معاينة السند" style="max-height:160px;max-width:100%;border-radius:8px;border:1px solid var(--border);margin-bottom:8px;" />
                                    <div style="font-size:12px;color:var(--teal);font-weight:700;" id="receiptFileName">
                                        {{ (isset($draft) && $draft->receipt_url) ? 'تم إرفاق السند مسبقاً (انقر لتغييره)' : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:28px;">
                            <button type="button" class="btn btn-ghost" onclick="jumpToStep(1)" style="padding:10px 22px;border:1px solid var(--border);border-radius:var(--radius-sm);font-weight:700;cursor:pointer;background:#fff;">
                                <i class="fa-solid fa-arrow-right"></i> الرجوع لاختيار الباقة
                            </button>
                            <button type="button" class="btn" onclick="submitStep2()" id="btnSubmitStep2" style="background:var(--teal);color:#fff;font-weight:800;font-size:14px;padding:12px 32px;border-radius:var(--radius-md);border:none;cursor:pointer;display:inline-flex;align-items:center;gap:10px;box-shadow:0 4px 14px rgba(43,138,133,0.3);">
                                <span>حفظ بيانات السداد والمتابعة</span>
                                <i class="fa-solid fa-arrow-left"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- نافذة منبثقة لنسخ رقم الحساب والتعليمات -->
    <div class="account-copy-modal-overlay" id="accountCopyModal">
        <div class="account-copy-modal-dialog">
            <div style="padding:18px 22px;border-bottom:1px solid #e2e8f0;display:flex;justify-content:space-between;align-items:center;background:var(--navy);color:#fff;">
                <h3 style="font-size:16px;font-weight:800;margin:0;display:flex;align-items:center;gap:8px;">
                    <i class="fa-solid fa-shield-halved" style="color:var(--gold);"></i>
                    <span>بيانات الحساب المعتمد للسداد</span>
                </h3>
                <button type="button" onclick="closeAccountModal()" style="background:transparent;border:none;color:#fff;font-size:22px;cursor:pointer;opacity:0.8;line-height:1;">&times;</button>
            </div>
            <div style="padding:24px;">
                <div style="background:#f0fdf4;border:1.5px solid #86efac;border-radius:12px;padding:14px 16px;margin-bottom:20px;font-size:13.5px;color:#166534;line-height:1.6;font-weight:700;text-align:center;">
                    <i class="fa-solid fa-circle-info" style="color:#16a34a;margin-left:6px;"></i>
                    يرجى تحويل رسوم الإعلان (<span id="modalPkgFeeText" style="color:#b45309;font-weight:900;">9,000 ريال يمني</span>) إلى الحساب التالي، ثم إدخال رقم المرجع وإرفاق صورة السند:
                </div>

                <div style="background:#f8fafc;border:1.5px dashed #cbd5e1;border-radius:14px;padding:20px;text-align:center;margin-bottom:22px;">
                    <div style="font-size:13px;font-weight:700;color:var(--ink-muted);margin-bottom:6px;" id="modalMethodProvider">بنك الكريمي للتمويل الأصغر</div>
                    <div style="font-size:24px;font-weight:900;font-family:monospace;color:var(--navy);letter-spacing:1px;margin-bottom:8px;direction:ltr;" id="modalAccountNumber">120456789</div>
                    <div style="font-size:12px;color:var(--ink-muted);" id="modalBeneficiary">الاسم: منصة إديو يمن - EduYemen للخدمات التعليمية</div>
                </div>

                <button type="button" class="btn" onclick="copyAndProceedToProof()" style="background:linear-gradient(135deg, var(--teal), #0d736d);color:#fff;width:100%;padding:14px;border-radius:12px;font-weight:800;border:none;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;gap:10px;font-size:14.5px;box-shadow:0 4px 14px rgba(43,138,133,0.3);">
                    <i class="fa-regular fa-copy" style="font-size:17px;"></i>
                    <span id="modalCopyBtnText">نسخ رقم الحساب والمتابعة لإثبات الدفع</span>
                </button>
            </div>
        </div>
    </div>

    <!-- =============================================================== -->
    <!-- المرحلة 3: محتوى الإعلان والوسائط                                 -->
    <!-- =============================================================== -->
    <div class="wizard-step-pane" id="wizardStepPane-3" style="{{ $initialStep == 3 ? 'display:block;' : 'display:none;' }}">
        <div style="background:var(--surface);border-radius:var(--radius-md);border:1px solid var(--border);padding:26px;box-shadow:var(--shadow-sm);margin-bottom:24px;">
            <div style="margin-bottom:20px;">
                <h3 style="font-size:17px;font-weight:800;color:var(--navy);margin-bottom:4px;">محتوى الإعلان والوسائط الترويجية</h3>
            </div>

            <!-- بطاقات ملخص الحقول المكتملة (تظهر في الأعلى عند اكتمال العنوان والوصف) -->
            <div id="completedStagesSummaryArea" style="max-width:720px;margin:0 auto 16px;">
                <!-- بطاقة ملخص العنوان المكتمل -->
                <div class="completed-field-card" id="summaryCard-title" style="display:none;" onclick="editTitleStage()">
                    <div style="display:flex;align-items:center;gap:12px;overflow:hidden;">
                        <div class="completed-check-icon"><i class="fa-solid fa-check"></i></div>
                        <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            <span class="completed-label">عنوان الإعلان الترويجي:</span>
                            <span class="completed-val" id="summaryTitleText"></span>
                        </div>
                    </div>
                    <button type="button" class="btn-edit-stage" onclick="event.stopPropagation(); editTitleStage()"><i class="fa-solid fa-pen-to-square"></i> تعديل</button>
                </div>

                <!-- بطاقة ملخص الوصف المكتمل -->
                <div class="completed-field-card" id="summaryCard-desc" style="display:none;" onclick="editDescStage()">
                    <div style="display:flex;align-items:center;gap:12px;overflow:hidden;">
                        <div class="completed-check-icon"><i class="fa-solid fa-check"></i></div>
                        <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            <span class="completed-label">وصف وتفاصيل الإعلان:</span>
                            <span class="completed-val" id="summaryDescText"></span>
                        </div>
                    </div>
                    <button type="button" class="btn-edit-stage" onclick="event.stopPropagation(); editDescStage()"><i class="fa-solid fa-pen-to-square"></i> تعديل</button>
                </div>
            </div>

            <form id="contentStepForm">
                <!-- 1. مرحلة إدخال العنوان (تفاعلية، في منتصف القسم، غير مشتتة وواضحة) -->
                <div class="stage-focus-card fade-slide-up" id="contentStage-title">
                    <div class="stage-icon-circle"><i class="fa-solid fa-heading"></i></div>
                    <h3 class="stage-card-title">عنوان الإعلان الترويجي</h3>
                    <p class="stage-card-desc">اكتب عنواناً جذاباً يلفت انتباه الطلاب ويوضح العرض الأساسي لجامعتك.</p>
                    <div style="margin-bottom:14px;">
                        <input type="text" id="advTitleInput" value="{{ $draft->adv_title ?? ($editAd->adv_title ?? '') }}" placeholder="اكتب عنوان الإعلان هنا..." class="stage-large-input" minlength="5" maxlength="75" oninput="handleTitleInput(this)" onkeydown="if(event.key==='Enter'){event.preventDefault();finishTitleStage();}" />
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;font-size:12.5px;color:var(--ink-muted);">
                        <span>من 5 إلى 75 حرفاً</span>
                        <span id="titleCharCount" style="font-weight:700;color:var(--navy);">0 / 75</span>
                    </div>
                    <div>
                        <button type="button" class="btn-stage-action" id="btnNextToDesc" onclick="finishTitleStage()" disabled>
                            <span>المتابعة إلى كتابة الوصف</span>
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                    </div>
                </div>

                <!-- 2. مرحلة إدخال الوصف (تفاعلية، تصعد للمنتصف بعد العنوان) -->
                <div class="stage-focus-card fade-slide-up" id="contentStage-desc" style="display:none;">
                    <div class="stage-icon-circle"><i class="fa-solid fa-align-right"></i></div>
                    <h3 class="stage-card-title">وصف وتفاصيل الإعلان</h3>
                    <p class="stage-card-desc">اكتب تفاصيل حملتك ومميزات جامعتك وشروط التسجيل بطريقة واضحة ومرتبة.</p>
                    <div style="margin-bottom:14px;">
                        <textarea id="advContentInput" rows="3" placeholder="اكتب تفاصيل الإعلان هنا..." class="stage-large-textarea" minlength="10" maxlength="200" oninput="handleDescInput(this)">{{ $draft->adv_content ?? ($editAd->adv_content ?? '') }}</textarea>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;font-size:12.5px;color:var(--ink-muted);">
                        <span>من 10 إلى 200 حرف</span>
                        <span id="descCharCount" style="font-weight:700;color:var(--navy);">0 / 200</span>
                    </div>
                    <div style="display:flex;justify-content:center;gap:12px;">
                        <button type="button" class="btn btn-ghost" onclick="editTitleStage()" style="padding:10px 20px;border:1px solid #cbd5e1;border-radius:10px;font-weight:700;cursor:pointer;">
                            <i class="fa-solid fa-arrow-right"></i> السابق
                        </button>
                        <button type="button" class="btn-stage-action" id="btnNextToMedia" onclick="finishDescStage()" disabled>
                            <span>المتابعة إلى رفع الوسائط</span>
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                    </div>
                </div>

                <!-- 3. مرحلة رفع الوسائط (تظهر بسلاسة بعد إكمال العنوان والوصف) -->
                <div class="fade-slide-up" id="contentStage-media" style="display:none;">
                    <div style="margin-bottom:18px;">
                        <h4 style="font-size:16px;font-weight:800;color:var(--navy);margin-bottom:4px;display:flex;align-items:center;gap:8px;">
                            <i class="fa-solid fa-photo-film" style="color:var(--teal);"></i>
                            <span>الوسائط الترويجية المرفقة بالإعلان</span>
                        </h4>
                    </div>

                    <div id="mediaUploadContainer" style="padding:22px;background:var(--surface-subtle);border-radius:var(--radius-md);border:1px solid var(--border);margin-bottom:24px;">
                        <!-- سيتم تحديث هذا القسم بالجافاسكربت حسب الباقة المختارة -->
                    </div>

                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:28px;">
                        <button type="button" class="btn btn-ghost" onclick="jumpToStep(2)" style="padding:10px 22px;border:1px solid var(--border);border-radius:var(--radius-sm);font-weight:700;cursor:pointer;background:#fff;">
                            <i class="fa-solid fa-arrow-right"></i> الرجوع للسداد
                        </button>
                        <button type="button" class="btn" onclick="submitStep3()" id="btnSubmitStep3" style="background:var(--teal);color:#fff;font-weight:800;font-size:14px;padding:12px 32px;border-radius:var(--radius-md);border:none;cursor:pointer;display:inline-flex;align-items:center;gap:10px;box-shadow:0 4px 14px rgba(43,138,133,0.3);">
                            <span>حفظ المحتوى ومعاينة الإعلان</span>
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- =============================================================== -->
    <!-- المرحلة 4: المعاينة والإرسال                                     -->
    <!-- =============================================================== -->
    <div class="wizard-step-pane" id="wizardStepPane-4" style="{{ $initialStep == 4 ? 'display:block;' : 'display:none;' }}">
        <div style="background:var(--surface);border-radius:var(--radius-md);border:1px solid var(--border);padding:28px;box-shadow:var(--shadow-sm);margin-bottom:24px;">
            <div style="text-align:center;margin-bottom:28px;">
                <span class="badge" style="background:#dcfce7;color:#15803d;padding:6px 16px;border-radius:20px;font-weight:800;font-size:12px;margin-bottom:8px;display:inline-block;">
                    <i class="fa-solid fa-eye"></i> الخطوة الأخيرة
                </span>
                <h2 style="font-size:20px;font-weight:800;color:var(--navy);margin-bottom:4px;">معاينة الإعلان كما سيظهر للطلاب</h2>
                <p style="font-size:13px;color:var(--ink-muted);margin:0;">تحقق من دقة التفاصيل قبل الإرسال النهائي لمدير النظام للاعتماد.</p>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));gap:30px;align-items:start;margin-bottom:30px;">
                <!-- بطاقة محاكاة الإعلان للطالب -->
                <div>
                    <div style="font-size:12px;font-weight:700;color:var(--ink-muted);margin-bottom:8px;text-align:center;">
                        شكل بطاقة الإعلان على موقع EduYemen:
                    </div>
                    
                    <div class="ad-student-mock-card">
                        <div class="mock-media-wrap" id="mockMediaWrap">
                            <span class="mock-sponsor-tag"><i class="fa-solid fa-star"></i> إعلان ممول</span>
                            <img src="{{ asset('img/default-ad.jpg') }}" alt="معاينة" id="mockImg" class="mock-media-img" onerror="this.src='/logo.png';" />
                            <video id="mockVideo" controls style="display:none;width:100%;height:100%;object-fit:cover;"></video>
                        </div>
                        <div style="padding:18px 20px;">
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                                <img src="{{ $university->logo_url }}" alt="{{ $university->university_name }}" style="width:36px;height:36px;border-radius:8px;object-fit:contain;border:1px solid var(--border);background:#fff;" onerror="this.src='/logo.png';" />
                                <div>
                                    <h5 style="font-size:13px;font-weight:800;color:var(--navy);margin:0;">{{ $university->university_name }}</h5>
                                    <div style="font-size:11px;color:var(--ink-muted);" id="mockPackageTag">الباقة المميزة</div>
                                </div>
                            </div>

                            <h4 id="mockTitle" style="font-size:15px;font-weight:800;color:var(--navy);line-height:1.4;margin-bottom:8px;">
                                عنوان الإعلان الترويجي
                            </h4>

                            <p id="mockDesc" style="font-size:12.5px;color:var(--ink);line-height:1.6;margin-bottom:16px;">
                                وصف الإعلان ومزاياه سيظهر هنا للطالب بالكامل...
                            </p>

                            <div style="display:flex;gap:10px;">
                                <a href="javascript:void(0)" style="flex:1;text-align:center;background:var(--navy);color:#fff;padding:9px;border-radius:8px;font-weight:700;font-size:12.5px;text-decoration:none;">
                                    استعراض التخصصات
                                </a>
                                <a href="javascript:void(0)" style="background:var(--teal-light);color:var(--teal);padding:9px 14px;border-radius:8px;font-weight:700;font-size:12.5px;text-decoration:none;">
                                    التسجيل
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ملخص الحملة والبيانات المالية -->
                <div>
                    <div style="background:#f8fafc;border-radius:var(--radius-md);border:1px solid var(--border);padding:20px;">
                        <h4 style="font-size:15px;font-weight:800;color:var(--navy);margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                            <i class="fa-solid fa-file-invoice-dollar" style="color:var(--teal);"></i>
                            <span>ملخص الحملة والسداد:</span>
                        </h4>

                        <div style="display:flex;flex-direction:column;gap:12px;font-size:13px;">
                            <div style="display:flex;justify-content:space-between;border-bottom:1px solid #e2e8f0;padding-bottom:8px;">
                                <span style="color:var(--ink-muted);">الباقة:</span>
                                <strong id="reviewPkgName" style="color:var(--navy);">الباقة المميزة ⭐</strong>
                            </div>
                            <div style="display:flex;justify-content:space-between;border-bottom:1px solid #e2e8f0;padding-bottom:8px;">
                                <span style="color:var(--ink-muted);">مدة الحملة:</span>
                                <strong id="reviewDuration">4 أيام</strong>
                            </div>
                            <div style="display:flex;justify-content:space-between;border-bottom:1px solid #e2e8f0;padding-bottom:8px;">
                                <span style="color:var(--ink-muted);">المبلغ المسدد:</span>
                                <strong id="reviewAmount" style="color:var(--teal);font-size:15px;">9,000 ر.ي</strong>
                            </div>
                            <div style="display:flex;justify-content:space-between;border-bottom:1px solid #e2e8f0;padding-bottom:8px;">
                                <span style="color:var(--ink-muted);">وسيلة الدفع:</span>
                                <strong id="reviewPayMethod">—</strong>
                            </div>
                            <div style="display:flex;justify-content:space-between;border-bottom:1px solid #e2e8f0;padding-bottom:8px;">
                                <span style="color:var(--ink-muted);">رقم المرجع/الحوالة:</span>
                                <strong id="reviewPayRef" style="font-family:monospace;">—</strong>
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span style="color:var(--ink-muted);">سند إثبات الدفع:</span>
                                <span id="reviewReceiptStatus" style="color:#10b981;font-weight:700;">
                                    <i class="fa-solid fa-circle-check"></i> تم الإرفاق
                                </span>
                            </div>
                        </div>

                        <!-- إقرار وموافقة -->
                        <div style="margin-top:20px;padding:12px 14px;background:#ecfdf5;border:1px solid #a7f3d0;border-radius:8px;font-size:12px;color:#065f46;">
                            <label style="display:flex;align-items:flex-start;gap:8px;cursor:pointer;">
                                <input type="checkbox" id="termsAgreementCheckbox" checked style="margin-top:2px;" />
                                <span>أقر بأن محتوى الإعلان والبيانات المقدمة صحيحة وتتوافق مع معايير ولوائح النشر لوزارة التعليم العالي ومنصة EduYemen.</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:28px;padding-top:20px;border-top:1px solid var(--border);">
                <button type="button" class="btn btn-ghost" onclick="jumpToStep(3)" style="padding:10px 22px;border:1px solid var(--border);border-radius:var(--radius-sm);font-weight:700;cursor:pointer;background:#fff;">
                    <i class="fa-solid fa-arrow-right"></i> تعديل المحتوى
                </button>
                <button type="button" class="btn" onclick="submitFinalWizard()" id="btnSubmitFinal" style="background:linear-gradient(135deg, #10b981, #059669);color:#fff;font-weight:800;font-size:15px;padding:13px 36px;border-radius:var(--radius-md);border:none;cursor:pointer;display:inline-flex;align-items:center;gap:10px;box-shadow:0 4px 18px rgba(16,185,129,0.35);">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>إرسال الإعلان للمراجعة والاعتماد</span>
                </button>
            </div>
        </div>
    </div>

</div>

<!-- نافذة محاكاة ومعاينة شكل الإعلان (Simulation Modal) -->
<div class="custom-modal-overlay" id="previewSimModal" style="display:none;position:fixed;inset:0;background:rgba(10,27,42,0.7);z-index:9999;backdrop-filter:blur(3px);align-items:center;justify-content:center;padding:20px;">
    <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:760px;max-height:90vh;overflow-y:auto;box-shadow:var(--shadow-lg);border:1px solid var(--border);">
        <div style="padding:18px 24px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;background:var(--navy);color:#fff;border-radius:var(--radius-lg) var(--radius-lg) 0 0;">
            <h3 style="font-size:16px;font-weight:800;margin:0;display:flex;align-items:center;gap:8px;">
                <i class="fa-solid fa-laptop" style="color:var(--gold);"></i>
                <span id="simModalTitle">محاكاة شكل ظهور الإعلان في موقع EduYemen</span>
            </h3>
            <button type="button" onclick="closePreviewSimModal()" style="background:none;border:none;font-size:20px;color:#fff;cursor:pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div style="padding:24px;" id="simModalBody">
            <!-- محتوى المحاكاة يتم توليده ديناميكياً -->
        </div>

        <div style="padding:14px 24px;border-top:1px solid var(--border);display:flex;justify-content:flex-end;background:#f8fafc;border-radius:0 0 var(--radius-lg) var(--radius-lg);">
            <button type="button" class="btn btn-ghost" onclick="closePreviewSimModal()" style="padding:8px 22px;border:1px solid var(--border);border-radius:var(--radius-sm);background:#fff;font-weight:700;cursor:pointer;">
                إغلاق المعاينة
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let currentStep = {{ $initialStep }};
    let adState = {
        id: document.getElementById('currentAdId').value,
        package: document.getElementById('selectedPackage').value,
        amount: Number(document.getElementById('selectedAmount').value),
        duration: Number(document.getElementById('selectedDuration').value),
        payMethod: document.getElementById('payMethodSelect') ? document.getElementById('payMethodSelect').value : '',
        payRef: document.getElementById('payReferenceInput') ? document.getElementById('payReferenceInput').value : '',
        receiptUrl: "{{ (isset($draft) && $draft->receipt_url) ? asset($draft->receipt_url) : '' }}",
        title: document.getElementById('advTitleInput') ? document.getElementById('advTitleInput').value : '',
        content: document.getElementById('advContentInput') ? document.getElementById('advContentInput').value : '',
        mediaType: 'image',
        mediaFiles: [],
        videoUrl: "{{ (isset($draft) && $draft->video_url) ? asset($draft->video_url) : '' }}"
    };

    const paymentAccountsData = {
        'kuraimi': {
            methodName: 'حوالة بنك الكريمي',
            name: '{{ $paymentAccounts["kuraimi"]["name"] }}',
            account: '{{ $paymentAccounts["kuraimi"]["account"] }}',
            beneficiary: '{{ $paymentAccounts["kuraimi"]["beneficiary"] }}'
        },
        'onecash': {
            methodName: 'محفظة ون كاش التجارية',
            name: '{{ $paymentAccounts["onecash"]["name"] }}',
            account: '{{ $paymentAccounts["onecash"]["account"] }}',
            beneficiary: '{{ $paymentAccounts["onecash"]["beneficiary"] }}'
        },
        'bank': {
            methodName: 'تحويل بنكي مباشر',
            name: '{{ $paymentAccounts["bank"]["name"] }}',
            account: '{{ $paymentAccounts["bank"]["iban"] }}',
            beneficiary: '{{ $paymentAccounts["bank"]["beneficiary"] }}'
        }
    };

    let currentChosenMethodKey = 'kuraimi';

    document.addEventListener('DOMContentLoaded', function() {
        highlightPackage(adState.package);
        renderMediaUploadInputs();
        updateSummaryBanner();
        initInteractiveContentStages();

        // فحص وسيلة الدفع المسجلة مسبقاً في المسودة
        if (adState.payMethod) {
            for (const [key, data] of Object.entries(paymentAccountsData)) {
                if (data.methodName === adState.payMethod || (key === 'bank' && adState.payMethod.includes('بنك'))) {
                    currentChosenMethodKey = key;
                    const card = document.getElementById(`payCard-${key}`);
                    if (card) card.classList.add('selected');
                    break;
                }
            }
        }
    });

    // -------------------------------------------------------------
    // التنقل بين الخطوات
    // -------------------------------------------------------------
    function jumpToStep(targetStep) {
        // حماية: لا يمكن الانتقال للخطوة 3 أو 4 إلا بوجود إعلان مسجل
        if (targetStep > 1 && !adState.id) {
            alert('يرجى اختيار الباقة أولاً للمتابعة.');
            return;
        }

        currentStep = targetStep;

        // تحديث الخطوات المرئية
        for (let i = 1; i <= 4; i++) {
            const pane = document.getElementById(`wizardStepPane-${i}`);
            const ind = document.getElementById(`stepIndicator-${i}`);
            if (pane) pane.style.display = (i === currentStep) ? 'block' : 'none';

            if (ind) {
                ind.classList.remove('active', 'completed');
                if (i < currentStep) ind.classList.add('completed');
                else if (i === currentStep) ind.classList.add('active');
            }
        }

        // تحديث خط التقدم
        const line = document.getElementById('stepperProgressLine');
        if (line) {
            line.style.width = ((currentStep - 1) * 33.33) + '%';
        }

        // تحديث معاينة الخطوة 4 إذا تم الانتقال إليها
        if (currentStep === 4) {
            renderFinalPreview();
        }

        window.scrollTo({ top: 120, behavior: 'smooth' });
    }

    // -------------------------------------------------------------
    // الخطوة 1: اختيار الباقة
    // -------------------------------------------------------------
    function selectPackage(pkgName, amount, duration) {
        adState.package = pkgName;
        adState.amount = amount;
        adState.duration = duration;

        document.getElementById('selectedPackage').value = pkgName;
        document.getElementById('selectedAmount').value = amount;
        document.getElementById('selectedDuration').value = duration;

        highlightPackage(pkgName);
        updateSummaryBanner();
        renderMediaUploadInputs();
    }

    function proceedWithPackage(pkgName, amount, duration) {
        selectPackage(pkgName, amount, duration);
        submitStep1(pkgName);
    }

    function highlightPackage(pkgName) {
        ['اقتصادية', 'مميزة', 'شاملة'].forEach(p => {
            const card = document.getElementById(`packageCard-${p}`);
            const btn = document.getElementById(`btnPkg-${p}`);
            if (card) {
                if (p === pkgName) {
                    card.classList.add('selected');
                    if (btn) {
                        btn.innerHTML = '<span>متابعة</span> <i class="fa-solid fa-check" style="font-size:14px;font-weight:900;"></i>';
                        btn.style.boxShadow = '0 4px 14px rgba(0,0,0,0.25)';
                    }
                } else {
                    card.classList.remove('selected');
                    if (btn) {
                        btn.innerHTML = '<span>متابعة</span>';
                        btn.style.boxShadow = 'none';
                    }
                }
            }
        });
    }

    function updateSummaryBanner() {
        const nameEl = document.getElementById('summaryPkgName');
        const durEl = document.getElementById('summaryPkgDuration');
        const amtEl = document.getElementById('summaryPkgAmount');

        if (nameEl) nameEl.textContent = 'الباقة ' + adState.package;
        if (durEl) durEl.textContent = adState.duration + ' أيام';
        if (amtEl) amtEl.textContent = Number(adState.amount).toLocaleString('ar-YE') + ' ر.ي';
    }

    async function submitStep1(pkgToSubmit = null) {
        const targetPkg = pkgToSubmit || adState.package;
        const activeBtn = document.getElementById(`btnPkg-${targetPkg}`);
        if (activeBtn) {
            activeBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>جارٍ المتابعة...</span>';
            activeBtn.disabled = true;
        }

        try {
            const res = await fetch("{{ route('university.advertisements.wizard.step2') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    package: targetPkg,
                    ad_id: adState.id || null
                })
            });

            const data = await res.json();
            if (data.success) {
                adState.id = data.ad_id;
                document.getElementById('currentAdId').value = data.ad_id;
                jumpToStep(2);
            } else {
                alert(data.message || 'حدث خطأ في حفظ الباقة.');
            }
        } catch(e) {
            alert('حدث خطأ في الاتصال بالخادم.');
        } finally {
            if (activeBtn) {
                activeBtn.disabled = false;
                highlightPackage(adState.package);
            }
        }
    }

    // -------------------------------------------------------------
    // الخطوة 2: السداد وإثبات الدفع والنافذة المنبثقة للنسخ
    // -------------------------------------------------------------
    function openAccountModal(methodKey) {
        currentChosenMethodKey = methodKey;
        const acc = paymentAccountsData[methodKey];
        if (!acc) return;

        // تمييز البطاقة المحددة
        ['kuraimi', 'onecash', 'bank'].forEach(k => {
            const el = document.getElementById(`payCard-${k}`);
            if (el) {
                if (k === methodKey) el.classList.add('selected');
                else el.classList.remove('selected');
            }
        });

        // تحديث رسوم الإعلان ديناميكياً
        const feeText = Number(adState.amount || 9000).toLocaleString('ar-YE') + ' ريال يمني';
        const feeEl = document.getElementById('modalPkgFeeText');
        if (feeEl) feeEl.textContent = feeText;

        document.getElementById('modalMethodProvider').textContent = acc.name;
        document.getElementById('modalAccountNumber').textContent = acc.account;
        document.getElementById('modalBeneficiary').textContent = 'الاسم: ' + acc.beneficiary;

        document.getElementById('accountCopyModal').style.display = 'flex';
    }

    function closeAccountModal() {
        document.getElementById('accountCopyModal').style.display = 'none';
    }

    function copyAndProceedToProof() {
        const acc = paymentAccountsData[currentChosenMethodKey];
        if (!acc) return;

        navigator.clipboard.writeText(acc.account).then(() => {
            const btnText = document.getElementById('modalCopyBtnText');
            if (btnText) btnText.innerHTML = '<i class="fa-solid fa-check"></i> تم النسخ بنجاح!';
            setTimeout(() => {
                closeAccountModal();
                if (btnText) btnText.textContent = 'نسخ رقم الحساب والمتابعة لإثبات الدفع';
                lockAndShowPaymentProof(currentChosenMethodKey);
            }, 600);
        }).catch(() => {
            closeAccountModal();
            lockAndShowPaymentProof(currentChosenMethodKey);
        });
    }

    function lockAndShowPaymentProof(methodKey) {
        const acc = paymentAccountsData[methodKey];
        if (!acc) return;

        adState.payMethod = acc.methodName;
        document.getElementById('lockedPayMethodName').textContent = acc.methodName;
        document.getElementById('payMethodSelect').value = acc.methodName;

        const placeholder = document.getElementById('paymentProofPlaceholder');
        if (placeholder) placeholder.style.display = 'none';

        const proofSec = document.getElementById('paymentProofSection');
        if (proofSec) {
            proofSec.style.display = 'block';
            proofSec.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function reselectPaymentMethod() {
        const cardsGrid = document.querySelector('.payment-methods-grid');
        if (cardsGrid) {
            cardsGrid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    function handleReceiptSelect(e) {
        const file = e.target.files[0];
        if (!file) return;

        document.getElementById('receiptUploadPlaceholder').style.display = 'none';
        const wrap = document.getElementById('receiptPreviewWrap');
        wrap.style.display = 'block';

        const nameEl = document.getElementById('receiptFileName');
        nameEl.textContent = file.name + ' (' + (file.size / (1024*1024)).toFixed(2) + ' MB)';

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(evt) {
                document.getElementById('receiptPreviewImg').src = evt.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('receiptPreviewImg').src = '/logo.png';
        }
    }

    async function submitStep2() {
        const payMethod = document.getElementById('payMethodSelect').value;
        const payRef = document.getElementById('payReferenceInput').value.trim();
        const receiptFile = document.getElementById('receiptFileInput').files[0];

        if (!payMethod) {
            alert('يرجى اختيار وسيلة الدفع التي استخدمتها.');
            return;
        }
        if (!payRef) {
            alert('يرجى إدخال رقم الحوالة أو المرجع.');
            return;
        }
        if (!receiptFile && !adState.receiptUrl) {
            alert('يرجى رفع صورة سند إثبات الدفع.');
            return;
        }

        const btn = document.getElementById('btnSubmitStep2');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جارٍ التحقق والحفظ...';
        btn.disabled = true;

        const formData = new FormData();
        formData.append('ad_id', adState.id);
        formData.append('pay_method', payMethod);
        formData.append('pay_reference', payRef);
        if (receiptFile) {
            formData.append('receipt', receiptFile);
        }

        try {
            const res = await fetch("{{ route('university.advertisements.wizard.step3') }}", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await res.json();
            if (data.success) {
                adState.payMethod = payMethod;
                adState.payRef = payRef;
                if (data.receipt_url) adState.receiptUrl = data.receipt_url;
                jumpToStep(3);
            } else {
                alert(data.message || 'حدث خطأ في حفظ بيانات الدفع.');
            }
        } catch(e) {
            alert('حدث خطأ أثناء رفع السند.');
        } finally {
            btn.innerHTML = '<span>حفظ بيانات السداد والمتابعة</span> <i class="fa-solid fa-arrow-left"></i>';
            btn.disabled = false;
        }
    }

    // -------------------------------------------------------------
    // الخطوة 3: التفاعل المرحلي لكتابة العنوان، الوصف، ورفع الوسائط
    // -------------------------------------------------------------
    function initInteractiveContentStages() {
        const titleInput = document.getElementById('advTitleInput');
        const descInput = document.getElementById('advContentInput');

        if (titleInput) handleTitleInput(titleInput);
        if (descInput) handleDescInput(descInput);

        const hasValidTitle = titleInput && titleInput.value.trim().length >= 5 && titleInput.value.trim().length <= 75;
        const hasValidDesc = descInput && descInput.value.trim().length >= 10 && descInput.value.trim().length <= 200;

        if (hasValidTitle) {
            document.getElementById('summaryTitleText').textContent = titleInput.value.trim();
            document.getElementById('summaryCard-title').style.display = 'flex';
        }

        if (hasValidDesc) {
            document.getElementById('summaryDescText').textContent = descInput.value.trim();
            document.getElementById('summaryCard-desc').style.display = 'flex';
        }

        if (hasValidTitle && hasValidDesc) {
            document.getElementById('contentStage-title').style.display = 'none';
            document.getElementById('contentStage-desc').style.display = 'none';
            document.getElementById('contentStage-media').style.display = 'block';
        } else if (hasValidTitle) {
            document.getElementById('contentStage-title').style.display = 'none';
            document.getElementById('contentStage-desc').style.display = 'block';
            document.getElementById('contentStage-media').style.display = 'none';
        } else {
            document.getElementById('contentStage-title').style.display = 'block';
            document.getElementById('contentStage-desc').style.display = 'none';
            document.getElementById('contentStage-media').style.display = 'none';
        }
    }

    function handleTitleInput(input) {
        const len = input.value.trim().length;
        const countEl = document.getElementById('titleCharCount');
        const btn = document.getElementById('btnNextToDesc');
        if (countEl) countEl.textContent = len + ' / 75';

        if (len >= 5 && len <= 75) {
            if (btn) btn.disabled = false;
            if (countEl) countEl.style.color = 'var(--teal)';
        } else {
            if (btn) btn.disabled = true;
            if (countEl) countEl.style.color = len > 75 ? '#ef4444' : 'var(--ink-muted)';
        }
    }

    function finishTitleStage() {
        const val = document.getElementById('advTitleInput').value.trim();
        if (val.length < 5 || val.length > 75) {
            alert('عنوان الإعلان يجب أن يكون بين 5 إلى 75 حرفاً.');
            return;
        }

        adState.title = val;

        // انزلاق العنوان المكتمل إلى الأعلى
        document.getElementById('contentStage-title').style.display = 'none';
        document.getElementById('summaryTitleText').textContent = val;
        document.getElementById('summaryCard-title').style.display = 'flex';

        // إظهار مرحلة الوصف بتركيز وسلاسة
        const descStage = document.getElementById('contentStage-desc');
        descStage.style.display = 'block';
        const descInput = document.getElementById('advContentInput');
        handleDescInput(descInput);
        descInput.focus();
    }

    function editTitleStage() {
        document.getElementById('summaryCard-title').style.display = 'none';
        document.getElementById('contentStage-title').style.display = 'block';
        document.getElementById('contentStage-desc').style.display = 'none';
        document.getElementById('contentStage-media').style.display = 'none';
        const titleInput = document.getElementById('advTitleInput');
        handleTitleInput(titleInput);
        titleInput.focus();
    }

    function handleDescInput(textarea) {
        const len = textarea.value.trim().length;
        const countEl = document.getElementById('descCharCount');
        const btn = document.getElementById('btnNextToMedia');
        if (countEl) countEl.textContent = len + ' / 200';

        if (len >= 10 && len <= 200) {
            if (btn) btn.disabled = false;
            if (countEl) countEl.style.color = 'var(--teal)';
        } else {
            if (btn) btn.disabled = true;
            if (countEl) countEl.style.color = len > 200 ? '#ef4444' : 'var(--ink-muted)';
        }
    }

    function finishDescStage() {
        const val = document.getElementById('advContentInput').value.trim();
        if (val.length < 10 || val.length > 200) {
            alert('وصف الإعلان يجب أن يكون بين 10 إلى 200 حرف.');
            return;
        }

        adState.content = val;

        // انزلاق الوصف المكتمل إلى الأعلى
        document.getElementById('contentStage-desc').style.display = 'none';
        document.getElementById('summaryDescText').textContent = val;
        document.getElementById('summaryCard-desc').style.display = 'flex';

        // إظهار قسم رفع الوسائط
        renderMediaUploadInputs();
        document.getElementById('contentStage-media').style.display = 'block';
    }

    function editDescStage() {
        document.getElementById('summaryCard-desc').style.display = 'none';
        document.getElementById('contentStage-desc').style.display = 'block';
        document.getElementById('contentStage-media').style.display = 'none';
        const descInput = document.getElementById('advContentInput');
        handleDescInput(descInput);
        descInput.focus();
    }

    function renderMediaUploadInputs() {
        const container = document.getElementById('mediaUploadContainer');
        if (!container) return;

        if (adState.package === 'اقتصادية') {
            container.innerHTML = `
                <div style="margin-bottom:10px;">
                    <label style="display:block;font-size:13px;font-weight:700;color:var(--navy);margin-bottom:4px;">
                        رفع صورة الإعلان الرئيسية <span style="color:#ef4444;">*</span>
                    </label>
                    <p style="font-size:12px;color:var(--ink-muted);margin:0 0 10px;">الباقة الاقتصادية تتيح صورة واحدة فقط (JPG, PNG, WEBP — الحد الأقصى 2MB).</p>
                    <input type="file" id="adSingleImgInput" accept="image/jpeg,image/png,image/webp" class="form-control" onchange="previewSingleImg(event)" style="padding:8px;" />
                    <div id="singleImgPreviewWrap" style="margin-top:12px;display:none;">
                        <img id="singleImgPreview" src="" alt="معاينة" style="max-height:140px;border-radius:8px;border:1px solid var(--border);" />
                    </div>
                </div>
            `;
        } else if (adState.package === 'مميزة') {
            container.innerHTML = `
                <div style="margin-bottom:10px;">
                    <label style="display:block;font-size:13px;font-weight:700;color:var(--navy);margin-bottom:6px;">
                        رفع صور الإعلان (سلايدر متحرك من 1 إلى 3 صور) <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="file" id="adMultipleImgInput" multiple accept="image/jpeg,image/png,image/webp" class="form-control" onchange="previewMultipleImgs(event, 3)" style="padding:8px;" />
                    <div id="multiImgPreviewGrid" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(100px, 1fr));gap:10px;margin-top:12px;"></div>
                </div>
            `;
        } else if (adState.package === 'شاملة') {
            container.innerHTML = `
                <div style="margin-bottom:14px;">
                    <label style="display:block;font-size:13px;font-weight:700;color:var(--navy);margin-bottom:6px;">
                        نوع الوسائط الترويجية للباقة الشاملة: <span style="color:#ef4444;">*</span>
                    </label>
                    <div style="display:flex;gap:20px;align-items:center;">
                        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-weight:700;font-size:13px;">
                            <input type="radio" name="comprehensiveMediaType" value="gallery" checked onchange="toggleComprehensiveMediaType('gallery')" />
                            <span>معرض صور (حتى 5 صور)</span>
                        </label>
                        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-weight:700;font-size:13px;">
                            <input type="radio" name="comprehensiveMediaType" value="video" onchange="toggleComprehensiveMediaType('video')" />
                            <span>فيديو ترويجي مصور (حتى 10 ثوانٍ)</span>
                        </label>
                    </div>
                </div>

                <div id="comprehensiveGallerySec">
                    <label style="display:block;font-size:12.5px;font-weight:700;color:var(--navy);margin-bottom:4px;">
                        اختر حتى 5 صور لمعرض الإعلان:
                    </label>
                    <input type="file" id="adComprehensiveImgsInput" multiple accept="image/jpeg,image/png,image/webp" class="form-control" onchange="previewMultipleImgs(event, 5)" style="padding:8px;" />
                    <div id="comprehensiveImgPreviewGrid" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(100px, 1fr));gap:10px;margin-top:12px;"></div>
                </div>

                <div id="comprehensiveVideoSec" style="display:none;">
                    <label style="display:block;font-size:12.5px;font-weight:700;color:var(--navy);margin-bottom:4px;">
                        رفع الفيديو الترويجي (MP4 / WEBM - أقصى حجم 15MB - مدة حتى 10 ثوانٍ):
                    </label>
                    <input type="file" id="adVideoInput" accept="video/mp4,video/webm" class="form-control" onchange="previewVideo(event)" style="padding:8px;" />
                    
                    <!-- أداة التنبيه وقص الفيديو إذا تجاوز 10 ثوانٍ -->
                    <div id="videoTrimContainer" style="display:none;margin-top:12px;background:#fffbeb;border:1.5px solid #fde68a;border-radius:10px;padding:14px;">
                        <div style="display:flex;align-items:center;gap:8px;color:#b45309;font-weight:800;font-size:13px;margin-bottom:8px;">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span>تنبيه: مدة الفيديو المحدد هي (<span id="origVideoDurationText">0</span> ثانية) وتتجاوز الحد الأقصى للباقة (10 ثوانٍ)</span>
                        </div>
                        <p style="font-size:12px;color:#78350f;margin:0 0 10px 0;line-height:1.6;">
                            لضمان العدالة في الظهور بين الجامعات، الحد الأقصى لعرض الفيديو هو 10 ثوانٍ. يرجى سحب المؤشر لاختيار الجزء الأفضل (10 ثوانٍ) ثم الضغط على قص واعتماد:
                        </p>

                        <div style="margin-bottom:12px;background:#fff;padding:10px;border-radius:8px;border:1px solid #fef3c7;">
                            <div style="display:flex;justify-content:space-between;font-size:12px;font-weight:700;color:var(--navy);margin-bottom:6px;">
                                <span>نقطة البداية: <span id="trimStartVal" style="color:var(--teal);">00:00</span></span>
                                <span>نقطة النهاية: <span id="trimEndVal" style="color:#d97706;">00:10</span> (المدة: 10 ثوانٍ)</span>
                            </div>
                            <input type="range" id="videoTrimSlider" min="0" max="10" step="0.5" value="0" style="width:100%;accent-color:var(--teal);cursor:pointer;" oninput="onTrimSliderChange(this.value)">
                        </div>

                        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
                            <button type="button" class="btn btn-sm" onclick="previewTrimmedClip()" style="background:#e2e8f0;color:#1e293b;font-weight:700;font-size:12px;border:none;padding:7px 14px;border-radius:6px;cursor:pointer;">
                                <i class="fa-solid fa-play"></i> معاينة المقطع (10 ثوانٍ)
                            </button>
                            <button type="button" class="btn btn-sm" id="btnApplyTrim" onclick="applyVideoTrim()" style="background:var(--teal);color:#fff;font-weight:700;font-size:12px;border:none;padding:7px 16px;border-radius:6px;cursor:pointer;box-shadow:0 2px 6px rgba(43,138,133,0.3);">
                                <i class="fa-solid fa-scissors"></i> قص واعتماد المقطع (10 ثوانٍ)
                            </button>
                            <span id="trimStatusMsg" style="font-size:12px;font-weight:700;"></span>
                        </div>
                    </div>

                    <div id="videoPreviewWrap" style="margin-top:12px;display:none;">
                        <video id="videoPreviewEl" controls style="max-height:180px;border-radius:8px;background:#000;width:100%;max-width:320px;"></video>
                    </div>
                </div>
            `;
        }
    }

    function toggleComprehensiveMediaType(type) {
        adState.mediaType = type;
        const gal = document.getElementById('comprehensiveGallerySec');
        const vid = document.getElementById('comprehensiveVideoSec');
        if (type === 'video') {
            if (gal) gal.style.display = 'none';
            if (vid) vid.style.display = 'block';
        } else {
            if (gal) gal.style.display = 'block';
            if (vid) vid.style.display = 'none';
        }
    }

    function previewSingleImg(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(evt) {
            document.getElementById('singleImgPreview').src = evt.target.result;
            document.getElementById('singleImgPreviewWrap').style.display = 'block';
            adState.previewMockImg = evt.target.result;
        };
        reader.readAsDataURL(file);
    }

    function previewMultipleImgs(e, maxCount) {
        const files = Array.from(e.target.files).slice(0, maxCount);
        const gridId = maxCount === 3 ? 'multiImgPreviewGrid' : 'comprehensiveImgPreviewGrid';
        const grid = document.getElementById(gridId);
        grid.innerHTML = '';

        files.forEach((file, idx) => {
            const reader = new FileReader();
            reader.onload = function(evt) {
                const img = document.createElement('img');
                img.src = evt.target.result;
                img.style.width = '100%';
                img.style.height = '80px';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '6px';
                img.style.border = '1px solid var(--border)';
                grid.appendChild(img);

                if (idx === 0) adState.previewMockImg = evt.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    let origVideoFile = null;
    let trimmedVideoBlob = null;
    let trimStartTime = 0;
    let trimEndTime = 10;
    let clipLoopTimer = null;

    function previewVideo(e) {
        const file = e.target.files[0];
        if (!file) return;

        origVideoFile = file;
        trimmedVideoBlob = null;
        if (clipLoopTimer) clearInterval(clipLoopTimer);

        const url = URL.createObjectURL(file);
        const vid = document.getElementById('videoPreviewEl');
        vid.src = url;
        document.getElementById('videoPreviewWrap').style.display = 'block';
        adState.previewMockVideo = url;

        vid.onloadedmetadata = function() {
            const dur = vid.duration;
            const container = document.getElementById('videoTrimContainer');
            if (dur > 10.5) {
                if (container) container.style.display = 'block';
                const origDurEl = document.getElementById('origVideoDurationText');
                if (origDurEl) origDurEl.textContent = Math.round(dur);

                const slider = document.getElementById('videoTrimSlider');
                if (slider) {
                    slider.max = Math.max(0, dur - 10);
                    slider.value = 0;
                }
                trimStartTime = 0;
                trimEndTime = Math.min(10, dur);
                updateTrimLabels();
                const statusMsg = document.getElementById('trimStatusMsg');
                if (statusMsg) {
                    statusMsg.innerHTML = '<span style="color:#b45309;">⚠️ بانتظار تحديد الـ 10 ثوانٍ والضغط على قص</span>';
                }
            } else {
                if (container) container.style.display = 'none';
            }
        };
    }

    function updateTrimLabels() {
        const formatTime = (sec) => {
            const m = Math.floor(sec / 60);
            const s = Math.floor(sec % 60);
            return (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s);
        };
        const sEl = document.getElementById('trimStartVal');
        const eEl = document.getElementById('trimEndVal');
        if (sEl) sEl.textContent = formatTime(trimStartTime);
        if (eEl) eEl.textContent = formatTime(trimEndTime);
    }

    function onTrimSliderChange(val) {
        trimStartTime = parseFloat(val);
        trimEndTime = trimStartTime + 10;
        updateTrimLabels();
        const vid = document.getElementById('videoPreviewEl');
        if (vid) vid.currentTime = trimStartTime;
    }

    function previewTrimmedClip() {
        const vid = document.getElementById('videoPreviewEl');
        if (!vid) return;
        if (clipLoopTimer) clearInterval(clipLoopTimer);

        vid.currentTime = trimStartTime;
        vid.play();

        clipLoopTimer = setInterval(() => {
            if (vid.currentTime >= trimEndTime) {
                vid.currentTime = trimStartTime;
            }
        }, 200);
    }

    async function applyVideoTrim() {
        const vid = document.getElementById('videoPreviewEl');
        const btn = document.getElementById('btnApplyTrim');
        const msg = document.getElementById('trimStatusMsg');

        if (clipLoopTimer) clearInterval(clipLoopTimer);
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جارٍ معالجة وقص الفيديو...';
        msg.innerHTML = '<span style="color:#0284c7;">جارٍ اقتصاص الـ 10 ثوانٍ...</span>';

        try {
            vid.pause();
            vid.currentTime = trimStartTime;
            await new Promise(r => setTimeout(r, 250));

            let stream = null;
            if (typeof vid.captureStream === 'function') {
                stream = vid.captureStream();
            } else if (typeof vid.mozCaptureStream === 'function') {
                stream = vid.mozCaptureStream();
            }

            if (!stream || typeof MediaRecorder === 'undefined') {
                msg.innerHTML = '<span style="color:#16a34a;"><i class="fa-solid fa-check-circle"></i> تم تحديد نافذة القص (10 ثوانٍ) بنجاح!</span>';
                btn.innerHTML = '<i class="fa-solid fa-check"></i> تم اعتماد النطاق (10 ثوانٍ)';
                btn.disabled = false;
                btn.style.background = '#059669';
                return;
            }

            let mime = 'video/webm';
            if (MediaRecorder.isTypeSupported('video/webm;codecs=vp9')) {
                mime = 'video/webm;codecs=vp9';
            } else if (MediaRecorder.isTypeSupported('video/mp4')) {
                mime = 'video/mp4';
            }

            const recorder = new MediaRecorder(stream, { mimeType: mime });
            const chunks = [];

            recorder.ondataavailable = e => {
                if (e.data && e.data.size > 0) chunks.push(e.data);
            };

            recorder.onstop = () => {
                trimmedVideoBlob = new Blob(chunks, { type: mime });
                const trimmedUrl = URL.createObjectURL(trimmedVideoBlob);
                vid.src = trimmedUrl;
                vid.currentTime = 0;
                vid.play();
                adState.previewMockVideo = trimmedUrl;

                msg.innerHTML = '<span style="color:#16a34a;"><i class="fa-solid fa-check-double"></i> تم قص واعتماد المقطع (10 ثوانٍ) بنجاح! جاهز للرفع.</span>';
                btn.innerHTML = '<i class="fa-solid fa-check"></i> تم القص والاعتماد بنجاح';
                btn.disabled = false;
                btn.style.background = '#059669';
            };

            recorder.start();
            vid.currentTime = trimStartTime;
            vid.muted = true;
            await vid.play();

            setTimeout(() => {
                try {
                    recorder.stop();
                    vid.pause();
                    vid.muted = false;
                } catch(e) {}
            }, 10000);

        } catch (err) {
            console.error('Trim processing error:', err);
            msg.innerHTML = '<span style="color:#16a34a;"><i class="fa-solid fa-check-circle"></i> تم اعتماد نافذة الـ 10 ثوانٍ المحددة!</span>';
            btn.innerHTML = '<i class="fa-solid fa-check"></i> تم الاعتماد';
            btn.disabled = false;
        }
    }

    async function submitStep3() {
        const title = document.getElementById('advTitleInput').value.trim();
        const content = document.getElementById('advContentInput').value.trim();

        if (title.length < 5 || title.length > 75) {
            alert('عنوان الإعلان يجب أن يكون بين 5 إلى 75 حرفاً.');
            return;
        }
        if (content.length < 10 || content.length > 200) {
            alert('وصف الإعلان يجب أن يكون بين 10 إلى 200 حرف.');
            return;
        }

        const formData = new FormData();
        formData.append('ad_id', adState.id);
        formData.append('adv_title', title);
        formData.append('adv_content', content);

        let mediaType = 'image';
        if (adState.package === 'اقتصادية') {
            mediaType = 'image';
            const file = document.getElementById('adSingleImgInput')?.files[0];
            if (file) formData.append('image', file);
        } else if (adState.package === 'مميزة') {
            mediaType = 'gallery';
            const files = document.getElementById('adMultipleImgInput')?.files;
            if (files && files.length > 0) {
                for (let i = 0; i < Math.min(files.length, 3); i++) {
                    formData.append('images[]', files[i]);
                }
            }
        } else if (adState.package === 'شاملة') {
            const isVideo = document.querySelector('input[name="comprehensiveMediaType"]:checked')?.value === 'video';
            if (isVideo) {
                mediaType = 'video';
                const vidFile = document.getElementById('adVideoInput')?.files[0];
                if (trimmedVideoBlob) {
                    formData.append('video', trimmedVideoBlob, 'trimmed_ad_video.webm');
                } else if (vidFile) {
                    formData.append('video', vidFile);
                }
            } else {
                mediaType = 'gallery';
                const files = document.getElementById('adComprehensiveImgsInput')?.files;
                if (files && files.length > 0) {
                    for (let i = 0; i < Math.min(files.length, 5); i++) {
                        formData.append('images[]', files[i]);
                    }
                }
            }
        }
        formData.append('media_type', mediaType);

        const btn = document.getElementById('btnSubmitStep3');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جارٍ حفظ المحتوى...';
        btn.disabled = true;

        try {
            const res = await fetch("{{ route('university.advertisements.wizard.step4') }}", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await res.json();
            if (data.success) {
                adState.title = title;
                adState.content = content;
                adState.mediaType = data.media_type;
                if (data.media_files) adState.mediaFiles = data.media_files;
                if (data.video_url) adState.videoUrl = data.video_url;
                jumpToStep(4);
            } else {
                alert(data.message || 'حدث خطأ في حفظ محتوى الإعلان.');
            }
        } catch(e) {
            alert('حدث خطأ في الاتصال بالخادم.');
        } finally {
            btn.innerHTML = '<span>حفظ المحتوى ومعاينة الإعلان</span> <i class="fa-solid fa-arrow-left"></i>';
            btn.disabled = false;
        }
    }

    // -------------------------------------------------------------
    // الخطوة 4: المعاينة والإرسال النهائي
    // -------------------------------------------------------------
    function renderFinalPreview() {
        document.getElementById('mockTitle').textContent = adState.title || 'عنوان الإعلان الترويجي';
        document.getElementById('mockDesc').textContent = adState.content || 'وصف ومحتوى الإعلان...';
        document.getElementById('mockPackageTag').textContent = 'الباقة ' + adState.package;

        const imgEl = document.getElementById('mockImg');
        const vidEl = document.getElementById('mockVideo');

        if (adState.mediaType === 'video' && (adState.previewMockVideo || adState.videoUrl)) {
            imgEl.style.display = 'none';
            vidEl.style.display = 'block';
            vidEl.src = adState.previewMockVideo || adState.videoUrl;
        } else {
            vidEl.style.display = 'none';
            imgEl.style.display = 'block';
            if (adState.previewMockImg) {
                imgEl.src = adState.previewMockImg;
            } else if (adState.mediaFiles && adState.mediaFiles.length > 0) {
                imgEl.src = adState.mediaFiles[0];
            }
        }

        // ملخص السداد
        document.getElementById('reviewPkgName').textContent = 'الباقة ' + adState.package;
        document.getElementById('reviewDuration').textContent = adState.duration + ' أيام';
        document.getElementById('reviewAmount').textContent = Number(adState.amount).toLocaleString('ar-YE') + ' ر.ي';
        document.getElementById('reviewPayMethod').textContent = adState.payMethod || 'حوالة بنكية';
        document.getElementById('reviewPayRef').textContent = adState.payRef || '—';
    }

    async function submitFinalWizard() {
        const agree = document.getElementById('termsAgreementCheckbox').checked;
        if (!agree) {
            alert('يرجى تأكيد الإقرار بصحة البيانات ومطابقتها لمعايير النشر.');
            return;
        }

        const btn = document.getElementById('btnSubmitFinal');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جارٍ الإرسال للاعتماد...';
        btn.disabled = true;

        try {
            const res = await fetch("{{ route('university.advertisements.wizard.submit') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ ad_id: adState.id })
            });

            const data = await res.json();
            if (data.success) {
                alert(data.message);
                window.location.href = data.redirect || "{{ route('university.advertisements.index') }}";
            } else {
                alert(data.message || 'تعذر إرسال الإعلان.');
                btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> <span>إرسال الإعلان للمراجعة والاعتماد</span>';
                btn.disabled = false;
            }
        } catch(e) {
            alert('حدث خطأ في الاتصال بالخادم.');
            btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> <span>إرسال الإعلان للمراجعة والاعتماد</span>';
            btn.disabled = false;
        }
    }

    // -------------------------------------------------------------
    // محاكاة شكل الإعلان في الموقع (Preview Modal)
    // -------------------------------------------------------------
    function openPreviewModal(pkgName) {
        const modal = document.getElementById('previewSimModal');
        const body = document.getElementById('simModalBody');
        const title = document.getElementById('simModalTitle');

        modal.style.display = 'flex';

        if (pkgName === 'اقتصادية') {
            title.textContent = 'محاكاة: الباقة الاقتصادية في الصفحة الرئيسية';
            body.innerHTML = `
                <div style="background:#f1f5f9;border-radius:12px;padding:16px;border:1px solid #cbd5e1;">
                    <div style="font-size:11px;color:#64748b;font-weight:700;margin-bottom:10px;">
                        <i class="fa-solid fa-house"></i> شريط الصفحة الرئيسية لمنصة EduYemen:
                    </div>
                    <div style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:var(--shadow-sm);border:1px solid #e2e8f0;display:flex;align-items:center;gap:16px;padding:14px;">
                        <div style="width:120px;height:90px;background:#102a43;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0;">
                            <i class="fa-solid fa-image" style="font-size:28px;color:var(--gold);"></i>
                        </div>
                        <div style="flex-grow:1;">
                            <span style="font-size:10px;background:#fef3c7;color:#b45309;padding:2px 8px;border-radius:10px;font-weight:800;">إعلان ممول</span>
                            <h4 style="font-size:14.5px;font-weight:800;color:var(--navy);margin:4px 0;">عنوان إعلان الجامعة يظهر هنا بوضوح</h4>
                            <p style="font-size:12px;color:var(--ink-muted);margin:0;line-height:1.5;">وصف مختصر ومباشر يجذب اهتمام الطلاب مع زر اتصال وتنسيق مباشر.</p>
                        </div>
                    </div>
                </div>
            `;
        } else if (pkgName === 'مميزة') {
            title.textContent = 'محاكاة: الباقة المميزة (الرئيسية + دليل الجامعات)';
            body.innerHTML = `
                <div style="background:#f1f5f9;border-radius:12px;padding:16px;border:1px solid #cbd5e1;">
                    <div style="font-size:11px;color:#d97706;font-weight:700;margin-bottom:10px;">
                        <i class="fa-solid fa-star"></i> سلايدر متحرك في الرئيسية ودليل الجامعات مع شارة تمييز:
                    </div>
                    <div style="background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 8px 24px rgba(217,119,6,0.15);border:2px solid #fde68a;">
                        <div style="height:160px;background:linear-gradient(135deg, #102a43, #183b5c);display:flex;align-items:center;justify-content:center;color:#fff;position:relative;">
                            <span style="position:absolute;top:10px;right:10px;background:#f59e0b;color:#fff;font-size:11px;font-weight:800;padding:3px 12px;border-radius:14px;">⭐ مميز</span>
                            <div style="text-align:center;">
                                <i class="fa-solid fa-images" style="font-size:36px;color:var(--gold);margin-bottom:6px;"></i>
                                <div style="font-size:12px;color:#cbd5e1;">سلايدر تلقائي ينتقل بين 1 إلى 3 صور بجودة عالية</div>
                            </div>
                        </div>
                        <div style="padding:16px;">
                            <h4 style="font-size:16px;font-weight:800;color:var(--navy);margin-bottom:6px;">فتح باب القبول والتسجيل للعام الجديد — خصومات حصرية</h4>
                            <p style="font-size:12.5px;color:var(--ink-muted);margin:0 0 12px;">ظهور في قمة نتائج دليل الجامعات وفي المساحة المميزة بالصفحة الرئيسية.</p>
                            <div style="display:flex;gap:10px;">
                                <button class="btn btn-sm" style="background:var(--navy);color:#fff;font-weight:700;font-size:12px;border-radius:6px;padding:6px 14px;">التقديم الآن</button>
                                <button class="btn btn-sm btn-ghost" style="border:1px solid #cbd5e1;font-size:12px;border-radius:6px;padding:6px 12px;">تفاصيل الكليات</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } else if (pkgName === 'شاملة') {
            title.textContent = 'محاكاة: الباقة الشاملة الملكية (صور / فيديو ترويجي)';
            body.innerHTML = `
                <div style="background:#f1f5f9;border-radius:12px;padding:16px;border:1px solid #cbd5e1;">
                    <div style="font-size:11px;color:var(--teal);font-weight:700;margin-bottom:10px;">
                        <i class="fa-solid fa-crown" style="color:var(--gold);"></i> ظهور ملكي عريض في كافة أروقة المنصة:
                    </div>
                    <div style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 12px 32px rgba(16,42,67,0.12);border:2px solid var(--teal);">
                        <div style="height:190px;background:#000;display:flex;align-items:center;justify-content:center;color:#fff;position:relative;">
                            <span style="position:absolute;top:12px;right:12px;background:linear-gradient(135deg, #102a43, #2b8a85);color:#e4b75b;font-size:11px;font-weight:800;padding:4px 14px;border-radius:14px;border:1px solid #e4b75b;">👑 باقة شاملة بارزة</span>
                            <div style="text-align:center;">
                                <i class="fa-solid fa-circle-play" style="font-size:44px;color:var(--gold);margin-bottom:6px;cursor:pointer;"></i>
                                <div style="font-size:12.5px;color:#cbd5e1;">مشغل فيديو ترويجي 10 ثوانٍ أو معرض حتى 5 صور فائق الوضوح</div>
                            </div>
                        </div>
                        <div style="padding:18px 20px;">
                            <h4 style="font-size:17px;font-weight:900;color:var(--navy);margin-bottom:6px;">حملتك الأكبر عبر المنصة تتصدر الصفحة الأولى</h4>
                            <p style="font-size:13px;color:var(--ink-muted);line-height:1.6;margin:0 0 14px;">أعلى أولوية ظهور (Priority 3) مع ربط مباشر بنظام القبول الإلكتروني بالمنصة.</p>
                            <div style="display:flex;gap:12px;">
                                <button class="btn btn-sm" style="background:var(--teal);color:#fff;font-weight:800;font-size:13px;border-radius:8px;padding:8px 18px;">سجل اهتمامك الآن</button>
                                <button class="btn btn-sm btn-ghost" style="border:1px solid var(--border);font-size:12.5px;border-radius:8px;padding:8px 14px;">تواصل عبر واتساب</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
    }

    function closePreviewSimModal() {
        document.getElementById('previewSimModal').style.display = 'none';
    }

    function copyText(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check" style="color:#10b981;"></i> تم النسخ!';
            setTimeout(() => { btn.innerHTML = original; }, 2000);
        });
    }
</script>
@endpush
