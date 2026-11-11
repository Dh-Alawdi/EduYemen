<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\RegRequest;
use App\Models\StudentReferral;
use App\Models\University;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StudentDashboardTest extends TestCase
{
    public function test_student_can_view_dashboard(): void
    {
        $studentUser = User::where('username', 'student1')->first();
        $response = $this->actingAs($studentUser)->get('/student/dashboard');

        $response->assertStatus(200);
        $response->assertSee('الملف الشخصي');
        $response->assertSee('البيانات الشخصية');
        $response->assertSee('رموز الإحالة');
    }

    public function test_student_can_update_personal_info(): void
    {
        $uniqueSuffix = time() . '_' . rand(100, 999);
        $user = User::create([
            'username' => 'test_std_upd_' . $uniqueSuffix,
            'password' => Hash::make('password123'),
            'user_type' => 'student',
            'account_status' => 'active',
        ]);
        $student = Student::create([
            'user_id' => $user->user_id,
            'full_name' => 'طالب للاختبار',
        ]);

        $testEmail = 'ahmed_' . $uniqueSuffix . '@example.com';
        $response = $this->actingAs($user)->post('/student/profile', [
            'full_name' => 'أحمد عبد الله العباسي',
            'phone' => '771234567',
            'email' => $testEmail,
            'gender' => 'ذكر',
            'birth_date' => '2004-05-15',
            'city' => 'صنعاء',
            'address' => 'حدة، شارع بيروت',
        ]);

        $response->assertRedirect(route('student.dashboard', ['tab' => 'profile']));
        $this->assertDatabaseHas('students', [
            'user_id' => $user->user_id,
            'full_name' => 'أحمد عبد الله العباسي',
            'city' => 'صنعاء',
        ]);
        $this->assertDatabaseHas('users', [
            'user_id' => $user->user_id,
            'email' => $testEmail,
        ]);
    }

    public function test_student_can_change_password_after_confirmation(): void
    {
        $studentUser = User::where('username', 'student1')->first();

        // فشل مع كلمة مرور حالية غير صحيحة
        $badResponse = $this->actingAs($studentUser)->post('/student/password', [
            'current_password' => 'wrong_password',
            'password' => 'new_secret_123',
            'password_confirmation' => 'new_secret_123',
        ]);
        $badResponse->assertSessionHasErrors('current_password');

        // نجاح مع كلمة المرور الحالية الصحيحة
        $goodResponse = $this->actingAs($studentUser)->post('/student/password', [
            'current_password' => 'student123',
            'password' => 'new_secret_123',
            'password_confirmation' => 'new_secret_123',
        ]);
        $goodResponse->assertRedirect('/student/dashboard');

        $studentUser->refresh();
        $this->assertTrue(Hash::check('new_secret_123', $studentUser->password));

        // إعادة تعيين كلمة المرور لتوافق الاختبارات الأخرى
        $studentUser->update(['password' => Hash::make('student123')]);
    }

    public function test_student_can_generate_referral_for_specific_university(): void
    {
        $studentUser = User::where('username', 'student1')->first();
        $univ = University::first();

        $response = $this->actingAs($studentUser)->postJson('/student/referral/create-for-university', [
            'university_id' => $univ->university_id,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'referral_code', 'university_name']);

        $this->assertDatabaseHas('student_referrals', [
            'student_id' => $studentUser->student->student_id,
            'university_id' => $univ->university_id,
        ]);
    }

    public function test_student_can_update_theme_preferences(): void
    {
        $studentUser = User::where('username', 'student1')->first();

        $response = $this->actingAs($studentUser)->postJson('/student/theme', [
            'theme_mode' => 'dark',
            'theme_color' => 'navy',
        ]);

        $response->assertStatus(200);
        $studentUser->refresh();
        $this->assertEquals('dark', $studentUser->theme_mode);

        // إعادة الوضع إلى الوضع النهاري الافتراضي
        $studentUser->update(['theme_mode' => 'light']);
    }

    public function test_student_can_update_notification_tone(): void
    {
        $studentUser = User::where('username', 'student1')->first();

        $response = $this->actingAs($studentUser)->postJson('/student/notifications', [
            'notifications_muted' => 0,
            'notification_sound' => 1,
            'notification_tone' => 'bell',
        ]);

        $response->assertStatus(200);
        $studentUser->refresh();
        $this->assertEquals('bell', $studentUser->notification_tone);
    }

    public function test_student_can_submit_registration_application_persisting_in_database(): void
    {
        $studentUser = User::where('username', 'student1')->first();
        $univ = University::first();

        // إزالة أي طلب سابق لنفس التخصص لضمان اختبار إنشاء الطلب بنجاح وفق ضوابط المنصة
        RegRequest::where('student_id', $studentUser->student->student_id)
            ->where('desired_spec', 'هندسة برمجيات')
            ->delete();

        $response = $this->actingAs($studentUser)->post('/student/apply', [
            'university_id' => $univ->university_id,
            'desired_spec' => 'هندسة برمجيات',
            'student_name' => 'ضياء محمد علي العودي',
            'seat_number' => '123456',
            'sec_avg' => 88.5,
            'sec_section' => 'علمي',
            'phone' => '771234567',
        ]);

        $response->assertRedirect(route('student.dashboard', ['tab' => 'requests']));
        $this->assertDatabaseHas('reg_requests', [
            'student_id' => $studentUser->student->student_id,
            'university_id' => $univ->university_id,
            'desired_spec' => 'هندسة برمجيات',
            'status' => 'قيد المراجعة',
        ]);
    }
}
