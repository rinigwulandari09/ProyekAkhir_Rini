@extends('layouts.dashboard')

@section('title', 'Edit User')

@section('header', 'Dashboard Admin')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-4xl shadow-xl shadow-gray-100/50 overflow-hidden border border-gray-50">
        <div class="bg-[#214122] p-6 px-10 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <x-heroicon-o-pencil-square class="w-6 h-6 text-white" />
                <h2 class="text-xl font-bold text-white">Perbarui Data User</h2>
            </div>
            <div class="bg-green-900/30 px-4 py-1 rounded-full border border-green-700/50">
                <span class="text-green-100 text-xs font-mono font-bold tracking-widest">UID: {{ $user->user_id }}</span>
            </div>
        </div>

        <form action="{{ route('user.update', $user->user_id) }}" method="POST" class="p-8">
            @csrf
            @method('PUT') 
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <div class="space-y-2">
                    <label class="block text-sm font-black text-gray-700 uppercase tracking-wider">Nama Lengkap</label>
                    <input 
                        type="text" 
                        name="user_nama" 
                        value="{{ old('user_nama', $user->user_nama) }}" 
                        class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-green-800 transition shadow-inner"
                        placeholder="Nama lengkap user"
                        required
                    >
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-black text-gray-700 uppercase tracking-wider">Username</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 font-bold">@</span>
                        <input 
                            type="text" 
                            name="user_username" 
                            value="{{ old('user_username', $user->user_username) }}" 
                            class="w-full pl-10 pr-5 py-4 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-green-800 transition shadow-inner"
                            placeholder="username_baru"
                            required
                        >
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-black text-gray-700 uppercase tracking-wider">Email</label>
                    <input 
                        type="email" 
                        name="user_email" 
                        value="{{ old('user_email', $user->user_email) }}" 
                        class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-green-800 transition shadow-inner"
                        placeholder="alamat_email@domain.com"
                        required
                    >
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-black text-gray-700 uppercase tracking-wider">
                        Desa Tugas
                    </label>

                    <select
                        name="desa_id"
                        class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-green-800"
                    >
                        <option value="">-- Pilih Desa --</option>

                        @foreach($desas as $desa)
                            <option
                                value="{{ $desa->desa_id }}"
                                {{ old('desa_id', $user->desa_id) == $desa->desa_id ? 'selected' : '' }}
                            >
                                {{ $desa->desa_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-black text-gray-700 uppercase tracking-wider">Role Akses</label>
                    <div class="relative">
                        <select name="user_role" class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-green-800 appearance-none cursor-pointer shadow-inner">
                            <option value="admin" {{ $user->user_role == 'admin' ? 'selected' : '' }}>Admin (Petugas)</option>
                            <option value="super_admin" {{ $user->user_role == 'super_admin' ? 'selected' : '' }}>Super Admin (Pemilik)</option>
                        </select>
                        <x-heroicon-o-chevron-down class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5 pointer-events-none" />
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-black text-gray-700 uppercase tracking-wider">
                        Ganti Password 
                        <span class="normal-case text-[10px] bg-amber-100 text-amber-700 px-2 py-0.5 rounded-lg ml-2 italic font-bold">Kosongkan jika tetap</span>
                    </label>
                    <input 
                        type="password" 
                        name="user_password" 
                        placeholder="••••••••"
                        class="w-full px-5 py-4 bg-gray-50 border-0 rounded-2xl focus:ring-2 focus:ring-green-800 transition shadow-inner"
                    >
                </div>

            </div>

            <div class="mt-12 flex items-center justify-between border-t border-gray-50 pt-8">
                <a href="{{ route('user.index') }}" class="text-gray-400 hover:text-gray-800 font-bold flex items-center gap-2 transition group">
                    <div class="p-2 rounded-xl group-hover:bg-gray-100 transition">
                        <x-heroicon-o-arrow-left class="w-5 h-5" />
                    </div>
                    Kembali
                </a>
                
                <button type="submit" class="bg-[#214122] text-white px-10 py-3 rounded-2xl font-bold hover:bg-green-900 transition shadow-lg shadow-green-900/20 flex items-center gap-2 active:scale-95">
                    <x-heroicon-o-arrow-path class="w-5 h-5" />
                    Perbarui Data User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection