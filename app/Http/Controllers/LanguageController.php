<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch($lang)
    {
        // Validasi apakah bahasa yang dipilih valid
        if (in_array($lang, ['en', 'my'])) {
            session(['locale' => $lang]);  // Set bahasa ke session
        } else {
            // Jika bahasa tidak valid, set ke bahasa default (misalnya 'en')
            session(['locale' => 'en']);
        }

        // Kembali ke halaman sebelumnya
        return redirect()->back();
    }
}
