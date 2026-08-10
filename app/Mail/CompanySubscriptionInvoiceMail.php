<?php

namespace App\Mail;

use App\Models\CompanyInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompanySubscriptionInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public CompanyInvoice $invoice)
    {
        $this->invoice->loadMissing(['company.user', 'package']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Invoice Langganan - ' . $this->invoice->nomor_invoice);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.company_subscription_invoice');
    }
}