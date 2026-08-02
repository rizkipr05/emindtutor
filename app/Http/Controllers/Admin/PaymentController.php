<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // Tambahkan Import ini
use Illuminate\Support\Facades\Log;
use App\Models\SubscriptionPackage;
use App\Models\CompanyInvoice;
use App\Models\StudentInvoice;
use App\Models\CompanyPayment;
use App\Models\Subscription;
use App\Models\Discount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\CompanyReceiptMail;
use App\Services\ToyyibPayService;
use App\Models\PaymentGateway;

class PaymentController extends Controller
{
    public function start($packageId)
    {
        if (auth()->check()) {
            return redirect()->route('subscribe.payment', $packageId);
        }
        session([
            'from_subscribe'   => true,
            'selected_package' => $packageId
        ]);
        return redirect()->route('register');
    }

    public function afterRegister()
    {
        if (session()->has('selected_package')) {
            $packageId = session('selected_package');
            session()->forget('selected_package');
            return redirect()->route('subscribe.payment', $packageId);
        }
        return redirect()->route('dashboard');
    }


    public function paymentPage($packageId)
    {
        $package = SubscriptionPackage::findOrFail($packageId);
        // Ambil diskon aktif untuk ditampilkan di view (opsional)
        $discounts = Discount::where('end_date', '>=', Carbon::today())->get();
        return view('admin.payment.index', compact('package', 'discounts'));
    }

    public function paymentPageFinal($invoiceId, $packageId)
    {
        $invoice = CompanyInvoice::findOrFail($invoiceId);
        $package = SubscriptionPackage::findOrFail($packageId);
        return view('admin.payment.show', compact('invoice', 'package'));
    }

    /**
     * LOGIC UTAMA: Buat bill di ToyyibPay.
     */
    public function createInvoice(Request $request)
    {
        $user = Auth::user();
        $companyId = $user->company->id; 
        $packageId = $request->package_id;
        $package   = SubscriptionPackage::findOrFail($packageId);
        $harga     = $package->harga;

        // 1. Cek Diskon
        $discount = null;
        if ($request->filled('kode_diskon')) {
            $discount = Discount::where('kode_diskon', $request->kode_diskon)
                        ->where('end_date', '>=', Carbon::today())
                        ->first();
        }

        $jumlahAkhir = $harga;
        if ($discount) {
            if ($discount->percent) {
                $jumlahAkhir -= ($harga * $discount->percent / 100);
            } elseif ($discount->potongan) {
                $jumlahAkhir -= $discount->potongan;
            }
            if ($jumlahAkhir < 0) $jumlahAkhir = 0;
        }

        $todayStr = Carbon::now()->format('Ymd');
        $countToday = CompanyInvoice::whereDate('created_at', Carbon::today())->count() + 1;
        $sequence   = str_pad($countToday, 4, '0', STR_PAD_LEFT);
        
        // Buat External ID Unik
        $externalId = "EMT-{$todayStr}-{$sequence}-" . time();
        $nomorInvoice = "INV-{$todayStr}-{$sequence}";

        // 2. Handle TRIAL (Tidak perlu ke payment gateway)
        if ($package->is_trial) {
            CompanyInvoice::create([
                'company_id'    => $companyId,
                'package_id'    => $package->id,
                'nomor_invoice' => $nomorInvoice . '-TRIAL',
                'external_id'   => $externalId . '-TRIAL',
                'jumlah'        => 0,
                'status_bayaran'=> 'paid',
                'tarikh_terbit' => Carbon::now(),
                'tarikh_tamat'  => Carbon::now()->addDays($package->duration_days),
            ]);

            // Aktifkan Subscription Langsung
            $this->activateSubscription($companyId, $packageId, $package->duration_days);

            return redirect()->route('a.dashboard.index')->with('success', 'Trial Activated');
        }

        // 3. Simpan invoice lokal sebelum membuat bill eksternal.
        $invoice = CompanyInvoice::create([
            'company_id'    => $companyId,
            'package_id'    => $packageId,
            'nomor_invoice' => $nomorInvoice,
            'external_id'   => $externalId,
            'jumlah'        => $jumlahAkhir,
            'status_bayaran'=> 'pending', // Status awal sebelum callback ToyyibPay
        ]);

        try {
            $gateway = PaymentGateway::superAdmin();
            if (! $gateway) {
                return back()->with('error', 'Payment Gateway Super Admin belum dikonfigurasi.');
            }
            $bill = app(ToyyibPayService::class)->createBill([
                'name' => 'eMindTutor ' . $package->nama_pakej,
                'description' => 'Subscription ' . $package->nama_pakej,
                'amount' => $jumlahAkhir,
                'reference' => $externalId,
                'return_url' => route('success', $invoice->id),
                'callback_url' => route('payment.webhook'),
                'payer_name' => $user->name,
                'payer_email' => $user->email,
            ], $gateway);

            $invoice->update([
                'toyyibpay_bill_code' => $bill['bill_code'],
                'payment_url' => $bill['payment_url'],
            ]);

            return redirect()->route('payment.pending', [$invoice->id, $packageId]);

        } catch (\Exception $e) {
            Log::error('ToyyibPay create bill failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat bill ToyyibPay: ' . $e->getMessage());
        }
    }

    /**
     * CALLBACK: Menerima notifikasi server-to-server dari ToyyibPay.
     */
    public function webhook(Request $request)
    {
        $data = $request->all();
        $externalId = (string) ($data['order_id'] ?? '');
        $companyInvoice = CompanyInvoice::with('package')->where('external_id', $externalId)->first();
        $studentInvoice = $companyInvoice ? null : StudentInvoice::with('student.company')->where('external_id', $externalId)->orWhere('nomor_invoice', $externalId)->first();
        $gateway = $companyInvoice ? PaymentGateway::superAdmin() : $studentInvoice?->student?->company?->paymentGateway;

        if (! $gateway || ! app(ToyyibPayService::class)->isValidCallback($data, $gateway)) {
            abort(403, 'Invalid ToyyibPay callback hash.');
        }

        // ToyyibPay status 1 means a successful payment.
        if (($data['status'] ?? null) === '1') {
            $invoice = $companyInvoice;

            // 3. Update hanya jika belum paid
            if ($invoice && $invoice->status_bayaran !== 'paid') {
                
                $paidAt = isset($data['transaction_time']) ? Carbon::parse($data['transaction_time']) : Carbon::now();
                
                // Hitung Tarikh Tamat (Ambil durasi dari paket)
                // Default 30 hari jika paket tidak ketemu (untuk jaga-jaga)
                $durationDays = $invoice->package ? $invoice->package->duration_days : 30; 
                $tarikhTamat  = $paidAt->copy()->addDays($durationDays);

                // Update Invoice
                $invoice->update([
                    'status_bayaran' => 'paid',
                    'tarikh_terbit'  => $paidAt,
                    'tarikh_tamat'   => $tarikhTamat,
                ]);

                // Kirim email ke email perusahaan (asumsi ada relasi company)
                $emailTujuan = $invoice->company?->user?->email;

                if ($emailTujuan) {
                    Mail::to($emailTujuan)->send(new CompanyReceiptMail($invoice));
                }
            }

            if ($studentInvoice && $studentInvoice->status_bayaran !== 'paid') {
                $paidAt = isset($data['transaction_time']) ? Carbon::parse($data['transaction_time']) : Carbon::now();

                $studentInvoice->update([
                    'status_bayaran' => 'paid',
                    'tarikh_terbit' => $paidAt->toDateString(),
                    'tarikh_tamat' => $paidAt->copy()->addDays(30)->toDateString(),
                ]);

                $studentInvoice->student?->update([
                    'status' => 'active',
                ]);
            }
        }

        return response()->json(['message' => 'ToyyibPay callback received']);
    }

    /**
     * Halaman kembali dari ToyyibPay. Callback tetap sumber status utama.
     */
    public function success($invoiceId)
    {
        $invoice = CompanyInvoice::with('package')->findOrFail($invoiceId);
        
        // Fallback jika callback belum diterima saat pengguna kembali ke aplikasi.
        if ($invoice->status_bayaran !== 'paid' && $invoice->toyyibpay_bill_code) {
            
            try {
                $gateway = PaymentGateway::superAdmin();
                if ($gateway && app(ToyyibPayService::class)->hasSuccessfulTransaction($invoice->toyyibpay_bill_code, $gateway)) {
                     
                     // Hitung Tanggal
                     $paidAt = Carbon::now();
                     $durationDays = $invoice->package ? $invoice->package->duration_days : 30;
                     $tarikhTamat  = $paidAt->copy()->addDays($durationDays);

                     // Update Invoice
                     $invoice->update([
                        'status_bayaran' => 'paid',
                        'tarikh_terbit'  => $paidAt,
                        'tarikh_tamat'   => $tarikhTamat,
                     ]);
                }

            } catch (\Exception $e) {
                // Biarkan error diam (silent fail) di page success agar user tidak panik,
                // toh webhook akan mengurusnya nanti.
                Log::error('Gagal cek status ToyyibPay: ' . $e->getMessage());
            }
        }

        return redirect()->route('a.dashboard.index')->with('success', 'Pembayaran Berhasil!');
    }

    /**
     * Helper untuk Aktivasi Langganan
     */
    private function activateSubscription($companyId, $packageId, $durationDays)
    {
        $subscription = Subscription::where('company_id', $companyId)
                        ->where('status', 'active')
                        ->latest()
                        ->first();

        $startDate = Carbon::today();
        
        if ($subscription && $subscription->end_date >= Carbon::today()) {
            $startDate = Carbon::parse($subscription->end_date)->addDay();
            $endDate   = Carbon::parse($subscription->end_date)->addDays($durationDays);
        } else {
            $endDate = Carbon::today()->addDays($durationDays);
        }

        Subscription::create([
            'company_id' => $companyId,
            'package_id' => $packageId,
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'status'     => 'active',
        ]);

        // Update tanggal tamat invoice agar sesuai
        CompanyInvoice::where('company_id', $companyId)
            ->where('package_id', $packageId)
            ->latest()
            ->first()
            ->update(['tarikh_tamat' => $endDate]);
    }
}
