<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_packages', function (Blueprint $table) {
            // ubah tipe data harga menjadi decimal (10 digit total, 2 digit di belakang koma)
            $table->decimal('harga', 10, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('subscription_packages', function (Blueprint $table) {
            // jika rollback, kembalikan jadi integer
            $table->integer('harga')->change();
        });
    }
};
