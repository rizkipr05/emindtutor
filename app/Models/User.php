<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;
use Rawilk\Webauthn\Models\WebauthnKey;

class User extends Authenticatable
{
    use Impersonate;
    
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function teachers()
    {
        return $this->hasOne(Teacher::class);
    }

    public function students()
    {
        return $this->hasOne(Student::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function announcementsCreated()
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    public function announcements()
    {
        return $this->belongsToMany(Announcement::class, 'announcement_user')
                    ->withPivot('is_read')
                    ->withTimestamps();
    }
    
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }

    public function webAuthnKeys()
    {
        return $this->hasMany(WebauthnKey::class);
    }

    /**
     * Return an ASCII-safe label for WebAuthn credential options.
     *
     * Legacy imported user data can contain malformed UTF-8 bytes. WebAuthn
     * options are JSON encoded, so use a safe identifier instead of exposing
     * that data to the browser credential API.
     */
    public function getWebauthnUsernameAttribute(): string
    {
        return 'EMindTutor user '.$this->getAuthIdentifier();
    }
}
