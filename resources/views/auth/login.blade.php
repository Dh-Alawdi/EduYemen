@extends('layouts.app')

@section('title', 'تسجيل الدخول — منصة EduYemen')

@section('styles')
<style>
    /* ========================================================
       تصميم صفحة تسجيل الدخول بعرض 80% مع التقسيم الثلاثي
       ======================================================== */
    body {
        background: radial-gradient(circle at 50% 20%, rgba(11, 61, 107, 0.08) 0%, rgba(244, 246, 251, 1) 100%) !important;
        min-height: 100vh;
    }

    .auth-page-wrapper {
        min-height: calc(100vh - var(--navbar-height, 64px));
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }

    /* الحاوية الرئيسية بعرض 80% كما طُلب بدقة */
    .auth-main-container {
        width: 80% !important;
        max-width: 1250px;
        min-width: 320px;
        margin: 0 auto;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 15px 50px rgba(11, 61, 107, 0.15), 0 0 25px rgba(197, 155, 39, 0.1);
        border: 1px solid rgba(197, 155, 39, 0.3);
        background: #ffffff;
    }

    /* الجانب الأيمن (ثلثين من الصفحة 2/3): استعراض المنصة والجامعات والتصاميم */
    .auth-showcase-side {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 65%, #1565a8 100%);
        color: #ffffff;
        padding: 3rem 2.8rem;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* أشكال هندسية زخرفية وخلفيات تفاعلية */
    .auth-showcase-side::before {
        content: '';
        position: absolute;
        top: -90px;
        left: -90px;
        width: 260px;
        height: 260px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(197, 155, 39, 0.35) 0%, transparent 70%);
        pointer-events: none;
    }

    .auth-showcase-side::after {
        content: '';
        position: absolute;
        bottom: -110px;
        right: -80px;
        width: 320px;
        height: 320px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(21, 101, 168, 0.5) 0%, transparent 70%);
        pointer-events: none;
    }

    .auth-showcase-logo-box {
        width: 82px;
        height: 82px;
        background: rgba(255, 255, 255, 0.15);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 20px;
        padding: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(8px);
        margin-bottom: 1.2rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
    }

    .auth-showcase-logo-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .auth-feature-pill {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 12px;
        padding: 10px 14px;
        backdrop-filter: blur(6px);
        transition: all 0.25s ease;
    }

    .auth-feature-pill:hover {
        background: rgba(255, 255, 255, 0.15);
        border-color: var(--accent);
        transform: translateY(-2px);
    }

    /* الجانب الآخر (ثلث 1/3): بطاقة تسجيل الدخول بالعربية بالكامل */
    .auth-form-side {
        background: #ffffff;
        padding: 2.5rem 2.2rem;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: right;
        direction: rtl;
    }

    .auth-form-side label,
    .auth-form-side h4,
    .auth-form-side h5,
    .auth-form-side p,
    .auth-form-side span,
    .auth-form-side a,
    .auth-form-side .form-check-label {
        text-align: right;
    }

    /* زر الإغلاق والرجوع */
    .btn-close-modal {
        position: absolute;
        top: 16px;
        left: 16px;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
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

    /* شريط ذهبي أعلى بطاقة النموذج */
    .auth-form-side::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--accent) 0%, var(--primary) 100%);
    }

    /* ========================================================
       تغيير لون وشكل وإطار الأيقونة عند الضغط على الحقل (Focus State)
       ======================================================== */
    .login-input-group {
        border: 1.5px solid #cbd5e1;
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.25s ease;
        background: #f8fafc;
        display: flex;
        align-items: stretch;
    }

    .login-input-group:focus-within {
        border-color: var(--accent) !important;
        background: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(197, 155, 39, 0.22) !important;
    }

    .login-input-group .input-group-text {
        background: transparent;
        border: none;
        color: #64748b;
        font-size: 1.05rem;
        padding: 0.65rem 0.85rem;
        transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        display: flex;
        align-items: center;
    }

    /* عند الضغط على الحقل: الأيقونة تتغير لونها وتكبيرها وتدويرها */
    .login-input-group:focus-within .input-group-text {
        color: #c59b27 !important;
        transform: scale(1.18) rotate(-8deg);
        background: rgba(197, 155, 39, 0.12);
        border-left: 1.5px solid rgba(197, 155, 39, 0.35);
    }

    .login-input-group .form-control {
        border: none;
        background: transparent;
        padding: 0.65rem 0.85rem;
        font-size: 0.92rem;
        text-align: right;
        box-shadow: none !important;
    }

    .btn-login-submit {
        background: linear-gradient(135deg, var(--primary) 0%, #1565a8 100%);
        color: #ffffff;
        font-weight: 800;
        font-size: 0.95rem;
        padding: 0.72rem;
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 14px rgba(11, 61, 107, 0.25);
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-login-submit:hover {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(11, 61, 107, 0.35);
    }

    .quick-fill-btn {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 3px 8px;
        cursor: pointer;
        font-weight: 600;
        color: var(--primary);
        font-size: 0.73rem;
        transition: all 0.15s;
    }

    .quick-fill-btn:hover {
        background: var(--primary);
        color: #ffffff;
        border-color: var(--primary);
    }

    /* التجاوب مع شاشات الهواتف واللوحيات */
    @media (max-width: 991.98px) {
        .auth-main-container {
            width: 92% !important;
        }
        .auth-showcase-side {
            padding: 2rem 1.5rem;
        }
        .auth-form-side {
            padding: 2rem 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="auth-page-wrapper">
    <div class="auth-main-container">
        <div class="row g-0">

            {{-- ====== القسم الأيمن (ثلثين من الصفحة 2/3): استعراض المنصة وهوية الجامعات ====== --}}
            <div class="col-lg-8 auth-showcase-side">
                <div>
                    {{-- الشعار الرسمي للمنصة --}}
                    <div class="auth-showcase-logo-box">
                        <img src="/images/logo.png" 
                             onerror="this.onerror=null; this.src='/images/eduyemen-logo.svg';"
                             alt="EduYemen Logo">
                    </div>

                    <div class="d-inline-block px-3 py-1 rounded-pill mb-3" style="background: rgba(197, 155, 39, 0.25); border: 1px solid var(--accent); color: var(--accent-light); font-size: 0.8rem; font-weight: 700;">
                        <i class="bi bi-shield-check me-1"></i>البوابة الموحدة لتنسيق وقبول الجامعات اليمنية
                    </div>

                    <h2 class="fw-bold text-white mb-2" style="font-size: 2rem; line-height: 1.35;">
                        منظومة <span style="color: var(--accent-light);">EduYemen</span> للخدمات الجامعية
                    </h2>
                    <p class="text-white-50 mb-4" style="max-width: 620px; font-size: 0.92rem; line-height: 1.85;">
                        تتيح المنصة للطلبة الاستفادة من الخدمات الجامعية الموحدة؛ بما يشمل تقديم طلبات الالتحاق المباشر بالجامعات، استشارة المستشار الأكاديمي، والمقارنة الشاملة بين الكليات والتخصصات وشروط القبول والرسوم الدراسية المعتمدة في العاصمة صنعاء والجامعات اليمنية.
                    </p>

                    {{-- خدمات المنصة الأكاديمية المعتمدة --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="auth-feature-pill d-flex align-items-center gap-3">
                                <div class="fs-4 text-warning"><i class="bi bi-file-earmark-check-fill"></i></div>
                                <div>
                                    <div class="fw-bold text-white small">تقديم طلبات الالتحاق</div>
                                    <div class="text-white-50" style="font-size:0.75rem;">رفع الوثائق والتسجيل الرسمي بالجامعات</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="auth-feature-pill d-flex align-items-center gap-3">
                                <div class="fs-4 text-info"><i class="bi bi-compass-fill"></i></div>
                                <div>
                                    <div class="fw-bold text-white small">خدمة المستشار الأكاديمي</div>
                                    <div class="text-white-50" style="font-size:0.75rem;">توجيه تخصصي يلائم معدل الثانوية واهتماماتك</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="auth-feature-pill d-flex align-items-center gap-3">
                                <div class="fs-4 text-success"><i class="bi bi-arrow-left-right"></i></div>
                                <div>
                                    <div class="fw-bold text-white small">مقارنة التخصصات والرسوم</div>
                                    <div class="text-white-50" style="font-size:0.75rem;">مفاضلة البرامج ومعدلات القبول بدقة</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="auth-feature-pill d-flex align-items-center gap-3">
                                <div class="fs-4 text-warning"><i class="bi bi-buildings-fill"></i></div>
                                <div>
                                    <div class="fw-bold text-white small">دليل جامعات العاصمة صنعاء</div>
                                    <div class="text-white-50" style="font-size:0.75rem;">استعراض بيانات الكليات ومعلومات البرامج المعتمدة</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- شريط التوثيق السفلي في جهة الشعار --}}
                <div class="pt-3 border-top border-white border-opacity-15 d-flex align-items-center justify-content-between flex-wrap gap-2 text-white-50 small">
                    <div><i class="bi bi-shield-check text-success me-1"></i>خدمات التنسيق والقبول الموحد للعام 2026/2027</div>
                    <div>EduYemen — الجمهورية اليمنية</div>
                </div>
            </div>

            {{-- ====== القسم الأيسر (ثلث 1/3): بطاقة تسجيل الدخول بالعربية بالكامل ====== --}}
            <div class="col-lg-4 auth-form-side">

                {{-- زر الإغلاق والرجوع للصفحة السابقة --}}
                <a href="javascript:void(0)" onclick="goBackToPreviousPage()" class="btn-close-modal" title="إغلاق والعودة" aria-label="إغلاق">
                    <i class="bi bi-x-lg"></i>
                </a>

                {{-- رأس البطاقة بالعربية --}}
                <div class="mb-4">
                    <div style="font-size: 1.25rem; font-weight: 800; line-height: 1.2;">
                        <span style="color: var(--primary);">Edu</span><span style="color: var(--accent);">Yemen</span>
                    </div>
                    <h4 class="fw-bold text-dark mt-2 mb-1" style="font-size: 1.25rem;">تسجيل الدخول</h4>
                    <p class="text-muted small mb-0">أدخل بيانات الدخول للوصول إلى حسابك</p>
                </div>

                {{-- رسائل التنبيهات والأخطاء --}}
                @if(session('success'))
                    <div class="alert alert-success py-2 px-3 small rounded-3 mb-3 d-flex align-items-center" style="font-size: 0.8rem;">
                        <i class="bi bi-check-circle-fill ms-2 fs-6"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3 d-flex align-items-center" style="font-size: 0.8rem;">
                        <i class="bi bi-exclamation-triangle-fill ms-2 fs-6"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf

                    {{-- حقل اسم المستخدم مع أيقونة تفاعلية متغيرة عند التركيز --}}
                    <div class="mb-3">
                        <label for="login_username" class="form-label small fw-bold text-secondary mb-1">
                            اسم المستخدم <span class="text-danger">*</span>
                        </label>
                        <div class="login-input-group">
                            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                            <input type="text"
                                   class="form-control @error('username') is-invalid @enderror"
                                   id="login_username"
                                   name="username"
                                   value="{{ old('username') }}"
                                   placeholder="أدخل اسم المستخدم"
                                   required
                                   autofocus>
                        </div>
                        @error('username')
                            <div class="text-danger small mt-1" style="font-size: 0.74rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- حقل كلمة المرور مع زر إظهار/إخفاء --}}
                    <div class="mb-3">
                        <label for="login_password" class="form-label small fw-bold text-secondary mb-1">
                            كلمة المرور <span class="text-danger">*</span>
                        </label>
                        <div class="login-input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="login_password"
                                   name="password"
                                   placeholder="أدخل كلمة المرور"
                                   required>
                            <button class="btn btn-link text-muted px-3 text-decoration-none"
                                    type="button"
                                    onclick="toggleLoginPassword()"
                                    title="إظهار / إخفاء كلمة المرور">
                                <i class="bi bi-eye" id="toggleLoginPwdIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1" style="font-size: 0.74rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- تذكرني --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label small text-muted me-1" for="remember" style="font-size: 0.8rem;">
                                تذكرني في هذه الجلسة
                            </label>
                        </div>
                    </div>

                    {{-- زر تسجيل الدخول --}}
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-login-submit">
                            <span>تسجيل الدخول</span>
                            <i class="bi bi-arrow-left"></i>
                        </button>
                    </div>

                    {{-- رابط إنشاء حساب جديد --}}
                    <div class="mb-3" style="font-size: 0.82rem;">
                        <span class="text-muted">ليس لديك حساب بعد؟</span>
                        <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none ms-1">
                            إنشاء حساب جديد
                        </a>
                    </div>

                    {{-- صندوق الحسابات التجريبية السريعة --}}
                    <div class="p-2 rounded-3 text-secondary mt-3" style="background: #f8fafc; border: 1px dashed #cbd5e1; font-size: 0.74rem;">
                        <div class="fw-bold mb-1 d-flex align-items-center gap-1 text-dark">
                            <i class="bi bi-lightning-charge-fill text-warning"></i>
                            <span>تعبئة سريعة لحسابات تجريبية:</span>
                        </div>
                        <div class="d-flex flex-wrap gap-1 mt-1">
                            <button type="button" class="quick-fill-btn" onclick="fillCredentials('student1', 'student123')">
                                طالب: student1
                            </button>
                            <button type="button" class="quick-fill-btn" onclick="fillCredentials('univ_saeeda', 'password123')">
                                جامعة: univ_saeeda
                            </button>
                            <button type="button" class="quick-fill-btn" onclick="fillCredentials('admin', 'admin123')">
                                مدير: admin
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
</script>
@endsection
