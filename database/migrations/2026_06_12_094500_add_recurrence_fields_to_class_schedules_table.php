<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            $table->enum('recurrence_type', ['weekly', 'monthly'])->default('weekly')->after('status');
            $table->date('recurrence_start_date')->nullable()->after('recurrence_type');
            $table->date('recurrence_end_date')->nullable()->after('recurrence_start_date');
        });
    }

    public function down(): void
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            $table->dropColumn([
                'recurrence_type',
                'recurrence_start_date',
                'recurrence_end_date',
            ]);
        });
    }
};
