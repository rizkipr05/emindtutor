<?php

namespace App\Services;

use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;

class XenditService
{
    public function __construct()
    {
        // Ambil Secret Key dari file .env
        Configuration::setApiKey(config('services.xendit.secret_key'));
    }

    public function createInvoice($studentInvoice)
    {
        $apiInstance = new InvoiceApi();

        // Data yang dikirim ke Xendit
        $createInvoiceRequest = new CreateInvoiceRequest([
            'external_id' => $studentInvoice->nomor_invoice,
            'amount' => (float) $studentInvoice->jumlah,
            'payer_email' => $studentInvoice->student->user->email,
            'description' => "Pembayaran Kursus: " . $studentInvoice->course->nama_kursus,
            'invoice_duration' => 86400, // Aktif selama 24 jam
            'success_redirect_url' => route('student.invoices.index'),
        ]);

        try {
            $result = $apiInstance->createInvoice($createInvoiceRequest);
            return $result;
        } catch (\Exception $e) {
            throw new \Exception("Xendit Error: " . $e->getMessage());
        }
    }
}