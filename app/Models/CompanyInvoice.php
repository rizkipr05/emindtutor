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
        'billing_period_start',
        'billing_period_end',
        'active_student_count',
        'invoice_type',
        'description',
    ];

    protected $casts = [
        'tarikh_terbit' => 'date',
        'tarikh_tamat' => 'date',
        'billing_period_start' => 'date',
        'billing_period_end' => 'date',
        'active_student_count' => 'integer',
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
