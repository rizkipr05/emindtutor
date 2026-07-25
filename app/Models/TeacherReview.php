<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeacherReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id', 'student_id', 'class_schedule_id', 'rating', 'comment',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function classSchedule()
    {
        return $this->belongsTo(ClassSchedule::class);
    }
}
