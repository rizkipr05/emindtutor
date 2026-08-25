<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClockingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_schedule_id',
        'teacher_id',
        'clock_date',
        'clock_in_at',
        'clock_out_at',
        'planned_duration_minutes',
        'clock_in_photo',
        'class_document',
        'latitude',
        'longitude',
        'class_report',
    ];

    protected $casts = [
        'clock_date' => 'date',
        'clock_in_at' => 'datetime',
        'clock_out_at' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function classSchedule()
    {
        return $this->belongsTo(ClassSchedule::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
