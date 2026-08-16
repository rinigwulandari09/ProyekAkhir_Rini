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
            'produksi_tanggal' => 'required|date',
            'jumlah_tbs'       => 'required|numeric',
            'harga_tbs'        => 'required|numeric',
            'petani_id'        => 'required|exists:petani,petani_id',
            'lahan_id'           => 'required|array',
            'lahan_id.*'         => 'integer',
            'jumlah_produksi'    => 'nullable|array',
            'subtotal_pendapatan' => 'nullable|array',
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
            'produksi_ket'     => $request->produksi_ket,

            // TAMBAHAN
            'produksi_bukti'   => $fotoPath
        ]);

        foreach ($request->lahan_id as $key => $lahanId) {

            DetailProduksi::create([
                'produksi_id'         => $produksi->id,
                'lahan_id'            => $lahanId,
                'jumlah_tbs'          => $request->jumlah_produksi[$key] ?? 0,
                'subtotal_pendapatan' => $request->subtotal_pendapatan[$key] ?? 0,
            ]);

        }

        // AMBIL DATA PETANI
        $petani = Petani::find($request->petani_id);

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
    // Kita panggil detail_produksi beserta data lahan yang ada di dalam masing-masing detail
    $produksi = Produksi::with([
        'petani',
        'detailProduksi.lahan' // nested eager loading: mengambil detail dan lahannya
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

            // DATA DETAIL PRODUKSI DAN LAHANNYA
            'detail_produksi' => $produksi->detailProduksi->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    // Silakan sesuaikan field dari tabel detail_produksi Anda di bawah ini:
                    'jumlah_tbs_detail' => $detail->jumlah_tbs ?? null, 
                    'harga_tbs_detail' => $detail->harga_tbs ?? null,
                    
                    // DATA LAHAN (Diambil dari relasi per detail produksi)
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