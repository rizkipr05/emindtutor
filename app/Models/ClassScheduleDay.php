<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassScheduleDay extends Model
{
    protected $fillable = [
        'class_schedule_id',
        'hari',
        'jam_mulai',
        'durasi_menit',
    ];

    public function sessions()
    {
        return $this->hasMany(ClassScheduleSession::class);
    }

    public function classSchedule()
    {
        return $this->belongsTo(ClassSchedule::class);
    }
}
