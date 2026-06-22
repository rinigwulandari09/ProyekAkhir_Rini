<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produksi;
use App\Models\Petani;
use Illuminate\Http\Request;
use App\Helpers\NotifikasiHelper;

class ProduksiController extends Controller
{
    public function index()
    {
        $produksi = Produksi::with([
            'petani',
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
            'petani_id'        => 'required|exists:petani,petani_id',
            'lahan_id'         => 'required|exists:lahan,lahan_id',
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
            'lahan_id'         => $request->lahan_id,
            'produksi_ket'     => $request->produksi_ket
        ]);

        // AMBIL DATA PETANI
        $petani = Petani::find($request->petani_id);

        // BUAT NOTIFIKASI
        NotifikasiHelper::create(
            'super_admin',
            'Produksi Baru',
            $petani->petani_nama . ' menambahkan data produksi',
            'produksi'
        );

        return response()->json([
            'success' => true,
            'message' => 'Data produksi berhasil ditambahkan',
            'data' => $produksi
        ], 201);
    }
}