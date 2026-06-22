<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class Lahan extends Model
{
    use HasFactory;

    // Menentukan nama tabel di Supabase
    protected $table = 'lahan';

    // Menentukan primary key tabel
    protected $primaryKey = 'lahan_id';

    // Jika primary key Anda di Supabase bukan auto-incrementing integer (misal: UUID), ubah ke false
    public $incrementing = true; 

    public $timestamps = false;

    // Isi kolom sesuai dengan skema di Supabase
    protected $fillable = [
        'petani_id',
        'lahan_nama',
        'lahan_lokasi',
        'lahan_luas',
        'area_lahan'
    ];

    protected $casts = [
        'area_lahan' => 'array'
    ];

    public function petani()
    {
        // Hubungkan ke tabel user berdasarkan kolom 'petani_id' dan primary key 'petani_id'
        return $this->belongsTo(Petani::class, 'petani_id', 'petani_id');
    }

    // relasi dgn tabel produksi
    public function produksi()
    {
        return $this->hasMany(Produksi::class, 'lahan_id'. 'lahan_id');
    }
}
