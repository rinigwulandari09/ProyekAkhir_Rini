<?php

namespace App\Http\Controllers;

use App\Models\Petani;
use App\Models\User;
use App\Models\Desa;
use Illuminate\Http\Request;

class PetaniController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $petaniQuery = Petani::with('desa');

        if ($user->user_role === 'admin') {
            $petaniQuery->where('desa_id', $user->desa_id);
        }

        $petani = $petaniQuery->get();

        if ($user->user_role === 'super_admin') {
            return view('super_admin.petani.index', compact('petani'));
        } elseif ($user->user_role === 'admin') {
            return view('admin.petani.index', compact('petani'));
        }

        abort(403);
    }
    
    public function create()
    {
        $desas = Desa::orderBy('desa_nama')->get();
        $user = auth()->user();

        if ($user->user_role === 'super_admin') {
            return view('super_admin.petani.create', compact('desas'));
        } elseif ($user->user_role === 'admin') {
            return view('admin.petani.create', compact('desas'));
        }

        abort(403);
    }

    public function store(Request $request)
    {
        $request->validate([
            'petani_nama' => 'required|string|max:255',
            'petani_username' => 'required|string|max:100|unique:petani,petani_username',
            'petani_email' => 'nullable|email|max:255',
            'petani_no_hp' => 'required|string|max:30',
            'petani_status' => 'required|in:Aktif,Nonaktif',
            'petani_jenis_kelamin' => 'nullable|string|max:10',
            'petani_tanggal_lahir' => 'nullable|date',
            'petani_alamat' => 'nullable|string',
            'desa_id' => 'nullable|integer|exists:desa,desa_id',
            'petani_pin' => 'required|string|min:6'
        ]);

        $username = $request->petani_username;
        if (User::where('user_username', $username)->exists()) {
            return back()->withErrors(['petani_username' => 'Username ini sudah terdaftar sebagai akun Admin!'])->withInput();
        }

        Petani::create([
            'petani_nama' => $request->petani_nama,
            'petani_username' => $request->petani_username,
            'petani_email' => $request->petani_email,
            'petani_no_hp' => $request->petani_no_hp,
            'petani_status' => $request->petani_status,
            'petani_jenis_kelamin' => $request->petani_jenis_kelamin,
            'petani_tanggal_lahir' => $request->petani_tanggal_lahir,
            'petani_alamat' => $request->petani_alamat,
            'desa_id' => $request->desa_id,
            'petani_pin' => \Illuminate\Support\Facades\Hash::make($request->petani_pin)
        ]);

        return redirect()
            ->route('petani.index')
            ->with('success', 'Data petani berhasil ditambahkan');
    }
    
    public function edit($id)
    {
        $petani = Petani::with(['lahans', 'desa'])->findOrFail($id);
        $desas = Desa::orderBy('desa_nama')->get();
        $user = auth()->user();

        // Jika Super Admin, gunakan pembungkus view super_admin
        if ($user->user_role === 'super_admin') {
            return view('super_admin.petani.edit', compact('petani', 'desas'));
        } 
        // Jika Admin, gunakan pembungkus view admin biasa
        elseif ($user->user_role === 'admin') {
            return view('admin.petani.edit', compact('petani', 'desas'));
        }

        abort(403);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'petani_nama' => 'required|string|max:255',
            'petani_username' => 'required|string|max:100',
            'petani_email' => 'nullable|email|max:255',
            'petani_no_hp' => 'required|string|max:30',
            'petani_status' => 'required|in:Aktif,Nonaktif',
            'petani_jenis_kelamin' => 'nullable|string|max:10',
            'petani_tanggal_lahir' => 'nullable|date',
            'petani_alamat' => 'nullable|string',
            'desa_id' => 'nullable|integer|exists:desa,desa_id'
        ]);

        $petani = Petani::findOrFail($id);

        $username = $request->petani_username;
        if (User::where('user_username', $username)->exists()) {
            return back()->withErrors(['petani_username' => 'Username ini sudah terdaftar sebagai akun Admin!'])->withInput();
        }
        if (Petani::where('petani_username', $username)->where('petani_id', '!=', $id)->exists()) {
            return back()->withErrors(['petani_username' => 'Username ini sudah terdaftar pada akun Petani lain!'])->withInput();
        }

        $petani->update([
            'petani_nama' => $request->petani_nama,
            'petani_username' => $request->petani_username,
            'petani_email' => $request->petani_email,
            'petani_no_hp' => $request->petani_no_hp,
            'petani_status' => $request->petani_status,
            'petani_jenis_kelamin' => $request->petani_jenis_kelamin,
            'petani_tanggal_lahir' => $request->petani_tanggal_lahir,
            'petani_alamat' => $request->petani_alamat,
            'desa_id' => $request->desa_id
        ]);

        return redirect()
            ->route('petani.edit', $id)
            ->with('success', 'Data petani berhasil diperbarui');
    }

    public function destroy($id)
    {
        // Mencari data berdasarkan primary key petani_id
        $petani = Petani::findOrFail($id);
        $petani->delete();

        return redirect()
            ->route('petani.index')
            ->with('success', 'Data petani berhasil dihapus');
    }

    public function formatImport()
    {
        if (auth()->user()->user_role !== 'super_admin') {
            abort(403);
        }

        $desas = \App\Models\Desa::pluck('desa_nama')->toArray();
        $panduanDesa = empty($desas) ? 'Ketik Nama Desa' : 'Pilih/Ketik: ' . implode(', ', $desas);

        $data = [
            [
                'Nama Petani', 
                'Username', 
                'Email', 
                'No HP', 
                'Jenis Kelamin (L/P)', 
                'Tanggal Lahir (YYYY-MM-DD)', 
                'Alamat', 
                'Nama Desa (' . $panduanDesa . ')', 
                'PIN (Min 6 Karakter)'
            ]
        ];

        $contohDesa = !empty($desas) ? $desas[0] : 'Sukamaju';
        $data[] = [
            'Budi Santoso',
            'budi123',
            'budi@email.com',
            '08123456789',
            'L',
            '1990-01-01',
            'Jl. Mawar No. 2',
            $contohDesa,
            '123456'
        ];

        $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($data);
        
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="Format_Import_Petani.xlsx"',
        ];

        $callback = function () use ($xlsx) {
            echo $xlsx;
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        if (auth()->user()->user_role !== 'super_admin') {
            abort(403);
        }

        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls|max:2048'
        ], [
            'file_excel.required' => 'File Excel wajib diunggah',
            'file_excel.mimes' => 'Format file harus berupa Excel (.xlsx atau .xls)',
            'file_excel.max' => 'Ukuran file maksimal 2MB',
        ]);

        $file = $request->file('file_excel');
        
        if ($xlsx = \Shuchkin\SimpleXLSX::parse($file->getPathname())) {
            $rows = $xlsx->rows();
            $header = true;
            $sukses = 0;
            $gagal = 0;

            foreach ($rows as $row) {
                if ($header) {
                    $header = false;
                    continue;
                }

                // Skip jika baris kosong
                if (empty(array_filter($row))) {
                    continue;
                }

                try {
                    $username = trim($row[1] ?? '');
                    
                    // Cek apakah username sudah ada
                    if (\App\Models\User::where('user_username', $username)->exists() || 
                        Petani::where('petani_username', $username)->exists()) {
                        $gagal++;
                        continue;
                    }

                    // Cari ID Desa berdasarkan Nama Desa
                    $nama_desa = trim($row[7] ?? '');
                    $desa_id = null;
                    
                    if (!empty($nama_desa)) {
                        $desa = \App\Models\Desa::where('desa_nama', $nama_desa)->first();
                        if ($desa) {
                            $desa_id = $desa->desa_id;
                        } else {
                            // Jika nama desa tidak ditemukan, lewati baris ini
                            $gagal++;
                            continue;
                        }
                    }

                    Petani::create([
                        'petani_nama' => trim($row[0] ?? ''),
                        'petani_username' => $username,
                        'petani_email' => trim($row[2] ?? ''),
                        'petani_no_hp' => trim($row[3] ?? ''),
                        'petani_status' => 'Aktif',
                        'petani_jenis_kelamin' => trim($row[4] ?? ''),
                        'petani_tanggal_lahir' => trim($row[5] ?? '') ?: null,
                        'petani_alamat' => trim($row[6] ?? ''),
                        'desa_id' => $desa_id,
                        'petani_pin' => \Illuminate\Support\Facades\Hash::make(trim($row[8] ?? '123456'))
                    ]);
                    $sukses++;
                } catch (\Exception $e) {
                    $gagal++;
                }
            }
            
            return redirect()->route('petani.index')
                ->with('success', "Import selesai: $sukses berhasil, $gagal gagal (cek duplikat username, format salah, atau nama desa tidak valid).");
        } else {
            return back()->with('error', 'Gagal membaca file Excel: ' . \Shuchkin\SimpleXLSX::parseError());
        }
    }
}