<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Petani;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'petani_nama' => 'required',
            'petani_email' => 'required',
            'petani_pin' => 'required',
            'petani_no_hp' => 'required'
        ]);

        $petani = Petani::create([
            'petani_nama' => $request->petani_nama,
            'petani_alamat' => $request->petani_alamat,
            'petani_no_hp' => $request->petani_no_hp,
            'petani_status' => 'Pending',
            'petani_email' => $request->petani_email,
            'petani_pin' => $request->petani_pin,
            'petani_desa' => $request->petani_desa,
            'petani_jenis_kelamin' => $request->petani_jenis_kelamin,
            'petani_tanggal_lahir' => $request->petani_tanggal_lahir,
            'petani_username' => $request->petani_username,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil',
            'data' => $petani
        ], 201);
    }
}