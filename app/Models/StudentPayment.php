<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentPayment extends Model
{
    protected $fillable = [
        'student_invoice_id',
        'status', // pending, success, failed
        'dibayar_pada', 
        'bukti_bayar', 
    ];

    public function studentInvoice()
    {
        return $this->belongsTo(StudentInvoice::class);
    }
}
