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
        Schema::table('student_invoices', function (Blueprint $table) {
            // external_id biasanya digunakan sebagai unique reference untuk Xendit
            $table->string('external_id')->nullable()->after('nomor_invoice')->index();
            
            // payment_url untuk menyimpan link invoice dari Xendit
            $table->string('payment_url')->nullable()->after('external_id');
            
            // Opsional: tambahkan kolom expiry_date jika ingin sinkron dengan Xendit
            $table->timestamp('expiry_date')->nullable()->after('payment_url');
        });
    }

    public function down(): void
    {
        Schema::table('student_invoices', function (Blueprint $table) {
            $table->dropColumn(['external_id', 'payment_url', 'expiry_date']);
        });
    }
};
