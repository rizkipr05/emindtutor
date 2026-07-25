<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1️⃣ Company Bank Details
        if (!Schema::hasTable('company_bank_details')) {
            Schema::create('company_bank_details', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
                $table->string('nama_bank');
                $table->string('nombor_akaun_bank');
                $table->timestamps();
            });
        }

        // 2️⃣ WhatsApp Settings
        if (!Schema::hasTable('whatsapp_settings')) {
            Schema::create('whatsapp_settings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
                $table->string('instance_id');
                $table->text('access_token');
                $table->text('teks_message')->nullable();
                $table->timestamps();
            });
        }

        // 3️⃣ Payment Settings
        if (!Schema::hasTable('payment_settings')) {
            Schema::create('payment_settings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
                $table->string('api_key');
                $table->string('secret_key');
                $table->string('payment_gateway', 100);
                $table->timestamps();
            });
        }

        // 4️⃣ Announcements
        if (!Schema::hasTable('announcements')) {
            Schema::create('announcements', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('message');
                $table->enum('target_audience', ['all', 'companies', 'teachers', 'students']);
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
                $table->timestamps();
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('payment_settings');
        Schema::dropIfExists('whatsapp_settings');
        Schema::dropIfExists('company_bank_details');
    }
};
