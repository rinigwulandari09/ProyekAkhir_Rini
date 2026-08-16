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

        // Tangkap parameter filter tanggal dan lahan
        $bulanAwal  = $request->input('bulan_awal');  
        $bulanAkhir = $request->input('bulan_akhir'); 
        $tahun      = $request->input('tahun');       
        $lahanId    = $request->input('lahan_id');

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

        // Terapkan filter lahan
        if ($lahanId) {
            $produksiQuery->where(function($q) use ($lahanId) {
                $q->where('lahan_id', $lahanId)
                  ->orWhereHas('detailProduksi', function($q2) use ($lahanId) {
                      $q2->where('lahan_id', $lahanId);
                  });
            });
            $biayaQuery->where('lahan_id', $lahanId);
        }

        // PERBAIKAN UTAMA DETAIL: Urutkan berdasarkan tanggal transaksi terbaru (DESC)
        $pemasukan = $produksiQuery
            ->with(['lahan', 'detailProduksi.lahan'])
            ->orderBy('produksi_tanggal', 'desc')
            ->get();

        $pengeluaran = $biayaQuery
            ->orderBy('biaya_tanggal', 'desc')
            ->get();

        // 4. Hitung ringkasan total akumulasi nominal
        $totalPemasukan = $pemasukan->sum('total_pendapatan');
        $totalPengeluaran = $pengeluaran->sum('biaya_total');

        // 5. Ambil data lahan untuk dropdown filter
        $lahans = $petani->lahans;

        $compactData = compact('petani', 'pemasukan', 'pengeluaran', 'totalPemasukan', 'totalPengeluaran', 'bulanAwal', 'bulanAkhir', 'tahun', 'lahanId', 'lahans');

        // Alihkan ke view sesuai role
        if ($user->user_role === 'super_admin') {
            return view('super_admin.keuangan.show', $compactData);
        } elseif ($user->user_role === 'admin') {
            return view('admin.keuangan.show', $compactData);
        }

        abort(403);
    }

    public function editProduksi($id)
    {
        $user = auth()->user();
        if ($user->user_role !== 'super_admin') {
            abort(403, 'Hanya Super Admin yang dapat mengakses halaman ini.');
        }
        $produksi = Produksi::findOrFail($id);
        $lahans = $produksi->petani->lahans;
        return view('super_admin.keuangan.edit_produksi', compact('produksi', 'lahans'));
    }

    public function updateProduksi(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->user_role !== 'super_admin') {
            abort(403, 'Hanya Super Admin yang dapat mengubah data ini.');
        }

        $produksi = Produksi::findOrFail($id);

        $request->validate([
            'lahan_id' => 'required|exists:lahan,lahan_id',
            'produksi_tanggal' => 'required|date',
            'jumlah_tbs' => 'required|numeric',
            'harga_tbs' => 'required|numeric',
            'produksi_ket' => 'nullable|string',
            'produksi_bukti' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048'
        ]);

        $data = $request->only(['lahan_id', 'produksi_tanggal', 'jumlah_tbs', 'harga_tbs']);
        $data['total_pendapatan'] = $request->jumlah_tbs * $request->harga_tbs;
        
        if ($request->has('produksi_ket')) {
            $data['produksi_ket'] = $request->produksi_ket;
        }

        if ($request->hasFile('produksi_bukti')) {
            // Delete old file if necessary, logic depending on existing system
            $file = $request->file('produksi_bukti');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/bukti_produksi', $filename);
            $data['produksi_bukti'] = 'bukti_produksi/' . $filename;
        }

        $produksi->update($data);

        return redirect()->route('keuangan.show', $produksi->petani_id)->with('success', 'Data Pemasukan (Produksi) berhasil diperbarui.');
    }

    public function editBiayaOperasional($id)
    {
        $user = auth()->user();
        if ($user->user_role !== 'super_admin') {
            abort(403, 'Hanya Super Admin yang dapat mengakses halaman ini.');
        }
        $biaya = BiayaOperasional::findOrFail($id);
        $lahans = $biaya->petani->lahans;
        return view('super_admin.keuangan.edit_biaya', compact('biaya', 'lahans'));
    }

    public function updateBiayaOperasional(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->user_role !== 'super_admin') {
            abort(403, 'Hanya Super Admin yang dapat mengubah data ini.');
        }

        $biaya = BiayaOperasional::findOrFail($id);

        $request->validate([
            'lahan_id' => 'required|exists:lahan,lahan_id',
            'biaya_tanggal' => 'required|date',
            'biaya_jenis' => 'required|string',
            'biaya_nama' => 'required|string',
            'biaya_jumlah' => 'nullable|numeric',
            'biaya_total' => 'required|numeric',
            'biaya_ket' => 'nullable|string',
            'biaya_bukti' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048'
        ]);

        $data = $request->only(['lahan_id', 'biaya_tanggal', 'biaya_jenis', 'biaya_nama', 'biaya_jumlah', 'biaya_total']);
        
        if ($request->has('biaya_ket')) {
            $data['biaya_ket'] = $request->biaya_ket;
        }

        if ($request->hasFile('biaya_bukti')) {
            // Delete old file if necessary
            $file = $request->file('biaya_bukti');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/bukti_biaya', $filename);
            $data['biaya_bukti'] = 'bukti_biaya/' . $filename;
        }

        $biaya->update($data);

        return redirect()->route('keuangan.show', $biaya->petani_id)->with('success', 'Data Pengeluaran (Biaya Operasional) berhasil diperbarui.');
    }
}