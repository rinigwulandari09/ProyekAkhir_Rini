@extends('layouts.admin')

@section('content')
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Jumlah Petani --}}
        <div class="bg-[#BBD6F2] p-6 rounded-2xl flex items-center gap-4 shadow-sm border border-blue-200">
            <div class="bg-white/50 p-3 rounded-xl flex items-center justify-center">
                <iconify-icon icon="heroicons:user-group-20-solid" class="text-3xl text-blue-600"></iconify-icon>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-700">Jumlah Petani</p>
                <h3 class="text-2xl font-black text-gray-800">859</h3>
            </div>
        </div>

        {{-- Jumlah Lahan --}}
        <div class="bg-[#C5E8D1] p-6 rounded-2xl flex items-center gap-4 shadow-sm border border-green-200">
            <div class="bg-white/50 p-3 rounded-xl flex items-center justify-center">
                <iconify-icon icon="heroicons:map-20-solid" class="text-3xl text-green-600"></iconify-icon>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-700">Jumlah Lahan</p>
                <h3 class="text-2xl font-black text-gray-800">1040</h3>
            </div>
        </div>

        {{-- Pendapatan --}}
        <div class="bg-[#F2E3B1] p-6 rounded-2xl flex items-center gap-4 shadow-sm border border-yellow-200">
            <div class="bg-white/50 p-3 rounded-xl flex items-center justify-center">
                <iconify-icon icon="heroicons:banknotes-20-solid" class="text-3xl text-yellow-600"></iconify-icon>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-700">Pendapatan Bulan Ini</p>
                <h3 class="text-2xl font-black text-gray-800">859</h3>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <h4 class="text-xs font-bold text-gray-600 mb-4 uppercase">Pemasukan Per Bulan</h4>
            <div class="h-48 bg-gray-50 rounded-xl flex items-end justify-between px-4 pb-2">
                @foreach([30, 60, 40, 45, 20, 30, 50, 80] as $h)
                    <div style="height: {{ $h }}%" class="w-6 bg-[#214122] rounded-t-sm hover:bg-green-700 transition-colors cursor-pointer"></div>
                @endforeach
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex gap-4">
            <div class="flex-1">
                <h4 class="text-xs font-bold text-gray-600 mb-4 uppercase">Pengeluaran Per Kategori</h4>
                <div class="space-y-3">
                    <div class="flex justify-between text-[10px]"><span class="text-blue-500 font-bold">Pupuk :</span> <span class="text-gray-600 font-semibold">Rp. 150.000.000</span></div>
                    <div class="flex justify-between text-[10px]"><span class="text-blue-700 font-bold">Pestisida :</span> <span class="text-gray-600 font-semibold">Rp. 90.000.000</span></div>
                    <div class="flex justify-between text-[10px]"><span class="text-cyan-500 font-bold">Tenaga Kerja :</span> <span class="text-gray-600 font-semibold">Rp. 120.000.000</span></div>
                </div>
            </div>
            {{-- Donut Chart Placeholder --}}
            <div class="w-32 h-32 rounded-full border-12 border-blue-500 border-r-blue-700 border-b-cyan-400 rotate-45"></div>
        </div>
    </div>

    {{-- Daftar Tugas Aktif --}}
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mt-6">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-xs font-bold text-gray-600 uppercase tracking-wider">Daftar Tugas Aktif :</h4>
            <a href="#" class="text-[10px] text-green-700 font-bold underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="text-gray-400 text-[10px] uppercase border-b border-gray-50">
                    <tr>
                        <th class="py-3 px-2">No</th>
                        <th class="py-3">Tugas</th>
                        <th class="py-3">Batas Akhir</th>
                        <th class="py-3">Status</th>
                        <th class="py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-[10px] font-medium text-gray-700">
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-2">01</td>
                        <td class="py-4 font-bold text-gray-800">Audit Petani</td>
                        <td class="py-4 text-gray-500">12/03/2026</td>
                        <td class="py-4">
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[9px] font-bold inline-flex items-center gap-1">
                                <span class="w-1 h-1 bg-green-700 rounded-full"></span> sedang berjalan
                            </span>
                        </td>
                        <td class="py-4 text-center">
                            <button class="text-gray-400 hover:text-[#214122] transition-colors">
                                <iconify-icon icon="heroicons:pencil-square-20-solid" class="text-xl"></iconify-icon>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection