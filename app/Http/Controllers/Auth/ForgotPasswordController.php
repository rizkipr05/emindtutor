<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ForgotPasswordController extends Controller
{
    // 1. Tampilkan form input email
    public function showLinkRequestForm() {
        return view('forgot-password');
    }

    // 2. Kirim link reset ke email
    public function sendResetLinkEmail(Request $request) {
        $request->validate(['email' => 'required|email|exists:users,email']);

        try {
            // Mengirim link via Broker Laravel (Ini akan memanggil Notifikasi yang kita buat tadi)
            $status = Password::sendResetLink($request->only('email'));
        } catch (\Throwable $exception) {
            Log::error('Password reset email could not be sent.', [
                'email' => $request->email,
                'exception' => $exception->getMessage(),
            ]);

            return back()->withInput()->withErrors([
                'email' => 'E-mel reset kata laluan tidak dapat dihantar buat masa ini. Sila cuba lagi kemudian atau hubungi pentadbir.',
            ]);
        }

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    // 3. Tampilkan form ganti password baru
    public function showResetForm(Request $request, $token = null) {
        return view('reset-password')->with(['token' => $token, 'email' => $request->email]);
    }

    // 4. Proses update password baru
    public function reset(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}