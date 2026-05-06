@extends('layouts.dashboard')

@section('title', 'Detail Lahan')

@section('content')
<div class="p-2 max-w-6xl mx-auto">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start gap-6 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#214122]">Data Lahan</h1>
            <p class="text-sm text-gray-500 mt-1">Informasi spesifik mengenai koordinat dan kepemilikan lahan.</p>
        </div>
        
        {{-- Info Box Kanan Atas --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 w-full md:max-w-md">
            <div class="space-y-2 text-sm">
                <div class="flex">
                    <span class="w-32 font-bold text-gray-700">Lokasi</span>
                    <span class="text-gray-600">: Jalan kemayongan, Desa Rumbai</span>
                </div>
                <div class="flex">
                    <span class="w-32 font-bold text-gray-700">Luas Lahan</span>
                    <span class="text-gray-600">: 7,2 Ha</span>
                </div>
                <div class="flex">
                    <span class="w-32 font-bold text-gray-700">Tahun Tanam</span>
                    <span class="text-gray-600">: 2015</span>
                </div>
                <div class="flex">
                    <span class="w-32 font-bold text-gray-700">Pemilik</span>
                    <span class="text-gray-600 font-semibold">: Bayu Winandar</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Map Section Card --}}
    <div class="bg-white rounded-3xl shadow-sm p-4 md:p-8 border border-gray-200">
        <div class="relative w-full h-80 md:h-112 rounded-2xl overflow-hidden mb-8 border border-gray-100 shadow-inner">
            {{-- Peta Satelit --}}
            <img src="https://maps.googleapis.com/maps/api/staticmap?center=-0.58,101.42&zoom=16&size=1000x500&maptype=satellite&key=YOUR_API_KEY" 
                 class="w-full h-full object-cover" 
                 alt="Lokasi Lahan">
            
            {{-- Marker Overlay (Heroicon Solid) --}}
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="flex flex-col items-center">
                    <div class="bg-white px-3 py-1 rounded shadow-md text-[10px] font-bold mb-1 border border-gray-200">
                        Ladang sawit Sianturi
                    </div>
                    <x-heroicon-s-map-pin class="w-10 h-10 text-red-500 drop-shadow-xl" />
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="{{ route('lahan.index') }}" 
               class="bg-[#FF3B30] text-white px-8 md:px-16 py-3 rounded-xl font-bold hover:bg-red-700 transition shadow-lg text-center w-full sm:w-auto min-w-50 flex items-center justify-center gap-2">
                <x-heroicon-o-arrow-left class="w-5 h-5" />
                Kembali
            </a>
            
            <a href="https://www.google.com/maps" target="_blank"
               class="bg-[#214122] text-white px-8 md:px-16 py-3 rounded-xl font-bold hover:bg-green-900 transition shadow-lg text-center w-full sm:w-auto min-w-50 flex items-center justify-center gap-2">
                <x-heroicon-o-map class="w-5 h-5" />
                Lihat Maps
            </a>
        </div>
    </div>
</div>
@endsection