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
            Schema::create('company_payments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_invoice_id');
                $table->integer('jumlah');
                $table->string('status')->default('pending'); // pending, success, failed
                $table->string('transaction_id')->nullable(); // dari gateway
                $table->string('payment_method')->nullable(); // fpx, toyyibpay, dll
                $table->dateTime('dibayar_pada')->nullable();
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
