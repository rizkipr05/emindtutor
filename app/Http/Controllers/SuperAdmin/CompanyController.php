<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Company;
use App\Models\User;
use App\Models\CompanyInvoice;
use App\Models\StudentInvoice;

class CompanyController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $companies = Company::with(['user', 'invoices' => function ($query) {
            $query->orderBy('tarikh_tamat', 'desc'); // urutkan, biar yang terbaru diambil dulu
        }])->get();

        // Tambahkan properti helper "activeInvoice" biar gampang di Blade
        foreach ($companies as $company) {
            $company->activeInvoice = $company->invoices
                ->where('tarikh_terbit', '<=', $today)
                ->where('tarikh_tamat', '>=', $today)
                ->sortByDesc('tarikh_tamat')
                ->first();
        }

        return view('super-admin.syarikat.index', compact('companies'));
    }

    public function create()
    {
        return view('super-admin.syarikat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nama_wakil_syarikat' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'no_telefon' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        $lastCompany = Company::orderBy('id', 'desc')->first();

        if ($lastCompany && $lastCompany->nomor_pendaftar_syarikat) {
            // Ambil nomor terakhir → increment
            $lastNumber = (int) str_replace('EMT-', '', $lastCompany->nomor_pendaftar_syarikat);
            $newNumber = $lastNumber + 1;
        } else {
            // Jika belum ada data sama sekali
            $newNumber = 1;
        }

        // Format final
        $nomorPendaftar = 'EMT-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);
        // dd($nomorPendaftar);

        Company::create([
            'user_id' => $user->id,
            'nama_wakil_syarikat' => $request->nama_wakil_syarikat,
            'alamat' => $request->alamat,
            'no_telefon' => $request->no_telefon,
            'nombor_pendaftaran_syarikat' => $nomorPendaftar,
        ]);

        return redirect()->route('companies.index')->with('success', 'Syarikat berjaya ditambah.');
    }


    public function show(Company $company)
    {
        $company->load('user');

        // ambil semua student_id dari company ini
        $studentIds = $company->students->pluck('id');

        // hitung total invoice yang paid
        $totalPaidInvoices = StudentInvoice::whereIn('student_id', $studentIds)
            ->where('status_bayaran', 'paid')
            ->count();

        // hitung total pendapatan dari invoice paid
        $totalRevenue = StudentInvoice::whereIn('student_id', $studentIds)
            ->where('status_bayaran', 'paid')
            ->sum('jumlah'); // pastikan kolom jumlah ada di tabel student_invoices

        return view('super-admin.syarikat.show', compact('company', 'totalPaidInvoices', 'totalRevenue'));
    }

    public function edit(Company $company)
    {
        $company->load('user');
        return view('super-admin.syarikat.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nama_wakil_syarikat' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $company->user_id,
            'no_telefon' => 'required|string|max:20',
        ]);

        // Update user
        $company->user->update([
            'name' => $request->nama,
            'email' => $request->email,
        ]);

        // Update company
        $company->update([
            'nama_wakil_syarikat' => $request->nama_wakil_syarikat,
            'alamat' => $request->alamat,
            'no_telefon' => $request->no_telefon,
            'status' => $request->status ?? true,
        ]);

        return redirect()->route('companies.index')->with('success', 'Syarikat berjaya dikemas kini.');
    }

    public function destroy(Company $company)
    {
        // hapus user juga, akan cascade ke company
        $company->user->delete();
        return redirect()->route('companies.index')->with('success', 'Syarikat berjaya dipadam.');
    }

    public function toggleStatus($id)
    {
        $company = Company::findOrFail($id);
        $company->status = !$company->status;
        $company->save();

        return redirect()->back()->with('success', 'Status syarikat berjaya dikemaskini.');
    }

    public function loginAs($id)
    {
        $company = Company::findOrFail($id);

        // Simulasi login sebagai user perusahaan
        session(['acting_as_company' => $company->user->id]);

        return redirect('/dashboard')->with('success', 'Anda kini log masuk sebagai admin syarikat: ' . $company->user->name);
    }
}