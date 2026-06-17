<div class="p-2">
    {{-- Header Section --}}
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#214122]">Daftar Petani</h1>
            <p class="text-sm text-gray-500">Daftar seluruh petani sawit yang terdaftar.</p>
        </div>
    </div>

    {{-- Container tempat tombol Export --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
        <div id="exportButtonsContainer" class="flex gap-3"></div>
    </div>
    
    {{-- Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-200">
        <div class="overflow-x-auto">
            <table id="petaniTable" class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-[#D9F99D] border-b border-gray-200">
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center w-12">No</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">Nama</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">Username</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">No. HP</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">Email</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">Gender</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">Tgl Lahir</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">Desa</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">Alamat</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center">Status</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($petani as $p)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-xs text-center text-gray-500 font-mono"></td>
                        <td class="p-4 text-xs text-gray-800 font-medium">{{ $p->petani_nama }}</td>
                        <td class="p-4 text-xs text-gray-600 font-mono">@ {{ $p->petani_username ?? '-' }}</td>
                        <td class="p-4 text-xs text-gray-800">{{ $p->petani_no_hp ?? '-' }}</td>
                        <td class="p-4 text-xs text-gray-500">{{ $p->petani_email }}</td>
                        <td class="p-4 text-xs text-gray-600">{{ $p->petani_jenis_kelamin ?? '-' }}</td>
                        <td class="p-4 text-xs text-gray-600">
                            @php
                                try {
                                    echo \Carbon\Carbon::createFromFormat('d/m/Y', $p->petani_tanggal_lahir)->translatedFormat('d M Y');
                                } catch (\Exception $e) {
                                    echo $p->petani_tanggal_lahir ?? '-';
                                }
                            @endphp
                        </td>
                        <td class="p-4 text-xs text-gray-800 font-medium">{{ $p->desa->desa_nama ?? '-' }}</td>
                        <td class="p-4 text-xs text-gray-500 max-w-xs truncate">{{ $p->petani_alamat ?? '-' }}</td>
                        <td class="p-4 text-center">
                            @if($p->petani_status == 'Aktif')
                                <span class="bg-[#DCFCE7] text-[#166534] px-3 py-1 rounded-full text-[10px] font-bold">Aktif</span>
                            @elseif($p->petani_status == 'Ditolak')
                                <span class="bg-[#FEE2E2] text-[#991B1B] px-3 py-1 rounded-full text-[10px] font-bold">Ditolak</span>
                            @elseif($p->petani_status == 'Nonaktif')
                                <span class="bg-[#FEE2E2] text-[#991B1B] px-3 py-1 rounded-full text-[10px] font-bold">Nonaktif</span>
                            @else
                                <span class="bg-[#FEF3C7] text-[#92400E] px-3 py-1 rounded-full text-[10px] font-bold">Pending</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-3">
                                <a href="{{ route('petani.edit', $p->petani_id) }}" class="text-green-700 hover:scale-110 transition">
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

{{-- DataTables CSS & JS Libraries --}}
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
        if ($.fn.dataTable && $.fn.dataTable.Buttons) { $.fn.dataTable.Buttons.jszip(window.JSZip); }
        if ($.fn.DataTable.isDataTable('#petaniTable')) { $('#petaniTable').DataTable().destroy(); }

        var table = $('#petaniTable').DataTable({
            "destroy": true,
            "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" },
            "pageLength": 5,
            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
            "order": [[ 1, "asc" ]],
            "columnDefs": [ { "orderable": false, "targets": [0, 10] } ],
            "buttons": [
                {
                    extend: 'excelHtml5',
                    text: 'Export ke Excel',
                    className: 'btn-export-excel',
                    title: 'Data_Petani_NotaSawit',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] }
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Export ke PDF',
                    className: 'btn-export-pdf',
                    title: 'Data_Petani_NotaSawit',
                    orientation: 'landscape',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] }
                }
            ],
            "dom": '<"flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-3"B <"flex items-center gap-4"lf>>rtip'
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
    /* Paste semua custom CSS style DataTables Anda di sini */
    .dataTables_wrapper .dataTables_filter input { border: 1px solid #e5e7eb !important; border-radius: 9999px !important; padding: 4px 12px !important; }
    .dt-buttons .btn-export-excel { background-color: transparent !important; border: 1px solid #10B981 !important; color: #047857 !important; border-radius: 0.5rem !important; padding: 0.5rem 1rem !important; font-weight: 600 !important; }
    .dt-buttons .btn-export-pdf { background-color: transparent !important; border: 1px solid #FCA5A5 !important; color: #DC2626 !important; border-radius: 0.5rem !important; padding: 0.5rem 1rem !important; font-weight: 600 !important; }
</style>