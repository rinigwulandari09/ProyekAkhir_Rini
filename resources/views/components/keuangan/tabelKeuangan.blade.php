<div class="p-2">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#214122]">Daftar Keuangan Petani</h1>
            <p class="text-sm text-gray-500">Ringkasan aktivitas pemasukan (produksi) dan pengeluaran (operasional) seluruh petani.</p>
        </div>
        
        <div class="flex flex-wrap gap-4 w-full md:w-auto">
            {{-- Card Pemasukan --}}
            <div class="bg-white p-3 px-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 flex-1 md:flex-none">
                <div class="bg-green-100 p-2 rounded-lg text-green-600 flex">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307L21.75 6.75M21.75 6.75H16.5M21.75 6.75v5.25"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Total Pemasukan</p>
                    <p class="text-lg font-bold text-gray-800">Rp {{ number_format($totalPemasukanseluruh, 0, ',', '.') }}</p>
                </div>
            </div>
            {{-- Card Pengeluaran --}}
            <div class="bg-white p-3 px-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 flex-1 md:flex-none">
                <div class="bg-red-100 p-2 rounded-lg text-red-400 flex">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6L9 12.75l4.306-4.307L21.75 17.25M21.75 17.25H16.5M21.75 17.25v-5.25"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Total Pengeluaran</p>
                    <p class="text-lg font-bold text-gray-800">Rp {{ number_format($totalPengeluaranSeluruh, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Bar & Container Tombol Export --}}
    <div class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-4 bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
        {{-- Form Filter Rentang Waktu (Kiri) --}}
        <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
            @php
                $namaBulan = [1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'];
            @endphp
            
            <div class="w-36">
                <select name="bulan_awal" class="w-full bg-gray-50 border border-gray-200 text-gray-700 py-2 px-3 rounded-xl text-xs font-semibold outline-none focus:ring-1 focus:ring-green-700 cursor-pointer">
                    <option value="">Dari Bulan</option>
                    @foreach($namaBulan as $num => $name)
                        <option value="{{ $num }}" {{ request('bulan_awal') == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-36">
                <select name="bulan_akhir" class="w-full bg-gray-50 border border-gray-200 text-gray-700 py-2 px-3 rounded-xl text-xs font-semibold outline-none focus:ring-1 focus:ring-green-700 cursor-pointer">
                    <option value="">Sampai Bulan</option>
                    @foreach($namaBulan as $num => $name)
                        <option value="{{ $num }}" {{ request('bulan_akhir') == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-28">
                <select name="tahun" class="w-full bg-gray-50 border border-gray-200 text-gray-700 py-2 px-3 rounded-xl text-xs font-semibold outline-none focus:ring-1 focus:ring-green-700 cursor-pointer">
                    <option value="">Tahun</option>
                    @for($y = date('Y'); $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <button type="submit" class="bg-[#214122] text-white px-5 py-2 rounded-xl text-xs font-bold hover:bg-[#325a33] transition cursor-pointer">
                Terapkan Filter
            </button>

            @if(request('bulan_awal') || request('bulan_akhir') || request('tahun'))
                <a href="{{ url()->current() }}" class="text-xs text-red-500 hover:underline ml-1">Reset Filter</a>
            @endif
        </form>

        {{-- Tempat Menampung Tombol Export DataTables --}}
        <div id="exportButtonsContainer" class="flex gap-3"></div>
    </div>
    
    {{-- Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-200">
        <div class="overflow-x-auto">
            <table id="keuanganTable" class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-[#D9F99D] border-b border-gray-200">
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center w-12">No</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">Nama Petani</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-right">Pemasukan (Produksi)</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-right">Pengeluaran (Operasional)</th>
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
                        <td class="p-4 text-xs text-green-600 font-bold text-right">
                            Rp {{ number_format($petani->total_masuk ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="p-4 text-xs text-red-500 font-medium text-right">
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
</div>

{{-- Assets Library DataTables --}}
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
        window.JSZip = JSZip;
        
        if ($.fn.DataTable.isDataTable('#keuanganTable')) { 
            $('#keuanganTable').DataTable().destroy(); 
        }

        // Fungsi pembersih HTML & spasi kosong untuk Export Excel & PDF
        var exportFormatHandler = {
            body: function (data, row, column, node) {
                if (column === 0) {
                    return row + 1; // Penomoran urut otomatis di excel/pdf
                }
                if (node !== null && (column === 1 || column === 2 || column === 3)) {
                    let plainText = node.textContent || node.innerText || "";
                    return plainText.replace(/\s+/g, ' ').trim();
                }
                return data;
            }
        };

        var table = $('#keuanganTable').DataTable({
            "destroy": true,
            "pageLength": 5,
            "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
            "order": [[ 1, "asc" ]],
            "columnDefs": [ 
                { "orderable": false, "targets": [0, 4] },
                { "searchable": false, "targets": [0, 4] }
            ],
            
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "Showing 0 to 0 of 0 entries",
                "infoFiltered": "(filtered from _MAX_ total entries)",
                "zeroRecords": "No matching records found",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Prev"
                }
            },
            "buttons": [
                {
                    extend: 'excelHtml5',
                    text: 'Export ke Excel',
                    className: 'btn-export-excel',
                    title: 'Laporan_Keuangan_Petani_NotaSawit',
                    exportOptions: { 
                        columns: [0, 1, 2, 3],
                        format: exportFormatHandler
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Export ke PDF',
                    className: 'btn-export-pdf',
                    title: 'LAPORAN KEUANGAN PETANI',
                    filename: 'Data_Keuangan_Petani_NotaSawit',
                    orientation: 'portrait',
                    pageSize: 'A4',
                    exportOptions: { 
                        columns: [0, 1, 2, 3],
                        format: exportFormatHandler
                    },
                    customize: function (doc) {
                        // Set perbandingan lebar kolom (Total = 100%)
                        doc.content[1].table.widths = ['8%', '42%', '25%', '25%'];

                        doc.styles.title = {
                            color: '#214122',
                            fontSize: '15',
                            alignment: 'center',
                            bold: true,
                            margin: [0, 0, 0, 20]
                        };

                        // Mengecilkan font di PDF & auto-wrap agar teks panjang tidak terpotong ke kanan
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
                            
                            // Suntik nomor urut manual di PDF
                            doc.content[1].table.body[j][0].text = j;

                            // Terapkan ukuran font kecil ke seluruh baris data
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
            "dom": '<"flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-3"B><"flex flex-col sm:flex-row justify-between items-center gap-4 w-full mb-3"l f>rt<"flex flex-col sm:flex-row justify-between items-center gap-4 mt-4 pt-4 border-t border-gray-100"i p>'
        });

        // Event penomoran halaman yang aman dari ancaman data kosong / data berapapun
        table.on('draw.dt', function () {
            let info = table.page.info();
            // Cek proteksi jika records total di atas 0 baru lakukan iterasi pembuatan nomor urut halaman
            if (info.recordsTotal > 0) {
                table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function(cell, i) {
                    if (cell) {
                        cell.innerHTML = info.start + i + 1;
                    }
                });
            }
        });

        // Pemicu draw pertama kali untuk mengaktifkan index nomor awal
        table.draw();

        table.buttons().container().appendTo('#exportButtonsContainer');
    });
</script>

<style>
    /* Styling Filter & Pencarian */
    .dataTables_wrapper .dataTables_filter input { 
        border: 1px solid #e5e7eb !important; 
        border-radius: 9999px !important; 
        padding: 4px 12px !important; 
        outline: none;
    }
    .dataTables_wrapper .dataTables_filter input:focus { border-color: #214122 !important; }
    .dataTables_wrapper .dataTables_length select { border: 1px solid #e5e7eb !important; border-radius: 0.375rem !important; padding: 2px 8px !important; }
    
    /* Styling Tombol Export */
    .dt-buttons .btn-export-excel { background-color: transparent !important; border: 1px solid #10B981 !important; color: #047857 !important; border-radius: 0.5rem !important; padding: 0.5rem 1rem !important; font-weight: 600 !important; cursor: pointer;}
    .dt-buttons .btn-export-excel:hover { background-color: #10B981 !important; color: white !important; }
    
    .dt-buttons .btn-export-pdf { background-color: transparent !important; border: 1px solid #FCA5A5 !important; color: #DC2626 !important; border-radius: 0.5rem !important; padding: 0.5rem 1rem !important; font-weight: 600 !important; cursor: pointer;}
    .dt-buttons .btn-export-pdf:hover { background-color: #DC2626 !important; color: white !important; }

    /* Modifikasi Khusus Bagian Bawah Tabel (Info & Pagination Modern) */
    .dataTables_wrapper .dataTables_info {
        font-size: 0.875rem !important;
        color: #4b5563 !important;
        padding-top: 0 !important;
    }
    
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 0 !important;
        display: flex !important;
        gap: 0.25rem !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid #d1d5db !important;
        border-radius: 0.375rem !important;
        padding: 0.375rem 0.75rem !important;
        margin-left: 0 !important;
        font-size: 0.875rem !important;
        background: #ffffff !important;
        color: #374151 !important;
        transition: all 0.2s;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f3f4f6 !important;
        color: #111827 !important;
        border-color: #9ca3af !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #214122 !important;
        color: #ffffff !important;
        border-color: #214122 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        background: #f9fafb !important;
        color: #9ca3af !important;
        border-color: #e5e7eb !important;
        cursor: not-allowed !important;
    }
</style>