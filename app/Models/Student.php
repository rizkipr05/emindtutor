<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'ic_number',
        'phone',
        'address',
        'notes',
        'sponsor_name',
        'sponsor_phone',
        'sponsor_email',
        'is_approved',
        'status',
        'sub_start',
        'sub_end',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function invoices()
    {
        return $this->hasMany(StudentInvoice::class);
    }

    public function classScheduleStudents()
    {
        return $this->hasMany(ClassScheduleStudent::class);
    }

    public function review()
    {
        return $this->hasMany(Review::class);
    }

    public function monthlyReports()
    {
        return $this->hasMany(MonthlyStudentReport::class);
    }

    public function teachingReports()
    {
        return $this->hasMany(TeachingReport::class);
    }

    public function clockingSessions()
    {
        return $this->hasMany(StudentClockingSession::class);
    }

    public function hasActiveCourseAccess(int $courseId, ?Carbon $date = null): bool
    {
        $date = $date ?: Carbon::now('Asia/Kuala_Lumpur');
        $dateString = $date->toDateString();

        $paidInvoices = $this->relationLoaded('invoices')
            ? $this->invoices->where('course_id', $courseId)->where('status_bayaran', 'paid')->filter(fn ($invoice) => $invoice->activated_at && $invoice->class_schedule_id)
            : StudentInvoice::where('student_id', $this->id)
                ->where('course_id', $courseId)
                ->where('status_bayaran', 'paid')
                ->whereNotNull('activated_at')
                ->whereNotNull('class_schedule_id')
                ->get();

        $hasActiveInvoice = $paidInvoices->contains(function ($invoice) use ($dateString) {
            if (!$invoice->tarikh_terbit || !$invoice->tarikh_tamat) {
                return false;
            }

            return $invoice->tarikh_terbit <= $dateString
                && $invoice->tarikh_tamat >= $dateString;
        });

        return $hasActiveInvoice;
    }
}
