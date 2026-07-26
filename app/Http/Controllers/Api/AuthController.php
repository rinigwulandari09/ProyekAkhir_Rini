<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Petani;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'petani_nama'            => 'required|string|max:255',
            'petani_email'           => 'required|email|max:255',
            'petani_pin'             => 'required|min:6',
            'petani_no_hp'           => 'required',
            'desa_id'                => 'required|exists:desa,desa_id',
            'petani_alamat'          => 'nullable|string',
            'petani_jenis_kelamin'   => 'nullable|string',
            'petani_tanggal_lahir'   => 'nullable',
            'petani_username'        => 'nullable|string|max:255',
            'petani_profil'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $filePath = null;

        if ($request->hasFile('petani_profil')) {
            $file = $request->file('petani_profil');
            $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->storeAs('public/petani', $fileName);
            $filePath = 'petani/'.$fileName;
        }

        // TIPS: Jika dari mobile mengirim PIN terenkripsi SHA-256 atau plain text, 
        // kita bisa simpan atau biarkan sesuai kebutuhan. 
        // Di sini kita simpan apa adanya atau di-hash jika diperlukan.
        $petani = Petani::create([
            'petani_nama' => $request->petani_nama,
            'petani_alamat' => $request->petani_alamat,
            'petani_no_hp' => $request->petani_no_hp,
            'petani_status' => 'Pending',
            'petani_email' => $request->petani_email,
            'petani_pin' => $request->petani_pin, // Sesuaikan jika mobile kirim hash sha256
            'petani_jenis_kelamin' => $request->petani_jenis_kelamin,
            'petani_tanggal_lahir' => $request->petani_tanggal_lahir,
            'petani_username' => $request->petani_username,
            'desa_id' => $request->desa_id,
            'petani_profil' => $filePath
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil',
            'data' => $petani
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $inputPassword = $request->password;
        // Buat juga versi SHA-256 dari inputan mobile untuk berjaga-jaga
        $inputPasswordSha256 = hash('sha256', $inputPassword);

        // ==========================================
        // 1. LOGIN ADMIN
        // ==========================================
        $user = User::where('user_username', $request->username)->first();

        if ($user) {
            $isAdminValid = false;

            // Cek apakah cocok dengan SHA-256, teks biasa, atau Bcrypt Laravel
            if ($user->user_password == $inputPassword || 
                $user->user_password == $inputPasswordSha256 || 
                Hash::check($inputPassword, $user->user_password)) {
                $isAdminValid = true;
            }

            if ($isAdminValid) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil',
                    'role' => $user->user_role,
                    'data' => [
                        'user_id' => $user->user_id,
                        'user_username' => $user->user_username,
                        'user_role' => $user->user_role
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Password salah'
            ], 401);
        }

        // ==========================================
        // 2. LOGIN PETANI
        // ==========================================
        $petani = Petani::where('petani_username', $request->username)->first();
        
        if ($petani) {
            $isPetaniValid = false;

            // Cek apakah PIN cocok dengan teks biasa, SHA-256, atau Bcrypt
            if ($petani->petani_pin == $inputPassword || 
                $petani->petani_pin == $inputPasswordSha256 || 
                Hash::check($inputPassword, $petani->petani_pin)) {
                $isPetaniValid = true;
            }

            if ($isPetaniValid) {
                $profilPetani = Petani::where('petani_id', $petani->petani_id)
                    ->value('petani_profil');

                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil',
                    'role' => 'petani',
                    'data' => [
                        'petani_id' => $petani->petani_id,
                        'petani_nama' => $petani->petani_nama,
                        'petani_username' => $petani->petani_username,
                        'petani_status' => $petani->petani_status,
                        'desa_id' => $petani->desa_id,
                        'petani_profil' => $profilPetani
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'PIN salah'
            ], 401);
        }

        return response()->json([
            'success' => false,
            'message' => 'Username tidak ditemukan'
        ], 404);
    }

    public function getPetani($petani_id)
    {
        $petani = Petani::find($petani_id);

        if (!$petani) {
            return response()->json([
                'success' => false,
                'message' => 'Data petani tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail petani berhasil diambil',
            'data' => $petani
        ], 200);
    }
}