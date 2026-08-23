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

        // PEMASUKAN (Tampil per-lahan yang di-split dari detail_produksi)
        $pemasukan = DB::table('detail_produksi')
            ->join('produksi', 'detail_produksi.produksi_id', '=', 'produksi.id')
            ->join('lahan', 'detail_produksi.lahan_id', '=', 'lahan.lahan_id')
            ->select(
                'produksi.id as id',
                'detail_produksi.id as detail_id',
                'produksi.produksi_tanggal as tanggal',
                'detail_produksi.subtotal_pendapatan as nominal',
                'detail_produksi.jumlah_tbs_detail as jumlah_tbs',
                DB::raw("'pemasukan' as tipe"),
                DB::raw("'Penjualan TBS' as judul"),
                'lahan.lahan_nama as lahan_nama',
                'detail_produksi.lahan_id as lahan_id',
                DB::raw("'produksi' as source_table")
            )
            ->where('produksi.petani_id', $petaniId);

        // PENGELUARAN (Tampil per-lahan yang di-split dari detail_biaya_operasional)
        $pengeluaran = DB::table('detail_biaya_operasional')
            ->join('biaya_operasional', 'detail_biaya_operasional.biaya_operasional_id', '=', 'biaya_operasional.id')
            ->join('lahan', 'detail_biaya_operasional.lahan_id', '=', 'lahan.lahan_id')
            ->select(
                'biaya_operasional.id as id',
                'detail_biaya_operasional.id as detail_id',
                'biaya_operasional.biaya_tanggal as tanggal',
                DB::raw("COALESCE(detail_biaya_operasional.subtotal, biaya_operasional.biaya_total) as nominal"),
                DB::raw("biaya_operasional.biaya_jumlah as jumlah_tbs"),
                DB::raw("'pengeluaran' as tipe"),
                'biaya_operasional.biaya_nama as judul',
                'lahan.lahan_nama as lahan_nama',
                'detail_biaya_operasional.lahan_id as lahan_id',
                DB::raw("'biaya' as source_table")
            )
            ->where('biaya_operasional.petani_id', $petaniId);

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
            $data = Produksi::with('detailProduksi.lahan')->findOrFail($id);

            return response()->json([
                'success' => true,
                'source' => 'produksi',
                'data' => $data
            ]);
        }

        if ($source == "biaya") {
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
