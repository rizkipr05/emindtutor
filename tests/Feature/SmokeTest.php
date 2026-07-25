<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Course;
use App\Models\Student;
use App\Models\StudentInvoice;
use App\Models\Staff;
use App\Models\Teacher;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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
            ->assertOk()
            ->assertSee(__('super_admin/dashboard.metrics_heading'))
            ->assertSee(__('super_admin/dashboard.recent_companies_heading'))
            ->assertSee(__('super_admin/dashboard.recent_invoices_heading'));
    }

    public function test_superadmin_settings_page_is_available_for_superadmin_users(): void
    {
        $user = User::factory()->create([
            'role' => 'super admin',
        ]);

        $this->actingAs($user)
            ->get(route('superadmin.settings.index'))
            ->assertOk()
            ->assertSee(__('super_admin/settings.heading'))
            ->assertSee(__('super_admin/settings.sections.overview.title'))
            ->assertSee(__('super_admin/settings.features.title'));
    }

    public function test_superadmin_settings_can_be_saved(): void
    {
        $user = User::factory()->create([
            'role' => 'super admin',
        ]);

        $this->actingAs($user)
            ->post(route('superadmin.settings.update', ['section' => 'overview']), [
                'app_name' => 'EMindTutor Prime',
                'default_locale' => 'my',
                'support_email' => 'support@example.com',
            ])
            ->assertRedirect(route('superadmin.settings.index', ['section' => 'overview']))
            ->assertSessionHas('success', __('super_admin/settings.messages.saved'));

        $this->assertDatabaseHas('system_settings', [
            'setting_key' => 'app_name',
            'setting_value' => 'EMindTutor Prime',
        ]);

        $this->assertDatabaseHas('system_settings', [
            'setting_key' => 'default_locale',
            'setting_value' => 'my',
        ]);

        $this->assertDatabaseHas('system_settings', [
            'setting_key' => 'support_email',
            'setting_value' => 'support@example.com',
        ]);
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
            ->assertOk()
            ->assertDontSee(route('t.dashboard.index'))
            ->assertSee(__('admin/dashboard.summary_heading'));
    }

    public function test_admin_menu_is_visible_even_without_active_subscription(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        Company::create([
            'user_id' => $user->id,
            'nama_wakil_syarikat' => 'No Subscription Company',
            'alamat' => 'Jalan Test 1',
            'no_telefon' => '0123456789',
            'status' => true,
        ]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertOk()
            ->assertSee(route('admin.courses.index'))
            ->assertSee(route('admin.teachers.index'))
            ->assertSee(route('admin.students.approved'))
            ->assertSee(route('admin.staff.index'));
    }

    public function test_admin_staff_page_shows_related_user_data(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $company = Company::create([
            'user_id' => $user->id,
            'nama_wakil_syarikat' => 'Staff Page Company',
            'alamat' => 'Jalan Test 9',
            'no_telefon' => '0123456799',
            'status' => true,
        ]);

        $staffUser = User::factory()->create([
            'name' => 'Staff One',
            'email' => 'staff.one@example.com',
            'role' => 'admin',
        ]);

        Staff::create([
            'user_id' => $staffUser->id,
            'company_id' => $company->id,
            'phone' => '0123456780',
            'ic_number' => '900101-01-4321',
            'allowance' => 1000,
            'allowance_type' => 'per_month',
            'bank_name' => 'Test Bank',
            'bank_account_number' => '111222333',
            'bank_account_holder' => 'Staff One',
            'account_holders_ic_number' => '900101-01-4321',
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->get(route('admin.staff.index'))
            ->assertOk()
            ->assertSee('staff.one@example.com')
            ->assertSee('Staff One')
            ->assertSee('Admin');
    }

    public function test_admin_staff_page_shows_company_owner_when_no_staff_exists(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'email' => 'owner.empty@example.com',
        ]);

        Company::create([
            'user_id' => $user->id,
            'nama_wakil_syarikat' => 'Empty Staff Company',
            'alamat' => 'Jalan Test 10',
            'no_telefon' => '0123456798',
            'status' => true,
        ]);

        $this->actingAs($user)
            ->get(route('admin.staff.index'))
            ->assertOk()
            ->assertSee(__('admin/staff/index.owner.title'))
            ->assertSee('owner.empty@example.com');
    }

    public function test_student_payment_is_limited_to_the_invoice_owner_and_webhook_activates_course_access(): void
    {
        Storage::fake('public');

        $companyOwner = User::factory()->create(['role' => 'admin']);
        $company = Company::create([
            'user_id' => $companyOwner->id,
            'nama_wakil_syarikat' => 'Payment Test Company',
            'alamat' => 'Jalan Test 11',
            'no_telefon' => '0123456777',
            'status' => true,
        ]);
        $course = Course::create([
            'company_id' => $company->id,
            'nama_kos' => 'Mathematics',
            'fee' => 100,
            'max_student' => 20,
            'teaching_mode' => 'online',
            'sessions_duration_unit' => 'minute',
            'jenis' => 'individu',
        ]);

        $studentUser = User::factory()->create(['role' => 'student']);
        $student = Student::create([
            'user_id' => $studentUser->id,
            'company_id' => $company->id,
            'ic_number' => '990101-01-1001',
            'phone' => '0123456776',
            'address' => 'Kuala Lumpur',
            'is_approved' => 'yes',
        ]);
        $otherStudentUser = User::factory()->create(['role' => 'student']);
        $otherStudent = Student::create([
            'user_id' => $otherStudentUser->id,
            'company_id' => $company->id,
            'ic_number' => '990101-01-1002',
            'phone' => '0123456775',
            'address' => 'Kuala Lumpur',
            'is_approved' => 'yes',
        ]);

        $ownInvoice = StudentInvoice::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'nomor_invoice' => 'STU-OWN-001',
            'jumlah' => 100,
            'status_bayaran' => 'upcoming',
        ]);
        $otherInvoice = StudentInvoice::create([
            'student_id' => $otherStudent->id,
            'course_id' => $course->id,
            'nomor_invoice' => 'STU-OTHER-001',
            'jumlah' => 100,
            'status_bayaran' => 'upcoming',
        ]);

        $this->actingAs($studentUser)
            ->get(route('student.payments.create', $otherInvoice))
            ->assertNotFound();

        $this->actingAs($studentUser)
            ->post(route('student.payments.store'), [
                'student_invoice_id' => $ownInvoice->id,
                'dibayar_pada' => now()->toDateString(),
                'bukti_bayar' => UploadedFile::fake()->image('proof.jpg'),
            ])
            ->assertRedirect(route('student.payments.index'));

        $this->assertDatabaseHas('student_payments', [
            'student_invoice_id' => $ownInvoice->id,
            'status' => 'pending',
        ]);

        $this->actingAs($studentUser)
            ->post(route('student.payments.store'), [
                'student_invoice_id' => $ownInvoice->id,
                'dibayar_pada' => now()->toDateString(),
                'bukti_bayar' => UploadedFile::fake()->image('duplicate.jpg'),
            ])
            ->assertSessionHasErrors('student_invoice_id');

        $this->withHeader('x-callback-token', (string) config('services.xendit.webhook_secret'))
            ->postJson(route('payment.webhook'), [
            'external_id' => $otherInvoice->nomor_invoice,
            'status' => 'PAID',
        ])->assertOk();

        $this->assertDatabaseHas('student_invoices', [
            'id' => $otherInvoice->id,
            'status_bayaran' => 'paid',
        ]);
        $this->assertDatabaseHas('students', [
            'id' => $otherStudent->id,
            'status' => 'active',
        ]);
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
            ->assertOk()
            ->assertSee(__('teacher/dashboard.overview_heading'));
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
            ->assertOk()
            ->assertSee(__('student/dashboard/dashboard.overview_heading'));
    }
}
