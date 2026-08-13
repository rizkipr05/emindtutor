<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyStudentReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'student_id',
        'report_month',
        'report_year',
        'period_start',
        'period_end',
        'collected_at',
        'summary',
        'pdf_path',
        'public_token',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'collected_at' => 'datetime',
        'summary' => 'array',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
