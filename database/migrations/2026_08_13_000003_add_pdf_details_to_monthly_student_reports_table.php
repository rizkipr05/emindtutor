<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monthly_student_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('monthly_student_reports', 'pdf_path')) {
                $table->string('pdf_path')->nullable()->after('summary');
            }
            if (!Schema::hasColumn('monthly_student_reports', 'public_token')) {
                $table->uuid('public_token')->nullable()->unique()->after('pdf_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('monthly_student_reports', function (Blueprint $table) {
            $table->dropColumn(['pdf_path', 'public_token']);
        });
    }
};
