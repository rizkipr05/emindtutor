<?php
return [
    'title' => 'Add Payment',
    'subtitle' => 'Upload your proof of payment for the invoice',

    'breadcrumb' => [
        'payments' => 'Payments',
        'current' => 'Add Payment',
    ],

    'error_title' => 'An error occurred!',

    'form' => [
        'header' => 'Payment Form',
        'subtitle' => 'Complete your payment information',
    ],

    'invoice' => [
        'info_label' => 'Invoice Information',
        'no_course' => 'No Course',
        'helper' => 'This invoice will be used for your payment',
    ],

    'bank_info' => [
        'title' => 'Recipient Bank Account Information',
        'copy_button' => 'Copy account number',
        'empty' => 'No bank information available. Please contact the company.',
        'copy_success' => 'Account number copied successfully!',
    ],

    'date' => [
        'label' => 'Payment Date',
        'helper' => 'Select the date when you made the payment',
    ],

    'upload' => [
        'label' => 'Proof of Payment',
        'button' => 'Upload file',
        'or' => 'or drag and drop',
        'hint' => 'JPG, JPEG, PNG, PDF up to 2MB',
    ],

    'tips' => [
        'title' => 'Tip:',
        'content' => 'Make sure the proof of payment is clear and legible. Blurry or unclear files will be rejected.',
    ],

    'note' => 'Make sure all data is correct before saving',

    'buttons' => [
        'back' => 'Back',
        'submit' => 'Save Payment',
    ],

    'footer' => [
        'title' => 'Important!',
        'content' => 'After submitting proof of payment, your payment status will change to "Pending" and wait for admin verification. Verification usually takes 1-2 business days.',
    ],
];
