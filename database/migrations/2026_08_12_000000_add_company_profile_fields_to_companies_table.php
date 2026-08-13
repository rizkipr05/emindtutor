<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'nama_syarikat')) {
                $table->string('nama_syarikat')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('companies', 'email_syarikat')) {
                $table->string('email_syarikat')->nullable()->after('nama_syarikat');
            }
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('companies', 'nama_syarikat')) {
                $columns[] = 'nama_syarikat';
            }

            if (Schema::hasColumn('companies', 'email_syarikat')) {
                $columns[] = 'email_syarikat';
            }

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};