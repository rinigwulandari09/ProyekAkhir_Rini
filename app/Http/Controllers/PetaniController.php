<?php

namespace App\Http\Controllers;

use App\Models\Petani;
use Illuminate\Http\Request;

class PetaniController extends Controller
{
    public function index()
    {
        $petani = Petani::with('desa')->get();
        $user = auth()->user();

        // Jika Super Admin, gunakan layout master 'layouts.dashboard'
        if ($user->user_role === 'super_admin') {
            return view('super_admin.petani.index', compact('petani'));
        } 
        // Jika Admin Biasa, gunakan layout master 'layouts.app'
        elseif ($user->user_role === 'admin') {
            return view('admin.petani.index', compact('petani'));
        }

        abort(403);
    }   
    
    public function edit($id)
    {
        $petani = Petani::with(['lahan', 'desa'])->findOrFail($id);
        $user = auth()->user();

        // Jika Super Admin, gunakan pembungkus view super_admin
        if ($user->user_role === 'super_admin') {
            return view('super_admin.petani.edit', compact('petani'));
        } 
        // Jika Admin, gunakan pembungkus view admin biasa
        elseif ($user->user_role === 'admin') {
            return view('admin.petani.edit', compact('petani'));
        }

        abort(403);
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