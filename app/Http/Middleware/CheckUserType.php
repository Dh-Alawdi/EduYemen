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
        // 1. أولاً: التحقق من الحراسة الخاصة بكل نوع مستخدم (Multi-Guard Session Isolation)
        // هذا يسمح بفتح المدير والجامعة والطالب في نوافذ منفصلة دون أي تعارض بين الجلسات
        foreach ($types as $type) {
            if (in_array($type, ['admin', 'university', 'student']) && Auth::guard($type)->check()) {
                $user = Auth::guard($type)->user();

                if (!$user->isActive()) {
                    Auth::guard($type)->logout();
                    return redirect()->route('login')->withErrors([
                        'username' => 'حسابك معطل أو غير نشط. يرجى التواصل مع إدارة المنصة.',
                    ]);
                }

                Auth::shouldUse($type);
                Auth::setUser($user);

                return $next($request);
            }
        }

        // 2. ثانياً: فحص الحراسة الافتراضية web إذا كان المستخدم مسجلاً بها
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            if (!$user->isActive()) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'username' => 'حسابك معطل أو غير نشط. يرجى التواصل مع إدارة المنصة.',
                ]);
            }

            // إذا كان نوع المستخدم يطابق النوع المطلوب، نقوم بمزامنته مع الحراسة المخصصة وتمرير الطلب
            if (empty($types) || in_array($user->user_type, $types)) {
                if (in_array($user->user_type, ['admin', 'university', 'student'])) {
                    Auth::guard($user->user_type)->setUser($user);
                    Auth::shouldUse($user->user_type);
                }
                Auth::setUser($user);

                return $next($request);
            }

            // إذا كان المستخدم مسجلاً بنوع آخر (مثلاً طالب يحاول فتح لوحة الجامعة):
            // لا نوجهه تلقائياً للوحة تحكمه لتجنب القفز بين النوافذ بل نطلب منه تسجيل الدخول بالحساب المطلوب
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك بالوصول إلى هذه الصفحة باستخدام هذا الحساب.'
                ], 403);
            }

            $requiredRoleName = match (reset($types)) {
                'admin' => 'مدير النظام',
                'university' => 'الجامعة',
                'student' => 'الطالب',
                default => 'حساب مصرح له',
            };

            return redirect()->route('login')->withErrors([
                'username' => "أنت مسجل حالياً بحساب ($user->user_type). يتطلب الوصول إلى هذه الصفحة تسجيل الدخول بحساب ($requiredRoleName).",
            ]);
        }

        // 3. المستخدم غير مسجل دخول على الإطلاق
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى تسجيل الدخول أولاً للوصول إلى هذه الصفحة.'
            ], 401);
        }

        return redirect()->route('login')->withErrors([
            'username' => 'يرجى تسجيل الدخول أولاً للوصول إلى هذه الصفحة.',
        ]);
    }
}
