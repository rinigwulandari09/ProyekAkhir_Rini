<?php

namespace App\Http\Controllers;

use App\Models\Petani; // Ditambahkan: Wajib di-import agar tidak error Class Not Found
use Illuminate\Http\Request;

class PetaniController extends Controller
{
    public function index()
    {
        // Mengambil semua data dari tabel petani
        $petani = Petani::all(); 
        return view('super_admin.petani.index', compact('petani'));
    }    
    
    public function edit($id)
{
    // Mengambil data petani beserta lahannya
    $petani = Petani::with('lahan')->findOrFail($id);

    // Mengarah ke file resources/views/super_admin/petani/edit.blade.php
    return view('super_admin.petani.edit', compact('petani'));
}

    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'petani_status' => 'required|in:Aktif,Nonaktif'
    ]);

    $petani = Petani::findOrFail($id);
    $petani->update([
        'petani_status' => $request->petani_status
    ]);

    // BARU: Di-redirect ke rute petani.edit (Aman menggunakan metode GET bawaan browser)
    return redirect()
        ->route('petani.edit', $id)
        ->with('success', 'Status akun petani berhasil diperbarui');
}

    public function destroy($id)
    {
        // Mencari data berdasarkan primary key petani_id
        $petani = Petani::findOrFail($id);
        $petani->delete();

        return redirect()
            ->route('petani.index')
            ->with('success', 'Data petani berhasil dihapus');
    }
}