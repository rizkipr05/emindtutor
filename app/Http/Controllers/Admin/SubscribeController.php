<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPackage;

class SubscribeController extends Controller
{
    public function index()
    {
        $packages = SubscriptionPackage::where('status', 'aktif')->get();

        // $subAktif = SubAktif::find(1);
        // $perStudentActive = $subAktif ? $subAktif->pricing_per_student : false;
        

        return view('admin.pricing.index', compact('packages'));
    }
}
