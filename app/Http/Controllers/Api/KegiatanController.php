<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KegiatanController extends Controller
{
    /**
     * Menampilkan semua data kegiatan
     */
    public function index()
    {
        $kegiatan = Kegiatan::all();

        return response()->json([
            'success' => true,
            'message' => 'Daftar semua kegiatan',
            'data'    => $kegiatan
        ], 200);
    }

    /**
     * Menyimpan data kegiatan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'lahan_id'   => 'required|integer',
            'id_jenis'   => 'required|integer',
            'petani_id'  => 'required|integer',
            'tanggal'    => 'required|date',
            'jumlah'     => 'required|numeric',
            'satuan'     => 'required|string|max:50',
            'keterangan' => 'nullable|string'
        ]);

        $kegiatan = Kegiatan::create([
            'lahan_id'   => $request->lahan_id,
            'id_jenis'   => $request->id_jenis,
            'petani_id'  => $request->petani_id,
            'tanggal'    => $request->tanggal,
            'jumlah'     => $request->jumlah,
            'satuan'     => $request->satuan,
            'keterangan' => $request->keterangan
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kegiatan berhasil disimpan',
            'data'    => $kegiatan
        ], 201);
    }

    /**
     * Menampilkan detail satu kegiatan berdasarkan id_kegiatan
     */
    public function show($id)
    {
        try {
            // Menggunakan findOrFail karena primaryKey custom (id_kegiatan)
            $kegiatan = Kegiatan::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Detail data kegiatan',
                'data'    => $kegiatan
            ], 200);
            
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data kegiatan tidak ditemukan'
            ], 404);
        }
    }
}