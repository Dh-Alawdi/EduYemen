<?php

namespace App\Http\Controllers;

use App\Mail\AuthOtpMail;
use App\Mail\ResetPasswordOtpMail;
use App\Models\EmailVerificationCode;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * التحقق من سلامة رابط إعادة التوجيه لمنع التوجيه الضار أو العشوائي
     */
    private function isSafeRedirectUrl(?string $url): bool
    {
        if (empty($url)) {
            return false;
        }

        $blockedPatterns = [
            '/login',
            '/register',
            '/logout',
            '/verify-otp',
            '/forgot-password',
            '/reset-password',
            '/auth/google',
        ];

        foreach ($blockedPatterns as $pattern) {
            if (str_contains($url, $pattern)) {
                return false;
            }
        }

        // مسار نسبي آمن (مثال: /universities/5)
        if (str_starts_with($url, '/') && !str_starts_with($url, '//')) {
            return true;
        }

        // مسار كامل تابع لنفس التطبيق
        $appUrl = url('/');
        if (!empty($appUrl) && $appUrl !== 'http://:' && str_starts_with($url, $appUrl)) {
            return true;
        }

        // التحقق من اسم النطاق (Host) ليتطابق مع المنصة أو بيئة التطوير
        $host = parse_url($url, PHP_URL_HOST);
        $appHost = parse_url($appUrl, PHP_URL_HOST);
        if ($host && ($host === $appHost || $host === 'localhost' || $host === '127.0.0.1')) {
            return true;
        }

        return false;
    }

    /**
     * التحقق مما إذا كان الرابط يمثل صفحة مقصودة محددة (وليس مجرد الصفحة الرئيسية العامة)
     */
    private function isSpecificIntendedUrl(?string $url): bool
    {
        if (empty($url) || !$this->isSafeRedirectUrl($url)) {
            return false;
        }

        $path = trim(parse_url($url, PHP_URL_PATH) ?? '', '/');
        $homePath = trim(parse_url(url('/'), PHP_URL_PATH) ?? '', '/');

        // استبعاد الصفحة الرئيسية العامة والمسار الفارغ
        if ($path === $homePath || $path === '') {
            return false;
        }

        return true;
    }

    /**
     * عرض صفحة تسجيل الدخول الموحدة (تشمل الدخول وإنشاء الحساب في آن واحد)
     */
    public function showLoginForm(Request $request)
    {
        // نحفظ الرابط السابق فقط إذا كان صفحة مقصودة محددة (مثل صفحة جامعة جاء منها الزائر)
        $candidate = $request->query('return') ?: $request->query('redirect_to') ?: url()->previous();
        if (!session()->has('url.intended') && $candidate && $this->isSpecificIntendedUrl($candidate)) {
            session(['url.intended' => $candidate]);
        }

        return view('auth.login');
    }

    /**
     * معالجة تسجيل الدخول الموحد الشامل:
     * - إذا كان الحساب مسجلاً مسبقاً (مدير، جامعة، طالب): فحص كلمة المرور والدخول المباشر فوراً دون أي كود OTP!
     * - إذا كان الحساب جديداً: إنشاء وتفعيل حساب طالب تلقائياً بعد التحقق من ملكية البريد عبر رمز الأمان (OTP).
     */
    public function login(Request $request)
    {
        $validated = $request->validate([ 
            'username' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'username.required' => 'يرجى إدخال البريد الإلكتروني أو اسم المستخدم.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
            'password.min'      => 'كلمة المرور يجب ألا تقل عن 6 خانات.',
        ]);

        $loginInput = trim($validated['username']);
        $password   = $validated['password'];

        // حفظ رابط الوجهة إن تم تمريره في الطلب
        if ($request->filled('redirect_to') && $this->isSafeRedirectUrl($request->redirect_to)) {
            session(['url.intended' => $request->redirect_to]);
        }

        // الكشف الذكي عما إذا كان المدخل بريداً إلكترونياً أم اسم مستخدم
        $isEmail = filter_var($loginInput, FILTER_VALIDATE_EMAIL);

        // البحث عن الحساب في قاعدة البيانات
        $user = $isEmail 
            ? User::where('email', $loginInput)->first()
            : User::where('username', $loginInput)->first();

        if (!$user && !$isEmail) {
            $user = User::where('email', $loginInput)->orWhere('username', $loginInput)->first();
        }

        // =========================================================================
        // المسار الأول: المستخدم مسجل مسبقاً (مدير نظام، جامعة، أو طالب موجود)
        // =========================================================================
        if ($user) {
            // التحقق من صحة كلمة المرور المدخلة
            if (!Hash::check($password, $user->password)) {
                throw ValidationException::withMessages([
                    'username' => 'كلمة المرور غير صحيحة لهذا الحساب. هل نسيت كلمة المرور؟ يمكنك استعادتها وتعيين كلمة مرور جديدة بسهولة عبر الرابط أدناه.',
                ]);
            }

            // فحص حالة الحساب
            if (!$user->isActive()) {
                throw ValidationException::withMessages([
                    'username' => 'حسابك معطل أو غير نشط. يرجى التواصل مع إدارة المنصة.',
                ]);
            }

            // إذا كان البريد وكلمة المرور صحيحين: تسجيل الدخول مباشرة فوراً دون طلب أي رمز أمان!
            return $this->completeLogin($user, $request->boolean('remember'), $request);
        }

        // =========================================================================
        // المسار الثاني: المستخدم غير موجود مسبقاً -> إنشاء حساب طالب جديد تلقائياً
        // =========================================================================
        if (!$isEmail) {
            throw ValidationException::withMessages([
                'username' => 'هذا الحساب غير مسجل مسبقاً. لإنشاء حساب طالب جديد فوراً، يرجى كتابة بريدك الإلكتروني.',
            ]);
        }

        // توليد رمز الأمان السداسي لتأكيد ملكية البريد وتفعيل الحساب الجديد
        $otpCode = sprintf("%06d", mt_rand(100000, 999999));

        EmailVerificationCode::where('email', $loginInput)
            ->where('type', 'register_otp')
            ->delete();

        EmailVerificationCode::create([
            'email'      => $loginInput,
            'code'       => $otpCode,
            'type'       => 'register_otp',
            'user_id'    => null,
            'expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::to($loginInput)->send(new AuthOtpMail($otpCode, 'register', explode('@', $loginInput)[0]));
        } catch (\Exception $e) {
            Log::error('فشل إرسال كود التحقق لإنشاء الحساب الجديد: ' . $e->getMessage());
        }

        // حفظ بيانات الحساب الجديد المراد إنشاؤه في الجلسة لحين كتابة الرمز
        session([
            'auth_pending_type'     => 'register',
            'auth_pending_email'    => $loginInput,
            'auth_pending_password' => $password,
            'auth_dev_otp'          => app()->isLocal() ? $otpCode : null,
        ]);

        return redirect()->route('auth.otp.show')->with('success', 'أهلاً بك! تم إرسال رمز الأمان لتأكيد بريدك الإلكتروني وتفعيل حساب الطالب الخاص بك.');
    }

    /**
     * عرض شاشة إدخال رمز التحقق (OTP)
     */
    public function showOtpForm(Request $request)
    {
        $pendingEmail = session('auth_pending_email');
        $pendingType  = session('auth_pending_type', 'login');

        if (!$pendingEmail) {
            return redirect()->route('login');
        }

        // تمويه البريد الإلكتروني للأمان (مثال: a*****i@gmail.com)
        $parts = explode('@', $pendingEmail);
        $name = $parts[0];
        $domain = $parts[1] ?? '';
        $maskedName = strlen($name) <= 2 ? $name . '***' : substr($name, 0, 1) . str_repeat('*', max(3, strlen($name) - 2)) . substr($name, -1);
        $maskedEmail = $maskedName . '@' . $domain;

        $devOtp = session('auth_dev_otp');

        return view('auth.verify_otp', [
            'maskedEmail' => $maskedEmail,
            'type'        => $pendingType,
            'devOtp'      => $devOtp,
        ]);
    }

    /**
     * التحقق من صحة كود الأمان المدخل (OTP) للدخول أو التفعيل
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ], [
            'code.required' => 'يرجى إدخال رمز التحقق المكون من 6 أرقام.',
            'code.size'     => 'رمز التحقق يجب أن يتكون من 6 أرقام بدقة.',
        ]);

        $pendingEmail = session('auth_pending_email');
        $pendingType  = session('auth_pending_type');

        if (!$pendingEmail || !$pendingType) {
            return redirect()->route('login')->with('error', 'انتهت جلسة التحقق، يرجى المحاولة من جديد.');
        }

        $code = trim($request->input('code'));
        $targetType = ($pendingType === 'register') ? 'register_otp' : 'login_otp';

        // البحث عن الرمز والتأكد من عدم انتهاء صلاحيته
        $verification = EmailVerificationCode::where('email', $pendingEmail)
            ->where('code', $code)
            ->where('type', $targetType)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return back()->withErrors(['code' => 'رمز التحقق غير صحيح أو انتهت صلاحيته (المدة 10 دقائق). يرجى التأكد أو طلب رمز جديد.']);
        }

        // حذف الرمز المستخدم
        $verification->delete();

        // ---------------------------------------------------------------------
        // الحالة الأولى: تسجيل دخول لحساب موجود
        // ---------------------------------------------------------------------
        if ($pendingType === 'login') {
            $userId = session('auth_pending_user_id');
            $user   = User::find($userId);

            if (!$user) {
                return redirect()->route('login')->with('error', 'تعذر العثور على الحساب.');
            }

            if (!$user->email_verified_at) {
                $user->update(['email_verified_at' => now()]);
            }

            $this->clearPendingSession();

            return $this->completeLogin($user, false, $request);
        }

        // ---------------------------------------------------------------------
        // الحالة الثانية: إنشاء وتفعيل حساب طالب جديد تلقائياً
        // ---------------------------------------------------------------------
        if ($pendingType === 'register') {
            $pendingPassword = session('auth_pending_password');

            if (!$pendingPassword) {
                return redirect()->route('login')->with('error', 'انتهت الجلسة، يرجى إعادة المحاولة من جديد.');
            }

            // توليد اسم مستخدم فريد ومناسب
            $baseUsername = Str::slug(explode('@', $pendingEmail)[0], '_');
            $username = $baseUsername ?: 'student_' . mt_rand(1000, 9999);
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . '_' . $counter++;
            }

            // اسم افتراضي من جزء البريد
            $defaultName = ucwords(str_replace(['.', '_', '-'], ' ', explode('@', $pendingEmail)[0]));

            DB::beginTransaction();
            try {
                $user = User::create([
                    'username'          => $username,
                    'password'          => $pendingPassword, // casts: hashed
                    'user_type'         => 'student', // حصراً طالب
                    'email'             => $pendingEmail,
                    'email_verified_at' => now(),
                    'account_status'    => 'active',
                    'theme_mode'        => 'light',
                ]);

                Student::create([
                    'user_id'   => $user->user_id,
                    'full_name' => $defaultName,
                ]);

                DB::commit();

                $this->clearPendingSession();

                return $this->completeLogin($user, false, $request);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('فشل إنشاء حساب الطالب الجديد: ' . $e->getMessage());
                return redirect()->route('login')->withErrors(['username' => 'حدث خطأ أثناء حفظ الحساب: ' . $e->getMessage()]);
            }
        }

        return redirect()->route('login');
    }

    /**
     * إعادة إرسال رمز تحقق جديد
     */
    public function resendOtp(Request $request)
    {
        $pendingEmail = session('auth_pending_email');
        $pendingType  = session('auth_pending_type', 'login');

        if (!$pendingEmail) {
            return redirect()->route('login');
        }

        $otpCode = sprintf("%06d", mt_rand(100000, 999999));
        $targetType = ($pendingType === 'register') ? 'register_otp' : 'login_otp';

        EmailVerificationCode::where('email', $pendingEmail)
            ->where('type', $targetType)
            ->delete();

        EmailVerificationCode::create([
            'email'      => $pendingEmail,
            'code'       => $otpCode,
            'type'       => $targetType,
            'user_id'    => session('auth_pending_user_id'),
            'expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::to($pendingEmail)->send(new AuthOtpMail($otpCode, $pendingType));
        } catch (\Exception $e) {
            Log::error('فشل إعادة إرسال رمز OTP: ' . $e->getMessage());
        }

        session(['auth_dev_otp' => app()->isLocal() ? $otpCode : null]);

        return back()->with('success', 'تم إرسال رمز تحقق جديد إلى بريدك الإلكتروني.');
    }

    /**
     * توجيه أي طلب قديم لإنشاء الحساب إلى صفحة الدخول الموحدة
     */
    public function showRegisterForm(Request $request)
    {
        return redirect()->route('login');
    }

    /**
     * عرض صفحة طلب استعادة كلمة المرور
     */
    public function showForgotPasswordForm(Request $request)
    {
        // نحفظ الرابط السابق فقط إذا كان صفحة مقصودة محددة (مثل صفحة جامعة)
        $candidate = $request->query('return') ?: $request->query('redirect_to') ?: url()->previous();
        if (!session()->has('url.intended') && $candidate && $this->isSpecificIntendedUrl($candidate)) {
            session(['url.intended' => $candidate]);
        }

        return view('auth.forgot_password');
    }

    /**
     * إرسال رمز الأمان (OTP) لإعادة تعيين كلمة المرور
     */
    public function sendResetOtp(Request $request)
    {
        $validated = $request->validate([
            'identity' => ['required', 'string'],
        ], [
            'identity.required' => 'يرجى إدخال البريد الإلكتروني أو اسم المستخدم.',
        ]);

        // الحفاظ على رابط الوجهة إن تم تمريره
        if ($request->filled('redirect_to') && $this->isSafeRedirectUrl($request->redirect_to)) {
            session(['url.intended' => $request->redirect_to]);
        }

        $identity = trim($validated['identity']);
        $isEmail  = filter_var($identity, FILTER_VALIDATE_EMAIL);

        $user = $isEmail 
            ? User::where('email', $identity)->first()
            : User::where('username', $identity)->first();

        if (!$user && !$isEmail) {
            $user = User::where('email', $identity)->first();
        }

        if (!$user || empty($user->email)) {
            throw ValidationException::withMessages([
                'identity' => 'لم نتمكن من العثور على حساب مرتبط بهذا البريد أو اسم المستخدم، أو أن الحساب لا يحتوي على بريد مسجل للاستعادة.',
            ]);
        }

        // توليد رمز إعادة تعيين سداسي صالح لمدة 15 دقيقة
        $otpCode = sprintf("%06d", mt_rand(100000, 999999));

        EmailVerificationCode::where('email', $user->email)
            ->where('type', 'reset_password_otp')
            ->delete();

        EmailVerificationCode::create([
            'email'      => $user->email,
            'code'       => $otpCode,
            'type'       => 'reset_password_otp',
            'user_id'    => $user->user_id,
            'expires_at' => now()->addMinutes(15),
        ]);

        try {
            Mail::to($user->email)->send(new ResetPasswordOtpMail($otpCode, $user->username));
        } catch (\Exception $e) {
            Log::error('فشل إرسال كود إعادة تعيين كلمة المرور: ' . $e->getMessage());
        }

        session([
            'password_reset_email'   => $user->email,
            'password_reset_user_id' => $user->user_id,
            'password_reset_dev_otp' => app()->isLocal() ? $otpCode : null,
        ]);

        return redirect()->route('password.reset')->with('success', 'تم إرسال رمز الأمان لإعادة تعيين كلمة المرور إلى بريدك الإلكتروني.');
    }

    /**
     * عرض صفحة إدخال رمز الأمان وتعيين كلمة المرور الجديدة
     */
    public function showResetPasswordForm(Request $request)
    {
        $email = session('password_reset_email');
        if (!$email) {
            return redirect()->route('password.request')->with('error', 'يرجى إدخال بريدك الإلكتروني أولاً لاستلام رمز إعادة التعيين.');
        }

        // تمويه البريد للأمان (مثال: a*****i@gmail.com)
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1] ?? '';
        $maskedName = strlen($name) <= 2 ? $name . '***' : substr($name, 0, 1) . str_repeat('*', max(3, strlen($name) - 2)) . substr($name, -1);
        $maskedEmail = $maskedName . '@' . $domain;

        $devOtp = session('password_reset_dev_otp');

        return view('auth.reset_password', [
            'maskedEmail' => $maskedEmail,
            'devOtp'      => $devOtp,
        ]);
    }

    /**
     * التحقق من الرمز وحفظ كلمة المرور الجديدة (اختياري) والدخول المباشر فوراً دون إرجاع لصفحة تسجيل الدخول
     */
    public function resetPassword(Request $request)
    {
        $rules = [
            'code' => ['required', 'string', 'size:6'],
        ];

        $messages = [
            'code.required' => 'يرجى إدخال رمز التحقق المكون من 6 أرقام.',
            'code.size'     => 'رمز التحقق يجب أن يتكون من 6 أرقام بدقة.',
        ];

        // تغيير كلمة المرور اختياري: إذا أدخل كلمة مرور جديدة ولم يختر زر التخطي للدخول المباشر
        $hasPassword = $request->filled('password') && $request->input('action') !== 'direct_login';

        if ($hasPassword) {
            $rules['password'] = ['required', 'string', 'min:6', 'confirmed'];
            $messages['password.min']       = 'كلمة المرور يجب ألا تقل عن 6 أحرف.';
            $messages['password.confirmed'] = 'تأكيد كلمة المرور غير متطابق.';
        }

        $request->validate($rules, $messages);

        $email = session('password_reset_email');
        if (!$email) {
            return redirect()->route('password.request')->with('error', 'انتهت الجلسة، يرجى إعادة طلب رمز التحقق.');
        }

        $code = trim($request->input('code'));

        $verification = EmailVerificationCode::where('email', $email)
            ->where('code', $code)
            ->where('type', 'reset_password_otp')
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return back()->withErrors(['code' => 'رمز التحقق غير صحيح أو انتهت صلاحيته (15 دقيقة). يرجى التأكد أو طلب رمز جديد.']);
        }

        $verification->delete();

        $userId = session('password_reset_user_id');
        $user   = User::find($userId) ?? User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'تعذر العثور على الحساب، يرجى المحاولة لاحقاً.');
        }

        // تحديث كلمة المرور إن تم تقديمها
        if ($hasPassword) {
            $user->update([
                'password'             => $request->password, // casts: hashed
                'must_change_password' => false,
            ]);
            $successMsg = 'تم تحديث كلمة المرور وتسجيل الدخول بنجاح!';
        } else {
            $successMsg = 'تم التحقق من هويتك بنجاح ومتابعة تسجيل الدخول!';
        }

        // تنظيف جلسة الاستعادة
        session()->forget([
            'password_reset_email',
            'password_reset_user_id',
            'password_reset_dev_otp',
        ]);

        // تسجيل الدخول المباشر فوراً دون إرجاع المستخدم لصفحة تسجيل الدخول!
        return $this->completeLogin($user, false, $request)->with('success', $successMsg);
    }

    /**
     * إعادة إرسال رمز استعادة كلمة المرور
     */
    public function resendResetOtp(Request $request)
    {
        $email = session('password_reset_email');
        if (!$email) {
            return redirect()->route('password.request');
        }

        $user = User::where('email', $email)->first();
        $otpCode = sprintf("%06d", mt_rand(100000, 999999));

        EmailVerificationCode::where('email', $email)
            ->where('type', 'reset_password_otp')
            ->delete();

        EmailVerificationCode::create([
            'email'      => $email,
            'code'       => $otpCode,
            'type'       => 'reset_password_otp',
            'user_id'    => $user ? $user->user_id : null,
            'expires_at' => now()->addMinutes(15),
        ]);

        try {
            Mail::to($email)->send(new ResetPasswordOtpMail($otpCode, $user ? $user->username : null));
        } catch (\Exception $e) {
            Log::error('فشل إعادة إرسال كود استعادة المرور: ' . $e->getMessage());
        }

        session(['password_reset_dev_otp' => app()->isLocal() ? $otpCode : null]);

        return back()->with('success', 'تم إرسال رمز إعادة تعيين جديد إلى بريدك الإلكتروني.');
    }

    /**
     * إعادة التوجيه إلى Google للمصادقة السريعة
     */
    public function redirectToGoogle(Request $request)
    {
        $clientId = config('services.google.client_id') ?? env('GOOGLE_CLIENT_ID');

        // إذا لم تكن مفاتيح Google معدة في ملف .env: نوفر خيار محاكاة سريع للمطور
        if (empty($clientId)) {
            if (app()->isLocal()) {
                return view('auth.google_dev_prompt');
            }
            return redirect()->route('login')->with('error', 'خدمة تسجيل الدخول بحساب Google غير مفعلة حالياً في الخادم (يرجى ضبط GOOGLE_CLIENT_ID في .env).');
        }

        // حفظ الرابط المقصود قبل الانتقال لـ Google
        $candidate = $request->query('return') ?: $request->query('redirect_to') ?: url()->previous();
        if (!session()->has('url.intended') && $candidate && $this->isSafeRedirectUrl($candidate)) {
            session(['url.intended' => $candidate]);
        }

        $redirectUri = config('services.google.redirect') ?: route('auth.google.callback');
        $state = Str::random(40);

        session([
            'google_oauth_state'        => $state,
            'google_oauth_redirect_uri' => $redirectUri,
        ]);

        $query = http_build_query([
            'client_id'     => $clientId,
            'redirect_uri'  => $redirectUri,
            'response_type' => 'code',
            'scope'         => 'openid profile email',
            'access_type'   => 'online',
            'state'         => $state,
            'prompt'        => 'select_account',
        ]);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    /**
     * استقبال رد Google بعد المصادقة
     */
    public function handleGoogleCallback(Request $request)
    {
        if ($request->has('error')) {
            $errorDesc = $request->input('error_description') ?: $request->input('error');
            Log::warning('إلغاء أو خطأ في مصادقة Google: ' . $errorDesc);
            return redirect()->route('login')->with('error', 'تم إلغاء أو تعذر المصادقة عبر Google: ' . $errorDesc);
        }

        $code = $request->input('code');
        if (!$code) {
            return redirect()->route('login')->with('error', 'لم يتم استلام كود المصادقة من Google.');
        }

        // التحقق من حالة الأمان State للحماية من CSRF
        $savedState = session()->pull('google_oauth_state');
        if ($savedState && $request->input('state') !== $savedState) {
            return redirect()->route('login')->with('error', 'فشل التحقق الأمني من حالة طلب Google، يرجى المحاولة من جديد.');
        }

        $clientId     = config('services.google.client_id') ?? env('GOOGLE_CLIENT_ID');
        $clientSecret = config('services.google.client_secret') ?? env('GOOGLE_CLIENT_SECRET');
        $redirectUri  = session()->pull('google_oauth_redirect_uri') ?: (config('services.google.redirect') ?: route('auth.google.callback'));

        try {
            // 1. تبادل الكود للحصول على Access Token
            $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'code'          => $code,
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri'  => $redirectUri,
                'grant_type'    => 'authorization_code',
            ]);

            if (!$tokenResponse->successful()) {
                Log::error('فشل جلب Google Token: ' . $tokenResponse->body());
                $errorMsg = $tokenResponse->json('error_description') ?? $tokenResponse->json('error') ?? 'تعذر استبدال رمز Google';
                return redirect()->route('login')->with('error', 'خطأ في استبدال رمز Google: ' . $errorMsg);
            }

            $accessToken = $tokenResponse->json('access_token');

            // 2. جلب بيانات ملف المستخدم من Google
            $userResponse = Http::withToken($accessToken)->get('https://www.googleapis.com/oauth2/v3/userinfo');

            if (!$userResponse->successful()) {
                Log::error('فشل جلب بيانات ملف Google: ' . $userResponse->body());
                return redirect()->route('login')->with('error', 'تعذر جلب بيانات الحساب من Google.');
            }

            $googleData = $userResponse->json();
            return $this->loginOrCreateGoogleUser($googleData, $request);

        } catch (\Exception $e) {
            Log::error('خطأ غير متوقع أثناء الدخول بـ Google: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'حدث خطأ أثناء الاتصال بخدمات Google: ' . $e->getMessage());
        }
    }

    /**
     * محاكاة سريعة لتسجيل الدخول بـ Google في بيئة التطوير المحلية
     */
    public function mockGoogleLogin(Request $request)
    {
        if (!app()->isLocal()) {
            abort(403);
        }

        $request->validate([
            'email' => ['required', 'email'],
            'name'  => ['required', 'string'],
        ]);

        $mockData = [
            'sub'     => 'google_mock_' . md5($request->email),
            'email'   => $request->email,
            'name'    => $request->name,
            'picture' => 'https://ui-avatars.com/api/?name=' . urlencode($request->name) . '&background=0b3d6b&color=fff',
        ];

        return $this->loginOrCreateGoogleUser($mockData, $request);
    }

    /**
     * تسجيل الدخول بحساب Google أو إنشاؤه كطالب إذا لم يكن موجوداً
     */
    private function loginOrCreateGoogleUser(array $googleData, Request $request)
    {
        $googleId = $googleData['sub'] ?? null;
        $email    = $googleData['email'] ?? null;
        $fullName = $googleData['name'] ?? 'مستخدم Google';
        $picture  = $googleData['picture'] ?? null;

        if (!$email) {
            return redirect()->route('login')->with('error', 'لم يقم حساب Google بتزويدنا بعنوان بريد إلكتروني صالح.');
        }

        // البحث عن المستخدم بـ google_id أولاً ثم بالبريد الإلكتروني
        $user = User::where('google_id', $googleId)->first();
        if (!$user) {
            $user = User::where('email', $email)->first();
        }

        if ($user) {
            // تحديث بيانات Google إذا كانت غير مكتملة
            $user->update([
                'google_id'          => $googleId,
                'email_verified_at'  => $user->email_verified_at ?? now(),
                'avatar_url'         => $picture ?? $user->avatar_url,
                'last_login'         => now(),
            ]);
        } else {
            // توليد اسم مستخدم فريد ومناسب
            $baseUsername = Str::slug(explode('@', $email)[0], '_');
            $username = $baseUsername;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . '_' . $counter++;
            }

            DB::beginTransaction();
            try {
                $user = User::create([
                    'username'          => $username,
                    'password'          => Hash::make(Str::random(32)),
                    'user_type'         => 'student', // حصراً طالب
                    'email'             => $email,
                    'email_verified_at' => now(),
                    'google_id'         => $googleId,
                    'avatar_url'        => $picture,
                    'account_status'    => 'active',
                    'theme_mode'        => 'light',
                    'last_login'        => now(),
                ]);

                Student::create([
                    'user_id'   => $user->user_id,
                    'full_name' => $fullName,
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('فشل إنشاء حساب عبر Google: ' . $e->getMessage());
                return redirect()->route('login')->with('error', 'تعذر إنشاء الحساب عبر Google: ' . $e->getMessage());
            }
        }

        return $this->completeLogin($user, false, $request);
    }

    /**
     * إكمال الجلسة وتوجيه المستخدم للوحة التحكم أو الوجهة المطلوبة بدقة حسب نوع حسابه
     */
    private function completeLogin(User $user, bool $remember, Request $request)
    {
        $request->session()->regenerate();

        Auth::guard('web')->login($user, $remember);

        if (in_array($user->user_type, ['admin', 'university', 'student'])) {
            Auth::guard($user->user_type)->login($user, $remember);
        }

        $user->update(['last_login' => now()]);

        // الوجهة المطلوبة إن وجدت
        $targetUrl = $request->input('redirect_to') ?: session()->pull('url.intended');

        // =========================================================================
        // 1. مدير النظام (Admin): يتوجه دائماً إلى لوحة تحكم المدير الرئيسية!
        // إلا إذا كان يحاول الوصول لرابط محدد يتبع إدارة النظام (مثل /admin/users)
        // =========================================================================
        if ($user->isAdmin()) {
            if ($targetUrl && $this->isSafeRedirectUrl($targetUrl)) {
                $path = parse_url($targetUrl, PHP_URL_PATH) ?? $targetUrl;
                if (str_contains($path, '/admin') && !str_contains($path, '/login')) {
                    return redirect()->to($targetUrl)->with('success', 'مرحباً بك في لوحة تحكم مدير النظام.');
                }
            }
            return redirect()->route('admin.dashboard')->with('success', 'مرحباً بك في لوحة تحكم مدير النظام.');
        }

        // =========================================================================
        // 2. ممثل الجامعة (University): يتوجه دائماً إلى بوابة الجامعة الرئيسية!
        // إلا إذا كان يحاول الوصول لرابط محدد يتبع بوابة الجامعة (مثل /university/programs)
        // =========================================================================
        if ($user->isUniversity()) {
            if ($targetUrl && $this->isSafeRedirectUrl($targetUrl)) {
                $path = parse_url($targetUrl, PHP_URL_PATH) ?? $targetUrl;
                if (str_contains($path, '/university') && !str_contains($path, '/universities') && !str_contains($path, '/login')) {
                    return redirect()->to($targetUrl)->with('success', 'مرحباً بك في بوابة الجامعة.');
                }
            }
            return redirect()->route('university.dashboard')->with('success', 'مرحباً بك في بوابة الجامعة.');
        }

        // =========================================================================
        // 3. حساب الطالب (Student):
        // إذا كان يتصفح صفحة محددة (مثل صفحة جامعة معينة /universities/5 أو مقارنة)، نعيده إليها
        // =========================================================================
        if ($user->isStudent()) {
            if ($targetUrl && $this->isSpecificIntendedUrl($targetUrl)) {
                $path = parse_url($targetUrl, PHP_URL_PATH) ?? $targetUrl;
                // حماية: منع توجيه الطالب لصفحات الإدارة أو الجامعة
                if (!str_contains($path, '/admin') && !str_contains($path, '/university/')) {
                    return redirect()->to($targetUrl)->with('success', 'تم تسجيل الدخول بنجاح، مرحباً بك في منصة EduYemen.');
                }
            }
            return redirect()->route('home')->with('success', 'تم تسجيل الدخول بنجاح، مرحباً بك في منصة EduYemen.');
        }

        return $this->redirectBasedOnRole($user);
    }

    /**
     * مسح متغيرات جلسة التحقق المؤقتة
     */
    private function clearPendingSession()
    {
        session()->forget([
            'auth_pending_type',
            'auth_pending_user_id',
            'auth_pending_email',
            'auth_pending_password',
            'auth_pending_remember',
            'auth_pending_registration_data',
            'auth_dev_otp',
        ]);
    }

    /**
     * تسجيل الخروج الآمن والعودة للصفحة الرئيسية
     */
    public function logout(Request $request)
    {
        $role = $request->input('role');
        $referer = url()->previous();

        if ($role && in_array($role, ['admin', 'university', 'student'])) {
            Auth::guard($role)->logout();
            if (Auth::guard('web')->check() && Auth::guard('web')->user()->user_type === $role) {
                Auth::guard('web')->logout();
            }
        } elseif (str_contains($referer, '/admin')) {
            Auth::guard('admin')->logout();
            if (Auth::guard('web')->check() && Auth::guard('web')->user()->isAdmin()) {
                Auth::guard('web')->logout();
            }
        } elseif (str_contains($referer, '/university')) {
            Auth::guard('university')->logout();
            if (Auth::guard('web')->check() && Auth::guard('web')->user()->isUniversity()) {
                Auth::guard('web')->logout();
            }
        } elseif (str_contains($referer, '/student')) {
            Auth::guard('student')->logout();
            if (Auth::guard('web')->check() && Auth::guard('web')->user()->isStudent()) {
                Auth::guard('web')->logout();
            }
        } else {
            // تسجيل خروج كامل
            Auth::guard('admin')->logout();
            Auth::guard('university')->logout();
            Auth::guard('student')->logout();
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('home')->with('success', 'تم تسجيل الخروج بنجاح.');
    }

    /**
     * توجيه المستخدم حسب نوع حسابه
     */
    private function redirectBasedOnRole(User $user)
    {
        return match ($user->user_type) {
            'admin'      => redirect()->route('admin.dashboard'),
            'university' => redirect()->route('university.dashboard'),
            'student'    => redirect()->route('student.dashboard'),
            default      => redirect()->route('home'),
        };
    }
}
