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
}
