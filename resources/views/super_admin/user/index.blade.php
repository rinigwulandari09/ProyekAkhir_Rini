@extends('layouts.dashboard')

@section('title', 'Daftar User')

@section('content')
<div class="p-2">
    {{-- Header Section --}}
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#214122]">Manajemen User</h1>
            <p class="text-sm text-gray-500">Daftar Pengguna yang Terdaftar</p>
        </div>
        
        <a href="{{ route('user.create') }}" class="bg-[#214122] text-white px-4 py-2.5 rounded-lg inline-flex items-center gap-2 hover:bg-green-900 transition shadow-md font-semibold text-sm">
            <x-heroicon-o-user-plus class="w-5 h-5" />
            Tambah User
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative text-sm flex items-center shadow-sm" role="alert">
            <x-heroicon-o-check-circle class="w-5 h-5 mr-2" />
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Container tempat tombol Export --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
        <div id="exportButtonsContainer" class="flex flex-wrap items-center w-full sm:w-auto gap-2 sm:gap-3"></div>
    </div>
    
    {{-- Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm p-3 sm:p-4 border border-gray-200">
        <div class="overflow-x-auto">
            <table id="userTable" class="w-full text-left border-collapse display responsive nowrap">
                <thead>
                    <tr class="bg-[#D9F99D] border-b border-gray-200">
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center w-12">No</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">Nama</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">Username</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">Email</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">Desa Tugas</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center">Role</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        {{-- 1. No --}}
                        <td class="p-4 text-xs text-center text-gray-500 font-mono"></td>
                        
                        {{-- 2. Nama --}}
                        <td class="p-4 text-xs text-gray-800 font-medium">{{ $user->user_nama }}</td>
                        
                        {{-- 3. Username --}}
                        <td class="p-4 text-xs text-gray-500 font-mono">{{ $user->user_username }}</td>
                        
                        {{-- 4. Email --}}
                        <td class="p-4 text-xs text-gray-600">{{ $user->user_email ?? '-' }}</td>
                        
                        {{-- 5. Desa --}}
                        <td class="p-4 text-xs text-gray-600">{{ $user->desa->desa_nama ?? '-' }} </td>
                        
                        {{-- 6. Role --}}
                        <td class="p-4 text-justify-center">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $user->user_role == 'super_admin' ? 'bg-purple-100 text-purple-700 border border-purple-200' : 'bg-blue-100 text-blue-700 border border-blue-200' }}">
                                {{ str_replace('_', ' ', $user->user_role) }}
                            </span>
                        </td>
                        
                        {{-- 7. Aksi --}}
                        <td class="p-4">
                            <div class="flex justify gap-3">
                                <a href="{{ route('user.edit', $user->user_id) }}" class="text-green-700 hover:scale-110 transition">
                                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                                </a>
                                <form action="{{ route('user.destroy', $user->user_id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:scale-110 transition">
                                        <x-heroicon-o-trash class="w-5 h-5" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-10 text-center text-gray-400 italic text-xs">
                            Belum ada data user di database.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- DataTables CSS & JS Libraries (Termasuk modul Responsive) --}}
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
        if ($.fn.DataTable.isDataTable('#userTable')) { $('#userTable').DataTable().destroy(); }

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

        var table = $('#userTable').DataTable({
            "destroy": true,
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            "language": {
                "search": "",                    // Menghapus teks "Search" di luar kotak
                "searchPlaceholder": "Search..." // Memasukkan tulisan "Search..." ke dalam kotak input
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
                { "className": "text-center all", "targets": 0 }, 
                { "className": "all", "targets": 1 }, 
                { "className": "min-tablet", "targets": [2, 3, 4, 5, 6] } 
            ],
            "buttons": [
                {
                    extend: 'excelHtml5',
                    text: '<div class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg><span>Export Excel</span></div>',
                    className: 'btn-export-excel',
                    title: 'Data_User_NotaSawit',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5], format: cleanExportFormat }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<div class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg><span>Export PDF</span></div>',
                    className: 'btn-export-pdf',
                    title: 'LAPORAN DAFTAR PENGGUNA (ADMIN DAN SUPER ADMIN)',
                    filename: 'Data_User_NotaSawit',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5], format: cleanExportFormat }
                }
            ],
            "dom": '<"flex flex-col md:flex-row md:items-center gap-4 mb-4" <"flex items-center gap-3" B l> <"ml-auto" f> > rt <"flex flex-col sm:flex-row justify-between items-center gap-4 mt-4" i p>'
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

    /* MENGATUR LABEL NORMAL DI DESKTOP */
    .dataTables_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_filter label { 
        display: inline-flex !important; 
        align-items: center !important; 
        gap: 0.5rem !important; 
        font-size: 0.875rem !important; 
        color: #374151 !important; 
        margin: 0 !important; 
    }
    
    /* STYLING INPUT & SELECT */
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
    .dataTables_wrapper .dataTables_filter input:focus { border-color: #214122 !important; }

    #userTable th, #userTable td { white-space: normal !important; word-break: break-word; }
    #userTable th { white-space: nowrap; }

    /* =========================================
   2. KHUSUS MODE HP (max-width: 640px)
   ========================================= */
    @media (max-width: 640px) {
        /* 1. Membuat Entries dan Search Sejajar Kesamping */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            display: inline-block !important;
            margin: 0 !important;
        }

        /* Pembungkus utama Entries & Search kita buat flex sejajar */
        .dataTables_wrapper .flex-row.items-center.justify-between {
            display: flex !important;
            flex-direction: row !important;
            justify-content: space-between !important;
            align-items: center !important;
            width: 100% !important;
            gap: 0.5rem !important;
        }

        /* 2. Menghilangkan text label luar yang bikin sempit */
        .dataTables_wrapper .dataTables_length label {
            font-size: 0 !important;
        }
        
        /* 3. Menyesuaikan ukuran lebar input di HP agar pas */
        .dataTables_wrapper .dataTables_length select {
            width: 70px !important;
        }
        
        .dataTables_wrapper .dataTables_filter input { 
            width: 100% !important;
            max-width: 160px !important;
            font-size: 0.875rem !important;
            color: #374151 !important;
        }

        /* 4. Tombol Export Jadi 2 sejajar di HP */
        .dt-buttons { 
            display: flex !important; 
            flex-direction: row !important; 
            width: 100% !important;
        }
        .dt-buttons .dt-button { 
            flex: 1; 
            display: flex; 
            justify-content: center; 
        }

        /* Info & Pagination tetap di tengah */
        .dataTables_wrapper .dataTables_info, 
        .dataTables_wrapper .dataTables_paginate { 
            width: 100%; 
            justify-content: center; 
            text-align: center; 
            margin-top: 5px; 
        }

        /* CUSTOM ICON PLUS (+) HANYA MUNCUL DI HP */
        table.dataTable.dtr-inline.collapsed > tbody > tr > td:first-child {
            position: relative;
            padding-left: 32px !important;
            cursor: pointer;
        }
        table.dataTable.dtr-inline.collapsed > tbody > tr > td:first-child::before {
            content: '+' !important;
            position: absolute;
            top: 50% !important;
            left: 8px !important;
            transform: translateY(-50%) !important;
            background-color: #10B981 !important;
            color: white !important;
            width: 16px !important;
            height: 16px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 9999px !important;
            font-weight: bold !important;
            font-size: 14px !important;
            line-height: 1 !important;
            box-shadow: 0 1px 2px rgba(0,0,0,0.2) !important;
        }
    }

    /* =========================================
       3. STYLE PAGINATION
       ========================================= */
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
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #214122 !important;
        color: #ffffff !important;
        border-color: #214122 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f3f4f6 !important;
        color: #1f2937 !important;
        border-color: #d1d5db !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        color: #9ca3af !important;
        background: #f9fafb !important;
        border-color: #e5e7eb !important;
        cursor: not-allowed;
    }
</style>
@endsection