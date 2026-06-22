<?php

namespace App\Helpers;

use App\Models\Notifikasi;

class NotifikasiHelper
{
    public static function create(
        $target,
        $judul,
        $pesan,
        $jenis,
        $userId = null
    )
    {
        Notifikasi::create([
            'target' => $target,
            'user_id' => $userId,
            'judul' => $judul,
            'pesan' => $pesan,
            'jenis' => $jenis
        ]);
    }
}