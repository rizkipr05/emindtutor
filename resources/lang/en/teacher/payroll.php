<?php

return [
    'page_title' => 'Digital Payroll Report',
    'header_title' => '📊 Digital Payroll Report',
    'teacher_info' => ':name - IC Number: :ic',
    'company_payroll_record' => 'Teacher Payment Record',
    'letter_head_title' => 'Letter Head',
    'self_detail_title' => 'Personal Details',
    'learning_detail_title' => 'Learning Details',
    'company_name' => 'Company Name',
    'company_registration' => 'Company Registration',
    'company_address' => 'Company Address',
    'name' => 'Name',
    'ic_number' => 'Identity Card Number',
    'payslip_date' => 'Payslip Date',
    'payroll_period' => 'Payroll Period',
    'teacher_profile_title' => 'Teacher Profile',
    'company_profile_title' => 'Company Profile',
    'payment_summary_title' => 'Payroll Summary',
    'payslip_title' => 'Teacher Payslip',
    'generated_at' => 'Generated At',
    'net_salary' => 'Net Salary',
    'class_count' => 'Student Count',
    'not_available' => 'Not available',
    'filter_status_label' => 'Status',
    'class_type' => [
        'individu' => 'Individual',
        'kumpulan' => 'Group',
    ],
    'deductions' => 'Total Deductions',
    'status_deductions' => [
        'paid' => 'Applied Deductions',
        'pending' => 'Pending Deductions',
    ],

    //Months
    'months' => [
        'january' => 'January',
        'february' => 'February',
        'march' => 'March',
        'april' => 'April',
        'may' => 'May',
        'june' => 'June',
        'july' => 'July',
        'august' => 'August',
        'september' => 'September',
        'october' => 'October',
        'november' => 'November',
        'december' => 'December',
    ],

    // Filters
    'filter' => [
        'month' => 'Month',
        'year' => 'Year',
        'button' => 'Filter',
    ],

    // Summary cards
    'summary' => [
        'total_classes' => 'Total Classes Taught',
        'total_teaching_salary' => 'Total Teaching Salary',
        'total_bonus' => 'Total Bonus',
        'total_deductions' => 'Total Deductions',
    ],

    // Detail sections
    'detail' => [
        'teaching_title' => 'Teaching Salary Details',
        'bonus_title' => 'Bonus Details',
        'deduction_title' => 'Deductions Details',
        'summary_title' => 'Total Summary & Download',
    ],

    // Table headers
    'table' => [
        'subject' => 'Subject',
        'class' => 'Class',
        'salary' => 'Salary',
        'status' => 'Status',
        'description' => 'Description',
        'month' => 'Month',
        'amount' => 'Amount',
        'type' => 'Type',
        'day_time' => 'Day & Time',
        'sessions' => 'Sessions',
        'day' => 'Day',
        'time' => 'Time',
    ],

    // Totals
    'totals' => [
        'total_teaching_salary' => 'Total Teaching Salary',
        'total_bonus' => 'Total Bonus',
        'total_deductions' => 'Total Deductions',
        'net_total' => 'NET TOTAL',
    ],

    // Download / status
    'download' => 'Print PDF Payslip',
    'preview' => 'Preview PDF',
    'customize' => 'Customize PDF Payslip',
    'customize_back' => 'Back to Payslip',
    'customize_desc' => 'Edit the payslip content for :month :year, then preview or download the PDF.',
    'customize_button' => 'Customize PDF Payslip',
    'download_disabled' => 'No payroll data for the selected period',
    'status_partial' => 'Partially Unpaid',
    'status_all_paid' => 'All Paid',
    'no_data' => 'No payroll data for this period',

    'customize_fields' => [
        'company_name' => 'Company Name',
        'company_registration' => 'Registration No.',
        'company_address' => 'Company Address',
        'employee_name' => 'Employee Name',
        'employee_nric' => 'NRIC',
        'bank_name' => 'Bank',
        'bank_account_number' => 'Bank Account Number',
        'outlet' => 'Outlet',
        'payslip_date' => 'Payslip Date',
        'payroll_period' => 'Payroll Period',
        'department' => 'Department',
        'position' => 'Position',
        'employee_ref' => 'Employee Ref',
    ],

    'customize_sections' => [
        'annual_leave' => 'Annual Leave',
        'medical_leave' => 'Medical Leave',
        'employer_contribution' => 'Employer Contribution',
    ],

    'customize_placeholders' => [
        'entitlement' => 'Entitlement',
        'earned_leave' => 'Earned Leave',
        'cf' => 'C/F',
        'taken' => 'Taken',
        'balance' => 'Balance',
        'epf' => 'EPF',
        'socso' => 'SOCSO',
        'eis' => 'EIS',
        'pcb' => 'PCB',
        'hrd' => 'HRD',
    ],

    // Bank info labels
    'bank' => [
        'name' => 'Bank Name',
        'account_number' => 'Bank Account Number',
        'account_holder' => 'Account Holder',
        'account_holder_ic' => 'Account Holder IC',
    ],

    // Common
    'paid' => 'Paid',
    'pending' => 'Pending',
    'download_slip' => 'Download Monthly Payslip',
];
