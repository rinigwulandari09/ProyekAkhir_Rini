<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';

    protected $primaryKey = 'kegiatan_id';

    public $timestamps = false;

    protected $fillable = [
        'petani_id',
        'nama_kegiatan',
        'jenis_kegiatan_id',
        'kegiatan_tanggal',
        'kegiatan_jumlah',
        'kegiatan_satuan',
        'kegiatan_ket',
        'nama_bahan',
        'jenis_limbah',
        'status_limbah'
    ];


    // relasi ke tabel jenis kegiatan
    public function jenisKegiatan()
    {
        return $this->belongsTo(JenisKegiatan::class, 'id_jenis', 'id_jenis');
    }

    public function detailLahan()
    {
        return $this->hasMany(DetailKegiatan::class, 'kegiatan_id', 'kegiatan_id');
    }

    public function jenis()
    {
        return $this->belongsTo(JenisKegiatan::class, 'jenis_kegiatan_id');
    }
}

