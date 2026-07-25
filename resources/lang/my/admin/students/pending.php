<?php
return [
    'header' => [
        'title' => 'Senarai Pelajar Belum Diluluskan',
        'subtitle' => 'Urusan permohonan pendaftaran pelajar menunggu kelulusan',
    ],

    'search' => [
        'label' => 'Cari Pelajar',
        'placeholder' => 'Carian mengikut IC atau nama pelajar...',
        'button' => 'Cari',
    ],

    'table' => [
        'title' => 'Data Pelajar Pending',
        'total' => 'Total: :count pelajar menunggu kelulusan',
        'columns' => [
            'name' => 'Nama Pelajar',
            'ic' => 'No. IC',
            'phone' => 'Telefon',
            'course' => 'Kursus',
            'payment_status' => 'Status Bayaran',
            'actions' => 'Tindakan',
        ],
        'empty' => [
            'title' => 'Tiada pelajar pending',
            'subtitle' => 'Tiada pelajar yang sedang menunggu kelulusan pada masa ini.',
        ],
    ],

    'buttons' => [
        'approve' => 'Luluskan',
    ],

    'pagination' => [
        'showing' => 'Menunjukkan :first hingga :last daripada :total hasil',
    ],

    'status_bayaran' => [
        'paid' => 'Dibayar',
        'overdue' => 'Tertunggak',
        'upcoming' => 'Akan Datang',
    ]
];
