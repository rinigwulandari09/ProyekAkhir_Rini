<?php

namespace App\Http\Controllers;

use App\Models\User;
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
}