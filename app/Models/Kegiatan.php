<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';

    protected $primaryKey = 'id_kegiatan';

    public $timestamps = false;

    protected $fillable = [
        'lahan_id',
        'id_jenis',
        'petani_id',
        'tanggal',
        'jumlah',
        'satuan',
        'keterangan'
    ];


    // relasi ke tabel jenis kegiatan
    public function jenisKegiatan()
    {
        return $this->belongsTo(JenisKegiatan::class, 'id_jenis', 'id_jenis');
    }
}

