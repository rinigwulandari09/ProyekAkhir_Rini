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
        'lahan_lokasi',
        'lahan_luas',
        'petani_id',
        'area_lahan',
        'lahan_nama'
    ];

    public function petani()
    {
        // Hubungkan ke tabel user berdasarkan kolom 'petani_id' dan primary key 'user_id'
        return $this->belongsTo(Petani::class, 'petani_id', 'petani_id');
    }
}
