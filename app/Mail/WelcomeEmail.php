<?php

namespace App\Mail;

use App\Models\User;
use App\Models\SystemSetting;
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
        $this->user = $user->loadMissing('company');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->renderSubject(SystemSetting::value('welcome_email_subject', 'Welcome to eMindTutor, {company_name}')),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome', // Pastikan file ini ada di resources/views/emails/welcome.blade.php
            with: [
                'templateBody' => $this->templateBody(),
                'templateVariables' => $this->templateVariables(),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }

    public function templateBody(): string
    {
        return SystemSetting::value('welcome_email_body', "Hi {company_name},\n\nYour company account has been successfully activated.\n\nLogin email: {email}\nLogin URL: {login_url}\n\nPlease log in to complete your company profile and start managing teachers, students, classes, and payments.\n\nBest regards,\nTeam eMindTutor");
    }

    public function templateVariables(): array
    {
        return [
            'company_name' => $this->user->name,
            'representative_name' => $this->user->company?->nama_wakil_syarikat ?? $this->user->name,
            'email' => $this->user->email,
            'login_url' => route('login'),
            'app_name' => config('app.name', 'eMindTutor'),
            'support_email' => SystemSetting::value('support_email', config('mail.from.address')),
        ];
    }

    private function renderSubject(string $subject): string
    {
        foreach ($this->templateVariables() as $key => $value) {
            $subject = str_replace('{' . $key . '}', (string) $value, $subject);
        }

        return $subject;
    }
}
