<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'monthly_report_schedule')) {
                $table->enum('monthly_report_schedule', ['first_day', 'last_day', 'custom_day'])
                    ->default('first_day')
                    ->after('status');
            }
            if (!Schema::hasColumn('companies', 'monthly_report_custom_day')) {
                $table->unsignedTinyInteger('monthly_report_custom_day')
                    ->nullable()
                    ->after('monthly_report_schedule');
            }
            if (!Schema::hasColumn('companies', 'monthly_report_pdf_title')) {
                $table->string('monthly_report_pdf_title')
                    ->nullable()
                    ->after('monthly_report_custom_day');
            }
            if (!Schema::hasColumn('companies', 'monthly_report_pdf_footer')) {
                $table->text('monthly_report_pdf_footer')
                    ->nullable()
                    ->after('monthly_report_pdf_title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['monthly_report_schedule', 'monthly_report_custom_day', 'monthly_report_pdf_title', 'monthly_report_pdf_footer']);
        });
    }
};
