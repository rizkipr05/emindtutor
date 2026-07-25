<?php

return [
    'page_title' => 'Laporan Digital Payroll',
    'header_title' => '📊 Laporan Digital Payroll',
    'teacher_info' => ':name - Nombor IC: :ic',
    'company_payroll_record' => 'Rekod Pembayaran Guru',
    'letter_head_title' => 'Letter Head',
    'self_detail_title' => 'Detail Diri',
    'learning_detail_title' => 'Detail Pembelajaran',
    'company_name' => 'Nama Company',
    'company_registration' => 'Pendaftaran Company',
    'company_address' => 'Alamat Company',
    'name' => 'Nama',
    'ic_number' => 'No Kad Pengenalan',
    'payslip_date' => 'Payslip Date',
    'payroll_period' => 'Payroll Period',
    'teacher_profile_title' => 'Profil Guru',
    'company_profile_title' => 'Profil Company',
    'payment_summary_title' => 'Ringkasan Payroll',
    'payslip_title' => 'Slip Gaji Guru',
    'generated_at' => 'Tarikh Cetakan',
    'net_salary' => 'Gaji Bersih',
    'class_count' => 'Jumlah Pelajar',
    'not_available' => 'Tidak ada',
    'filter_status_label' => 'Status',
    'class_type' => [
        'individu' => 'Individu',
        'kumpulan' => 'Kumpulan',
    ],
    'deductions' => 'Total Potongan',
    'status_deductions' => [
        'paid' => 'Sudah Dipotong',
        'pending' => 'Belum Dipotong',
    ],

    //Months
    'months' => [
        'january' => 'Januari',
        'february' => 'Februari',
        'march' => 'Mac',
        'april' => 'April',
        'may' => 'Mei',
        'june' => 'Jun',
        'july' => 'Julai',
        'august' => 'Ogos',
        'september' => 'September',
        'october' => 'Oktober',
        'november' => 'November',
        'december' => 'Disember',
    ],

    // Filters
    'filter' => [
        'month' => 'Bulan',
        'year' => 'Tahun',
        'button' => 'Filter',
    ],

    // Summary cards
    'summary' => [
        'total_classes' => 'Jumlah Kelas Diajar',
        'total_teaching_salary' => 'Total Gaji Mengajar',
        'total_bonus' => 'Total Bonus',
        'total_deductions' => 'Total Potongan',
    ],

    // Detail sections
    'detail' => [
        'teaching_title' => 'Butiran Gaji Mengajar',
        'bonus_title' => 'Butiran Bonus',
        'deduction_title' => 'Butiran Potongan',
        'summary_title' => 'Ringkasan Total & Muat Turun',
    ],

    // Table headers
    'table' => [
        'subject' => 'Mata Pelajaran',
        'class' => 'Kelas',
        'salary' => 'Gaji',
        'status' => 'Status',
        'description' => 'Keterangan',
        'month' => 'Bulan',
        'amount' => 'Jumlah',
        'type' => 'Jenis',
        'day_time' => 'Hari & Waktu',
        'sessions' => 'Jumlah Sesi',
        'day' => 'Hari',
        'time' => 'Waktu',
    ],

    // Totals
    'totals' => [
        'total_teaching_salary' => 'Total Gaji Mengajar',
        'total_bonus' => 'Total Bonus',
        'total_deductions' => 'Total Potongan',
        'net_total' => 'TOTAL BERSIH',
    ],

    // Download / status
    'download' => 'Cetak PDF Slip Gaji',
    'preview' => 'Lihat PDF',
    'customize' => 'Kustom PDF Slip Gaji',
    'customize_back' => 'Kembali ke Slip Gaji',
    'customize_desc' => 'Edit isi slip gaji untuk periode :month :year, lalu lihat atau muat turun PDF.',
    'customize_button' => 'Kustom PDF Slip Gaji',
    'download_disabled' => 'Tiada data gaji untuk tempoh yang dipilih',
    'status_partial' => 'Sebagian Belum Dibayar',
    'status_all_paid' => 'Semua Lunas',
    'no_data' => 'Tidak ada data gaji untuk periode ini',

    'customize_fields' => [
        'company_name' => 'Nama Syarikat',
        'company_registration' => 'No. Pendaftaran',
        'company_address' => 'Alamat Syarikat',
        'employee_name' => 'Nama Pekerja',
        'employee_nric' => 'NRIC',
        'bank_name' => 'Bank',
        'bank_account_number' => 'No. Akaun Bank',
        'outlet' => 'Outlet',
        'payslip_date' => 'Payslip Date',
        'payroll_period' => 'Payroll Period',
        'department' => 'Department',
        'position' => 'Position',
        'employee_ref' => 'Employee Ref',
    ],

    'customize_sections' => [
        'annual_leave' => 'Annual Leave',
        'medical_leave' => 'Medical Leave',
        'employer_contribution' => 'Employer Contribution',
    ],

    'customize_placeholders' => [
        'entitlement' => 'Entitlement',
        'earned_leave' => 'Earned Leave',
        'cf' => 'C/F',
        'taken' => 'Taken',
        'balance' => 'Balance',
        'epf' => 'EPF',
        'socso' => 'SOCSO',
        'eis' => 'EIS',
        'pcb' => 'PCB',
        'hrd' => 'HRD',
    ],

    // Bank info labels
    'bank' => [
        'name' => 'Nama Bank',
        'account_number' => 'No. Akaun Bank',
        'account_holder' => 'Pemegang Akaun',
        'account_holder_ic' => 'IC Pemegang Akaun',
    ],

    // Common
    'paid' => 'Lunas',
    'pending' => 'Belum Dibayar',
    'download_slip' => 'Muat Turun Slip Gaji Bulanan',
];
