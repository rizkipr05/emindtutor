<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppReminder extends Model
{
    protected $fillable = [
        'company_id', 'audience', 'title', 'message', 'frequency',
        'next_run_at', 'last_run_at', 'is_active',
    ];

    protected $casts = [
        'next_run_at' => 'datetime',
        'last_run_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function dispatches()
    {
        return $this->hasMany(WhatsAppReminderDispatch::class);
    }
}