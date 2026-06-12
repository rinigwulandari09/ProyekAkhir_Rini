<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peta Sebaran Lahan - Notasawit</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map { height: 550px; width: 100%; border-radius: 8px; }
    </style>
</head>
<body class="bg-light p-4">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-success m-0">Peta Sebaran Lahan Notasawit</h3>
            <a href="{{ route('lahan.create') }}" class="btn btn-success">+ Input Lahan Baru</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm p-2">
            <div id="map"></div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // 1. Inisialisasi Peta (Arahkan center default ke Riau/daerah kelapa sawit)
        var map = L.map('map').setView([-0.5, 101.3], 9); 

        // 2. Tambahkan Tile Layer (Peta Dasar)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // 3. Ambil data dari Laravel Blade Loop untuk digambar sebagai Polygon GeoJSON
        @foreach($semua_lahan as $lahan)
            @if($lahan->area_geojson)
                
                var geojsonData = {
                    "type": "Feature",
                    "properties": {
                        "nama_lahan": "{{ $lahan->nama_lahan }}",
                        "nama_petani": "{{ $lahan->nama_petani }}",
                        "luas": "{{ $lahan->luas_hektar }}"
                    },
                    "geometry": {!! $lahan->area_geojson !!} // Langsung output string GeoJSON dari database Supabase
                };

                // 4. Gambar Polygon ke Peta
                L.geoJSON(geojsonData, {
                    style: function(feature) {
                        return {
                            color: "#1E5631",       // Warna garis tepi (Hijau Tua)
                            weight: 3,              // Ketebalan garis pembatas
                            fillColor: "#4C9A2A",   // Warna area dalam lahan (Hijau Terang)
                            fillOpacity: 0.4        // Transparansi warna area dalam
                        };
                    },
                    onEachFeature: function (feature, layer) {
                        // Popup info interaktif saat polygon lahan diklik
                        layer.bindPopup(`
                            <div style="font-family: Arial, sans-serif; min-width: 150px;">
                                <h5 style="margin:0 0 5px 0; color:#1E5631;">${feature.properties.nama_lahan}</h5>
                                <hr style="margin:5px 0;">
                                <b>Pemilik:</b> ${feature.properties.nama_petani}<br>
                                <b>Luas Area:</b> ${feature.properties.luas} Hektar
                            </div>
                        `);
                    }
                }).addTo(map);

            @endif
        @endforeach
    </script>
</body>
</html>