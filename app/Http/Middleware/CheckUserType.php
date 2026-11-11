<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$types
     */
    public function handle(Request $request, Closure $next, string ...$types): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['username' => 'يرجى تسجيل الدخول أولاً للوصول إلى هذه الصفحة.']);
        }

        $user = Auth::user();

        // فحص حالة الحساب
        if (!$user->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'username' => 'حسابك معطل أو غير نشط. يرجى التواصل مع إدارة المنصة.',
            ]);
        }

        // فحص نوع الحساب والصلاحيات
        if (!empty($types) && !in_array($user->user_type, $types)) {
            // توجيه المستخدم تلقائياً للوحة التحكم الخاصة به إذا حاول دخول لوحة فئة أخرى
            return match ($user->user_type) {
                'admin' => redirect()->route('admin.dashboard')->with('error', 'غير مصرح لك بالوصول إلى هذه الصفحة.'),
                'university' => redirect()->route('university.dashboard')->with('error', 'غير مصرح لك بالوصول إلى هذه الصفحة.'),
                'student' => redirect()->route('student.dashboard')->with('error', 'غير مصرح لك بالوصول إلى هذه الصفحة.'),
                default => redirect()->route('home')->with('error', 'غير مصرح لك بالوصول إلى هذه الصفحة.'),
            };
        }

        return $next($request);
    }
}
