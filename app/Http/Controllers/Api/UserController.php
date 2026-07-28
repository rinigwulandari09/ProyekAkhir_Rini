<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAdmins()
    {
        // Ambil data user dengan role admin
        $admins = User::where('user_role', 'admin')
            ->select('user_id', 'user_nama', 'user_username', 'user_email')
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
        if ($request->has('user_nama')) $user->user_nama = $request->user_nama;
        if ($request->has('user_username')) $user->user_username = $request->user_username;
        if ($request->has('user_email')) $user->user_email = $request->user_email;

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diupdate',
            'data' => $user
        ], 200);
    }
}
