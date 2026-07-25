<?php
return [
    'back' => 'Back to Teacher List',
    'header' => 'Teacher Details',

    'tabs' => [
        'info' => 'Teacher Info',
        'classes' => 'Classes',
        'timetable' => 'Timetable',
        'reviews' => 'Reviews',
    ],

    'sections' => [
        'personal' => 'Personal Information',
        'financial' => 'Financial Information',
    ],

    'fields' => [
        'name' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'ic_number' => 'IC Number',
        'status' => 'Status',
        'status_active' => 'Active',
        'status_inactive' => 'Inactive',

        'allowance' => 'Allowance',
        'allowance_type_per_hour' => 'Per Hour',
        'allowance_type_per_session' => 'Per Session',
        'allowance_type_monthly' => 'Monthly',

        'bank_name' => 'Bank Name',
        'bank_account_number' => 'Account Number',
        'bank_account_holder' => 'Account Holder',
        'account_holders_ic_number' => 'Holder IC',
    ],

    'classes' => [
        'individual' => 'Individual Classes',
        'group' => 'Group Classes',
        'no_individual' => 'No individual classes',
        'no_group' => 'No group classes',
        'view_details' => 'View Details',
        'students' => 'Students',
        'total_sessions' => 'Total Sessions',
        'attendance' => 'Attendance',
        'schedule' => 'Schedule',
        'minute' => 'minutes',
        'session_history' => 'Session History',
    ],

    'timetable' => [
        'class_schedule' => 'Class Schedule',
        'unavailable' => 'Unavailable',
        'available' => 'Available',
        'time' => 'Time',

        // Full day names for translation usage (e.g. array keys)
        'days' => [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ],

        // Short/label day names shown in timetable header
        'day_names' => [
            'MON' => 'MON',
            'TUE' => 'TUE',
            'WED' => 'WED',
            'THU' => 'THU',
            'FRI' => 'FRI',
            'SAT' => 'SAT',
            'SUN' => 'SUN',
        ],
    ],

    'reviews' => [
        'rating_average' => 'Average Rating',
        'total_reviews' => 'Total Reviews',
        'empty_title' => 'No reviews yet',
        'empty_desc' => 'Student reviews will appear here after they complete the course.',
        'load_more' => 'Load More',
        'anonymous' => 'Anonymous',
    ],

    'actions' => [
        'edit' => 'Edit Teacher',
    ],
];
