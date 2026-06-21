<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    protected $table = 'produksi';

    protected $fillable = [
        'produksi_tanggal',
        'jumlah_tbs',
        'harga_tbs',
        'total_pendapatan',
        'status_validasi',
        'petani_id',
        'desa_id',
        'lahan_id',
        'produksi_ket'
    ];

    public $timestamps = false;

    // relasi ke petani
    public function petani()
    {
        return $this->belongsTo(Petani::class, 'petani_id', 'petani_id');
    }

    // relasi ke tabel desa
    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id', 'desa_id');
    }

    // relasi ke tabel lahan
    public function lahan()
    {
        return $this->belongsTo(Lahan::class,'lahan_id','lahan_id');
    }

}