<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sallaries', function (Blueprint $table) {
            $table->unsignedBigInteger('attendance_id')->nullable()->after('class_schedule_id')->unique();
        });
    }

    public function down(): void
    {
        Schema::table('sallaries', function (Blueprint $table) {
            $table->dropUnique(['attendance_id']);
            $table->dropColumn('attendance_id');
        });
    }
};
