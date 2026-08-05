<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use App\Models\User;
use App\Models\Company;
use App\Mail\WelcomeEmail;
use App\Mail\CompanyReceiptMail;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use App\Services\RoleRedirector;
use App\Services\ToyyibPayService;
use App\Models\PaymentGateway;
use App\Models\PendingCompanyRegistration;
use App\Models\SubscriptionPackage;
use App\Models\CompanyInvoice;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;


class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function login_post(Request $request)
    {
    // Pastikan locale di-set dari session bila middleware localization tidak diterapkan pada route ini
    App::setLocale(session('locale', 'en'));

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => __('messages.email_required'),
            'email.email' => __('messages.email_invalid'),
            'password.required' => __('messages.password_required'),
        ]);
        
        $user = User::where('email', $request->email)->first();

        if ($user && $this->passwordMatches($request->password, $user)) {
            Auth::login($user);
            $request->session()->regenerate();

            return $this->redirectByRole($user->role, __('messages.login_success'));
        }

        // Kembalikan ke halaman login dengan pesan error dan input email tetap terisi
        return back()->withErrors([
            'email' => __('messages.invalid_credentials'),
        ])->withInput($request->only('email'));
    }

    public function update(Request $request)
    {
    // Pastikan locale di-set dari session bila middleware localization tidak diterapkan pada route ini
    App::setLocale(session('locale', 'en'));

        $user = auth()->user();

        // $request->validate([
        //     'password' => 'nullable|min:8|confirmed',
        // ]);
        $request->validate([
            'current_password'      => 'required',
            'new_password'          => 'required|string|min:8|confirmed', // otomatis cocok dengan field `new_password_confirmation`
        ],[
            'new_password.confirmed' => __('messages.password_confirmation_mismatch')
        ]);

        // cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => __('teacher/dashboard.current_password_incorrect')]);
        }

        // update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Redirect ke halaman home dengan pesan sukses (gunakan terjemahan)
        $msg = __('messages.success_saved');
        return redirect()
            ->route(RoleRedirector::routeNameForRole($user->role))
            ->with('success', $msg);

    }

    public function logout()
    {
    Auth::logout();

    return redirect()->route('login')->with('success', __('messages.logout_success'));
    }

    protected function passwordMatches(string $plainPassword, User $user): bool
    {
        try {
            $matches = Hash::check($plainPassword, $user->password);
        } catch (RuntimeException $e) {
            // Fallback for legacy/plain-text passwords so login does not crash.
            if (!hash_equals((string) $user->password, $plainPassword)) {
                return false;
            }

            $user->forceFill([
                'password' => Hash::make($plainPassword),
            ])->save();

            return true;
        }

        if ($matches && Hash::needsRehash($user->password)) {
            $user->forceFill([
                'password' => Hash::make($plainPassword),
            ])->save();
        }

        return $matches;
    }

    protected function redirectByRole(string $role, string $message)
    {
        $routeName = RoleRedirector::routeNameForRole($role);

        return redirect()->route($routeName)->with('success', $message);
    }

    public function register()
    {
        $packages = SubscriptionPackage::query()
            ->where('status', 'aktif')
            ->orderBy('harga')
            ->get();

        return view('compro.signup', [
            'packages' => $packages,
            'selectedPackageId' => old('package_id', session('selected_package')),
        ]);
    }

    public function register_post(Request $request)
    {
        // Compatibility for visitors who selected a package from the public
        // pricing page before this package selector was added to Sign Up.
        if (! $request->filled('package_id') && session()->has('selected_package')) {
            $request->merge(['package_id' => session('selected_package')]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'nama_wakil_syarikat' => 'required|string|max:255',
            'pic_telefon' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'no_telefon' => 'required|string|max:20',
            'package_id' => 'required|exists:subscription_packages,id',
        ]);

        $package = SubscriptionPackage::where('status', 'aktif')->find($request->integer('package_id'));
        if (! $package) {
            return back()->withErrors(['package_id' => 'Paket yang dipilih tidak tersedia.'])->withInput();
        }

        // A paid subscription must be confirmed before any user/company is created.
        if (! $package->is_trial) {
            $gateway = PaymentGateway::superAdmin();
            if (! $gateway) {
                return back()->withErrors(['email' => 'Payment Gateway belum dikonfigurasi.']);
            }

            $existingRegistration = PendingCompanyRegistration::where('email', $request->email)->first();
            if ($existingRegistration?->status === 'pending' && $existingRegistration->payment_url && (! $existingRegistration->expires_at || $existingRegistration->expires_at->isFuture())) {
                session()->forget(['from_subscribe', 'selected_package']);

                return redirect()->away($existingRegistration->payment_url);
            }

            $registrationData = [
                'package_id' => $package->id, 'name' => $request->name, 'email' => $request->email,
                'password' => Hash::make($request->password),
                'nama_wakil_syarikat' => $request->nama_wakil_syarikat, 'pic_telefon' => $request->pic_telefon,
                'alamat' => $request->alamat, 'no_telefon' => $request->no_telefon,
                'external_reference' => 'REG-' . Str::upper(Str::random(20)),
                'expires_at' => now()->addDays($gateway->expiry_days),
                'toyyibpay_bill_code' => null, 'payment_url' => null, 'status' => 'pending',
            ];

            if ($existingRegistration?->status === 'paid') {
                return back()->withErrors(['email' => 'Pembayaran untuk email ini sudah tercatat. Silakan login atau hubungi admin.']);
            }

            if ($existingRegistration) {
                $existingRegistration->update($registrationData);
                $registration = $existingRegistration;
            } else {
                $registration = PendingCompanyRegistration::create($registrationData);
            }

            try {
                $bill = app(ToyyibPayService::class)->createBill([
                    'name' => 'eMindTutor Registration', 'description' => 'Subscription ' . $package->nama_pakej,
                    'amount' => $package->harga, 'reference' => $registration->external_reference,
                    'return_url' => route('registration.payment.return', $registration),
                    'callback_url' => route('registration.payment.callback'),
                    'payer_name' => $request->name, 'payer_email' => $request->email, 'payer_phone' => $request->no_telefon,
                ], $gateway);
                $registration->update(['toyyibpay_bill_code' => $bill['bill_code'], 'payment_url' => $bill['payment_url']]);
                session()->forget(['from_subscribe', 'selected_package']);

                return redirect()->away($bill['payment_url']);
            } catch (\Throwable $e) {
                $registration->delete();
                return back()->withErrors(['email' => 'Gagal membuat pembayaran: ' . $e->getMessage()]);
            }
        }

        $lastCompany = Company::orderBy('id', 'desc')->first();
        // dd($lastCompany->nombor_pendaftaran_syarikat);

        if ($lastCompany && $lastCompany->nombor_pendaftaran_syarikat) {
            // Ambil nomor terakhir → increment
            $lastNumber = (int) str_replace('EMT-', '', $lastCompany->nombor_pendaftaran_syarikat);
            $newNumber = $lastNumber + 1;
            // dd($newNumber);
        } else {
            // Jika belum ada data sama sekali
            $newNumber = 1;
        }

        // Format final
        $nomorPendaftar = 'EMT-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // Simpan ke users
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);
        // dd($request->all());
        // Simpan ke companies
        $company = Company::create([
            'user_id' => $user->id,
            'nama_wakil_syarikat' => $request->nama_wakil_syarikat,
            'pic_telefon' => $request->pic_telefon,
            'alamat' => $request->alamat,
            'no_telefon' => $request->no_telefon,
            'nombor_pendaftaran_syarikat' => $nomorPendaftar,
        ]);

        // Paket percubaan tidak melalui payment gateway, tetapi langganan tetap
        // diaktifkan supaya had fitur syarikat berlaku sejak akaun dibuat.
        if ($package->is_trial) {
            $start = Carbon::today();
            $end = $start->copy()->addDays($package->duration_days);

            CompanyInvoice::create([
                'company_id' => $company->id,
                'package_id' => $package->id,
                'nomor_invoice' => 'TRIAL-' . $company->id,
                'jumlah' => 0,
                'status_bayaran' => 'paid',
                'tarikh_terbit' => $start,
                'tarikh_tamat' => $end,
            ]);

            Subscription::create([
                'company_id' => $company->id,
                'package_id' => $package->id,
                'start_date' => $start,
                'end_date' => $end,
                'status' => 'active',
            ]);
        }

        // Kirim Email
        try {
            Mail::to($user->email)->send(new WelcomeEmail($user));
        } catch (\Exception $e) {
            // Log error jika email gagal terkirim agar aplikasi tidak crash
            \Log::error("Gagal kirim welcome email: " . $e->getMessage());
        }

        Auth::login($user);

        session()->forget(['from_subscribe', 'selected_package']);

    return redirect()->route('a.dashboard.index')->with('success', __('messages.company_created'));
    }

    public function registrationPaymentCallback(Request $request)
    {
        $registration = PendingCompanyRegistration::where('external_reference', $request->input('order_id'))->first();
        $gateway = PaymentGateway::superAdmin();
        abort_unless($registration && $gateway && app(ToyyibPayService::class)->isValidCallback($request->all(), $gateway), 403);

        if ((string) $request->input('status') !== '1' || $registration->status === 'paid') {
            return response()->json(['message' => 'Callback received']);
        }

        $this->completeRegistration($registration);

        return response()->json(['message' => 'Registration completed']);
    }

    private function completeRegistration(PendingCompanyRegistration $registration): User
    {
        return DB::transaction(function () use ($registration) {
            $registration->refresh();
            if ($registration->status === 'paid' && $registration->user_id) {
                return User::findOrFail($registration->user_id);
            }
            abort_if(User::where('email', $registration->email)->exists(), 409, 'Email already registered.');
            $last = Company::whereNotNull('nombor_pendaftaran_syarikat')->latest('id')->value('nombor_pendaftaran_syarikat');
            $number = $last ? ((int) str_replace('EMT-', '', $last)) + 1 : 1;
            $user = User::create(['name' => $registration->name, 'email' => $registration->email, 'password' => $registration->password, 'role' => 'admin']);
            $company = Company::create(['user_id' => $user->id, 'nama_wakil_syarikat' => $registration->nama_wakil_syarikat, 'pic_telefon' => $registration->pic_telefon, 'pic_email' => $registration->email, 'alamat' => $registration->alamat, 'no_telefon' => $registration->no_telefon, 'nombor_pendaftaran_syarikat' => 'EMT-' . str_pad($number, 4, '0', STR_PAD_LEFT), 'status' => true]);
            $package = SubscriptionPackage::findOrFail($registration->package_id);
            $start = Carbon::today(); $end = $start->copy()->addDays($package->duration_days);
            $invoice = CompanyInvoice::create(['company_id' => $company->id, 'package_id' => $package->id, 'nomor_invoice' => 'REG-' . $registration->id, 'external_id' => $registration->external_reference, 'toyyibpay_bill_code' => $registration->toyyibpay_bill_code, 'payment_url' => $registration->payment_url, 'jumlah' => $package->harga, 'status_bayaran' => 'paid', 'tarikh_terbit' => $start, 'tarikh_tamat' => $end]);
            Subscription::create(['company_id' => $company->id, 'package_id' => $package->id, 'start_date' => $start, 'end_date' => $end, 'status' => 'active']);
            $registration->update(['status' => 'paid', 'user_id' => $user->id, 'company_id' => $company->id]);
            try { 
                Mail::to($user->email)->send(new WelcomeEmail($user)); 
                Mail::to($user->email)->send(new CompanyReceiptMail($invoice));
            } catch (\Throwable $e) { 
                \Log::error('Gagal kirim email registrasi: ' . $e->getMessage()); 
            }
            return $user;
        });
    }

    public function registrationPaymentReturn(Request $request, PendingCompanyRegistration $registration)
    {
        $returnStatus = (string) $request->query('status_id', '');

        // Localhost cannot receive ToyyibPay callbacks. On a successful return,
        // verify the bill server-to-server before creating and logging in the user.
        if ($registration->status !== 'paid' && $returnStatus === '1' && $registration->toyyibpay_bill_code) {
            try {
                $gateway = PaymentGateway::superAdmin();
                if ($gateway && app(ToyyibPayService::class)->hasSuccessfulTransaction($registration->toyyibpay_bill_code, $gateway)) {
                    $user = $this->completeRegistration($registration);
                    Auth::login($user);
                    $request->session()->regenerate();

                    return redirect()->route('a.dashboard.index')->with('success', 'Pembayaran berhasil. Akun perusahaan Anda sudah aktif.');
                }
            } catch (\Throwable $e) {
                \Log::error('Gagal verifikasi pembayaran registrasi: ' . $e->getMessage());
            }
        }

        return view('compro.registration-payment-return', compact('registration', 'returnStatus'));
    }
}
