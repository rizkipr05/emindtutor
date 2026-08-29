<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_invoices', function (Blueprint $table) {
            $table->timestamp('payment_verified_at')->nullable()->after('status_bayaran');
            $table->timestamp('activated_at')->nullable()->after('payment_verified_at');
            $table->unsignedBigInteger('class_schedule_id')->nullable()->after('activated_at');
            $table->index(['student_id', 'course_id', 'activated_at'], 'student_invoice_course_activation_index');
        });
    }

    public function down(): void
    {
        Schema::table('student_invoices', function (Blueprint $table) {
            $table->dropIndex('student_invoice_course_activation_index');
            $table->dropColumn(['payment_verified_at', 'activated_at', 'class_schedule_id']);
        });
    }
};