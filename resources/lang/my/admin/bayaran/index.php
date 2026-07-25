<?php

return [
    'title' => 'Senarai Bayaran & Yuran',
    'subtitle' => 'Urus dan pantau semua pembayaran pelajar',

    'stats' => [
        'paid' => 'Telah Dibayar',
        'pending' => 'Menunggu Pengesahan',
        'unpaid' => 'Belum Dibayar',
    ],

    'section' => [
        'paid' => [
            'title' => 'Telah Dibayar',
            'desc' => 'Invois yang telah berjaya dibayar dan disahkan',
            'empty' => 'Tiada data pembayaran yang telah diselesaikan',
        ],
        'upcoming' => [
            'title' => 'Menunggu Pembayaran',
            'desc' => 'Invois yang belum dibayar oleh pelajar',
            'empty' => 'Tiada invois yang menunggu pembayaran',
        ],
        'pending' => [
            'title' => 'Menunggu Pengesahan',
            'desc' => 'Invois yang telah dibayar dan perlu disahkan oleh pentadbir',
            'empty' => 'Tiada pembayaran yang menunggu pengesahan',
        ],
    ],

    'table' => [
        'invoice_number' => 'Nombor Invois',
        'student' => 'Pelajar',
        'course' => 'Kursus',
        'amount' => 'Jumlah',
        'status' => 'Status',
        'action' => 'Tindakan',
    ],

    'badge' => [
        'paid' => 'Lunas',
        'unpaid' => 'Belum Dibayar',
        'pending' => 'Menunggu',
    ],

    'action' => [
        'verify' => 'Sahkan',
    ],

    'sub_title' => [
        'sudah_bayar' => 'Sudah Bayar',
        'menunggu_bayar' => 'Menunggu Pembayaran',
        'menunggu_verifikasi' => 'Menunggu Pengesahan Admin'
    ]
];
