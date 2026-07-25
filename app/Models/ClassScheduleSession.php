<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassScheduleSession extends Model
{
    protected $fillable = [
        'class_schedule_id',
        'class_schedule_day_id',
        'session_date',
        'start_time',
        'end_time',
        'status',
        'cancellation_reason',
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    public function classSchedule()
    {
        return $this->belongsTo(ClassSchedule::class);
    }

    public function classScheduleDay()
    {
        return $this->belongsTo(ClassScheduleDay::class);
    }
}
