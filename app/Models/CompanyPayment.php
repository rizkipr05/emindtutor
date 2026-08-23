<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPayment extends Model
{
    protected $casts = [
        'dibayar_pada' => 'datetime',
        'balance_after' => 'decimal:2',
    ];

    protected $fillable = [
        'company_invoice_id',
        'jumlah',
        'status',
        'transaction_id',
        'payment_method',
        'dibayar_pada',
        'reference_number',
        'notes',
        'balance_after',
    ];

    public function invoice()
    {
        return $this->belongsTo(CompanyInvoice::class, 'company_invoice_id');
    }
}
