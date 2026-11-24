@extends('layouts.app')

@section('title', 'التحقق من رمز الأمان — منصة EduYemen')

@section('styles')
<style>
    body {
        background: radial-gradient(circle at 50% 20%, rgba(11, 61, 107, 0.08) 0%, rgba(244, 246, 251, 1) 100%) !important;
        min-height: 100vh;
    }

    .auth-page-wrapper {
        min-height: calc(100vh - var(--navbar-height, 64px));
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2.5rem 0;
    }

    .auth-main-container {
        width: 80% !important;
        max-width: 1100px;
        min-width: 320px;
        margin: 0 auto;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 15px 50px rgba(11, 61, 107, 0.15), 0 0 25px rgba(197, 155, 39, 0.1);
        border: 1px solid rgba(197, 155, 39, 0.3);
        background: #ffffff;
    }

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
    }

    .auth-form-side {
        background: #ffffff;
        padding: 3rem 2.5rem;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .btn-close-modal {
        position: absolute;
        top: 20px;
        left: 20px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-close-modal:hover {
        background: #fee2e2;
        color: #ef4444;
        border-color: #fca5a5;
        transform: rotate(90deg);
    }

    /* حقول رمز التحقق */
    .otp-input-field {
        font-family: 'Courier New', Courier, monospace;
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: 12px;
        text-align: center;
        height: 60px;
        border-radius: 14px;
        border: 2px solid #cbd5e1;
        background: #f8fafc;
        color: #0b3d6b;
        transition: all 0.2s ease;
        direction: ltr;
    }

    .otp-input-field:focus {
        border-color: var(--primary);
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(11, 61, 107, 0.12);
        outline: none;
    }

    .btn-verify-submit {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: #ffffff;
        border: none;
        padding: 14px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.25s ease;
        box-shadow: 0 4px 14px rgba(11, 61, 107, 0.25);
    }

    .btn-verify-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(11, 61, 107, 0.35);
        color: #fff;
    }

    .resend-btn {
        background: none;
        border: none;
        color: var(--primary);
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
</style>
@endsection

@section('content')
<div class="auth-page-wrapper">
    <div class="auth-main-container">
        <div class="row g-0">

            {{-- الجانب الأيمن (2/3): استعراض المنصة والأمان --}}
            <div class="col-lg-7 auth-showcase-side d-none d-lg-flex">
                <div>
                    <div class="auth-showcase-logo-box">
                        <img src="/images/logo.png" onerror="this.src='/images/eduyemen-logo.svg';" alt="EduYemen">
                    </div>
                    <div class="hero-badge mb-3 d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill" style="background: rgba(197,155,39,0.25); border: 1px solid rgba(197,155,39,0.5); color: #f0c842; font-size: 0.8rem;">
                        <i class="bi bi-shield-lock-fill"></i>
                        <span>نظام المصادقة وحماية الحسابات</span>
                    </div>
                    <h2 class="display-6 fw-bold text-white mb-3" style="font-size: 2rem; line-height: 1.35;">
                        حماية متطورة لحسابك الأكاديمي عبر <span style="color: #f0c842;">رمز الأمان (OTP)</span>
                    </h2>
                    <p class="text-white-50 mb-4" style="font-size: 0.95rem; line-height: 1.8;">
                        تعتمد منصة EduYemen معايير أمان موحدة للتحقق من ملكية البريد الإلكتروني ومنع الوصول غير المصرح به لبيانات القبول والجامعات.
                    </p>

                    <div class="row g-3">
                        <div class="col-12">
                            <div class="auth-feature-pill d-flex align-items-center gap-3">
                                <div class="fs-4 text-warning"><i class="bi bi-envelope-check-fill"></i></div>
                                <div>
                                    <div class="fw-bold text-white small">التحقق بالبريد الإلكتروني المباشر</div>
                                    <div class="text-white-50" style="font-size:0.75rem;">تصلك رموز التحقق فورياً لتأكيد هويتك الشخصية</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="auth-feature-pill d-flex align-items-center gap-3">
                                <div class="fs-4 text-info"><i class="bi bi-key-fill"></i></div>
                                <div>
                                    <div class="fw-bold text-white small">صلاحية مؤقتة مشفرة</div>
                                    <div class="text-white-50" style="font-size:0.75rem;">رموز صلاحيتها 10 دقائق فقط لضمان أعلى مستوى من الخصوصية</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-top border-white border-opacity-15 d-flex align-items-center justify-content-between text-white-50 small">
                    <div><i class="bi bi-shield-check text-success me-1"></i>EduYemen Multi-Factor Security</div>
                    <div>صنعاء — الجمهورية اليمنية</div>
                </div>
            </div>

            {{-- الجانب الأيسر (1/3): بطاقة إدخال كود التحقق --}}
            <div class="col-lg-5 auth-form-side">

                {{-- زر الإغلاق والرجوع --}}
                <a href="{{ route('login') }}" class="btn-close-modal" title="إلغاء والعودة" aria-label="إلغاء">
                    <i class="bi bi-x-lg"></i>
                </a>

                {{-- رأس البطاقة --}}
                <div class="mb-4 text-center">
                    <div style="font-size: 1.3rem; font-weight: 800; line-height: 1.2;">
                        <span style="color: var(--primary);">Edu</span><span style="color: var(--accent);">Yemen</span>
                    </div>
                    <div class="my-3 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 50%; background: rgba(11, 61, 107, 0.08); color: var(--primary); font-size: 1.7rem;">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-1" style="font-size: 1.3rem;">أدخل رمز التحقق (OTP)</h4>
                    <p class="text-muted small mb-0">
                        تم إرسال رمز الأمان المكون من 6 أرقام إلى:
                    </p>
                    <div class="fw-bold text-primary mt-1" style="font-size: 0.95rem; direction: ltr; display: inline-block;">
                        {{ $maskedEmail ?? 'بريدك الإلكتروني المسجل' }}
                    </div>
                </div>

                {{-- رسائل التنبيهات والأخطاء --}}
                @if(session('success'))
                    <div class="alert alert-success py-2 px-3 small rounded-3 mb-3 d-flex align-items-center" style="font-size: 0.8rem;">
                        <i class="bi bi-check-circle-fill ms-2 fs-6"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if(session('error') || $errors->has('code'))
                    <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3 d-flex align-items-center" style="font-size: 0.8rem;">
                        <i class="bi bi-exclamation-triangle-fill ms-2 fs-6"></i>
                        <div>{{ session('error') ?? $errors->first('code') }}</div>
                    </div>
                @endif

                {{-- تنبيه المطور في بيئة التطوير المحلية للتجربة السريعة --}}
                @if(session('dev_otp') || (isset($devOtp) && $devOtp))
                    <div class="p-2 rounded-3 text-secondary mb-3 text-center" style="background: #fef3c7; border: 1px dashed #f59e0b; font-size: 0.78rem;">
                        <i class="bi bi-lightning-charge-fill text-warning me-1"></i>
                        <span>رمز التجربة السريع (للاختبار المحلي):</span>
                        <strong class="text-dark font-monospace fs-6 ms-1">{{ session('dev_otp') ?? $devOtp }}</strong>
                    </div>
                @endif

                {{-- نموذج إدخال رمز التحقق --}}
                <form action="{{ route('auth.otp.verify') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type ?? 'login' }}">
                    <input type="hidden" name="redirect_to" value="{{ session('url.intended') ?? request('redirect_to') ?? '' }}">

                    <div class="mb-4">
                        <label for="otp_code" class="form-label small fw-bold text-secondary mb-2 text-center d-block">
                            أدخل الرمز المكون من 6 أرقام:
                        </label>
                        <input type="text"
                               class="form-control otp-input-field @error('code') is-invalid @enderror"
                               id="otp_code"
                               name="code"
                               maxlength="6"
                               placeholder="------"
                               autocomplete="one-time-code"
                               inputmode="numeric"
                               autofocus
                               required>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-verify-submit">
                            <span>تأكيد ومتابعة الدخول</span>
                            <i class="bi bi-arrow-left"></i>
                        </button>
                    </div>
                </form>

                {{-- إعادة إرسال الرمز والعداد التنازلي --}}
                <div class="text-center mt-3 small text-muted">
                    <span>لم يصلك الرمز؟</span>
                    <form action="{{ route('auth.otp.resend') }}" method="POST" class="d-inline" id="resendForm">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type ?? 'login' }}">
                        <button type="submit" class="resend-btn ms-1" id="resendBtn">
                            إعادة إرسال الرمز
                        </button>
                    </form>
                    <div class="mt-1" style="font-size: 0.74rem;" id="timerWrap">
                        يمكنك طلب رمز جديد بعد <span id="countdown" class="fw-bold text-dark">60</span> ثانية
                    </div>
                </div>

                <div class="text-center mt-4 pt-3 border-top" style="font-size: 0.8rem;">
                    <a href="{{ route('login') }}" class="text-muted text-decoration-none">
                        <i class="bi bi-arrow-right me-1"></i>الرجوع واختيار حساب آخر
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // إدخال الأرقام فقط وتلقائية الإرسال عند اكتمال الـ 6 أرقام
    const otpInput = document.getElementById('otp_code');
    if (otpInput) {
        otpInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length === 6) {
                // إرسال تلقائي اختياري أو تمييز الحقل
                this.style.borderColor = '#16a34a';
            } else {
                this.style.borderColor = '';
            }
        });
    }

    // عداد تنازلي لإعادة الإرسال (60 ثانية)
    let timeLeft = 60;
    const countdownEl = document.getElementById('countdown');
    const resendBtn = document.getElementById('resendBtn');
    const timerWrap = document.getElementById('timerWrap');

    if (resendBtn && countdownEl) {
        resendBtn.disabled = true;

        const timer = setInterval(() => {
            timeLeft--;
            countdownEl.textContent = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(timer);
                resendBtn.disabled = false;
                timerWrap.style.display = 'none';
            }
        }, 1000);
    }
</script>
@endsection
