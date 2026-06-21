<div class="p-2">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#214122]">Daftar Lahan</h1>
            <p class="text-sm text-gray-500">Daftar lahan spasial yang terdaftar di database.</p>
        </div>
        
        {{-- TOMBOL AKSI: Hanya Muncul Untuk Role Admin --}}
        @if(auth()->user()->user_role === 'admin')
        <div class="flex flex-wrap gap-3">
            {{-- FORM IMPORT FILE JSON DIRECT --}}
            <form action="{{ route('lahan.import_geojson') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 bg-gray-100 p-1.5 rounded-xl border border-gray-300 shadow-sm">
                @csrf
                <input type="file" name="geojson_file" accept=".json" required class="text-xs text-gray-600 max-w-45 file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300 cursor-pointer">
                <button type="submit" class="bg-[#214122] text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-green-900 active:scale-95 transition cursor-pointer">
                    Import JSON
                </button>
            </form>

            {{-- TOMBOL TAMBAH MANUAL --}}
            {{-- <a href="{{ route('lahan.create') }}" class="bg-[#214122] text-white text-sm font-semibold px-5 py-2 rounded-xl hover:bg-green-900 active:scale-95 transition shadow-sm flex items-center gap-2 cursor-pointer">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
                Tambah Lahan
            </a> --}}
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

    {{-- Container tempat tombol Export diletakkan --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
        <div id="exportButtonsContainer" class="flex gap-3"></div>
    </div>
    
    {{-- Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-200">
        <div class="overflow-x-auto">
            <table id="lahanTable" class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-[#D9F99D] border-b border-gray-200">
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center w-12">No</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">Lokasi Lahan</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center">Luas Lahan</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">Nama Pemilik/Petani</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($lahans as $lahan)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-xs text-justify text-gray-500 font-mono"></td>
                        <td class="p-4 text-xs text-gray-800 font-medium">{{ $lahan->lahan_lokasi }}</td>
                        <td class="p-4 text-xs text-gray-600 text-justify">{{ $lahan->lahan_luas }} Ha</td>
                        <td class="p-4 text-xs text-gray-600">
                            {{ $lahan->petani ? $lahan->petani->petani_nama : 'Tidak terikat petani' }}
                        </td>
                        <td class="p-4">
                            <div class="flex justify gap-3 items-center">
                                {{-- Detail Lahan (Bisa dilihat oleh Admin & Super Admin) --}}
                                <a href="{{ route('lahan.show', $lahan->lahan_id) }}" class="text-blue-600 hover:scale-110 transition" title="Lihat Peta / Detail">
                                    <x-heroicon-o-map-pin class="w-5 h-5" />
                                </a>
                                
                                {{-- Fitur Tambahan Aksi Khusus Admin (Edit & Hapus) --}}
                                @if(auth()->user()->user_role === 'admin')
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
</div>

{{-- DataTables CSS & JS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<script>
    $(document).ready(function() {
        window.JSZip = jszip = JSZip;
        if ($.fn.dataTable && $.fn.dataTable.Buttons) { $.fn.dataTable.Buttons.jszip(window.JSZip); }
        if ($.fn.DataTable.isDataTable('#lahanTable')) { $('#lahanTable').DataTable().destroy(); }

        // Format pembersih teks spasi kosong berlebih pada row data export
        var cleanExportFormat = {
            body: function (data, row, column, node) {
                if (column === 0) {
                    return row + 1;
                }
                if (node !== null) {
                    let text = node.textContent || node.innerText || "";
                    return text.replace(/\s+/g, ' ').trim();
                }
                return data;
            }
        };

        var table = $('#lahanTable').DataTable({
            "destroy": true,
            "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" },
            "pageLength": 10, 
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            "order": [[ 1, "asc" ]], 
            "columnDefs": [
                { "orderable": false, "targets": [0, 4] }, 
                { "searchable": false, "targets": [0, 4] }
            ],
            "buttons": [
                {
                    extend: 'excelHtml5',
                    text: '<svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg> Export ke Excel',
                    className: 'btn-export-excel',
                    title: 'Data_Lahan_NotaSawit',
                    exportOptions: { 
                        columns: [0, 1, 2, 3],
                        format: cleanExportFormat
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg> Export ke PDF',
                    className: 'btn-export-pdf',
                    title: 'LAPORAN DAFTAR LAHAN SPASIAL',
                    filename: 'Data_Lahan_NotaSawit',
                    orientation: 'portrait',
                    pageSize: 'A4',
                    exportOptions: { 
                        columns: [0, 1, 2, 3],
                        format: cleanExportFormat
                    },
                    customize: function (doc) {
                        doc.content[1].table.widths = ['10%', '35%', '20%', '35%'];

                        doc.styles.title = {
                            color: '#1e293b',
                            fontSize: '15',
                            alignment: 'center',
                            bold: true,
                            margin: [0, 0, 0, 20]
                        };

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
                            doc.content[1].table.body[j][3].alignment = 'left';
                            
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
                        objLayout['paddingLeft'] = function(i) { return 8; };
                        objLayout['paddingRight'] = function(i) { return 8; };
                        objLayout['paddingTop'] = function(i) { return 6; };
                        objLayout['paddingBottom'] = function(i) { return 6; };
                        doc.content[1].layout = objLayout;
                    }
                }
            ],
            // DOM diperbarui: info (i) di kiri bawah, pagination (p) di kanan bawah. Dibungkus utility flex responsif
            "dom": '<"flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-3"B <"flex items-center gap-4"l f>>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4"i p>'
        });

        // Loop penomoran baris otomatis yang kompatibel dengan pagination & filter data
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
    /* Custom CSS style DataTables global elements */
    .dataTables_wrapper .dataTables_filter input { border: 1px solid #e5e7eb !important; border-radius: 9999px !important; padding: 4px 12px !important; outline: none !important; }
    .dataTables_wrapper .dataTables_filter input:focus { border-color: #214122 !important; }
    .dataTables_wrapper .dataTables_length select { border: 1px solid #e5e7eb !important; border-radius: 8px !important; padding: 4px 24px 4px 8px !important; }
    
    .dt-buttons .btn-export-excel { background-color: transparent !important; border: 1px solid #10B981 !important; color: #047857 !important; border-radius: 0.5rem !important; padding: 0.5rem 1rem !important; font-weight: 600 !important; font-size: 0.875rem !important; transition: all 0.2s !important; box-shadow: none !important; }
    .dt-buttons .btn-export-excel:hover { background-color: #F0FDF4 !important; transform: scale(1.02); }
    .dt-buttons .btn-export-pdf { background-color: transparent !important; border: 1px solid #FFCACA !important; color: #DC2626 !important; border-radius: 0.5rem !important; padding: 0.5rem 1rem !important; font-weight: 600 !important; font-size: 0.875rem !important; transition: all 0.2s !important; box-shadow: none !important; }
    .dt-buttons .btn-export-pdf:hover { background-color: #FEF2F2 !important; transform: scale(1.02); }
    .dt-buttons { float: none !important; }

    /* Custom Styling Bagian Informasi Halaman (Kiri Bawah) */
    .dataTables_wrapper .dataTables_info {
        font-size: 0.875rem !important;
        color: #6b7280 !important;
        padding-top: 0 !important;
    }
    .dataTables_wrapper .dataTables_info b, 
    .dataTables_wrapper .dataTables_info strong {
        font-weight: 700 !important;
        color: #1f2937 !important;
    }

    /* Custom Styling Pagination Buttons (Kanan Bawah) */
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 0 !important;
        display: flex !important;
        gap: 0.25rem !important;
        align-items: center;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid #e5e7eb !important;
        background: #ffffff !important;
        color: #4b5563 !important;
        border-radius: 0.375rem !important;
        padding: 4px 12px !important;
        font-size: 0.875rem !important;
        transition: all 0.2s;
    }
    /* Tombol Halaman Aktif (Biru Cerah) */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #214122 !important;
        color: #ffffff !important;
        border-color: #214122 !important;
    }
    /* State Hover tombol biasa */
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f3f4f6 !important;
        color: #1f2937 !important;
        border-color: #d1d5db !important;
    }
    /* State Disabled ketika tombol navigasi mati */
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        color: #9ca3af !important;
        background: #f9fafb !important;
        border-color: #e5e7eb !important;
        cursor: not-allowed;
    }
</style>