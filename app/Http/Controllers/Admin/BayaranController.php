<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentInvoice;
use App\Models\StudentPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BayaranController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company?->id;
        abort_unless($companyId, 403);

        // Invoice sudah dibayar
        $paidInvoices = StudentInvoice::with(['student', 'course', 'payment'])
            ->where('status_bayaran', 'paid')
            ->whereHas('student', fn ($query) => $query->where('company_id', $companyId))
            ->get();

        // Menunggu pembayaran
        $upcomingInvoices = StudentInvoice::with(['student', 'course', 'payment'])
            ->where('status_bayaran', 'upcoming')
            ->whereDoesntHave('payment') // belum ada payment
            ->whereHas('student', fn ($query) => $query->where('company_id', $companyId))
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'asc')
            ->get();


        // Sudah ada payment, status masih pending (butuh verifikasi admin)
        $pendingVerification = StudentInvoice::with(['student', 'course', 'payment'])
            ->whereHas('payment', function ($q) {
                $q->where('status', 'pending');
            })
            ->whereHas('student', fn ($query) => $query->where('company_id', $companyId))
            ->get();

        return view('admin.bayaran.index', compact(
            'paidInvoices',
            'upcomingInvoices',
            'pendingVerification'
        ));
    }


    public function verifyForm($id)
    {
        $companyId = Auth::user()->company?->id;
        abort_unless($companyId, 403);

        $payment = StudentPayment::with('studentInvoice.course', 'studentInvoice.student')
            ->whereHas('studentInvoice.student', fn ($query) => $query->where('company_id', $companyId))
            ->findOrFail($id);

        return view('admin.bayaran.verify', compact('payment'));
    }

    public function verify($id)
    {
        $companyId = Auth::user()->company?->id;
        abort_unless($companyId, 403);

        DB::transaction(function () use ($id, $companyId) {
            $payment = StudentPayment::with('studentInvoice.student')
                ->lockForUpdate()
                ->findOrFail($id);

            abort_unless($payment->studentInvoice?->student?->company_id === $companyId, 403);
            abort_unless($payment->status === 'pending', 422, 'Pembayaran ini telah diproses.');

            $payment->update(['status' => 'success']);
            $payment->studentInvoice->update([
                'status_bayaran' => 'paid',
                'payment_verified_at' => now(),
            ]);
        });

        return redirect()->route('admin.students.pending')->with('success', 'Pembayaran berjaya disahkan. Sila assign dan aktifkan kursus pelajar.');
    }
}
