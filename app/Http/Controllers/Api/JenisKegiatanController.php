<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisKegiatan;

class JenisKegiatanController extends Controller
{
    public function index()
    {
        $data = JenisKegiatan::orderBy('id_jenis')->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}