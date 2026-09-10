<?php

namespace App\Http\Controllers;

use App\Models\HargaTbs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HargaTbsController extends Controller
{
    public function index()
    {
        $hargaTerbaru = HargaTbs::latest('created_at')->first();
        $riwayatHarga = HargaTbs::with('creator')->orderBy('created_at', 'desc')->paginate(15);

        $view = (Auth::check() && Auth::user()->user_role === 'admin')
            ? 'admin.harga_tbs.index'
            : 'super_admin.harga_tbs.index';

        return view($view, compact('hargaTerbaru', 'riwayatHarga'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'harga_dinas' => 'required|numeric|min:0',
            'harga_pt_sar' => 'required|numeric|min:0',
            'tanggal_berlaku' => 'required|date',
        ], [
            'harga_dinas.required' => 'Harga Dinas Perkebunan wajib diisi.',
            'harga_dinas.numeric' => 'Harga Dinas Perkebunan harus angka.',
            'harga_pt_sar.required' => 'Harga PT. SAR wajib diisi.',
            'harga_pt_sar.numeric' => 'Harga PT. SAR harus angka.',
            'tanggal_berlaku.required' => 'Tanggal berlaku wajib diisi.',
        ]);

        HargaTbs::create([
            'harga_dinas' => $request->harga_dinas,
            'harga_pt_sar' => $request->harga_pt_sar,
            'tanggal_berlaku' => $request->tanggal_berlaku,
            'created_by_user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Harga TBS berhasil diperbarui dan disimpan ke riwayat!');
    }

    public function getLatestHargaApi()
    {
        $latest = HargaTbs::latest('created_at')->first();

        if (!$latest) {
            return response()->json([
                'success' => true,
                'data' => [
                    'harga_dinas' => 0,
                    'harga_pt_sar' => 0,
                    'tanggal_berlaku' => date('Y-m-d'),
                    'created_at' => date('Y-m-d H:i:s'),
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'harga_tbs_id' => $latest->harga_tbs_id,
                'harga_dinas' => (double) $latest->harga_dinas,
                'harga_pt_sar' => (double) $latest->harga_pt_sar,
                'tanggal_berlaku' => $latest->tanggal_berlaku,
                'created_at' => $latest->created_at ? $latest->created_at->format('Y-m-d H:i:s') : date('Y-m-d H:i:s'),
            ]
        ]);
    }
}
