<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = [
        'company_id', 'provider', 'user_secret_key', 'category_code', 'base_url',
        'api_url', 'payment_channel', 'expiry_days', 'is_active',
    ];

    protected $hidden = ['user_secret_key'];

    protected function casts(): array
    {
        return [
            'user_secret_key' => 'encrypted',
            'is_active' => 'boolean',
            'payment_channel' => 'integer',
            'expiry_days' => 'integer',
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public static function superAdmin(): ?self
    {
        return static::query()->whereNull('company_id')->where('is_active', true)->first();
    }
}
