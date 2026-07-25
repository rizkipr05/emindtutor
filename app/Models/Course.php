<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'nama_kos',
        'fee',
        'max_student',
        'jenis',
        'teaching_mode',
        'minimum_sessions',
        'minimum_sessions_unit',
        'sessions_duration',
        'sessions_duration_unit',

        'charge_on_registration',
        'include_registration_fee',
        'registration_link',

        'description',
        'instructions_template',
        'session_notes_template',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function classSchedule()
    {
        return $this->hasMany(ClassSchedule::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
