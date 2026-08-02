<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->unique()->constrained()->cascadeOnDelete();
            $table->string('provider', 30)->default('toyyibpay');
            $table->text('user_secret_key');
            $table->string('category_code');
            $table->string('base_url')->default('https://toyyibpay.com');
            $table->string('api_url')->default('https://toyyibpay.com');
            $table->unsignedTinyInteger('payment_channel')->default(0);
            $table->unsignedTinyInteger('expiry_days')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
