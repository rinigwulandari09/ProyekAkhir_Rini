<?php

namespace App\Http\Controllers;

use App\Models\KunjunganLapangan;
use Illuminate\Http\Request;

class KunjunganLapanganController extends Controller
{
    public function index()
    {
        $kunjungan = KunjunganLapangan::orderBy('id_kunjungan', 'desc')->get();
        return view('super_admin.Audit.kunjungan', compact('kunjungan'));
    }
}
