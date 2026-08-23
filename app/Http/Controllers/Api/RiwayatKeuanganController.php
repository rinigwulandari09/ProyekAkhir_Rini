<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Produksi;
use App\Models\BiayaOperasional;

class RiwayatKeuanganController extends Controller
{
    public function index(Request $request)
    {
        try {
            $petaniId = $request->query('petani_id') ?? $request->petani_id;
            $bulan    = $request->query('bulan') ?? $request->bulan;
            $tahun    = $request->query('tahun') ?? $request->tahun;
            $lahanId  = $request->query('lahan_id') ?? $request->lahan_id;
            $tipe     = $request->query('tipe') ?? $request->tipe ?? 'semua';

            $result = collect();

            // 1. PEMASUKAN
            if ($tipe == 'semua' || $tipe == 'pemasukan') {
                $pemasukanQuery = DB::table('produksi')
                    ->leftJoin('detail_produksi', 'produksi.id', '=', 'detail_produksi.produksi_id')
                    ->leftJoin('lahan', DB::raw("COALESCE(detail_produksi.lahan_id, produksi.lahan_id)"), '=', 'lahan.lahan_id')
                    ->select(
                        'produksi.id as id',
                        'detail_produksi.detail_produksi_id as detail_id',
                        'produksi.produksi_tanggal as tanggal',
                        DB::raw("COALESCE(detail_produksi.subtotal_pendapatan, produksi.total_pendapatan, 0) as nominal"),
                        DB::raw("COALESCE(detail_produksi.jumlah_tbs, produksi.jumlah_tbs, 0) as jumlah_tbs"),
                        DB::raw("COALESCE(detail_produksi.lahan_id, produksi.lahan_id) as lahan_id"),
                        DB::raw("COALESCE(lahan.lahan_nama, '') as lahan_nama"),
                        DB::raw("'pemasukan' as tipe"),
                        DB::raw("'Penjualan TBS' as judul"),
                        DB::raw("'produksi' as source_table")
                    );

                if ($petaniId) {
                    $pemasukanQuery->where('produksi.petani_id', $petaniId);
                }

                if ($bulan && $bulan != 'Semua Bulan') {
                    $pemasukanQuery->whereMonth('produksi.produksi_tanggal', $bulan);
                }

                if ($tahun && $tahun != 'Semua Tahun') {
                    $pemasukanQuery->whereYear('produksi.produksi_tanggal', $tahun);
                }

                if ($lahanId) {
                    $pemasukanQuery->where(function($q) use ($lahanId) {
                        $q->where('detail_produksi.lahan_id', $lahanId)
                          ->orWhere('produksi.lahan_id', $lahanId);
                    });
                }

                $listPemasukan = $pemasukanQuery->get()->map(function($item) {
                    return [
                        'id'           => (int) $item->id,
                        'detail_id'    => $item->detail_id ? (int) $item->detail_id : null,
                        'tanggal'      => (string) $item->tanggal,
                        'nominal'      => (float) $item->nominal,
                        'jumlah_tbs'   => (float) $item->jumlah_tbs,
                        'tipe'         => 'pemasukan',
                        'judul'        => (string) $item->judul,
                        'lahan_nama'   => (string) $item->lahan_nama,
                        'lahan_id'     => $item->lahan_id ? (int) $item->lahan_id : null,
                        'source_table' => 'produksi'
                    ];
                });

                $result = $result->concat($listPemasukan);
            }

            // 2. PENGELUARAN
            if ($tipe == 'semua' || $tipe == 'pengeluaran') {
                $pengeluaranQuery = DB::table('biaya_operasional')
                    ->leftJoin('detail_biaya_operasional', 'biaya_operasional.id', '=', 'detail_biaya_operasional.biaya_operasional_id')
                    ->leftJoin('lahan', DB::raw("COALESCE(detail_biaya_operasional.lahan_id, biaya_operasional.lahan_id)"), '=', 'lahan.lahan_id')
                    ->select(
                        'biaya_operasional.id as id',
                        'detail_biaya_operasional.detail_biaya_operasional_id as detail_id',
                        'biaya_operasional.biaya_tanggal as tanggal',
                        'biaya_operasional.biaya_total as nominal',
                        'biaya_operasional.biaya_jumlah as jumlah_tbs',
                        'biaya_operasional.biaya_nama as judul',
                        DB::raw("COALESCE(detail_biaya_operasional.lahan_id, biaya_operasional.lahan_id) as lahan_id"),
                        DB::raw("COALESCE(lahan.lahan_nama, '') as lahan_nama"),
                        DB::raw("'pengeluaran' as tipe"),
                        DB::raw("'biaya' as source_table")
                    );

                if ($petaniId) {
                    $pengeluaranQuery->where('biaya_operasional.petani_id', $petaniId);
                }

                if ($bulan && $bulan != 'Semua Bulan') {
                    $pengeluaranQuery->whereMonth('biaya_operasional.biaya_tanggal', $bulan);
                }

                if ($tahun && $tahun != 'Semua Tahun') {
                    $pengeluaranQuery->whereYear('biaya_operasional.biaya_tanggal', $tahun);
                }

                if ($lahanId) {
                    $pengeluaranQuery->where(function($q) use ($lahanId) {
                        $q->where('detail_biaya_operasional.lahan_id', $lahanId)
                          ->orWhere('biaya_operasional.lahan_id', $lahanId);
                    });
                }

                $listPengeluaran = $pengeluaranQuery->get()->map(function($item) {
                    return [
                        'id'           => (int) $item->id,
                        'detail_id'    => $item->detail_id ? (int) $item->detail_id : null,
                        'tanggal'      => (string) $item->tanggal,
                        'nominal'      => (float) $item->nominal,
                        'jumlah_tbs'   => (float) $item->jumlah_tbs,
                        'tipe'         => 'pengeluaran',
                        'judul'        => (string) $item->judul,
                        'lahan_nama'   => (string) $item->lahan_nama,
                        'lahan_id'     => $item->lahan_id ? (int) $item->lahan_id : null,
                        'source_table' => 'biaya'
                    ];
                });

                $result = $result->concat($listPengeluaran);
            }

            $sortedData = $result->sortByDesc('tanggal')->values();

            return response()->json([
                'success' => true,
                'data'    => $sortedData
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile()
            ], 500);
        }
    }

    // DETAIL TRANSAKSI
    public function detail(Request $request)
    {
        $id = $request->id;
        $source = $request->source;

        if ($source == "produksi") {
            $data = Produksi::with(['petani', 'detailProduksi.lahan'])->find($id);
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data produksi tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'source' => 'produksi',
                'data' => $data
            ]);
        }

        if ($source == "biaya") {
            $data = BiayaOperasional::with(['petani', 'detailBiayaOperasional.lahan'])->find($id);
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data biaya tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'source' => 'biaya',
                'data' => $data
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Source tidak valid'
        ], 400);
    }
}
