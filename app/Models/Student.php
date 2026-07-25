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

    public function hasActiveCourseAccess(int $courseId, ?Carbon $date = null): bool
    {
        $date = $date ?: Carbon::now('Asia/Kuala_Lumpur');
        $dateString = $date->toDateString();

        $paidInvoices = $this->relationLoaded('invoices')
            ? $this->invoices->where('course_id', $courseId)->where('status_bayaran', 'paid')
            : StudentInvoice::where('student_id', $this->id)
                ->where('course_id', $courseId)
                ->where('status_bayaran', 'paid')
                ->get();

        $hasActiveInvoice = $paidInvoices->contains(function ($invoice) use ($dateString) {
            if (!$invoice->tarikh_terbit || !$invoice->tarikh_tamat) {
                return false;
            }

            return $invoice->tarikh_terbit <= $dateString
                && $invoice->tarikh_tamat >= $dateString;
        });

        if ($hasActiveInvoice) {
            return true;
        }

        if ($this->status !== 'active') {
            return false;
        }

        if (!$this->sub_start && !$this->sub_end) {
            return true;
        }

        if ($this->sub_start && $this->sub_start > $dateString) {
            return false;
        }

        if ($this->sub_end && $this->sub_end < $dateString) {
            return false;
        }

        return true;
    }
}
