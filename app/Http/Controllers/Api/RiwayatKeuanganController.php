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
        $petaniId = $request->query('petani_id') ?? $request->petani_id;
        $bulan    = $request->query('bulan') ?? $request->bulan;
        $tahun    = $request->query('tahun') ?? $request->tahun;
        $lahanId  = $request->query('lahan_id') ?? $request->lahan_id;
        $tipe     = $request->query('tipe') ?? $request->tipe ?? 'semua';

        // 1. PEMASUKAN
        $pemasukanQuery = DB::table('produksi')
            ->leftJoin('detail_produksi', 'produksi.id', '=', 'detail_produksi.produksi_id')
            ->leftJoin('lahan', 'detail_produksi.lahan_id', '=', 'lahan.lahan_id')
            ->select(
                'produksi.id as id',
                'detail_produksi.id as detail_id',
                'produksi.produksi_tanggal as tanggal',
                DB::raw("COALESCE(detail_produksi.subtotal_pendapatan, produksi.total_pendapatan, 0) as nominal"),
                DB::raw("COALESCE(detail_produksi.jumlah_tbs, produksi.jumlah_tbs, 0) as jumlah_tbs"),
                DB::raw("'pemasukan' as tipe"),
                DB::raw("'Penjualan TBS' as judul"),
                DB::raw("COALESCE(lahan.lahan_nama, '') as lahan_nama"),
                'detail_produksi.lahan_id as lahan_id',
                DB::raw("'produksi' as source_table")
            );

        if ($petaniId) {
            $pemasukanQuery->where('produksi.petani_id', $petaniId);
        }

        // 2. PENGELUARAN
        $pengeluaranQuery = DB::table('biaya_operasional')
            ->leftJoin('detail_biaya_operasional', 'biaya_operasional.id', '=', 'detail_biaya_operasional.biaya_operasional_id')
            ->leftJoin('lahan', 'detail_biaya_operasional.lahan_id', '=', 'lahan.lahan_id')
            ->select(
                'biaya_operasional.id as id',
                'detail_biaya_operasional.id as detail_id',
                'biaya_operasional.biaya_tanggal as tanggal',
                DB::raw("COALESCE(detail_biaya_operasional.subtotal, biaya_operasional.biaya_total, 0) as nominal"),
                DB::raw("COALESCE(biaya_operasional.biaya_jumlah, 0) as jumlah_tbs"),
                DB::raw("'pengeluaran' as tipe"),
                'biaya_operasional.biaya_nama as judul',
                DB::raw("COALESCE(lahan.lahan_nama, '') as lahan_nama"),
                'detail_biaya_operasional.lahan_id as lahan_id',
                DB::raw("'biaya' as source_table")
            );

        if ($petaniId) {
            $pengeluaranQuery->where('biaya_operasional.petani_id', $petaniId);
        }

        // FILTER BULAN
        if ($bulan && $bulan != 'Semua Bulan') {
            $pemasukanQuery->whereMonth('produksi.produksi_tanggal', $bulan);
            $pengeluaranQuery->whereMonth('biaya_operasional.biaya_tanggal', $bulan);
        }

        // FILTER TAHUN
        if ($tahun && $tahun != 'Semua Tahun') {
            $pemasukanQuery->whereYear('produksi.produksi_tanggal', $tahun);
            $pengeluaranQuery->whereYear('biaya_operasional.biaya_tanggal', $tahun);
        }

        // FILTER LAHAN
        if ($lahanId) {
            $pemasukanQuery->where(function($q) use ($lahanId) {
                $q->where('detail_produksi.lahan_id', $lahanId);
            });
            $pengeluaranQuery->where(function($q) use ($lahanId) {
                $q->where('detail_biaya_operasional.lahan_id', $lahanId);
            });
        }

        if ($tipe == 'pemasukan') {
            $data = $pemasukanQuery->orderByDesc('produksi.produksi_tanggal')->get();
        } else if ($tipe == 'pengeluaran') {
            $data = $pengeluaranQuery->orderByDesc('biaya_operasional.biaya_tanggal')->get();
        } else {
            $dataPemasukan = $pemasukanQuery->get();
            $dataPengeluaran = $pengeluaranQuery->get();
            $data = $dataPemasukan->concat($dataPengeluaran)->sortByDesc('tanggal')->values();
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
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
