<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Company;
use App\Models\CompanyInvoice;
use App\Models\SubscriptionPackage;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $metrics = [
            'totalCompanies' => Company::count(),
            'activeCompanies' => Company::where('status', true)->count(),
            'totalPackages' => SubscriptionPackage::count(),
            'totalInvoices' => CompanyInvoice::count(),
            'paidInvoices' => CompanyInvoice::where('status_bayaran', 'paid')->count(),
            'totalUsers' => User::count(),
            'totalAnnouncements' => Announcement::count(),
        ];

        $recentCompanies = Company::with('user')
            ->latest()
            ->take(5)
            ->get();

        $recentInvoices = CompanyInvoice::with(['company.user', 'package'])
            ->latest()
            ->take(5)
            ->get();

        return view('super-admin.dashboard', compact('metrics', 'recentCompanies', 'recentInvoices'));
    }
}
