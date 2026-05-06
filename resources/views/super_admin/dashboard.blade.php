@extends('layouts.dashboard')

@section('title', 'Beranda')

@section('content')
<div class="space-y-6">
    
    {{-- Statistik Utama --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#A0C4E8] p-6 rounded-xl flex items-center justify-between shadow-sm border border-black/5">
            <div>
                <p class="text-blue-900 font-bold text-sm">Jumlah Petani</p>
                <h3 class="text-3xl font-black text-blue-900 leading-none">859</h3>
            </div>
            <x-heroicon-o-user-group class="w-12 h-12 text-blue-900/50" />
        </div>
        <div class="bg-[#A8D5BA] p-6 rounded-xl flex items-center justify-between shadow-sm border border-black/5">
            <div>
                <p class="text-green-900 font-bold text-sm">Jumlah Lahan</p>
                <h3 class="text-3xl font-black text-green-900 leading-none">1040</h3>
            </div>
            <x-heroicon-o-map class="w-12 h-12 text-green-900/50" />
        </div>
        <div class="bg-[#E9D79E] p-6 rounded-xl flex items-center justify-between shadow-sm border border-black/5">
            <div>
                <p class="text-yellow-900 font-bold text-sm">Pendapatan Bulan Ini</p>
                <h3 class="text-3xl font-black text-yellow-900 leading-none">859</h3>
            </div>
            <x-heroicon-o-banknotes class="w-12 h-12 text-yellow-900/50" />
        </div>
    </div>

    {{-- Grafik Section --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-4 rounded-xl shadow-sm h-64 flex flex-col items-center">
            <p class="self-start text-[10px] font-bold text-gray-500 mb-4">Pemasukan Per Bulan</p>
            <div class="w-full h-full bg-gray-50 rounded flex flex-col items-center justify-center italic text-gray-400">
                <x-heroicon-o-chart-bar class="w-8 h-8 mb-2" />
                <span class="text-xs">[ Grafik Garis Pemasukan ]</span>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm h-64 flex flex-col items-center">
            <p class="self-start text-[10px] font-bold text-gray-500 mb-4">Pengeluaran Per Kategori</p>
            <div class="w-full h-full bg-gray-50 rounded flex flex-col items-center justify-center italic text-gray-400">
                <x-heroicon-o-chart-pie class="w-8 h-8 mb-2" />
                <span class="text-xs">[ Grafik Pie Pengeluaran ]</span>
            </div>
        </div>
    </div>

    {{-- Status Audit --}}
    <div class="bg-white p-4 rounded-xl shadow-sm">
        <h3 class="text-[10px] font-bold text-gray-500 mb-4 uppercase tracking-widest">Status Audit RSPO/ISPO</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-[#D1FAE5] p-4 rounded-lg flex items-center gap-4 border border-green-200">
                <x-heroicon-s-check-circle class="w-10 h-10 text-green-800" />
                <div><p class="text-2xl font-black text-green-900 leading-none">670</p><p class="text-[10px] font-bold text-green-700">LULUS</p></div>
            </div>
            <div class="bg-[#FEF3C7] p-4 rounded-lg flex items-center gap-4 border border-yellow-200">
                <x-heroicon-s-information-circle class="w-10 h-10 text-yellow-600" />
                <div><p class="text-2xl font-black text-yellow-900 leading-none">130</p><p class="text-[10px] font-bold text-yellow-700 uppercase">PERLU PERBAIKAN</p></div>
            </div>
            <div class="bg-[#FEE2E2] p-4 rounded-lg flex items-center gap-4 border border-red-200">
                <x-heroicon-s-exclamation-triangle class="w-10 h-10 text-red-600" />
                <div><p class="text-2xl font-black text-red-900 leading-none">30</p><p class="text-[10px] font-bold text-red-700 uppercase">PERLU DIAUDIT</p></div>
            </div>
        </div>
    </div>

    {{-- Tabel Verifikasi --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="bg-[#D9F99D] p-3 text-[10px] font-black text-gray-700 uppercase tracking-wider border-b flex items-center gap-2">
            <x-heroicon-o-clipboard-document-check class="w-4 h-4" />
            Petani yang perlu di verifikasi
        </div>
        <table class="w-full text-left text-[10px]">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-3 text-center uppercase">ID</th>
                    <th class="p-3 uppercase">Nama</th>
                    <th class="p-3 uppercase">Email</th>
                    <th class="p-3 text-center uppercase">Status</th>
                    <th class="p-3 text-center uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach(['PT001' => 'Bayu Mirandar', 'PT002' => 'Siti Lestari', 'PT003' => 'Indah Vitonita', 'PT004' => 'Raya Puspita'] as $id => $nama)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3 text-center text-gray-500">{{ $id }}</td>
                    <td class="p-3 font-bold italic">{{ $nama }}</td>
                    <td class="p-3 text-gray-400 italic">user@gmail.com</td>
                    <td class="p-3 text-center">
                        <span class="bg-[#FEF08A] px-4 py-1 rounded-full font-bold shadow-sm">Pending</span>
                    </td>
                    <td class="p-3">
                        <div class="flex justify-center gap-3">
                            <button title="Edit">
                                <x-heroicon-o-pencil-square class="w-5 h-5 text-green-600" />
                            </button>
                            <button title="Hapus">
                                <x-heroicon-o-trash class="w-5 h-5 text-red-500" />
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        {{-- Mini Pagination --}}
        <div class="p-2 flex justify-end gap-1">
            <button class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">
                <x-heroicon-o-chevron-left class="w-3 h-3" />
            </button>
            <button class="px-2 py-1 bg-gray-300 rounded text-[9px]">1</button>
            <button class="px-2 py-1 bg-gray-100 rounded text-[9px]">2</button>
            <button class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">
                <x-heroicon-o-chevron-right class="w-3 h-3" />
            </button>
        </div>
    </div>

    {{-- Map Section --}}
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-2 mb-2">
            <x-heroicon-o-map-pin class="w-4 h-4 text-gray-500" />
            <h3 class="text-[10px] font-bold text-gray-500 uppercase">Sebaran Lahan Anggota</h3>
        </div>
        <div class="w-full h-80 rounded-lg overflow-hidden bg-gray-200 relative">
            <img src="https://maps.googleapis.com/maps/api/staticmap?center=-0.489,101.406&zoom=13&size=800x400&maptype=satellite&key=YOUR_KEY" class="w-full h-full object-cover">
        </div>
    </div>

</div>
@endsection