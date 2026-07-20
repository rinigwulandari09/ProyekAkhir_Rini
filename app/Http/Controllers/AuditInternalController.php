<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditInternal;
use App\Models\KunjunganLapangan;
use Illuminate\Support\Facades\Storage;

class AuditInternalController extends Controller
{
    public function index()
    {
        // Mengambil data kunjungan dan audit
        $kunjungan = KunjunganLapangan::orderBy('tanggal_kunjungan', 'desc')->get();
        $audit = AuditInternal::orderBy('tanggal', 'desc')->get();

        return view('super_admin.Audit.index', compact('kunjungan', 'audit'));
    }

    public function destroyKunjungan($id)
    {
        $kunjungan = KunjunganLapangan::findOrFail($id);
        
        // Menghapus file fisik jika ada
        if ($kunjungan->path_file_kunjungan && Storage::disk('public')->exists($kunjungan->path_file_kunjungan)) {
            Storage::disk('public')->delete($kunjungan->path_file_kunjungan);
        }
        
        $kunjungan->delete();

        return redirect()->route('audit.index')->with('success', 'Data kunjungan lapangan berhasil dihapus.');
    }

    public function destroyInternal($id)
    {
        $audit = AuditInternal::findOrFail($id);
        
        // Menghapus file fisik jika ada
        if ($audit->path_file_kunjungan && Storage::disk('public')->exists($audit->path_file_kunjungan)) {
            Storage::disk('public')->delete($audit->path_file_kunjungan);
        }
        
        $audit->delete();

        return redirect()->route('audit.index')->with('success', 'Data audit internal berhasil dihapus.');
    }
}
