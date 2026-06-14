<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'user_nama',
        'user_username',
        'user_email',
        'user_password',
        'user_role',
        'desa_id',
    ];

    protected $hidden = [
        'user_password',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->user_password;
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id', 'desa_id');
    }
}