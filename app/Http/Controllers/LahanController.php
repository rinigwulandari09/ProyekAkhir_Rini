<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lahan;
use App\Models\Petani;

class LahanController extends Controller
{
    // 1. Menampilkan Halaman List Lahan
    public function index()
    {
        $lahans = Lahan::with('petani')->get();
        $user = auth()->user();

        // Mengalihkan view sesuai dengan role user
        if ($user->user_role === 'super_admin') {
            return view('super_admin.lahan.index', compact('lahans'));
        } elseif ($user->user_role === 'admin') {
            return view('admin.lahan.index', compact('lahans'));
        }

        abort(403);
    }

    // 2. Menampilkan Form Input Lahan
    public function create()
    {
        $petanis = Petani::all(); 
        return view('admin.lahan.tambah', compact('petanis')); // <-- Pastikan diarahkan ke 'tambah'
    }
    // public function create()
    // {
    //     $petanis = Petani::all(); 
    //     $user = auth()->user();
        
    //     if ($user->user_role === 'super_admin') {
    //         return view('super_admin.lahan.create', compact('petanis'));
    //     } elseif ($user->user_role === 'admin') {
    //         return view('admin.lahan.create', compact('petanis'));
    //     }

    //     abort(403);
    // }

    // 3. Memproses Simpan Data Lahan dari Form
    public function store(Request $request)
{
    $request->validate([
        'lahan_nama'   => 'required|string|max:255',
        'lahan_lokasi' => 'required|string|max:255',
        'lahan_luas'   => 'required|numeric',
        'petani_id'    => 'required',
        'area_lahan'   => 'required|json', // Validasi memastikan bahwa data yang dikirim berformat JSON
    ]);

    Lahan::create([
        'lahan_nama'   => $request->lahan_nama,
        'lahan_lokasi' => $request->lahan_lokasi,
        'lahan_luas'   => $request->lahan_luas,
        'petani_id'    => $request->petani_id,
        'area_lahan'   => json_decode($request->area_lahan), // Decode jika model belum otomatis meng-cast ke json
    ]);

    return redirect()->route('lahan.index')->with('success', 'Data lahan dan polygon berhasil disimpan!');
}

    // 4. Menampilkan detail satu lahan berdasarkan ID lahan
    public function show($id)
    {
        $lahan = Lahan::with('petani')->findOrFail($id);
        $user = auth()->user();

        if ($user->user_role === 'super_admin') {
            return view('super_admin.lahan.show', compact('lahan'));
        } elseif ($user->user_role === 'admin') {
            return view('admin.lahan.show', compact('lahan'));
        }

        abort(403);
    }

    // 5. Menampilkan form edit lahan
    public function edit($id)
    {
        $lahan = Lahan::findOrFail($id);
        $petanis = Petani::all();
        $user = auth()->user();

        if ($user->user_role === 'super_admin') {
            return view('super_admin.lahan.edit', compact('lahan', 'petanis'));
        } elseif ($user->user_role === 'admin') {
            return view('admin.lahan.edit', compact('lahan', 'petanis'));
        }

        abort(403);
    }

    // 6. Memperbarui data lahan
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

        return redirect()->route('lahan.index')->with('success', 'Data lahan berhasil diperbarui!');
    }

    // 7. Menghapus data lahan
    public function destroy($id)
    {
        $lahan = Lahan::findOrFail($id);
        $lahan->delete();

        return redirect()->route('lahan.index')->with('success', 'Data lahan berhasil dihapus!');
    }
}