<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users'; // Nama tabel di Supabase
    protected $primaryKey = 'user_id'; // Primary key

    protected $fillable = [
        'user_nama',
        'user_username',
        'user_password',
        'user_role',
    ];

    protected $hidden = [
        'user_password',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->user_password;
    }

    protected function casts(): array
    {
        return [
            'user_password' => 'hashed',
        ];
    }
}