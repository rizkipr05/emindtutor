<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffEmind extends Model
{
    protected $table = 'staff_emind';

    protected $fillable = [
        'user_id',
        'company_id',
        'phone',
        'ic_number',
        'allowance',
        'allowance_type',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'account_holders_ic_number',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
