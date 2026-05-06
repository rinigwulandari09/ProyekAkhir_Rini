@extends('layouts.dashboard')

@section('title', 'Edit User')

@section('header', 'Dashboard Admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
        {{-- Header Card --}}
        <div class="bg-[#214122] p-4 px-8 flex items-center gap-3">
            <x-heroicon-o-pencil-square class="w-6 h-6 text-white" />
            <h2 class="text-xl font-bold text-white text-center md:text-left">Edit User</h2>
        </div>

        {{-- Form --}}
        <form action="#" method="POST" class="p-8">
            @csrf
            @method('PUT') {{-- Standar Laravel untuk Update Data --}}
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                
                {{-- Nama --}}
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Nama</label>
                    <input 
                        type="text" 
                        name="name" 
                        value="Bayu Winandar" {{-- Nanti diganti {{ $user->name }} --}}
                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-700 transition"
                    >
                </div>

                {{-- Email --}}
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        value="bayu@gmail.com" {{-- Nanti diganti {{ $user->email }} --}}
                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-700 transition"
                    >
                </div>

                {{-- Password --}}
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        placeholder="Ovdusk"
                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-700 transition text-gray-400"
                    >
                </div>

                {{-- Role --}}
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Role</label>
                    <div class="relative">
                        <select name="role" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-700 appearance-none cursor-pointer">
                            <option value="Admin" selected>Admin</option>
                            <option value="Super Admin">Super Admin</option>
                        </select>
                        <x-heroicon-o-chevron-down class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 w-5 h-5 pointer-events-none" />
                    </div>
                </div>

                {{-- Desa --}}
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Desa</label>
                    <div class="relative">
                        <select name="desa" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-700 appearance-none cursor-pointer">
                            <option value="Sekijang" selected>Sekijang</option>
                            <option value="Langgam">Langgam</option>
                        </select>
                        <x-heroicon-o-chevron-down class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 w-5 h-5 pointer-events-none" />
                    </div>
                </div>

                {{-- Tugas --}}
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Tugas</label>
                    <div class="relative">
                        <select name="tugas" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-700 appearance-none cursor-pointer">
                            <option value="" disabled selected>Silahkan Pilih Tugas</option>
                            <option value="Verifikasi">Verifikasi Petani</option>
                            <option value="Audit">Audit Lahan</option>
                        </select>
                        <x-heroicon-o-chevron-down class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 w-5 h-5 pointer-events-none" />
                    </div>
                </div>

            </div>

            {{-- Button Simpan --}}
            <div class="mt-12 flex justify-end">
                <button type="submit" class="bg-[#214122] text-white px-12 py-3 rounded-xl font-bold hover:bg-green-900 transition shadow-lg flex items-center gap-2">
                    <x-heroicon-o-arrow-path class="w-5 h-5" />
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection