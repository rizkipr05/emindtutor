<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SystemSetting extends Model
{
    protected $fillable = [
        'setting_group',
        'setting_key',
        'setting_label',
        'setting_type',
        'setting_value',
    ];

    public static function value(string $key, mixed $default = null): mixed
    {
        $value = static::query()->where('setting_key', $key)->value('setting_value');

        return $value === null || $value === '' ? $default : $value;
    }

    public static function fonnteToken(): ?string
    {
        $encryptedToken = static::query()
            ->where('setting_key', 'fonnte_token')
            ->value('setting_value');

        if (blank($encryptedToken)) {
            return null;
        }

        try {
            return Crypt::decryptString($encryptedToken);
        } catch (\Throwable) {
            return null;
        }
    }
}
