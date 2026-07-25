<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanyBankDetail;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
/**
     * Memaparkan senarai akaun bank syarikat. (kayanya ga kepake, tapi buat sementara aja)
     */
    public function index()
    {
        $bankDetails = CompanyBankDetail::where('company_id', Auth::user()->company->id)
            ->latest()
            ->get();

        return view('admin.bank_details.index', compact('bankDetails'));
    }

    /**
     * Menunjukkan borang tambah akaun bank.
     */
    public function create()
    {
        $company = Auth::user()->company;

        return view('admin.bank_details.create', compact('company'));
    }

    /**
     * Menyimpan data akaun bank baharu ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:255',
            'nombor_akaun_bank' => 'required|string|max:50',
        ]);

        CompanyBankDetail::create([
            'company_id' => Auth::user()->company->id,
            'nama_bank' => $request->nama_bank,
            'nombor_akaun_bank' => $request->nombor_akaun_bank,
        ]);

        return redirect()->route('admin.bank_details.index')
            ->with('success', 'Maklumat bank berjaya ditambah.');
    }

    /**
     * Menunjukkan maklumat terperinci akaun bank (pilihan).
     */
    public function show(CompanyBankDetail $bankDetail)
    {
        // Pastikan hanya pemilik syarikat boleh melihat
        if ($bankDetail->company_id !== Auth::user()->company->id) {
            abort(403);
        }

        return view('admin.bank_details.show', compact('bankDetail'));
    }

    /**
     * Menunjukkan borang edit akaun bank.
     */
    public function edit(CompanyBankDetail $bankDetail)
    {
        // Pastikan akaun bank milik syarikat user tersebut
        if ($bankDetail->company_id !== Auth::user()->company->id) {
            abort(403);
        }

        return view('admin.bank_details.edit', compact('bankDetail'));
    }

    /**
     * Mengemaskini maklumat akaun bank.
     */
    public function update(Request $request, CompanyBankDetail $bankDetail)
    {
        if ($bankDetail->company_id !== Auth::user()->company->id) {
            abort(403);
        }

        $request->validate([
            'nama_bank' => 'required|string|max:255',
            'nombor_akaun_bank' => 'required|string|max:50',
        ]);

        $bankDetail->update([
            'nama_bank' => $request->nama_bank,
            'nombor_akaun_bank' => $request->nombor_akaun_bank,
        ]);

        return redirect()->route('admin.bank_details.index')
            ->with('success', 'Maklumat bank berjaya dikemaskini.');
    }

    /**
     * Memadam maklumat akaun bank.
     */
    public function destroy(CompanyBankDetail $bankDetail)
    {
        if ($bankDetail->company_id !== Auth::user()->company->id) {
            abort(403);
        }

        $bankDetail->delete();

        return redirect()->route('admin.bank_details.index')
            ->with('success', 'Maklumat bank berjaya dipadam.');
    }
}
