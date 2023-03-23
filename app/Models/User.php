<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = ['email_verified_at' => 'datetime'];

    protected $appends = ['profile_photo_url'];

    /**
     * Does the user belongs to at least one team?
     */
    public function belongsToATeam(): bool
    {
        return $this->allTeams()->count() > 0;
    }

    /**
     * Does the user have a profile photo?
     */
    public function getHasProfilePhotoAttribute(): bool
    {
        return (string) $this->profile_photo_path !== '';
    }
}
