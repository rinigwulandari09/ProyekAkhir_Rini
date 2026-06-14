<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BiayaOperasional;
use Illuminate\Http\Request;

class BiayaOperasionalController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => BiayaOperasional::all()
        ]);
    }

    public function store(Request $request)
    {
        $biaya = BiayaOperasional::create([
            'biaya_tanggal' => $request->biaya_tanggal,
            'biaya_jenis' => $request->biaya_jenis,
            'biaya_jumlah' => $request->biaya_jumlah,
            'biaya_ket' => $request->biaya_ket,
            'petani_id' => $request->petani_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data biaya berhasil ditambahkan',
            'data' => $biaya
        ]);
    }

    public function show($id)
    {
        return response()->json(
            BiayaOperasional::findOrFail($id)
        );
    }
}