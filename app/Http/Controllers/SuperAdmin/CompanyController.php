<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Company;
use App\Models\User;
use App\Models\CompanyInvoice;
use App\Models\StudentInvoice;
use App\Models\CompanyPayment;
use App\Mail\CompanySubscriptionInvoiceMail;
use App\Services\WhatsAppNotificationService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CompanyController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $companies = Company::with(['user', 'invoices' => function ($query) {
            $query->orderBy('tarikh_tamat', 'desc'); // urutkan, biar yang terbaru diambil dulu
        }])->get();

        // Tambahkan properti helper "activeInvoice" biar gampang di Blade
        foreach ($companies as $company) {
            $company->activeInvoice = $company->invoices
                ->where('status_bayaran', 'paid')
                ->where('tarikh_terbit', '<=', $today)
                ->where('tarikh_tamat', '>=', $today)
                ->sortByDesc('tarikh_tamat')
                ->first();
            $company->pendingInvoice = $company->invoices
                ->whereIn('status_bayaran', ['pending', 'upcoming'])
                ->sortByDesc('created_at')
                ->first();
            $company->latestPaidInvoice = $company->invoices
                ->where('status_bayaran', 'paid')
                ->sortByDesc('tarikh_tamat')
                ->first();
        }

        return view('super-admin.syarikat.index', compact('companies'));
    }

    public function create()
    {
        return view('super-admin.syarikat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nama_wakil_syarikat' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'no_telefon' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        $lastCompany = Company::orderBy('id', 'desc')->first();

        if ($lastCompany && $lastCompany->nomor_pendaftar_syarikat) {
            // Ambil nomor terakhir → increment
            $lastNumber = (int) str_replace('EMT-', '', $lastCompany->nomor_pendaftar_syarikat);
            $newNumber = $lastNumber + 1;
        } else {
            // Jika belum ada data sama sekali
            $newNumber = 1;
        }

        // Format final
        $nomorPendaftar = 'EMT-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);
        // dd($nomorPendaftar);

        Company::create([
            'user_id' => $user->id,
            'nama_wakil_syarikat' => $request->nama_wakil_syarikat,
            'alamat' => $request->alamat,
            'no_telefon' => $request->no_telefon,
            'nombor_pendaftaran_syarikat' => $nomorPendaftar,
        ]);

        return redirect()->route('companies.index')->with('success', 'Syarikat berjaya ditambah.');
    }


    public function show(Company $company)
    {
        $company->load(['user', 'invoices.payments']);

        // ambil semua student_id dari company ini
        $studentIds = $company->students->pluck('id');

        // hitung total invoice yang paid
        $totalPaidInvoices = StudentInvoice::whereIn('student_id', $studentIds)
            ->where('status_bayaran', 'paid')
            ->count();

        // hitung total pendapatan dari invoice paid
        $totalRevenue = StudentInvoice::whereIn('student_id', $studentIds)
            ->where('status_bayaran', 'paid')
            ->sum('jumlah'); // pastikan kolom jumlah ada di tabel student_invoices

        $accountInvoices = $company->invoices->sortByDesc(fn (CompanyInvoice $invoice) => $invoice->tarikh_terbit ?? $invoice->created_at);
        $accountPayments = CompanyPayment::with('invoice')
            ->whereHas('invoice', fn ($query) => $query->where('company_id', $company->id))
            ->orderByDesc('dibayar_pada')
            ->orderByDesc('id')
            ->get();
        $totalInvoiceAmount = (float) $accountInvoices->sum('jumlah');
        $totalPaymentAmount = (float) $accountPayments->where('status', 'success')->sum('jumlah');
        $accountBalance = round($totalInvoiceAmount - $totalPaymentAmount, 2);

        return view('super-admin.syarikat.show', compact(
            'company', 'totalPaidInvoices', 'totalRevenue', 'accountInvoices', 'accountPayments',
            'totalInvoiceAmount', 'totalPaymentAmount', 'accountBalance'
        ));
    }

    public function account(Company $company)
    {
        $company->load(['user', 'invoices.payments']);
        $accountInvoices = $company->invoices->sortByDesc(fn (CompanyInvoice $invoice) => $invoice->tarikh_terbit ?? $invoice->created_at);
        $accountPayments = CompanyPayment::with('invoice')
            ->whereHas('invoice', fn ($query) => $query->where('company_id', $company->id))
            ->orderByDesc('dibayar_pada')->orderByDesc('id')->get();
        $totalInvoiceAmount = (float) $accountInvoices->sum('jumlah');
        $totalPaymentAmount = (float) $accountPayments->where('status', 'success')->sum('jumlah');
        $accountBalance = round($totalInvoiceAmount - $totalPaymentAmount, 2);

        return view('super-admin.syarikat.account', compact(
            'company', 'accountInvoices', 'accountPayments', 'totalInvoiceAmount', 'totalPaymentAmount', 'accountBalance'
        ));
    }

    public function storeManualInvoice(Request $request, Company $company)
    {
        $data = $request->validate([
            'description' => ['required', 'string', 'max:1000'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'due_date' => ['required', 'date'],
        ]);

        $number = 'MAN-' . now()->format('Ymd') . '-' . str_pad(
            (string) (CompanyInvoice::whereDate('created_at', today())->count() + 1), 4, '0', STR_PAD_LEFT
        );

        $totalInvoiced = (float) $company->invoices()->sum('jumlah');
        $totalPaid = (float) CompanyPayment::whereHas('invoice', fn ($query) => $query->where('company_id', $company->id))
            ->where('status', 'success')
            ->sum('jumlah');
        $availableCredit = max(0, $totalPaid - $totalInvoiced);
        $isCoveredByCredit = $availableCredit >= (float) $data['amount'];

        CompanyInvoice::create([
            'company_id' => $company->id,
            'nomor_invoice' => $number,
            'jumlah' => $data['amount'],
            'status_bayaran' => $isCoveredByCredit ? 'paid' : 'upcoming',
            'status' => 'inactive',
            'tarikh_terbit' => today(),
            'tarikh_tamat' => $data['due_date'],
            'invoice_type' => 'manual',
            'description' => $data['description'],
        ]);

        return back()->with('success', 'Invoice manual berjaya dibuat dan masuk ke Invoice History.');
    }

    public function storeManualPayment(Request $request, Company $company)
    {
        $data = $request->validate([
            'company_invoice_id' => ['required', 'integer'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'in:transfer_bank,cash,cheque,other'],
            'paid_at' => ['required', 'date'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $invoice = $company->invoices()->findOrFail($data['company_invoice_id']);

        DB::transaction(function () use ($company, $invoice, $data) {
            $totalInvoiceAmount = (float) $company->invoices()->sum('jumlah');
            $totalPaymentBefore = (float) CompanyPayment::whereHas('invoice', fn ($query) => $query->where('company_id', $company->id))
                ->where('status', 'success')
                ->sum('jumlah');
            $balanceAfter = round($totalInvoiceAmount - ($totalPaymentBefore + (float) $data['amount']), 2);

            CompanyPayment::create([
                'company_invoice_id' => $invoice->id,
                'jumlah' => $data['amount'],
                'status' => 'success',
                'transaction_id' => ($data['reference_number'] ?? null) ?: 'PAY-' . now()->format('YmdHis'),
                'reference_number' => $data['reference_number'] ?? null,
                'payment_method' => $data['payment_method'],
                'notes' => $data['notes'] ?? null,
                'balance_after' => $balanceAfter,
                'dibayar_pada' => $data['paid_at'],
            ]);

            $invoicePaid = (float) $invoice->payments()->where('status', 'success')->sum('jumlah') + (float) $data['amount'];
            if ($invoicePaid >= (float) $invoice->jumlah) {
                $invoice->update(['status_bayaran' => 'paid']);
            }
        });

        return back()->with('success', 'Pembayaran manual berjaya direkodkan.');
    }

    public function edit(Company $company)
    {
        $company->load('user');
        return view('super-admin.syarikat.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nama_wakil_syarikat' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $company->user_id,
            'no_telefon' => 'required|string|max:20',
        ]);

        // Update user
        $company->user->update([
            'name' => $request->nama,
            'email' => $request->email,
        ]);

        // Update company
        $company->update([
            'nama_wakil_syarikat' => $request->nama_wakil_syarikat,
            'alamat' => $request->alamat,
            'no_telefon' => $request->no_telefon,
            'status' => $request->status ?? true,
        ]);

        return redirect()->route('companies.index')->with('success', 'Syarikat berjaya dikemas kini.');
    }

    public function destroy(Company $company)
    {
        DB::transaction(function () use ($company) {
            $studentIds = DB::table('students')->where('company_id', $company->id)->pluck('id');
            $studentUserIds = DB::table('students')->where('company_id', $company->id)->pluck('user_id');
            $teacherIds = DB::table('teachers')->where('company_id', $company->id)->pluck('id');
            $teacherUserIds = DB::table('teachers')->where('company_id', $company->id)->pluck('user_id');
            $staffUserIds = Schema::hasTable('staff') ? DB::table('staff')->where('company_id', $company->id)->pluck('user_id') : collect();
            $scheduleIds = DB::table('class_schedules')->where('company_id', $company->id)->pluck('id');
            $studentInvoiceIds = DB::table('student_invoices')->whereIn('student_id', $studentIds)->pluck('id');
            $companyInvoiceIds = DB::table('company_invoices')->where('company_id', $company->id)->pluck('id');

            $this->deleteWhereIn('student_payments', 'student_invoice_id', $studentInvoiceIds);
            $this->deleteWhereIn('teacher_reviews', 'student_invoice_id', $studentInvoiceIds);
            $this->deleteWhereIn('student_attendances', 'student_id', $studentIds);
            $this->deleteWhereIn('class_schedule_student', 'student_id', $studentIds);
            $this->deleteWhereIn('class_schedule_student', 'class_schedule_id', $scheduleIds);
            $this->deleteWhereIn('class_schedule_days', 'class_schedule_id', $scheduleIds);
            $this->deleteWhereIn('class_schedule_sessions', 'class_schedule_id', $scheduleIds);
            $this->deleteWhereIn('class_schedule_exceptions', 'class_schedule_id', $scheduleIds);
            $this->deleteWhereIn('teacher_attendances', 'teacher_id', $teacherIds);
            $this->deleteWhereIn('teacher_unavailabilities', 'teacher_id', $teacherIds);
            $this->deleteWhereIn('company_payments', 'company_invoice_id', $companyInvoiceIds);
            $this->deleteWhereIn('student_invoices', 'id', $studentInvoiceIds);

            foreach (['payment_gateways', 'company_bank_details', 'whatsapp_settings', 'payment_settings', 'teacher_rules', 'company_reviews', 'subscriptions', 'company_invoices', 'class_schedules', 'courses', 'students', 'teachers', 'staff'] as $table) {
                $this->deleteWhere($table, 'company_id', $company->id);
            }

            DB::table('companies')->where('id', $company->id)->delete();
            DB::table('users')->whereIn('id', collect([$company->user_id])->merge($studentUserIds)->merge($teacherUserIds)->merge($staffUserIds)->filter()->unique())->delete();
        });

        return redirect()->route('companies.index')->with('success', 'Syarikat dan semua rekod berkaitan berjaya dipadam.');
    }

    private function deleteWhere(string $table, string $column, mixed $value): void
    {
        if (Schema::hasTable($table) && Schema::hasColumn($table, $column)) {
            DB::table($table)->where($column, $value)->delete();
        }
    }

    private function deleteWhereIn(string $table, string $column, $values): void
    {
        if ($values->isNotEmpty() && Schema::hasTable($table) && Schema::hasColumn($table, $column)) {
            DB::table($table)->whereIn($column, $values)->delete();
        }
    }

    public function toggleStatus($id)
    {
        $company = Company::findOrFail($id);
        $company->status = !$company->status;
        $company->save();

        return redirect()->back()->with('success', 'Status syarikat berjaya dikemaskini.');
    }

    public function sendInvoiceReminder(Request $request, Company $company)
    {
        $data = $request->validate(['channel' => ['required', 'in:whatsapp,email']]);
        $invoice = $company->invoices()
            ->latest('tarikh_terbit')
            ->latest('created_at')
            ->first();

        if (! $invoice) {
            return back()->with('error', 'Tiada rekod invoice untuk syarikat ini.');
        }

        try {
            if ($data['channel'] === 'email') {
                $email = $company->user?->email ?: $company->pic_email ?: $company->email_syarikat;
                if (! $email) {
                    return back()->with('error', 'Email syarikat belum ditetapkan.');
                }

                Mail::to($email)->send(new CompanySubscriptionInvoiceMail($invoice));
                logger()->info('Manual company invoice email sent.', [
                    'company_id' => $company->id,
                    'invoice_id' => $invoice->id,
                    'recipient' => $email,
                ]);
            } else {
                if (! $company->no_telefon) {
                    return back()->with('error', 'Nombor WhatsApp syarikat belum ditetapkan.');
                }

                $message = "Makluman invoice langganan\n\n"
                    . "Invoice: {$invoice->nomor_invoice}\n"
                    . 'Jumlah: RM ' . number_format($invoice->jumlah, 2) . "\n"
                    . 'Status: ' . ucfirst($invoice->status_bayaran)
                    . ($invoice->payment_url ? "\nBayar di sini: {$invoice->payment_url}" : '');

                $sent = app(WhatsAppNotificationService::class)->sendOnce(
                    $company,
                    'manual_company_invoice_reminder_' . $invoice->id . '_' . now()->format('YmdHisv'),
                    $company->no_telefon,
                    $message
                );

                if (! $sent) {
                    return back()->with('error', 'Peringatan WhatsApp gagal dihantar. Semak nombor dan token Fonnte.');
                }
            }
        } catch (\Throwable $exception) {
            report($exception);
            logger()->error('Manual company invoice reminder failed.', [
                'company_id' => $company->id,
                'invoice_id' => $invoice->id,
                'channel' => $data['channel'],
                'error' => $exception->getMessage(),
            ]);

            $message = $data['channel'] === 'email'
                ? 'Email gagal dihantar. SMTP tidak dapat dihubungkan: ' . $exception->getMessage()
                : 'Peringatan WhatsApp gagal dihantar. Semak nombor dan token Fonnte.';

            return back()->with('error', $message);
        }

        return back()->with('success', 'Peringatan invoice berjaya dihantar melalui ' . strtoupper($data['channel']) . '.');
    }

    public function loginAs($id)
    {
        $company = Company::findOrFail($id);

        // Simulasi login sebagai user perusahaan
        session(['acting_as_company' => $company->user->id]);

        return redirect('/dashboard')->with('success', 'Anda kini log masuk sebagai admin syarikat: ' . $company->user->name);
    }
}
