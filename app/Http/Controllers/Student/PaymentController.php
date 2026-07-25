<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentInvoice;
use App\Models\StudentPayment;
use App\Models\CompanyBankDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class PaymentController extends Controller
{
    //menampilkan daftar invoice yg belum dibayar
    public function index()
    {
        $upcomingInvoices = StudentInvoice::where('student_id', Auth::user()->students->id)->with('payment')
            ->where('status_bayaran', 'upcoming')
            ->get();

        $paidInvoices = StudentInvoice::where('student_id', Auth::user()->students->id)->with('payment')
                        ->where('status_bayaran', 'paid')
                        ->orderBy('created_at', 'desc')
                        ->get();

        // dd($upcomingInvoices);
        return view('student.payments.index', compact('upcomingInvoices', 'paidInvoices'));
    }

    public function create($invoiceId)
    {
        $student = Auth::user()->students;
        abort_unless($student, 403);

        $invoice = StudentInvoice::query()
            ->whereKey($invoiceId)
            ->where('student_id', $student->id)
            ->where('status_bayaran', 'upcoming')
            ->firstOrFail();

        if ($invoice->payment()->whereIn('status', ['pending', 'success'])->exists()) {
            return redirect()
                ->route('student.payments.index')
                ->with('error', 'Pembayaran untuk invoice ini sedang diproses atau sudah selesai.');
        }

        $bankDetails = CompanyBankDetail::where('company_id', $student->company_id)
            ->latest()
            ->get();

        return view('student.payments.create', compact('invoice', 'bankDetails'));
    }

    public function createe($invoiceId)
    {
        // Ambil invoice berserta data company dan bank_details (Eager Loading)
        $invoice = StudentInvoice::with(['company.bankDetails'])->where('id', $invoiceId)->firstOrFail();

        // Ambil senarai bank terus dari company yang mengeluarkan invoice tersebut
        $bankDetails = $invoice->company->bankDetails;

        return view('student.payments.create', compact('invoice', 'bankDetails'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_invoice_id' => 'required|integer',
            'dibayar_pada' => 'required|date',
            'bukti_bayar' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $student = Auth::user()->students;
        abort_unless($student, 403);

        $payment = DB::transaction(function () use ($request, $validated, $student) {
            $invoice = StudentInvoice::query()
                ->whereKey($validated['student_invoice_id'])
                ->where('student_id', $student->id)
                ->where('status_bayaran', 'upcoming')
                ->lockForUpdate()
                ->firstOrFail();

            if ($invoice->payment()->whereIn('status', ['pending', 'success'])->exists()) {
                throw ValidationException::withMessages([
                    'student_invoice_id' => 'Pembayaran untuk invoice ini sedang diproses atau sudah selesai.',
                ]);
            }

            return StudentPayment::create([
                'student_invoice_id' => $invoice->id,
                'dibayar_pada' => $validated['dibayar_pada'],
                'bukti_bayar' => $request->file('bukti_bayar')->store('bukti_bayar', 'public'),
                'status' => 'pending',
            ]);
        });

        return redirect()->route('student.payments.index')->with('success', 'Payment berhasil ditambahkan!');
    }

}
