<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubAktif;

class SubAktifController extends Controller
{
    public function toggle()
    {
        // Ambil data pertama (id 1)
        $sub = SubAktif::find(1);

        if (!$sub) {
            // Kalau belum ada, buat barunya
            $sub = SubAktif::create([
                'pricing_per_student' => true, // default jadi true saat toggle pertama
            ]);
        } else {
            // Toggle nilai boolean
            $sub->pricing_per_student = !$sub->pricing_per_student;
            $sub->save();
        }

        return redirect()->back()->with('success', 'Status pricing per student berhasil diubah!');
    }
}
