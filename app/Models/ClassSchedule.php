<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'course_id',
        'teacher_id',
        'nama_kelas',
        'link_group',
        'notes',
        'allowance_type',
        'allowance',
        'status',
        'recurrence_type',
        'recurrence_start_date',
        'recurrence_end_date',
    ];

    protected $casts = [
        'recurrence_start_date' => 'date',
        'recurrence_end_date' => 'date',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function classScheduleDays()
    {
        return $this->hasMany(ClassScheduleDay::class);
    }

    public function sessions()
    {
        return $this->hasMany(ClassScheduleSession::class);
    }

    public function exceptions()
    {
        return $this->hasMany(ClassScheduleException::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'class_schedule_student', 'class_schedule_id', 'student_id');
    }

    public function teacherAttendances()
    {
        return $this->hasMany(TeacherAttendance::class);
    }

    public function studentAttendances()
    {
        return $this->hasMany(StudentAttendance::class);
    }

    public function studentClockingSessions()
    {
        return $this->hasMany(StudentClockingSession::class);
    }

    public function classScheduleStudent()
    {
        return $this->hasMany(ClassScheduleStudent::class);
    }
}
