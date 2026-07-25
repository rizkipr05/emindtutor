<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $senderName;
    public $type; // 'subscription' or 'tuition'

    public function __construct($payment, $senderName, $type)
    {
        $this->payment = $payment;
        $this->senderName = $senderName;
        $this->type = $type;
    }

    public function envelope(): Envelope
    {
        $subject = ($this->type === 'subscription') 
                    ? "Invoice - Subscription eMindTutor" 
                    : "Payment Receipt - " . $this->senderName;

        return new Envelope(
            from: new Address(config('mail.from.address'), $this->senderName),
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment_notification',
        );
    }
}
