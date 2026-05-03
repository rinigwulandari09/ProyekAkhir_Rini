@extends('layouts.dashboard')

@section('title', 'Daftar Keuangan Petani')

@section('content')
<div class="p-2">
    {{-- Header & Summary Cards --}}
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#214122]">Daftar Keuangan Petani</h1>
            <p class="text-sm text-gray-500">Ringkasan aktivitas keuangan seluruh petani sawit yang terdaftar.</p>
        </div>
        
        <div class="flex gap-4">
            {{-- Card Pemasukan --}}
            <div class="bg-white p-3 px-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="bg-green-100 p-2 rounded-lg text-green-600 flex">
                    <iconify-icon icon="mdi:trending-up" class="text-2xl"></iconify-icon>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Total Pemasukan</p>
                    <p class="text-lg font-bold text-gray-800">Rp 145.2M</p>
                </div>
            </div>
            {{-- Card Pengeluaran --}}
            <div class="bg-white p-3 px-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="bg-red-100 p-2 rounded-lg text-red-400 flex">
                    <iconify-icon icon="mdi:trending-down" class="text-2xl"></iconify-icon>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Total Pengeluaran</p>
                    <p class="text-lg font-bold text-gray-800">Rp 82.4M</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Actions Bar --}}
    <div class="bg-white p-4 rounded-t-2xl border border-gray-200 border-b-0 flex flex-wrap justify-between items-center gap-4">
        <div class="flex items-center gap-3">
            <div class="relative">
                <iconify-icon icon="mdi:magnify" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></iconify-icon>
                <input type="text" placeholder="Cari nama petani..." class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-1 focus:ring-green-700 outline-none w-64">
            </div>
            <div class="relative">
                <iconify-icon icon="mdi:calendar-range" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></iconify-icon>
                <input type="text" value="Januari 2024 s/d Desember 2024" class="pl-10 pr-10 py-2 border border-gray-200 rounded-lg text-sm outline-none w-72 bg-gray-50 cursor-default" readonly>
                <iconify-icon icon="mdi:chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"></iconify-icon>
            </div>
            <button class="bg-[#214122] text-white px-6 py-2 rounded-lg text-sm font-bold flex items-center gap-2">
                <iconify-icon icon="mdi:filter-variant"></iconify-icon> Cari
            </button>
        </div>

        <div class="flex items-center gap-2">
            <button class="border border-green-200 text-green-700 px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 hover:bg-green-50">
                <iconify-icon icon="mdi:file-excel-outline" class="text-lg"></iconify-icon> Export ke Excel
            </button>
            <button class="border border-red-200 text-red-600 px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-2 hover:bg-red-50">
                <iconify-icon icon="mdi:file-pdf-box" class="text-lg"></iconify-icon> Export ke PDF
            </button>
        </div>
    </div>

    {{-- Table Keuangan --}}
    <div class="bg-white rounded-b-2xl shadow-sm overflow-hidden border border-gray-200">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-[#D9F99D] text-[#214122] font-bold text-xs uppercase">
                    <th class="p-4 text-center">Nama Petani</th>
                    <th class="p-4 text-center">Pemasukan</th>
                    <th class="p-4 text-center">Pengeluaran</th>
                    <th class="p-4 text-center">Asal Lahan</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-50">
                @php
                $data = [
                    ['inisial' => 'AS', 'nama' => 'Ahmad Subardjo', 'masuk' => 'Rp 12.450.000', 'keluar' => 'Rp 4.200.000', 'lahan' => 'Blok A, Jalan Sejati 1'],
                    ['inisial' => 'DL', 'nama' => 'Deddy Leman', 'masuk' => 'Rp 6.700.000', 'keluar' => 'Rp 3.500.000', 'lahan' => 'Blok A, Jalan Sejati 1'],
                    ['inisial' => 'RK', 'nama' => 'Rahmat Kartolo', 'masuk' => 'Rp 11.100.000', 'keluar' => 'Rp 5.200.000', 'lahan' => 'Blok A, Jalan Sejati 1'],
                    ['inisial' => 'DL', 'nama' => 'Deddy Leman', 'masuk' => 'Rp 6.700.000', 'keluar' => 'Rp 3.500.000', 'lahan' => 'Blok A, Jalan Sejati 1'],
                    ['inisial' => 'RK', 'nama' => 'Rahmat Kartolo', 'masuk' => 'Rp 11.100.000', 'keluar' => 'Rp 5.200.000', 'lahan' => 'Blok A, Jalan Sejati 1'],
                ];
                @endphp

                @foreach($data as $d)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="p-4 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-[10px] font-bold text-blue-400 border border-blue-100">
                            {{ $d['inisial'] }}
                        </div>
                        <span class="font-bold text-gray-700">{{ $d['nama'] }}</span>
                    </td>
                    <td class="p-4 text-center text-green-600 font-bold">{{ $d['masuk'] }}</td>
                    <td class="p-4 text-center text-gray-500 font-medium">{{ $d['keluar'] }}</td>
                    <td class="p-4 text-center text-gray-600 text-xs">{{ $d['lahan'] }}</td>
                    <td class="p-4">
                        <div class="flex justify-center gap-4 text-gray-400">
                            {{-- Link ke Halaman Detail Keuangan --}}
                            <a href="{{ route('keuangan.show', $d['id'] ?? 1) }}" 
                            class="hover:text-blue-600 transition flex items-center" 
                            title="Lihat Detail">
                                <iconify-icon icon="mdi:pencil-outline" class="text-lg"></iconify-icon>
                            </a>

                            {{-- Tombol Hapus --}}
                            <button class="hover:text-red-500 transition flex items-center" title="Hapus">
                                <iconify-icon icon="mdi:trash-can-outline" class="text-lg"></iconify-icon>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        {{-- Footer Table --}}
        <div class="p-4 flex justify-between items-center bg-white border-t border-gray-100">
            <p class="text-xs text-gray-400">Menampilkan 1 - 5 dari 1.240 data petani</p>
            <div class="flex gap-1">
                <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-400"><iconify-icon icon="mdi:chevron-left"></iconify-icon></button>
                <button class="w-8 h-8 flex items-center justify-center rounded bg-[#214122] text-white text-xs font-bold">1</button>
                <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-xs font-bold text-gray-600">2</button>
                <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-xs font-bold text-gray-600">3</button>
                <span class="px-2 text-gray-400">...</span>
                <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-xs font-bold text-gray-600">248</button>
                <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-200 text-gray-400"><iconify-icon icon="mdi:chevron-right"></iconify-icon></button>
            </div>
        </div>
    </div>
</div>
@endsection