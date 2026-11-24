@extends('layouts.app')

@section('title', 'نسيت كلمة المرور — منصة EduYemen')

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
       تصميم صفحة استعادة كلمة المرور بنسق تسجيل الدخول
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

    /* الحاوية الرئيسية بأبعاد متوازنة تمنع ظهور أي شريط تمرير */
    .auth-main-container {
        width: 100%;
        max-width: 920px;
        margin: auto;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 16px 48px rgba(11, 42, 74, 0.16), 0 0 24px rgba(197, 155, 39, 0.08);
        border: 1px solid rgba(197, 155, 39, 0.28);
        background: #ffffff;
        box-sizing: border-box;
    }

    /* ========================================================
       قسم معلومات المنصة والأمان (الجانب الأيمن)
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

    /* الحاوية الداخلية للمحتوى */
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

    /* بطاقات معلومات الأمان والمميزات */
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
       قسم النموذج (الجانب الأيسر)
       ======================================================== */
    .auth-form-side {
        background: #ffffff;
        padding: 2rem 2.2rem;
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
        font-size: 1.1rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0.25rem 0 0.15rem;
    }

    .auth-form-subtitle {
        font-size: 0.77rem;
        color: #64748b;
        margin-bottom: 0.9rem;
    }

    /* حقول الإدخال */
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

    .btn-submit-action {
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

    .btn-submit-action:hover {
        background: linear-gradient(135deg, #072b4f 0%, #0b3d6b 100%);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(11, 61, 107, 0.32);
    }

    /* ========================================================
       التحكم بالأحجام لضمان عدم الحاجة للسحب على الشاشات العادية
       ======================================================== */
    @media (max-height: 820px) and (min-width: 992px) {
        .auth-page-wrapper {
            padding: 0.5rem 0.75rem !important;
        }
        .auth-showcase-side {
            padding: 1.4rem 1.6rem !important;
        }
        .auth-form-side {
            padding: 1.5rem 1.75rem !important;
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
            padding: 6px 9px !important;
            margin-bottom: 0.4rem !important;
        }
        .feature-icon-bubble {
            width: 28px !important;
            height: 28px !important;
            font-size: 0.85rem !important;
        }
        .login-input-group {
            height: 36px !important;
        }
        .btn-submit-action {
            height: 36px !important;
            font-size: 0.84rem !important;
        }
        .auth-form-subtitle {
            margin-bottom: 0.65rem !important;
        }
    }

    @media (max-width: 991.98px) {
        .auth-page-wrapper {
            padding: 1rem 0.75rem !important;
            min-height: 100vh !important;
        }
        .auth-main-container {
            width: 96% !important;
            max-width: 500px !important;
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

            {{-- الجانب الأيمن: استعراض الأمان ومعلومات المنصة --}}
            <div class="col-lg-6 auth-showcase-side d-none d-lg-flex">
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
                                <i class="bi bi-shield-lock-fill"></i>
                                <span>استعادة الوصول الآمن</span>
                            </div>
                        </div>

                        <h2 class="auth-showcase-title">
                            استعادة الحساب عبر <span style="color: #f0c842;">رمز التحقق المباشر</span>
                        </h2>
                        <p class="auth-showcase-desc">
                            لا تقلق في حال نسيت كلمة المرور، يمكنك استعادة الوصول إلى حسابك بسهولة من خلال إدخال بريدك الإلكتروني المسجل واستلام رمز أمان موثق (OTP).
                        </p>

                        {{-- بطاقتان تعريفيتان بنسق أنيق لملء الحاوية بدون فراغ --}}
                        <div class="auth-feature-card">
                            <div class="feature-icon-bubble icon-gold">
                                <i class="bi bi-key-fill"></i>
                            </div>
                            <div>
                                <div class="feature-card-title">إعادة تعيين سريعة وفورية</div>
                                <div class="feature-card-desc">خطوات فورية تضمن استعادة حسابك دون فقدان أي من بياناتك أو طلباتك</div>
                            </div>
                        </div>

                        <div class="auth-feature-card">
                            <div class="feature-icon-bubble icon-cyan">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div>
                                <div class="feature-card-title">أمان مشفر وموثوق</div>
                                <div class="feature-card-desc">رمز حماية لمرة واحدة يضمن وصول صاحب الحساب فقط لحساباته</div>
                            </div>
                        </div>
                    </div>

                    {{-- التوثيق السفلي --}}
                    <div class="auth-showcase-footer">
                        <div><i class="bi bi-shield-check text-success me-1"></i>حماية متقدمة للبيانات الأكاديمية</div>
                        <div>EduYemen — الجمهورية اليمنية</div>
                    </div>
                </div>
            </div>

            {{-- الجانب الأيسر: نموذج استعادة كلمة المرور --}}
            <div class="col-lg-6 auth-form-side">

                {{-- زر الإغلاق والرجوع --}}
                <a href="{{ route('login') }}" class="btn-close-modal" title="إغلاق والعودة لتسجيل الدخول" aria-label="إغلاق">
                    <i class="bi bi-x-lg"></i>
                </a>

                {{-- رأس البطاقة --}}
                <div class="mb-3">
                    <div class="auth-form-brand">
                        <span class="brand-edu">Edu</span><span class="brand-yemen">Yemen</span>
                    </div>
                    <h3 class="auth-form-title">استعادة كلمة المرور</h3>
                    <p class="auth-form-subtitle">أدخل بريدك الإلكتروني أو اسم المستخدم لإرسال رمز التحقق</p>
                </div>

                {{-- رسائل التنبيهات والأخطاء --}}
                @if(session('error'))
                    <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3 d-flex align-items-center" style="font-size: 0.8rem;">
                        <i class="bi bi-exclamation-triangle-fill ms-2 fs-6"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success py-2 px-3 small rounded-3 mb-3 d-flex align-items-center" style="font-size: 0.8rem;">
                        <i class="bi bi-check-circle-fill ms-2 fs-6"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <input type="hidden" name="redirect_to" value="{{ session('url.intended') ?? request('redirect_to') ?? '' }}">

                    <div class="mb-3">
                        <label for="reset_identity" class="form-label small fw-bold text-secondary mb-1">
                            البريد الإلكتروني أو اسم المستخدم <span class="text-danger">*</span>
                        </label>
                        <div class="login-input-group">
                            <span class="input-group-text"><i class="bi bi-envelope-at-fill"></i></span>
                            <input type="text"
                                   class="form-control @error('identity') is-invalid @enderror"
                                   id="reset_identity"
                                   name="identity"
                                   value="{{ old('identity') }}"
                                   placeholder="أدخل بريدك الإلكتروني المسجل"
                                   required
                                   autofocus>
                        </div>
                        @error('identity')
                            <div class="text-danger small mt-1" style="font-size: 0.74rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-submit-action">
                            <span>إرسال رمز إعادة التعيين (OTP)</span>
                            <i class="bi bi-arrow-left"></i>
                        </button>
                    </div>

                    <div class="text-center pt-2 border-top" style="font-size: 0.82rem;">
                        <span class="text-muted">تذكرت كلمة المرور؟</span>
                        <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none ms-1">
                            تسجيل الدخول
                        </a>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>
@endsection
