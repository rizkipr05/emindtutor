<?php
return [
    'title' => 'Give Teacher Review',
    'header' => 'Give a Review for the Teacher',
    'breadcrumb' => [
        'back' => 'Back',
    ],

    'labels' => [
        'teacher_name' => 'Teacher Name',
        'course_name' => 'Subject',
        'rating' => 'Rating',
        'comment' => 'Comment',
    ],

    'rating' => [
        'required' => 'Rating',
        'helper' => 'Click a star to set the rating',
    ],

    'comment' => [
        'placeholder' => 'Write your comment about this teacher... (optional)',
        'max_hint' => 'Maximum :max characters',
    ],

    'tips' => [
        'title' => 'Tips:',
        'content' => 'Be clear and specific when leaving feedback to help improve the learning experience.',
    ],

    'buttons' => [
        'submit' => 'Submit Review',
        'cancel' => 'Cancel',
    ],

    'js' => [
        'give_rating_alert' => 'Please provide a rating before submitting!',
        'characters' => 'characters',
    ],
];
