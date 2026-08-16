<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditInternal;
use App\Models\KunjunganLapangan;
use Illuminate\Support\Facades\Storage;

class AuditInternalController extends Controller
{
    public function index(Request $request)
    {
        $bulanAwal = $request->input('dari_bulan');
        $bulanAkhir = $request->input('sampai_bulan');
        $tahun = $request->input('tahun');
        $status = $request->input('status');

        $user = auth()->user();
        $isAdmin = $user && $user->user_role === 'admin';
        $adminId = $isAdmin ? $user->user_id : null;
        $adminName = $isAdmin ? $user->user_nama : null;

        // Kunjungan Query
        $kunjunganQuery = KunjunganLapangan::query();

        // Admin Filter for Kunjungan
        if ($isAdmin && $adminName) {
            $kunjunganQuery->where('nama_auditor', 'like', "%{$adminName}%");
        }
        if ($bulanAwal && $bulanAkhir) {
            $kunjunganQuery->whereRaw('EXTRACT(MONTH FROM CAST(tanggal_kunjungan AS DATE)) >= ?', [$bulanAwal])
                           ->whereRaw('EXTRACT(MONTH FROM CAST(tanggal_kunjungan AS DATE)) <= ?', [$bulanAkhir]);
        }
        if ($tahun) {
            $kunjunganQuery->whereRaw('EXTRACT(YEAR FROM CAST(tanggal_kunjungan AS DATE)) = ?', [$tahun]);
        }
        if ($status) {
            if ($status === 'Menunggu Konfirmasi') {
                $kunjunganQuery->where(function($q) {
                    $q->whereNull('status')->orWhere('status', '');
                });
            } else {
                $kunjunganQuery->where('status', $status);
            }
        }
        $kunjungan = $kunjunganQuery->orderBy('tanggal_kunjungan', 'desc')->get();

        // Audit Query
        $auditQuery = AuditInternal::query();

        // Admin Filter for Audit
        if ($isAdmin && $adminId) {
            $auditQuery->where('user_id', $adminId);
        }
        if ($bulanAwal && $bulanAkhir) {
            $auditQuery->whereRaw('EXTRACT(MONTH FROM CAST(tanggal AS DATE)) >= ?', [$bulanAwal])
                       ->whereRaw('EXTRACT(MONTH FROM CAST(tanggal AS DATE)) <= ?', [$bulanAkhir]);
        }
        if ($tahun) {
            $auditQuery->whereRaw('EXTRACT(YEAR FROM CAST(tanggal AS DATE)) = ?', [$tahun]);
        }
        if ($status) {
            if ($status === 'Menunggu Konfirmasi') {
                $auditQuery->where(function($q) {
                    $q->whereNull('status_audit')->orWhere('status_audit', '');
                });
            } else {
                $auditQuery->where('status_audit', $status);
            }
        }
        $auditRaw = $auditQuery->orderBy('tanggal', 'desc')->orderBy('audit_attempt', 'desc')->get();
        // Group by nama_petani
        $audit = $auditRaw->groupBy('nama_petani');

        if ($isAdmin) {
            return view('admin.Audit.index', compact('kunjungan', 'audit', 'bulanAwal', 'bulanAkhir', 'tahun', 'status'));
        }

        return view('super_admin.Audit.index', compact('kunjungan', 'audit', 'bulanAwal', 'bulanAkhir', 'tahun', 'status'));
    }

    public function destroyKunjungan($id)
    {
        $kunjungan = KunjunganLapangan::findOrFail($id);
        
        // Menghapus file fisik jika ada
        if ($kunjungan->path_file_kunjungan) {
            $path = str_replace('storage/', '', $kunjungan->path_file_kunjungan);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
        
        $kunjungan->delete();

        return redirect()->route('audit.index')->with('success', 'Data kunjungan lapangan berhasil dihapus.');
    }

    public function updateStatusKunjungan(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Lulus,Perlu Perbaikan',
            'keterangan' => 'nullable|string'
        ]);

        $kunjungan = KunjunganLapangan::findOrFail($id);
        
        $kunjungan->status = $request->status;
        
        if ($request->status === 'Lulus') {
            $kunjungan->keterangan = null;
        } else {
            $kunjungan->keterangan = $request->keterangan;
        }

        $kunjungan->save();

        return redirect()->route('audit.index')->with('success', 'Status kunjungan lapangan berhasil diperbarui.');
    }

    public function destroyInternal($id)
    {
        $audit = AuditInternal::findOrFail($id);
        
        // Menghapus file fisik jika ada
        if ($audit->path_file_kunjungan) {
            $path = str_replace('storage/', '', $audit->path_file_kunjungan);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
        
        $audit->delete();

        return redirect()->route('audit.index')->with('success', 'Data audit internal berhasil dihapus.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_audit' => 'required|string|in:Lulus,Perlu Perbaikan,Ditolak',
            'keterangan' => 'nullable|string'
        ]);

        $audit = AuditInternal::findOrFail($id);
        
        $audit->status_audit = $request->status_audit;
        
        if ($request->status_audit === 'Lulus') {
            $audit->keterangan = null;
        } else {
            $audit->keterangan = $request->keterangan;
        }

        $audit->save();

        return redirect()->route('audit.index')->with('success', 'Status audit internal berhasil diperbarui.');
    }
}
