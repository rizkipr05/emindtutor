<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// HAPUS "implements ShouldQueue" di sini supaya email terkirim langsung
class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to eMindTutor!', // Subjek email
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome', // Pastikan file ini ada di resources/views/emails/welcome.blade.php
        );
    }

    public function attachments(): array
    {
        return [];
    }
}