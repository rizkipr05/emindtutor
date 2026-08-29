<?php

return [
    'title' => 'Rekod Akaun Pelajar',
    'back' => 'Kembali ke Maklumat Pelajar',
    'subtitle' => 'Ringkasan invois dan pembayaran pelajar.',
    'summary' => [
        'current_balance' => 'Baki Semasa',
        'student_credit' => 'Kredit pelajar',
        'balance_due' => 'Baki perlu dibayar',
        'total_invoices' => 'Jumlah Invois',
        'total_payments' => 'Jumlah Pembayaran',
        'account_balance' => 'Baki Akaun',
    ],
    'invoices' => ['title' => 'Invois Dikeluarkan'],
    'form' => [
        'course' => 'Kursus', 'select_course' => 'Pilih kursus', 'amount' => 'Jumlah (RM)',
        'start_date' => 'Tarikh mula', 'end_date' => 'Tarikh tamat', 'status' => 'Status',
    ],
    'actions' => ['create_manual_invoice' => 'Buat Invois Manual', 'save' => 'Jana & Simpan'],
    'invoice_status' => ['paid' => 'Dibayar', 'upcoming' => 'Akan Datang', 'overdue' => 'Tertunggak'],
    'payment_status' => ['pending' => 'Menunggu pengesahan'],
    'table' => [
        'date' => 'Tarikh', 'invoice_number' => 'No. Invois', 'course' => 'Kursus', 'amount' => 'Jumlah',
        'status' => 'Status', 'reference' => 'Rujukan', 'method_proof' => 'Kaedah / Bukti',
    ],
    'payment_history' => ['title' => 'Sejarah Pembayaran', 'proof' => 'Bukti pembayaran', 'manual_note' => 'Catatan manual'],
    'timeline' => ['title' => 'Aliran Akaun', 'invoice' => 'Invois'],
    'empty' => ['invoices' => 'Tiada invois direkodkan.', 'payments' => 'Tiada pembayaran berjaya direkodkan.', 'transactions' => 'Belum ada rekod transaksi.'],
    'messages' => ['manual_invoice_created' => 'Invois manual pelajar berjaya dibuat.'],
];