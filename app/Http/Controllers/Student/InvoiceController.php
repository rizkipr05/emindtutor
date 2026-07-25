<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentInvoice;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function pay($id)
    {
        $invoice = StudentInvoice::with('student.user', 'course')->findOrFail($id);
        
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

    public function handleWebhook(Request $request)
    {
        $data = $request->all();
        $externalId = $data['external_id'];

        if ($data['status'] === 'PAID' || $data['status'] === 'SETTLED') {
            
            // LOGIC 1: Jika ini Invoice Syarikat (Misal prefix EMT-CO-)
            if (str_contains($externalId, 'EMT-CO')) {
                $this->processCompanyPayment($data);
            } 
            
            // LOGIC 2: Jika ini Invoice Student (Misal prefix EMT-STU-)
            elseif (str_contains($externalId, 'EMT-STU')) {
                $this->processStudentPayment($data);
            }
        }

        return response()->json(['message' => 'Webhook Processed']);
    }

    protected function processStudentPayment($data)
    {
        $invoice = StudentInvoice::where('nomor_invoice', $data['external_id'])->first();

        if ($invoice && $invoice->status_bayaran !== 'paid') {
            $invoice->update([
                'status_bayaran' => 'paid',
                'tarikh_bayaran' => now(),
            ]);

            // Aktifkan masa berlaku student (misal tambah 30 hari)
            $student = $invoice->student;
            $student->update([
                'status' => 'active',
                'sub_end' => now()->addDays(30) 
            ]);

            // Kirim email bukti bayar ke student pakai email jagoan hosting tadi
            Mail::to($student->user->email)->send(new StudentReceiptMail($invoice));
        }
    }
}
