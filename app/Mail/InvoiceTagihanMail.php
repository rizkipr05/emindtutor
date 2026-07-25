<?php

namespace App\Mail;

use App\Models\StudentInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceTagihanMail extends Mailable
{
    use Queueable, SerializesModels;

    // Kita definisikan variabel public agar bisa dibaca oleh file Blade email
    public $invoice;

    public function __construct(StudentInvoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tagihan Pembayaran: ' . $this->invoice->nomor_invoice,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice_tagihan', // Ini nama file blade-nya nanti
        );
    }
}