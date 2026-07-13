@extends('layouts.dashboard')

@section('title', 'Tambah User')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        
        <div class="border-b border-slate-100 p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-[#214122] text-white rounded-lg">
                    <x-heroicon-o-user-plus class="w-5 h-5" />
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Tambah Pengguna Baru</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Daftarkan akun petugas atau super admin baru ke dalam sistem.</p>
                </div>
            </div>
            <span class="self-start sm:self-center text-[10px] font-semibold bg-[#214122] text-white px-2.5 py-1 rounded-md uppercase tracking-wider border border-[#214122]/60">
                Baru
            </span>
        </div>

        @if ($errors->any())
            <div class="m-5 sm:m-6 p-4 bg-rose-50 border border-rose-100 rounded-xl text-rose-800 text-sm shadow-sm">
                <div class="flex items-center gap-2 mb-2 font-semibold text-rose-900">
                    <x-heroicon-o-x-circle class="w-5 h-5 text-rose-600" />
                    <span>Periksa kembali inputan Anda:</span>
                </div>
                <ul class="list-disc ml-5 space-y-1 text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.store') }}" method="POST" class="p-5 sm:p-6 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input 
                        type="text" 
                        name="user_nama" 
                        value="{{ old('user_nama') }}" 
                        placeholder="Masukkan nama lengkap"
                        class="w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#214122] focus:ring-4 focus:ring-[#214122]/10 transition"
                        required
                    >
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700">Username <span class="text-rose-500">*</span></label>
                    <input 
                        type="text" 
                        name="user_username" 
                        value="{{ old('user_username') }}" 
                        placeholder="Contoh: admin01"
                        class="w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#214122] focus:ring-4 focus:ring-[#214122]/10 transition"
                        required
                    >
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700">Email <span class="text-rose-500">*</span></label>
                    <input 
                        type="email" 
                        name="user_email" 
                        value="{{ old('user_email') }}" 
                        placeholder="alamat_email@domain.com"
                        class="w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#214122] focus:ring-4 focus:ring-[#214122]/10 transition"
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
                                <option value="{{ $desa->desa_id }}" {{ old('desa_id') == $desa->desa_id ? 'selected' : '' }}>
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
                            <option value="">-- Pilih Role --</option>
                            <option value="admin" {{ old('user_role') == 'admin' ? 'selected' : '' }}> Admin (Petugas)</option>
                            <option value="super_admin" {{ old('user_role') == 'super_admin' ? 'selected' : '' }}> Super Admin (Pemilik)</option>
                        </select>
                        <x-heroicon-o-chevron-down class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4 pointer-events-none" />
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700">Password <span class="text-rose-500">*</span></label>
                    <input 
                        type="password" 
                        name="user_password" 
                        placeholder="Masukkan password akun"
                        class="w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#214122] focus:ring-4 focus:ring-[#214122]/10 transition"
                        required
                    >
                </div>

            </div>

            <div class="mt-8 flex flex-col-reverse sm:flex-row items-center justify-between gap-3 border-t border-slate-100 pt-5">
                <a href="{{ route('user.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition">
                    <x-heroicon-o-arrow-left class="w-4 h-4" />
                    Kembali
                </a>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#214122]  text-white px-5 py-2 text-sm font-medium rounded-lg hover:bg-[#214122] focus:ring-4 focus:ring-[#214122]/20 active:scale-[0.98] transition shadow-sm">
                    <x-heroicon-o-check-circle class="w-4 h-4" />
                    Simpan User Baru
                </button>
            </div>
        </form>
    </div>
</div>
@endsection