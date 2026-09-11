@extends('layouts.dashboard')

@section('title', 'Beranda')

@section('content')
{{-- Include Leaflet.js Assets & DataTables --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<div class="space-y-6 pb-6">
    
    {{-- Breadcrumb & Title Section (Style HARMONITAS) --}}
    <div>
        <div class="flex items-center gap-2 text-xs font-semibold text-gray-400 mb-1">
            <x-heroicon-o-home class="w-3.5 h-3.5 text-[#234323]" />
            <span>›</span>
            <span class="text-gray-700 font-medium">Beranda Dashboard</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight font-poppins">Dashboard</h1>
    </div>

    {{-- Welcome Hero Banner Card (Style HARMONITAS: Card Putih dengan Garis Atas Emas) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 border-t-4 border-t-[#D4AF37] p-5 sm:p-6 transition hover:shadow-md">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-50 text-amber-800 border border-amber-200/80 rounded-full text-xs font-semibold">
                    <x-heroicon-s-shield-check class="w-3.5 h-3.5 text-[#D4AF37]" />
                    <span>Asosiasi PSKS Pelalawan Siak • Sertifikasi RSPO & ISPO</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-gray-800 font-poppins">
                    Selamat Datang, {{ Auth::user()->user_nama }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 max-w-2xl leading-relaxed">
                    Pusat pemantauan dan kendali terpadu data petani, luasan lahan, rekapitulasi tonase produksi, aktivasi akun, dan verifikasi audit sertifikasi perkebunan sawit.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <div class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-600">
                    <x-heroicon-o-calendar class="w-4 h-4 text-gray-400" />
                    <span>Tahun {{ date('Y') }}</span>
                </div>
                <a href="{{ route('lahan.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 transition">
                    <x-heroicon-o-map class="w-4 h-4 text-emerald-600" />
                    <span>Data Lahan</span>
                </a>
                <a href="{{ route('produksi.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 transition">
                    <x-heroicon-o-chart-bar class="w-4 h-4 text-blue-600" />
                    <span>Data Produksi</span>
                </a>
                <a href="{{ route('petani.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#234323] hover:bg-[#184D2E] text-white rounded-xl text-xs font-bold transition shadow-sm hover:shadow-md">
                    <x-heroicon-o-user-group class="w-4 h-4 text-[#D4AF37]" />
                    <span>Semua Petani</span>
                    <x-heroicon-o-chevron-right class="w-3.5 h-3.5 text-white/70" />
                </a>
            </div>
        </div>
    </div>

    {{-- 5 Stat Cards Row (Exact HARMONITAS Style) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        {{-- Card 1: TOTAL PETANI --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-gray-100 hover:shadow-md hover:border-emerald-200 transition group flex flex-col justify-between relative overflow-hidden">
            <div class="flex items-center justify-between gap-2 mb-3">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400 font-poppins">Total Petani</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                    <x-heroicon-o-user-group class="w-4 h-4" />
                </div>
            </div>
            <div>
                <h3 class="text-2xl sm:text-3xl font-black text-gray-800 font-poppins tracking-tight">
                    {{ number_format($jumlahPetani, 0, ',', '.') }}
                </h3>
            </div>
            <div class="flex items-center justify-between text-[11px] text-gray-400 mt-3 pt-2 border-t border-gray-50">
                <span>Petani terdaftar</span>
                <a href="{{ route('petani.index') }}" class="text-gray-300 group-hover:text-emerald-700 transition font-bold" title="Lihat Petani">↗</a>
            </div>
        </div>

        {{-- Card 2: LUAS LAHAN --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-gray-100 hover:shadow-md hover:border-blue-200 transition group flex flex-col justify-between relative overflow-hidden">
            <div class="flex items-center justify-between gap-2 mb-3">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400 font-poppins">Luas Lahan (Ha)</span>
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0">
                    <x-heroicon-o-map class="w-4 h-4" />
                </div>
            </div>
            <div>
                <h3 class="text-2xl sm:text-3xl font-black text-gray-800 font-poppins tracking-tight">
                    {{ number_format($jumlahLahan, 2, ',', '.') }}
                </h3>
            </div>
            <div class="flex items-center justify-between text-[11px] text-gray-400 mt-3 pt-2 border-t border-gray-50">
                <span>Total hamparan kebun</span>
                <a href="{{ route('lahan.index') }}" class="text-gray-300 group-hover:text-blue-700 transition font-bold" title="Lihat Lahan">↗</a>
            </div>
        </div>

        {{-- Card 3: PRODUKSI HARI INI --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-gray-100 hover:shadow-md hover:border-amber-200 transition group flex flex-col justify-between relative overflow-hidden">
            <div class="flex items-center justify-between gap-2 mb-3">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400 font-poppins">Produksi Hari Ini</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0">
                    <x-heroicon-o-scale class="w-4 h-4" />
                </div>
            </div>
            <div>
                <h3 class="text-2xl sm:text-3xl font-black text-gray-800 font-poppins tracking-tight">
                    {{ number_format($jumlahProduksiHariIni, 0, ',', '.') }}
                </h3>
            </div>
            <div class="flex items-center justify-between text-[11px] text-gray-400 mt-3 pt-2 border-t border-gray-50">
                <span>Transaksi panen hari ini</span>
                <a href="{{ route('produksi.index') }}" class="text-gray-300 group-hover:text-amber-700 transition font-bold" title="Lihat Produksi">↗</a>
            </div>
        </div>

        {{-- Card 4: PENDAPATAN BULAN INI --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-gray-100 hover:shadow-md hover:border-green-200 transition group flex flex-col justify-between relative overflow-hidden">
            <div class="flex items-center justify-between gap-2 mb-3">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400 font-poppins">Pendapatan Bln Ini</span>
                <div class="w-8 h-8 rounded-xl bg-green-50 text-green-700 flex items-center justify-center shrink-0">
                    <x-heroicon-o-banknotes class="w-4 h-4" />
                </div>
            </div>
            <div>
                <h3 class="text-xl sm:text-2xl font-black text-gray-800 font-poppins tracking-tight" title="Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}">
                    Rp {{ $pendapatanBulanIni >= 1000000000 ? number_format($pendapatanBulanIni/1000000000, 1, ',', '.') . ' M' : ($pendapatanBulanIni >= 1000000 ? number_format($pendapatanBulanIni/1000000, 1, ',', '.') . ' Jt' : number_format($pendapatanBulanIni, 0, ',', '.')) }}
                </h3>
            </div>
            <div class="flex items-center justify-between text-[11px] text-gray-400 mt-3 pt-2 border-t border-gray-50">
                <span>Penjualan TBS</span>
                <a href="{{ route('keuangan.index') }}" class="text-gray-300 group-hover:text-green-700 transition font-bold" title="Lihat Keuangan">↗</a>
            </div>
        </div>

        {{-- Card 5: AUDIT LULUS --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-gray-100 hover:shadow-md hover:border-teal-200 transition group flex flex-col justify-between relative overflow-hidden">
            <div class="flex items-center justify-between gap-2 mb-3">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400 font-poppins">Audit Lulus</span>
                <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center shrink-0">
                    <x-heroicon-o-shield-check class="w-4 h-4" />
                </div>
            </div>
            <div>
                <h3 class="text-2xl sm:text-3xl font-black text-gray-800 font-poppins tracking-tight">
                    {{ $auditLulus }}
                </h3>
            </div>
            <div class="flex items-center justify-between text-[11px] text-gray-400 mt-3 pt-2 border-t border-gray-50">
                <span>{{ $auditPending }} pending verifikasi</span>
                <a href="{{ route('audit.index') }}" class="text-gray-300 group-hover:text-teal-700 transition font-bold" title="Lihat Audit">↗</a>
            </div>
        </div>
    </div>

    {{-- Main Grid Section (2 Kolom Layout Sesuai HARMONITAS) --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
        
        {{-- KOLOM KIRI (7 Kolom): Grafik & Peta Spasial --}}
        <div class="xl:col-span-7 space-y-6">

            {{-- Card Grafik Pemasukan & Pengeluaran --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
                <div class="flex items-center justify-between gap-4 mb-5 pb-3 border-b border-gray-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#234323]/10 text-[#234323] flex items-center justify-center">
                            <x-heroicon-o-presentation-chart-line class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-800 font-poppins">Tren Keuangan & Produksi Bulanan</h3>
                            <p class="text-[11px] text-gray-400">Pemasukan penjualan TBS dan pengeluaran operasional asosiasi</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full text-[10px] font-bold">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Live Data</span>
                    </span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400 mb-2 font-poppins">Pemasukan Per Bulan (Rp)</p>
                        <div class="relative w-full h-56">
                            <canvas id="chartPemasukan"></canvas>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400 mb-2 font-poppins">Biaya Operasional Per Kategori</p>
                        <div class="relative w-full h-56 flex justify-center">
                            <canvas id="chartPengeluaran"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Peta Sebaran Lahan Spasial --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
                <div class="flex items-center justify-between gap-4 mb-4 pb-3 border-b border-gray-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#D4AF37]/20 text-[#214122] flex items-center justify-center">
                            <x-heroicon-o-map-pin class="w-5 h-5 text-[#214122]" />
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-800 font-poppins">Sebaran Lahan Anggota (Peta Spasial GIS)</h3>
                            <p class="text-[11px] text-gray-400">Pemetaan poligon hamparan kebun kelapa sawit seluruh desa anggota</p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-gray-500 bg-gray-50 border border-gray-200 px-3 py-1 rounded-lg">
                        {{ count($semuaLahan ?? []) }} Poligon Terpetakan
                    </span>
                </div>
                <div id="mapSebaran" class="w-full h-80 sm:h-96 rounded-xl bg-gray-100 relative border border-gray-200" style="z-index: 1;"></div>
            </div>

        </div>

        {{-- KOLOM KANAN (5 Kolom): Status Audit & Aktivasi Akun Petani --}}
        <div class="xl:col-span-5 space-y-6">

            {{-- Card Status Audit & Aktivasi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3 mb-4 pb-3 border-b border-gray-100">
                    <div class="flex items-center gap-2 text-gray-800">
                        <x-heroicon-o-user-plus class="w-5 h-5 text-[#D4AF37]" />
                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-gray-800 font-poppins">Tugas Perlu Tindakan</h3>
                    </div>
                    <span class="px-2.5 py-0.5 text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200 rounded-md uppercase tracking-wider">
                        Prioritas Peran
                    </span>
                </div>

                {{-- Status Audit Internal Summary (Format List Item HARMONITAS) --}}
                <div class="space-y-2.5 mb-5">
                    <div class="p-3 bg-gray-50 hover:bg-emerald-50/40 border border-gray-100 rounded-xl flex items-center justify-between transition">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                <x-heroicon-s-check-circle class="w-4 h-4" />
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-800">Audit Lulus (Sesuai)</p>
                                <p class="text-[10px] text-gray-400">Telah memenuhi kriteria sertifikasi</p>
                            </div>
                        </div>
                        <span class="w-7 h-7 bg-emerald-600 text-white text-xs font-black rounded-lg flex items-center justify-center">
                            {{ $auditLulus }}
                        </span>
                    </div>

                    <div class="p-3 bg-gray-50 hover:bg-amber-50/40 border border-gray-100 rounded-xl flex items-center justify-between transition">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                                <x-heroicon-s-information-circle class="w-4 h-4" />
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-800">Butuh Tindak Lanjut</p>
                                <p class="text-[10px] text-gray-400">Perlu perbaikan temuan lapangan</p>
                            </div>
                        </div>
                        <span class="w-7 h-7 bg-amber-500 text-white text-xs font-black rounded-lg flex items-center justify-center">
                            {{ $auditPerbaikan }}
                        </span>
                    </div>

                    <div class="p-3 bg-gray-50 hover:bg-rose-50/40 border border-gray-100 rounded-xl flex items-center justify-between transition">
                        <div class="flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                                <x-heroicon-s-clock class="w-4 h-4" />
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-800">Menunggu Verifikasi</p>
                                <p class="text-[10px] text-gray-400">Belum diputuskan auditor</p>
                            </div>
                        </div>
                        <span class="w-7 h-7 bg-rose-600 text-white text-xs font-black rounded-lg flex items-center justify-center">
                            {{ $auditPending }}
                        </span>
                    </div>
                </div>

                {{-- Tabel Aktivasi Akun Petani Pending --}}
                <div class="pt-2 border-t border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-xs font-bold text-gray-700 font-poppins">Aktivasi Akun Petani Baru</h4>
                        <span class="text-[10px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                            {{ count($petaniPending) }} Menunggu
                        </span>
                    </div>

                    <div class="overflow-x-auto w-full">
                        <table id="tabelPetani" class="w-full text-left border-collapse display responsive nowrap">
                            <thead>
                                <tr class="bg-[#D4AF37] text-black">
                                    <th class="p-2.5 text-[10px] font-bold uppercase tracking-wider rounded-l-lg">No</th>
                                    <th class="p-2.5 text-[10px] font-bold uppercase tracking-wider">Nama & Email</th>
                                    <th class="p-2.5 text-[10px] font-bold uppercase tracking-wider text-center">Status</th>
                                    <th class="p-2.5 text-[10px] font-bold uppercase tracking-wider text-center rounded-r-lg">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($petaniPending as $index => $petani)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="p-2.5 text-xs text-gray-500 font-mono">{{ $index + 1 }}</td>
                                    <td class="p-2.5 text-xs">
                                        <p class="font-bold text-gray-800 leading-tight">{{ $petani->petani_nama }}</p>
                                        <p class="text-[10px] text-gray-400">{{ $petani->petani_email ?? '-' }}</p>
                                    </td>
                                    <td class="p-2.5 text-center">
                                        <span class="inline-block px-2 py-0.5 bg-amber-50 text-amber-700 rounded text-[10px] font-bold border border-amber-100">
                                            {{ $petani->petani_status }}
                                        </span>
                                    </td>
                                    <td class="p-2.5 text-center whitespace-nowrap">
                                        <button type="button" title="Verifikasi / Edit" 
                                                class="px-2.5 py-1 bg-[#234323] hover:bg-[#184D2E] text-white rounded-lg text-xs font-semibold transition shadow-2xs flex items-center gap-1 mx-auto"
                                                onclick="openEditModal('{{ $petani->petani_id }}', '{{ addslashes($petani->petani_nama) }}', '{{ $petani->petani_status }}', '{{ addslashes($petani->petani_email ?? '-') }}', '{{ addslashes($petani->petani_no_hp ?? '-') }}', '{{ addslashes($petani->petani_alamat ?? '-') }}', '{{ addslashes($petani->petani_jenis_kelamin ?? '-') }}', '{{ addslashes($petani->desa_nama ?? '-') }}')">
                                            <x-heroicon-o-pencil-square class="w-3.5 h-3.5" />
                                            <span>Verifikasi</span>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-4 text-center text-xs text-gray-400">Tidak ada pengajuan akun baru yang pending.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Modal Edit Status Petani --}}
<div id="statusModal" class="fixed inset-0 z-50 hidden bg-black/40 items-center justify-center transition-opacity p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform" id="modalContent">
        
        <form id="formUbahStatus" method="POST" action="">
            @csrf
            @method('PUT')
            
            {{-- Header --}}
            <div class="px-5 py-4 flex justify-between items-center border-b border-gray-100 bg-gray-50">
                <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2 font-poppins">
                    <x-heroicon-o-user-circle class="w-5 h-5 text-[#234323]" />
                    Aktivasi Akun Petani
                </h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <x-heroicon-o-x-mark class="w-5 h-5" />
                </button>
            </div>  
            
            <div class="p-5 space-y-4">
                {{-- Info Utama --}}
                <div class="flex items-center gap-3 bg-[#234323]/5 p-3 rounded-xl border border-[#234323]/10">
                    <div class="w-10 h-10 bg-[#234323]/10 text-[#234323] rounded-full flex items-center justify-center shrink-0">
                        <x-heroicon-o-user class="w-5 h-5" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p id="modalNamaPetani" class="text-sm font-bold text-gray-900 truncate"></p>
                        <p id="modalEmailPetani" class="text-[11px] text-gray-500 truncate"></p>
                    </div>
                </div>

                {{-- Detail Info --}}
                <div class="bg-gray-50 rounded-xl p-3 text-xs border border-gray-100">
                    <div class="grid grid-cols-2 gap-y-3 gap-x-2">
                        <div>
                            <span class="block text-gray-400 font-medium mb-0.5 text-[10px] uppercase tracking-wider">No. HP</span>
                            <span id="modalHpPetani" class="text-gray-700 font-medium font-mono"></span>
                        </div>
                        <div>
                            <span class="block text-gray-400 font-medium mb-0.5 text-[10px] uppercase tracking-wider">Kelamin</span>
                            <span id="modalJkPetani" class="text-gray-700 font-medium"></span>
                        </div>
                        <div>
                            <span class="block text-gray-400 font-medium mb-0.5 text-[10px] uppercase tracking-wider">Desa</span>
                            <span id="modalDesaPetani" class="text-gray-700 font-medium truncate"></span>
                        </div>
                        <div>
                            <span class="block text-gray-400 font-medium mb-0.5 text-[10px] uppercase tracking-wider">Alamat</span>
                            <span id="modalAlamatPetani" class="text-gray-700 font-medium truncate block"></span>
                        </div>
                    </div>
                </div>
                
                {{-- Form Status --}}
                <div>
                    <label for="selectStatus" class="block text-xs font-bold text-gray-700 mb-1.5">Ubah Status Akun</label>
                    <div class="relative">
                        <select id="selectStatus" name="petani_status" class="w-full border border-gray-200 rounded-xl pl-3.5 pr-8 py-2 text-sm focus:border-[#234323] outline-none bg-white text-gray-700 appearance-none font-medium shadow-xs transition cursor-pointer">
                            <option value="Pending">Pending (Menunggu)</option>
                            <option value="Aktif">Disetujui (Aktif)</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                            <x-heroicon-o-chevron-down class="w-4 h-4" />
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Footer Action --}}
            <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-xs font-bold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-100 transition">Batal</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-[#214122] rounded-xl hover:bg-[#1b3d1b] transition shadow-sm">Simpan Status</button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
    function openEditModal(id, nama, status, email, hp, alamat, jk, desa) {
        document.getElementById('modalNamaPetani').innerText = nama;
        document.getElementById('modalEmailPetani').innerText = email;
        document.getElementById('modalHpPetani').innerText = hp;
        document.getElementById('modalJkPetani').innerText = jk;
        document.getElementById('modalDesaPetani').innerText = desa;
        document.getElementById('modalAlamatPetani').innerText = alamat;
        
        document.getElementById('selectStatus').value = status;
        document.getElementById('formUbahStatus').action = `/dashboard/petani/${id}/status`;
        
        const modal = document.getElementById('statusModal');
        const modalContent = document.getElementById('modalContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => {
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
    }

    function closeEditModal() {
        const modal = document.getElementById('statusModal');
        const modalContent = document.getElementById('modalContent');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 150);
    }

    function initSuperAdminDashboard() {
        if (typeof $ !== 'undefined' && $('#tabelPetani').length) {
            if ($.fn.DataTable.isDataTable('#tabelPetani')) { $('#tabelPetani').DataTable().destroy(); }
            $('#tabelPetani').DataTable({
                "pageLength": 5, 
                "lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
                "language": {
                    "search": "",
                    "searchPlaceholder": "Cari petani pending...",
                    "emptyTable": "Tidak ada pengajuan akun petani baru."
                },
                "responsive": true,
                "columnDefs": [
                    { "orderable": false, "targets": [0, 3] }
                ],
                "dom": '<"flex justify-between items-center w-full mb-3 gap-2" f> <"overflow-x-auto w-full" tr> <"flex justify-between items-center gap-2 mt-3 text-xs" p>'
            });
        }

        const canvasPemasukan = document.getElementById('chartPemasukan');
        if (canvasPemasukan) {
            const ctxPemasukan = canvasPemasukan.getContext('2d');
            const dataPemasukan = @json(array_values($pemasukanGrafik ?? []));

            new Chart(ctxPemasukan, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Total Pemasukan (Rp)',
                        data: dataPemasukan,
                        borderColor: '#234323',
                        backgroundColor: 'rgba(35, 67, 35, 0.08)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#D4AF37',
                        pointBorderColor: '#ffffff',
                        pointRadius: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#F3F4F6' },
                            ticks: { 
                                font: { size: 10 },
                                callback: value => 'Rp ' + Number(value).toLocaleString('id-ID') 
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });
        }

        const canvasPengeluaran = document.getElementById('chartPengeluaran');
        if (canvasPengeluaran) {
            const ctxPengeluaran = canvasPengeluaran.getContext('2d');
            const rawPengeluaran = @json($pengeluaranGrafik ?? []);
            const labelsPengeluaran = Array.isArray(rawPengeluaran) ? rawPengeluaran.map(item => item.biaya_jenis) : [];
            const dataPengeluaran = Array.isArray(rawPengeluaran) ? rawPengeluaran.map(item => item.total) : [];

            new Chart(ctxPengeluaran, {
                type: 'doughnut',
                data: {
                    labels: labelsPengeluaran.length ? labelsPengeluaran : ['Belum Ada Pengeluaran'],
                    datasets: [{
                        data: dataPengeluaran.length ? dataPengeluaran : [1],
                        backgroundColor: ['#234323', '#D4AF37', '#10B981', '#3B82F6', '#F59E0B', '#EF4444'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 10, font: { size: 9.5 } }
                        }
                    }
                }
            });
        }

        const mapEl = document.getElementById('mapSebaran');
        if (mapEl && typeof L !== 'undefined') {
            const mapSebaran = L.map('mapSebaran', { minZoom: 3, maxZoom: 19 }).setView([0.65, 101.85], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(mapSebaran);

            const polygonGroup = L.featureGroup().addTo(mapSebaran);
            const listLahan = @json($semuaLahan ?? []);

            listLahan.forEach(function(lahan) {
                if (lahan.area_lahan) {
                    try {
                        let areaData = typeof lahan.area_lahan === 'string' ? JSON.parse(lahan.area_lahan) : lahan.area_lahan;

                        if (areaData && areaData.geometry && areaData.geometry.coordinates) {
                            areaData = areaData.geometry.coordinates[0];
                        } else if (areaData && areaData.coordinates) {
                            areaData = areaData.coordinates[0];
                        } else if (areaData && areaData.features && areaData.features[0]) {
                            areaData = areaData.features[0].geometry.coordinates[0];
                        }

                        if (Array.isArray(areaData) && areaData.length > 0) {
                            const polyCoords = areaData.map(coord => {
                                if (coord !== null && typeof coord === 'object' && 'lat' in coord && 'lng' in coord) {
                                    return [coord.lat, coord.lng];
                                } else if (Array.isArray(coord) && coord.length >= 2) {
                                    if (Math.abs(coord[0]) > 90) {
                                        return [coord[1], coord[0]];
                                    }
                                    return [coord[0], coord[1]];
                                }
                                return null;
                            }).filter(c => c !== null);

                            if (polyCoords.length > 0) {
                                const polygon = L.polygon(polyCoords, {
                                    color: '#234323',
                                    fillColor: '#D4AF37',
                                    fillOpacity: 0.35,
                                    weight: 2
                                });

                                polygon.bindPopup(`
                                    <div style="font-family: sans-serif; font-size: 12px; min-width: 170px;">
                                        <strong style="color: #234323; font-size: 13px;">${lahan.lahan_nama || 'Blok Lahan'}</strong><br>
                                        <hr style="margin: 4px 0; border: 0; border-top: 1px solid #e5e7eb;">
                                        <b>Pemilik:</b> ${lahan.petani_nama || '-'}<br>
                                        <b>Lokasi:</b> ${lahan.lahan_lokasi || '-'}<br>
                                        <b>Luas:</b> ${lahan.lahan_luas || '0'} Ha
                                    </div>
                                `);

                                polygon.addTo(polygonGroup);
                            }
                        }
                    } catch (e) {
                        console.error("Gagal rendering polygon pada Lahan ID: " + lahan.lahan_id, e);
                    }
                }
            });

            setTimeout(() => {
                mapSebaran.invalidateSize();
                if (polygonGroup.getLayers().length > 0) {
                    mapSebaran.fitBounds(polygonGroup.getBounds(), { padding: [30, 30] });
                }
            }, 300);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSuperAdminDashboard);
    } else {
        initSuperAdminDashboard();
    }
</script>
@endsection