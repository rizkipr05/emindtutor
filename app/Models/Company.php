<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_wakil_syarikat',
        'pic_telefon',
        'pic_email',
        'alamat',
        'link',
        'no_telefon',
        'nombor_pendaftaran_syarikat',
        
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // (Opsional) Relasi ke subscriptions
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    // (Opsional) Relasi ke invoices
    public function invoices()
    {
        return $this->hasMany(CompanyInvoice::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function teacherRule()
    {
        return $this->hasOne(TeacherRule::class, 'company_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class);
    }

    public function companyBankDetails()
    {
        return $this->hasOne(CompanyBankDetail::class, 'company_id');
    }

    public function paymentGateway()
    {
        return $this->hasOne(PaymentGateway::class);
    }
}
