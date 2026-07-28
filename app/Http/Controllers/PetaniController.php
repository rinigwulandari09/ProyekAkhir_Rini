<?php

namespace App\Http\Controllers;

use App\Models\Petani;
use App\Models\Desa;
use Illuminate\Http\Request;

class PetaniController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $petaniQuery = Petani::with('desa');

        if ($user->user_role === 'admin') {
            $petaniQuery->where('desa_id', $user->desa_id);
        }

        $petani = $petaniQuery->get();

        if ($user->user_role === 'super_admin') {
            return view('super_admin.petani.index', compact('petani'));
        } elseif ($user->user_role === 'admin') {
            return view('admin.petani.index', compact('petani'));
        }

        abort(403);
    }
    
    public function edit($id)
    {
        $petani = Petani::with(['lahans', 'desa'])->findOrFail($id);
        $desas = Desa::orderBy('desa_nama')->get();
        $user = auth()->user();

        // Jika Super Admin, gunakan pembungkus view super_admin
        if ($user->user_role === 'super_admin') {
            return view('super_admin.petani.edit', compact('petani', 'desas'));
        } 
        // Jika Admin, gunakan pembungkus view admin biasa
        elseif ($user->user_role === 'admin') {
            return view('admin.petani.edit', compact('petani', 'desas'));
        }

        abort(403);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'petani_nama' => 'required|string|max:255',
            'petani_username' => 'required|string|max:100',
            'petani_email' => 'nullable|email|max:255',
            'petani_no_hp' => 'required|string|max:30',
            'petani_status' => 'required|in:Aktif,Nonaktif',
            'petani_jenis_kelamin' => 'nullable|string|max:10',
            'petani_tanggal_lahir' => 'nullable|date',
            'petani_alamat' => 'nullable|string',
            'desa_id' => 'nullable|integer|exists:desa,desa_id'
        ]);

        $petani = Petani::findOrFail($id);

        $petani->update([
            'petani_nama' => $request->petani_nama,
            'petani_username' => $request->petani_username,
            'petani_email' => $request->petani_email,
            'petani_no_hp' => $request->petani_no_hp,
            'petani_status' => $request->petani_status,
            'petani_jenis_kelamin' => $request->petani_jenis_kelamin,
            'petani_tanggal_lahir' => $request->petani_tanggal_lahir,
            'petani_alamat' => $request->petani_alamat,
            'desa_id' => $request->desa_id
        ]);

        return redirect()
            ->route('petani.edit', $id)
            ->with('success', 'Data petani berhasil diperbarui');
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