<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentInvoice;
use App\Services\ToyyibPayService;

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
        
        // Kalau sudah pernah buat invoice dan masih aktif, redirect ke URL yang sama aja
        if ($invoice->payment_url && $invoice->status_bayaran == 'upcoming') {
            return redirect($invoice->payment_url);
        }

        try {
            $gateway = $invoice->student->company?->paymentGateway;
            if (! $gateway || ! $gateway->is_active) {
                return back()->with('error', 'Payment Gateway perusahaan belum dikonfigurasi.');
            }
            $bill = app(ToyyibPayService::class)->createBill([
                'name' => 'eMindTutor Course',
                'description' => 'Course ' . ($invoice->course->nama_kos ?? 'Payment'),
                'amount' => $invoice->jumlah,
                'reference' => $invoice->nomor_invoice,
                'return_url' => route('student.payments.index'),
                'callback_url' => route('payment.webhook'),
                'payer_name' => $invoice->student->user->name,
                'payer_email' => $invoice->student->user->email,
                'payer_phone' => $invoice->student->phone,
            ], $gateway);

            $invoice->update([
                'payment_url' => $bill['payment_url'],
                'external_id' => $invoice->nomor_invoice,
            ]);

            return redirect($bill['payment_url']);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghubungi ToyyibPay: ' . $e->getMessage());
        }
    }

}
