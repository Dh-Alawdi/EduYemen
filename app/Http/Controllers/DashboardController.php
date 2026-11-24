<?php

namespace App\Http\Controllers;

use App\Models\AdmissionData;
use App\Models\AdmissionDoc;
use App\Models\Advertisement;
use App\Models\College;
use App\Models\Course;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\RegDoc;
use App\Models\RegRequest;
use App\Models\Semester;
use App\Models\Specialization;
use App\Models\Student;
use App\Models\StudentReferral;
use App\Models\StudyPlan;
use App\Models\University;
use App\Models\UniversityPaymentMethod;
use App\Models\User;
use App\Services\MinistryVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    /**
     * لوحة تحكم مدير النظام
     */
    public function adminDashboard()
    {
        $stats = [
            'total_universities' => University::count(),
            'active_universities' => University::where('status', 'نشطة')->count(),
            'total_students' => Student::count(),
            'total_requests' => RegRequest::where('status', '!=', 'مسودة')->count(),
            'pending_requests' => RegRequest::where('status', 'قيد المراجعة')->count(),
            'accepted_requests' => RegRequest::where('status', 'مقبول')->count(),
        ];

        $universities = University::withCount([
            'colleges',
            'regRequests' => fn($q) => $q->where('status', '!=', 'مسودة')
        ])->latest('university_id')->get();
        $recentRequests = RegRequest::where('status', '!=', 'مسودة')->with(['student', 'university'])->latest('req_id')->take(6)->get();

        return view('dashboards.admin', compact('stats', 'universities', 'recentRequests'));
    }

    /**
     * توجيه إلى لوحة التحكم حيث تظهر نافذة تأمين الحساب المنبثقة
     */
    public function showFirstLoginForm()
    {
        return redirect()->route('university.dashboard');
    }

    /**
     * حفظ وتأمين بيانات الدخول عند أول تسجيل دخول عبر النافذة المنبثقة
     */
    public function saveFirstLoginSetup(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'يرجى تسجيل الدخول للمتابعة.'], 401);
            }
            return redirect()->route('login');
        }

        $request->validate([
            'username'         => 'required|string|min:4|max:50|unique:users,username,' . $user->user_id . ',user_id',
            'current_password' => 'required|string',
            'password'         => 'required|string|min:6|confirmed',
        ], [
            'username.required'         => 'اسم المستخدم إلزامي لتسجيل الدخول.',
            'username.unique'           => 'اسم المستخدم هذا مستخدم بالفعل، يرجى اختيار اسم مستخدم متاح.',
            'username.min'              => 'يجب ألا يقل اسم المستخدم عن 4 أحرف.',
            'current_password.required' => 'كلمة المرور الحالية (المؤقتة) مطلوبة لتأكيد هويتكم.',
            'password.required'         => 'يرجى إدخال كلمة المرور الجديدة.',
            'password.min'              => 'يجب ألا تقل كلمة المرور الجديدة عن 6 أحرف.',
            'password.confirmed'        => 'تأكيد كلمة المرور غير متطابق مع كلمة المرور الجديدة.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'كلمة المرور الحالية (المؤقتة) غير صحيحة، يرجى التأكد وإعادة المحاولة.',
                    'errors'  => ['current_password' => ['كلمة المرور الحالية (المؤقتة) غير صحيحة.']]
                ], 422);
            }
            return back()->withInput()->withErrors(['current_password' => 'كلمة المرور الحالية (المؤقتة) غير صحيحة.']);
        }

        try {
            // تحديث بيانات الدخول وتأمين الحساب
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->must_change_password = false;
            $user->last_login = now();
            $user->save();

            $redirectToProfile = $request->boolean('redirect_to_profile');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success'             => true,
                    'message'             => 'تم تأمين وتحديث بيانات الدخول بنجاح!',
                    'redirect_to_profile' => $redirectToProfile,
                ]);
            }

            if ($redirectToProfile) {
                return redirect()->route('university.dashboard', ['tab' => 'profile'])
                    ->with('success', 'تم تأمين وتحديث بيانات الدخول بنجاح! يمكنك الآن استعراض وتحديث بيانات ملف الجامعة.');
            }

            return redirect()->route('university.dashboard')
                ->with('success', 'تم تأمين وتحديث بيانات الدخول بنجاح! مرحباً بكم في منصة EduYemen.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء حفظ بيانات الدخول: ' . $e->getMessage()
                ], 500);
            }
            return back()->withInput()->withErrors(['general' => 'حدث خطأ أثناء حفظ بيانات الدخول: ' . $e->getMessage()]);
        }
    }

    /**
     * لوحة تحكم الجامعة
     */
    public function universityDashboard(?Request $request = null)
    {
        $user = Auth::user();
        $university = $user->university;

        if (!$university) {
            abort(403, 'لا يوجد ملف جامعة مرتبط بهذا الحساب.');
        }

        // تحميل العلاقات
        $university->load([
            'colleges.specializations.admissionData',
            'announcements' => fn($q) => $q->latest('adv_id'),
            'paymentMethods' => fn($q) => $q->latest('method_id'),
            'referrals.student',
        ]);

        $requestsQuery = $university->regRequests()
            ->where('status', '!=', 'مسودة')
            ->with(['student', 'regDocs'])
            ->latest('req_id');
        $requests = $requestsQuery->get();

        $referrals = $university->referrals()->with('student')->latest('referral_id')->get();
        $announcements = $university->announcements;
        $paymentMethods = $university->paymentMethods;

        $totalRequests = $requests->count();
        $pendingRequests = $requests->where('status', 'قيد المراجعة')->count();
        $acceptedRequests = $requests->where('status', 'مقبول')->count();
        $rejectedRequests = $requests->where('status', 'مرفوض')->count();
        $needsCompletionRequests = $requests->where('status', 'يتطلب استكمال')->count();

        $stats = [
            'colleges_count' => $university->colleges->count(),
            'specializations_count' => $university->specializations->count(),
            'total_requests' => $totalRequests,
            'pending_requests' => $pendingRequests,
            'accepted_requests' => $acceptedRequests,
            'rejected_requests' => $rejectedRequests,
            'needs_completion_requests' => $needsCompletionRequests,
            'total_referrals' => $referrals->count(),
            'used_referrals' => $referrals->where('status', 'مستخدم')->count(),
            'active_referrals' => $referrals->where('status', '!=', 'مستخدم')->count(),
            'announcements_count' => $announcements->count(),
            'payment_methods_count' => $paymentMethods->count(),
            'avg_highschool_score' => $totalRequests > 0 ? round($requests->avg('sec_avg'), 1) : 0,
        ];

        $activeTab = ($request && $request->filled('tab')) ? $request->query('tab') : 'overview';

        $notifications = $user->notifications()->orderBy('created_at', 'desc')->orderBy('notification_id', 'desc')->take(60)->get();
        $unreadNotifsCount = $notifications->where('is_read', false)->count();
        $activeModerations = $university->activeModerations()->get();

        return view('dashboards.university', compact('university', 'stats', 'requests', 'referrals', 'announcements', 'paymentMethods', 'activeTab', 'notifications', 'unreadNotifsCount', 'activeModerations'));
    }

    /**
     * لوحة تحكم الطالب والملف الشخصي
     */
    public function studentDashboard()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403, 'لا يوجد ملف طالب مرتبط بهذا الحساب.');
        }

        $requests = $student->regRequests()
            ->where('status', '!=', 'ملغي')
            ->with(['university.paymentMethods' => function($q) { $q->where('is_active', true); }, 'regDocs', 'payments.paymentMethod'])
            ->latest('req_id')
            ->get();
        $referrals = $student->referrals()->with('university')->latest('referral_id')->get();
        $universities = University::whereNotIn('status', ['معلقة', 'محظورة'])->with(['colleges.specializations'])->get();

        return view('dashboards.student', compact('user', 'student', 'requests', 'referrals', 'universities'));
    }

    /**
     * تحديث بيانات الملف الشخصي للطالب (البيانات الشخصية فقط)
     */
    public function updateStudentProfile(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            abort(403);
        }

        $request->validate([
            'full_name' => 'required|string|max:150',
            'phone' => 'required|string|max:20',
            'email' => ['nullable', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->user_id, 'user_id')],
            'gender' => 'nullable|in:ذكر,أنثى',
            'birth_date' => 'nullable|date',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
        ]);

        $user->update([
            'phone' => $request->phone,
            'email' => $request->filled('email') ? $request->email : null,
        ]);

        $student->update([
            'full_name' => $request->full_name,
            'gender' => $request->gender ?: null,
            'birth_date' => $request->filled('birth_date') ? $request->birth_date : null,
            'city' => $request->city ?: null,
            'address' => $request->address ?: null,
        ]);

        return redirect()->route('student.dashboard', ['tab' => 'profile'])->with('success', 'تم حفظ وتحديث البيانات الشخصية بنجاح.');
    }

    /**
     * تغيير كلمة المرور بعد التحقق من الحالية
     */
    public function changeStudentPassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'يرجى إدخال كلمة المرور الحالية لتأكيد الهوية.',
            'password.required' => 'يرجى كتابة كلمة المرور الجديدة.',
            'password.min' => 'يجب ألا تقل كلمة المرور الجديدة عن 6 أحرف.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية التي أدخلتها غير صحيحة.'])->with('tab', 'security');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('student.dashboard')->with('success', 'تم تغيير كلمة المرور وتأمين الحساب بنجاح.');
    }

    /**
     * حفظ وتحديث مظهر وثيم الحساب (ليلي / ضوئي / تلقائي)
     */
    public function updateStudentTheme(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'theme_mode' => 'required|in:light,dark,auto',
            'theme_color' => 'nullable|string|max:30',
        ]);

        $user->update([
            'theme_mode' => $request->theme_mode,
            'theme_color' => $request->theme_color ?? 'navy',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حفظ المظهر بنجاح',
                'theme_mode' => $user->theme_mode,
                'theme_color' => $user->theme_color,
            ]);
        }

        return redirect()->route('student.dashboard')->with('success', 'تم حفظ تفضيلات المظهر بنجاح.');
    }

    /**
     * تحديث تفضيلات الإشعارات (كتم / صوت / نغمة)
     */
    public function updateStudentNotifications(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'notifications_muted' => 'nullable|boolean',
            'notification_sound' => 'nullable|boolean',
            'notification_tone' => 'nullable|string|max:50',
        ]);

        $user->update([
            'notifications_muted' => $request->boolean('notifications_muted'),
            'notification_sound' => $request->boolean('notification_sound', true),
            'notification_tone' => $request->notification_tone ?? ($user->notification_tone ?? 'chime'),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث إعدادات الإشعارات بنجاح',
                'notifications_muted' => $user->notifications_muted,
                'notification_sound' => $user->notification_sound,
                'notification_tone' => $user->notification_tone,
            ]);
        }

        return redirect()->route('student.dashboard')->with('success', 'تم تحديث إعدادات الإشعارات بنجاح.');
    }

    /**
     * تحديد جميع إشعارات المستخدم كمقروءة
     */
    public function markAllNotificationsRead(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->notifications()->where('is_read', false)->update(['is_read' => true]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديد جميع الإشعارات كمقروءة بنجاح.'
            ]);
        }

        return back()->with('success', 'تم تحديد جميع الإشعارات كمقروءة.');
    }

    /**
     * تحديد إشعار معين كمقروء
     */
    public function markNotificationRead(Request $request, $id)
    {
        $user = Auth::user();
        if ($user) {
            $user->notifications()->where('notification_id', $id)->update(['is_read' => true]);
        }

        return response()->json([
            'success' => true,
            'unread_count' => $user ? $user->notifications()->where('is_read', false)->count() : 0
        ]);
    }

    /**
     * إصدار رمز إحالة معتمد لجامعة محددة للحضور الشخصي
     */
    public function createReferralForUniversity(Request $request)
    {
        $user = Auth::user();
        if ($user->user_type === 'university' || !$user->student) {
            return response()->json([
                'success' => false,
                'error' => 'غير مصرح لإدارة الجامعة بإصدار كود إحالة لنفسها. هذا الإجراء مخصص لحسابات الطلبة فقط.'
            ], 403);
        }
        $student = $user->student;

        $univId = $request->input('university_id');
        $univ = University::findOrFail($univId);

        $referral = StudentReferral::where('student_id', $student->student_id)
            ->where('university_id', $univId)
            ->first();

        if (!$referral) {
            $referral = StudentReferral::create([
                'student_id' => $student->student_id,
                'university_id' => $univId,
                'referral_code' => 'EDU-REF-' . $univId . '-' . rand(1000, 9999),
                'status' => 'فعال',
                'created_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'referral_code' => $referral->referral_code,
            'university_name' => $univ->university_name,
        ]);
    }

    /**
     * إصدار رمز إحالة معتمد للحضور الشخصي
     */
    public function generateStudentReferral()
    {
        $user = Auth::user();
        if ($user->user_type === 'university' || !$user->student) {
            abort(403, 'غير مصرح لإدارة الجامعة بإصدار رمز إحالة طلابي.');
        }

        if (!$user->referral_code) {
            $code = 'EDU-' . strtoupper(Str::random(6));
            while (User::where('referral_code', $code)->exists()) {
                $code = 'EDU-' . strtoupper(Str::random(6));
            }
            $user->update(['referral_code' => $code]);
        }

        return redirect()->route('student.dashboard')->with('success', 'تم إصدار رمز الإحالة المعتمد بنجاح!');
    }

    /**
     * عرض استمارة طلب التسجيل الإلكتروني الرسمية
     */
    public function showRegistrationForm(Request $request)
    {
        $user = Auth::user();
        $student = ($user && $user->isStudent()) ? $user->student : null;
        $isPreview = ($user && $user->user_type === 'university');

        $selectedUniversityId = $request->query('university_id') ?? $request->query('universityId');
        $selectedMajor = $request->query('major') ?? $request->query('spec') ?? $request->query('desired_spec');
        $reqId = $request->query('req_id');
        
        $existingRequest = null;
        if ($reqId && $student) {
            $existingRequest = $student->regRequests()->with('regDocs')->find($reqId);
            if ($existingRequest) {
                $selectedUniversityId = $existingRequest->university_id;
                $selectedMajor = $existingRequest->desired_spec;
            }
        }

        $universitiesQuery = University::whereNotIn('status', ['معلقة', 'محظورة'])
            ->with([
                'colleges' => function ($cq) {
                    $cq->where('status', 'نشطة')->whereDoesntHave('activeModeration')->select('college_id', 'university_id', 'college_name')
                        ->with(['specializations' => function ($sq) {
                            $sq->where('status', 'نشط')->whereDoesntHave('activeModeration')
                                ->select('specialization_id', 'college_id', 'specialization_name', 'tuition_fees', 'duration_years', 'language')
                                ->with('admissionData');
                        }]);
                },
                'paymentMethods' => function ($pmq) {
                    $pmq->where('is_active', true);
                }
            ])
            ->orderBy('university_type', 'asc')
            ->orderBy('university_name', 'asc');

        if ($isPreview && $user->university) {
            $selectedUniversityId = $user->university->university_id;
        }

        $universities = $universitiesQuery->get();
        $selectedUniversity = $selectedUniversityId ? $universities->firstWhere('university_id', $selectedUniversityId) : null;

        return view('student.apply', compact(
            'user', 'student', 'universities', 'selectedUniversityId', 'selectedUniversity', 'selectedMajor', 'existingRequest', 'isPreview'
        ));
    }

    /**
     * الاستعلام والتحقق التلقائي من رقم الجلوس عبر خادم محاكاة وزارة التربية والتعليم
     */
    public function verifyHighSchoolSeatNumber(Request $request, MinistryVerificationService $service)
    {
        $seatNumber = $request->query('seat_number') ?? $request->input('seat_number');
        $year = (int)($request->query('grad_year') ?? ($request->input('grad_year') ?? 2024));
        $section = $request->query('sec_section') ?? ($request->input('sec_section') ?? 'علمي');

        $result = $service->verifySeatNumber($seatNumber, $year, $section);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * تقديم طلب تسجيل جديد مباشرة أو استكمال طلب سابق
     */
    public function submitStudentApplication(Request $request)
    {
        $user = Auth::user();
        if ($user->user_type === 'university') {
            if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'أنت تتصفح في وضع المعاينة المخصص لإدارة الجامعة. تقديم طلبات التسجيل متاح لحسابات الطلبة فقط.'
                ], 403);
            }
            abort(403, 'يجب تسجيل الدخول كطالب لتقديم طلب تسجيل (معاينة فقط للجامعة).');
        }

        $student = $user->student;
        if (!$student) {
            $student = Student::create([
                'user_id' => $user->user_id,
                'full_name' => $request->full_name ?? ($request->student_name ?? ($request->fullName ?? ($user->username ?? 'طالب جديد'))),
            ]);
        }

        $request->validate([
            'university_id' => 'required|exists:universities,university_id',
            'desired_spec' => 'nullable|string|max:150',
            'specialty_id' => 'nullable',
            'full_name' => 'nullable|string|max:150',
            'fullName' => 'nullable|string|max:150',
            'student_name' => 'nullable|string|max:150',
            'nationality' => 'nullable|string|max:50',
            'gender' => 'nullable|string|in:ذكر,أنثى',
            'birth_date' => 'nullable|date',
            'birthDate' => 'nullable|date',
            'birth_place' => 'nullable|string|max:150',
            'birthPlace' => 'nullable|string|max:150',
            'province' => 'nullable|string|max:100',
            'governorate' => 'nullable|string|max:100',
            'secondary_school' => 'nullable|string|max:200',
            'school' => 'nullable|string|max:200',
            'seat_number' => 'nullable|string|max:50',
            'seatNumber' => 'nullable|string|max:50',
            'grad_year' => 'nullable|integer|between:2000,2035',
            'graduationYear' => 'nullable|integer|between:2000,2035',
            'sec_section' => 'nullable|string|max:50',
            'schoolSection' => 'nullable|string|max:50',
            'residence' => 'nullable|string|max:150',
            'district' => 'nullable|string|max:150',
            'total_score' => 'nullable|numeric|between:0,1200',
            'totalScore' => 'nullable|numeric|between:0,1200',
            'sec_avg' => 'nullable|numeric|between:40,100',
            'highSchoolRate' => 'nullable|numeric|between:40,100',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'guardian_name' => 'nullable|string|max:150',
            'guardianName' => 'nullable|string|max:150',
            'guardian_rel' => 'nullable|string|max:50',
            'relationship' => 'nullable|string|max:50',
            'guardian_occup' => 'nullable|string|max:150',
            'guardianJob' => 'nullable|string|max:150',
            'home_address' => 'nullable|string|max:255',
            'homeAddress' => 'nullable|string|max:255',
            'gu_phone' => 'nullable|string|max:30',
            'guardianPhone' => 'nullable|string|max:30',
            'is_draft' => 'nullable',
            'referral_code' => 'nullable|string|max:50',
            'existing_req_id' => 'nullable|integer',
            'req_id' => 'nullable|integer',
            'doc_id_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'idCard' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_cert' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'originalCertificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_transcript' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'secondaryForm' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_other' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'otherDocument' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'doc_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // اسم الطالب الخاص بالطلب الحالي (لا يؤثر إطلاقاً على اسم صاحب الحساب أو ملفه الشخصي كولي أمر)
        $fullName = $request->full_name ?? ($request->fullName ?? ($request->student_name ?? $student->full_name));
        $phone = $request->phone ?? ($request->user()->phone ?? null);

        $isDraft = $request->boolean('is_draft') || $request->input('is_draft') === '1' || $request->input('is_draft') === 'true';
        $status = $isDraft ? 'مسودة' : 'قيد المراجعة';

        // تحديد اسم التخصص المطلوب
        $desiredSpec = $request->desired_spec;
        if (!$desiredSpec && $request->filled('specialty_id')) {
            $spec = Specialization::find($request->specialty_id);
            $desiredSpec = $spec ? $spec->specialization_name : $request->specialty_id;
        }
        if (!$desiredSpec) {
            $desiredSpec = 'تخصص عام';
        }

        // التعديل الثالث: التحقق من استيفاء معدل الطالب للحد الأدنى المطلوب للتخصص
        $studentAvg = (float) ($request->sec_avg ?? ($request->highSchoolRate ?? ($student->high_school_avg ?? 0)));
        if ($studentAvg > 0 && !$isDraft && $desiredSpec && $desiredSpec !== 'تخصص عام' && $request->filled('university_id')) {
            $specObj = Specialization::where('specialization_name', $desiredSpec)
                ->whereHas('college', function ($q) use ($request) {
                    $q->where('university_id', $request->university_id);
                })
                ->with('admissionData')
                ->first();

            if ($specObj && $specObj->admissionData && $specObj->admissionData->minimum_average) {
                $minAvg = (float) $specObj->admissionData->minimum_average;
                if ($minAvg > 0 && $studentAvg < $minAvg) {
                    $errorMessage = "معدلك ({$studentAvg}%) غير كافٍ للتسجيل بتخصص «{$desiredSpec}»؛ حيث أن الحد الأدنى المطلوب للقبول في هذا التخصص هو ({$minAvg}%).";
                    if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => $errorMessage,
                            'gpa_error' => true,
                            'min_average' => $minAvg,
                            'student_average' => $studentAvg,
                        ], 422);
                    }
                    return back()->withInput()->with('error', $errorMessage);
                }
            }
        }

        $existingReqId = $request->existing_req_id ?? $request->req_id;
        $regReq = null;
        if ($existingReqId) {
            $regReq = RegRequest::where('student_id', $student->student_id)->find($existingReqId);
        }

        $canCancel = true;
        $isReplacement = false;

        // تطبيق ضوابط تقديم الطلبات في حال كان الطلب جديداً (وليس تعديلاً لمسودة سابقة أو استكمالاً)
        if (!$regReq) {
            // الضابط الأول: لا يمكن للطالب تقديم أكثر من طلب لنفس التخصص
            $existingSpecReq = RegRequest::where('student_id', $student->student_id)
                ->where('desired_spec', $desiredSpec)
                ->whereNotIn('status', ['ملغي', 'مرفوض'])
                ->first();

            if ($existingSpecReq) {
                $specUnivName = $existingSpecReq->university ? $existingSpecReq->university->university_name : 'الجامعة';
                $errorMessage = "لا يمكن تقديم أكثر من طلب لنفس التخصص ({$desiredSpec}). لديك طلب مسجل بالفعل بهذا التخصص لدى ({$specUnivName}) برقم طلب #{$existingSpecReq->req_id} وحالته: {$existingSpecReq->status}.";
                if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage
                    ], 422);
                }
                return back()->withInput()->with('error', $errorMessage);
            }

            // الضابط الثاني: لا يمكن تقديم أكثر من طلب لنفس الجامعة خلال 24 ساعة إلا بعد إلغاء الطلب الأول، وفي هذه الحالة لا يمكن إلغاء الطلب الثاني
            $twentyFourHoursAgo = now()->subHours(24);
            $recentUnivRequests = RegRequest::where('student_id', $student->student_id)
                ->where('university_id', $request->university_id)
                ->where('req_date', '>=', $twentyFourHoursAgo)
                ->get();

            // فحص وجود طلب نشط (غير ملغي) لنفس الجامعة خلال 24 ساعة
            $activeRecentReq = $recentUnivRequests->where('status', '!=', 'ملغي')->first();
            if ($activeRecentReq) {
                $univObj = University::find($request->university_id);
                $uName = $univObj ? $univObj->university_name : 'هذه الجامعة';
                $errorMessage = "لا يمكنك تقديم أكثر من طلب لدى {$uName} خلال 24 ساعة إلا بعد إلغاء طلبك الأول (#{$activeRecentReq->req_id}). يمكنك التوجه إلى صفحة طلباتي وإلغاء الطلب السابق للتقديم على تخصص آخر.";
                if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage
                    ], 422);
                }
                return back()->withInput()->with('error', $errorMessage);
            }

            // فحص هل سبق للطالب تقديم طلب بديل ثانٍ لنفس الجامعة خلال 24 ساعة
            $alreadyReplaced = $recentUnivRequests->where('is_replacement', true)->first();
            if ($alreadyReplaced) {
                $errorMessage = "عذراً، لقد استنفدت الحد المسموح به لطلبات التقديم لهذه الجامعة خلال 24 ساعة (تم تقديم طلب بديل مسبقاً برقم #{$alreadyReplaced->req_id}).";
                if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage
                    ], 422);
                }
                return back()->withInput()->with('error', $errorMessage);
            }

            // إذا ألغى الطالب طلباً سابقاً لنفس الجامعة خلال 24 ساعة، يكون هذا الطلب هو الطلب الثاني البديل (لا يمكن إلغاؤه)
            $cancelledRecentReq = $recentUnivRequests->where('status', 'ملغي')->first();
            if ($cancelledRecentReq) {
                $isReplacement = true;
                $canCancel = false;
            }
        }

        $reqData = [
            'student_id' => $student->student_id,
            'university_id' => $request->university_id,
            'full_name' => $fullName,
            'nationality' => $request->nationality ?? 'يمني',
            'gender' => $request->gender ?? ($student->gender ?? 'ذكر'),
            'birth_date' => $request->birth_date ?? ($request->birthDate ?? ($student->birth_date ?? '2005-01-01')),
            'birth_place' => $request->birth_place ?? ($request->birthPlace ?? ($student->city ?? 'صنعاء')),
            'province' => $request->province ?? ($request->governorate ?? 'أمانة العاصمة'),
            'secondary_school' => $request->secondary_school ?? ($request->school ?? ($student->high_school_name ?? 'ثانوية عامة')),
            'seat_number' => $request->seat_number ?? ($request->seatNumber ?? ('S-' . rand(10000, 99999))),
            'grad_year' => $request->grad_year ?? ($request->graduationYear ?? ($student->grad_year ?? 2024)),
            'sec_section' => $request->sec_section ?? ($request->schoolSection ?? 'علمي'),
            'residence' => $request->residence ?? ($request->district ?? ($student->address ?? 'صنعاء')),
            'total_score' => $request->total_score ?? ($request->totalScore ?? ($request->sec_avg ? round($request->sec_avg * 8) : 700)),
            'sec_avg' => $request->sec_avg ?? ($request->highSchoolRate ?? ($student->high_school_avg ?? 80.00)),
            'req_date' => now(),
            'phone' => $phone ?? '770000000',
            'email' => $request->email ?? ($user->email ?? 'student@example.com'),
            'guardian_name' => $request->guardian_name ?? ($request->guardianName ?? ('ولي أمر ' . $fullName)),
            'guardian' => $request->guardian_name ?? ($request->guardianName ?? ('ولي أمر ' . $fullName)),
            'guardian_rel' => $request->guardian_rel ?? ($request->relationship ?? 'أب'),
            'guardian_occup' => $request->guardian_occup ?? ($request->guardianJob ?? null),
            'home_address' => $request->home_address ?? ($request->homeAddress ?? ($student->address ?? 'صنعاء')),
            'gu_phone' => $request->gu_phone ?? ($request->guardianPhone ?? ($phone ?? '770000000')),
            'desired_spec' => $desiredSpec,
            'status' => $status,
            'can_cancel' => $regReq ? $regReq->can_cancel : $canCancel,
            'is_replacement' => $regReq ? $regReq->is_replacement : $isReplacement,
            'rejection_reason' => $status === 'قيد المراجعة' ? null : ($regReq->rejection_reason ?? null),
        ];

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            if ($regReq) {
                $regReq->update($reqData);
            } else {
                $regReq = RegRequest::create($reqData);
            }

            // معالجة وحفظ الوثائق المرفوعة
            $docsMap = [
                'doc_id_card' => 'صورة البطاقة الشخصية',
                'idCard' => 'صورة البطاقة الشخصية',
                'doc_cert' => 'شهادة الثانوية الأصل',
                'originalCertificate' => 'شهادة الثانوية الأصل',
                'doc_transcript' => 'صورة استمارة الثانوية',
                'secondaryForm' => 'صورة استمارة الثانوية',
                'doc_other' => 'وثيقة أخرى',
                'otherDocument' => 'وثيقة أخرى',
                'doc_file' => 'استمارة الثانوية / وثيقة الهوية',
            ];

            foreach ($docsMap as $inputKey => $docLabel) {
                if ($request->hasFile($inputKey)) {
                    $file = $request->file($inputKey);
                    $ext = $file->getClientOriginalExtension();
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $storedFileName = $docLabel . ' - ' . $originalName . '.' . $ext;
                    $uniqueStorageName = time() . '_' . uniqid() . '.' . $ext;
                    $path = $file->storeAs('reg_docs', $uniqueStorageName, 'public');

                    RegDoc::create([
                        'req_id' => $regReq->req_id,
                        'file_name' => $storedFileName,
                        'file_path' => $path,
                        'up_at' => now(),
                        'ver_status' => 'قيد المراجعة',
                    ]);
                }
            }

            // معالجة الوثائق المرفوعة بصيغة مصفوفة إن وجدت
            if ($request->hasFile('documents')) {
                $docs = $request->file('documents');
                $docTypes = (array) $request->input('doc_type', []);
                foreach ((array)$docs as $idx => $docFile) {
                    if ($docFile) {
                        $typeKey = $docTypes[$idx] ?? 'otherDocument';
                        $label = $docsMap[$typeKey] ?? 'مستند مرفق';
                        $ext = $docFile->getClientOriginalExtension();
                        $originalName = pathinfo($docFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $storedFileName = $label . ' - ' . $originalName . '.' . $ext;
                        $uniqueStorageName = time() . '_' . uniqid() . '.' . $ext;
                        $path = $docFile->storeAs('reg_docs', $uniqueStorageName, 'public');

                        RegDoc::create([
                            'req_id' => $regReq->req_id,
                            'file_name' => $storedFileName,
                            'file_path' => $path,
                            'up_at' => now(),
                            'ver_status' => 'قيد المراجعة',
                        ]);
                    }
                }
            }

            // معالجة وحفظ سند سداد الرسوم المرفوع مع استمارة التقديم
            $proofPath = null;
            if ($request->hasFile('payment_proof')) {
                $pFile = $request->file('payment_proof');
                $pExt = $pFile->getClientOriginalExtension();
                $proofPath = $pFile->storeAs('payments', time() . '_' . uniqid() . '.' . $pExt, 'public');
            } elseif ($request->hasFile('proof_file')) {
                $pFile = $request->file('proof_file');
                $pExt = $pFile->getClientOriginalExtension();
                $proofPath = $pFile->storeAs('payments', time() . '_' . uniqid() . '.' . $pExt, 'public');
            }

            $methodId = $request->payment_method_id ?? ($request->method_id ?? null);
            $reference = $request->payment_reference ?? ($request->reference ?? null);

            // احتساب رسوم التسجيل الرسمية المقررة من الجامعة للتخصص تلقائياً
            $officialFee = 5000;
            if ($request->university_id && $regReq->desired_spec) {
                $specModel = Specialization::where('specialization_name', $regReq->desired_spec)
                    ->whereHas('college', function ($q) use ($request) {
                        $q->where('university_id', $request->university_id);
                    })
                    ->first();
                if ($specModel && $specModel->admission && $specModel->admission->registration_fee) {
                    $officialFee = (float)$specModel->admission->registration_fee;
                }
            }
            $amount = $officialFee;
            $payDate = $request->payment_date ?? ($request->pay_date ?? now()->toDateString());
            $payNotes = $request->payment_notes ?? ($request->pay_notes ?? null);

            if ($proofPath || $reference || $methodId) {
                Payment::create([
                    'req_id' => $regReq->req_id,
                    'method_id' => $methodId,
                    'amount' => $amount,
                    'pay_date' => $payDate,
                    'reference' => $reference,
                    'proof_file' => $proofPath,
                    'ver_status' => 'قيد التحقق',
                    'notes' => $payNotes,
                ]);
            }

            if ($request->filled('referral_code')) {
                StudentReferral::where('student_id', $student->student_id)
                    ->where('university_id', $request->university_id)
                    ->where('referral_code', trim($request->referral_code))
                    ->update(['used_at' => now(), 'status' => 'مستخدم']);
            }

            // إرسال إشعارات في النظام
            $univ = University::find($request->university_id);
            $univName = $univ ? $univ->university_name : 'الجامعة';

            // إشعار للطالب
            Notification::create([
                'user_id' => $user->user_id,
                'notification_type' => $isDraft ? 'registration_draft' : ($existingReqId ? 'registration_completed' : 'registration_submitted'),
                'title' => $isDraft ? 'تم حفظ مسودة طلب التسجيل' : ($existingReqId ? 'تم استكمال وإعادة إرسال طلب التسجيل' : 'تم استلام طلب التسجيل بنجاح'),
                'message' => $isDraft
                    ? 'تم حفظ مسودة طلبك لتخصص ' . $regReq->desired_spec . ' لدى ' . $univName . ' (رقم المسودة #' . $regReq->req_id . '). المسودة محفوظة في حسابك فقط ولم تُرسل للجامعة بعد.'
                    : ($existingReqId ? 'تم استكمال وإعادة إرسال طلبك برقم #' . $regReq->req_id . ' لتخصص ' . $regReq->desired_spec . ' لدى ' . $univName : 'تم تقديم طلب الالتحاق بتخصص ' . $regReq->desired_spec . ' لدى ' . $univName . ' برقم طلب #' . $regReq->req_id),
                'is_read' => false,
                'created_at' => now(),
            ]);

            // لا يتم إرسال أي إشعار أو بيانات للجامعة إذا كان الطلب مسودة!
            // يتم إرسال الطلب وإشعار الجامعة فقط عند قيام الطالب بالضغط على "إرسال طلب التسجيل" (is_draft = 0)
            if (!$isDraft && $univ && $univ->user_id) {
                Notification::create([
                    'user_id' => $univ->user_id,
                    'notification_type' => $existingReqId ? 'registration_resubmitted' : 'new_registration_request',
                    'title' => ($existingReqId ? 'تم استكمال طلب التسجيل #' : 'طلب تسجيل جديد #') . $regReq->req_id,
                    'message' => ($existingReqId ? 'قام الطالب ' . $regReq->full_name . ' باستكمال وإعادة إرسال طلبه لتخصص ' : 'وصل طلب تسجيل جديد من الطالب ' . $regReq->full_name . ' للالتحاق بتخصص ') . $regReq->desired_spec,
                    'is_read' => false,
                    'created_at' => now(),
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            throw $e;
        }

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $isDraft
                    ? 'تم حفظ مسودة طلبك بنجاح في ملفك الشخصي فقط، ولم يتم إرسالها إلى الجامعة. يمكنك استكمالها وإرسالها متى شئت.'
                    : 'تم تقديم طلب الالتحاق بنجاح، رقم الطلب #' . $regReq->req_id,
                'req_id' => $regReq->req_id,
                'status' => $regReq->status,
                'is_draft' => $isDraft,
            ]);
        }

        return redirect()->route('student.dashboard', ['tab' => 'requests'])
            ->with('success', $isDraft ? 'تم حفظ مسودة طلبك بنجاح في حسابك ولم يتم إرسالها للجامعة بعد.' : 'تم تقديم طلب التسجيل بنجاح، ويمكنك متابعة حالته مباشرة.');
    }

    /**
     * إلغاء طلب التسجيل من قِبل الطالب وفق ضوابط المنصة
     */
    public function cancelStudentApplication(Request $request, $id)
    {
        $user = Auth::user();
        $student = $user->student;
        if (!$student) {
            abort(403, 'غير مصرح.');
        }

        $regReq = RegRequest::where('student_id', $student->student_id)->findOrFail($id);

        if (in_array($regReq->status, ['مقبول', 'مرفوض'])) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن إلغاء الطلب بعد صدور قرار نهائي بالقبول أو الرفض من لجنة القبول.'
            ], 422);
        }

        if ($regReq->status === 'ملغي') {
            return response()->json([
                'success' => false,
                'message' => 'هذا الطلب ملغي بالفعل مسبقاً.'
            ], 422);
        }

        // تطبيق الضابط: "وفي هذه الحالة لا يمكنه الغاء الطلب الثاني"
        if (!$regReq->can_cancel || $regReq->is_replacement) {
            return response()->json([
                'success' => false,
                'message' => 'عذراً، تنص ضوابط المنصة على عدم إمكانية إلغاء الطلب البديل (الثاني) المقدم لنفس الجامعة خلال 24 ساعة.'
            ], 403);
        }

        $univName = $regReq->university ? $regReq->university->university_name : 'الجامعة';
        $desiredSpec = $regReq->desired_spec;
        $reqId = $regReq->req_id;
        $studentFullName = $student->full_name ?: ($user->username ?? 'الطالب');
        $univUserId = $regReq->university?->user_id;

        // حذف الطلب نهائياً وكلياً من قاعدة البيانات لتجنب إرباك الطالب
        $regReq->regDocs()->delete();
        $regReq->payments()->delete();
        $regReq->delete();

        // إشعار للطالب
        Notification::create([
            'user_id' => $user->user_id,
            'notification_type' => 'registration_cancelled',
            'title' => 'تم إلغاء وحذف طلب التسجيل #' . $reqId,
            'message' => 'تم إلغاء طلبك للالتحاق بتخصص ' . $desiredSpec . ' لدى ' . $univName . ' بنجاح. يمكنك الآن التقديم على تخصص بديل.',
            'is_read' => false,
            'created_at' => now(),
        ]);

        // إشعار لمسؤول القبول في الجامعة
        if ($univUserId) {
            Notification::create([
                'user_id' => $univUserId,
                'notification_type' => 'registration_cancelled_by_student',
                'title' => 'إلغاء طلب تسجيل #' . $reqId,
                'message' => 'قام الطالب ' . $studentFullName . ' بإلغاء طلبه للالتحاق بتخصص ' . $desiredSpec . '.',
                'is_read' => false,
                'created_at' => now(),
            ]);
        }

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إلغاء طلب التسجيل بنجاح. يمكنك الآن التقديم على طلب جديد أو تخصص آخر لدى نفس الجامعة إذا رغبت.',
                'req_id' => $regReq->req_id,
            ]);
        }

        return redirect()->route('student.dashboard', ['tab' => 'requests'])
            ->with('success', 'تم إلغاء طلب التسجيل بنجاح.');
    }

    /*
    |--------------------------------------------------------------------------
    | وظائف مستخدم الجامعة (University User Management Methods)
    |--------------------------------------------------------------------------
    */

    /**
     * تحديث بيانات الجامعة الأساسية
     */
    public function updateUniversityProfile(Request $request)
    {
        $user = Auth::user();
        $university = $user->university;

        if (!$university) {
            abort(403, 'غير مصرح.');
        }

        $validated = $request->validate([
            'university_name' => 'required|string|max:100',
            'university_type' => 'required|string|max:50',
            'phone' => 'nullable|string|max:20',
            'contact_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
            'website' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:100',
            'address' => 'required|string|max:255',
            'year_establ' => 'nullable|integer|between:1900,2035',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,jfif|max:5120',
            'campus_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp,jfif|max:8192',
        ]);

        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            $logoName = 'logo_' . $university->university_id . '_' . time() . '.' . $logoFile->getClientOriginalExtension();
            $logoFile->move(public_path('uploads/universities'), $logoName);
            $validated['logo'] = '/uploads/universities/' . $logoName;
        } else {
            unset($validated['logo']);
        }

        if ($request->hasFile('campus_image')) {
            $campusFile = $request->file('campus_image');
            $campusName = 'campus_' . $university->university_id . '_' . time() . '.' . $campusFile->getClientOriginalExtension();
            $campusFile->move(public_path('uploads/universities'), $campusName);
            $validated['campus_image'] = '/uploads/universities/' . $campusName;
        } else {
            unset($validated['campus_image']);
        }

        $university->update($validated);

        if ($request->filled('email') && $request->email !== $user->email) {
            $user->update(['email' => $request->email]);
        }
        if ($request->filled('phone') && $request->phone !== $user->phone) {
            $user->update(['phone' => $request->phone]);
        }

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حفظ وتحديث بيانات الجامعة بنجاح.',
                'university' => $university,
            ]);
        }

        return back()->with('success', 'تم تحديث بيانات الجامعة بنجاح.');
    }

    /**
     * تغيير كلمة مرور حساب الجامعة
     */
    public function changeUniversityPassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'كلمة المرور الحالية غير صحيحة.',
                ], 422);
            }
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تغيير كلمة المرور بنجاح.',
            ]);
        }

        return back()->with('success', 'تم تغيير كلمة المرور بنجاح.');
    }

    /**
     * تحديث إعدادات الجامعة (حالة القبول، التنبيهات)
     */
    public function updateUniversitySettings(Request $request)
    {
        $user = Auth::user();
        $university = $user->university;

        if (!$university) {
            abort(403);
        }

        if ($request->has('admission_status')) {
            $university->update([
                'status' => $request->admission_status === 'open' ? 'نشطة' : 'مغلق',
            ]);
        }

        if ($request->has('theme_mode')) {
            $user->update(['theme_mode' => $request->theme_mode]);
        }

        if ($request->has('notification_tone')) {
            $user->update(['notification_tone' => $request->notification_tone]);
        }

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث إعدادات النظام بنجاح.',
            ]);
        }

        return back()->with('success', 'تم تحديث إعدادات النظام بنجاح.');
    }

    /**
     * جلب تفاصيل طلب تسجيل معين
     */
    public function getUniversityRequestDetails($id)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $req = $university->regRequests()->where('status', '!=', 'مسودة')->with(['student', 'regDocs', 'payments.paymentMethod'])->findOrFail($id);

        $formattedPayments = $req->payments->map(function($p) {
            return [
                'payment_id' => $p->payment_id,
                'method_name' => $p->paymentMethod ? $p->paymentMethod->method_name : ($p->method_id ? 'وسيلة دفع #' . $p->method_id : 'وسيلة دفع معتمدة'),
                'method_type' => $p->paymentMethod ? $p->paymentMethod->method_type : 'حساب بنكي / محفظة',
                'account_number' => $p->paymentMethod ? $p->paymentMethod->account_number : '—',
                'amount' => $p->amount,
                'pay_date' => $p->pay_date ? $p->pay_date->format('Y-m-d') : null,
                'reference' => $p->reference,
                'proof_file' => $p->proof_file,
                'proof_file_url' => $p->proof_file ? asset('storage/' . $p->proof_file) : null,
                'ver_status' => $p->ver_status ?? 'قيد التحقق',
                'ver_result' => $p->ver_result,
                'notes' => $p->notes,
            ];
        });

        return response()->json([
            'success' => true,
            'request' => $req,
            'payments' => $formattedPayments,
            'payment_status' => $req->payment_status,
            'req_date_formatted' => $req->req_date ? $req->req_date->format('Y-m-d H:i') : null,
            'birth_date_formatted' => $req->birth_date ? $req->birth_date->format('Y-m-d') : null,
        ]);
    }

    /**
     * تحديث حالة طلب التسجيل (قبول / رفض / يتطلب استكمال / قيد المراجعة)
     */
    public function updateUniversityRequestStatus(Request $request, $id)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $req = $university->regRequests()->where('status', '!=', 'مسودة')->with('student')->findOrFail($id);

        $request->validate([
            'status' => 'required|in:قيد المراجعة,مقبول,مرفوض,يتطلب استكمال',
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $updateData = [
            'status' => $request->status,
            'decision_date' => $request->status !== 'قيد المراجعة' ? now() : null,
            'rejection_reason' => in_array($request->status, ['مرفوض', 'يتطلب استكمال']) ? $request->rejection_reason : null,
        ];

        $req->update($updateData);

        // إرسال إشعار فوري للطالب عند تحديث حالة طلبه
        if ($req->student && $req->student->user_id) {
            $notifTitle = match($request->status) {
                'مقبول' => 'تهانينا! تم قبول طلب تسجيلك #' . $req->req_id,
                'يتطلب استكمال' => 'مطلوب استكمال وثائق/بيانات طلب التسجيل #' . $req->req_id,
                'مرفوض' => 'إشعار بشأن قرار طلب التسجيل #' . $req->req_id,
                default => 'تحديث في حالة طلب التسجيل #' . $req->req_id,
            };

            $notifMsg = match($request->status) {
                'مقبول' => 'تم قبول طلب التحاقك بتخصص (' . $req->desired_spec . ') لدى جامعة ' . $university->university_name . ' بنجاح. يرجى مراجعة إدارة القبول والتسجيل لاستكمال الإجراءات.',
                'يتطلب استكمال' => 'تطلب جامعة ' . $university->university_name . ' استكمال بيانات أو وثائق لطلب تسجيلك بتخصص (' . $req->desired_spec . '). ملاحظات الجامعة: ' . ($request->rejection_reason ?: 'يرجى مراجعة وإعادة إرفاق الوثائق المطلوبة بدقة من صفحة استكمال الطلب.'),
                'مرفوض' => 'نعتذر، لم يتم قبول طلب التحاقك لدى جامعة ' . $university->university_name . ($request->rejection_reason ? ' بسبب: ' . $request->rejection_reason : '.'),
                default => 'تم تغيير حالة طلبك رقم #' . $req->req_id . ' إلى: ' . $request->status,
            };

            Notification::create([
                'user_id' => $req->student->user_id,
                'notification_type' => $request->status === 'يتطلب استكمال' ? 'request_needs_completion' : ($request->status === 'مقبول' ? 'request_accepted' : 'request_status_updated'),
                'title' => $notifTitle,
                'message' => $notifMsg,
                'is_read' => false,
                'created_at' => now(),
            ]);
        }

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث حالة الطلب رقم #' . $req->req_id . ' للطالب ' . $req->full_name . ' إلى: ' . $req->status,
                'status' => $req->status,
                'rejection_reason' => $req->rejection_reason,
            ]);
        }

        return back()->with('success', 'تم تحديث حالة الطلب بنجاح.');
    }

    /**
     * إضافة كلية جديدة
     */
    public function storeCollege(Request $request)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $request->validate([
            'college_name' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
        ]);

        $college = $university->colleges()->create([
            'college_name' => $request->college_name,
            'description' => $request->description,
            'status' => 'نشطة',
        ]);

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تمت إضافة الكلية بنجاح: ' . $college->college_name,
                'college' => $college,
            ]);
        }

        return back()->with('success', 'تمت إضافة الكلية بنجاح.');
    }

    /**
     * تعديل بيانات كلية
     */
    public function updateCollege(Request $request, $id)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $college = $university->colleges()->findOrFail($id);

        $request->validate([
            'college_name' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|string|in:نشطة,معلقة,مغلقة',
        ]);

        $college->update([
            'college_name' => $request->college_name,
            'description' => $request->description,
            'status' => $request->status ?? $college->status,
        ]);

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الكلية بنجاح.',
                'college' => $college,
            ]);
        }

        return back()->with('success', 'تم تحديث الكلية بنجاح.');
    }

    /**
     * حذف كلية
     */
    public function deleteCollege($id)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $college = $university->colleges()->findOrFail($id);
        $name = $college->college_name;
        $college->delete();

        if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف كلية (' . $name . ') وكافة تخصصاتها بنجاح.',
            ]);
        }

        return back()->with('success', 'تم حذف الكلية بنجاح.');
    }

    /**
     * إضافة تخصص أكاديمي جديد
     */
    public function storeSpecialization(Request $request)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $request->validate([
            'college_id' => 'required|exists:colleges,college_id',
            'specialization_name' => 'required|string|max:150',
            'tuition_fees' => 'required|numeric|min:0',
            'duration_years' => 'nullable|integer|between:1,8',
            'language' => 'nullable|string|max:50',
            'minimum_average' => 'nullable|numeric|between:50,100',
            'secondary_last_year' => 'nullable|integer|between:2000,2035',
            'registration_fee' => 'nullable|numeric|min:0',
            'admission_start_date' => 'nullable|date',
            'admission_end_date' => 'nullable|date',
            'admission_status' => 'nullable|string|in:مفتوح,مغلق,قريباً',
            'description' => 'nullable|string|max:2000',
        ]);

        $college = $university->colleges()->findOrFail($request->college_id);

        $spec = $college->specializations()->create([
            'specialization_name' => $request->specialization_name,
            'tuition_fees' => $request->tuition_fees,
            'duration_years' => $request->duration_years ?? 4,
            'language' => $request->language ?? 'العربية',
            'description' => $request->description,
            'status' => 'نشط',
        ]);

        $reqDocs = $request->input('required_documents');
        if (is_string($reqDocs)) {
            $reqDocs = json_decode($reqDocs, true);
        }
        if (!$reqDocs || !is_array($reqDocs)) {
            $reqDocs = [
                ['name' => 'أصل استمارة الثانوية العامة', 'description' => 'مصدقة رسمياً أو صورة طبق الأصل', 'is_required' => true],
                ['name' => 'صورة البطاقة الشخصية أو جواز السفر', 'description' => 'وثيقة إثبات الهوية سارية المفعول', 'is_required' => true],
                ['name' => 'صور شخصية حديثة (4×6)', 'description' => 'خلفية بيضاء عدد 4 صور', 'is_required' => true],
                ['name' => 'شهادة الفحص الطبي المخبري', 'description' => 'مطلوبة للتخصصات العلمية والصحية', 'is_required' => false],
            ];
        }

        $admission = $spec->admissionData()->create([
            'admission_data' => $request->input('admission_data', 'شروط القبول والتسجيل للبرنامج الأكاديمي'),
            'minimum_average' => $request->minimum_average ?? 70.00,
            'description' => $request->input('admission_description', 'تطبق شروط المفاضلة المعتمدة بوزارة التعليم العالي والبحث العلمي.'),
            'secondary_last_year' => $request->secondary_last_year ?? 2025,
            'tuition_fees' => $request->tuition_fees,
            'registration_fee' => $request->registration_fee ?? 5000.00,
            'admission_start_date' => $request->admission_start_date ?? '2026-06-01',
            'admission_end_date' => $request->admission_end_date ?? '2026-09-30',
            'admission_status' => $request->admission_status ?? 'مفتوح',
            'required_documents' => [],
        ]);

        $admission->syncDocuments($reqDocs);

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تمت إضافة التخصص الأكاديمي (' . $spec->specialization_name . ') بنجاح.',
                'specialization' => $spec->load('admissionData'),
            ]);
        }

        return back()->with('success', 'تمت إضافة التخصص الأكاديمي بنجاح.');
    }

    /**
     * تعديل تخصص أكاديمي
     */
    public function updateSpecialization(Request $request, $id)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $spec = Specialization::whereHas('college', fn($q) => $q->where('university_id', $university->university_id))->findOrFail($id);

        $request->validate([
            'specialization_name' => 'required|string|max:150',
            'tuition_fees' => 'required|numeric|min:0',
            'duration_years' => 'nullable|integer|between:1,8',
            'language' => 'nullable|string|max:50',
            'minimum_average' => 'nullable|numeric|between:50,100',
            'secondary_last_year' => 'nullable|integer|between:2000,2035',
            'registration_fee' => 'nullable|numeric|min:0',
            'admission_start_date' => 'nullable|date',
            'admission_end_date' => 'nullable|date',
            'admission_status' => 'nullable|string|in:مفتوح,مغلق,قريباً',
            'description' => 'nullable|string|max:2000',
            'status' => 'nullable|string|in:نشط,مغلق,معلق',
        ]);

        $updateData = [
            'specialization_name' => $request->specialization_name,
            'tuition_fees' => $request->tuition_fees,
            'description' => $request->description,
            'status' => $request->status ?? $spec->status,
        ];

        if ($request->filled('duration_years')) {
            $updateData['duration_years'] = (int)$request->duration_years;
        }
        if ($request->filled('language')) {
            $updateData['language'] = $request->language;
        }

        $spec->update($updateData);

        // تحديث أو إنشاء بيانات القبول
        $admissionUpdates = [];
        if ($request->filled('minimum_average')) {
            $admissionUpdates['minimum_average'] = $request->minimum_average;
        }
        if ($request->filled('secondary_last_year')) {
            $admissionUpdates['secondary_last_year'] = $request->secondary_last_year;
        }
        if ($request->filled('admission_data')) {
            $admissionUpdates['admission_data'] = $request->admission_data;
        }
        if ($request->filled('description')) {
            $admissionUpdates['description'] = $request->description;
        }
        if ($request->filled('registration_fee')) {
            $admissionUpdates['registration_fee'] = $request->registration_fee;
        }
        if ($request->filled('admission_start_date')) {
            $admissionUpdates['admission_start_date'] = $request->admission_start_date;
        }
        if ($request->filled('admission_end_date')) {
            $admissionUpdates['admission_end_date'] = $request->admission_end_date;
        }
        if ($request->filled('admission_status')) {
            $admissionUpdates['admission_status'] = $request->admission_status;
        }
        $admissionUpdates['tuition_fees'] = $request->tuition_fees;

        $admission = $spec->admissionData;
        if (!$admission) {
            $admissionUpdates['admission_data'] = $admissionUpdates['admission_data'] ?? 'شروط القبول والتسجيل للبرنامج الأكاديمي';
            $admissionUpdates['minimum_average'] = $admissionUpdates['minimum_average'] ?? 70.00;
            $admissionUpdates['secondary_last_year'] = $admissionUpdates['secondary_last_year'] ?? 2025;
            $admissionUpdates['registration_fee'] = $admissionUpdates['registration_fee'] ?? 5000.00;
            $admissionUpdates['admission_status'] = $admissionUpdates['admission_status'] ?? 'مفتوح';
            $admissionUpdates['required_documents'] = [];
            $admission = $spec->admissionData()->create($admissionUpdates);
        } else {
            $admission->update($admissionUpdates);
        }

        if ($request->has('required_documents')) {
            $reqDocs = $request->input('required_documents');
            if (is_string($reqDocs)) {
                $reqDocs = json_decode($reqDocs, true);
            }
            if (is_array($reqDocs)) {
                $admission->syncDocuments($reqDocs);
            }
        }

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث التخصص وبيانات القبول بنجاح.',
                'specialization' => $spec->fresh('admissionData'),
            ]);
        }

        return back()->with('success', 'تم تحديث التخصص بنجاح.');
    }

    /**
     * جلب بيانات القبول والتسجيل والوثائق لتخصص محدد يتبع للجامعة
     */
    public function getSpecializationAdmission($id)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $spec = Specialization::whereHas('college', fn($q) => $q->where('university_id', $university->university_id))
            ->with(['admissionData', 'college'])
            ->findOrFail($id);

        $admission = $spec->admissionData;
        if (!$admission) {
            $admission = $spec->admissionData()->create([
                'admission_data' => 'شروط القبول والتسجيل للبرنامج الأكاديمي',
                'minimum_average' => 70.00,
                'secondary_last_year' => 2025,
                'document_name' => 'أصل استمارة الثانوية العامة',
                'is_required' => true,
            ]);
        }

        return response()->json([
            'success' => true,
            'specialization_id' => $spec->specialization_id,
            'specialization_name' => $spec->specialization_name,
            'college_name' => $spec->college->college_name,
            'tuition_fees' => (float)$spec->tuition_fees,
            'admission' => [
                'admission_id' => $admission->admission_id,
                'minimum_average' => (float)$admission->minimum_average,
                'secondary_last_year' => (int)$admission->secondary_last_year,
                'admission_data' => $admission->admission_data,
                'description' => $admission->description,
                'tuition_fees' => (float)($admission->tuition_fees ?? $spec->tuition_fees),
                'registration_fee' => (float)($admission->registration_fee ?? 0),
                'admission_start_date' => $admission->admission_start_date ? $admission->admission_start_date->format('Y-m-d') : null,
                'admission_end_date' => $admission->admission_end_date ? $admission->admission_end_date->format('Y-m-d') : null,
                'admission_status' => $admission->admission_status ?? 'مفتوح',
            ],
            'documents' => $admission->docs->map(fn($d) => [
                'doc_id' => $d->doc_id,
                'document_name' => $d->document_name,
                'name' => $d->document_name,
                'description' => $d->description,
                'is_required' => (bool)$d->is_required,
            ]),
        ]);
    }

    /**
     * تحديث بيانات وشروط القبول والتسجيل والرسوم وفترة القبول لتخصص محدد
     */
    public function updateSpecializationAdmission(Request $request, $id)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $spec = Specialization::whereHas('college', fn($q) => $q->where('university_id', $university->university_id))
            ->findOrFail($id);

        $request->validate([
            'minimum_average' => 'required|numeric|between:50,100',
            'secondary_last_year' => 'required|integer|between:2000,2035',
            'tuition_fees' => 'nullable|numeric|min:0',
            'registration_fee' => 'nullable|numeric|min:0',
            'admission_start_date' => 'nullable|date',
            'admission_end_date' => 'nullable|date|after_or_equal:admission_start_date',
            'admission_status' => 'required|string|in:مفتوح,مغلق,قريباً',
            'admission_data' => 'required|string|max:5000',
            'description' => 'nullable|string|max:2000',
        ]);

        if ($request->filled('tuition_fees')) {
            $spec->update(['tuition_fees' => $request->tuition_fees]);
        }

        $admission = $spec->admissionData;
        $data = [
            'minimum_average' => $request->minimum_average,
            'secondary_last_year' => $request->secondary_last_year,
            'tuition_fees' => $request->tuition_fees ?? $spec->tuition_fees,
            'registration_fee' => $request->registration_fee ?? 0,
            'admission_start_date' => $request->admission_start_date,
            'admission_end_date' => $request->admission_end_date,
            'admission_status' => $request->admission_status,
            'admission_data' => $request->admission_data,
            'description' => $request->description,
        ];

        if ($admission) {
            $admission->update($data);
        } else {
            $data['required_documents'] = [];
            $admission = $spec->admissionData()->create($data);
        }

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حفظ بيانات وشروط القبول والتسجيل للتخصص (' . $spec->specialization_name . ') بنجاح.',
                'admission' => $admission->fresh(),
            ]);
        }

        return back()->with('success', 'تم حفظ بيانات القبول والتسجيل بنجاح.');
    }

    /**
     * حفظ قائمة الوثائق المطلوبة للتسجيل لتخصص محدد
     */
    public function saveSpecializationDocuments(Request $request, $id)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $spec = Specialization::whereHas('college', fn($q) => $q->where('university_id', $university->university_id))
            ->findOrFail($id);

        $request->validate([
            'documents' => 'present|array',
            'documents.*.name' => 'nullable|string|max:150',
            'documents.*.document_name' => 'nullable|string|max:150',
            'documents.*.description' => 'nullable|string|max:500',
            'documents.*.is_required' => 'nullable',
        ]);

        $admission = $spec->admissionData;
        if (!$admission) {
            $admission = $spec->admissionData()->create([
                'admission_data' => 'شروط القبول والتسجيل المعتمدة',
                'minimum_average' => 70.00,
                'secondary_last_year' => 2025,
                'tuition_fees' => $spec->tuition_fees,
                'registration_fee' => 5000.00,
                'admission_start_date' => '2026-06-01',
                'admission_end_date' => '2026-09-30',
                'admission_status' => 'مفتوح',
                'required_documents' => [],
            ]);
        }

        $docsInput = $request->input('documents', []);
        $admission->syncDocuments($docsInput);

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم اعتماد وحفظ قائمة الوثائق المطلوبة للتخصص (' . $spec->specialization_name . ') بنجاح.',
                'documents' => $admission->docs,
            ]);
        }

        return back()->with('success', 'تم اعتماد قائمة الوثائق المطلوبة بنجاح.');
    }

    /**
     * إضافة وثيقة مطلوبة جديدة لتخصص محدد
     */
    public function addSpecializationDocument(Request $request, $id)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $spec = Specialization::whereHas('college', fn($q) => $q->where('university_id', $university->university_id))
            ->findOrFail($id);

        $request->validate([
            'document_name' => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
            'is_required' => 'nullable|boolean',
        ]);

        $admission = $spec->admissionData;
        if (!$admission) {
            $admission = $spec->admissionData()->create([
                'admission_data' => 'شروط القبول والتسجيل المعتمدة',
                'minimum_average' => 70.00,
                'secondary_last_year' => 2025,
                'tuition_fees' => $spec->tuition_fees,
                'registration_fee' => 5000.00,
                'admission_start_date' => '2026-06-01',
                'admission_end_date' => '2026-09-30',
                'admission_status' => 'مفتوح',
                'required_documents' => [],
            ]);
        }

        $admission->update([
            'document_name' => trim($request->document_name),
            'description' => $request->description ? trim($request->description) : $admission->description,
            'is_required' => $request->boolean('is_required', true),
        ]);

        $createdDoc = AdmissionDoc::create([
            'admission_id' => $admission->admission_id,
            'document_name' => trim($request->document_name),
            'description' => $request->description ? trim($request->description) : null,
            'is_required' => $request->boolean('is_required', true),
        ]);

        $newDoc = (object)[
            'doc_id' => $createdDoc->doc_id,
            'document_name' => $createdDoc->document_name,
            'name' => $createdDoc->document_name,
            'description' => $createdDoc->description,
            'is_required' => (bool)$createdDoc->is_required,
        ];

        return response()->json([
            'success' => true,
            'message' => 'تمت إضافة الوثيقة (' . $newDoc->document_name . ') بنجاح.',
            'document' => $newDoc,
            'all_documents' => $admission->docs,
        ]);
    }

    /**
     * حذف وثيقة مطلوبة تتبع لتخصص في الجامعة
     */
    public function deleteSpecializationDocument(Request $request, $docId)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $doc = AdmissionDoc::whereHas('admissionData.specialization.college', function ($q) use ($university) {
            $q->where('university_id', $university->university_id);
        })->find($docId);

        if ($doc) {
            $docName = $doc->document_name;
            $doc->delete();
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الوثيقة (' . $docName . ') بنجاح.',
            ]);
        }

        $admission = AdmissionData::whereHas('specialization.college', function ($q) use ($university) {
            $q->where('university_id', $university->university_id);
        })->where('admission_id', $docId)->first();

        if ($admission) {
            $docName = $admission->document_name ?? 'الوثيقة';
            $admission->update(['document_name' => null, 'is_required' => false]);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الوثيقة (' . $docName . ') بنجاح.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'لم يتم العثور على الوثيقة.',
        ], 404);
    }

    /**
     * حذف تخصص أكاديمي
     */
        /**
     * استرجاع كافة الخطط الدراسية لتخصص محدد مع فصولها ومقرراتها
     */
    public function getSpecializationPlans(Request $request, $id)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $spec = Specialization::whereHas('college', fn($q) => $q->where('university_id', $university->university_id))
            ->with(['studyPlans.semesters.courses'])
            ->findOrFail($id);

        $plans = $spec->studyPlans->map(function ($plan) {
            return [
                'study_plan_id' => $plan->study_plan_id,
                'plan_name' => $plan->plan_name,
                'duration_years' => $plan->duration_years ?? 4,
                'total_hours' => $plan->total_hours ?? 0,
                'status' => $plan->status ?? 'معتمدة',
                'semesters_count' => $plan->semesters->count(),
                'courses_count' => $plan->semesters->sum(fn($s) => $s->courses->count()),
                'semesters' => $plan->semesters->sortBy('semester_number')->values()->map(function ($sem) {
                    return [
                        'semester_id' => $sem->semester_id,
                        'semester_number' => $sem->semester_number,
                        'courses_count' => $sem->courses->count(),
                        'hours_sum' => $sem->courses->sum('hours'),
                        'courses' => $sem->courses->map(function ($c) {
                            return [
                                'course_id' => $c->course_id,
                                'course_name' => $c->course_name,
                                'course_code' => $c->course_code ?? '',
                                'hours' => $c->hours ?? 3,
                                'course_type' => $c->course_type ?? 'متطلب تخصص',
                                'description' => $c->description ?? '',
                            ];
                        }),
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'specialization_id' => $spec->specialization_id,
            'specialization_name' => $spec->specialization_name,
            'duration_years' => $spec->duration_years,
            'plans' => $plans,
        ]);
    }

    /**
     * إضافة خطة دراسية جديدة للتخصص
     */
    public function storeStudyPlan(Request $request, $id)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $spec = Specialization::whereHas('college', fn($q) => $q->where('university_id', $university->university_id))
            ->findOrFail($id);

        $request->validate([
            'plan_name' => 'required|string|max:200',
            'duration_years' => 'required|integer|between:1,8',
            'total_hours' => 'nullable|integer|min:0',
            'status' => 'nullable|string|max:50',
        ]);

        $durationYears = (int)$request->duration_years;
        $totalHours = $request->filled('total_hours') ? (int)$request->total_hours : ($durationYears * 34);

        $plan = StudyPlan::create([
            'specialization_id' => $spec->specialization_id,
            'plan_name' => trim($request->plan_name),
            'duration_years' => $durationYears,
            'total_hours' => $totalHours,
            'status' => $request->status ?? 'معتمدة',
        ]);

        // إنشاء الفصول الدراسية تلقائياً بحسب عدد السنوات (فصلين لكل سنة)
        $semestersCount = $durationYears * 2;
        for ($i = 1; $i <= $semestersCount; $i++) {
            $plan->semesters()->create([
                'semester_number' => $i,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تمت إضافة الخطة الدراسية (' . $plan->plan_name . ') بنجاح.',
            'plan' => $plan->load('semesters.courses'),
        ]);
    }

    /**
     * تعديل بيانات الخطة الدراسية
     */
    public function updateStudyPlan(Request $request, $planId)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $plan = StudyPlan::whereHas('specialization.college', fn($q) => $q->where('university_id', $university->university_id))
            ->with('semesters')
            ->findOrFail($planId);

        $request->validate([
            'plan_name' => 'required|string|max:200',
            'duration_years' => 'required|integer|between:1,8',
            'total_hours' => 'nullable|integer|min:0',
            'status' => 'nullable|string|max:50',
        ]);

        $newDuration = (int)$request->duration_years;

        $plan->update([
            'plan_name' => trim($request->plan_name),
            'duration_years' => $newDuration,
            'total_hours' => $request->filled('total_hours') ? (int)$request->total_hours : $plan->total_hours,
            'status' => $request->status ?? $plan->status,
        ]);

        // مزامنة الفصول الدراسية إذا زادت المدة
        $expectedSemesters = $newDuration * 2;
        $existingSemesters = $plan->semesters->pluck('semester_number')->toArray();
        for ($i = 1; $i <= $expectedSemesters; $i++) {
            if (!in_array($i, $existingSemesters)) {
                $plan->semesters()->create(['semester_number' => $i]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث بيانات الخطة الدراسية بنجاح.',
            'plan' => $plan->fresh(['semesters.courses']),
        ]);
    }

    /**
     * حذف خطة دراسية بالكامل مع فصولها ومقرراتها
     */
    public function deleteStudyPlan(Request $request, $planId)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $plan = StudyPlan::whereHas('specialization.college', fn($q) => $q->where('university_id', $university->university_id))
            ->with('semesters.courses')
            ->findOrFail($planId);

        $name = $plan->plan_name;

        // حذف المقررات ثم الفصول ثم الخطة
        foreach ($plan->semesters as $sem) {
            $sem->courses()->delete();
        }
        $plan->semesters()->delete();
        $plan->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الخطة الدراسية (' . $name . ') بنجاح.',
        ]);
    }

    /**
     * إضافة أو تعديل مادة دراسية ضمن فصل في الخطة
     */
    public function savePlanCourse(Request $request, $planId)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $plan = StudyPlan::whereHas('specialization.college', fn($q) => $q->where('university_id', $university->university_id))
            ->findOrFail($planId);

        $request->validate([
            'semester_number' => 'required|integer|min:1|max:16',
            'course_name' => 'required|string|max:150',
            'course_code' => 'nullable|string|max:50',
            'hours' => 'required|integer|between:1,12',
            'course_type' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'course_id' => 'nullable|integer',
        ]);

        $semester = $plan->semesters()->firstOrCreate(
            ['semester_number' => (int)$request->semester_number],
            ['study_plan_id' => $plan->study_plan_id]
        );

        if ($request->filled('course_id')) {
            $course = Course::where('semester_id', $semester->semester_id)->findOrFail($request->course_id);
            $course->update([
                'course_name' => trim($request->course_name),
                'course_code' => $request->course_code ? trim($request->course_code) : null,
                'hours' => (int)$request->hours,
                'course_type' => $request->course_type ?? 'متطلب تخصص',
                'description' => $request->description ? trim($request->description) : null,
            ]);
            $msg = 'تم تعديل المادة الدراسية (' . $course->course_name . ') بنجاح.';
        } else {
            $course = $semester->courses()->create([
                'course_name' => trim($request->course_name),
                'course_code' => $request->course_code ? trim($request->course_code) : null,
                'hours' => (int)$request->hours,
                'course_type' => $request->course_type ?? 'متطلب تخصص',
                'description' => $request->description ? trim($request->description) : null,
            ]);
            $msg = 'تمت إضافة المادة الدراسية (' . $course->course_name . ') إلى الفصل ' . $request->semester_number . ' بنجاح.';
        }

        // تحديث إجمالي الساعات
        $totalHours = Course::whereIn('semester_id', $plan->semesters()->pluck('semester_id'))->sum('hours');
        if ($totalHours > 0) {
            $plan->update(['total_hours' => $totalHours]);
        }

        return response()->json([
            'success' => true,
            'message' => $msg,
            'course' => $course,
            'semester_number' => $semester->semester_number,
            'total_hours' => $plan->total_hours,
        ]);
    }

    /**
     * حذف مادة دراسية من الخطة
     */
    public function deletePlanCourse(Request $request, $courseId)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $course = Course::whereHas('semester.studyPlan.specialization.college', function ($q) use ($university) {
            $q->where('university_id', $university->university_id);
        })->with('semester.studyPlan')->findOrFail($courseId);

        $plan = $course->semester->studyPlan;
        $name = $course->course_name;
        $course->delete();

        // إعادة احتساب إجمالي الساعات
        $totalHours = Course::whereIn('semester_id', $plan->semesters()->pluck('semester_id'))->sum('hours');
        $plan->update(['total_hours' => $totalHours]);

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المادة (' . $name . ') بنجاح.',
            'total_hours' => $totalHours,
        ]);
    }


public function deleteSpecialization($id)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $spec = Specialization::whereHas('college', fn($q) => $q->where('university_id', $university->university_id))->findOrFail($id);
        $name = $spec->specialization_name;
        $spec->delete();

        if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف التخصص (' . $name . ') بنجاح.',
            ]);
        }

        return back()->with('success', 'تم حذف التخصص بنجاح.');
    }

    /**
     * التحقق من كود الإحالة وتفعيله/اعتماده
     */
    public function verifyReferralCode(Request $request)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $request->validate([
            'referral_code' => 'required|string|max:50',
        ]);

        $code = trim($request->referral_code);

        $referral = StudentReferral::where('university_id', $university->university_id)
            ->where(function ($q) use ($code) {
                $q->where('referral_code', $code)->orWhere('ref_code', $code);
            })
            ->with('student')
            ->first();

        if (!$referral) {
            return response()->json([
                'success' => false,
                'message' => 'كود الإحالة (' . $code . ') غير صحيح أو غير مخصص لهذه الجامعة.',
            ], 404);
        }

        if ($referral->status === 'مستخدم' || $referral->ref_status === 'مستخدم') {
            return response()->json([
                'success' => false,
                'message' => 'هذا الكود تم استخدامه واعتماده مسبقاً للطالب (' . ($referral->student->full_name ?? 'طالب') . ').',
                'referral' => $referral,
            ], 422);
        }

        // تفعيل الكود
        $referral->update([
            'status' => 'مستخدم',
            'ref_status' => 'مستخدم',
            'notes' => 'تم اعتماده بتاريخ ' . now()->toDateString(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم التحقق بنجاح واعتماد كود الإحالة للطالب: ' . ($referral->student->full_name ?? 'طالب') . ' مع استحقاق خصم التنسيق الجامعي.',
            'referral' => $referral,
        ]);
    }

    /**
     * نشر إعلان جديد
     */
    public function storeAnnouncement(Request $request)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'category' => 'nullable|string|max:50',
        ]);

        $announcement = $university->announcements()->create([
            'adv_title' => $request->title,
            'adv_content' => $request->content,
            'package' => $request->category ?: 'عام',
            'duration' => 30,
            'amount' => 0.00,
            'pay_method' => 'مجاني',
            'pay_reference' => 'INTERNAL',
            'review_status' => 'معتمد',
            'status' => 'نشط',
            'created_at' => now(),
        ]);

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم نشر الإعلان بنجاح.',
                'announcement' => $announcement,
            ]);
        }

        return back()->with('success', 'تم نشر الإعلان بنجاح.');
    }

    /**
     * حذف إعلان
     */
    public function deleteAnnouncement($id)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $ann = $university->announcements()->findOrFail($id);
        $ann->delete();

        if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الإعلان بنجاح.',
            ]);
        }

        return back()->with('success', 'تم حذف الإعلان بنجاح.');
    }

    /**
     * إضافة طريقة دفع جديدة
     */
    public function storePaymentMethod(Request $request)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $methodName = $request->input('method_name') ?? $request->input('provider_name');
        $accountName = $request->input('account_name') ?? $request->input('account_holder');

        $request->merge([
            'method_name' => $methodName,
            'account_name' => $accountName,
        ]);

        $request->validate([
            'method_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:100',
            'account_name' => 'nullable|string|max:150',
            'instructions' => 'nullable|string|max:500',
        ]);

        $pm = $university->paymentMethods()->create([
            'method_name' => $methodName,
            'method_type' => $request->method_type ?? 'تحويل بنكي',
            'account_number' => $request->account_number,
            'account_name' => $accountName,
            'alternative_number' => $request->alternative_number,
            'instructions' => $request->instructions,
            'is_active' => true,
        ]);

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تمت إضافة وسيلة الدفع بنجاح.',
                'payment_method' => $pm,
            ]);
        }

        return back()->with('success', 'تمت إضافة وسيلة الدفع بنجاح.');
    }

    /**
     * تبديل تفعيل طريقة دفع
     */
    public function togglePaymentMethod($id)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $pm = $university->paymentMethods()->findOrFail($id);
        $pm->update(['is_active' => !$pm->is_active]);

        if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث حالة طريقة الدفع بنجاح.',
                'is_active' => $pm->is_active,
            ]);
        }

        return back()->with('success', 'تم تحديث حالة طريقة الدفع بنجاح.');
    }

    /**
     * حذف طريقة دفع
     */
    public function deletePaymentMethod($id)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $pm = $university->paymentMethods()->findOrFail($id);
        $pm->delete();

        if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف طريقة الدفع بنجاح.',
            ]);
        }

        return back()->with('success', 'تم حذف طريقة الدفع بنجاح.');
    }

    /**
     * تصدير طلبات التسجيل إلى ملف CSV مع ترميز UTF-8 لدعم اللغة العربية في إكسل
     */
    public function exportRequestsCsv()
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $requests = $university->regRequests()->where('status', '!=', 'مسودة')->latest('req_id')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="university_requests_' . date('Y-m-d') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return new StreamedResponse(function () use ($requests) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM so Excel opens Arabic correctly
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'رقم الطلب',
                'اسم الطالب',
                'الجنس',
                'المحافظة',
                'المدرسة الثانوية',
                'رقم الجلوس',
                'سنة التخرج',
                'القسم',
                'معدل الثانوية (%)',
                'التخصص المطلوب',
                'رقم الهاتف',
                'البريد الإلكتروني',
                'اسم ولي الأمر',
                'هاتف ولي الأمر',
                'تاريخ التقديم',
                'حالة الطلب',
            ]);

            foreach ($requests as $r) {
                fputcsv($handle, [
                    $r->req_id,
                    $r->full_name,
                    $r->gender,
                    $r->province,
                    $r->secondary_school,
                    $r->seat_number,
                    $r->grad_year,
                    $r->sec_section,
                    $r->sec_avg,
                    $r->desired_spec,
                    $r->phone,
                    $r->email,
                    $r->guardian_name,
                    $r->gu_phone,
                    $r->req_date ? $r->req_date->format('Y-m-d H:i') : '',
                    $r->status,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * رفع وإرفاق سند سداد رسوم التنسيق لطلب قائم من قِبل الطالب
     */
    public function submitApplicationPaymentProof(Request $request, $id)
    {
        $user = Auth::user();
        $student = $user->student;
        if (!$student) {
            abort(403, 'يجب تسجيل الدخول كطالب لرفع سند السداد.');
        }

        $regReq = $student->regRequests()->findOrFail($id);

        $request->validate([
            'method_id' => 'required|exists:pay_methods,method_id',
            'reference' => 'required|string|max:150',
            'amount' => 'nullable|numeric|min:0',
            'pay_date' => 'nullable|date',
            'proof_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'notes' => 'nullable|string|max:500',
        ]);

        $proofPath = null;
        if ($request->hasFile('proof_file')) {
            $file = $request->file('proof_file');
            $ext = $file->getClientOriginalExtension();
            $proofPath = $file->storeAs('payments', time() . '_' . uniqid() . '.' . $ext, 'public');
        }

        // احتساب رسوم التسجيل الرسمية المقررة من الجامعة للتخصص تلقائياً
        $officialFee = 5000;
        if ($regReq->university_id && $regReq->desired_spec) {
            $specModel = Specialization::where('specialization_name', $regReq->desired_spec)
                ->whereHas('college', function ($q) use ($regReq) {
                    $q->where('university_id', $regReq->university_id);
                })
                ->first();
            if ($specModel && $specModel->admission && $specModel->admission->registration_fee) {
                $officialFee = (float)$specModel->admission->registration_fee;
            }
        }

        $payment = Payment::create([
            'req_id' => $regReq->req_id,
            'method_id' => $request->method_id,
            'amount' => $officialFee,
            'pay_date' => $request->pay_date ?? now()->toDateString(),
            'reference' => $request->reference,
            'proof_file' => $proofPath,
            'ver_status' => 'قيد التحقق',
            'notes' => $request->notes,
        ]);

        if ($regReq->university && $regReq->university->user_id) {
            Notification::create([
                'user_id' => $regReq->university->user_id,
                'notification_type' => 'payment_proof_submitted',
                'title' => 'إشعار سداد جديد للطلب #' . $regReq->req_id,
                'message' => 'قام الطالب ' . $regReq->full_name . ' برفع سند سداد رسوم التنسيق برقم عملية (' . $payment->reference . ') لتخصص ' . $regReq->desired_spec,
                'is_read' => false,
                'created_at' => now(),
            ]);
        }

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم توثيق ورفع سند السداد بنجاح، والطلب الآن قيد التدقيق المالي من قِبل الجامعة.',
                'payment' => $payment,
            ]);
        }

        return back()->with('success', 'تم رفع سند السداد بنجاح، جاري التدقيق والمطابقة من إدارة الجامعة.');
    }

    /**
     * تدقيق واعتماد أو رفض سند السداد المالي من قبل إدارة الجامعة
     */
    public function verifyRequestPayment(Request $request, $id)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $regReq = $university->regRequests()->findOrFail($id);
        $payment = $regReq->payments()->latest('payment_id')->first();
        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'لا توجد بيانات سداد مسجلة لهذا الطلب حتى الآن.',
            ], 404);
        }

        $request->validate([
            'ver_status' => 'required|in:معتمد ومؤكد,مرفوض',
            'ver_result' => 'nullable|string|max:255',
        ]);

        $payment->update([
            'ver_status' => $request->ver_status,
            'ver_date' => now(),
            'ver_result' => $request->ver_result,
        ]);

        if ($regReq->student && $regReq->student->user_id) {
            $isApproved = ($request->ver_status === 'معتمد ومؤكد');
            Notification::create([
                'user_id' => $regReq->student->user_id,
                'notification_type' => $isApproved ? 'payment_approved' : 'payment_rejected',
                'title' => $isApproved ? 'تم اعتماد سند السداد المالي' : 'ملاحظة على سند السداد المالي',
                'message' => $isApproved
                    ? 'تم التحقق من سند سداد رسوم التنسيق لطلبك #' . $regReq->req_id . ' واعتماده بنجاح من الشؤون المالية لدى ' . $university->university_name . '.'
                    : 'تعذر اعتماد سند السداد لطلبك #' . $regReq->req_id . '. السبب: ' . ($request->ver_result ?? 'سند غير مطابق أو غير واضح') . '. يرجى إعادة رفع السند الصحيح من لوحة تحكمك.',
                'is_read' => false,
                'created_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة التدقيق المالي بنجاح.',
            'ver_status' => $payment->ver_status,
            'ver_result' => $payment->ver_result,
        ]);
    }

    /**
     * تحديث بيانات وسيلة الدفع الجامعية
     */
    public function updatePaymentMethod(Request $request, $id)
    {
        $university = Auth::user()->university;
        if (!$university) {
            abort(403);
        }

        $pm = $university->paymentMethods()->findOrFail($id);

        $request->validate([
            'method_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:100',
            'method_type' => 'nullable|string|max:50',
            'account_name' => 'nullable|string|max:150',
            'alternative_number' => 'nullable|string|max:50',
            'instructions' => 'nullable|string|max:500',
        ]);

        $pm->update([
            'method_name' => $request->method_name,
            'method_type' => $request->method_type ?? $pm->method_type,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name ?? $pm->account_name,
            'alternative_number' => $request->alternative_number ?? $pm->alternative_number,
            'instructions' => $request->instructions ?? $pm->instructions,
        ]);

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث وسيلة الدفع بنجاح.',
                'payment_method' => $pm,
            ]);
        }

        return back()->with('success', 'تم تحديث وسيلة الدفع بنجاح.');
    }

    /**
     * إرسال تعديلات وبيان تصحيح من الجامعة لمدير النظام بخصوص عنصر محجوب رقابياً
     */
    public function submitModerationCorrection(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || !$user->isUniversity() || !$user->university) {
            return response()->json(['success' => false, 'message' => 'غير مصرح'], 403);
        }

        $mod = \App\Models\ContentModeration::where('moderation_id', $id)
            ->where('university_id', $user->university->university_id)
            ->firstOrFail();

        $university = $user->university;
        $updatedDetails = [];

        // 1. معالجة وتطبيق التعديل بحسب نوع العنصر المحجوب مباشرة من النافذة
        if ($mod->item_type === 'logo' && $request->hasFile('new_logo')) {
            $request->validate([
                'new_logo' => 'file|image|mimes:jpeg,png,jpg,webp,svg|max:5120',
            ]);
            $file = $request->file('new_logo');
            $fileName = 'logo_' . $university->university_id . '_' . time() . '.' . $file->getClientOriginalExtension();
            if (!file_exists(public_path('uploads/logos'))) {
                mkdir(public_path('uploads/logos'), 0777, true);
            }
            $file->move(public_path('uploads/logos'), $fileName);
            $university->logo = 'uploads/logos/' . $fileName;
            $university->save();
            $updatedDetails[] = 'تم رفع وتعديل الشعار الرسمي للجامعة مباشرة من نافذة التوجيهات: uploads/logos/' . $fileName;
        } elseif ($mod->item_type === 'campus_image' && $request->hasFile('new_campus_image')) {
            $request->validate([
                'new_campus_image' => 'file|image|mimes:jpeg,png,jpg,webp|max:10240',
            ]);
            $file = $request->file('new_campus_image');
            $fileName = 'campus_' . $university->university_id . '_' . time() . '.' . $file->getClientOriginalExtension();
            if (!file_exists(public_path('uploads/campus'))) {
                mkdir(public_path('uploads/campus'), 0777, true);
            }
            $file->move(public_path('uploads/campus'), $fileName);
            $university->campus_image = 'uploads/campus/' . $fileName;
            $university->save();
            $updatedDetails[] = 'تم رفع وتعديل صورة الحرم الجامعي.';
        } elseif ($mod->item_type === 'description' && $request->filled('new_description')) {
            $university->description = $request->input('new_description');
            $university->save();
            $updatedDetails[] = 'تم تحديث النبذة والوصف التعريفي للجامعة.';
        } elseif ($mod->item_type === 'contact_info') {
            if ($request->filled('new_phone')) $university->phone = $request->input('new_phone');
            if ($request->filled('new_email')) $university->email = $request->input('new_email');
            if ($request->filled('new_website')) $university->website = $request->input('new_website');
            $university->save();
            $updatedDetails[] = 'تم تصحيح بيانات الاتصال والتواصل.';
        } elseif ($mod->item_type === 'college' && $mod->item_id) {
            $col = \App\Models\College::where('college_id', $mod->item_id)->where('university_id', $university->university_id)->first();
            if ($col) {
                if ($request->filled('new_college_name')) $col->college_name = $request->input('new_college_name');
                if ($request->filled('new_college_desc')) $col->description = $request->input('new_college_desc');
                $col->save();
                $updatedDetails[] = 'تم تعديل بيانات الكلية (' . $col->college_name . ').';
            }
        } elseif ($mod->item_type === 'specialization' && $mod->item_id) {
            $spec = \App\Models\Specialization::where('specialization_id', $mod->item_id)->first();
            if ($spec) {
                if ($request->filled('new_spec_name')) $spec->specialization_name = $request->input('new_spec_name');
                if ($request->filled('new_min_percentage')) $spec->min_percentage = $request->input('new_min_percentage');
                if ($request->filled('new_study_years')) $spec->study_years = $request->input('new_study_years');
                if ($request->filled('new_annual_fees')) $spec->annual_fees = $request->input('new_annual_fees');
                $spec->save();
                $updatedDetails[] = 'تم تصحيح بيانات وشروط التخصص (' . $spec->specialization_name . ').';
            }
        } elseif ($mod->item_type === 'announcement' && $mod->item_id) {
            $ann = \App\Models\Announcement::where('announcement_id', $mod->item_id)->where('university_id', $university->university_id)->first();
            if ($ann) {
                if ($request->filled('new_ann_title')) $ann->title = $request->input('new_ann_title');
                if ($request->filled('new_ann_content')) $ann->content = $request->input('new_ann_content');
                $ann->save();
                $updatedDetails[] = 'تم تعديل عنوان ومحتوى الإعلان الأكاديمي.';
            }
        }

        $notes = $request->input('correction_notes', '');
        if (!empty($updatedDetails)) {
            $combinedNotes = implode("\n", $updatedDetails) . ($notes ? "\n--- ملاحظات وإيضاحات الجامعة ---\n" . $notes : '');
        } else {
            $combinedNotes = $notes;
        }

        if (empty(trim($combinedNotes))) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى إرفاق التعديل المطلوب أو كتابة توضيح لمدير النظام.'
            ], 422);
        }

        $mod->update([
            'correction_notes' => trim($combinedNotes),
            'status' => 'قيد_المراجعة',
            'updated_at' => now(),
        ]);

        // إشعار المشرفين بوجود تعديلات واردة من الجامعة
        try {
            \App\Models\Notification::create([
                'user_id' => 1, // Super Admin
                'notification_type' => 'moderation_review_requested',
                'title' => 'تعديل وارد بانتظار الفحص من ' . $university->university_name,
                'message' => "قامت جامعة {$university->university_name} بإرسال تعديلات وتوضيحات بخصوص العنصر المحجوب ({$mod->item_title}):\n{$combinedNotes}",
                'link' => route('admin.moderation'),
                'is_read' => false,
            ]);
        } catch (\Throwable $e) {}

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إرسال التعديلات لمدير النظام بنجاح. سيتم مراجعتها والبت فيها قريباً.',
            ]);
        }

        return back()->with('success', 'تم إرسال التعديلات لمدير النظام بنجاح. سيتم مراجعتها والبت فيها قريباً.');
    }



    // =========================================================================
    //  معالج وبوابة الإعلانات الممولة للجامعة (Sponsored Advertisements Wizard)
    // =========================================================================

    /**
     * عرض قائمة إعلانات الجامعة مع إحصائياتها
     */
    public function universityAdvertisements()
    {
        $user = Auth::user();
        if (!$user || !$user->isUniversity() || !$user->university) {
            abort(403, 'لا يوجد ملف جامعة مرتبط بهذا الحساب.');
        }
        $university = $user->university;

        $ads = Advertisement::where('university_id', $university->university_id)
            ->latest('adv_id')
            ->get();

        $stats = [
            'total'      => $ads->count(),
            'active'     => $ads->where('status', 'نشط')->count(),
            'pending'    => $ads->where('review_status', 'قيد المراجعة')->count(),
            'draft'      => $ads->where('review_status', 'مسودة')->count(),
            'needs_edit' => $ads->where('review_status', 'مطلوب تعديل')->count(),
            'rejected'   => $ads->where('review_status', 'مرفوض')->count(),
            'expired'    => $ads->where('status', 'منتهي')->count(),
        ];

        // مسودة معلقة غير مكتملة
        $pendingDraft = Advertisement::where('university_id', $university->university_id)
            ->where('review_status', 'مسودة')
            ->latest('adv_id')
            ->first();

        // الإعلانات النشطة والمنتهية لحاوية رأس الصفحة
        $activeAds = $ads->filter(function($a) {
            return $a->status === 'نشط' && $a->isActive();
        })->values();

        $expiredAds = $ads->filter(function($a) {
            return $a->status === 'منتهي' || $a->isExpired();
        })->values();

        $paymentAccounts = [
            'kuraimi' => [
                'name'        => 'بنك الكريمي للتمويل الأصغر الإسلامي',
                'account'     => \App\Models\SystemSetting::get('platform_kuraimi_account', '120456789'),
                'beneficiary' => \App\Models\SystemSetting::get('platform_kuraimi_name', 'منصة إديو يمن - EduYemen للخدمات التعليمية'),
            ],
            'onecash' => [
                'name'        => 'محفظة ون كاش التجارية (OneCash)',
                'account'     => \App\Models\SystemSetting::get('platform_onecash_account', '777000123'),
                'beneficiary' => \App\Models\SystemSetting::get('platform_onecash_name', 'EduYemen - إديو يمن'),
            ],
            'bank' => [
                'name'        => \App\Models\SystemSetting::get('platform_bank_name', 'بنك التضامن الإسلامي الدولي'),
                'iban'        => \App\Models\SystemSetting::get('platform_bank_iban', 'YE2900040000000123456789'),
                'beneficiary' => \App\Models\SystemSetting::get('platform_bank_beneficiary', 'شركة إديو يمن لتقنية المعلومات والتعليم'),
            ],
        ];

        return view('dashboards.university.advertisements.index', compact('university', 'ads', 'stats', 'pendingDraft', 'activeAds', 'expiredAds', 'paymentAccounts'));
    }

    /**
     * معالج إنشاء إعلان جديد - المرحلة 1
     */
    public function advertisementWizard(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isUniversity() || !$user->university) {
            abort(403, 'لا يوجد ملف جامعة مرتبط بهذا الحساب.');
        }
        $university = $user->university;

        // دعم طلب تجديد إعلان منتهي (استنساخ المحتوى والتوجه فوراً لخطوة الدفع)
        if ($request->filled('renew_id')) {
            $sourceAd = Advertisement::where('university_id', $university->university_id)
                ->where('adv_id', $request->renew_id)
                ->first();

            if ($sourceAd) {
                $config = $sourceAd->getPackageConfig();
                $renewDraft = Advertisement::create([
                    'university_id' => $university->university_id,
                    'package'       => $sourceAd->package,
                    'duration'      => $config['duration'],
                    'amount'        => $config['amount'],
                    'adv_title'     => $sourceAd->adv_title,
                    'adv_content'   => $sourceAd->adv_content,
                    'media_type'    => $sourceAd->media_type,
                    'media_files'   => $sourceAd->media_files,
                    'video_url'     => $sourceAd->video_url,
                    'review_status' => 'مسودة',
                    'status'        => 'غير نشط',
                    'wizard_step'   => 2, // جاهز لمرحلة الدفع وسند التحويل
                    'created_at'    => now(),
                ]);

                return redirect()->route('university.advertisements.wizard', ['draft_id' => $renewDraft->adv_id])
                    ->with('success', 'تم إنشاء مسودة تجديد جديدة بالإبقاء على بيانات ووسائط إعلانك السابق. يرجى إرفاق سند السداد الجديد للمتابعة.');
            }
        }

        $draft = null;
        if ($request->filled('draft_id')) {
            $draft = Advertisement::where('university_id', $university->university_id)
                ->where('adv_id', $request->draft_id)
                ->first();
        }

        // إذا لم يُحدد مسودة ولم يطلب بدء إعلان جديد صراحةً
        if (!$draft && !$request->has('new')) {
            $pendingDraft = Advertisement::where('university_id', $university->university_id)
                ->where('review_status', 'مسودة')
                ->latest('adv_id')
                ->first();

            if ($pendingDraft) {
                return redirect()->route('university.advertisements.wizard', ['draft_id' => $pendingDraft->adv_id])
                    ->with('info', 'لديك مسودة إعلان معلقة غير مكتملة، يمكنك متابعة إكمالها أو النقر على "إعلان جديد" للبدء من جديد.');
            }
        }

        $editAd = null;
        if ($request->filled('edit_id')) {
            $editAd = Advertisement::where('university_id', $university->university_id)
                ->where('adv_id', $request->edit_id)
                ->where('review_status', 'مطلوب تعديل')
                ->first();
        }

        $paymentAccounts = [
            'kuraimi' => [
                'name'        => 'بنك الكريمي للتمويل الأصغر الإسلامي',
                'account'     => \App\Models\SystemSetting::get('platform_kuraimi_account', '120456789'),
                'beneficiary' => \App\Models\SystemSetting::get('platform_kuraimi_name', 'منصة إديو يمن - EduYemen للخدمات التعليمية'),
            ],
            'onecash' => [
                'name'        => 'محفظة ون كاش التجارية (OneCash)',
                'account'     => \App\Models\SystemSetting::get('platform_onecash_account', '777000123'),
                'beneficiary' => \App\Models\SystemSetting::get('platform_onecash_name', 'EduYemen - إديو يمن'),
            ],
            'bank' => [
                'name'        => \App\Models\SystemSetting::get('platform_bank_name', 'بنك التضامن الإسلامي الدولي'),
                'iban'        => \App\Models\SystemSetting::get('platform_bank_iban', 'YE2900040000000123456789'),
                'beneficiary' => \App\Models\SystemSetting::get('platform_bank_beneficiary', 'شركة إديو يمن لتقنية المعلومات والتعليم'),
            ],
        ];

        return view('dashboards.university.advertisements.wizard', compact('university', 'draft', 'editAd', 'paymentAccounts'));
    }

    /**
     * المعالج: الخطوة 2 - تثبيت الباقة وتخزين مسودة
     */
    public function advertisementWizardStep2(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isUniversity() || !$user->university) {
            return response()->json(['success' => false, 'message' => 'غير مصرح'], 403);
        }
        $university = $user->university;

        $request->validate([
            'package' => 'required|in:اقتصادية,مميزة,شاملة',
            'ad_id'   => 'nullable|integer',
        ], [
            'package.required' => 'يرجى اختيار إحدى الباقات الإعلانية المتاحة.',
            'package.in'       => 'الباقة المختارة غير معتمدة.',
        ]);

        $packages = [
            'اقتصادية' => ['duration' => 2, 'amount' => 5000],
            'مميزة'     => ['duration' => 4, 'amount' => 9000],
            'شاملة'     => ['duration' => 10, 'amount' => 20000],
        ];

        $pkg = $packages[$request->package];

        $ad = null;
        if ($request->filled('ad_id')) {
            $ad = Advertisement::where('university_id', $university->university_id)
                ->where('adv_id', $request->ad_id)
                ->first();

            if ($ad && in_array($ad->review_status, ['مسودة', 'مطلوب تعديل'])) {
                $ad->update([
                    'package'     => $request->package,
                    'duration'    => $pkg['duration'],
                    'amount'      => $pkg['amount'],
                    'wizard_step' => max((int)$ad->wizard_step, 1),
                ]);
            } else {
                $ad = null;
            }
        }

        if (!$ad) {
            $ad = Advertisement::create([
                'university_id' => $university->university_id,
                'adv_title'     => 'إعلان ' . $university->university_name . ' - ' . $request->package,
                'adv_content'   => '',
                'package'       => $request->package,
                'duration'      => $pkg['duration'],
                'amount'        => $pkg['amount'],
                'review_status' => 'مسودة',
                'status'        => 'غير نشط',
                'wizard_step'   => 1,
                'created_at'    => now(),
            ]);
        }

        return response()->json([
            'success'     => true,
            'ad_id'       => $ad->adv_id,
            'package'     => $ad->package,
            'duration'    => $ad->duration,
            'amount'      => $ad->amount,
            'amount_fmt'  => number_format($ad->amount),
            'wizard_step' => $ad->wizard_step,
            'message'     => 'تم اختيار الباقة وحفظ مسودة الإعلان بنجاح.',
        ]);
    }

    /**
     * المعالج: الخطوة 3 - تثبيت بيانات الدفع وسند التحويل
     */
    public function advertisementWizardStep3(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isUniversity() || !$user->university) {
            return response()->json(['success' => false, 'message' => 'غير مصرح'], 403);
        }
        $university = $user->university;

        $request->validate([
            'ad_id'         => 'required|integer|exists:advertisements,adv_id',
            'pay_method'    => 'required|string|max:100',
            'pay_reference' => 'required|string|max:150',
            'receipt'       => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ], [
            'pay_method.required'    => 'يرجى اختيار وسيلة الدفع التي استخدمتها.',
            'pay_reference.required' => 'يرجى إدخال رقم الحوالة أو إشعار التحويل البنكي.',
            'receipt.mimes'          => 'يجب أن يكون السند بصيغة JPG أو PNG أو WEBP أو PDF.',
            'receipt.max'            => 'الحد الأقصى لحجم ملف السند هو 5 ميجابايت.',
        ]);

        $ad = Advertisement::where('university_id', $university->university_id)
            ->findOrFail($request->ad_id);

        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '_' . Str::random(12) . '.' . $ext;

            // السندات تُرفع إلى storage/app/private/ads/receipts/{university_id}/
            $privateDir = storage_path("app/private/ads/receipts/{$university->university_id}");
            if (!is_dir($privateDir)) {
                mkdir($privateDir, 0755, true);
            }
            $file->move($privateDir, $filename);

            // نسخة في public/uploads/ads/receipts/{university_id}/ لتمكين الإدارة من المعاينة
            $publicDir = public_path("uploads/ads/receipts/{$university->university_id}");
            if (!is_dir($publicDir)) {
                mkdir($publicDir, 0755, true);
            }
            copy("{$privateDir}/{$filename}", "{$publicDir}/{$filename}");

            $ad->receipt_url = "uploads/ads/receipts/{$university->university_id}/{$filename}";
        }

        if (!$ad->receipt_url) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى إرفاق صورة سند إثبات الدفع للمتابعة إلى الخطوة التالية.',
            ], 422);
        }

        $ad->pay_method = $request->pay_method;
        $ad->pay_reference = $request->pay_reference;
        if ((int)$ad->wizard_step < 2) {
            $ad->wizard_step = 2;
        }
        $ad->save();

        return response()->json([
            'success'     => true,
            'ad_id'       => $ad->adv_id,
            'receipt_url' => asset($ad->receipt_url),
            'wizard_step' => $ad->wizard_step,
            'message'     => 'تم حفظ بيانات السداد وسند الدفع بنجاح.',
        ]);
    }

    /**
     * المعالج: الخطوة 4 - رفع محتوى الإعلان والوسائط
     */
    public function advertisementWizardStep4(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isUniversity() || !$user->university) {
            return response()->json(['success' => false, 'message' => 'غير مصرح'], 403);
        }
        $university = $user->university;

        $ad = Advertisement::where('university_id', $university->university_id)
            ->findOrFail($request->ad_id);

        $rules = [
            'ad_id'       => 'required|integer',
            'adv_title'   => 'required|string|min:5|max:75',
            'adv_content' => 'required|string|min:10|max:200',
            'media_type'  => 'required|in:image,gallery,video',
        ];

        $messages = [
            'adv_title.required'   => 'يرجى كتابة عنوان الإعلان.',
            'adv_title.min'        => 'عنوان الإعلان يجب ألا يقل عن 5 أحرف.',
            'adv_title.max'        => 'عنوان الإعلان لا يتجاوز 75 حرفاً.',
            'adv_content.required' => 'يرجى كتابة وصف ومحتوى الإعلان.',
            'adv_content.min'      => 'محتوى الإعلان يجب ألا يقل عن 10 أحرف.',
            'adv_content.max'      => 'محتوى الإعلان لا يتجاوز 200 حرف.',
        ];

        $existingMedia = $ad->mediaFilesArray();
        $hasExistingMedia = !empty($existingMedia);
        $hasExistingVideo = !empty($ad->video_url);

        if ($ad->package === 'اقتصادية') {
            $rules['media_type'] = 'in:image';
            $rules['image'] = ($hasExistingMedia ? 'nullable' : 'required') . '|file|mimes:jpg,jpeg,png,webp|max:2048';
            $messages['image.required'] = 'يرجى رفع صورة الإعلان (بحد أقصى 2 ميجابايت).';
            $messages['image.max'] = 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت للباقة الاقتصادية.';
        } elseif ($ad->package === 'مميزة') {
            $rules['media_type'] = 'in:image,gallery';
            $rules['images'] = ($hasExistingMedia ? 'nullable' : 'required') . '|array|min:1|max:3';
            $rules['images.*'] = 'file|mimes:jpg,jpeg,png,webp|max:3072';
            $messages['images.required'] = 'يرجى رفع من 1 إلى 3 صور للإعلان.';
            $messages['images.max'] = 'الباقة المميزة تتيح رفع 3 صور كحد أقصى.';
        } elseif ($ad->package === 'شاملة') {
            if ($request->media_type === 'video') {
                $rules['video'] = ($hasExistingVideo ? 'nullable' : 'required') . '|file|mimes:mp4,webm|max:15360';
                $messages['video.required'] = 'يرجى رفع الفيديو الترويجي للإعلان.';
                $messages['video.max'] = 'حجم الفيديو يجب ألا يتجاوز 15 ميجابايت (10 ثوانٍ كحد أقصى).';
            } else {
                $rules['images'] = ($hasExistingMedia ? 'nullable' : 'required') . '|array|min:1|max:5';
                $rules['images.*'] = 'file|mimes:jpg,jpeg,png,webp|max:4096';
                $messages['images.required'] = 'يرجى رفع صور الإعلان (حتى 5 صور).';
                $messages['images.max'] = 'الباقة الشاملة تتيح رفع 5 صور كحد أقصى.';
            }
        }

        $request->validate($rules, $messages);

        $imgDir = public_path("uploads/ads/images/{$university->university_id}");
        if (!is_dir($imgDir)) {
            mkdir($imgDir, 0755, true);
        }

        if ($ad->package === 'اقتصادية') {
            $ad->media_type = 'image';
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . Str::random(12) . '.' . $file->getClientOriginalExtension();
                $file->move($imgDir, $filename);
                $ad->media_files = ["uploads/ads/images/{$university->university_id}/{$filename}"];
            }
            $ad->video_url = null;
        } elseif ($request->media_type === 'video' && $ad->package === 'شاملة') {
            $ad->media_type = 'video';
            if ($request->hasFile('video')) {
                $vidDir = public_path("uploads/ads/videos/{$university->university_id}");
                if (!is_dir($vidDir)) {
                    mkdir($vidDir, 0755, true);
                }
                $videoFile = $request->file('video');
                $filename = time() . '_' . Str::random(12) . '.' . $videoFile->getClientOriginalExtension();
                $videoFile->move($vidDir, $filename);
                $ad->video_url = "uploads/ads/videos/{$university->university_id}/{$filename}";
            }
            $ad->media_files = null;
        } else {
            $ad->media_type = ($ad->package === 'مميزة' && $request->hasFile('images') && count($request->file('images')) === 1 && empty($existingMedia)) ? 'image' : 'gallery';
            if ($request->hasFile('images')) {
                $uploadedFiles = $request->boolean('replace_media') ? [] : $existingMedia;
                $maxAllowed = ($ad->package === 'مميزة') ? 3 : 5;
                foreach ($request->file('images') as $file) {
                    if (count($uploadedFiles) < $maxAllowed) {
                        $filename = time() . '_' . Str::random(12) . '.' . $file->getClientOriginalExtension();
                        $file->move($imgDir, $filename);
                        $uploadedFiles[] = "uploads/ads/images/{$university->university_id}/{$filename}";
                    }
                }
                $ad->media_files = array_values($uploadedFiles);
            }
            $ad->video_url = null;
        }

        $ad->adv_title = $request->adv_title;
        $ad->adv_content = $request->adv_content;
        if ((int)$ad->wizard_step < 3) {
            $ad->wizard_step = 3;
        }
        $ad->save();

        return response()->json([
            'success'     => true,
            'ad_id'       => $ad->adv_id,
            'adv_title'   => $ad->adv_title,
            'adv_content' => $ad->adv_content,
            'media_type'  => $ad->media_type,
            'media_files' => array_map(fn($f) => asset($f), $ad->mediaFilesArray()),
            'video_url'   => $ad->video_url ? asset($ad->video_url) : null,
            'wizard_step' => $ad->wizard_step,
            'message'     => 'تم حفظ محتوى الإعلان والوسائط بنجاح.',
        ]);
    }

    /**
     * المعالج: إرسال الإعلان للمراجعة والاعتماد
     */
    public function advertisementWizardSubmit(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->isUniversity() || !$user->university) {
            return response()->json(['success' => false, 'message' => 'غير مصرح'], 403);
        }
        $university = $user->university;

        $request->validate(['ad_id' => 'required|integer|exists:advertisements,adv_id']);

        $ad = Advertisement::where('university_id', $university->university_id)
            ->findOrFail($request->ad_id);

        if (!in_array($ad->review_status, ['مسودة', 'مطلوب تعديل'])) {
            return response()->json(['success' => false, 'message' => 'تم إرسال هذا الإعلان للمراجعة مسبقاً.'], 400);
        }

        if (empty($ad->adv_title) || empty($ad->adv_content)) {
            return response()->json(['success' => false, 'message' => 'يرجى إكمال عنوان الإعلان ووصفه أولاً.'], 422);
        }

        if (empty($ad->receipt_url) || empty($ad->pay_reference)) {
            return response()->json(['success' => false, 'message' => 'يرجى إكمال بيانات الدفع وإرفاق السند أولاً.'], 422);
        }

        $mediaFiles = $ad->mediaFilesArray();
        if (empty($mediaFiles) && empty($ad->video_url)) {
            return response()->json(['success' => false, 'message' => 'يرجى رفع وسائط الإعلان (صورة أو فيديو) أولاً.'], 422);
        }

        $ad->update([
            'review_status' => 'قيد المراجعة',
            'status'        => 'غير نشط',
            'wizard_step'   => 4,
            'notes'         => null,
        ]);

        // إرسال إشعار للمدير العام
        try {
            \App\Models\Notification::create([
                'user_id'           => 1, // Super Admin
                'notification_type' => 'ad_submitted_for_review',
                'title'             => 'إعلان ترويجي جديد بانتظار الاعتماد',
                'message'           => "قامت جامعة {$university->university_name} بتقديم إعلان جديد بعنوان: \"{$ad->adv_title}\" على الباقة ({$ad->package})، وهو الآن بانتظار المراجعة والاعتماد.",
                'link'              => route('admin.advertisements'),
                'is_read'           => false,
            ]);
        } catch (\Throwable $e) {}

        return response()->json([
            'success'  => true,
            'message'  => 'تم إرسال الإعلان للمراجعة والاعتماد بنجاح! سيتم فحص الطلب وإشعارك قريباً.',
            'redirect' => route('university.advertisements.index'),
        ]);
    }

    /**
     * عرض تفاصيل إعلان محدد
     */
    public function showUniversityAdvertisement($id)
    {
        $user = Auth::user();
        if (!$user || !$user->isUniversity() || !$user->university) {
            abort(403, 'غير مصرح.');
        }
        $university = $user->university;

        $ad = Advertisement::where('university_id', $university->university_id)
            ->findOrFail($id);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'ad' => [
                    'id'               => $ad->adv_id,
                    'title'            => $ad->adv_title,
                    'content'          => $ad->adv_content,
                    'package'          => $ad->package,
                    'package_label'    => $ad->getPackageLabel(),
                    'duration'         => $ad->duration,
                    'amount'           => $ad->amount,
                    'amount_formatted' => number_format($ad->amount) . ' ر.ي',
                    'pay_method'       => $ad->pay_method,
                    'pay_reference'    => $ad->pay_reference,
                    'receipt_url'      => $ad->receipt_url ? asset($ad->receipt_url) : null,
                    'media_type'       => $ad->media_type,
                    'media_files'      => array_map(fn($f) => asset($f), $ad->mediaFilesArray()),
                    'video_url'        => $ad->video_url ? asset($ad->video_url) : null,
                    'review_status'    => $ad->review_status,
                    'status'           => $ad->status,
                    'notes'            => $ad->notes,
                    'wizard_step'      => $ad->wizard_step,
                    'remaining_days'   => $ad->remainingDays(),
                    'is_active'        => $ad->isActive(),
                    'is_expired'       => $ad->isExpired(),
                    'published_at'     => $ad->published_at ? $ad->published_at->format('Y-m-d H:i') : null,
                    'expires_at'       => $ad->expires_at ? $ad->expires_at->format('Y-m-d H:i') : null,
                    'created_at'       => $ad->created_at ? $ad->created_at->format('Y-m-d H:i') : null,
                ],
            ]);
        }

        return view('dashboards.university.advertisements.show', compact('university', 'ad'));
    }

    /**
     * إلغاء وحذف مسودة إعلان
     */
    public function cancelUniversityAd($id)
    {
        $user = Auth::user();
        if (!$user || !$user->isUniversity() || !$user->university) {
            return response()->json(['success' => false, 'message' => 'غير مصرح'], 403);
        }
        $university = $user->university;

        $ad = Advertisement::where('university_id', $university->university_id)
            ->where('adv_id', $id)
            ->firstOrFail();

        if ($ad->review_status !== 'مسودة') {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن حذف هذا الإعلان لأنه ليس مسودة معلقة.',
                ], 403);
            }
            return back()->with('error', 'لا يمكن حذف هذا الإعلان لأنه ليس مسودة معلقة.');
        }

        // حذف الملفات المرتبطة
        try {
            if ($ad->receipt_url && file_exists(public_path($ad->receipt_url))) {
                @unlink(public_path($ad->receipt_url));
            }
            if (!empty($ad->media_files)) {
                foreach ($ad->mediaFilesArray() as $f) {
                    if (file_exists(public_path($f))) {
                        @unlink(public_path($f));
                    }
                }
            }
            if ($ad->video_url && file_exists(public_path($ad->video_url))) {
                @unlink(public_path($ad->video_url));
            }
        } catch (\Throwable $e) {}

        $ad->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => 'تم إلغاء وحذف مسودة الإعلان بنجاح.',
                'redirect' => route('university.advertisements.index'),
            ]);
        }

        return redirect()->route('university.advertisements.index')
            ->with('success', 'تم إلغاء وحذف مسودة الإعلان بنجاح.');
    }

}
