<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyReview extends Model
{
    use HasFactory;

    protected $table = 'company_reviews';

    protected $fillable = [
        'company_id',
        'company_invoice_id',
        'rating',
        'comment',
    ];

    /**
     * Relasi ke Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
