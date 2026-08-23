<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_invoices', function (Blueprint $table) {
            if (! Schema::hasColumn('company_invoices', 'invoice_type')) {
                $table->string('invoice_type')->default('subscription')->after('nomor_invoice');
            }
            if (! Schema::hasColumn('company_invoices', 'description')) {
                $table->text('description')->nullable()->after('invoice_type');
            }
        });

        Schema::table('company_payments', function (Blueprint $table) {
            if (! Schema::hasColumn('company_payments', 'reference_number')) {
                $table->string('reference_number')->nullable()->after('transaction_id');
            }
            if (! Schema::hasColumn('company_payments', 'notes')) {
                $table->text('notes')->nullable()->after('payment_method');
            }
            if (! Schema::hasColumn('company_payments', 'balance_after')) {
                $table->decimal('balance_after', 12, 2)->nullable()->after('jumlah');
            }
        });
    }

    public function down(): void
    {
        Schema::table('company_invoices', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('company_invoices', 'description') ? 'description' : null,
                Schema::hasColumn('company_invoices', 'invoice_type') ? 'invoice_type' : null,
            ]);
            if ($columns !== []) $table->dropColumn($columns);
        });
        Schema::table('company_payments', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('company_payments', 'balance_after') ? 'balance_after' : null,
                Schema::hasColumn('company_payments', 'notes') ? 'notes' : null,
                Schema::hasColumn('company_payments', 'reference_number') ? 'reference_number' : null,
            ]);
            if ($columns !== []) $table->dropColumn($columns);
        });
    }
};
