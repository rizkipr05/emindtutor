<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyBankDetail;
use App\Models\CompanyInvoice;
use App\Models\PaymentGateway;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    private function resolveCompany(): ?Company
    {
        $user = Auth::user();
        return Company::where('user_id', $user->id)->first()
            ?? Staff::with('company')->where('user_id', $user->id)->first()?->company;
    }

    private function buildSubscriptionData(Company $company): array
    {
        $today = now()->toDateString();
        $activeSubscription = CompanyInvoice::where('company_id', $company->id)
            ->where('status', 'active')
            ->where('status_bayaran', 'paid')
            ->whereDate('tarikh_terbit', '<=', $today)
            ->whereDate('tarikh_tamat', '>=', $today)
            ->latest()->with('package')->first();

        $remainingDays = 0;
        $expiryDate    = null;
        $totalDays     = 0;
        $startDate     = null;

        if ($activeSubscription) {
            $todayCarbon   = Carbon::today();
            $start         = Carbon::parse($activeSubscription->tarikh_terbit);
            $end           = Carbon::parse($activeSubscription->tarikh_tamat);
            $remainingDays = max(0, $todayCarbon->diffInDays($end) + 1);
            $expiryDate    = $end;
            $startDate     = $start;
            $totalDays     = max(1, $start->diffInDays($end) + 1);
        }

        $subscriptionHistory = CompanyInvoice::where('company_id', $company->id)
            ->where('status_bayaran', 'paid')
            ->latest()->with('package')->take(10)->get();

        return compact('activeSubscription', 'remainingDays', 'expiryDate', 'totalDays', 'startDate', 'subscriptionHistory');
    }

    private function navSections(): array
    {
        return [
            'overview'        => ['label' => __('admin/settings.overview.label'), 'caption' => __('admin/settings.overview.caption'), 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5'],
            'subscription'    => ['label' => __('admin/settings.subscription.label'), 'caption' => __('admin/settings.subscription.caption'), 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
            'bank_details'    => ['label' => __('admin/settings.bank_details.label'), 'caption' => __('admin/settings.bank_details.caption'), 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v11a2 2 0 002 2z'],
            'payment_gateway' => ['label' => __('admin/settings.payment_gateway.label'), 'caption' => __('admin/settings.payment_gateway.caption'), 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
            'security'        => ['label' => __('admin/settings.security.label'), 'caption' => __('admin/settings.security.caption'), 'icon' => 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z'],
        ];
    }

    public function index(Request $request, ?string $section = null)
    {
        $section = $this->normalizeSection($section);
        $user    = Auth::user();
        $company = $this->resolveCompany();

        if (!$company) {
            return redirect()->route('a.dashboard.index')
                ->with('error', __('admin/settings.company_not_found'));
        }

        $navSections       = $this->navSections();
        $currentSectionKey = $section;
        $gateway           = $company->paymentGateway;
        $bankDetails       = CompanyBankDetail::where('company_id', $company->id)->get();

        $subscriptionData = $this->buildSubscriptionData($company);

        return view('admin.settings.index', array_merge(
            compact('company', 'user', 'navSections', 'currentSectionKey', 'gateway', 'bankDetails'),
            $subscriptionData
        ));
    }

    public function update(Request $request, string $section)
    {
        $section = $this->normalizeSection($section);
        $user    = Auth::user();
        $company = $this->resolveCompany();

        if (!$company) abort(403);

        switch ($section) {

            case 'overview':
                $validated = $request->validate([
                    'nama_wakil_syarikat'          => 'required|string|max:255',
                    'pic_telefon'                  => 'nullable|string|max:20',
                    'pic_email'                    => 'nullable|email|max:255',
                    'no_telefon'                   => 'nullable|string|max:20',
                    'alamat'                       => 'nullable|string|max:500',
                    'nombor_pendaftaran_syarikat'  => 'nullable|string|max:100',
                ]);
                $company->update($validated);
                return redirect()->route('admin.settings.index', ['section' => 'overview'])
                    ->with('success', __('admin/settings.company_updated'));

            case 'payment_gateway':
                $validated = $request->validate([
                    'user_secret_key'  => 'required|string|max:255',
                    'category_code'    => 'required|string|max:100',
                    'base_url'         => 'required|url|max:255',
                    'api_url'          => 'required|url|max:255',
                    'payment_channel'  => 'required|integer|in:0,1,2',
                    'expiry_days'      => 'required|integer|min:1|max:100',
                    'is_active'        => 'nullable|boolean',
                ]);
                $company->paymentGateway()->updateOrCreate([], array_merge($validated, [
                    'provider'  => 'toyyibpay',
                    'is_active' => $request->boolean('is_active'),
                ]));
                return redirect()->route('admin.settings.index', ['section' => 'payment_gateway'])
                    ->with('success', __('admin/settings.gateway_saved'));

            case 'security':
                $validated = $request->validate([
                    'current_password'          => 'required|string',
                    'password'                  => 'required|string|min:8|confirmed',
                ]);
                if (!Hash::check($validated['current_password'], $user->password)) {
                    return back()->withErrors(['current_password' => __('admin/settings.current_password_incorrect')]);
                }
                $user->update(['password' => Hash::make($validated['password'])]);
                return redirect()->route('admin.settings.index', ['section' => 'security'])
                    ->with('success', __('admin/settings.password_updated'));

            default:
                return redirect()->route('admin.settings.index', ['section' => $section]);
        }
    }

    private function normalizeSection(?string $section): string
    {
        $valid = array_keys($this->navSections());
        return in_array($section, $valid) ? $section : 'overview';
    }
}
