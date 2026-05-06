@extends('layouts.dashboard')

@section('title', 'Detail Keuangan Petani')

@section('content')
<div class="p-2">
    {{-- Breadcrumb & Title --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#214122]">Daftar Keuangan Petani</h1>
        <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
            Data Keuangan 
            <x-heroicon-o-chevron-right class="w-3 h-3" /> 
            <span class="text-gray-600 font-medium">Detail</span>
        </p>
    </div>

    {{-- Main Container Card --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-xl font-bold text-gray-800">Nama Petani : <span class="font-medium">Bayu Winandar</span></h2>
            <button class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-xs font-bold flex items-center gap-2 hover:bg-gray-50 transition">
                <x-heroicon-o-arrow-up-tray class="w-5 h-5" /> Export Laporan
            </button>
        </div>

        {{-- Tabel Pemasukan Section --}}
        <div class="mb-10 border border-gray-100 rounded-xl p-4 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-700">Tabel Pemasukan</h3>
                <div class="relative">
                    <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                    <input type="text" placeholder="Search" class="pl-10 pr-4 py-1.5 border border-gray-200 rounded-full text-xs outline-none w-64 focus:ring-1 focus:ring-green-600">
                </div>
            </div>
            <div class="overflow-hidden rounded-lg border border-gray-100">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-[#D9F99D] text-[#214122] text-xs font-bold">
                            <th class="p-3 text-center">Tanggal</th>
                            <th class="p-3 text-center">Asal Lahan</th>
                            <th class="p-3 text-center">Hasil Panen</th>
                            <th class="p-3 text-center">Total</th>
                            <th class="p-3 text-center">Bukti Nota</th>
                            <th class="p-3 text-center">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-50">
                        @for ($i = 0; $i < 4; $i++)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-3 text-center text-gray-600">12 Okt 2023</td>
                            <td class="p-3 text-center text-gray-600">Blok A - Lahan Gambut</td>
                            <td class="p-3 text-center text-gray-600">2.5 Ton</td>
                            <td class="p-3 text-center text-gray-600 font-medium">Rp 5.000.000</td>
                            <td class="p-3 text-center">
                                <button class="bg-[#214122] text-white px-3 py-1 rounded-md flex items-center gap-1 mx-auto text-[10px] hover:bg-[#3D5A3E] transition">
                                    <x-heroicon-o-eye class="w-3.5 h-3.5" /> Lihat
                                </button>
                            </td>
                            <td class="p-3 text-center text-gray-400">Panen Raya</td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel Pengeluaran Section --}}
        <div class="border border-gray-100 rounded-xl p-4 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-700">Tabel Pengeluaran</h3>
                <div class="relative">
                    <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                    <input type="text" placeholder="Search" class="pl-10 pr-4 py-1.5 border border-gray-200 rounded-full text-xs outline-none w-64 focus:ring-1 focus:ring-green-600">
                </div>
            </div>
            <div class="overflow-hidden rounded-lg border border-gray-100">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-[#D9F99D] text-[#214122] text-xs font-bold">
                            <th class="p-3 text-center">Tanggal</th>
                            <th class="p-3 text-center">Asal Lahan</th>
                            <th class="p-3 text-center">Jumlah</th>
                            <th class="p-3 text-center">Jenis</th>
                            <th class="p-3 text-center">Bukti Nota</th>
                            <th class="p-3 text-center">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-50">
                        @for ($i = 0; $i < 3; $i++)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-3 text-center text-gray-600">15 Okt 2023</td>
                            <td class="p-3 text-center text-gray-600">Blok A - Lahan Gambut</td>
                            <td class="p-3 text-center text-gray-600 font-medium">Rp 1.500.000</td>
                            <td class="p-3 text-center">
                                <span class="bg-gray-100 px-3 py-0.5 rounded text-gray-400 border border-gray-200">Pupuk</span>
                            </td>
                            <td class="p-3 text-center">
                                <button class="bg-[#214122] text-white px-3 py-1 rounded-md flex items-center gap-1 mx-auto text-[10px] hover:bg-[#3D5A3E] transition">
                                    <x-heroicon-o-eye class="w-3.5 h-3.5" /> Lihat
                                </button>
                            </td>
                            <td class="p-3 text-center text-gray-400">Pupuk Urea 5 Karung</td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer Table Info --}}
        <div class="mt-6 flex justify-between items-center text-xs">
            <p class="text-gray-400 italic">Showing 1 to 3 of 8 entries</p>
            <div class="flex gap-1">
                <button class="w-6 h-6 flex items-center justify-center rounded border border-gray-200 text-gray-400 hover:bg-gray-50 transition">
                    <x-heroicon-o-chevron-left class="w-3.5 h-3.5" />
                </button>
                <button class="w-6 h-6 flex items-center justify-center rounded bg-[#214122] text-white font-bold">1</button>
                <button class="w-6 h-6 flex items-center justify-center rounded border border-gray-200 text-gray-600 font-bold hover:bg-gray-50 transition">2</button>
                <button class="w-6 h-6 flex items-center justify-center rounded border border-gray-200 text-gray-400 hover:bg-gray-50 transition">
                    <x-heroicon-o-chevron-right class="w-3.5 h-3.5" />
                </button>
            </div>
        </div>
    </div>
</div>
@endsection