<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('تسجيل الدخول');
        $response->assertSee('EduYemen');
        $response->assertSee('eduyemen-logo.svg');
        $response->assertDontSee('شريط التنقل الرئيسي');
    }

    public function test_register_screen_can_be_rendered_with_required_fields_only(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('إنشاء حساب طالب جديد');
        $response->assertSee('full_name');
        $response->assertSee('birth_date');
        $response->assertSee('phone');
        $response->assertSee('username');
        $response->assertSee('password');
        $response->assertSee('password_confirmation');
        $response->assertDontSee('name="gender"', false);
        $response->assertDontSee('name="address"', false);
        $response->assertDontSee('شريط التنقل الرئيسي');
    }

    public function test_student_can_login_and_is_redirected_to_home_or_intended(): void
    {
        $response = $this->post('/login', [
            'username' => 'student1',
            'password' => 'student123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
        $this->assertEquals('student', Auth::user()->user_type);
    }

    public function test_university_can_login_and_is_redirected_to_university_dashboard(): void
    {
        $response = $this->post('/login', [
            'username' => 'univ_saeeda',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/university/dashboard');
        $this->assertAuthenticated();
        $this->assertEquals('university', Auth::user()->user_type);
    }

    public function test_admin_can_login_and_is_redirected_to_admin_dashboard(): void
    {
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticated();
        $this->assertEquals('admin', Auth::user()->user_type);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_inactive_user_cannot_login(): void
    {
        $inactiveUser = User::create([
            'username' => 'inactive_user',
            'password' => 'secret123',
            'user_type' => 'student',
            'account_status' => 'inactive',
        ]);

        $response = $this->post('/login', [
            'username' => 'inactive_user',
            'password' => 'secret123',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();

        $inactiveUser->delete();
    }

    public function test_student_can_register_new_account(): void
    {
        $uniqueUser = 'test_student_' . time();
        $response = $this->post('/register', [
            'full_name' => 'علي صالح الأحمدي',
            'birth_date' => '2005-01-01',
            'phone' => '779988776',
            'username' => $uniqueUser,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['username' => $uniqueUser, 'user_type' => 'student', 'phone' => '779988776']);
        $this->assertDatabaseHas('students', ['full_name' => 'علي صالح الأحمدي', 'birth_date' => '2005-01-01']);
    }

    public function test_student_cannot_access_admin_dashboard(): void
    {
        $studentUser = User::where('username', 'student1')->first();

        $response = $this->actingAs($studentUser)->get('/admin/dashboard');
        $response->assertRedirect('/student/dashboard');
    }

    public function test_university_cannot_access_admin_dashboard(): void
    {
        $univUser = User::where('username', 'univ_saeeda')->first();

        $response = $this->actingAs($univUser)->get('/admin/dashboard');
        $response->assertRedirect('/university/dashboard');
    }

    public function test_user_can_logout_safely(): void
    {
        $user = User::where('username', 'admin')->first();

        $response = $this->actingAs($user)->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
