<?php

namespace Tests\Feature;

use App\Models\AdmissionData;
use App\Models\AdmissionDoc;
use App\Models\College;
use App\Models\Specialization;
use App\Models\University;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\TestCase;

class AdmissionManagementTest extends TestCase
{
    protected User $univUser;
    protected University $university;
    protected Specialization $specialization;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('user_type', 'university')
            ->whereHas('university.colleges.specializations')
            ->first();

        if (!$user) {
            $user = User::where('user_type', 'university')->first();
        }

        $this->univUser = $user;
        $this->university = $this->univUser->university;
        $this->specialization = $this->university->specializations()->first();
    }

    /**
     * 1. تمكين الجامعة من استرجاع بيانات القبول والتسجيل لتخصصها
     */
    public function test_university_can_get_admission_data_for_its_specialization(): void
    {
        $response = $this->actingAs($this->univUser)
            ->getJson(route('university.specializations.admission.get', $this->specialization->specialization_id));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'specialization_id',
            'specialization_name',
            'college_name',
            'tuition_fees',
            'admission' => [
                'admission_id',
                'minimum_average',
                'secondary_last_year',
                'admission_data',
                'description',
                'tuition_fees',
                'registration_fee',
                'admission_start_date',
                'admission_end_date',
                'admission_status',
            ],
            'documents',
        ]);
    }

    /**
     * 2. تمكين الجامعة من إضافة وتعديل شروط القبول، الرسوم، والحد الأدنى وفترة القبول
     */
    public function test_university_can_update_admission_data_and_fees_and_period(): void
    {
        $payload = [
            'minimum_average' => 78.5,
            'secondary_last_year' => 2022,
            'tuition_fees' => 1500,
            'registration_fee' => 7500,
            'admission_start_date' => '2026-07-01',
            'admission_end_date' => '2026-09-15',
            'admission_status' => 'مفتوح',
            'admission_data' => 'اجتياز اختبار المفاضلة في مهارات الحاسوب واللغة الإنجليزية والمقابلة الشخصية.',
            'description' => 'ملاحظات: تسدد رسوم التسجيل خلال 48 ساعة من صدور الموافقة.',
        ];

        $response = $this->actingAs($this->univUser)
            ->postJson(route('university.specializations.admission.update', $this->specialization->specialization_id), $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('admission_data', [
            'specialization_id' => $this->specialization->specialization_id,
            'minimum_average' => 78.5,
            'secondary_last_year' => 2022,
            'registration_fee' => 7500,
            'admission_start_date' => '2026-07-01',
            'admission_end_date' => '2026-09-15',
            'admission_status' => 'مفتوح',
            'admission_data' => $payload['admission_data'],
        ]);

        $this->assertDatabaseHas('specializations', [
            'specialization_id' => $this->specialization->specialization_id,
            'tuition_fees' => 1500,
        ]);
    }

    /**
     * 3. إدارة الوثائق المطلوبة وتحديد ما إذا كانت إلزامية أو اختيارية
     */
    public function test_university_can_manage_required_documents(): void
    {
        $specId = $this->specialization->specialization_id;

        // أ) إضافة وثيقة جديدة للتخصص
        $addResponse = $this->actingAs($this->univUser)
            ->postJson(route('university.specializations.documents.add', $specId), [
                'document_name' => 'شهادة حسن سيرة وسلوك',
                'description' => 'صادرة من المدرسة الثانوية',
                'is_required' => false,
            ]);

        $addResponse->assertStatus(200);
        $addResponse->assertJson(['success' => true]);

        $this->assertDatabaseHas('admission_docs', [
            'document_name' => 'شهادة حسن سيرة وسلوك',
            'is_required' => 0,
        ]);

        $doc = AdmissionDoc::where('document_name', 'شهادة حسن سيرة وسلوك')->first();
        $this->assertNotNull($doc);

        // ب) حفظ قائمة وثائق كاملة للتخصص
        $saveListResponse = $this->actingAs($this->univUser)
            ->postJson(route('university.specializations.documents.save', $specId), [
                'documents' => [
                    ['name' => 'أصل استمارة الثانوية العامة', 'description' => 'مصدقة رسمياً', 'is_required' => true],
                    ['name' => 'صورة البطاقة الشخصية', 'description' => 'سارية', 'is_required' => true],
                    ['name' => 'شهادة الفحص الطبي', 'description' => 'فحص فصيلة الدم والخلو من الأمراض', 'is_required' => false],
                ]
            ]);

        $saveListResponse->assertStatus(200);
        $saveListResponse->assertJson(['success' => true]);

        $this->assertEquals(3, $this->specialization->fresh()->admissionData->docs()->count());
        $this->assertEquals(2, $this->specialization->fresh()->admissionData->docs()->where('is_required', true)->count());
        $this->assertEquals(1, $this->specialization->fresh()->admissionData->docs()->where('is_required', false)->count());

        // ج) حذف وثيقة تابعة للتخصص
        $firstDoc = $this->specialization->fresh()->admissionData->docs()->first();
        $delResponse = $this->actingAs($this->univUser)
            ->postJson(route('university.specializations.documents.delete', $firstDoc->doc_id));

        $delResponse->assertStatus(200);
        $this->assertDatabaseMissing('admission_docs', ['doc_id' => $firstDoc->doc_id]);
    }

    /**
     * 4. التحقق من الصلاحيات والتحكم بالأمان: لا يمكن لجامعة تعديل تخصص يتبع لجامعة أخرى
     */
    public function test_university_cannot_modify_other_university_specialization(): void
    {
        $otherUniv = University::where('university_id', '!=', $this->university->university_id)->first();
        $otherSpec = $otherUniv->specializations()->first();

        if ($otherSpec) {
            $response = $this->actingAs($this->univUser)
                ->postJson(route('university.specializations.admission.update', $otherSpec->specialization_id), [
                    'minimum_average' => 99.0,
                    'secondary_last_year' => 2025,
                    'admission_status' => 'مغلق',
                    'admission_data' => 'محاولة تعديل غير مصرح بها',
                ]);

            $response->assertStatus(404);
        }
    }

    /**
     * 5. التحقق من صحة المدخلات (Validation rules)
     */
    public function test_admission_input_validation(): void
    {
        // معدل خارج النطاق المقبول
        $response = $this->actingAs($this->univUser)
            ->postJson(route('university.specializations.admission.update', $this->specialization->specialization_id), [
                'minimum_average' => 150, // غير صالح
                'secondary_last_year' => 1980, // غير صالح
                'admission_status' => 'حالة_غير_معروفة',
                'admission_data' => '',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['minimum_average', 'secondary_last_year', 'admission_status', 'admission_data']);
    }

    /**
     * 6. إتاحة عرض هذه البيانات للطالب عبر صفحة التخصص المعني ونقطة العرض العامة
     */
    public function test_student_and_public_can_view_specialization_admission_data(): void
    {
        $specId = $this->specialization->specialization_id;

        $response = $this->getJson(route('specializations.admission.public', $specId));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'specialization' => [
                'id',
                'name',
                'college_name',
                'university_name',
                'tuition_fees',
                'duration_years',
                'language',
                'status',
            ],
            'admission' => [
                'minimum_average',
                'secondary_last_year',
                'admission_data',
                'registration_fee',
                'admission_start_date',
                'admission_end_date',
                'admission_status',
                'documents',
            ]
        ]);

        $this->assertEquals($this->specialization->specialization_name, $response->json('specialization.name'));
    }

    /**
     * 7. شرط أساسي: كل بيانات قبول وتسجيل ترتبط بتخصص محدد ولا تختلط ببيانات التخصصات الأخرى
     */
    public function test_admission_data_strict_isolation_between_specializations(): void
    {
        $specs = Specialization::where('college_id', $this->specialization->college_id)->take(2)->get();
        if ($specs->count() < 2) {
            $specs = Specialization::take(2)->get();
        }

        $specA = $specs[0];
        $specB = $specs[1];

        // تعيين بيانات مميزة للتخصص A
        $this->actingAs($this->univUser)->postJson(route('university.specializations.admission.update', $specA->specialization_id), [
            'minimum_average' => 83.2,
            'secondary_last_year' => 2023,
            'tuition_fees' => 1800,
            'registration_fee' => 6000,
            'admission_start_date' => '2026-07-10',
            'admission_end_date' => '2026-09-20',
            'admission_status' => 'مفتوح',
            'admission_data' => 'شروط خاصة بالتخصص الأول الفريد A',
        ]);

        // تعيين بيانات مميزة للتخصص B
        $this->actingAs($this->univUser)->postJson(route('university.specializations.admission.update', $specB->specialization_id), [
            'minimum_average' => 65.0,
            'secondary_last_year' => 2018,
            'tuition_fees' => 950,
            'registration_fee' => 3000,
            'admission_start_date' => '2026-08-01',
            'admission_end_date' => '2026-10-01',
            'admission_status' => 'قريباً',
            'admission_data' => 'شروط خاصة بالتخصص الثاني الفريد B',
        ]);

        $dataA = $specA->fresh()->admissionData;
        $dataB = $specB->fresh()->admissionData;

        // التأكد من استقلال السجلين تماماً
        $this->assertNotEquals($dataA->admission_id, $dataB->admission_id);
        $this->assertEquals(83.2, (float)$dataA->minimum_average);
        $this->assertEquals(65.0, (float)$dataB->minimum_average);
        $this->assertEquals('شروط خاصة بالتخصص الأول الفريد A', $dataA->admission_data);
        $this->assertEquals('شروط خاصة بالتخصص الثاني الفريد B', $dataB->admission_data);
        $this->assertEquals('مفتوح', $dataA->admission_status);
        $this->assertEquals('قريباً', $dataB->admission_status);
    }

    /**
     * 8. صفحة الكليات والتخصصات تبدأ بدون اختيار تلقائي لأي كلية أو تخصص وتطلب التحديد اليدوي
     */
    public function test_colleges_tab_requires_manual_college_and_specialty_selection(): void
    {
        $response = $this->actingAs($this->univUser)
            ->get(route('university.dashboard', ['tab' => 'colleges']));

        $response->assertStatus(200);
        $response->assertSee('اختر الكلية أولاً');
        $response->assertSee('يرجى اختيار الكلية أولاً');
        $response->assertSee('يرجى تحديد الكلية والتخصص الأكاديمي');
        $response->assertSee('specialtyEmptyPrompt');
        $response->assertSee('specialtyDetailsSection');
    }

    /**
     * 9. التحقق من تعديلات الصفحة الرئيسية للجامعة والشريط العلوي:
     * - إزالة حالة القبول وزر المعاينة من الشريط العلوي
     * - عرض قسم حول بعد حاوية الترحيب والوقت
     * - ظهور بطاقة معاينة صفحة الجامعة للطالب ضمن البطاقات بعد "تنبيهات مهمة"
     */
    public function test_university_dashboard_header_and_overview_layout(): void
    {
        $response = $this->actingAs($this->univUser)
            ->get(route('university.dashboard'));

        $response->assertStatus(200);

        // الشريط العلوي لا يحتوي على حالة القبول
        $response->assertDontSee('id="headerAdmissionStatusPill"', false);

        // الصفحة الرئيسية تحتوي على بطاقة معاينة صفحة الجامعة للطالب
        $response->assertSee('معاينة صفحة الجامعة للطالب');
        $response->assertSee('تنبيهات مهمة');

        // التحقق من ترتيب ظهور بطاقة المعاينة بعد تنبيهات مهمة
        $content = $response->getContent();
        $posAlerts = strpos($content, 'تنبيهات مهمة');
        $posPreview = strpos($content, 'معاينة صفحة الجامعة للطالب', $posAlerts);
        $this->assertNotFalse($posPreview, 'يجب أن تظهر بطاقة معاينة صفحة الجامعة للطالب بعد تنبيهات مهمة');

        // التحقق من ظهور قسم حول بعد حاوية الترحيب والوقت
        $posWelcome = strpos($content, 'summary-welcome');
        $posAbout = strpos($content, 'id="aboutSection"');
        $this->assertNotFalse($posWelcome);
        $this->assertNotFalse($posAbout);
        $this->assertGreaterThan($posWelcome, $posAbout, 'يجب أن يظهر قسم حول بعد حاوية الترحيب والوقت');
    }
}
