@extends('layouts.dashboard')

@section('title', 'Edit User')

@section('header', 'Dashboard Admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        
        <div class="border-b border-slate-100 p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-[#214122] text-white rounded-lg">
                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Perbarui Data User</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi data diri dan hak akses pengguna sistem.</p>
                </div>
            </div>
            <span class="self-start sm:self-center text-xs font-mono font-medium bg-slate-100 text-slate-700 px-3 py-1 rounded-md border border-slate-200">
                UID: {{ $user->user_id }}
            </span>
        </div>

        <form action="{{ route('user.update', $user->user_id) }}" method="POST" class="p-5 sm:p-6 space-y-6">
            @csrf
            @method('PUT') 
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input 
                        type="text" 
                        name="user_nama" 
                        value="{{ old('user_nama', $user->user_nama) }}" 
                        class="w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#214122] focus:ring-4 focus:ring-[#214122]/10 transition"
                        placeholder="Nama lengkap user"
                        required
                    >
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700">Username <span class="text-rose-500">*</span></label>
                    <input 
                        type="text" 
                        name="user_username" 
                        value="{{ old('user_username', $user->user_username) }}" 
                        class="w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#214122] focus:ring-4 focus:ring-[#214122]/10 transition"
                        placeholder="username_baru"
                        required
                    >
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700">Email <span class="text-rose-500">*</span></label>
                    <input 
                        type="email" 
                        name="user_email" 
                        value="{{ old('user_email', $user->user_email) }}" 
                        class="w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#214122] focus:ring-4 focus:ring-[#214122]/10 transition"
                        placeholder="alamat_email@domain.com"
                        required
                    >
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700">Desa Tugas</label>
                    <div class="relative">
                        <select
                            name="desa_id"
                            class="w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-[#214122] focus:ring-4 focus:ring-[#214122]/10 appearance-none cursor-pointer"
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
                        <x-heroicon-o-chevron-down class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4 pointer-events-none" />
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700">Role Akses <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <select name="user_role" class="w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-[#214122] focus:ring-4 focus:ring-[#214122]/10 appearance-none cursor-pointer" required>
                            <option value="admin" {{ $user->user_role == 'admin' ? 'selected' : '' }}>Admin (Petugas)</option>
                            <option value="super_admin" {{ $user->user_role == 'super_admin' ? 'selected' : '' }}>Super Admin (Pemilik)</option>
                        </select>
                        <x-heroicon-o-chevron-down class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4 pointer-events-none" />
                    </div>
                </div>

                <div class="space-y-1.5">
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <label class="block text-sm font-medium text-slate-700">Ganti Password</label>
                        <span class="text-[11px] font-normal bg-amber-50 text-amber-800 px-2 py-0.5 rounded border border-amber-200/60 italic">
                            Kosongkan jika tidak diubah 
                        </span>
                    </div>
                    <input 
                        type="password" 
                        name="user_password" 
                        placeholder="6 angka password baru"
                        class="w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#214122] focus:ring-4 focus:ring-[#214122]/10 transition"
                        inputmode="numeric" 
                        pattern="[0-9]{6}" 
                        maxlength="6" 
                        minlength="6"
                        title="Password harus berupa 6 angka"
                    >
                </div>

            </div>

            <div class="mt-8 flex flex-col-reverse sm:flex-row items-center justify-between gap-3 border-t border-slate-100 pt-5">
                <a href="{{ route('user.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition">
                    <x-heroicon-o-arrow-left class="w-4 h-4" />
                    Kembali
                </a>
                
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#214122] text-white px-5 py-2 text-sm font-medium rounded-lg hover:bg-[#214122] focus:ring-4 focus:ring-[#214122]/20 active:scale-[0.98] transition shadow-sm">
                    <x-heroicon-o-arrow-path class="w-4 h-4" />
                    Perbarui Data User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection