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

        if (auth()->user()->user_role === 'admin') {
            $petaniQuery->where('desa_id', auth()->user()->desa_id);
        }

        // Pemasukan (Produksi)
        $petaniQuery->withSum(['produksi as total_masuk' => function($query) use ($bulanAwal, $bulanAkhir, $tahun) {
            if ($tahun) {
                $query->whereYear('produksi_tanggal', $tahun);
            }
            if ($bulanAwal && $bulanAkhir) {
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
                $query->whereRaw("EXTRACT(MONTH FROM biaya_tanggal) BETWEEN ? AND ?", [$bulanAwal, $bulanAkhir]);
            } elseif ($bulanAwal) {
                $query->whereRaw("EXTRACT(MONTH FROM biaya_tanggal) >= ?", [$bulanAwal]);
            } elseif ($bulanAkhir) {
                $query->whereRaw("EXTRACT(MONTH FROM biaya_tanggal) <= ?", [$bulanAkhir]);
            }
        }], 'biaya_total');

        // PERBAIKAN UTAMA: Mengurutkan petani berdasarkan tanggal transaksi terbaru 
        // (Mencari tanggal terbesar dari tabel produksi atau biaya_operasional menggunakan Subquery)
        $petanis = $petaniQuery->orderByRaw('
            GREATEST(
                COALESCE((SELECT MAX(produksi_tanggal) FROM produksi WHERE produksi.petani_id = petani.petani_id), \'1970-01-01\'),
                COALESCE((SELECT MAX(biaya_tanggal) FROM biaya_operasional WHERE biaya_operasional.petani_id = petani.petani_id), \'1970-01-01\')
            ) DESC
        ')->get();

        // Hitung Ringkasan Summary Card
        $produksiSummary = Produksi::query();
        $biayaSummary = BiayaOperasional::query();

        if ($tahun) {
            $produksiSummary->whereYear('produksi_tanggal', $tahun);
            $biayaSummary->whereYear('biaya_tanggal', $tahun);
        }
        
        if ($bulanAwal && $bulanAkhir) {
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
        $totalPengeluaranSeluruh = $biayaSummary->sum('biaya_total');

        $user = auth()->user();

        $produksiSummary = Produksi::query();
        $biayaSummary = BiayaOperasional::query();

        if ($user->user_role === 'admin') {
            $produksiSummary->join('petani', 'produksi.petani_id', '=', 'petani.petani_id')
                ->where('petani.desa_id', $user->desa_id);
            $biayaSummary->join('petani', 'biaya_operasional.petani_id', '=', 'petani.petani_id')
                ->where('petani.desa_id', $user->desa_id);
        }

        if ($tahun) {
            $produksiSummary->whereYear('produksi_tanggal', $tahun);
            $biayaSummary->whereYear('biaya_tanggal', $tahun);
        }

        if ($bulanAwal && $bulanAkhir) {
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
        $totalPengeluaranSeluruh = $biayaSummary->sum('biaya_total');

        $compactData = compact('petanis', 'totalPemasukanseluruh', 'totalPengeluaranSeluruh', 'bulanAwal', 'bulanAkhir', 'tahun');

        if ($user->user_role === 'super_admin') {
            return view('super_admin.keuangan.index', $compactData);
        } elseif ($user->user_role === 'admin') {
            return view('admin.keuangan.index', $compactData);
        }

        abort(403);
    }

    public function show(Request $request, $id)
    {
        // 1. Cari data petani, pastikan ID ditemukan
        $petani = Petani::findOrFail($id);
        $user = auth()->user();

        if ($user->user_role === 'admin' && $petani->desa_id !== $user->desa_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat data keuangan petani ini.');
        }

        // Tangkap parameter filter tanggal
        $bulanAwal  = $request->input('bulan_awal');  
        $bulanAkhir = $request->input('bulan_akhir'); 
        $tahun      = $request->input('tahun');       

        // 2. Ambil query relasi
        $produksiQuery = $petani->produksi(); 
        $biayaQuery = $petani->biayaOperasinals()->with('lahan');

        // 3. Terapkan filter PostgreSQL dengan nama kolom yang benar
        if ($tahun) {
            $produksiQuery->whereYear('produksi_tanggal', $tahun);
            $biayaQuery->whereYear('biaya_tanggal', $tahun);
        }
        if ($bulanAwal && $bulanAkhir) {
            $produksiQuery->whereRaw("EXTRACT(MONTH FROM produksi_tanggal) BETWEEN ? AND ?", [$bulanAwal, $bulanAkhir]);
            $biayaQuery->whereRaw("EXTRACT(MONTH FROM biaya_tanggal) BETWEEN ? AND ?", [$bulanAwal, $bulanAkhir]);
        } elseif ($bulanAwal) {
            $produksiQuery->whereRaw("EXTRACT(MONTH FROM produksi_tanggal) >= ?", [$bulanAwal]);
            $biayaQuery->whereRaw("EXTRACT(MONTH FROM biaya_tanggal) >= ?", [$bulanAwal]);
        } elseif ($bulanAkhir) {
            $produksiQuery->whereRaw("EXTRACT(MONTH FROM produksi_tanggal) <= ?", [$bulanAkhir]);
            $biayaQuery->whereRaw("EXTRACT(MONTH FROM biaya_tanggal) <= ?", [$bulanAkhir]);
        }

        // PERBAIKAN UTAMA DETAIL: Urutkan berdasarkan tanggal transaksi terbaru (DESC)
        $pemasukan = $produksiQuery
            ->with('lahan')
            ->orderBy('produksi_tanggal', 'desc')
            ->get();

        $pengeluaran = $biayaQuery
            ->orderBy('biaya_tanggal', 'desc')
            ->get();

        // 4. Hitung ringkasan total akumulasi nominal
        $totalPemasukan = $pemasukan->sum('total_pendapatan');
        $totalPengeluaran = $pengeluaran->sum('biaya_total');

        $compactData = compact('petani', 'pemasukan', 'pengeluaran', 'totalPemasukan', 'totalPengeluaran', 'bulanAwal', 'bulanAkhir', 'tahun');

        // Alihkan ke view sesuai role
        if ($user->user_role === 'super_admin') {
            return view('super_admin.keuangan.show', $compactData);
        } elseif ($user->user_role === 'admin') {
            return view('admin.keuangan.show', $compactData);
        }

        abort(403);
    }
}