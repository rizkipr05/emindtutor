<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // Tambahkan Import ini
use Illuminate\Support\Facades\Log;
use App\Models\SubscriptionPackage;
use App\Models\CompanyInvoice;
use App\Models\CompanyPayment;
use App\Models\Subscription;
use App\Models\Discount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\CompanyReceiptMail;

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
     * LOGIC UTAMA: Buat Invoice ke Xendit
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

        // 2. Handle TRIAL (Tidak perlu ke Xendit)
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

        // 3. Simpan Invoice Lokal (Pending)
        $invoice = CompanyInvoice::create([
            'company_id'    => $companyId,
            'package_id'    => $packageId,
            'nomor_invoice' => $nomorInvoice,
            'external_id'   => $externalId,
            'jumlah'        => $jumlahAkhir,
            'status_bayaran'=> 'pending', // Status awal sebelum callback Xendit
        ]);

        // 4. Request ke Xendit API
        $secretKey = config('services.xendit.secret_key');
        $baseUrl   = config('services.xendit.base_url');

        try {
            // Xendit menggunakan Basic Auth dengan Username=SecretKey dan Password=Kosong
            $response = Http::withBasicAuth($secretKey, '')
                ->post($baseUrl . '/v2/invoices', [
                    'external_id'      => $externalId,
                    'amount'           => $jumlahAkhir,
                    'payer_email'      => $user->email,
                    'description'      => "Payment for " . $package->nama_pakej,
                    'invoice_duration' => 86400, // 24 jam
                    'currency'         => 'IDR', // Sesuaikan mata uang (IDR/MYR/PHP)
                    'success_redirect_url' => route('success', $invoice->id),
                    'failure_redirect_url' => route('subscribe.payment', $packageId),
                ]);

            $xenditData = $response->json();

            if (isset($xenditData['invoice_url'])) {
                // Update Invoice dengan Data Xendit
                $invoice->update([
                    'xendit_id'          => $xenditData['id'],
                    'xendit_invoice_url' => $xenditData['invoice_url'],
                    'status_bayaran'     => strtolower($xenditData['status'] ?? 'pending')
                ]);

                // Redirect ke halaman detail (user akan klik tombol Pay di sana)
                return redirect()->route('payment.pending', [$invoice->id, $packageId]);
            } else {
                // Handle Error dari Xendit
                Log::error('Xendit Error', $xenditData);
                return back()->with('error', 'Gagal membuat invoice Xendit: ' . ($xenditData['message'] ?? 'Unknown error'));
            }

        } catch (\Exception $e) {
            Log::error('Xendit Exception: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan koneksi ke Payment Gateway.');
        }
    }

    /**
     * WEBHOOK: Menerima notifikasi otomatis dari Xendit
     * Pastikan route ini bypass CSRF Protection!
     */
/**
     * WEBHOOK: Menerima notifikasi otomatis dari Xendit
     */
    public function webhook(Request $request)
    {
        // ... kode validasi token kamu (jika ada) ...

        $data = $request->all();

        // 1. Terima status PAID atau SETTLED
        if (isset($data['status']) && ($data['status'] === 'PAID' || $data['status'] === 'SETTLED')) {
            $externalId = $data['external_id'];
            
            // 2. Cari Invoice
            // Pastikan eager load 'package' untuk ambil durasi hari
            $invoice = CompanyInvoice::with('package')->where('external_id', $externalId)->first();

            // 3. Update hanya jika belum paid
            if ($invoice && $invoice->status_bayaran !== 'paid') {
                
                // Tentukan Tanggal Mulai (dari waktu bayar Xendit atau Sekarang)
                $paidAt = isset($data['paid_at']) ? Carbon::parse($data['paid_at']) : Carbon::now();
                
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
        }

        return response()->json(['message' => 'Webhook received']);
    }

    /**
     * Halaman Sukses (Redirect Back dari Xendit)
     */
    public function success($invoiceId)
    {
        $invoice = CompanyInvoice::with('package')->findOrFail($invoiceId);
        
        // Cek status manual ke Xendit jika webhook belum masuk (Fallback)
        if ($invoice->status_bayaran !== 'paid' && $invoice->xendit_id) {
            
            try {
                $response = Http::withBasicAuth(config('services.xendit.secret_key'), '')
                            ->get(config('services.xendit.base_url') . '/v2/invoices/' . $invoice->xendit_id);
                
                $data = $response->json();

                // 1. Terima status PAID atau SETTLED
                if (isset($data['status']) && ($data['status'] === 'PAID' || $data['status'] === 'SETTLED')) {
                     
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
                Log::error('Gagal Cek Status Manual: ' . $e->getMessage());
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
