<?php

return [
    'heading' => 'Super Admin Staff Listing',
    'add_button' => '+ Add Staff',
    'table' => [
        'no' => 'No',
        'name' => 'Name',
        'email' => 'Email',
        'ic' => 'IC Number',
        'actions' => 'Actions',
        'empty' => 'No super admin staff data'
    ],
    'show_button' => 'Show',
    'edit_button' => 'Edit',
    'delete' => 'Delete',
    'confirm_delete' => 'Are you sure you want to delete this item?',

    'create' => [
        'title' => 'Add Staff',
        'personal_info' => 'Personal Information',
        'allowance_info' => 'Allowance Information',
        'bank_info' => 'Bank Information',
        'labels' => [
            'name' => 'Name',
            'email' => 'Email',
            'ic_number' => 'IC Number',
            'phone' => 'Phone',
            'allowance' => 'Allowance',
            'allowance_type' => 'Allowance Type',
            'allowance_type_options' => [
                'choose' => 'Select Type',
                'per_hour' => 'Per Hour',
                'per_session' => 'Per Session',
                'per_month' => 'Per Month',
            ],
            'bank_name' => 'Bank Name',
            'bank_account_number' => 'Bank Account Number',
            'bank_account_holder' => 'Bank Account Holder',
            'account_holders_ic_number' => "Account Holder's IC Number",
            'ic_helper' => 'IC Number will be used as the default password',
            'optional' => 'Optional',
        ],
        'buttons' => [
            'cancel' => 'Cancel',
            'save' => 'Save',
        ],
    ],

    'edit' => [
        'title' => 'Edit Staff',
        'user_info' => 'User Information',
        'allowance_info' => 'Allowance Information',
        'bank_info' => 'Bank Information',
        'status' => 'Status',
        'buttons' => [
            'cancel' => 'Cancel',
            'update' => 'Update',
        ],
    ],

    'show' => [
        'title' => 'Staff Details',
        'name' => 'Name',
        'phone' => 'Phone Number',
        'allowance' => 'Allowance',
        'allowance_type' => 'Allowance Type',
        'bank_name' => 'Bank Name',
        'bank_account_number' => 'Bank Account Number',
        'bank_account_holder' => 'Bank Account Holder',
        'account_holders_ic_number' => 'Account Holder\'s IC Number',
        'user_info' => 'User Information',
        'allowance_info' => 'Allowance Information',
        'bank_info' => 'Bank Information',
        'status' => 'Status',
        'status_active' => [
            'active' => 'Active',
            'inactive' => 'Inactive'
        ],
        'buttons' => [
            'back' => 'Back',
            'edit' => 'Edit',
            'delete' => 'Delete',
        ],
    ],
];