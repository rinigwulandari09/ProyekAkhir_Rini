<div class="p-2">
    {{-- Header Section --}}
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#214122]">Daftar Kunjungan Lapangan</h1>
            <p class="text-sm text-gray-500">Data hasil kunjungan lapangan oleh auditor.</p>
        </div>
    </div>

    {{-- Container tempat tombol Export dan Filter --}}
    <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-3 mb-4">
        <div id="exportButtonsContainer" class="flex flex-wrap items-center w-full xl:w-auto gap-2"></div>

        <form action="{{ route('audit.index') }}" method="GET" class="flex flex-wrap items-center gap-2 w-full xl:w-auto">
            <input type="hidden" name="tab" value="kunjungan">
            
            <select name="dari_bulan" class="bg-white border-gray-200 text-gray-700 rounded-lg py-2 pl-3 pr-8 text-xs font-medium focus:ring-2 focus:ring-[#234323]/20 focus:border-[#234323] hover:border-[#234323] hover:shadow-md transition-all duration-300 shadow-sm cursor-pointer outline-none">
                <option value="">Dari Bln</option>
                @for ($m=1; $m<=12; $m++)
                    <option value="{{ $m }}" {{ request('dari_bulan') == $m && request('tab') == 'kunjungan' ? 'selected' : '' }}>{{ date('M', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
            </select>

            <select name="sampai_bulan" class="bg-white border-gray-200 text-gray-700 rounded-lg py-2 pl-3 pr-8 text-xs font-medium focus:ring-2 focus:ring-[#234323]/20 focus:border-[#234323] hover:border-[#234323] hover:shadow-md transition-all duration-300 shadow-sm cursor-pointer outline-none">
                <option value="">Sampai Bln</option>
                @for ($m=1; $m<=12; $m++)
                    <option value="{{ $m }}" {{ request('sampai_bulan') == $m && request('tab') == 'kunjungan' ? 'selected' : '' }}>{{ date('M', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
            </select>

            <select name="tahun" class="bg-white border-gray-200 text-gray-700 rounded-lg py-2 pl-3 pr-8 text-xs font-medium focus:ring-2 focus:ring-[#234323]/20 focus:border-[#234323] hover:border-[#234323] hover:shadow-md transition-all duration-300 shadow-sm cursor-pointer outline-none">
                <option value="">Tahun</option>
                @php $currentYear = date('Y'); @endphp
                @for ($y = $currentYear + 2; $y >= 2023; $y--)
                    <option value="{{ $y }}" {{ request('tahun') == $y && request('tab') == 'kunjungan' ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>

            <select name="status" class="bg-white border-gray-200 text-gray-700 rounded-lg py-2 pl-3 pr-8 text-xs font-medium focus:ring-2 focus:ring-[#234323]/20 focus:border-[#234323] hover:border-[#234323] hover:shadow-md transition-all duration-300 shadow-sm cursor-pointer outline-none">
                <option value="">Semua Status</option>
                <option value="Menunggu Konfirmasi" {{ request('status') == 'Menunggu Konfirmasi' && request('tab') == 'kunjungan' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                <option value="Lulus" {{ request('status') == 'Lulus' && request('tab') == 'kunjungan' ? 'selected' : '' }}>Lulus</option>
                <option value="Perlu Perbaikan" {{ request('status') == 'Perlu Perbaikan' && request('tab') == 'kunjungan' ? 'selected' : '' }}>Perlu Perbaikan</option>
            </select>

            <button type="submit" class="bg-[#214122] text-white px-4 py-2 rounded-lg text-xs font-bold tracking-wide hover:bg-[#1a331a] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 shadow-sm">
                Filter
            </button>
            
            @if(request()->hasAny(['dari_bulan', 'sampai_bulan', 'tahun', 'status']) && request('tab') == 'kunjungan')
                <a href="{{ route('audit.index', ['tab' => 'kunjungan']) }}" class="bg-white text-gray-700 border border-gray-200 px-4 py-2 rounded-lg text-xs font-bold tracking-wide hover:bg-gray-50 hover:border-gray-300 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 shadow-sm">
                    Reset
                </a>
            @endif
        </form>
    </div>
    
    {{-- Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm p-3 sm:p-4 border border-gray-200">
        <table id="kunjunganTable" class="w-full text-left border-collapse display responsive nowrap" style="width: 100%">
            <thead>
                <tr class="bg-[#D4AF37] border-b border-[#B8860B] shadow-sm text-black">
                    <th class="p-4 text-xs font-extrabold uppercase text-center w-12 rounded-tl-lg tracking-wider">No</th>
                    <th class="p-4 text-xs font-extrabold uppercase text-left tracking-wider">Tanggal Kunjungan</th>
                    <th class="p-4 text-xs font-extrabold uppercase text-left tracking-wider">Desa Kebun</th>
                    <th class="p-4 text-xs font-extrabold uppercase text-left tracking-wider">Desa Kepengurusan</th>
                    <th class="p-4 text-xs font-extrabold uppercase text-left tracking-wider">Nama Auditor</th>
                    <th class="p-4 text-xs font-extrabold uppercase text-left tracking-wider">Status</th>
                    <th class="p-4 text-xs font-extrabold uppercase text-left tracking-wider">Keterangan</th>
                    <th class="p-4 text-xs font-extrabold uppercase text-center rounded-tr-lg tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                    @foreach($kunjungan as $k)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-xs text-center text-gray-500 font-mono"></td>
                        <td class="p-4 text-xs text-gray-800 font-medium">
                            {{ $k->tanggal_kunjungan ? date('Y-m-d', strtotime($k->tanggal_kunjungan)) : '-' }}
                        </td>
                        <td class="p-4 text-xs text-gray-600">{{ $k->desa_kebun ?? '-' }}</td>
                        <td class="p-4 text-xs text-gray-800">{{ $k->desa_kepengurusan ?? '-' }}</td>
                        <td class="p-4 text-xs text-gray-500">{{ $k->nama_auditor ?? '-' }}</td>
                        <td class="p-4 text-xs">
                            @php
                                $displayStatusK = $k->status ?: 'Menunggu Konfirmasi';
                                $statusColorK = match(strtolower($displayStatusK)) {
                                    'disetujui', 'lolos', 'lulus', 'selesai' => 'bg-green-50 text-green-700 border-green-200',
                                    'ditolak', 'tidak lolos', 'gagal', 'perlu perbaikan' => 'bg-red-50 text-red-700 border-red-200',
                                    'proses', 'pending', 'menunggu', 'menunggu konfirmasi' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                    default => 'bg-gray-50 text-gray-700 border-gray-200'
                                };
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $statusColorK }} whitespace-nowrap">
                                {{ $displayStatusK }}
                            </span>
                        </td>
                        <td class="p-4 text-xs text-gray-600 max-w-xs truncate" title="{{ $k->keterangan }}">{{ $k->keterangan ?? '-' }}</td>
                        <td class="p-4">
                            <div class="flex justify-center gap-2">
                                <button type="button" onclick="openDetailModal('{{ md5($k->id_kunjungan) }}')" class="inline-flex items-center gap-1.5 bg-[#234323] text-white px-3 py-1.5 rounded-lg text-[10px] font-bold hover:bg-[#1a331a] hover:shadow-md transition whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                    Tinjau & Verifikasi
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
</div>

{{-- DataTables CSS & JS Libraries --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
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
        if ($.fn.DataTable.isDataTable('#kunjunganTable')) { $('#kunjunganTable').DataTable().destroy(); }

        var cleanExportFormat = {
            body: function (data, row, column, node) {
                if (column === 0) return row + 1;
                if (node !== null) {
                    let text = node.textContent || node.innerText || "";
                    return text.replace(/\s+/g, ' ').trim();
                }
                return data;
            }
        };

        var table = $('#kunjunganTable').DataTable({
            "destroy": true,
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
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
            "order": [[ 1, "desc" ]],
            "columnDefs": [ 
                { "orderable": false, "searchable": false, "targets": [0, 7] },
                { "className": "text-center all", "targets": 0 }, 
                { "className": "all", "targets": 1 }, 
                { "className": "min-tablet", "targets": [2, 3, 4, 5, 6, 7] } 
            ],
            "buttons": [
                {
                    extend: 'excelHtml5',
                    text: '<div class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg><span>Export Excel</span></div>',
                    className: 'btn-export-excel',
                    title: 'Data_Kunjungan_Lapangan',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6], format: cleanExportFormat }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<div class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg><span>Export PDF</span></div>',
                    className: 'btn-export-pdf',
                    title: 'LAPORAN DAFTAR KUNJUNGAN LAPANGAN',
                    filename: 'Data_Kunjungan_Lapangan',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6], format: cleanExportFormat },
                    customize: function (doc) {
                        doc.content[1].table.widths = ['auto', '*', '*', '*', '*', '*', '*'];
                        doc.styles.tableHeader.alignment = 'center';
                        if (doc.content[0]) {
                            doc.content[0].alignment = 'center';
                        }
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
    
    .dt-buttons .dt-button {
        background-color: transparent !important;
        border-radius: 0.5rem !important;
        padding: 0.4rem 0.8rem !important;
        font-weight: 600 !important;
        font-size: 0.8rem !important;
        transition: all 0.2s !important;
        border: none;
    }
    .btn-export-excel { border: 1px solid #10B981 !important; color: #047857 !important; }
    .btn-export-excel:hover { background-color: #F0FDF4 !important; transform: scale(1.02); }
    
    .btn-export-pdf { border: 1px solid #FCA5A5 !important; color: #DC2626 !important; }
    .btn-export-pdf:hover { background-color: #FEF2F2 !important; transform: scale(1.02); }

    .dataTables_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_filter label { 
        display: inline-flex !important; 
        align-items: center !important; 
        gap: 0.5rem !important; 
        font-size: 0.875rem !important; 
        color: #374151 !important; 
        margin: 0 !important; 
    }
    
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input { 
        font-size: 0.875rem !important; 
        color: #374151 !important; 
        border: 1px solid #e5e7eb !important; 
        border-radius: 8px !important; 
        padding: 4px 12px !important; 
        margin: 0 !important; 
        outline: none !important; 
    }
    .dataTables_wrapper .dataTables_filter input { 
        padding-left: 32px !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239CA3AF'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'%3E%3C/path%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: 10px center !important;
        background-size: 16px 16px !important;
    }
    .dataTables_wrapper .dataTables_filter input:focus { border-color: #214122 !important; }

    /* =========================================
   2. KHUSUS MODE HP (max-width: 640px)
   ========================================= */
    @media (max-width: 640px) {
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            display: inline-block !important;
            margin: 0 !important;
        }

        .dataTables_wrapper .flex-row.items-center.justify-between {
            display: flex !important;
            flex-direction: row !important;
            justify-content: space-between !important;
            align-items: center !important;
            width: 100% !important;
            gap: 0.5rem !important;
        }

        .dataTables_wrapper .dataTables_length label { font-size: 0 !important; }
        .dataTables_wrapper .dataTables_length select { width: 70px !important; }
        
        .dataTables_wrapper .dataTables_filter input { 
            width: 100% !important;
            max-width: 160px !important;
            font-size: 0.875rem !important;
            color: #374151 !important;
        }

        .dt-buttons { display: flex !important; flex-direction: row !important; width: 100% !important; }
        .dt-buttons .dt-button { flex: 1; display: flex; justify-content: center; }

        .dataTables_wrapper .dataTables_info, 
        .dataTables_wrapper .dataTables_paginate { width: 100%; justify-content: center; text-align: center; margin-top: 5px; }

        table.dataTable.dtr-inline.collapsed > tbody > tr:not(.child) > td:first-child { position: relative; padding-left: 32px !important; cursor: pointer; }
        table.dataTable.dtr-inline.collapsed > tbody > tr:not(.child) > td:first-child::before {
            content: '+' !important; position: absolute; top: 50% !important; left: 8px !important; transform: translateY(-50%) !important;
            background-color: #234323 !important; color: white !important; width: 16px !important; height: 16px !important;
            display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 9999px !important;
            font-weight: bold !important; font-size: 14px !important; line-height: 1 !important; box-shadow: 0 1px 2px rgba(0,0,0,0.2) !important;
        }
        table.dataTable.dtr-inline.collapsed > tbody > tr.parent > td:first-child::before { content: '-' !important; background-color: #dc2626 !important; }
    }

    /* =========================================
       3. STYLE PAGINATION
       ========================================= */
    .dataTables_wrapper .dataTables_info { font-size: 0.875rem !important; color: #6b7280 !important; padding-top: 0 !important; }
    .dataTables_wrapper .dataTables_info b, .dataTables_wrapper .dataTables_info strong { font-weight: 700 !important; color: #1f2937 !important; }
    .dataTables_wrapper .dataTables_paginate { padding-top: 0 !important; display: flex !important; gap: 0.25rem !important; align-items: center; justify-content: center; }
    .dataTables_wrapper .dataTables_paginate .paginate_button { border: 1px solid #e5e7eb !important; background: #ffffff !important; color: #4b5563 !important; border-radius: 0.375rem !important; padding: 4px 12px !important; font-size: 0.875rem !important; transition: all 0.2s; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background: #214122 !important; color: #ffffff !important; border-color: #214122 !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #f3f4f6 !important; color: #1f2937 !important; border-color: #d1d5db !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover { color: #9ca3af !important; background: #f9fafb !important; border-color: #e5e7eb !important; cursor: not-allowed; }
</style>

{{-- Modal Detail & Update Status Kunjungan --}}
@foreach($kunjungan as $k)
    <div id="detailModal-{{ md5($k->id_kunjungan) }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-black/50" aria-hidden="true" onclick="closeDetailModal('{{ md5($k->id_kunjungan) }}')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-xl sm:w-full z-10">
                
                {{-- Header --}}
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-extrabold text-gray-900">Kelola Kunjungan Lapangan</h3>
                        <p class="text-xs text-gray-500 mt-1">Tinjau detail kunjungan dan verifikasi status.</p>
                    </div>
                    <button type="button" onclick="closeDetailModal('{{ md5($k->id_kunjungan) }}')" class="text-gray-400 hover:text-red-500 transition-colors p-2 rounded-full hover:bg-red-50">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="p-6">
                    <div class="flex flex-col gap-5">
                        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-1 h-full bg-[#EAB308]"></div>
                            <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#EAB308]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Detail Kunjungan
                            </h4>
                            
                            <div class="mb-4 space-y-2">
                                <div class="flex justify-between text-xs border-b border-dashed pb-2">
                                    <span class="text-gray-500">Tanggal</span>
                                    <span class="font-semibold text-gray-800">{{ $k->tanggal_kunjungan ? date('Y-m-d', strtotime($k->tanggal_kunjungan)) : '-' }}</span>
                                </div>
                                <div class="flex justify-between text-xs border-b border-dashed pb-2">
                                    <span class="text-gray-500">Desa Kebun</span>
                                    <span class="font-semibold text-gray-800">{{ $k->desa_kebun ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between text-xs border-b border-dashed pb-2">
                                    <span class="text-gray-500">Desa Kepengurusan</span>
                                    <span class="font-semibold text-gray-800">{{ $k->desa_kepengurusan ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between text-xs border-b border-dashed pb-2">
                                    <span class="text-gray-500">Auditor</span>
                                    <span class="font-semibold text-gray-800">{{ $k->nama_auditor ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between text-xs border-b border-dashed pb-2">
                                    <span class="text-gray-500">File Bukti</span>
                                    @if($k->path_file_kunjungan)
                                        <a href="{{ Storage::url(str_replace(['storage/', 'public/'], '', $k->path_file_kunjungan)) }}" target="_blank" class="text-blue-600 font-semibold hover:underline flex items-center gap-1">
                                            Lihat PDF <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </a>
                                    @else
                                        <span class="text-gray-400 italic">Tidak ada</span>
                                    @endif
                                </div>
                            </div>

                            <form action="/audit/kunjungan/{{ $k->id_kunjungan }}/status" method="POST" class="mt-5 border-t pt-4">
                                @csrf
                                @method('PUT')
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Ubah Status</label>
                                    <select name="status" onchange="toggleKeteranganFieldDalamModal(this, '{{ md5($k->id_kunjungan) }}')" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-sm focus:ring-[#234323] focus:border-[#234323]">
                                        <option value="" disabled {{ !$k->status ? 'selected' : '' }} hidden>Pilih Status...</option>
                                        <option value="Lulus" {{ $k->status == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                                        <option value="Perlu Perbaikan" {{ in_array($k->status, ['Perlu Perbaikan', 'Tidak Lolos', 'Gagal']) ? 'selected' : '' }}>Perlu Perbaikan</option>
                                    </select>
                                </div>
                                <div id="ket-container-{{ md5($k->id_kunjungan) }}" class="mb-4 {{ in_array($k->status, ['Perlu Perbaikan', 'Ditolak', 'Tidak Lolos', 'Gagal']) ? '' : 'hidden' }}">
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Alasan / Keterangan</label>
                                    <textarea name="keterangan" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-sm focus:ring-[#234323] focus:border-[#234323]" placeholder="Tulis catatan di sini...">{{ $k->keterangan }}</textarea>
                                </div>
                                <button type="submit" class="w-full bg-[#234323] text-white py-2.5 rounded-lg text-sm font-bold shadow-md hover:bg-[#1a331a] hover:shadow-lg transition flex justify-center items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Simpan Keputusan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script>
    function toggleKeteranganFieldDalamModal(selectElement, id) {
        const container = document.getElementById('ket-container-' + id);
        if (selectElement.value === 'Perlu Perbaikan' || selectElement.value === 'Ditolak') {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }

    function openDetailModal(id) {
        document.getElementById('detailModal-' + id).classList.remove('hidden');
    }

    function closeDetailModal(id) {
        document.getElementById('detailModal-' + id).classList.add('hidden');
    }
</script>
