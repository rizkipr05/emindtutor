<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentInvoice extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'nomor_invoice',
        'jumlah',
        'status_bayaran',
        'payment_verified_at',
        'activated_at',
        'class_schedule_id',
        'tarikh_terbit',
        'tarikh_tamat',
        'external_id',
        'toyyibpay_bill_code',
        'payment_url',
    ];

    protected $casts = [
        'payment_verified_at' => 'datetime',
        'activated_at' => 'datetime',
        'tarikh_terbit' => 'date',
        'tarikh_tamat' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function payment()
    {
        return $this->hasOne(StudentPayment::class, 'student_invoice_id');
    }

    public function classSchedule()
    {
        return $this->belongsTo(ClassSchedule::class);
    }

    public function review()
    {
        return $this->hasMany(TeacherReview::class);
    }
}
