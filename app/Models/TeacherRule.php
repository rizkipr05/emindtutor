<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeacherRule extends Model
{
    use HasFactory;

    protected $table = 'teacher_rules';

    protected $fillable = [
        'company_id',
        'rule_description',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
