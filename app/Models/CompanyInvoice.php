<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyInvoice extends Model
{
    protected $fillable = [
        'company_id',
        'package_id',
        'nomor_invoice',
        'external_id',
        'toyyibpay_bill_code',
        'payment_url',
        'jumlah',
        'status',
        'status_bayaran',
        'tarikh_terbit',
        'tarikh_tamat',
        'reminder_expired_sent',
        'reminder_3_days_sent',
        'reminder_7_days_processed',
    ];

    protected $casts = [
        'tarikh_terbit' => 'date',
        'tarikh_tamat' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function payments()
    {
        return $this->hasMany(CompanyPayment::class, 'company_invoice_id');
    }

    public function package()
    {
        return $this->belongsTo(SubscriptionPackage::class);
    }
}
