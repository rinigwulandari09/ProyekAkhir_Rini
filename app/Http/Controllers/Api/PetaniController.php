<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Petani;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PetaniController extends Controller
{
    public function getAll()
    {
        // Ambil data nama petani untuk dropdown / pilihan
        $petani = Petani::select('petani_id', 'petani_nama', 'petani_username', 'desa_id')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data petani',
            'data' => $petani
        ], 200);
    }

    public function update(Request $request, $petani_id)
    {
        $petani = Petani::find($petani_id);

        if (!$petani) {
            return response()->json([
                'success' => false,
                'message' => 'Data petani tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'petani_nama'            => 'required|string|max:255',
            'petani_email'           => 'required|email|max:255',
            'petani_no_hp'           => 'required',
            'desa_id'                => 'required|exists:desa,desa_id',
            'petani_alamat'          => 'nullable|string',
            'petani_username'        => 'nullable|string|max:255',
            'petani_profil'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $message = 'Profil berhasil diperbarui.';
        if ($request->hasFile('petani_profil')) {
            $file = $request->file('petani_profil');
            $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->storeAs('petani', $fileName, 'public');
            $petani->petani_profil = 'petani/'.$fileName;
            $message .= ' Foto profil diunggah.';
        } else {
            $message .= ' Tidak ada foto yang diunggah dari Android.';
        }

        $petani->petani_nama = $request->petani_nama;
        $petani->petani_email = $request->petani_email;
        $petani->petani_no_hp = $request->petani_no_hp;
        $petani->desa_id = $request->desa_id;
        $petani->petani_alamat = $request->petani_alamat;
        $petani->petani_username = $request->petani_username;

        $petani->save();

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $petani
        ], 200);
    }

    public function ubahPin(Request $request, $petani_id)
    {
        $petani = Petani::find($petani_id);
        if (!$petani) {
            return response()->json([
                'success' => false,
                'message' => 'Data petani tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'pin_baru' => 'required|numeric|digits:6',
        ]);

        $petani->petani_pin = Hash::make($request->pin_baru);
        $petani->save();

        return response()->json([
            'success' => true,
            'message' => 'PIN berhasil diubah'
        ], 200);
    }
}
