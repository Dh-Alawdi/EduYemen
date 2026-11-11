<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Student;
use App\Models\University;
use App\Models\College;
use App\Models\Specialization;
use App\Models\AdmissionData;
use App\Models\AdmissionDoc;
use App\Models\RegRequest;
use App\Models\RegDoc;
use App\Models\UniversityPaymentMethod;
use App\Models\Payment;
use App\Models\Advertisement;
use App\Models\StudyPlan;
use App\Models\Semester;
use App\Models\Course;
use App\Models\StudentReferral;
use App\Models\RegProof;
use App\Models\RecData;
use App\Models\RecResult;
use App\Models\Notification;
use Illuminate\Support\Facades\Schema;

class ErdSchemaCompleteTest extends TestCase
{
    /**
     * التحقق من وجود جميع جداول مخطط ERD
     */
    public function test_all_erd_tables_exist(): void
    {
        $erdTables = [
            'users',
            'admins',
            'students',
            'universities',
            'colleges',
            'specializations',
            'admission_data',
            'admission_docs',
            'reg_requests',
            'reg_docs',
            Schema::hasTable('pay_methods') ? 'pay_methods' : 'university_payment_methods',
            'payments',
            'advertisements',
            'study_plans',
            'semesters',
            'courses',
            Schema::hasTable('referrals') ? 'referrals' : 'student_referrals',
            'reg_proofs',
            'rec_data',
            'rec_results',
            'notifications',
        ];

        foreach ($erdTables as $table) {
            $this->assertTrue(Schema::hasTable($table), "Table [{$table}] must exist in database according to ERD.");
        }

        // التأكد من عدم وجود جداول خارج المخططين المعتمدين
        $this->assertFalse(Schema::hasTable('discounts'), "Table [discounts] must not exist as it is excluded from diagrams.");
    }

    /**
     * التحقق من علاقات الإدارة، الإشعارات، والجامعة
     */
    public function test_admin_and_notifications(): void
    {
        $adminUser = User::create([
            'username' => 'admin_test_' . uniqid(),
            'password' => bcrypt('password123'),
            'user_type' => 'admin',
            'email' => 'admin_' . uniqid() . '@example.com',
            'account_status' => 'active',
        ]);
        $admin = Admin::create([
            'user_id' => $adminUser->user_id,
            'full_name' => 'مدير النظام التجريبي',
        ]);

        $this->assertEquals($adminUser->user_id, $admin->user->user_id);
        $this->assertEquals($admin->admin_id, $adminUser->admin->admin_id);

        // إشعار
        $notification = Notification::create([
            'user_id' => $adminUser->user_id,
            'notification_type' => 'نظام',
            'title' => 'مرحباً بالمدير',
            'message' => 'تم تسجيل دخولك بنجاح',
            'is_read' => false,
        ]);
        $this->assertEquals(1, $adminUser->notifications()->count());
    }

    /**
     * التحقق من الخطة الدراسية، الفصول، والمقررات
     */
    public function test_study_plan_semesters_and_courses(): void
    {
        $spec = Specialization::first();
        $this->assertNotNull($spec);

        $plan = StudyPlan::create([
            'specialization_id' => $spec->specialization_id,
            'plan_name' => 'الخطة الأكاديمية المطورة',
            'duration_years' => 4,
            'total_credit_hours' => 136,
            'status' => 'نشط',
        ]);

        $semester1 = Semester::create([
            'study_plan_id' => $plan->study_plan_id,
            'plan_name' => 'الفصل الدراسي الأول',
            'semester_number' => 1,
        ]);

        $course = Course::create([
            'semester_id' => $semester1->semester_id,
            'course_name' => 'مقدمة في علوم الحاسوب',
            'course_code' => 'CS101',
            'hours' => 3,
            'course_type' => 'إجباري',
        ]);

        $this->assertEquals($plan->study_plan_id, $semester1->studyPlan->study_plan_id);
        $this->assertEquals($semester1->semester_id, $course->semester->semester_id);
        $this->assertEquals(1, $semester1->courses()->count());
    }

    /**
     * التحقق من نظام التوصية (rec_data و rec_results) مع الربط المرجعي بالجامعة والتخصص
     */
    public function test_recommendation_data_and_results(): void
    {
        $student = Student::first();
        $this->assertNotNull($student);

        $uni = University::first();
        $spec = Specialization::first();

        $recData = RecData::create([
            'student_id' => $student->student_id,
            'average' => 92.5,
            'interests' => 'الذكاء الاصطناعي وهندسة البرمجيات',
            'hobbies' => 'البرمجة والقراءة',
            'abilities' => 'حل المشكلات والرياضيات',
            'preferences' => 'جامعات أمانة العاصمة',
            'location' => 'صنعاء',
        ]);

        $recResult = RecResult::create([
            'r_d_id' => $recData->r_d_id,
            'university_id' => $uni ? $uni->university_id : null,
            'specialization_id' => $spec ? $spec->specialization_id : null,
            'univ_name' => $uni ? $uni->university_name : 'جامعة صنعاء',
            'spec_name' => $spec ? $spec->specialization_name : 'ذكاء اصطناعي',
            'match_percentage' => 95.0,
            'result_order' => 1,
            'notes' => 'التخصص يلائم رغباتك ومعدلك بدرجة عالية',
        ]);

        $this->assertEquals($student->student_id, $recData->student->student_id);
        $this->assertEquals($recData->r_d_id, $recResult->recData->r_d_id);
        $this->assertEquals(1, $recData->results()->count());

        if ($uni) {
            $this->assertEquals($uni->university_id, $recResult->university->university_id);
        }
        if ($spec) {
            $this->assertEquals($spec->specialization_id, $recResult->specialization->specialization_id);
        }
    }

    /**
     * التحقق من إثبات التسجيل وعمليات الدفع
     */
    public function test_reg_proof_and_payment(): void
    {
        $student = Student::first();
        $uni = University::first();

        // إحالة + إثبات
        $referral = StudentReferral::create([
            'student_id' => $student->student_id,
            'university_id' => $uni->university_id,
            'referral_code' => 'TEST-REF-' . uniqid(),
            'status' => 'نشط',
            'ref_status' => 'نشط',
        ]);

        $proof = RegProof::create([
            'referral_id' => $referral->referral_id,
            'receipt_number' => 'REC-998877',
            'registration_date' => now()->toDateString(),
            'student_name' => $student->full_name,
            'submitted_at' => now(),
            'verification_status' => 'تم التحقق',
        ]);

        $this->assertEquals($referral->referral_id, $proof->referral->referral_id);
        $this->assertEquals($proof->reg_proof_id, $referral->proof->reg_proof_id);

        // عملية دفع
        $req = RegRequest::where('student_id', $student->student_id)->first();
        if ($req) {
            $payment = Payment::create([
                'req_id' => $req->req_id,
                'amount' => 5000.00,
                'pay_date' => now()->toDateString(),
                'reference' => 'TXN-123456',
                'ver_status' => 'مؤكد',
            ]);

            $this->assertEquals($req->req_id, $payment->regRequest->req_id);
            $this->assertTrue($req->payments->contains($payment));
        }
    }
}
