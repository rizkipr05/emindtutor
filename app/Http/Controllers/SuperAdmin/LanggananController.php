<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPackage;

class LanggananController extends Controller
{
    public function index()
    {
        $packages = SubscriptionPackage::with('package')->get();
        
        return view('super-admin.langganan.index', compact('packages'));
    }
}
