<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditInternal;
use App\Models\Petani;
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

            $petani = Petani::where('petani_nama', $request->nama_petani)->first();
            $petani_id = $petani ? $petani->petani_id : null;

            $audit = AuditInternal::create([
                'user_id' => $request->user_id,
                'tanggal' => $request->tanggal,
                'desa' => $request->desa,
                'nama_auditor' => $request->nama_auditor,
                'nama_petani' => $request->nama_petani,
                'petani_id' => $petani_id,
                'is_read' => 0,
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

    public function getNotifications($petani_id)
    {
        $notifications = AuditInternal::where('petani_id', $petani_id)
            ->orderBy('tanggal', 'desc')
            ->orderBy('id_audit', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil diambil',
            'data' => $notifications
        ], 200);
    }

    public function markAsRead($id_audit)
    {
        $audit = AuditInternal::find($id_audit);
        if ($audit) {
            $audit->is_read = 1;
            $audit->save();

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi ditandai sudah dibaca'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan'
        ], 404);
    }
}
