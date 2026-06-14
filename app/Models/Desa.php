<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    protected $table = 'desa';
    protected $primaryKey = 'desa_id';

    public $timestamps = false;

    protected $fillable = [
        'nama_desa',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'desa_id', 'desa_id');
    }

    public function petani()
    {
        return $this->hasMany(Petani::class, 'desa_id', 'desa_id');
    }
}