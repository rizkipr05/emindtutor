<?php

namespace App\Http\Controllers\Compro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Company;
use App\Models\CompanyInvoice;
use App\Models\SubscriptionPackage;
use App\Models\Review;
use App\Models\SubAktif;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // Hitung total teacher
        $totalTeachers = Teacher::count();

        // Hitung total company
        $totalCompanies = Company::count();

        // Ambil jumlah subscribe tiap package dari CompanyInvoice
        $packageStats = CompanyInvoice::select('package_id')
            ->selectRaw('COUNT(*) as total')
            ->where('status_bayaran', 'paid') // hanya yang sudah bayar
            ->groupBy('package_id')
            ->orderByDesc('total')
            ->with('package')
            ->get();

        // Ambil paket paling populer (jika ada)
        $mostPopularPackage = $packageStats->first();

        // // Ambil semua paket dengan jumlah subscribe masing-masing
        // $allPackages = SubscriptionPackage::withCount([
        //     'companyInvoices as total_subscribes' => function ($q) {
        //         $q->where('status_bayaran', 'paid');
        //     }
        // ])->get();

        // Ambil status toggle pricing per student
        $subAktif = SubAktif::find(1);
        $perStudentActive = $subAktif ? $subAktif->pricing_per_student : false;

        // Ambil semua paket beserta jumlah subscribe yang sudah dibayar
        $allPackages = SubscriptionPackage::withCount([
            'companyInvoices as total_subscribes' => function ($q) {
                $q->where('status_bayaran', 'paid');
            }
        ])->get();

        // Filter sesuai toggle global
        $filteredPackages = $allPackages->filter(function($package) use ($perStudentActive) {
            return $package->pricing_per_student == $perStudentActive;
        });


        // Ambil 5 review terbaru
        $latestReviews = Review::with(['student.user', 'teacher.user', 'course'])
            ->latest()
            ->take(6)
            ->get();


        $today = Carbon::today();

        // 3 company terbaru yang masih memiliki subscription aktif
        $activeCompanies = Company::whereHas('invoices', function ($q) use ($today) {
                $q->where('status_bayaran', 'paid')
                ->where('tarikh_terbit', '<=', $today)
                ->where('tarikh_tamat', '>=', $today);
            })
            ->with(['invoices' => function($q) use ($today) {
                $q->where('status_bayaran', 'paid')
                ->where('tarikh_terbit', '<=', $today)
                ->where('tarikh_tamat', '>=', $today);
            }])
            ->take(4)
            ->get();

        return view('compro.index', compact(
            'totalTeachers',
            'totalCompanies',
            'packageStats',
            'mostPopularPackage',
            'allPackages',
            'latestReviews',
            'activeCompanies',
            'perStudentActive',
            'filteredPackages'
        ));
    }
}
