@extends('layouts.dashboard')

@section('title', 'Daftar User')

@section('content')
<div class="p-2">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Daftar User</h1>

    <div class="flex justify-between items-center mb-4">
       <a href="{{ route('user.create') }}" class="bg-[#214122] text-white px-4 py-2 rounded-lg inline-flex items-center gap-2 hover:bg-green-900 transition shadow-sm font-semibold text-sm">
            <x-heroicon-o-user-plus class="w-5 h-5" />
            Tambah User
        </a>

        <div class="relative w-64">
            <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5" />
            <input 
                type="text" 
                placeholder="Cari user..." 
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-700 text-sm"
            >
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-yellow-50 border-b border-yellow-100">
                    <th class="p-4 text-sm font-bold text-gray-600 uppercase tracking-wider">ID</th>
                    <th class="p-4 text-sm font-bold text-gray-600 uppercase tracking-wider">Nama</th>
                    <th class="p-4 text-sm font-bold text-gray-600 uppercase tracking-wider">Email</th>
                    <th class="p-4 text-sm font-bold text-gray-600 uppercase tracking-wider">Role</th>
                    <th class="p-4 text-sm font-bold text-gray-600 uppercase tracking-wider">Desa</th>
                    <th class="p-4 text-sm font-bold text-gray-600 uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @php
                $users = [
                    ['id' => 'US001', 'nama' => 'Bayu Mirandar', 'email' => 'bayu12@gmail.com', 'role' => 'Admin', 'desa' => 'Sekijang'],
                    ['id' => 'US002', 'nama' => 'Siti Lestari', 'email' => 'siti12@gmail.com', 'role' => 'Admin', 'desa' => 'Sekijang'],
                    ['id' => 'US003', 'nama' => 'Indah Vitonita', 'email' => 'indah12@gmail.com', 'role' => 'Admin', 'desa' => 'Sekijang'],
                    ['id' => 'US004', 'nama' => 'Raya Puspita', 'email' => 'raya12@gmail.com', 'role' => 'Admin', 'desa' => 'Sekijang'],
                    ['id' => 'US005', 'nama' => 'Rina Permata Sari', 'email' => 'rina12@gmail.com', 'role' => 'Admin', 'desa' => 'Sekijang'],
                    ['id' => 'US006', 'nama' => 'Ratna Rahmawati', 'email' => 'ratna12@gmail.com', 'role' => 'Admin', 'desa' => 'Sekijang'],
                    ['id' => 'US007', 'nama' => 'Willy Salam', 'email' => 'willy12@gmail.com', 'role' => 'Admin', 'desa' => 'Sekijang'],
                ];
                @endphp

                @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 text-sm text-gray-600 font-medium">{{ $user['id'] }}</td>
                    <td class="p-4 text-sm text-gray-800 font-semibold">{{ $user['nama'] }}</td>
                    <td class="p-4 text-sm text-gray-600 italic">{{ $user['email'] }}</td>
                    <td class="p-4 text-sm text-gray-600">{{ $user['role'] }}</td>
                    <td class="p-4 text-sm text-gray-600">{{ $user['desa'] }}</td>
                    <td class="p-4">
                        <div class="flex justify-center gap-2">
                            <button class="w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition shadow-sm">
                                <x-heroicon-o-eye class="w-5 h-5" />
                            </button>
                            <a href="{{ route('user.edit', 1) }}" class="w-8 h-8 flex items-center justify-center bg-green-100 text-green-700 rounded-lg hover:bg-green-700 hover:text-white transition shadow-sm">
                                <x-heroicon-o-pencil-square class="w-5 h-5" />
                            </a>
                            <button class="w-8 h-8 flex items-center justify-center bg-red-100 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition shadow-sm">
                                <x-heroicon-o-trash class="w-5 h-5" />
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="flex justify-end mt-6">
        <nav class="inline-flex shadow-sm rounded-md overflow-hidden border border-gray-300">
            <button class="px-3 py-2 bg-gray-100 text-gray-500 hover:bg-gray-200 border-r transition">
                <x-heroicon-o-chevron-left class="w-4 h-4" />
            </button>
            <button class="px-4 py-2 bg-gray-300 text-gray-700 font-bold border-r text-sm">1</button>
            <button class="px-4 py-2 bg-white text-gray-600 hover:bg-gray-100 border-r transition text-sm">2</button>
            <button class="px-4 py-2 bg-white text-gray-600 hover:bg-gray-100 border-r transition text-sm">3</button>
            <button class="px-3 py-2 bg-white text-gray-600 hover:bg-gray-100 transition">
                <x-heroicon-o-chevron-right class="w-4 h-4" />
            </button>
        </nav>
    </div>
</div>
@endsection