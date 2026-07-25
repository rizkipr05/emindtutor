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
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('teacher_id');
            $table->string('nama_kelas');
            $table->string('link_group')->nullable(); 
            $table->text('notes')->nullable();
            $table->enum('allowance_type', ['per_month', 'per_session', 'per_hour'])->nullable();
            $table->decimal('allowance', 10, 2)->nullable();
            $table->enum('status', ['selesai', 'akan_datang', 'batal'])->default('akan_datang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_schedules');
    }
};
