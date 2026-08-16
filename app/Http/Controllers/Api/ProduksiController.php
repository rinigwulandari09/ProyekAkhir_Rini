<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produksi;
use App\Models\Petani;
use App\Models\User;
use App\Models\DetailProduksi;
use Illuminate\Http\Request;

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
            'produksi_tanggal' => 'required',
            'jumlah_tbs'       => 'required|numeric',
            'harga_tbs'        => 'required|numeric',
            'petani_id'        => 'required',
            'lahan_id'           => 'required|array',
            'lahan_id.*'         => 'integer',
            'jumlah_produksi'    => 'nullable',
            'jumlah_tbs_detail'  => 'nullable',
            'subtotal_pendapatan' => 'nullable',
            'subtotal'           => 'nullable',
            'produksi_ket'     => 'nullable',
            'produksi_bukti'   => 'nullable'
        ]);

        $totalPendapatan = $request->total_pendapatan ?? ($request->jumlah_tbs * $request->harga_tbs);

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
            'produksi_ket'     => $request->produksi_ket,
            'produksi_bukti'   => $fotoPath
        ]);

        // Ambil array jumlah_tbs dan subtotal dari berbagai opsi key parameter
        $jumlahTbsArr = $request->input('jumlah_tbs_detail') 
            ?? $request->input('jumlah_produksi') 
            ?? (is_array($request->input('jumlah_tbs')) ? $request->input('jumlah_tbs') : []);

        $subtotalArr = $request->input('subtotal_pendapatan') 
            ?? $request->input('subtotal') 
            ?? [];

        foreach ($request->lahan_id as $key => $lahanId) {
            DetailProduksi::create([
                'produksi_id'         => $produksi->id,
                'lahan_id'            => $lahanId,
                'jumlah_tbs'          => $jumlahTbsArr[$key] ?? 0,
                'subtotal_pendapatan' => $subtotalArr[$key] ?? 0,
            ]);
        }

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
            'detailProduksi.lahan'
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
                'produksi_bukti' => $produksi->produksi_bukti,
                'produksi_bukti_url' => $produksi->produksi_bukti
                    ? asset('storage/' . $produksi->produksi_bukti)
                    : null,
                'petani' => [
                    'id' => $produksi->petani->petani_id ?? null,
                    'nama' => $produksi->petani->petani_nama ?? null
                ],
                'detail_produksi' => $produksi->detailProduksi->map(function ($detail) {
                    return [
                        'id' => $detail->id,
                        'jumlah_tbs_detail' => $detail->jumlah_tbs ?? null, 
                        'harga_tbs_detail' => $detail->harga_tbs ?? null,
                        'subtotal_pendapatan' => $detail->subtotal_pendapatan ?? null,
                        'lahan' => [
                            'id' => $detail->lahan->lahan_id ?? null,
                            'nama' => $detail->lahan->lahan_nama ?? null
                        ]
                    ];
                })
            ]
        ], 200);
    }
}
