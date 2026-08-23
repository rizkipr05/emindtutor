<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Subscription;
use App\Models\CompanyInvoice;
use App\Models\Student;
use App\Models\StudentInvoice;
use App\Models\ClassScheduleSession;
use App\Models\SystemSetting;
use App\Models\Teacher;
use App\Models\Company;
use App\Services\WhatsAppNotificationService;
use App\Services\MonthlyStudentReportCollector;
use App\Mail\CompanyInvoiceExpiredMail;
use App\Mail\InvoiceTagihanMail;
use App\Mail\CompanySubscriptionInvoiceMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Collect a permanent monthly report for each student when the company's configured date is due.
Schedule::dailyAt('00:10')->call(function () {
    app(MonthlyStudentReportCollector::class)->collectDueReports();
});

// Pakej harga per pelajar: bulan pertama percuma. Pada 1 haribulan,
// keluarkan invoice bagi bulan lepas berdasarkan pelajar yang masih aktif.
Schedule::monthlyOn(1, '00:15')->call(function () {
    $previousMonthStart = now()->subMonthNoOverflow()->startOfMonth()->toDateString();
    $previousMonthEnd = now()->subMonthNoOverflow()->endOfMonth()->toDateString();
    $dueDate = now()->addDays(7)->toDateString();

    Company::with(['invoices' => fn ($query) => $query->with('package')->latest()])
        ->whereHas('invoices.package', fn ($query) => $query->where('pricing_per_student', true))
        ->each(function (Company $company) use ($previousMonthStart, $previousMonthEnd, $dueDate) {
            $latestInvoice = $company->invoices->first();

            if (! $latestInvoice?->package?->pricing_per_student || CompanyInvoice::where('company_id', $company->id)
                ->whereDate('billing_period_start', $previousMonthStart)
                ->exists()) {
                return;
            }

            $activeStudents = $company->students()->where('status', 'active')->count();
            $amount = round($activeStudents * (float) $latestInvoice->package->harga_per_pelajar, 2);
            $sequence = str_pad((string) (CompanyInvoice::whereDate('created_at', today())->count() + 1), 4, '0', STR_PAD_LEFT);
            $invoice = CompanyInvoice::create([
                'company_id' => $company->id,
                'package_id' => $latestInvoice->package_id,
                'nomor_invoice' => 'INV-' . now()->format('Ymd') . '-' . $sequence . '-STUDENT',
                'jumlah' => $amount,
                'status_bayaran' => $activeStudents === 0 ? 'paid' : 'upcoming',
                'status' => $activeStudents === 0 ? 'active' : 'inactive',
                'tarikh_terbit' => today(),
                'tarikh_tamat' => $activeStudents === 0 ? now()->endOfMonth() : $dueDate,
                'billing_period_start' => $previousMonthStart,
                'billing_period_end' => $previousMonthEnd,
                'active_student_count' => $activeStudents,
            ]);

            if ($activeStudents === 0) {
                $company->update(['status' => 1]);
                Subscription::create([
                    'company_id' => $company->id,
                    'package_id' => $latestInvoice->package_id,
                    'start_date' => today(),
                    'end_date' => now()->endOfMonth(),
                    'status' => 'active',
                ]);
                return;
            }

            $recipient = $company->user?->email ?? $company->pic_email ?? $company->email_syarikat;
            if ($recipient) {
                Mail::to($recipient)->send(new CompanySubscriptionInvoiceMail($invoice));
            }
        });
})->name('Generate per-student subscription invoices');


Schedule::daily()->call(function () {

    $today = now()->toDateString();
    $whatsApp = app(WhatsAppNotificationService::class);

    $invoices = CompanyInvoice::whereDate('tarikh_tamat', $today)
        ->where('status_bayaran', 'paid')
        ->where('status', 'active')
        ->where('reminder_expired_sent', false)
        ->get();

    foreach ($invoices as $inv) {

        $whatsApp->sendOnce(
            $inv,
            'subscription_expired',
            $inv->company->no_telefon,
            "Langganan syarikat sudah habis. Sila perbaharui segera untuk mengelakkan gangguan akses."
        );

        if ($inv->company->email) {
            Mail::to($inv->company->email)->send(new CompanyInvoiceExpiredMail($inv));
        }

        $inv->update([
            'status' => 'inactive',
            'reminder_expired_sent' => true
        ]);

        // Update status di tabel company juga
        $inv->company->update([
            'status' => 'inactive'
        ]);
    }

});


// ======================================================
// 2. Reminder hari ke-3 setelah expired
// ======================================================
Schedule::daily()->call(function () {

    $targetDate = now()->subDays(3)->toDateString();
    $whatsApp = app(WhatsAppNotificationService::class);

    $invoices = CompanyInvoice::where('status', 'inactive')
        ->where('reminder_expired_sent', true)
        ->where('reminder_3_days_sent', false)
        ->whereDate('tarikh_tamat', $targetDate)
        ->get();

    foreach ($invoices as $inv) {

        // CEK JIKA SUDAH ADA INVOICE ACTIVE BARU
        $sudahRenew = CompanyInvoice::where('company_id', $inv->company_id)
            ->where('id', '!=', $inv->id)
            ->where('status_bayaran', 'paid')
            ->where('status', 'active')
            ->exists();

        if ($sudahRenew) {
            // Tidak perlu kirim reminder 3 hari
            $inv->update(['reminder_3_days_sent' => true]);
            continue;
        }

        // Masih belum renew → kirim broadcast
        $whatsApp->sendOnce(
            $inv,
            'subscription_expired_grace_reminder',
            $inv->company->no_telefon,
            "Peringatan: Langganan anda telah tamat sejak 3 hari yang lalu. Sila perbaharui segera untuk mengelakkan gangguan akses kepada sistem."
        );

        $inv->update([
            'reminder_3_days_sent' => true,
        ]);
    }

});


// ======================================================
// 3. Hari ke-7 → jadikan company inactive
// ======================================================
Schedule::daily()->call(function () {

    $graceDays = max(0, (int) SystemSetting::value('subscription_grace_days', 7));
    $targetDate = now()->subDays($graceDays)->toDateString();
    $whatsApp = app(WhatsAppNotificationService::class);

    $invoices = CompanyInvoice::where('status', 'inactive')
        ->where('reminder_7_days_processed', false)
        ->whereDate('tarikh_tamat', $targetDate)
        ->get();

    foreach ($invoices as $inv) {

        // Cek apakah company sudah renew
        $sudahRenew = CompanyInvoice::where('company_id', $inv->company_id)
            ->where('id', '!=', $inv->id)
            ->where('status_bayaran', 'paid')
            ->where('status', 'active')
            ->exists();

        if ($sudahRenew) {
            $inv->update(['reminder_7_days_processed' => true]);
            continue;
        }

        // Belum renew: ubah status company
        $inv->company->update(['status' => 'inactive']);

        // Kirim pesan
        $whatsApp->sendOnce(
            $inv,
            'subscription_deactivated',
            $inv->company->no_telefon,
            "Akaun syarikat anda telah dinyahaktifkan kerana tidak memperbaharui langganan selepas {$graceDays} hari tamat tempoh."
        );

        $inv->update(['reminder_7_days_processed' => true]);
    }

});

// Remind teachers to clock in before a scheduled class. The company controls the lead time.
Schedule::everyFiveMinutes()->call(function () {
    $leadMinutes = max(1, (int) SystemSetting::value('class_clock_in_reminder_minutes', 30));
    $now = now('Asia/Kuala_Lumpur');
    $windowEnd = $now->copy()->addMinutes(5);
    $whatsApp = app(WhatsAppNotificationService::class);

    ClassScheduleSession::with(['classSchedule.teacher.user'])
        ->whereDate('session_date', $now->toDateString())
        ->where('status', 'scheduled')
        ->get()
        ->filter(function (ClassScheduleSession $session) use ($now, $windowEnd, $leadMinutes) {
            $start = \Carbon\Carbon::parse($session->session_date->format('Y-m-d') . ' ' . $session->start_time, 'Asia/Kuala_Lumpur')
                ->subMinutes($leadMinutes);

            return $start->betweenIncluded($now, $windowEnd);
        })
        ->each(function (ClassScheduleSession $session) use ($whatsApp) {
            $schedule = $session->classSchedule;
            $teacher = $schedule?->teacher;

            if (! $teacher || $schedule->teacherAttendances()->whereDate('check_in', $session->session_date)->exists()) {
                return;
            }

            $whatsApp->sendOnce(
                $session,
                'class_clock_in_reminder',
                $teacher->phone,
                "Peringatan kelas: {$schedule->nama_kelas} bermula pada {$session->start_time}. Sila clock in sebelum kelas bermula."
            );
        });
});

// Cek setiap hari untuk student yang expired
Schedule::daily()->call(function () {
    
    $expiredToday = Student::where('status', 'active')
        ->whereDate('sub_end', '<=', now()->toDateString())
        ->get();

    foreach ($expiredToday as $student) {
        // Ambil invoice terakhir untuk referensi kursus
        $lastInv = $student->invoices()->latest()->first();
        
        if (!$lastInv) continue; // Skip jika tidak ada data invoice lama

        // Cek apakah sudah ada invoice yang belum dibayar (upcoming)
        $exists = StudentInvoice::where('student_id', $student->id)
            ->where('status_bayaran', 'upcoming')
            ->exists();

        if (!$exists) {
            $newInvoice = StudentInvoice::create([
                'student_id'     => $student->id,
                'course_id'      => $lastInv->course_id, // Ambil dari invoice sebelumnya
                'nomor_invoice'  => 'INV-STU-' . strtoupper(Str::random(6)) . '-' . now()->format('Ymd'),
                'jumlah'         => $lastInv->course->fee, 
                'status_bayaran' => 'upcoming',
                'tarikh_terbit'  => now()->toDateString(),
                'tarikh_tamat'   => now()->addDays(7)->toDateString() // Tambah kurung ()
            ]);

            $student->update(['status' => 'expired']);

            // --- KIRIM EMAIL DI SINI ---
            Mail::to($student->user->email)->send(new InvoiceTagihanMail($newInvoice));
        }
    }
});

// Cek setiap hari untuk invoice yang sudah lewat jatuh tempo tapi belum dibayar → ubah status jadi overdue
Schedule::daily()->call(function () {
    // Cari invoice yang statusnya 'upcoming' tapi sudah lewat tanggal jatuh tempo
    $overdueInvoices = StudentInvoice::where('status_bayaran', 'upcoming')
        ->whereDate('tarikh_tamat', '<', now()->toDateString())
        ->get();

    foreach ($overdueInvoices as $invoice) {
        $invoice->update([
            'status_bayaran' => 'overdue'
        ]);
    }
});
