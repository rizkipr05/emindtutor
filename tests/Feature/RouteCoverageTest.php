<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\CompanyInvoice;
use App\Models\Course;
use App\Models\Student;
use App\Models\StudentInvoice;
use App\Models\SubscriptionPackage;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteCoverageTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_routes_and_language_switch_work(): void
    {
        $company = $this->makeActiveCompany();

        $this->get('/')->assertOk();
        $this->get('/about')->assertOk();
        $this->get('/pricing')->assertOk();
        $this->get(route('compro.companies.index'))->assertOk();
        $this->get(route('compro.companies.show', $company))->assertOk();

        $this->from('/login')
            ->get('/set-language/my')
            ->assertRedirect('/login');

        $this->assertSame('my', session('locale'));

        $this->from('/login')
            ->get('/set-language/en')
            ->assertRedirect('/login');

        $this->assertSame('en', session('locale'));
    }

    public function test_superadmin_pages_render(): void
    {
        $user = User::factory()->create(['role' => 'super admin']);
        $company = $this->makeActiveCompany();
        $package = $this->makePackage();

        $this->actingAs($user)->get(route('dashboard.index'))->assertOk();
        $this->actingAs($user)->get(route('dashboard.index'))->assertSee(route('subscription-packages.index'));
        $this->actingAs($user)->get(route('companies.index'))->assertOk();
        $this->actingAs($user)->get(route('companies.show', $company))->assertOk();
        $this->actingAs($user)->get(route('invoices.index'))->assertOk();
        $this->actingAs($user)->get(route('invoices.create', $company->id))->assertOk();
        $this->actingAs($user)->get(route('subscription-packages.index'))->assertOk();
        $this->actingAs($user)->get(route('subscription-packages.create'))->assertOk();
        $this->actingAs($user)->get(route('subscription-packages.show', $package))->assertOk();
        $this->actingAs($user)->get(route('superadmin.stats.index'))->assertOk();
        $this->actingAs($user)->get(route('superadmin.staff.index'))->assertOk();
        $this->actingAs($user)->get(route('langganan.index'))->assertOk();
    }

    public function test_admin_pages_render_with_linked_company_and_subscription(): void
    {
        [$admin, $company, $package] = $this->makeAdminWithCompanyAndPackage();
        $teacher = $this->makeTeacher($company);
        $student = $this->makeStudent($company);

        $this->actingAs($admin)->get(route('a.dashboard.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.courses.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.courses.create'))->assertOk();
        $this->actingAs($admin)->get(route('admin.teachers.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.students.pending'))->assertOk();
        $this->actingAs($admin)->get(route('admin.students.approved'))->assertOk();
        $this->actingAs($admin)->get(route('admin.staff.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.bank_details.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.reports.index'))->assertOk();
        $this->actingAs($admin)->get(route('analytics.new-students'))->assertOk();
        $this->actingAs($admin)->get(route('analytics.invoices-payments'))->assertOk();
        $this->actingAs($admin)->get(route('analytics.sales'))->assertOk();
        $this->actingAs($admin)->get(route('admin.company.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.subscribe.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.class.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.bayaran.index'))->assertOk();
        $this->actingAs($admin)->get(route('company.reviews.index'))->assertOk();
        $this->actingAs($admin)->get(route('teacher.rules'))->assertOk();
        $this->actingAs($admin)->get(route('teacher.performance'))->assertOk();
        $this->actingAs($admin)->get(route('teacher.salary.index'))->assertOk();
        $this->actingAs($admin)->get(route('subscribe.payment', $package->id))->assertOk();

        $this->assertDatabaseHas('teachers', ['id' => $teacher->id]);
        $this->assertDatabaseHas('students', ['id' => $student->id]);
    }

    public function test_teacher_and_student_pages_render(): void
    {
        [$teacherUser, $company] = $this->makeTeacherUserWithCompany();
        [$studentUser, $student] = $this->makeStudentUserWithCompany($company);

        $this->actingAs($teacherUser)->get(route('t.dashboard.index'))->assertOk();
        $this->actingAs($teacherUser)->get(route('teacher.attendance.index'))->assertOk();
        $this->actingAs($teacherUser)->get(route('teacher.schedule.index'))->assertOk();
        $this->actingAs($teacherUser)->get(route('teacher.schedule.create'))->assertOk();
        $this->actingAs($teacherUser)->get(route('teacher.reviews.index'))->assertOk();
        $this->actingAs($teacherUser)->get(route('teacher.sallaries.index'))->assertOk();
        $this->actingAs($teacherUser)->get(route('teacher.sallaries.customize'))->assertOk();

        $this->actingAs($studentUser)->get(route('s.dashboard.index'))->assertOk();
        $this->actingAs($studentUser)->get(route('student.reviews.index'))->assertOk();
        $this->actingAs($studentUser)->get(route('student.payments.index'))->assertOk();
        $this->actingAs($studentUser)->get(route('student.schedule.index'))->assertOk();
        $this->actingAs($studentUser)->get(route('student.progress.index'))->assertOk();

        $this->assertDatabaseHas('students', ['id' => $student->id]);
    }

    private function makeActiveCompany(): Company
    {
        $owner = User::factory()->create(['role' => 'admin']);

        $company = Company::create([
            'user_id' => $owner->id,
            'nama_wakil_syarikat' => 'Coverage Company',
            'alamat' => 'Jalan Coverage 1',
            'no_telefon' => '0123456000',
            'status' => true,
            'link' => 'https://example.com',
        ]);

        $package = $this->makePackage();

        CompanyInvoice::create([
            'company_id' => $company->id,
            'package_id' => $package->id,
            'nomor_invoice' => 'INV-COVERAGE-001',
            'external_id' => 'EMT-COVERAGE-001',
            'jumlah' => 100000,
            'status' => 'active',
            'status_bayaran' => 'paid',
            'tarikh_terbit' => now()->startOfDay(),
            'tarikh_tamat' => now()->addDays(30)->endOfDay(),
        ]);

        Course::create([
            'company_id' => $company->id,
            'nama_kos' => 'Coverage Course',
            'fee' => 100,
            'max_student' => 10,
            'jenis' => 'individu',
            'teaching_mode' => 'online',
            'minimum_sessions' => 4,
            'minimum_sessions_unit' => 'session',
            'sessions_duration' => 60,
            'sessions_duration_unit' => 'minute',
            'charge_on_registration' => false,
            'include_registration_fee' => false,
            'registration_link' => 'https://example.com/register',
            'description' => 'Coverage course',
        ]);

        return $company;
    }

    private function makePackage(array $overrides = []): SubscriptionPackage
    {
        return SubscriptionPackage::create(array_merge([
            'is_trial' => false,
            'nama_pakej' => 'Coverage Package',
            'duration_days' => 30,
            'harga' => 100000,
            'maks_pelajar' => 10,
            'maks_guru' => 5,
            'maks_kelas' => 3,
            'deskripsi' => 'Coverage package',
            'status' => 'aktif',
            'pricing_per_student' => false,
            'harga_per_pelajar' => null,
        ], $overrides));
    }

    private function makeAdminWithCompanyAndPackage(): array
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $company = Company::create([
            'user_id' => $admin->id,
            'nama_wakil_syarikat' => 'Admin Coverage',
            'alamat' => 'Jalan Admin 1',
            'no_telefon' => '0123456001',
            'status' => true,
        ]);

        $package = $this->makePackage();

        CompanyInvoice::create([
            'company_id' => $company->id,
            'package_id' => $package->id,
            'nomor_invoice' => 'INV-ADMIN-001',
            'external_id' => 'EMT-ADMIN-001',
            'jumlah' => 200000,
            'status' => 'active',
            'status_bayaran' => 'paid',
            'tarikh_terbit' => now()->startOfDay(),
            'tarikh_tamat' => now()->addDays(30)->endOfDay(),
        ]);

        return [$admin, $company, $package];
    }

    private function makeTeacherUserWithCompany(): array
    {
        $owner = User::factory()->create(['role' => 'admin']);
        $company = Company::create([
            'user_id' => $owner->id,
            'nama_wakil_syarikat' => 'Teacher Coverage',
            'alamat' => 'Jalan Teacher 1',
            'no_telefon' => '0123456002',
            'status' => true,
        ]);

        $teacherUser = User::factory()->create(['role' => 'teacher']);

        Teacher::create([
            'user_id' => $teacherUser->id,
            'company_id' => $company->id,
            'phone' => '0123456003',
            'ic_number' => '800101-01-1234',
            'allowance' => 0,
            'allowance_type' => 'per_hour',
            'bank_name' => 'Coverage Bank',
            'bank_account_number' => '1234567890',
            'bank_account_holder' => 'Teacher Coverage',
            'account_holders_ic_number' => '800101-01-1234',
            'status' => 'active',
        ]);

        return [$teacherUser, $company];
    }

    private function makeTeacher(Company $company): Teacher
    {
        $teacherUser = User::factory()->create(['role' => 'teacher']);

        return Teacher::create([
            'user_id' => $teacherUser->id,
            'company_id' => $company->id,
            'phone' => '0123456004',
            'ic_number' => '800101-01-5678',
            'allowance' => 0,
            'allowance_type' => 'per_hour',
            'bank_name' => 'Coverage Bank',
            'bank_account_number' => '9876543210',
            'bank_account_holder' => 'Teacher Coverage 2',
            'account_holders_ic_number' => '800101-01-5678',
            'status' => 'active',
        ]);
    }

    private function makeStudentUserWithCompany(Company $company): array
    {
        $studentUser = User::factory()->create(['role' => 'student']);

        $student = Student::create([
            'user_id' => $studentUser->id,
            'company_id' => $company->id,
            'ic_number' => '990101-01-5678',
            'phone' => '0123456005',
            'address' => 'Kuala Lumpur',
            'notes' => null,
            'sponsor_name' => null,
            'sponsor_phone' => null,
            'sponsor_email' => null,
            'is_approved' => 'no',
        ]);

        return [$studentUser, $student];
    }

    private function makeStudent(Company $company): Student
    {
        $studentUser = User::factory()->create(['role' => 'student']);

        return Student::create([
            'user_id' => $studentUser->id,
            'company_id' => $company->id,
            'ic_number' => '990101-01-9999',
            'phone' => '0123456006',
            'address' => 'Shah Alam',
            'notes' => null,
            'sponsor_name' => null,
            'sponsor_phone' => null,
            'sponsor_email' => null,
            'is_approved' => 'no',
        ]);
    }
}
