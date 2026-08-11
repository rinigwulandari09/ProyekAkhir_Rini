<div class="p-2">
    {{-- Header Section --}}
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#214122]">Daftar Audit Internal</h1>
            <p class="text-sm text-gray-500">Data hasil audit internal yang telah dilakukan.</p>
        </div>
    </div>

    {{-- Container tempat tombol Export dan Filter --}}
    <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-3 mb-4">
        <div id="exportButtonsContainerAudit" class="flex flex-wrap items-center w-full xl:w-auto gap-2"></div>
        
        <form action="{{ route('audit.index') }}" method="GET" class="flex flex-wrap items-center gap-2 w-full xl:w-auto">
            <input type="hidden" name="tab" value="audit">
            
            <select name="dari_bulan" class="bg-white border-gray-200 text-gray-700 rounded-lg py-2 pl-3 pr-8 text-xs font-medium focus:ring-2 focus:ring-[#234323]/20 focus:border-[#234323] hover:border-[#234323] hover:shadow-md transition-all duration-300 shadow-sm cursor-pointer outline-none">
                <option value="">Dari Bln</option>
                @for ($m=1; $m<=12; $m++)
                    <option value="{{ $m }}" {{ request('dari_bulan') == $m && request('tab', 'audit') == 'audit' ? 'selected' : '' }}>{{ date('M', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
            </select>

            <select name="sampai_bulan" class="bg-white border-gray-200 text-gray-700 rounded-lg py-2 pl-3 pr-8 text-xs font-medium focus:ring-2 focus:ring-[#234323]/20 focus:border-[#234323] hover:border-[#234323] hover:shadow-md transition-all duration-300 shadow-sm cursor-pointer outline-none">
                <option value="">Sampai Bln</option>
                @for ($m=1; $m<=12; $m++)
                    <option value="{{ $m }}" {{ request('sampai_bulan') == $m && request('tab', 'audit') == 'audit' ? 'selected' : '' }}>{{ date('M', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
            </select>

            <select name="tahun" class="bg-white border-gray-200 text-gray-700 rounded-lg py-2 pl-3 pr-8 text-xs font-medium focus:ring-2 focus:ring-[#234323]/20 focus:border-[#234323] hover:border-[#234323] hover:shadow-md transition-all duration-300 shadow-sm cursor-pointer outline-none">
                <option value="">Tahun</option>
                @php $currentYear = date('Y'); @endphp
                @for ($y = $currentYear + 2; $y >= 2023; $y--)
                    <option value="{{ $y }}" {{ request('tahun') == $y && request('tab', 'audit') == 'audit' ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>

            <select name="status" class="bg-white border-gray-200 text-gray-700 rounded-lg py-2 pl-3 pr-8 text-xs font-medium focus:ring-2 focus:ring-[#234323]/20 focus:border-[#234323] hover:border-[#234323] hover:shadow-md transition-all duration-300 shadow-sm cursor-pointer outline-none">
                <option value="">Semua Status</option>
                <option value="Menunggu Konfirmasi" {{ request('status') == 'Menunggu Konfirmasi' && request('tab', 'audit') == 'audit' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                <option value="Lulus" {{ request('status') == 'Lulus' && request('tab', 'audit') == 'audit' ? 'selected' : '' }}>Lulus</option>
                <option value="Perlu Perbaikan" {{ request('status') == 'Perlu Perbaikan' && request('tab', 'audit') == 'audit' ? 'selected' : '' }}>Perlu Perbaikan</option>
            </select>

            <button type="submit" class="bg-[#214122] text-white px-4 py-2 rounded-lg text-xs font-bold tracking-wide hover:bg-[#1a331a] hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 shadow-sm">
                Filter
            </button>
            
            @if(request()->hasAny(['dari_bulan', 'sampai_bulan', 'tahun', 'status']) && request('tab', 'audit') == 'audit')
                <a href="{{ route('audit.index', ['tab' => 'audit']) }}" class="bg-white text-gray-700 border border-gray-200 px-4 py-2 rounded-lg text-xs font-bold tracking-wide hover:bg-gray-50 hover:border-gray-300 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 shadow-sm">
                    Reset
                </a>
            @endif
        </form>
    </div>
    
    {{-- Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm p-3 sm:p-4 border border-gray-200">
        <div class="overflow-x-auto w-full">
            <table id="auditTable" class="w-full text-left border-collapse display responsive nowrap" style="width: 100%">
                <thead>
                    <tr class="bg-[#FFC107] border-b border-[#E0A800] shadow-sm">
                        <th class="p-4 text-xs font-extrabold text-gray-900 uppercase text-center w-12 rounded-tl-lg tracking-wider">No</th>
                        <th class="p-4 text-xs font-extrabold text-gray-900 uppercase tracking-wider">Tanggal</th>
                        <th class="p-4 text-xs font-extrabold text-gray-900 uppercase tracking-wider">Desa</th>
                        <th class="p-4 text-xs font-extrabold text-gray-900 uppercase tracking-wider">Nama Auditor</th>
                        <th class="p-4 text-xs font-extrabold text-gray-900 uppercase tracking-wider">Nama Petani</th>
                        <th class="p-4 text-xs font-extrabold text-gray-900 uppercase tracking-wider">Status</th>
                        <th class="p-4 text-xs font-extrabold text-gray-900 uppercase tracking-wider">Keterangan</th>
                        <th class="p-4 text-xs font-extrabold text-gray-900 uppercase text-center rounded-tr-lg tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($audit as $a)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-xs text-center text-gray-500 font-mono"></td>
                        <td class="p-4 text-xs text-gray-800 font-medium">
                            {{ $a->tanggal ? date('Y-m-d', strtotime($a->tanggal)) : '-' }}
                        </td>
                        <td class="p-4 text-xs text-gray-600">{{ $a->desa ?? '-' }}</td>
                        <td class="p-4 text-xs text-gray-500">{{ $a->nama_auditor ?? '-' }}</td>
                        <td class="p-4 text-xs text-gray-800">{{ $a->nama_petani ?? '-' }}</td>
                        <td class="p-4 text-xs">
                            @php
                                $displayStatus = $a->status_audit ?: 'Menunggu Konfirmasi';
                                $statusColor = match(strtolower($displayStatus)) {
                                    'disetujui', 'lolos', 'lulus', 'selesai' => 'bg-green-50 text-green-700 border-green-200',
                                    'ditolak', 'tidak lolos', 'gagal', 'perlu perbaikan' => 'bg-red-50 text-red-700 border-red-200',
                                    'proses', 'pending', 'menunggu', 'menunggu konfirmasi' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                    default => 'bg-gray-50 text-gray-700 border-gray-200'
                                };
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $statusColor }} whitespace-nowrap">
                                {{ $displayStatus }}
                            </span>
                        </td>
                        <td class="p-4 text-xs text-gray-600 max-w-xs truncate" title="{{ $a->keterangan }}">{{ $a->keterangan ?? '-' }}</td>
                        <td class="p-4">
                            <div class="flex justify-center gap-3">
                                @if($a->path_file_kunjungan)
                                <a href="{{ Storage::url(str_replace(['storage/', 'public/'], '', $a->path_file_kunjungan)) }}" target="_blank" class="text-blue-600 hover:scale-110 transition" title="Lihat File">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"></path></svg>
                                </a>
                                @endif
                                <button type="button" 
                                        data-id="{{ $a->id_audit }}"
                                        data-status="{{ $a->status_audit ?: '' }}"
                                        data-keterangan="{{ $a->keterangan ?? '' }}"
                                        class="btn-edit-status text-yellow-500 hover:scale-110 transition" title="Ubah Status">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"></path></svg>
                                </button>
                                <form action="{{ route('audit.internal.destroy', $a->id_audit) }}" method="POST" onsubmit="return confirm('Yakin hapus data audit internal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:scale-110 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path></svg>
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

<script>
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#auditTable')) { $('#auditTable').DataTable().destroy(); }

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

        var tableAudit = $('#auditTable').DataTable({
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
                    title: 'Data_Audit_Internal',
                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6], format: cleanExportFormat }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<div class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg><span>Export PDF</span></div>',
                    className: 'btn-export-pdf',
                    title: 'LAPORAN DAFTAR AUDIT INTERNAL',
                    filename: 'Data_Audit_Internal',
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
            "dom": '<"hidden" B> <"flex justify-between items-center w-full mb-4 gap-2" l f> rt <"flex flex-col sm:flex-row justify-between items-center gap-4 mt-4" i p>'
        });

        tableAudit.on('order.dt search.dt draw.dt', function () {
            let start = tableAudit.page.info().start;
            tableAudit.column(0, {search: 'applied', order: 'applied'}).nodes().each(function(cell, i) {
                cell.innerHTML = start + i + 1;
            });
        }).draw();

        tableAudit.buttons().container().appendTo('#exportButtonsContainerAudit');
    });
</script>

{{-- Modal Update Status --}}
<div id="editStatusModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        {{-- Background overlay --}}
        <div class="fixed inset-0 transition-opacity bg-black/50" aria-hidden="true" onclick="closeEditStatusModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="relative inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 z-10">
            <div class="sm:flex sm:items-start">
                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-[#234323]/10 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="w-6 h-6 text-[#234323]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Ubah Status Audit Internal</h3>
                    <div class="mt-4">
                        <form id="editStatusForm" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-4">
                                <label for="status_audit" class="block text-sm font-medium text-gray-700 mb-1">Status Audit</label>
                                <select id="status_audit" name="status_audit" onchange="toggleKeteranganField()" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-[#234323] focus:border-[#234323] sm:text-sm">
                                    <option value="" disabled selected hidden>Pilih Status...</option>
                                    <option value="Lulus">Lulus</option>
                                    <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                                </select>
                            </div>

                            <div id="keterangan_container" class="mb-4 hidden">
                                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan / Alasan Perbaikan</label>
                                <textarea id="keterangan" name="keterangan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-[#234323] focus:border-[#234323] sm:text-sm" placeholder="Tuliskan keterangan detail di sini..."></textarea>
                            </div>

                            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-[#234323] border border-transparent rounded-lg shadow-sm hover:bg-[#3D5A3E] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#234323] sm:ml-3 sm:w-auto sm:text-sm">
                                    Simpan Perubahan
                                </button>
                                <button type="button" onclick="closeEditStatusModal()" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#234323] sm:mt-0 sm:w-auto sm:text-sm">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Gunakan event delegation agar tombol yang ada di dalam pagination DataTables tetap berfungsi
    $(document).on('click', '.btn-edit-status', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const status = $(this).data('status');
        const keterangan = $(this).data('keterangan');
        
        openEditStatusModal(id, status, keterangan);
    });

    function openEditStatusModal(id, currentStatus, currentKeterangan) {
        const form = document.getElementById('editStatusForm');

        form.action = `/audit/internal/${id}/status`;
        
        const statusSelect = document.getElementById('status_audit');
        const keteranganInput = document.getElementById('keterangan');

        if (!currentStatus) {
            statusSelect.value = '';
        } else if (currentStatus === 'Perlu Perbaikan' || currentStatus === 'Tidak Lolos' || currentStatus === 'Gagal') {
            statusSelect.value = 'Perlu Perbaikan';
        } else {
            statusSelect.value = 'Lulus';
        }

        keteranganInput.value = currentKeterangan || '';
        
        toggleKeteranganField();
        
        document.getElementById('editStatusModal').classList.remove('hidden');
    }

    function closeEditStatusModal() {
        document.getElementById('editStatusModal').classList.add('hidden');
    }

    function toggleKeteranganField() {
        const status = document.getElementById('status_audit').value;
        const container = document.getElementById('keterangan_container');
        if (status === 'Perlu Perbaikan') {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }
</script>
