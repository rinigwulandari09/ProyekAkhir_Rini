<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lahan;
use App\Models\User;

class LahanController extends Controller
{
    // 1. Menampilkan Halaman Peta Sebaran Lahan
    public function index()
    {
        // Mengambil semua data lahan beserta relasi petaninya agar tidak terjadi N+1 query problem
        $lahans = Lahan::with('petani')->get();

        // Diarahkan ke folder resources/views/super_admin/lahan/index.blade.php
        return view('super_admin.lahan.index', compact('lahans'));
    }

    // 2. Menampilkan Form Input Lahan
    public function create()
    {
        // Ambil data petani untuk pilihan dropdown di form
        $petanis = User::where('user_role', 'petani')->get(); 
        
        // Disamakan foldernya ke super_admin agar terstruktur
        return view('super_admin.lahan.create', compact('petanis'));
    }

    // 3. Memproses Simpan Data Lahan dari Form
    public function store(Request $request)
    {
        $request->validate([
            'lahan_lokasi' => 'required|string|max:255',
            'lahan_luas'   => 'required|numeric',
            'petani_id'    => 'required',
            'area_lahan'   => 'nullable|string', 
        ]);

        Lahan::create([
            'lahan_lokasi' => $request->lahan_lokasi,
            'lahan_luas'   => $request->lahan_luas,
            'petani_id'    => $request->petani_id,
            'area_lahan'   => $request->area_lahan, 
        ]);

        // Dialihkan kembali ke rute index admin yang terdaftar di route:list
        return redirect()->route('admin.lahan.index')->with('success', 'Data lahan berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail satu lahan
     */
    public function show($id)
    {
        $lahan = Lahan::with('petani')->findOrFail($id);
        return view('super_admin.lahan.show', compact('lahan'));
    }

    /**
     * Menampilkan form edit lahan
     */
    public function edit($id)
    {
        $lahan = Lahan::findOrFail($id);
        $petanis = User::where('user_role', 'petani')->get();

        return view('super_admin.lahan.edit', compact('lahan', 'petanis'));
    }

    /**
     * Memperbarui data lahan di Supabase
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'lahan_lokasi' => 'required|string|max:255',
            'lahan_luas'   => 'required|numeric',
            'petani_id'    => 'required',
        ]);

        $lahan = Lahan::findOrFail($id);
        $lahan->update([
            'lahan_lokasi' => $request->lahan_lokasi,
            'lahan_luas'   => $request->lahan_luas,
            'petani_id'    => $request->petani_id,
        ]);

        // Dialihkan kembali ke rute index admin yang terdaftar di route:list
        return redirect()->route('admin.lahan.index')->with('success', 'Data lahan berhasil diperbarui!');
    }

    /**
     * Menghapus data lahan dari Supabase
     */
    public function destroy($id)
    {
        $lahan = Lahan::findOrFail($id);
        $lahan->delete();

        // Dialihkan kembali ke rute index admin yang terdaftar di route:list
        return redirect()->route('admin.lahan.index')->with('success', 'Data lahan berhasil dihapus!');
    }
}
