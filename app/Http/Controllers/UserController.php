<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Tampilkan Semua User dari Supabase
    public function index()
    {
        // Mengambil semua data dari tabel users
        $users = User::all();
        return view('super_admin.user.index', compact('users'));
    }

    // Form Tambah User
    public function create()
    {
        return view('super_admin.user.create');
    }

    // Proses Simpan User Baru
    public function store(Request $request)
    {
        $request->validate([
            'user_nama' => 'required|string|max:255',
            'user_username' => 'required|string|unique:users,user_username',
            'user_password' => 'required|min:6',
            'user_role' => 'required|in:super_admin,admin',
        ], [
            'user_username.unique' => 'Username ini sudah terdaftar di Supabase!',
            'user_password.min' => 'Password minimal harus 6 karakter.'
        ]);

        User::create([
            'user_nama' => $request->user_nama,
            'user_username' => $request->user_username,
            'user_password' => Hash::make($request->user_password),
            'user_role' => $request->user_role,
        ]);

        return redirect()->route('user.index')->with('success', 'User ' . $request->user_nama . ' berhasil ditambahkan!');
    }

    // Form Edit User
    public function edit($id)
    {
        // Mencari berdasarkan user_id (primary key)
        $user = User::where('user_id', $id)->firstOrFail();
        return view('super_admin.user.edit', compact('user'));
    }

    // Proses Update User
    public function update(Request $request, $id)
    {
        $user = User::where('user_id', $id)->firstOrFail();

        $request->validate([
            'user_nama' => 'required|string|max:255',
            // Pengecualian unique agar bisa update data sendiri
            'user_username' => 'required|string|unique:users,user_username,'.$id.',user_id',
            'user_role' => 'required',
        ]);

        $data = [
            'user_nama' => $request->user_nama,
            'user_username' => $request->user_username,
            'user_role' => $request->user_role,
        ];

        // Hanya ganti password jika diisi di form edit
        if ($request->filled('user_password')) {
            $data['user_password'] = Hash::make($request->user_password);
        }

        $user->update($data);

        return redirect()->route('user.index')->with('success', 'Data user di Supabase berhasil diperbarui.');
    }

    // Proses Hapus User
    public function destroy($id)
    {
        $user = User::where('user_id', $id)->firstOrFail();
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus dari Supabase.');
    }
}