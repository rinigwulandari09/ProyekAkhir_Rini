<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KunjunganLapangan extends Model
{
    use HasFactory;

    protected $table = 'kunjungan_lapangan';
    protected $primaryKey = 'id_kunjungan';
    public $timestamps = false; // Assuming no created_at/updated_at based on the schema

    protected $fillable = [
        'tanggal_kunjungan',
        'desa_kebun',
        'desa_kepengurusan',
        'nama_auditor',
        'path_file_kunjungan'
    ];
}
