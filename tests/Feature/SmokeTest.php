<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_and_route_check_work(): void
    {
        $this->get('/')->assertOk();
        $this->get('/about')->assertOk();
        $this->get('/pricing')->assertOk();
        $this->get('/login')->assertOk();
        $this->get('/daftar')->assertOk();
        $this->get('/forgot-password')->assertOk();

        $this->assertTrue(Route::has('login'));
        $this->assertTrue(Route::has('register'));
        $this->assertTrue(Route::has('s.dashboard.index'));
        $this->get('/__route-check')->assertNotFound();
    }

    public function test_guests_are_redirected_from_protected_dashboards(): void
    {
        $this->get('/superadmin')->assertRedirectToRoute('login');
        $this->get('/admin')->assertRedirectToRoute('login');
        $this->get('/teacher')->assertRedirectToRoute('login');
        $this->get('/student')->assertRedirectToRoute('login');
    }

    public function test_superadmin_dashboard_is_available_for_superadmin_users(): void
    {
        $user = User::factory()->create([
            'role' => 'super admin',
        ]);

        $this->actingAs($user)
            ->get('/superadmin')
            ->assertOk();
    }

    public function test_admin_dashboard_is_available_when_company_is_linked(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        Company::create([
            'user_id' => $user->id,
            'nama_wakil_syarikat' => 'Admin Smoke Test',
            'alamat' => 'Jalan Test 1',
            'no_telefon' => '0123456789',
            'status' => true,
        ]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertOk();
    }

    public function test_teacher_dashboard_is_available_for_teacher_users(): void
    {
        $companyOwner = User::factory()->create([
            'role' => 'admin',
        ]);

        $company = Company::create([
            'user_id' => $companyOwner->id,
            'nama_wakil_syarikat' => 'Teacher Smoke Test',
            'alamat' => 'Jalan Test 2',
            'no_telefon' => '0123456788',
            'status' => true,
        ]);

        $user = User::factory()->create([
            'role' => 'teacher',
        ]);

        Teacher::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'phone' => '0123456787',
            'ic_number' => '900101-01-1234',
            'allowance' => 0,
            'allowance_type' => 'per_hour',
            'bank_name' => 'Test Bank',
            'bank_account_number' => '1234567890',
            'bank_account_holder' => 'Teacher Smoke Test',
            'account_holders_ic_number' => '900101-01-1234',
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->get('/teacher')
            ->assertOk();
    }

    public function test_student_dashboard_is_available_for_student_users(): void
    {
        $companyOwner = User::factory()->create([
            'role' => 'admin',
        ]);

        $company = Company::create([
            'user_id' => $companyOwner->id,
            'nama_wakil_syarikat' => 'Student Smoke Test',
            'alamat' => 'Jalan Test 3',
            'no_telefon' => '0123456786',
            'status' => true,
        ]);

        $user = User::factory()->create([
            'role' => 'student',
        ]);

        Student::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'ic_number' => '990101-01-1234',
            'phone' => '0123456785',
            'address' => 'Kuala Lumpur',
            'notes' => null,
            'sponsor_name' => null,
            'sponsor_phone' => null,
            'sponsor_email' => null,
            'is_approved' => 'no',
        ]);

        $this->actingAs($user)
            ->get('/student')
            ->assertOk();
    }
}
