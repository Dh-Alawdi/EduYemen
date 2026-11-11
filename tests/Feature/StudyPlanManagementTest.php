<?php

namespace Tests\Feature;

use App\Models\College;
use App\Models\Course;
use App\Models\Semester;
use App\Models\Specialization;
use App\Models\StudyPlan;
use App\Models\University;
use App\Models\User;
use Tests\TestCase;

class StudyPlanManagementTest extends TestCase
{
    protected User $univUser;
    protected University $university;
    protected Specialization $specialization;

    protected function setUp(): void
    {
        parent::setUp();

        $this->univUser = User::where('user_type', 'university')
            ->whereHas('university.colleges.specializations')
            ->first();

        $this->university = $this->univUser->university;
        $this->specialization = $this->university->specializations()->first();
    }

    public function test_university_can_get_specialization_plans(): void
    {
        $response = $this->actingAs($this->univUser)
            ->getJson(route('university.specializations.plans.get', $this->specialization->specialization_id));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'specialization_id',
            'specialization_name',
            'plans',
        ]);
    }

    public function test_university_can_add_multiple_study_plans_for_specialization(): void
    {
        // إضافة الخطة الأولى
        $resp1 = $this->actingAs($this->univUser)
            ->postJson(route('university.specializations.plans.store', $this->specialization->specialization_id), [
                'plan_name' => 'خطة بكالوريوس مطورة 2026',
                'duration_years' => 4,
                'total_hours' => 136,
                'status' => 'معتمدة',
            ]);

        $resp1->assertStatus(200);
        $resp1->assertJson(['success' => true]);
        $plan1Id = $resp1->json('plan.study_plan_id');

        // إضافة خطة ثانية لنفس التخصص (مسار بديل)
        $resp2 = $this->actingAs($this->univUser)
            ->postJson(route('university.specializations.plans.store', $this->specialization->specialization_id), [
                'plan_name' => 'خطة مسار الذكاء الاصطناعي 2026',
                'duration_years' => 4,
                'total_hours' => 140,
                'status' => 'نشطة',
            ]);

        $resp2->assertStatus(200);
        $resp2->assertJson(['success' => true]);
        $plan2Id = $resp2->json('plan.study_plan_id');

        // التأكد من وجود الخطتين للتخصص في قاعدة البيانات
        $this->assertDatabaseHas('study_plans', [
            'study_plan_id' => $plan1Id,
            'plan_name' => 'خطة بكالوريوس مطورة 2026',
        ]);
        $this->assertDatabaseHas('study_plans', [
            'study_plan_id' => $plan2Id,
            'plan_name' => 'خطة مسار الذكاء الاصطناعي 2026',
        ]);

        // تنظيف
        StudyPlan::whereIn('study_plan_id', [$plan1Id, $plan2Id])->delete();
    }

    public function test_university_can_update_study_plan_details(): void
    {
        $plan = StudyPlan::create([
            'specialization_id' => $this->specialization->specialization_id,
            'plan_name' => 'خطة قديمة 2020',
            'duration_years' => 4,
            'total_hours' => 130,
            'status' => 'قيد الإعداد',
        ]);

        $response = $this->actingAs($this->univUser)
            ->postJson(route('university.plans.update', $plan->study_plan_id), [
                'plan_name' => 'خطة محدثة 2026',
                'duration_years' => 5,
                'total_hours' => 160,
                'status' => 'معتمدة',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('study_plans', [
            'study_plan_id' => $plan->study_plan_id,
            'plan_name' => 'خطة محدثة 2026',
            'duration_years' => 5,
            'total_hours' => 160,
            'status' => 'معتمدة',
        ]);

        $plan->delete();
    }

    public function test_university_can_add_and_delete_course_in_plan(): void
    {
        $plan = StudyPlan::create([
            'specialization_id' => $this->specialization->specialization_id,
            'plan_name' => 'خطة مسار البرمجيات',
            'duration_years' => 4,
            'total_hours' => 0,
            'status' => 'معتمدة',
        ]);

        $response = $this->actingAs($this->univUser)
            ->postJson(route('university.plans.courses.save', $plan->study_plan_id), [
                'semester_number' => 1,
                'course_name' => 'مقدمة في علوم الحاسوب',
                'course_code' => 'CS101',
                'hours' => 3,
                'course_type' => 'متطلب تخصص إجباري',
                'description' => 'المفاهيم الأساسية',
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $courseId = $response->json('course.course_id');

        $this->assertDatabaseHas('courses', [
            'course_id' => $courseId,
            'course_name' => 'مقدمة في علوم الحاسوب',
            'course_code' => 'CS101',
            'hours' => 3,
        ]);

        // حذف المادة
        $delResp = $this->actingAs($this->univUser)
            ->postJson(route('university.plans.courses.delete', $courseId));

        $delResp->assertStatus(200);
        $this->assertDatabaseMissing('courses', [
            'course_id' => $courseId,
        ]);

        $plan->delete();
    }
}
