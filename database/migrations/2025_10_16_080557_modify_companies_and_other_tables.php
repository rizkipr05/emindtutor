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
        // 🏢 Tambah field ke tabel companies
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'logo_syarikat')) {
                $table->string('logo_syarikat')->nullable()->after('id');
            }

            if (!Schema::hasColumn('companies', 'nombor_pendaftaran_syarikat')) {
                $table->string('nombor_pendaftaran_syarikat')->nullable()->after('logo_syarikat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('companies', 'logo_syarikat')) {
                $columnsToDrop[] = 'logo_syarikat';
            }

            if (Schema::hasColumn('companies', 'nombor_pendaftaran_syarikat')) {
                $columnsToDrop[] = 'nombor_pendaftaran_syarikat';
            }

            if ($columnsToDrop !== []) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
