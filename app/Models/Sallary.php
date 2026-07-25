<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sallary extends Model
{
    protected $fillable = [
        'teacher_id',
        'staff_id',
        'class_schedule_id',
        'attendance_id',
        'amount',
        'description',
        'month',
        'year',
        'payment_date',
        'status', // pending, paid, failed
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function classSchedule()
    {
        return $this->belongsTo(ClassSchedule::class);
    }
}
