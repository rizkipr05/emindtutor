<?php

return [
    'title' => 'Edit Staff',

    'sections' => [
        'personal' => 'Personal Information',
        'allowance' => 'Allowance Information',
        'bank' => 'Bank Information',
    ],

    'labels' => [
        'name' => 'Name',
        'email' => 'Email',
        'ic_number' => 'IC Number',
        'ic_helper' => 'Password will be reset to IC Number if changed',
        'phone' => 'Phone',

        'allowance' => 'Allowance',
        'allowance_type' => 'Allowance Type',
        'allowance_type_options' => [
            'choose' => 'Select Type',
            'per_hour' => 'Per Hour',
            'session' => 'Per Session',
            'per_month' => 'Per Month',
        ],

        'bank_name' => 'Bank Name',
        'bank_account_number' => 'Bank Account Number',
        'bank_account_holder' => 'Bank Account Holder',
        'account_holders_ic_number' => "Account Holder's IC Number",
        'optional' => 'Optional',
    ],

    'buttons' => [
        'cancel' => 'Cancel',
        'update' => 'Update',
    ],
];
