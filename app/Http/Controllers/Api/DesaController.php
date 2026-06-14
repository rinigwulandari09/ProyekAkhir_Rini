<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Desa;

class DesaController extends Controller
{
    public function index()
    {
        $desa = Desa::select('desa_id', 'desa_nama')
                    ->orderBy('desa_nama')
                    ->get();

        return response()->json([
            'success' => true,
            'data' => $desa
        ]);
    }
}