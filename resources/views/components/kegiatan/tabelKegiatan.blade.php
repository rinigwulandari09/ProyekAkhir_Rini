<div class="p-2 space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 text-[11px] font-bold bg-[#D4AF37]/20 text-[#214122] rounded-full border border-[#D4AF37]/40 tracking-wide uppercase">
                    Sinkronisasi Mobile
                </span>
                <!-- <span class="text-xs text-gray-500 font-medium">Ketertelusuran Kegiatan RSPO & ISPO</span> -->
            </div>
            <h1 class="text-2xl font-bold text-[#214122] mt-1.5 font-poppins">Data Kegiatan Petani</h1>
            <p class="text-sm text-gray-500">Daftar rekapitulasi aktivitas perkebunan sawit yang diinput petani dari aplikasi mobile.</p>
        </div>

        {{-- Container tempat tombol Export --}}
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('kegiatan.export', request()->query()) }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#184D2E] text-white rounded-xl hover:bg-green-900 transition shadow-sm font-semibold text-xs">
                <x-heroicon-o-arrow-down-tray class="w-4 h-4 text-[#D4AF37]" />
                <span>Export Excel (.xlsx)</span>
            </a>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative text-sm flex items-center shadow-sm" role="alert">
            <x-heroicon-o-check-circle class="w-5 h-5 mr-2 shrink-0" />
            <span class="block sm:inline">{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()" class="ml-auto text-green-700 hover:text-green-900">
                <x-heroicon-o-x-mark class="w-4 h-4" />
            </button>
        </div>
    @endif

    {{-- Cards Summary Section --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Total Catatan Kegiatan --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <x-heroicon-o-clipboard-document-list class="w-6 h-6" />
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider font-poppins">Total Kegiatan</p>
                <p class="text-2xl font-bold text-gray-800 font-poppins">{{ number_format($totalKegiatan, 0, ',', '.') }} <span class="text-xs font-normal text-gray-500">Data</span></p>
            </div>
        </div>

        {{-- Card 2: Kegiatan Bulan Ini --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <x-heroicon-o-calendar-days class="w-6 h-6" />
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider font-poppins">Kegiatan Bulan Ini</p>
                <p class="text-2xl font-bold text-[#234323] font-poppins">{{ number_format($kegiatanBulanIni, 0, ',', '.') }} <span class="text-xs font-normal text-gray-500">Data</span></p>
            </div>
        </div>

        {{-- Card 3: Limbah Belum Disetor --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <x-heroicon-o-archive-box-x-mark class="w-6 h-6" />
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider font-poppins">Limbah Belum Disetor</p>
                <p class="text-2xl font-bold text-amber-600 font-poppins">{{ number_format($limbahBelumDisetor, 0, ',', '.') }} <span class="text-xs font-normal text-gray-500">B3</span></p>
            </div>
        </div>

        {{-- Card 4: Jenis Terbanyak --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <x-heroicon-o-sparkles class="w-6 h-6" />
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider font-poppins">Aktivitas Dominan</p>
                <p class="text-base font-bold text-purple-700 font-poppins truncate max-w-[160px]" title="{{ $jenisTerbanyak }}">{{ $jenisTerbanyak }}</p>
            </div>
        </div>
    </div>

    {{-- Filter Toolbar Section --}}
    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-gray-200">
        <form method="GET" action="{{ route('kegiatan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            
            {{-- Filter Desa (Hanya jika Super Admin) --}}
            @if(auth()->user()->user_role === 'super_admin')
            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1 font-poppins">Desa / Wilayah</label>
                <select name="desa_id" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl p-2.5 focus:ring-[#234323] focus:border-[#234323]">
                    <option value="">Semua Desa</option>
                    @foreach($desas as $d)
                        <option value="{{ $d->desa_id }}" {{ (string)$desaId === (string)$d->desa_id ? 'selected' : '' }}>
                            {{ $d->desa_nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- Filter Jenis Kegiatan --}}
            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1 font-poppins">Jenis Kegiatan</label>
                <select name="jenis_id" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl p-2.5 focus:ring-[#234323] focus:border-[#234323]">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisList as $j)
                        <option value="{{ $j->id_jenis }}" {{ (string)$jenisId === (string)$j->id_jenis ? 'selected' : '' }}>
                            {{ $j->nama_jenis }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Status Limbah --}}
            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1 font-poppins">Status Limbah B3</label>
                <select name="status_limbah" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl p-2.5 focus:ring-[#234323] focus:border-[#234323]">
                    <option value="">Semua Status</option>
                    <option value="Belum Disetor" {{ $statusLimbah === 'Belum Disetor' ? 'selected' : '' }}>Belum Disetor</option>
                    <option value="Sudah Disetor" {{ $statusLimbah === 'Sudah Disetor' ? 'selected' : '' }}>Sudah Disetor</option>
                </select>
            </div>

            {{-- Filter Bulan --}}
            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1 font-poppins">Bulan</label>
                <select name="bulan" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl p-2.5 focus:ring-[#234323] focus:border-[#234323]">
                    <option value="">Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        @php $mStr = sprintf('%02d', $m); @endphp
                        <option value="{{ $mStr }}" {{ (string)$bulan === $mStr ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                        </option>
                    @endfor
                </select>
            </div>

            {{-- Tombol Submit Filter & Reset --}}
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-[#234323] hover:bg-[#184D2E] text-white text-xs font-bold rounded-xl transition shadow-xs">
                    <x-heroicon-o-funnel class="w-4 h-4 text-[#D4AF37]" />
                    <span>Filter</span>
                </button>
                <a href="{{ route('kegiatan.index') }}" class="p-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl transition text-xs font-semibold" title="Reset Filter">
                    <x-heroicon-o-arrow-path class="w-4 h-4" />
                </a>
            </div>

        </form>
    </div>

    {{-- Table Card (Bentuk Mirip Tabel User dengan Header Emas & Search Lengkap) --}}
    <div class="bg-white rounded-2xl shadow-sm p-3 sm:p-5 border border-gray-200">
        <table id="tabelKegiatan" class="w-full text-left border-collapse display nowrap" style="width: 100%">
            <thead>
                <tr class="bg-[#D4AF37] border-b border-[#B8860B] shadow-sm text-black">
                    <th class="p-4 text-xs font-extrabold uppercase text-center w-12 rounded-tl-lg tracking-wider">No</th>
                    <th class="p-4 text-xs font-extrabold uppercase text-left tracking-wider">Tanggal</th>
                    <th class="p-4 text-xs font-extrabold uppercase text-left tracking-wider">Nama Petani</th>
                    <th class="p-4 text-xs font-extrabold uppercase text-left tracking-wider">Desa</th>
                    <th class="p-4 text-xs font-extrabold uppercase text-left tracking-wider">Jenis Kegiatan</th>
                    <th class="p-4 text-xs font-extrabold uppercase text-left tracking-wider">Jumlah / Dosis</th>
                    <th class="p-4 text-xs font-extrabold uppercase text-left tracking-wider">Bahan / Keterangan</th>
                    <th class="p-4 text-xs font-extrabold uppercase text-left tracking-wider">Lahan Dikerjakan</th>
                    <th class="p-4 text-xs font-extrabold uppercase text-center tracking-wider">Status Limbah B3</th>
                    <th class="p-4 text-xs font-extrabold uppercase text-center rounded-tr-lg tracking-wider w-28">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($kegiatans as $k)
                    @php
                        $jenisNama = $k->jenis->nama_jenis ?? ($k->nama_kegiatan ?? 'Kegiatan');
                        $isPanen = str_contains(strtolower($jenisNama), 'panen');
                        $isPupuk = str_contains(strtolower($jenisNama), 'pupuk');
                        $isSemprot = str_contains(strtolower($jenisNama), 'semprot') || str_contains(strtolower($jenisNama), 'gulma') || str_contains(strtolower($jenisNama), 'hama');
                        
                        $badgeClass = 'bg-gray-100 text-gray-700 border-gray-200';
                        if ($isPanen) {
                            $badgeClass = 'bg-emerald-50 text-emerald-800 border-emerald-200';
                        } elseif ($isPupuk) {
                            $badgeClass = 'bg-blue-50 text-blue-800 border-blue-200';
                        } elseif ($isSemprot) {
                            $badgeClass = 'bg-amber-50 text-amber-800 border-amber-200';
                        }
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        {{-- 1. No (Auto number via DataTables) --}}
                        <td class="p-4 text-xs text-center text-gray-500 font-mono"></td>

                        {{-- 2. Tanggal --}}
                        <td class="p-4 text-xs text-gray-800">
                            <span class="font-bold text-gray-800 block">{{ $k->kegiatan_tanggal ? date('d M Y', strtotime($k->kegiatan_tanggal)) : '-' }}</span>
                            <span class="text-[10px] text-gray-400 font-mono">{{ $k->kegiatan_tanggal ? date('l', strtotime($k->kegiatan_tanggal)) : '' }}</span>
                        </td>

                        {{-- 3. Nama Petani --}}
                        <td class="p-4 text-xs text-gray-900 font-semibold">
                            {{ $k->petani->petani_nama ?? 'Petani #' . $k->petani_id }}
                        </td>

                        {{-- 4. Desa --}}
                        <td class="p-4 text-xs text-gray-600">
                            <span class="inline-flex items-center gap-1">
                                <x-heroicon-o-map-pin class="w-3.5 h-3.5 text-[#D4AF37]" />
                                <span>{{ $k->petani->desa->desa_nama ?? '-' }}</span>
                            </span>
                        </td>

                        {{-- 5. Jenis Kegiatan --}}
                        <td class="p-4 text-xs">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold border {{ $badgeClass }}">
                                {{ $jenisNama }}
                            </span>
                        </td>

                        {{-- 6. Jumlah / Dosis --}}
                        <td class="p-4 text-xs">
                            <span class="font-black text-gray-900 font-poppins">{{ number_format($k->kegiatan_jumlah, 2, ',', '.') }}</span>
                            <span class="text-[10.5px] font-semibold text-gray-500">{{ $k->kegiatan_satuan ?? '' }}</span>
                        </td>

                        {{-- 7. Bahan / Keterangan --}}
                        <td class="p-4 text-xs text-gray-700">
                            <span class="font-medium block max-w-xs truncate" title="{{ $k->nama_bahan ?? $k->kegiatan_ket ?? '-' }}">
                                {{ $k->nama_bahan ?? $k->kegiatan_ket ?? '-' }}
                            </span>
                            @if($k->nama_bahan && $k->kegiatan_ket && $k->nama_bahan !== $k->kegiatan_ket)
                                <span class="text-[10px] text-gray-400 block truncate max-w-xs" title="{{ $k->kegiatan_ket }}">Ket: {{ $k->kegiatan_ket }}</span>
                            @endif
                        </td>

                        {{-- 8. Lahan Dikerjakan --}}
                        <td class="p-4 text-xs">
                            @if($k->detailLahan && count($k->detailLahan) > 0)
                                <div class="flex flex-wrap gap-1 max-w-xs">
                                    @foreach($k->detailLahan as $dl)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-gray-100 text-gray-700 text-[10px] font-semibold border border-gray-200">
                                            {{ $dl->lahan->lahan_nama ?? 'Plot #' . $dl->lahan_id }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400 italic text-[11px]">- Semua Plot -</span>
                            @endif
                        </td>

                        {{-- 9. Status Limbah B3 --}}
                        <td class="p-4 text-xs text-center">
                            @if($k->status_limbah === 'Sudah Disetor')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full text-[10px] font-bold">
                                    <x-heroicon-s-check-circle class="w-3 h-3 text-emerald-600" />
                                    <span>Sudah Disetor</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-full text-[10px] font-bold cursor-pointer hover:bg-amber-100 transition" onclick="openStatusLimbahModal({{ $k->kegiatan_id }}, '{{ $k->status_limbah }}')" title="Klik untuk ubah status limbah">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    <span>Belum Disetor</span>
                                </span>
                            @endif
                        </td>

                        {{-- 10. Aksi --}}
                        <td class="p-4 text-xs text-center">
                            <div class="flex justify-center items-center gap-1.5">
                                {{-- Tombol Detail --}}
                                <button type="button" 
                                        onclick="openDetailModal({{ $k->kegiatan_id }})" 
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-[#184D2E]/10 text-[#184D2E] hover:bg-[#184D2E] hover:text-white rounded-lg transition-colors border border-[#184D2E]/20 text-xs font-semibold"
                                        title="Lihat Detail Kegiatan">
                                    <x-heroicon-o-eye class="w-3.5 h-3.5" />
                                    <span>Detail</span>
                                </button>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('kegiatan.destroy', $k->kegiatan_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kegiatan ini?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-colors border border-red-100" title="Hapus Data Kegiatan">
                                        <x-heroicon-o-trash class="w-4 h-4" />
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

{{-- MODAL DETAIL KEGIATAN --}}
<div id="modalDetailKegiatan" class="fixed inset-0 z-50 hidden bg-black/40 items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-transform">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-[#234323]/10 text-[#234323] flex items-center justify-center">
                    <x-heroicon-o-clipboard-document-check class="w-5 h-5 text-[#234323]" />
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-800 font-poppins">Detail Informasi Kegiatan Petani</h3>
                    <p class="text-[10.5px] text-gray-400">Rincian data lapangan yang tersinkronisasi dari mobile</p>
                </div>
            </div>
            <button type="button" onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                <x-heroicon-o-x-mark class="w-5 h-5" />
            </button>
        </div>

        <div class="p-5 space-y-4 text-xs">
            {{-- Petani Info Banner --}}
            <div class="p-3 bg-emerald-50/60 rounded-xl border border-emerald-100 flex items-center justify-between">
                <div>
                    <p class="text-[10px] uppercase font-bold text-emerald-800">Nama Petani</p>
                    <p id="detailPetaniNama" class="text-sm font-black text-gray-900 font-poppins">-</p>
                    <p id="detailPetaniDesa" class="text-[11px] text-gray-500">-</p>
                </div>
                <div class="text-right">
                    <span id="detailJenisBadge" class="inline-flex px-2.5 py-1 rounded-lg text-[11px] font-bold bg-white text-[#234323] border border-emerald-200">
                        -
                    </span>
                </div>
            </div>

            {{-- Grid Info --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Tanggal Kegiatan</span>
                    <span id="detailTanggal" class="font-bold text-gray-800 text-xs">-</span>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Jumlah / Dosis</span>
                    <span id="detailJumlah" class="font-bold text-gray-800 text-xs">-</span>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Bahan / Pupuk / Herbisida</span>
                    <span id="detailBahan" class="font-bold text-gray-800 text-xs">-</span>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Status Limbah B3</span>
                    <span id="detailStatusLimbah" class="font-bold text-xs">-</span>
                </div>
            </div>

            {{-- Keterangan --}}
            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Catatan Tambahan / Keterangan</span>
                <p id="detailKeterangan" class="text-gray-700 italic">-</p>
            </div>

            {{-- Daftar Lahan Terkait --}}
            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1.5">Lahan / Plot Terkait</span>
                <div id="detailLahanList" class="flex flex-wrap gap-1.5">
                    <span class="text-gray-400 italic text-[11px]">- Tidak ada plot spesifik terhubung -</span>
                </div>
            </div>
        </div>

        <div class="px-5 py-3 border-t border-gray-100 bg-gray-50 flex justify-end">
            <button type="button" onclick="closeDetailModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold text-xs rounded-xl transition">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- MODAL UBAH STATUS LIMBAH --}}
<div id="modalStatusLimbah" class="fixed inset-0 z-50 hidden bg-black/40 items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-transform">
        <form id="formStatusLimbah" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-800 font-poppins">Ubah Status Limbah B3</h3>
                <button type="button" onclick="closeStatusLimbahModal()" class="text-gray-400 hover:text-gray-600">
                    <x-heroicon-o-x-mark class="w-5 h-5" />
                </button>
            </div>
            <div class="p-5 space-y-3">
                <p class="text-xs text-gray-500">Perbarui status penyerahan wadah/kemasan limbah pestisida untuk kepatuhan sertifikasi RSPO/ISPO:</p>
                <select name="status_limbah" id="inputStatusLimbah" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl p-2.5 font-bold">
                    <option value="Belum Disetor">Belum Disetor</option>
                    <option value="Sudah Disetor">Sudah Disetor</option>
                </select>
            </div>
            <div class="px-5 py-3 border-t border-gray-100 bg-gray-50 flex justify-end gap-2">
                <button type="button" onclick="closeStatusLimbahModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold text-xs rounded-xl transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-[#234323] hover:bg-[#184D2E] text-white font-bold text-xs rounded-xl transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- DataTables CSS & JS Libraries (Sesuai Persis dengan Tabel User) --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
    let kegiatanTable = null;
    if (typeof $.fn.dataTable !== 'undefined') {
        $.fn.dataTable.ext.errMode = 'none';
    }

    function initKegiatanTable() {
        if ($.fn.DataTable.isDataTable('#tabelKegiatan')) {
            $('#tabelKegiatan').DataTable().destroy();
        }

        const isMobile = window.innerWidth < 768;

        kegiatanTable = $('#tabelKegiatan').DataTable({
            destroy: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            autoWidth: false,
            responsive: isMobile ? {
                details: {
                    renderer: function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col) {
                            if (col.hidden) {
                                var value = col.data || '-';
                                return '<div class="flex items-start justify-between gap-3 py-2 text-xs leading-snug border-b border-gray-100 last:border-0">' +
                                       '<span class="font-bold text-gray-500 uppercase tracking-wider text-[10.5px] shrink-0">' + col.title + '</span>' +
                                       '<span class="text-gray-800 text-right">' + value + '</span>' +
                                       '</div>';
                            }
                            return '';
                        }).join('');
                        return data ? $('<div class="rounded-xl bg-gray-50/95 p-3.5 shadow-inner space-y-1 w-full mt-2 border border-gray-200"></div>').append(data).prop('outerHTML') : false;
                    }
                }
            } : false,
            language: {
                search: "",                    // Menghilangkan tulisan "Search" di luar input box
                searchPlaceholder: "Search...", // Placeholder Search di dalam kotak input mirip tabel user
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan <b>_START_</b> s/d <b>_END_</b> dari <b>_TOTAL_</b> kegiatan",
                infoEmpty: "Tidak ada data kegiatan",
                infoFiltered: "(disaring dari <b>_MAX_</b> total data)",
                zeroRecords: "Tidak ditemukan data kegiatan yang cocok",
                emptyTable: "Belum ada data kegiatan perkebunan yang ditemukan",
                paginate: {
                    previous: "‹",
                    next: "›"
                }
            },
            order: [[ 1, "desc" ]],
            columnDefs: isMobile ? [
                { orderable: false, searchable: false, targets: [0, 9] },
                { className: "text-center", targets: [0, 8, 9] },
                { className: "all", targets: [0, 1, 2, 9] },
                { className: "none", targets: [3, 4, 5, 6, 7, 8] }
            ] : [
                { orderable: false, searchable: false, targets: [0, 9] },
                { className: "text-center", targets: [0, 8, 9] }
            ],
            // DOM Persis dengan Tabel User: Length dan Filter Sejajar di Atas Tabel, bungkus tabel dengan custom scrollbar
            dom: '<"flex flex-col sm:flex-row justify-between items-center w-full mb-4 gap-3" l f> <"overflow-x-auto w-full custom-table-scroll" tr> <"flex flex-col sm:flex-row justify-between items-center gap-4 mt-4" i p>'
        });

        // Penomoran otomatis baris (No)
        kegiatanTable.on('order.dt search.dt draw.dt', function () {
            let start = kegiatanTable.page.info().start;
            kegiatanTable.column(0, {search: 'applied', order: 'applied'}).nodes().each(function(cell, i) {
                cell.innerHTML = start + i + 1;
            });
        }).draw();
    }

    $(document).ready(function() {
        initKegiatanTable();

        let resizeTimer;
        let lastIsMobile = window.innerWidth < 768;

        $(window).on('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                let currentIsMobile = window.innerWidth < 768;
                if (currentIsMobile !== lastIsMobile) {
                    lastIsMobile = currentIsMobile;
                    initKegiatanTable();
                }
            }, 250);
        });
    });

    function openDetailModal(id) {
        const modal = document.getElementById('modalDetailKegiatan');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        fetch(`/kegiatan/${id}`)
            .then(res => res.json())
            .then(res => {
                if(res.success && res.data) {
                    const d = res.data;
                    document.getElementById('detailPetaniNama').innerText = d.petani ? d.petani.petani_nama : 'Petani #' + d.petani_id;
                    document.getElementById('detailPetaniDesa').innerText = d.petani && d.petani.desa ? 'Desa ' + d.petani.desa.desa_nama : 'Desa tidak terdata';
                    document.getElementById('detailJenisBadge').innerText = d.jenis ? d.jenis.nama_jenis : (d.nama_kegiatan || '-');
                    document.getElementById('detailTanggal').innerText = d.kegiatan_tanggal ? new Date(d.kegiatan_tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-';
                    document.getElementById('detailJumlah').innerText = (d.kegiatan_jumlah || 0) + ' ' + (d.kegiatan_satuan || '');
                    document.getElementById('detailBahan').innerText = d.nama_bahan || d.kegiatan_ket || '-';
                    
                    const limbahEl = document.getElementById('detailStatusLimbah');
                    limbahEl.innerText = d.status_limbah || 'Belum Disetor';
                    limbahEl.className = d.status_limbah === 'Sudah Disetor' ? 'font-bold text-emerald-700' : 'font-bold text-amber-600';

                    document.getElementById('detailKeterangan').innerText = d.kegiatan_ket || '-';

                    const lahanContainer = document.getElementById('detailLahanList');
                    lahanContainer.innerHTML = '';
                    if (d.detail_lahan && d.detail_lahan.length > 0) {
                        d.detail_lahan.forEach(dl => {
                            const badge = document.createElement('span');
                            badge.className = 'inline-flex items-center px-2 py-1 rounded-md bg-white border border-gray-200 text-gray-800 text-[11px] font-semibold';
                            badge.innerText = dl.lahan ? dl.lahan.lahan_nama + ' (' + (dl.lahan.lahan_luas || 0) + ' Ha)' : 'Plot #' + dl.lahan_id;
                            lahanContainer.appendChild(badge);
                        });
                    } else {
                        lahanContainer.innerHTML = '<span class="text-gray-400 italic text-[11px]">- Semua / Tidak Terikat Plot Tertentu -</span>';
                    }
                }
            })
            .catch(err => console.error(err));
    }

    function closeDetailModal() {
        const modal = document.getElementById('modalDetailKegiatan');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openStatusLimbahModal(id, currentStatus) {
        const modal = document.getElementById('modalStatusLimbah');
        const form = document.getElementById('formStatusLimbah');
        const select = document.getElementById('inputStatusLimbah');
        
        form.action = `/kegiatan/${id}/status-limbah`;
        select.value = currentStatus || 'Belum Disetor';

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeStatusLimbahModal() {
        const modal = document.getElementById('modalStatusLimbah');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

<style>
    /* =========================================
       1. GLOBAL STYLES (SESUAI TABEL USER)
       ========================================= */
    #tabelKegiatan thead tr {
        background-color: #D4AF37 !important;
    }
    #tabelKegiatan thead th {
        background-color: #D4AF37 !important;
        color: #111827 !important;
        border-bottom: 2px solid #B8860B !important;
        font-weight: 800 !important;
        font-size: 0.75rem !important;
        letter-spacing: 0.05em !important;
    }

    /* Scrollbar Horizontal Halus & Modern */
    .custom-table-scroll {
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
    }
    .custom-table-scroll::-webkit-scrollbar {
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

    /* Tata letak entries dan search */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        float: none !important;
    }

    .dataTables_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_filter label { 
        display: inline-flex !important; 
        align-items: center !important; 
        gap: 0.5rem !important; 
        font-size: 0.875rem !important; 
        color: #374151 !important; 
        margin: 0 !important; 
    }
    
    /* STYLING INPUT & SELECT PERSIS TABEL USER */
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input { 
        font-size: 0.875rem !important; 
        color: #374151 !important; 
        border: 1px solid #e5e7eb !important; 
        border-radius: 8px !important; 
        padding: 5px 12px !important; 
        margin: 0 !important; 
        outline: none !important; 
        background-color: #ffffff !important;
    }
    .dataTables_wrapper .dataTables_length select:focus {
        border-color: #214122 !important;
    }
    .dataTables_wrapper .dataTables_filter input { 
        padding-left: 34px !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239CA3AF'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'%3E%3C/path%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: 10px center !important;
        background-size: 16px 16px !important;
        width: 220px !important;
        transition: all 0.2s;
    }
    .dataTables_wrapper .dataTables_filter input:focus { 
        border-color: #214122 !important; 
        width: 260px !important;
        box-shadow: 0 0 0 2px rgba(33, 65, 34, 0.1) !important;
    }

    /* =========================================
       2. MODE DESKTOP (min-width: 768px):
          1 Tabel utuh, scroll ke samping jika layar sempit,
          TIDAK ADA kolom yang collapse ke bawah
       ========================================= */
    @media (min-width: 768px) {
        #tabelKegiatan {
            min-width: 1150px !important;
            width: 100% !important;
        }
        #tabelKegiatan th, 
        #tabelKegiatan td { 
            white-space: nowrap !important;
            display: table-cell !important;
        }
        #tabelKegiatan tr.child {
            display: none !important;
        }
        table.dataTable.dtr-inline.collapsed > tbody > tr > td:first-child::before,
        table.dataTable.dtr-inline.collapsed > tbody > tr > th:first-child::before {
            display: none !important;
        }
    }

    /* =========================================
       3. MODE HP / MOBILE (max-width: 767px)
          Boleh collapse ke bawah dengan accordion
       ========================================= */
    @media (max-width: 767px) {
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            display: inline-block !important;
            margin: 0 !important;
        }

        .dataTables_wrapper .dataTables_length select {
            width: 70px !important;
        }
        
        .dataTables_wrapper .dataTables_filter input { 
            width: 100% !important;
            max-width: 160px !important;
            font-size: 0.875rem !important;
        }

        .dataTables_wrapper .dataTables_info, 
        .dataTables_wrapper .dataTables_paginate { 
            width: 100%; 
            justify-content: center; 
            text-align: center; 
            margin-top: 5px; 
        }

        /* Icon plus (+) & minus (-) untuk baris yang collapse di HP */
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
            width: 18px !important;
            height: 18px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 9999px !important;
            font-weight: bold !important;
            font-size: 13px !important;
            line-height: 1 !important;
            box-shadow: 0 1px 2px rgba(0,0,0,0.2) !important;
        }
        table.dataTable.dtr-inline.collapsed > tbody > tr.parent > td:first-child::before { 
            content: '−' !important; 
            background-color: #dc2626 !important; 
        }
    }

    /* =========================================
       4. STYLE PAGINATION PERSIS TABEL USER
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
        font-weight: bold !important;
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
