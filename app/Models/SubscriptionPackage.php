<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionPackage extends Model
{
    use HasFactory;

    protected $table = 'subscription_packages';

    protected $fillable = [
        'is_trial',
        'nama_pakej',
        'duration_days',
        'harga',
        'maks_pelajar',
        'maks_guru',
        'maks_kelas',
        'deskripsi',
        'status',
        'pricing_per_student',
        'harga_per_pelajar',
    ];

    protected $casts = [
        'is_trial' => 'boolean',
        'duration_days' => 'integer',
        'harga' => 'integer',
        'maks_pelajar' => 'integer',
        'maks_guru' => 'integer',
        'maks_kelas' => 'integer',
    ];

    public function package()
    {
        return $this->belongsTo(SubscriptionPackage::class, 'package_id');
    }

    public function companyInvoices()
    {
        return $this->hasMany(CompanyInvoice::class, 'package_id');
    }
}
