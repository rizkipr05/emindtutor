<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'target_audience',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke user yang menerima
    public function users()
    {
        return $this->belongsToMany(User::class, 'announcement_user')
                    ->withPivot('is_read')
                    ->withTimestamps();
    }
}
