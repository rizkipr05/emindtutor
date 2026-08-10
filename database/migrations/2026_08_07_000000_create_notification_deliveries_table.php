<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('notification_type', 100);
            $table->string('notifiable_type', 100);
            $table->unsignedBigInteger('notifiable_id');
            $table->string('channel', 30);
            $table->string('recipient', 100)->nullable();
            $table->string('status', 20)->default('sent');
            $table->text('message')->nullable();
            $table->json('response')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->unique(
                ['notification_type', 'notifiable_type', 'notifiable_id', 'channel'],
                'notification_delivery_once'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_deliveries');
    }
};