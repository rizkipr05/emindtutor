<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_schedule_exceptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_schedule_id');
            $table->date('exception_date');
            $table->enum('action', ['cancel'])->default('cancel');
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->unique(['class_schedule_id', 'exception_date'], 'class_exception_unique');
            $table->index(['class_schedule_id', 'exception_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_schedule_exceptions');
    }
};
