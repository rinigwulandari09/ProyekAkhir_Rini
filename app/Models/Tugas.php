<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas';

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'deadline',
        'is_read',
        'read_at',
        'is_done',
    ];
}
