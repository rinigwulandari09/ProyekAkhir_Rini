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
                <h2 class="text-md font-extrabold text-gray-800 mt-0.5">
                    Nama Petani : <span id="namaPetaniCetak" class="font-medium text-gray-600">{{ $petani->petani_nama ?? $petani->nama ?? 'Dhini Handayani' }}</span>
                </h2>
            </div>
            {{-- Tombol Utama pemicu download --}}
            <div class="flex flex-col sm:flex-row gap-2">
                <button onclick="exportExcelLaporan()" class="bg-white text-[#107C41] border border-[#107C41] px-4 py-2 rounded-xl text-xs font-bold flex items-center justify-center gap-2 hover:bg-[#107C41] hover:text-white transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                    Export Excel
                </button>
                <button onclick="exportSemuaLaporan()" class="bg-[#214122] text-white px-4 py-2 rounded-xl text-xs font-bold flex items-center justify-center gap-2 hover:bg-[#325934] transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                    Export PDF
                </button>
            </div>
        </div>

        {{-- Filter Bar Modern --}}
        <div class="bg-gray-50/50 rounded-2xl border border-gray-200 p-4 sm:p-5 mb-6">
            {{-- Header Mini Filter --}}
            <div class="flex items-center gap-2 mb-4 text-gray-700">
                <x-heroicon-o-funnel class="w-4 h-4 text-[#214122]" />
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-600">Filter Pencarian Data</h2>
            </div>

            <form action="{{ url()->current() }}" method="GET" class="space-y-4">
                @php
                    $namaBulan = [1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'];
                @endphp
                {{-- BARIS FILTER DROPDOWN --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Dropdown 1 --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 pl-1">Dari Bulan</label>
                        <div class="relative">
                            <select name="bulan_awal" class="w-full bg-white border border-gray-200 text-gray-700 text-sm rounded-xl px-3.5 py-2.5 outline-none focus:border-[#214122] transition appearance-none cursor-pointer">
                                <option value="">Pilih Bulan</option>
                                @foreach($namaBulan as $num => $name)
                                    <option value="{{ $num }}" {{ request('bulan_awal') == $num ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
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
                            <select name="bulan_akhir" class="w-full bg-white border border-gray-200 text-gray-700 text-sm rounded-xl px-3.5 py-2.5 outline-none focus:border-[#214122] transition appearance-none cursor-pointer">
                                <option value="">Pilih Bulan</option>
                                @foreach($namaBulan as $num => $name)
                                    <option value="{{ $num }}" {{ request('bulan_akhir') == $num ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
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
                            <select name="tahun" class="w-full bg-white border border-gray-200 text-gray-700 text-sm rounded-xl px-3.5 py-2.5 outline-none focus:border-[#214122] transition appearance-none cursor-pointer">
                                <option value="">Pilih Tahun</option>
                                @for($y = date('Y'); $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-gray-400">
                                <x-heroicon-o-chevron-down class="w-4 h-4" />
                            </div>
                        </div>
                    </div>

                    {{-- Dropdown 4 (Lahan) --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-gray-500 pl-1">Pilih Lahan</label>
                        <div class="relative">
                            <select name="lahan_id" id="filterLahan" class="w-full bg-white border border-gray-200 text-gray-700 text-sm rounded-xl px-3.5 py-2.5 outline-none focus:border-[#214122] transition appearance-none cursor-pointer">
                                <option value="">Semua Lahan</option>
                                @foreach($lahans as $lahan)
                                    <option value="{{ $lahan->lahan_id }}" {{ request('lahan_id') == $lahan->lahan_id ? 'selected' : '' }}>
                                        {{ $lahan->lahan_nama ?: 'Lahan ' . $loop->iteration }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-gray-400">
                                <x-heroicon-o-chevron-down class="w-4 h-4" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BARIS TOMBOL AKSI --}}
                <div class="flex flex-col sm:flex-row items-center gap-3 pt-4 border-t border-gray-100">
                    <button type="submit" class="w-full sm:w-auto bg-[#214122] text-white px-6 py-2.5 rounded-xl font-semibold text-sm hover:bg-[#325a33] transition shadow-sm text-center flex items-center justify-center gap-2">
                        <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                        Terapkan Filter
                    </button>
                    @if(request('bulan_awal') || request('bulan_akhir') || request('tahun') || request('lahan_id'))
                        <a href="{{ url()->current() }}" class="text-sm font-semibold text-red-500 hover:text-red-700 hover:underline">Reset Filter</a>
                    @endif
                </div>
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
        <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6 border border-gray-200 mb-6 keuangan-wrapper">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4 header-tabel-custom-masuk">
                <h3 class="font-bold text-gray-700 text-base flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-green-600 rounded"></span> Tabel Pemasukan (Produksi)
                </h3>
            </div>
            <div class="overflow-x-auto w-full">
                <table id="tabelPemasukan" class="w-full text-left border-collapse display responsive nowrap">
                <thead>
                    <tr class="bg-[#D4AF37] border-b border-[#B8860B] shadow-sm text-black">
                        <th class="p-4 text-xs font-bold text-[#214122] uppercase text-center w-12">No</th>
                        <th class="p-4 text-xs font-bold text-[#214122] uppercase">Tanggal</th>
                        <th class="p-4 text-xs font-bold text-[#214122] uppercase">Asal Lahan</th>
                        <th class="p-4 text-xs font-bold text-[#214122] uppercase text-center">Jumlah TBS</th>
                        <th class="p-4 text-xs font-bold text-[#214122] uppercase text-right">Harga TBS</th>
                        <th class="p-4 text-xs font-bold text-[#214122] uppercase text-right">Total Pendapatan</th>
                        <th class="p-4 text-xs font-bold text-[#214122] uppercase text-center">Bukti Nota</th>
                        <th class="p-4 text-xs font-bold text-[#214122] uppercase">Keterangan</th>
                        @if(auth()->user()->user_role === 'super_admin')
                        <th class="p-4 text-xs font-bold text-[#214122] uppercase text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($pemasukan as $masuk)
                        @if($masuk->detailProduksi && $masuk->detailProduksi->count() > 0)
                            @foreach($masuk->detailProduksi as $dp)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4 text-xs text-center text-gray-500 font-mono"></td>
                                <td class="p-4 text-xs text-gray-800 font-medium" data-order="{{ $masuk->produksi_tanggal }}">
                                    {{ $masuk->produksi_tanggal ? date('Y-m-d', strtotime($masuk->produksi_tanggal)) : '-' }}
                                </td>
                                <td class="p-4 text-xs text-gray-800 font-medium">{{ $dp->lahan->lahan_nama ?? '-' }}</td>
                                <td class="p-4 text-xs text-gray-800 font-medium text-justify">{{ $dp->jumlah_tbs ? number_format($dp->jumlah_tbs, 2, ',', '.') . ' Kg' : '-' }}</td>
                                <td class="p-4 text-xs text-gray-800 font-medium text-justify">Rp {{ number_format($masuk->harga_tbs ?? 0, 0, ',', '.') }}</td>
                                <td class="p-4 text-xs text-green-600 font-bold text-justify pr-6">
                                    Rp {{ number_format($dp->subtotal_pendapatan, 0, ',', '.') }}
                                </td>
                                <td class="p-4 text-xs text-justify">
                                    @if($masuk->produksi_bukti)
                                        <a href="{{ Storage::url($masuk->produksi_bukti) }}" target="_blank" class="text-blue-600 hover:text-blue-800 transition" title="Lihat Bukti">
                                            <x-heroicon-o-document-text class="w-5 h-5" />
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="p-4 text-xs text-gray-500 max-w-xs truncate" title="{{ $masuk->produksi_ket ?? $masuk->keterangan }}">
                                    {{ $masuk->produksi_ket ?? $masuk->keterangan ?? '-' }}
                                </td>
                                @if(auth()->user()->user_role === 'super_admin')
                                <td class="p-4 text-xs text-justify">
                                    <a href="{{ route('produksi.edit', $masuk->id ?? $masuk->produksi_id) }}" class="p-1.5 bg-[#184D2E]/10 text-[#184D2E] hover:bg-[#184D2E] hover:text-white rounded-lg transition-colors border border-[#184D2E]/20 inline-block" title="Edit Data">
                                        <x-heroicon-o-pencil-square class="w-5 h-5" />
                                    </a>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        @else
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4 text-xs text-center text-gray-500 font-mono"></td>
                                <td class="p-4 text-xs text-gray-800 font-medium" data-order="{{ $masuk->produksi_tanggal }}">
                                    {{ $masuk->produksi_tanggal ? date('Y-m-d', strtotime($masuk->produksi_tanggal)) : '-' }}
                                </td>
                                <td class="p-4 text-xs text-gray-800 font-medium">{{ $masuk->lahan->lahan_nama ?? '-' }}</td>
                                <td class="p-4 text-xs text-gray-800 font-medium text-justify">{{ $masuk->jumlah_tbs ? number_format($masuk->jumlah_tbs, 2, ',', '.') . ' Kg' : '-' }}</td>
                                <td class="p-4 text-xs text-gray-800 font-medium text-justify">Rp {{ number_format($masuk->harga_tbs ?? 0, 0, ',', '.') }}</td>
                                <td class="p-4 text-xs text-green-600 font-bold text-justify pr-6">
                                    Rp {{ number_format($masuk->total_pendapatan, 0, ',', '.') }}
                                </td>
                                <td class="p-4 text-xs text-justify">
                                    @if($masuk->produksi_bukti)
                                        <a href="{{ Storage::url($masuk->produksi_bukti) }}" target="_blank" class="text-blue-600 hover:text-blue-800 transition" title="Lihat Bukti">
                                            <x-heroicon-o-document-text class="w-5 h-5" />
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="p-4 text-xs text-gray-500 max-w-xs truncate" title="{{ $masuk->produksi_ket ?? $masuk->keterangan }}">
                                    {{ $masuk->produksi_ket ?? $masuk->keterangan ?? '-' }}
                                </td>
                                @if(auth()->user()->user_role === 'super_admin')
                                <td class="p-4 text-xs text-justify">
                                    <a href="{{ route('produksi.edit', $masuk->id ?? $masuk->produksi_id) }}" class="p-1.5 bg-[#184D2E]/10 text-[#184D2E] hover:bg-[#184D2E] hover:text-white rounded-lg transition-colors border border-[#184D2E]/20 inline-block" title="Edit Data">
                                        <x-heroicon-o-pencil-square class="w-5 h-5" />
                                    </a>
                                </td>
                                @endif
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel Pengeluaran Section --}}
        <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6 border border-gray-200 mb-6 keuangan-wrapper">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4 header-tabel-custom-keluar">
                <h3 class="font-bold text-gray-700 text-base flex items-center gap-2">
                    <span class="w-1.5 h-5 bg-red-500 rounded"></span> Tabel Pengeluaran (Operasional)
                </h3>
            </div>
            <div class="overflow-x-auto w-full">
                <table id="tabelPengeluaran" class="w-full text-left border-collapse display responsive nowrap">
                <thead>
                    <tr class="bg-[#FFE4E6] border-b border-gray-200">
                        <th class="p-4 text-xs font-bold text-[#991B1B] uppercase text-center w-12">No</th>
                        <th class="p-4 text-xs font-bold text-[#991B1B] uppercase">Tanggal</th>
                        <th class="p-4 text-xs font-bold text-[#991B1B] uppercase">Asal Lahan</th>
                        <th class="p-4 text-xs font-bold text-[#991B1B] uppercase">Jenis Biaya</th>
                        <th class="p-4 text-xs font-bold text-[#991B1B] uppercase">Nama Biaya</th>
                        <th class="p-4 text-xs font-bold text-[#991B1B] uppercase text-center">Jumlah / Qty</th>
                        <th class="p-4 text-xs font-bold text-[#991B1B] uppercase text-right">Total Biaya</th>
                        <th class="p-4 text-xs font-bold text-[#991B1B] uppercase text-center">Bukti</th>
                        <th class="p-4 text-xs font-bold text-[#991B1B] uppercase">Keterangan</th>
                        @if(auth()->user()->user_role === 'super_admin')
                        <th class="p-4 text-xs font-bold text-[#991B1B] uppercase text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($pengeluaran as $keluar)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-xs text-center text-gray-500 font-mono"></td>
                        <td class="p-4 text-xs text-gray-800 font-medium" data-order="{{ $keluar->biaya_tanggal }}">
                            {{ $keluar->biaya_tanggal ? date('Y-m-d', strtotime($keluar->biaya_tanggal)) : '-' }}
                        </td>
                        <td class="p-4 text-xs text-gray-800 font-medium">
                            {{ $keluar->lahan->lahan_nama ?? '-' }}
                        </td>
                        <td class="p-4 text-xs text-gray-800 font-medium">
                            {{ $keluar->biaya_jenis ?? '-' }}
                        </td>
                        <td class="p-4 text-xs text-gray-800 font-medium">
                            {{ $keluar->biaya_nama ?? '-' }}
                        </td>
                        <td class="p-4 text-xs text-gray-800 font-medium text-center">
                            {{ $keluar->biaya_jumlah ?? '-' }}
                        </td>
                        <td class="p-4 text-xs text-red-500 font-bold text-justify pr-6">
                            Rp {{ number_format($keluar->biaya_total, 0, ',', '.') }}
                        </td>
                        <td class="p-4 text-xs text-justify">
                            @if($keluar->biaya_bukti)
                                <a href="{{ Storage::url($keluar->biaya_bukti) }}" target="_blank" class="text-blue-600 hover:text-blue-800 transition" title="Lihat Bukti">
                                    <x-heroicon-o-document-text class="w-5 h-5" />
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="p-4 text-xs text-gray-500 max-w-xs truncate" title="{{ $keluar->biaya_keterangan ?? $keluar->biaya_ket ?? '-' }}">
                            {{ $keluar->biaya_keterangan ?? $keluar->biaya_ket ?? '-' }}
                        </td>
                        @if(auth()->user()->user_role === 'super_admin')
                        <td class="p-4 text-xs text-justify">
                            <a href="{{ route('biaya_operasional.edit', $keluar->id ?? $keluar->biaya_id ?? $keluar->biaya_operasional_id) }}" class="p-1.5 bg-[#184D2E]/10 text-[#184D2E] hover:bg-[#184D2E] hover:text-white rounded-lg transition-colors border border-[#184D2E]/20 inline-block" title="Edit Data">
                                <x-heroicon-o-pencil-square class="w-5 h-5" />
                            </a>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Dependensi DataTables & Ekstensi PDFMake --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    var tableMasuk, tableKeluar;

    $(document).ready(function() {
        var konfigurasiBahasa = {
            "search": "",
            "searchPlaceholder": "Search data...",
            "lengthMenu": "<span class='text-gray-500 font-medium'>Show</span> _MENU_ <span class='text-gray-500 font-medium'>entries</span>",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "Showing 0 to 0 of 0 entries",
            "infoFiltered": "(filtered from _MAX_ total entries)",
            "emptyTable": "Belum ada data transaksi.",
            "zeroRecords": "No matching records found",
            "paginate": { "next": "Next", "previous": "Prev" }
        };

        var dtConfig = {
            "pageLength": 5,
            "lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
            "language": konfigurasiBahasa,
            "columnDefs": [
                { "orderable": false, "searchable": false, "targets": 0 },
                { "className": "text-center all", "targets": 0 }, 
                { "className": "all", "targets": 1 },            
                { "className": "min-tablet", "targets": '_all' }
            ],
            "order": [[1, 'desc']],
            "responsive": {
                "details": {
                    "renderer": function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col) {
                            if (col.hidden) {
                                var value = col.data === null || col.data === '' ? '-' : col.data;
                                return '<div class="flex items-start justify-between gap-3 py-2 text-xs border-b border-gray-100 last:border-0"><span class="font-bold text-gray-600">' + col.title + '</span><span class="text-gray-700 text-right">' + value + '</span></div>';
                            }
                            return '';
                        }).join('');
                        return data ? $('<div class="rounded-lg bg-gray-50 p-3 shadow-inner w-full mt-2"></div>').append(data) : false;
                    }
                }
            },
            // DOM: Menyembunyikan length & filter dari layout default agar bisa dipindah manual
            "dom": '<"hidden" l f>rt<"flex flex-col sm:flex-row justify-between items-center gap-4 mt-4 pt-4 border-t border-gray-100"i p>'
        };

        // Inisialisasi
        tableMasuk = $('#tabelPemasukan').DataTable(dtConfig);
        tableKeluar = $('#tabelPengeluaran').DataTable(dtConfig);

        // Auto Numbering
        [tableMasuk, tableKeluar].forEach(function(table) {
            table.on('order.dt search.dt draw.dt', function () {
                let info = table.page.info();
                table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = info.start + i + 1;
                });
            }).draw();
        });

        // PINDAHKAN FILTER & ENTRIES KE HEADER TABEL
        function moveControls(tableId, headerClass) {
            // Buat container flexbox untuk membungkus Show Entries dan Search
            let controlsContainer = $('<div class="flex flex-row items-center gap-4 w-full sm:w-auto justify-between sm:justify-end mt-4 sm:mt-0"></div>');
            $(headerClass).append(controlsContainer);
            
            // Pindahkan elemen bawaan datatables ke dalam container baru
            controlsContainer.append($('#' + tableId + '_length'));
            controlsContainer.append($('#' + tableId + '_filter'));
        }

        moveControls('tabelPemasukan', '.header-tabel-custom-masuk');
        moveControls('tabelPengeluaran', '.header-tabel-custom-keluar');
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
                { text: cells.eq(3).text().trim(), alignment: 'center' },
                { text: cells.eq(4).text().trim(), alignment: 'right' },
                { text: cells.eq(5).text().trim(), alignment: 'right' },
                cells.eq(7).text().trim()
            ]);
        });
        if (dataPemasukan.length === 0) {
            dataPemasukan.push([{ text: 'Belum ada catatan transaksi.', colspan: 7, alignment: 'center', italic: true }, '', '', '', '', '', '']);
        }

        var dataPengeluaran = [];
        tableKeluar.rows({ search: 'applied' }).every(function (rowIdx, tableLoop, rowLoop) {
            var cells = $(this.node()).find('td');
            dataPengeluaran.push([
                rowLoop + 1,
                cells.eq(1).text().trim(),
                cells.eq(2).text().trim(),
                cells.eq(3).text().trim(),
                cells.eq(4).text().trim(),
                { text: cells.eq(5).text().trim(), alignment: 'center' },
                { text: cells.eq(6).text().trim(), alignment: 'right' },
                cells.eq(8).text().trim()
            ]);
        });
        if (dataPengeluaran.length === 0) {
            dataPengeluaran.push([{ text: 'Belum ada catatan transaksi.', colspan: 8, alignment: 'center', italic: true }, '', '', '', '', '', '', '']);
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
                        { text: [{ text: 'Lahan : ', bold: true }, $('#filterLahan option:selected').text().trim()], alignment: 'center', fontSize: 10 },
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
                        widths: ['5%', '13%', '16%', '11%', '15%', '20%', '20%'],
                        headerRows: 1,
                        body: [
                            [
                                { text: 'No', style: 'tableHeaderMasuk' },
                                { text: 'Tanggal', style: 'tableHeaderMasuk' },
                                { text: 'Asal Lahan', style: 'tableHeaderMasuk' },
                                { text: 'Jml TBS', style: 'tableHeaderMasuk' },
                                { text: 'Harga TBS', style: 'tableHeaderMasuk', alignment: 'right' },
                                { text: 'Total Pendapatan', style: 'tableHeaderMasuk', alignment: 'right' },
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
                        widths: ['4%', '13%', '13%', '13%', '16%', '6%', '15%', '20%'],
                        headerRows: 1,
                        body: [
                            [
                                { text: 'No', style: 'tableHeaderKeluar' },
                                { text: 'Tanggal', style: 'tableHeaderKeluar' },
                                { text: 'Asal Lahan', style: 'tableHeaderKeluar' },
                                { text: 'Jenis Biaya', style: 'tableHeaderKeluar' },
                                { text: 'Nama Biaya', style: 'tableHeaderKeluar' },
                                { text: 'Qty', style: 'tableHeaderKeluar' },
                                { text: 'Total Biaya', style: 'tableHeaderKeluar', alignment: 'right' },
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

    // FUNGSI UTAMA EXPORT LAPORAN GABUNGAN KE EXCEL
    function exportExcelLaporan() {
        var namaPetani = $('#namaPetaniCetak').text().trim();
        var wb = XLSX.utils.book_new();

        // Sheet 1: Pemasukan
        var wsDataMasuk = [
            ["LAPORAN PEMASUKAN (PRODUKSI)"],
            ["Nama Petani", namaPetani],
            ["Filter Lahan", $('#filterLahan option:selected').text().trim()],
            ["Tanggal Unduh", new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })],
            [],
            ["No", "Tanggal", "Asal Lahan", "Jumlah TBS (Kg)", "Harga TBS (Rp)", "Total Pendapatan (Rp)", "Keterangan"]
        ];

        tableMasuk.rows({ search: 'applied' }).every(function (rowIdx, tableLoop, rowLoop) {
            var cells = $(this.node()).find('td');
            var jumlahClean = cells.eq(3).text().replace(/[^0-9]/g, '');
            var hargaClean = cells.eq(4).text().replace(/[^0-9]/g, '');
            var pendapatanClean = cells.eq(5).text().replace(/[^0-9]/g, '');
            wsDataMasuk.push([
                rowLoop + 1,
                cells.eq(1).text().trim(),
                cells.eq(2).text().trim(),
                jumlahClean ? parseInt(jumlahClean) : 0,
                hargaClean ? parseInt(hargaClean) : 0,
                pendapatanClean ? parseInt(pendapatanClean) : 0,
                cells.eq(7).text().trim()
            ]);
        });

        if (wsDataMasuk.length === 6) {
            wsDataMasuk.push(["Belum ada catatan transaksi."]);
        }
        var wsMasuk = XLSX.utils.aoa_to_sheet(wsDataMasuk);
        XLSX.utils.book_append_sheet(wb, wsMasuk, "Pemasukan");

        // Sheet 2: Pengeluaran
        var wsDataKeluar = [
            ["LAPORAN PENGELUARAN (OPERASIONAL)"],
            ["Nama Petani", namaPetani],
            ["Filter Lahan", $('#filterLahan option:selected').text().trim()],
            ["Tanggal Unduh", new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })],
            [],
            ["No", "Tanggal", "Asal Lahan", "Jenis Biaya", "Nama Biaya", "Qty", "Total Biaya (Rp)", "Keterangan"]
        ];

        tableKeluar.rows({ search: 'applied' }).every(function (rowIdx, tableLoop, rowLoop) {
            var cells = $(this.node()).find('td');
            var pengeluaranClean = cells.eq(6).text().replace(/[^0-9]/g, '');
            wsDataKeluar.push([
                rowLoop + 1,
                cells.eq(1).text().trim(),
                cells.eq(2).text().trim(),
                cells.eq(3).text().trim(),
                cells.eq(4).text().trim(),
                cells.eq(5).text().trim(),
                pengeluaranClean ? parseInt(pengeluaranClean) : 0,
                cells.eq(8).text().trim()
            ]);
        });

        if (wsDataKeluar.length === 6) {
            wsDataKeluar.push(["Belum ada catatan transaksi."]);
        }
        var wsKeluar = XLSX.utils.aoa_to_sheet(wsDataKeluar);
        XLSX.utils.book_append_sheet(wb, wsKeluar, "Pengeluaran");

        // Download
        XLSX.writeFile(wb, 'Laporan_Keuangan_' + namaPetani.replace(/\s+/g, '_') + '.xlsx');
    }
</script>

<style>
    /* Reset & Base Tabel */
    .dataTables_wrapper { font-size: 0.75rem; width: 100%; }
    table.dataTable { border-collapse: collapse !important; border-spacing: 0 !important; width: 100% !important; margin: 0 !important; }
    table.dataTable thead th { border-bottom: 1px solid #e5e7eb !important; }
    
    #tabelPemasukan th, #tabelPemasukan td, #tabelPengeluaran th, #tabelPengeluaran td { white-space: nowrap !important; }

    /* CUSTOM ICON PLUS (+) HANYA MUNCUL DI HP */
    @media (max-width: 640px) {
        table.dataTable.dtr-inline.collapsed > tbody > tr:not(.child) > td:first-child {
            position: relative;
            padding-left: 32px !important;
            cursor: pointer;
        }
        table.dataTable.dtr-inline.collapsed > tbody > tr:not(.child) > td:first-child::before {
            content: '+' !important;
            position: absolute;
            top: 50% !important;
            left: 8px !important;
            transform: translateY(-50%) !important;
            background-color: #234323 !important;
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
        table.dataTable.dtr-inline.collapsed > tbody > tr.parent > td:first-child::before { content: '-' !important; background-color: #dc2626 !important; }
    }

    /* =========================================
       STYLING SHOW ENTRIES & SEARCH (PERBAIKAN)
       ========================================= */
    /* Container Show Entries */
    .dataTables_length { margin: 0 !important; }
    .dataTables_length label { 
        display: flex !important; 
        align-items: center !important; 
        gap: 0.5rem !important; 
        font-size: 0.75rem !important; 
        margin: 0 !important; 
    }
    /* Select Dropdown */
    .dataTables_length select { 
        font-size: 0.75rem !important; 
        color: #374151 !important; 
        border: 1px solid #d1d5db !important; 
        background-color: #f9fafb !important;
        border-radius: 0.5rem !important; 
        padding: 0.25rem 1.5rem 0.25rem 0.75rem !important; 
        margin: 0 !important; 
        outline: none !important; 
        cursor: pointer;
        transition: border-color 0.2s;
    }
    .dataTables_length select:focus { border-color: #214122 !important; box-shadow: 0 0 0 1px #214122 !important; }

    /* Container Search */
    .dataTables_filter { margin: 0 !important; }
    .dataTables_filter label { 
        display: flex !important; 
        align-items: center !important;
        font-size: 0 !important; /* Menyembunyikan teks label "Search:" bawaan */
        margin: 0 !important; 
    }
    /* Input Search */
    .dataTables_filter input[type="search"] {
        border: 1px solid #d1d5db !important;
        background-color: #f9fafb !important;
        border-radius: 9999px !important; /* Bentuk kapsul */
        padding: 0.35rem 1rem 0.35rem 2.25rem !important;
        font-size: 0.75rem !important;
        color: #374151 !important;
        outline: none !important;
        width: 180px !important;
        margin-left: 0 !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: 0.6rem center !important;
        background-size: 1rem !important;
        transition: all 0.2s ease;
    }
    .dataTables_filter input[type="search"]:focus { 
        border-color: #214122 !important; 
        background-color: #ffffff !important;
        box-shadow: 0 0 0 1px #214122 !important; 
    }

   /* =========================================
       PAGINATION & INFO
       ========================================= */
    .dataTables_wrapper .dataTables_info { font-size: 0.8rem !important; color: #6b7280 !important; font-style: italic !important; padding-top: 0 !important; }
    .dataTables_wrapper .dataTables_paginate { padding-top: 0 !important; display: flex !important; gap: 0.25rem !important; }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid #d1d5db !important; 
        border-radius: 0.375rem !important; 
        padding: 0.25rem 0.6rem !important;
        margin-left: 0 !important; 
        font-size: 0.8rem !important; 
        background: #ffffff !important; 
        color: #374151 !important; /* Warna teks normal */
        font-weight: 500 !important; 
        transition: all 0.15s ease;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover { 
        background: #f3f4f6 !important; 
        color: #111827 !important; 
        border-color: #9ca3af !important; 
    }

    /* INI BAGIAN YANG DIPERBAIKI */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover { 
        background: #214122 !important; /* Hijau Tua Anda */
        color: #ffffff !important;      /* Teks jadi PUTIH */
        border-color: #214122 !important; 
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover { 
        background: #f9fafb !important; 
        color: #9ca3af !important; 
        border-color: #e5e7eb !important; 
        cursor: not-allowed !important; 
    }
    /* =========================================
       RESPONSIVE (HP)
       ========================================= */
    @media (max-width: 640px) {
        .header-tabel-custom-masuk, .header-tabel-custom-keluar { flex-direction: column !important; align-items: flex-start !important; gap: 0.5rem !important; }
        .header-tabel-custom-masuk > div:last-child, .header-tabel-custom-keluar > div:last-child { 
            width: 100% !important; 
            justify-content: space-between !important;
            margin-top: 0.5rem !important;
        }

        /* Sembunyikan teks "Show" dan "entries" agar hanya tampil kotak angkanya di HP */
        .dataTables_length label { gap: 0 !important; }
        .dataTables_length label span { display: none !important; }
        .dataTables_length select { width: 70px !important; padding: 0.25rem 0.5rem !important; text-align: center; margin: 0 !important; }

        /* Pencarian di HP */
        .dataTables_filter { flex: 1; display: flex; justify-content: flex-end; }
        .dataTables_filter input[type="search"] { width: 100% !important; max-width: 160px !important; }
        
        /* Pagination & Info di HP */
        .dataTables_wrapper .dataTables_info, 
        .dataTables_wrapper .dataTables_paginate { width: 100% !important; justify-content: center !important; text-align: center !important; margin-top: 8px !important; flex-wrap: wrap !important; }

        table.dataTable.dtr-inline.collapsed > tbody > tr:not(.child) > td:first-child { position: relative; padding-left: 32px !important; cursor: pointer; }
        table.dataTable.dtr-inline.collapsed > tbody > tr:not(.child) > td:first-child::before { 
            content: '+' !important; position: absolute; top: 50% !important; left: 8px !important; transform: translateY(-50%) !important; 
            background-color: #234323 !important; color: white !important; width: 16px !important; height: 16px !important; 
            display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 9999px !important; 
            font-weight: bold !important; font-size: 14px !important; line-height: 1 !important; box-shadow: 0 1px 2px rgba(0,0,0,0.2) !important; 
        }
        table.dataTable.dtr-inline.collapsed > tbody > tr.parent > td:first-child::before { content: '-' !important; background-color: #dc2626 !important; }
    }
</style>