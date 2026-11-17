<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| المسارات العامة (Public Routes)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/universities', [PublicController::class, 'universities'])->name('universities.index');
Route::get('/universities/{id}', [PublicController::class, 'showUniversity'])->name('universities.show');
Route::get('/compare', [PublicController::class, 'compare'])->name('compare');
Route::get('/ai-advisor', [PublicController::class, 'aiAdvisor'])->name('ai.advisor');
Route::get('/specializations/{id}/admission', [PublicController::class, 'getSpecializationAdmissionData'])->name('specializations.admission.public');

/*
|--------------------------------------------------------------------------
| مسارات المصادقة (Authentication Routes)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| مسارات الطالب المحمية (Student Protected Routes)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'user_type:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'studentDashboard'])->name('dashboard');
    Route::post('/profile', [DashboardController::class, 'updateStudentProfile'])->name('profile.update');
    Route::post('/password', [DashboardController::class, 'changeStudentPassword'])->name('password.change');
    Route::post('/theme', [DashboardController::class, 'updateStudentTheme'])->name('theme.update');
    Route::post('/notifications', [DashboardController::class, 'updateStudentNotifications'])->name('notifications.update');
    Route::post('/referral/create-for-university', [DashboardController::class, 'createReferralForUniversity'])->name('referral.create_univ');
    Route::post('/referral/generate', [DashboardController::class, 'generateStudentReferral'])->name('referral.generate');
    Route::get('/apply', [DashboardController::class, 'showRegistrationForm'])->name('apply');
    Route::post('/apply', [DashboardController::class, 'submitStudentApplication'])->name('apply.submit');
    Route::post('/requests/{id}/cancel', [DashboardController::class, 'cancelStudentApplication'])->name('requests.cancel');
    Route::post('/requests/{id}/payment', [DashboardController::class, 'submitApplicationPaymentProof'])->name('requests.payment.submit');
    Route::get('/verify-seat-number', [DashboardController::class, 'verifyHighSchoolSeatNumber'])->name('verify.seat');
});

// مسار عام للتحقق من رقم الجلوس لاستمارة التقديم
Route::get('/api/verify-seat-number', [DashboardController::class, 'verifyHighSchoolSeatNumber'])->name('api.verify.seat');

// توجيه عام لطلب التسجيل مع الحفاظ على الرابط المقصود
Route::get('/apply', function (\Illuminate\Http\Request $request) {
    if (!Auth::check()) {
        session()->put('url.intended', request()->fullUrl());
        return redirect()->route('login')->with('warning', 'يرجى تسجيل الدخول أو إنشاء حساب طالب للبدء في تعبئة طلب التسجيل.');
    }
    if (Auth::user()->user_type === 'university') {
        return app(App\Http\Controllers\DashboardController::class)->showRegistrationForm($request);
    }
    return redirect()->route('student.apply', $request->query());
})->name('apply.direct');

// نقطة نهاية لجلب تخصصات الجامعة ديناميكياً
Route::get('/api/universities/{id}/specialties', function ($id) {
    $u = App\Models\University::with(['colleges' => function($cq) {
        $cq->where('status', 'نشطة')->whereDoesntHave('activeModeration')->with(['specializations' => function($sq) {
            $sq->where('status', 'نشط')->whereDoesntHave('activeModeration');
        }]);
    }])->findOrFail($id);

    $specialties = [];
    foreach ($u->colleges as $col) {
        foreach ($col->specializations as $sp) {
            $specialties[] = [
                'specialty_id' => $sp->specialization_id,
                'specialty_name' => $sp->specialization_name,
                'college_id' => $col->college_id,
                'college_name' => $col->college_name,
                'tuition_fees' => $sp->tuition_fees,
                'duration_years' => $sp->duration_years,
            ];
        }
    }
    return response()->json($specialties);
});

// نقطة نهاية لجلب وسائل الدفع النشطة للجامعة ديناميكياً
Route::get('/api/universities/{id}/payment-methods', function ($id) {
    $methods = App\Models\UniversityPaymentMethod::where('university_id', $id)
        ->where('is_active', true)
        ->get();
    return response()->json([
        'success' => true,
        'payment_methods' => $methods
    ]);
});

/*
|--------------------------------------------------------------------------
| مسارات الجامعة المحمية (University Protected Routes)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'user_type:university'])->prefix('university')->name('university.')->group(function () {
    // تهيئة الحساب لأول مرة وتغيير كلمة المرور وتحديث البيانات الأولية
    Route::get('/setup', [DashboardController::class, 'showFirstLoginForm'])->name('first_login');
    Route::post('/setup', [DashboardController::class, 'saveFirstLoginSetup'])->name('first_login.save');

    Route::get('/dashboard', [DashboardController::class, 'universityDashboard'])->name('dashboard');
    Route::post('/profile', [DashboardController::class, 'updateUniversityProfile'])->name('profile.update');
    Route::post('/password', [DashboardController::class, 'changeUniversityPassword'])->name('password.change');
    Route::post('/settings', [DashboardController::class, 'updateUniversitySettings'])->name('settings.update');

    // إدارة طلبات التسجيل
    Route::get('/requests/export', [DashboardController::class, 'exportRequestsCsv'])->name('requests.export');
    Route::get('/requests/{id}', [DashboardController::class, 'getUniversityRequestDetails'])->name('requests.details');
    Route::post('/requests/{id}/status', [DashboardController::class, 'updateUniversityRequestStatus'])->name('requests.status');
    Route::post('/requests/{id}/payment-verify', [DashboardController::class, 'verifyRequestPayment'])->name('requests.payment.verify');

    // إدارة الكليات
    Route::post('/colleges', [DashboardController::class, 'storeCollege'])->name('colleges.store');
    Route::post('/colleges/{id}/update', [DashboardController::class, 'updateCollege'])->name('colleges.update');
    Route::post('/colleges/{id}/delete', [DashboardController::class, 'deleteCollege'])->name('colleges.delete');

    // إدارة التخصصات الأكاديمية
    Route::post('/specializations', [DashboardController::class, 'storeSpecialization'])->name('specializations.store');
    Route::post('/specializations/{id}/update', [DashboardController::class, 'updateSpecialization'])->name('specializations.update');
    Route::post('/specializations/{id}/delete', [DashboardController::class, 'deleteSpecialization'])->name('specializations.delete');

    // إدارة بيانات القبول والتسجيل للتخصص
    Route::get('/specializations/{id}/admission', [DashboardController::class, 'getSpecializationAdmission'])->name('specializations.admission.get');
    Route::post('/specializations/{id}/admission', [DashboardController::class, 'updateSpecializationAdmission'])->name('specializations.admission.update');

    // إدارة وثائق القبول والتسجيل المطلوبة للتخصص
    Route::post('/specializations/{id}/documents', [DashboardController::class, 'saveSpecializationDocuments'])->name('specializations.documents.save');
    Route::post('/specializations/{id}/documents/add', [DashboardController::class, 'addSpecializationDocument'])->name('specializations.documents.add');
    Route::post('/documents/{docId}/delete', [DashboardController::class, 'deleteSpecializationDocument'])->name('specializations.documents.delete');

    // إدارة الخطط الدراسية والمقررات للتخصص
    Route::get('/specializations/{id}/plans', [DashboardController::class, 'getSpecializationPlans'])->name('specializations.plans.get');
    Route::post('/specializations/{id}/plans', [DashboardController::class, 'storeStudyPlan'])->name('specializations.plans.store');
    Route::post('/plans/{planId}/update', [DashboardController::class, 'updateStudyPlan'])->name('plans.update');
    Route::post('/plans/{planId}/delete', [DashboardController::class, 'deleteStudyPlan'])->name('plans.delete');
    Route::post('/plans/{planId}/courses', [DashboardController::class, 'savePlanCourse'])->name('plans.courses.save');
    Route::post('/courses/{courseId}/delete', [DashboardController::class, 'deletePlanCourse'])->name('plans.courses.delete');

    // الإحالات
    Route::post('/referrals/verify', [DashboardController::class, 'verifyReferralCode'])->name('referrals.verify');

    // الإعلانات
    Route::post('/announcements', [DashboardController::class, 'storeAnnouncement'])->name('announcements.store');
    Route::post('/announcements/{id}/delete', [DashboardController::class, 'deleteAnnouncement'])->name('announcements.delete');

    // طرق الدفع
    Route::post('/payments', [DashboardController::class, 'storePaymentMethod'])->name('payments.store');
    Route::post('/payments/{id}/update', [DashboardController::class, 'updatePaymentMethod'])->name('payments.update');
    Route::post('/payments/{id}/toggle', [DashboardController::class, 'togglePaymentMethod'])->name('payments.toggle');
    Route::post('/payments/{id}/delete', [DashboardController::class, 'deletePaymentMethod'])->name('payments.delete');
});

/*
|--------------------------------------------------------------------------
| مسارات مدير النظام المحمية (Admin Protected Routes)
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\AdminController;

Route::middleware(['auth', 'user_type:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // إدارة المستخدمين
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/export', [AdminController::class, 'exportUsers'])->name('users.export');
    Route::post('/users/bulk', [AdminController::class, 'bulkUserAction'])->name('users.bulk');
    Route::post('/users/store', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('users.show');
    Route::post('/users/{id}/update', [AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle');
    Route::post('/users/{id}/reset-password', [AdminController::class, 'resetUserPassword'])->name('users.reset-password');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');

    // إدارة الجامعات
    Route::get('/universities', [AdminController::class, 'universities'])->name('universities');
    Route::post('/universities/store', [AdminController::class, 'storeUniversity'])->name('universities.store');
    Route::get('/universities/{id}', [AdminController::class, 'showUniversity'])->name('universities.show');
    Route::post('/universities/{id}/update', [AdminController::class, 'updateUniversity'])->name('universities.update');
    Route::post('/universities/{id}/toggle-status', [AdminController::class, 'toggleUniversityStatus'])->name('universities.toggle');
    Route::delete('/universities/{id}', [AdminController::class, 'deleteUniversity'])->name('universities.delete');

    // الحوكمة والرقابة المستقلة على ظهور المحتوى الجامعي (مركز الرقابة الإدارية)
    Route::get('/moderation', [AdminController::class, 'moderationIndex'])->name('moderation');
    Route::get('/moderation/university/{id}', [AdminController::class, 'getUniversityAuditData'])->name('moderation.university-audit');
    Route::get('/moderations/quarantined', [AdminController::class, 'getQuarantinedItems'])->name('moderations.quarantined');
    Route::post('/moderations/quarantine', [AdminController::class, 'quarantineItem'])->name('moderations.quarantine');
    Route::post('/moderations/{id}/resolve', [AdminController::class, 'resolveQuarantine'])->name('moderations.resolve');

    // إدارة الإعلانات الجامعية
    Route::get('/advertisements', [AdminController::class, 'advertisements'])->name('advertisements');
    Route::get('/advertisements/{id}', [AdminController::class, 'showAdvertisement'])->name('advertisements.show');
    Route::post('/advertisements/{id}/approve', [AdminController::class, 'approveAdvertisement'])->name('advertisements.approve');
    Route::post('/advertisements/{id}/reject', [AdminController::class, 'rejectAdvertisement'])->name('advertisements.reject');
    Route::delete('/advertisements/{id}', [AdminController::class, 'deleteAdvertisement'])->name('advertisements.delete');

    // إدارة الإحالات
    Route::get('/referrals', [AdminController::class, 'referrals'])->name('referrals');

    // إدارة العمولات (مفصولة)
    Route::get('/commissions', [AdminController::class, 'commissions'])->name('commissions');
    Route::post('/referrals/{id}/confirm-commission', [AdminController::class, 'confirmCommission'])->name('referrals.confirm');
    Route::post('/referrals/{id}/reject-commission', [AdminController::class, 'rejectCommission'])->name('referrals.reject');

    // متابعة طلبات التسجيل
    Route::get('/requests', [AdminController::class, 'requests'])->name('requests');
    Route::get('/requests/{id}', [AdminController::class, 'showRequest'])->name('requests.show');

    // التقارير والإحصائيات
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');

    // الإعدادات العامة
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings/update', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::post('/settings/platform', [AdminController::class, 'updatePlatformSettings'])->name('settings.platform');
    Route::post('/settings/reset-defaults', [AdminController::class, 'resetDefaultSettings'])->name('settings.reset');
    Route::post('/settings/change-password', [AdminController::class, 'changePassword'])->name('settings.password');
});
