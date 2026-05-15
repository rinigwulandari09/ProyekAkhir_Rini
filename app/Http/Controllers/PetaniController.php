<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PetaniController extends Controller
{
    public function index()
    {
        // Mengambil semua data dari tabel petani
        $petani = \App\Models\Petani::all(); 
        return view('super_admin.petani.index', compact('petani'));
    }    
    
}
