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
            ->select('user_id', 'user_nama', 'user_username')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengambil data admin',
            'data' => $admins
        ], 200);
    }
}
