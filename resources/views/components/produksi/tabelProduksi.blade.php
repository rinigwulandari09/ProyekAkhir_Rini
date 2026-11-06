<div class="p-2 space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 text-[11px] font-bold bg-[#D4AF37]/20 text-[#214122] rounded-full border border-[#D4AF37]/40 tracking-wide uppercase">
                    Sertifikasi RSPO / ISPO
                </span>
                <span class="text-xs text-gray-500 font-medium">Periode 12 Bulan (Sep {{ $tahun - 1 }} - Agu {{ $tahun }})</span>
            </div>
            <h1 class="text-2xl font-bold text-[#214122] mt-1.5 font-poppins">Data & Tonase Produksi Per Plot</h1>
            <p class="text-sm text-gray-500">Basic Info dan Rekapitulasi Tonase Produksi Petani Asosiasi PSKS Pelalawan Siak.</p>
        </div>

        {{-- Tombol Ekspor Excel Utama --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('produksi.export', request()->query()) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#184D2E] text-white rounded-xl hover:bg-green-900 transition shadow-sm font-semibold text-xs">
                <x-heroicon-o-arrow-down-tray class="w-4 h-4 text-[#D4AF37]" />
                <span>Export Excel RSPO (.xlsx)</span>
            </a>
        </div>
    </div>

    {{-- Cards Summary Section --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Total Plot / Blok --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <x-heroicon-o-map class="w-6 h-6" />
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider font-poppins">Total Plot / Blok</p>
                <p class="text-2xl font-bold text-gray-800 font-poppins">{{ count($rows) }} <span class="text-xs font-normal text-gray-500">Plot</span></p>
            </div>
        </div>

        {{-- Card 2: Total Luas Lahan --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <x-heroicon-o-square-3-stack-3d class="w-6 h-6" />
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider font-poppins">Total Luas Lahan</p>
                <p class="text-2xl font-bold text-gray-800 font-poppins">{{ number_format($totalAreaSum, 2, ',', '.') }} <span class="text-xs font-normal text-gray-500">Ha</span></p>
            </div>
        </div>

        {{-- Card 3: Total Produksi (Ton) --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <x-heroicon-o-archive-box class="w-6 h-6" />
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider font-poppins">Total Produksi (12 Bln)</p>
                <p class="text-2xl font-bold text-[#214122] font-poppins">{{ number_format($overallTon, 2, ',', '.') }} <span class="text-xs font-normal text-gray-500">Ton</span></p>
            </div>
        </div>

        {{-- Card 4: Rata-Rata YPH --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <x-heroicon-o-chart-bar class="w-6 h-6" />
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider font-poppins">Produktivitas (YPH)</p>
                <p class="text-2xl font-bold text-purple-700 font-poppins">{{ number_format($overallYph, 2, ',', '.') }} <span class="text-xs font-normal text-gray-500">Ton/Ha/Thn</span></p>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-5 border border-gray-200">
        <form action="{{ route('produksi.index') }}" method="GET" class="space-y-4">
            <div class="flex items-center gap-2 text-gray-700">
                <x-heroicon-o-funnel class="w-4 h-4 text-[#D4AF37]" />
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-600 font-poppins">Filter Periode Audit & Wilayah</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Dropdown Periode Tahun Audit --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider pl-1 font-poppins">Tahun Periode Audit</label>
                    <div class="relative">
                        <select name="tahun" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl p-2.5 outline-none focus:ring-[#234323] focus:border-[#234323] transition appearance-none cursor-pointer">
                            @foreach([2027, 2026, 2025, 2024, 2023] as $y)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                    Tahun {{ $y }} (Sep {{ $y - 1 }} - Agu {{ $y }})
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                            <x-heroicon-o-chevron-down class="w-4 h-4" />
                        </div>
                    </div>
                </div>

                {{-- Dropdown Desa (Hanya jika Super Admin atau Desa belum terikat) --}}
                @if(auth()->user()->user_role === 'super_admin')
                <div class="flex flex-col gap-1.5">
                    <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider pl-1 font-poppins">Wilayah Desa</label>
                    <div class="relative">
                        <select name="desa_id" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl p-2.5 outline-none focus:ring-[#234323] focus:border-[#234323] transition appearance-none cursor-pointer">
                            <option value="">Semua Desa</option>
                            @foreach($desas as $desa)
                                <option value="{{ $desa->desa_id }}" {{ $desaId == $desa->desa_id ? 'selected' : '' }}>
                                    {{ $desa->desa_nama }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                            <x-heroicon-o-chevron-down class="w-4 h-4" />
                        </div>
                    </div>
                </div>
                @endif

                {{-- Input Pencarian Server --}}
                <div class="flex flex-col gap-1.5 {{ auth()->user()->user_role === 'super_admin' ? 'sm:col-span-2 lg:col-span-1' : 'sm:col-span-2' }}">
                    <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider pl-1 font-poppins">Cari Petani / ID Blok</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Ketik nama atau blok..."
                               class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl pl-9 pr-3.5 py-2.5 outline-none focus:ring-[#234323] focus:border-[#234323] transition">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                            <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                        </div>
                    </div>
                </div>

                {{-- Tombol Filter --}}
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-[#234323] hover:bg-[#184D2E] text-white text-xs font-bold rounded-xl transition shadow-xs">
                        <x-heroicon-o-funnel class="w-4 h-4 text-[#D4AF37]" />
                        <span>Filter</span>
                    </button>
                    @if($search || $desaId || $tahun != date('Y'))
                    <a href="{{ route('produksi.index') }}" class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl transition text-xs font-semibold" title="Reset Filter">
                        <x-heroicon-o-arrow-path class="w-4 h-4" />
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Tabel Preview Data Produksi Format RSPO (Sesuai Warna Emas SILAUSA) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        {{-- Card Header --}}
        <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-gray-50/60">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-[#214122]/10 text-[#214122] flex items-center justify-center shrink-0">
                    <x-heroicon-o-table-cells class="w-4 h-4 text-[#214122]" />
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-sm font-poppins">Tabel Matriks Produksi & Audit RSPO Tahun {{ $tahun }}</h3>
                    <p class="text-xs text-gray-500">Menampilkan tonase bulanan (Kg), total tahunan (Ton), dan produktivitas (YPH) per plot lahan.</p>
                </div>
            </div>
            <a href="{{ route('produksi.export', request()->query()) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#184D2E] hover:text-green-900 bg-[#D4AF37]/15 hover:bg-[#D4AF37]/25 border border-[#D4AF37]/40 px-3.5 py-1.5 rounded-xl transition shadow-xs">
                <x-heroicon-o-arrow-down-tray class="w-3.5 h-3.5 text-[#214122]" />
                <span>Unduh File Excel</span>
            </a>
        </div>

        {{-- Toolbar: Entries Selector (Show Data) & Search Mirip Tabel User --}}
        <div class="p-4 flex flex-col sm:flex-row justify-between items-center gap-3 border-b border-gray-100 bg-white">
            {{-- Entries Selector (Show Data) --}}
            <div>
                <label class="inline-flex items-center gap-2 text-sm text-gray-700 font-medium">
                    <span>Tampilkan</span>
                    <select id="produksiPerPage" class="text-xs text-gray-800 border border-gray-200 rounded-lg px-2.5 py-1.5 outline-none bg-white focus:border-[#214122] focus:ring-1 focus:ring-[#214122] transition cursor-pointer">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="-1">Semua</option>
                    </select>
                    <span>data</span>
                </label>
            </div>

            {{-- Quick Search Box --}}
            <div class="w-full sm:w-auto">
                <div class="relative">
                    <input type="search" id="produksiTableSearch" placeholder="Search..."
                           class="text-xs text-gray-800 border border-gray-200 rounded-lg py-1.5 pl-8 pr-3 outline-none focus:border-[#214122] focus:ring-2 focus:ring-[#214122]/10 transition w-full sm:w-[220px] focus:sm:w-[260px]">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-2.5 pointer-events-none text-gray-400">
                        <x-heroicon-o-magnifying-glass class="w-3.5 h-3.5" />
                    </div>
                </div>
            </div>
        </div>

        {{-- Scrollable Container dengan Custom Scrollbar --}}
        <div class="overflow-x-auto w-full max-h-[620px] overflow-y-auto custom-table-scroll">
            <table id="tabelProduksi" class="w-full text-xs text-left border-collapse min-w-[1450px]">
                {{-- Header 2 Baris Bertema Emas SILAUSA (#D4AF37 & #B8860B) --}}
                <thead class="sticky top-0 z-20 shadow-sm border-b border-[#B8860B]">
                    {{-- Baris 1: Header Utama --}}
                    <tr class="bg-[#D4AF37] text-gray-950 font-extrabold text-xs tracking-wider">
                        <th rowspan="2" class="p-3 border border-[#B8860B] text-center w-12 bg-[#D4AF37]">No</th>
                        <th rowspan="2" class="p-3 border border-[#B8860B] text-center w-24 bg-[#D4AF37] cursor-pointer hover:bg-[#C59F2D] transition select-none group sortable-th" data-sort="id_petani" title="Klik untuk mengurutkan berdasarkan ID Petani">
                            <div class="inline-flex items-center justify-center gap-1.5 w-full">
                                <span>ID Petani</span>
                                <span class="sort-icon-wrapper transition"></span>
                            </div>
                        </th>
                        <th rowspan="2" class="p-3 border border-[#B8860B] text-center w-24 bg-[#D4AF37] cursor-pointer hover:bg-[#C59F2D] transition select-none group sortable-th" data-sort="blok" title="Klik untuk mengurutkan berdasarkan ID Blok">
                            <div class="inline-flex items-center justify-center gap-1.5 w-full">
                                <span>ID Blok</span>
                                <span class="sort-icon-wrapper transition"></span>
                            </div>
                        </th>
                        <th rowspan="2" class="p-3 border border-[#B8860B] text-left min-w-[170px] bg-[#D4AF37] cursor-pointer hover:bg-[#C59F2D] transition select-none group sortable-th" data-sort="nama" title="Klik untuk mengurutkan berdasarkan Smallholder Name">
                            <div class="inline-flex items-center gap-1.5 w-full">
                                <span>Smallholder Name</span>
                                <span class="sort-icon-wrapper transition"></span>
                            </div>
                        </th>
                        <th rowspan="2" class="p-3 border border-[#B8860B] text-center min-w-[120px] bg-[#D4AF37]">Location</th>
                        <th colspan="2" class="p-2 border border-[#B8860B] text-center bg-[#D4AF37]">Coordinate</th>
                        <th colspan="2" class="p-2 border border-[#B8860B] text-center bg-[#D4AF37]">Area (Ha)</th>
                        <th rowspan="2" class="p-3 border border-[#B8860B] text-center min-w-[90px] bg-[#D4AF37]">Planted Year</th>
                        <th colspan="12" class="p-2 border border-[#B8860B] text-center bg-[#D4AF37] text-[#214122] font-black uppercase tracking-wider text-xs">
                            {{ $tahun }} (Produksi Kg Bulanan)
                        </th>
                        <th rowspan="2" class="p-3 border border-[#B8860B] text-right min-w-[105px] bg-[#D4AF37] cursor-pointer hover:bg-[#C59F2D] transition select-none group sortable-th" data-sort="total_ton" title="Klik untuk mengurutkan Total Produksi">
                            <div class="inline-flex items-center justify-end gap-1.5 w-full">
                                <span class="text-right leading-tight">Total Produksi<br><span class="font-normal text-[10px] text-gray-800">(Ton)</span></span>
                                <span class="sort-icon-wrapper transition"></span>
                            </div>
                        </th>
                        <th rowspan="2" class="p-3 border border-[#B8860B] text-right min-w-[105px] bg-[#D4AF37] cursor-pointer hover:bg-[#C59F2D] transition select-none group sortable-th" data-sort="yph" title="Klik untuk mengurutkan YPH">
                            <div class="inline-flex items-center justify-end gap-1.5 w-full">
                                <span class="text-right leading-tight">YPH<br><span class="font-normal text-[10px] text-gray-800">(Ton/Ha/Thn)</span></span>
                                <span class="sort-icon-wrapper transition"></span>
                            </div>
                        </th>
                    </tr>

                    {{-- Baris 2: Sub-headers Koordinat, Area, dan 12 Bulan (Emas Lembut Senada #ECC867) --}}
                    <tr class="bg-[#ECC867] text-gray-900 font-bold text-[10.5px] uppercase tracking-wide">
                        {{-- Coordinate Sub-headers --}}
                        <th class="p-2 border border-[#B8860B]/60 text-center min-w-[130px] bg-[#ECC867]">Longitude (E)</th>
                        <th class="p-2 border border-[#B8860B]/60 text-center min-w-[130px] bg-[#ECC867]">Latitude (N)</th>
                        {{-- Area Sub-headers --}}
                        <th class="p-2 border border-[#B8860B]/60 text-center w-16 bg-[#ECC867]">Total</th>
                        <th class="p-2 border border-[#B8860B]/60 text-center w-16 bg-[#ECC867]">Production</th>
                        {{-- 12 Bulan Sub-headers --}}
                        @foreach($months as $m)
                            <th class="p-2 border border-[#B8860B]/60 text-center w-14 bg-[#ECC867]">{{ $m['label'] }}</th>
                        @endforeach
                    </tr>
                </thead>

                {{-- Body Tabel --}}
                <tbody id="tabelProduksiBody" class="divide-y divide-gray-200 bg-white">
                    @forelse($rows as $r)
                    <tr class="produksi-row hover:bg-amber-50/50 transition"
                        data-id-petani="{{ $r['id_petani'] }}"
                        data-nama="{{ $r['smallholder_name'] }}"
                        data-blok="{{ $r['id_blok'] }}"
                        data-total-ton="{{ $r['total_ton'] }}"
                        data-yph="{{ $r['yph'] }}">
                        <td class="kolom-nomor p-2.5 border border-gray-200 text-center font-mono text-gray-500 font-medium">{{ $r['no'] }}</td>
                        <td class="p-2.5 border border-gray-200 text-center font-mono text-xs">
                            @if(!empty($r['id_petani']) && $r['id_petani'] !== '-')
                                <span class="text-[#214122] font-bold">{{ $r['id_petani'] }}</span>
                            @else
                                <span class="text-gray-400 font-normal italic">-</span>
                            @endif
                        </td>
                        <td class="p-2.5 border border-gray-200 text-center font-bold text-[#214122] bg-emerald-50/40">{{ $r['id_blok'] }}</td>
                        <td class="p-2.5 border border-gray-200 font-semibold text-gray-900">{{ $r['smallholder_name'] }}</td>
                        <td class="p-2.5 border border-gray-200 text-center text-gray-700">{{ $r['location'] }}</td>
                        <td class="p-2.5 border border-gray-200 text-center font-mono text-[10.5px] text-gray-500 whitespace-nowrap">{{ $r['lng_dms'] }}</td>
                        <td class="p-2.5 border border-gray-200 text-center font-mono text-[10.5px] text-gray-500 whitespace-nowrap">{{ $r['lat_dms'] }}</td>
                        <td class="p-2.5 border border-gray-200 text-right font-mono text-gray-800">{{ number_format($r['area_total'], 2, ',', '.') }}</td>
                        <td class="p-2.5 border border-gray-200 text-right font-mono font-medium text-gray-800">{{ number_format($r['area_production'], 2, ',', '.') }}</td>
                        <td class="p-2.5 border border-gray-200 text-center text-gray-600">{{ $r['planted_year'] }}</td>
                        
                        {{-- 12 Bulan (Kg) --}}
                        @foreach($months as $m)
                            @php $val = $r['monthly'][$m['key']] ?? 0; @endphp
                            <td class="p-2 border border-gray-200 text-right font-mono {{ $val > 0 ? 'text-gray-900 font-medium' : 'text-gray-300' }}">
                                {{ $val > 0 ? number_format($val, 0, ',', '.') : '-' }}
                            </td>
                        @endforeach

                        {{-- Total Tonase --}}
                        <td class="p-2.5 border border-gray-200 text-right font-bold text-[#184D2E] bg-emerald-50/50">
                            {{ number_format($r['total_ton'], 2, ',', '.') }}
                        </td>

                        {{-- YPH --}}
                        <td class="p-2.5 border border-gray-200 text-right font-bold {{ $r['yph'] >= 18 ? 'text-emerald-700 bg-emerald-50/40' : ($r['yph'] >= 12 ? 'text-amber-700 bg-amber-50/30' : 'text-gray-700 bg-gray-50/30') }}">
                            {{ number_format($r['yph'], 2, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyProduksiRow">
                        <td colspan="24" class="p-10 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <x-heroicon-o-document-magnifying-glass class="w-8 h-8 text-gray-400" />
                                <p class="text-sm font-medium">Tidak ada data plot lahan atau produksi ditemukan untuk kriteria filter ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                    {{-- Baris bila hasil pencarian lokal nihil --}}
                    <tr id="noProduksiMatchRow" style="display: none;">
                        <td colspan="24" class="p-8 text-center text-gray-500 italic text-xs">
                            Tidak ditemukan data plot yang sesuai dengan kata kunci pencarian.
                        </td>
                    </tr>
                </tbody>

                {{-- Footer Total Keseluruhan (Sesuai Emas SILAUSA) --}}
                @if(count($rows) > 0)
                <tfoot class="sticky bottom-0 z-10 bg-[#FAF5E4] text-gray-900 font-bold border-t-2 border-[#D4AF37] shadow-md">
                    <tr>
                        <td colspan="7" class="p-3 border border-[#D4AF37]/50 text-center font-extrabold uppercase text-[#214122] tracking-wider text-xs">TOTAL KESELURUHAN</td>
                        <td class="p-2 border border-[#D4AF37]/50 text-right font-mono font-bold text-gray-900">{{ number_format($totalAreaSum, 2, ',', '.') }}</td>
                        <td class="p-2 border border-[#D4AF37]/50 text-right font-mono font-bold text-gray-900">{{ number_format($totalAreaSum, 2, ',', '.') }}</td>
                        <td class="p-2 border border-[#D4AF37]/50 text-center text-gray-500 font-mono">-</td>

                        {{-- Total per bulan --}}
                        @foreach($months as $m)
                            <td class="p-2 border border-[#D4AF37]/50 text-right font-mono font-bold text-[11px] text-gray-900">
                                {{ number_format($monthlyTotals[$m['key']] ?? 0, 0, ',', '.') }}
                            </td>
                        @endforeach

                        <td class="p-3 border border-[#D4AF37]/50 text-right font-black text-[#184D2E] text-sm bg-emerald-100/60">
                            {{ number_format($overallTon, 2, ',', '.') }}
                        </td>
                        <td class="p-3 border border-[#D4AF37]/50 text-right font-black text-purple-900 text-sm bg-purple-100/60">
                            {{ number_format($overallYph, 2, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        {{-- Footer Toolbar: Info & Pagination --}}
        <div class="p-4 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50">
            {{-- Info Data --}}
            <div id="produksiTableInfo" class="text-xs text-gray-600 font-medium">
                Menampilkan <b>1</b> s/d <b>{{ min(10, count($rows)) }}</b> dari <b>{{ count($rows) }}</b> plot
            </div>

            {{-- Tombol Navigasi Halaman --}}
            <div id="produksiTablePagination" class="flex items-center gap-1">
                {{-- Rendered dynamically by JavaScript --}}
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let currentPage = 1;
        let perPage = 10;
        let searchQuery = '';
        let currentSortCol = 'id_petani'; // Default urut berdasarkan ID Petani
        let currentSortDir = 'asc';       // Default menaik (A-Z / 1-9)

        const $rows = $('#tabelProduksiBody tr.produksi-row');
        const totalOriginalRows = $rows.length;

        // Kumpulkan data baris ke dalam array untuk sorting dan searching instan
        let rowsArray = [];
        $rows.each(function(i) {
            const $tr = $(this);
            rowsArray.push({
                element: $tr,
                originalIndex: i,
                id_petani: ($tr.data('id-petani') !== undefined ? $tr.data('id-petani') : '').toString().trim(),
                nama: ($tr.data('nama') !== undefined ? $tr.data('nama') : '').toString().trim(),
                blok: ($tr.data('blok') !== undefined ? $tr.data('blok') : '').toString().trim(),
                total_ton: parseFloat($tr.data('total-ton')) || 0,
                yph: parseFloat($tr.data('yph')) || 0,
                text: $tr.text().toLowerCase()
            });
        });

        const iconNeutral = '<svg class="w-3.5 h-3.5 opacity-40 inline-block shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>';
        const iconAsc = '<svg class="w-3.5 h-3.5 text-[#214122] inline-block shrink-0 drop-shadow-xs" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4l-6.5 11h13L12 4z"/></svg>';
        const iconDesc = '<svg class="w-3.5 h-3.5 text-[#214122] inline-block shrink-0 drop-shadow-xs" fill="currentColor" viewBox="0 0 24 24"><path d="M12 20l6.5-11h-13L12 20z"/></svg>';

        function updateSortIcons() {
            $('.sortable-th').each(function() {
                const col = $(this).data('sort');
                const $iconWrapper = $(this).find('.sort-icon-wrapper');
                if (col === currentSortCol) {
                    $(this).addClass('bg-[#C59F2D]').removeClass('bg-[#D4AF37]');
                    if (currentSortDir === 'asc') {
                        $iconWrapper.html(iconAsc);
                        $(this).attr('title', 'Diurutkan Menaik (A-Z / 1-9). Klik untuk Menurun (Z-A / 9-1)');
                    } else {
                        $iconWrapper.html(iconDesc);
                        $(this).attr('title', 'Diurutkan Menurun (Z-A / 9-1). Klik untuk Menaik (A-Z / 1-9)');
                    }
                } else {
                    $(this).removeClass('bg-[#C59F2D]').addClass('bg-[#D4AF37]');
                    $iconWrapper.html(iconNeutral);
                    $(this).attr('title', 'Klik untuk mengurutkan');
                }
            });
        }

        function sortRows(column, direction) {
            rowsArray.sort(function(a, b) {
                if (column === 'id_petani') {
                    let valA = a.id_petani;
                    let valB = b.id_petani;

                    // Nilai '-' diletakkan di paling bawah
                    if (valA === '-' && valB !== '-') return 1;
                    if (valB === '-' && valA !== '-') return -1;
                    if (valA === '-' && valB === '-') return a.originalIndex - b.originalIndex;

                    let cmp = valA.localeCompare(valB, undefined, { numeric: true, sensitivity: 'base' });
                    return direction === 'asc' ? cmp : -cmp;
                } else if (column === 'nama') {
                    let cmp = a.nama.localeCompare(b.nama, 'id', { sensitivity: 'base' });
                    return direction === 'asc' ? cmp : -cmp;
                } else if (column === 'blok') {
                    let cmp = a.blok.localeCompare(b.blok, undefined, { numeric: true, sensitivity: 'base' });
                    return direction === 'asc' ? cmp : -cmp;
                } else if (column === 'total_ton') {
                    return direction === 'asc' ? a.total_ton - b.total_ton : b.total_ton - a.total_ton;
                } else if (column === 'yph') {
                    return direction === 'asc' ? a.yph - b.yph : b.yph - a.yph;
                }
                return a.originalIndex - b.originalIndex;
            });

            // Re-order elemen DOM di dalam tbody
            const $tbody = $('#tabelProduksiBody');
            rowsArray.forEach(function(item) {
                $tbody.append(item.element);
            });
            // Pastikan baris no-match tetap di posisi terbawah
            $tbody.append($('#noProduksiMatchRow'));
        }

        function renderTable() {
            if (totalOriginalRows === 0) {
                $('#produksiTableInfo').html('Tidak ada data plot');
                $('#produksiTablePagination').empty();
                return;
            }

            // 1. Saring baris berdasarkan pencarian
            let matchedRows = [];
            rowsArray.forEach(function(item) {
                if (!searchQuery || item.text.indexOf(searchQuery) > -1) {
                    matchedRows.push(item.element);
                } else {
                    item.element.hide();
                }
            });

            const totalMatched = matchedRows.length;

            if (totalMatched === 0) {
                $('#noProduksiMatchRow').show();
                $('#produksiTableInfo').html('Menampilkan <b>0</b> dari <b>' + totalOriginalRows + '</b> plot');
                $('#produksiTablePagination').empty();
                return;
            } else {
                $('#noProduksiMatchRow').hide();
            }

            // 2. Hitung jumlah halaman
            let totalPages = 1;
            if (perPage === -1) {
                totalPages = 1;
                currentPage = 1;
            } else {
                totalPages = Math.ceil(totalMatched / perPage);
                if (currentPage > totalPages) currentPage = totalPages;
                if (currentPage < 1) currentPage = 1;
            }

            const startIndex = perPage === -1 ? 0 : (currentPage - 1) * perPage;
            const endIndex = perPage === -1 ? totalMatched : Math.min(startIndex + perPage, totalMatched);

            // 3. Tampilkan baris sesuai halaman & update penomoran No
            matchedRows.forEach(function($row, idx) {
                if (idx >= startIndex && idx < endIndex) {
                    $row.show();
                    $row.find('td.kolom-nomor').text(idx + 1);
                } else {
                    $row.hide();
                }
            });

            // 4. Update teks info
            let infoHtml = 'Menampilkan <b>' + (startIndex + 1) + '</b> s/d <b>' + endIndex + '</b> dari <b>' + totalMatched + '</b> plot';
            if (totalMatched < totalOriginalRows) {
                infoHtml += ' <span class="text-gray-400 font-normal">(disaring dari <b>' + totalOriginalRows + '</b> total data)</span>';
            }
            $('#produksiTableInfo').html(infoHtml);

            // 5. Render tombol pagination
            renderPaginationButtons(totalPages);
        }

        function renderPaginationButtons(totalPages) {
            const $pag = $('#produksiTablePagination');
            $pag.empty();

            if (totalPages <= 1) return;

            // Tombol Previous
            const $prev = $('<button type="button" class="paginate_button" title="Halaman Sebelumnya">‹</button>');
            if (currentPage === 1) {
                $prev.addClass('disabled').attr('disabled', true);
            } else {
                $prev.on('click', function() {
                    currentPage--;
                    renderTable();
                });
            }
            $pag.append($prev);

            // Logika Halaman (1, 2, 3...)
            let pages = [];
            if (totalPages <= 7) {
                for (let i = 1; i <= totalPages; i++) pages.push(i);
            } else {
                pages.push(1);
                if (currentPage > 3) pages.push('...');
                let start = Math.max(2, currentPage - 1);
                let end = Math.min(totalPages - 1, currentPage + 1);
                for (let i = start; i <= end; i++) pages.push(i);
                if (currentPage < totalPages - 2) pages.push('...');
                pages.push(totalPages);
            }

            pages.forEach(function(p) {
                if (p === '...') {
                    $pag.append('<span class="px-2 py-1 text-xs text-gray-400 select-none">...</span>');
                } else {
                    const $btn = $('<button type="button" class="paginate_button">' + p + '</button>');
                    if (p === currentPage) {
                        $btn.addClass('active');
                    } else {
                        $btn.on('click', function() {
                            currentPage = p;
                            renderTable();
                        });
                    }
                    $pag.append($btn);
                }
            });

            // Tombol Next
            const $next = $('<button type="button" class="paginate_button" title="Halaman Selanjutnya">›</button>');
            if (currentPage === totalPages) {
                $next.addClass('disabled').attr('disabled', true);
            } else {
                $next.on('click', function() {
                    currentPage++;
                    renderTable();
                });
            }
            $pag.append($next);
        }

        // Event: Klik Header Kolom untuk Sorting
        $('.sortable-th').on('click', function() {
            const col = $(this).data('sort');
            if (currentSortCol === col) {
                currentSortDir = (currentSortDir === 'asc') ? 'desc' : 'asc';
            } else {
                currentSortCol = col;
                currentSortDir = 'asc';
            }
            sortRows(currentSortCol, currentSortDir);
            updateSortIcons();
            currentPage = 1;
            renderTable();
        });

        // Event: Dropdown Tampilkan X data (per page)
        $('#produksiPerPage').on('change', function() {
            perPage = parseInt($(this).val(), 10);
            currentPage = 1;
            renderTable();
        });

        // Event: Kotak pencarian lokal instan
        let searchTimer;
        $('#produksiTableSearch').on('input', function() {
            clearTimeout(searchTimer);
            const val = $(this).val().toLowerCase().trim();
            searchTimer = setTimeout(function() {
                searchQuery = val;
                currentPage = 1;
                renderTable();
            }, 150);
        });

        // Inisialisasi awal (Urutkan otomatis berdasarkan ID Petani ASC)
        sortRows(currentSortCol, currentSortDir);
        updateSortIcons();
        renderTable();
    });
</script>

<style>
    /* Scrollbar Horizontal & Vertikal Halus & Modern */
    .custom-table-scroll {
        -webkit-overflow-scrolling: touch;
    }
    .custom-table-scroll::-webkit-scrollbar {
        width: 7px;
        height: 7px;
    }
    .custom-table-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 9999px;
    }
    .custom-table-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 9999px;
    }
    .custom-table-scroll::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Styling Tombol Pagination Persis DataTables SILAUSA */
    .paginate_button {
        border: 1px solid #e5e7eb;
        background: #ffffff;
        color: #4b5563;
        border-radius: 0.375rem;
        padding: 4px 12px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        user-select: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 30px;
    }
    .paginate_button:hover:not(.active):not(.disabled) {
        background: #f3f4f6;
        color: #1f2937;
        border-color: #d1d5db;
    }
    .paginate_button.active {
        background: #214122 !important;
        color: #ffffff !important;
        border-color: #214122 !important;
        font-weight: bold;
    }
    .paginate_button.disabled {
        color: #9ca3af;
        background: #f9fafb;
        border-color: #e5e7eb;
        cursor: not-allowed;
        opacity: 0.7;
    }
</style>
