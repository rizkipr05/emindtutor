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
        Schema::table('staff_emind', function (Blueprint $table) {
            if (!Schema::hasColumn('staff_emind', 'permissions')) {
                $table->json('permissions')->nullable()->after('updated_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_emind', function (Blueprint $table) {
            if (Schema::hasColumn('staff_emind', 'permissions')) {
                $table->dropColumn('permissions');
            }
        });
    }
};
