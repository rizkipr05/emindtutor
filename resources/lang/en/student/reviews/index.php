<?php
return [
    'title' => 'Teacher Reviews',
    'subtitle' => 'Provide feedback to improve learning quality',

    'tabs' => [
        'available' => 'Teachers Available for Review',
        'history' => 'Review History (:count)',
    ],

    'labels' => [
        'role_teacher' => 'Teacher',
    ],

    'available' => [
        'empty_title' => 'No teachers available',
        'empty_desc' => 'There are no teachers available for review. You need to be enrolled in a class first.',
        'give_review' => 'Give Review',
    ],

    'table' => [
        'head' => [
            'teacher' => 'Teacher',
            'subject' => 'Subject',
            'rating' => 'Rating',
            'comment' => 'Comment',
            'date' => 'Date',
            'actions' => 'Actions',
        ],
        'no_comment' => 'No comment',
    ],

    'history' => [
        'empty_title' => 'No reviews yet',
        'empty_desc' => 'You have not reviewed any teachers yet.',
    ],

    'actions' => [
        'view' => 'View Details',
        'edit' => 'Edit Review',
        'delete' => 'Delete Review',
        'confirm_delete' => 'Are you sure you want to delete this review?',
    ],
];
