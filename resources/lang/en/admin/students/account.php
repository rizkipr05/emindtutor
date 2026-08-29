<?php

return [
    'title' => 'Student Account Record',
    'back' => 'Back to Student Details',
    'subtitle' => 'Student invoice and payment summary.',
    'summary' => [
        'current_balance' => 'Current Balance',
        'student_credit' => 'Student credit',
        'balance_due' => 'Balance due',
        'total_invoices' => 'Total Invoices',
        'total_payments' => 'Total Payments',
        'account_balance' => 'Account Balance',
    ],
    'invoices' => ['title' => 'Issued Invoices'],
    'form' => [
        'course' => 'Course', 'select_course' => 'Select course', 'amount' => 'Amount (RM)',
        'start_date' => 'Start date', 'end_date' => 'End date', 'status' => 'Status',
    ],
    'actions' => ['create_manual_invoice' => 'Create Manual Invoice', 'save' => 'Generate & Save'],
    'invoice_status' => ['paid' => 'Paid', 'upcoming' => 'Upcoming', 'overdue' => 'Overdue'],
    'payment_status' => ['pending' => 'Pending verification'],
    'table' => [
        'date' => 'Date', 'invoice_number' => 'Invoice No.', 'course' => 'Course', 'amount' => 'Amount',
        'status' => 'Status', 'reference' => 'Reference', 'method_proof' => 'Method / Proof',
    ],
    'payment_history' => ['title' => 'Payment History', 'proof' => 'Payment proof', 'manual_note' => 'Manual note'],
    'timeline' => ['title' => 'Account Activity', 'invoice' => 'Invoice'],
    'empty' => ['invoices' => 'No invoices recorded.', 'payments' => 'No successful payments recorded.', 'transactions' => 'No transactions yet.'],
    'messages' => ['manual_invoice_created' => 'Manual student invoice has been created.'],
];