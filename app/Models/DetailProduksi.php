<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailProduksi extends Model
{
    use HasFactory;

    protected $table = 'detail_produksi';
    protected $primaryKey = 'detail_produksi_id';

    public $timestamps = false;

    protected $fillable = [
        'produksi_id',
        'lahan_id',
        'jumlah_tbs',
        'subtotal_pendapatan'
    ];

    public function produksi()
    {
        return $this->belongsTo(Produksi::class, 'produksi_id', 'id');
    }

    public function lahan()
    {
        return $this->belongsTo(Lahan::class, 'lahan_id', 'lahan_id');
    }
}
