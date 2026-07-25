<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassScheduleStudent extends Model
{
    protected $table = 'class_schedule_student';

    protected $fillable = [
        'student_id',
        'class_schedule_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function classSchedule()
    {
        return $this->belongsTo(ClassSchedule::class);
    }
}
