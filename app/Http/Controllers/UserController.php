<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Petani;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Tampilkan Semua User dari Supabase
    public function index()
    {
        $users = User::with('desa')->get();

        return view('super_admin.user.index', compact('users'));
    }

    // Form Tambah User
    public function create()
    {
        $desas = Desa::orderBy('desa_nama')->get();

        return view('super_admin.user.create', compact('desas'));
    }

    // Proses Simpan User Baru
    public function store(Request $request)
    {
        $request->validate([
            'user_nama' => 'required|string|max:255',
            'user_username' => 'required|string|unique:users,user_username',
            'user_email' => 'required|email|max:255|unique:users,user_email', // Validasi email baru & unik
            'user_password' => 'required|digits:6',
            'user_role' => 'required|in:super_admin,admin',
            'desa_id' => 'nullable|exists:desa,desa_id',
        ], [
            'user_username.unique' => 'Username ini sudah terdaftar di Supabase!',
            'user_email.required' => 'Email wajib diisi.',
            'user_email.email' => 'Format email tidak valid.',
            'user_email.unique' => 'Email ini sudah terdaftar di Supabase!',
            'user_password.required' => 'Password wajib diisi.',
            'user_password.digits' => 'Password harus berupa 6 angka.'
        ]);

        $username = $request->user_username;
        if (Petani::where('petani_username', $username)->exists()) {
            return back()->withErrors(['user_username' => 'Username ini sudah terdaftar sebagai akun Petani!'])->withInput();
        }

        User::create([
            'user_nama' => $request->user_nama,
            'user_username' => $request->user_username,
            'user_email' => $request->user_email,
            'user_password' => Hash::make($request->user_password),
            'user_role' => $request->user_role,
            'desa_id' => $request->desa_id,
        ]);

        return redirect()->route('user.index')->with('success', 'User ' . $request->user_nama . ' berhasil ditambahkan!');
    }

    // Form Edit User
    public function edit($id)
    {
        $user = User::where('user_id', $id)->firstOrFail();
        $desas = Desa::all();
        // dd($desas->toArray());
        return view('super_admin.user.edit', compact('user', 'desas'));
    }

    // Proses Update User
    public function update(Request $request, $id)
    {
        $user = User::where('user_id', $id)->firstOrFail();

        $request->validate([
            'user_nama' => 'required|string|max:255',
            // Pengecualian unique agar bisa update data sendiri tanpa bentrok dengan id yang sedang diedit
            'user_username' => 'required|string|unique:users,user_username,'.$id.',user_id',
            'user_email' => 'required|email|max:255|unique:users,user_email,'.$id.',user_id', // Pengecualian unique untuk email
            'user_role' => 'required|in:super_admin,admin',
            'desa_id' => 'nullable|exists:desa,desa_id',
            'user_password' => 'nullable|digits:6', // Validasi password jika diisi saat edit
        ], [
            'user_username.unique' => 'Username ini sudah digunakan oleh user lain!',
            'user_email.unique' => 'Email ini sudah digunakan oleh user lain!',
            'user_password.digits' => 'Password baru harus berupa 6 angka.'
        ]);

        $data = [
            'user_nama' => $request->user_nama,
            'user_username' => $request->user_username,
            'user_email' => $request->user_email,
            'user_role' => $request->user_role,
            'desa_id' => $request->desa_id,
        ];

        // Hanya ganti password jika diisi di form edit
        if ($request->filled('user_password')) {
            $data['user_password'] = Hash::make($request->user_password);
        }

        $username = $request->user_username;
        if (Petani::where('petani_username', $username)->exists()) {
            return back()->withErrors(['user_username' => 'Username ini sudah terdaftar sebagai akun Petani!'])->withInput();
        }

        $user->update($data);

        return redirect()->route('user.index')->with('success', 'Data user berhasil diperbarui.');
    }

    // Proses Hapus User
    public function destroy($id)
    {
        $user = User::where('user_id', $id)->firstOrFail();
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
    }

    // Download Format Excel untuk Import User
    public function formatImport()
    {
        if (auth()->user()->user_role !== 'super_admin') {
            abort(403);
        }

        $desas = Desa::pluck('desa_nama')->toArray();
        $panduanDesa = empty($desas) ? 'Ketik Nama Desa' : 'Pilih/Ketik: ' . implode(', ', $desas);

        $data = [
            [
                'Nama Lengkap',
                'Username',
                'Email',
                'Password (6 Angka)',
                'Role (admin / super_admin)',
                'Nama Desa Tugas (' . $panduanDesa . ')'
            ]
        ];

        $contohDesa = !empty($desas) ? $desas[0] : 'Beringin Indah';
        $data[] = [
            'Budi Santoso',
            'budi_admin',
            'budi.admin@example.com',
            '123456',
            'admin',
            $contohDesa
        ];
        $data[] = [
            'Siti Rahma',
            'siti_super',
            'siti.super@example.com',
            '654321',
            'super_admin',
            ''
        ];

        $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($data);

        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="Format_Import_User.xlsx"',
        ];

        $callback = function () use ($xlsx) {
            echo $xlsx;
        };

        return response()->stream($callback, 200, $headers);
    }

    // Proses Import File Excel User
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
                    $nama     = trim($row[0] ?? '');
                    $username = trim((string)($row[1] ?? ''));
                    $email    = trim($row[2] ?? '');
                    $password = trim((string)($row[3] ?? ''));
                    $role     = strtolower(trim($row[4] ?? 'admin'));
                    $namaDesa = trim($row[5] ?? '');

                    if (empty($nama) || empty($username) || empty($email)) {
                        $gagal++;
                        continue;
                    }

                    // Cek username duplikat di user dan petani
                    if (User::where('user_username', $username)->exists() || Petani::where('petani_username', $username)->exists()) {
                        $gagal++;
                        continue;
                    }

                    // Cek email duplikat di user
                    if (User::where('user_email', $email)->exists()) {
                        $gagal++;
                        continue;
                    }

                    // Validasi role
                    if (!in_array($role, ['super_admin', 'admin'])) {
                        $role = 'admin';
                    }

                    // Validasi password (harus 6 angka)
                    if (empty($password) || strlen($password) !== 6 || !is_numeric($password)) {
                        $password = '123456';
                    }

                    // Cari ID Desa jika role admin dan nama desa diisi
                    $desa_id = null;
                    if (!empty($namaDesa)) {
                        $desa = Desa::where('desa_nama', $namaDesa)->first();
                        if ($desa) {
                            $desa_id = $desa->desa_id;
                        } else if ($role === 'admin') {
                            $gagal++;
                            continue;
                        }
                    }

                    User::create([
                        'user_nama' => $nama,
                        'user_username' => $username,
                        'user_email' => $email,
                        'user_password' => Hash::make($password),
                        'user_role' => $role,
                        'desa_id' => $desa_id,
                    ]);

                    $sukses++;
                } catch (\Exception $e) {
                    $gagal++;
                }
            }

            return redirect()->route('user.index')
                ->with('success', "Import selesai: $sukses berhasil, $gagal gagal (cek duplikat username/email, format password 6 digit, atau nama desa tidak valid).");
        } else {
            return back()->with('error', 'Gagal membaca file Excel: ' . \Shuchkin\SimpleXLSX::parseError());
        }
    }
}