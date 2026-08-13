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
        <div class="bg-gradient-to-r from-[#184D2E] to-[#D4AF37] p-6 rounded-2xl flex items-center justify-between shadow-lg shadow-[#184D2E]/20 border border-white/10 relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/20 rounded-full blur-2xl pointer-events-none"></div>
            <div class="relative z-10">
                <p class="text-white/90 font-medium text-sm font-poppins tracking-wide">Jumlah Petani</p>
                <h3 class="text-3xl font-black text-white leading-none font-poppins mt-1">
                    {{ number_format($jumlahPetani, 0, ',', '.') }}
                </h3>
            </div>
            <x-heroicon-o-user-group class="w-12 h-12 text-white/30 relative z-10" />
        </div>
        <div class="bg-gradient-to-r from-[#FDE047] to-[#D4AF37] p-6 rounded-2xl flex items-center justify-between shadow-lg shadow-[#D4AF37]/20 border border-white/10 relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/40 rounded-full blur-2xl pointer-events-none"></div>
            <div class="relative z-10">
                <p class="text-[#184D2E] font-medium text-sm font-poppins tracking-wide">Luas Lahan (Ha)</p>
                <h3 class="text-3xl font-black text-[#184D2E] leading-none font-poppins mt-1">
                    {{ number_format($jumlahLahan, 0, ',', '.') }}
                </h3>
            </div>
            <x-heroicon-o-map class="w-12 h-12 text-[#184D2E]/30 relative z-10" />
        </div>
        <div class="bg-gradient-to-r from-[#318552] to-[#184D2E] p-6 rounded-2xl flex items-center justify-between shadow-lg shadow-[#184D2E]/20 border border-white/10 relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="relative z-10">
                <p class="text-white/90 font-medium text-sm font-poppins tracking-wide">Pendapatan Bulan Ini</p>
                <h3 class="text-3xl font-black text-white leading-none font-poppins mt-1">
                    Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}
                </h3>
            </div>
            <x-heroicon-o-banknotes class="w-12 h-12 text-white/30 relative z-10" />
        </div>
    </div>

    {{-- Grafik Section --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-5 rounded-xl shadow-sm h-80 flex flex-col">
            <p class="text-[11px] font-bold text-gray-500 mb-2 uppercase tracking-wider font-poppins">Pemasukan Per Bulan</p>
            <div class="relative flex-1 w-full h-full">
                <canvas id="chartPemasukan"></canvas>
            </div>
        </div>
        
        <div class="bg-white p-5 rounded-xl shadow-sm h-80 flex flex-col">
            <p class="text-[11px] font-bold text-gray-500 mb-2 uppercase tracking-wider font-poppins">Pengeluaran Per Kategori</p>
            <div class="relative flex-1 w-full h-full flex justify-center">
                <canvas id="chartPengeluaran"></canvas>
            </div>
        </div>
    </div>

    {{-- Audit & Aktivasi Akun (Bersebelahan) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Status Audit --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 lg:col-span-1 flex flex-col">
            <h3 class="text-[11px] font-bold text-gray-500 mb-5 uppercase tracking-wider font-poppins flex items-center gap-2">
                <x-heroicon-o-clipboard-document-check class="w-4 h-4 text-[#184D2E]" />
                Status Audit Internal
            </h3>
            <div class="flex flex-col gap-4 flex-1 justify-center">
                <div class="bg-white p-4 rounded-xl flex items-center justify-between border border-[#184D2E]/10 group hover:bg-[#184D2E]/5 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-[#184D2E]/10 text-[#184D2E] rounded-full flex items-center justify-center shrink-0">
                            <x-heroicon-s-check-circle class="w-6 h-6" />
                        </div>
                        <div>
                            <p class="text-xs font-bold text-[#184D2E] tracking-wider">LULUS</p>
                            <p class="text-[11px] text-gray-500 font-medium mt-0.5">Sudah Sesuai</p>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-[#184D2E] font-poppins">{{ $auditLulus }}</p>
                </div>
                
                <div class="bg-white p-4 rounded-xl flex items-center justify-between border border-[#D4AF37]/20 group hover:bg-[#D4AF37]/10 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-[#D4AF37]/20 text-[#b59223] rounded-full flex items-center justify-center shrink-0">
                            <x-heroicon-s-information-circle class="w-6 h-6" />
                        </div>
                        <div>
                            <p class="text-xs font-bold text-[#856b17] tracking-wider">PERBAIKAN</p>
                            <p class="text-[11px] text-gray-500 font-medium mt-0.5">Butuh tindak lanjut</p>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-[#856b17] font-poppins">{{ $auditPerbaikan }}</p>
                </div>

                <div class="bg-rose-50/50 p-4 rounded-xl flex items-center justify-between border border-rose-100/50 group hover:bg-rose-50 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center shrink-0">
                            <x-heroicon-s-exclamation-triangle class="w-6 h-6" />
                        </div>
                        <div>
                            <p class="text-xs font-bold text-rose-700 tracking-wider">PENDING</p>
                            <p class="text-[11px] text-gray-500 font-medium mt-0.5">Perlu Verifikasi</p>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-rose-700 font-poppins">{{ $auditPending }}</p>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-200 lg:col-span-2 flex flex-col">
            <div class="flex items-center gap-2 mb-4">
                <x-heroicon-o-user-plus class="w-5 h-5 text-[#184D2E]" />
                <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-wider font-poppins">Aktivasi Akun Petani</h3>
            </div>
            <div class="overflow-x-auto p-1 flex-1">
                <table id="tabelPetani" class="w-full text-left border-collapse display responsive nowrap">
                    <thead>
                        <tr class="bg-[#D4AF37] border-b border-[#B8860B] shadow-sm text-black">
                            <th class="p-4 text-[10px] font-bold text-black uppercase tracking-wider">No</th>
                            <th class="p-4 text-[10px] font-bold text-black uppercase tracking-wider">Nama</th>
                            <th class="p-4 text-[10px] font-bold text-black uppercase tracking-wider">Email</th>
                            <th class="p-4 text-[10px] font-bold text-black uppercase tracking-wider text-center">Status</th>
                            <th class="p-4 text-[10px] font-bold text-black uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($petaniPending as $index => $petani)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-4 text-xs text-gray-500 font-mono">{{ $index + 1 }}</td>
                            <td class="p-4 text-xs text-gray-800 font-semibold">{{ $petani->petani_nama }}</td>
                            <td class="p-4 text-xs text-gray-600">{{ $petani->petani_email ?? '-' }}</td>
                            <td class="p-4 text-center">
                                <span class="inline-block px-2.5 py-1 bg-amber-50 text-amber-700 rounded-md text-[11px] font-bold border border-amber-100">
                                    {{ $petani->petani_status }}
                                </span>
                            </td>
                            <td class="p-4 text-center whitespace-nowrap">
                                <div class="flex justify-center gap-2">
                                    <button type="button" title="Edit" class="p-1.5 bg-[#184D2E]/10 text-[#184D2E] hover:bg-[#184D2E] hover:text-white rounded-lg transition-colors border border-[#184D2E]/20"
                                            onclick="openEditModal('{{ $petani->petani_id }}', '{{ addslashes($petani->petani_nama) }}', '{{ $petani->petani_status }}', '{{ addslashes($petani->petani_email ?? '-') }}', '{{ addslashes($petani->petani_no_hp ?? '-') }}', '{{ addslashes($petani->petani_alamat ?? '-') }}', '{{ addslashes($petani->petani_jenis_kelamin ?? '-') }}', '{{ addslashes($petani->desa_nama ?? '-') }}')">
                                        <x-heroicon-o-pencil-square class="w-4 h-4" />
                                    </button>
                                    <button title="Hapus" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-colors border border-red-100">
                                        <x-heroicon-o-trash class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Map Section --}}
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-2 mb-2">
            <x-heroicon-o-map-pin class="w-4 h-4 text-gray-500" />
            <h3 class="text-[10px] font-bold text-gray-500 uppercase font-poppins">Sebaran Lahan Anggota</h3>
        </div>
        {{-- Container Peta Sebaran --}}
        <div id="mapSebaran" class="w-full h-96 rounded-lg bg-gray-100 relative border border-gray-200" style="z-index: 1;"></div>
    </div>

</div>

{{-- Modal Edit Status --}}
<div id="statusModal" class="fixed inset-0 z-50 hidden bg-black/40 items-center justify-center transition-opacity p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform" id="modalContent">
        
        <form id="formUbahStatus" method="POST" action="">
            @csrf
            @method('PUT')
            
            {{-- Header --}}
            <div class="px-5 py-4 flex justify-between items-center border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                    <x-heroicon-o-user-circle class="w-5 h-5 text-green-600" />
                    Aktivasi Akun Petani
                </h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <x-heroicon-o-x-mark class="w-5 h-5" />
                </button>
            </div>  
            
            <div class="p-5 space-y-4">
                
                {{-- Info Utama --}}
                <div class="flex items-center gap-3 bg-green-50/50 p-3 rounded-lg border border-green-100/50">
                    <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center shrink-0">
                        <x-heroicon-o-user class="w-5 h-5" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p id="modalNamaPetani" class="text-sm font-bold text-gray-900 truncate"></p>
                        <p id="modalEmailPetani" class="text-[11px] text-gray-500 truncate"></p>
                    </div>
                </div>

                {{-- Detail Info --}}
                <div class="bg-gray-50 rounded-lg p-3 text-xs border border-gray-100">
                    <div class="grid grid-cols-2 gap-y-3 gap-x-2">
                        <div>
                            <span class="block text-gray-400 font-medium mb-0.5 text-[10px] uppercase tracking-wider">No. HP</span>
                            <span id="modalHpPetani" class="text-gray-700 font-medium font-mono"></span>
                        </div>
                        <div>
                            <span class="block text-gray-400 font-medium mb-0.5 text-[10px] uppercase tracking-wider">Kelamin</span>
                            <span id="modalJkPetani" class="text-gray-700 font-medium"></span>
                        </div>
                        <div>
                            <span class="block text-gray-400 font-medium mb-0.5 text-[10px] uppercase tracking-wider">Desa</span>
                            <span id="modalDesaPetani" class="text-gray-700 font-medium truncate"></span>
                        </div>
                        <div>
                            <span class="block text-gray-400 font-medium mb-0.5 text-[10px] uppercase tracking-wider">Alamat</span>
                            <span id="modalAlamatPetani" class="text-gray-700 font-medium truncate block"></span>
                        </div>
                    </div>
                </div>
                
                {{-- Form Status --}}
                <div>
                    <label for="selectStatus" class="block text-xs font-bold text-gray-700 mb-1.5">Ubah Status</label>
                    <div class="relative">
                        <select id="selectStatus" name="petani_status" class="w-full border border-gray-200 rounded-lg pl-3 pr-8 py-2 text-sm focus:ring-2 focus:ring-green-500/20 focus:border-green-600 outline-none bg-white text-gray-700 appearance-none font-medium shadow-sm transition cursor-pointer">
                            <option value="Pending">Pending</option>
                            <option value="Aktif">Disetujui (Aktif)</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                            <x-heroicon-o-chevron-down class="w-4 h-4" />
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Footer Action --}}
            <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()" class="px-3 py-1.5 text-xs font-bold text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition">Batal</button>
                <button type="submit" class="px-4 py-1.5 text-xs font-bold text-white bg-[#214122] rounded-lg hover:bg-[#1b3d1b] transition shadow-sm focus:ring-2 focus:ring-[#214122]/30">Simpan Status</button>
            </div>
        </form>
    </div>
</div>

{{-- Script Inisialisasi Chart.js, DataTables & Leaflet --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

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
                                    <b>Luas Lahan:</b> ${lahan.lahan_luas || '0'} Ha<br>
                                    <b>Tahun Tanam:</b> ${lahan.tahun_tanam || '-'}<br>
                                    <b>No Surat:</b> ${lahan.lahan_no_surat || '-'}
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
        "lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        "order": [[ 1, "asc" ]], 
        "language": {
            "search": "",
            "searchPlaceholder": "Cari data...",
            "emptyTable": "Tidak ada data aktif untuk ditampilkan."
        },
        "responsive": {
            "details": {
                "renderer": function (api, rowIdx, columns) {
                    var data = $.map(columns, function (col) {
                        if (col.hidden) {
                            var value = col.data;
                            if (value === null || value === undefined || value === '') { value = '-'; }
                            return '<div class="flex items-start justify-between gap-3 py-1.5 text-xs leading-snug border-b border-gray-200 last:border-0"><span class="font-semibold text-gray-600">' + col.title + '</span><span class="text-gray-700 text-right">' + value + '</span></div>';
                        }
                        return '';
                    }).join('');
                    return data ? $('<div class="rounded-lg bg-gray-50 p-3 shadow-inner space-y-1 w-full mt-2"></div>').append(data).prop('outerHTML') : false;
                }
            }
        },
        "columnDefs": [
            { "orderable": false, "searchable": false, "targets": [4] },
            { "orderable": false, "targets": [0] },
            { "className": "all", "targets": [0, 1] }, 
            { "className": "min-tablet", "targets": [2, 3, 4] } 
        ],
        "dom": '<"flex justify-between items-center w-full mb-4 gap-2" l f> rt <"flex flex-col sm:flex-row justify-between items-center gap-4 mt-4" i p>'
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
    function openEditModal(id, nama, status, email, hp, alamat, jk, desa) {
        const modal = document.getElementById('statusModal');
        const form = document.getElementById('formUbahStatus');
        const namaText = document.getElementById('modalNamaPetani');
        const statusSelect = document.getElementById('selectStatus');
        
        document.getElementById('modalEmailPetani').innerText = email;
        document.getElementById('modalHpPetani').innerText = hp;
        document.getElementById('modalAlamatPetani').innerText = alamat;
        document.getElementById('modalAlamatPetani').title = alamat; // tooltip hover untuk alamat panjang
        document.getElementById('modalJkPetani').innerText = jk;
        document.getElementById('modalDesaPetani').innerText = desa;
        
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

<style>
    /* =========================================
       1. GLOBAL STYLES (TAMPILAN DESKTOP)
       ========================================= */
    .dataTables_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_filter label { display: inline-flex !important; align-items: center !important; gap: 0.5rem !important; font-size: 0.875rem !important; color: #374151 !important; margin: 0 !important; }
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input { font-size: 0.875rem !important; color: #374151 !important; border: 1px solid #e5e7eb !important; border-radius: 8px !important; padding: 4px 12px !important; margin: 0 !important; outline: none !important; }
    .dataTables_wrapper .dataTables_filter input { 
        padding-left: 32px !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239CA3AF'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'%3E%3C/path%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: 10px center !important;
        background-size: 16px 16px !important;
    }
    .dataTables_wrapper .dataTables_filter input:focus { border-color: #214122 !important; }

    #tabelPetani { width: 100% !important; }
    #tabelPetani th { white-space: nowrap !important; }

    .dataTables_wrapper .dataTables_info { font-size: 0.875rem !important; color: #6b7280 !important; padding-top: 0 !important; }
    .dataTables_wrapper .dataTables_info b, .dataTables_wrapper .dataTables_info strong { font-weight: 700 !important; color: #1f2937 !important; }

    .dataTables_wrapper .dataTables_paginate { padding-top: 0 !important; display: flex !important; gap: 0.25rem !important; align-items: center; }
    .dataTables_wrapper .dataTables_paginate .paginate_button { border: 1px solid #e5e7eb !important; background: #ffffff !important; color: #4b5563 !important; border-radius: 0.375rem !important; padding: 4px 12px !important; font-size: 0.875rem !important; transition: all 0.2s; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background: #214122 !important; color: #ffffff !important; border-color: #214122 !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #f3f4f6 !important; color: #1f2937 !important; border-color: #d1d5db !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover { color: #9ca3af !important; background: #f9fafb !important; border-color: #e5e7eb !important; cursor: not-allowed; }

    /* =========================================
       2. KHUSUS MODE HP (max-width: 640px)
       ========================================= */
    @media (max-width: 640px) {
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter { display: inline-block !important; margin: 0 !important; }
        .dataTables_wrapper .flex-row.items-center.justify-between { display: flex !important; flex-direction: row !important; justify-content: space-between !important; align-items: center !important; width: 100% !important; gap: 0.5rem !important; }

        .dataTables_wrapper .dataTables_length label { font-size: 0 !important; }
        .dataTables_wrapper .dataTables_length select { width: 70px !important; }
        .dataTables_wrapper .dataTables_filter input { width: 100% !important; max-width: 160px !important; font-size: 0.875rem !important; color: #374151 !important; }
        
        .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_paginate { width: 100%; justify-content: center; text-align: center; margin-top: 5px; }

        /* CUSTOM ICON PLUS (+) HANYA MUNCUL DI HP */
        table.dataTable.dtr-inline.collapsed > tbody > tr:not(.child) > td:first-child { position: relative; padding-left: 32px !important; cursor: pointer; }
        table.dataTable.dtr-inline.collapsed > tbody > tr:not(.child) > td:first-child::before { content: '+' !important; position: absolute; top: 50% !important; left: 8px !important; transform: translateY(-50%) !important; background-color: #234323 !important; color: white !important; width: 16px !important; height: 16px !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 9999px !important; font-weight: bold !important; font-size: 14px !important; line-height: 1 !important; box-shadow: 0 1px 2px rgba(0,0,0,0.2) !important; }
        table.dataTable.dtr-inline.collapsed > tbody > tr.parent > td:first-child::before { content: '-' !important; background-color: #dc2626 !important; }
    }
</style>
@endsection