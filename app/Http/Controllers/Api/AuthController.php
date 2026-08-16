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

        // default null
        $filePath = null;

        // CEK ADA FILE FOTO
        if ($request->hasFile('petani_profil')) {
            $file = $request->file('petani_profil');

            // bikin nama unik
            $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

            // simpan ke storage/app/public/petani
            $file->storeAs('public/petani', $fileName);

            // path yang disimpan ke DB
            $filePath = 'petani/'.$fileName;
        }

        $petani = Petani::create([
            'petani_nama' => $request->petani_nama,
            'petani_alamat' => $request->petani_alamat,
            'petani_no_hp' => $request->petani_no_hp,
            'petani_status' => 'Pending',
            'petani_email' => $request->petani_email,
            'petani_pin' => Hash::make($request->petani_pin),
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

        // LOGIN ADMIN
        $user = User::where('user_username', $request->username)->first();

        if ($user) {

            if (Hash::check($request->password, $user->user_password)) {

                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil',
                    'role' => $user->user_role,
                    'data' => [
                        'user_id' => $user->user_id,
                        'user_username' => $user->user_username,
                        'user_role' => $user->user_role,
                        'desa_id' => $user->desa_id
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Password salah'
            ], 401);
        }

        // LOGIN PETANI
        $petani = Petani::where('petani_username', $request->username)->first();
        if ($petani) {
            if (Hash::check($request->password, $petani->petani_pin)) {
                // Ambil ulang data profil berdasarkan petani_id
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

        // JIKA USERNAME TIDAK DITEMUKAN DI KEDUANYA
        return response()->json([
            'success' => false,
            'message' => 'Username tidak ditemukan'
        ], 404);
    }

    public function lupaPin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email'    => 'required|email',
            'pin_baru' => 'required|numeric|digits:6',
        ]);

        $petani = Petani::where('petani_username', $request->username)
            ->where('petani_email', $request->email)
            ->first();

        if ($petani) {
            $petani->petani_pin = Hash::make($request->pin_baru);
            $petani->save();

            return response()->json([
                'success' => true,
                'message' => 'PIN berhasil diresett. Silakan login dengan PIN baru Anda.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data username dan email tidak cocok atau tidak ditemukan.'
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