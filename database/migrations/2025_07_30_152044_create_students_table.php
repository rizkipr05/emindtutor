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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('company_id');
            $table->string('ic_number'); // Nomor IC pelajar
            $table->string('phone');
            $table->string('address')->nullable();
            $table->string('notes')->nullable(); // Nomor IC pelajar
            $table->string('sponsor_name')->nullable(); 
            $table->string('sponsor_phone')->nullable();
            $table->string('sponsor_email')->nullable();
            $table->enum('is_approved', ['yes', 'no'])->default('no'); // Status kelulusan pelajar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
