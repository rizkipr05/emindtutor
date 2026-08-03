<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingCompanyRegistration extends Model
{
    protected $fillable = ['package_id', 'name', 'email', 'password', 'nama_wakil_syarikat', 'pic_telefon', 'alamat', 'no_telefon', 'external_reference', 'toyyibpay_bill_code', 'payment_url', 'status', 'expires_at', 'user_id', 'company_id'];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return ['expires_at' => 'datetime'];
    }
}
