<?php

namespace Tests\Feature;

use App\Models\College;
use App\Models\UniversityPaymentMethod;
use App\Models\RegRequest;
use App\Models\Specialization;
use App\Models\Student;
use App\Models\StudentReferral;
use App\Models\University;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UniversityOperationsTest extends TestCase
{
    protected User $univUser;
    protected University $university;
    protected User $testStudentUser;
    protected Student $testStudent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->univUser = User::where('user_type', 'university')->first();
        $this->university = $this->univUser->university;

        $suffix = time() . '_' . rand(100, 999);
        $this->testStudentUser = User::create([
            'username' => 'test_univ_std_' . $suffix,
            'password' => Hash::make('secret123'),
            'user_type' => 'student',
            'account_status' => 'active',
        ]);

        $this->testStudent = Student::create([
            'user_id' => $this->testStudentUser->user_id,
            'full_name' => 'طالب فحص عمليات الجامعة',
        ]);
    }

    protected function tearDown(): void
    {
        if (isset($this->testStudentUser)) {
            RegRequest::where('student_id', $this->testStudent->student_id)->delete();
            StudentReferral::where('student_id', $this->testStudent->student_id)->delete();
            $this->testStudent->delete();
            $this->testStudentUser->delete();
        }

        parent::tearDown();
    }

    public function test_university_can_update_application_status(): void
    {
        $regReq = RegRequest::create([
            'student_id' => $this->testStudent->student_id,
            'university_id' => $this->university->university_id,
            'full_name' => 'طالب فحص عمليات الجامعة',
            'phone' => '770000000',
            'desired_spec' => 'هندسة حاسوب',
            'status' => 'قيد المراجعة',
            'created_at' => now(),
        ]);

        $response = $this->actingAs($this->univUser)->postJson(
            route('university.requests.status', $regReq->req_id),
            [
                'status' => 'مقبول',
                'rejection_reason' => null,
            ]
        );

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('reg_requests', [
            'req_id' => $regReq->req_id,
            'status' => 'مقبول',
        ]);
    }

    public function test_university_can_manage_payment_methods(): void
    {
        $response = $this->actingAs($this->univUser)->post(
            route('university.payments.store'),
            [
                'method_type' => 'حساب بنكي',
                'provider_name' => 'بنك الكريمي الإسلامي',
                'account_number' => '123456789',
                'account_name' => 'جامعة صنعاء',
                'instructions' => 'يرجى إرسال الإشعار بعد التحويل',
            ]
        );

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('university_payment_methods', [
            'university_id' => $this->university->university_id,
            'method_name' => 'بنك الكريمي الإسلامي',
            'account_number' => '123456789',
        ]);

        $pm = UniversityPaymentMethod::where('university_id', $this->university->university_id)
            ->where('account_number', '123456789')
            ->first();

        // Toggle
        $toggleResp = $this->actingAs($this->univUser)->postJson(route('university.payments.toggle', $pm->method_id));
        $toggleResp->assertStatus(200);

        // Delete
        $delResp = $this->actingAs($this->univUser)->post(route('university.payments.delete', $pm->method_id));
        $delResp->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('university_payment_methods', ['method_id' => $pm->method_id]);
    }

    public function test_university_can_verify_referral_code(): void
    {
        $uniqueCode = 'REF_' . strtoupper(substr(uniqid(), -8));

        $referral = StudentReferral::create([
            'student_id' => $this->testStudent->student_id,
            'university_id' => $this->university->university_id,
            'ref_code' => $uniqueCode,
            'referral_code' => $uniqueCode,
            'ref_status' => 'صالح',
            'status' => 'صالح',
        ]);

        $response = $this->actingAs($this->univUser)->postJson(
            route('university.referrals.verify'),
            ['referral_code' => $uniqueCode]
        );

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
    }

    public function test_university_can_export_requests_csv(): void
    {
        $response = $this->actingAs($this->univUser)->get(route('university.requests.export'));
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-type') ?? '', 'text/csv') || str_contains($response->headers->get('content-disposition') ?? '', '.csv'));
    }

    public function test_applications_table_has_only_view_action_and_modal_refinements(): void
    {
        $regReq = RegRequest::create([
            'student_id' => $this->testStudent->student_id,
            'university_id' => $this->university->university_id,
            'full_name' => 'طالب فحص واجهة الإجراءات',
            'phone' => '770000000',
            'desired_spec' => 'طب وجراحة',
            'status' => 'قيد المراجعة',
            'seat_number' => '423000',
            'created_at' => now(),
        ]);

        $response = $this->actingAs($this->univUser)->get('/university/dashboard?tab=applications');
        $response->assertStatus(200);

        // عمود الإجراءات في الجدول يحتوي فقط على زر "عرض"
        $response->assertSee('viewRequestDetails(' . $regReq->req_id . ')', false);
        $response->assertDontSee('quickAcceptRequest(' . $regReq->req_id . ')', false);
        $response->assertDontSee('openRejectModal(' . $regReq->req_id, false);
        $response->assertDontSee('openNeedsCompletionModal(' . $regReq->req_id, false);

        // عدم وجود عبارة التوجيه غير المفيدة
        $response->assertDontSee('قم بمراجعة وتدقيق المستندات المرفقة من الطالب ومطابقتها مع البيانات المدخلة قبل اتخاذ قرار القبول.');

        // زر فحص نتيجة الوزارة موجود وواضح
        $response->assertSee('btn-ministry-badge', false);
        $response->assertSee('فحص نتيجة الوزارة');
    }
}
