<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Notifikasi;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. STATISTIK UTAMA
        $jumlahPetani = DB::table('petani')->count('petani_id'); 
        $jumlahLahan = DB::table('lahan')->sum('lahan_luas');

        $pendapatanBulanIni = DB::table('produksi')
            ->whereMonth('produksi_tanggal', Carbon::now()->month)
            ->whereYear('produksi_tanggal', Carbon::now()->year)
            ->sum('total_pendapatan');

        // 2. TABEL VERIFIKASI
        $petaniPending = DB::table('petani')
            ->where('petani_status', 'Pending')
            ->get(['petani_id', 'petani_nama', 'petani_email', 'petani_status']);

        // 3. GRAFIK LINE PEMASUKAN
        $pemasukanData = DB::table('produksi')
            ->select(DB::raw("DATE_PART('month', produksi_tanggal) as bulan"), DB::raw("SUM(total_pendapatan) as total"))
            ->whereYear('produksi_tanggal', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get();

        // Jika kolom tanggal Anda bernama 'produksi_tanggal', pastikan query di atas sesuai
        // Kode di bawah ini mengantisipasi jika ada perbedaan penulisan kolom produksi_tanggal
        try {
            $pemasukanData = DB::table('produksi')
                ->select(DB::raw("DATE_PART('month', produksi_tanggal) as bulan"), DB::raw("SUM(total_pendapatan) as total"))
                ->whereYear('produksi_tanggal', date('Y'))
                ->groupBy('bulan')
                ->get();
        } catch (\Exception $e) {
            $pemasukanData = [];
        }

        $pemasukanGrafik = array_fill(1, 12, 0); 
        foreach ($pemasukanData as $data) {
            $pemasukanGrafik[(int)$data->bulan] = (int)$data->total;
        }

        // GRAFIK PIE PENGELUARAN
        $pengeluaranGrafik = DB::table('biaya_operasional')
            ->select('biaya_jenis', DB::raw("SUM(biaya_total) as total"))
            ->groupBy('biaya_jenis')
            ->get();

        // --- FILTER KEAMANAN DATABASES LAHAN + JOIN PETANI ---
        $semuaLahan = DB::table('lahan')
            ->join('petani', 'lahan.petani_id', '=', 'petani.petani_id') // Melakukan JOIN untuk mengambil nama petani
            ->whereNotNull('lahan.area_lahan')
            ->get([
                'lahan.lahan_id', 
                'lahan.lahan_lokasi', 
                'lahan.lahan_luas', 
                'lahan.area_lahan',
                'petani.petani_nama' // Mengambil kolom nama petani
            ]);

        $user = auth()->user();

        if ($user->user_role === 'super_admin') {

            $notifikasi = Notifikasi::where('target', 'superadmin')
                ->latest()
                ->take(10)
                ->get();

            $unreadCount = Notifikasi::where('target', 'superadmin')
                ->where('is_read', false)
                ->count();

        } else {

            $notifikasi = Notifikasi::where('target', 'admin')
                ->where('user_id', $user->user_id)
                ->latest()
                ->take(10)
                ->get();

            $unreadCount = Notifikasi::where('target', 'admin')
                ->where('user_id', $user->user_id)
                ->where('is_read', false)
                ->count();
        }

        // 
        $jumlahProduksiHariIni = DB::table('produksi')
        ->whereDate('created_at', today())
        ->count();

        if ($user->user_role === 'super_admin') {

            return view('super_admin.dashboard', compact(
                'jumlahPetani',
                'jumlahLahan',
                'pendapatanBulanIni',
                'petaniPending',
                'pemasukanGrafik',
                'pengeluaranGrafik',
                'semuaLahan',
                'notifikasi',
                'unreadCount',
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
                'notifikasi',
                'unreadCount',
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