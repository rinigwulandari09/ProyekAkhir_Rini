<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditInternal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuditInternalController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'tanggal' => 'required|date',
            'desa' => 'required|string',
            'nama_auditor' => 'required|string',
            'nama_petani' => 'required|string',
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
                $namaFile = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs(
                    'audit_internal',
                    $namaFile,
                    'public'
                );
            }

            $audit = AuditInternal::create([
                'user_id' => $request->user_id,
                'tanggal' => $request->tanggal,
                'desa' => $request->desa,
                'nama_auditor' => $request->nama_auditor,
                'nama_petani' => $request->nama_petani,
                'path_file_kunjungan' => $path,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data audit internal berhasil disimpan',
                'data' => $audit
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
