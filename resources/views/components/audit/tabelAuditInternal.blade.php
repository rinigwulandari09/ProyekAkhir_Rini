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
                <option value="Menunggu Keputusan" {{ request('status') == 'Menunggu Keputusan' && request('tab', 'audit') == 'audit' ? 'selected' : '' }}>Menunggu Keputusan</option>
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
        <table id="auditTable" class="w-full text-left border-collapse display responsive nowrap" style="width: 100%">
                <thead>
                    <tr class="bg-[#D4AF37] border-b border-[#B8860B] shadow-sm text-black">
                        <th class="p-4 text-xs font-extrabold uppercase text-center w-12 rounded-tl-lg tracking-wider">No</th>
                        <th class="p-4 text-xs font-extrabold uppercase tracking-wider">Nama Petani</th>
                        <th class="p-4 text-xs font-extrabold uppercase tracking-wider">Tanggal</th>
                        <th class="p-4 text-xs font-extrabold uppercase tracking-wider">Desa</th>
                        <th class="p-4 text-xs font-extrabold uppercase tracking-wider">Nama Auditor</th>
                        <th class="p-4 text-xs font-extrabold uppercase tracking-wider">Status</th>
                        <th class="p-4 text-xs font-extrabold uppercase tracking-wider">Keterangan</th>
                        <th class="p-4 text-xs font-extrabold uppercase rounded-tr-lg tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($audit as $nama_petani => $history)
                    @php $a = $history->first(); @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-xs text-center text-gray-500 font-mono"></td>
                        <td class="p-4 text-xs text-gray-800 font-semibold">{{ $a->nama_petani ?? '-' }}</td>
                        <td class="p-4 text-xs text-gray-800 font-medium">
                            {{ $a->tanggal ? date('Y-m-d', strtotime($a->tanggal)) : '-' }}
                        </td>
                        <td class="p-4 text-xs text-gray-600">{{ $a->desa ?? '-' }}</td>
                        <td class="p-4 text-xs text-gray-500">{{ $a->nama_auditor ?? '-' }}</td>
                        <td class="p-4 text-xs">
                            @php
                                $displayStatus = $a->status_audit ?: 'Menunggu Keputusan';
                                $statusColor = match(strtolower($displayStatus)) {
                                    'disetujui', 'lolos', 'lulus', 'selesai' => 'bg-green-50 text-green-700 border-green-200',
                                    'ditolak', 'tidak lolos', 'gagal', 'perlu perbaikan' => 'bg-red-50 text-red-700 border-red-200',
                                    'proses', 'pending', 'menunggu', 'menunggu konfirmasi', 'menunggu keputusan' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                    default => 'bg-gray-50 text-gray-700 border-gray-200'
                                };
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $statusColor }} whitespace-nowrap">
                                {{ $displayStatus }}
                            </span>
                        </td>
                        <td class="p-4 text-xs text-gray-600 max-w-xs truncate" title="{{ $a->keterangan }}">{{ $a->keterangan ?? '-' }}</td>
                        <td class="p-4">
                            <div class="flex gap-2">
                                <button type="button" onclick="openDetailModal('{{ md5($nama_petani) }}')" class="inline-flex items-center gap-1.5 bg-[#234323] text-white px-3 py-1.5 rounded-lg text-[10px] font-bold hover:bg-[#1a331a] hover:shadow-md transition whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                    {{ auth()->user()->user_role == 'super_admin' ? 'Tinjau & Verifikasi' : 'Riwayat Audit' }}
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
        </table>
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
            "order": [[ 2, "desc" ]],
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
            "dom": '<"hidden" B> <"flex justify-between items-center w-full mb-4 gap-2" l f> <"overflow-x-auto w-full" tr> <"flex flex-col sm:flex-row justify-between items-center gap-4 mt-4" i p>'
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

{{-- Modals for Detail & History --}}
@foreach($audit as $nama_petani => $history)
    @php $latest = $history->first(); @endphp
    <div id="detailModal-{{ md5($nama_petani) }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-black/50" aria-hidden="true" onclick="closeDetailModal('{{ md5($nama_petani) }}')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full z-10">
                
                {{-- Header --}}
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-extrabold text-gray-900">Kelola Audit: {{ $nama_petani }}</h3>
                        <p class="text-xs text-gray-500 mt-1">Tinjau hasil audit terbaru dan lihat riwayat sebelumnya.</p>
                    </div>
                    <button type="button" onclick="closeDetailModal('{{ md5($nama_petani) }}')" class="text-gray-400 hover:text-red-500 transition-colors p-2 rounded-full hover:bg-red-50">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        @if(auth()->user()->user_role == 'super_admin')
                        {{-- Kiri: Form Verifikasi Audit Terakhir --}}
                        <div class="lg:col-span-5 flex flex-col gap-5">
                            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-1 h-full bg-[#EAB308]"></div>
                                <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#EAB308]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Verifikasi Audit Terakhir
                                </h4>
                                
                                <div class="mb-4 space-y-2">
                                    <div class="flex justify-between text-xs border-b border-dashed pb-2">
                                        <span class="text-gray-500">Tanggal</span>
                                        <span class="font-semibold text-gray-800">{{ $latest->tanggal ? date('Y-m-d', strtotime($latest->tanggal)) : '-' }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs border-b border-dashed pb-2">
                                        <span class="text-gray-500">Auditor</span>
                                        <span class="font-semibold text-gray-800">{{ $latest->nama_auditor ?? '-' }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs border-b border-dashed pb-2">
                                        <span class="text-gray-500">File Bukti</span>
                                        @if($latest->path_file_kunjungan)
                                            <a href="{{ Storage::url(str_replace(['storage/', 'public/'], '', $latest->path_file_kunjungan)) }}" target="_blank" class="text-blue-600 font-semibold hover:underline flex items-center gap-1">
                                                Lihat PDF <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">Tidak ada</span>
                                        @endif
                                    </div>
                                </div>

                                <form action="/audit/internal/{{ $latest->id_audit }}/status" method="POST" class="mt-5 border-t pt-4">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Ubah Status</label>
                                        <select name="status_audit" onchange="toggleKeteranganFieldDalamModal(this, '{{ md5($nama_petani) }}')" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-sm focus:ring-[#234323] focus:border-[#234323]">
                                            <option value="" disabled {{ !$latest->status_audit ? 'selected' : '' }} hidden>Pilih Status...</option>
                                            <option value="Lulus" {{ $latest->status_audit == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                                            <option value="Perlu Perbaikan" {{ in_array($latest->status_audit, ['Perlu Perbaikan', 'Tidak Lolos', 'Gagal']) ? 'selected' : '' }}>Perlu Perbaikan</option>
                                        </select>
                                    </div>
                                    <div id="ket-container-{{ md5($nama_petani) }}" class="mb-4 {{ in_array($latest->status_audit, ['Perlu Perbaikan', 'Tidak Lolos', 'Gagal']) ? '' : 'hidden' }}">
                                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Alasan / Keterangan</label>
                                        <textarea name="keterangan" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm text-sm focus:ring-[#234323] focus:border-[#234323]" placeholder="Tulis catatan di sini...">{{ $latest->keterangan }}</textarea>
                                    </div>
                                    <button type="submit" class="w-full bg-[#234323] text-white py-2.5 rounded-lg text-sm font-bold shadow-md hover:bg-[#1a331a] hover:shadow-lg transition flex justify-center items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Simpan Keputusan
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif

                        {{-- Kanan: Riwayat Audit (Semua attempt) --}}
                        <div class="{{ auth()->user()->user_role == 'super_admin' ? 'lg:col-span-7' : 'lg:col-span-12' }} flex flex-col">
                            <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Riwayat Audit Keseluruhan
                            </h4>
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex-1">
                                <div class="overflow-y-auto max-h-[400px]">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50 sticky top-0 z-10">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                                <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Auditor</th>
                                                <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                                <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-100">
                                            @foreach($history as $index => $h)
                                            <tr class="{{ $index === 0 ? 'bg-blue-50/30' : 'hover:bg-gray-50' }}">
                                                <td class="px-4 py-3 text-xs text-gray-900 whitespace-nowrap">
                                                    {{ $h->tanggal ? date('Y-m-d', strtotime($h->tanggal)) : '-' }}
                                                    @if($index === 0) <span class="ml-1 text-[9px] bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded-full font-bold">Terbaru</span> @endif
                                                </td>
                                                <td class="px-4 py-3 text-xs text-gray-900">{{ $h->nama_auditor ?? '-' }}</td>
                                                <td class="px-4 py-3 text-xs whitespace-nowrap">
                                                    @php
                                                        $statusLabel = $h->status_audit ?: 'Menunggu Keputusan';
                                                        $badge = match(strtolower($statusLabel)) {
                                                            'disetujui', 'lolos', 'lulus', 'selesai' => 'bg-green-100 text-green-700',
                                                            'ditolak', 'tidak lolos', 'gagal', 'perlu perbaikan' => 'bg-red-100 text-red-700',
                                                            default => 'bg-yellow-100 text-yellow-700'
                                                        };
                                                    @endphp
                                                    <span class="px-2 py-1 inline-flex text-[10px] leading-4 font-bold rounded-full {{ $badge }}">
                                                        {{ $statusLabel }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-xs text-gray-500 min-w-[150px]">
                                                    {{ $h->keterangan ?? '-' }}
                                                    @if($h->path_file_kunjungan)
                                                    <div class="mt-1">
                                                        <a href="{{ Storage::url(str_replace(['storage/', 'public/'], '', $h->path_file_kunjungan)) }}" target="_blank" class="text-blue-600 hover:underline text-[10px] inline-flex items-center gap-0.5">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                            Lampiran
                                                        </a>
                                                    </div>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

{{-- Modals for History --}}
@foreach($audit as $nama_petani => $history)
    @if($history->count() > 1)
    <div id="historyModal-{{ md5($nama_petani) }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-black/50" aria-hidden="true" onclick="closeHistoryModal('{{ md5($nama_petani) }}')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl shadow-xl sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full sm:p-6 z-10">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Riwayat Audit: {{ $nama_petani }}</h3>
                    <button type="button" onclick="closeHistoryModal('{{ md5($nama_petani) }}')" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="mt-2 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attempt</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auditor</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($history as $h)
                            <tr>
                                <td class="px-3 py-2 text-sm text-gray-900">{{ $h->audit_attempt ?? '-' }}</td>
                                <td class="px-3 py-2 text-sm text-gray-900">{{ $h->tanggal }}</td>
                                <td class="px-3 py-2 text-sm text-gray-900">{{ $h->nama_auditor }}</td>
                                <td class="px-3 py-2 text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($h->status_audit == 'Lulus') bg-green-100 text-green-800 
                                        @elseif($h->status_audit == 'Perlu Perbaikan') bg-red-100 text-red-800 
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ $h->status_audit ?: 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 text-sm text-gray-500 max-w-[150px] truncate" title="{{ $h->keterangan }}">{{ $h->keterangan ?? '-' }}</td>
                                <td class="px-3 py-2 text-sm text-gray-500 flex items-center gap-2">
                                    @if($h->path_file_kunjungan)
                                    <a href="{{ Storage::url(str_replace(['storage/', 'public/'], '', $h->path_file_kunjungan)) }}" target="_blank" class="text-blue-600 hover:underline">Lihat PDF</a>
                                    @else
                                    <span>-</span>
                                    @endif
                                    
                                    <button type="button" 
                                            data-id="{{ $h->id_audit }}"
                                            data-status="{{ $h->status_audit ?: '' }}"
                                            data-keterangan="{{ $h->keterangan ?? '' }}"
                                            class="btn-edit-status text-yellow-500 hover:scale-110 transition" title="Ubah Status">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"></path></svg>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach

<script>
    function toggleKeteranganFieldDalamModal(selectElement, id) {
        const container = document.getElementById('ket-container-' + id);
        if (selectElement.value === 'Perlu Perbaikan') {
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
