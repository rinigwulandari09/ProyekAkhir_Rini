<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditInternal extends Model
{
    use HasFactory;

    protected $table = 'audit_internal';
    protected $primaryKey = 'id_audit';
    public $timestamps = false; 

    protected $fillable = [
        'user_id',
        'tanggal',
        'desa',
        'nama_auditor',
        'nama_petani',
        'path_file_kunjungan'
    ];
}
