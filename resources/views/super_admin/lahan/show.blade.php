@extends('layouts.dashboard')

@section('title', 'Detail Lahan')

@section('content')
{{-- Load Leaflet.js Assets --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="p-2 max-w-6xl mx-auto">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start gap-6 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#214122]">Data Lahan</h1>
            <p class="text-sm text-gray-500 mt-1">Informasi spesifik mengenai koordinat dan kepemilikan lahan.</p>
        </div>
        
        {{-- Info Box Kanan Atas (Dinamis dari Database) --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 w-full md:max-w-md">
            <div class="space-y-2 text-sm">
                <div class="flex">
                    <span class="w-32 font-bold text-gray-700">Lokasi</span>
                    <span class="text-gray-600">: {{ $lahan->lahan_lokasi }}</span>
                </div>
                <div class="flex">
                    <span class="w-32 font-bold text-gray-700">Luas Lahan</span>
                    <span class="text-gray-600">: {{ number_format($lahan->lahan_luas, 1, ',', '.') }} Ha</span>
                </div>
                <div class="flex">
                    <span class="w-32 font-bold text-gray-700">Tahun Tanam</span>
                    {{-- Menampilkan tahun_tanam jika kolom ada, jika tidak ada tampilkan tanda strip --}}
                    <span class="text-gray-600">: {{ $lahan->tahun_tanam ?? '-' }}</span>
                </div>
                <div class="flex">
                    <span class="w-32 font-bold text-gray-700">Pemilik</span>
                    {{-- Mengambil nama dari relasi tabel petani --}}
                    <span class="text-gray-600 font-semibold">: {{ $lahan->petani->petani_nama ?? 'Tidak Ada Pemilik' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Map Section Card --}}
    <div class="bg-white rounded-3xl shadow-sm p-4 md:p-8 border border-gray-200">
        <div class="relative w-full h-80 md:h-112 rounded-2xl overflow-hidden mb-8 border border-gray-100 shadow-inner">
            {{-- Wadah Elemen Peta Interaktif Leaflet --}}
            <div id="mapDetailLahan" class="w-full h-full bg-gray-50" style="z-index: 1;"></div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            {{-- Mengarahkan kembali ke route index admin yang benar --}}
            <a href="{{ route('lahan.index') }}" 
               class="bg-[#FF3B30] text-white px-8 md:px-16 py-3 rounded-xl font-bold hover:bg-red-700 transition shadow-lg text-center w-full sm:w-auto min-w-50 flex items-center justify-center gap-2">
                <x-heroicon-o-arrow-left class="w-5 h-5" />
                Kembali
            </a>
            
            {{-- Tombol Lihat Maps Eksternal (Link koordinat di-generate otomatis via JavaScript di bawah) --}}
            <a id="btnGoogleMaps" href="#" target="_blank"
               class="bg-[#214122] text-white px-8 md:px-16 py-3 rounded-xl font-bold hover:bg-green-900 transition shadow-lg text-center w-full sm:w-auto min-w-50 flex items-center justify-center gap-2">
                <x-heroicon-o-map class="w-5 h-5" />
                Lihat Maps
            </a>
        </div>
    </div>
</div>

{{-- CONFIG JAVASCRIPT UNTUK MERENDER POLYGON INDIVIDUAL --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Inisialisasi awal objek peta (default view ditaruh di Riau)
        const mapDetail = L.map('mapDetailLahan').setView([-0.489, 101.406], 13);

        // 2. Load Tile OpenStreetMap Standard (Tema abu-abu jalanan bersih)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(mapDetail);

        // 3. Tangkap string koordinat area_lahan dari database
        const areaLahanRaw = @json($lahan->area_lahan);

        if (areaLahanRaw) {
            try {
                // Parsing data jika format di database masih berupa JSON string teks
                const areaData = typeof areaLahanRaw === 'string' ? JSON.parse(areaLahanRaw) : areaLahanRaw;
                
                if (Array.isArray(areaData) && areaData.length > 0) {
                    // Mapping data koordinat [{lat: x, lng: y}] ke format array Leaflet [[lat, lng]]
                    const polyCoords = areaData.map(coord => [coord.lat, coord.lng]);

                    // 4. Gambar Poligon Lahan Tunggal dengan variasi warna hijau gelap elegan (#214122)
                    const polygon = L.polygon(polyCoords, {
                        color: '#214122',       // Garis tepi luar
                        fillColor: '#214122',   // Isian dalam poligon
                        fillOpacity: 0.4,       // Transparansi isi poligon
                        weight: 3               // Ketebalan garis
                    }).addTo(mapDetail);

                    // Tambahkan marker popup deskripsi saat poligon diklik
                    polygon.bindPopup(`
                        <div style="font-family: sans-serif; font-size: 12px; min-width: 130px;">
                            <strong style="color: #214122; font-size: 13px;">Lahan Milik Petani</strong><br>
                            <hr style="margin: 4px 0; border: 0; border-top: 1px solid #eee;">
                            <b>Pemilik:</b> {{ $lahan->petani->petani_nama ?? '-' }}<br>
                            <b>Luas:</b> {{ $lahan->lahan_luas }} Ha
                        </div>
                    `);

                    // 5. Otomatis atur kamera peta agar langsung memfokuskan poligon lahan di tengah layar
                    const bounds = polygon.getBounds();
                    mapDetail.fitBounds(bounds, { padding: [50, 50] });

                    // 6. Set dinamis koordinat link tombol Google Maps eksternal berdasarkan titik tengah poligon
                    const centerPoint = bounds.getCenter();
                    document.getElementById('btnGoogleMaps').href = `https://www.google.com/maps?q=${centerPoint.lat},${centerPoint.lng}`;
                }
            } catch (e) {
                console.error("Gagal membaca struktur array koordinat poligon lahan ini:", e);
                // Jika koordinat korup atau gagal dibaca, set default penanda map biasa
                mapDetail.setView([-0.489, 101.406], 12);
            }
        }
    });
</script>
@endsection