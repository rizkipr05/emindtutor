<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pending_company_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('subscription_packages');
            $table->string('name'); $table->string('email')->unique(); $table->string('password');
            $table->string('nama_wakil_syarikat'); $table->string('pic_telefon');
            $table->text('alamat')->nullable(); $table->string('no_telefon');
            $table->string('external_reference')->unique(); $table->string('toyyibpay_bill_code')->nullable();
            $table->string('payment_url')->nullable(); $table->string('status', 20)->default('pending');
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pending_company_registrations'); }
};
