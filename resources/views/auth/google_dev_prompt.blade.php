@extends('layouts.app')

@section('title', 'تهيئة تسجيل الدخول بحساب Google — منصة EduYemen')

@section('styles')
<style>
    body {
        background: radial-gradient(circle at 50% 20%, rgba(11, 61, 107, 0.08) 0%, rgba(244, 246, 251, 1) 100%) !important;
        min-height: 100vh;
    }
    .google-setup-card {
        max-width: 650px;
        margin: 40px auto;
        background: #ffffff;
        border-radius: 20px;
        padding: 35px 30px;
        box-shadow: 0 15px 40px rgba(11, 61, 107, 0.1);
        border: 1px solid #e2e8f0;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="google-setup-card">
        <div class="text-center mb-4">
            <svg width="48" height="48" viewBox="0 0 24 24" class="mb-2">
                <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17z"/>
                <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.27 21.36 7.35 24 12 24z"/>
                <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.98 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.35 0 3.27 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
            </svg>
            <h4 class="fw-bold text-dark">تسجيل الدخول بحساب Google</h4>
            <p class="text-muted small">إعدادات التكامل في بيئة التطوير (Local Development)</p>
        </div>

        <div class="alert alert-info py-2 px-3 small rounded-3 mb-4 d-flex align-items-center">
            <i class="bi bi-info-circle-fill ms-2 fs-5"></i>
            <div>
                تم تجهيز وبرمجة محرك Google OAuth 2.0 بالكامل في النظام. لربطه مباشرة مع حساب Google السحابي الحقيقي، قم بإضافة المتغيرين التاليين في ملف <code>.env</code>:
            </div>
        </div>

        <div class="bg-dark text-white p-3 rounded-3 mb-4 font-monospace" style="font-size: 0.8rem; direction: ltr; text-align: left;">
            GOOGLE_CLIENT_ID=your_google_client_id_here<br>
            GOOGLE_CLIENT_SECRET=your_google_client_secret_here
        </div>

        <div class="card p-3 border-dashed rounded-3 bg-light mb-4">
            <h6 class="fw-bold text-primary mb-2">
                <i class="bi bi-play-circle-fill me-1"></i> تجربة سريعة وفورية للمحاكاة (Google Auth Simulator):
            </h6>
            <p class="small text-muted mb-3">
                يمكنك تجربة دورة عمل زر Google فوراً (إنشاء طالب جديد تلقائياً وتفعيل بريده وتسجيل دخوله بنقرة واحدة):
            </p>

            <form action="{{ route('auth.google.mock') }}" method="POST">
                @csrf
                <div class="row g-2 mb-2">
                    <div class="col-sm-6">
                        <label class="form-label small fw-bold text-secondary mb-1">اسم الطالب التجريبي:</label>
                        <input type="text" name="name" class="form-control form-control-sm" value="طالب قوقل التجريبي" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-bold text-secondary mb-1">البريد الإلكتروني:</label>
                        <input type="email" name="email" class="form-control form-control-sm" value="google.student@gmail.com" required dir="ltr">
                    </div>
                </div>
                <div class="d-grid mt-3">
                    <button type="submit" class="btn btn-primary btn-sm py-2 fw-bold">
                        <i class="bi bi-check2-circle me-1"></i> إتمام المحاكاة والدخول كطالب Google
                    </button>
                </div>
            </form>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-muted small text-decoration-none">
                <i class="bi bi-arrow-right me-1"></i> العودة لصفحة تسجيل الدخول العادية
            </a>
        </div>
    </div>
</div>
@endsection
