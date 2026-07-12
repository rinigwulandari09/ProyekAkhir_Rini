<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailProduksi extends Model
{
    protected $table = 'detail_produksi';

    protected $primaryKey = 'detail_produksi_id';

    public $timestamps = false;

    protected $fillable = [
        'produksi_id',
        'lahan_id',
    ];

    public function lahan()
    {
        return $this->belongsTo(Lahan::class,'lahan_id');
    }
}