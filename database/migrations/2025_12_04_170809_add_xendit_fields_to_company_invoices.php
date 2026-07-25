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
            // external_id wajib di Xendit untuk identifikasi unik
            if (!Schema::hasColumn('company_invoices', 'external_id')) {
                $table->string('external_id')->nullable()->after('nomor_invoice');
            }
            // xendit_id adalah ID internal dari Xendit (contoh: 5f47a...)
            if (!Schema::hasColumn('company_invoices', 'xendit_id')) {
                $table->string('xendit_id')->nullable()->after('external_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_invoices', function (Blueprint $table) {
            $columns = array_filter(['external_id', 'xendit_id'], function ($column) {
                return Schema::hasColumn('company_invoices', $column);
            });

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
