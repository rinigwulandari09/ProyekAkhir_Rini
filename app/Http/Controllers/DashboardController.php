<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. STATISTIK UTAMA
        // Total petani berdasarkan id_petani
        $jumlahPetani = DB::table('petani')->count('petani_id'); 

        // Total luas lahan dari kolom luas_lahan
        $jumlahLahan = DB::table('lahan')->sum('lahan_luas');

        // Total pendapatan bulan ini dari tabel produksi (filter bulan berjalan)
        $pendapatanBulanIni = DB::table('produksi')
            ->whereMonth('produksi_tanggal', Carbon::now()->month)
            ->whereYear('produksi_tanggal', Carbon::now()->year)
            ->sum('total_pendapatan');


        // 2. TABEL VERIFIKASI
        // Mengambil data petani yang statusnya masih pending
        $petaniPending = DB::table('petani')
            ->where('petani_status', 'pending')
            ->get(['petani_id', 'petani_nama', 'petani_email', 'petani_status']);


        //GRAFIK (Dikirim dalam bentuk array/JSON untuk dibaca Chart.js nanti)

        // Ambil data Pemasukan per Bulan untuk tahun ini
        $pemasukanData = DB::table('produksi')
            ->select(DB::raw("DATE_PART('month', produksi_tanggal) as bulan"), DB::raw("SUM(total_pendapatan) as total"))
            ->whereYear('produksi_tanggal', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get();

        // Konversi angka bulan (1-12) menjadi nama bulan pendek Indonesia
        $pemasukanGrafik = array_fill(1, 12, 0); // Buat default isi 0 untuk bulan Jan - Des
        foreach ($pemasukanData as $data) {
            $pemasukanGrafik[(int)$data->bulan] = (int)$data->total;
        }

        // Pengeluaran per Kategori biaya_jenis
        // Ambil data Pengeluaran berdasarkan biaya_jenis
        $pengeluaranGrafik = DB::table('biaya_operasional')
            ->select('biaya_jenis', DB::raw("SUM(biaya_jumlah) as total"))
            ->groupBy('biaya_jenis')
            ->get();


        $user = auth()->user();

        if ($user->user_role === 'super_admin') {
            return view('super_admin.dashboard', compact(
                'jumlahPetani', 'jumlahLahan', 'pendapatanBulanIni', 
                'petaniPending', 'pemasukanGrafik', 'pengeluaranGrafik'
            ));
        } elseif ($user->user_role === 'admin') {
            return view('admin.dashboard', compact(
                'jumlahPetani', 'jumlahLahan', 'pendapatanBulanIni', 
                'petaniPending', 'pemasukanGrafik', 'pengeluaranGrafik'
            ));
        }

        // Jika ada role lain yang tidak diizinkan masuk
        abort(403, 'Anda tidak memiliki hak akses ke halaman dashboard ini.');
    }
}