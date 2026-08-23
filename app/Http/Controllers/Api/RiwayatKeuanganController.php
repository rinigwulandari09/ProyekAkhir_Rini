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
                $pemasukanQuery = DB::table('detail_produksi')
                    ->join('produksi', 'detail_produksi.produksi_id', '=', 'produksi.id')
                    ->join('lahan', 'detail_produksi.lahan_id', '=', 'lahan.lahan_id')
                    ->select(
                        'produksi.id as id',
                        'detail_produksi.detail_produksi_id as detail_id',
                        'produksi.produksi_tanggal as tanggal',
                        'produksi.total_pendapatan as total_nominal',
                        'produksi.jumlah_tbs as total_tbs',
                        'detail_produksi.lahan_id as lahan_id',
                        'lahan.lahan_nama as lahan_nama',
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
                    $pemasukanQuery->where('detail_produksi.lahan_id', $lahanId);
                }

                $listPemasukan = $pemasukanQuery->get();

                $lahanCounts = DB::table('detail_produksi')
                    ->select('produksi_id', DB::raw('count(*) as count_lahan'))
                    ->groupBy('produksi_id')
                    ->pluck('count_lahan', 'produksi_id');

                $pemasukanFormatted = $listPemasukan->map(function($item) use ($lahanCounts) {
                    $count = $lahanCounts[$item->id] ?? 1;
                    $nominalSplit = $count > 0 ? ((float)$item->total_nominal / $count) : (float)$item->total_nominal;
                    $tbsSplit = $count > 0 ? ((float)$item->total_tbs / $count) : (float)$item->total_tbs;

                    return [
                        'id'           => $item->id,
                        'detail_id'    => $item->detail_id,
                        'tanggal'      => $item->tanggal,
                        'nominal'      => round($nominalSplit, 2),
                        'jumlah_tbs'   => round($tbsSplit, 2),
                        'tipe'         => 'pemasukan',
                        'judul'        => $item->judul,
                        'lahan_nama'   => $item->lahan_nama,
                        'lahan_id'     => $item->lahan_id,
                        'source_table' => 'produksi'
                    ];
                });

                $result = $result->concat($pemasukanFormatted);
            }

            // 2. PENGELUARAN
            if ($tipe == 'semua' || $tipe == 'pengeluaran') {
                $pengeluaranQuery = DB::table('detail_biaya_operasional')
                    ->join('biaya_operasional', 'detail_biaya_operasional.biaya_operasional_id', '=', 'biaya_operasional.id')
                    ->join('lahan', 'detail_biaya_operasional.lahan_id', '=', 'lahan.lahan_id')
                    ->select(
                        'biaya_operasional.id as id',
                        'detail_biaya_operasional.detail_biaya_operasional_id as detail_id',
                        'biaya_operasional.biaya_tanggal as tanggal',
                        'biaya_operasional.biaya_total as total_nominal',
                        'biaya_operasional.biaya_jumlah as total_tbs',
                        'detail_biaya_operasional.lahan_id as lahan_id',
                        'lahan.lahan_nama as lahan_nama',
                        'biaya_operasional.biaya_nama as judul',
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
                    $pengeluaranQuery->where('detail_biaya_operasional.lahan_id', $lahanId);
                }

                $listPengeluaran = $pengeluaranQuery->get();

                $lahanCountsBiaya = DB::table('detail_biaya_operasional')
                    ->select('biaya_operasional_id', DB::raw('count(*) as count_lahan'))
                    ->groupBy('biaya_operasional_id')
                    ->pluck('count_lahan', 'biaya_operasional_id');

                $pengeluaranFormatted = $listPengeluaran->map(function($item) use ($lahanCountsBiaya) {
                    $count = $lahanCountsBiaya[$item->id] ?? 1;
                    $nominalSplit = $count > 0 ? ((float)$item->total_nominal / $count) : (float)$item->total_nominal;

                    return [
                        'id'           => $item->id,
                        'detail_id'    => $item->detail_id,
                        'tanggal'      => $item->tanggal,
                        'nominal'      => round($nominalSplit, 2),
                        'jumlah_tbs'   => (float)$item->total_tbs,
                        'tipe'         => 'pengeluaran',
                        'judul'        => $item->judul,
                        'lahan_nama'   => $item->lahan_nama,
                        'lahan_id'     => $item->lahan_id,
                        'source_table' => 'biaya'
                    ];
                });

                $result = $result->concat($pengeluaranFormatted);
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
