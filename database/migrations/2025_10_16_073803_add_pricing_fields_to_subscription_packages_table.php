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
        Schema::table('subscription_packages', function (Blueprint $table) {
            if (!Schema::hasColumn('subscription_packages', 'pricing_per_student')) {
                $table->boolean('pricing_per_student')->default(false)->after('status');
            }

            if (!Schema::hasColumn('subscription_packages', 'harga_per_pelajar')) {
                $table->decimal('harga_per_pelajar')->nullable()->after('pricing_per_student');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_packages', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('subscription_packages', 'pricing_per_student')) {
                $columnsToDrop[] = 'pricing_per_student';
            }

            if (Schema::hasColumn('subscription_packages', 'harga_per_pelajar')) {
                $columnsToDrop[] = 'harga_per_pelajar';
            }

            if ($columnsToDrop !== []) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
