<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentInvoice;
use App\Models\StudentPayment;
use Carbon\Carbon;

class BayaranController extends Controller
{
    public function index()
    {
        // Invoice sudah dibayar
        $paidInvoices = StudentInvoice::with(['student', 'course', 'payment'])
            ->where('status_bayaran', 'paid')
            ->get();

        // Menunggu pembayaran
        $upcomingInvoices = StudentInvoice::with(['student', 'course', 'payment'])
            ->where('status_bayaran', 'upcoming')
            ->whereDoesntHave('payment') // belum ada payment
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'asc')
            ->get();


        // Sudah ada payment, status masih pending (butuh verifikasi admin)
        $pendingVerification = StudentInvoice::with(['student', 'course', 'payment'])
            ->whereHas('payment', function ($q) {
                $q->where('status', 'pending');
            })
            ->get();

        return view('admin.bayaran.index', compact(
            'paidInvoices',
            'upcomingInvoices',
            'pendingVerification'
        ));
    }


    public function verifyForm($id)
    {
        $payment = StudentPayment::with('studentInvoice.course', 'studentInvoice.student')
                    ->findOrFail($id);

        return view('admin.bayaran.verify', compact('payment'));
    }

    public function verify($id)
    {
        $payment = StudentPayment::with('studentInvoice')->findOrFail($id);

        // Update status payment
        $payment->update([
            'status' => 'success',
        ]);

        // Update status invoice
        $payment->studentInvoice->update([
            'status_bayaran' => 'paid',
            'tarikh_terbit' => Carbon::today(),
            'tarikh_tamat'  => Carbon::today()->addDays(30),
        ]);

        return redirect()->route('admin.bayaran.index')->with('success', 'Pembayaran berhasil diverifikasi.');
    }
}
