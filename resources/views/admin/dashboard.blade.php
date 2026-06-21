@extends('layouts.admin')

@section('title', 'Beranda')

@section('content')
{{-- Include Leaflet.js Assets & Chart.js --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- Tambahan CSS Buttons --}}
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<div class="space-y-6">
    
    {{-- Statistik Utama --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#A0C4E8] p-6 rounded-xl flex items-center justify-between shadow-sm border border-black/5">
            <div>
                <p class="text-blue-900 font-bold text-sm">Jumlah Petani</p>
                <h3 class="text-3xl font-black text-blue-900 leading-none">
                    {{ number_format($jumlahPetani, 0, ',', '.') }}
                </h3>
            </div>
            <x-heroicon-o-user-group class="w-12 h-12 text-blue-900/50" />
        </div>
        <div class="bg-[#A8D5BA] p-6 rounded-xl flex items-center justify-between shadow-sm border border-black/5">
            <div>
                <p class="text-green-900 font-bold text-sm">Luas Lahan (Ha)</p>
                <h3 class="text-3xl font-black text-green-900 leading-none">
                    {{ number_format($jumlahLahan, 0, ',', '.') }}
                </h3>
            </div>
            <x-heroicon-o-map class="w-12 h-12 text-green-900/50" />
        </div>
        <div class="bg-[#E9D79E] p-6 rounded-xl flex items-center justify-between shadow-sm border border-black/5">
            <div>
                <p class="text-yellow-900 font-bold text-sm">Pendapatan Bulan Ini</p>
                <h3 class="text-3xl font-black text-yellow-900 leading-none">
                    Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}
                </h3>
            </div>
            <x-heroicon-o-banknotes class="w-12 h-12 text-yellow-900/50" />
        </div>
    </div>

    {{-- Grafik Section --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-5 rounded-xl shadow-sm h-80 flex flex-col">
            <p class="text-[11px] font-bold text-gray-500 mb-2 uppercase tracking-wider">Pemasukan Per Bulan</p>
            <div class="relative flex-1 w-full h-full">
                <canvas id="chartPemasukan"></canvas>
            </div>
        </div>
        
        <div class="bg-white p-5 rounded-xl shadow-sm h-80 flex flex-col">
            <p class="text-[11px] font-bold text-gray-500 mb-2 uppercase tracking-wider">Pengeluaran Per Kategori</p>
            <div class="relative flex-1 w-full h-full flex justify-center">
                <canvas id="chartPengeluaran"></canvas>
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

    {{-- Map Section --}}
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-2 mb-2">
            <x-heroicon-o-map-pin class="w-4 h-4 text-gray-500" />
            <h3 class="text-[10px] font-bold text-gray-500 uppercase">Sebaran Lahan Anggota</h3>
        </div>
        {{-- Container Peta Sebaran --}}
        <div id="mapSebaran" class="w-full h-96 rounded-lg bg-gray-100 relative border border-gray-200" style="z-index: 1;"></div>
    </div>

</div>

{{-- Script Inisialisasi Chart.js, DataTables & Leaflet --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // --- 1. CONFIG GRAFIK PEMASUKAN (LINE CHART) ---
        const ctxPemasukan = document.getElementById('chartPemasukan').getContext('2d');
        const dataPemasukan = @json(array_values($pemasukanGrafik)); 

        new Chart(ctxPemasukan, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Total Pemasukan (Rp)',
                    data: dataPemasukan,
                    borderColor: '#234323', 
                    backgroundColor: 'rgba(35, 67, 35, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: value => 'Rp ' + value.toLocaleString('id-ID') }
                    }
                }
            }
        });

        // --- 2. CONFIG GRAFIK PENGELUARAN (PIE CHART) ---
        const ctxPengeluaran = document.getElementById('chartPengeluaran').getContext('2d');
        const rawPengeluaran = @json($pengeluaranGrafik);
        const labelsPengeluaran = rawPengeluaran.map(item => item.biaya_jenis);
        const dataPengeluaran = rawPengeluaran.map(item => item.total);

        new Chart(ctxPengeluaran, {
            type: 'pie',
            data: {
                labels: labelsPengeluaran.length ? labelsPengeluaran : ['Belum Ada Pengeluaran'],
                datasets: [{
                    data: dataPengeluaran.length ? dataPengeluaran : [1],
                    backgroundColor: ['#EF4444', '#F59E0B', '#10B981', '#3B82F6', '#8B5CF6', '#EC4899'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 10 } }
                    }
                }
            }
        });

        // --- 3. CONFIG LEAFLET MAPS ---
        const mapSebaran = L.map('mapSebaran', {
            minZoom: 3,
            maxZoom: 19
        }).setView([0.65, 101.85], 13);

        // MENGGUNAKAN LAYER DETAIL OPENSTREETMAP (Sama seperti Gambar 1 Anda)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(mapSebaran);

        const polygonGroup = L.featureGroup().addTo(mapSebaran);
        const listLahan = @json($semuaLahan ?? []);

        listLahan.forEach(function(lahan) {
            if (lahan.area_lahan) {
                try {
                    const areaData = typeof lahan.area_lahan === 'string' ? JSON.parse(lahan.area_lahan) : lahan.area_lahan;
                    
                    if (areaData && areaData.type === 'Polygon' && Array.isArray(areaData.coordinates)) {
                        
                        const polyCoords = areaData.coordinates[0].map(c => [c[1], c[0]]);

                        const isValidWGS84 = polyCoords.every(coord => 
                            coord[0] > -5 && coord[0] < 10 && 
                            coord[1] > 95 && coord[1] < 140   
                        );

                        if (isValidWGS84 && polyCoords.length > 0) {
                            const polygon = L.polygon(polyCoords, {
                                color: '#15803d',       
                                fillColor: '#22c55e',   
                                fillOpacity: 0.4,       
                                weight: 2.5               
                            });

                            polygon.bindPopup(`
                                <div style="font-family: sans-serif; font-size: 12px; min-width: 170px;">
                                    <strong style="color: #166534; font-size: 13px;">Detail Lahan Spasial</strong><br>
                                    <hr style="margin: 4px 0; border: 0; border-top: 1px solid #e5e7eb;">
                                    <b>Nama Pemilik:</b> ${lahan.petani_nama || '-'}<br>
                                    <b>Lokasi Lahan:</b> ${lahan.lahan_lokasi || '-'}<br>
                                    <b>Luas Hamparan:</b> ${lahan.lahan_luas || '0'} Ha
                                </div>
                            `);

                            polygon.addTo(polygonGroup);
                        }
                    }
                } catch (e) {
                    console.error("Gagal rendering polygon pada Lahan ID: " + lahan.lahan_id, e);
                }
            }
        });

        // Mengatur auto-focus dan membatasi agar tidak melakukan zoom out terlalu jauh (ngelebar)
        if (polygonGroup.getLayers().length > 0) {
            mapSebaran.fitBounds(polygonGroup.getBounds(), { 
                padding: [40, 40],
                maxZoom: 16 // Mengunci level zoom otomatis supaya langsung fokus dekat ke area jalan
            });
        }
    });
</script>
@endsection