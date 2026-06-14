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
        'petani_id'
    ];

    public $timestamps = false;
}