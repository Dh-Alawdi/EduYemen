@extends('layouts.app')

@section('title', 'تعيين كلمة المرور الجديدة — منصة EduYemen')

@section('styles')
<style>
    /* تطبيق الخط الطبيعي المعتمد: Cairo و Tajawal */
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
       تصميم صفحة التحقق وتعيين كلمة المرور الجديدة
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

    /* الحاوية الرئيسية */
    .auth-main-container {
        width: 100%;
        max-width: 960px;
        margin: auto;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 16px 48px rgba(11, 42, 74, 0.16), 0 0 24px rgba(197, 155, 39, 0.08);
        border: 1px solid rgba(197, 155, 39, 0.28);
        background: #ffffff;
        box-sizing: border-box;
    }

    /* قسم المنصة الأيمن */
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

    /* شبكة خلفية هندسية شفافة */
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
        width: 200px;
        height: 200px;
        top: -40px;
        left: -30px;
        background: radial-gradient(circle, rgba(240, 200, 66, 0.28) 0%, transparent 70%);
        animation: floatOrb1 16s ease-in-out infinite;
    }

    .orb-2 {
        width: 230px;
        height: 230px;
        bottom: -50px;
        right: -40px;
        background: radial-gradient(circle, rgba(14, 165, 233, 0.24) 0%, transparent 70%);
        animation: floatOrb2 20s ease-in-out infinite alternate;
    }

    .orb-3 {
        width: 150px;
        height: 150px;
        top: 48%;
        left: 55%;
        background: radial-gradient(circle, rgba(197, 155, 39, 0.16) 0%, transparent 70%);
        animation: floatOrb3 18s ease-in-out infinite;
    }

    @keyframes floatOrb1 {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(20px, 16px) scale(1.12); }
    }

    @keyframes floatOrb2 {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(-20px, -16px) scale(1.08); }
    }

    @keyframes floatOrb3 {
        0%, 100% { transform: translate(0, 0) scale(0.92); opacity: 0.45; }
        50% { transform: translate(-12px, 14px) scale(1.15); opacity: 0.85; }
    }

    /* أشكال هندسية زخرفية ناعمة */
    .floating-geo-shape {
        position: absolute;
        pointer-events: none;
        opacity: 0.14;
        border: 1.5px solid rgba(255, 255, 255, 0.4);
        border-radius: 10px;
        z-index: 1;
    }

    .geo-1 {
        width: 34px;
        height: 34px;
        top: 15%;
        right: 12%;
        transform: rotate(20deg);
        animation: rotateGeo 24s linear infinite;
        border-color: rgba(240, 200, 66, 0.55);
    }

    .geo-2 {
        width: 26px;
        height: 26px;
        bottom: 20%;
        left: 12%;
        border-radius: 50%;
        animation: floatGeo 14s ease-in-out infinite alternate;
        background: rgba(255, 255, 255, 0.05);
    }

    .geo-3 {
        width: 18px;
        height: 18px;
        top: 55%;
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
        100% { transform: translateY(-14px); }
    }

    /* الحاوية الداخلية */
    .auth-showcase-content {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        gap: 1rem;
    }

    .auth-showcase-logo-box {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.15);
        border: 1.5px solid rgba(240, 200, 66, 0.5);
        border-radius: 14px;
        padding: 6px;
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
        font-size: 1.22rem;
        font-weight: 800;
        color: #ffffff;
        line-height: 1.35;
        margin: 0.35rem 0 0.25rem;
    }

    .auth-showcase-desc {
        font-size: 0.79rem;
        color: rgba(255, 255, 255, 0.82);
        line-height: 1.6;
        margin: 0 0 0.6rem;
    }

    .auth-feature-card {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.16);
        border-radius: 11px;
        padding: 8px 11px;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 0.5rem;
        transition: all 0.25s ease;
    }

    .auth-feature-card:hover {
        background: rgba(255, 255, 255, 0.14);
        border-color: rgba(240, 200, 66, 0.5);
        transform: translateY(-2px);
    }

    .feature-icon-bubble {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        flex-shrink: 0;
    }

    .feature-icon-bubble.icon-gold {
        background: rgba(240, 200, 66, 0.18);
        color: #f0c842;
        border: 1px solid rgba(240, 200, 66, 0.35);
    }

    .feature-icon-bubble.icon-cyan {
        background: rgba(14, 165, 233, 0.18);
        color: #38bdf8;
        border: 1px solid rgba(14, 165, 233, 0.35);
    }

    .feature-card-title {
        font-size: 0.78rem;
        font-weight: 700;
        color: #ffffff;
        line-height: 1.25;
    }

    .feature-card-desc {
        font-size: 0.68rem;
        color: rgba(255, 255, 255, 0.7);
        line-height: 1.35;
    }

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
       قسم النموذج (الجانب الأيسر)
       ======================================================== */
    .auth-form-side {
        background: #ffffff;
        padding: 1.6rem 2rem;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: right;
        direction: rtl;
        box-sizing: border-box;
    }

    .auth-form-side::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3.5px;
        background: linear-gradient(90deg, #f0c842 0%, #0b3d6b 100%);
    }

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
        margin: 0.2rem 0 0.1rem;
    }

    .auth-form-subtitle {
        font-size: 0.76rem;
        color: #64748b;
        margin-bottom: 0.75rem;
    }

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
        padding: 0 0.7rem;
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
        padding: 0.4rem 0.65rem;
        font-size: 0.84rem;
        box-shadow: none !important;
        height: 100%;
    }

    .otp-code-input {
        font-family: 'Courier New', Courier, monospace !important;
        font-size: 1.4rem !important;
        font-weight: 800;
        letter-spacing: 8px;
        text-align: center !important;
        direction: ltr;
        color: #0b3d6b;
    }

    .btn-submit-action {
        background: linear-gradient(135deg, #0b3d6b 0%, #1565a8 100%);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.84rem;
        height: 38px;
        border: none;
        border-radius: 9px;
        box-shadow: 0 4px 12px rgba(11, 61, 107, 0.2);
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
    }

    .btn-submit-action:hover {
        background: linear-gradient(135deg, #072b4f 0%, #0b3d6b 100%);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(11, 61, 107, 0.32);
    }

    .btn-direct-login {
        border: 1.5px solid #0b3d6b;
        color: #0b3d6b;
        background: transparent;
        font-weight: 700;
        font-size: 0.8rem;
        height: 36px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        transition: all 0.2s ease;
    }

    .btn-direct-login:hover {
        background: rgba(11, 61, 107, 0.08);
        color: #072b4f;
    }

    .resend-btn {
        background: none;
        border: none;
        color: #0b3d6b;
        font-weight: 700;
        padding: 0;
        cursor: pointer;
        text-decoration: underline;
    }

    .resend-btn:disabled {
        color: #94a3b8;
        cursor: not-allowed;
        text-decoration: none;
    }

    /* التحكم بالتجاوب */
    @media (max-height: 820px) and (min-width: 992px) {
        .auth-page-wrapper {
            padding: 0.4rem 0.6rem !important;
        }
        .auth-showcase-side {
            padding: 1.35rem 1.6rem !important;
        }
        .auth-form-side {
            padding: 1.25rem 1.6rem !important;
        }
        .auth-showcase-title {
            font-size: 1.1rem !important;
            margin: 0.2rem 0 !important;
        }
        .auth-showcase-desc {
            font-size: 0.75rem !important;
            margin-bottom: 0.4rem !important;
            line-height: 1.5 !important;
        }
        .auth-feature-card {
            padding: 6px 9px !important;
            margin-bottom: 0.35rem !important;
        }
        .feature-icon-bubble {
            width: 28px !important;
            height: 28px !important;
            font-size: 0.85rem !important;
        }
        .login-input-group {
            height: 35px !important;
        }
        .btn-submit-action,
        .btn-direct-login {
            height: 35px !important;
            font-size: 0.8rem !important;
        }
    }

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

            {{-- الجانب الأيمن: استعراض الأمان والمعلومات --}}
            <div class="col-lg-5 auth-showcase-side d-none d-lg-flex">
                <div class="auth-showcase-grid-bg"></div>
                <div class="auth-ambient-orb orb-1"></div>
                <div class="auth-ambient-orb orb-2"></div>
                <div class="auth-ambient-orb orb-3"></div>
                <div class="floating-geo-shape geo-1"></div>
                <div class="floating-geo-shape geo-2"></div>
                <div class="floating-geo-shape geo-3"></div>

                <div class="auth-showcase-content">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="auth-showcase-logo-box">
                                <img src="/images/logo.png" onerror="this.src='/images/eduyemen-logo.svg';" alt="EduYemen">
                            </div>
                            <div class="auth-platform-badge">
                                <i class="bi bi-shield-check"></i>
                                <span>التحقق الأمني المباشر</span>
                            </div>
                        </div>

                        <h2 class="auth-showcase-title">
                            المصادقة وتأكيد <span style="color: #f0c842;">الهوية</span>
                        </h2>
                        <p class="auth-showcase-desc">
                            بمجرد إدخال رمز التحقق (OTP) المرسل إلى بريدك، يمكنك تعيين كلمة مرور جديدة أو المتابعة والدخول مباشرة إلى المنصة والصفحة التي تريدها.
                        </p>

                        <div class="auth-feature-card">
                            <div class="feature-icon-bubble icon-gold">
                                <i class="bi bi-box-arrow-in-left"></i>
                            </div>
                            <div>
                                <div class="feature-card-title">دخول مباشر أو تعيين كلمة مرور</div>
                                <div class="feature-card-desc">يمكنك المتابعة بالرمز فوراً دون إلزامية كتابة كلمة مرور جديدة</div>
                            </div>
                        </div>

                        <div class="auth-feature-card">
                            <div class="feature-icon-bubble icon-cyan">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <div>
                                <div class="feature-card-title">تشفير وأمان مشدد</div>
                                <div class="feature-card-desc">حماية تامة وسرية مطلقة لبياناتك الأكاديمية والجامعية</div>
                            </div>
                        </div>
                    </div>

                    <div class="auth-showcase-footer">
                        <div><i class="bi bi-lock-fill text-success me-1"></i>تشفير كامل لكلمات المرور</div>
                        <div>EduYemen — الجمهورية اليمنية</div>
                    </div>
                </div>
            </div>

            {{-- الجانب الأيسر: نموذج تعيين كلمة المرور الجديدة --}}
            <div class="col-lg-7 auth-form-side">

                {{-- زر الإغلاق والرجوع --}}
                <a href="{{ route('login') }}" class="btn-close-modal" title="إلغاء والعودة لتسجيل الدخول" aria-label="إلغاء">
                    <i class="bi bi-x-lg"></i>
                </a>

                {{-- رأس البطاقة --}}
                <div class="mb-2">
                    <div class="auth-form-brand">
                        <span class="brand-edu">Edu</span><span class="brand-yemen">Yemen</span>
                    </div>
                    <h3 class="auth-form-title">تأكيد الرمز وكلمة المرور</h3>
                    <p class="auth-form-subtitle mb-2">
                        أدخل رمز التحقق (OTP) المرسل إلى:
                        <strong class="text-primary font-monospace" style="direction: ltr; display: inline-block;">{{ $maskedEmail }}</strong>
                    </p>
                </div>

                {{-- رسائل التنبيهات والأخطاء --}}
                @if(session('success'))
                    <div class="alert alert-success py-1 px-3 small rounded-3 mb-2 d-flex align-items-center" style="font-size: 0.78rem;">
                        <i class="bi bi-check-circle-fill ms-2 fs-6"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger py-1 px-3 small rounded-3 mb-2 d-flex align-items-center" style="font-size: 0.78rem;">
                        <i class="bi bi-exclamation-triangle-fill ms-2 fs-6"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                {{-- تنبيه المطور في بيئة التطوير --}}
                @if(session('dev_otp') || (isset($devOtp) && $devOtp))
                    <div class="py-1 px-2 rounded-3 text-secondary mb-2 text-center" style="background: #fef3c7; border: 1px dashed #f59e0b; font-size: 0.75rem;">
                        <i class="bi bi-lightning-charge-fill text-warning me-1"></i>
                        <span>رمز التجربة السريع (للاختبار المحلي):</span>
                        <strong class="text-dark font-monospace fs-6 ms-1">{{ session('dev_otp') ?? $devOtp }}</strong>
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST" id="resetPasswordForm">
                    @csrf
                    <input type="hidden" name="redirect_to" value="{{ session('url.intended') ?? request('redirect_to') ?? '' }}">

                    {{-- حقل رمز التحقق OTP --}}
                    <div class="mb-2">
                        <label for="reset_code" class="form-label small fw-bold text-secondary mb-1" style="font-size: 0.78rem;">
                            رمز التحقق (OTP) المكون من 6 أرقام <span class="text-danger">*</span>
                        </label>
                        <div class="login-input-group">
                            <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                            <input type="text"
                                   class="form-control otp-code-input @error('code') is-invalid @enderror"
                                   id="reset_code"
                                   name="code"
                                   maxlength="6"
                                   placeholder="------"
                                   autocomplete="one-time-code"
                                   required
                                   autofocus>
                        </div>
                        @error('code')
                            <div class="text-danger small mt-1" style="font-size: 0.72rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ملاحظة سريعة هادئة --}}
                    <div class="d-flex align-items-center gap-2 px-2 py-1 rounded-2 mb-2" style="background: #f8fafc; border: 1px solid #e2e8f0; font-size: 0.74rem;">
                        <i class="bi bi-info-circle text-primary"></i>
                        <span class="text-muted">تغيير كلمة المرور اختياري؛ يمكنك حفظ كلمة جديدة أو الدخول مباشرة بالرمز.</span>
                    </div>

                    {{-- كلمة المرور الجديدة وتأكيدها (اختيارية) --}}
                    <div class="row g-2 mb-2">
                        <div class="col-sm-6">
                            <label for="new_password" class="form-label small fw-bold text-secondary mb-1" style="font-size: 0.76rem;">
                                كلمة المرور الجديدة <span class="text-muted fw-normal">(اختياري)</span>
                            </label>
                            <div class="login-input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="new_password"
                                       name="password"
                                       placeholder="اتركها للتخطي">
                                <button class="btn btn-link text-muted px-2 text-decoration-none"
                                        type="button"
                                        onclick="togglePwd('new_password', 'toggleNewPwdIcon')"
                                        title="إظهار / إخفاء">
                                    <i class="bi bi-eye" id="toggleNewPwdIcon" style="font-size: 0.85rem;"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label for="password_confirmation" class="form-label small fw-bold text-secondary mb-1" style="font-size: 0.76rem;">
                                تأكيد كلمة المرور <span class="text-muted fw-normal">(عند التغيير)</span>
                            </label>
                            <div class="login-input-group">
                                <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                                <input type="password"
                                       class="form-control"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       placeholder="أعد كتابتها">
                            </div>
                        </div>

                        @error('password')
                            <div class="col-12 text-danger small mt-1" style="font-size: 0.72rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- أزرار الإجراء --}}
                    <div class="d-grid gap-2 mb-2">
                        <button type="submit" name="action" value="save" class="btn btn-submit-action">
                            <i class="bi bi-shield-check"></i>
                            <span>حفظ كلمة المرور والمتابعة للمنصة</span>
                        </button>

                        <button type="submit" name="action" value="direct_login" class="btn btn-direct-login" onclick="clearPasswordForDirectLogin()">
                            <i class="bi bi-box-arrow-in-left"></i>
                            <span>المتابعة والدخول مباشرة بالرمز (دون تغيير كلمة المرور)</span>
                        </button>
                    </div>
                </form>

                {{-- إعادة إرسال الكود والعداد التنازلي --}}
                <div class="text-center small text-muted" style="font-size: 0.76rem;">
                    <span>لم يصلك رمز التحقق؟</span>
                    <form action="{{ route('password.resend') }}" method="POST" class="d-inline" id="resendResetForm">
                        @csrf
                        <button type="submit" class="resend-btn ms-1" id="resendResetBtn">
                            إعادة إرسال الرمز
                        </button>
                    </form>
                    <div class="mt-1 text-secondary" style="font-size: 0.72rem;" id="timerResetWrap">
                        يمكنك طلب رمز جديد بعد <span id="countdownReset" class="fw-bold text-dark">60</span> ثانية
                    </div>
                </div>

                <div class="text-center mt-2 pt-2 border-top" style="font-size: 0.78rem;">
                    <a href="{{ route('login') }}" class="text-muted text-decoration-none">
                        <i class="bi bi-arrow-right me-1"></i>إلغاء والعودة لصفحة تسجيل الدخول
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function clearPasswordForDirectLogin() {
        const p1 = document.getElementById('new_password');
        const p2 = document.getElementById('password_confirmation');
        if (p1) p1.value = '';
        if (p2) p2.value = '';
    }

    function togglePwd(inputId, iconId) {
        const inp = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!inp || !icon) return;

        if (inp.type === 'password') {
            inp.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            inp.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    // قصر حقل الكود على أرقام فقط
    const resetCodeInput = document.getElementById('reset_code');
    if (resetCodeInput) {
        resetCodeInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }

    // عداد تنازلي 60 ثانية لإعادة الإرسال
    let timeLeft = 60;
    const countdownEl = document.getElementById('countdownReset');
    const resendBtn = document.getElementById('resendResetBtn');
    const timerWrap = document.getElementById('timerResetWrap');

    if (resendBtn && countdownEl) {
        resendBtn.disabled = true;
        const timer = setInterval(() => {
            timeLeft--;
            countdownEl.textContent = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(timer);
                resendBtn.disabled = false;
                if (timerWrap) timerWrap.style.display = 'none';
            }
        }, 1000);
    }
</script>
@endsection
