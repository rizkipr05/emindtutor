<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teaching_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_schedule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->date('class_date');
            $table->string('learning_topic');
            $table->text('teacher_note')->nullable();
            $table->string('attendance_status', 20)->nullable();
            $table->timestamps();

            $table->unique(['class_schedule_id', 'student_id', 'class_date'], 'teaching_report_student_session_unique');
            $table->index(['company_id', 'student_id', 'class_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_reports');
    }
};
