<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
