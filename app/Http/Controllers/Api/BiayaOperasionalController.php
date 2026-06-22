<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BiayaOperasional;
use Illuminate\Http\Request;

class BiayaOperasionalController extends Controller
{
    public function index()
    {
        $data = BiayaOperasional::with([
            'petani',
            'lahan'
        ])->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Data biaya operasional berhasil diambil',
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $data = BiayaOperasional::with([
            'petani',
            'lahan'
        ])->find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'biaya_tanggal' => 'required|date',
            'biaya_nama'    => 'required|string|max:255',
            'biaya_jenis'   => 'required|string|max:255',
            'biaya_jumlah'  => 'required|numeric',
            'petani_id'     => 'required|exists:petani,id',
            'lahan_id'      => 'required|exists:lahan,id',
            'biaya_ket'     => 'nullable|string',

            // Upload bukti
            'biaya_bukti'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $path = null;

        if ($request->hasFile('biaya_bukti')) {

            $path = $request->file('biaya_bukti')
                ->store('bukti-biaya', 'public');
        }

        $biaya = BiayaOperasional::create([
            'biaya_tanggal' => $request->biaya_tanggal,
            'biaya_nama'    => $request->biaya_nama,
            'biaya_jenis'   => $request->biaya_jenis,
            'biaya_jumlah'  => $request->biaya_jumlah,
            'biaya_total'   => $request->biaya_jumlah,
            'biaya_ket'     => $request->biaya_ket,
            'petani_id'     => $request->petani_id,
            'lahan_id'      => $request->lahan_id,
            'biaya_bukti'   => $path
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data biaya operasional berhasil ditambahkan',
            'data' => $biaya
        ], 201);
    }
}