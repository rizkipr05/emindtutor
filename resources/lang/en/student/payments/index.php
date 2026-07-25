<?php
return [
    'title' => 'Payment Invoices',
    'subtitle' => 'Manage and pay your payment invoices',

    'tabs' => [
        'upcoming' => 'Upcoming Invoices',
        'paid' => 'Paid Invoices',
    ],

    'summary_upcoming_title' => 'Upcoming Invoices Summary',
    'summary' => [
        'total_invoices' => 'Total Invoices',
        'total_amount' => 'Total Amount',
        'overdue' => 'Overdue',
    ],

    'invoice' => [
        'number' => 'Invoice #:number',
        'default_description' => 'School payment',
        'status' => [
            'pending' => 'Pending verification',
            'success' => 'Paid',
            'failed' => 'Failed',
            'unpaid' => 'Unpaid',
        ],
        'labels' => [
            'amount' => 'Invoice Amount:',
            'due_date' => 'Due Date:',
            'period' => 'Period:',
        ],
        'actions' => [
            'pay_now' => 'Pay Now',
            'paid_or_pending' => 'Paid / Pending Verification',
        ],
        'overdue_notice' => 'This invoice has passed its due date',
    ],

    'empty' => [
        'upcoming_title' => 'No Upcoming Invoices',
        'upcoming_desc' => 'There are currently no invoices that require payment.',
        'paid_title' => 'No Payments Yet',
        'paid_desc' => 'Payment history will appear here after you make a payment.',
    ],

    // small labels
    'status_paid' => 'Paid',
    'status_waiting' => 'Waiting',
    'label_paid_total' => 'Total Paid',
    'label_total_amount' => 'Total Amount',
    
    'months' => [
        '1' => 'January',
        '2' => 'February',
        '3' => 'March',
        '4' => 'April',
        '5' => 'May',
        '6' => 'June',
        '7' => 'July',
        '8' => 'August',
        '9' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December',
    ],
];
