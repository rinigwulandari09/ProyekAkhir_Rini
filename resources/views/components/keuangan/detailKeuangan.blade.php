<div class="p-2">
    {{-- Breadcrumb & Title --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#214122]">Detail Keuangan Petani</h1>
            <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                Data Keuangan 
                <x-heroicon-o-chevron-right class="w-3 h-3" /> 
                <span class="text-gray-600 font-medium">Detail</span>
            </p>
        </div>
        <div>
            <a href="{{ route('keuangan.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-600 hover:text-[#214122] transition bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-xl border border-gray-200 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path></svg>
                Kembali ke Daftar
            </a>
        </div>
    </div>

    {{-- Main Container Card --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-200">
        {{-- Header Detail Petani --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-100 pb-6 mb-6 gap-4">
            <div>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Informasi Petani</span>
                <h2 class="text-xl font-extrabold text-gray-800 mt-0.5">
                    Nama Petani: <span id="namaPetaniCetak" class="font-medium text-gray-600">{{ $petani->petani_nama ?? $petani->nama ?? 'Dhini Handayani' }}</span>
                </h2>
            </div>
            {{-- Tombol Utama pemicu download PDF --}}
            <button onclick="exportSemuaLaporan()" class="bg-[#214122] text-white px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 hover:bg-[#325934] transition shadow-sm">
                <x-heroicon-o-arrow-up-tray class="w-4 h-4" /> Export Laporan Keuangan (PDF)
            </button>
        </div>

        {{-- Filter Data Keuangan --}}
        <div class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-6 bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
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
        </div>

        {{-- Ringkasan Akumulasi Petani Ini --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <div class="bg-green-50/60 border border-green-100 rounded-xl p-4 flex items-center gap-3">
                <div class="text-green-600 bg-green-100 p-2 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307L21.75 6.75M21.75 6.75H16.5M21.75 6.75v5.25"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Total Pemasukan Petani</p>
                    <p id="totalMasukCetak" class="text-base font-bold text-green-700">Rp {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="bg-red-50/60 border border-red-100 rounded-xl p-4 flex items-center gap-3">
                <div class="text-red-500 bg-red-100 p-2 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6L9 12.75l4.306-4.307L21.75 17.25M21.75 17.25H16.5M21.75 17.25v-5.25"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Total Pengeluaran Petani</p>
                    <p id="totalKeluarCetak" class="text-base font-bold text-red-600">Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Tabel Pemasukan Section --}}
        <div class="mb-10 border border-gray-100 rounded-xl p-4 shadow-sm keuangan-wrapper">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4 header-tabel-custom-masuk">
                <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-green-600 rounded"></span> Tabel Pemasukan (Produksi)
                </h3>
            </div>
            <div class="overflow-x-auto rounded-lg border border-gray-100">
                <table id="tabelPemasukan" class="w-full text-left whitespace-nowrap m-0">
                    <thead>
                        <tr class="bg-[#D9F99D] text-[#214122] text-xs font-bold border-b border-gray-200">
                            <th class="p-3 text-justify w-12">No</th>
                            <th class="p-3 text-justify">Tanggal</th>
                            <th class="p-3">Asal Lahan</th>
                            <th class="p-3 text-justify">Total Pendapatan</th>
                            <th class="p-3 text-justify">Bukti Nota</th>
                            <th class="p-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-50">
                        @forelse ($pemasukan as $masuk)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-3 text-justify text-gray-400 font-mono"></td>
                            <td class="p-3 text-justify text-gray-600" data-order="{{ $masuk->produksi_tanggal }}">
                                {{ \Carbon\Carbon::parse($masuk->produksi_tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td class="p-3 text-gray-700 font-medium">{{ $masuk->lahan->lahan_nama ?? '-' }}</td>
                            <td class="p-3 text-justify text-green-600 font-bold">
                                Rp {{ number_format($masuk->total_pendapatan, 0, ',', '.') }}
                            </td>
                            <td class="p-3 text-justify">
                                @if($masuk->produksi_bukti)
                                    <a href="{{ Storage::url($masuk->produksi_bukti) }}" target="_blank" class="text-blue-600 underline hover:text-blue-800">
                                        Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="p-3 text-gray-500 max-w-50 truncate" title="{{ $masuk->produksi_keterangan ?? $masuk->keterangan }}">
                                {{ $masuk->produksi_keterangan ?? $masuk->keterangan ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="p-6 text-center text-gray-400 italic bg-gray-50/50">Belum ada data transaksi pemasukan.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel Pengeluaran Section --}}
        <div class="border border-gray-100 rounded-xl p-4 shadow-sm keuangan-wrapper">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4 header-tabel-custom-keluar">
                <h3 class="font-bold text-gray-700 text-sm flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-red-500 rounded"></span> Tabel Pengeluaran (Operasional)
                </h3>
            </div>
            <div class="overflow-x-auto rounded-lg border border-gray-100">
                <table id="tabelPengeluaran" class="w-full text-left whitespace-nowrap m-0">
                    <thead>
                        <tr class="bg-[#FFE4E6] text-[#991B1B] text-xs font-bold border-b border-gray-200">
                            <th class="p-3 text-justify w-12">No</th>
                            <th class="p-3 text-justify">Tanggal</th>
                            <th class="p-3">Asal Lahan</th>
                            <th class="p-3">Jenis Biaya</th>
                            <th class="p-3 text-justify">Jumlah Biaya</th>
                            <th class="p-3 text-justify">Bukti</th>
                            <th class="p-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-50">
                        @forelse ($pengeluaran as $keluar)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-3 text-justify text-gray-400 font-mono"></td>
                            <td class="p-3 text-justify text-gray-600" data-order="{{ $keluar->biaya_tanggal }}">
                                {{ \Carbon\Carbon::parse($keluar->biaya_tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td class="p-3 text-gray-700 font-medium">
                                {{ $keluar->lahan_nama }}
                            </td>
                            <td class="p-3 text-gray-600">
                                {{ $keluar->biaya_jenis ?? '-' }}
                            </td>
                            <td class="p-3 text-justify text-red-600 font-bold">
                                Rp {{ number_format($keluar->biaya_total, 0, ',', '.') }}
                            </td>
                            <td class="p-3 text-justify">
                                @if($keluar->biaya_bukti)
                                    <a href="{{ Storage::url($keluar->biaya_bukti) }}" target="_blank" class="text-blue-600 underline hover:text-blue-800">
                                        Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="p-3 text-gray-500">
                                {{ $keluar->biaya_keterangan ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="p-6 text-center text-gray-400 italic bg-gray-50/50">Belum ada data transaksi pengeluaran.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Dependensi DataTables & Ekstensi PDFMake --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script>
    var tableMasuk, tableKeluar;

    $(document).ready(function() {
        var konfigurasiBahasa = {
            "search": "",
            "searchPlaceholder": "Search data...",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "Showing 0 to 0 of 0 entries",
            "infoFiltered": "(filtered from _MAX_ total entries)",
            "emptyTable": "Belum ada data transaksi.",
            "zeroRecords": "No matching records found",
            "paginate": { "next": "Next", "previous": "Prev" }
        };

        // Inisialisasi DataTables Pemasukan
        tableMasuk = $('#tabelPemasukan').DataTable({
            "pageLength": 5,
            "lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
            "language": konfigurasiBahasa,
            "columnDefs": [
                { "orderable": false, "targets": 0 },
                { "searchable": false, "targets": 0 }
            ],
            "order": [[1, 'desc']],
            "dom": '<"flex justify-between items-center gap-4 mb-2"f>rt<"flex flex-col sm:flex-row justify-between items-center gap-4 mt-4 pt-4 border-t border-gray-100"i p>'
        });

        tableMasuk.on('order.dt search.dt draw.dt', function () {
            let info = tableMasuk.page.info();
            tableMasuk.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            });
        }).draw();

        // Inisialisasi DataTables Pengeluaran
        tableKeluar = $('#tabelPengeluaran').DataTable({
            "pageLength": 5,
            "lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
            "language": konfigurasiBahasa,
            "columnDefs": [
                { "orderable": false, "targets": 0 },
                { "searchable": false, "targets": 0 }
            ],
            "order": [[1, 'desc']],
            "dom": '<"flex justify-between items-center gap-4 mb-2"f>rt<"flex flex-col sm:flex-row justify-between items-center gap-4 mt-4 pt-4 border-t border-gray-100"i p>'
        });

        tableKeluar.on('order.dt search.dt draw.dt', function () {
            let info = tableKeluar.page.info();
            tableKeluar.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = info.start + i + 1;
            });
        }).draw();

        // Pindahkan kolom pencarian bawaan ke barisan judul
        $('.header-tabel-custom-masuk').append($('#tabelPemasukan_wrapper .dataTables_filter'));
        $('.header-tabel-custom-keluar').append($('#tabelPengeluaran_wrapper .dataTables_filter'));
    });

    // FUNGSI UTAMA EXPORT LAPORAN GABUNGAN KE PDF
    function exportSemuaLaporan() {
        var namaPetani = $('#namaPetaniCetak').text().trim();
        var totalMasuk = $('#totalMasukCetak').text().trim();
        var totalKeluar = $('#totalKeluarCetak').text().trim();

        var dataPemasukan = [];
        tableMasuk.rows({ search: 'applied' }).every(function (rowIdx, tableLoop, rowLoop) {
            var cells = $(this.node()).find('td');
            dataPemasukan.push([
                rowLoop + 1,
                cells.eq(1).text().trim(),
                cells.eq(2).text().trim(),
                { text: cells.eq(3).text().trim(), alignment: 'right' },
                cells.eq(4).text().trim(),
                cells.eq(5).text().trim()
            ]);
        });
        if (dataPemasukan.length === 0) {
            dataPemasukan.push([{ text: 'Belum ada catatan transaksi.', colspan: 6, alignment: 'center', italic: true }, '', '', '', '', '']);
        }

        var dataPengeluaran = [];
        tableKeluar.rows({ search: 'applied' }).every(function (rowIdx, tableLoop, rowLoop) {
            var cells = $(this.node()).find('td');
            dataPengeluaran.push([
                rowLoop + 1,
                cells.eq(1).text().trim(),
                cells.eq(2).text().trim(),
                cells.eq(3).text().trim(),
                { text: cells.eq(4).text().trim(), alignment: 'right' },
                cells.eq(5).text().trim(),
                cells.eq(6).text().trim()
            ]);
        });
        if (dataPengeluaran.length === 0) {
            dataPengeluaran.push([{ text: 'Belum ada catatan transaksi.', colspan: 7, alignment: 'center', italic: true }, '', '', '', '', '', '']);
        }

        var docDefinition = {
            pageSize: 'A4',
            pageOrientation: 'portrait',
            pageMargins: [40, 40, 40, 40],
            content: [
                { text: 'LAPORAN REKAPITULASI KEUANGAN PETANI', style: 'docTitle' },
                { text: 'Sistem Informasi Manajemen Keuangan Petani', style: 'docSub', alignment: 'center' },
                { canvas: [{ type: 'line', x1: 0, y1: 5, x2: 515, y2: 5, lineWidth: 1.5, lineColor: '#214122' }] },
                { text: '\n' },
                {
                    columns: [
                        { text: [{ text: 'Nama Petani : ', bold: true }, namaPetani], fontSize: 10 },
                        { text: [{ text: 'Tanggal Unduh : ', bold: true }, new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })], alignment: 'right', fontSize: 10 }
                    ]
                },
                { text: '\n' },
                {
                    table: {
                        widths: ['50%', '50%'],
                        body: [
                            [
                                { text: 'TOTAL AKUMULASI PEMASUKAN', fillColor: '#f0fdf4', color: '#166534', bold: true, alignment: 'center', fontSize: 9, padding: [6, 6] },
                                { text: 'TOTAL AKUMULASI PENGELUARAN', fillColor: '#fef2f2', color: '#991b1b', bold: true, alignment: 'center', fontSize: 9, padding: [6, 6] }
                            ],
                            [
                                { text: totalMasuk, color: '#166534', bold: true, alignment: 'center', fontSize: 12, padding: [8, 8] },
                                { text: totalKeluar, color: '#991b1b', bold: true, alignment: 'center', fontSize: 12, padding: [8, 8] }
                            ]
                        ]
                    }
                },
                { text: '\n\n' },
                { text: '1. Rincian Pendapatan / Pemasukan (Produksi)', style: 'sectionHeader' },
                {
                    style: 'tableStyle',
                    table: {
                        widths: ['6%', '16%', '18%', '18%', '14%', '28%'],
                        headerRows: 1,
                        body: [
                            [
                                { text: 'No', style: 'tableHeaderMasuk' },
                                { text: 'Tanggal', style: 'tableHeaderMasuk' },
                                { text: 'Asal Lahan', style: 'tableHeaderMasuk' },
                                { text: 'Total Pendapatan', style: 'tableHeaderMasuk', alignment: 'right' },
                                { text: 'Bukti Nota', style: 'tableHeaderMasuk' },
                                { text: 'Keterangan', style: 'tableHeaderMasuk' }
                            ],
                            ...dataPemasukan
                        ]
                    }
                },
                { text: '\n\n' },
                { text: '2. Rincian Biaya Operasional / Pengeluaran', style: 'sectionHeader' },
                {
                    style: 'tableStyle',
                    table: {
                        widths: ['6%', '15%', '16%', '15%', '17%', '11%', '20%'],
                        headerRows: 1,
                        body: [
                            [
                                { text: 'No', style: 'tableHeaderKeluar' },
                                { text: 'Tanggal', style: 'tableHeaderKeluar' },
                                { text: 'Asal Lahan', style: 'tableHeaderKeluar' },
                                { text: 'Jenis Biaya', style: 'tableHeaderKeluar' },
                                { text: 'Jumlah Biaya', style: 'tableHeaderKeluar', alignment: 'right' },
                                { text: 'Bukti', style: 'tableHeaderKeluar' },
                                { text: 'Keterangan', style: 'tableHeaderKeluar' }
                            ],
                            ...dataPengeluaran
                        ]
                    }
                }
            ],
            styles: {
                docTitle: { fontSize: 14, bold: true, alignment: 'center', color: '#214122', margin: [0, 0, 0, 2] },
                docSub: { fontSize: 9, color: '#6b7280', margin: [0, 0, 0, 10] },
                sectionHeader: { fontSize: 11, bold: true, color: '#1f2937', margin: [0, 0, 0, 6] },
                tableStyle: { margin: [0, 2, 0, 10], fontSize: 9 },
                tableHeaderMasuk: { fillColor: '#214122', color: 'white', bold: true, alignment: 'center', padding: [5, 5] },
                tableHeaderKeluar: { fillColor: '#991b1b', color: 'white', bold: true, alignment: 'center', padding: [5, 5] }
            }
        };

        pdfMake.createPdf(docDefinition).download('Laporan_Keuangan_' + namaPetani.replace(/\s+/g, '_') + '.pdf');
    }
</script>

<style>
    .dataTables_wrapper { font-size: 0.75rem; }
    table.dataTable { border-collapse: collapse !important; border-spacing: 0 !important; width: 100% !important; margin: 0 !important; }
    table.dataTable thead th { border-bottom: 1px solid #e5e7eb !important; }

    .dataTables_filter { float: none !important; text-align: left !important; }
    .dataTables_filter label { font-size: 0 !important; position: relative; display: block; }
    .dataTables_filter input[type="search"] {
        border: 1px solid #e5e7eb !important;
        border-radius: 8px !important;
        padding: 6px 14px 6px 36px !important;
        font-size: 0.75rem !important;
        outline: none !important;
        width: 240px !important;
        margin-left: 0 !important;
        background-color: #ffffff !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: 12px center !important;
        background-size: 16px !important;
        transition: all 0.2s ease;
    }
    .dataTables_filter input[type="search"]:focus { border-color: #214122 !important; box-shadow: 0 0 0 1px #214122 !important; }

    .dataTables_wrapper .dataTables_info { font-size: 0.85rem !important; color: #6b7280 !important; font-style: italic !important; padding-top: 0 !important; }

    /* Navigasi Pagination Hijau Tema Petani */
    .dataTables_wrapper .dataTables_paginate { padding-top: 0 !important; display: flex !important; gap: 0.25rem !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid #d1d5db !important; border-radius: 0.375rem !important; padding: 0.35rem 0.75rem !important;
        margin-left: 0 !important; font-size: 0.85rem !important; background: #ffffff !important; color: #374151 !important;
        font-weight: 500 !important; transition: all 0.15s ease;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover { background: #f3f4f6 !important; color: #111827 !important; border-color: #9ca3af !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { background: #214122 !important; color: #ffffff !important; border-color: #214122 !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover { background: #f9fafb !important; color: #9ca3af !important; border-color: #e5e7eb !important; cursor: not-allowed !important; }

    /* =========================================
       KHUSUS MODE HP (max-width: 640px)
       ========================================= */
    @media (max-width: 640px) {
        .dataTables_wrapper .flex.justify-between {
            width: 100% !important;
        }
        .dataTables_filter {
            width: 100% !important;
        }
        .dataTables_filter label {
            width: 100% !important;
        }
        .dataTables_filter input[type="search"] {
            width: 100% !important;
            max-width: 100% !important;
        }
    }
</style>