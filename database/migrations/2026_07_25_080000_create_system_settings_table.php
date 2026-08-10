<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('system_settings')) {
            Schema::create('system_settings', function (Blueprint $table) {
                $table->id();
                $table->string('setting_group', 50)->index();
                $table->string('setting_key')->unique();
                $table->string('setting_label');
                $table->string('setting_type', 20)->default('text');
                $table->text('setting_value')->nullable();
                $table->timestamps();
            });
        }

        $defaults = [
            ['setting_group' => 'overview', 'setting_key' => 'app_name', 'setting_label' => 'Application Name', 'setting_type' => 'text', 'setting_value' => config('app.name', 'EMindTutor')],
            ['setting_group' => 'overview', 'setting_key' => 'default_locale', 'setting_label' => 'Default Language', 'setting_type' => 'select', 'setting_value' => app()->getLocale() ?: 'en'],
            ['setting_group' => 'overview', 'setting_key' => 'support_email', 'setting_label' => 'Support Email', 'setting_type' => 'email', 'setting_value' => config('mail.from.address', 'support@emindtutor.test')],
            ['setting_group' => 'companies', 'setting_key' => 'allow_login_as', 'setting_label' => 'Allow Login-as', 'setting_type' => 'toggle', 'setting_value' => '1'],
            ['setting_group' => 'companies', 'setting_key' => 'auto_activate_companies', 'setting_label' => 'Auto Activate Companies', 'setting_type' => 'toggle', 'setting_value' => '1'],
            ['setting_group' => 'packages', 'setting_key' => 'trial_package_id', 'setting_label' => 'Trial Package', 'setting_type' => 'select', 'setting_value' => (string) DB::table('subscription_packages')->where('is_trial', true)->value('id')],
            ['setting_group' => 'packages', 'setting_key' => 'allow_package_management', 'setting_label' => 'Allow Package Management', 'setting_type' => 'toggle', 'setting_value' => '1'],
            ['setting_group' => 'billing', 'setting_key' => 'allow_manual_verification', 'setting_label' => 'Allow Manual Verification', 'setting_type' => 'toggle', 'setting_value' => '1'],
            ['setting_group' => 'billing', 'setting_key' => 'invoice_reminder_days', 'setting_label' => 'Invoice Reminder Days', 'setting_type' => 'number', 'setting_value' => '7'],
            ['setting_group' => 'billing', 'setting_key' => 'subscription_grace_days', 'setting_label' => 'Subscription Grace Days', 'setting_type' => 'number', 'setting_value' => '7'],
            ['setting_group' => 'communications', 'setting_key' => 'class_clock_in_reminder_minutes', 'setting_label' => 'Class Clock-in Reminder Minutes', 'setting_type' => 'number', 'setting_value' => '30'],
            ['setting_group' => 'staff', 'setting_key' => 'enable_staff_module', 'setting_label' => 'Enable Staff Module', 'setting_type' => 'toggle', 'setting_value' => '1'],
            ['setting_group' => 'staff', 'setting_key' => 'auto_activate_staff', 'setting_label' => 'Auto Activate Staff', 'setting_type' => 'toggle', 'setting_value' => '0'],
            ['setting_group' => 'reports', 'setting_key' => 'allow_pdf_export', 'setting_label' => 'Allow PDF Export', 'setting_type' => 'toggle', 'setting_value' => '1'],
            ['setting_group' => 'reports', 'setting_key' => 'allow_excel_export', 'setting_label' => 'Allow Excel Export', 'setting_type' => 'toggle', 'setting_value' => '1'],
            ['setting_group' => 'communications', 'setting_key' => 'enable_broadcasts', 'setting_label' => 'Enable Broadcasts', 'setting_type' => 'toggle', 'setting_value' => '1'],
            ['setting_group' => 'communications', 'setting_key' => 'default_audience', 'setting_label' => 'Default Audience', 'setting_type' => 'select', 'setting_value' => 'all'],
        ];

        foreach ($defaults as $setting) {
            DB::table('system_settings')->updateOrInsert(
                ['setting_key' => $setting['setting_key']],
                $setting + ['updated_at' => now(), 'created_at' => now()]
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
