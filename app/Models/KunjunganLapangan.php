<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KunjunganLapangan extends Model
{
    use HasFactory;

    protected $table = 'kunjungan_lapangan';
    protected $primaryKey = 'id_kunjungan';
    
    // Di foto database Anda terdapat kolom created_at dan updated_at
    public $timestamps = true; 

    protected $fillable = [
        'tanggal_kunjungan',
        'desa_kebun',
        'desa_kepengurusan',
        'nama_auditor',
        'nama_petani',         
        'user_id',             // fk admin
        'petani_id',           // fk petani
        'path_file_kunjungan',
        'status',
        'keterangan',
        'periode',             
        'visit_attempt'        
    ];

    // Relasi ke tabel User (Admin / Auditor)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relasi ke tabel Petani
    public function petani()
    {
        return $this->belongsTo(Petani::class, 'petani_id', 'petani_id');
    }
}
