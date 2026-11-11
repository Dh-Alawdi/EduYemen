<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\UniversityPaymentMethod;
use App\Models\RegRequest;
use App\Models\Student;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaymentWorkflowTest extends TestCase
{
    protected User $univUser;
    protected University $university;
    protected User $studentUser;
    protected Student $student;
    protected UniversityPaymentMethod $paymentMethod;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->univUser = User::where('user_type', 'university')->first();
        $this->university = $this->univUser->university;

        $suffix = time() . '_' . rand(100, 999);
        $this->studentUser = User::create([
            'username' => 'std_pay_test_' . $suffix,
            'password' => Hash::make('password123'),
            'user_type' => 'student',
            'account_status' => 'active',
        ]);

        $this->student = Student::create([
            'user_id' => $this->studentUser->user_id,
            'full_name' => 'طالب اختبار السداد ' . $suffix,
            'phone' => '771234567',
        ]);

        // Ensure active payment method exists for this university
        $this->paymentMethod = UniversityPaymentMethod::firstOrCreate(
            [
                'university_id' => $this->university->university_id,
                'method_name' => 'بنك الكريمي الإسلامي',
            ],
            [
                'method_type' => 'حساب بنكي',
                'account_number' => '123456789',
                'account_name' => $this->university->university_name,
                'is_active' => true,
            ]
        );
    }

    protected function tearDown(): void
    {
        if (isset($this->student)) {
            $reqIds = RegRequest::where('student_id', $this->student->student_id)->pluck('req_id');
            Payment::whereIn('req_id', $reqIds)->delete();
            RegRequest::where('student_id', $this->student->student_id)->delete();
            $this->student->delete();
            $this->studentUser->delete();
        }

        parent::tearDown();
    }

    /**
     * فحص جلب وسائل الدفع النشطة للجامعة عبر API
     */
    public function test_api_fetches_active_university_payment_methods(): void
    {
        $response = $this->getJson("/api/universities/{$this->university->university_id}/payment-methods");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertNotEmpty($response->json('payment_methods'));
    }

    /**
     * فحص تقديم الطالب لطلب تسجيل مع سداد فوري وإرفاق إشعار وسند التحويل
     */
    public function test_student_applies_with_payment_proof_immediately(): void
    {
        $dummyReceipt = UploadedFile::fake()->create('receipt.pdf', 100, 'application/pdf');

        $postData = [
            'university_id' => $this->university->university_id,
            'desired_spec' => 'هندسة برمجيات تجريبي ' . rand(100, 999),
            'full_name' => $this->student->full_name,
            'seat_number' => 'SEAT-' . rand(1000, 9999),
            'phone' => '771234567',
            'payment_timing' => 'now',
            'method_id' => $this->paymentMethod->method_id,
            'reference' => 'KUR-REF-' . rand(10000, 99999),
            'amount' => 5000,
            'pay_date' => now()->toDateString(),
            'payment_proof' => $dummyReceipt,
            'notes' => 'سداد رسوم عبر الكريمي',
        ];

        $response = $this->actingAs($this->studentUser)
            ->postJson(route('student.apply.submit'), $postData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $reqId = $response->json('req_id');
        $regReq = RegRequest::with('payments')->find($reqId);

        $this->assertNotNull($regReq);
        $this->assertCount(1, $regReq->payments);
        $payment = $regReq->payments->first();
        $this->assertEquals('قيد التحقق', $payment->ver_status);
        $this->assertEquals($this->paymentMethod->method_id, $payment->method_id);
        $this->assertEquals($postData['reference'], $payment->reference);
        $this->assertEquals('قيد التحقق', $regReq->payment_status);
    }

    /**
     * فحص تقديم الطالب لطلب تسجيل مع اختيار السداد لاحقاً
     */
    public function test_student_applies_with_pay_later(): void
    {
        $postData = [
            'university_id' => $this->university->university_id,
            'desired_spec' => 'أمن سيبراني تجريبي ' . rand(100, 999),
            'full_name' => $this->student->full_name,
            'seat_number' => 'SEAT-' . rand(1000, 9999),
            'phone' => '771234567',
            'payment_timing' => 'later',
        ];

        $response = $this->actingAs($this->studentUser)
            ->postJson(route('student.apply.submit'), $postData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $reqId = $response->json('req_id');
        $regReq = RegRequest::with('payments')->find($reqId);

        $this->assertNotNull($regReq);
        $this->assertCount(0, $regReq->payments);
        $this->assertEquals('بانتظار السداد', $regReq->payment_status);
    }

    /**
     * فحص رفع الطالب لسند السداد لاحقاً عبر لوحة التحكم
     */
    public function test_student_submits_payment_proof_later(): void
    {
        // إنشاء طلب بحالة بانتظار السداد
        $regReq = RegRequest::create([
            'student_id' => $this->student->student_id,
            'university_id' => $this->university->university_id,
            'desired_spec' => 'نظم معلومات تجريبي ' . rand(100, 999),
            'full_name' => $this->student->full_name,
            'seat_number' => 'SEAT-' . rand(1000, 9999),
            'phone' => '771234567',
            'status' => 'قيد المراجعة',
            'req_date' => now(),
        ]);

        $this->assertEquals('بانتظار السداد', $regReq->payment_status);

        $dummyReceipt = UploadedFile::fake()->create('receipt_later.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->studentUser)
            ->postJson(route('student.requests.payment.submit', $regReq->req_id), [
                'method_id' => $this->paymentMethod->method_id,
                'reference' => 'ONE-CASH-' . rand(1000, 9999),
                'amount' => 5000,
                'pay_date' => now()->toDateString(),
                'proof_file' => $dummyReceipt,
                'notes' => 'سداد متأخر عبر محفظة ون كاش',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $regReq->refresh();
        $this->assertEquals('قيد التحقق', $regReq->payment_status);
        $this->assertNotNull($regReq->latestPayment);
        $this->assertEquals('قيد التحقق', $regReq->latestPayment->ver_status);
    }

    /**
     * فحص اعتماد وتأكيد السداد المالي من قبل إدارة الجامعة
     */
    public function test_university_verifies_and_approves_payment(): void
    {
        $regReq = RegRequest::create([
            'student_id' => $this->student->student_id,
            'university_id' => $this->university->university_id,
            'desired_spec' => 'طب وجراحة تجريبي ' . rand(100, 999),
            'full_name' => $this->student->full_name,
            'seat_number' => 'SEAT-' . rand(1000, 9999),
            'phone' => '771234567',
            'status' => 'قيد المراجعة',
            'req_date' => now(),
        ]);

        $payment = Payment::create([
            'req_id' => $regReq->req_id,
            'method_id' => $this->paymentMethod->method_id,
            'amount' => 6000,
            'pay_date' => now()->toDateString(),
            'reference' => 'VER-REF-' . rand(1000, 9999),
            'ver_status' => 'قيد التحقق',
        ]);

        $response = $this->actingAs($this->univUser)
            ->postJson(route('university.requests.payment.verify', $regReq->req_id), [
                'ver_status' => 'معتمد ومؤكد',
                'ver_result' => 'تم التحقق من الحساب البنكي واستلام المبلغ بالكامل.',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $payment->refresh();
        $regReq->refresh();
        $this->assertEquals('معتمد ومؤكد', $payment->ver_status);
        $this->assertEquals('مسدد ومعتمد', $regReq->payment_status);
    }

    /**
     * فحص رفض الجامعة لسند السداد مع إبداء السبب
     */
    public function test_university_rejects_payment_with_reason(): void
    {
        $regReq = RegRequest::create([
            'student_id' => $this->student->student_id,
            'university_id' => $this->university->university_id,
            'desired_spec' => 'صيدلة سريرية تجريبي ' . rand(100, 999),
            'full_name' => $this->student->full_name,
            'seat_number' => 'SEAT-' . rand(1000, 9999),
            'phone' => '771234567',
            'status' => 'قيد المراجعة',
            'req_date' => now(),
        ]);

        $payment = Payment::create([
            'req_id' => $regReq->req_id,
            'method_id' => $this->paymentMethod->method_id,
            'amount' => 5000,
            'pay_date' => now()->toDateString(),
            'reference' => 'BAD-REF-' . rand(1000, 9999),
            'ver_status' => 'قيد التحقق',
        ]);

        $rejectionReason = 'صورة السند غير واضحة ورقم الحساب غير ظاهر';

        $response = $this->actingAs($this->univUser)
            ->postJson(route('university.requests.payment.verify', $regReq->req_id), [
                'ver_status' => 'مرفوض',
                'ver_result' => $rejectionReason,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $payment->refresh();
        $regReq->refresh();
        $this->assertEquals('مرفوض', $payment->ver_status);
        $this->assertEquals($rejectionReason, $payment->ver_result);
        $this->assertEquals('سند مرفوض', $regReq->payment_status);
    }

    /**
     * فحص تعديل الجامعة لبيانات وسيلة دفع معتمدة
     */
    public function test_university_updates_payment_method(): void
    {
        $updateData = [
            'method_name' => 'بنك التضامن الإسلامي المحدث',
            'method_type' => 'حساب بنكي',
            'account_number' => '9988776655',
            'account_name' => 'جامعة صنعاء - الرسوم الدراسية',
            'instructions' => 'يرجى إبراز الاسم ورقم الطلب في الحوالة',
        ];

        $response = $this->actingAs($this->univUser)
            ->postJson(route('university.payments.update', $this->paymentMethod->method_id), $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->paymentMethod->refresh();
        $this->assertEquals('بنك التضامن الإسلامي المحدث', $this->paymentMethod->method_name);
        $this->assertEquals('9988776655', $this->paymentMethod->account_number);
    }
}
