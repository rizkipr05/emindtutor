<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyBankDetail;
use App\Models\CompanyInvoice;
use App\Models\PaymentGateway;
use App\Models\Staff;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SettingsController extends Controller
{
    private function resolveCompany(): ?Company
    {
        $user = Auth::user();
        return Company::with('user')->where('user_id', $user->id)->first()
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
            // 'attendance' section removed by request
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
        $globalGateway     = PaymentGateway::superAdmin();
        $bankDetails       = CompanyBankDetail::where('company_id', $company->id)->get();

        $subscriptionData = $this->buildSubscriptionData($company);

        return view('admin.settings.index', array_merge(
            compact('company', 'user', 'navSections', 'currentSectionKey', 'gateway', 'globalGateway', 'bankDetails'),
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
                    'no_telefon'                   => 'nullable|string|max:20',
                    'alamat'                       => 'nullable|string|max:500',
                    'nombor_pendaftaran_syarikat'  => 'nullable|string|max:100',
                    'link'                          => 'nullable|url|max:255',
                    'logo_syarikat'                 => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                ]);

                unset($validated['logo_syarikat']);
                $previousLogo = $company->logo_syarikat;

                if ($request->hasFile('logo_syarikat')) {
                    // cPanel deployments commonly expose ../public_html as the
                    // web root while Laravel itself lives in ~/laravel.
                    $hostingPublicUploads = base_path('../public_html/uploads');
                    $uploadDirectory = File::isDirectory(base_path('../public_html'))
                        ? $hostingPublicUploads
                        : base_path('uploads');
                    if (!File::isDirectory($uploadDirectory) && !File::makeDirectory($uploadDirectory, 0775, true, true)) {
                        throw ValidationException::withMessages([
                            'logo_syarikat' => 'Folder upload logo tidak dapat dibuat. Sila hubungi pentadbir hosting.',
                        ]);
                    }

                    $file = $request->file('logo_syarikat');
                    if (! $file->isValid()) {
                        throw ValidationException::withMessages([
                            'logo_syarikat' => 'Upload logo gagal. Semak had upload PHP di hosting (upload_max_filesize dan post_max_size).',
                        ]);
                    }

                    if (!is_writable($uploadDirectory)) {
                        throw ValidationException::withMessages([
                            'logo_syarikat' => 'Folder uploads tidak boleh ditulis. Tetapkan permission folder kepada 775 di hosting.',
                        ]);
                    }

                    $filename = 'company-logo-' . now()->format('YmdHis') . '-' . bin2hex(random_bytes(6)) . '.' . $file->extension();
                    try {
                        $file->move($uploadDirectory, $filename);
                    } catch (\Throwable $exception) {
                        report($exception);

                        throw ValidationException::withMessages([
                            'logo_syarikat' => 'Logo tidak dapat disimpan. Pastikan folder uploads mempunyai permission 775 dan dimiliki oleh user hosting.',
                        ]);
                    }

                    $validated['logo_syarikat'] = $filename;
                }

                $company->update($validated);

                if (isset($validated['logo_syarikat']) && $previousLogo) {
                    foreach ([base_path('../public_html/uploads/' . $previousLogo), base_path('uploads/' . $previousLogo)] as $oldLogoPath) {
                        if (file_exists($oldLogoPath)) {
                            @unlink($oldLogoPath);
                        }
                    }
                }

                return redirect()->route('admin.settings.index', ['section' => 'overview'])
                    ->with('success', __('admin/settings.company_updated'));

            case 'payment_gateway':
                $hasExistingGateway = $company->paymentGateway()->exists();
                $rules = [
                    'user_secret_key'  => $hasExistingGateway ? 'nullable|string|max:255' : 'required|string|max:255',
                    'category_code'    => 'required|string|max:100',
                    'base_url'         => 'required|url|max:255',
                    'api_url'          => 'required|url|max:255',
                    'payment_channel'  => 'required|integer|in:0,1,2',
                    'expiry_days'      => 'required|integer|min:1|max:100',
                    'is_active'        => 'nullable|boolean',
                ];

                $validated = $request->validate($rules);

                $secretKeyEntered = $request->filled('user_secret_key');

                if (! $secretKeyEntered) {
                    unset($validated['user_secret_key']);
                }

                $validated['provider'] = 'toyyibpay';
                // A newly entered/replaced secret key means the company wants
                // to use ToyyibPay. Keep the explicit toggle for later manual
                // deactivation, but do not save a newly configured gateway as
                // inactive just because its switch was not clicked.
                $validated['is_active'] = $secretKeyEntered || $request->boolean('is_active');

                $company->paymentGateway()->updateOrCreate([], $validated);

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

            case 'attendance':
                $validated = $request->validate([
                    'attendance_require_photo' => 'nullable|boolean',
                    'attendance_require_gps' => 'nullable|boolean',
                ]);

                $photo = $request->boolean('attendance_require_photo') ? '1' : '0';
                $gps = $request->boolean('attendance_require_gps') ? '1' : '0';

                \App\Models\SystemSetting::updateOrCreate([
                    'setting_key' => "company_{$company->id}_attendance_require_photo",
                ], [
                    'setting_group' => 'attendance',
                    'setting_label' => 'Require Photo for Attendance (Company)',
                    'setting_type' => 'toggle',
                    'setting_value' => $photo,
                ]);

                \App\Models\SystemSetting::updateOrCreate([
                    'setting_key' => "company_{$company->id}_attendance_require_gps",
                ], [
                    'setting_group' => 'attendance',
                    'setting_label' => 'Require GPS for Attendance (Company)',
                    'setting_type' => 'toggle',
                    'setting_value' => $gps,
                ]);

                return redirect()->route('admin.settings.index', ['section' => 'attendance'])
                    ->with('success', 'Attendance settings saved.');

            default:
                return redirect()->route('admin.settings.index', ['section' => $section]);
        }
    }

    public function subscriptionReceipt(CompanyInvoice $invoice)
    {
        $company = $this->resolveCompany();

        abort_unless(
            $company
            && $invoice->company_id === $company->id,
            404
        );

        $invoice->load(['company.user', 'package']);

        return Pdf::loadView('super-admin.invoices.receipt', compact('invoice'))
            ->setPaper('A4', 'portrait')
            ->download("{$invoice->nomor_invoice}-{$invoice->company->user->name}.pdf");
    }

    private function normalizeSection(?string $section): string
    {
        $valid = array_keys($this->navSections());
        return in_array($section, $valid) ? $section : 'overview';
    }
}
