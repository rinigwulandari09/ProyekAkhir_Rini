<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Petani;
use App\Models\User;
use Illuminate\Http\Request;

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
        ]);

        $petani = Petani::create([
            'petani_nama' => $request->petani_nama,
            'petani_alamat' => $request->petani_alamat,
            'petani_no_hp' => $request->petani_no_hp,
            'petani_status' => 'Pending',
            'petani_email' => $request->petani_email,
            'petani_pin' => $request->petani_pin,
            'petani_jenis_kelamin' => $request->petani_jenis_kelamin,
            'petani_tanggal_lahir' => $request->petani_tanggal_lahir,
            'petani_username' => $request->petani_username,
            'desa_id' => $request->desa_id
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

            if ($user->user_password == $request->password) {

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

        // LOGIN PETANI
        $petani = Petani::where('petani_username', $request->username)->first();

        if ($petani) {

            if ($petani->petani_pin == $request->password) {

                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil',
                    'role' => 'petani',
                    'data' => [
                        'petani_id' => $petani->petani_id,
                        'petani_nama' => $petani->petani_nama,
                        'petani_username' => $petani->petani_username,
                        'petani_status' => $petani->petani_status,
                        'desa_id' => $petani->desa_id
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
}