<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Petani;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAdmins()
    {
        // Ambil data user dengan role admin
        $admins = User::where('user_role', 'admin')
            ->select('user_id', 'user_nama', 'user_username', 'user_email', 'user_profil')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data admin',
            'data' => $admins
        ], 200);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data',
            'data' => $user
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        // Update data dasar
        if ($request->has('user_username') && !empty($request->user_username)) {
            $newUsername = $request->user_username;
            $existsInUsers = User::where('user_username', $newUsername)->where('user_id', '!=', $id)->exists();
            $existsInPetani = Petani::where('petani_username', $newUsername)->exists();
            if ($existsInUsers || $existsInPetani) {
                return response()->json([
                    'success' => false,
                    'message' => 'Username sudah digunakan. Silakan gunakan username lain.'
                ], 400);
            }
        }

        if ($request->has('user_nama')) $user->user_nama = $request->user_nama;
        if ($request->has('user_username')) $user->user_username = $request->user_username;
        if ($request->has('user_email')) $user->user_email = $request->user_email;

        // Handle upload profil
        if ($request->hasFile('user_profil')) {
            $file = $request->file('user_profil');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Pindahkan file ke public/storage/profil atau sesuai konfigurasi filesystem Laravel
            $file->move(public_path('storage/profil'), $filename);
            
            $user->user_profil = $filename;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diupdate',
            'data' => $user
        ], 200);
    }
    public function ubahPin(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Data admin/user tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'pin_baru' => 'required|numeric|digits:6',
        ]);

        $user->user_password = Hash::make($request->pin_baru);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'PIN berhasil diubah'
        ], 200);
    }
}
