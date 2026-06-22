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

    public function store(Request $request)
    {
        $request->validate([
            'produksi_tanggal' => 'required|date',
            'jumlah_tbs'       => 'required|numeric',
            'harga_tbs'        => 'required|numeric',
            'petani_id'        => 'required|exists:petani,petani_id',
            'lahan_id'         => 'required|exists:lahan,lahan_id',
            'produksi_ket'     => 'nullable|string',

            // TAMBAHAN
            'produksi_bukti'   => 'nullable|image|mimes:jpg,jpeg,png|max:5120'
        ]);

        $totalPendapatan = $request->jumlah_tbs * $request->harga_tbs;

        // SIMPAN FOTO
        $fotoPath = null;

        if ($request->hasFile('produksi_bukti')) {
            $file = $request->file('produksi_bukti');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $fotoPath = $file->storeAs(
                'produksi_bukti',
                $namaFile,
                'public'
            );
        }

        $produksi = Produksi::create([
            'produksi_tanggal' => $request->produksi_tanggal,
            'jumlah_tbs'       => $request->jumlah_tbs,
            'harga_tbs'        => $request->harga_tbs,
            'total_pendapatan' => $totalPendapatan,
            'status_validasi'  => 'Pending',
            'petani_id'        => $request->petani_id,
            'lahan_id'         => $request->lahan_id,
            'produksi_ket'     => $request->produksi_ket,

            // TAMBAHAN
            'produksi_bukti'   => $fotoPath
        ]);

        // AMBIL DATA PETANI
        $petani = Petani::find($request->petani_id);

        // BUAT NOTIFIKASI
        NotifikasiHelper::create(
            'superadmin',
            'Produksi Baru',
            $petani->petani_nama . ' menambahkan data produksi',
            'produksi'
        );

        return response()->json([
            'success' => true,
            'message' => 'Data produksi berhasil ditambahkan',
            'data' => [
                'id' => $produksi->id,
                'produksi_tanggal' => $produksi->produksi_tanggal,
                'jumlah_tbs' => $produksi->jumlah_tbs,
                'harga_tbs' => $produksi->harga_tbs,
                'total_pendapatan' => $produksi->total_pendapatan,
                'produksi_bukti' => $produksi->produksi_bukti,

                // URL YANG BISA DIPAKAI GLIDE
                'produksi_bukti_url' => $fotoPath
                    ? asset('storage/' . $fotoPath)
                    : null
            ]
        ], 201);
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
                'message' => 'Data produksi tidak ditemukan'
            ], 404);

        }

        return response()->json([
            'success' => true,
            'message' => 'Detail produksi berhasil diambil',
            'data' => [
                'id' => $produksi->id,
                'produksi_tanggal' => $produksi->produksi_tanggal,
                'jumlah_tbs' => $produksi->jumlah_tbs,
                'harga_tbs' => $produksi->harga_tbs,
                'total_pendapatan' => $produksi->total_pendapatan,
                'status_validasi' => $produksi->status_validasi,
                'produksi_ket' => $produksi->produksi_ket,

                // PATH GAMBAR
                'produksi_bukti' => $produksi->produksi_bukti,

                // URL GAMBAR UNTUK ANDROID
                'produksi_bukti_url' => $produksi->produksi_bukti
                    ? asset('storage/' . $produksi->produksi_bukti)
                    : null,

                // DATA PETANI
                'petani' => [
                    'id' => $produksi->petani->petani_id ?? null,
                    'nama' => $produksi->petani->petani_nama ?? null
                ],

                // DATA LAHAN
                'lahan' => [
                    'id' => $produksi->lahan->lahan_id ?? null,
                    'nama' => $produksi->lahan->lahan_nama ?? null
                ]

            ]

        ], 200);
    }
}