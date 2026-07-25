<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyBankDetail extends Model
{
    use HasFactory;

    /**
     * Nama jadual yang dikaitkan dengan model ini (pilihan jika nama sudah standard).
     * @var string
     */
    protected $table = 'company_bank_details';

    /**
     * Atribut yang boleh diisi secara pukal (Mass Assignable).
     * @var array
     */
    protected $fillable = [
        'company_id',
        'nama_bank',
        'nombor_akaun_bank',
    ];

    /**
     * Hubungan 'Belongs To' - Bank detail ini milik sebuah Syarikat.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}