@extends('layouts.app')

@section('title', 'تسجيل الدخول — منصة EduYemen')

@section('styles')
<style>
    /* الخط الطبيعي المعتمد والمستخدم في المنصة: Cairo و Tajawal */
    body,
    .auth-page-wrapper,
    .auth-main-container,
    .auth-main-container input,
    .auth-main-container button,
    .auth-main-container select,
    .auth-main-container textarea,
    .auth-showcase-side,
    .auth-form-side {
        font-family: 'Cairo', 'Tajawal', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
    }

    /* ========================================================
       تصميم صفحة تسجيل الدخول بأبعاد متناسقة ومريحة لكافة الشاشات
       ======================================================== */
    body {
        background: radial-gradient(circle at 50% 25%, #e9eff7 0%, #d8e4f0 100%) !important;
        min-height: 100vh;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    .auth-page-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem 1.25rem;
        box-sizing: border-box;
    }

    /* الحاوية الرئيسية بأبعاد متوازنة تمنع ظهور شريط التمرير على الشاشات العادية */
    .auth-main-container {
        width: 100%;
        max-width: 980px;
        margin: auto;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 16px 48px rgba(11, 42, 74, 0.16), 0 0 24px rgba(197, 155, 39, 0.08);
        border: 1px solid rgba(197, 155, 39, 0.28);
        background: #ffffff;
        box-sizing: border-box;
    }

    /* ========================================================
       قسم معلومات المنصة (الجانب الأيمن) - بدون فراغات سفلية ومع خلفيات جمالية
       ======================================================== */
    .auth-showcase-side {
        background: linear-gradient(145deg, #061e36 0%, #0b3d6b 55%, #10528f 100%);
        color: #ffffff;
        padding: 1.85rem 2rem;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 100%;
        box-sizing: border-box;
    }

    /* شبكة خلفية هندسية شفافة بطابع أكاديمي هادئ */
    .auth-showcase-grid-bg {
        position: absolute;
        inset: 0;
        background-image: 
            radial-gradient(rgba(240, 200, 66, 0.13) 1px, transparent 1px),
            radial-gradient(rgba(255, 255, 255, 0.07) 1px, transparent 1px);
        background-size: 24px 24px;
        background-position: 0 0, 12px 12px;
        opacity: 0.65;
        pointer-events: none;
        z-index: 1;
    }

    /* أشكال إضاءة ضبابية متحركة بنعومة في الخلفية (Ambient Floating Orbs) */
    .auth-ambient-orb {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        filter: blur(50px);
        z-index: 1;
    }

    .orb-1 {
        width: 220px;
        height: 220px;
        top: -40px;
        left: -30px;
        background: radial-gradient(circle, rgba(240, 200, 66, 0.28) 0%, transparent 70%);
        animation: floatOrb1 16s ease-in-out infinite;
    }

    .orb-2 {
        width: 250px;
        height: 250px;
        bottom: -50px;
        right: -40px;
        background: radial-gradient(circle, rgba(14, 165, 233, 0.24) 0%, transparent 70%);
        animation: floatOrb2 20s ease-in-out infinite alternate;
    }

    .orb-3 {
        width: 160px;
        height: 160px;
        top: 48%;
        left: 55%;
        background: radial-gradient(circle, rgba(197, 155, 39, 0.16) 0%, transparent 70%);
        animation: floatOrb3 18s ease-in-out infinite;
    }

    @keyframes floatOrb1 {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(22px, 18px) scale(1.12); }
    }

    @keyframes floatOrb2 {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(-22px, -18px) scale(1.08); }
    }

    @keyframes floatOrb3 {
        0%, 100% { transform: translate(0, 0) scale(0.92); opacity: 0.45; }
        50% { transform: translate(-14px, 16px) scale(1.15); opacity: 0.85; }
    }

    /* أشكال هندسية زخرفية ناعمة تدور بهدوء وبدون تشتيت */
    .floating-geo-shape {
        position: absolute;
        pointer-events: none;
        opacity: 0.14;
        border: 1.5px solid rgba(255, 255, 255, 0.4);
        border-radius: 10px;
        z-index: 1;
    }

    .geo-1 {
        width: 36px;
        height: 36px;
        top: 14%;
        right: 12%;
        transform: rotate(20deg);
        animation: rotateGeo 24s linear infinite;
        border-color: rgba(240, 200, 66, 0.55);
    }

    .geo-2 {
        width: 28px;
        height: 28px;
        bottom: 22%;
        left: 12%;
        border-radius: 50%;
        animation: floatGeo 14s ease-in-out infinite alternate;
        background: rgba(255, 255, 255, 0.05);
    }

    .geo-3 {
        width: 20px;
        height: 20px;
        top: 58%;
        right: 22%;
        transform: rotate(45deg);
        animation: rotateGeoRev 18s linear infinite;
        border-color: rgba(14, 165, 233, 0.55);
    }

    @keyframes rotateGeo {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    @keyframes rotateGeoRev {
        from { transform: rotate(0deg); }
        to { transform: rotate(-360deg); }
    }

    @keyframes floatGeo {
        0% { transform: translateY(0); }
        100% { transform: translateY(-16px); }
    }

    /* الحاوية الداخلية لمحتوى المنصة */
    .auth-showcase-content {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        gap: 1.15rem;
    }

    .auth-showcase-logo-box {
        width: 52px;
        height: 52px;
        background: rgba(255, 255, 255, 0.15);
        border: 1.5px solid rgba(240, 200, 66, 0.5);
        border-radius: 14px;
        padding: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
    }

    .auth-showcase-logo-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .auth-platform-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(240, 200, 66, 0.18);
        border: 1px solid rgba(240, 200, 66, 0.5);
        color: #fce484;
        font-size: 0.74rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        width: fit-content;
    }

    .auth-showcase-title {
        font-size: 1.28rem;
        font-weight: 800;
        color: #ffffff;
        line-height: 1.4;
        margin: 0.5rem 0 0.4rem;
    }

    .auth-showcase-desc {
        font-size: 0.83rem;
        color: rgba(255, 255, 255, 0.84);
        line-height: 1.65;
        margin: 0 0 1rem;
    }

    /* بطاقات مميزات المنصة الأكاديمية (الـ 4 بطاقات مع تباعد رحب ومظهر متناسق) */
    .auth-showcase-features {
        margin: 0.5rem 0 0.8rem;
    }

    .auth-feature-card {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.16);
        border-radius: 12px;
        padding: 12px 13px;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.25s ease;
        height: 100%;
    }

    .auth-feature-card:hover {
        background: rgba(255, 255, 255, 0.14);
        border-color: rgba(240, 200, 66, 0.5);
        transform: translateY(-2px);
    }

    .feature-icon-bubble {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
    }

    .feature-icon-bubble.icon-gold  { background: rgba(240, 200, 66, 0.18); color: #f0c842; border: 1px solid rgba(240, 200, 66, 0.35); }
    .feature-icon-bubble.icon-cyan  { background: rgba(14, 165, 233, 0.18); color: #38bdf8; border: 1px solid rgba(14, 165, 233, 0.35); }
    .feature-icon-bubble.icon-green { background: rgba(34, 197, 94, 0.18); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.35); }
    .feature-icon-bubble.icon-amber { background: rgba(245, 158, 11, 0.18); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.35); }

    .feature-card-title {
        font-size: 0.82rem;
        font-weight: 700;
        color: #ffffff;
        line-height: 1.3;
        margin-bottom: 2px;
    }

    .feature-card-desc {
        font-size: 0.71rem;
        color: rgba(255, 255, 255, 0.75);
        line-height: 1.4;
    }

    /* شريط التوثيق السفلي لقسم المنصة */
    .auth-showcase-footer {
        padding-top: 0.65rem;
        border-top: 1px solid rgba(255, 255, 255, 0.13);
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.72rem;
        color: rgba(255, 255, 255, 0.7);
        flex-wrap: wrap;
        gap: 6px;
    }

    /* ========================================================
       قسم تسجيل الدخول (الجانب الأيسر)
       ======================================================== */
    .auth-form-side {
        background: #ffffff;
        padding: 1.6rem 1.75rem;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: right;
        direction: rtl;
        box-sizing: border-box;
    }

    /* شريط ذهبي أعلى بطاقة النموذج */
    .auth-form-side::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3.5px;
        background: linear-gradient(90deg, #f0c842 0%, #0b3d6b 100%);
    }

    /* زر الإغلاق والرجوع */
    .btn-close-modal {
        position: absolute;
        top: 14px;
        left: 14px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.88rem;
        cursor: pointer;
        transition: all 0.2s ease;
        z-index: 20;
        text-decoration: none;
    }

    .btn-close-modal:hover {
        background: #fee2e2;
        color: #ef4444;
        border-color: #fca5a5;
        transform: rotate(90deg);
    }

    .auth-form-brand {
        font-size: 1.15rem;
        font-weight: 800;
        line-height: 1.2;
    }

    .auth-form-brand .brand-edu {
        color: #0b3d6b;
    }

    .auth-form-brand .brand-yemen {
        color: #c59b27;
    }

    .auth-form-title {
        font-size: 1.08rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0.25rem 0 0.15rem;
    }

    .auth-form-subtitle {
        font-size: 0.76rem;
        color: #64748b;
        margin-bottom: 0.75rem;
    }

    /* ========================================================
       حقول الإدخال
       ======================================================== */
    .login-input-group {
        border: 1.5px solid #cbd5e1;
        border-radius: 9px;
        overflow: hidden;
        transition: all 0.22s ease;
        background: #f8fafc;
        display: flex;
        align-items: stretch;
        height: 38px;
    }

    .login-input-group:focus-within {
        border-color: #c59b27 !important;
        background: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(197, 155, 39, 0.2) !important;
    }

    .login-input-group .input-group-text {
        background: transparent;
        border: none;
        color: #64748b;
        font-size: 0.92rem;
        padding: 0 0.75rem;
        transition: all 0.22s ease;
        display: flex;
        align-items: center;
    }

    .login-input-group:focus-within .input-group-text {
        color: #c59b27 !important;
        background: rgba(197, 155, 39, 0.1);
        border-left: 1.5px solid rgba(197, 155, 39, 0.3);
    }

    .login-input-group .form-control {
        border: none;
        background: transparent;
        padding: 0.45rem 0.75rem;
        font-size: 0.84rem;
        text-align: right;
        box-shadow: none !important;
        height: 100%;
    }

    .btn-login-submit {
        background: linear-gradient(135deg, #0b3d6b 0%, #1565a8 100%);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.86rem;
        height: 38px;
        border: none;
        border-radius: 9px;
        box-shadow: 0 4px 12px rgba(11, 61, 107, 0.2);
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
    }

    .btn-login-submit:hover {
        background: linear-gradient(135deg, #072b4f 0%, #0b3d6b 100%);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(11, 61, 107, 0.32);
    }

    .quick-fill-btn {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 2px 7px;
        cursor: pointer;
        font-weight: 700;
        color: #0b3d6b;
        font-size: 0.71rem;
        transition: all 0.15s;
    }

    .quick-fill-btn:hover {
        background: #0b3d6b;
        color: #ffffff;
        border-color: #0b3d6b;
    }

    /* حاوية سؤال ليس لديك حساب مع الملاحظة الذكية */
    .no-account-wrapper {
        position: relative;
        text-align: center;
        margin: 0.4rem 0 0.5rem;
    }

    .btn-no-account-question {
        background: none;
        border: none;
        padding: 3px 8px;
        color: #0b3d6b;
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        border-radius: 6px;
        transition: all 0.2s ease;
        text-decoration: underline;
        text-underline-offset: 3px;
        text-decoration-color: rgba(11, 61, 107, 0.35);
    }

    .btn-no-account-question:hover {
        color: #072b4f;
        background: rgba(11, 61, 107, 0.06);
        text-decoration-color: #0b3d6b;
    }

    .btn-no-account-question i {
        font-size: 0.82rem;
        color: #c59b27;
        transition: transform 0.2s ease;
    }

    .btn-no-account-question:hover i {
        transform: scale(1.15);
    }

    .no-account-popover {
        position: absolute;
        bottom: calc(100% + 8px);
        left: 50%;
        transform: translateX(-50%) translateY(4px);
        width: 290px;
        max-width: 90vw;
        background: #072b4f;
        color: #ffffff;
        padding: 8px 12px;
        border-radius: 10px;
        font-size: 0.74rem;
        line-height: 1.45;
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(197, 155, 39, 0.4);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.22s ease, transform 0.22s ease, visibility 0.22s;
        z-index: 100;
    }

    .no-account-popover.show {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        transform: translateX(-50%) translateY(0);
    }

    .no-account-popover .popover-arrow {
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border-left: 6px solid transparent;
        border-right: 6px solid transparent;
        border-top: 6px solid #072b4f;
    }

    .no-account-popover .popover-content {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-align: right;
    }

    /* ========================================================
       التحكم بالأحجام لضمان عدم الحاجة للسحب على الشاشات العادية
       ======================================================== */
    @media (max-height: 820px) and (min-width: 992px) {
        .auth-page-wrapper {
            padding: 0.5rem 0.75rem !important;
        }
        .auth-showcase-side {
            padding: 1.35rem 1.6rem !important;
        }
        .auth-form-side {
            padding: 1.25rem 1.5rem !important;
        }
        .auth-showcase-title {
            font-size: 1.12rem !important;
            margin: 0.25rem 0 !important;
        }
        .auth-showcase-desc {
            font-size: 0.76rem !important;
            margin-bottom: 0.5rem !important;
            line-height: 1.5 !important;
        }
        .auth-feature-card {
            padding: 9px 11px !important;
        }
        .feature-icon-bubble {
            width: 32px !important;
            height: 32px !important;
            font-size: 0.95rem !important;
        }
        .login-input-group {
            height: 36px !important;
        }
        .btn-login-submit {
            height: 36px !important;
            font-size: 0.84rem !important;
        }
        .auth-form-subtitle {
            margin-bottom: 0.55rem !important;
        }
    }

    /* التجاوب مع شاشات الهواتف واللوحيات */
    @media (max-width: 991.98px) {
        .auth-page-wrapper {
            padding: 1rem 0.75rem !important;
            min-height: 100vh !important;
        }
        .auth-main-container {
            width: 96% !important;
            max-width: 520px !important;
        }
        .auth-showcase-side {
            padding: 1.75rem 1.25rem !important;
        }
        .auth-form-side {
            padding: 1.75rem 1.25rem !important;
        }
    }
</style>
@endsection

@section('content')
<div class="auth-page-wrapper">
    <div class="auth-main-container">
        <div class="row g-0">

            {{-- ====== القسم الأيمن (معلومات المنصة والهوية الأكاديمية): بتنسيق متوازن وبدون فراغ سفلي ====== --}}
            <div class="col-lg-7 auth-showcase-side">
                {{-- طبقة الشبكة الهندسية الشفافة --}}
                <div class="auth-showcase-grid-bg"></div>

                {{-- الأشكال الضبابية المتحركة بهدوء في الخلفية --}}
                <div class="auth-ambient-orb orb-1"></div>
                <div class="auth-ambient-orb orb-2"></div>
                <div class="auth-ambient-orb orb-3"></div>

                {{-- الأشكال الهندسية الزخرفية الدوارة --}}
                <div class="floating-geo-shape geo-1"></div>
                <div class="floating-geo-shape geo-2"></div>
                <div class="floating-geo-shape geo-3"></div>

                {{-- المحتوى التفاعلي لقسم المنصة --}}
                <div class="auth-showcase-content">
                    <div>
                        {{-- رأس القسم والشعار --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="auth-showcase-logo-box">
                                <img src="/images/logo.png" 
                                     onerror="this.onerror=null; this.src='/images/eduyemen-logo.svg';"
                                     alt="EduYemen Logo">
                            </div>
                            <span class="auth-platform-badge">
                                <i class="bi bi-shield-check"></i>البوابة الموحدة للجامعات اليمنية
                            </span>
                        </div>

                        <h2 class="auth-showcase-title">
                            بوابتك الرسمية المعتمدة للالتحاق والتنسيق الجامعي
                        </h2>
                        <p class="auth-showcase-desc">
                            استكشف الجامعات والتخصصات الأكاديمية المعتمدة، قارن نسب القبول والرسوم الدراسية، وقدّم طلبك الإلكتروني المباشر بكل سهولة وموثوقية.
                        </p>

                        {{-- شبكة مميزات وخدمات المنصة (الـ 4 بطاقات مع تباعد رحب ومتناسق) --}}
                        <div class="row g-3 auth-showcase-features">
                            <div class="col-sm-6">
                                <div class="auth-feature-card">
                                    <div class="feature-icon-bubble icon-gold">
                                        <i class="bi bi-file-earmark-check-fill"></i>
                                    </div>
                                    <div>
                                        <div class="feature-card-title">تقديم مباشر</div>
                                        <div class="feature-card-desc">رفع الوثائق والتسجيل الرسمي</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="auth-feature-card">
                                    <div class="feature-icon-bubble icon-cyan">
                                        <i class="bi bi-compass-fill"></i>
                                    </div>
                                    <div>
                                        <div class="feature-card-title">المستشار الذكي</div>
                                        <div class="feature-card-desc">توجيه تخصصي يلائم معدلك</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="auth-feature-card">
                                    <div class="feature-icon-bubble icon-green">
                                        <i class="bi bi-arrow-left-right"></i>
                                    </div>
                                    <div>
                                        <div class="feature-card-title">مقارنة التخصصات</div>
                                        <div class="feature-card-desc">مفاضلة الرسوم وشروط القبول</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="auth-feature-card">
                                    <div class="feature-icon-bubble icon-amber">
                                        <i class="bi bi-buildings-fill"></i>
                                    </div>
                                    <div>
                                        <div class="feature-card-title">دليل الجامعات</div>
                                        <div class="feature-card-desc">بيانات الكليات والبرامج المعتمدة</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- شريط التوثيق السفلي --}}
                    <div class="auth-showcase-footer">
                        <div class="d-flex align-items-center gap-1">
                            <i class="bi bi-patch-check-fill text-warning"></i>
                            <span>نظام تنسيق أكاديمي موحد معتمد</span>
                        </div>
                        <div>EduYemen © 2026/2027</div>
                    </div>
                </div>
            </div>

            {{-- ====== القسم الأيسر (بطاقة تسجيل الدخول بالعربية بالكامل): حجم متناسق ومريح ====== --}}
            <div class="col-lg-5 auth-form-side">

                {{-- زر الإغلاق والرجوع للصفحة السابقة --}}
                <a href="javascript:void(0)" onclick="goBackToPreviousPage()" class="btn-close-modal" title="إغلاق والعودة" aria-label="إغلاق">
                    <i class="bi bi-x-lg"></i>
                </a>

                {{-- رأس البطاقة بالعربية --}}
                <div class="mb-2">
                    <div class="auth-form-brand">
                        <span class="brand-edu">Edu</span><span class="brand-yemen">Yemen</span>
                    </div>
                    <h3 class="auth-form-title">تسجيل الدخول الموحد</h3>
                    <p class="auth-form-subtitle">للدخول المباشر أو تفعيل حساب جديد تلقائياً</p>
                </div>

                {{-- رسائل التنبيهات والأخطاء --}}
                @if(session('success'))
                    <div class="alert alert-success py-1.5 px-2.5 small rounded-3 mb-2 d-flex align-items-center" style="font-size: 0.78rem;">
                        <i class="bi bi-check-circle-fill ms-2 fs-6"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger py-1.5 px-2.5 small rounded-3 mb-2 d-flex align-items-center" style="font-size: 0.78rem;">
                        <i class="bi bi-exclamation-triangle-fill ms-2 fs-6"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @if(Auth::check())
                    <div class="alert alert-info py-1.5 px-2.5 small rounded-3 mb-2 d-flex align-items-center" style="font-size: 0.76rem; background-color: rgba(14, 165, 233, 0.1); border-color: rgba(14, 165, 233, 0.3); color: #0284c7;">
                        <i class="bi bi-info-circle-fill ms-2 fs-6"></i>
                        <div>
                            أنت مسجل حالياً كـ <strong>{{ Auth::user()->username }}</strong> ({{ Auth::user()->isAdmin() ? 'مدير النظام' : (Auth::user()->isUniversity() ? 'الجامعة' : 'طالب') }}).
                        </div>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <input type="hidden" name="redirect_to" value="{{ session('url.intended') ?? request('return') ?? request('redirect_to') ?? '' }}">

                    {{-- حقل اسم المستخدم أو البريد الإلكتروني --}}
                    <div class="mb-2">
                        <label for="login_username" class="form-label small fw-bold text-secondary mb-1" style="font-size: 0.78rem;">
                            اسم المستخدم أو البريد الإلكتروني <span class="text-danger">*</span>
                        </label>
                        <div class="login-input-group">
                            <span class="input-group-text"><i class="bi bi-person-badge-fill"></i></span>
                            <input type="text"
                                   class="form-control @error('username') is-invalid @enderror"
                                   id="login_username"
                                   name="username"
                                   value="{{ old('username') }}"
                                   placeholder="اسم المستخدم أو البريد الإلكتروني"
                                   required
                                   autofocus>
                        </div>
                        @error('username')
                            <div class="text-danger small mt-1" style="font-size: 0.72rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- حقل كلمة المرور مع زر إظهار/إخفاء ورابط نسيان كلمة المرور --}}
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="login_password" class="form-label small fw-bold text-secondary mb-0" style="font-size: 0.78rem;">
                                كلمة المرور <span class="text-danger">*</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="small text-decoration-none fw-semibold" style="font-size: 0.74rem; color: #0b3d6b;">
                                نسيت كلمة المرور؟
                            </a>
                        </div>
                        <div class="login-input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="login_password"
                                   name="password"
                                   placeholder="أدخل كلمة المرور"
                                   required>
                            <button class="btn btn-link text-muted px-2.5 text-decoration-none"
                                     type="button"
                                     onclick="toggleLoginPassword()"
                                     title="إظهار / إخفاء كلمة المرور">
                                <i class="bi bi-eye" id="toggleLoginPwdIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1" style="font-size: 0.72rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- تذكرني --}}
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label small text-muted me-1" for="remember" style="font-size: 0.76rem;">
                                تذكرني في هذه الجلسة
                            </label>
                        </div>
                    </div>

                    {{-- زر تسجيل الدخول --}}
                    <div class="d-grid mb-2">
                        <button type="submit" class="btn btn-login-submit">
                            <span>تسجيل الدخول</span>
                            <i class="bi bi-arrow-left"></i>
                        </button>
                    </div>

                    {{-- فاصل أو بواسطة --}}
                    <div class="d-flex align-items-center my-2 text-muted">
                        <hr class="flex-grow-1 my-0 opacity-25">
                        <span class="px-2" style="font-size: 0.72rem;">أو عبر المصادقة السريعة</span>
                        <hr class="flex-grow-1 my-0 opacity-25">
                    </div>

                    {{-- زر المتابعة باستخدام حساب قوقل --}}
                    <div class="d-grid mb-2">
                        <a href="{{ route('auth.google') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2 py-1.5 rounded-3 text-dark fw-bold" style="background: #ffffff; border: 1.5px solid #dadce0; font-size: 0.8rem; height: 38px; transition: all 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                            <svg width="17" height="17" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17z"/>
                                <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.27 21.36 7.35 24 12 24z"/>
                                <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.98 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                                <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.35 0 3.27 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                            </svg>
                            <span>المتابعة بحساب Google</span>
                        </a>
                    </div>

                    {{-- سؤال ليس لديك حساب؟ --}}
                    <div class="no-account-wrapper">
                        <button type="button" class="btn-no-account-question" id="noAccountTrigger" onclick="toggleNoAccountPopup(event)" aria-expanded="false" title="انقر لمعرفة آلية التسجيل">
                            <span>ليس لديك حساب؟</span>
                            <i class="bi bi-question-circle ms-1"></i>
                        </button>

                        <div class="no-account-popover" id="noAccountPopover" role="tooltip">
                            <div class="popover-arrow"></div>
                            <div class="popover-content">
                                <i class="bi bi-sparkles text-warning me-1"></i>
                                <span>لا حاجة لإنشاء حساب مسبق؛ يكفيك إدخال بريدك الإلكتروني أو المتابعة بحساب Google وسيتم إنشاء وتفعيل حسابك تلقائياً.</span>
                            </div>
                        </div>
                    </div>

                    {{-- شريط الحسابات التجريبية السريعة المتراص --}}
                    <div class="p-1.5 rounded-3 text-secondary mt-1 d-flex align-items-center justify-content-between flex-wrap gap-1" style="background: #f8fafc; border: 1px dashed #cbd5e1; font-size: 0.71rem;">
                        <span class="fw-bold text-dark"><i class="bi bi-lightning-charge-fill text-warning"></i> تجريبي:</span>
                        <div class="d-flex gap-1">
                            <button type="button" class="quick-fill-btn" onclick="fillCredentials('student1', 'student123')">
                                طالب
                            </button>
                            <button type="button" class="quick-fill-btn" onclick="fillCredentials('univ_saeeda', 'password123')">
                                جامعة
                            </button>
                            <button type="button" class="quick-fill-btn" onclick="fillCredentials('admin', 'admin123')">
                                مدير
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // الرجوع للصفحة السابقة أو الصفحة الرئيسية
    function goBackToPreviousPage() {
        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.href = "{{ route('home') }}";
        }
    }

    // إغلاق بالضغط على مفتاح Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            goBackToPreviousPage();
        }
    });

    // إظهار وإخفاء كلمة المرور
    function toggleLoginPassword() {
        const pwdInput = document.getElementById('login_password');
        const icon = document.getElementById('toggleLoginPwdIcon');
        if (!pwdInput || !icon) return;

        if (pwdInput.type === 'password') {
            pwdInput.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            pwdInput.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    // تعبئة الحسابات التجريبية بنقرة واحدة
    function fillCredentials(username, password) {
        const u = document.getElementById('login_username');
        const p = document.getElementById('login_password');
        if (u && p) {
            u.value = username;
            p.value = password;
        }
    }

    // إظهار وإخفاء ملاحظة "ليس لديك حساب؟" المنبثقة الذكية
    function toggleNoAccountPopup(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        const pop = document.getElementById('noAccountPopover');
        const trigger = document.getElementById('noAccountTrigger');
        if (!pop) return;

        const isShowing = pop.classList.contains('show');
        if (isShowing) {
            pop.classList.remove('show');
            if (trigger) trigger.setAttribute('aria-expanded', 'false');
        } else {
            pop.classList.add('show');
            if (trigger) trigger.setAttribute('aria-expanded', 'true');
        }
    }

    // إغلاق الملاحظة المنبثقة تلقائياً عند النقر في أي مكان آخر بالصفحة
    document.addEventListener('click', function(e) {
        const pop = document.getElementById('noAccountPopover');
        const trigger = document.getElementById('noAccountTrigger');
        if (pop && pop.classList.contains('show')) {
            if (!pop.contains(e.target) && !trigger.contains(e.target)) {
                pop.classList.remove('show');
                if (trigger) trigger.setAttribute('aria-expanded', 'false');
            }
        }
    });
</script>
@endsection
