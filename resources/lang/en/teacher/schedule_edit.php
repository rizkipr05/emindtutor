<?php
return [
    'header' => [
        'title' => 'Edit Class Schedule',
    ],
    'intro_notice' => 'Save the schedule pattern once. The system will repeat it automatically every week based on the days and times you set.',
    'steps' => [
        'one' => 'Check that the class name, link, and notes are correct.',
        'two' => 'Add the teaching day, start time, and duration. Example: every Wednesday, 8:30 PM, 90 minutes.',
        'three' => 'Review the auto-generated preview below before saving changes.',
    ],
    'preview' => [
        'title' => 'Auto-Generated Preview',
        'desc' => 'The dates below are generated automatically from the current schedule pattern. If the pattern changes, these dates will change too.',
    ],

    'errors' => [
        'title' => 'There are some errors:',
    ],

    'sections' => [
        'basic_info' => 'Basic Information',
        'schedule' => 'Days & Time Schedule',
    ],

    'fields' => [
        'class_name' => 'Class Name',
        'link_group' => 'Link Group',
        'notes' => 'Notes',
        'day' => 'Day',
        'start_time' => 'Start Time',
        'duration_minutes' => 'Duration (minutes)',
    ],

    'actions' => [
        'add_day' => '+ Add Day',
        'remove' => 'Remove',
    ],

    'select' => [
        'select_day' => 'Select Day',
    ],

    'schedule_item' => [
        'title_prefix' => 'Schedule',
    ],

    'alerts' => [
        'min_one_schedule' => 'At least one schedule day is required',
    ],

    'buttons' => [
        'cancel' => '← Cancel',
        'save' => '💾 Save Changes',
        'saving' => 'Saving...',
    ],

    'day_names' => [
        'monday' => 'Monday',
        'tuesday' => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday' => 'Thursday',
        'friday' => 'Friday',
        'saturday' => 'Saturday',
        'sunday' => 'Sunday',
    ],
];
