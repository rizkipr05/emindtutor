<?php
return [
    'header' => [
        'title' => 'Kemaskini Pelajar - :name',
        'back_default' => 'Butiran Pelajar',
    ],

    'sections' => [
        'personal' => [
            'title' => 'Informasi Peribadi',
            'subtitle' => 'Data asas pelajar yang didaftarkan',
        ],
        'sponsor' => [
            'title' => 'Informasi Penaja',
            'subtitle' => 'Data pihak yang mensponsor pelajar (opsional)',
        ],
    ],

    'placeholders' => [
        'name' => 'Masukkan nama lengkap',
        'email' => 'contoh@email.com',
        'phone' => '01xxxxxxxxxx',
        'ic_number' => 'xxxxxx-xx-xxxx',
        'address' => 'Masukkan alamat lengkap',
        'notes' => 'Catatan tambahan (pilihan)',
        'sponsor_name' => 'Nama sponsor (pilihan)',
        'sponsor_phone' => '01xxxxxxxxxx',
        'sponsor_email' => 'sponsor@email.com',
    ],

    'form' => [
        'personal' => [
            'name' => 'Nama',
            'email' => 'Email',
            'phone' => 'Nombor Telefon',
            'ic_number' => 'Nombor IC',
            'address' => 'Alamat',
            'notes' => 'Nota tambahan (pilihan)',
        ],

        'penaja' => [
            'sponsor_name' => 'Nama Penaja (pilihan)',
            'sponsor_phone' => 'Telefon Penaja',
            'sponsor_email' => 'Email Penaja',
        ]
    ],

    'notes' => [
        'ic_changed' => 'Jika IC diubah, password login juga akan diperbarui otomatis',
    ],

    'buttons' => [
        'update' => 'Kemaskini Pelajar',
        'delete' => 'Hapus',
        'confirm_delete' => 'Ya, hapus',
        'cancel' => 'Batal',
    ],

    'danger' => [
        'title' => 'Zon Bahaya',
    'subtitle' => 'Tindakan ini tidak dapat dibatalkan setelah dilaksanakan',
    'delete_heading' => 'Hapus Murid',
    'delete_text' => 'Hapus murid ini secara kekal dari sistem',
    'delete_title' => 'Zon Bahaya',
    'delete_subtitle' => 'Tindakan ini tidak dapat dibatalkan setelah dilaksanakan',
    ],

    'sweetalert' => [
    'confirm_delete_title' => 'Yakin ingin hapus murid ini?',
    'confirm_delete_text' => 'Tindakan ini tidak boleh diundur! Bisa menghapus semua data murid yang telah tersimpan',
    'confirm_delete_yes' => 'Ya, hapus',

    'ic_title' => 'Sahkan Nombor IC',
    'ic_label' => 'Masukkan nombor IC murid untuk pengesahan',
    'ic_placeholder' => 'XXXXXXXXXXXX',
    'ic_confirm' => 'Sahkan',
    'ic_required' => 'Nombor IC wajib diisi',
    'ic_mismatch_title' => 'IC tidak sepadan',
    'ic_mismatch_text' => 'Nombor IC yang anda masukkan tidak sepadan dengan rekod pelajar.',

    // Compatibility keys used by Blade deleteLocale
    'confirm_title' => 'Yakin ingin hapus murid ini?',
    'confirm_text' => 'Tindakan ini tidak boleh diundur! Bisa menghapus semua data murid yang telah tersimpan',
    'confirm_button' => 'Ya, hapus',
    'cancel_button' => 'Batal',

    'verify_title' => 'Sahkan Nombor IC',
    'verify_label' => 'Masukkan nombor IC murid untuk pengesahan',
    'verify_placeholder' => 'XXXXXXXXXXXX',
    'verify_confirm' => 'Sahkan',
    'verify_empty' => 'Nombor IC wajib diisi',

    'deleting' => 'Sedang menghapus...',

    'mismatch_title' => 'Nombor IC tidak sepadan',
    'mismatch_text' => 'Sila cuba lagi dengan nombor IC yang betul.',
    'mismatch_confirm' => 'OK',
    ],
];
