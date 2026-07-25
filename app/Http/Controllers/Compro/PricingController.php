<?php

namespace App\Http\Controllers\Compro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPackage;

class PricingController extends Controller
{
    public function index()
    {
        $packages = SubscriptionPackage::where('status', 'aktif')->get();
        return view('compro.pricing.index', compact('packages'));
    }
}
