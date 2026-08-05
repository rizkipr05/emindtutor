<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Localization
{
    public function handle($request, Closure $next)
    {
        // Ambil bahasa dari query atau session
        $locale = $request->query('lang', Session::get('locale', 'en'));
        $locale = in_array($locale, ['en', 'my'], true) ? $locale : 'en';

        // Set bahasa yang dipilih
        App::setLocale($locale);

        // Simpan bahasa ke session
        Session::put('locale', $locale);

        return $next($request);
    }
}