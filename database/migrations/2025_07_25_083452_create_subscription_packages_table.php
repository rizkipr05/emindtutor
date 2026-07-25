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
        Schema::create('subscription_packages', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_trial')->default(false); // apakah pakej ini adalah percubaan
            $table->string('nama_pakej');
            $table->integer('duration_days'); // jumlah hari langganan
            $table->integer('harga'); 
            $table->integer('maks_pelajar');
            $table->integer('maks_guru');
            $table->integer('maks_kelas');
            $table->text('deskripsi')->nullable(); // deskripsi pakej
            $table->enum('status', ['aktif', 'tamat'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_packages');
    }
};
