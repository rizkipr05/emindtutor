<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_clocking_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_schedule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->date('clock_date');
            $table->dateTime('clock_in_at');
            $table->dateTime('clock_out_at')->nullable();
            $table->unsignedInteger('planned_duration_minutes')->nullable();
            $table->string('clock_in_photo')->nullable();
            $table->string('class_document')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('class_report')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'class_schedule_id', 'clock_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_clocking_sessions');
    }
};
