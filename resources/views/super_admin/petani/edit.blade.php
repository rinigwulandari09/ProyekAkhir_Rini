@extends('layouts.dashboard')

@section('title', 'Edit Status & Detail Petani')

@section('header', 'Dashboard Admin')

@section('content')
{{-- Include Leaflet.js Assets (CSS & JS) --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="max-w-6xl mx-auto px-4 py-2 animate-fade-in">
    
    {{-- Pop-up Notifikasi Sukses --}}
    @if(session('success'))
    <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl shadow-sm">
        <x-heroicon-s-check-circle class="w-5 h-5 text-green-600 shrink-0" />
        <p class="text-sm font-medium">{{ session('success') }}</p>
    </div>
    @endif

    {{-- PEMBUNGKUS FORM - MENYEMBUHKAN METHOD NOT ALLOWED ERROR --}}
    <form action="{{ route('petani.updateStatus', $petani->petani_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
            {{-- Header Card --}}
            <div class="bg-[#214122] p-5 px-8 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <x-heroicon-o-user-circle class="w-6 h-6 text-white" />
                    <h2 class="text-lg font-bold text-white tracking-wide">Data Pribadi & Lahan Petani</h2>
                </div>
                <a href="{{ route('petani.index') }}" class="text-xs bg-white/10 text-white border border-white/20 px-4 py-2 rounded-xl font-semibold hover:bg-white/20 transition flex items-center gap-1.5 shadow-sm">
                    <x-heroicon-o-arrow-left class="w-4 h-4" />
                    Kembali
                </a>
            </div>

            <div class="p-6 md:p-8 space-y-10">
                {{-- Section 1: Profil & Informasi Dasar Petani --}}
                <div class="flex flex-col md:flex-row gap-8 items-start">
                    {{-- Foto Avatar Dinamis --}}
                    <div class="w-full md:w-1/4 flex flex-col items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($petani->petani_nama) }}&size=250&background=214122&color=fff&bold=true" 
                             alt="Foto Petani" 
                             class="w-48 h-48 md:w-full md:h-52 object-cover rounded-2xl shadow-md border-2 border-gray-100">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $petani->petani_status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <span class="w-2 h-2 rounded-full {{ $petani->petani_status == 'Aktif' ? 'bg-green-600' : 'bg-red-600' }}"></span>
                            Akun {{ $petani->petani_status }}
                        </span>
                    </div>

                    {{-- Data Profil dari Database --}}
                    <div class="flex-1 w-full">
                        <h3 class="text-xs font-bold text-[#214122] uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Informasi Akun</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase">Nama Lengkap</label>
                                <p class="text-gray-800 font-medium">{{ $petani->petani_nama }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase">Username</label>
                                <p class="text-gray-600 font-mono">@ {{ $petani->petani_username }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase">Alamat Email</label>
                                <p class="text-gray-800">{{ $petani->petani_email ?? '-' }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase">Nomor Handphone</label>
                                <p class="text-gray-800 font-medium">{{ $petani->petani_no_hp }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase">Desa</label>
                                <p class="text-gray-800">{{ $petani->desa->desa_nama ?? '-' }}</p>
                            </div>
                            
                            {{-- Dropdown Status Akun --}}
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase block">Ubah Status Akun</label>
                                <div class="relative max-w-45 mt-1">
                                    <select name="petani_status" class="w-full pl-3 pr-10 py-2 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600 appearance-none cursor-pointer text-sm font-semibold text-gray-700 shadow-sm transition">
                                        <option value="Aktif" {{ $petani->petani_status == 'Aktif' ? 'selected' : '' }}>🟢 Aktif</option>
                                        <option value="Nonaktif" {{ $petani->petani_status == 'Nonaktif' ? 'selected' : '' }}>🔴 Nonaktif</option>
                                    </select>
                                    <x-heroicon-o-chevron-down class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 pointer-events-none" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Informasi Lahan & Geometris Spasial --}}
                <div class="pt-8 border-t border-gray-200">
                    <h3 class="text-xs font-bold text-[#214122] uppercase tracking-wider mb-6 flex items-center gap-2">
                        <x-heroicon-o-map class="w-5 h-5 text-gray-500" />
                        Informasi Kepemilikan Lahan Pertanian
                    </h3>
                    
                    <div class="flex flex-col lg:flex-row gap-8">
                        {{-- Sisi Kiri: Peta Poligon Leaflet Interaktif --}}
                        <div class="w-full lg:w-1/2">
                            @if($petani->lahan && $petani->lahan->area_lahan)
                                {{-- Container untuk peta interaktif --}}
                                <div id="map" class="w-full h-64 rounded-2xl border border-gray-200 shadow-inner relative" style="z-index: 1;"></div>
                            @else
                                <div class="w-full h-64 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-center p-6">
                                    <x-heroicon-o-map-pin class="w-12 h-12 text-gray-300 mb-2" />
                                    <p class="text-sm font-semibold text-gray-500">Data Koordinat Kosong</p>
                                    <p class="text-xs text-gray-400 mt-1">Kolom `area_lahan` pada database belum terisi.</p>
                                </div>
                            @endif
                        </div>

                        {{-- Sisi Kanan: Detail Data Lahan --}}
                        <div class="flex-1 bg-gray-50/60 rounded-2xl p-6 border border-gray-100 flex flex-col justify-center">
                            @if($petani->lahan)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5 text-xs">
                                    <div class="space-y-1">
                                        <span class="font-semibold text-gray-400 block uppercase tracking-wider">Lokasi Lahan</span>
                                        <span class="text-sm font-bold text-gray-800">{{ $petani->lahan->lahan_lokasi ?? 'Lokasi belum diatur' }}</span>
                                    </div>
                                    
                                    <div class="space-y-1">
                                        <span class="font-semibold text-gray-400 block uppercase tracking-wider">Luas Lahan</span>
                                        <span class="text-sm font-bold bg-green-100 text-green-900 px-2.5 py-1 rounded-lg inline-block">
                                            {{ $petani->lahan->lahan_luas ?? '0' }} Ha
                                        </span>
                                    </div>
                                    
                                    <div class="space-y-1 sm:col-span-2">
                                        <span class="font-semibold text-gray-400 block uppercase tracking-wider">Area Spasial (GeoJSON / Koordinat)</span>
                                        <div class="bg-white p-3 rounded-xl border border-gray-200 max-h-24 overflow-y-auto font-mono text-[11px] text-gray-600 shadow-sm">
                                            @if($petani->lahan->area_lahan)
                                                {{ Str::limit($petani->lahan->area_lahan, 120, '...') }}
                                            @else
                                                <span class="text-gray-400 italic">Tidak ada data spasial</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="h-full flex flex-col items-center justify-center text-center py-8">
                                    <x-heroicon-o-document-text class="w-10 h-10 text-gray-300 mb-2" />
                                    <p class="text-xs font-semibold text-gray-500">Belum Memiliki Lahan</p>
                                    <p class="text-[11px] text-gray-400 mt-0.5">ID Petani ini belum terikat dengan tabel lahan manapun.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Panel Tombol Aksi Simpan --}}
                <div class="flex items-center justify-end pt-6 border-t border-gray-100">
                    <button type="submit" class="bg-[#214122] text-white px-8 py-3 rounded-xl font-bold hover:bg-green-900 active:scale-95 transition shadow-md flex items-center gap-2 text-sm cursor-pointer">
                        <x-heroicon-o-check-circle class="w-5 h-5 text-green-400" />
                        Simpan Perubahan Status
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Script Penggambar Polygon Leaflet --}}
@if($petani->lahan && $petani->lahan->area_lahan)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Ambil data mentah koordinat dari DB
        const rawData = {!! $petani->lahan->area_lahan !!};
        
        try {
            // Pastikan data diparsing menjadi array objek javascript
            const areaData = typeof rawData === 'string' ? JSON.parse(rawData) : rawData;

            if (Array.isArray(areaData) && areaData.length > 0) {
                // 2. Mapping format [{lat, lng}] ke format array koordinat Leaflet [[lat, lng]]
                const polygonCoordinates = areaData.map(item => [item.lat, item.lng]);

                // 3. Inisialisasi peta ke element #map, set koordinat pusat awal ke titik pertama polygon
                const map = L.map('map').setView(polygonCoordinates[0], 15);

                // 4. Tambahkan layer peta OpenStreetMap standart
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                // 5. Gambar objek poligon lahan di atas peta
                const polygon = L.polygon(polygonCoordinates, {
                    color: '#214122',       // Garis tepi warna hijau gelap sesuai tema webmu
                    fillColor: '#214122',   // Isian warna hijau
                    fillOpacity: 0.4,       // Tingkat transparansi isian poligon
                    weight: 3               // Ketebalan garis tepi
                }).addTo(map);

                // 6. Atur batas zoom kamera otomatis agar fit dan fokus membungkus seluruh poligon lahan
                map.fitBounds(polygon.getBounds());
            }
        } catch (error) {
            console.error("Gagal memproses struktur koordinat polygon:", error);
        }
    });
</script>
@endif

@endsection