<div class="p-2">
    {{-- Header Section --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#214122]">Daftar Keuangan Petani</h1>
        <p class="text-sm text-gray-500 mt-1">Ringkasan aktivitas pemasukan (produksi) dan pengeluaran (operasional) seluruh petani.</p>
    </div>
    
    {{-- Cards Summary Section --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        {{-- Card Pemasukan --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition duration-300">
            <div class="bg-green-100 p-3.5 rounded-xl text-green-600 flex items-center justify-center shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307L21.75 6.75M21.75 6.75H16.5M21.75 6.75v5.25"></path></svg>
            </div>
            <div class="flex-1">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Pemasukan</p>
                <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalPemasukanseluruh, 0, ',', '.') }}</p>
            </div>
        </div>
        
        {{-- Card Pengeluaran --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition duration-300">
            <div class="bg-red-100 p-3.5 rounded-xl text-red-500 flex items-center justify-center shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6L9 12.75l4.306-4.307L21.75 17.25M21.75 17.25H16.5M21.75 17.25v-5.25"></path></svg>
            </div>
            <div class="flex-1">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Pengeluaran</p>
                <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalPengeluaranSeluruh, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Filter Bar Modern --}}
    <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6 border border-gray-200 mb-6">
        {{-- Header Mini Filter --}}
        <div class="flex items-center gap-2 mb-4 text-gray-700">
            <x-heroicon-o-funnel class="w-4 h-4 text-[#214122]" />
            <h2 class="text-xs font-bold uppercase tracking-wider text-gray-600">Filter Pencarian Data</h2>
        </div>

        <form action="#" method="GET" class="space-y-4">
            {{-- BARIS FILTER DROPDOWN --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- Dropdown 1 --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-gray-500 pl-1">Dari Bulan</label>
                    <div class="relative">
                        <select name="dari_bulan" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl px-3.5 py-2.5 outline-none focus:border-[#214122] focus:bg-white transition appearance-none cursor-pointer">
                            <option value="">Pilih Bulan</option>
                            @for ($m=1; $m<=12; $m++)
                                <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                            @endfor
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-gray-400">
                            <x-heroicon-o-chevron-down class="w-4 h-4" />
                        </div>
                    </div>
                </div>

                {{-- Dropdown 2 --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-gray-500 pl-1">Sampai Bulan</label>
                    <div class="relative">
                        <select name="sampai_bulan" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl px-3.5 py-2.5 outline-none focus:border-[#214122] focus:bg-white transition appearance-none cursor-pointer">
                            <option value="">Pilih Bulan</option>
                            @for ($m=1; $m<=12; $m++)
                                <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                            @endfor
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-gray-400">
                            <x-heroicon-o-chevron-down class="w-4 h-4" />
                        </div>
                    </div>
                </div>

                {{-- Dropdown 3 --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-gray-500 pl-1">Tahun</label>
                    <div class="relative">
                        <select name="tahun" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl px-3.5 py-2.5 outline-none focus:border-[#214122] focus:bg-white transition appearance-none cursor-pointer">
                            <option value="">Pilih Tahun</option>
                            <option value="2026">2026</option>
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-gray-400">
                            <x-heroicon-o-chevron-down class="w-4 h-4" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- BARIS TOMBOL AKSI --}}
            <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4 pt-4 border-t border-gray-100">
                <button type="submit" class="w-full sm:w-auto bg-[#214122] text-white px-6 py-2.5 rounded-xl font-semibold text-sm hover:bg-green-900 transition shadow-sm text-center flex items-center justify-center gap-2">
                    <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                    Terapkan Filter
                </button>
                <div id="exportButtonsContainer" class="w-full sm:w-auto flex justify-start sm:justify-end"></div>
            </div>
        </form>
    </div>
        
    {{-- Table Card (PERBAIKAN: Mengganti padding p-3 menjadi p-4 sm:p-6 dan menghapus overflow-x-auto) --}}
    <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6 border border-gray-200 mb-6">
        <table id="keuanganTable" class="w-full text-left border-collapse display responsive nowrap">
            <thead>
                <tr class="bg-[#D9F99D] border-b border-gray-200">
                    <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center w-12">No</th>
                    <th class="p-4 text-xs font-bold text-gray-700 uppercase">Nama Petani</th>
                    <th class="p-4 text-xs font-bold text-gray-700 uppercase text-right">Total Pemasukan (Produksi)</th>
                    <th class="p-4 text-xs font-bold text-gray-700 uppercase text-right">Total Pengeluaran (Operasional)</th>
                    <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($petanis as $petani)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 text-xs text-center text-gray-500 font-mono"></td>
                    <td class="p-4 text-xs text-gray-800 font-medium">
                        {{ $petani->petani_nama }}
                    </td>
                    <td class="p-4 text-xs text-green-600 font-bold text-right pr-6">
                        Rp {{ number_format($petani->total_masuk ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="p-4 text-xs text-red-500 font-medium text-right pr-6">
                        Rp {{ number_format($petani->total_keluar ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="p-4">
                        <div class="flex justify-center gap-3">
                            <a href="{{ route('keuangan.show', $petani->petani_id) }}" class="text-green-700 hover:scale-110 transition" title="Lihat Detail Transaksi">
                                <x-heroicon-o-pencil-square class="w-5 h-5" />
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Assets Library DataTables --}}
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
        window.JSZip = JSZip;
        
        if ($.fn.DataTable.isDataTable('#keuanganTable')) { 
            $('#keuanganTable').DataTable().destroy(); 
        }

        var exportFormatHandler = {
            body: function (data, row, column, node) {
                if (column === 0) { return row + 1; }
                if (node !== null && (column === 1 || column === 2 || column === 3)) {
                    let plainText = node.textContent || node.innerText || "";
                    return plainText.replace(/\s+/g, ' ').trim();
                }
                return data;
            }
        };

        var table = $('#keuanganTable').DataTable({
            "destroy": true,
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "order": [], 
            "columnDefs": [ 
                { "orderable": false, "searchable": false, "targets": [0, 4] },
                { "className": "text-center all", "targets": 0 }, 
                { "className": "all", "targets": 1 },            
                { "className": "min-tablet", "targets": [2, 3, 4] } 
            ],
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
            "buttons": [
                {
                    extend: 'excelHtml5',
                    text: '<div class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg><span>Excel</span></div>',
                    className: 'btn-export-excel',
                    title: 'Laporan_Keuangan_Petani_NotaSawit',
                    exportOptions: { columns: [0, 1, 2, 3], format: exportFormatHandler }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<div class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg><span>PDF</span></div>',
                    className: 'btn-export-pdf',
                    title: 'LAPORAN KEUANGAN PETANI',
                    filename: 'Data_Keuangan_Petani_NotaSawit',
                    orientation: 'portrait',
                    pageSize: 'A4',
                    exportOptions: { columns: [0, 1, 2, 3], format: exportFormatHandler },
                    customize: function (doc) {
                        doc.content[1].table.widths = ['8%', '42%', '25%', '25%'];
                        doc.styles.title = { color: '#214122', fontSize: '15', alignment: 'center', bold: true, margin: [0, 0, 0, 20] };
                        doc.styles.tableBodyNormal = { fontSize: 8.5 };
                        doc.styles.tableHeader = { fontSize: 8.5, bold: true };
                        doc.content[1].table.headerRows = 1;
                        var rowCount = doc.content[1].table.body.length;
                        
                        for (var i = 0; i < doc.content[1].table.body[0].length; i++) {
                            doc.content[1].table.body[0][i].fillColor = '#214122';
                            doc.content[1].table.body[0][i].color = 'white';
                            doc.content[1].table.body[0][i].alignment = 'center';
                            doc.content[1].table.body[0][i].fontSize = 8.5;
                        }
                        for (var j = 1; j < rowCount; j++) {
                            doc.content[1].table.body[j][0].alignment = 'center';
                            doc.content[1].table.body[j][1].alignment = 'left';
                            doc.content[1].table.body[j][2].alignment = 'right';
                            doc.content[1].table.body[j][3].alignment = 'right';
                            doc.content[1].table.body[j][0].text = j;
                            for (var c = 0; c < doc.content[1].table.body[j].length; c++) {
                                doc.content[1].table.body[j][c].fontSize = 8.5;
                            }
                            if (j % 2 === 0) {
                                for (var k = 0; k < doc.content[1].table.body[j].length; k++) {
                                    doc.content[1].table.body[j][k].fillColor = '#f8fafc';
                                }
                            }
                        }
                        var objLayout = {};
                        objLayout['hLineWidth'] = function(i) { return .5; };
                        objLayout['vLineWidth'] = function(i) { return .5; };
                        objLayout['hLineColor'] = function(i) { return '#cbd5e1'; };
                        objLayout['vLineColor'] = function(i) { return '#cbd5e1'; };
                        objLayout['paddingLeft'] = function(i) { return 6; };
                        objLayout['paddingRight'] = function(i) { return 6; };
                        objLayout['paddingTop'] = function(i) { return 6; };
                        objLayout['paddingBottom'] = function(i) { return 6; };
                        doc.content[1].layout = objLayout;
                    }
                }
            ],
            // DOM Layout: Length di kiri, Search di kanan (sejajar di semua device)
            "dom": '<"hidden" B> <"flex justify-between items-center w-full mb-4 gap-2" l f> rt <"flex flex-col sm:flex-row justify-between items-center gap-4 mt-4 pt-4 border-t border-gray-100" i p>'
        });

        table.on('draw.dt', function () {
            let info = table.page.info();
            if (info.recordsTotal > 0) {
                table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function(cell, i) {
                    if (cell) { cell.innerHTML = info.start + i + 1; }
                });
            }
        });

        table.draw();
        table.buttons().container().appendTo('#exportButtonsContainer');
    });
</script>

<style>
    /* =========================================
       1. GLOBAL STYLES (TAMPILAN DESKTOP)
       ========================================= */
    .dt-buttons { display: flex; flex-wrap: nowrap; gap: 0.5rem; align-items: center; }
    .dt-buttons .dt-button { background-color: transparent !important; border-radius: 0.5rem !important; padding: 0.5rem 1rem !important; font-weight: 600 !important; font-size: 0.875rem !important; transition: all 0.2s !important; cursor: pointer; box-shadow: none !important; margin: 0 !important; }
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

    #keuanganTable th, #keuanganTable td { white-space: normal !important; word-break: break-word; }
    #keuanganTable th { white-space: nowrap; }

    .dataTables_wrapper .dataTables_info { font-size: 0.875rem !important; color: #4b5563 !important; padding-top: 0 !important; }
    .dataTables_wrapper .dataTables_paginate { padding-top: 0 !important; display: flex !important; gap: 0.25rem !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button { border: 1px solid #d1d5db !important; border-radius: 0.375rem !important; padding: 0.375rem 0.75rem !important; margin-left: 0 !important; font-size: 0.875rem !important; background: #ffffff !important; color: #374151 !important; transition: all 0.2s; }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #f3f4f6 !important; color: #111827 !important; border-color: #9ca3af !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background: #214122 !important; color: #ffffff !important; border-color: #214122 !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover { background: #f9fafb !important; color: #9ca3af !important; border-color: #e5e7eb !important; cursor: not-allowed !important; }

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
        
        .dataTables_wrapper .dataTables_info, 
        .dataTables_wrapper .dataTables_paginate { width: 100%; justify-content: center; text-align: center; margin-top: 5px; }

        #exportButtonsContainer { width: 100% !important; }
        .dt-buttons { width: 100% !important; display: flex !important; flex-direction: row !important; }
        .dt-buttons .dt-button { flex: 1 !important; display: flex !important; justify-content: center !important; text-align: center !important; }

        table.dataTable.dtr-inline.collapsed > tbody > tr:not(.child) > td:first-child { position: relative; padding-left: 32px !important; cursor: pointer; }
        table.dataTable.dtr-inline.collapsed > tbody > tr:not(.child) > td:first-child::before { content: '+' !important; position: absolute; top: 50% !important; left: 8px !important; transform: translateY(-50%) !important; background-color: #234323 !important; color: white !important; width: 16px !important; height: 16px !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 9999px !important; font-weight: bold !important; font-size: 14px !important; line-height: 1 !important; box-shadow: 0 1px 2px rgba(0,0,0,0.2) !important; }
        table.dataTable.dtr-inline.collapsed > tbody > tr.parent > td:first-child::before { content: '-' !important; background-color: #dc2626 !important; }
    }
</style>