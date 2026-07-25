<?php

return [
    'title' => 'Staff Details',
    'buttons' => [
        'edit' => 'Edit Staff',
        'back' => 'Back',
        'delete' => 'Delete Staff',
    ],

    'sections' => [
        'personal' => 'Personal Information',
        'allowance' => 'Allowance Information',
        'bank' => 'Bank Information',
        'status' => 'Status & Actions',
        'additional' => 'Additional Information',
    ],

    'labels' => [
        'full_name' => 'Full name',
        'email' => 'Email',
        'ic_number' => 'IC Number',
        'phone' => 'Phone Number',
        'role' => 'Role',
        'allowance_amount' => 'Allowance Amount',
        'allowance_type' => 'Allowance Type',
        'estimated_income' => 'Estimated Income:',
        'bank_name' => 'Bank Name',
        'bank_account' => 'Account Number',
        'bank_holder' => 'Account Holder Name',
        'bank_holder_ic' => 'Account Holder IC Number',
        'status' => 'Staff Status',
        'joined_at' => 'Joined At',
        'updated_at' => 'Last Updated',
        'account_created' => 'Account Created',
        'info' => 'Info:',
    ],

    'status' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ],

    'warning' => [
        'title' => 'Attention',
        'bank_holder_mismatch' => 'Account holder name differs from staff name. Please verify this information.',
    ],

    'confirm_delete' => 'Are you sure you want to delete this staff? This action cannot be undone.',
    'remember_token' => 'User has an active remember token (persistent login).',

    'jenis_elaun' => [
        'per_hour' => 'Per Hour',
        'per_session' => 'Per Session',
        'per_month' => 'Per Month',
    ],
];
