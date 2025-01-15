<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class DashboardUser extends Authenticatable
{
    use HasApiTokens, Notifiable, TwoFactorAuthenticatable, HasRoles;

    protected $table = 'dashboard_users';

    protected $fillable = [
        'first_name',
        'last_name',
        'login',
        'email',
        'password',
        'super_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
