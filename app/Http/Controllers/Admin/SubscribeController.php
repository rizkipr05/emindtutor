<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CompanyInvoice;
use App\Models\SubscriptionPackage;

class SubscribeController extends Controller
{
    public function index()
    {
        $packages = SubscriptionPackage::where('status', 'aktif')->get();
        $companyId = Auth::user()?->company?->id;
        $trialUsed = $companyId && CompanyInvoice::where('company_id', $companyId)
            ->whereHas('package', fn ($query) => $query->where('is_trial', true))
            ->exists();

        // $subAktif = SubAktif::find(1);
        // $perStudentActive = $subAktif ? $subAktif->pricing_per_student : false;
        

        return view('admin.pricing.index', compact('packages', 'trialUsed'));
    }
}
