<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KunjunganLapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KunjunganLapanganController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_kunjungan' => 'required|date',
            'desa_kebun' => 'required|string',
            'desa_kepengurusan' => 'required|string',
            'nama_auditor' => 'required|string',
            'file_kunjungan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240' // Max 10MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $path = null;
            if ($request->hasFile('file_kunjungan')) {
                $file = $request->file('file_kunjungan');
                $filename = time() . '_' . $file->getClientOriginalName();
                // Store in storage/app/public/kunjungan_lapangan
                $path = $file->storeAs('public/kunjungan_lapangan', $filename);
                // Ubah format string agar sesuai jika dipanggil di frontend (storage/...)
                $path = str_replace('public/', 'storage/', $path);
            }

            $kunjungan = KunjunganLapangan::create([
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
                'desa_kebun' => $request->desa_kebun,
                'desa_kepengurusan' => $request->desa_kepengurusan,
                'nama_auditor' => $request->nama_auditor,
                'path_file_kunjungan' => $path,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data kunjungan lapangan berhasil disimpan',
                'data' => $kunjungan
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
