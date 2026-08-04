<?php

namespace App\Mail;

use App\Models\CompanyInvoice;
use App\Models\SystemSetting;
use Carbon\Carbon;
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
        $this->invoice = $invoice->loadMissing(['company.user', 'package']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->renderSubject(SystemSetting::value('payment_confirmation_subject', 'Payment Confirmation - {invoice_number}')),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.company_receipt',
            with: [
                'templateBody' => $this->templateBody(),
                'templateVariables' => $this->templateVariables(),
            ],
        );
    }

    public function templateBody(): string
    {
        return SystemSetting::value('payment_confirmation_body', "Hi {company_name},\n\nYour subscription payment has been confirmed.\n\nInvoice: {invoice_number}\nPackage: {package_name}\nAmount: RM {amount}\nPaid date: {paid_date}\nActive until: {expired_date}\n\nYour subscription access is now active.\n\nBest regards,\nTeam eMindTutor");
    }

    public function templateVariables(): array
    {
        return [
            'company_name' => $this->invoice->company?->user?->name ?? $this->invoice->company?->nama_wakil_syarikat ?? 'Company',
            'representative_name' => $this->invoice->company?->nama_wakil_syarikat ?? '-',
            'email' => $this->invoice->company?->user?->email ?? $this->invoice->company?->pic_email ?? '-',
            'invoice_number' => $this->invoice->nomor_invoice ?? $this->invoice->external_id ?? '-',
            'transaction_id' => $this->invoice->external_id ?? '-',
            'package_name' => $this->invoice->package?->nama_pakej ?? 'Langganan',
            'amount' => number_format((float) ($this->invoice->jumlah ?? 0), 2),
            'paid_date' => $this->formatDate($this->invoice->tarikh_terbit),
            'expired_date' => $this->formatDate($this->invoice->tarikh_tamat),
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

    private function formatDate($date): string
    {
        return $date ? Carbon::parse($date)->format('d M Y') : '-';
    }
}
