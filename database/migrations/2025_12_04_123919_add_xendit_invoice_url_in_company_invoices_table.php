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
        Schema::table('company_invoices', function (Blueprint $table) {
            // 1. Tambah kolom baru untuk menyimpan URL pembayaran dari Xendit
            if (!Schema::hasColumn('company_invoices', 'xendit_invoice_url')) {
                $table->string('xendit_invoice_url', 255)->nullable()->after('jumlah');
            }
            
            // 2. Perluas/Ubah kolom status_bayaran dari ENUM ke VARCHAR(20) agar lebih fleksibel
            // Ini mengatasi masalah "Data truncated" karena nilai status Xendit lebih panjang
            $table->string('status_bayaran', 20)->change();

            // 3. Hapus kolom snap_token yang tidak lagi digunakan (Midtrans)
            if (Schema::hasColumn('company_invoices', 'snap_token')) {
                $table->dropColumn('snap_token');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_invoices', function (Blueprint $table) {
            // Hapus kolom Xendit
            if (Schema::hasColumn('company_invoices', 'xendit_invoice_url')) {
                $table->dropColumn('xendit_invoice_url');
            }
            
            // Kembalikan status_bayaran ke ENUM atau VARCHAR(pendek) yang Anda gunakan sebelumnya
            // Catatan: Jika sebelumnya ENUM, Anda harus mendefinisikan ulang ENUM di sini
            // Contoh jika sebelumnya ENUM('pnd','upc','paid'):
            // $table->enum('status_bayaran', ['pnd','upc','paid'])->change();
            // Kami kembalikan ke VARCHAR(5) sebagai contoh umum rollback
            $table->string('status_bayaran', 5)->change();

            // Tambahkan kembali kolom snap_token (jika diperlukan untuk rollback)
            if (!Schema::hasColumn('company_invoices', 'snap_token')) {
                $table->string('snap_token', 255)->nullable()->after('status_bayaran');
            }
        });
    }
};
