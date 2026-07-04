<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisKegiatan extends Model
{
    protected $table = 'jenis_kegiatan';

    protected $primaryKey = 'id_jenis';

    public $timestamps = false;

    protected $fillable = [
        'nama_jenis',
        'ikon'
    ];

    // relasi ke tabel kegiatan
    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'id_jenis', 'id_jenis');
    }
}