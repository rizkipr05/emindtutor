<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'nama_diskon', 'kode_diskon', 'deskripsi', 'percent', 'potongan', 'end_date',
    ];
}
