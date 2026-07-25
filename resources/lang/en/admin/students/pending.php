<?php
return [
    'header' => [
        'title' => 'Pending Students',
        'subtitle' => 'Manage student registration requests awaiting approval',
    ],

    'search' => [
        'label' => 'Search Student',
        'placeholder' => 'Search by IC or student name...',
        'button' => 'Search',
    ],

    'table' => [
        'title' => 'Pending Student Data',
        'total' => 'Total: :count students awaiting approval',
        'columns' => [
            'name' => 'Student Name',
            'ic' => 'IC Number',
            'phone' => 'Phone',
            'course' => 'Course',
            'payment_status' => 'Payment Status',
            'actions' => 'Actions',
        ],
        'empty' => [
            'title' => 'No pending students',
            'subtitle' => 'There are no students currently awaiting approval.',
        ],
    ],

    'buttons' => [
        'approve' => 'Approve',
    ],

    'pagination' => [
        'showing' => 'Showing :first to :last of :total results',
    ],

    'status_bayaran' => [
        'paid' => 'Paid',
        'overdue' => 'Overdue',
        'upcoming' => 'Upcoming',
    ]
];
