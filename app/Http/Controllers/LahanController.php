<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Lahan;
use App\Models\Petani;
use App\Models\Desa;

class LahanController extends Controller
{
    // Menampilkan Halaman List Lahan
    public function index()
    {
        $user = auth()->user();

        $lahans = Lahan::with('petani');

        if ($user->user_role === 'admin') {
            $lahans->whereHas('petani', function ($query) use ($user) {
                $query->where('desa_id', $user->desa_id);
            });
        }

        $lahans = $lahans->get();

        if ($user->user_role === 'super_admin') {
            return view('super_admin.lahan.index', compact('lahans'));
        } elseif ($user->user_role === 'admin') {
            return view('admin.lahan.index', compact('lahans'));
        }

        abort(403);
    }

    // Menampilkan Form Input Lahan
    public function create()
    {
        $user = auth()->user();
        $petanis = Petani::query();

        if ($user->user_role === 'admin') {
            $petanis->where('desa_id', $user->desa_id);
        }

        return view('admin.lahan.tambah', ['petanis' => $petanis->get()]);
    }
    // public function create()
    // {
    //     $petanis = Petani::all(); 
    //     $user = auth()->user();
        
    //     if ($user->user_role === 'super_admin') {
    //         return view('super_admin.lahan.create', compact('petanis'));
    //     } elseif ($user->user_role === 'admin') {
    //         return view('admin.lahan.create', compact('petanis'));
    //     }

    //     abort(403);
    // }

    // Memproses Simpan Data Lahan dari Form
    public function store(Request $request)
    {
        $request->validate([
            'lahan_nama'   => 'required|string|max:255',
            'lahan_lokasi' => 'required|string|max:255',
            'lahan_luas'   => 'required|numeric',
            'petani_id'    => 'required',
            'area_lahan'   => 'required|json', // Validasi memastikan bahwa data yang dikirim berformat JSON
            'tahun_tanam'  => 'nullable|integer',
            'lahan_no_surat' => 'nullable|string|max:255',
        ]);

        Lahan::create([
            'lahan_nama'   => $request->lahan_nama,
            'lahan_lokasi' => $request->lahan_lokasi,
            'lahan_luas'   => $request->lahan_luas,
            'petani_id'    => $request->petani_id,
            'tahun_tanam'  => $request->tahun_tanam,
            'lahan_no_surat' => $request->lahan_no_surat,
            'area_lahan'   => json_decode($request->area_lahan), // Decode jika model belum otomatis meng-cast ke json
        ]);

        return redirect()->route('lahan.index')->with('success', 'Data lahan dan polygon berhasil disimpan!');
    }

    // Menampilkan detail satu lahan berdasarkan ID lahan
    public function show($id)
    {
        $lahan = Lahan::with('petani')->findOrFail($id);
        $user = auth()->user();

        if ($user->user_role === 'admin' && $lahan->petani && $lahan->petani->desa_id !== $user->desa_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat lahan ini.');
        }

        if ($user->user_role === 'super_admin') {
            return view('super_admin.lahan.show', compact('lahan'));
        }

        if ($user->user_role === 'admin') {
            return view('admin.lahan.show', compact('lahan'));
        }

        abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
    }

    // Menampilkan form edit lahan
    public function edit($id)
    {
        $lahan = Lahan::findOrFail($id);
        $user = auth()->user();
        $petanis = Petani::query();

        if ($user->user_role === 'admin') {
            $petanis->where('desa_id', $user->desa_id);
        }

        $petanis = $petanis->get();

        if ($user->user_role === 'super_admin') {
            return view('super_admin.lahan.edit', compact('lahan', 'petanis'));
        } elseif ($user->user_role === 'admin') {
            return view('admin.lahan.edit', compact('lahan', 'petanis'));
        }

        abort(403);
    }

    // Memperbarui data lahan
    public function update(Request $request, $id)
    {
        $request->validate([
            'lahan_nama'   => 'required|string|max:255',
            'lahan_lokasi' => 'required|string|max:255',
            'lahan_luas'   => 'required|numeric',
            'petani_id'    => 'required',
            'area_lahan'   => 'nullable|json',
            'tahun_tanam'  => 'nullable|integer',
            'lahan_no_surat' => 'nullable|string|max:255',
        ]);

        $lahan = Lahan::findOrFail($id);
        
        $updateData = [
            'lahan_nama'   => $request->lahan_nama,
            'lahan_lokasi' => $request->lahan_lokasi,
            'lahan_luas'   => $request->lahan_luas,
            'petani_id'    => $request->petani_id,
            'tahun_tanam'  => $request->tahun_tanam,
            'lahan_no_surat' => $request->lahan_no_surat,
        ];
        
        if ($request->has('area_lahan') && !empty($request->area_lahan)) {
            $updateData['area_lahan'] = json_decode($request->area_lahan);
        }

        $lahan->update($updateData);

        return redirect()->route('lahan.index')->with('success', 'Data lahan berhasil diperbarui!');
    }

    // Menghapus data lahan
    public function destroy($id)
    {
        $lahan = Lahan::findOrFail($id);
        $lahan->delete();

        return redirect()->route('lahan.index')->with('success', 'Data lahan berhasil dihapus!');
    }


    private function cariPetaniTerbaik($namaJson, $desaId = null)
    {
        $namaJson = strtolower(trim($namaJson));

        // Hapus angka di belakang (misal: "Adi Mahyudin 1" menjadi "adi mahyudin")
        $namaJson = preg_replace('/\s+\d+$/', '', $namaJson);

        // --- TAHAP 1: CARI DI DESA YANG SAMA (Jika desa valid) ---
        if ($desaId) {
            // 1. Exact Match
            $petani = Petani::where('desa_id', $desaId)
                ->whereRaw('LOWER(petani_nama) = ?', [$namaJson])
                ->first();
            if ($petani) return $petani;

            // 2. LIKE Match
            $petani = Petani::where('desa_id', $desaId)
                ->whereRaw('LOWER(petani_nama) LIKE ?', ['%' . $namaJson . '%'])
                ->first();
            if ($petani) return $petani;

            // 3. Similarity Match
            $petanis = Petani::where('desa_id', $desaId)->get();
            $bestMatch = null;
            $bestScore = 0;

            foreach ($petanis as $p) {
                similar_text($namaJson, strtolower($p->petani_nama), $score);
                if ($score > $bestScore) {
                    $bestScore = $score;
                    $bestMatch = $p;
                }
            }

            // Minimal kemiripan 70% untuk lokal
            if ($bestScore >= 70) {
                return $bestMatch;
            }
        }

        // --- TAHAP 2: CARI DI SELURUH DATABASE (LINTAS DESA) ---
        // 4. Exact Match (Lintas Desa)
        $petaniGlobal = Petani::whereRaw('LOWER(petani_nama) = ?', [$namaJson])->get();
        if ($petaniGlobal->count() === 1) {
            return $petaniGlobal->first(); // Berhasil jika namanya unik di seluruh sistem
        }

        // 5. LIKE Match (Lintas Desa)
        $petaniGlobalLike = Petani::whereRaw('LOWER(petani_nama) LIKE ?', ['%' . $namaJson . '%'])->get();
        if ($petaniGlobalLike->count() === 1) {
            return $petaniGlobalLike->first();
        }

        return null;
    }

    // import file
    // import file GeoJSON dengan sistem LOCK total (Anti-Duplikat Murni)
    public function importGeoJson(Request $request)
    {
        $request->validate([
            'geojson_file' => 'required|file|mimes:json,txt',
        ]);

        $file = $request->file('geojson_file');
        $jsonContent = file_get_contents($file->getRealPath());
        $data = json_decode($jsonContent, true);

        if (!isset($data['features']) || !is_array($data['features'])) {
            return redirect()->back()->with('error', 'Format file GeoJSON tidak valid.');
        }

        DB::beginTransaction();

        $jumlahSukses = 0;
        $jumlahDilewati = 0;

        $gagalMapping = [];

        try {
            foreach ($data['features'] as $index => $feature) {
                if (empty($feature) || !isset($feature['geometry'])) {
                    $jumlahDilewati++;
                    continue;
                }

                $properties = $feature['properties'] ?? [];
                $propsLower = array_change_key_case($properties, CASE_LOWER);

                // VALIDASI DESA
                $namaDesa = isset($propsLower['desa']) ? trim($propsLower['desa']) : '';
                if (empty($namaDesa)) {
                    $jumlahDilewati++;
                    continue; 
                }

                $desaData = DB::table('desa')->where('desa_nama', $namaDesa)->first();
                if (!$desaData) {
                    $jumlahDilewati++;
                    continue; 
                }

                $desaId = $desaData->desa_id;

                // VALIDASI PETANI
                // $namaRaw = $propsLower['nama_petan'] ?? $propsLower['nama_petani'] ?? $propsLower['nama'] ?? '';
                // if (empty($namaRaw) || trim($namaRaw) === '') {
                //     $jumlahDilewati++;
                //     continue; 
                // }
                
                // $namaBersih = trim(preg_replace('/\s+\d+$/', '', $namaRaw)); 

                // $petaniData = Petani::where('petani_nama', $namaBersih)
                //                     ->where('desa_id', $desaId)
                //                     ->first();

                // if (!$petaniData) {
                //     $jumlahDilewati++;
                //     continue; 
                // }

                $namaRaw = $propsLower['nama_petan']
                    ?? $propsLower['nama_petani']
                    ?? $propsLower['nama']
                    ?? '';

                if (empty($namaRaw)) {
                    $jumlahDilewati++;
                    continue;
                }

                $petaniData = $this->cariPetaniTerbaik(
                    $namaRaw,
                    $desaId
                );

                if (!$petaniData) {

                    $gagalMapping[] = [
                        'nama_json' => $namaRaw,
                        'desa' => $namaDesa
                    ];

                    $jumlahDilewati++;
                    continue;
                }

                // PROSES AMBIL DATA ATRIBUT LAHAN
                $hectareRaw = $propsLower['hectare'] ?? $propsLower['hectares'] ?? $propsLower['luas'] ?? 0;
                if (is_string($hectareRaw)) {
                    $hectareRaw = str_replace(',', '.', $hectareRaw);
                }
                $luasLahan = (float) $hectareRaw;

                $namaLahan = $propsLower['id_sub_blo'] ?? $propsLower['no_blok'] ?? $namaRaw;

                $geometry = $feature['geometry'];
                $geometryJsonString = json_encode($geometry);
                $geometriBaruRaw = "ST_Transform(ST_SetSRID(ST_GeomFromGeoJSON('$geometryJsonString'), 32647), 4326)";

                // CEK DUPLIKASI GANDA (Atribut + Spasial)
                // Cek apakah data lahan dengan Nama Lahan & Petani ini SUDAH ADA
                $lahanEksis->update([
                    'lahan_luas'   => $luasLahan,
                    'lahan_lokasi' => $desaData->desa_nama,
                    'area_lahan'   => DB::raw("ST_AsGeoJSON(" . $geometriBaruRaw . ")::jsonb"),
                ]);

                // Pengecekan alternatif via koordinat murni jika nama lahannya berbeda di JSON
                if (!$lahanEksis) {
                    $lahanEksis = Lahan::where('petani_id', $petaniData->petani_id)
                        ->whereRaw("ST_Equals(ST_SetSRID(ST_GeomFromGeoJSON(area_lahan::text), 4326), " . $geometriBaruRaw . ")")
                        ->first();
                }

                // JIKA DATA SUDAH ADA -> KITA UPDATE/REPLACE (JANGAN TAMBAH BARU)
                if ($lahanEksis) {
                    $lahanEksis->update([
                        'lahan_luas'   => $luasLahan,
                        'lahan_lokasi' => "Kec. " . $kecamatan . ", Kab. " . $kabupaten,
                        'area_lahan'   => DB::raw("ST_AsGeoJSON(" . $geometriBaruRaw . ")::jsonb"),
                    ]);
                    $jumlahDilewati++; // Kita hitung dilewati karena tidak membuat data baru
                    continue;
                }

                // JIKA BENAR-BENAR BARU -> CREATE NEW DATA
                Lahan::create([
                    'lahan_nama'   => $namaLahan,
                    'lahan_luas'   => $luasLahan,
                    'lahan_lokasi' => $desaData->desa_nama,
                    'petani_id'    => $petaniData->petani_id,
                    'area_lahan'   => DB::raw("ST_AsGeoJSON(" . $geometriBaruRaw . ")::jsonb"),
                ]);

                $jumlahSukses++;
            }

            // JIKA SAMA SEKALI TIDAK ADA LAHAN BARU YANG DIBUAT
            if ($jumlahSukses === 0) {
                DB::commit(); // Tetap commit karena proses update data lama berhasil dijalankan
                return redirect()->back()->with('success', 'Semua data telah disinkronisasi. Tidak ada data duplikat yang ditambahkan (Data lama otomatis diperbarui).');
            }

            DB::commit();

            $statusPesan = "Berhasil mengimport " . $jumlahSukses . " lahan baru.";
            if ($jumlahDilewati > 0) {
                $statusPesan .= " Sebanyak " . $jumlahDilewati . " data lama berhasil diperbarui/dilewati.";
            }

            return redirect()
                ->route('lahan.index')
                ->with('success', $statusPesan)
                ->with('gagal_mapping', $gagalMapping);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses file GeoJSON. Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // preview
    public function previewImport(Request $request)
    {
        $request->validate([
            'geojson_file' => 'required|file|mimes:json,txt'
        ]);

        $json = json_decode(
            file_get_contents(
                $request->file('geojson_file')->getRealPath()
            ),
            true
        );

        if (!isset($json['features'])) {
            return back()->with(
                'error',
                'Format GeoJSON tidak valid'
            );
        }

        $preview = [];

        foreach ($json['features'] as $feature) {

            $properties = $feature['properties'] ?? [];

            $namaPetani =
                $properties['Nama_Petan']
                ?? $properties['Nama_Petani']
                ?? '';

            $namaDesa =
                $properties['Desa']
                ?? '';

            $desa = Desa::where(
                'desa_nama',
                $namaDesa
            )->first();
            
            $desaId = $desa ? $desa->desa_id : null;

            $petani = $this->cariPetaniTerbaik($namaPetani, $desaId);

            $geometry = $feature['geometry'] ?? null;
            $isDuplicate = false;
            
            if ($geometry) {
                // Kita gunakan PHP array comparison yang sudah terbukti jalan dari processImport
                $geometryJson = json_encode($geometry);
                $hasil = DB::selectOne("
                    SELECT ST_AsGeoJSON(
                        ST_Transform(
                            ST_SetSRID(
                                ST_GeomFromGeoJSON(?),
                                32647
                            ),
                            4326
                        )
                    ) AS geojson
                ", [$geometryJson]);

                if ($hasil && $hasil->geojson) {
                    $geojson4326 = json_decode($hasil->geojson, true);
                    $isDuplicate = $this->polygonSudahAda($geojson4326);
                }
            }

            $preview[] = [
                'nama_json' => $namaPetani,
                'desa' => $namaDesa,
                'petani_db' => $petani?->petani_nama,
                'petani_id' => $petani?->petani_id,
                'status' => $petani ? ($isDuplicate ? 'duplikat' : 'cocok') : 'tidak_cocok',
                'feature' => $feature
            ];
        }

        session([
            'preview_geojson' => $preview
        ]);

        $role = auth()->user()->user_role;

        if ($role === 'super_admin') {
            return view(
                'super_admin.lahan.preview_import',
                compact('preview')
            );
        }

        return view(
            'admin.lahan.preview_import',
            compact('preview')
        );
    }

    //
    private function cekPolygonSudahAda($geometry)
    {
        $geometryJson = json_encode($geometry);

        $existing = DB::selectOne("
            SELECT lahan_id
            FROM lahan
            WHERE ST_Equals(
                ST_SetSRID(
                    ST_GeomFromGeoJSON(area_lahan::text),
                    4326
                ),
                ST_Transform(
                    ST_SetSRID(
                        ST_GeomFromGeoJSON(?),
                        32647
                    ),
                    4326
                )
            )
            LIMIT 1
        ", [$geometryJson]);

        return $existing ? true : false;
    }

   public function processImport()
    {
        $preview = session('preview_geojson');

        if (!$preview) {
            return redirect()
                ->route('lahan.index')
                ->with(
                    'error',
                    'Data preview tidak ditemukan'
                );
        }

        DB::beginTransaction();

        try {

            $jumlahImport = 0;
            $jumlahSkip   = 0;

            foreach ($preview as $item) {

                if ($item['status'] === 'duplikat') {
                    $jumlahSkip++;
                    continue;
                }

                if ($item['status'] !== 'cocok') {
                    continue;
                }

                $feature = $item['feature'];

                $properties = $feature['properties'] ?? [];

                $geometry = $feature['geometry'] ?? null;

                if (!$geometry) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | KONVERSI 32647 -> 4326
                |--------------------------------------------------------------------------
                */

                $geometryJson = json_encode($geometry);

                $hasil = DB::selectOne("
                    SELECT ST_AsGeoJSON(
                        ST_Transform(
                            ST_SetSRID(
                                ST_GeomFromGeoJSON(?),
                                32647
                            ),
                            4326
                        )
                    ) AS geojson
                ", [$geometryJson]);

                $geojson4326 = json_decode(
                    $hasil->geojson,
                    true
                );

                /*
                |--------------------------------------------------------------------------
                | CEK DUPLIKAT POLYGON
                |--------------------------------------------------------------------------
                */

                if ($this->polygonSudahAda($geojson4326)) {

                    $jumlahSkip++;

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | DATA ATRIBUT
                |--------------------------------------------------------------------------
                */

                $luas =
                    $properties['Hectare']
                    ?? $properties['Hectares']
                    ?? $properties['hectare']
                    ?? $properties['hectares']
                    ?? 0;

                if (is_string($luas)) {
                    $luas = str_replace(',', '.', $luas);
                }

                $namaLahan =
                    $properties['Id_Sub_Blo']
                    ?? $properties['id_sub_blo']
                    ?? $properties['No_Blok']
                    ?? $properties['no_blok']
                    ?? 'Lahan';

                $petani = Petani::with('desa')
                    ->find($item['petani_id']);

                if (!$petani) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | SIMPAN
                |--------------------------------------------------------------------------
                */

                Lahan::create([
                    'petani_id' => $petani->petani_id,
                    'lahan_nama' => $namaLahan,
                    'lahan_luas' => (float) $luas,
                    'lahan_lokasi' => $petani->desa->desa_nama,
                    'area_lahan' => $geojson4326
                ]);

                $jumlahImport++;
            }

            DB::commit();

            session()->forget(
                'preview_geojson'
            );

            return redirect()
                ->route('lahan.index')
                ->with(
                    'success',
                    "Import selesai. {$jumlahImport} data berhasil ditambahkan dan {$jumlahSkip} data dilewati karena polygon sudah ada."
                );

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->route('lahan.index')
                ->with(
                    'error',
                    'Gagal import: ' . $e->getMessage()
                );
        }
    }

    private function polygonSudahAda($geojson4326)
    {
        if (
            !is_array($geojson4326)
            || !isset($geojson4326['coordinates'])
            || !isset($geojson4326['coordinates'][0])
        ) {
            return false;
        }

        $coordsBaru = collect(
            $geojson4326['coordinates'][0]
        )->map(function ($item) {

            return [
                round($item[0], 6),
                round($item[1], 6)
            ];

        })->toArray();

        foreach (Lahan::all() as $lahan) {

            $polygonLama = is_array($lahan->area_lahan)
                ? $lahan->area_lahan
                : json_decode($lahan->area_lahan, true);

            if (
                !$polygonLama ||
                !isset($polygonLama['coordinates']) ||
                !isset($polygonLama['coordinates'][0])
            ) {
                continue;
            }

            $coordsLama = collect(
                $polygonLama['coordinates'][0]
            )->map(function ($item) {

                return [
                    round($item[0], 6),
                    round($item[1], 6)
                ];

            })->toArray();

            if ($coordsBaru == $coordsLama) {
                return true;
            }
        }

        return false;
    }
}