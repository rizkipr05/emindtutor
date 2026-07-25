<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanyInvoice;
use App\Models\Course;
use App\Models\Staff;
use App\Models\Student;
use App\Models\StudentInvoice;
use App\Models\SubscriptionPackage;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();
        $package = SubscriptionPackage::query()
            ->where('is_trial', true)
            ->orWhere('nama_pakej', 'PERCUBAAN')
            ->first();

        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Admin eMindTutor',
                'password' => 'SuperAdmin123!',
                'role' => 'super admin',
            ]
        );

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Demo',
                'password' => 'Admin123!',
                'role' => 'admin',
            ]
        );

        $teacherLogin = User::updateOrCreate(
            ['email' => 'teacher@example.com'],
            [
                'name' => 'Teacher Demo',
                'password' => 'Teacher123!',
                'role' => 'teacher',
            ]
        );

        $studentLogin = User::updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student Demo',
                'password' => 'Student123!',
                'role' => 'student',
            ]
        );

        $company = Company::firstOrCreate(
            ['user_id' => $admin->id],
            [
                'nama_wakil_syarikat' => 'Admin Demo',
                'pic_telefon' => '011-2000000',
                'pic_email' => 'admin@example.com',
                'alamat' => 'Demo Address',
                'no_telefon' => '011-2000000',
                'nombor_pendaftaran_syarikat' => 'EMT-DEMO-0004',
                'status' => 1,
            ]
        );

        if ($package) {
            CompanyInvoice::updateOrCreate(
                [
                    'company_id' => $company->id,
                    'package_id' => $package->id,
                ],
                [
                    'nomor_invoice' => 'EMT-DEMO-COMPANY-20',
                    'external_id' => 'EMT-DEMO-COMPANY-20',
                    'xendit_id' => null,
                    'xendit_invoice_url' => null,
                    'jumlah' => $package->harga ?? 0,
                    'status' => 'active',
                    'status_bayaran' => 'paid',
                    'tarikh_terbit' => $today,
                    'tarikh_tamat' => $today->copy()->addDays((int) ($package->duration_days ?: 30)),
                ]
            );
        }

        Course::updateOrCreate(
            [
                'company_id' => $company->id,
                'nama_kos' => 'Kelas Demo EMindTutor',
            ],
            [
                'fee' => 50,
                'max_student' => 20,
                'jenis' => 'individu',
                'teaching_mode' => 'online',
                'minimum_sessions' => 4,
                'minimum_sessions_unit' => 'session',
                'sessions_duration' => 60,
                'sessions_duration_unit' => 'minute',
                'charge_on_registration' => true,
                'include_registration_fee' => false,
                'registration_link' => null,
                'description' => 'Kelas demo untuk memaparkan data pada paparan admin.',
                'instructions_template' => null,
                'session_notes_template' => null,
            ]
        );

        $staffUser = User::updateOrCreate(
            ['email' => 'staff.demo@emindtutor.test'],
            [
                'name' => 'Staff Demo',
                'password' => 'Staff123!',
                'role' => 'admin',
            ]
        );

        Staff::updateOrCreate(
            ['user_id' => $staffUser->id],
            [
                'company_id' => $company->id,
                'phone' => '011-2000001',
                'ic_number' => 'STAFF-DEMO-001',
                'allowance' => 200,
                'allowance_type' => 'per_month',
                'bank_name' => 'MAYBANK',
                'bank_account_number' => '1002003004',
                'bank_account_holder' => 'Staff Demo',
                'account_holders_ic_number' => 'STAFF-DEMO-001',
                'status' => 'active',
            ]
        );

        $teacherActiveUser = User::updateOrCreate(
            ['email' => 'teacher.demo.active@emindtutor.test'],
            [
                'name' => 'Teacher Demo Active',
                'password' => 'Teacher123!',
                'role' => 'teacher',
            ]
        );

        Teacher::updateOrCreate(
            ['user_id' => $teacherActiveUser->id],
            [
                'company_id' => $company->id,
                'phone' => '011-2000002',
                'ic_number' => 'TCH-DEMO-001',
                'allowance' => 25,
                'allowance_type' => 'per_hour',
                'bank_name' => 'CIMB BANK',
                'bank_account_number' => '2002003004',
                'bank_account_holder' => 'Teacher Demo Active',
                'account_holders_ic_number' => 'TCH-DEMO-001',
                'status' => 'active',
            ]
        );

        $teacherInactiveUser = User::updateOrCreate(
            ['email' => 'teacher.demo.inactive@emindtutor.test'],
            [
                'name' => 'Teacher Demo Inactive',
                'password' => 'Teacher123!',
                'role' => 'teacher',
            ]
        );

        Teacher::updateOrCreate(
            ['user_id' => $teacherInactiveUser->id],
            [
                'company_id' => $company->id,
                'phone' => '011-2000003',
                'ic_number' => 'TCH-DEMO-002',
                'allowance' => 20,
                'allowance_type' => 'per_hour',
                'bank_name' => 'PUBLIC BANK',
                'bank_account_number' => '3002003004',
                'bank_account_holder' => 'Teacher Demo Inactive',
                'account_holders_ic_number' => 'TCH-DEMO-002',
                'status' => 'inactive',
            ]
        );

        $approvedActiveUser = User::updateOrCreate(
            ['email' => 'student.demo.active@emindtutor.test'],
            [
                'name' => 'Student Demo Active',
                'password' => 'Student123!',
                'role' => 'student',
            ]
        );

        Student::updateOrCreate(
            ['user_id' => $approvedActiveUser->id],
            [
                'company_id' => $company->id,
                'ic_number' => 'STD-DEMO-ACTIVE',
                'phone' => '011-3000001',
                'address' => 'Demo Student Address',
                'notes' => null,
                'sponsor_name' => null,
                'sponsor_phone' => null,
                'sponsor_email' => null,
                'is_approved' => 'yes',
                'status' => 'active',
                'sub_start' => null,
                'sub_end' => null,
            ]
        );

        $approvedPendingUser = User::updateOrCreate(
            ['email' => 'student.demo.pending@emindtutor.test'],
            [
                'name' => 'Student Demo Pending',
                'password' => 'Student123!',
                'role' => 'student',
            ]
        );

        Student::updateOrCreate(
            ['user_id' => $approvedPendingUser->id],
            [
                'company_id' => $company->id,
                'ic_number' => 'STD-DEMO-PENDING',
                'phone' => '011-3000002',
                'address' => 'Demo Student Address',
                'notes' => null,
                'sponsor_name' => null,
                'sponsor_phone' => null,
                'sponsor_email' => null,
                'is_approved' => 'yes',
                'status' => 'pending',
                'sub_start' => null,
                'sub_end' => null,
            ]
        );

        $approvedExpiredUser = User::updateOrCreate(
            ['email' => 'student.demo.expired@emindtutor.test'],
            [
                'name' => 'Student Demo Expired',
                'password' => 'Student123!',
                'role' => 'student',
            ]
        );

        Student::updateOrCreate(
            ['user_id' => $approvedExpiredUser->id],
            [
                'company_id' => $company->id,
                'ic_number' => 'STD-DEMO-EXPIRED',
                'phone' => '011-3000003',
                'address' => 'Demo Student Address',
                'notes' => null,
                'sponsor_name' => null,
                'sponsor_phone' => null,
                'sponsor_email' => null,
                'is_approved' => 'yes',
                'status' => 'expired',
                'sub_start' => null,
                'sub_end' => null,
            ]
        );

        $waitingUser = User::updateOrCreate(
            ['email' => 'student.demo.waiting@emindtutor.test'],
            [
                'name' => 'Student Demo Waiting',
                'password' => 'Student123!',
                'role' => 'student',
            ]
        );

        $waitingStudent = Student::updateOrCreate(
            ['user_id' => $waitingUser->id],
            [
                'company_id' => $company->id,
                'ic_number' => 'STD-DEMO-WAITING',
                'phone' => '011-3000004',
                'address' => 'Demo Student Address',
                'notes' => null,
                'sponsor_name' => null,
                'sponsor_phone' => null,
                'sponsor_email' => null,
                'is_approved' => 'no',
                'status' => 'pending',
                'sub_start' => null,
                'sub_end' => null,
            ]
        );

        $activeStudent = Student::where('user_id', $approvedActiveUser->id)->first();
        $pendingStudent = Student::where('user_id', $approvedPendingUser->id)->first();
        $expiredStudent = Student::where('user_id', $approvedExpiredUser->id)->first();

        if ($activeStudent) {
            StudentInvoice::updateOrCreate(
                ['nomor_invoice' => 'EMT-DEMO-STUDENT-ACTIVE'],
                [
                    'student_id' => $activeStudent->id,
                    'course_id' => Course::where('company_id', $company->id)->value('id'),
                    'jumlah' => 50,
                    'status_bayaran' => 'paid',
                    'tarikh_terbit' => $today,
                    'tarikh_tamat' => $today->copy()->addDays(30),
                ]
            );
        }

        if ($pendingStudent) {
            StudentInvoice::updateOrCreate(
                ['nomor_invoice' => 'EMT-DEMO-STUDENT-PENDING'],
                [
                    'student_id' => $pendingStudent->id,
                    'course_id' => Course::where('company_id', $company->id)->value('id'),
                    'jumlah' => 50,
                    'status_bayaran' => 'upcoming',
                    'tarikh_terbit' => $today,
                    'tarikh_tamat' => $today->copy()->addDays(7),
                ]
            );
        }

        if ($expiredStudent) {
            StudentInvoice::updateOrCreate(
                ['nomor_invoice' => 'EMT-DEMO-STUDENT-EXPIRED'],
                [
                    'student_id' => $expiredStudent->id,
                    'course_id' => Course::where('company_id', $company->id)->value('id'),
                    'jumlah' => 50,
                    'status_bayaran' => 'overdue',
                    'tarikh_terbit' => $today->copy()->subDays(40),
                    'tarikh_tamat' => $today->copy()->subDays(10),
                ]
            );
        }

        StudentInvoice::updateOrCreate(
            ['nomor_invoice' => 'EMT-DEMO-STUDENT-WAITING'],
            [
                'student_id' => $waitingStudent->id,
                'course_id' => Course::where('company_id', $company->id)->value('id'),
                'jumlah' => 50,
                'status_bayaran' => 'upcoming',
                'tarikh_terbit' => $today,
                'tarikh_tamat' => $today->copy()->addDays(7),
            ]
        );

        $superAdmin->refresh();
        $admin->refresh();
        $teacherLogin->refresh();
        $studentLogin->refresh();
    }
}
