<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BiayaOperasional;
use App\Models\DetailBiayaOperasional;
use App\Models\Lahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BiayaOperasionalController extends Controller
{
    public function index()
    {
        $data = BiayaOperasional::with([
            "petani",
            "lahan"
        ])->latest()->get();

        return response()->json([
            "success" => true,
            "message" => "Data biaya operasional berhasil diambil",
            "data" => $data
        ]);
    }

    public function show($id)
    {
        $biaya = BiayaOperasional::with([
            "petani",
            "detailBiayaOperasional.lahan"
        ])->find($id);

        if (!$biaya) {
            return response()->json([
                "success" => false,
                "message" => "Data biaya operasional tidak ditemukan"
            ], 404);
        }

        return response()->json([
            "success" => true,
            "message" => "Detail biaya operasional berhasil diambil",
            "data" => [
                "id" => $biaya->id,
                "biaya_tanggal" => $biaya->biaya_tanggal,
                "biaya_nama" => $biaya->biaya_nama,
                "biaya_jenis" => $biaya->biaya_jenis,
                "biaya_jumlah" => $biaya->biaya_jumlah,
                "biaya_total" => $biaya->biaya_total,
                "biaya_ket" => $biaya->biaya_ket,

                "biaya_bukti" => $biaya->biaya_bukti,
                "biaya_bukti_url" => $biaya->biaya_bukti
                    ? asset("storage/" . $biaya->biaya_bukti)
                    : null,

                "petani" => [
                    "id" => $biaya->petani->petani_id ?? null,
                    "nama" => $biaya->petani->petani_nama ?? null
                ],

                "detail_biaya" => $biaya->detailBiayaOperasional->map(function ($detail) {
                    return [
                        "id" => $detail->id,
                        "nama_detail" => $detail->nama_detail ?? null, 
                        "subtotal" => $detail->subtotal ?? null,
                        
                        "lahan" => [
                            "id" => $detail->lahan->lahan_id ?? null,
                            "nama" => $detail->lahan->lahan_nama ?? null
                        ]
                    ];
                })
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            "biaya_tanggal" => "required|date",
            "biaya_nama"    => "required|string|max:255",
            "biaya_jenis"   => "required|string|max:255",
            "biaya_jumlah"  => "required|numeric",
            "petani_id"     => "required|exists:petani,petani_id",
            "lahan_id"      => "required|array",
            "lahan_id.*"    => "integer",
            "biaya_ket"     => "nullable|string",

            "biaya_bukti"   => "nullable|image|mimes:jpg,jpeg,png|max:2048"
        ]);

        $path = null;

        if ($request->hasFile("biaya_bukti")) {
            $path = $request->file("biaya_bukti")
                ->store("bukti-biaya", "public");
        }

        $totalBiaya = (float) ($request->biaya_total ?? 0);

        DB::beginTransaction();
        try {
            $biaya = BiayaOperasional::create([
                "biaya_tanggal" => $request->biaya_tanggal,
                "biaya_nama"    => $request->biaya_nama,
                "biaya_jenis"   => $request->biaya_jenis,
                "biaya_jumlah"  => $request->biaya_jumlah,
                "biaya_total"   => $totalBiaya,
                "biaya_ket"     => $request->biaya_ket,
                "petani_id"     => $request->petani_id,
                "biaya_bukti"   => $path
            ]);

            $lahanIds = is_array($request->lahan_id) ? $request->lahan_id : [$request->lahan_id];

            $lahans = collect();
            if (class_exists(Lahan::class)) {
                try {
                    $lahans = Lahan::whereIn("lahan_id", $lahanIds)->get();
                    if ($lahans->isEmpty()) {
                        $lahans = Lahan::whereIn("id", $lahanIds)->get();
                    }
                } catch (\Exception $ex) {
                    try {
                        $lahans = DB::table("lahan")->whereIn("lahan_id", $lahanIds)->get();
                    } catch (\Exception $e) {
                        $lahans = DB::table("lahan")->whereIn("id", $lahanIds)->get();
                    }
                }
            } else {
                try {
                    $lahans = DB::table("lahan")->whereIn("lahan_id", $lahanIds)->get();
                } catch (\Exception $e) {
                    $lahans = DB::table("lahan")->whereIn("id", $lahanIds)->get();
                }
            }

            $totalLuasLahan = $lahans->sum(function ($lahan) {
                return (float) ($lahan->lahan_luas ?? $lahan->luas_lahan ?? $lahan->luas ?? 0);
            });

            $countLahan = count($lahanIds);
            $totalDihitung = 0;

            $subtotalArr = $request->input("subtotal_detail") 
                ?? $request->input("subtotal") 
                ?? [];

            foreach ($lahanIds as $key => $lahanId) {
                $lahanModel = $lahans->firstWhere("lahan_id", $lahanId) ?? $lahans->firstWhere("id", $lahanId);
                $luasLahan = $lahanModel ? ((float) ($lahanModel->lahan_luas ?? $lahanModel->luas_lahan ?? $lahanModel->luas ?? 0)) : 0;

                if (isset($subtotalArr[$key]) && (float)$subtotalArr[$key] > 0) {
                    $subtotalDetail = (float)$subtotalArr[$key];
                } else {
                    if ($key == $countLahan - 1) {
                        $subtotalDetail = $totalBiaya - $totalDihitung;
                    } else {
                        if ($totalLuasLahan > 0 && $luasLahan > 0) {
                            $subtotalDetail = ($totalBiaya / $totalLuasLahan) * $luasLahan;
                        } else {
                            $subtotalDetail = $countLahan > 0 ? ($totalBiaya / $countLahan) : 0;
                        }
                    }
                }
                $totalDihitung += $subtotalDetail;

                DetailBiayaOperasional::create([
                    "biaya_operasional_id" => $biaya->id,
                    "lahan_id"            => $lahanId,
                    "subtotal"            => round($subtotalDetail, 2),
                ]);
            }

            DB::commit();

            return response()->json([
                "success" => true,
                "message" => "Data biaya operasional berhasil ditambahkan",
                "data" => $biaya
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                "message" => "Gagal menyimpan biaya operasional: " . $e->getMessage()
            ], 500);
        }
    }
}

