<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\WebAuthnController;
use App\Http\Controllers\SuperAdmin\DashboardController as SADashController;
use App\Http\Controllers\Admin\DashboardController as ADashController;
use App\Http\Controllers\Teacher\DashboardController as TDashController;
use App\Http\Controllers\SuperAdmin\CompanyController;
use App\Http\Controllers\SuperAdmin\SubscriptionPackageController;
use App\Http\Controllers\SuperAdmin\LanggananController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\StudentController;
// use App\Http\Controllers\Admin\StudentApprovalController;
use App\Http\Controllers\Admin\SallaryController;
use App\Http\Controllers\Teacher\ScheduleController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Compro\HomeController;
use App\Http\Controllers\Compro\PricingController;
use App\Http\Controllers\Teacher\AttendanceController as TeacherAttendanceController;
use App\Http\Controllers\Teacher\TeachingReportController;
use App\Http\Controllers\Admin\RuleController;
use App\Http\Controllers\Student\DashboardController as SDashController;
use App\Http\Controllers\Student\TeacherReviewController;
use App\Http\Controllers\Admin\TeacherUnavailabilityController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\SuperAdmin\CompanyInvoiceController;
use App\Http\Controllers\SuperAdmin\StatsController;
use App\Http\Controllers\SuperAdmin\SettingsController;
use App\Http\Controllers\SuperAdmin\SubAktifController;
use App\Http\Controllers\Compro\AboutController;
use App\Http\Controllers\Compro\CompanyController as ComproCompanyController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\SubscribeController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\MonthlyStudentReportController;
use App\Http\Controllers\Admin\ClassScheduleController;
use App\Http\Controllers\Admin\BayaranController;
use App\Http\Controllers\Teacher\ReviewController;
use App\Http\Controllers\Teacher\SallaryController as TeacherSallaryController;
use App\Http\Controllers\Student\PaymentController as StudentPaymentController;
use App\Http\Controllers\Student\ScheduleController as StudentScheduleController;
use App\Http\Controllers\Student\ProgressController;
use App\Http\Controllers\Student\MonthlyReportController;
use App\Http\Controllers\Student\CommunicationController;
use App\Http\Controllers\Admin\BonusController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\CompanyController as ACompanyController;
use App\Http\Controllers\Admin\TestController;
use Lab404\Impersonate\Services\ImpersonateManager;
use App\Http\Controllers\Admin\ReviewController as CompanyReviewController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Student\InvoiceController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\StudentAttendanceController as AdminStudentAttendanceController;
use App\Http\Controllers\SuperAdmin\WhatsAppReminderTemplateController;

Route::group(['middleware' => ['localization']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('compro.index');
    Route::get('/pricing', [PricingController::class, 'index'])->name('compro.pricing.index');
    Route::get('/about', [AboutController::class, 'index'])->name('compro.about.index');
    Route::get('/companies', [ComproCompanyController::class, 'index'])->name('compro.companies.index');
    Route::get('/companies/{company}', [ComproCompanyController::class, 'show'])->name('compro.companies.show');
});

Route::post('/payment/toyyibpay-callback', [PaymentController::class, 'webhook'])->name('payment.webhook');

// Rute untuk mengganti bahasa
Route::get('/set-language/{lang}', [LanguageController::class, 'switch'])
    ->where('lang', 'en|my')
    ->middleware('localization')
    ->name('set.language');

//WA API Test Route
Route::get('/test-wa', [TestController::class, 'test']);

Route::get('/__route-check', function () {
    abort_unless(app()->environment('local'), 404);

    return response()->json([
        'app_path' => base_path(),
        'login_route_exists' => Route::has('login'),
        'register_route_exists' => Route::has('register'),
        'student_route_exists' => Route::has('s.dashboard.index'),
        'time' => now()->toDateTimeString(),
    ]);
});

Route::get('/studet', fn () => redirect('/student'));
Route::get('/studet/{any}', fn () => redirect('/student'))->where('any', '.*');

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'login_post')->name('login_post');
    Route::get('/signin', 'login')->name('signin');
    Route::get('/sign-in', 'login')->name('sign-in');
    Route::post('/signin', 'login_post')->name('signin_post');
    Route::post('/sign-in', 'login_post')->name('sign-in_post');
    Route::get('/daftar', 'register')->name('register');
    Route::post('/daftar', 'register_post')->name('register_post');
    Route::get('/register', 'register')->name('register.alias');
    Route::get('/signup', 'register')->name('signup');
    Route::get('/sign-up', 'register')->name('sign-up');
    Route::post('/register', 'register_post')->name('register.alias_post');
    Route::post('/signup', 'register_post')->name('signup_post');
    Route::post('/sign-up', 'register_post')->name('sign-up_post');
});

Route::controller(WebAuthnController::class)->group(function () {
    Route::post('/webauthn/login/options', 'loginOptions')->name('webauthn.login.options');
    Route::post('/webauthn/login', 'login')->name('webauthn.login');
});

// Halaman input email
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
// Proses kirim email link reset
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// Halaman input password baru (link dari email)
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
// Proses update password ke database
Route::post('reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::put('/logout', [AuthController::class, 'update'])->middleware('auth')->name('update');

// payment
Route::get('/subscribe/start/{package}', [PaymentController::class, 'start'])->name('subscribe.start');
Route::post('/registration/toyyibpay-callback', [AuthController::class, 'registrationPaymentCallback'])->name('registration.payment.callback');
Route::get('/registration/payment/{registration}/return', [AuthController::class, 'registrationPaymentReturn'])->name('registration.payment.return');

Route::get('/impersonate/leave', function (ImpersonateManager $impersonate) {
    // dd($impersonate);
    if ($impersonate->isImpersonating()) {
        $impersonate->leave();
    }
    return redirect()->route('dashboard.index'); // arahkan balik ke dashboard superadmin
})->name('impersonate.leave');

//route khusus auth saja (semua role) - ganti
Route::middleware(['auth', 'localization'])->group(function () {
    Route::controller(CommunicationController::class)->prefix('broadcasts')->name('broadcasts.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
    });

    Route::controller(WebAuthnController::class)->group(function () {
        Route::post('/webauthn/register/options', 'registerOptions')->name('webauthn.register.options');
        Route::post('/webauthn/register', 'register')->name('webauthn.register');
    });
});

Route::prefix('superadmin')->middleware(['auth', 'super admin', 'localization'])->group(function () {
    Route::controller(WhatsAppReminderTemplateController::class)->prefix('whatsapp-reminder-templates')->name('superadmin.whatsapp-reminder-templates.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/fonnte-token', 'updateFonnteToken')->name('fonnte-token.update');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::post('/{template}/send', 'send')->name('send');
        Route::get('/{template}/edit', 'edit')->name('edit');
        Route::put('/{template}', 'update')->name('update');
        Route::delete('/{template}', 'destroy')->name('destroy');
    });

    Route::get('/impersonate/{id}', function ($id) {
        $user = \App\Models\User::findOrFail($id);
        Auth::user()->impersonate($user);
        return redirect('/admin'); // arahkan ke dashboard admin syarikat
    })->name('impersonate');

    Route::controller(SADashController::class)->group(function () {
        Route::get('/', 'index')->name('dashboard.index');
    });
    Route::controller(CompanyController::class)->group(function () {
        Route::get('/syarikat', 'index')->name('companies.index');
        Route::get('/syarikat/tambah', 'create')->name('companies.create');
        Route::post('/syarikat/tambah', 'store')->name('companies.store');
        Route::get('/syarikat/{company}/account', 'account')->name('companies.account');
        Route::get('/syarikat/{company}', 'show')->name('companies.show');
        Route::get('/syarikat/{company}/edit', 'edit')->name('companies.edit');
        Route::put('/syarikat/{company}', 'update')->name('companies.update');
        Route::delete('/syarikat/{company}', 'destroy')->name('companies.destroy');
        Route::post('/companies/{id}/toggle-status', [CompanyController::class, 'toggleStatus'])->name('companies.toggleStatus');
        Route::post('/syarikat/{company}/invoice-reminder', 'sendInvoiceReminder')->name('companies.invoice-reminder');
        Route::post('/syarikat/{company}/manual-invoices', 'storeManualInvoice')->name('companies.manual-invoices.store');
        Route::post('/syarikat/{company}/manual-payments', 'storeManualPayment')->name('companies.manual-payments.store');
        Route::post('/companies/{id}/login-as', [CompanyController::class, 'loginAs'])->name('companies.loginAs');
    });
    Route::resource('subscription-packages', SubscriptionPackageController::class);
    Route::controller(LanggananController::class)->group(function () {
        Route::get('/langganan', 'index')->name('langganan.index');
    });
    Route::get('/invoices', [CompanyInvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/{invoice}/receipt', [CompanyInvoiceController::class, 'receipt'])->name('invoices.receipt');
    Route::get('/invoices/create/{companyId}', [CompanyInvoiceController::class, 'create'])->name('invoices.create');
    Route::get('/invoices/store/{companyId}', fn ($companyId) => redirect()
        ->route('invoices.create', $companyId)
        ->with('error', 'Gunakan formulir invoice untuk menyimpan data.'))->name('invoices.store.form');
    Route::post('/invoices/store/{companyId}', [CompanyInvoiceController::class, 'store'])->name('invoices.store');
    Route::post('/invoices/{id}/confirm-manual', [CompanyInvoiceController::class, 'confirmManualPayment'])->name('invoices.confirmManual');
    
    // Route::get('/statistics', [StatsController::class, 'index'])->name('superadmin.stats.index');
    Route::get('/statistics/export-pdf', [StatsController::class, 'exportPdf'])->name('superadmin.stats.export-pdf');
    Route::get('/statistics/export-excel', [StatsController::class, 'exportExcel'])->name('superadmin.stats.export-excel');
    Route::get('/statistics/chart-data', [StatsController::class, 'getChartData'])->name('superadmin.stats.chart-data');

    Route::get('/statistics', [StatsController::class, 'index'])->name('superadmin.stats.index');
    Route::get('/statistics/export/pdf/{period}/{startDate}/{endDate}', [StatsController::class, 'exportPdf'])->name('superadmin.stats.export.pdf');
    Route::get('/statistics/export/excel/{period}/{startDate}/{endDate}', [StatsController::class, 'exportExcel'])->name('superadmin.stats.export.excel');

    Route::get('/settings/{section?}', [SettingsController::class, 'index'])->name('superadmin.settings.index');
    Route::post('/settings/email-templates/send', [SettingsController::class, 'sendWelcomeEmail'])->name('superadmin.settings.email-templates.send');
    Route::post('/settings/{section?}', [SettingsController::class, 'update'])->name('superadmin.settings.update');


    Route::post('/sub-aktif/toggle', [SubAktifController::class, 'toggle'])->name('sub-aktif.toggle');
});

Route::prefix('admin')->middleware(['auth', 'admin', 'localization'])->group(function () {
    //route company reviews
    Route::get('/company/reviews/create', [CompanyReviewController::class, 'create'])
        ->name('company.reviews.create');
    Route::post('/company/reviews', [CompanyReviewController::class, 'store'])
        ->name('company.reviews.store');
    Route::get('/company/reviews', [CompanyReviewController::class, 'index'])
        ->name('company.reviews.index');
    Route::get('/company/reviews/{id}/edit', [CompanyReviewController::class, 'edit'])
        ->name('company.reviews.edit');
    Route::put('/company/reviews/{id}', [CompanyReviewController::class, 'update'])
        ->name('company.reviews.update');
    Route::delete('/company/reviews/{id}', [CompanyReviewController::class, 'destroy'])
        ->name('company.reviews.destroy');

    Route::get('/subscribe/payment/{package}', [PaymentController::class, 'paymentPage'])->name('subscribe.payment');
    Route::post('/subscribe/payment/{package}/process', [PaymentController::class, 'createInvoice'])->name('payment.show');
    Route::get('/subscribe/payment/{invoiceId}/{packageId}/pending', [PaymentController::class, 'paymentPageFinal'])->name('payment.pending');
    Route::get('/subscribe/payment/{invoice}/done', [PaymentController::class, 'success'])->name('success');

    // Route::post('/subscribe/payment/{package}/pending', [PaymentController::class, 'storePayment'])->name('payment.store');

    Route::controller(ADashController::class)->group(function () {
        Route::get('/', 'index')->name('a.dashboard.index');
        Route::get('/maklumat-syarikat/edit', 'edit')->name('admin.maklumat.edit');
        Route::put('/maklumat-syarikat/update', 'update')->name('admin.maklumat.update');
        Route::post('/maklumat-syarikat/link/{id}', 'addLink')->name('admin.maklumat.addLink');
    });
    // Route::resource('/courses', CourseController::class);
    Route::controller(CourseController::class)->group(function () {
        Route::get('/kursus', 'index')->name('admin.courses.index');
        Route::get('/kursus/tambah', 'create')->name('admin.courses.create');
        Route::post('/kursus/tambah', 'store')->name('admin.courses.store');
        Route::get('/kursus/{course}', 'show')->name('admin.courses.show');
        Route::get('/kursus/{course}/edit', 'edit')->name('admin.courses.edit');
        Route::put('/kursus/{course}', 'update')->name('admin.courses.update');
        Route::delete('/kursus/{course}', 'destroy')->name('admin.courses.destroy');
    });
    Route::controller(AdminStudentAttendanceController::class)->prefix('student-attendances')->name('admin.student-attendances.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{attendance}/edit', 'edit')->name('edit');
        Route::put('/{attendance}', 'update')->name('update');
    });
    Route::controller(SallaryController::class)->prefix('teachers/sallaries')->name('admin.teachers.sallaries.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/sync-attendance', 'syncAttendance')->name('syncAttendance');
        Route::post('/create-paid', 'createPaid')->name('createPaid');
        Route::get('/{teacher}/show', 'show')->name('show');
        Route::post('/{teacher}/add-payment', 'addPayment')->name('addPayment');
        // Route::get('/{sallary}/mark-paid', 'markPaid')->name('markPaid');
        Route::post('/mark-paid', 'markPaid')->name('markPaid');
        Route::get('/{sallary}/update-description', 'updateDescription')->name('updateDescription');

    });
    
    Route::controller(SubscribeController::class)->prefix('subscribe')->name('admin.subscribe.')->group(function (){
        Route::get('/', 'index')->name('index');
    });
    // Route::resource('teachers', TeacherController::class);
    Route::controller(TeacherController::class)->prefix('teachers')->name('admin.teachers.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{teacher}', 'show')->name('show');
        Route::get('/{teacher}/classes', 'classes')->name('classes');
        Route::get('/{teacher}/edit', 'edit')->name('edit');
        Route::put('/{teacher}', 'update')->name('update');
        Route::delete('/{teacher}', 'destroy')->name('destroy');
        Route::post('/bulk-activate', 'bulkActivate')->name('bulkActivate');
        Route::post('/bulk-deactivate', 'bulkDeactivate')->name('bulkDeactivate');
    });
    //belom bikin juga
    Route::get('teacher/manage/inactive', [TeacherController::class, 'inactive'])->name('teacher.manage.inactive');
    //masih ga guna - StudentApprovalController belum dibuat
    // Route::get('students/approve', [StudentApprovalController::class, 'index'])->name('students.approve');
    //lom bikin
    Route::get('teacher/rules', [TeacherController::class, 'rules'])->name('teacher.rules');
    Route::get('teacher/performance', [TeacherController::class, 'performance'])->name('teacher.performance');
    Route::get('teacher/sallary', [SallaryController::class, 'index'])->name('teacher.salary.index');
    
    Route::controller(TeacherUnavailabilityController::class)->prefix('unavailabilities')->name('admin.teachers.unavailabilities.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create'); 
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/show', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    // peraturan guru
    Route::controller(RuleController::class)->prefix('rules')->name('admin.teachers.rules.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create'); 
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::controller(StudentController::class)->prefix('students')->name('admin.students.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('store');
        Route::post('/import', 'import')->name('import');
        Route::get('/pending', 'pending')->name('pending');
        Route::get('/approved', 'approved')->name('approved');
        Route::get('/{student}/account', 'account')->name('account');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/enroll', 'enroll')->name('enrollments');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::get('/{id}/approve', 'showApprovalForm')->name('approval');
        // Route::post('/{id}/approve', 'approve')->name('approve');
        Route::post('/{student}/approve/{invoice}', 'approve')->name('approve');
        Route::post('/{student}/subscription-notifications/{invoice}', 'sendSubscriptionNotification')->name('subscription-notifications.send');
        Route::get('/{student}/invoices/{invoice}/receipt', 'invoiceReceipt')->name('invoices.receipt');
        Route::post('/{student}/invoices', 'storeManualInvoice')->name('invoices.store');
    });

    Route::controller(StaffController::class)->prefix('staff')->name('admin.staff.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::delete('/{staff}', 'destroy')->name('destroy');
        Route::get('/{staff}', 'show')->name('show');
        Route::get('/{staff}/edit', 'edit')->name('edit');
        Route::put('/{staff}', 'update')->name('update');
        Route::post('/bulk-activate', 'bulkActivate')->name('bulkActivate');
        Route::post('/bulk-deactivate', 'bulkDeactivate')->name('bulkDeactivate');
        Route::post('/activate', 'activate')->name('activate');
        Route::post('/deactivate', 'deactivate')->name('deactivate');
    });

    // Route untuk staff update profile sendiri
    Route::middleware(['auth'])->group(function () {
        Route::patch('profile/staff', [StaffController::class, 'updateProfile'])->name('staff.profile.update');
    });

    Route::controller(ReportController::class)->prefix('reports')->name('admin.reports.')->group(function (){
        Route::get('/', 'index')->name('index');
        Route::get('/reports/download/{classId}/{type}/{month}/{year}', 'downloadClassReport')->name('download');
    });

    Route::controller(MonthlyStudentReportController::class)->prefix('monthly-reports')->name('admin.monthly-reports.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{report}/download', 'download')->name('download');
    });

    Route::controller(ClassScheduleController::class)->prefix('class')->name('admin.class.')->group(function (){
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
    });

    Route::controller(BayaranController::class)->prefix('bayaran')->name('admin.bayaran.')->group(function (){
        Route::get('/', 'index')->name('index');
        Route::get('/{id}/verify', 'verifyForm')->name('verifyForm');     
        Route::post('/{id}/verify', 'verify')->name('verify');
    });

    Route::controller(BonusController::class)->prefix('bonus')->name('admin.bonus.')->group(function () {
        Route::get('/{user}/create', 'createBonus')->name('create');
        Route::post('/{user}/store', 'storeBonus')->name('store');
    });

    // Route::controller(CommunicationController::class)->prefix('broadcasts')->name('broadcasts.')->group(function () {
    //     // Route::get('/', 'index')->name('index');
    //     Route::get('/create', 'create')->name('create');
    //     Route::post('/store', 'store')->name('store');
    // });

    Route::controller(AnalyticsController::class)->prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/new-students', 'new_students')->name('new-students');
        Route::get('/invoices-payments', 'invoices_payments')->name('invoices-payments');
        Route::get('/sales', 'sales')->name('sales');
    });

    Route::controller(ACompanyController::class)->prefix('company')->name('admin.company.')->group(function () {
        route::get('/', 'index')->name('index');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
    });



    Route::get('/settings/subscription/invoices/{invoice}/receipt', [AdminSettingsController::class, 'subscriptionReceipt'])
        ->name('admin.settings.subscription.receipt');
    Route::get('/settings/{section?}', [AdminSettingsController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings/{section}', [AdminSettingsController::class, 'update'])->name('admin.settings.update');

    Route::controller(BankController::class)->prefix('bank-details')->name('admin.bank_details.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{bankDetail}/edit', 'edit')->name('edit');
        Route::put('/{bankDetail}/update', 'update')->name('update');
        Route::delete('/{bankDetail}/destroy', 'destroy')->name('destroy');
    });
});

Route::prefix('teacher')->middleware(['auth', 'teacher', 'localization'])->group(function () {
    Route::controller(TDashController::class)->group(function () {
        // dd("hi");
        Route::get('/', 'index')->name('t.dashboard.index');
    });

    // Teacher Attendance Routes
    Route::controller(TeacherAttendanceController::class)->prefix('attendance')->name('teacher.attendance.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{classSchedule}/take', 'takeAttendance')->name('take');
        Route::post('/{classSchedule}/checkin', 'teacherCheckIn')->name('checkin');
        Route::post('/{classSchedule}/checkout', 'teacherCheckOut')->name('checkout');
        Route::post('/{classSchedule}/store', 'storeAttendance')->name('store');
        Route::post('/{classSchedule}/students/{student}/clock', 'clockStudent')->name('student.clock');
        Route::post('/{classSchedule}/students/{student}/status', 'setStudentStatus')->name('student.status');
        Route::get('/{classSchedule}/students/{student}', 'studentClocking')->name('student.clocking');
        Route::post('/{classSchedule}/students/{student}/clocking/start', 'startStudentClocking')->name('student.clocking.start');
        Route::post('/clocking/{clockingSession}/end', 'endStudentClocking')->name('student.clocking.end');
        Route::put('/clocking/{clockingSession}', 'updateStudentClocking')->name('student.clocking.update');
        Route::delete('/clocking/{clockingSession}', 'destroyStudentClocking')->name('student.clocking.destroy');
    });

    Route::controller(TeachingReportController::class)->prefix('teaching-reports')->name('teacher.teaching-reports.')->group(function () {
        Route::get('/{classSchedule}/create', 'create')->name('create');
        Route::post('/{classSchedule}', 'store')->name('store');
    });
    
    Route::controller(ScheduleController::class)->group(function () {
        Route::get('/schedule', 'index')->name('teacher.schedule.index');
        Route::get('/schedule/create', 'create')->name('teacher.schedule.create');
        Route::post('/schedule', 'store')->name('teacher.schedule.store');
        Route::delete('/schedule/{id}', 'destroy')->name('teacher.schedule.destroy');
        Route::get('/schedule/{id}/edit', 'edit')->name('teacher.schedule.edit');
        Route::put('/schedule/{id}', 'update')->name('teacher.schedule.update');
    });

    Route::controller(ReviewController::class)->prefix('feedbacks')->name('teacher.reviews.')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    Route::controller(TeacherSallaryController::class)->prefix('sallaries')->name('teacher.sallaries.')->group(function () {
        Route::get('/', 'index')->name('index');
        // Route::get('/{salary}/show', 'show')->name('show');
        Route::get('preview/{month}/{year}', 'preview')->name('preview');
        Route::get('download/{month}/{year}', 'download')->name('download');
    });
    
});

Route::get('/student', [SDashController::class, 'index'])
    ->middleware(['auth', 'student', 'localization'])
    ->name('s.dashboard.index');

Route::prefix('student')->middleware(['auth', 'student', 'localization'])->group(function () {
    Route::get('/courses/{course}', [SDashController::class, 'courseDetail'])->name('student.courses.detail');
    Route::get('/monthly-reports', [MonthlyReportController::class, 'index'])->name('student.monthly-reports.index');
    Route::get('/monthly-reports/{report}/download', [MonthlyReportController::class, 'download'])->name('student.monthly-reports.download');
    Route::controller(TeacherReviewController::class)->prefix('feedbacks')->name('student.reviews.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/create', 'store')->name('store');
        Route::get('/{invoiceId}', 'show')->name('show');
        Route::get('/{invoiceId}/edit', 'edit')->name('edit');
        Route::post('/{invoiceId}/update', 'update')->name('update');
        Route::delete('/{invoiceId}/destroy', 'destroy')->name('destroy');

        // Alternative manual routes jika tidak menggunakan resource
        /*
        Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::get('reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
        Route::post('reviews', [ReviewController::class, 'store'])->name('reviews.store');
        Route::get('reviews/{id}', [ReviewController::class, 'show'])->name('reviews.show');
        Route::get('reviews/{id}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
        Route::put('reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::delete('reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
        */
    });

    Route::controller(StudentPaymentController::class)->prefix('payments')->name('student.payments.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{invoiceId}/receipt', 'receipt')->name('receipt');
        Route::get('/create/{invoiceId}', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
    });

    Route::controller(StudentScheduleController::class)->prefix('schedule')->name('student.schedule.')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    Route::controller(ProgressController::class)->prefix('progress')->name('student.progress.')->group(function () {
        Route::get('/', 'index')->name('index');
    });

    Route::get('/pay/{id}', [InvoiceController::class, 'pay'])->name('student.pay');
    Route::get('/payments/toyyibpay/return/{invoice}', [InvoiceController::class, 'paymentReturn'])->name('student.payment.return');
});

Route::get('/reports/public/{token}', [MonthlyReportController::class, 'publicDownload'])->name('reports.public.download');

Route::fallback(function () {
    if (request()->is('student') || request()->is('student/*') || request()->is('studet') || request()->is('studet/*')) {
        return redirect()->route('login');
    }

    abort(404);
});
