<?php

return [
    'title' => 'System Setting | EMindTutor',
    'heading' => 'System Setting',
    'intro' => 'Central control for the superadmin modules already available in EMindTutor.',
    'breadcrumb' => [
        'superadmin' => 'Super Admin',
        'home' => 'Home',
        'settings' => 'Settings',
    ],
    'sidebar' => [
        'title' => 'Settings Hub',
        'subtitle' => 'Pick a module area to manage.',
        'section_hint' => 'Linked to existing superadmin features',
        'active' => 'Active',
    ],
    'overview' => [
        'app' => 'Application',
        'app_label' => 'Application',
        'locale' => 'Locale: :locale',
        'locale_label' => 'Locale',
    ],
    'sections' => [
        'overview' => [
            'title' => 'Overview',
            'subtitle' => 'A quick view of the active system controls and module shortcuts.',
            'badge' => 'System hub',
        ],
        'companies' => [
            'title' => 'Companies',
            'subtitle' => 'Manage company records, activation status, and login-as access.',
            'badge' => 'Company control',
        ],
        'packages' => [
            'title' => 'Packages & Subscription',
            'subtitle' => 'Maintain subscription packages and the trial toggle used by company access.',
            'badge' => 'Package control',
        ],
        'billing' => [
            'title' => 'Billing & Invoices',
            'subtitle' => 'Monitor invoices, payment confirmation, and receipt access.',
            'badge' => 'Billing control',
        ],
        'staff' => [
            'title' => 'Superadmin Staff',
            'subtitle' => 'Manage EMindTutor internal staff accounts and access records.',
            'badge' => 'Staff control',
        ],
        'reports' => [
            'title' => 'Reports & Analytics',
            'subtitle' => 'Open reports, charts, and export tools for monitoring the system.',
            'badge' => 'Report control',
        ],
        'communications' => [
            'title' => 'Announcements & Broadcasts',
            'subtitle' => 'Use the communication tools that already exist in the platform.',
            'badge' => 'Communication control',
        ],
    ],
    'metrics' => [
        'companies' => 'Companies',
        'active_companies' => ':count active companies',
        'active_companies_label' => 'Active Companies',
        'packages' => 'Packages',
        'trial_package' => 'Trial package: :name',
        'invoices' => 'Invoices',
        'paid_invoices' => ':count paid invoices',
        'paid_invoices_label' => 'Paid Invoices',
        'staff' => 'Staff',
        'announcements' => ':count announcements',
        'announcements_label' => 'Announcements',
    ],
    'company' => [
        'controls' => 'Company controls',
        'login_as' => 'Login-as support',
        'enabled' => 'Enabled',
        'status' => 'Status',
        'login_as_hint' => 'Switch into a company account for support and troubleshooting.',
    ],
    'package' => [
        'trial' => 'Current trial package: :name',
        'trial_label' => 'Trial Package',
        'subscription_toggle' => 'Subscription toggle',
        'enabled' => 'Enabled',
        'status' => 'Status',
        'subscription_toggle_hint' => 'Controlled by the existing subscription toggle module.',
    ],
    'billing' => [
        'reconcile' => 'Payment reconciliation',
        'manual_verify' => 'Manual verification',
        'receipt_access' => 'Receipt Access',
        'reconcile_hint' => 'Keep invoices and receipts aligned before marking them paid.',
        'paid_invoices' => ':count paid invoices',
    ],
    'staff' => [
        'note' => 'Internal staff account data',
        'security' => 'Access scope',
        'access_level' => 'Access level',
        'admin_only' => 'Superadmin only',
        'status' => 'Status',
        'security_hint' => 'Use this module to manage internal operator accounts.',
    ],
    'reports' => [
        'current_scope' => 'Report scope',
        'company_scope' => 'Company-level overview',
        'company_scope_hint' => 'The current reports module already aggregates company data.',
        'exports' => 'Exports',
        'exports_label' => 'Exports',
        'pdf_excel' => 'PDF and Excel',
        'pdf_export' => 'PDF Export',
        'excel_export' => 'Excel Export',
        'exports_hint' => 'Exports are already available in the reports module.',
    ],
    'communications' => [
        'broadcast_scope' => 'Broadcasts and announcements already connected to users.',
        'channel' => 'Channel',
        'broadcasts' => 'Broadcast module',
        'audience' => 'Audience',
        'status' => 'Status',
        'channel_hint' => 'Use it for announcements and direct communication.',
    ],
    'actions' => [
        'manage_companies' => [
            'title' => 'Manage Companies',
            'desc' => 'Open company listing, activation, and login-as actions.',
        ],
        'create_company' => [
            'title' => 'Create Company',
            'desc' => 'Register a new company from the current superadmin flow.',
        ],
        'manage_packages' => [
            'title' => 'Manage Packages',
            'desc' => 'Add or adjust subscription packages and limits.',
        ],
        'manage_subscription_toggle' => [
            'title' => 'Subscription Toggle',
            'desc' => 'Control the trial and active toggle for subscriptions.',
        ],
        'manage_billing' => [
            'title' => 'Manage Billing',
            'desc' => 'Review invoices and confirm payment manually when needed.',
        ],
        'subscription_list' => [
            'title' => 'Subscription List',
            'desc' => 'Open the active subscription toggle overview.',
        ],
        'manage_staff' => [
            'title' => 'Manage Staff',
            'desc' => 'View and maintain EMindTutor internal staff accounts.',
        ],
        'open_reports' => [
            'title' => 'Open Reports',
            'desc' => 'Go to charts, statistics, and exported reports.',
        ],
        'export_pdf' => [
            'title' => 'Export PDF',
            'desc' => 'Download the current statistics report as PDF.',
        ],
        'export_excel' => [
            'title' => 'Export Excel',
            'desc' => 'Download the current statistics report as Excel.',
        ],
        'open_communications' => [
            'title' => 'Open Broadcasts',
            'desc' => 'Send announcements and communication messages.',
        ],
    ],
    'quick_links' => [
        'title' => 'Quick Links',
        'companies' => 'Companies',
        'companies_desc' => 'Open the company management screen.',
        'billing' => 'Billing',
        'billing_desc' => 'Open invoices and payment verification.',
        'reports' => 'Reports',
        'reports_desc' => 'Open charts and system analytics.',
        'open' => 'Open',
    ],
    'features' => [
        'title' => 'Features Access',
        'subtitle' => 'These are the superadmin modules linked to the selected section.',
    ],
    'bottom' => [
        'title' => 'Control Center Notes',
        'desc' => 'This page is intentionally tied to modules that already exist in EMindTutor so superadmins can reach company, billing, reporting, staff, and broadcast controls from one place.',
        'active_companies' => 'Active companies',
        'paid_invoices' => 'Paid invoices',
    ],
];
