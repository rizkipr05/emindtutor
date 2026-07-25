<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Company;
use App\Models\CompanyInvoice;
use App\Models\StaffEmind;
use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(Request $request, ?string $section = null)
    {
        $section = $section ?: 'overview';

        $stats = [
            'companies' => Company::count(),
            'activeCompanies' => Company::where('status', true)->count(),
            'packages' => SubscriptionPackage::count(),
            'trialPackage' => SubscriptionPackage::where('is_trial', true)->value('nama_pakej') ?? '-',
            'invoices' => CompanyInvoice::count(),
            'paidInvoices' => CompanyInvoice::where('status_bayaran', 'paid')->count(),
            'staff' => StaffEmind::count(),
            'announcements' => Announcement::count(),
            'locale' => app()->getLocale(),
            'appName' => config('app.name', 'EMindTutor'),
        ];

        $sections = [
            'overview' => [
                'title' => __('super_admin/settings.sections.overview.title'),
                'subtitle' => __('super_admin/settings.sections.overview.subtitle'),
                'badge' => __('super_admin/settings.sections.overview.badge'),
                'details' => [
                    ['label' => __('super_admin/settings.overview.app_label'), 'value' => $stats['appName']],
                    ['label' => __('super_admin/settings.overview.locale_label'), 'value' => strtoupper($stats['locale'])],
                    ['label' => __('super_admin/settings.metrics.companies'), 'value' => $stats['companies']],
                    ['label' => __('super_admin/settings.metrics.active_companies_label'), 'value' => $stats['activeCompanies']],
                    ['label' => __('super_admin/settings.metrics.packages'), 'value' => $stats['packages']],
                    ['label' => __('super_admin/settings.metrics.paid_invoices_label'), 'value' => $stats['paidInvoices']],
                    ['label' => __('super_admin/settings.metrics.staff'), 'value' => $stats['staff']],
                    ['label' => __('super_admin/settings.metrics.announcements_label'), 'value' => $stats['announcements']],
                ],
                'features' => [
                    ['label' => __('layouts/superadmin.companies'), 'desc' => __('super_admin/settings.actions.manage_companies.desc'), 'url' => route('companies.index')],
                    ['label' => __('layouts/superadmin.subscription_packages'), 'desc' => __('super_admin/settings.actions.manage_packages.desc'), 'url' => route('subscription-packages.index')],
                    ['label' => __('layouts/superadmin.invoices'), 'desc' => __('super_admin/settings.actions.manage_billing.desc'), 'url' => route('invoices.index')],
                    ['label' => __('layouts/superadmin.staff'), 'desc' => __('super_admin/settings.actions.manage_staff.desc'), 'url' => route('superadmin.staff.index')],
                    ['label' => __('layouts/superadmin.stats'), 'desc' => __('super_admin/settings.actions.open_reports.desc'), 'url' => route('superadmin.stats.index')],
                    ['label' => __('layouts/superadmin.communications'), 'desc' => __('super_admin/settings.actions.open_communications.desc'), 'url' => route('broadcasts.index')],
                ],
                'highlights' => [
                    ['label' => __('super_admin/settings.metrics.companies'), 'value' => $stats['companies'], 'hint' => __('super_admin/settings.metrics.active_companies', ['count' => $stats['activeCompanies']])],
                    ['label' => __('super_admin/settings.metrics.packages'), 'value' => $stats['packages'], 'hint' => __('super_admin/settings.metrics.trial_package', ['name' => $stats['trialPackage']])],
                    ['label' => __('super_admin/settings.metrics.invoices'), 'value' => $stats['invoices'], 'hint' => __('super_admin/settings.metrics.paid_invoices', ['count' => $stats['paidInvoices']])],
                    ['label' => __('super_admin/settings.metrics.staff'), 'value' => $stats['staff'], 'hint' => __('super_admin/settings.metrics.announcements', ['count' => $stats['announcements']])],
                ],
                'actions' => [
                    [
                        'title' => __('super_admin/settings.actions.manage_companies.title'),
                        'desc' => __('super_admin/settings.actions.manage_companies.desc'),
                        'url' => route('companies.index'),
                    ],
                    [
                        'title' => __('super_admin/settings.actions.manage_packages.title'),
                        'desc' => __('super_admin/settings.actions.manage_packages.desc'),
                        'url' => route('subscription-packages.index'),
                    ],
                    [
                        'title' => __('super_admin/settings.actions.manage_billing.title'),
                        'desc' => __('super_admin/settings.actions.manage_billing.desc'),
                        'url' => route('invoices.index'),
                    ],
                    [
                        'title' => __('super_admin/settings.actions.manage_staff.title'),
                        'desc' => __('super_admin/settings.actions.manage_staff.desc'),
                        'url' => route('superadmin.staff.index'),
                    ],
                ],
            ],
            'companies' => [
                'title' => __('super_admin/settings.sections.companies.title'),
                'subtitle' => __('super_admin/settings.sections.companies.subtitle'),
                'badge' => __('super_admin/settings.sections.companies.badge', ['count' => $stats['companies']]),
                'details' => [
                    ['label' => __('super_admin/settings.metrics.companies'), 'value' => $stats['companies']],
                    ['label' => __('super_admin/settings.metrics.active_companies_label'), 'value' => $stats['activeCompanies']],
                    ['label' => __('super_admin/settings.company.login_as'), 'value' => __('super_admin/settings.company.enabled')],
                    ['label' => __('super_admin/settings.company.status'), 'value' => __('super_admin/settings.sidebar.active')],
                ],
                'features' => [
                    ['label' => __('super_admin/settings.actions.manage_companies.title'), 'desc' => __('super_admin/settings.actions.manage_companies.desc'), 'url' => route('companies.index')],
                    ['label' => __('super_admin/settings.actions.create_company.title'), 'desc' => __('super_admin/settings.actions.create_company.desc'), 'url' => route('companies.create')],
                    ['label' => __('super_admin/settings.company.login_as'), 'desc' => __('super_admin/settings.company.login_as_hint'), 'url' => route('companies.index')],
                ],
                'highlights' => [
                    ['label' => __('super_admin/settings.metrics.companies'), 'value' => $stats['companies'], 'hint' => __('super_admin/settings.metrics.active_companies', ['count' => $stats['activeCompanies']])],
                    ['label' => __('super_admin/settings.company.controls'), 'value' => __('super_admin/settings.company.login_as'), 'hint' => __('super_admin/settings.company.login_as_hint')],
                ],
                'actions' => [
                    [
                        'title' => __('super_admin/settings.actions.manage_companies.title'),
                        'desc' => __('super_admin/settings.actions.manage_companies.desc'),
                        'url' => route('companies.index'),
                    ],
                    [
                        'title' => __('super_admin/settings.actions.create_company.title'),
                        'desc' => __('super_admin/settings.actions.create_company.desc'),
                        'url' => route('companies.create'),
                    ],
                ],
            ],
            'packages' => [
                'title' => __('super_admin/settings.sections.packages.title'),
                'subtitle' => __('super_admin/settings.sections.packages.subtitle'),
                'badge' => __('super_admin/settings.sections.packages.badge', ['count' => $stats['packages']]),
                'details' => [
                    ['label' => __('super_admin/settings.metrics.packages'), 'value' => $stats['packages']],
                    ['label' => __('super_admin/settings.package.trial_label'), 'value' => $stats['trialPackage']],
                    ['label' => __('super_admin/settings.package.subscription_toggle'), 'value' => __('super_admin/settings.package.enabled')],
                    ['label' => __('super_admin/settings.package.status'), 'value' => __('super_admin/settings.sidebar.active')],
                ],
                'features' => [
                    ['label' => __('super_admin/settings.actions.manage_packages.title'), 'desc' => __('super_admin/settings.actions.manage_packages.desc'), 'url' => route('subscription-packages.index')],
                    ['label' => __('super_admin/settings.actions.manage_subscription_toggle.title'), 'desc' => __('super_admin/settings.actions.manage_subscription_toggle.desc'), 'url' => route('langganan.index')],
                ],
                'highlights' => [
                    ['label' => __('super_admin/settings.metrics.packages'), 'value' => $stats['packages'], 'hint' => __('super_admin/settings.package.trial', ['name' => $stats['trialPackage']])],
                    ['label' => __('super_admin/settings.package.subscription_toggle'), 'value' => __('super_admin/settings.package.enabled'), 'hint' => __('super_admin/settings.package.subscription_toggle_hint')],
                ],
                'actions' => [
                    [
                        'title' => __('super_admin/settings.actions.manage_packages.title'),
                        'desc' => __('super_admin/settings.actions.manage_packages.desc'),
                        'url' => route('subscription-packages.index'),
                    ],
                    [
                        'title' => __('super_admin/settings.actions.manage_subscription_toggle.title'),
                        'desc' => __('super_admin/settings.actions.manage_subscription_toggle.desc'),
                        'url' => route('langganan.index'),
                    ],
                ],
            ],
            'billing' => [
                'title' => __('super_admin/settings.sections.billing.title'),
                'subtitle' => __('super_admin/settings.sections.billing.subtitle'),
                'badge' => __('super_admin/settings.sections.billing.badge', ['count' => $stats['paidInvoices']]),
                'details' => [
                    ['label' => __('super_admin/settings.metrics.invoices'), 'value' => $stats['invoices']],
                    ['label' => __('super_admin/settings.metrics.paid_invoices_label'), 'value' => $stats['paidInvoices']],
                    ['label' => __('super_admin/settings.billing.manual_verify'), 'value' => __('super_admin/settings.billing.reconcile')],
                    ['label' => __('super_admin/settings.billing.receipt_access'), 'value' => __('super_admin/settings.quick_links.open')],
                ],
                'features' => [
                    ['label' => __('super_admin/settings.actions.manage_billing.title'), 'desc' => __('super_admin/settings.actions.manage_billing.desc'), 'url' => route('invoices.index')],
                    ['label' => __('super_admin/settings.actions.subscription_list.title'), 'desc' => __('super_admin/settings.actions.subscription_list.desc'), 'url' => route('langganan.index')],
                    ['label' => __('super_admin/settings.actions.export_pdf.title'), 'desc' => __('super_admin/settings.actions.export_pdf.desc'), 'url' => route('superadmin.stats.export-pdf')],
                    ['label' => __('super_admin/settings.actions.export_excel.title'), 'desc' => __('super_admin/settings.actions.export_excel.desc'), 'url' => route('superadmin.stats.export-excel')],
                ],
                'highlights' => [
                    ['label' => __('super_admin/settings.metrics.invoices'), 'value' => $stats['invoices'], 'hint' => __('super_admin/settings.billing.paid_invoices', ['count' => $stats['paidInvoices']])],
                    ['label' => __('super_admin/settings.billing.reconcile'), 'value' => __('super_admin/settings.billing.manual_verify'), 'hint' => __('super_admin/settings.billing.reconcile_hint')],
                ],
                'actions' => [
                    [
                        'title' => __('super_admin/settings.actions.manage_billing.title'),
                        'desc' => __('super_admin/settings.actions.manage_billing.desc'),
                        'url' => route('invoices.index'),
                    ],
                    [
                        'title' => __('super_admin/settings.actions.subscription_list.title'),
                        'desc' => __('super_admin/settings.actions.subscription_list.desc'),
                        'url' => route('langganan.index'),
                    ],
                ],
            ],
            'staff' => [
                'title' => __('super_admin/settings.sections.staff.title'),
                'subtitle' => __('super_admin/settings.sections.staff.subtitle'),
                'badge' => __('super_admin/settings.sections.staff.badge', ['count' => $stats['staff']]),
                'details' => [
                    ['label' => __('super_admin/settings.metrics.staff'), 'value' => $stats['staff']],
                    ['label' => __('super_admin/settings.staff.access_level'), 'value' => __('super_admin/settings.staff.admin_only')],
                    ['label' => __('super_admin/settings.staff.security'), 'value' => __('super_admin/settings.staff.security_hint')],
                    ['label' => __('super_admin/settings.staff.status'), 'value' => __('super_admin/settings.sidebar.active')],
                ],
                'features' => [
                    ['label' => __('super_admin/settings.actions.manage_staff.title'), 'desc' => __('super_admin/settings.actions.manage_staff.desc'), 'url' => route('superadmin.staff.index')],
                ],
                'highlights' => [
                    ['label' => __('super_admin/settings.metrics.staff'), 'value' => $stats['staff'], 'hint' => __('super_admin/settings.staff.note')],
                    ['label' => __('super_admin/settings.staff.security'), 'value' => __('super_admin/settings.staff.admin_only'), 'hint' => __('super_admin/settings.staff.security_hint')],
                ],
                'actions' => [
                    [
                        'title' => __('super_admin/settings.actions.manage_staff.title'),
                        'desc' => __('super_admin/settings.actions.manage_staff.desc'),
                        'url' => route('superadmin.staff.index'),
                    ],
                ],
            ],
            'reports' => [
                'title' => __('super_admin/settings.sections.reports.title'),
                'subtitle' => __('super_admin/settings.sections.reports.subtitle'),
                'badge' => __('super_admin/settings.sections.reports.badge'),
                'details' => [
                    ['label' => __('super_admin/settings.reports.current_scope'), 'value' => __('super_admin/settings.reports.company_scope')],
                    ['label' => __('super_admin/settings.reports.exports_label'), 'value' => __('super_admin/settings.reports.pdf_excel')],
                    ['label' => __('super_admin/settings.reports.pdf_export'), 'value' => __('super_admin/settings.actions.export_pdf.title')],
                    ['label' => __('super_admin/settings.reports.excel_export'), 'value' => __('super_admin/settings.actions.export_excel.title')],
                ],
                'features' => [
                    ['label' => __('super_admin/settings.actions.open_reports.title'), 'desc' => __('super_admin/settings.actions.open_reports.desc'), 'url' => route('superadmin.stats.index')],
                    ['label' => __('super_admin/settings.actions.export_pdf.title'), 'desc' => __('super_admin/settings.actions.export_pdf.desc'), 'url' => route('superadmin.stats.export-pdf')],
                    ['label' => __('super_admin/settings.actions.export_excel.title'), 'desc' => __('super_admin/settings.actions.export_excel.desc'), 'url' => route('superadmin.stats.export-excel')],
                ],
                'highlights' => [
                    ['label' => __('super_admin/settings.reports.current_scope'), 'value' => __('super_admin/settings.reports.company_scope'), 'hint' => __('super_admin/settings.reports.company_scope_hint')],
                    ['label' => __('super_admin/settings.reports.exports'), 'value' => __('super_admin/settings.reports.pdf_excel'), 'hint' => __('super_admin/settings.reports.exports_hint')],
                ],
                'actions' => [
                    [
                        'title' => __('super_admin/settings.actions.open_reports.title'),
                        'desc' => __('super_admin/settings.actions.open_reports.desc'),
                        'url' => route('superadmin.stats.index'),
                    ],
                    [
                        'title' => __('super_admin/settings.actions.export_pdf.title'),
                        'desc' => __('super_admin/settings.actions.export_pdf.desc'),
                        'url' => route('superadmin.stats.export-pdf'),
                    ],
                    [
                        'title' => __('super_admin/settings.actions.export_excel.title'),
                        'desc' => __('super_admin/settings.actions.export_excel.desc'),
                        'url' => route('superadmin.stats.export-excel'),
                    ],
                ],
            ],
            'communications' => [
                'title' => __('super_admin/settings.sections.communications.title'),
                'subtitle' => __('super_admin/settings.sections.communications.subtitle'),
                'badge' => __('super_admin/settings.sections.communications.badge', ['count' => $stats['announcements']]),
                'details' => [
                    ['label' => __('super_admin/settings.metrics.announcements_label'), 'value' => $stats['announcements']],
                    ['label' => __('super_admin/settings.communications.channel'), 'value' => __('super_admin/settings.communications.broadcasts')],
                    ['label' => __('super_admin/settings.communications.audience'), 'value' => __('super_admin/settings.communications.broadcast_scope')],
                    ['label' => __('super_admin/settings.communications.status'), 'value' => __('super_admin/settings.sidebar.active')],
                ],
                'features' => [
                    ['label' => __('super_admin/settings.actions.open_communications.title'), 'desc' => __('super_admin/settings.actions.open_communications.desc'), 'url' => route('broadcasts.index')],
                ],
                'highlights' => [
                    ['label' => __('super_admin/settings.metrics.announcements'), 'value' => $stats['announcements'], 'hint' => __('super_admin/settings.communications.broadcast_scope')],
                    ['label' => __('super_admin/settings.communications.channel'), 'value' => __('super_admin/settings.communications.broadcasts'), 'hint' => __('super_admin/settings.communications.channel_hint')],
                ],
                'actions' => [
                    [
                        'title' => __('super_admin/settings.actions.open_communications.title'),
                        'desc' => __('super_admin/settings.actions.open_communications.desc'),
                        'url' => route('broadcasts.index'),
                    ],
                ],
            ],
        ];

        $allowedSections = array_keys($sections);
        if (!in_array($section, $allowedSections, true)) {
            $section = 'overview';
        }

        return view('super-admin.settings.index', [
            'sections' => $sections,
            'currentSection' => $sections[$section],
            'currentSectionKey' => $section,
            'stats' => $stats,
        ]);
    }
}
