<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppReminderTemplate extends Model
{
    protected $fillable = ['audience', 'title', 'message', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}