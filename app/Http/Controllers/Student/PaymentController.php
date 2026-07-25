<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentInvoice;
use App\Models\StudentPayment;
use App\Models\CompanyBankDetail;
use Illuminate\Support\Facades\Auth;


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
        $invoice = StudentInvoice::where('id', $invoiceId)->firstOrFail();
        // dd(Auth::user()->students->company_id);
        $bankDetails = CompanyBankDetail::where('company_id', Auth::user()->students->company_id)
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
        // dd($request->bukti_bayar);
        // Validasi input
        $validated = $request->validate([
            'student_invoice_id' => 'required|exists:student_invoices,id',
            'dibayar_pada' => 'nullable|date',
            'bukti_bayar' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // dd("hi");

        // Handle upload bukti bayar (jika ada)
        if ($request->hasFile('bukti_bayar')) {
            $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
            $validated['bukti_bayar'] = $path;
        }
                $classPhotoPath = null;
        if ($request->hasFile('class_photo')) {
            $classPhotoPath = $request->file('class_photo')->store('class_photos', 'public');
        }

        // Tambahkan status default pending
        $validated['status'] = 'pending';
        // dd($validated);

        // Simpan data
        $payment = StudentPayment::create($validated);
        // dd($payment);
        return redirect()->route('student.payments.index')->with('success', 'Payment berhasil ditambahkan!');
    }

}
