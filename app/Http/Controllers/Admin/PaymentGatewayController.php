<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function edit()
    {
        $company = auth()->user()->company;
        abort_unless($company, 403);
        $gateway = $company->paymentGateway;

        return view('admin.payment-gateway.edit', compact('gateway', 'company'));
    }

    public function update(Request $request)
    {
        $company = auth()->user()->company;
        abort_unless($company, 403);
        $validated = $request->validate([
            'user_secret_key' => 'required|string|max:255',
            'category_code' => 'required|string|max:100',
            'base_url' => 'required|url|max:255',
            'api_url' => 'required|url|max:255',
            'payment_channel' => 'required|integer|in:0,1,2',
            'expiry_days' => 'required|integer|min:1|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $company->paymentGateway()->updateOrCreate([], array_merge($validated, [
            'provider' => 'toyyibpay',
            'is_active' => $request->boolean('is_active'),
        ]));

        return back()->with('success', 'Payment Gateway perusahaan berhasil disimpan.');
    }
}
