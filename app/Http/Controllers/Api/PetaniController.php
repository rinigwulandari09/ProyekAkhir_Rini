<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Petani;
use Illuminate\Http\Request;

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
}
