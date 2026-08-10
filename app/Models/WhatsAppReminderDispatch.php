<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppReminderDispatch extends Model
{
    protected $fillable = [
        'whats_app_reminder_id', 'recipient_type', 'recipient_id',
        'recipient_name', 'recipient_phone', 'message', 'scheduled_for', 'sent_at', 'status',
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function reminder()
    {
        return $this->belongsTo(WhatsAppReminder::class, 'whats_app_reminder_id');
    }
}