<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\CompanyInvoice;
use App\Models\Company;
use App\Models\SubscriptionPackage;
use App\Models\SystemSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class CompanyInvoiceController extends Controller
{
    // List semua invoice berdasarkan company
    public function index()
    {
        // Ambil semua invoice + relasi company
        $invoices = CompanyInvoice::with('company')->latest()->get();

        return view('super-admin.invoices.index', compact('invoices'));
    }

    public function receipt(CompanyInvoice $invoice)
    {
        $invoice->load([
            'company.user',
            'package',
            'payments',
        ]);
        $paidAmount = (float) $invoice->payments->where('status', 'success')->sum('jumlah');
        $balanceDue = max(0, (float) $invoice->jumlah - $paidAmount);
        $invoiceSettings = [
            'logo' => SystemSetting::value('invoice_pdf_logo'),
            'company_name' => SystemSetting::value('invoice_pdf_company_name', 'Your Company Name'),
            'tagline' => SystemSetting::value('invoice_pdf_tagline', ''),
            'address' => SystemSetting::value('invoice_pdf_address', ''),
            'phone' => SystemSetting::value('invoice_pdf_phone', ''),
            'email' => SystemSetting::value('invoice_pdf_email', ''),
            'website' => SystemSetting::value('invoice_pdf_website', ''),
            'bank_name' => SystemSetting::value('invoice_pdf_bank_name', ''),
            'bank_account' => SystemSetting::value('invoice_pdf_bank_account', ''),
            'payment_note' => SystemSetting::value('invoice_pdf_payment_note', 'Please use the invoice number as your payment reference.'),
            'important_note' => SystemSetting::value('invoice_pdf_important_note', 'Please keep this invoice as your payment reference.'),
        ];

        // Generate PDF
        $pdf = \PDF::loadView('super-admin.invoices.receipt', [
            'invoice' => $invoice,
            'paidAmount' => $paidAmount,
            'balanceDue' => $balanceDue,
            'invoiceSettings' => $invoiceSettings,
        ]);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("Invoice-{$invoice->nomor_invoice}.pdf");
    }   

    public function create($companyId)
    {
        $company = Company::findOrFail($companyId);
        // dd($company->user->name);
        $packages = SubscriptionPackage::where('status', 'aktif')->get();

        return view('super-admin.invoices.create', compact('company', 'packages'));
    }

public function store(Request $request, $companyId)
    {
        $company = Company::findOrFail($companyId);
        $package = SubscriptionPackage::findOrFail($request->package_id);

        // 1. Validasi Input
        $request->validate([
            'package_id'    => 'required',
            'duration_days' => 'required|integer|min:1',
            'jumlah'        => 'required|numeric|min:0',
            'status_bayaran'=> 'required|in:paid,upcoming',
        ]);

        // 2. Logic Paket Per Student vs Biasa
        // Kita hitung limit pelajar (jika nanti perlu disimpan di tabel company)
        $limitPelajar = $package->maks_pelajar; // Default paket biasa

        if ($package->pricing_per_student == 1) {
            $request->validate([
                'student_count' => 'required|integer|min:1'
            ]);
            $limitPelajar = $request->student_count; // Override limit sesuai input
        }

        // 3. Logic Tanggal & Status (INI YANG DIPERBAIKI)
        $tarikhTerbit = null;
        $tarikhTamat  = null;
        $statusInvoice = 'inactive'; // Default status record invoice

        if ($request->status_bayaran === 'paid') {
            // Jika LUNAS: Set tanggal hari ini & hitung expired
            $tarikhTerbit = Carbon::now();
            $tarikhTamat  = Carbon::now()->addDays((int)$request->duration_days);
            $statusInvoice = 'active'; // Invoice dianggap aktif
        } 
        // Jika UPCOMING: Biarkan $tarikhTerbit & $tarikhTamat tetap NULL

        // 4. Generate Nomor Invoice
        $todayStr = Carbon::now()->format('Ymd');
        $countToday = CompanyInvoice::whereDate('created_at', Carbon::today())->count() + 1;
        $nomorInvoice = "EMT-{$todayStr}-" . str_pad($countToday, 4, '0', STR_PAD_LEFT);

        // 5. Simpan ke Database
        CompanyInvoice::create([
            'company_id'     => $company->id,
            'package_id'     => $package->id,
            'nomor_invoice'  => $nomorInvoice,
            'jumlah'         => $request->jumlah,
            'status_bayaran' => $request->status_bayaran, // 'paid' atau 'upcoming'
            
            'tarikh_terbit'  => $tarikhTerbit, // NULL jika upcoming
            'tarikh_tamat'   => $tarikhTamat,  // NULL jika upcoming
            'status'         => $statusInvoice, 
            
            // Opsional: Jika tabel invoice Anda punya kolom untuk menyimpan info student
            // 'student_count_snapshot' => $package->pricing_per_student ? $request->student_count : 0
        ]);

        // 6. Update Status Company (Hanya jika PAID)
        if ($request->status_bayaran === 'paid') {
            $company->update([
                'status' => 1, // Aktifkan Company
                // Update limit pelajar di table company (jika kolomnya ada)
                // 'max_students' => $limitPelajar 
            ]);

            $message = "Invoice LUNAS berhasil dibuat. Company aktif dengan limit {$limitPelajar} pelajar.";
        } else {
            // Jika Upcoming, jangan aktifkan company dulu
            $message = "Invoice UPCOMING berhasil dibuat. Menunggu pembayaran untuk pengaktifan.";
        }

        return redirect()
            ->route('companies.index')
            ->with('success', $message);
    }

public function confirmManualPayment($id)
{
    $invoice = CompanyInvoice::with('package', 'company')->findOrFail($id);

    // 1. Cek jika sudah lunas
    if ($invoice->status_bayaran === 'paid') {
        return back()->with('error', 'Invoice ini sudah lunas.');
    }

    // 2. LOGIK BARU: Cek Usia Invoice (7 Hari)
    // Jika waktu sekarang > created_at + 7 hari
    if ($invoice->created_at->addDays(7)->isPast()) {
        return back()->with('error', 'Gagal! Invoice ini sudah expired (lebih dari 7 hari) dan tidak bisa dibayar.');
    }

    // 3. Lanjut proses jika valid
    $duration = $invoice->package->duration_days ?? 30; 
    $today = \Carbon\Carbon::now();
    
    $invoice->update([
        'status_bayaran' => 'paid',
        'status'         => 'active',
        'tarikh_terbit'  => $today,
        'tarikh_tamat'   => $today->copy()->addDays((int)$duration),
    ]);

    $invoice->company->update(['status' => 1]);

    return back()->with('success', 'Pembayaran dikonfirmasi manual. Paket aktif mulai hari ini.');
}
}
