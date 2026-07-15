<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiayaOperasional extends Model
{
    protected $table = 'biaya_operasional';
    protected $primaryKey = 'id'; 

    // 2. JIKA tipe data 'id' di PostgreSQL Anda menggunakan tipe SERIAL / BIGSERIAL (Integer otomatis), set ke true.
    //    JIKA tipe data 'id' adalah UUID / String, ubah menjadi false.
    public $incrementing = true; 

    // 3. Sesuaikan tipe datanya ('int' jika integer, 'string' jika UUID/Text)
    protected $keyType = 'int';
    
    protected $fillable = [
        'biaya_tanggal',
        'biaya_nama',
        'biaya_jenis',
        'biaya_jumlah',
        'biaya_total',
        'biaya_ket',
        'petani_id',
        'lahan_id',
        'biaya_bukti'
    ];

    public $timestamps = false;

    public function petani()
    {
        return $this->belongsTo(Petani::class, 'petani_id', 'petani_id');
    }

    public function lahan()
    {
        return $this->belongsTo(Lahan::class, 'lahan_id', 'lahan_id');
    }
}