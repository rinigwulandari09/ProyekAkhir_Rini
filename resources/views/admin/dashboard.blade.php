@extends('layouts.admin')

@section('title', 'Beranda')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-6">
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
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">
        {{-- Status Audit (1 Kolom) --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 h-full flex flex-col col-span-1">
            <h3 class="text-xs font-bold text-gray-500 mb-5 uppercase tracking-widest font-poppins flex items-center gap-2">
                <x-heroicon-o-clipboard-document-check class="w-4 h-4" />
                Status Audit Internal
            </h3>
            <div class="flex flex-col gap-4 flex-1 justify-center">
                <div class="bg-emerald-50/50 p-4 rounded-xl flex items-center justify-between border border-emerald-100/50 group hover:bg-emerald-50 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center shrink-0">
                            <x-heroicon-s-check-circle class="w-6 h-6" />
                        </div>
                        <div>
                            <p class="text-xs font-bold text-emerald-700 tracking-wider">LULUS</p>
                            <p class="text-sm text-gray-500 font-medium">Sudah Sesuai</p>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-emerald-700 font-poppins">{{ $auditLulus }}</p>
                </div>
                
                <div class="bg-amber-50/50 p-4 rounded-xl flex items-center justify-between border border-amber-100/50 group hover:bg-amber-50 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center shrink-0">
                            <x-heroicon-s-information-circle class="w-6 h-6" />
                        </div>
                        <div>
                            <p class="text-xs font-bold text-amber-700 tracking-wider"> PERLU PERBAIKAN</p>
                            <p class="text-sm text-gray-500 font-medium">Butuh tindak lanjut</p>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-amber-700 font-poppins">{{ $auditPerbaikan }}</p>
                </div>

                <div class="bg-rose-50/50 p-4 rounded-xl flex items-center justify-between border border-rose-100/50 group hover:bg-rose-50 transition">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center shrink-0">
                            <x-heroicon-s-exclamation-triangle class="w-6 h-6" />
                        </div>
                        <div>
                            <p class="text-xs font-bold text-rose-700 tracking-wider">PENDING</p>
                            <p class="text-sm text-gray-500 font-medium">Perlu Verifikasi</p>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-rose-700 font-poppins">{{ $auditPending }}</p>
                </div>
            </div>
        </div>

        {{-- Pengingat Tugas (2 Kolom) --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 col-span-1 xl:col-span-2 h-full flex flex-col">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5 pb-4 border-b border-gray-50">
                <div>
                    <h3 class="text-sm font-bold text-gray-800 font-poppins flex items-center gap-2">
                        <x-heroicon-o-bell-alert class="w-5 h-5 text-amber-500" />
                        Pengingat Tugas
                    </h3>
                    <p class="text-[11px] text-gray-500 font-poppins mt-1">Tugas dari Superadmin diurutkan berdasarkan deadline terdekat.</p>
                </div>
            </div>

            <div class="overflow-x-auto flex-1">
                <table id="tugasTable" class="w-full text-left border-collapse display responsive">
                    <thead>
                        <tr class="bg-[#D4AF37] border-b border-[#B8860B] shadow-sm text-black">
                            <th class="p-4 text-[10px] font-bold text-black uppercase tracking-wider rounded-l-xl">Judul</th>
                            <th class="p-4 text-[10px] font-bold text-black uppercase tracking-wider">Pesan</th>
                            <th class="p-4 text-[10px] font-bold text-black uppercase tracking-wider">Deadline</th>
                            <th class="p-4 text-[10px] font-bold text-black uppercase tracking-wider text-center rounded-r-xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($taskNotifications as $task)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-4 text-xs text-gray-800 font-semibold">{{ $task->judul }}</td>
                                <td class="p-4 text-xs text-gray-600 whitespace-normal break-words min-w-[150px]">{{ $task->pesan }}</td>
                                <td class="p-4">
                                    <span class="inline-block whitespace-nowrap px-2.5 py-1 bg-amber-50 text-amber-700 rounded-md text-[11px] font-bold border border-amber-100">
                                        {{ $task->deadline ? date('d/m/Y', strtotime($task->deadline)) : '-' }}
                                    </span>
                                </td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    <form action="{{ route('tugas.complete', ['id' => $task->id]) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menandai tugas ini sebagai selesai?')">
                                        @csrf
                                        <button type="submit" class="inline-flex whitespace-nowrap items-center gap-1.5 rounded-lg bg-[#234323] px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-[#1b3d1b] cursor-pointer shadow-sm active:scale-95">
                                            <x-heroicon-s-check class="w-3.5 h-3.5" />
                                            Selesai
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400 text-sm">Belum ada pengingat tugas dari Superadmin.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Kalender Tugas --}}
    <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-200 mb-6">
        <div class="flex items-center gap-2 mb-4">
            <x-heroicon-o-calendar class="w-5 h-5 text-[#234323]" />
            <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wider font-poppins">Kalender Audit & Pengingat</h2>
        </div>
        <div id="tugasCalendar" class="w-full bg-gray-50 rounded-xl border border-gray-100 p-2 sm:p-4"></div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-2 mb-2">
            <x-heroicon-o-map-pin class="w-4 h-4 text-gray-500" />
            <h3 class="text-[10px] font-bold text-gray-500 uppercase font-poppins">Sebaran Lahan Anggota</h3>
        </div>
        <div id="mapSebaran" class="w-full h-96 rounded-lg bg-gray-100 relative border border-gray-200" style="z-index: 1;"></div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if ($.fn.DataTable.isDataTable('#tugasTable')) { $('#tugasTable').DataTable().destroy(); }
        $('#tugasTable').DataTable({
            "pageLength": 5, 
            "lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
            "language": {
                "search": "",
                "searchPlaceholder": "Cari tugas...",
                "emptyTable": "Tidak ada tugas aktif untuk ditampilkan."
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
                { "orderable": false, "searchable": false, "targets": [3] },
                { "className": "all", "targets": 0 }, 
                { "className": "min-tablet", "targets": [1, 2, 3] } 
            ],
            "dom": '<"flex justify-between items-center w-full mb-4 gap-2" l f> rt <"flex flex-col sm:flex-row justify-between items-center gap-4 mt-4" i p>'
        });
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

        const mapSebaran = L.map('mapSebaran', { minZoom: 3, maxZoom: 19 }).setView([0.65, 101.85], 13);
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

                        const isValidWGS84 = polyCoords.every(coord => coord[0] > -5 && coord[0] < 10 && coord[1] > 95 && coord[1] < 140);

                        if (isValidWGS84 && polyCoords.length > 0) {
                            const polygon = L.polygon(polyCoords, {
                                color: '#234323',
                                fillColor: '#234323',
                                fillOpacity: 0.4,
                                weight: 2.5
                            });

                            polygon.bindPopup(`
                                <div style="font-family: sans-serif; font-size: 12px; min-width: 170px;">
                                    <strong style="color: #234323; font-size: 13px;">Detail Lahan Spasial</strong><br>
                                    <hr style="margin: 4px 0; border: 0; border-top: 1px solid #e5e7eb;">
                                    <b>Nama Pemilik:</b> ${lahan.petani_nama || '-'}<br>
                                    <b>Lokasi Lahan:</b> ${lahan.lahan_lokasi || '-'}<br>
                                    <b>Luas Hamparan:</b> ${lahan.lahan_luas || '0'} Ha<br>
                                    <b>Tahun Tanam:</b> ${lahan.tahun_tanam || '-'}<br>
                                    <b>No Surat:</b> ${lahan.lahan_no_surat || '-'}
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

        if (polygonGroup.getLayers().length > 0) {
            mapSebaran.fitBounds(polygonGroup.getBounds(), { padding: [40, 40] });
        }
    });
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

    #tugasTable th, #tugasTable td { white-space: nowrap !important; }

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

    /* =========================================
       3. KALENDER CUSTOM CSS
       ========================================= */
    .fc { font-family: inherit !important; font-size: 0.85rem !important; }
    .fc-theme-standard td, .fc-theme-standard th { border-color: #f3f4f6 !important; }
    .fc-col-header-cell { background-color: #f9fafb; padding: 6px 0 !important; font-weight: 600; font-size: 0.75rem !important; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb !important; }
    .fc-daygrid-day-number { color: #4b5563; font-weight: 600; font-size: 0.8rem !important; padding: 4px 8px !important; }
    .fc-day-today { background-color: #f0fdf4 !important; }
    .fc-daygrid-event { border-radius: 4px !important; padding: 2px 6px !important; font-size: 0.7rem !important; border: none !important; font-weight: 600 !important; transition: transform 0.2s; cursor: pointer; margin: 1px 2px !important; }
    .fc-daygrid-event:hover { transform: scale(1.02); opacity: 0.9; }
    .fc-event-title { font-weight: 600 !important; }
    
    /* Toolbar & Buttons */
    .fc-toolbar-title { font-size: 1.1rem !important; font-weight: 700 !important; color: #1f2937 !important; }
    .fc-toolbar-chunk { display: flex; align-items: center; gap: 0.5rem; }
    .fc-button-group { display: flex; gap: 0.25rem; }
    
    /* Inactive Button Style (Ghost/Outline) */
    .fc-button-primary { 
        background-color: #ffffff !important; 
        color: #374151 !important;
        border: 1px solid #d1d5db !important; 
        border-radius: 6px !important; 
        text-transform: capitalize !important; 
        font-weight: 600 !important; 
        font-size: 0.75rem !important;
        padding: 4px 10px !important;
        transition: all 0.2s !important; 
        box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
    }
    .fc-button-primary:hover { 
        background-color: #f3f4f6 !important; 
        border-color: #9ca3af !important; 
        color: #1f2937 !important;
    }
    /* Active Button Style (Filled Dark Green) */
    .fc-button-primary:not(:disabled):active, 
    .fc-button-primary:not(:disabled).fc-button-active { 
        background-color: #234323 !important; 
        border-color: #234323 !important; 
        color: #ffffff !important;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.1) !important;
    }
    
    @media (max-width: 640px) {
        .fc-toolbar { flex-direction: column; gap: 0.75rem; align-items: center; }
        .fc-toolbar-chunk { display: flex; justify-content: center; width: 100%; flex-wrap: wrap; gap: 0.5rem; }
        .fc-toolbar-title { font-size: 1.1rem !important; text-align: center; width: 100%; }
        .fc-button { padding: 0.3rem 0.6rem !important; font-size: 0.75rem !important; }
        .fc-header-toolbar { margin-bottom: 1rem !important; }
    }
</style>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var calendarEl = document.getElementById('tugasCalendar');
        var kalenderEvents = {!! $kalenderEvents ?? '[]' !!};

        if(calendarEl) {
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: window.innerWidth < 768 ? 'listMonth' : 'dayGridMonth',
                contentHeight: 'auto', // Membuat kalender tidak terlalu tinggi (menyesuaikan isi)
                aspectRatio: 1.5, // Membuat rasio lebih proporsional (tidak terlalu kotak besar)
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listMonth'
                },
                events: kalenderEvents,
                locale: 'id',
                buttonText: {
                    today:    'Hari Ini',
                    month:    'Bulan',
                    week:     'Minggu',
                    day:      'Hari',
                    list:     'Daftar'
                },
                eventClick: function(info) {
                    var isDone = info.event.extendedProps.status === 'Selesai';
                    var statusHtml = isDone 
                        ? '<span style="color:#059669;font-weight:bold;">Selesai</span>'
                        : '<span style="color:#DC2626;font-weight:bold;">Pending</span>';
                    
                    alert(
                        "Tugas: " + info.event.title + "\n" +
                        "Deskripsi: " + (info.event.extendedProps.description || '-') + "\n" +
                        "Status: " + info.event.extendedProps.status
                    );
                }
            });
            calendar.render();

            // Re-render when resizing to handle mobile/desktop views better
            window.addEventListener('resize', function() {
                if (window.innerWidth < 768 && calendar.view.type !== 'listMonth') {
                    calendar.changeView('listMonth');
                } else if (window.innerWidth >= 768 && calendar.view.type === 'listMonth') {
                    calendar.changeView('dayGridMonth');
                }
            });
        }
    });
</script>
@endsection
