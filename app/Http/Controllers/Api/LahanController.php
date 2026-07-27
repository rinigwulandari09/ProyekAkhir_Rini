<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lahan;
use Illuminate\Http\Request;

class LahanController extends Controller
{
    // Ambil semua lahan
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Lahan::orderBy('lahan_id')->get()
        ]);
    }

    // Ambil lahan berdasarkan petani
    public function getByPetani($petaniId)
    {
        $lahan = Lahan::where('petani_id', $petaniId)->get();

        return response()->json([
            'success' => true,
            'data' => $lahan
        ]);
    }

    // Tambah lahan
    public function store(Request $request)
    {
        $request->validate([
            'petani_id' => 'required',
            'lahan_nama' => 'required',
            'lahan_lokasi' => 'required',
            'lahan_luas' => 'required',
            'area_lahan' => 'required'
        ]);

        $lahan = Lahan::create([
            'petani_id' => $request->petani_id,
            'lahan_nama' => $request->lahan_nama,
            'lahan_lokasi' => $request->lahan_lokasi,
            'lahan_luas' => $request->lahan_luas,
            'area_lahan' => $request->area_lahan
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lahan berhasil ditambahkan',
            'data' => $lahan
        ], 201);
    }

    // Update lahan (tahun_tanam dan lahan_no_surat)
    public function update(Request $request, $lahan_id)
    {
        $request->validate([
            'tahun_tanam' => 'nullable|string',
            'lahan_no_surat' => 'nullable|string',
        ]);

        $lahan = Lahan::find($lahan_id);
        
        if (!$lahan) {
            return response()->json([
                'success' => false,
                'message' => 'Lahan tidak ditemukan'
            ], 404);
        }

        if ($request->has('tahun_tanam')) {
            $lahan->tahun_tanam = $request->tahun_tanam;
        }
        if ($request->has('lahan_no_surat')) {
            $lahan->lahan_no_surat = $request->lahan_no_surat;
        }
        
        $lahan->save();

        return response()->json([
            'success' => true,
            'message' => 'Data lahan berhasil diperbarui',
            'data' => $lahan
        ]);
    }
}