<?php

return [
    'header' => [
        'title' => 'Attendance & Performance Reports',
        'subtitle' => 'Comprehensive dashboard for monitoring attendance and achievements',
        'period' => 'Period',
    ],

    'filters' => [
        'month' => 'Month',
        'year' => 'Year',
        'filter' => 'Filter',
    'loading' => 'Loading...',
    ],

    'cards' => [
        'present' => 'Students Present',
        'late' => 'Students Late',
        'absent' => 'Students Absent',
        'teachers' => 'Active Teachers',
    ],

    'tabs' => [
        'attendance' => ['label' => 'Attendance Report', 'short' => 'Attendance'],
        'performance' => ['label' => 'Performance Comparison', 'short' => 'Performance'],
        'cancelled' => ['label' => 'Cancelled Classes', 'short' => 'Cancelled'],
    ],

    'attendance' => [
        'title' => 'Class Attendance Report',
        'per_class' => 'Attendance Report Per Class',
        'class_name' => 'Class',
        'teacher' => 'Teacher',
        'students' => 'students',
        'sessions' => 'sessions',
        'attendance_rate' => 'Attendance Rate',
        'view_details' => 'View Class Details',
        'session_history' => 'Session History',
        'teacher_notes' => 'Teacher Notes',
        'class_photos' => 'Class Photos',
        'no_sessions' => 'No sessions available',
        'present' => 'Present',
        'late' => 'Late',
        'absent' => 'Absent',
    ],

    'performance' => [
        'title' => 'Performance Comparison by Class',
        'chart' => 'Performance Chart',
    'chart_label' => 'Attendance Rate (%)',
    'tooltip_label_prefix' => 'Attendance:',
        'table_headers' => ['class' => 'Class', 'attendance_rate' => 'Attendance Rate', 'status' => 'Status', 'total_sessions' => 'Total Sessions', 'target' => 'Target'],
        'status' => [
            'excellent' => 'Excellent',
            'good' => 'Good',
            'needs_attention' => 'Needs Attention',
        ],
    ],

    'cancelled' => [
        'title' => 'Cancelled Classes Report',
    'no_classes' => 'No cancelled classes found',
    'no_classes_message' => 'All classes are running smoothly this month!',
    'list_title' => 'Cancelled classes',
    'status_cancelled' => 'Cancelled',
    'note_label' => 'Note:',
    ],

    'buttons' => [
    'view' => 'View',
    'downloading' => 'Downloading...',
    ],

    'months' => [
        '1' => 'January',
        '2' => 'February',
        '3' => 'March',
        '4' => 'April',
        '5' => 'May',
        '6' => 'June',
        '7' => 'July',
        '8' => 'August',
        '9' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December',
    ],

    'minimum_sessions_unit' => [
        'session' => 'Session',
        'hours' => 'Hours',
        'minutes' => 'Minutes',
    ]
];
