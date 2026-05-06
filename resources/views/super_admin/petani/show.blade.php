@extends('layouts.dashboard')

@section('title', 'Detail Petani')

@section('header', 'Dashboard Admin')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
        {{-- Header Card --}}
        <div class="bg-[#214122] p-4 px-8 flex items-center gap-3">
            <x-heroicon-o-user-circle class="w-6 h-6 text-white" />
            <h2 class="text-xl font-bold text-white">Data Pribadi Petani</h2>
        </div>

        <div class="p-8 space-y-10">
            {{-- Section 1: Profil & Informasi Dasar --}}
            <div class="flex flex-col md:flex-row gap-10">
                {{-- Foto Petani --}}
                <div class="w-full md:w-1/3">
                    <img src="https://ui-avatars.com/api/?name=Bayu+Winandar&size=300" 
                         alt="Foto Petani" 
                         class="w-full h-64 object-cover rounded-xl shadow-sm border border-gray-100">
                </div>

                {{-- Detail Informasi --}}
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-y-4 text-sm">
                    <div class="flex">
                        <span class="w-32 font-bold text-gray-700">Nama</span>
                        <span class="text-gray-600">: Bayu Winandar</span>
                    </div>
                    <div class="flex">
                        <span class="w-40 font-bold text-gray-700">Produksi</span>
                        <span class="text-gray-600">: 12 Ton</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 font-bold text-gray-700">Alamat</span>
                        <span class="text-gray-600">: Jalan Tegal Sari 90 B</span>
                    </div>
                    <div class="flex">
                        <span class="w-40 font-bold text-gray-700">Jumlah Pemasukan</span>
                        <span class="text-gray-600">: Rp. 12.000.000</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 font-bold text-gray-700">Nomor Handphone</span>
                        <span class="text-gray-600">: 08978986785</span>
                    </div>
                    <div class="flex">
                        <span class="w-40 font-bold text-gray-700">Jumlah Pengeluaran</span>
                        <span class="text-gray-600">: Rp. 8.000.000</span>
                    </div>

                    {{-- Status Akun Dropdown --}}
                    <div class="flex items-center mt-4 col-span-2 md:col-span-1">
                        <span class="w-32 font-bold text-gray-700">Status Akun</span>
                        <div class="relative flex-1 max-w-37.5">
                            <select class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-green-700 appearance-none cursor-pointer text-xs">
                                <option value="Aktif" selected>Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                            <x-heroicon-o-chevron-down class="absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 pointer-events-none" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 2: Informasi Lahan & Peta --}}
            <div class="flex flex-col md:flex-row gap-10 pt-6 border-t border-gray-100">
                {{-- Map Placeholder --}}
                <div class="w-full md:w-1/2">
                    <div class="w-full h-64 bg-gray-200 rounded-xl overflow-hidden relative shadow-inner">
                        <img src="https://maps.googleapis.com/maps/api/staticmap?center=-0.489,101.406&zoom=15&size=600x300&maptype=satellite&key=YOUR_KEY" 
                             class="w-full h-full object-cover" alt="Lokasi Lahan">
                        <div class="absolute inset-0 flex items-center justify-center bg-black/10">
                            <x-heroicon-s-map-pin class="w-12 h-12 text-red-600 drop-shadow-lg" />
                        </div>
                    </div>
                </div>

                {{-- Detail Lahan --}}
                <div class="flex-1 grid grid-cols-1 gap-y-2 text-xs">
                    <div class="flex">
                        <span class="w-32 font-bold text-gray-700">Nama Lahan</span>
                        <span class="text-gray-600">: Blok A, Lahan Sejahtera 1</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 font-bold text-gray-700">Luas Lahan</span>
                        <span class="text-gray-600">: 7,2 Ha</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 font-bold text-gray-700">No Surat Tanah</span>
                        <span class="text-gray-600">: 125745852904</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 font-bold text-gray-700">Tahun Tanam</span>
                        <span class="text-gray-600">: 2015</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 font-bold text-gray-700">Longitude</span>
                        <span class="text-gray-600">: 1,679937</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 font-bold text-gray-700">Latitude</span>
                        <span class="text-gray-600">: 0,98757</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 font-bold text-gray-700">Jenis Bibit</span>
                        <span class="text-gray-600">: Marihat</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 font-bold text-gray-700">Topografi</span>
                        <span class="text-gray-600">: Datar</span>
                    </div>
                </div>
            </div>

            {{-- Tombol Simpan --}}
            <div class="flex justify-end pt-4 gap-3">
                <button type="submit" class="bg-[#214122] text-white px-10 py-2.5 rounded-xl font-bold hover:bg-green-900 transition shadow-md flex items-center gap-2">
                    <x-heroicon-o-check-circle class="w-5 h-5" />
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>
@endsection