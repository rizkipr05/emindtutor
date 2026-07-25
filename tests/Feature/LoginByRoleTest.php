<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class LoginByRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_login_redirects_to_superadmin_dashboard(): void
    {
        $user = $this->makeUser('super admin');

        $this->post(route('login_post'), [
            'email' => $user->email,
            'password' => 'Password123!',
        ])->assertRedirect(route('dashboard.index'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_login_redirects_to_admin_dashboard(): void
    {
        $user = $this->makeUser('admin');
        $this->makeCompany($user);

        $this->post(route('login_post'), [
            'email' => $user->email,
            'password' => 'Password123!',
        ])->assertRedirect(route('a.dashboard.index'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_teacher_login_redirects_to_teacher_dashboard(): void
    {
        $owner = $this->makeUser('admin', 'owner@example.com');
        $company = $this->makeCompany($owner);

        $user = $this->makeUser('teacher');
        $this->makeTeacher($user, $company);

        $this->post(route('login_post'), [
            'email' => $user->email,
            'password' => 'Password123!',
        ])->assertRedirect(route('t.dashboard.index'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_student_login_redirects_to_student_dashboard(): void
    {
        $owner = $this->makeUser('admin', 'owner-student@example.com');
        $company = $this->makeCompany($owner);

        $user = $this->makeUser('student');
        $this->makeStudent($user, $company);

        $this->post(route('login_post'), [
            'email' => $user->email,
            'password' => 'Password123!',
        ])->assertRedirect(route('s.dashboard.index'));

        $this->assertAuthenticatedAs($user);
    }

    private function makeUser(string $role, ?string $email = null): User
    {
        return User::factory()->create([
            'name' => ucfirst(str_replace(' ', '', $role)) . ' Login',
            'email' => $email ?? Str::slug($role, '.') . '.login@example.com',
            'password' => Hash::make('Password123!'),
            'role' => $role,
        ]);
    }

    private function makeCompany(User $owner): Company
    {
        return Company::create([
            'user_id' => $owner->id,
            'nama_wakil_syarikat' => 'Login Test Company',
            'alamat' => 'Jalan Login 1',
            'no_telefon' => '0123456000',
            'status' => true,
        ]);
    }

    private function makeTeacher(User $user, Company $company): Teacher
    {
        return Teacher::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'phone' => '0123456001',
            'ic_number' => '900101-01-1234',
            'allowance' => 0,
            'allowance_type' => 'per_hour',
            'bank_name' => 'Test Bank',
            'bank_account_number' => '1234567890',
            'bank_account_holder' => 'Teacher Login',
            'account_holders_ic_number' => '900101-01-1234',
            'status' => 'active',
        ]);
    }

    private function makeStudent(User $user, Company $company): Student
    {
        return Student::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'ic_number' => '990101-01-1234',
            'phone' => '0123456002',
            'address' => 'Kuala Lumpur',
            'notes' => null,
            'sponsor_name' => null,
            'sponsor_phone' => null,
            'sponsor_email' => null,
            'is_approved' => 'no',
        ]);
    }
}
