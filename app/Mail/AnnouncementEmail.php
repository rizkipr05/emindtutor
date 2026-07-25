<?php

namespace App\Mail;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AnnouncementEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $announcement;
    public $sender;
    public $recipient;

    public function __construct(Announcement $announcement, $sender, $recipient)
    {
        $this->announcement = $announcement;
        $this->sender = $sender;
        $this->recipient = $recipient;
    }

    public function envelope(): Envelope
    {
        // Menentukan nama pengirim secara dinamis
        $senderName = 'eMindTutor';
        
        // Jika pengirim punya perusahaan, pakai nama perusahaannya
        if ($this->sender->company) {
            $senderName = $this->sender->name . ' via eMindTutor';
        }

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address(config('mail.from.address'), $senderName),
            subject: 'New Announcement: ' . $this->announcement->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.announcement',
        );
    }
}