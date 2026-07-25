<?php

return [
    // Tabs
    'tabs' => [
        'manual' => 'Input Manual',
        'import' => 'Import Excel',
    ],

    // Manual input section
    'manual' => [
        'header' => 'Tambah Pelajar',
        'subheader' => 'Pilih kaedah untuk menambah pelajar baru',
        'errors_title' => 'Terdapat beberapa ralat:',

        'fields' => [
            'name' => ['label' => 'Nama Pelajar', 'placeholder' => 'Masukkan nama penuh'],
            'email' => ['label' => 'Email', 'placeholder' => 'contoh@email.com'],
            'ic_number' => ['label' => 'Nombor IC', 'placeholder' => '123456789012'],
            'phone' => ['label' => 'No Telefon', 'placeholder' => '08123456789'],
            'sponsor_name' => ['label' => 'Nama Penaja (pilihan)', 'placeholder' => 'Nama penaja (pilihan)'],
            'sponsor_phone' => ['label' => 'Telefon Penaja (pilihan)', 'placeholder' => 'Telefon penaja (pilihan)'],
            'sponsor_email' => ['label' => 'Email Penaja (pilihan)', 'placeholder' => 'Email penaja (pilihan)'],
            'address' => ['label' => 'Alamat', 'placeholder' => 'Masukkan alamat penuh (pilihan)'],
            'course' => ['label' => 'Kursus', 'placeholder' => '-- Pilih Kursus --'],
        ],

        'buttons' => [
            'cancel' => 'Batal',
            'save' => 'Simpan Pelajar',
        ],
    ],

    // Import section
    'import' => [
    'header' => 'Import Pelajar (Excel)',
    'description' => 'Muat naik fail Excel untuk mengimport pelajar secara berkumpulan.',
    'file_label' => 'Muat naik fail Excel',
    'template' => 'Template Excel',
    'template_desc' => 'Fail Excel mesti mengandungi lajur: :cols',
    'course_note' => 'Kursus akan ditetapkan berdasarkan pilihan di atas.',
    'download' => 'Muat turun Template Excel',
        'course_block' => [
            'title' => 'Pilih Kursus untuk Semua Pelajar',
            'desc' => 'Semua pelajar yang diimport dari Excel akan didaftarkan ke Kursus yang dipilih',
        ],
        'buttons' => [
            'cancel' => 'Batal',
            'import' => 'Import Excel',
        ],
    ],

    // generic messages
    'success' => 'Operasi berjaya',
];
