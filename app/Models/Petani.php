<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'petani_desa',
        'petani_jenis_kelamin',
        'petani_tanggal_lahir',
        'petani_username'
    ];
}