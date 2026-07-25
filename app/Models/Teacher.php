<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'phone',
        'ic_number',
        'allowance',
        'allowance_type',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'account_holders_ic_number',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class);
    }

    public function unavailabilities()
    {
        return $this->hasMany(TeacherUnavailability::class);
    }
    
    public function reviews()
    {
        return $this->hasMany(TeacherReview::class);
    }

    public function attendances()
    {
        return $this->hasMany(TeacherAttendance::class);
    }
}
