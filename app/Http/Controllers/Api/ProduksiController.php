<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produksi;
use App\Models\Petani;
use App\Models\User;
use App\Models\DetailProduksi;
use App\Models\Lahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProduksiController extends Controller
{
    public function index()
    {
        $produksi = Produksi::with([
            'petani',
            'detailProduksi.lahan'
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
            'produksi_tanggal'    => 'required',
            'jumlah_tbs'          => 'required|numeric',
            'harga_tbs'           => 'required|numeric',
            'petani_id'           => 'required',
            'lahan_id'            => 'required',
            'jumlah_produksi'     => 'nullable',
            'jumlah_tbs_detail'   => 'nullable',
            'subtotal_pendapatan' => 'nullable',
            'subtotal'            => 'nullable',
            'produksi_ket'        => 'nullable',
            'produksi_bukti'      => 'nullable'
        ]);

        // CEK DUPLIKASI ENTRY SECARA AMAN (Memeriksa created_at hanya jika kolom tersebut ada)
        $queryDuplicate = Produksi::where('petani_id', $request->petani_id)
            ->where('produksi_tanggal', $request->produksi_tanggal)
            ->where('jumlah_tbs', $request->jumlah_tbs)
            ->where('harga_tbs', $request->harga_tbs);

        if ($request->filled('produksi_ket')) {
            $queryDuplicate->where('produksi_ket', $request->produksi_ket);
        }

        if (Schema::hasColumn('produksi', 'created_at')) {
            $queryDuplicate->where('created_at', '>=', now()->subSeconds(10));
        }

        $existing = $queryDuplicate->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => 'Data produksi sudah tersimpan sebelumnya',
                'data' => [
                    'id' => $existing->id,
                    'produksi_tanggal' => $existing->produksi_tanggal,
                    'jumlah_tbs' => $existing->jumlah_tbs,
                    'harga_tbs' => $existing->harga_tbs,
                    'total_pendapatan' => $existing->total_pendapatan,
                    'produksi_bukti' => $existing->produksi_bukti,
                    'produksi_bukti_url' => $existing->produksi_bukti
                        ? asset('storage/' . $existing->produksi_bukti)
                        : null
                ]
            ], 200);
        }

        // Pastikan lahan_id dalam bentuk array
        $lahanIds = is_array($request->lahan_id) ? $request->lahan_id : [$request->lahan_id];

        // Total pendapatan keseluruhan
        $totalPendapatan = $request->total_pendapatan ?? ($request->jumlah_tbs * $request->harga_tbs);

        // SIMPAN FOTO BUKTI
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

        DB::beginTransaction();
        try {
            // 1. SIMPAN 1 DATA UTAMA PRODUKSI (TOTAL KESELURUHAN)
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

            // 2. AMBIL DATA LAHAN DENGAN FALLBACK AMAN
            $lahans = collect();
            if (class_exists(Lahan::class)) {
                try {
                    $lahans = Lahan::whereIn('lahan_id', $lahanIds)->get();
                    if ($lahans->isEmpty()) {
                        $lahans = Lahan::whereIn('id', $lahanIds)->get();
                    }
                } catch (\Exception $ex) {
                    try {
                        $lahans = DB::table('lahan')->whereIn('lahan_id', $lahanIds)->get();
                    } catch (\Exception $e) {
                        $lahans = DB::table('lahan')->whereIn('id', $lahanIds)->get();
                    }
                }
            } else {
                try {
                    $lahans = DB::table('lahan')->whereIn('lahan_id', $lahanIds)->get();
                } catch (\Exception $e) {
                    $lahans = DB::table('lahan')->whereIn('id', $lahanIds)->get();
                }
            }

            // Hitung total luas lahan terpilih
            $totalLuasLahan = $lahans->sum(function ($lahan) {
                return $lahan->lahan_luas ?? $lahan->luas_lahan ?? $lahan->luas ?? 0;
            });

            $countLahan = count($lahanIds);

            // Ambil array detail jika dikirim dari request
            $jumlahTbsArr = $request->input('jumlah_tbs_detail') 
                ?? $request->input('jumlah_produksi') 
                ?? [];

            $subtotalArr = $request->input('subtotal_pendapatan') 
                ?? $request->input('subtotal') 
                ?? [];

            // 3. SIMPAN KE TABEL DETAIL_PRODUKSI SEBANYAK LAHAN YANG DIPILIH
            foreach ($lahanIds as $key => $lahanId) {
                $lahanModel = $lahans->firstWhere('lahan_id', $lahanId) ?? $lahans->firstWhere('id', $lahanId);
                $luasLahan = $lahanModel ? ($lahanModel->lahan_luas ?? $lahanModel->luas_lahan ?? $lahanModel->luas ?? 0) : 0;

                // Split Jumlah TBS
                if (isset($jumlahTbsArr[$key]) && (float)$jumlahTbsArr[$key] > 0) {
                    $jumlahTbsDetail = (float)$jumlahTbsArr[$key];
                } else {
                    if ($totalLuasLahan > 0) {
                        $jumlahTbsDetail = ($request->jumlah_tbs / $totalLuasLahan) * $luasLahan;
                    } else {
                        $jumlahTbsDetail = $request->jumlah_tbs / $countLahan;
                    }
                }

                // Split Subtotal Pendapatan
                if (isset($subtotalArr[$key]) && (float)$subtotalArr[$key] > 0) {
                    $subtotalPendapatanDetail = (float)$subtotalArr[$key];
                } else {
                    if ($totalLuasLahan > 0) {
                        $subtotalPendapatanDetail = ($totalPendapatan / $totalLuasLahan) * $luasLahan;
                    } else {
                        $subtotalPendapatanDetail = $totalPendapatan / $countLahan;
                    }
                }

                DetailProduksi::create([
                    'produksi_id'         => $produksi->id,
                    'lahan_id'            => $lahanId,
                    'jumlah_tbs'          => round($jumlahTbsDetail, 2),
                    'subtotal_pendapatan' => round($subtotalPendapatanDetail, 2),
                ]);
            }

            DB::commit();

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

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data produksi: ' . $e->getMessage()
            ], 500);
        }
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
                            'id' => $detail->lahan->lahan_id ?? $detail->lahan->id ?? null,
                            'nama' => $detail->lahan->lahan_nama ?? $detail->lahan->nama ?? null
                        ]
                    ];
                })
            ]
        ], 200);
    }
}
