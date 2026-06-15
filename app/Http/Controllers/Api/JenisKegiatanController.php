<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisKegiatan;
use Illuminate\Http\Request;

class JenisKegiatanController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => JenisKegiatan::orderBy('id_jenis')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|unique:jenis_kegiatan,nama_jenis',
            'ikon' => 'required'
        ]);

        $jenis = JenisKegiatan::create([
            'nama_jenis' => $request->nama_jenis,
            'ikon' => $request->ikon
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jenis kegiatan berhasil ditambahkan',
            'data' => $jenis
        ], 201);
    }
}