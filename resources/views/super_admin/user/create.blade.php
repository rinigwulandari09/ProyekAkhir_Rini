@extends('layouts.dashboard')

@section('title', 'Tambah User')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
        <div class="bg-[#214122] p-4 px-6">
            <h2 class="text-xl font-bold text-white">Tambah User</h2>
        </div>

        <form action="#" method="POST" class="p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Nama</label>
                    <input 
                        type="text" 
                        name="name" 
                        placeholder="Masukkan nama"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-700 focus:bg-white transition shadow-sm"
                    >
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        placeholder="Masukkan email"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-700 focus:bg-white transition shadow-sm"
                    >
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Password</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password" 
                            placeholder="Masukkan password"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-700 focus:bg-white transition shadow-sm"
                        >
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Role</label>
                    <div class="relative">
                        <select name="role" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-700 appearance-none cursor-pointer shadow-sm">
                            <option value="Admin">Admin</option>
                            <option value="Super Admin">Super Admin</option>
                        </select>
                        <iconify-icon icon="mdi:chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xl"></iconify-icon>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700">Desa</label>
                    <div class="relative">
                        <select name="desa" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-700 appearance-none cursor-pointer shadow-sm">
                            <option value="Sekijang">Sekijang</option>
                            <option value="Langgam">Langgam</option>
                            <option value="Pangkalan Kerinci">Pangkalan Kerinci</option>
                        </select>
                        <iconify-icon icon="mdi:chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xl"></iconify-icon>
                    </div>
                </div>

            </div>

            <div class="mt-12 flex justify-end">
                <button type="submit" class="bg-[#214122] text-white px-10 py-3 rounded-xl font-bold hover:bg-green-900 transition shadow-lg transform hover:scale-105 active:scale-95">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection