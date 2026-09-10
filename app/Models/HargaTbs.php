<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaTbs extends Model
{
    use HasFactory;

    protected $table = 'harga_tbs';
    protected $primaryKey = 'harga_tbs_id';

    protected $fillable = [
        'harga_dinas',
        'harga_pt_sar',
        'tanggal_berlaku',
        'created_by_user_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id', 'user_id');
    }
}
