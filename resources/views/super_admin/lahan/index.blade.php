@extends('layouts.dashboard')

@section('title', 'Daftar Lahan')

@section('content')
<div class="p-2">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-[#214122]">Daftar Lahan</h1>
        
        {{-- Search Bar dengan Ikon di Kanan --}}
        <div class="flex items-center shadow-sm">
            <input 
                type="text" 
                placeholder="Cari berdasarkan nama petani" 
                class="px-4 py-2 w-72 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-1 focus:ring-green-700 text-sm"
            >
            <button class="bg-[#D9F99D] border border-l-0 border-gray-300 p-2.5 rounded-r-lg hover:bg-green-200 transition">
                <iconify-icon icon="mdi:magnify" class="text-xl text-[#214122]"></iconify-icon>
            </button>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#D9F99D] border-b border-gray-200">
                        <th class="p-4 text-sm font-bold text-gray-700 text-center">Lokasi</th>
                        <th class="p-4 text-sm font-bold text-gray-700 text-center">Luas Lahan</th>
                        <th class="p-4 text-sm font-bold text-gray-700 text-center">Pemilik</th>
                        <th class="p-4 text-sm font-bold text-gray-700 text-center">No Surat Tanah</th>
                        <th class="p-4 text-sm font-bold text-gray-700 text-center">Thn Tanam</th>
                        <th class="p-4 text-sm font-bold text-gray-700 text-center">Lihat Lokasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                    $lahan = [
                        ['lokasi' => 'Blok A, Lahan Sejahtera 1', 'luas' => '30 ha', 'pemilik' => 'Budi Susilo', 'surat' => '134567', 'tahun' => '2015'],
                        ['lokasi' => 'Blok B', 'luas' => '30 ha', 'pemilik' => 'Budi', 'surat' => '236783', 'tahun' => '2016'],
                        ['lokasi' => 'Blok A, Lahan Sejahtera 2', 'luas' => '30 ha', 'pemilik' => 'Susilo', 'surat' => '753431', 'tahun' => '2010'],
                        ['lokasi' => 'Blok C, Lahan Sejahtera 1', 'luas' => '30 ha', 'pemilik' => 'Budiman', 'surat' => '854331', 'tahun' => '2019'],
                        ['lokasi' => 'Blok C, Lahan 1', 'luas' => '30 ha', 'pemilik' => 'Surya', 'surat' => '983334', 'tahun' => '2020'],
                        ['lokasi' => 'Blok D, Lahan 1', 'luas' => '30 ha', 'pemilik' => 'Soni', 'surat' => '842514', 'tahun' => '2011'],
                        ['lokasi' => 'Blok D', 'luas' => '30 ha', 'pemilik' => 'Roni', 'surat' => '521589', 'tahun' => '2010'],
                        ['lokasi' => 'Blok E', 'luas' => '30 ha', 'pemilik' => 'Riki', 'surat' => '531567', 'tahun' => '2012'],
                        ['lokasi' => 'Blok E, Lahan 1', 'luas' => '30 ha', 'pemilik' => 'Samsul', 'surat' => '851335', 'tahun' => '2011'],
                    ];
                    @endphp

                    @foreach($lahan as $l)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-xs text-gray-800 font-medium">{{ $l['lokasi'] }}</td>
                        <td class="p-4 text-xs text-gray-600 text-center">{{ $l['luas'] }}</td>
                        <td class="p-4 text-xs text-gray-600 text-center">{{ $l['pemilik'] }}</td>
                        <td class="p-4 text-xs text-gray-600 text-center">{{ $l['surat'] }}</td>
                        <td class="p-4 text-xs text-gray-600 text-center">{{ $l['tahun'] }}</td>
                        <td class="p-4 text-center">
                            <a href="{{ route('lahan.show', 1) }}" class="bg-[#214122] text-white px-4 py-1.5 rounded-full text-[10px] flex items-center justify-center gap-1.5 mx-auto hover:bg-green-900 transition shadow-sm">
                                <iconify-icon icon="mdi:map-marker-radius-outline" class="text-sm"></iconify-icon>
                                Lihat
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 flex justify-end border-t border-gray-100 bg-white">
            <nav class="inline-flex items-center -space-x-px shadow-sm rounded-md">
                <button class="px-2 py-1 border border-gray-300 rounded-l-md hover:bg-gray-50 text-gray-400">
                    <iconify-icon icon="mdi:chevron-double-left"></iconify-icon>
                </button>
                <button class="px-3 py-1 border border-gray-300 bg-gray-200 text-gray-700 font-bold text-xs">1</button>
                <button class="px-3 py-1 border border-gray-300 hover:bg-gray-50 text-gray-600 text-xs font-semibold">2</button>
                <button class="px-3 py-1 border border-gray-300 hover:bg-gray-50 text-gray-600 text-xs font-semibold">3</button>
                <button class="px-3 py-1 border border-gray-300 hover:bg-gray-50 text-gray-600 text-xs font-semibold">4</button>
                <button class="px-3 py-1 border border-gray-300 hover:bg-gray-50 text-gray-600 text-xs font-semibold">..</button>
                <button class="px-2 py-1 border border-gray-300 rounded-r-md hover:bg-gray-50 text-gray-400">
                    <iconify-icon icon="mdi:chevron-double-right"></iconify-icon>
                </button>
            </nav>
        </div>
    </div>
</div>
@endsection