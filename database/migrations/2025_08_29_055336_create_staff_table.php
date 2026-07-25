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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('company_id');
            $table->string('phone');
            $table->string('ic_number');
            $table->integer('allowance');
            $table->enum('allowance_type', ['per_hour', 'per_session', 'per_month']); // jenis allowance
            $table->string('bank_name');     // Nama bank
            $table->string('bank_account_number'); // Nomor rekening
            $table->string('bank_account_holder'); // Nama pemegang rekening
            $table->string('account_holders_ic_number')->nullable(); // Alamat
            $table->enum('status', ['active', 'inactive'])->default('active'); // status ketersediaan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
