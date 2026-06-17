{{-- Load Leaflet.js Assets --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="p-2 max-w-6xl mx-auto">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start gap-6 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#214122]">Data Detail Lahan</h1>
            <p class="text-sm text-gray-500 mt-1">Informasi spesifik mengenai koordinat spasial dan kepemilikan lahan.</p>
        </div>
        
        {{-- Info Box Kanan Atas (Dinamis dari Database) --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 w-full md:max-w-md">
            <div class="space-y-2 text-sm">
                <div class="flex">
                    <span class="w-32 font-bold text-gray-700">Nama Lahan</span>
                    <span class="text-gray-600 font-medium">: {{ $lahan->lahan_nama ?? '-' }}</span>
                </div>
                <div class="flex">
                    <span class="w-32 font-bold text-gray-700">Lokasi</span>
                    <span class="text-gray-600">: {{ $lahan->lahan_lokasi }}</span>
                </div>
                <div class="flex">
                    <span class="w-32 font-bold text-gray-700">Luas Lahan</span>
                    <span class="text-gray-600">: {{ number_format($lahan->lahan_luas, 1, ',', '.') }} Ha</span>
                </div>
                <div class="flex">
                    <span class="w-32 font-bold text-gray-700">Pemilik / Petani</span>
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
            {{-- Mengarahkan kembali ke route index utama --}}
            <a href="{{ route('lahan.index') }}" 
               class="bg-[#FF3B30] text-white px-8 md:px-16 py-3 rounded-xl font-bold hover:bg-red-700 transition shadow-md text-center w-full sm:w-auto min-w-50 flex items-center justify-center gap-2 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path>
                </svg>
                Kembali
            </a>
            
            {{-- Tombol Lihat Maps Eksternal Google Maps --}}
            <a id="btnGoogleMaps" href="#" target="_blank"
               class="bg-[#214122] text-white px-8 md:px-16 py-3 rounded-xl font-bold hover:bg-green-900 transition shadow-md text-center w-full sm:w-auto min-w-50 flex items-center justify-center gap-2 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.446l6.002-3.001a1.125 1.125 0 00.597-1.002V3.75a1.125 1.125 0 00-1.708-.969l-6.002 3.001a1.125 1.125 0 01-1.002 0L6.708 2.78a1.125 1.125 0 00-1.003 0L.305 5.782A1.125 1.125 0 000 6.783v11.75a1.125 1.125 0 001.708.969l6.002-3.001a1.125 1.125 0 011.002 0l4.795 2.397a1.125 1.125 0 001.003 0z"></path>
                </svg>
                Lihat Google Maps
            </a>
        </div>
    </div>
</div>

{{-- CONFIG JAVASCRIPT UNTUK MERENDER POLYGON INDIVIDUAL --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Inisialisasi awal objek peta (default view di Riau)
        const mapDetail = L.map('mapDetailLahan').setView([-0.489, 101.406], 13);

        // 2. Load Tile OpenStreetMap Standard
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(mapDetail);

        // 3. Tangkap string koordinat area_lahan dari database
        const areaLahanRaw = @json($lahan->area_lahan);

        if (areaLahanRaw) {
            try {
                // Parsing data jika format di database masih berupa JSON string teks
                const areaData = typeof areaLahanRaw === 'string' ? JSON.parse(areaLahanRaw) : areaLahanRaw;
                
                // Cek format data (Jika dari GeoJSON hasil draw, koordinat berada di dalam objek coordinates)
                let finalCoords = [];
                if (areaData.coordinates && Array.isArray(areaData.coordinates[0])) {
                    // Merestrukturisasi standar GeoJSON [lng, lat] menjadi format Leaflet Polygon [lat, lng]
                    finalCoords = areaData.coordinates[0].map(coord => [coord[1], coord[0]]);
                } else if (Array.isArray(areaData)) {
                    // Fallback jika database kamu menyimpan array object [{lat: x, lng: y}] atau [[lat, lng]]
                    finalCoords = areaData.map(coord => {
                        if (coord.lat !== undefined) return [coord.lat, coord.lng];
                        return coord;
                    });
                }

                if (finalCoords.length > 0) {
                    // 4. Gambar Poligon Lahan Tunggal menggunakan fungsi bawaan L.polygon sesuai kodemu semula
                    const polygon = L.polygon(finalCoords, {
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
                            <b>Nama Lahan:</b> {{ $lahan->lahan_nama ?? '-' }}<br>
                            <b>Pemilik:</b> {{ $lahan->petani->petani_nama ?? '-' }}<br>
                            <b>Luas:</b> {{ $lahan->lahan_luas }} Ha
                        </div>
                    `);

                    // 5. Otomatis atur kamera peta agar langsung memfokuskan poligon lahan di tengah layar
                    const bounds = polygon.getBounds();
                    mapDetail.fitBounds(bounds, { padding: [50, 50] });

                    // 6. Perbaikan Tautan Google Maps Eksternal menggunakan titik tengah polygon
                    const centerPoint = bounds.getCenter();
                    document.getElementById('btnGoogleMaps').href = `https://www.google.com/maps?q=${centerPoint.lat},${centerPoint.lng}`;
                }
            } catch (e) {
                console.error("Gagal membaca struktur array koordinat poligon lahan ini:", e);
                mapDetail.setView([-0.489, 101.406], 12);
            }
        }
    });
</script>