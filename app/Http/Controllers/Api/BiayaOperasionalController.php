<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BiayaOperasional;
use App\Models\DetailPengeluaran;
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
        $biaya = BiayaOperasional::with([
            'petani',
            'lahan'
        ])->find($id);

        if (!$biaya) {
            return response()->json([
                'success' => false,
                'message' => 'Data biaya operasional tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail biaya operasional berhasil diambil',
            'data' => [
                'id' => $biaya->id, // atau $biaya->biaya_id sesuai primary key Anda
                'biaya_tanggal' => $biaya->biaya_tanggal,
                'biaya_nama' => $biaya->biaya_nama,
                'biaya_jenis' => $biaya->biaya_jenis,
                'biaya_jumlah' => $biaya->biaya_jumlah,
                'biaya_total' => $biaya->biaya_total,
                'biaya_ket' => $biaya->biaya_ket,

                // PATH GAMBAR
                'biaya_bukti' => $biaya->biaya_bukti,

                // URL GAMBAR UNTUK ANDROID
                'biaya_bukti_url' => $biaya->biaya_bukti
                    ? asset('storage/' . $biaya->biaya_bukti)
                    : null,

                // DATA PETANI
                'petani' => [
                    'id' => $biaya->petani->petani_id ?? null,
                    'nama' => $biaya->petani->petani_nama ?? null
                ],

                // DATA LAHAN
                'lahan' => [
                    'id' => $biaya->lahan->lahan_id ?? null,
                    'nama' => $biaya->lahan->lahan_nama ?? null
                ]
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'biaya_tanggal' => 'required|date',
            'biaya_nama'    => 'required|string|max:255',
            'biaya_jenis'   => 'required|string|max:255',
            'biaya_jumlah'  => 'required|numeric',
            'petani_id'     => 'required|exists:petani,petani_id',
            'lahan_id'           => 'required|array',
            'lahan_id.*'         => 'integer',
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
            'biaya_total'   => $request->biaya_total,
            'biaya_ket'     => $request->biaya_ket,
            'petani_id'     => $request->petani_id,
            'biaya_bukti'   => $path
        ]);

        foreach ($request->lahan_id as $lahanId) {

            DetailBiayaOperasional::create([
                'detail_biaya_operasional_id' => $biaya->id,
                'lahan_id' => $lahanId,
            ]);

        }

        return response()->json([
            'success' => true,
            'message' => 'Data biaya operasional berhasil ditambahkan',
            'data' => $biaya
        ], 201);
    }
}