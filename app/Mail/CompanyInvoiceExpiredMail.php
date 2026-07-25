<?php

namespace App\Mail;

use App\Models\CompanyInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompanyInvoiceExpiredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $inv;

    public function __construct(CompanyInvoice $inv)
    {
        $this->inv = $inv;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pemberitahuan Langganan Tamat - ' . $this->inv->company->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.company_expired_invoice',
        );
    }
}