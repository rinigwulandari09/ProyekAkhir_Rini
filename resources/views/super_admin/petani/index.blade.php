@extends('layouts.dashboard')

@section('title', 'Daftar Petani')

@section('header', 'Dashboard Admin')

@section('content')
<div class="p-2">
    {{-- Header Section --}}
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#214122]">Daftar Petani</h1>
            <p class="text-sm text-gray-500">Daftar seluruh petani sawit yang terdaftar.</p>
        </div>
        <a href="#" class="bg-[#214122] text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-green-900 transition shadow-sm font-semibold text-sm">
            <iconify-icon icon="mdi:plus" class="text-xl"></iconify-icon>
            Tambah Petani
        </a>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
        {{-- Search Bar --}}
        <div class="p-4 flex justify-end">
            <div class="relative w-64">
                <iconify-icon icon="mdi:magnify" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></iconify-icon>
                <input 
                    type="text" 
                    placeholder="Cari nama petani..." 
                    class="w-full pl-10 pr-4 py-1.5 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-green-700 text-xs"
                >
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#D9F99D] border-b border-gray-200">
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center">ID</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">Nama</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">Email</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center">Status</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center">Luas Lahan</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center">Produksi</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center">Pengeluaran</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                    $petani = [
                        ['id' => 'PT001', 'nama' => 'Bayu Winandar', 'email' => 'bayu12@gmail.com', 'status' => 'Aktif', 'luas' => '50 ha', 'produksi' => '120 ton', 'pengeluaran' => 'Rp 4.200.000'],
                        ['id' => 'PT002', 'nama' => 'Siti Lestari', 'email' => 'siti12@gmail.com', 'status' => 'Pending', 'luas' => '50 ha', 'produksi' => '120 ton', 'pengeluaran' => 'Rp 4.200.000'],
                        ['id' => 'PT003', 'nama' => 'Indah Wanita', 'email' => 'indah12@gmail.com', 'status' => 'Aktif', 'luas' => '50 ha', 'produksi' => '120 ton', 'pengeluaran' => 'Rp 4.200.000'],
                        ['id' => 'PT004', 'nama' => 'Raya Puspita', 'email' => 'raya12@gmail.com', 'status' => 'Aktif', 'luas' => '50 ha', 'produksi' => '120 ton', 'pengeluaran' => 'Rp 4.200.000'],
                        ['id' => 'PT005', 'nama' => 'Rina Permata Sari', 'email' => 'rina12@gmail.com', 'status' => 'Aktif', 'luas' => '50 ha', 'produksi' => '120 ton', 'pengeluaran' => 'Rp 4.200.000'],
                        ['id' => 'PT006', 'nama' => 'Ratna Rahmawati', 'email' => 'ratna12@gmail.com', 'status' => 'Aktif', 'luas' => '50 ha', 'produksi' => '120 ton', 'pengeluaran' => 'Rp 4.200.000'],
                        ['id' => 'PT007', 'nama' => 'Willy Salam', 'email' => 'willy12@gmail.com', 'status' => 'Aktif', 'luas' => '50 ha', 'produksi' => '120 ton', 'pengeluaran' => 'Rp 4.200.000'],
                    ];
                    @endphp

                    @foreach($petani as $p)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-xs text-gray-500 text-center font-mono">{{ $p['id'] }}</td>
                        <td class="p-4 text-xs text-gray-800 font-medium italic">{{ $p['nama'] }}</td>
                        <td class="p-4 text-xs text-gray-500 italic">{{ $p['email'] }}</td>
                        <td class="p-4 text-center">
                            @if($p['status'] == 'Aktif')
                                <span class="bg-[#DCFCE7] text-[#166534] px-3 py-1 rounded-full text-[10px] font-bold">Aktif</span>
                            @else
                                <span class="bg-[#FEF3C7] text-[#92400E] px-3 py-1 rounded-full text-[10px] font-bold">Pending</span>
                            @endif
                        </td>
                        <td class="p-4 text-xs text-gray-600 text-center">{{ $p['luas'] }}</td>
                        <td class="p-4 text-xs text-gray-600 text-center">{{ $p['produksi'] }}</td>
                        <td class="p-4 text-xs text-gray-600 text-center">{{ $p['pengeluaran'] }}</td>
                        <td class="p-4">
                            <div class="flex justify-center gap-3">
                                <a href="{{ route('petani.show', $p['id']) }}" class="text-green-700 hover:scale-110 transition">
                                    <iconify-icon icon="mdi:square-edit-outline" class="text-xl"></iconify-icon>
                                </a>
                                <button class="text-red-500 hover:scale-110 transition">
                                    <iconify-icon icon="mdi:trash-can-outline" class="text-xl"></iconify-icon>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 flex justify-end border-t border-gray-100">
            <nav class="inline-flex gap-1">
                <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-400 hover:bg-gray-50">
                    <iconify-icon icon="mdi:chevron-left"></iconify-icon>
                </button>
                <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 bg-gray-200 text-gray-700 font-bold text-xs">1</button>
                <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 text-xs">2</button>
                <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 text-xs">3</button>
                <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 text-xs">4</button>
                <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-400 hover:bg-gray-50">
                    <iconify-icon icon="mdi:chevron-right"></iconify-icon>
                </button>
            </nav>
        </div>
    </div>
</div>
@endsection