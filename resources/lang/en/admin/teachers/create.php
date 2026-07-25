<?php
return [
    'header' => [
        'title' => 'Add New Teacher',
        'subtitle' => 'Fill in complete information to register a new teacher',
    ],

    'errors' => [
        'title' => 'There are errors in the form:',
    ],

    'sections' => [
        'personal_information' => 'Personal Information',
        'allowance_information' => 'Allowance Information',
        'bank_information' => 'Bank Information',
    ],

    'fields' => [
        'full_name' => 'Full Name',
        'email' => 'Email Address',
        'phone' => 'Phone Number',
        'ic_number' => 'IC Number',
        'allowance_rate' => 'Allowance Rate (RM)',
        'allowance_type' => 'Allowance Type',
        'bank_name' => 'Bank Name',
        'bank_account_number' => 'Bank Account Number',
        'bank_account_holder' => 'Account Holder Name',
        'account_holders_ic_number' => 'Account Holder IC Number',
    ],

    'placeholders' => [
        'full_name' => 'Enter full name',
        'email' => 'example@email.com',
        'phone' => '60XXXXXXXX',
        'ic_number' => 'XXXXXXXXXXXX',
        'allowance_rate' => '0.00',
        'bank_name' => 'E.g.: Bank Islam Malaysia Berhad',
        'bank_account_number' => '1234567890',
        'bank_account_holder' => 'Name as in bank',
        'account_holders_ic_number' => 'XXXXXXXXXXXX (optional)',
    ],

    'notes' => [
        'ic_used_password' => 'Used as initial password',
        'same_as_teacher_ic' => 'Leave empty if same as teacher IC',
    ],

    'buttons' => [
        'cancel' => 'Cancel',
        'save_teacher' => 'Save Teacher',
    ],

    'selects' => [
        'choose_allowance_type' => 'Select allowance type',
        'per_session' => 'Per Session',
        'per_hour' => 'Per Hour',
        'per_month' => 'Per Month',
    ],
];
