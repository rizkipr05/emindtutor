<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('company_invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('company_invoices', 'reminder_expired_sent')) {
                $table->boolean('reminder_expired_sent')->default(false);
            }

            if (!Schema::hasColumn('company_invoices', 'reminder_3_days_sent')) {
                $table->boolean('reminder_3_days_sent')->default(false);
            }

            if (!Schema::hasColumn('company_invoices', 'reminder_7_days_processed')) {
                $table->boolean('reminder_7_days_processed')->default(false);
            }
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_invoices', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('company_invoices', 'reminder_expired_sent')) {
                $columnsToDrop[] = 'reminder_expired_sent';
            }

            if (Schema::hasColumn('company_invoices', 'reminder_3_days_sent')) {
                $columnsToDrop[] = 'reminder_3_days_sent';
            }

            if (Schema::hasColumn('company_invoices', 'reminder_7_days_processed')) {
                $columnsToDrop[] = 'reminder_7_days_processed';
            }

            if ($columnsToDrop !== []) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
