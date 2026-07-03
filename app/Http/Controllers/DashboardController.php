<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $jumlahPetaniQuery = DB::table('petani');
        $jumlahLahanQuery = DB::table('lahan')
            ->join('petani', 'lahan.petani_id', '=', 'petani.petani_id');
        $pendapatanBulanIniQuery = DB::table('produksi')
            ->join('petani', 'produksi.petani_id', '=', 'petani.petani_id');
        $petaniPendingQuery = DB::table('petani')->where('petani_status', 'Pending');
        $pemasukanDataQuery = DB::table('produksi')
            ->join('petani', 'produksi.petani_id', '=', 'petani.petani_id');
        $pengeluaranGrafikQuery = DB::table('biaya_operasional')
            ->join('petani', 'biaya_operasional.petani_id', '=', 'petani.petani_id');
        $semuaLahanQuery = DB::table('lahan')
            ->join('petani', 'lahan.petani_id', '=', 'petani.petani_id')
            ->whereNotNull('lahan.area_lahan');

        if ($user->user_role === 'admin') {
            $jumlahPetaniQuery->where('desa_id', $user->desa_id);
            $jumlahLahanQuery->where('petani.desa_id', $user->desa_id);
            $pendapatanBulanIniQuery->where('petani.desa_id', $user->desa_id);
            $petaniPendingQuery->where('desa_id', $user->desa_id);
            $pemasukanDataQuery->where('petani.desa_id', $user->desa_id);
            $pengeluaranGrafikQuery->where('petani.desa_id', $user->desa_id);
            $semuaLahanQuery->where('petani.desa_id', $user->desa_id);
        }

        $jumlahPetani = $jumlahPetaniQuery->count('petani_id');
        $jumlahLahan = $jumlahLahanQuery->sum('lahan_luas');

        $pendapatanBulanIni = $pendapatanBulanIniQuery
            ->whereMonth('produksi_tanggal', Carbon::now()->month)
            ->whereYear('produksi_tanggal', Carbon::now()->year)
            ->sum('total_pendapatan');

        $petaniPending = $petaniPendingQuery
            ->get(['petani_id', 'petani_nama', 'petani_email', 'petani_status']);

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

        $pengeluaranGrafik = $pengeluaranGrafikQuery
            ->select('biaya_jenis', DB::raw("SUM(biaya_total) as total"))
            ->groupBy('biaya_jenis')
            ->get();

        $semuaLahan = $semuaLahanQuery
            ->get([
                'lahan.lahan_id',
                'lahan.lahan_lokasi',
                'lahan.lahan_luas',
                'lahan.area_lahan',
                'petani.petani_nama'
            ]);

        $jumlahProduksiHariIni = DB::table('produksi')
            ->join('petani', 'produksi.petani_id', '=', 'petani.petani_id');

        if ($user->user_role === 'admin') {
            $jumlahProduksiHariIni->where('petani.desa_id', $user->desa_id);
        }

        $jumlahProduksiHariIni = $jumlahProduksiHariIni
            ->whereDate('produksi_tanggal', today())
            ->count();

        // notifikasi untuk popup diambil secara runtime melalui AJAX dari tabel produksi.
        // tidak lagi bergantung pada tabel notifikasi.

        if ($user->user_role === 'super_admin') {

            return view('super_admin.dashboard', compact(
                'jumlahPetani',
                'jumlahLahan',
                'pendapatanBulanIni',
                'petaniPending',
                'pemasukanGrafik',
                'pengeluaranGrafik',
                'semuaLahan',
                'jumlahProduksiHariIni'
            ));

        } elseif ($user->user_role === 'admin') {

            return view('admin.dashboard', compact(
                'jumlahPetani',
                'jumlahLahan',
                'pendapatanBulanIni',
                'petaniPending',
                'pemasukanGrafik',
                'pengeluaranGrafik',
                'semuaLahan',
                'jumlahProduksiHariIni'
            ));
        }

        abort(403, 'Anda tidak memiliki hak akses ke halaman dashboard ini.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['petani_status' => 'required|string']);

        DB::table('petani')->where('petani_id', $id)->update([
            'petani_status' => $request->petani_status,
        ]);

        return redirect()->back()->with('success', 'Status petani berhasil diperbarui!');
    }
}