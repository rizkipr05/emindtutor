<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_invoices', function (Blueprint $table) {
            $table->string('toyyibpay_bill_code')->nullable()->after('external_id')->index();
        });

        Schema::table('student_payments', function (Blueprint $table) {
            $table->string('bukti_bayar')->nullable()->change();
            $table->string('provider')->nullable()->after('status');
            $table->string('gateway_refno')->nullable()->after('provider')->unique();
        });
    }

    public function down(): void
    {
        Schema::table('student_payments', function (Blueprint $table) {
            $table->dropUnique(['gateway_refno']);
            $table->dropColumn(['provider', 'gateway_refno']);
            $table->string('bukti_bayar')->nullable(false)->change();
        });

        Schema::table('student_invoices', function (Blueprint $table) {
            $table->dropIndex(['toyyibpay_bill_code']);
            $table->dropColumn('toyyibpay_bill_code');
        });
    }
};