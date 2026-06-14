<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiayaOperasional extends Model
{
    protected $table = 'biaya_operasional';

    protected $fillable = [
        'biaya_tanggal',
        'biaya_jenis',
        'biaya_jumlah',
        'biaya_ket',
        'petani_id'
    ];

    public $timestamps = false;
}