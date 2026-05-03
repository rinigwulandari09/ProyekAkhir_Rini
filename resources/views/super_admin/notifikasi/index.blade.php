@extends('layouts.dashboard')

@section('title', 'Semua Notifikasi')

@section('content')
<div class="p-2">
    {{-- Header Section --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#214122]">Semua Notifikasi</h1>
            <p class="text-xs text-green-600 font-medium flex items-center gap-1">
                <span class="w-2 h-2 bg-green-500 rounded-full"></span> 8 Notifikasi Belum Terbaca
            </p>
        </div>
        <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 hover:bg-gray-50 transition shadow-sm">
            <iconify-icon icon="mdi:check-all" class="text-lg text-green-600"></iconify-icon>
            Tandai Semua Terbaca
        </button>
    </div>

    {{-- Filter & Search Container --}}
    <div class="bg-white p-4 rounded-t-2xl border border-gray-200 border-b-0 flex justify-between items-center">
        {{-- Tabs --}}
        <div class="flex gap-2 p-1 bg-gray-100 rounded-xl">
            <button class="px-6 py-1.5 bg-white shadow-sm rounded-lg text-xs font-bold text-[#214122]">Semua</button>
            <button class="px-6 py-1.5 text-gray-400 hover:text-gray-600 text-xs font-bold transition">Produksi</button>
            <button class="px-6 py-1.5 text-gray-400 hover:text-gray-600 text-xs font-bold transition">Keuangan</button>
        </div>

        {{-- Search --}}
        <div class="relative">
            <iconify-icon icon="mdi:magnify" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></iconify-icon>
            <input type="text" placeholder="Cari notifikasi..." class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-xs outline-none w-64 focus:ring-1 focus:ring-green-700">
        </div>
    </div>

    {{-- Notification List Container --}}
    <div class="bg-white rounded-b-2xl shadow-sm p-6 border border-gray-200 space-y-4">
        
        {{-- Urgent/System Notification --}}
        <div class="relative flex items-center gap-4 p-5 bg-green-50/50 border-l-4 border-green-600 rounded-r-xl">
            <div class="w-12 h-12 bg-[#214122] rounded-xl flex items-center justify-center text-white shrink-0 shadow-sm">
                <iconify-icon icon="mdi:file-chart-outline" class="text-2xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <div class="flex justify-between items-center mb-1">
                    <h3 class="text-sm font-bold text-green-800">Laporan Harian Produksi</h3>
                    <div class="flex items-center gap-2">
                        <span class="bg-green-200 text-green-700 px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider">URGENT</span>
                        <span class="text-[10px] text-gray-400 font-medium">Sekarang</span>
                    </div>
                </div>
                <p class="text-xs text-gray-600 mb-2">15 Petani telah mengisi data produksi hari ini. Silakan tinjau ringkasan harian untuk validasi gudang.</p>
                <a href="#" class="text-xs font-bold text-green-700 underline hover:text-green-900 transition">Tinjau Laporan</a>
            </div>
        </div>

        {{-- Regular Notifications (Petani Activity) --}}
        @php
        $notifs = [
            ['nama' => 'Ahmad Subardjo', 'lokasi' => 'Blok A-12', 'waktu' => '2m ago', 'hasil' => '2.5 Ton Kelapa Sawit (TBS)'],
            ['nama' => 'Siti Aminah', 'lokasi' => 'Blok C-05', 'waktu' => '15m ago', 'hasil' => '1.8 Ton Kelapa Sawit (TBS)'],
            ['nama' => 'Bambang Wijaya', 'lokasi' => 'Blok B-08', 'waktu' => '1h ago', 'hasil' => '3.2 Ton Kelapa Sawit (TBS)'],
            ['nama' => 'Bambang Wijaya', 'lokasi' => 'Blok B-08', 'waktu' => '1h ago', 'hasil' => '3.2 Ton Kelapa Sawit (TBS)'],
            ['nama' => 'Bambang Wijaya', 'lokasi' => 'Blok B-08', 'waktu' => '1h ago', 'hasil' => '3.2 Ton Kelapa Sawit (TBS)'],
        ];
        @endphp

        @foreach($notifs as $n)
        <div class="flex items-center gap-4 p-4 hover:bg-gray-50 border border-gray-100 rounded-2xl transition group cursor-pointer">
            <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-blue-400 border border-blue-100 shrink-0 group-hover:bg-blue-100 transition">
                <iconify-icon icon="mdi:account-plus-outline" class="text-xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs text-gray-700 leading-normal">
                    <span class="font-bold text-gray-900">{{ $n['nama'] }}</span> telah mengisi data produksi: 
                    <span class="text-green-700 font-bold ml-1">{{ $n['hasil'] }}</span>
                </p>
                <div class="flex items-center gap-3 mt-1 text-[10px] text-gray-400">
                    <span class="flex items-center gap-1"><iconify-icon icon="mdi:map-marker-outline"></iconify-icon> {{ $n['lokasi'] }}</span>
                    <span class="flex items-center gap-1"><iconify-icon icon="mdi:clock-outline"></iconify-icon> {{ $n['waktu'] }}</span>
                </div>
            </div>
        </div>
        @endforeach

        {{-- Footer/Pagination --}}
        <div class="mt-8 pt-4 border-t border-gray-100 flex justify-between items-center">
            <p class="text-[10px] text-gray-400">Menampilkan 1-10 dari 48 notifikasi</p>
            <div class="flex gap-1">
                <button class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 text-sm hover:bg-gray-50 transition"><iconify-icon icon="mdi:chevron-left"></iconify-icon></button>
                <button class="w-7 h-7 flex items-center justify-center rounded-lg bg-[#214122] text-white text-[10px] font-bold">1</button>
                <button class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 text-[10px] font-bold hover:bg-gray-50 transition">2</button>
                <button class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 text-[10px] font-bold hover:bg-gray-50 transition">3</button>
                <span class="px-2 text-gray-400 flex items-center text-[10px]">...</span>
                <button class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 text-[10px] font-bold hover:bg-gray-50 transition">5</button>
                <button class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 text-sm hover:bg-gray-50 transition"><iconify-icon icon="mdi:chevron-right"></iconify-icon></button>
            </div>
        </div>
    </div>
</div>
@endsection