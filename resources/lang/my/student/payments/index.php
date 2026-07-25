<?php
return [
    'title' => 'Tagihan Pembayaran',
    'subtitle' => 'Urus dan bayar tagihan pembayaran anda',

    'tabs' => [
        'upcoming' => 'Tagihan Menunggu',
        'paid' => 'Sudah Dibayar',
    ],

    'summary_upcoming_title' => 'Ringkasan Tagihan Menunggu',
    'summary' => [
        'total_invoices' => 'Total Tagihan',
        'total_amount' => 'Total Jumlah',
        'overdue' => 'Terlambat',
    ],

    'invoice' => [
        'number' => 'Invoice #:number',
        'default_description' => 'Pembayaran Sekolah',
        'status' => [
            'pending' => 'Menunggu verifikasi',
            'success' => 'Lunas',
            'failed' => 'Gagal',
            'unpaid' => 'Belum dibayar',
        ],
        'labels' => [
            'amount' => 'Jumlah Tagihan:',
            'due_date' => 'Tanggal Jatuh Tempo:',
            'period' => 'Periode:',
        ],
        'actions' => [
            'pay_now' => 'Bayar Sekarang',
            'paid_or_pending' => 'Sudah Dibayar / Menunggu Verifikasi',
        ],
        'overdue_notice' => 'Tagihan sudah melewati tanggal jatuh tempo',
    ],

    'empty' => [
        'upcoming_title' => 'Tidak Ada Tagihan Menunggu',
        'upcoming_desc' => 'Saat ini tidak ada tagihan pembayaran yang perlu dibayar.',
        'paid_title' => 'Belum Ada Pembayaran',
        'paid_desc' => 'Riwayat pembayaran akan muncul di sini setelah Anda melakukan pembayaran.',
    ],

    // small labels
    'status_paid' => 'Lunas',
    'status_waiting' => 'Menunggu',
    'label_paid_total' => 'Total Dibayar',
    'label_total_amount' => 'Total Jumlah',

    'months' => [
        '1' => 'Januari',
        '2' => 'Februari',
        '3' => 'Mac',
        '4' => 'April',
        '5' => 'Mei',
        '6' => 'Jun',
        '7' => 'Julai',
        '8' => 'Ogos',
        '9' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Disember',
    ],

];
