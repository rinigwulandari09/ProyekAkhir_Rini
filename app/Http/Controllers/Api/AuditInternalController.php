<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditInternal;
use App\Models\Petani;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

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
            'file_kunjungan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240' // Max 10MB
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
                'id_audit' => $request->id_audit,
                'user_id' => $request->user_id,
                'tanggal' => $request->tanggal,
                'desa' => $request->desa,
                'nama_auditor' => $request->nama_auditor,
                'nama_petani' => $request->nama_petani,
                'petani_id' => $petani_id,
                'is_read' => 0,
                'path_file_kunjungan' => $path,
                'status_audit' => $request->status_audit,
                'keterangan' => $request->keterangan,
                'periode' => $request->periode,
                'audit_attempt' => $request->audit_attempt ?? 1
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

    public function getAllByDesa(Request $request)
    {
        $desa = $request->input('desa');
        
        $query = AuditInternal::query();
        if ($desa) {
            $query->where('desa', $desa);
        }

        $audits = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Data audit berhasil diambil',
            'data' => $audits
        ], 200);
    }

    public function getNotifications($petani_id)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $currentMonthStr = str_pad($currentMonth, 2, '0', STR_PAD_LEFT);
        $likePattern = "{$currentYear}-{$currentMonthStr}-%";

        $audit = \App\Models\AuditInternal::where('petani_id', $petani_id)
            ->where('tanggal', 'like', $likePattern)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id_audit,
                    'type' => 'audit',
                    'title' => 'Hasil Audit Internal Baru',
                    'message' => 'Auditor: ' . $item->nama_auditor,
                    'tanggal' => $item->tanggal,
                    'is_read' => $item->is_read,
                    'data_url' => $item->path_file_kunjungan
                ];
            });

        $produksi = \App\Models\Produksi::where('petani_id', $petani_id)
            ->where('produksi_tanggal', 'like', $likePattern)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'produksi',
                    'title' => 'Pemasukan Baru',
                    'message' => 'Pendapatan: Rp ' . number_format($item->total_pendapatan, 0, ',', '.'),
                    'tanggal' => $item->produksi_tanggal,
                    'is_read' => $item->is_read ?? 0, // Fallback to 0 if column doesn't exist yet
                    'data_url' => null
                ];
            });

        $pengeluaran = \App\Models\BiayaOperasional::where('petani_id', $petani_id)
            ->where('biaya_tanggal', 'like', $likePattern)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'pengeluaran',
                    'title' => 'Pengeluaran Baru',
                    'message' => 'Total: Rp ' . number_format($item->biaya_total, 0, ',', '.'),
                    'tanggal' => $item->biaya_tanggal,
                    'is_read' => $item->is_read ?? 0,
                    'data_url' => null
                ];
            });

        // Merge and Sort by Date Descending, then ID Descending (agar notifikasi terbaru selalu di paling atas)
        $notifications = $audit->concat($produksi)
                               ->concat($pengeluaran)
                               ->sortBy([
                                   ['tanggal', 'desc'],
                                   ['id', 'desc']
                               ])
                               ->values();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil diambil',
            'data' => $notifications
        ], 200);
    }

    public function markAsRead(Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('id');

        if (!$type || !$id) {
            return response()->json(['success' => false, 'message' => 'Parameter type dan id wajib diisi'], 400);
        }

        $record = null;

        if ($type === 'audit') {
            $record = \App\Models\AuditInternal::find($id);
        } elseif ($type === 'produksi') {
            $record = \App\Models\Produksi::find($id);
        } elseif ($type === 'pengeluaran') {
            $record = \App\Models\BiayaOperasional::find($id);
        }

        if ($record) {
            $record->is_read = 1;
            $record->save();

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
