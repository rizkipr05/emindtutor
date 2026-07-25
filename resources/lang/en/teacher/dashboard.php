<?php

return [
    // Page
    'page_title' => 'Dashboard | :site',

    // Header
    'teacher_dashboard' => 'Teacher Dashboard - :company',
    'welcome_back' => 'Welcome back to :site',

    // Sections
    'info_heading' => 'Teacher Information',
    'stats_heading' => 'Statistics',
    'unavailability_heading' => 'Times You Cannot Accept Classes',

    // Info labels
    'label_name' => 'Name',
    'label_email' => 'Email',
    'label_phone' => 'Phone',
    'label_ic' => 'IC Number',

    // Stats labels
    'stat_students' => 'Number of Students',
    'stat_classes_this_week' => 'Classes This Week',
    'stat_attendance_rate' => 'Student Attendance Rate',
    'announcement_create_title' => 'Create Announcement',
    'announcement_create_desc' => 'This announcement will appear on the student dashboard in your company.',
    'announcement_title_label' => 'Announcement Title',
    'announcement_title_placeholder' => 'Example: Tomorrow\'s class is postponed to 3 PM',
    'announcement_message_label' => 'Announcement Content',
    'announcement_message_placeholder' => 'Write the announcement for students here',
    'announcement_send_notice' => 'The system will send this announcement to all students in your company.',
    'announcement_send_button' => 'Send Announcement',
    'announcement_recent_title' => 'Latest Announcements',
    'announcement_recent_desc' => 'History of announcements you have sent.',
    'view_all' => 'View all',
    'announcement_empty' => 'No announcements have been sent yet.',
    'announcement_target_prefix' => 'For :target',
    'biometric_title' => 'Fingerprint Login (Biometric)',
    'biometric_desc' => 'Register your fingerprint on this device so you can log in next time without entering a password.',
    'biometric_register' => 'Register Fingerprint',
    'biometric_not_supported' => 'Your browser or device does not support biometric registration (WebAuthn).',
    'biometric_https_required' => 'Biometric registration is only allowed on a secure connection (HTTPS) or localhost.',
    'biometric_error_prefix' => 'Biometric Registration Error: ',
    'biometric_device_error_prefix' => 'Device Error: ',
    'biometric_name' => 'Teacher Fingerprint',
    'biometric_success' => 'Fingerprint registered successfully!',
    'biometric_failed' => 'Registration failed.',
    'biometric_verification_error' => 'Fingerprint registration verification encountered an error.',
    'biometric_server_error' => 'Server connection failed. Make sure the MySQL database is running.',

    // Table headers
    'table_day' => 'Day',
    'table_start_time' => 'Start Time',
    'table_end_time' => 'End Time',

    // Form labels & placeholders
    'change_password' => 'Change Password',
    'label_current_password' => 'Current Password',
    'label_new_password' => 'New Password',
    'label_confirm_password' => 'Confirm New Password',
    'placeholder_current_password' => 'Enter your current password',
    'placeholder_new_password' => 'Enter a new password',
    'placeholder_confirm_password' => 'Confirm the new password',
    'update_password' => 'Update Password',

    // Unavailability messages
    'no_unavailability' => 'No unavailability records.',
    'available_all_times' => 'You are available for all class times.',

    // Password
    'change_password' => 'Change Password',
    'success_saved' => 'Password successfully updated.',
    'error_heading' => 'Please fix the following errors:',
    'current_password_incorrect' => 'The current password is incorrect.',

    'days' => [
        'Sunday' => 'Sunday',
        'Monday' => 'Monday',
        'Tuesday' => 'Tuesday',
        'Wednesday' => 'Wednesday',
        'Thursday' => 'Thursday',
        'Friday' => 'Friday',
        'Saturday' => 'Saturday',
    ]
];
