<div class="p-2">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#214122]">Daftar Lahan</h1>
            <p class="text-sm text-gray-500">Daftar lahan spasial yang terdaftar di database.</p>
        </div>
        
        {{-- TOMBOL AKSI: Muncul Untuk Role Admin dan Super Admin --}}
        @if(in_array(auth()->user()->user_role, ['admin', 'super_admin']))
        <div class="flex flex-wrap gap-3">
            {{-- FORM IMPORT FILE JSON DIRECT --}}
            <form action="{{ route('lahan.preview_import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 bg-gray-100 p-1.5 rounded-xl border border-gray-300 shadow-sm">
                @csrf
                <input type="file" name="geojson_file" accept=".json" required class="text-xs text-gray-600 max-w-45 file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300 cursor-pointer">
                <button type="submit" class="bg-[#214122] text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-green-900 active:scale-95 transition cursor-pointer">
                    Import JSON
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- Alert Notification Sukses --}}
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative text-sm flex items-center shadow-sm" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Alert Notification Error Gagal Import --}}
    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative text-sm flex items-center shadow-sm" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if(session('gagal_mapping') && count(session('gagal_mapping')) > 0)
    <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded-xl">
        <strong>Beberapa petani tidak ditemukan:</strong>
        <ul class="mt-2 list-disc pl-5">
            @foreach(session('gagal_mapping') as $item)
                <li>{{ $item['nama_json'] }} ({{ $item['desa'] }})</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Container tempat tombol Export --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
        <div id="exportButtonsContainer" class="flex flex-wrap items-center w-full sm:w-auto gap-2 sm:gap-3"></div>
    </div>
    
    {{-- Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm p-3 sm:p-4 border border-gray-200">
        <table id="lahanTable" class="w-full text-left border-collapse display responsive nowrap" style="width: 100%">
            <thead>
                <tr class="bg-[#D4AF37] border-b border-[#B8860B] shadow-sm text-black">
                    <th class="p-4 text-xs font-extrabold uppercase text-center w-12 rounded-tl-lg tracking-wider">No</th>
                    <th class="p-4 text-xs font-extrabold uppercase text-left tracking-wider">Nama Pemilik/Petani</th>
                    <th class="p-4 text-xs font-extrabold uppercase text-center tracking-wider">Luas Lahan</th>
                    <th class="p-4 text-xs font-extrabold uppercase text-center tracking-wider">Tahun Tanam</th>
                    <th class="p-4 text-xs font-extrabold uppercase text-left tracking-wider">No Surat</th>
                    <th class="p-4 text-xs font-extrabold uppercase text-left tracking-wider">Lokasi Lahan</th>
                    <th class="p-4 text-xs font-extrabold uppercase text-center rounded-tr-lg tracking-wider">Aksi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($lahans as $lahan)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-xs text-justify text-gray-500 font-mono"></td>
                        <td class="p-4 text-xs text-gray-800 font-medium">
                            {{ $lahan->petani ? $lahan->petani->petani_nama : 'Tidak terikat petani' }}
                        </td>
                        <td class="p-4 text-xs text-gray-600 text-justify">{{ $lahan->lahan_luas }} Ha</td>
                        <td class="p-4 text-xs text-gray-600 text-justify">{{ $lahan->tahun_tanam ?? '-' }}</td>
                        <td class="p-4 text-xs text-gray-600">{{ $lahan->lahan_no_surat ?? '-' }}</td>
                        <td class="p-4 text-xs text-gray-600">{{ $lahan->lahan_lokasi }}</td>
                        <td class="p-4">
                            <div class="flex text-justify gap-3 items-justify">
                                <a href="{{ route('lahan.show', $lahan->lahan_id) }}" class="text-blue-600 hover:scale-110 transition" title="Lihat Peta / Detail">
                                    <x-heroicon-o-map-pin class="w-5 h-5" />
                                </a>
                                
                                @if(in_array(auth()->user()->user_role, ['admin', 'super_admin']))
                                <a href="{{ route('lahan.edit', $lahan->lahan_id) }}" class="text-green-700 hover:scale-110 transition" title="Edit Lahan">
                                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                                </a>
                                
                                <form action="{{ route('lahan.destroy', $lahan->lahan_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data lahan ini?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:scale-110 transition cursor-pointer" title="Hapus Lahan">
                                        <x-heroicon-o-trash class="w-5 h-5" />
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
</div>

{{-- DataTables CSS & JS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
    $(document).ready(function() {
        window.JSZip = jszip = JSZip;
        if ($.fn.dataTable && $.fn.dataTable.Buttons) { $.fn.dataTable.Buttons.jszip(window.JSZip); }
        if ($.fn.DataTable.isDataTable('#lahanTable')) { $('#lahanTable').DataTable().destroy(); }

        var cleanExportFormat = {
            body: function (data, row, column, node) {
                if (column === 0) { return row + 1; }
                if (node !== null) {
                    let text = node.textContent || node.innerText || "";
                    return text.replace(/\s+/g, ' ').trim();
                }
                return data;
            }
        };

        var table = $('#lahanTable').DataTable({
            "destroy": true,
            "pageLength": 10, 
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "language": {
                "search": "",
                "searchPlaceholder": "Search..."
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
            "order": [[ 1, "asc" ]], 
            "columnDefs": [
                { "orderable": false, "searchable": false, "targets": [0, 6] },
                { "className": "text-center all", "targets": 0 }, // No wajib tampil di HP
                { "className": "all", "targets": 1 },            // Nama Pemilik wajib tampil di HP
                { "className": "min-tablet", "targets": [2, 3, 4, 5, 6] } // Sisanya disembunyikan di HP
            ],
            "buttons": [
                {
                    extend: 'excelHtml5',
                    text: '<div class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg><span>Export Excel</span></div>',
                    className: 'btn-export-excel',
                    title: 'Data_Lahan_SILAUSA',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5], format: cleanExportFormat }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<div class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg><span>Export PDF</span></div>',
                    className: 'btn-export-pdf',
                    title: 'LAPORAN DAFTAR LAHAN SPASIAL',
                    filename: 'Data_Lahan_SILAUSA',
                    orientation: 'portrait',
                    pageSize: 'A4',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5], format: cleanExportFormat },
                    customize: function (doc) {
                        doc.content[1].table.widths = ['5%', '25%', '15%', '15%', '20%', '20%'];
                        doc.styles.title = { color: '#1e293b', fontSize: '15', alignment: 'center', bold: true, margin: [0, 0, 0, 20] };
                        doc.content[1].table.headerRows = 1;
                        var rowCount = doc.content[1].table.body.length;
                        
                        for (var i = 0; i < doc.content[1].table.body[0].length; i++) {
                            doc.content[1].table.body[0][i].fillColor = '#214122';
                            doc.content[1].table.body[0][i].color = 'white';
                            doc.content[1].table.body[0][i].alignment = 'center';
                            doc.content[1].table.body[0][i].bold = true;
                        }
                        for (var j = 1; j < rowCount; j++) {
                            doc.content[1].table.body[j][0].alignment = 'center';
                            doc.content[1].table.body[j][1].alignment = 'left';
                            doc.content[1].table.body[j][2].alignment = 'center';
                            doc.content[1].table.body[j][3].alignment = 'center';
                            doc.content[1].table.body[j][4].alignment = 'left';
                            doc.content[1].table.body[j][5].alignment = 'left';
                            if (j % 2 === 0) {
                                for (var k = 0; k < doc.content[1].table.body[j].length; k++) { doc.content[1].table.body[j][k].fillColor = '#f8fafc'; }
                            }
                        }
                        var objLayout = {};
                        objLayout['hLineWidth'] = function(i) { return .5; };
                        objLayout['vLineWidth'] = function(i) { return .5; };
                        objLayout['hLineColor'] = function(i) { return '#cbd5e1'; };
                        objLayout['vLineColor'] = function(i) { return '#cbd5e1'; };
                        objLayout['paddingLeft'] = function(i) { return 8; };
                        objLayout['paddingRight'] = function(i) { return 8; };
                        objLayout['paddingTop'] = function(i) { return 6; };
                        objLayout['paddingBottom'] = function(i) { return 6; };
                        doc.content[1].layout = objLayout;
                    }
                }
            ],
            "dom": '<"hidden" B> <"flex justify-between items-center w-full mb-4 gap-2" l f> <"overflow-x-auto w-full" tr> <"flex flex-col sm:flex-row justify-between items-center gap-4 mt-4" i p>'
        });

        table.on('order.dt search.dt draw.dt', function () {
            let start = table.page.info().start;
            table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function(cell, i) {
                cell.innerHTML = start + i + 1;
            });
        }).draw();

        table.buttons().container().appendTo('#exportButtonsContainer');
    });
</script>

<style>
    /* =========================================
       1. GLOBAL STYLES (TAMPILAN DESKTOP)
       ========================================= */
    .dt-buttons { display: flex; flex-wrap: wrap; gap: 0.5rem; width: 100%; }
    .dt-buttons .dt-button { background-color: transparent !important; border-radius: 0.5rem !important; padding: 0.4rem 0.8rem !important; font-weight: 600 !important; font-size: 0.8rem !important; transition: all 0.2s !important; border: none; }
    .btn-export-excel { border: 1px solid #10B981 !important; color: #047857 !important; }
    .btn-export-excel:hover { background-color: #F0FDF4 !important; transform: scale(1.02); }
    .btn-export-pdf { border: 1px solid #FCA5A5 !important; color: #DC2626 !important; }
    .btn-export-pdf:hover { background-color: #FEF2F2 !important; transform: scale(1.02); }

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

    #lahanTable th, #lahanTable td { white-space: nowrap !important; }

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
        
        .dt-buttons { display: flex !important; flex-direction: row !important; }
        .dt-buttons .dt-button { flex: 1; display: flex; justify-content: center; }
        .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_paginate { width: 100%; justify-content: center; text-align: center; margin-top: 5px; }

        /* CUSTOM ICON PLUS (+) HANYA MUNCUL DI HP */
        table.dataTable.dtr-inline.collapsed > tbody > tr:not(.child) > td:first-child { position: relative; padding-left: 32px !important; cursor: pointer; }
        table.dataTable.dtr-inline.collapsed > tbody > tr:not(.child) > td:first-child::before { content: '+' !important; position: absolute; top: 50% !important; left: 8px !important; transform: translateY(-50%) !important; background-color: #234323 !important; color: white !important; width: 16px !important; height: 16px !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 9999px !important; font-weight: bold !important; font-size: 14px !important; line-height: 1 !important; box-shadow: 0 1px 2px rgba(0,0,0,0.2) !important; }
        table.dataTable.dtr-inline.collapsed > tbody > tr.parent > td:first-child::before { content: '-' !important; background-color: #dc2626 !important; }
    }
</style>