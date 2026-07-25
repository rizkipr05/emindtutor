<?php

return [
    // Tabs
    'tabs' => [
        'manual' => 'Manual Input',
        'import' => 'Import Excel',
    ],

    // Manual input section
    'manual' => [
        'header' => 'Add Student',
        'subheader' => 'Choose a method to add a new student',
        'errors_title' => 'There were some errors:',

        'fields' => [
            'name' => ['label' => 'Student Name', 'placeholder' => 'Enter full name'],
            'email' => ['label' => 'Email', 'placeholder' => 'example@email.com'],
            'ic_number' => ['label' => 'IC Number', 'placeholder' => '123456789012'],
            'phone' => ['label' => 'Phone', 'placeholder' => '08123456789'],
            'sponsor_name' => ['label' => 'Sponsor Name (optional)', 'placeholder' => 'Sponsor name (optional)'],
            'sponsor_phone' => ['label' => 'Sponsor Phone (optional)', 'placeholder' => 'Sponsor phone (optional)'],
            'sponsor_email' => ['label' => 'Sponsor Email (optional)', 'placeholder' => 'Sponsor email (optional)'],
            'address' => ['label' => 'Address', 'placeholder' => 'Enter full address (optional)'],
            'course' => ['label' => 'Course', 'placeholder' => '-- Select Course --'],
        ],

        'buttons' => [
            'cancel' => 'Cancel',
            'save' => 'Save Student',
        ],
    ],

    // Import section
    'import' => [
        'header' => 'Import Students (Excel)',
        'description' => 'Upload an Excel file to import multiple students at once.',
        'file_label' => 'Upload Excel file',
        'template' => 'Excel Template',
    'template_desc' => 'Excel file must contain columns: :cols',
        'course_note' => 'Course will be assigned based on the selection above.',
        'download' => 'Download Template Excel',
        'course_block' => [
            'title' => 'Select Course for All Students',
            'desc' => 'All students imported from Excel will be registered to the selected course',
        ],
        'buttons' => [
            'cancel' => 'Cancel',
            'import' => 'Import Excel',
        ],
    ],

    // generic messages
    'success' => 'Operation successful',
];
