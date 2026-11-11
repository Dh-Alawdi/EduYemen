<?php

namespace Tests\Feature;

use App\Models\University;
use App\Models\User;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('EduYemen');
        $response->assertSee('المنصة الموحدة');
        $response->assertSee('univ-card-footer-actions', false);
        $response->assertSee('الانتقال إلى صفحة الجامعة');
        $response->assertSee('btn-univ-compare', false);
        $response->assertSee('مقارنة');

        // زر تسجيل الدخول يوجه مباشرة لصفحة الدخول بدون نافذة منبثقة
        $response->assertDontSee('data-bs-target="#loginModal"', false);
        $response->assertDontSee('id="loginModal"', false);
        $response->assertSee(route('login'));
    }

    public function test_navbar_authenticated_user_shows_profile_avatar_without_name_or_role(): void
    {
        $studentUser = User::where('username', 'student1')->first();
        $response = $this->actingAs($studentUser)->get('/');

        $response->assertStatus(200);
        $response->assertSee('btn-nav-profile-avatar', false);
        $response->assertSee('nav-avatar-circle', false);
        $response->assertSee('الملف الشخصي');
        $response->assertSee('تسجيل الخروج');

        // لا يتم عرض اسم المستخدم ولا شارة نوعه في زر الشريط العلوي
        $response->assertDontSee('class="btn btn-nav-login d-flex align-items-center gap-2"', false);
    }

    public function test_universities_index_page_loads(): void
    {
        $response = $this->get('/universities');
        $response->assertStatus(200);
        $response->assertSee('دليل الجامعات');
        $response->assertSee('جامعة صنعاء');
        $response->assertSee('univ-card-footer-actions', false);
        $response->assertSee('الانتقال إلى صفحة الجامعة');
        $response->assertSee('btn-univ-compare', false);
        $response->assertSee('مقارنة');

        // بطاقات الجامعات تعرض عدد الكليات والتخصص المتاح بنسق موحد
        $response->assertSee('عدد الكليات');
        $response->assertSee('تخصص متاح');
    }

    public function test_universities_index_can_filter_by_type(): void
    {
        $response = $this->get('/universities?type=حكومية');
        $response->assertStatus(200);
        $response->assertSee('جامعة صنعاء');
    }

    public function test_universities_index_can_search(): void
    {
        $response = $this->get('/universities?search=صنعاء');
        $response->assertStatus(200);
        $response->assertSee('جامعة صنعاء');
    }

    public function test_university_show_page_loads(): void
    {
        $univ = University::where('university_name', 'جامعة صنعاء')->first();
        $this->assertNotNull($univ);

        $response = $this->get("/universities/{$univ->university_id}");
        $response->assertStatus(200);
        $response->assertSee('جامعة صنعاء');
        $response->assertSee('الكليات والتخصصات');
        $response->assertSee('btn-hamburger-menu');
        $response->assertSee('uni-hero-grid');
        $response->assertSee('الحرم الجامعي');
    }

    public function test_university_show_has_colleges_and_specs(): void
    {
        $univ = University::where('university_name', 'جامعة صنعاء')->first();
        $response = $this->get("/universities/{$univ->university_id}");
        $response->assertStatus(200);
        $response->assertSee('الحد الأدنى للقبول');
        $response->assertSee('الرسوم الدراسية');
        $response->assertSee('الوثائق المطلوبة');
    }

    public function test_university_show_returns_404_for_invalid_id(): void
    {
        $response = $this->get('/universities/99999');
        $response->assertStatus(404);
    }

    public function test_compare_page_loads(): void
    {
        $response = $this->get('/compare');
        $response->assertStatus(200);
        $response->assertSee('مقارنة الجامعات');

        // خيارات عرض المقارنة بحسب معروضة في نموذج التحديد بدون تكرار خارجي
        $response->assertSee('compareScopeSelectorRow', false);
        $response->assertSee('عرض المقارنة بحسب');
        $response->assertSee('كافة المعلومات الشاملة');
        $response->assertSee('الكليات والتخصصات المتاحة');
        $response->assertDontSee('scope-filters-bar', false);

        // إزالة كلمة افتراضي من زر كافة المعلومات الشاملة وازالة الملاحظة
        $response->assertDontSee('كافة المعلومات الشاملة (افتراضي)');
        $response->assertDontSee('كافة المعلومات الشاملة افتراضي');
        $response->assertDontSee('المقارنة تشمل كافة المعلومات تلقائياً');

        // زر إعادة الضبط بجانب حقول الجامعات
        $response->assertSee('إعادة ضبط');

        // زر بدء المقارنة الكبير وعلامة التحميل المضمنة
        $response->assertSee('btn-start-compare-lg', false);
        $response->assertSee('compareLoadingIndicator', false);
    }

    public function test_compare_page_with_selections(): void
    {
        $univs = University::where('status', 'نشطة')->take(2)->get();
        $u1 = $univs->first()->university_id;
        $u2 = $univs->last()->university_id;

        $response = $this->get("/compare?u1={$u1}&u2={$u2}");
        $response->assertStatus(200);
        $response->assertSee('وجه المقارنة');
        $response->assertSee('id="comparisonResults"', false);
        $response->assertDontSee('scope-filters-bar', false);
    }

    public function test_ai_advisor_page_loads(): void
    {
        $response = $this->get('/ai-advisor');
        $response->assertStatus(200);
        $response->assertSee('المساعد الذكي');
        $response->assertSee('قيد التطوير');
    }
}
