<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petani extends Model
{
    /** @use HasFactory<\Database\Factories\PetaniFactory> */
    use HasFactory;

    protected $table = 'petani'; // Nama tabel di Supabase
    protected $primaryKey = 'petani_id'; // Primary key

    protected $fillable = [
        'petani_nama',
        'petani_username',
        'petani_alamat',
        'petani_status',
    ];
}
