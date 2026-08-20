<?php

namespace App\Http\Controllers;

use App\Models\KunjunganLapangan;
use Illuminate\Http\Request;

class KunjunganLapanganController extends Controller
{
    public function index()
    {
        $kunjunganRaw = KunjunganLapangan::orderBy('tanggal_kunjungan', 'desc')
            ->orderBy('visit_attempt', 'desc')
            ->orderBy('id_kunjungan', 'desc')
            ->get();
            
        $kunjungan = $kunjunganRaw->groupBy('nama_petani');
        
        $user = auth()->user();
        if ($user && $user->user_role === 'admin') {
            return view('admin.Audit.kunjungan', compact('kunjungan'));
        }
        
        return view('super_admin.Audit.kunjungan', compact('kunjungan'));
    }
}
