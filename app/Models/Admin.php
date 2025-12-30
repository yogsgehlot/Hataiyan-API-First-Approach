<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; 
class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes, TwoFactorAuthenticatable;

    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];
    protected $casts = [
        'two_factor_confirmed_at' => 'datetime',
    ];
    protected $hidden = [
        'password',
    ];

    // ensures password is hashed
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
