<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardApiController extends Controller
{
    public function index()
    {
        try {
            // Jumlah Petani
            $jumlahPetani = DB::table('petani')->count('petani_id');
            
            // Jumlah Luas Lahan
            $jumlahLahan = DB::table('lahan')
                ->join('petani', 'lahan.petani_id', '=', 'petani.petani_id')
                ->sum('lahan_luas');

            // Pemasukan per bulan
            $pemasukanDataQuery = DB::table('produksi')
                ->join('petani', 'produksi.petani_id', '=', 'petani.petani_id');
                
            $pemasukanData = $pemasukanDataQuery
                ->select(DB::raw("DATE_PART('month', produksi_tanggal) as bulan"), DB::raw("SUM(total_pendapatan) as total"))
                ->whereYear('produksi_tanggal', date('Y'))
                ->groupBy('bulan')
                ->orderBy('bulan', 'asc')
                ->get();

            $pemasukanGrafik = array_fill(1, 12, 0);
            foreach ($pemasukanData as $data) {
                $pemasukanGrafik[(int)$data->bulan] = (int)$data->total;
            }

            // Pengeluaran per kategori
            $pengeluaranGrafikQuery = DB::table('biaya_operasional')
                ->join('petani', 'biaya_operasional.petani_id', '=', 'petani.petani_id');
                
            $pengeluaranData = $pengeluaranGrafikQuery
                ->select('biaya_jenis', DB::raw("SUM(biaya_total) as total"))
                ->groupBy('biaya_jenis')
                ->get();

            $pengeluaranGrafik = [];
            foreach ($pengeluaranData as $data) {
                $pengeluaranGrafik[] = [
                    'jenis' => $data->biaya_jenis,
                    'total' => (int)$data->total
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'jumlah_petani' => $jumlahPetani,
                    'jumlah_lahan' => (float)$jumlahLahan,
                    'pemasukan' => array_values($pemasukanGrafik), // 12 item (Jan-Dec)
                    'pengeluaran' => $pengeluaranGrafik
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dashboard: ' . $e->getMessage()
            ], 500);
        }
    }

    public function petaniSummary($petani_id)
    {
        try {
            $now = Carbon::now();
            $currentMonth = $now->month;
            $currentYear = $now->year;

            $lastMonthDate = $now->copy()->subMonth();
            $lastMonth = $lastMonthDate->month;
            $lastMonthYear = $lastMonthDate->year;

            // Pemasukan
            $pemasukanBulanIni = DB::table('produksi')
                ->where('petani_id', $petani_id)
                ->whereMonth('produksi_tanggal', $currentMonth)
                ->whereYear('produksi_tanggal', $currentYear)
                ->sum('total_pendapatan');

            $pemasukanBulanLalu = DB::table('produksi')
                ->where('petani_id', $petani_id)
                ->whereMonth('produksi_tanggal', $lastMonth)
                ->whereYear('produksi_tanggal', $lastMonthYear)
                ->sum('total_pendapatan');

            // Pengeluaran
            $pengeluaranBulanIni = DB::table('biaya_operasional')
                ->where('petani_id', $petani_id)
                ->whereMonth('biaya_tanggal', $currentMonth)
                ->whereYear('biaya_tanggal', $currentYear)
                ->sum('biaya_total');

            $pengeluaranBulanLalu = DB::table('biaya_operasional')
                ->where('petani_id', $petani_id)
                ->whereMonth('biaya_tanggal', $lastMonth)
                ->whereYear('biaya_tanggal', $lastMonthYear)
                ->sum('biaya_total');

            return response()->json([
                'success' => true,
                'data' => [
                    'pemasukan_bulan_ini' => (float) $pemasukanBulanIni,
                    'pemasukan_bulan_lalu' => (float) $pemasukanBulanLalu,
                    'pengeluaran_bulan_ini' => (float) $pengeluaranBulanIni,
                    'pengeluaran_bulan_lalu' => (float) $pengeluaranBulanLalu,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil summary petani: ' . $e->getMessage()
            ], 500);
        }
    }
}
