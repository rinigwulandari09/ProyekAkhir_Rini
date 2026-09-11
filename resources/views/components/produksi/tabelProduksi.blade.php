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
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#1F7A44] text-white text-sm font-semibold rounded-xl hover:bg-[#185e35] transition shadow-sm hover:shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                </svg>
                <span>Export Excel RSPO (.xlsx)</span>
            </a>
        </div>
    </div>

    {{-- Cards Summary Section --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Total Plot / Blok --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <x-heroicon-o-map class="w-6 h-6" />
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Plot / Blok</p>
                <p class="text-2xl font-bold text-gray-800">{{ count($rows) }} <span class="text-xs font-normal text-gray-500">Plot</span></p>
            </div>
        </div>

        {{-- Card 2: Total Luas Lahan --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <x-heroicon-o-square-3-stack-3d class="w-6 h-6" />
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Luas Lahan</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($totalAreaSum, 2, ',', '.') }} <span class="text-xs font-normal text-gray-500">Ha</span></p>
            </div>
        </div>

        {{-- Card 3: Total Produksi (Ton) --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <x-heroicon-o-archive-box class="w-6 h-6" />
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Produksi (12 Bln)</p>
                <p class="text-2xl font-bold text-[#214122]">{{ number_format($overallTon, 2, ',', '.') }} <span class="text-xs font-normal text-gray-500">Ton</span></p>
            </div>
        </div>

        {{-- Card 4: Rata-Rata YPH --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <x-heroicon-o-chart-bar class="w-6 h-6" />
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Produktivitas (YPH)</p>
                <p class="text-2xl font-bold text-purple-700">{{ number_format($overallYph, 2, ',', '.') }} <span class="text-xs font-normal text-gray-500">Ton/Ha/Thn</span></p>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-5 border border-gray-200">
        <form action="{{ route('produksi.index') }}" method="GET" class="space-y-4">
            <div class="flex items-center gap-2 text-gray-700">
                <x-heroicon-o-funnel class="w-4 h-4 text-[#214122]" />
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-600">Filter Periode Audit & Wilayah</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Dropdown Periode Tahun Audit --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-gray-500 pl-1">Tahun Periode Audit</label>
                    <div class="relative">
                        <select name="tahun" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl px-3.5 py-2.5 outline-none focus:border-[#214122] focus:bg-white transition appearance-none cursor-pointer">
                            @foreach([2027, 2026, 2025, 2024, 2023] as $y)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                    Tahun {{ $y }} (Sep {{ $y - 1 }} - Agu {{ $y }})
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-gray-400">
                            <x-heroicon-o-chevron-down class="w-4 h-4" />
                        </div>
                    </div>
                </div>

                {{-- Dropdown Desa (Hanya jika Super Admin atau Desa belum terikat) --}}
                @if(auth()->user()->user_role === 'super_admin')
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-gray-500 pl-1">Wilayah Desa</label>
                    <div class="relative">
                        <select name="desa_id" class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl px-3.5 py-2.5 outline-none focus:border-[#214122] focus:bg-white transition appearance-none cursor-pointer">
                            <option value="">Semua Desa</option>
                            @foreach($desas as $desa)
                                <option value="{{ $desa->desa_id }}" {{ $desaId == $desa->desa_id ? 'selected' : '' }}>
                                    {{ $desa->desa_nama }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-gray-400">
                            <x-heroicon-o-chevron-down class="w-4 h-4" />
                        </div>
                    </div>
                </div>
                @endif

                {{-- Input Pencarian --}}
                <div class="flex flex-col gap-1.5 {{ auth()->user()->user_role === 'super_admin' ? 'sm:col-span-2 lg:col-span-1' : 'sm:col-span-2' }}">
                    <label class="text-xs font-semibold text-gray-500 pl-1">Cari Petani / ID Blok</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Ketik nama atau blok..."
                               class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl pl-9 pr-3.5 py-2.5 outline-none focus:border-[#214122] focus:bg-white transition">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                            <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                        </div>
                    </div>
                </div>

                {{-- Tombol Filter --}}
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-[#214122] text-white px-4 py-2.5 rounded-xl font-semibold text-sm hover:bg-[#184D2E] transition shadow-sm flex items-center justify-center gap-1.5">
                        <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                        <span>Filter</span>
                    </button>
                    @if($search || $desaId || $tahun != date('Y'))
                    <a href="{{ route('produksi.index') }}" class="px-3.5 py-2.5 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-sm font-medium transition" title="Reset Filter">
                        <x-heroicon-o-arrow-path class="w-4 h-4" />
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Tabel Preview Data Produksi Format RSPO --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-gray-50/50">
            <div>
                <h3 class="font-bold text-gray-800 text-sm font-poppins">Tabel Matriks Produksi & Audit RSPO Tahun {{ $tahun }}</h3>
                <p class="text-xs text-gray-500">Menampilkan tonase bulanan (Kg), total tahunan (Ton), dan produktivitas (YPH) per plot lahan.</p>
            </div>
            <a href="{{ route('produksi.export', request()->query()) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#1F7A44] hover:text-green-900 bg-green-50 hover:bg-green-100 border border-green-200 px-3 py-1.5 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"></path></svg>
                <span>Unduh File Excel</span>
            </a>
        </div>

        <div class="overflow-x-auto w-full max-h-[600px] overflow-y-auto">
            <table class="w-full text-xs text-left border-collapse min-w-[1400px]">
                {{-- Header 2 Baris Sesuai Excel RSPO --}}
                <thead class="sticky top-0 z-20 bg-[#D9D9D9] text-gray-800 font-bold border-b border-gray-400 shadow-sm">
                    <tr>
                        <th rowspan="2" class="p-2.5 border border-gray-300 text-center w-12 bg-[#D9D9D9]">No</th>
                        <th rowspan="2" class="p-2.5 border border-gray-300 text-center w-16 bg-[#D9D9D9]">ID Petani</th>
                        <th rowspan="2" class="p-2.5 border border-gray-300 text-center w-16 bg-[#D9D9D9]">ID Blok</th>
                        <th rowspan="2" class="p-2.5 border border-gray-300 text-left min-w-[160px] bg-[#D9D9D9]">Smallholder Name</th>
                        <th rowspan="2" class="p-2.5 border border-gray-300 text-center min-w-[120px] bg-[#D9D9D9]">Location</th>
                        <th colspan="2" class="p-2 border border-gray-300 text-center bg-[#D9D9D9]">Coordinate</th>
                        <th colspan="2" class="p-2 border border-gray-300 text-center bg-[#D9D9D9]">Area (Ha)</th>
                        <th rowspan="2" class="p-2.5 border border-gray-300 text-center min-w-[90px] bg-[#D9D9D9]">Planted Year</th>
                        <th colspan="12" class="p-2 border border-gray-300 text-center bg-[#D9D9D9] text-[#214122] font-black">
                            {{ $tahun }} (Produksi Kg Bulanan)
                        </th>
                        <th rowspan="2" class="p-2.5 border border-gray-300 text-right min-w-[90px] bg-[#D9D9D9]">
                            Total Produksi<br><span class="font-normal text-[10px]">(Ton)</span>
                        </th>
                        <th rowspan="2" class="p-2.5 border border-gray-300 text-right min-w-[90px] bg-[#D9D9D9]">
                            YPH<br><span class="font-normal text-[10px]">(Ton/Ha/Thn)</span>
                        </th>
                    </tr>
                    <tr>
                        {{-- Coordinate Sub-headers --}}
                        <th class="p-1.5 border border-gray-300 text-center text-[10px] min-w-[130px] bg-[#E5E5E5]">Longitude (E)</th>
                        <th class="p-1.5 border border-gray-300 text-center text-[10px] min-w-[130px] bg-[#E5E5E5]">Latitude (N)</th>
                        {{-- Area Sub-headers --}}
                        <th class="p-1.5 border border-gray-300 text-center text-[10px] w-16 bg-[#E5E5E5]">Total</th>
                        <th class="p-1.5 border border-gray-300 text-center text-[10px] w-16 bg-[#E5E5E5]">Production</th>
                        {{-- 12 Bulan Sub-headers --}}
                        @foreach($months as $m)
                            <th class="p-1.5 border border-gray-300 text-center text-[10px] w-14 bg-[#E5E5E5]">{{ $m['label'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($rows as $r)
                    <tr class="hover:bg-amber-50/50 transition">
                        <td class="p-2 border border-gray-200 text-center font-mono text-gray-500">{{ $r['no'] }}</td>
                        <td class="p-2 border border-gray-200 text-center font-medium">{{ $r['id_petani'] }}</td>
                        <td class="p-2 border border-gray-200 text-center font-medium text-[#214122] bg-gray-50/50">{{ $r['id_blok'] }}</td>
                        <td class="p-2 border border-gray-200 font-medium text-gray-900">{{ $r['smallholder_name'] }}</td>
                        <td class="p-2 border border-gray-200 text-center text-gray-600">{{ $r['location'] }}</td>
                        <td class="p-2 border border-gray-200 text-center font-mono text-[10px] text-gray-500 whitespace-nowrap">{{ $r['lng_dms'] }}</td>
                        <td class="p-2 border border-gray-200 text-center font-mono text-[10px] text-gray-500 whitespace-nowrap">{{ $r['lat_dms'] }}</td>
                        <td class="p-2 border border-gray-200 text-right font-mono">{{ number_format($r['area_total'], 2, ',', '.') }}</td>
                        <td class="p-2 border border-gray-200 text-right font-mono font-medium">{{ number_format($r['area_production'], 2, ',', '.') }}</td>
                        <td class="p-2 border border-gray-200 text-center text-gray-600">{{ $r['planted_year'] }}</td>
                        
                        {{-- 12 Bulan (Kg) --}}
                        @foreach($months as $m)
                            @php $val = $r['monthly'][$m['key']] ?? 0; @endphp
                            <td class="p-2 border border-gray-200 text-right font-mono {{ $val > 0 ? 'text-gray-800' : 'text-gray-300' }}">
                                {{ $val > 0 ? number_format($val, 0, ',', '.') : '-' }}
                            </td>
                        @endforeach

                        {{-- Total Tonase --}}
                        <td class="p-2 border border-gray-200 text-right font-bold text-[#184D2E] bg-emerald-50/30">
                            {{ number_format($r['total_ton'], 2, ',', '.') }}
                        </td>

                        {{-- YPH --}}
                        <td class="p-2 border border-gray-200 text-right font-bold {{ $r['yph'] >= 18 ? 'text-green-600' : ($r['yph'] >= 12 ? 'text-amber-600' : 'text-gray-700') }} bg-purple-50/20">
                            {{ number_format($r['yph'], 2, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="24" class="p-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <x-heroicon-o-document-magnifying-glass class="w-8 h-8 text-gray-400" />
                                <p class="text-sm font-medium">Tidak ada data plot lahan atau produksi ditemukan untuk kriteria filter ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if(count($rows) > 0)
                <tfoot class="sticky bottom-0 z-10 bg-[#E5E7EB] text-gray-900 font-bold border-t-2 border-gray-400">
                    <tr>
                        <td colspan="7" class="p-2.5 border border-gray-300 text-center font-extrabold uppercase">TOTAL KESELURUHAN</td>
                        <td class="p-2 border border-gray-300 text-right font-mono">{{ number_format($totalAreaSum, 2, ',', '.') }}</td>
                        <td class="p-2 border border-gray-300 text-right font-mono">{{ number_format($totalAreaSum, 2, ',', '.') }}</td>
                        <td class="p-2 border border-gray-300 text-center">-</td>

                        {{-- Total per bulan --}}
                        @foreach($months as $m)
                            <td class="p-2 border border-gray-300 text-right font-mono text-[11px]">
                                {{ number_format($monthlyTotals[$m['key']] ?? 0, 0, ',', '.') }}
                            </td>
                        @endforeach

                        <td class="p-2.5 border border-gray-300 text-right font-extrabold text-[#184D2E] text-sm">
                            {{ number_format($overallTon, 2, ',', '.') }}
                        </td>
                        <td class="p-2.5 border border-gray-300 text-right font-extrabold text-purple-800 text-sm">
                            {{ number_format($overallYph, 2, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
