<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPayment extends Model
{
    protected $fillable = [
        'company_invoice_id',
        'jumlah',
        'status',
        'transaction_id',
        'payment_method',
        'dibayar_pada',
    ];

    public function invoice()
    {
        return $this->belongsTo(CompanyInvoice::class, 'company_invoice_id');
    }
}
