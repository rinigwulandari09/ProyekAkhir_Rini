@extends('layouts.dashboard')

@section('title', 'Tambah User')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-gray-100">
        <div class="bg-[#214122] p-6 px-8 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <x-heroicon-o-user-plus class="w-6 h-6 text-white" />
                <h2 class="text-xl font-bold text-white">Tambah Pengguna Baru</h2>
            </div>
            <span class="text-green-200 text-xs font-mono bg-green-900/50 px-3 py-1 rounded-full uppercase tracking-tighter">Baru</span>
        </div>

        @if ($errors->any())
            <div class="m-6 p-4 bg-red-50 border-none rounded-2xl text-red-700 text-sm italic shadow-sm">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.store') }}" method="POST" class="p-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Nama Lengkap</label>
                    <input 
                        type="text" 
                        name="user_nama" 
                        value="{{ old('user_nama') }}" 
                        placeholder="Masukkan nama lengkap"
                        class="w-full px-5 py-3 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-green-700 transition"
                        required
                    >
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Username</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="user_username" 
                            value="{{ old('user_username') }}" 
                            placeholder="Contoh: admin01"
                            class="w-full px-5 py-3 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-green-700 transition"
                            required
                        >
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Email</label>
                    <input 
                        type="email" 
                        name="user_email" 
                        value="{{ old('user_email') }}" 
                        placeholder="alamat_email@domain.com"
                        class="w-full px-5 py-3 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-green-700 transition"
                        required
                    >
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Desa Tugas</label>
                    <input 
                        type="text" 
                        name="user_desa" 
                        value="{{ old('user_desa') }}" 
                        placeholder="Masukkan nama desa wilayah tugas"
                        class="w-full px-5 py-3 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-green-700 transition"
                    >
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Role Akses</label>
                    <div class="relative">
                        <select name="user_role" class="w-full px-5 py-3 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-green-700 appearance-none cursor-pointer" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin" {{ old('user_role') == 'admin' ? 'selected' : '' }}> Admin (Petugas)</option>
                            <option value="super_admin" {{ old('user_role') == 'super_admin' ? 'selected' : '' }}> Super Admin (Pemilik)</option>
                        </select>
                        <x-heroicon-o-chevron-down class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5 pointer-events-none" />
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Password</label>
                    <input 
                        type="password" 
                        name="user_password" 
                        placeholder="Masukkan password akun"
                        class="w-full px-5 py-3 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-green-700 transition"
                        required
                    >
                </div>

            </div>

            <div class="mt-12 flex items-center justify-between border-t border-gray-50 pt-8">
                <a href="{{ route('user.index') }}" class="text-gray-400 hover:text-gray-600 font-bold flex items-center gap-2 transition">
                    <x-heroicon-o-arrow-left class="w-5 h-5" />
                    Kembali
                </a>
                <button type="submit" class="bg-[#214122] text-white px-10 py-3 rounded-2xl font-bold hover:bg-green-900 transition shadow-lg shadow-green-900/20 flex items-center gap-2 active:scale-95">
                    <x-heroicon-o-check-circle class="w-5 h-5" />
                    Simpan User Baru
                </button>
            </div>
        </form>
    </div>
</div>
@endsection