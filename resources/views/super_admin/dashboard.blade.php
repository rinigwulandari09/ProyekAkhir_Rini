@extends('layouts.dashboard')

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

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-200">
        <div class="overflow-x-auto">
            <table id="tabelPetani" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#D9F99D] border-b border-gray-200">
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">No</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">Nama</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">Email</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center">Status</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($petaniPending as $index => $petani)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-xs text-gray-500 font-mono"></td>
                        <td class="p-4 text-xs text-gray-800 font-medium">{{ $petani->petani_nama }}</td>
                        <td class="p-4 text-xs text-gray-500">{{ $petani->petani_email ?? 'tidak ada email' }}</td>
                        <td class="p-4 text-justify">
                            <span class="bg-[#FEF3C7] text-[#92400E] px-3 py-1 rounded-full text-[10px] font-bold">
                                {{ $petani->petani_status }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex justify gap-3">
                                <button type="button" title="Edit" class="text-green-700 hover:scale-110 transition"
                                        onclick="openEditModal('{{ $petani->petani_id }}', '{{ addslashes($petani->petani_nama) }}', '{{ $petani->petani_status }}', '{{ addslashes($petani->petani_email ?? '-') }}', '{{ addslashes($petani->petani_no_hp ?? '-') }}', '{{ addslashes($petani->petani_alamat ?? '-') }}', '{{ addslashes($petani->petani_jenis_kelamin ?? '-') }}')">
                                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                                </button>
                                <button title="Hapus" class="text-red-500 hover:scale-110 transition">
                                    <x-heroicon-o-trash class="w-5 h-5" />
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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

{{-- Modal Edit Status --}}
<div id="statusModal" class="fixed inset-0 z-50 hidden bg-black/40 items-center justify-center transition-opacity">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform scale-95 transition-transform border border-[#214122]/20" id="modalContent">
        <div class="bg-[#214122] px-6 py-4 flex justify-between items-center">
            <h3 class="text-lg font-bold text-white">Aktivasi Akun Petani</h3>
            <button type="button" onclick="closeEditModal()" class="text-white hover:text-red-300 transition">
                <x-heroicon-o-x-mark class="w-6 h-6" />
            </button>
        </div>  
        
        <form id="formUbahStatus" method="POST" action="">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Petani</label>
                    <p id="modalNamaPetani" class="text-gray-900 bg-gray-100 px-3 py-2 rounded-lg font-medium"></p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Email</label>
                        <p id="modalEmailPetani" class="text-xs text-gray-800 bg-gray-50 px-3 py-2 rounded-lg border border-gray-100 truncate"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">No. HP</label>
                        <p id="modalHpPetani" class="text-xs text-gray-800 bg-gray-50 px-3 py-2 rounded-lg border border-gray-100"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Jenis Kelamin</label>
                        <p id="modalJkPetani" class="text-xs text-gray-800 bg-gray-50 px-3 py-2 rounded-lg border border-gray-100"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Alamat</label>
                        <p id="modalAlamatPetani" class="text-xs text-gray-800 bg-gray-50 px-3 py-2 rounded-lg border border-gray-100 truncate"></p>
                    </div>
                </div>
                
                <div>
                    <label for="petani_status" class="block text-sm font-bold text-gray-200 mb-1">Status Baru</label>
                    <select id="selectStatus" name="petani_status" class="w-full border border-[#214122]/30 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#214122] focus:border-[#214122] outline-none bg-[#f8faf7] text-[#214122]">
                        <option value="Pending">Pending</option>
                        <option value="Aktif">Disetujui</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                </div>
            </div>
            
            <div class="bg-[#f1f5f0] px-6 py-4 border-t border-[#214122]/10 flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-sm font-bold text-[#214122] bg-white border border-[#214122]/20 rounded-lg hover:bg-[#e8f0e8] transition">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-[#214122] rounded-lg hover:bg-[#1b3d1b] transition shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
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

        // --- 3. CONFIG LEAFLET MAPS - SEBARAN BANYAK LAHAN (FIXED) ---
        const mapSebaran = L.map('mapSebaran').setView([-0.489, 101.406], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(mapSebaran);

        const polygonGroup = L.featureGroup().addTo(mapSebaran);
        const listLahan = @json($semuaLahan ?? []);

        listLahan.forEach(function(lahan) {
            if (lahan.area_lahan) {
                try {
                    let areaData = typeof lahan.area_lahan === 'string' ? JSON.parse(lahan.area_lahan) : lahan.area_lahan;
                    
                    // Ekstraksi data jika dibungkus format GeoJSON standard (geometry.coordinates)
                    if (areaData.geometry && areaData.geometry.coordinates) {
                        areaData = areaData.geometry.coordinates[0];
                    } else if (areaData.coordinates) {
                        areaData = areaData.coordinates[0];
                    } else if (areaData.features && areaData.features[0]) {
                        areaData = areaData.features[0].geometry.coordinates[0];
                    }

                    if (Array.isArray(areaData) && areaData.length > 0) {
                        const polyCoords = areaData.map(coord => {
                            if (coord !== null && typeof coord === 'object' && 'lat' in coord && 'lng' in coord) {
                                return [coord.lat, coord.lng];
                            } else if (Array.isArray(coord) && coord.length >= 2) {
                                // Koreksi otomatis jika koordinat terbalik [longitude, latitude] dari format GeoJSON Postgres
                                if (Math.abs(coord[0]) > 90) {
                                    return [coord[1], coord[0]];
                                }
                                return [coord[0], coord[1]];
                            }
                            return null;
                        }).filter(c => c !== null);

                        if (polyCoords.length > 0) {
                            const polygon = L.polygon(polyCoords, {
                                color: '#214122',       
                                fillColor: '#214122',   
                                fillOpacity: 0.4,      
                                weight: 3              
                            });

                            polygon.bindPopup(`
                                <div style="font-family: sans-serif; font-size: 12px; min-width: 160px;">
                                    <strong style="color: #214122; font-size: 13px;">Detail Lahan Anggota</strong><br>
                                    <hr style="margin: 6px 0; border: 0; border-top: 1px solid #eee;">
                                    <b>Nama Petani:</b> ${lahan.petani_nama || '-'}<br>
                                    <b>Lokasi Lahan:</b> ${lahan.lahan_lokasi || '-'}<br>
                                    <b>Luas Lahan:</b> ${lahan.lahan_luas || '0'} Ha
                                </div>
                            `);

                            polygon.addTo(polygonGroup);
                        }
                    }
                } catch (e) {
                    console.error("Gagal membaca koordinat lahan ID: " + lahan.lahan_id, e);
                }
            }
        });

        // Trigger otomatis agar Leaflet menyesuaikan bound map dan ukuran container
        if (polygonGroup.getLayers().length > 0) {
            setTimeout(() => {
                mapSebaran.invalidateSize();
                mapSebaran.fitBounds(polygonGroup.getBounds(), { padding: [40, 40] });
            }, 300);
        }
    });

    // --- 4. INITIALISASI DATATABLES (AUTO NUMBER & BAHASA INDONESIA) ---
    var table = $('#tabelPetani').DataTable({
        "pageLength": 5,
        "lengthMenu": [5, 10, 25, 50],
        "order": [[ 1, "asc" ]], 
        "dom": '<"flex justify-between items-center mb-4"lf>rt<"flex justify-between items-center mt-4"ip>',
        "columnDefs": [
            { "orderable": false, "targets": [0, 4] } 
        ]
    });

    table.on('order.dt search.dt draw.dt', function () {
        let start = table.page.info().start;
        table.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            cell.innerHTML = start + i + 1;
        });
    }).draw();
</script>

{{-- Script Modal Edit --}}
<script>
    function openEditModal(id, nama, status, email, hp, alamat, jk) {
        const modal = document.getElementById('statusModal');
        const form = document.getElementById('formUbahStatus');
        const namaText = document.getElementById('modalNamaPetani');
        const statusSelect = document.getElementById('selectStatus');
        
        document.getElementById('modalEmailPetani').innerText = email;
        document.getElementById('modalHpPetani').innerText = hp;
        document.getElementById('modalAlamatPetani').innerText = alamat;
        document.getElementById('modalAlamatPetani').title = alamat; // tooltip hover untuk alamat panjang
        document.getElementById('modalJkPetani').innerText = jk;
        
        form.action = `/dashboard/petani/${id}/status`; 
        namaText.innerText = nama;
        statusSelect.value = status;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            document.getElementById('modalContent').classList.replace('scale-95', 'scale-100');
        }, 10);
    }

    function closeEditModal() {
        const modal = document.getElementById('statusModal');
        document.getElementById('modalContent').classList.replace('scale-100', 'scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }
</script>

{{-- DataTables CSS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<style>
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e5e7eb !important;
        border-radius: 9999px !important;
        padding: 4px 12px !important;
        margin-bottom: 10px !important;
        outline: none !important;
    }
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #e5e7eb !important;
        border-radius: 8px !important;
        padding: 2px 8px !important;
    }
    table.dataTable thead th {
        border-bottom: 1px solid #e5e7eb !important;
    }
</style>
@endsection