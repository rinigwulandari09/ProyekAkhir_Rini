<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisKegiatan;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JenisKegiatanController extends Controller
{
    /**
     * Menampilkan semua daftar jenis kegiatan
     */
    public function index()
    {
        $data = JenisKegiatan::orderBy('id_jenis')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar jenis kegiatan berhasil diambil',
            'data'    => $data
        ], 200);
    }

    /**
     * Menampilkan detail satu jenis kegiatan berdasarkan id_jenis
     */
    public function show($id)
    {
        try {
            // Menggunakan findOrFail untuk mencari berdasarkan primaryKey custom (id_jenis)
            $jenisKegiatan = JenisKegiatan::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Detail jenis kegiatan ditemukan',
                'data'    => $jenisKegiatan
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis kegiatan tidak ditemukan'
            ], 404);
        }
    }
}