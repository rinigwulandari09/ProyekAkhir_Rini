<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PetaniController extends Controller
{
    public function index()
    {
        // Mengambil semua data dari tabel petani
        $petani = \App\Models\Petani::all(); 
        return view('super_admin.petani.index', compact('petani'));
    }    
    
    public function show($id)
    {
        $petani = Petani::findOrFail($id);

        return view('super_admin.petani.show', compact('petani'));
    }

    public function destroy($id)
    {
        $petani = Petani::where('petani_id', $id)->firstOrFail();

        $petani->delete();

        return redirect()
            ->route('petani.index')
            ->with('success', 'Data petani berhasil dihapus');
    }
}
