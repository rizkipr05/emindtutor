<?php
return [
    'header' => [
        'title' => 'Update Student - :name',
        'back_default' => 'Student Details',
    ],

    'sections' => [
        'personal' => [
            'title' => 'Personal Information',
            'subtitle' => 'Basic data of the registered student',
        ],
        'sponsor' => [
            'title' => 'Sponsor Information',
            'subtitle' => 'Data of the sponsoring party (optional)',
        ],
    ],

    'placeholders' => [
        'name' => 'Enter full name',
        'email' => 'example@email.com',
        'phone' => '01xxxxxxxxxx',
        'ic_number' => 'xxxxxx-xx-xxxx',
        'address' => 'Enter full address',
        'notes' => 'Additional notes (optional)',
        'sponsor_name' => 'Sponsor name (optional)',
        'sponsor_phone' => '01xxxxxxxxxx',
        'sponsor_email' => 'sponsor@email.com',
    ],

    'form' => [
        'personal' => [
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone Number',
            'ic_number' => 'IC Number',
            'address' => 'Address',
            'notes' => 'Additional Notes (optional)',
        ],

        'penaja' => [
            'sponsor_name' => 'Sponsor Name (optional)',
            'sponsor_phone' => 'Sponsor Phone',
            'sponsor_email' => 'Sponsor Email',
        ]
    ],

    'notes' => [
        'ic_changed' => 'If IC is changed, login password will be updated automatically',
    ],

    'buttons' => [
        'update' => 'Update Student',
        'delete' => 'Delete',
        'confirm_delete' => 'Yes, delete',
        'cancel' => 'Cancel',
    ],

    'danger' => [
        'title' => 'Danger Zone',
        'subtitle' => 'This action cannot be undone once executed',
    'delete_heading' => 'Delete Student',
    'delete_text' => 'Permanently remove this student from the system',
    'delete_title' => 'Danger Zone',
    'delete_subtitle' => 'This action cannot be undone once executed',
    ],

    'sweetalert' => [
    'confirm_delete_title' => 'Are you sure you want to delete this student?',
    'confirm_delete_text' => "This action cannot be undone and may remove all student data stored.",
    'confirm_delete_yes' => 'Yes, delete',

    'ic_title' => 'Confirm IC Number',
    'ic_label' => 'Enter student IC number to confirm',
    'ic_placeholder' => 'XXXXXXXXXXXX',
    'ic_confirm' => 'Confirm',
    'ic_required' => 'IC number is required',
    'ic_mismatch_title' => 'IC does not match',
    'ic_mismatch_text' => 'The IC you entered does not match the student record.',

    // Compatibility keys used by Blade deleteLocale
    'confirm_title' => 'Are you sure you want to delete this student?',
    'confirm_text' => "This action cannot be undone and may remove all student data stored.",
    'confirm_button' => 'Yes, delete',
    'cancel_button' => 'Cancel',

    'verify_title' => 'Confirm IC Number',
    'verify_label' => 'Enter student IC number to confirm',
    'verify_placeholder' => 'XXXXXXXXXXXX',
    'verify_confirm' => 'Confirm',
    'verify_empty' => 'IC number is required',

    'deleting' => 'Deleting...',

    'mismatch_title' => 'IC does not match',
    'mismatch_text' => 'The IC you entered does not match the student record.',
    'mismatch_confirm' => 'OK',
    ],
];
