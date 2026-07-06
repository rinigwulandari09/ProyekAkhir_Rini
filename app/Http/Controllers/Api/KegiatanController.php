<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KegiatanController extends Controller
{
    /**
     * Menampilkan semua data kegiatan
     */
    public function index()
    {
        $kegiatan = Kegiatan::all();

        return response()->json([
            'success' => true,
            'message' => 'Daftar semua kegiatan',
            'data'    => $kegiatan
        ], 200);
    }

    /**
     * Menyimpan data kegiatan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'petani_id'          => 'required|integer',
            'jenis_kegiatan_id'  => 'required|integer',
            'kegiatan_tanggal'   => 'required|date',
            'kegiatan_jumlah'    => 'required|numeric',
            'kegiatan_satuan'    => 'required|string|max:50',
            'kegiatan_ket'       => 'nullable|string',
            'lahan_id'           => 'required|array',
            'lahan_id.*'         => 'integer',
        ]);

        $kegiatan = Kegiatan::create([
            'petani_id'          => $request->petani_id,
            'jenis_kegiatan_id'  => $request->jenis_kegiatan_id,
            'kegiatan_tanggal'   => $request->kegiatan_tanggal,
            'kegiatan_jumlah'    => $request->kegiatan_jumlah,
            'kegiatan_satuan'    => $request->kegiatan_satuan,
            'kegiatan_ket'       => $request->kegiatan_ket,
        ]);

        foreach ($request->lahan_id as $lahanId) {

            DetailKegiatan::create([
                'kegiatan_id' => $kegiatan->kegiatan_id,
                'lahan_id'    => $lahanId,
            ]);

        }

        return response()->json([
            'success' => true,
            'message' => 'Kegiatan berhasil disimpan',
            'data'    => $kegiatan,
        ], 201);

    }

    /**
     * Menampilkan detail satu kegiatan berdasarkan id_kegiatan
     */
    public function show($id)
    {
        try {
            // Menggunakan findOrFail karena primaryKey custom (id_kegiatan)
            $kegiatan = Kegiatan::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Detail data kegiatan',
                'data'    => $kegiatan
            ], 200);
            
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data kegiatan tidak ditemukan'
            ], 404);
        }
    }

    public function riwayat(Request $request)
    {
        $query = Kegiatan::with([
            'jenis',
            'detailLahan.lahan'
        ]);

        if ($request->filled('petani_id')) {

            $query->where(
                'petani_id',
                $request->petani_id
            );

        }

        if ($request->filled('bulan')) {

            $query->whereMonth(
                'kegiatan_tanggal',
                $request->bulan
            );

        }

        if ($request->filled('tahun')) {

            $query->whereYear(
                'kegiatan_tanggal',
                $request->tahun
            );

        }

        if ($request->filled('jenis')) {

            $query->whereHas('jenis', function ($q) use ($request){

                $q->where(
                    'nama_jenis',
                    'ILIKE',
                    '%'.$request->jenis.'%'
                );

            });

        }

        if ($request->filled('lahan_id')) {

            $query->whereHas('detailLahan', function($q) use ($request){

                $q->where(
                    'lahan_id',
                    $request->lahan_id
                );

            });

        }

        return response()->json(

            $query
                ->orderByDesc('kegiatan_tanggal')
                ->get()

        );
    }

    public function detail($id)
    {
        $kegiatan = Kegiatan::with([
            'jenis',
            'detailLahan.lahan'
        ])->findOrFail($id);

        return response()->json($kegiatan);
    }
}