<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Petani;
use App\Models\Produksi;
use App\Models\BiayaOperasional;
use Illuminate\Support\Facades\DB;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        // Tangkap parameter sesuai name di element select HTML
        $bulanAwal  = $request->input('bulan_awal');  
        $bulanAkhir = $request->input('bulan_akhir'); 
        $tahun      = $request->input('tahun');       

        $petaniQuery = Petani::query();

        // Pemasukan (Produksi)
        $petaniQuery->withSum(['produksis as total_masuk' => function($query) use ($bulanAwal, $bulanAkhir, $tahun) {
            if ($tahun) {
                $query->whereYear('produksi_tanggal', $tahun);
            }
            if ($bulanAwal && $bulanAkhir) {
                // Perbaikan untuk PostgreSQL menggunakan EXTRACT MONTH
                $query->whereRaw("EXTRACT(MONTH FROM produksi_tanggal) BETWEEN ? AND ?", [$bulanAwal, $bulanAkhir]);
            } elseif ($bulanAwal) {
                $query->whereRaw("EXTRACT(MONTH FROM produksi_tanggal) >= ?", [$bulanAwal]);
            } elseif ($bulanAkhir) {
                $query->whereRaw("EXTRACT(MONTH FROM produksi_tanggal) <= ?", [$bulanAkhir]);
            }
        }], 'total_pendapatan');

        // Pengeluaran (Operasional)
        $petaniQuery->withSum(['biayaOperasinals as total_keluar' => function($query) use ($bulanAwal, $bulanAkhir, $tahun) {
            if ($tahun) {
                $query->whereYear('biaya_tanggal', $tahun);
            }
            if ($bulanAwal && $bulanAkhir) {
                // Perbaikan untuk PostgreSQL menggunakan EXTRACT MONTH
                $query->whereRaw("EXTRACT(MONTH FROM biaya_tanggal) BETWEEN ? AND ?", [$bulanAwal, $bulanAkhir]);
            } elseif ($bulanAwal) {
                $query->whereRaw("EXTRACT(MONTH FROM biaya_tanggal) >= ?", [$bulanAwal]);
            } elseif ($bulanAkhir) {
                $query->whereRaw("EXTRACT(MONTH FROM biaya_tanggal) <= ?", [$bulanAkhir]);
            }
        }], 'biaya_jumlah');

        $petanis = $petaniQuery->get();

        // Hitung Ringkasan Summary Card
        $produksiSummary = Produksi::query();
        $biayaSummary = BiayaOperasional::query();

        if ($tahun) {
            $produksiSummary->whereYear('produksi_tanggal', $tahun);
            $biayaSummary->whereYear('biaya_tanggal', $tahun);
        }
        
        if ($bulanAwal && $bulanAkhir) {
            // Perbaikan pencarian summary card untuk PostgreSQL
            $produksiSummary->whereRaw("EXTRACT(MONTH FROM produksi_tanggal) BETWEEN ? AND ?", [$bulanAwal, $bulanAkhir]);
            $biayaSummary->whereRaw("EXTRACT(MONTH FROM biaya_tanggal) BETWEEN ? AND ?", [$bulanAwal, $bulanAkhir]);
        } elseif ($bulanAwal) {
            $produksiSummary->whereRaw("EXTRACT(MONTH FROM produksi_tanggal) >= ?", [$bulanAwal]);
            $biayaSummary->whereRaw("EXTRACT(MONTH FROM biaya_tanggal) >= ?", [$bulanAwal]);
        } elseif ($bulanAkhir) {
            $produksiSummary->whereRaw("EXTRACT(MONTH FROM produksi_tanggal) <= ?", [$bulanAkhir]);
            $biayaSummary->whereRaw("EXTRACT(MONTH FROM biaya_tanggal) <= ?", [$bulanAkhir]);
        }

        $totalPemasukanseluruh = $produksiSummary->sum('total_pendapatan');
        $totalPengeluaranSeluruh = $biayaSummary->sum('biaya_jumlah');

        $user = auth()->user();
        $compactData = compact('petanis', 'totalPemasukanseluruh', 'totalPengeluaranSeluruh', 'bulanAwal', 'bulanAkhir', 'tahun');

        if ($user->user_role === 'super_admin') {
            return view('super_admin.keuangan.index', $compactData);
        } elseif ($user->user_role === 'admin') {
            return view('admin.keuangan.index', $compactData);
        }

        abort(403);
    }
}