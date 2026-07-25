<?php
return [
    'header' => [
        'title' => 'Edit Jadual Kelas',
    ],
    'intro_notice' => 'Simpan pola jadual sekali sahaja. Sistem akan mengulang jadual ini secara automatik setiap minggu mengikut hari dan waktu yang anda tetapkan.',
    'steps' => [
        'one' => 'Pastikan nama kelas, pautan, dan catatan kelas sudah betul.',
        'two' => 'Tambah hari, waktu mula, dan durasi. Contoh: setiap Rabu, 8:30 malam, 90 minit.',
        'three' => 'Lihat pratonton auto generate di bawah sebelum simpan perubahan.',
    ],
    'preview' => [
        'title' => 'Pratonton Auto Generate',
        'desc' => 'Tarikh berikut dijana automatik daripada pola jadual semasa. Jika pola diubah, tarikh ini juga akan berubah.',
    ],

    'errors' => [
        'title' => 'Terdapat ralat:',
    ],

    'sections' => [
        'basic_info' => 'Maklumat Asas',
        'schedule' => 'Jadual Hari & Waktu',
    ],

    'fields' => [
        'class_name' => 'Nama Kelas',
        'link_group' => 'Kumpulan Pautan',
        'notes' => 'Nota',
        'day' => 'Hari',
        'start_time' => 'Waktu Mula',
        'duration_minutes' => 'Tempoh (minit)',
    ],

    'actions' => [
        'add_day' => '+ Tambah Hari',
        'remove' => 'Buang',
    ],

    'select' => [
        'select_day' => 'Pilih Hari',
    ],

    'schedule_item' => [
        'title_prefix' => 'Jadual',
    ],

    'alerts' => [
        'min_one_schedule' => 'Sekurang-kurangnya mesti ada 1 jadual hari',
    ],

    'buttons' => [
        'cancel' => '← Batal',
        'save' => '💾 Simpan Perubahan',
        'saving' => 'Menyimpan...',
    ],

    'day_names' => [
        'monday' => 'Isnin',
        'tuesday' => 'Selasa',
        'wednesday' => 'Rabu',
        'thursday' => 'Khamis',
        'friday' => 'Jumaat',
        'saturday' => 'Sabtu',
        'sunday' => 'Ahad',
    ],
];
