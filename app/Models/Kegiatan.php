<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';

    protected $primaryKey = 'id_kegiatan';

    public $timestamps = false;

    protected $fillable = [
        'kegiatan_id',
        'petani_id',
        'jenis_kegiatan_id',
        'kegiatan_tanggal',
        'kegiatan_jumlah',
        'kegiatan_satuan',
        'kegiatan_ket'
    ];


    // relasi ke tabel jenis kegiatan
    public function jenisKegiatan()
    {
        return $this->belongsTo(JenisKegiatan::class, 'id_jenis', 'id_jenis');
    }

    public function detailLahan()
    {
        return $this->hasMany(DetailKegiatan::class, 'kegiatan_id', 'id_kegiatan');
    }

    public function jenis()
    {
        return $this->belongsTo(JenisKegiatan::class, 'jenis_kegiatan_id');
    }
}

