<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'lahan_id' => 'required|integer',
            'id_jenis' => 'required|integer',
            'petani_id' => 'required|integer',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric',
            'satuan' => 'required|string|max:50',
            'keterangan' => 'nullable|string'
        ]);

        $kegiatan = Kegiatan::create([
            'lahan_id' => $request->lahan_id,
            'id_jenis' => $request->id_jenis,
            'petani_id' => $request->petani_id,
            'tanggal' => $request->tanggal,
            'jumlah' => $request->jumlah,
            'satuan' => $request->satuan,
            'keterangan' => $request->keterangan
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kegiatan berhasil disimpan',
            'data' => $kegiatan
        ], 201);
    }
}