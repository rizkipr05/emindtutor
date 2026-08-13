<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('monthly_student_reports')) {
            return;
        }

        Schema::create('monthly_student_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('report_month');
            $table->unsignedSmallInteger('report_year');
            $table->date('period_start');
            $table->date('period_end');
            $table->timestamp('collected_at');
            $table->json('summary');
            $table->timestamps();

            $table->unique(['student_id', 'report_month', 'report_year'], 'monthly_student_report_period_unique');
            $table->index(['company_id', 'report_year', 'report_month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_student_reports');
    }
};
