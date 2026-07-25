<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubAktif extends Model
{
    use HasFactory;

    // Nama tabel (optional kalau Laravel sudah bisa tebak)
    protected $table = 'sub_aktif';

    // Kolom yang bisa diisi
    protected $fillable = [
        'pricing_per_student',
    ];
}
