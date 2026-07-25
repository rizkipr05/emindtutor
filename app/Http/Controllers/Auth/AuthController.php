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
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use App\Services\RoleRedirector;


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
        return view('compro.signup');
    }

    public function register_post(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nama_wakil_syarikat' => 'required|string|max:255',
            'pic_telefon' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'no_telefon' => 'required|string|max:20',
        ]);

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
        Company::create([
            'user_id' => $user->id,
            'nama_wakil_syarikat' => $request->nama_wakil_syarikat,
            'pic_telefon' => $request->pic_telefon,
            'alamat' => $request->alamat,
            'no_telefon' => $request->no_telefon,
            'nombor_pendaftaran_syarikat' => $nomorPendaftar,
        ]);

        // Kirim Email
        try {
            Mail::to($user->email)->send(new WelcomeEmail($user));
        } catch (\Exception $e) {
            // Log error jika email gagal terkirim agar aplikasi tidak crash
            \Log::error("Gagal kirim welcome email: " . $e->getMessage());
        }

        Auth::login($user);

        // cek apakah datang dari subscribe flow
        if (session('from_subscribe')) {
            $packageId = session('selected_package');

            // hapus biar ga ke-redirect lagi di login berikutnya
            session()->forget(['from_subscribe', 'selected_package']);

            return redirect()->route('subscribe.payment', $packageId);
        }

        // dd(session('from_subscribe'));

    return redirect()->route('a.dashboard.index')->with('success', __('messages.company_created'));
    }
}
