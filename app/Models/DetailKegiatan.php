<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisKegiatan extends Model
{
    protected $table = 'detail_kegiatan';

    protected $primaryKey = 'detail_kegiatan_id';

    public $timestamps = false;

    protected $fillable = [
        'kegiatan_id',
        'lahan_id',
    ];

    public function lahan()
    {
        return $this->belongsTo(Lahan::class,'lahan_id');
    }
}