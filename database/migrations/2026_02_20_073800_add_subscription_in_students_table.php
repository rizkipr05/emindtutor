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
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'status')) {
                $table->enum('status', ['active', 'expired', 'pending'])->default('pending')->after('is_approved');
            }

            if (!Schema::hasColumn('students', 'sub_start')) {
                $table->date('sub_start')->nullable()->after('status');
            }

            if (!Schema::hasColumn('students', 'sub_end')) {
                $table->date('sub_end')->nullable()->after('sub_start');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $columns = array_filter(['status', 'sub_start', 'sub_end'], function ($column) {
                return Schema::hasColumn('students', $column);
            });

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
