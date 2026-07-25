<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    // Ambil locale dari session, jika tidak ada gunakan 'en' sebagai default
    // (normalized to match Localization middleware)
    $locale = session('locale', 'en');

        // Set locale untuk aplikasi
        App::setLocale($locale);

        return $next($request);
    }
}