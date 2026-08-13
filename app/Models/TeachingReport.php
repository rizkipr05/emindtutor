<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'teacher_id', 'class_schedule_id', 'student_id',
        'class_date', 'learning_topic', 'teacher_note', 'attendance_status',
    ];

    protected $casts = ['class_date' => 'date'];

    public function company() { return $this->belongsTo(Company::class); }
    public function teacher() { return $this->belongsTo(Teacher::class); }
    public function classSchedule() { return $this->belongsTo(ClassSchedule::class); }
    public function student() { return $this->belongsTo(Student::class); }
}
