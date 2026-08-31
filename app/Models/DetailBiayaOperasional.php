<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBiayaOperasional extends Model
{
    protected $table = "detail_biaya_operasional";

    protected $primaryKey = "detail_biaya_operasional_id";

    public $timestamps = false;

    protected $fillable = [
        "biaya_operasional_id",
        "lahan_id",
        "subtotal"
    ];

    public function lahan()
    {
        return $this->belongsTo(Lahan::class, "lahan_id");
    }
}

