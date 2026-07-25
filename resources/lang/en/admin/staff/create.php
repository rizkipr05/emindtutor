<?php

return [
    'title' => 'Add Staff',

    'sections' => [
        'personal' => 'Personal Information',
        'allowance' => 'Allowance Information',
        'bank' => 'Bank Information',
    ],

    'labels' => [
        'name' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'ic_number' => 'IC Number',
        'ic_helper' => 'IC Number will be used as the default password',

        'allowance' => 'Allowance',
        'allowance_type' => 'Allowance Type',
        'allowance_type_options' => [
            'choose' => 'Select Type',
            'per_hour' => 'Per Hour',
            'session' => 'Per Session',
            'per_month' => 'Per Month',
        ],

        'bank_name' => 'Bank Name',
        'bank_account_number' => 'Account Number',
        'bank_account_holder' => 'Account Holder',
        'account_holders_ic_number' => "Account Holder's IC",
        'optional' => 'Optional',
    ],

    'buttons' => [
        'cancel' => 'Cancel',
        'save' => 'Save',
    ],
];
