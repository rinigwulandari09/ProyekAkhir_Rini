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
        $petaniId = $request->petani_id;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $lahanId = $request->lahan_id;
        $tipe = $request->tipe;

        // PEMASUKAN (Diubah menggunakan Join ke tabel detail_produksi)
        $pemasukan = DB::table('produksi')
            ->join('detail_produksi', 'produksi.id', '=', 'detail_produksi.produksi_id')
            ->join('lahan', 'detail_produksi.lahan_id', '=', 'lahan.lahan_id')
            ->select(
                'produksi.id',
                'produksi.produksi_tanggal as tanggal',
                'produksi.total_pendapatan as nominal',
                DB::raw("'pemasukan' as tipe"),
                DB::raw("'Penjualan TBS' as judul"),
                // Menggabungkan nama-nama lahan produksi
                DB::raw("string_agg(lahan.lahan_nama, ', ') as lahan_nama"),
                DB::raw("'produksi' as source_table")
            )
            ->where('produksi.petani_id', $petaniId)
            ->groupBy(
                'produksi.id',
                'produksi.produksi_tanggal',
                'produksi.total_pendapatan'
            );

        
        // PENGELUARAN (Menggunakan Join ke tabel detail_biaya_operasional)
        $pengeluaran = DB::table('biaya_operasional')
            ->join('detail_biaya_operasional', 'biaya_operasional.id', '=', 'detail_biaya_operasional.biaya_operasional_id')
            ->join('lahan', 'detail_biaya_operasional.lahan_id', '=', 'lahan.lahan_id')
            ->select(
                'biaya_operasional.id',
                'biaya_operasional.biaya_tanggal as tanggal',
                'biaya_operasional.biaya_total as nominal',
                DB::raw("'pengeluaran' as tipe"),
                'biaya_operasional.biaya_nama as judul',
                // Menggabungkan nama-nama lahan pengeluaran
                DB::raw("string_agg(lahan.lahan_nama, ', ') as lahan_nama"), 
                DB::raw("'biaya' as source_table")
            )
            ->where('biaya_operasional.petani_id', $petaniId)
            ->groupBy(
                'biaya_operasional.id', 
                'biaya_operasional.biaya_tanggal', 
                'biaya_operasional.biaya_total', 
                'biaya_operasional.biaya_nama'
            );

        // FILTER BULAN
        if ($bulan) {
            $pemasukan->whereMonth('produksi.produksi_tanggal', $bulan);
            $pengeluaran->whereMonth('biaya_operasional.biaya_tanggal', $bulan);
        }

        // FILTER TAHUN
        if ($tahun) {
            $pemasukan->whereYear('produksi.produksi_tanggal', $tahun);
            $pengeluaran->whereYear('biaya_operasional.biaya_tanggal', $tahun);
        }

        // FILTER LAHAN
        if ($lahanId) {
            // Filter berdasarkan lahan yang ada di detail masing-masing
            $pemasukan->where('detail_produksi.lahan_id', $lahanId);
            $pengeluaran->where('detail_biaya_operasional.lahan_id', $lahanId);
        }

        // HANYA PEMASUKAN
        if ($tipe == 'pemasukan') {
            $data = $pemasukan->orderByDesc('tanggal')->get();
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        // HANYA PENGELUARAN
        if ($tipe == 'pengeluaran') {
            $data = $pengeluaran->orderByDesc('tanggal')->get();
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        // SEMUA
        $data = DB::query()
            ->fromSub($pemasukan->unionAll($pengeluaran), 'combined_records')
            ->orderByDesc('tanggal')
            ->get();

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
            // Memuat relasi detailProduksi beserta data lahan di dalamnya
            $data = Produksi::with('detailProduksi.lahan')->findOrFail($id);

            return response()->json([
                'success' => true,
                'source' => 'produksi',
                'data' => $data
            ]);
        }

        if ($source == "biaya") {
            // Memuat relasi detailBiayaOperasional beserta data lahan di dalamnya
            $data = BiayaOperasional::with('detailBiayaOperasional.lahan')->findOrFail($id);

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