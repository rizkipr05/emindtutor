<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_invoices', function (Blueprint $table) {
            if (! Schema::hasColumn('company_invoices', 'toyyibpay_bill_code')) {
                $table->string('toyyibpay_bill_code')->nullable()->after('external_id')->index();
            }

            if (! Schema::hasColumn('company_invoices', 'payment_url')) {
                $table->string('payment_url', 255)->nullable()->after('jumlah');
            }
        });
    }

    public function down(): void
    {
        Schema::table('company_invoices', function (Blueprint $table) {
            foreach (['toyyibpay_bill_code', 'payment_url'] as $column) {
                if (Schema::hasColumn('company_invoices', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
