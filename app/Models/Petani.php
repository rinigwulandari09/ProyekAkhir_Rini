<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Desa;
use App\Models\Lahan;

class Petani extends Model
{
    protected $table = 'petani';
    protected $primaryKey = 'petani_id';
    public $timestamps = false;

    protected $fillable = [
        'petani_nama',
        'petani_alamat',
        'petani_no_hp',
        'petani_status',
        'petani_email',
        'petani_pin',
        'petani_jenis_kelamin',
        'petani_tanggal_lahir',
        'petani_username',
        'desa_id',
        'petani_profil'
    ];

    // relasi ke tabel lahan
    public function lahan()
    {
        return $this->hasOne(Lahan::class, 'petani_id', 'petani_id');
    }

    // relasi tabel desa
    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id', 'desa_id');
    }

    // relasi produksi
    public function produksi()
    {
        return $this->hasMany(Produksi::class, 'petani_id', 'petani_id');
    }

    // relasi biaya_operasional
    public function biayaOperasinals()
    {
        return $this->hasMany(BiayaOperasional::class, 'petani_id', 'petani_id');
    }
}