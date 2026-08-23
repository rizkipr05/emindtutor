<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_invoices', function (Blueprint $table) {
            if (! Schema::hasColumn('company_invoices', 'billing_period_start')) {
                $table->date('billing_period_start')->nullable()->after('tarikh_tamat');
            }

            if (! Schema::hasColumn('company_invoices', 'billing_period_end')) {
                $table->date('billing_period_end')->nullable()->after('billing_period_start');
            }

            if (! Schema::hasColumn('company_invoices', 'active_student_count')) {
                $table->unsignedInteger('active_student_count')->nullable()->after('billing_period_end');
            }
        });
    }

    public function down(): void
    {
        Schema::table('company_invoices', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('company_invoices', 'active_student_count') ? 'active_student_count' : null,
                Schema::hasColumn('company_invoices', 'billing_period_end') ? 'billing_period_end' : null,
                Schema::hasColumn('company_invoices', 'billing_period_start') ? 'billing_period_start' : null,
            ]);

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
