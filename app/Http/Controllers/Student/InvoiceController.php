<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentInvoice;
use App\Models\StudentPayment;
use App\Services\ToyyibPayService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function pay($id)
    {
        $student = auth()->user()->students;
        abort_unless($student, 403);

        $invoice = StudentInvoice::with('student.user', 'course')
            ->whereKey($id)
            ->where('student_id', $student->id)
            ->where('status_bayaran', 'upcoming')
            ->firstOrFail();
        
        // Simpan Bill Code untuk invoice lama yang dibuat sebelum kolom bill code tersedia.
        if (! $invoice->toyyibpay_bill_code && $invoice->payment_url) {
            $invoice->update([
                'toyyibpay_bill_code' => $this->billCodeFromUrl($invoice->payment_url),
            ]);
            $invoice->refresh();
        }

        // Kalau sudah pernah buat bill dan masih aktif, redirect ke URL yang sama aja.
        if ($invoice->payment_url && $invoice->status_bayaran == 'upcoming') {
            return redirect($invoice->payment_url);
        }

        try {
            $gateway = $invoice->student->company?->effectivePaymentGateway();
            if (! $gateway) {
                return back()->with('error', 'Payment Gateway perusahaan belum dikonfigurasi.');
            }
            $bill = app(ToyyibPayService::class)->createBill([
                'name' => 'eMindTutor Course',
                'description' => 'Course ' . ($invoice->course->nama_kos ?? 'Payment'),
                'amount' => $invoice->jumlah,
                'reference' => $invoice->nomor_invoice,
                'return_url' => route('student.payment.return', $invoice),
                'callback_url' => route('payment.webhook'),
                'payer_name' => $invoice->student->user->name,
                'payer_email' => $invoice->student->user->email,
                'payer_phone' => $invoice->student->phone,
            ], $gateway);

            $invoice->update([
                'payment_url' => $bill['payment_url'],
                'external_id' => $invoice->nomor_invoice,
                'toyyibpay_bill_code' => $bill['bill_code'],
            ]);

            return redirect($bill['payment_url']);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghubungi ToyyibPay: ' . $e->getMessage());
        }
    }

    public function paymentReturn(StudentInvoice $invoice)
    {
        $student = auth()->user()->students;
        abort_unless($student && $invoice->student_id === $student->id, 403);

        $billCode = $invoice->toyyibpay_bill_code ?: $this->billCodeFromUrl($invoice->payment_url);

        if (! $invoice->toyyibpay_bill_code && $billCode) {
            $invoice->update(['toyyibpay_bill_code' => $billCode]);
        }

        if ($invoice->status_bayaran !== 'paid' && $billCode) {
            try {
                $gateway = $invoice->student->company?->effectivePaymentGateway();

                if ($gateway && app(ToyyibPayService::class)->hasSuccessfulTransaction($billCode, $gateway)) {
                    $this->finalizeToyyibPayPayment($invoice, 'return-' . $billCode, Carbon::now());
                }
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        return redirect()->route('student.payments.index')->with(
            $invoice->fresh()->status_bayaran === 'paid' ? 'success' : 'error',
            $invoice->fresh()->status_bayaran === 'paid'
                ? 'Pembayaran berjaya diterima. Sila tunggu admin assign dan aktifkan kursus.'
                : 'Pembayaran belum dapat disahkan. Sila tunggu sebentar atau hubungi admin.'
        );
    }

    private function finalizeToyyibPayPayment(StudentInvoice $invoice, string $reference, Carbon $paidAt): void
    {
        DB::transaction(function () use ($invoice, $reference, $paidAt) {
            $invoice = StudentInvoice::lockForUpdate()->findOrFail($invoice->id);

            if ($invoice->status_bayaran === 'paid') {
                return;
            }

            $payment = StudentPayment::where('student_invoice_id', $invoice->id)->lockForUpdate()->first();

            if ($payment && $payment->status !== 'success') {
                throw new \RuntimeException('Pembayaran manual untuk invoice ini sedang menunggu pengesahan admin.');
            }

            StudentPayment::updateOrCreate(
                ['student_invoice_id' => $invoice->id],
                [
                    'status' => 'success',
                    'provider' => 'toyyibpay',
                    'gateway_refno' => $reference,
                    'dibayar_pada' => $paidAt,
                    'bukti_bayar' => null,
                ]
            );

            $invoice->update([
                'status_bayaran' => 'paid',
                'payment_verified_at' => $paidAt,
            ]);
        });
    }

    private function billCodeFromUrl(?string $paymentUrl): ?string
    {
        if (! $paymentUrl) {
            return null;
        }

        $path = trim((string) parse_url($paymentUrl, PHP_URL_PATH), '/');

        return $path !== '' ? basename($path) : null;
    }

}
