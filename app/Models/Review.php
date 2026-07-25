<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'student_id', 'teacher_id', 'course_id', 'class_schedule_id', 'student_invoice_id',
        'rating', 'comment'
    ];

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function classSchedule() {
        return $this->belongsTo(ClassSchedule::class);
    }

    public function invoice() {
        return $this->belongsTo(StudentInvoice::class);
    }
}
