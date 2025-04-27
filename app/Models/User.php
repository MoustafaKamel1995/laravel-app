<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'ip_address',
        'disk_serial',
        'memory_serial',
        'expiry_date',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'disk_serial',
        'memory_serial',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'expiry_date' => 'datetime',
        'is_active' => 'boolean',
    ];
}
