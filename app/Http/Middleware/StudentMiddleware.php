<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            if (in_array(Auth::user()->role, ['student'])) {
                return $next($request);
            }
        }

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return redirect('/')->with('error', 'Akses ini hanya untuk murid (student) sahaja.');
    }
}
