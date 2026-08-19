<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_syarikat',
        'email_syarikat',
        'nama_wakil_syarikat',
        'pic_telefon',
        'pic_email',
        'alamat',
        'link',
        'no_telefon',
        'nombor_pendaftaran_syarikat',
        'logo_syarikat',
        'status',
        'monthly_report_schedule',
        'monthly_report_custom_day',
        'monthly_report_pdf_title',
        'monthly_report_pdf_footer',
    ];

    protected $casts = [
        'status' => 'boolean',
        'monthly_report_custom_day' => 'integer',
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

    public function monthlyStudentReports()
    {
        return $this->hasMany(MonthlyStudentReport::class);
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

    /**
     * Return the gateway that should be used for this company.
     *
     * A company-specific gateway takes priority. When one has not yet been
     * configured, use the active global gateway configured by Super Admin.
     * An explicitly disabled company gateway remains disabled.
     */
    public function effectivePaymentGateway(): ?PaymentGateway
    {
        $gateway = $this->relationLoaded('paymentGateway')
            ? $this->getRelation('paymentGateway')
            : $this->paymentGateway()->first();

        if ($gateway) {
            return $gateway->is_active ? $gateway : null;
        }

        return PaymentGateway::superAdmin();
    }

    public function whatsAppReminders()
    {
        return $this->hasMany(WhatsAppReminder::class);
    }
}
