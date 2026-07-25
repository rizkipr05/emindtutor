<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassScheduleException extends Model
{
    protected $fillable = [
        'class_schedule_id',
        'exception_date',
        'action',
        'reason',
    ];

    protected $casts = [
        'exception_date' => 'date',
    ];

    public function classSchedule()
    {
        return $this->belongsTo(ClassSchedule::class);
    }
}
