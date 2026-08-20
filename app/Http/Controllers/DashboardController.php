<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Inisialisasi Query (Tanpa Filter Desa agar menampilkan data KESELURUHAN)
        $jumlahPetaniQuery = DB::table('petani');
        
        $jumlahLahanQuery = DB::table('lahan')
            ->join('petani', 'lahan.petani_id', '=', 'petani.petani_id');
            
        $pendapatanBulanIniQuery = DB::table('produksi')
            ->join('petani', 'produksi.petani_id', '=', 'petani.petani_id');
            
        $petaniPendingQuery = DB::table('petani')
            ->leftJoin('desa', 'petani.desa_id', '=', 'desa.desa_id')
            ->where('petani.petani_status', 'Pending');
        
        $pemasukanDataQuery = DB::table('produksi')
            ->join('petani', 'produksi.petani_id', '=', 'petani.petani_id');
            
        $pengeluaranGrafikQuery = DB::table('biaya_operasional')
            ->join('petani', 'biaya_operasional.petani_id', '=', 'petani.petani_id');
            
        $semuaLahanQuery = DB::table('lahan')
            ->join('petani', 'lahan.petani_id', '=', 'petani.petani_id')
            ->whereNotNull('lahan.area_lahan');

        // Jika user adalah admin, filter data berdasarkan desa_id
        if ($user->user_role === 'admin' && $user->desa_id) {
            $jumlahPetaniQuery->where('petani.desa_id', $user->desa_id);
            $jumlahLahanQuery->where('petani.desa_id', $user->desa_id);
            $pendapatanBulanIniQuery->where('petani.desa_id', $user->desa_id);
            $petaniPendingQuery->where('petani.desa_id', $user->desa_id);
            $pemasukanDataQuery->where('petani.desa_id', $user->desa_id);
            $pengeluaranGrafikQuery->where('petani.desa_id', $user->desa_id);
            $semuaLahanQuery->where('petani.desa_id', $user->desa_id);
        }
        // 2. Eksekusi Pengambilan Data Keseluruhan
        $jumlahPetani = $jumlahPetaniQuery->count('petani_id');
        $jumlahLahan = $jumlahLahanQuery->sum('lahan_luas');

        $pendapatanBulanIni = $pendapatanBulanIniQuery
            ->whereMonth('produksi_tanggal', Carbon::now()->month)
            ->whereYear('produksi_tanggal', Carbon::now()->year)
            ->sum('total_pendapatan');

        $petaniPending = $petaniPendingQuery
            ->get(['petani.*', 'desa.desa_nama']);

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

        $jumlahProduksiHariIniQuery = DB::table('produksi')
            ->join('petani', 'produksi.petani_id', '=', 'petani.petani_id')
            ->whereDate('produksi_tanggal', today());

        if ($user->user_role === 'admin' && $user->desa_id) {
            $jumlahProduksiHariIniQuery->where('petani.desa_id', $user->desa_id);
        }

        $jumlahProduksiHariIni = $jumlahProduksiHariIniQuery->count();

        $userIdColumn = Schema::hasColumn('users', 'user_id') ? 'user_id' : 'id';

        $taskNotifications = DB::table('tugas')
            ->where(function ($q) use ($user, $userIdColumn) {
                $q->whereNull('user_id')
                  ->orWhere('user_id', $user->{$userIdColumn});
            })
            ->where('is_done', false)
            ->orderByRaw("CASE WHEN deadline IS NULL THEN 1 ELSE 0 END ASC")
            ->orderBy('deadline', 'asc')
            ->get(['id', 'judul', 'pesan', 'deadline', 'created_at']);

        // Format data untuk kalender
        $kalenderTugas = DB::table('tugas')
            ->where(function ($q) use ($user, $userIdColumn) {
                $q->whereNull('user_id')
                  ->orWhere('user_id', $user->{$userIdColumn});
            })
            ->whereNotNull('deadline')
            ->get(['id', 'judul', 'pesan', 'deadline', 'is_done']);

        // Data Audit Internal (Status Terakhir Per Petani)
        $latestAuditsSubQuery = DB::table('audit_internal')
            ->select(DB::raw('DISTINCT ON (nama_petani) id_audit'))
            ->orderBy('nama_petani')
            ->orderBy('tanggal', 'desc')
            ->orderBy('audit_attempt', 'desc')
            ->orderBy('id_audit', 'desc');

        $auditQuery = DB::table('audit_internal')
            ->joinSub($latestAuditsSubQuery, 'latest_audits', function ($join) {
                $join->on('audit_internal.id_audit', '=', 'latest_audits.id_audit');
            });
        
        if ($user->user_role === 'admin' && $user->desa_id) {
            $auditQuery->join('petani', 'audit_internal.petani_id', '=', 'petani.petani_id')
                       ->where('petani.desa_id', $user->desa_id);
        }

        $auditLulus = (clone $auditQuery)
            ->where('status_audit', 'Lulus')
            ->count();
            
        $auditPerbaikan = (clone $auditQuery)
            ->where('status_audit', 'Perlu Perbaikan')
            ->count();
            
        $auditPending = (clone $auditQuery)
            ->where(function ($query) {
                $query->whereNull('status_audit')
                      ->orWhere('status_audit', '')
                      ->orWhere('status_audit', 'Menunggu Konfirmasi');
            })
            ->count();

        $events = [];
        foreach ($kalenderTugas as $tugas) {
            $events[] = [
                'id' => $tugas->id,
                'title' => $tugas->judul,
                'start' => \Carbon\Carbon::parse($tugas->deadline)->format('Y-m-d'),
                'description' => $tugas->pesan,
                'backgroundColor' => $tugas->is_done ? '#9CA3AF' : '#234323', // Abu-abu jika selesai, hijau gelap jika belum
                'borderColor' => $tugas->is_done ? '#9CA3AF' : '#234323',
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'status' => $tugas->is_done ? 'Selesai' : 'Pending'
                ]
            ];
        }
        $kalenderEvents = json_encode($events);

        // 3. Pengalihan Halaman View sesuai Role (Data yang dikirimkan sekarang sudah SAMA)
        if ($user->user_role === 'super_admin') {
            return view('super_admin.dashboard', compact(
                'jumlahPetani', 'jumlahLahan', 'pendapatanBulanIni', 'petaniPending',
                'pemasukanGrafik', 'pengeluaranGrafik', 'semuaLahan', 'jumlahProduksiHariIni',
                'auditLulus', 'auditPerbaikan', 'auditPending'
            ));
        } elseif ($user->user_role === 'admin') {
            return view('admin.dashboard', compact(
                'jumlahPetani', 'jumlahLahan', 'pendapatanBulanIni', 'petaniPending',
                'pemasukanGrafik', 'pengeluaranGrafik', 'semuaLahan', 'jumlahProduksiHariIni',
                'taskNotifications', 'kalenderEvents',
                'auditLulus', 'auditPerbaikan', 'auditPending'
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

    public function completeTask($id)
    {
        $user = auth()->user();
        if (! in_array($user->user_role, ['super_admin', 'admin'])) {
            abort(403);
        }

        $userIdColumn = Schema::hasColumn('users', 'user_id') ? 'user_id' : 'id';
        $query = DB::table('tugas')
            ->where('id', $id)
            ->where(function ($q) use ($user, $userIdColumn) {
                $q->whereNull('user_id')
                  ->orWhere('user_id', $user->{$userIdColumn});
            });

        $updated = $query->update([
            'is_done' => true,
            'is_read' => false,
            'read_at' => null,
            'updated_at' => now(),
        ]);

        if (! $updated) {
            return redirect()->back()->with('error', 'Tugas tidak ditemukan atau sudah selesai.');
        }

        return redirect()->back()->with('success', 'Tugas ditandai selesai dan akan tetap muncul di notifikasi sementara.');
    }
}