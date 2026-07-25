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
        'tarikh_terbit',
        'tarikh_tamat',
        'external_id',
        'payment_url',
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

    public function review()
    {
        return $this->hasMany(TeacherReview::class);
    }
}
