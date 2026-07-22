@extends('layouts.admin') 

@section('title', 'Edit Lahan')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#214122]">Edit Lahan</h1>
        <p class="text-sm text-gray-500">Perbarui data lahan dan area polygon spasial.</p>
    </div>

    {{-- Error Alert --}}
    @if ($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl text-sm">
            <strong>Terjadi kesalahan:</strong>
            <ul class="list-disc pl-5 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('lahan.update', $lahan->lahan_id) }}" method="POST" id="formLahan">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                {{-- Nama Lahan --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lahan</label>
                    <input type="text" name="lahan_nama" value="{{ old('lahan_nama', $lahan->lahan_nama) }}" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:border-green-600 focus:ring-1 focus:ring-green-600 outline-none" placeholder="Contoh: Lahan Sawit Blok A">
                </div>

                {{-- Lokasi Lahan --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Lokasi Lahan (Deskripsi/Alamat)</label>
                    <input type="text" name="lahan_lokasi" value="{{ old('lahan_lokasi', $lahan->lahan_lokasi) }}" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:border-green-600 focus:ring-1 focus:ring-green-600 outline-none" placeholder="Contoh: Desa Makmur, RT 02">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                {{-- Luas Lahan --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Luas Lahan (Ha)</label>
                    <input type="number" step="0.01" name="lahan_luas" value="{{ old('lahan_luas', $lahan->lahan_luas) }}" required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:border-green-600 focus:ring-1 focus:ring-green-600 outline-none" placeholder="Contoh: 2.5">
                </div>

                {{-- Pilih Petani --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pemilik / Petani</label>
                    <select name="petani_id" required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:border-green-600 focus:ring-1 focus:ring-green-600 outline-none bg-white">
                        <option value="">-- Pilih Petani Pemilik --</option>
                        @foreach($petanis as $petani)
                            <option value="{{ $petani->petani_id }}" {{ old('petani_id', $lahan->petani_id) == $petani->petani_id ? 'selected' : '' }}>
                                {{ $petani->petani_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                {{-- Tahun Tanam --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tahun Tanam (Opsional)</label>
                    <input type="number" name="tahun_tanam" value="{{ old('tahun_tanam', $lahan->tahun_tanam) }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:border-green-600 focus:ring-1 focus:ring-green-600 outline-none" placeholder="Contoh: 2018">
                </div>

                {{-- No Surat Lahan --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">No Surat Lahan (Opsional)</label>
                    <input type="text" name="lahan_no_surat" value="{{ old('lahan_no_surat', $lahan->lahan_no_surat) }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:border-green-600 focus:ring-1 focus:ring-green-600 outline-none" placeholder="Contoh: SHM.123/Desa">
                </div>
            </div>

            {{-- Textarea untuk menampilkan/mengedit koordinat JSON Polygon --}}
            <div class="mb-5">
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-semibold text-gray-700">Data Koordinat Lahan (GeoJSON)</label>
                    <button type="button" id="btnUpdateMap" class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded hover:bg-blue-200 font-medium transition cursor-pointer shadow-sm">
                        Update Peta dari Teks
                    </button>
                </div>
                <textarea name="area_lahan" id="area_lahan" rows="4" class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:border-green-600 focus:ring-1 focus:ring-green-600 outline-none font-mono text-gray-600" placeholder='{"type":"Polygon","coordinates":[[...]]}'>{{ old('area_lahan', is_array($lahan->area_lahan) || is_object($lahan->area_lahan) ? json_encode($lahan->area_lahan) : $lahan->area_lahan) }}</textarea>
                <p class="text-xs text-gray-400 mt-1">*Anda dapat menggambar di peta bawah ATAU menempelkan (paste) data koordinat langsung ke kotak ini.</p>
            </div>

            {{-- Peta Leaflet untuk Menggambar Polygon --}}
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Area Polygon Lahan pada Peta</label>
                <div id="map" class="w-full h-96 rounded-2xl border border-gray-300 z-0 relative"></div>
                <p class="text-xs text-gray-400 mt-1">*Anda bisa mengedit bentuk polygon yang sudah ada atau menghapusnya dan menggambar yang baru.</p>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end gap-3 border-t border-gray-100 pt-4">
                <a href="{{ route('lahan.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#214122] hover:bg-green-950 text-white text-sm font-semibold active:scale-95 transition shadow-sm cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- LEAFLET CSS & JS + LEAFLET DRAW PLUGIN --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css"/>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

<script>
    // 1. Inisialisasi Peta
    var map = L.map('map').setView([-0.7893, 113.9213], 5); // Default Indonesia

    // Perbaikan bug peta abu-abu (grey tile) saat diload dalam layout dinamis
    setTimeout(function() {
        map.invalidateSize();
    }, 400);

    // Menggunakan tile layer OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // 2. Wadah/Grup untuk menyimpan hasil gambar koordinat
    var drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    // Fungsi untuk meload data GeoJSON dari Textarea ke Peta
    function loadDataToMap() {
        var dataArea = document.getElementById('area_lahan').value;
        drawnItems.clearLayers(); // Bersihkan peta sebelum dirender ulang
        
        if(dataArea && dataArea.trim() !== '') {
            try {
                var geojson = JSON.parse(dataArea);
                
                // Format geojson dari DB jika hanya geometry, jadikan Feature
                if (geojson.type !== 'Feature' && geojson.type !== 'FeatureCollection') {
                     geojson = {
                         "type": "Feature",
                         "geometry": geojson
                     };
                }
                
                var layer = L.geoJSON(geojson, {
                    style: {
                        color: '#214122',
                        fillColor: '#D9F99D',
                        fillOpacity: 0.5
                    }
                });
                
                // Tambahkan setiap polygon di geojson ke drawnItems agar bisa diedit
                layer.eachLayer(function(l) {
                    drawnItems.addLayer(l);
                });
                // Pusatkan peta ke area polygon yang ada (tunggu invalidateSize selesai)
                setTimeout(function() {
                    var bounds = layer.getBounds();
                    if(bounds.isValid()) {
                        map.fitBounds(bounds, {
                            padding: [20, 20],
                            maxZoom: 17
                        });
                    }
                }, 500);
                
            } catch(e) { 
                console.log("Error loading map data:", e); 
                alert("Format JSON koordinat tidak valid!");
            }
        }
    }

    // Panggil saat pertama kali load
    loadDataToMap();

    // Event listener untuk tombol Update Peta dari Teks
    document.getElementById('btnUpdateMap').addEventListener('click', function() {
        loadDataToMap();
    });

    // 3. Konfigurasi Toolbar Menggambar (Hanya Polygon yang aktif)
    var drawControl = new L.Control.Draw({
        edit: {
            featureGroup: drawnItems,
            remove: true
        },
        draw: {
            polygon: {
                allowIntersection: false, // Mencegah garis saling silang berpotongan
                shapeOptions: {
                    color: '#214122',
                    fillColor: '#D9F99D',
                    fillOpacity: 0.5
                }
            },
            polyline: false,
            circle: false,
            rectangle: false,
            marker: false,
            circlemarker: false
        }
    });
    map.addControl(drawControl);

    // 4. Proses menangkap koordinat selesai digambar
    map.on(L.Draw.Event.CREATED, function (event) {
        drawnItems.clearLayers(); // Bersihkan polygon lama (1 lahan hanya boleh punya 1 polygon area)

        var layer = event.layer;
        drawnItems.addLayer(layer);

        var geojsonData = layer.toGeoJSON();
        // Set ke value hidden input dalam bentuk String GeoJSON Geometry
        document.getElementById('area_lahan').value = JSON.stringify(geojsonData.geometry);
    });

    // Deteksi perubahan bentuk polygon saat diedit
    map.on(L.Draw.Event.EDITED, function (event) {
        var layers = event.layers;
        layers.eachLayer(function (layer) {
            var geojsonData = layer.toGeoJSON();
            document.getElementById('area_lahan').value = JSON.stringify(geojsonData.geometry);
        });
    });

    // Reset value input jika polygon dihapus dari peta
    map.on(L.Draw.Event.DELETED, function () {
        document.getElementById('area_lahan').value = '';
    });

    // Intersepsi submit untuk validasi peta kosong
    document.getElementById('formLahan').addEventListener('submit', function(e) {
        var area = document.getElementById('area_lahan').value;
        if(!area) {
            e.preventDefault();
            alert('Silakan gambar area polygon lahan terlebih dahulu menggunakan alat di kiri peta!');
        }
    });
</script>
@endsection
