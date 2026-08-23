<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whats_app_reminder_templates', function (Blueprint $table) {
            $table->string('content_type', 20)->default('text')->after('message');
        });
    }

    public function down(): void
    {
        Schema::table('whats_app_reminder_templates', function (Blueprint $table) {
            $table->dropColumn('content_type');
        });
    }
};
