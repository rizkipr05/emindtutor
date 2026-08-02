<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function edit()
    {
        $gateway = PaymentGateway::query()->whereNull('company_id')->first();

        return view('super-admin.payment-gateway.edit', compact('gateway'));
    }

    public function update(Request $request)
    {
        $validated = $this->validated($request);
        $gateway = PaymentGateway::firstOrNew(['company_id' => null]);
        $gateway->fill($validated)->save();

        return back()->with('success', 'Payment Gateway Super Admin berhasil disimpan.');
    }

    private function validated(Request $request): array
    {
        return array_merge($request->validate([
            'user_secret_key' => 'required|string|max:255',
            'category_code' => 'required|string|max:100',
            'base_url' => 'required|url|max:255',
            'api_url' => 'required|url|max:255',
            'payment_channel' => 'required|integer|in:0,1,2',
            'expiry_days' => 'required|integer|min:1|max:100',
            'is_active' => 'nullable|boolean',
        ]), ['provider' => 'toyyibpay', 'is_active' => $request->boolean('is_active')]);
    }
}
