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
        $lahans = Lahan::with('petani')->get();
        $user = auth()->user();

        // Mengalihkan view sesuai dengan role user
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
        $petanis = Petani::all(); 
        return view('admin.lahan.tambah', compact('petanis')); // <-- Pastikan diarahkan ke 'tambah'
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
        ]);

        Lahan::create([
            'lahan_nama'   => $request->lahan_nama,
            'lahan_lokasi' => $request->lahan_lokasi,
            'lahan_luas'   => $request->lahan_luas,
            'petani_id'    => $request->petani_id,
            'area_lahan'   => json_decode($request->area_lahan), // Decode jika model belum otomatis meng-cast ke json
        ]);

        return redirect()->route('lahan.index')->with('success', 'Data lahan dan polygon berhasil disimpan!');
    }

    // Menampilkan detail satu lahan berdasarkan ID lahan
    public function show($id)
    {
        $lahan = Lahan::with('petani')->findOrFail($id);
        $role = auth()->user()->user_role;

        // 1. Jika yang login adalah Super Admin
        if ($role === 'super_admin') {
            // Mengarah ke folder resources/views/super_Admin/lahan/show.blade.php
            return view('super_admin.lahan.show', compact('lahan'));
        } 
        
        // 2. Jika yang login adalah Admin Biasa
        if ($role === 'admin') {
            // Mengarah ke folder resources/views/lahan/show.blade.php
            return view('admin.lahan.show', compact('lahan'));
        }

        // 3. Jika role lain mencoba masuk
        abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
    }

    // Menampilkan form edit lahan
    public function edit($id)
    {
        $lahan = Lahan::findOrFail($id);
        $petanis = Petani::all();
        $user = auth()->user();

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
            'lahan_lokasi' => 'required|string|max:255',
            'lahan_luas'   => 'required|numeric',
            'petani_id'    => 'required',
        ]);

        $lahan = Lahan::findOrFail($id);
        $lahan->update([
            'lahan_lokasi' => $request->lahan_lokasi,
            'lahan_luas'   => $request->lahan_luas,
            'petani_id'    => $request->petani_id,
        ]);

        return redirect()->route('lahan.index')->with('success', 'Data lahan berhasil diperbarui!');
    }

    // Menghapus data lahan
    public function destroy($id)
    {
        $lahan = Lahan::findOrFail($id);
        $lahan->delete();

        return redirect()->route('lahan.index')->with('success', 'Data lahan berhasil dihapus!');
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

                $namaRaw = $propsLower['nama_petan'] ?? $propsLower['nama_petani'] ?? $propsLower['nama'] ?? '';
                if (empty($namaRaw) || trim($namaRaw) === '') {
                    $jumlahDilewati++;
                    continue; 
                }
                
                $namaBersih = trim(preg_replace('/\s+\d+$/', '', $namaRaw)); 

                // MENCARI DENGAN LIKE (Contoh: "Nita" akan mencocokkan ke "Nita Salsabilla")
                $petaniData = Petani::where('desa_id', $desaId)
                                    ->where('petani_nama', 'ILIKE', $namaBersih . '%') // Mencari yang berawalan nama tersebut
                                    ->first();

                // Pilihan cadangan: Jika masih tidak ketemu, coba cari apakah nama di JSON mengandung bagian dari nama DB
                if (!$petaniData) {
                    $petaniData = Petani::where('desa_id', $desaId)
                                        ->where('petani_nama', 'ILIKE', '%' . $namaBersih . '%')
                                        ->first();
                }

                // JIKA SAMA SEKALI TIDAK ADA DI DATABASE VPS -> SKIP
                if (!$petaniData) {
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
                $kecamatan = $propsLower['kecamatan'] ?? '-';
                $kabupaten = $propsLower['kabupaten'] ?? '-';

                $geometry = $feature['geometry'];
                $geometryJsonString = json_encode($geometry);
                $geometriBaruRaw = "ST_Transform(ST_SetSRID(ST_GeomFromGeoJSON('$geometryJsonString'), 32647), 4326)";

                // CEK DUPLIKASI GANDA (Atribut + Spasial)
                // Cek apakah data lahan dengan Nama Lahan & Petani ini SUDAH ADA
                $lahanEksis = Lahan::where('petani_id', $petaniData->petani_id)
                                   ->where('lahan_nama', $namaLahan)
                                   ->first();

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
                    'lahan_lokasi' => "Kec. " . $kecamatan . ", Kab. " . $kabupaten,
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

            return redirect()->route('lahan.index')->with('success', $statusPesan);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses file GeoJSON. Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}