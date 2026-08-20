<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KunjunganLapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KunjunganLapanganController extends Controller
{
    // 1. Method untuk Menyimpan Data dari Android
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_kunjungan' => 'required|date',
            'desa_kebun'        => 'required|string',
            'desa_kepengurusan' => 'required|string',
            'nama_auditor'      => 'required|string',
            'file_kunjungan'    => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240' // Max 10MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $path = null;
            if ($request->hasFile('file_kunjungan')) {
                $file = $request->file('file_kunjungan');
                $namaFile = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs(
                    'kunjungan_lapangan',
                    $namaFile,
                    'public'
                );
            }

            $kunjungan = KunjunganLapangan::create([
                'tanggal_kunjungan'   => $request->tanggal_kunjungan,
                'desa_kebun'          => $request->desa_kebun,
                'desa_kepengurusan'   => $request->desa_kepengurusan,
                'nama_auditor'        => $request->nama_auditor,
                'nama_petani'         => $request->nama_petani,
                'user_id'             => $request->user_id,
                'petani_id'           => $request->petani_id,
                'status'              => $request->status_kunjungan ?? $request->status ?? 'Belum Kunjungan',
                'keterangan'          => $request->keterangan,
                'periode'             => $request->periode,
                'visit_attempt'       => $request->visit_attempt ?? 1,
                'path_file_kunjungan' => $path,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data kunjungan lapangan berhasil disimpan',
                'data'    => $kunjungan
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // 2. Method untuk Tarik Semua Data Kunjungan (Digunakan Android saat Refresh/Sync)
    public function getAllByDesa(Request $request)
    {
        try {
            $desa = $request->query('desa');
            $query = KunjunganLapangan::with(['petani', 'user']);

            if (!empty($desa)) {
                $query->where(function($q) use ($desa) {
                    $q->where('desa_kebun', $desa)
                      ->orWhere('desa_kepengurusan', $desa);
                });
            }

            $data = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'status'  => 'success',
                'success' => true,
                'data'    => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'success' => false,
                'message' => 'Gagal mengambil data kunjungan: ' . $e->getMessage()
            ], 500);
        }
    }
}
