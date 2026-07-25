<?php

namespace App\Http\Controllers\Compro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Carbon\Carbon;

class CompanyController extends Controller
{

    public function index()
    {
        $today = Carbon::now();

        $companies = Company::with('user')
            ->where('status', true) // hanya company aktif
            ->whereHas('invoices', function($query) use ($today) {
                $query->where('tarikh_terbit', '<=', $today)
                    ->where('tarikh_tamat', '>=', $today);
            })
            ->get();

        return view('compro.company.index', compact('companies'));
    }


    public function show(Company $company)
    {
        // ambil courses yang terkait dengan company
        $courses = $company->courses()->get();

        return view('compro.company.show', compact('company', 'courses'));
    }
}
