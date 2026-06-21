<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produksi;
use Illuminate\Http\Request;

class ProduksiController extends Controller
{
    public function index()
    {
        $produksi = Produksi::with([
            'petani',
            'desa',
            'lahan'
        ])->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Data produksi berhasil diambil',
            'data' => $produksi
        ]);
    }

    public function show($id)
    {
        $produksi = Produksi::with([
            'petani',
            'desa',
            'lahan'
        ])->find($id);

        if (!$produksi) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $produksi
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'produksi_tanggal' => 'required|date',
            'jumlah_tbs'       => 'required|numeric',
            'harga_tbs'        => 'required|numeric',
            'petani_id'        => 'required|exists:petani,id',
            'desa_id'          => 'required|exists:desa,id',
            'lahan_id'         => 'required|exists:lahan,id',
            'produksi_ket'     => 'nullable|string'
        ]);

        $totalPendapatan = $request->jumlah_tbs * $request->harga_tbs;

        $produksi = Produksi::create([
            'produksi_tanggal' => $request->produksi_tanggal,
            'jumlah_tbs'       => $request->jumlah_tbs,
            'harga_tbs'        => $request->harga_tbs,
            'total_pendapatan' => $totalPendapatan,
            'status_validasi'  => 'Pending',
            'petani_id'        => $request->petani_id,
            'desa_id'          => $request->desa_id,
            'lahan_id'         => $request->lahan_id,
            'produksi_ket'     => $request->produksi_ket
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data produksi berhasil ditambahkan',
            'data' => $produksi
        ], 201);
    }
}