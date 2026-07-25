<?php
return [
    'header' => [
        'title' => 'Tambah Guru Baru',
        'subtitle' => 'Isi maklumat lengkap untuk mendaftar guru baharu',
    ],

    'errors' => [
        'title' => 'Terdapat ralat dalam borang:',
    ],

    'sections' => [
        'personal_information' => 'Maklumat Peribadi',
        'allowance_information' => 'Maklumat Elaun',
        'bank_information' => 'Maklumat Bank',
    ],

    'fields' => [
        'full_name' => 'Nama Penuh',
        'email' => 'Alamat Email',
        'phone' => 'No. Telefon',
        'ic_number' => 'No. IC',
        'allowance_rate' => 'Kadar Elaun (RM)',
        'allowance_type' => 'Jenis Elaun',
        'bank_name' => 'Nama Bank',
        'bank_account_number' => 'Nombor Akaun Bank',
        'bank_account_holder' => 'Nama Pemegang Akaun',
        'account_holders_ic_number' => 'No. IC Pemegang Akaun',
    ],

    'placeholders' => [
        'full_name' => 'Masukkan nama penuh',
        'email' => 'contoh@email.com',
        'phone' => '60XXXXXXXX',
        'ic_number' => 'XXXXXXXXXXXX',
        'allowance_rate' => '0.00',
        'bank_name' => 'Contoh: Bank Islam Malaysia Berhad',
        'bank_account_number' => '1234567890',
        'bank_account_holder' => 'Nama seperti dalam bank',
        'account_holders_ic_number' => 'XXXXXXXXXXXX (opsional)',
    ],

    'notes' => [
        'ic_used_password' => 'Digunakan sebagai password awal',
        'same_as_teacher_ic' => 'Kosongkan jika sama dengan IC guru',
    ],

    'buttons' => [
        'cancel' => 'Batal',
        'save_teacher' => 'Simpan Guru',
    ],

    'selects' => [
        'choose_allowance_type' => 'Pilih jenis elaun',
        'per_session' => 'Per Sesi',
        'per_hour' => 'Per Jam',
        'per_month' => 'Per Bulan',
    ],
];
