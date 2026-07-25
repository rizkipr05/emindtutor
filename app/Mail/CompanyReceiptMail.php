<?php

namespace App\Mail;

use App\Models\CompanyInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompanyReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;

    public function __construct(CompanyInvoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bukti Pembayaran Langganan - ' . $this->invoice->external_id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.company_receipt',
        );
    }
}