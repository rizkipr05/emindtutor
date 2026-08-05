<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch($lang)
    {
        $lang = in_array($lang, ['en', 'my'], true) ? $lang : 'en';
        session(['locale' => $lang]);
        app()->setLocale($lang);

        // Kembali ke halaman sebelumnya
        return redirect()->back();
    }
}
