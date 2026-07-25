<?php

return [
    'header' => [
        'title' => 'Laporan Kehadiran & Prestasi',
        'subtitle' => 'Papan pemuka komprehensif untuk memantau kehadiran dan pencapaian',
        'period' => 'Periode',
    ],

    'filters' => [
        'month' => 'Bulan',
        'year' => 'Tahun',
        'filter' => 'Filter',
    'loading' => 'Memuat...',
    ],

    'cards' => [
        'present' => 'Siswa Hadir',
        'late' => 'Siswa Terlambat',
        'absent' => 'Siswa Absent',
        'teachers' => 'Guru Aktif',
    ],

    'tabs' => [
        'attendance' => ['label' => 'Laporan Kehadiran', 'short' => 'Kehadiran'],
        'performance' => ['label' => 'Perbandingan Prestasi', 'short' => 'Prestasi'],
        'cancelled' => ['label' => 'Kelas Batal', 'short' => 'Batal'],
    ],

    'attendance' => [
        'title' => 'Laporan Kehadiran Per Kelas',
        'per_class' => 'Laporan Kehadiran Per Kelas',
        'class_name' => 'Kelas',
        'teacher' => 'Guru',
        'students' => 'siswa',
        'sessions' => 'sesi',
        'attendance_rate' => 'Tingkat Kehadiran',
        'view_details' => 'Lihat Detail Kelas',
        'session_history' => 'Riwayat Sesi Kelas',
        'teacher_notes' => 'Catatan Guru',
        'class_photos' => 'Foto Kelas',
        'no_sessions' => 'Tidak ada sesi tersedia',
        'present' => 'Hadir',
        'late' => 'Terlambat',
        'absent' => 'Absent',
    ],

    'performance' => [
        'title' => 'Perbandingan Prestasi Mengikut Kelas',
        'chart' => 'Carta Prestasi',
    'chart_label' => 'Tingkat Kehadiran (%)',
    'tooltip_label_prefix' => 'Kehadiran:',
        'table_headers' => ['class' => 'Kelas', 'attendance_rate' => 'Tingkat Kehadiran', 'status' => 'Status', 'total_sessions' => 'Jumlah Sesi', 'target' => 'Target'],
        'status' => [
            'excellent' => 'Cemerlang',
            'good' => 'Baik',
            'needs_attention' => 'Perlu Perhatian',
        ],
    ],

    'cancelled' => [
        'title' => 'Laporan Kelas yang Dibatalkan',
    'no_classes' => 'Tiada kelas yang dibatalkan',
    'no_classes_message' => 'Semua kelas berjalan dengan baik bulan ini!',
    'list_title' => 'Kelas Dibatalkan',
    'status_cancelled' => 'Dibatalkan',
    'note_label' => 'Catatan:',
    ],

    'buttons' => [
    'view' => 'Lihat',
    'downloading' => 'Mengunduh...',
    ],

    'months' => [
        '1' => 'Januari',
        '2' => 'Februari',
        '3' => 'Mac',
        '4' => 'April',
        '5' => 'Mei',
        '6' => 'Jun',
        '7' => 'Julai',
        '8' => 'Ogos',
        '9' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Disember',
    ],

    'minimum_sessions_unit' => [
        'session' => 'Sesi',
        'hours' => 'Jam',
        'minutes' => 'Minit',
    ]
];
