<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('whats_app_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('audience', 20);
            $table->string('title');
            $table->text('message');
            $table->string('frequency', 20)->default('once');
            $table->timestamp('next_run_at');
            $table->timestamp('last_run_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['is_active', 'next_run_at']);
        });

        Schema::create('whats_app_reminder_dispatches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('whats_app_reminder_id')->constrained()->cascadeOnDelete();
            $table->string('recipient_type', 100);
            $table->unsignedBigInteger('recipient_id');
            $table->string('recipient_name')->nullable();
            $table->string('recipient_phone', 100)->nullable();
            $table->text('message');
            $table->timestamp('scheduled_for');
            $table->timestamp('sent_at')->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamps();
            $table->unique(['whats_app_reminder_id', 'recipient_type', 'recipient_id', 'scheduled_for'], 'reminder_dispatch_once');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whats_app_reminder_dispatches');
        Schema::dropIfExists('whats_app_reminders');
    }
};