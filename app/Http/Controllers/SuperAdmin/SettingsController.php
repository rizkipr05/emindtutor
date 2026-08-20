<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Company;
use App\Models\CompanyInvoice;
use App\Models\PaymentGateway;
use App\Models\StaffEmind;
use App\Models\SubscriptionPackage;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(Request $request, ?string $section = null)
    {
        $section = $this->normalizeSection($section);

        return view('super-admin.settings.index', $this->buildPageData($section));
    }

    public function update(Request $request, ?string $section = null)
    {
        $section = $this->normalizeSection($section);

        // Payment gateway section saves to payment_gateways table, not system_settings.
        if ($section === 'payment_gateway') {
            $validated = $request->validate([
                'user_secret_key'  => 'required|string|max:255',
                'category_code'    => 'required|string|max:100',
                'base_url'         => 'required|url|max:255',
                'api_url'          => 'required|url|max:255',
                'payment_channel'  => 'required|integer|in:0,1,2',
                'expiry_days'      => 'required|integer|min:1|max:100',
                'is_active'        => 'nullable|boolean',
            ]);

            $gateway = PaymentGateway::firstOrNew(['company_id' => null]);
            $gateway->fill(array_merge($validated, [
                'provider'  => 'toyyibpay',
                'is_active' => $request->boolean('is_active'),
            ]))->save();

            return redirect()
                ->route('superadmin.settings.index', ['section' => 'payment_gateway'])
                ->with('success', __('super_admin/settings.messages.saved'));
        }

        $sections = $this->buildSections();
        $fields = $sections[$section]['fields'];

        $rules = [];
        foreach ($fields as $field) {
            $rules[$field['name']] = $this->validationRulesFor($field);
        }

        $validated = $request->validate($rules);

        foreach ($fields as $field) {
            $value = $this->resolveFieldValue($request, $field, $validated);

            SystemSetting::updateOrCreate(
                ['setting_key' => $field['name']],
                [
                    'setting_group' => $section,
                    'setting_label' => $field['label'],
                    'setting_type' => $field['type'],
                    'setting_value' => $value,
                ]
            );
        }

        return redirect()
            ->route('superadmin.settings.index', ['section' => $section])
            ->with('success', __('super_admin/settings.messages.saved'));
    }

    private function buildPageData(string $section): array
    {
        $stats = [
            'companies' => Company::count(),
            'activeCompanies' => Company::where('status', true)->count(),
            'packages' => SubscriptionPackage::count(),
            'invoices' => CompanyInvoice::count(),
            'paidInvoices' => CompanyInvoice::where('status_bayaran', 'paid')->count(),
            'staff' => StaffEmind::count(),
            'announcements' => Announcement::count(),
        ];

        $settings = SystemSetting::query()->get()->keyBy('setting_key');
        $sections = $this->buildSections();

        // For the payment_gateway section, populate field values from the DB row.
        $gateway = PaymentGateway::query()->whereNull('company_id')->first();

        foreach ($sections as $key => &$definition) {
            foreach ($definition['fields'] as &$field) {
                if ($key === 'payment_gateway' && $gateway) {
                    $field['value'] = (string) ($gateway->{$field['name']} ?? $field['default'] ?? '');
                } else {
                    $field['value'] = $this->fieldValue($settings, $field);
                }
            }
            unset($field);
        }
        unset($definition);

        $navSections = $this->navSections();

        return [
            'sections' => $sections,
            'navSections' => $navSections,
            'currentSection' => $sections[$section],
            'currentSectionKey' => $section,
            'gateway' => $gateway,
            'managementPanel' => $this->managementPanel($section),
            'stats' => $stats,
        ];
    }

    private function managementPanel(string $section): ?array
    {
        return match ($section) {
            'companies' => [
                'title' => __('super_admin/settings.management.companies.title'),
                'subtitle' => __('super_admin/settings.management.companies.subtitle'),
                'create_url' => route('companies.create'),
                'manage_url' => route('companies.index'),
                'records' => Company::with('user')->latest()->take(5)->get()->map(fn (Company $company) => [
                    'title' => $company->user?->name ?? $company->nama_wakil_syarikat,
                    'meta' => $company->user?->email ?? $company->no_telefon,
                    'status' => $company->status ? __('super_admin/settings.management.active') : __('super_admin/settings.management.inactive'),
                    'edit_url' => route('companies.edit', $company),
                    'delete_url' => route('companies.destroy', $company),
                ]),
            ],
            'staff' => [
                'title' => __('super_admin/settings.management.staff.title'),
                'subtitle' => __('super_admin/settings.management.staff.subtitle'),
                'create_url' => route('superadmin.staff.create'),
                'manage_url' => route('superadmin.staff.index'),
                'records' => StaffEmind::with('user')->latest()->take(5)->get()->map(fn (StaffEmind $staff) => [
                    'title' => $staff->user?->name ?? $staff->ic_number,
                    'meta' => $staff->user?->email ?? $staff->phone,
                    'status' => $staff->status,
                    'edit_url' => route('superadmin.staff.edit', $staff),
                    'delete_url' => route('superadmin.staff.destroy', $staff),
                ]),
            ],
            'billing' => [
                'title' => __('super_admin/settings.management.billing.title'),
                'subtitle' => __('super_admin/settings.management.billing.subtitle'),
                'manage_url' => route('invoices.index'),
                'records' => CompanyInvoice::with('company.user')->latest()->take(5)->get()->map(fn (CompanyInvoice $invoice) => [
                    'title' => $invoice->nomor_invoice,
                    'meta' => $invoice->company?->user?->name ?? '-',
                    'status' => $invoice->status_bayaran,
                ]),
            ],
            'communications' => [
                'title' => __('super_admin/settings.management.communications.title'),
                'subtitle' => __('super_admin/settings.management.communications.subtitle'),
                'create_url' => route('broadcasts.create'),
                'manage_url' => route('broadcasts.index'),
                'records' => Announcement::latest()->take(5)->get()->map(fn (Announcement $announcement) => [
                    'title' => $announcement->title,
                    'meta' => $announcement->target_audience,
                    'status' => __('super_admin/settings.management.sent'),
                    'view_url' => route('broadcasts.show', $announcement->id),
                ]),
            ],
            default => null,
        };
    }

    private function buildSections(): array
    {
        $packageOptions = SubscriptionPackage::query()
            ->orderBy('nama_pakej')
            ->pluck('nama_pakej', 'id')
            ->toArray();

        $defaultPackageId = SubscriptionPackage::query()->where('is_trial', true)->value('id');
        if ($defaultPackageId === null) {
            $defaultPackageId = SubscriptionPackage::query()->value('id');
        }

        return [
            'overview' => [
                'title' => __('super_admin/settings.sections.overview.title'),
                'subtitle' => __('super_admin/settings.sections.overview.subtitle'),
                'badge' => __('super_admin/settings.sections.overview.badge'),
                'fields' => [
                    [
                        'name' => 'app_name',
                        'label' => __('super_admin/settings.forms.app_name'),
                        'type' => 'text',
                        'default' => config('app.name', 'EMindTutor'),
                        'placeholder' => config('app.name', 'EMindTutor'),
                        'help' => __('super_admin/settings.forms.app_name_help'),
                    ],
                    [
                        'name' => 'default_locale',
                        'label' => __('super_admin/settings.forms.default_locale'),
                        'type' => 'select',
                        'default' => app()->getLocale() ?: 'en',
                        'options' => [
                            'en' => __('super_admin/settings.forms.locale_options.en'),
                            'my' => __('super_admin/settings.forms.locale_options.my'),
                        ],
                        'help' => __('super_admin/settings.forms.default_locale_help'),
                    ],
                    [
                        'name' => 'support_email',
                        'label' => __('super_admin/settings.forms.support_email'),
                        'type' => 'email',
                        'default' => config('mail.from.address', 'support@emindtutor.test'),
                        'placeholder' => 'support@emindtutor.test',
                        'help' => __('super_admin/settings.forms.support_email_help'),
                    ],
                ],
                'features' => [
                    ['label' => __('layouts/superadmin.companies'), 'desc' => __('super_admin/settings.actions.manage_companies.desc'), 'url' => route('companies.index')],
                    ['label' => __('layouts/superadmin.subscription_packages'), 'desc' => __('super_admin/settings.actions.manage_packages.desc'), 'url' => route('subscription-packages.index')],
                    ['label' => __('layouts/superadmin.invoices'), 'desc' => __('super_admin/settings.actions.manage_billing.desc'), 'url' => route('invoices.index')],
                    ['label' => __('layouts/superadmin.staff'), 'desc' => __('super_admin/settings.actions.manage_staff.desc'), 'url' => route('superadmin.staff.index')],
                    ['label' => __('layouts/superadmin.stats'), 'desc' => __('super_admin/settings.actions.open_reports.desc'), 'url' => route('superadmin.stats.index')],
                    ['label' => __('layouts/superadmin.communications'), 'desc' => __('super_admin/settings.actions.open_communications.desc'), 'url' => route('broadcasts.index')],
                ],
            ],
            'companies' => [
                'title' => __('super_admin/settings.sections.companies.title'),
                'subtitle' => __('super_admin/settings.sections.companies.subtitle'),
                'badge' => __('super_admin/settings.sections.companies.badge', ['count' => Company::count()]),
                'fields' => [
                    [
                        'name' => 'allow_login_as',
                        'label' => __('super_admin/settings.forms.allow_login_as'),
                        'type' => 'toggle',
                        'default' => '1',
                        'help' => __('super_admin/settings.forms.allow_login_as_help'),
                    ],
                    [
                        'name' => 'auto_activate_companies',
                        'label' => __('super_admin/settings.forms.auto_activate_companies'),
                        'type' => 'toggle',
                        'default' => '1',
                        'help' => __('super_admin/settings.forms.auto_activate_companies_help'),
                    ],
                ],
                'features' => [
                    ['label' => __('super_admin/settings.actions.manage_companies.title'), 'desc' => __('super_admin/settings.actions.manage_companies.desc'), 'url' => route('companies.index')],
                    ['label' => __('super_admin/settings.actions.create_company.title'), 'desc' => __('super_admin/settings.actions.create_company.desc'), 'url' => route('companies.create')],
                ],
            ],
            'packages' => [
                'title' => __('super_admin/settings.sections.packages.title'),
                'subtitle' => __('super_admin/settings.sections.packages.subtitle'),
                'badge' => __('super_admin/settings.sections.packages.badge', ['count' => SubscriptionPackage::count()]),
                'fields' => [
                    [
                        'name' => 'trial_package_id',
                        'label' => __('super_admin/settings.forms.trial_package'),
                        'type' => 'select',
                        'default' => $defaultPackageId,
                        'options' => $packageOptions,
                        'required' => ! empty($packageOptions),
                        'help' => __('super_admin/settings.forms.trial_package_help'),
                    ],
                    [
                        'name' => 'allow_package_management',
                        'label' => __('super_admin/settings.forms.allow_package_management'),
                        'type' => 'toggle',
                        'default' => '1',
                        'help' => __('super_admin/settings.forms.allow_package_management_help'),
                    ],
                ],
                'features' => [
                    ['label' => __('super_admin/settings.actions.manage_packages.title'), 'desc' => __('super_admin/settings.actions.manage_packages.desc'), 'url' => route('subscription-packages.index')],
                    ['label' => __('super_admin/settings.actions.manage_subscription_toggle.title'), 'desc' => __('super_admin/settings.actions.manage_subscription_toggle.desc'), 'url' => route('langganan.index')],
                ],
            ],
            'billing' => [
                'title' => __('super_admin/settings.sections.billing.title'),
                'subtitle' => __('super_admin/settings.sections.billing.subtitle'),
                'badge' => __('super_admin/settings.sections.billing.badge', ['count' => CompanyInvoice::count()]),
                'fields' => [
                    [
                        'name' => 'invoice_reminder_days',
                        'label' => __('super_admin/settings.forms.invoice_reminder_days'),
                        'type' => 'number',
                        'default' => '7',
                        'help' => __('super_admin/settings.forms.invoice_reminder_days_help'),
                    ],
                ],
                'features' => [
                    ['label' => __('super_admin/settings.actions.manage_billing.title'), 'desc' => __('super_admin/settings.actions.manage_billing.desc'), 'url' => route('invoices.index')],
                    ['label' => __('super_admin/settings.actions.subscription_list.title'), 'desc' => __('super_admin/settings.actions.subscription_list.desc'), 'url' => route('langganan.index')],
                    ['label' => __('super_admin/settings.actions.export_pdf.title'), 'desc' => __('super_admin/settings.actions.export_pdf.desc'), 'url' => route('superadmin.stats.export-pdf')],
                    ['label' => __('super_admin/settings.actions.export_excel.title'), 'desc' => __('super_admin/settings.actions.export_excel.desc'), 'url' => route('superadmin.stats.export-excel')],
                ],
            ],
            'staff' => [
                'title' => __('super_admin/settings.sections.staff.title'),
                'subtitle' => __('super_admin/settings.sections.staff.subtitle'),
                'badge' => __('super_admin/settings.sections.staff.badge', ['count' => StaffEmind::count()]),
                'fields' => [
                    [
                        'name' => 'enable_staff_module',
                        'label' => __('super_admin/settings.forms.enable_staff_module'),
                        'type' => 'toggle',
                        'default' => '1',
                        'help' => __('super_admin/settings.forms.enable_staff_module_help'),
                    ],
                    [
                        'name' => 'auto_activate_staff',
                        'label' => __('super_admin/settings.forms.auto_activate_staff'),
                        'type' => 'toggle',
                        'default' => '0',
                        'help' => __('super_admin/settings.forms.auto_activate_staff_help'),
                    ],
                ],
                'features' => [
                    ['label' => __('super_admin/settings.actions.manage_staff.title'), 'desc' => __('super_admin/settings.actions.manage_staff.desc'), 'url' => route('superadmin.staff.index')],
                ],
            ],
            'reports' => [
                'title' => __('super_admin/settings.sections.reports.title'),
                'subtitle' => __('super_admin/settings.sections.reports.subtitle'),
                'badge' => __('super_admin/settings.sections.reports.badge'),
                'fields' => [
                    [
                        'name' => 'allow_pdf_export',
                        'label' => __('super_admin/settings.forms.allow_pdf_export'),
                        'type' => 'toggle',
                        'default' => '1',
                        'help' => __('super_admin/settings.forms.allow_pdf_export_help'),
                    ],
                    [
                        'name' => 'allow_excel_export',
                        'label' => __('super_admin/settings.forms.allow_excel_export'),
                        'type' => 'toggle',
                        'default' => '1',
                        'help' => __('super_admin/settings.forms.allow_excel_export_help'),
                    ],
                ],
                'features' => [
                    ['label' => __('super_admin/settings.actions.open_reports.title'), 'desc' => __('super_admin/settings.actions.open_reports.desc'), 'url' => route('superadmin.stats.index')],
                    ['label' => __('super_admin/settings.actions.export_pdf.title'), 'desc' => __('super_admin/settings.actions.export_pdf.desc'), 'url' => route('superadmin.stats.export-pdf')],
                    ['label' => __('super_admin/settings.actions.export_excel.title'), 'desc' => __('super_admin/settings.actions.export_excel.desc'), 'url' => route('superadmin.stats.export-excel')],
                ],
            ],
            'communications' => [
                'title' => __('super_admin/settings.sections.communications.title'),
                'subtitle' => __('super_admin/settings.sections.communications.subtitle'),
                'badge' => __('super_admin/settings.sections.communications.badge', ['count' => Announcement::count()]),
                'fields' => [
                    [
                        'name' => 'enable_broadcasts',
                        'label' => __('super_admin/settings.forms.enable_broadcasts'),
                        'type' => 'toggle',
                        'default' => '1',
                        'help' => __('super_admin/settings.forms.enable_broadcasts_help'),
                    ],
                    [
                        'name' => 'default_audience',
                        'label' => __('super_admin/settings.forms.default_audience'),
                        'type' => 'select',
                        'default' => 'all',
                        'options' => [
                            'all' => __('super_admin/settings.forms.default_audience_options.all'),
                            'companies' => __('super_admin/settings.forms.default_audience_options.companies'),
                            'teachers' => __('super_admin/settings.forms.default_audience_options.teachers'),
                            'students' => __('super_admin/settings.forms.default_audience_options.students'),
                        ],
                        'help' => __('super_admin/settings.forms.default_audience_help'),
                    ],
                ],
                'features' => [
                    ['label' => __('super_admin/settings.actions.open_communications.title'), 'desc' => __('super_admin/settings.actions.open_communications.desc'), 'url' => route('broadcasts.index')],
                ],
            ],
            'email_templates' => [
                'title' => __('super_admin/settings.sections.email_templates.title'),
                'subtitle' => __('super_admin/settings.sections.email_templates.subtitle'),
                'badge' => __('super_admin/settings.sections.email_templates.badge'),
                'fields' => [
                    [
                        'name' => 'welcome_email_subject',
                        'label' => __('super_admin/settings.forms.welcome_email_subject'),
                        'type' => 'text',
                        'default' => 'Welcome to eMindTutor, {company_name}',
                        'placeholder' => 'Welcome to eMindTutor, {company_name}',
                        'help' => __('super_admin/settings.forms.email_subject_help'),
                    ],
                    [
                        'name' => 'payment_confirmation_subject',
                        'label' => __('super_admin/settings.forms.payment_confirmation_subject'),
                        'type' => 'text',
                        'default' => 'Payment Confirmation - {invoice_number}',
                        'placeholder' => 'Payment Confirmation - {invoice_number}',
                        'help' => __('super_admin/settings.forms.email_subject_help'),
                    ],
                    [
                        'name' => 'welcome_email_body',
                        'label' => __('super_admin/settings.forms.welcome_email_body'),
                        'type' => 'textarea',
                        'rows' => 10,
                        'full_width' => true,
                        'default' => "Hi {company_name},\n\nYour company account has been successfully activated.\n\nLogin email: {email}\nLogin URL: {login_url}\n\nPlease log in to complete your company profile and start managing teachers, students, classes, and payments.\n\nBest regards,\nTeam eMindTutor",
                        'help' => __('super_admin/settings.forms.email_template_help'),
                    ],
                ],
                'features' => [
                    ['label' => __('super_admin/settings.actions.manage_billing.title'), 'desc' => __('super_admin/settings.actions.manage_billing.desc'), 'url' => route('invoices.index')],
                    ['label' => __('super_admin/settings.actions.open_communications.title'), 'desc' => __('super_admin/settings.actions.open_communications.desc'), 'url' => route('broadcasts.index')],
                ],
            ],
            'payment_gateway' => [
                'title' => __('super_admin/settings.sections.payment_gateway.title'),
                'subtitle' => __('super_admin/settings.sections.payment_gateway.subtitle'),
                'badge' => __('super_admin/settings.sections.payment_gateway.badge'),
                'fields' => [
                    [
                        'name'        => 'user_secret_key',
                        'label'       => 'User Secret Key',
                        'type'        => 'text',
                        'default'     => '',
                        'placeholder' => 'xxxxx-xxxxx-xxxxx',
                        'help'        => __('admin/settings.secret_key_help'),
                    ],
                    [
                        'name'        => 'category_code',
                        'label'       => 'Category Code',
                        'type'        => 'text',
                        'default'     => '',
                        'placeholder' => 'xxxxxxxx',
                        'help'        => __('admin/settings.category_code_help'),
                    ],
                    [
                        'name'        => 'base_url',
                        'label'       => 'Base URL',
                        'type'        => 'text',
                        'default'     => 'https://toyyibpay.com',
                        'placeholder' => 'https://toyyibpay.com',
                        'help'        => 'Base URL ToyyibPay.',
                    ],
                    [
                        'name'        => 'api_url',
                        'label'       => 'API URL',
                        'type'        => 'text',
                        'default'     => 'https://toyyibpay.com',
                        'placeholder' => 'https://toyyibpay.com',
                        'help'        => 'API Endpoint URL ToyyibPay.',
                    ],
                    [
                        'name'    => 'payment_channel',
                        'label'   => __('admin/settings.payment_channel'),
                        'type'    => 'select',
                        'default' => '0',
                        'options' => [
                            '0' => __('admin/settings.all_fpx_card'),
                            '1' => __('admin/settings.fpx_only'),
                            '2' => __('admin/settings.credit_card_only'),
                        ],
                        'help'    => 'Saluran pembayaran yang dibenarkan.',
                    ],
                    [
                        'name'    => 'expiry_days',
                        'label'   => __('admin/settings.invoice_expiry_days'),
                        'type'    => 'number',
                        'default' => '3',
                        'help'    => __('admin/settings.invoice_expiry_help'),
                    ],
                    [
                        'name'    => 'is_active',
                        'label'   => __('admin/settings.enable_gateway'),
                        'type'    => 'toggle',
                        'default' => '1',
                        'help'    => __('admin/settings.gateway_disabled_help'),
                        'full_width' => true,
                    ],
                ],
                'features' => [
                    ['label' => 'Senarai Invois', 'desc' => 'Urus semua invois syarikat.', 'url' => route('invoices.index')],
                ],
            ],
        ];
    }

    private function navSections(): array
    {
        return [
            'overview' => [
                'label' => __('super_admin/settings.sections.overview.title'),
                'caption' => __('super_admin/settings.sections.overview.subtitle'),
                'icon' => 'M4 7.25A3.25 3.25 0 017.25 4h9.5A3.25 3.25 0 0120 7.25v9.5A3.25 3.25 0 0116.75 20h-9.5A3.25 3.25 0 014 16.75v-9.5zM8.5 8.5h7v1.5h-7zm0 3h7V13h-7zm0 3h4V16h-4z',
            'tone' => 'from-cyan-500 to-teal-500',
        ],
        'companies' => [
            'label' => __('super_admin/settings.sections.companies.title'),
            'caption' => __('super_admin/settings.forms.allow_login_as'),
            'icon' => 'M5 20h14a1 1 0 001-1v-8a1 1 0 00-1-1h-2V6.5A2.5 2.5 0 0014.5 4h-5A2.5 2.5 0 007 6.5V10H5a1 1 0 00-1 1v8a1 1 0 001 1zm5-13.5V6.5a1.5 1.5 0 011.5-1.5h5a1.5 1.5 0 011.5 1.5V10z',
            'tone' => 'from-indigo-500 to-blue-500',
        ],
        'packages' => [
            'label' => __('super_admin/settings.sections.packages.title'),
            'caption' => __('super_admin/settings.forms.trial_package'),
            'icon' => 'M12 2l7 4v6c0 5-3.5 9.5-7 10-3.5-.5-7-5-7-10V6l7-4zm0 5.2a2.8 2.8 0 100 5.6 2.8 2.8 0 000-5.6z',
            'tone' => 'from-violet-500 to-fuchsia-500',
        ],
        'billing' => [
            'label' => __('super_admin/settings.sections.billing.title'),
            'caption' => __('super_admin/settings.forms.allow_manual_verification'),
            'icon' => 'M7 4h10a1 1 0 011 1v14l-4-2-4 2-4-2-4 2V5a1 1 0 011-1h4zm1.5 4.5h7v1.5h-7zm0 3h7V13h-7z',
            'tone' => 'from-amber-500 to-orange-500',
        ],
        'staff' => [
            'label' => __('super_admin/settings.sections.staff.title'),
            'caption' => __('super_admin/settings.forms.enable_staff_module'),
            'icon' => 'M12 12a4 4 0 100-8 4 4 0 000 8zm-7 8a7 7 0 0114 0H5z',
            'tone' => 'from-rose-500 to-pink-500',
        ],
        'reports' => [
            'label' => __('super_admin/settings.sections.reports.title'),
            'caption' => __('super_admin/settings.forms.allow_pdf_export'),
            'icon' => 'M4 19.5h16M6 16V9m4 7V6m4 10v-5m4 5V8',
            'outline' => true,
            'tone' => 'from-sky-500 to-cyan-500',
        ],
        'communications' => [
            'label' => __('super_admin/settings.sections.communications.title'),
            'caption' => __('super_admin/settings.forms.enable_broadcasts'),
            'icon' => 'M3 6.75A2.75 2.75 0 015.75 4h12.5A2.75 2.75 0 0121 6.75v7.5A2.75 2.75 0 0118.25 17H9l-5 3v-3H5.75A2.75 2.75 0 013 14.25z',
            'tone' => 'from-emerald-500 to-teal-500',
        ],
        'email_templates' => [
            'label' => __('super_admin/settings.sections.email_templates.title'),
            'caption' => __('super_admin/settings.sections.email_templates.subtitle'),
            'icon' => 'M4 6.5A2.5 2.5 0 016.5 4h11A2.5 2.5 0 0120 6.5v11a2.5 2.5 0 01-2.5 2.5h-11A2.5 2.5 0 014 17.5v-11zm2.2.8l5.8 4.35 5.8-4.35-.9-1.2L12 9.75 7.1 6.1l-.9 1.2z',
            'tone' => 'from-cyan-500 to-blue-500',
        ],
        'payment_gateway' => [
            'label'   => 'Payment Gateway',
            'caption' => 'ToyyibPay global',
            'icon'    => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z',
            'outline' => true,
            'tone'    => 'from-green-500 to-teal-500',
        ],
    ];
    }

    private function normalizeSection(?string $section): string
    {
        $section = $section ?: 'overview';

        return array_key_exists($section, $this->buildSections()) ? $section : 'overview';
    }

    private function fieldValue($settings, array $field): string
    {
        $setting = $settings->get($field['name']);
        $value = $setting?->setting_value;

        if ($value === null || $value === '') {
            $value = $field['default'] ?? '';
        }

        return (string) $value;
    }

    private function validationRulesFor(array $field): array
    {
        return match ($field['type']) {
            'email' => ['required', 'email', 'max:255'],
            'number' => ['required', 'integer', 'min:0'],
            'textarea' => ['required', 'string', 'max:10000'],
            'select' => array_values(array_filter([
                ($field['required'] ?? true) ? 'required' : 'nullable',
                'string',
                ! empty($field['options']) ? 'in:' . implode(',', array_keys($field['options'])) : null,
            ])),
            'toggle' => ['boolean'],
            default => ['required', 'string', 'max:255'],
        };
    }

    private function resolveFieldValue(Request $request, array $field, array $validated): string
    {
        return match ($field['type']) {
            'toggle' => $request->boolean($field['name']) ? '1' : '0',
            default => (string) ($validated[$field['name']] ?? ''),
        };
    }

}
