<?php

return [
    'title' => 'System Setting | EMindTutor',
    'heading' => 'System Setting',
    'intro' => 'Pusat kawalan untuk modul superadmin yang memang sudah tersedia dalam EMindTutor.',
    'breadcrumb' => [
        'superadmin' => 'Super Admin',
        'home' => 'Home',
        'settings' => 'Settings',
    ],
    'sidebar' => [
        'title' => 'Hab Pengaturan',
        'subtitle' => 'Pilih bahagian modul yang ingin dikawal.',
        'section_hint' => 'Terhubung dengan fitur superadmin sedia ada',
        'active' => 'Aktif',
    ],
    'overview' => [
        'app' => 'Aplikasi',
        'app_label' => 'Aplikasi',
        'locale' => 'Bahasa: :locale',
        'locale_label' => 'Bahasa',
    ],
    'sections' => [
        'overview' => [
            'title' => 'Ringkasan',
            'subtitle' => 'Gambaran pantas kawalan sistem dan pintasan modul.',
            'badge' => 'Hab sistem',
        ],
        'companies' => [
            'title' => 'Syarikat',
            'subtitle' => 'Urus rekod syarikat, status aktif, dan akses login-as.',
            'badge' => 'Kawalan syarikat',
        ],
        'packages' => [
            'title' => 'Pakej & Langganan',
            'subtitle' => 'Kekalkan pakej langganan dan suis percubaan yang dipakai oleh akses syarikat.',
            'badge' => 'Kawalan pakej',
        ],
        'billing' => [
            'title' => 'Bil & Invois',
            'subtitle' => 'Pantau invois, pengesahan bayaran, dan akses resit.',
            'badge' => 'Kawalan bil',
        ],
        'staff' => [
            'title' => 'Staf Superadmin',
            'subtitle' => 'Urus akaun staf dalaman EMindTutor dan rekod akses.',
            'badge' => 'Kawalan staf',
        ],
        'reports' => [
            'title' => 'Laporan & Analitik',
            'subtitle' => 'Buka laporan, carta, dan alat eksport untuk pemantauan sistem.',
            'badge' => 'Kawalan laporan',
        ],
        'communications' => [
            'title' => 'Hebahan & Broadcast',
            'subtitle' => 'Gunakan alat komunikasi yang memang sudah ada dalam platform.',
            'badge' => 'Kawalan komunikasi',
        ],
    ],
    'metrics' => [
        'companies' => 'Syarikat',
        'active_companies' => ':count syarikat aktif',
        'active_companies_label' => 'Syarikat Aktif',
        'packages' => 'Pakej',
        'trial_package' => 'Pakej percubaan: :name',
        'invoices' => 'Invois',
        'paid_invoices' => ':count invois dibayar',
        'paid_invoices_label' => 'Invois Dibayar',
        'staff' => 'Staf',
        'announcements' => ':count pengumuman',
        'announcements_label' => 'Pengumuman',
    ],
    'company' => [
        'controls' => 'Kawalan syarikat',
        'login_as' => 'Sokongan login-as',
        'enabled' => 'Diaktifkan',
        'status' => 'Status',
        'login_as_hint' => 'Tukar masuk ke akaun syarikat untuk sokongan dan troubleshooting.',
    ],
    'package' => [
        'trial' => 'Pakej percubaan semasa: :name',
        'trial_label' => 'Pakej Percubaan',
        'subscription_toggle' => 'Suis langganan',
        'enabled' => 'Diaktifkan',
        'status' => 'Status',
        'subscription_toggle_hint' => 'Dikawal oleh modul suis langganan yang sedia ada.',
    ],
    'billing' => [
        'reconcile' => 'Penyelarasan bayaran',
        'manual_verify' => 'Sahkan secara manual',
        'receipt_access' => 'Akses Resit',
        'reconcile_hint' => 'Pastikan invois dan resit selaras sebelum ditandakan dibayar.',
        'paid_invoices' => ':count invois dibayar',
    ],
    'staff' => [
        'note' => 'Data akaun staf dalaman',
        'security' => 'Skop akses',
        'access_level' => 'Tahap akses',
        'admin_only' => 'Superadmin sahaja',
        'status' => 'Status',
        'security_hint' => 'Gunakan modul ini untuk mengurus akaun operator dalaman.',
    ],
    'reports' => [
        'current_scope' => 'Skop laporan',
        'company_scope' => 'Gambaran peringkat syarikat',
        'company_scope_hint' => 'Modul laporan kini sudah mengumpulkan data syarikat.',
        'exports' => 'Eksport',
        'exports_label' => 'Eksport',
        'pdf_excel' => 'PDF dan Excel',
        'pdf_export' => 'Eksport PDF',
        'excel_export' => 'Eksport Excel',
        'exports_hint' => 'Eksport PDF dan Excel sudah tersedia dalam modul laporan.',
    ],
    'communications' => [
        'broadcast_scope' => 'Broadcast dan pengumuman sudah terhubung dengan pengguna.',
        'channel' => 'Saluran',
        'broadcasts' => 'Modul broadcast',
        'audience' => 'Sasaran',
        'status' => 'Status',
        'channel_hint' => 'Gunakan untuk hebahan dan komunikasi langsung.',
    ],
    'actions' => [
        'manage_companies' => [
            'title' => 'Urus Syarikat',
            'desc' => 'Buka senarai syarikat, status aktif, dan tindakan login-as.',
        ],
        'create_company' => [
            'title' => 'Tambah Syarikat',
            'desc' => 'Daftarkan syarikat baru dari aliran superadmin semasa.',
        ],
        'manage_packages' => [
            'title' => 'Urus Pakej',
            'desc' => 'Tambah atau ubah pakej langganan dan hadnya.',
        ],
        'manage_subscription_toggle' => [
            'title' => 'Suis Langganan',
            'desc' => 'Kawal toggle percubaan dan langganan aktif.',
        ],
        'manage_billing' => [
            'title' => 'Urus Bil',
            'desc' => 'Semak invois dan sahkan bayaran secara manual bila perlu.',
        ],
        'subscription_list' => [
            'title' => 'Senarai Langganan',
            'desc' => 'Buka ringkasan suis langganan yang aktif.',
        ],
        'manage_staff' => [
            'title' => 'Urus Staf',
            'desc' => 'Lihat dan urus akaun staf dalaman EMindTutor.',
        ],
        'open_reports' => [
            'title' => 'Buka Laporan',
            'desc' => 'Pergi ke carta, statistik, dan laporan eksport.',
        ],
        'export_pdf' => [
            'title' => 'Eksport PDF',
            'desc' => 'Muat turun laporan statistik semasa dalam PDF.',
        ],
        'export_excel' => [
            'title' => 'Eksport Excel',
            'desc' => 'Muat turun laporan statistik semasa dalam Excel.',
        ],
        'open_communications' => [
            'title' => 'Buka Broadcast',
            'desc' => 'Hantar pengumuman dan mesej komunikasi.',
        ],
    ],
    'quick_links' => [
        'title' => 'Pautan Pantas',
        'companies' => 'Syarikat',
        'companies_desc' => 'Buka skrin pengurusan syarikat.',
        'billing' => 'Bil',
        'billing_desc' => 'Buka invois dan pengesahan bayaran.',
        'reports' => 'Laporan',
        'reports_desc' => 'Buka carta dan analitik sistem.',
        'open' => 'Buka',
    ],
    'features' => [
        'title' => 'Akses Fitur',
        'subtitle' => 'Ini ialah modul superadmin yang dihubungkan dengan bahagian yang dipilih.',
    ],
    'bottom' => [
        'title' => 'Catatan Pusat Kawalan',
        'desc' => 'Halaman ini sengaja disambungkan kepada modul yang sudah wujud dalam EMindTutor supaya superadmin boleh capai kawalan syarikat, bil, laporan, staf, dan hebahan dari satu tempat.',
        'active_companies' => 'Syarikat aktif',
        'paid_invoices' => 'Invois dibayar',
    ],
];
