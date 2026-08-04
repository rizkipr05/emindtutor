<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            [
                'setting_group' => 'email_templates',
                'setting_key' => 'welcome_email_subject',
                'setting_label' => 'Welcome Email Subject',
                'setting_type' => 'text',
                'setting_value' => 'Welcome to eMindTutor, {company_name}',
            ],
            [
                'setting_group' => 'email_templates',
                'setting_key' => 'welcome_email_body',
                'setting_label' => 'Welcome Email Body',
                'setting_type' => 'textarea',
                'setting_value' => "Hi {company_name},\n\nYour company account has been successfully activated.\n\nLogin email: {email}\nLogin URL: {login_url}\n\nPlease log in to complete your company profile and start managing teachers, students, classes, and payments.\n\nBest regards,\nTeam eMindTutor",
            ],
            [
                'setting_group' => 'email_templates',
                'setting_key' => 'payment_confirmation_subject',
                'setting_label' => 'Payment Confirmation Subject',
                'setting_type' => 'text',
                'setting_value' => 'Payment Confirmation - {invoice_number}',
            ],
            [
                'setting_group' => 'email_templates',
                'setting_key' => 'payment_confirmation_body',
                'setting_label' => 'Payment Confirmation Body',
                'setting_type' => 'textarea',
                'setting_value' => "Hi {company_name},\n\nYour subscription payment has been confirmed.\n\nInvoice: {invoice_number}\nPackage: {package_name}\nAmount: RM {amount}\nPaid date: {paid_date}\nActive until: {expired_date}\n\nYour subscription access is now active.\n\nBest regards,\nTeam eMindTutor",
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('system_settings')->updateOrInsert(
                ['setting_key' => $setting['setting_key']],
                $setting + ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }

    public function down(): void
    {
        DB::table('system_settings')
            ->whereIn('setting_key', [
                'welcome_email_subject',
                'welcome_email_body',
                'payment_confirmation_subject',
                'payment_confirmation_body',
            ])
            ->delete();
    }
};
