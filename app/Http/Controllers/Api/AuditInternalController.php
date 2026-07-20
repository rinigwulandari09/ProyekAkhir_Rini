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
            'id_audit' => 'required|string',
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
                $filename = time() . '_' . $file->getClientOriginalName();
                // Store in storage/app/public/audit_internal
                $path = $file->storeAs('public/audit_internal', $filename);
                // Ubah format string agar sesuai jika dipanggil di frontend (storage/...)
                $path = str_replace('public/', 'storage/', $path);
            }

            $audit = AuditInternal::create([
                'id_audit' => $request->id_audit,
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
