<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class XenditController extends Controller
{
    public function createInvoice()
    {
        $secretKey = config('services.xendit.secret_key');
        $baseUrl = config('services.xendit.base_url');
        
        // Data yang akan dibayar (Ganti dengan data transaksi Anda)
        $data = [
            'external_id' => 'INV-' . Str::random(10), 
            'amount' => 75000,
            'payer_email' => 'pembeli@contoh.com',
            'description' => 'Pembelian Layanan Premium',
        ];

        try {
            // Menggunakan Laravel HTTP Client untuk Basic Auth (Xendit)
            $response = Http::withBasicAuth($secretKey, '') 
                ->post("$baseUrl/v2/invoices", $data);

            if ($response->successful()) {
                $invoice = $response->json();
                
                // Berhasil! Arahkan pengguna ke URL pembayaran Xendit
                return redirect($invoice['invoice_url']); 
            }

            // Tangani error jika Xendit menolak permintaan (e.g., Key salah atau data kurang)
            return back()->with('error', 'Gagal membuat pembayaran: ' . ($response->json()['message'] ?? 'Error tidak diketahui'));

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan koneksi server.');
        }
    }
}
