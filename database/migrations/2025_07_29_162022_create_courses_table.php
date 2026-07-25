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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('nama_kos'); // Contoh: Yuran Bulanan, Yuran Kelas Tambahan
            $table->integer('fee'); // Contoh: 50
            $table->boolean('charge_on_registration')->default(false); // No
            $table->boolean('include_registration_fee')->default(false); // Include YURAN PENDAFTARAN
            $table->enum('teaching_mode', ['offline', 'online', 'hybrid']); // Teaching mode
            $table->integer('minimum_sessions')->nullable(); 
            $table->enum('minimum_sessions_unit', ['session', 'hour', 'minute'])->nullable(); ; // satuan
            $table->integer('sessions_duration')->nullable(); 
            $table->enum('sessions_duration_unit', ['minute', 'hour']); // satuan
            $table->text('description')->nullable(); // Template text laporan
            $table->text('instructions_template')->nullable(); // Template text laporan
            $table->text('session_notes_template')->nullable();
            $table->enum('jenis', ['individu', 'kumpulan']); // Target biaya
            $table->string('registration_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
