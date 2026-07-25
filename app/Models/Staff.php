<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';

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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
