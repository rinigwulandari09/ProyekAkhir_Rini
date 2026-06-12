@extends('layouts.dashboard')

@section('title', 'Daftar Petani')

@section('content')
<div class="p-2">
    {{-- Header Section --}}
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#214122]">Daftar Petani</h1>
            <p class="text-sm text-gray-500">Daftar seluruh petani sawit yang terdaftar di Supabase.</p>
        </div>
    </div>

    {{-- Container tempat tombol Export diletakkan --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
        <div id="exportButtonsContainer" class="flex gap-3"></div>
    </div>
    
    {{-- Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-200">
        <div class="overflow-x-auto">
            <table id="petaniTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#D9F99D] border-b border-gray-200">
                        {{-- 1. Tambah Header Kolom No --}}
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center w-12">No</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">Nama</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">Email</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center">Status</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    {{-- 2. Tambahkan $loop->iteration untuk membuat nomor otomatis dari Laravel --}}
                    @foreach($petani as $p)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-xs text-center text-gray-500 font-mono">{{ $loop->iteration }}</td>
                        <td class="p-4 text-xs text-gray-800 font-medium">{{ $p->petani_nama }}</td>
                        <td class="p-4 text-xs text-gray-500">{{ $p->petani_email }}</td>
                        <td class="p-4 text-center">
                            @if($p->petani_status == 'Aktif')
                                <span class="bg-[#DCFCE7] text-[#166534] px-3 py-1 rounded-full text-[10px] font-bold">Aktif</span>
                            @else
                                <span class="bg-[#FEF3C7] text-[#92400E] px-3 py-1 rounded-full text-[10px] font-bold">Pending</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-3">
                                <a href="{{ route('petani.show', $p->petani_id) }}" class="text-green-700 hover:scale-110 transition">
                                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                                </a>

                                <form action="{{ route('petani.destroy', $p->petani_id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:scale-110 transition">
                                        <x-heroicon-o-trash class="w-5 h-5" />
                                    </button>
                                </form>
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

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<script>
    $(document).ready(function() {
        window.JSZip = jszip = JSZip;

        if ($.fn.dataTable && $.fn.dataTable.Buttons) {
            $.fn.dataTable.Buttons.jszip(window.JSZip);
        }

        if ($.fn.DataTable.isDataTable('#petaniTable')) {
            $('#petaniTable').DataTable().destroy();
        }

        var table = $('#petaniTable').DataTable({
            "destroy": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            "order": [[ 1, "asc" ]], // Urutkan berdasarkan Nama (Sekarang bergeser ke indeks ke-1)
            "columnDefs": [
                // 3. Matikan fitur sorting untuk kolom No (indeks 0) dan kolom Aksi (indeks 4)
                { "orderable": false, "targets": [0, 4] } 
            ],
            "buttons": [
                {
                    extend: 'excelHtml5',
                    text: '<svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg> Export ke Excel',
                    className: 'btn-export-excel',
                    title: 'Data_Petani_NotaSawit',
                    exportOptions: {
                        // 4. Ekspor kolom No (0), Nama (1), Email (2), dan Status (3)
                        columns: [0, 1, 2, 3] 
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg> Export ke PDF',
                    className: 'btn-export-pdf',
                    title: 'Data_Petani_NotaSawit',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    }
                }
            ],
            "dom": '<"flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-3"B <"flex items-center gap-4"lf>>rtip'
        });

        table.buttons().container().appendTo('#exportButtonsContainer');
    });
</script>

<style>
    /* Kustomisasi Tampilan DataTables agar serasi dengan Tailwind */
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e5e7eb !important;
        border-radius: 9999px !important;
        padding: 4px 12px !important;
        outline: none !important;
    }
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #e5e7eb !important;
        border-radius: 8px !important;
        padding: 4px 24px 4px 8px !important;
        background-position: right 8px center !important;
    }
    table.dataTable thead th {
        border-bottom: 1px solid #e5e7eb !important;
    }

    /* KUSTOMISASI TOMBOL EXCEL (HIJAU) */
    .dt-buttons .btn-export-excel {
        background-color: transparent !important;
        border: 1px solid #10B981 !important;
        color: #047857 !important;
        border-radius: 0.5rem !important;
        padding: 0.5rem 1rem !important;
        font-weight: 600 !important;
        font-size: 0.875rem !important;
        transition: all 0.2s !important;
        box-shadow: none !important;
    }
    .dt-buttons .btn-export-excel:hover {
        background-color: #F0FDF4 !important;
        transform: scale(1.02);
    }

    /* KUSTOMISASI TOMBOL PDF (MERAH) */
    .dt-buttons .btn-export-pdf {
        background-color: transparent !important;
        border: 1px solid #FCA5A5 !important;
        color: #DC2626 !important;
        border-radius: 0.5rem !important;
        padding: 0.5rem 1rem !important;
        font-weight: 600 !important;
        font-size: 0.875rem !important;
        transition: all 0.2s !important;
        box-shadow: none !important;
    }
    .dt-buttons .btn-export-pdf:hover {
        background-color: #FEF2F2 !important;
        transform: scale(1.02);
    }
    
    .dt-buttons {
        float: none !important;
    }
</style>
@endsection