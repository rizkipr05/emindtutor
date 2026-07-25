<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_schedule_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_schedule_id');
            $table->unsignedBigInteger('class_schedule_day_id')->nullable();
            $table->date('session_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['scheduled', 'cancelled', 'completed'])->default('scheduled');
            $table->string('cancellation_reason')->nullable();
            $table->timestamps();

            $table->unique(['class_schedule_id', 'session_date', 'start_time'], 'class_session_unique');
            $table->index(['class_schedule_id', 'session_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_schedule_sessions');
    }
};
