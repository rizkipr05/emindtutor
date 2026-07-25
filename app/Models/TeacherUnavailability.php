<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherUnavailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'day',
        'start_time',
        'end_time',
        'reason',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}