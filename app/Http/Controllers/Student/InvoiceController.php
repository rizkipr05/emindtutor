<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentInvoice;

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

        $xendit = new \App\Services\XenditService();
        
        try {
            $response = $xendit->createInvoice($invoice);
            
            // Simpan URL dari Xendit ke database
            $invoice->update([
                'payment_url' => $response['invoice_url'],
                'external_id' => $invoice->nomor_invoice // Pastikan nomor_invoice unik
            ]);

            return redirect($response['invoice_url']);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghubungi Xendit: ' . $e->getMessage());
        }
    }

}
