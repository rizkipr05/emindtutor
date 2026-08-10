<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('nama_kos_en')->nullable()->after('nama_kos');
            $table->string('nama_kos_my')->nullable()->after('nama_kos_en');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['nama_kos_en', 'nama_kos_my']);
        });
    }
};