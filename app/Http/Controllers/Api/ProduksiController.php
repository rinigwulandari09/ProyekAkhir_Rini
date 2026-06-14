<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produksi;
use Illuminate\Http\Request;

class ProduksiController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Produksi::all()
        ]);
    }

    public function store(Request $request)
    {
        $produksi = Produksi::create([
            'produksi_tanggal' => $request->produksi_tanggal,
            'jumlah_tbs' => $request->jumlah_tbs,
            'harga_tbs' => $request->harga_tbs,
            'total_pendapatan' => $request->jumlah_tbs * $request->harga_tbs,
            'status_validasi' => 'Menunggu Validasi',
            'petani_id' => $request->petani_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data produksi berhasil ditambahkan',
            'data' => $produksi
        ]);
    }

    public function show($id)
    {
        return response()->json(
            Produksi::findOrFail($id)
        );
    }
}