<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * عرض صفحة تسجيل الدخول
     */
    public function showLoginForm(Request $request)
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        if (!session()->has('url.intended')) {
            $prev = url()->previous();
            if ($prev && !str_contains($prev, '/login') && !str_contains($prev, '/register')) {
                session(['url.intended' => $prev]);
            }
        }

        return view('auth.login');
    }

    /**
     * معالجة طلب تسجيل الدخول
     */
    public function login(Request $request)
    {
        // التاكد من المدخلات وعدم تركها فارغة
        $credentials = $request->validate([ 
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'يرجى إدخال اسم المستخدم.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']], $remember)) {
            $user = Auth::user();

            // فحص حالة الحساب
            if (!$user->isActive()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    'username' => 'حسابك معطل أو غير نشط. يرجى التواصل مع إدارة النظام.',
                ]);
            }

            $request->session()->regenerate();

            // إذا كان المستخدم طالباً: التوجيه للصفحة التي ضغط منها تسجيل الدخول أو الصفحة الرئيسية
            if ($user->isStudent()) {
                $intended = session()->pull('url.intended');
                if ($intended && !str_contains($intended, '/login') && !str_contains($intended, '/register')) {
                    return redirect()->to($intended);
                }

                $redirectParam = $request->input('redirect_to');
                if ($redirectParam && !str_contains($redirectParam, '/login') && !str_contains($redirectParam, '/register')) {
                    return redirect()->to($redirectParam);
                }

                return redirect()->route('home');
            }

            return $this->redirectBasedOnRole($user);
        }

        throw ValidationException::withMessages([
            'username' => 'اسم المستخدم أو كلمة المرور غير صحيحة.',
        ]);
    }

    /**
     * عرض صفحة إنشاء حساب طالب جديد
     */
    public function showRegisterForm(Request $request)
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        if (!session()->has('url.intended')) {
            $prev = url()->previous();
            if ($prev && !str_contains($prev, '/login') && !str_contains($prev, '/register')) {
                session(['url.intended' => $prev]);
            }
        }

        return view('auth.register');
    }

    /**
     * معالجة تسجيل حساب طالب جديد
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:150'],
            'birth_date' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:20'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'full_name.required' => 'يرجى إدخال الاسم الكامل للطالب.',
            'birth_date.date' => 'صيغة تاريخ الميلاد غير صالحة.',
            'phone.max' => 'رقم الهاتف يجب ألا يتجاوز 20 خانة.',
            'username.required' => 'اسم المستخدم إلزامي.',
            'username.unique' => 'اسم المستخدم هذا مستخدم بالفعل، يرجى اختيار اسم آخر.',
            'password.required' => 'كلمة المرور إلزامية.',
            'password.min' => 'يجب ألا تقل كلمة المرور عن 6 أحرف.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'username' => $validated['username'],
                'password' => $validated['password'], // يتم تشفيرها تلقائياً عبر casts
                'user_type' => 'student',
                'phone' => $validated['phone'] ?? null,
                'email' => null,
                'account_status' => 'active',
                'theme_mode' => 'light',
            ]);

            Student::create([
                'user_id' => $user->user_id,
                'full_name' => $validated['full_name'],
                'gender' => null,
                'birth_date' => $validated['birth_date'] ?? null,
                'address' => null,
            ]);

            DB::commit();

            Auth::login($user);
            $request->session()->regenerate();

            $intended = session()->pull('url.intended');
            if ($intended && !str_contains($intended, '/login') && !str_contains($intended, '/register')) {
                return redirect()->to($intended)->with('success', 'تم إنشاء الحساب بنجاح، مرحباً بك في منصة EduYemen.');
            }

            return redirect()->route('home')->with('success', 'تم إنشاء الحساب بنجاح، مرحباً بك في منصة EduYemen.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['general' => 'حدث خطأ أثناء إنشاء الحساب: ' . $e->getMessage()]);
        }
    }

    /**
     * تسجيل الخروج الآمن والعودة للصفحة الرئيسية
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'تم تسجيل الخروج بنجاح.');
    }

    /**
     * توجيه المستخدم حسب نوع حسابه
     */
    private function redirectBasedOnRole(User $user)
    {
        return match ($user->user_type) { // داله خاصة لفحص قيمة "نوع الحساب"ا
            'admin' => redirect()->route('admin.dashboard'),
            'university' => redirect()->route('university.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default => redirect()->route('home'),
        };
    }
}
