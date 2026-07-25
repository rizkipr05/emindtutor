<?php

namespace App\Http\Middleware;

use App\Models\Staff;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (in_array($user->role, ['admin'])) {
                $company = $user->company;

                if (!$company) {
                    $staff = Staff::with('company')
                        ->where('user_id', $user->id)
                        ->where('status', 'active')
                        ->first();

                    $company = $staff?->company;
                }

                if (!$company) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('login')
                        ->with('error', 'Akun admin ini belum terhubung dengan syarikat.');
                }

                $user->setRelation('company', $company);

                return $next($request);
            }
        }

        return redirect('/superadmin')->with('error', 'Maaf, anda tidak dibenarkan untuk mengakses halaman ini.');
    }
}
