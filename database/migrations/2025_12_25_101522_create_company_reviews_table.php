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
        if (Schema::hasTable('company_reviews')) {
            return;
        }

        Schema::create('company_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->tinyInteger('rating')->default(0); // 1-5 bintang
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_reviews');
    }
};
