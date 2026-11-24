<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Advertisement;
use App\Models\College;
use App\Models\ContentModeration;
use App\Models\RegRequest;
use App\Models\RegProof;
use App\Models\Specialization;
use App\Models\Student;
use App\Models\StudentReferral;
use App\Models\SystemSetting;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * لوحة التحكم الرئيسية لمدير النظام
     */
    public function dashboard()
    {
        $stats = $this->getSystemStats();
        $recentUniversities = University::with('user')
            ->withCount(['colleges', 'regRequests' => fn($q) => $q->where('status', '!=', 'مسودة')])
            ->latest('university_id')
            ->take(5)
            ->get();

        $recentRequests = RegRequest::with(['student', 'university'])
            ->where('status', '!=', 'مسودة')
            ->latest('req_id')
            ->take(6)
            ->get();

        $recentUsers = User::latest('user_id')->take(5)->get();

        // إحصائيات الإعلانات
        $pendingAds = Advertisement::where('review_status', 'قيد المراجعة')->count();

        // إحصائيات الإحالات
        $pendingCommissions = StudentReferral::where('ref_status', 'مكتملة')->whereNull('commission_paid_at')->count();

        // نشاط المنصة بالأيام السبعة الأخيرة
        $weeklyActivity = $this->getWeeklyActivity();

        return view('dashboards.admin.index', compact(
            'stats', 'recentUniversities', 'recentRequests',
            'recentUsers', 'pendingAds', 'pendingCommissions', 'weeklyActivity'
        ));
    }

    /**
     * إدارة حسابات المستخدمين
     */
    /**
     * إدارة حسابات المستخدمين والصلاحيات
     */
    public function users(Request $request)
    {
        // تحديث آخر دخول للمشرف الحالي إذا كان غير مسجل
        if (Auth::check() && (!Auth::user()->last_login || \Carbon\Carbon::parse(Auth::user()->last_login)->diffInHours(now()) >= 1)) {
            Auth::user()->update(['last_login' => now()]);
        }

        $query = User::query();

        if ($request->filled('type')) {
            $query->where('user_type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('account_status', $request->status);
        }
        if ($request->filled('security')) {
            if ($request->security === 'temp_pass') {
                $query->where('must_change_password', true);
            }
        }
        if ($request->filled('search')) {
            $q = trim($request->search);
            $query->where(function ($sq) use ($q) {
                $sq->where('username', 'like', "%$q%")
                   ->orWhere('phone', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%")
                   ->orWhereHas('student', function ($stq) use ($q) {
                       $stq->where('full_name', 'like', "%$q%");
                   })
                   ->orWhereHas('university', function ($uq) use ($q) {
                       $uq->where('university_name', 'like', "%$q%");
                   })
                   ->orWhereHas('admin', function ($aq) use ($q) {
                       $aq->where('full_name', 'like', "%$q%");
                   });
            });
        }

        // خيارات الترتيب
        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'oldest' => $query->oldest('user_id'),
            'last_login' => $query->orderByDesc('last_login'),
            'username_asc' => $query->orderBy('username', 'asc'),
            'username_desc' => $query->orderBy('username', 'desc'),
            default => $query->latest('user_id'),
        };

        $perPage = in_array((int)$request->get('per_page', 15), [10, 15, 25, 50, 100]) ? (int)$request->get('per_page', 15) : 15;

        $users = $query->with(['student', 'university', 'admin'])
            ->paginate($perPage)
            ->withQueryString();

        $stats = [
            'total'               => User::count(),
            'students'            => User::where('user_type', 'student')->count(),
            'universities'        => User::where('user_type', 'university')->count(),
            'admins'              => User::where('user_type', 'admin')->count(),
            'active'              => User::where('account_status', 'active')->count(),
            'suspended'           => User::where('account_status', 'suspended')->count(),
            'must_change_password'=> User::where('must_change_password', true)->count(),
            'recent_active'       => User::whereNotNull('last_login')->where('last_login', '>=', now()->subDays(7))->count(),
        ];

        // قائمة الجامعات للربط عند إنشاء أو تعديل حساب جامعي
        $universitiesList = University::orderBy('university_name')->get(['university_id', 'university_name', 'user_id']);

        return view('dashboards.admin.users', compact('users', 'stats', 'universitiesList'));
    }

    /**
     * تصدير بيانات المستخدمين إلى ملف CSV متوافق مع Excel بكافة الحقول والبيانات المتاحة
     */
    public function exportUsers(Request $request)
    {
        $query = User::query()->with(['student.regRequests.university', 'university', 'admin']);

        if ($request->filled('type')) {
            $query->where('user_type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('account_status', $request->status);
        }
        if ($request->filled('security') && $request->security === 'temp_pass') {
            $query->where('must_change_password', true);
        }
        if ($request->filled('search')) {
            $q = trim($request->search);
            $query->where(function ($sq) use ($q) {
                $sq->where('username', 'like', "%$q%")
                   ->orWhere('phone', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%");
            });
        }

        $users = $query->latest('user_id')->get();

        $filename = "users_export_" . date('Y_m_d_His') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->streamDownload(function () use ($users) {
            $file = fopen('php://output', 'w');
            // Write UTF-8 BOM for Arabic character rendering in MS Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'المعرف',
                'اسم المستخدم',
                'الاسم الكامل / جهة الانتساب',
                'نوع الحساب / الصلاحية',
                'البريد الإلكتروني',
                'حالة توثيق البريد',
                'رقم الهاتف للتواصل',
                'حالة الحساب',
                'طريقة تسجيل الدخول',
                'إلزام تغيير كلمة المرور',
                'الجهة / التبعية الإدارية',
                'تاريخ ووقت الانضمام',
                'آخر تسجيل دخول',
            ]);

            foreach ($users as $u) {
                $st = $u->student;
                $univ = $u->university;
                $adm = $u->admin;
                $latestReq = $st?->regRequests?->sortByDesc('req_id')->first();

                $displayName = match($u->user_type) {
                    'admin'      => $adm?->full_name ?: ($u->username === 'admin' ? 'مدير المنظومة العام' : 'مسؤول نظام'),
                    'university' => $univ?->university_name ?: 'حساب جامعة',
                    'student'    => $st?->full_name ?: ($latestReq?->full_name ?: $u->username),
                    default      => $u->username,
                };

                $roleName = match($u->user_type) {
                    'admin'      => 'مدير نظام',
                    'university' => 'مسؤول جامعة',
                    'student'    => 'طالب',
                    default      => $u->user_type,
                };

                $email = $u->email ?: ($univ?->email ?: ($latestReq?->email ?: '—'));
                $phone = $u->phone ?: ($univ?->contact_phone ?: ($univ?->phone ?: ($latestReq?->phone ?: '—')));

                $entity = match($u->user_type) {
                    'admin'      => 'إدارة المنظومة والرقابة المركزية',
                    'university' => $univ ? "جامعة {$univ->university_type} ({$univ->district})" : 'صرح جامعي',
                    'student'    => $latestReq ? ('متقدم إلى: ' . ($latestReq->university?->university_name ?? 'جامعة')) : ($st?->city ? "المدينة: {$st->city}" : ($st?->address ?: 'طالب مسجل')),
                    default      => '—',
                };

                $emailVerified = $u->email_verified_at ? 'موثق' : ($u->google_id ? 'موثق (Google)' : 'غير موثق');
                $authMethod = $u->google_id ? 'حساب قوقل Google' : 'بريد إلكتروني وكلمة مرور';
                $status = $u->account_status === 'active' ? 'نشط' : 'معلق';
                $tempPass = $u->must_change_password ? 'نعم (مؤقتة)' : 'لا';
                $joinedAt = $u->created_at ? $u->created_at->format('Y-m-d H:i') : '—';
                $lastLogin = $u->last_login ? \Carbon\Carbon::parse($u->last_login)->format('Y-m-d H:i') : 'لم يسجل دخول بعد';

                fputcsv($file, [
                    $u->user_id,
                    $u->username,
                    $displayName,
                    $roleName,
                    $email,
                    $emailVerified,
                    $phone,
                    $status,
                    $authMethod,
                    $tempPass,
                    $entity,
                    $joinedAt,
                    $lastLogin,
                ]);
            }
            fclose($file);
        }, $filename, $headers);
    }

    /**
     * الإجراءات الجماعية على المستخدمين
     */
    public function bulkUserAction(Request $request)
    {
        $validated = $request->validate([
            'user_ids'   => 'required|array|min:1',
            'user_ids.*' => 'integer|exists:users,user_id',
            'action'     => 'required|in:activate,suspend,delete',
        ]);

        $ids = array_values(array_diff($validated['user_ids'], [Auth::id()]));

        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن تطبيق الإجراءات الجماعية على حسابك الإداري الحالي المسجل به.'
            ], 422);
        }

        if ($validated['action'] === 'activate') {
            User::whereIn('user_id', $ids)->update(['account_status' => 'active']);
            $msg = 'تم تفعيل ' . count($ids) . ' حساب بنجاح.';
        } elseif ($validated['action'] === 'suspend') {
            User::whereIn('user_id', $ids)->update(['account_status' => 'suspended']);
            $msg = 'تم تعليق ' . count($ids) . ' حساب بنجاح.';
        } elseif ($validated['action'] === 'delete') {
            User::whereIn('user_id', $ids)->delete();
            $msg = 'تم حذف ' . count($ids) . ' حساب بنجاح.';
        }

        return response()->json([
            'success'      => true,
            'message'      => $msg,
            'affected_ids' => $ids,
        ]);
    }

    /**
     * إنشاء مستخدم جديد من لوحة الإدارة
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'username'              => 'required|string|min:4|max:50|unique:users,username',
            'password'              => 'required|string|min:6',
            'user_type'             => 'required|in:admin,university,student',
            'account_status'        => 'required|in:active,suspended',
            'email'                 => 'nullable|email|max:150|unique:users,email',
            'phone'                 => 'nullable|string|max:20|unique:users,phone',
            'full_name'             => 'nullable|string|max:150',
            'must_change_password'  => 'nullable|boolean',
            'existing_university_id'=> 'nullable|integer|exists:universities,university_id',
            'university_type'       => 'nullable|string|in:حكومية,خاصة,أهلية',
        ], [
            'username.required'     => 'اسم المستخدم إلزامي.',
            'username.unique'       => 'اسم المستخدم هذا مستخدم بالفعل.',
            'username.min'          => 'اسم المستخدم يجب ألا يقل عن 4 أحرف.',
            'password.required'     => 'كلمة المرور مطلوبة.',
            'password.min'          => 'كلمة المرور يجب ألا تقل عن 6 أحرف.',
            'email.unique'          => 'البريد الإلكتروني مسجل لحساب آخر.',
            'phone.unique'          => 'رقم الهاتف مسجل لحساب آخر.',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'username'              => $validated['username'],
                'password'              => Hash::make($validated['password']),
                'user_type'             => $validated['user_type'],
                'email'                 => $validated['email'] ?? null,
                'phone'                 => $validated['phone'] ?? null,
                'account_status'        => $validated['account_status'],
                'must_change_password'  => $request->boolean('must_change_password', false),
                'created_at'            => now(),
            ]);

            $fullName = trim($request->input('full_name', ''));

            if ($user->user_type === 'admin') {
                Admin::create([
                    'user_id'   => $user->user_id,
                    'full_name' => $fullName ?: $user->username,
                ]);
            } elseif ($user->user_type === 'student') {
                Student::create([
                    'user_id'   => $user->user_id,
                    'full_name' => $fullName ?: $user->username,
                ]);
            } elseif ($user->user_type === 'university') {
                if ($request->filled('existing_university_id')) {
                    $univ = University::find($request->existing_university_id);
                    if ($univ) {
                        $univ->update(['user_id' => $user->user_id]);
                    }
                } else {
                    University::create([
                        'user_id'         => $user->user_id,
                        'university_name' => $fullName ?: ('جامعة ' . $user->username),
                        'university_type' => $request->input('university_type', 'أهلية'),
                        'district'        => 'صنعاء',
                        'address'         => 'الجمهورية اليمنية',
                        'status'          => 'نشطة',
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء حساب المستخدم بنجاح.',
                'user'    => $user,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء المستخدم: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب تفاصيل مستخدم للعرض أو التعديل
     */
    public function showUser(Request $request, $id)
    {
        $user = User::with(['student.regRequests', 'university.colleges', 'admin', 'notifications'])
            ->findOrFail($id);

        $name = match($user->user_type) {
            'admin'      => $user->admin?->full_name ?: 'مسؤول نظام',
            'university' => $user->university?->university_name ?: 'حساب جامعة',
            'student'    => $user->student?->full_name ?: 'طالب مسجل',
            default      => $user->username,
        };

        $extraInfo = [];
        $permissions = [];

        if ($user->user_type === 'admin') {
            $extraInfo = [
                'type_label'          => 'مدير نظام وإشراف عام',
                'role_desc'           => 'كامل الصلاحيات الإدارية والرقابية على كافة أقسام المنصة',
            ];
            $permissions = [
                ['name' => 'إدارة المستخدمين والحسابات', 'icon' => 'fa-users-gear', 'desc' => 'إنشاء وتعديل وتعليق وحذف الحسابات وإعادة تعيين كلمات المرور'],
                ['name' => 'إدارة الجامعات والكليات والتخصصات', 'icon' => 'fa-building-columns', 'desc' => 'اعتماد وتعديل وتجميد بيانات الصروح الجامعية والأكاديمية'],
                ['name' => 'مركز الرقابة الإدارية والحوكمة', 'icon' => 'fa-shield-halved', 'desc' => 'مراقبة المحتوى غير الملائم وتطبيق الحظر والحل الإداري المستقل'],
                ['name' => 'مراجعة واعتماد الإعلانات الجامعية', 'icon' => 'fa-rectangle-ad', 'desc' => 'الموافقة على الحملات الترويجية والتحكم في ظهورها للطلاب'],
                ['name' => 'إدارة الإحالات والعمولات المالية', 'icon' => 'fa-handshake', 'desc' => 'تأكيد وصرف وتدقيق عمولات استقطاب الطلاب'],
                ['name' => 'التقارير والإحصائيات والتحليلات', 'icon' => 'fa-chart-line', 'desc' => 'استخراج مؤشرات الأداء والنمو والتسجيل في المنصة'],
                ['name' => 'الإعدادات العامة والأمنية للمنصة', 'icon' => 'fa-gears', 'desc' => 'التحكم بسياسات المنصة وتفضيلات النظام وتغيير كلمة المرور'],
            ];
        } elseif ($user->user_type === 'university') {
            $extraInfo = [
                'type_label'          => 'صرح جامعي معتمد',
                'university_id'       => $user->university?->university_id,
                'university_name'     => $user->university?->university_name ?: '—',
                'university_type'     => $user->university?->university_type ?: '—',
                'colleges_count'      => $user->university ? $user->university->colleges->count() : 0,
                'district'            => $user->university?->district ?: '—',
                'address'             => $user->university?->address ?: '—',
                'website'             => $user->university?->website ?: '—',
                'phone'               => $user->university?->phone ?: '—',
                'email'               => $user->university?->email ?: '—',
                'status'              => $user->university?->status ?: 'نشطة',
            ];
            $permissions = [
                ['name' => 'إدارة الملف التعريفي للجامعة', 'icon' => 'fa-id-card', 'desc' => 'تحديث الشعار، صور الحرم الجامعي، النبذة التعريفية، وموقع الجامعة الجغرافي'],
                ['name' => 'إدارة الكليات والأقسام والتخصصات', 'icon' => 'fa-graduation-cap', 'desc' => 'إضافة وتعديل التخصصات والرسوم الدراسية والشروط ومقررات الخطط الدراسية'],
                ['name' => 'استقبال ومعالجة طلبات التنسيق والقبول', 'icon' => 'fa-file-invoice', 'desc' => 'مراجعة بيانات الطلاب واعتماد أو رفض وثائق وإيصالات القبول'],
                ['name' => 'إطلاق الحملات الترويجية والإعلانية', 'icon' => 'fa-bullhorn', 'desc' => 'تقديم طلبات الإعلانات الترويجية لتظهر في الصفحة الرئيسية وبانرات المنصة'],
                ['name' => 'متابعة كود الإحالات الخاص بالجامعة', 'icon' => 'fa-award', 'desc' => 'تتبع الطلاب المسجلين عن طريق الجامعة ومتابعة الإحصائيات'],
            ];
        } elseif ($user->user_type === 'student') {
            $extraInfo = [
                'type_label'          => 'طالب مسجل بالمنصة',
                'requests_count'      => $user->student ? $user->student->regRequests->count() : 0,
                'city'                => $user->student?->city ?: '—',
                'address'             => $user->student?->address ?: '—',
                'gender'              => $user->student?->gender ?: '—',
                'birth_date'          => $user->student?->birth_date ? $user->student->birth_date->format('Y-m-d') : '—',
            ];
            $permissions = [
                ['name' => 'استعراض الجامعات والبرامج الأكاديمية', 'icon' => 'fa-compass', 'desc' => 'البحث والتصفية والمقارنة بين التخصصات والرسوم ومواقع الجامعات'],
                ['name' => 'التقديم على التنسيق والقبول الجامعي', 'icon' => 'fa-file-signature', 'desc' => 'رفع وثائق الثانوية وسداد الرسوم ومتابعة حالة الطلب خطوة بخطوة'],
                ['name' => 'استخدام حاسبة التنسيق والفرص المتاحة', 'icon' => 'fa-calculator', 'desc' => 'معرفة التخصصات المناسبة لمعدل الثانوية العامة علمي/أدبي'],
                ['name' => 'برنامج مكافآت الإحالة للطلاب', 'icon' => 'fa-gift', 'desc' => 'مشاركة كود الإحالة والحصول على مكافآت مالية عند تسجيل الزملاء بنجاح'],
            ];
        }

        return response()->json([
            'success' => true,
            'user' => [
                'user_id'              => $user->user_id,
                'username'             => $user->username,
                'email'                => $user->email ?: '—',
                'phone'                => $user->phone ?: '—',
                'user_type'            => $user->user_type,
                'account_status'       => $user->account_status,
                'must_change_password' => (bool)$user->must_change_password,
                'display_name'         => $name,
                'raw_full_name'        => $user->student?->full_name ?? $user->admin?->full_name ?? $user->university?->university_name ?? '',
                'university_id'        => $user->university?->university_id,
                'created_at_formatted' => $user->created_at ? $user->created_at->format('Y-m-d H:i') : '—',
                'created_at_human'     => $user->created_at ? $user->created_at->diffForHumans() : '—',
                'last_login_formatted' => $user->last_login ? \Carbon\Carbon::parse($user->last_login)->diffForHumans() : 'لم يسجل دخول بعد',
                'last_login_exact'     => $user->last_login ? \Carbon\Carbon::parse($user->last_login)->format('Y-m-d H:i') : null,
                'extra'                => $extraInfo,
                'permissions'          => $permissions,
            ]
        ]);
    }

    /**
     * تحديث بيانات مستخدم
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username'              => 'required|string|min:4|max:50|unique:users,username,' . $user->user_id . ',user_id',
            'user_type'             => 'required|in:admin,university,student',
            'account_status'        => 'required|in:active,suspended',
            'email'                 => 'nullable|email|max:150|unique:users,email,' . $user->user_id . ',user_id',
            'phone'                 => 'nullable|string|max:20|unique:users,phone,' . $user->user_id . ',user_id',
            'password'              => 'nullable|string|min:6',
            'full_name'             => 'nullable|string|max:150',
            'must_change_password'  => 'nullable|boolean',
            'existing_university_id'=> 'nullable|integer|exists:universities,university_id',
        ], [
            'username.required'     => 'اسم المستخدم إلزامي.',
            'username.unique'       => 'اسم المستخدم هذا مسجل لحساب آخر.',
            'username.min'          => 'اسم المستخدم يجب ألا يقل عن 4 أحرف.',
            'password.min'          => 'كلمة المرور يجب ألا تقل عن 6 أحرف.',
            'email.unique'          => 'البريد الإلكتروني مسجل لحساب آخر.',
            'phone.unique'          => 'رقم الهاتف مسجل لحساب آخر.',
        ]);

        // حماية المشرف الحالي من تخفيض رتبته أو تعليق حسابه
        if ($user->user_id === Auth::id()) {
            if ($validated['account_status'] === 'suspended') {
                return response()->json(['success' => false, 'message' => 'لا يمكنك تعليق حسابك الإداري الحالي المسجل به.'], 403);
            }
            if ($validated['user_type'] !== 'admin') {
                return response()->json(['success' => false, 'message' => 'لا يمكنك سحب رتبة مدير النظام عن حسابك الحالي.'], 403);
            }
        }

        DB::beginTransaction();
        try {
            $user->username = $validated['username'];
            $user->user_type = $validated['user_type'];
            $user->account_status = $validated['account_status'];
            $user->email = $validated['email'] ?? null;
            $user->phone = $validated['phone'] ?? null;
            $user->must_change_password = $request->boolean('must_change_password', false);

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            // تحديث الاسم في السجل المرتبط
            $fullName = trim($request->input('full_name', ''));
            if ($user->user_type === 'admin') {
                if ($user->admin) {
                    $user->admin->update(['full_name' => $fullName ?: $user->username]);
                } else {
                    Admin::create(['user_id' => $user->user_id, 'full_name' => $fullName ?: $user->username]);
                }
            } elseif ($user->user_type === 'student') {
                if ($user->student) {
                    $user->student->update(['full_name' => $fullName ?: $user->username]);
                } else {
                    Student::create(['user_id' => $user->user_id, 'full_name' => $fullName ?: $user->username]);
                }
            } elseif ($user->user_type === 'university') {
                if ($request->filled('existing_university_id')) {
                    $univ = University::find($request->existing_university_id);
                    if ($univ) {
                        $univ->update(['user_id' => $user->user_id]);
                    }
                } elseif ($user->university) {
                    if ($fullName) {
                        $user->university->update(['university_name' => $fullName]);
                    }
                } else {
                    University::create([
                        'user_id'         => $user->user_id,
                        'university_name' => $fullName ?: ('جامعة ' . $user->username),
                        'university_type' => 'أهلية',
                        'district'        => 'صنعاء',
                        'address'         => 'الجمهورية اليمنية',
                        'status'          => 'نشطة',
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث بيانات المستخدم والصلاحيات بنجاح.',
                'user'    => $user,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث بيانات المستخدم: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تفعيل / تعطيل حساب مستخدم
     */
    public function toggleUserStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->user_id === Auth::id()) {
            return response()->json(['success' => false, 'message' => 'لا يمكنك تعطيل حسابك الخاص.'], 403);
        }

        $newStatus = $user->account_status === 'active' ? 'suspended' : 'active';
        $user->update(['account_status' => $newStatus]);

        return response()->json([
            'success' => true,
            'new_status' => $newStatus,
            'message' => $newStatus === 'active' ? 'تم تفعيل الحساب بنجاح.' : 'تم تعليق الحساب بنجاح.',
        ]);
    }

    /**
     * إعادة تعيين كلمة مرور المستخدم
     */
    public function resetUserPassword(Request $request, $id)
    {
        $request->validate(['new_password' => 'required|string|min:6'], [
            'new_password.required' => 'كلمة المرور الجديدة مطلوبة.',
            'new_password.min'      => 'كلمة المرور يجب ألا تقل عن 6 أحرف.',
        ]);
        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($request->new_password),
            'must_change_password' => $request->boolean('must_change_password', false)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إعادة تعيين كلمة المرور بنجاح.',
        ]);
    }

    /**
     * حذف مستخدم
     */
    public function deleteUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->user_id === Auth::id()) {
            return response()->json(['success' => false, 'message' => 'لا يمكنك حذف حسابك الخاص.'], 403);
        }
        $user->delete();
        return response()->json(['success' => true, 'message' => 'تم حذف الحساب بنجاح.']);
    }

    /**
     * إدارة الجامعات
     */
    public function universities(Request $request)
    {
        $query = University::with('user')->withCount(['colleges', 'specializations', 'regRequests']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('university_type', $request->type);
        }
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('university_name', 'like', "%$q%")
                   ->orWhere('district', 'like', "%$q%");
            });
        }

        $universities = $query->latest('university_id')->paginate(15)->withQueryString();

        $stats = [
            'total' => University::count(),
            'active' => University::where('status', 'نشطة')->count(),
            'pending' => University::where('status', 'معلقة')->count(),
            'banned' => University::where('status', 'محظورة')->count(),
        ];

        $quarantinedCount = ContentModeration::activeQuarantine()->count();

        return view('dashboards.admin.universities', compact('universities', 'stats', 'quarantinedCount'));
    }

    /**
     * إضافة جامعة جديدة مع إنشاء حساب دخول خاص بها
     */
    public function storeUniversity(Request $request)
    {
        $request->validate([
            'university_name'  => 'required|string|max:100|unique:universities,university_name',
            'university_type'  => 'required|in:حكومية,خاصة,أهلية',
            'district'         => 'required|string|max:100',
            'address'          => 'required|string|max:255',
            'description'      => 'nullable|string|max:2000',
            'phone'            => 'nullable|string|max:20',
            'contact_phone'    => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:150',
            'website'          => 'nullable|url|max:255',
            'latitude'         => 'nullable|numeric|between:-90,90',
            'longitude'        => 'nullable|numeric|between:-180,180',
            'year_establ'      => 'nullable|integer|between:1900,2030',
            'username'         => 'required|string|min:4|max:50|unique:users,username',
            'password'         => 'required|string|min:6',
            'logo'             => 'nullable|image|mimes:jpg,jpeg,png,svg|max:3072',
            // الكليات
            'colleges'         => 'nullable|array',
            'colleges.*.name'  => 'required_with:colleges|string|max:200',
        ], [
            'university_name.required' => 'يرجى إدخال اسم الجامعة.',
            'university_name.unique'   => 'هذا الاسم مسجل لجامعة أخرى بالفعل.',
            'address.required'         => 'يرجى إدخال العنوان والموقع التفصيلي للجامعة.',
            'username.unique'          => 'اسم المستخدم هذا مستخدم بالفعل، يرجى اختيار اسم آخر.',
            'username.min'             => 'يجب ألا يقل اسم المستخدم عن 4 أحرف.',
            'password.min'             => 'يجب ألا تقل كلمة المرور عن 6 أحرف.',
        ]);

        DB::beginTransaction();
        try {
            // إنشاء حساب المستخدم للجامعة مع تفعيل إلزامية تغيير كلمة المرور عند أول دخول
            $user = User::create([
                'username'             => $request->username,
                'password'             => Hash::make($request->password),
                'user_type'            => 'university',
                'phone'                => $request->phone ?? $request->contact_phone,
                'email'                => $request->email,
                'account_status'       => 'active',
                'must_change_password' => true,
            ]);

            // رفع الشعار إن وجد
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('logos', 'public');
            }

            // إنشاء سجل الجامعة مع كافة الحقول المتوافقة مع قاعدة البيانات
            $university = University::create([
                'user_id'         => $user->user_id,
                'university_name' => $request->university_name,
                'university_type' => $request->university_type,
                'district'        => $request->district,
                'description'     => $request->description,
                'address'         => $request->address,
                'contact_phone'   => $request->contact_phone ?? $request->phone,
                'latitude'        => $request->filled('latitude') ? (float)$request->latitude : null,
                'longitude'       => $request->filled('longitude') ? (float)$request->longitude : null,
                'phone'           => $request->phone,
                'email'           => $request->email,
                'website'         => $request->website,
                'year_establ'     => $request->year_establ,
                'logo'            => $logoPath,
                'status'          => 'نشطة',
            ]);

            // إضافة الكليات إن وجدت
            if ($request->filled('colleges')) {
                foreach ($request->colleges as $collegeData) {
                    if (!empty($collegeData['name'])) {
                        $college = College::create([
                            'university_id' => $university->university_id,
                            'college_name'  => $collegeData['name'],
                            'status'        => 'نشطة',
                        ]);
                        // إضافة التخصصات إن وجدت
                        if (!empty($collegeData['specializations'])) {
                            foreach ($collegeData['specializations'] as $specName) {
                                if (!empty($specName)) {
                                    Specialization::create([
                                        'college_id'          => $college->college_id,
                                        'specialization_name' => $specName,
                                        'status'              => 'نشط',
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();

            // إرسال بريد الاعتماد والترحيب الرسمي إلى الجامعة إن وُجد البريد
            $emailSent = false;
            $emailError = null;
            if ($request->filled('email')) {
                try {
                    \Illuminate\Support\Facades\Mail::to($request->email)->send(
                        new \App\Mail\UniversityWelcomeMail(
                            $university,
                            $user->username,
                            $request->password,
                            url('/login')
                        )
                    );
                    $emailSent = true;
                } catch (\Throwable $mailEx) {
                    \Illuminate\Support\Facades\Log::warning("Failed to send welcome email to {$university->university_name}: " . $mailEx->getMessage());
                    $emailError = $mailEx->getMessage();
                }
            }

            return response()->json([
                'success'         => true,
                'message'         => "تمت إضافة الجامعة «{$university->university_name}» وإنشاء حساب الدخول بنجاح.",
                'university_id'   => $university->university_id,
                'university_name' => $university->university_name,
                'username'        => $user->username,
                'raw_password'    => $request->password,
                'phone'           => $university->contact_phone ?? $university->phone,
                'email'           => $university->email,
                'login_url'       => url('/login'),
                'email_sent'      => $emailSent,
                'email_error'     => $emailError,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة الجامعة: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * عرض تفاصيل جامعة
     */
    public function showUniversity($id)
    {
        $university = University::with([
            'user',
            'colleges.specializations.activeModeration',
            'regRequests',
            'advertisements',
        ])->withCount(['colleges', 'specializations', 'regRequests'])->findOrFail($id);

        $recentRequests = RegRequest::with('student')
            ->where('university_id', $id)
            ->where('status', '!=', 'مسودة')
            ->latest('req_id')->take(10)->get();

        return response()->json([
            'success'    => true,
            'university' => [
                'id'              => $university->university_id,
                'name'            => $university->university_name,
                'type'            => $university->university_type,
                'status'          => $university->status,
                'district'        => $university->district,
                'description'     => $university->description,
                'address'         => $university->address,
                'latitude'        => $university->latitude,
                'longitude'       => $university->longitude,
                'phone'           => $university->phone,
                'contact_phone'   => $university->contact_phone,
                'email'           => $university->email,
                'website'         => $university->website,
                'year_establ'     => $university->year_establ,
                'logo_url'        => $university->logo_url,
                'colleges_count'  => $university->colleges_count,
                'specs_count'     => $university->specializations_count,
                'requests_count'  => $university->reg_requests_count,
                'colleges'        => $university->colleges->map(fn($c) => [
                    'id'              => $c->college_id,
                    'name'            => $c->college_name,
                    'status'          => $c->status,
                    'specializations' => $c->specializations->map(fn($s) => [
                        'id'                  => $s->specialization_id,
                        'name'                => $s->specialization_name,
                        'is_quarantined'      => $s->activeModeration !== null,
                        'moderation_id'       => $s->activeModeration?->moderation_id,
                        'moderation_status'   => $s->activeModeration?->status,
                        'violation_reason'    => $s->activeModeration?->violation_reason,
                        'admin_guidance'      => $s->activeModeration?->admin_guidance,
                        'correction_notes'    => $s->activeModeration?->correction_notes,
                        'suspended_at'        => $s->activeModeration?->suspended_at?->format('Y/m/d H:i'),
                    ]),
                ]),
                'recent_requests' => $recentRequests->map(fn($r) => [
                    'req_id'      => $r->req_id,
                    'student'     => $r->full_name,
                    'spec'        => $r->desired_spec,
                    'status'      => $r->status,
                    'date'        => $r->req_date?->format('Y-m-d'),
                ]),
                'ads_count'  => $university->advertisements->count(),
                'active_ads' => $university->advertisements->where('status', 'نشط')->count(),
            ],
        ]);
    }

    /**
     * الصفحة المنفصلة لمركز الرقابة وحوكمة المحتوى الجامعي
     */
    public function moderationIndex(Request $request)
    {
        $universities = University::orderBy('university_name')->get([
            'university_id', 'university_name', 'university_type', 'status', 'logo', 'campus_image', 'description'
        ]);

        // لا يجب أن تكون هناك جامعة محددة تلقائياً عند فتح الصفحة
        $selectedUnivId = (int)$request->get('university_id', 0);

        $activeModerations = ContentModeration::with(['university', 'suspendedByUser'])
            ->activeQuarantine()
            ->latest('moderation_id')
            ->get();

        // التعديلات والمراجعات الواردة من الجامعات للعناصر المحجوبة
        $pendingReviews = ContentModeration::with(['university', 'suspendedByUser'])
            ->where(function ($q) {
                $q->where('status', 'قيد_المراجعة')
                  ->orWhere(function ($sub) {
                      $sub->activeQuarantine()->whereNotNull('correction_notes');
                  });
            })
            ->latest('updated_at')
            ->get();

        $pendingReviewsCount = $pendingReviews->count();

        $historyModerations = ContentModeration::with(['university', 'suspendedByUser', 'resolvedByUser'])
            ->where('status', 'تم_الحل')
            ->latest('resolved_at')
            ->take(50)
            ->get();

        $stats = [
            'total_active' => $activeModerations->count(),
            'media'        => $activeModerations->whereIn('item_type', ['logo', 'campus_image'])->count(),
            'profile'      => $activeModerations->whereIn('item_type', ['description', 'contact_info'])->count(),
            'academic'     => $activeModerations->whereIn('item_type', ['specialization', 'college'])->count(),
            'resolved'     => ContentModeration::where('status', 'تم_الحل')->count(),
            'pending_reviews' => $pendingReviewsCount,
        ];

        return view('dashboards.admin.moderation', compact(
            'universities',
            'selectedUnivId',
            'activeModerations',
            'pendingReviews',
            'pendingReviewsCount',
            'historyModerations',
            'stats'
        ));
    }

    /**
     * جلب كافة بيانات وأصول الجامعة لأداة التدقيق الرقابي الفوري
     */
    public function getUniversityAuditData($id)
    {
        $u = University::with([
            'colleges.specializations.activeModeration',
            'colleges.activeModeration',
            'activeModerations'
        ])->findOrFail($id);

        $logoMod = $u->getActiveModeration('logo');
        $campusMod = $u->getActiveModeration('campus_image');
        $descMod = $u->getActiveModeration('description');
        $contactMod = $u->getActiveModeration('contact_info');

        return response()->json([
            'success' => true,
            'university' => [
                'id'                   => $u->university_id,
                'name'                 => $u->university_name,
                'type'                 => $u->university_type,
                'status'               => $u->status,
                'year_establ'          => $u->year_establ,
                
                'logo_url'             => $u->logo_url,
                'raw_logo'             => $u->logo,
                'logo_quarantined'     => $logoMod !== null,
                'logo_mod'             => $logoMod ? [
                    'moderation_id'    => $logoMod->moderation_id,
                    'violation_reason' => $logoMod->violation_reason,
                    'admin_guidance'   => $logoMod->admin_guidance,
                    'suspended_at'     => $logoMod->suspended_at?->format('Y/m/d H:i'),
                ] : null,

                'campus_image_url'     => $u->campus_image_url,
                'raw_campus_image'     => $u->campus_image,
                'campus_quarantined'   => $campusMod !== null,
                'campus_mod'           => $campusMod ? [
                    'moderation_id'    => $campusMod->moderation_id,
                    'violation_reason' => $campusMod->violation_reason,
                    'admin_guidance'   => $campusMod->admin_guidance,
                    'suspended_at'     => $campusMod->suspended_at?->format('Y/m/d H:i'),
                ] : null,

                'description'          => $u->description,
                'safe_description'     => $u->safe_description,
                'desc_quarantined'     => $descMod !== null,
                'desc_mod'             => $descMod ? [
                    'moderation_id'    => $descMod->moderation_id,
                    'violation_reason' => $descMod->violation_reason,
                    'admin_guidance'   => $descMod->admin_guidance,
                    'suspended_at'     => $descMod->suspended_at?->format('Y/m/d H:i'),
                ] : null,

                'phone'                => $u->phone ?? $u->contact_phone,
                'email'                => $u->email,
                'website'              => $u->website,
                'district'             => $u->district,
                'address'              => $u->address,
                'contact_quarantined'  => $contactMod !== null,
                'contact_mod'          => $contactMod ? [
                    'moderation_id'    => $contactMod->moderation_id,
                    'violation_reason' => $contactMod->violation_reason,
                    'admin_guidance'   => $contactMod->admin_guidance,
                    'suspended_at'     => $contactMod->suspended_at?->format('Y/m/d H:i'),
                ] : null,

                'colleges' => $u->colleges->map(fn($c) => [
                    'id'                   => $c->college_id,
                    'name'                 => $c->college_name,
                    'status'               => $c->status,
                    'is_quarantined'       => $c->activeModeration !== null,
                    'moderation'           => $c->activeModeration ? [
                        'moderation_id'    => $c->activeModeration->moderation_id,
                        'violation_reason' => $c->activeModeration->violation_reason,
                        'admin_guidance'   => $c->activeModeration->admin_guidance,
                        'suspended_at'     => $c->activeModeration->suspended_at?->format('Y/m/d H:i'),
                    ] : null,
                    'specializations_count'=> $c->specializations->count(),
                    'specializations'      => $c->specializations->map(fn($s) => [
                        'id'               => $s->specialization_id,
                        'name'             => $s->specialization_name,
                        'tuition_fees'     => (float)$s->tuition_fees,
                        'status'           => $s->status,
                        'is_quarantined'   => $s->activeModeration !== null,
                        'moderation'       => $s->activeModeration ? [
                            'moderation_id'    => $s->activeModeration->moderation_id,
                            'violation_reason' => $s->activeModeration->violation_reason,
                            'admin_guidance'   => $s->activeModeration->admin_guidance,
                            'suspended_at'     => $s->activeModeration->suspended_at?->format('Y/m/d H:i'),
                        ] : null,
                    ]),
                ]),
            ]
        ]);
    }

    /**
     * إيقاف ظهور عنصر رقابياً (حجر رقابي فوري ومستقل)
     */
    public function quarantineItem(Request $request)
    {
        $request->validate([
            'university_id'    => 'required|integer|exists:universities,university_id',
            'item_type'        => 'required|string|in:specialization,college,announcement,logo,campus_image,description,contact_info',
            'item_id'          => 'required|integer',
            'item_title'       => 'required|string|max:200',
            'violation_reason' => 'required|string|max:1000',
            'admin_guidance'   => 'nullable|string|max:1000',
        ]);

        $moderation = ContentModeration::updateOrCreate(
            [
                'university_id' => $request->university_id,
                'item_type'     => $request->item_type,
                'item_id'       => $request->item_id,
                'status'        => 'محجوب',
            ],
            [
                'item_title'       => $request->item_title,
                'violation_reason' => $request->violation_reason,
                'admin_guidance'   => $request->admin_guidance,
                'suspended_by'     => Auth::id(),
                'suspended_at'     => now(),
            ]
        );

        // إرسال إشعار ورسالة رسمية فورية لحساب الجامعة المستهدفة
        $university = University::find($request->university_id);
        if ($university && $university->user_id) {
            $itemLabel = match($request->item_type) {
                'logo'           => 'شعار الجامعة الرسمي',
                'campus_image'   => 'صورة الحرم الجامعي',
                'description'    => 'النبذة والوصف التعريفي',
                'contact_info'   => 'بيانات الاتصال والتواصل',
                'college'        => 'كلية أكاديمية',
                'specialization' => 'تخصص أكاديمي',
                default          => $request->item_title
            };

            \App\Models\Notification::create([
                'user_id'           => $university->user_id,
                'notification_type' => 'moderation_quarantine',
                'title'             => 'إيقاف ظهور (' . $request->item_title . ') في المنصة',
                'message'           => "أصدرت إدارة الرقابة والحوكمة المركزية بالمنظومة قراراً إدارياً بإيقاف ظهور ({$request->item_title} - {$itemLabel}) للطلاب والزوار مؤقتاً.\n\nسبب المخالفة الرقابية:\n{$request->violation_reason}\n" . ($request->admin_guidance ? "\nالتوجيهات الإدارية لتصحيح الوضع:\n{$request->admin_guidance}\n" : '') . "\nيرجى مراجعة وتصحيح البيانات في أسرع وقت لتمكين إدارة الرقابة من فك الحجب وإعادة تفعيل الظهور للطلاب.",
                'is_read'           => false,
                'created_at'        => now(),
            ]);
        }

        return response()->json([
            'success'    => true,
            'message'    => 'تم حجب العنصر بنجاح وإيقاف ظهوره للطلاب فوراً دون المساس ببقية بيانات الجامعة.',
            'moderation' => $moderation,
        ]);
    }

    /**
     * فك الحجب الرقابي وإعادة العنصر للظهور في المنصة
     */
    public function resolveQuarantine(Request $request, $id)
    {
        $moderation = ContentModeration::with('university')->findOrFail($id);
        $moderation->update([
            'status'       => 'تم_الحل',
            'resolved_by'  => Auth::id(),
            'resolved_at'  => now(),
        ]);

        // إرسال إشعار باعتماد العنصر وفك الحجب لحساب الجامعة
        $university = $moderation->university;
        if ($university && $university->user_id) {
            \App\Models\Notification::create([
                'user_id'           => $university->user_id,
                'notification_type' => 'moderation_resolved',
                'title'             => 'فك الحجب عن (' . $moderation->item_title . ')',
                'message'           => "يسر إدارة الرقابة والحوكمة إبلاغكم بأنه تم التحقق وفك الحجب الرقابي بنجاح عن ({$moderation->item_title})، وإعادة تفعيل ظهوره للطلاب والزوار في المنصة وفق المعايير المعتمدة.",
                'is_read'           => false,
                'created_at'        => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم فك الحجب بنجاح وإعادة تفعيل ظهور العنصر للطلاب في المنصة.',
        ]);
    }

    /**
     * جلب قائمة كافة العناصر المحجوبة رقابياً عبر المنصة
     */
    public function getQuarantinedItems(Request $request)
    {
        $items = ContentModeration::with(['university', 'suspendedByUser'])
            ->activeQuarantine()
            ->latest('moderation_id')
            ->get()
            ->map(fn($m) => [
                'moderation_id'    => $m->moderation_id,
                'university_id'    => $m->university_id,
                'university_name'  => $m->university?->university_name ?? '—',
                'item_type'        => $m->item_type,
                'item_id'          => $m->item_id,
                'item_title'       => $m->item_title,
                'status'           => $m->status,
                'violation_reason' => $m->violation_reason,
                'admin_guidance'   => $m->admin_guidance,
                'correction_notes' => $m->correction_notes,
                'suspended_by'     => $m->suspendedByUser?->username ?? 'مدير النظام',
                'suspended_at'     => $m->suspended_at?->format('Y/m/d H:i'),
            ]);

        return response()->json([
            'success' => true,
            'items'   => $items,
            'count'   => $items->count(),
        ]);
    }

    /**
     * تحديث بيانات الجامعة
     */
    public function updateUniversity(Request $request, $id)
    {
        $university = University::findOrFail($id);

        $request->validate([
            'university_name' => "required|string|max:100|unique:universities,university_name,{$id},university_id",
            'university_type' => 'required|in:حكومية,خاصة,أهلية',
            'district'        => 'required|string|max:100',
            'address'         => 'required|string|max:255',
            'description'     => 'nullable|string|max:2000',
            'phone'           => 'nullable|string|max:20',
            'contact_phone'   => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:150',
            'website'         => 'nullable|url|max:255',
            'latitude'        => 'nullable|numeric|between:-90,90',
            'longitude'       => 'nullable|numeric|between:-180,180',
            'year_establ'     => 'nullable|integer|between:1900,2030',
            'logo'            => 'nullable|image|mimes:jpg,jpeg,png,svg|max:3072',
        ]);

        $data = $request->only([
            'university_name', 'university_type', 'district', 'description',
            'address', 'phone', 'contact_phone', 'email', 'website', 'year_establ',
        ]);

        if ($request->filled('latitude')) $data['latitude'] = (float)$request->latitude;
        if ($request->filled('longitude')) $data['longitude'] = (float)$request->longitude;

        if ($request->hasFile('logo')) {
            if ($university->logo) Storage::disk('public')->delete($university->logo);
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $university->update($data);

        return response()->json(['success' => true, 'message' => 'تم تحديث بيانات الجامعة بنجاح.']);
    }

    /**
     * تفعيل / تعطيل جامعة
     */
    public function toggleUniversityStatus(Request $request, $id)
    {
        $university = University::with('user')->findOrFail($id);
        $statuses   = ['نشطة', 'معلقة', 'محظورة'];
        $newStatus  = $request->input('status');

        if (!in_array($newStatus, $statuses)) {
            $newStatus = $university->status === 'نشطة' ? 'معلقة' : 'نشطة';
        }

        $university->update(['status' => $newStatus]);

        // تحديث حالة الحساب المرتبط بالجامعة
        if ($university->user) {
            $accountStatus = $newStatus === 'نشطة' ? 'active' : 'suspended';
            $university->user->update(['account_status' => $accountStatus]);
        }

        return response()->json([
            'success'    => true,
            'new_status' => $newStatus,
            'message'    => "تم تغيير حالة الجامعة إلى «{$newStatus}» بنجاح.",
        ]);
    }

    /**
     * حذف جامعة
     */
    public function deleteUniversity(Request $request, $id)
    {
        $university = University::with('user')->findOrFail($id);
        DB::beginTransaction();
        try {
            if ($university->logo) Storage::disk('public')->delete($university->logo);
            $userId = $university->user_id;
            $university->delete();
            if ($userId) User::find($userId)?->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'تم حذف الجامعة وحساب الدخول الخاص بها بنجاح.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'فشل الحذف: ' . $e->getMessage()], 500);
        }
    }

    /**
     * إدارة الإعلانات الجامعية
     */
    public function advertisements(Request $request)
    {
        $query = Advertisement::with('university');

        if ($request->filled('review_status')) {
            $query->where('review_status', $request->review_status);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('package')) {
            $query->where('package', $request->package);
        }
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->whereHas('university', fn($uq) => $uq->where('university_name', 'like', "%$q%"))
                   ->orWhere('adv_title', 'like', "%$q%");
            });
        }

        $advertisements = $query->latest('adv_id')->paginate(12)->withQueryString();

        $stats = [
            'total'    => Advertisement::count(),
            'pending'  => Advertisement::where('review_status', 'قيد المراجعة')->count(),
            'approved' => Advertisement::where('review_status', 'معتمد')->count(),
            'rejected' => Advertisement::where('review_status', 'مرفوض')->count(),
            'active'   => Advertisement::where('status', 'نشط')->count(),
        ];

        // جلب الإعلانات المعلقة التي تحتاج مراجعة للبطاقة العلوية الخاصة
        $pendingAds = Advertisement::with('university')
            ->where('review_status', 'قيد المراجعة')
            ->latest('adv_id')
            ->get();

        return view('dashboards.admin.advertisements', compact('advertisements', 'stats', 'pendingAds'));
    }

    /**
     * عرض تفاصيل إعلان
     */
    public function showAdvertisement($id)
    {
        $ad = Advertisement::with('university')->findOrFail($id);
        
        $expiresCarbon = $ad->expires_at ? \Carbon\Carbon::parse($ad->expires_at) : null;
        $publishedCarbon = $ad->published_at ? \Carbon\Carbon::parse($ad->published_at) : null;

        return response()->json([
            'success' => true,
            'ad' => [
                'id'              => $ad->adv_id,
                'title'           => $ad->adv_title,
                'content'         => $ad->adv_content,
                'package'         => $ad->package,
                'package_label'   => $ad->getPackageLabel(),
                'duration'        => $ad->duration,
                'amount'          => $ad->amount,
                'pay_method'      => $ad->pay_method,
                'pay_reference'   => $ad->pay_reference,
                'review_status'   => $ad->review_status,
                'status'          => $ad->status,
                'notes'           => $ad->notes,
                'created_at'      => $ad->created_at?->format('Y-m-d H:i'),
                'created_at_time' => $ad->created_at ? $ad->created_at->format('Y/m/d - h:i A') : null,
                'university_name' => $ad->university?->university_name ?? 'الجامعة',
                'university_id'   => $ad->university_id,
                'university_logo' => $ad->university?->logo_url ? asset($ad->university->logo_url) : asset('logo.png'),
                'receipt_url'     => $ad->receipt_url ? (str_starts_with($ad->receipt_url, 'http') ? $ad->receipt_url : asset(ltrim($ad->receipt_url, '/'))) : null,
                'media_type'      => $ad->media_type,
                'media_files'     => array_map(function($f) {
                    if (empty($f)) return null;
                    if (str_starts_with($f, 'http://') || str_starts_with($f, 'https://')) return $f;
                    return asset(ltrim($f, '/'));
                }, array_values(array_filter($ad->mediaFilesArray()))),
                'first_media'     => $ad->firstMediaUrl(),
                'video_url'       => $ad->formattedVideoUrl(),
                'published_at'    => $publishedCarbon ? $publishedCarbon->format('Y-m-d H:i') : null,
                'published_at_time' => $publishedCarbon ? $publishedCarbon->format('Y/m/d - h:i A') : null,
                'expires_at'      => $expiresCarbon ? $expiresCarbon->format('Y-m-d H:i') : null,
                'expires_at_time' => $expiresCarbon ? $expiresCarbon->format('Y/m/d - h:i A') : null,
                'expires_remaining' => $expiresCarbon ? ($expiresCarbon->isPast() ? 'منتهي منذ ' . $expiresCarbon->diffForHumans() : 'متبقي ' . $expiresCarbon->diffForHumans(null, true)) : null,
                'is_expired'      => $expiresCarbon ? $expiresCarbon->isPast() : false,
            ],
        ]);
    }

    /**
     * اعتماد إعلان
     */
    public function approveAdvertisement(Request $request, $id)
    {
        $ad = Advertisement::findOrFail($id);
        $ad->update([
            'review_status' => 'معتمد',
            'status'        => 'نشط',
            'published_at'  => now(),
            'expires_at'    => now()->addDays($ad->duration ?? 2),
            'notes'         => $request->input('notes', $ad->notes),
        ]);

        $university = $ad->university;
        if ($university && $university->user_id) {
            try {
                \App\Models\Notification::create([
                    'user_id'           => $university->user_id,
                    'notification_type' => 'ad_approved',
                    'title'             => 'تم اعتماد إعلانكم: ' . $ad->adv_title,
                    'message'           => 'تمت مراجعة الإعلان والموافقة عليه ونشره رسمياً في المنصة بنجاح على الباقة (' . $ad->package . ').',
                    'is_read'           => false,
                    'created_at'        => now(),
                ]);
            } catch (\Throwable $e) {}
        }

        return response()->json(['success' => true, 'message' => 'تم اعتماد الإعلان ونشره بنجاح.']);
    }

    /**
     * طلب تعديل إعلان (إعادته للجامعة)
     */
    public function requestAdvertisementEdit(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:1000'], [
            'reason.required' => 'يرجى كتابة سبب طلب التعديل.',
        ]);
        $ad = Advertisement::findOrFail($id);
        $ad->update([
            'review_status' => 'مطلوب تعديل',
            'status'        => 'غير نشط',
            'notes'         => $request->reason,
        ]);

        $university = $ad->university;
        if ($university && $university->user_id) {
            try {
                \App\Models\Notification::create([
                    'user_id'           => $university->user_id,
                    'notification_type' => 'ad_edit_requested',
                    'title'             => 'طلب تعديل إعلان: ' . $ad->adv_title,
                    'message'           => 'أعادت إدارة المنصة الإعلان لإجراء التعديل التالي: ' . $request->reason,
                    'is_read'           => false,
                    'created_at'        => now(),
                ]);
            } catch (\Throwable $e) {}
        }

        return response()->json(['success' => true, 'message' => 'تم إرسال طلب التعديل للجامعة بنجاح.']);
    }

    /**
     * رفض إعلان
     */
    public function rejectAdvertisement(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:500']);
        $ad = Advertisement::findOrFail($id);
        $ad->update([
            'review_status' => 'مرفوض',
            'status'        => 'متوقف',
            'notes'         => $request->reason,
        ]);

        $university = $ad->university;
        if ($university && $university->user_id) {
            try {
                \App\Models\Notification::create([
                    'user_id'           => $university->user_id,
                    'notification_type' => 'ad_rejected',
                    'title'             => 'تم رفض الإعلان: ' . $ad->adv_title,
                    'message'           => 'تعذر قبول الإعلان بسبب: ' . $request->reason,
                    'is_read'           => false,
                    'created_at'        => now(),
                ]);
            } catch (\Throwable $e) {}
        }

        return response()->json(['success' => true, 'message' => 'تم رفض الإعلان مع إشعار الجهة المقدِّمة.']);
    }

    /**
     * حذف إعلان
     */
    public function deleteAdvertisement(Request $request, $id)
    {
        $ad = Advertisement::findOrFail($id);
        $ad->delete();
        return response()->json(['success' => true, 'message' => 'تم حذف الإعلان بنجاح.']);
    }

    /**
     * إيقاف مؤقت أو استئناف عرض الإعلان المعتمد
     */
    public function togglePauseAdvertisement($id)
    {
        $ad = Advertisement::findOrFail($id);

        if ($ad->review_status !== 'معتمد') {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن إيقاف أو استئناف إعلان غير معتمد.',
            ], 422);
        }

        if ($ad->status === 'متوقف مؤقتا') {
            if ($ad->expires_at && \Carbon\Carbon::parse($ad->expires_at)->isPast()) {
                $ad->status = 'منتهي';
                $ad->save();
                return response()->json([
                    'success' => false,
                    'message' => 'انتهت فترة صلاحية هذا الإعلان بالفعل ولا يمكن استئنافه.',
                ], 422);
            }
            $ad->status = 'نشط';
            $msg = 'تم استئناف عرض الإعلان بنجاح، ويظهر الآن للطلاب على المنصة.';
            $newStatus = 'نشط';
        } else {
            $ad->status = 'متوقف مؤقتا';
            $msg = 'تم إيقاف عرض الإعلان مؤقتاً، ولن يظهر للطلاب حتى يتم استئنافه.';
            $newStatus = 'متوقف مؤقتا';
        }

        $ad->save();

        return response()->json([
            'success' => true,
            'message' => $msg,
            'new_status' => $newStatus,
            'status' => $ad->status,
        ]);
    }

    /**
     * إدارة الإحالات والعمولات
     */
    public function referrals(Request $request)
    {
        $query = StudentReferral::with(['student.user', 'university', 'proof']);

        if ($request->filled('status')) {
            $query->where('ref_status', $request->status);
        }
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('ref_code', 'like', "%$q%")
                   ->orWhereHas('student', fn($st) => $st->where('full_name', 'like', "%$q%"))
                   ->orWhereHas('university', fn($u) => $u->where('university_name', 'like', "%$q%"));
            });
        }

        $referrals = $query->latest('referral_id')->paginate(20)->withQueryString();

        $stats = [
            'total'      => StudentReferral::count(),
            'active'     => StudentReferral::where('ref_status', 'نشط')->count(),
            'used'       => StudentReferral::where('ref_status', 'مستخدم')->count(),
            'completed'  => StudentReferral::where('ref_status', 'مكتملة')->count(),
            'pending_commission' => StudentReferral::where('ref_status', 'مكتملة')->whereNull('commission_paid_at')->count(),
        ];

        return view('dashboards.admin.referrals', compact('referrals', 'stats'));
    }

    /**
     * تأكيد صرف العمولة
     */
    public function confirmCommission(Request $request, $id)
    {
        $referral = StudentReferral::findOrFail($id);
        $referral->update([
            'commission_paid_at' => now(),
            'ref_status'         => 'مكتملة',
        ]);
        return response()->json(['success' => true, 'message' => 'تم تأكيد صرف العمولة بنجاح.']);
    }

    /**
     * رفض طلب العمولة
     */
    public function rejectCommission(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:500']);
        $referral = StudentReferral::findOrFail($id);
        $referral->update([
            'notes'      => $request->reason,
            'ref_status' => 'مرفوضة',
        ]);
        return response()->json(['success' => true, 'message' => 'تم رفض طلب العمولة مع تسجيل السبب.']);
    }

    /**
     * إدارة العمولات المالية المستحقة وصرف المكافآت (شاشة مستقلة مفصولة عن الإحالات)
     */
    public function commissions(Request $request)
    {
        $query = StudentReferral::with(['student.user', 'university', 'proof'])
            ->whereIn('ref_status', ['مكتملة', 'مستخدم', 'مرفوضة']);

        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where('ref_status', 'مكتملة')->whereNull('commission_paid_at');
            } elseif ($request->status === 'paid') {
                $query->whereNotNull('commission_paid_at');
            } elseif ($request->status === 'rejected') {
                $query->where('ref_status', 'مرفوضة');
            }
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('ref_code', 'like', "%$q%")
                   ->orWhereHas('student', fn($st) => $st->where('full_name', 'like', "%$q%"))
                   ->orWhereHas('university', fn($u) => $u->where('university_name', 'like', "%$q%"));
            });
        }

        $commissions = $query->latest('referral_id')->paginate(20)->withQueryString();

        $stats = [
            'total_completed'    => StudentReferral::where('ref_status', 'مكتملة')->count(),
            'pending_commission' => StudentReferral::where('ref_status', 'مكتملة')->whereNull('commission_paid_at')->count(),
            'paid_commission'    => StudentReferral::whereNotNull('commission_paid_at')->count(),
            'rejected'           => StudentReferral::where('ref_status', 'مرفوضة')->count(),
        ];

        return view('dashboards.admin.commissions', compact('commissions', 'stats'));
    }

    /**
     * متابعة طلبات الالتحاق بالجامعات (إشراف ورقابة إدارية دون المساس بخصوصية الطلبة أو قرارات الجامعات)
     */
    public function requests(Request $request)
    {
        $query = RegRequest::with(['university', 'payments'])
            ->where('status', '!=', 'مسودة');

        // 1. فلترة بحسب الجامعة
        if ($request->filled('university_id')) {
            $query->where('university_id', $request->university_id);
        }

        // 2. فلترة سريعة بالتبويبات (الطلبات المعلقة / الحديثة)
        if ($request->filled('tab')) {
            if ($request->tab === 'pending') {
                $query->where('status', 'قيد المراجعة');
            } elseif ($request->tab === 'recent') {
                $query->where('req_date', '>=', now()->subDays(3));
            }
        } elseif ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. فلترة بحسب النطاق الزمني والتاريخ
        if ($request->filled('date_from')) {
            try {
                $dFrom = \Carbon\Carbon::parse(str_replace('/', '-', trim($request->date_from)))->format('Y-m-d');
                $query->whereDate('req_date', '>=', $dFrom);
            } catch (\Exception $e) {
                // Ignore malformed date
            }
        }
        if ($request->filled('date_to')) {
            try {
                $dTo = \Carbon\Carbon::parse(str_replace('/', '-', trim($request->date_to)))->format('Y-m-d');
                $query->whereDate('req_date', '<=', $dTo);
            } catch (\Exception $e) {
                // Ignore malformed date
            }
        }

        // 4. بحث سريع (برقم الطلب، اسم الطالب، أو رقم الهاتف)
        if ($request->filled('search')) {
            $q = trim($request->search);
            $query->where(function ($sq) use ($q) {
                if (is_numeric($q)) {
                    $sq->where('req_id', $q)->orWhere('phone', 'like', "%$q%");
                } else {
                    $sq->where('full_name', 'like', "%$q%")
                       ->orWhere('phone', 'like', "%$q%")
                       ->orWhere('desired_spec', 'like', "%$q%")
                       ->orWhereHas('university', fn($u) => $u->where('university_name', 'like', "%$q%"));
                }
            });
        }

        $requests = $query->latest('req_id')->paginate(15)->withQueryString();

        // إحصائيات المتابعة العامة
        $stats = [
            'total'          => RegRequest::where('status', '!=', 'مسودة')->count(),
            'pending'        => RegRequest::where('status', 'قيد المراجعة')->count(),
            'recent'         => RegRequest::where('status', '!=', 'مسودة')->where('req_date', '>=', now()->subDays(3))->count(),
            'accepted'       => RegRequest::where('status', 'مقبول')->count(),
            'needs_complete' => RegRequest::where('status', 'يتطلب استكمال')->count(),
            'rejected'       => RegRequest::where('status', 'مرفوض')->count(),
        ];

        $universities = University::select('university_id', 'university_name', 'logo')->orderBy('university_name')->get();

        return view('dashboards.admin.requests', compact('requests', 'stats', 'universities'));
    }

    /**
     * تصدير بيانات طلبات الالتحاق إلى ملف CSV متوافق مع Excel
     * يصدر البيانات الإدارية والرقابية المتاحة والمهمة لمدير النظام
     */
    public function exportRequests(Request $request)
    {
        $query = RegRequest::with(['university', 'payments'])
            ->where('status', '!=', 'مسودة');

        // 1. فلترة بحسب الجامعة
        if ($request->filled('university_id')) {
            $query->where('university_id', $request->university_id);
        }

        // 2. فلترة سريعة بالتبويبات (الطلبات المعلقة / الحديثة) أو الحالة
        if ($request->filled('tab')) {
            if ($request->tab === 'pending') {
                $query->where('status', 'قيد المراجعة');
            } elseif ($request->tab === 'recent') {
                $query->where('req_date', '>=', now()->subDays(3));
            }
        } elseif ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. فلترة بحسب النطاق الزمني والتاريخ
        if ($request->filled('date_from')) {
            try {
                $dFrom = \Carbon\Carbon::parse(str_replace('/', '-', trim($request->date_from)))->format('Y-m-d');
                $query->whereDate('req_date', '>=', $dFrom);
            } catch (\Exception $e) {
                // Ignore
            }
        }
        if ($request->filled('date_to')) {
            try {
                $dTo = \Carbon\Carbon::parse(str_replace('/', '-', trim($request->date_to)))->format('Y-m-d');
                $query->whereDate('req_date', '<=', $dTo);
            } catch (\Exception $e) {
                // Ignore
            }
        }

        // 4. بحث سريع (برقم الطلب، اسم الطالب، أو رقم الهاتف)
        if ($request->filled('search')) {
            $q = trim($request->search);
            $query->where(function ($sq) use ($q) {
                if (is_numeric($q)) {
                    $sq->where('req_id', $q)->orWhere('phone', 'like', "%$q%");
                } else {
                    $sq->where('full_name', 'like', "%$q%")
                       ->orWhere('phone', 'like', "%$q%")
                       ->orWhere('desired_spec', 'like', "%$q%")
                       ->orWhereHas('university', fn($u) => $u->where('university_name', 'like', "%$q%"));
                }
            });
        }

        $requests = $query->latest('req_id')->get();

        $filename = "registration_requests_" . date('Y_m_d_His') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->streamDownload(function () use ($requests) {
            $file = fopen('php://output', 'w');
            // Write UTF-8 BOM for Arabic character rendering in MS Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                'رقم المعاملة',
                'اسم الطالب الرباعي',
                'رقم الهاتف',
                'البريد الإلكتروني',
                'الجامعة المتقدم إليها',
                'نوع الجامعة',
                'التخصص المطلوب',
                'معدل الثانوية %',
                'القسم الثانوي',
                'سنة التخرج',
                'المحافظة والسكن',
                'تاريخ التقديم',
                'حالة الطلب الإدارية',
                'تاريخ قرار الجامعة',
                'حالة سداد الرسوم',
                'سبب الرفض / ملاحظات الجامعة',
            ]);

            foreach ($requests as $r) {
                fputcsv($file, [
                    $r->req_id,
                    $r->full_name,
                    $r->phone ?: '—',
                    $r->email ?: '—',
                    $r->university?->university_name ?? '—',
                    $r->university?->university_type ?? '—',
                    $r->desired_spec ?? '—',
                    $r->sec_avg ? number_format($r->sec_avg, 2) . '%' : '—',
                    $r->sec_section ?: '—',
                    $r->grad_year ?: '—',
                    trim(($r->province ?? '') . ' - ' . ($r->residence ?? ''), ' -') ?: '—',
                    $r->req_date ? $r->req_date->format('Y-m-d H:i') : '—',
                    $r->status,
                    $r->decision_date ? $r->decision_date->format('Y-m-d H:i') : 'بانتظار القرار',
                    $r->payment_status,
                    $r->rejection_reason ?: '—',
                ]);
            }
            fclose($file);
        }, $filename, $headers);
    }

    /**
     * عرض بطاقة ملخص المتابعة لطلب الالتحاق (حقول محددة رقابياً دون التدخل في القرارات أو كشف البيانات الأكاديمية والمالية الخاصة)
     */
    public function showRequest($id)
    {
        $req = RegRequest::with(['university', 'payments'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'request' => [
                'req_id'         => $req->req_id,
                'full_name'      => $req->full_name,
                'phone'          => $req->phone,
                'university'       => $req->university?->university_name ?? '—',
                'university_phone' => $req->university?->contact_phone ?: ($req->university?->phone ?: null),
                'desired_spec'     => $req->desired_spec ?? '—',
                'status'           => $req->status,
                'req_date'         => $req->req_date ? $req->req_date->format('Y/m/d H:i') : '—',
                'decision_date'    => $req->decision_date ? $req->decision_date->format('Y/m/d H:i') : null,
                'payment_status'   => $req->payment_status,
                'rejection_reason' => $req->rejection_reason,
            ],
        ]);
    }

    /**
     * التقارير والإحصائيات الشاملة والمؤشرات التحليلية
     */
    public function reports()
    {
        $stats = $this->getSystemStats();

        // 1. توزيع الطلبات حسب الحالة مع النسب المئوية
        $requestsByStatus = RegRequest::where('status', '!=', 'مسودة')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // 2. المؤشرات الأكاديمية ومعدلات الثانوية العامة
        $academicMetrics = [
            'avg_sec_score' => round(RegRequest::where('status', '!=', 'مسودة')->where('sec_avg', '>', 0)->avg('sec_avg') ?: 0, 1),
            'max_sec_score' => round(RegRequest::where('status', '!=', 'مسودة')->where('sec_avg', '>', 0)->max('sec_avg') ?: 0, 1),
            'min_sec_score' => round(RegRequest::where('status', '!=', 'مسودة')->where('sec_avg', '>', 0)->min('sec_avg') ?: 0, 1),
        ];

        // 3. التوزيع الديموغرافي للطلاب (الجنس)
        $genderStats = RegRequest::where('status', '!=', 'مسودة')
            ->whereNotNull('gender')
            ->select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->get();

        // 4. ترتيب الجامعات الأكثر جذباً للطلاب ومعدل القبول بكل جامعة
        $topUniversities = University::withCount([
            'regRequests as total_reqs' => fn($q) => $q->where('status', '!=', 'مسودة'),
            'regRequests as accepted_reqs' => fn($q) => $q->where('status', 'مقبول'),
        ])
        ->orderByDesc('total_reqs')
        ->take(6)
        ->get();

        // 5. التخصصات الأكاديمية الأكثر طلباً
        $topSpecs = RegRequest::where('status', '!=', 'مسودة')
            ->whereNotNull('desired_spec')
            ->select('desired_spec', DB::raw('count(*) as total'))
            ->groupBy('desired_spec')
            ->orderByDesc('total')
            ->take(8)
            ->get();

        // 6. تدفق الطلبات اليومي والأسبوعي
        $dailyActivity = RegRequest::where('status', '!=', 'مسودة')
            ->select(DB::raw("DATE(req_date) as day_date"), DB::raw('count(*) as total'))
            ->groupBy('day_date')
            ->orderBy('day_date')
            ->take(10)
            ->get();

        // 7. التدفق الشهري لطلبات التسجيل (آخر 6 أشهر)
        $monthlyRequests = RegRequest::where('status', '!=', 'مسودة')
            ->where('req_date', '>=', now()->subMonths(6))
            ->select(DB::raw("DATE_FORMAT(req_date,'%Y-%m') as month"), DB::raw('count(*) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // 8. توزيع المستخدمين حسب نوع الحساب
        $usersByType = User::select('user_type', DB::raw('count(*) as total'))
            ->groupBy('user_type')
            ->get();

        // 9. توزيع الجامعات حسب التصنيف
        $universityTypes = University::select('university_type', DB::raw('count(*) as total'))
            ->groupBy('university_type')
            ->get();

        return view('dashboards.admin.reports', compact(
            'stats', 'requestsByStatus', 'academicMetrics', 'genderStats',
            'topUniversities', 'topSpecs', 'dailyActivity', 'monthlyRequests',
            'usersByType', 'universityTypes'
        ));
    }

    /**
     * الإعدادات العامة للنظام
     */
    public function settings()
    {
        $admin = Auth::user();
        $settings = SystemSetting::getAllKeyValues();
        return view('dashboards.admin.settings', compact('admin', 'settings'));
    }

    /**
     * تحديث إعدادات المنصة والنظام
     */
    public function updatePlatformSettings(Request $request)
    {
        $payload = $request->except(['_token']);

        if (!empty($payload)) {
            SystemSetting::setMany($payload);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ إعدادات النظام بنجاح وتطبيقها على المنصة.',
            'settings' => SystemSetting::getAllKeyValues(),
        ]);
    }

    /**
     * استعادة الإعدادات الافتراضية
     */
    public function resetDefaultSettings(Request $request)
    {
        $defaults = SystemSetting::defaults();
        $resetData = [];
        foreach ($defaults as $k => $item) {
            $resetData[$k] = $item['val'];
        }
        SystemSetting::setMany($resetData);

        return response()->json([
            'success' => true,
            'message' => 'تمت استعادة الإعدادات الافتراضية للنظام بنجاح.',
            'settings' => SystemSetting::getAllKeyValues(),
        ]);
    }

    /**
     * تحديث بيانات حساب المدير
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:4|max:50|unique:users,username,' . Auth::id() . ',user_id',
            'phone'    => 'nullable|string|max:20',
            'email'    => 'nullable|email|max:150|unique:users,email,' . Auth::id() . ',user_id',
        ]);

        Auth::user()->update([
            'username' => $request->username,
            'phone'    => $request->phone,
            'email'    => $request->email,
        ]);

        return response()->json(['success' => true, 'message' => 'تم تحديث بيانات الحساب بنجاح.']);
    }

    /**
     * تغيير كلمة مرور المدير
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return response()->json(['success' => false, 'message' => 'كلمة المرور الحالية غير صحيحة.'], 422);
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);
        return response()->json(['success' => true, 'message' => 'تم تغيير كلمة المرور بنجاح.']);
    }

    // ======= Helper Methods =======

    private function getSystemStats(): array
    {
        $totalRequests = RegRequest::where('status', '!=', 'مسودة')->count();
        $acceptedRequests = RegRequest::where('status', 'مقبول')->count();
        $pendingRequests = RegRequest::where('status', 'قيد المراجعة')->count();
        $rejectedRequests = RegRequest::where('status', 'مرفوض')->count();
        $cancelledRequests = RegRequest::where('status', 'ملغي')->count();
        $acceptanceRate = $totalRequests > 0 ? round(($acceptedRequests / $totalRequests) * 100, 1) : 0;

        $confirmedPayments = DB::table('payments')->where('ver_status', 'مؤكد')->sum('amount') ?: 0;
        $totalPayments = DB::table('payments')->sum('amount') ?: 0;
        $paymentsCount = DB::table('payments')->count();
        $confirmedPaymentsCount = DB::table('payments')->where('ver_status', 'مؤكد')->count();

        $totalProofs = DB::table('reg_proofs')->count();
        $verifiedProofs = DB::table('reg_proofs')->where('verification_status', 'تم التحقق')->count();

        return [
            'total_universities'     => University::count(),
            'active_universities'    => University::where('status', 'نشطة')->count(),
            'public_universities'    => University::where('university_type', 'حكومية')->count(),
            'private_universities'   => University::whereIn('university_type', ['أهلية', 'خاصة'])->count(),
            'total_students'         => Student::count(),
            'total_users'            => User::count(),
            'total_colleges'         => College::count(),
            'total_specializations'  => Specialization::count(),
            'avg_tuition_fees'       => round(Specialization::avg('tuition_fees') ?: 0),
            'total_requests'         => $totalRequests,
            'pending_requests'       => $pendingRequests,
            'accepted_requests'      => $acceptedRequests,
            'rejected_requests'      => $rejectedRequests,
            'cancelled_requests'     => $cancelledRequests,
            'acceptance_rate'        => $acceptanceRate,
            'confirmed_payments'     => $confirmedPayments,
            'total_payments'         => $totalPayments,
            'payments_count'         => $paymentsCount,
            'confirmed_payments_count' => $confirmedPaymentsCount,
            'total_proofs'           => $totalProofs,
            'verified_proofs'        => $verifiedProofs,
            'total_referrals'        => StudentReferral::count(),
            'active_referrals'       => StudentReferral::where('ref_status', 'نشط')->count(),
            'total_ads'              => Advertisement::count(),
            'pending_ads'            => Advertisement::where('review_status', 'قيد المراجعة')->count(),
            'active_ads'             => Advertisement::where('status', 'نشط')->count(),
        ];
    }

    private function getWeeklyActivity(): array
    {
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date     = now()->subDays($i);
            $dateStr  = $date->format('Y-m-d');
            $days[]   = [
                'label'    => $date->locale('ar')->dayName,
                'requests' => RegRequest::whereDate('req_date', $dateStr)->where('status', '!=', 'مسودة')->count(),
                'users'    => User::whereDate('created_at', $dateStr)->count(),
            ];
        }
        return $days;
    }
}
