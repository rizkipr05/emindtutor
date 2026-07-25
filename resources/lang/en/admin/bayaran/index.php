<?php

return [
    'title' => 'Payments & Fees',
    'subtitle' => 'Manage and monitor all student payments',

    'stats' => [
        'paid' => 'Paid',
        'pending' => 'Pending Verification',
        'unpaid' => 'Unpaid',
    ],

    'section' => [
        'paid' => [
            'title' => 'Paid',
            'desc' => 'Invoices that have been successfully paid and verified',
            'empty' => 'No paid payments found',
        ],
        'upcoming' => [
            'title' => 'Awaiting Payment',
            'desc' => 'Invoices that have not yet been paid by the student',
            'empty' => 'There are no invoices awaiting payment',
        ],
        'pending' => [
            'title' => 'Pending Verification',
            'desc' => 'Invoices that have been paid and require admin verification',
            'empty' => 'No payments pending verification',
        ],
    ],

    'table' => [
        'invoice_number' => 'Invoice Number',
        'student' => 'Student',
        'course' => 'Course',
        'amount' => 'Amount',
        'status' => 'Status',
        'action' => 'Action',
    ],

    'badge' => [
        'paid' => 'Paid',
        'unpaid' => 'Unpaid',
        'pending' => 'Pending',
    ],

    'action' => [
        'verify' => 'Verify',
    ],

    'sub_title' => [
        'sudah_bayar' => 'Already Paid',
        'menunggu_bayar' => 'Pending Payment',
        'menunggu_verifikasi' => 'Pending Admin Verification'
    ]
];
