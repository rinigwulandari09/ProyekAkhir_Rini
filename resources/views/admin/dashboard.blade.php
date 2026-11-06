@extends('layouts.admin')

@section('title', 'Beranda')

@section('content')
{{-- Leaflet Assets & DataTables --}}
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
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 border-t-4 border-t-[#D4AF37] p-5 sm:p-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-2">
                <!-- <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-50 text-amber-800 border border-amber-200/80 rounded-full text-xs font-semibold">
                    <x-heroicon-s-shield-check class="w-3.5 h-3.5 text-[#D4AF37]" />
                    <span>Asosiasi PSKS Pelalawan Siak • Sertifikasi RSPO & ISPO</span>
                </div> -->
                <h2 class="text-xl sm:text-2xl font-black text-gray-800 font-poppins">
                    Selamat Datang, {{ Auth::user()->user_nama }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 max-w-2xl leading-relaxed">
                    Pusat pemantauan dan kendali terpadu seluruh aktivitas perkebunan kelapa sawit anggota, luasan lahan, rekapitulasi tonase produksi, dan verifikasi audit sertifikasi.
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
                <a href="{{ route('kegiatan.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 transition">
                    <x-heroicon-o-clipboard-document-list class="w-4 h-4 text-amber-600" />
                    <span>Data Kegiatan</span>
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
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-gray-100 group flex flex-col justify-between relative overflow-hidden">
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
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-gray-100 group flex flex-col justify-between relative overflow-hidden">
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
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-gray-100 group flex flex-col justify-between relative overflow-hidden">
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
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-gray-100 group flex flex-col justify-between relative overflow-hidden">
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
                <span>Penjualan TBS bulan ini</span>
                <a href="{{ route('keuangan.index') }}" class="text-gray-300 group-hover:text-green-700 transition font-bold" title="Lihat Keuangan">↗</a>
            </div>
        </div>

        {{-- Card 5: AUDIT LULUS --}}
        <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-gray-100 group flex flex-col justify-between relative overflow-hidden">
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

    {{-- 1. CARD GRAFIK KEUANGAN & PRODUKSI (FULL WIDTH) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6 mb-6">
        <div class="flex items-center justify-between gap-4 mb-5 pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-[#234323]/10 text-[#234323] flex items-center justify-center">
                    <x-heroicon-o-presentation-chart-line class="w-5 h-5" />
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-800 font-poppins">Tren Keuangan & Produksi Bulanan</h3>
                    <p class="text-[11px] text-gray-400">Pemasukan penjualan TBS dan pengeluaran operasional perkebunan</p>
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

    {{-- 2. HASIL AUDIT & TABEL PENGINGAT TUGAS (DIBUAT SAMPINGAN / BERDAMPINGAN) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6 items-stretch">
        
        {{-- Card Hasil Audit (Kiri - 5 Kolom) --}}
        <div class="lg:col-span-5 flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
            <div class="flex items-center justify-between gap-3 mb-4 pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center">
                        <x-heroicon-o-clipboard-document-check class="w-5 h-5 text-teal-700" />
                    </div>
                    <div>
                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-gray-800 font-poppins">Hasil Audit Internal</h3>
                        <p class="text-[10.5px] text-gray-400">Status sertifikasi & temuan lapangan</p>
                    </div>
                </div>
                <a href="{{ route('audit.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 hover:text-emerald-800 transition">
                    <span>Lihat Semua</span>
                    <x-heroicon-o-arrow-right class="w-3.5 h-3.5" />
                </a>
            </div>

            {{-- Status Audit Summary Items --}}
            <div class="space-y-3 flex-1 flex flex-col justify-center">
                <div class="p-3.5 bg-gray-50 hover:bg-emerald-50/50 border border-gray-100 rounded-xl flex items-center justify-between transition">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                            <x-heroicon-s-check-circle class="w-4 h-4" />
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-800">Audit Lulus (Sesuai)</p>
                            <p class="text-[10px] text-gray-400">Telah memenuhi kriteria sertifikasi</p>
                        </div>
                    </div>
                    <span class="w-8 h-8 bg-emerald-600 text-white text-xs font-black rounded-lg flex items-center justify-center shadow-xs">
                        {{ $auditLulus }}
                    </span>
                </div>

                <div class="p-3.5 bg-gray-50 hover:bg-amber-50/50 border border-gray-100 rounded-xl flex items-center justify-between transition">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                            <x-heroicon-s-information-circle class="w-4 h-4" />
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-800">Butuh Tindak Lanjut</p>
                            <p class="text-[10px] text-gray-400">Perlu perbaikan temuan lapangan</p>
                        </div>
                    </div>
                    <span class="w-8 h-8 bg-amber-500 text-white text-xs font-black rounded-lg flex items-center justify-center shadow-xs">
                        {{ $auditPerbaikan }}
                    </span>
                </div>

                <div class="p-3.5 bg-gray-50 hover:bg-rose-50/50 border border-gray-100 rounded-xl flex items-center justify-between transition">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                            <x-heroicon-s-clock class="w-4 h-4" />
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-800">Menunggu Verifikasi</p>
                            <p class="text-[10px] text-gray-400">Belum diputuskan auditor</p>
                        </div>
                    </div>
                    <span class="w-8 h-8 bg-rose-600 text-white text-xs font-black rounded-lg flex items-center justify-center shadow-xs">
                        {{ $auditPending }}
                    </span>
                </div>
            </div>

            @php
                $totalAuditAll = ($auditLulus ?? 0) + ($auditPerbaikan ?? 0) + ($auditPending ?? 0);
                $persenLulus = $totalAuditAll > 0 ? round(($auditLulus / $totalAuditAll) * 100) : 0;
            @endphp
            <div class="mt-4 pt-3 border-t border-gray-100">
                <div class="flex items-center justify-between text-xs mb-1.5">
                    <span class="text-gray-500 font-medium">Kepatuhan Audit Selesai</span>
                    <span class="font-bold text-emerald-700">{{ $persenLulus }}%</span>
                </div>
                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-emerald-600 to-green-500 rounded-full transition-all duration-500" style="width: {{ $persenLulus }}%"></div>
                </div>
            </div>
        </div>

        {{-- Card Tabel Pengingat Tugas (Kanan - 7 Kolom) --}}
        <div class="lg:col-span-7 flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#234323]/10 text-[#234323] flex items-center justify-center">
                        <x-heroicon-o-bell-alert class="w-5 h-5 text-[#D4AF37]" />
                    </div>
                    <div>
                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-gray-800 font-poppins">Daftar Tugas Aktif</h3>
                        <p class="text-[10.5px] text-gray-400">Tenggat waktu & tindak lanjut operasional</p>
                    </div>
                </div>
                <span class="text-[10px] font-bold text-amber-800 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200 self-start sm:self-auto">
                    {{ count($taskNotifications) }} Tugas Terdaftar
                </span>
            </div>

            {{-- Custom Modern Search Bar --}}
            <div class="relative w-full mb-3">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <x-heroicon-o-magnifying-glass class="w-4 h-4 text-emerald-700" />
                </div>
                <input type="text" id="customSearchTugas" 
                       class="w-full pl-9 pr-24 py-2 bg-gray-50/80 hover:bg-gray-100/70 focus:bg-white border border-gray-200 focus:border-[#234323] focus:ring-2 focus:ring-[#234323]/15 rounded-xl text-xs text-gray-800 placeholder-gray-400 transition shadow-2xs outline-none"
                       placeholder="Cari tugas, instruksi, tanggal...">
                <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
                    <span class="text-[10px] font-bold text-[#234323] bg-emerald-50 border border-emerald-200/80 px-2 py-0.5 rounded-md">
                        {{ count($taskNotifications) }} Tugas
                    </span>
                </div>
            </div>

            {{-- Table Container --}}
            <div class="w-full overflow-hidden rounded-xl border border-gray-100 shadow-2xs flex-1">
                <table id="tugasTable" class="w-full text-left border-collapse table-auto">
                    <thead>
                        <tr class="bg-gradient-to-r from-[#234323] to-[#1C3B1C] text-white">
                            <th class="py-2.5 px-3 text-[10px] font-extrabold uppercase tracking-wider text-center w-10 text-[#D4AF37]">#</th>
                            <th class="py-2.5 px-3 text-[10px] font-extrabold uppercase tracking-wider">Judul & Pesan</th>
                            <th class="py-2.5 px-3 text-[10px] font-extrabold uppercase tracking-wider text-center w-28">Deadline</th>
                            <th class="py-2.5 px-3 text-[10px] font-extrabold uppercase tracking-wider text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs bg-white">
                        @forelse($taskNotifications as $task)
                            <tr class="hover:bg-emerald-50/25 transition-colors group">
                                <td class="py-2.5 px-2 text-center">
                                    <span class="w-6 h-6 mx-auto rounded-lg bg-gray-100 group-hover:bg-[#234323] group-hover:text-[#D4AF37] text-gray-600 text-[10.5px] font-bold flex items-center justify-center font-mono transition shadow-2xs"></span>
                                </td>
                                <td class="py-2.5 px-3">
                                    <p class="font-bold text-gray-800 group-hover:text-[#234323] transition leading-snug text-xs">{{ $task->judul }}</p>
                                    <p class="text-[10.5px] text-gray-500 line-clamp-1 mt-0.5 leading-tight">{{ $task->pesan }}</p>
                                </td>
                                <td class="py-2.5 px-2 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-50 text-amber-800 rounded-md text-[10px] font-bold border border-amber-200/80 shadow-2xs">
                                        <x-heroicon-o-calendar class="w-3 h-3 text-[#D4AF37]" />
                                        <span>{{ $task->deadline ? date('d/m/Y', strtotime($task->deadline)) : '-' }}</span>
                                    </span>
                                </td>
                                <td class="py-2.5 px-2 text-center whitespace-nowrap">
                                    <form action="{{ route('tugas.complete', ['id' => $task->id]) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menandai tugas ini sebagai selesai?')">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-gradient-to-r from-[#234323] to-[#184D2E] hover:from-[#184D2E] hover:to-[#0F351D] px-2.5 py-1 text-[10.5px] font-bold text-white transition shadow-2xs hover:shadow-xs transform hover:-translate-y-0.5 cursor-pointer">
                                            <x-heroicon-s-check class="w-3.5 h-3.5 text-[#D4AF37]" />
                                            <span>Selesai</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-xs text-gray-400">
                                    <x-heroicon-o-clipboard-document-check class="w-7 h-7 text-gray-300 mx-auto mb-1" />
                                    <p class="font-bold text-gray-600">Tidak ada tugas aktif.</p>
                                    <p class="text-[10px] text-gray-400">Semua target pekerjaan telah selesai.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- 3. GRID 2 KOLOM SEIMBANG: TREN HARGA TBS & KALENDER JADWAL AUDIT --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch mb-6">
        
        {{-- Card Tren Harga TBS (50% Kolom) --}}
        <div class="w-full flex flex-col justify-between bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5 pb-3 border-b border-gray-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#234323] flex items-center justify-center">
                            <x-heroicon-o-arrow-trending-up class="w-5 h-5 text-[#234323]" />
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-800 font-poppins">Tren Harga TBS Sawit (PT. SAR vs Dinas)</h3>
                            <p class="text-[11px] text-gray-400">Fluktuasi harga Tandan Buah Segar (Rp/Kg) pabrik PT. SAR dan ketetapan Dinas Perkebunan</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-800 border border-amber-200 rounded-full text-[10px] font-bold">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            <span>Update Berkala</span>
                        </span>
                        <a href="{{ route('harga_tbs.index') }}" class="inline-flex items-center gap-1 px-3 py-1 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 transition">
                            <x-heroicon-o-pencil-square class="w-3.5 h-3.5 text-emerald-700" />
                            <span>Kelola Harga</span>
                        </a>
                    </div>
                </div>

                {{-- Mini Summary Pills --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-4">
                    <div class="p-3 bg-gray-50 border border-gray-100 rounded-xl">
                        <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-wider text-gray-400 mb-1">
                            <span>Harga PT. SAR</span>
                            <span class="w-2.5 h-2.5 rounded-full bg-[#234323]" title="Indikator PT. SAR"></span>
                        </div>
                        <p class="text-base sm:text-lg font-black text-gray-800 font-poppins">
                            Rp {{ number_format($hargaTbsTerbaru->harga_pt_sar ?? 0, 0, ',', '.') }} <span class="text-[10px] font-medium text-gray-400">/Kg</span>
                        </p>
                    </div>

                    <div class="p-3 bg-gray-50 border border-gray-100 rounded-xl">
                        <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-wider text-gray-400 mb-1">
                            <span>Harga Dinas</span>
                            <span class="w-2.5 h-2.5 rounded-full bg-[#D4AF37]" title="Indikator Dinas Perkebunan"></span>
                        </div>
                        <p class="text-base sm:text-lg font-black text-gray-800 font-poppins">
                            Rp {{ number_format($hargaTbsTerbaru->harga_dinas ?? 0, 0, ',', '.') }} <span class="text-[10px] font-medium text-gray-400">/Kg</span>
                        </p>
                    </div>

                    <div class="p-3 bg-gray-50 border border-gray-100 rounded-xl col-span-2 sm:col-span-1">
                        <div class="flex items-center justify-between text-[10px] font-extrabold uppercase tracking-wider text-gray-400 mb-1">
                            <span>Selisih Margin</span>
                            <span class="text-[10px] font-semibold text-gray-400">Per Kg</span>
                        </div>
                        @php
                            $diff = ($hargaTbsTerbaru->harga_pt_sar ?? 0) - ($hargaTbsTerbaru->harga_dinas ?? 0);
                        @endphp
                        <p class="text-base sm:text-lg font-black font-poppins {{ $diff >= 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                            {{ $diff > 0 ? '+' : '' }}Rp {{ number_format($diff, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Chart Canvas / Empty State --}}
            <div class="relative w-full flex-1 min-h-[280px] sm:min-h-[300px] flex flex-col justify-center">
                @if(count($tbsLabels ?? []) > 0)
                    <canvas id="chartTrendTbs"></canvas>
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-xl bg-gray-50/50 p-6 text-center">
                        <div class="w-12 h-12 rounded-full bg-emerald-50 text-[#234323] flex items-center justify-center mb-2">
                            <x-heroicon-o-currency-dollar class="w-6 h-6 text-[#D4AF37]" />
                        </div>
                        <h4 class="text-xs sm:text-sm font-bold text-gray-700 font-poppins">Belum Ada Riwayat Harga TBS</h4>
                        <p class="text-[11px] text-gray-400 max-w-sm mt-1">Grafik pergerakan harga PT. SAR vs Dinas Perkebunan akan langsung tampil secara otomatis setelah data harga diinput.</p>
                        <a href="{{ route('harga_tbs.index') }}" class="mt-3 inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#234323] hover:bg-[#184D2E] text-white rounded-xl text-xs font-bold transition shadow-xs">
                            <x-heroicon-o-plus-circle class="w-4 h-4 text-[#D4AF37]" />
                            <span>Input Harga Sekarang</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Card Kalender Jadwal Audit (50% Kolom) --}}
        <div class="w-full flex flex-col justify-between bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
            <div class="flex items-center justify-between gap-2 mb-4 pb-3 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-calendar class="w-5 h-5 text-[#234323]" />
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider font-poppins">Kalender Jadwal Audit</h3>
                </div>
                <span class="text-[10px] font-semibold text-gray-400">Bulan {{ date('F Y') }}</span>
            </div>
            <div id="tugasCalendar" class="w-full flex-1 bg-gray-50/50 rounded-xl border border-gray-100 p-2.5 flex flex-col"></div>
        </div>

    </div>

    {{-- 4. PETA SEBARAN LAHAN SPASIAL (FULL WIDTH - PALING BAWAH) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-[#D4AF37]/20 text-[#214122] flex items-center justify-center">
                    <x-heroicon-o-map-pin class="w-5 h-5 text-[#214122]" />
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-800 font-poppins">Sebaran Lahan Anggota (Peta Spasial GIS)</h3>
                    <p class="text-[11px] text-gray-400">Pemetaan poligon hamparan kebun kelapa sawit bersertifikasi</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                {{-- Toggle Mode Peta: Satelit vs Biasa --}}
                <div class="inline-flex items-center bg-gray-100 p-1 rounded-xl border border-gray-200 shadow-2xs">
                    <button type="button" id="btnMapSatelit" onclick="setDashboardMapMode('satelit')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-xs bg-[#214122] text-white" title="Ganti ke Citra Satelit Google">
                        <svg class="w-3.5 h-3.5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Mode Satelit</span>
                    </button>
                    <button type="button" id="btnMapBiasa" onclick="setDashboardMapMode('biasa')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-600 hover:text-gray-900 transition" title="Ganti ke Peta Standar OpenStreetMap">
                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                        <span>Mode Biasa</span>
                    </button>
                </div>

                <span class="text-xs font-semibold text-gray-600 bg-gray-50 border border-gray-200 px-3 py-1.5 rounded-xl shadow-2xs">
                    {{ count($semuaLahan ?? []) }} Poligon Terpetakan
                </span>
                <a href="{{ route('lahan.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#234323] hover:bg-[#184D2E] text-white rounded-xl text-xs font-bold transition shadow-xs">
                    <x-heroicon-o-map class="w-3.5 h-3.5 text-[#D4AF37]" />
                    <span>Kelola Data Lahan</span>
                </a>
            </div>
        </div>
        <div id="mapSebaran" class="w-full h-96 sm:h-[460px] rounded-xl bg-gray-100 relative border border-gray-200" style="z-index: 1;"></div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

<script>
    function initAdminDashboard() {
        if (typeof $ !== 'undefined' && $('#tugasTable').length) {
            if ($.fn.DataTable.isDataTable('#tugasTable')) { $('#tugasTable').DataTable().destroy(); }
            var tableTugas = $('#tugasTable').DataTable({
                "pageLength": 5, 
                "lengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
                "language": {
                    "emptyTable": "Tidak ada tugas aktif untuk ditampilkan.",
                    "info": "Menampilkan _START_-_END_ dari _TOTAL_ tugas",
                    "infoEmpty": "0 tugas",
                    "infoFiltered": "(dari _MAX_ total)",
                    "paginate": {
                        "first": "«",
                        "last": "»",
                        "next": "›",
                        "previous": "‹"
                    }
                },
                "responsive": false,
                "autoWidth": false,
                "columnDefs": [
                    { "orderable": false, "searchable": false, "targets": [0, 3] },
                    { "width": "8%", "targets": 0 },
                    { "width": "54%", "targets": 1 },
                    { "width": "22%", "targets": 2 },
                    { "width": "16%", "targets": 3 }
                ],
                "dom": 't<"flex flex-col sm:flex-row items-center justify-between gap-2 mt-3 pt-2 text-xs text-gray-500"ip>'
            });

            // Live search dengan custom search bar modern
            $('#customSearchTugas').off('input').on('input', function () {
                tableTugas.search(this.value).draw();
            });

            tableTugas.on('order.dt search.dt draw.dt', function () {
                let start = tableTugas.page.info().start;
                tableTugas.column(0, { search: 'applied', order: 'applied' }).nodes().each(function(cell, i) {
                    let badge = cell.querySelector('span');
                    if (badge) {
                        badge.textContent = start + i + 1;
                    } else {
                        cell.innerHTML = start + i + 1;
                    }
                });
            }).draw();
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

        const canvasTbs = document.getElementById('chartTrendTbs');
        if (canvasTbs) {
            const ctxTbs = canvasTbs.getContext('2d');
            const labelsTbs = @json($tbsLabels ?? []);
            const dataPtSar = @json($tbsPtSar ?? []);
            const dataDinas = @json($tbsDinas ?? []);

            new Chart(ctxTbs, {
                type: 'line',
                data: {
                    labels: labelsTbs,
                    datasets: [
                        {
                            label: 'Harga PT. SAR (Pabrik)',
                            data: dataPtSar,
                            borderColor: '#234323',
                            backgroundColor: 'rgba(35, 67, 35, 0.08)',
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#234323',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Harga Dinas Perkebunan',
                            data: dataDinas,
                            borderColor: '#D4AF37',
                            backgroundColor: 'rgba(212, 175, 55, 0.08)',
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#D4AF37',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                font: { size: 11, weight: '600' }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' + Number(context.parsed.y).toLocaleString('id-ID') + ' / Kg';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
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

        const mapEl = document.getElementById('mapSebaran');
        if (mapEl && typeof L !== 'undefined') {
            const mapSebaran = L.map('mapSebaran', { minZoom: 3, maxZoom: 20 }).setView([0.65, 101.85], 13);

            // Layer 1: Peta Biasa (OpenStreetMap)
            const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19
            });

            // Layer 2: Mode Satelit (Google Hybrid: Citra Satelit + Label Jalan & Batas)
            const satelliteLayer = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
                attribution: '&copy; Google Maps',
                maxZoom: 20
            });

            // Baca preferensi mode peta dari localStorage atau default ke satelit
            const savedMode = localStorage.getItem('dashboard_map_mode') || 'satelit';
            if (savedMode === 'biasa') {
                osmLayer.addTo(mapSebaran);
            } else {
                satelliteLayer.addTo(mapSebaran);
            }

            // Tambahkan kontrol pilihan layer Leaflet di pojok kanan atas
            const baseMaps = {
                "🛰️ Mode Satelit (Google)": satelliteLayer,
                "🗺️ Mode Biasa (OSM)": osmLayer
            };
            L.control.layers(baseMaps, null, { position: 'topright' }).addTo(mapSebaran);

            // Handler global untuk switch layer dari tombol toolbar
            window.setDashboardMapMode = function(mode) {
                const btnSatelit = document.getElementById('btnMapSatelit');
                const btnBiasa = document.getElementById('btnMapBiasa');

                if (mode === 'satelit') {
                    if (mapSebaran.hasLayer(osmLayer)) mapSebaran.removeLayer(osmLayer);
                    if (!mapSebaran.hasLayer(satelliteLayer)) satelliteLayer.addTo(mapSebaran);

                    if (btnSatelit) {
                        btnSatelit.className = "inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-xs bg-[#214122] text-white";
                    }
                    if (btnBiasa) {
                        btnBiasa.className = "inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-600 hover:text-gray-900 transition";
                    }
                    localStorage.setItem('dashboard_map_mode', 'satelit');
                } else {
                    if (mapSebaran.hasLayer(satelliteLayer)) mapSebaran.removeLayer(satelliteLayer);
                    if (!mapSebaran.hasLayer(osmLayer)) osmLayer.addTo(mapSebaran);

                    if (btnBiasa) {
                        btnBiasa.className = "inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-xs bg-[#214122] text-white";
                    }
                    if (btnSatelit) {
                        btnSatelit.className = "inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-600 hover:text-gray-900 transition";
                    }
                    localStorage.setItem('dashboard_map_mode', 'biasa');
                }
            };

            // Sinkronisasi tombol jika layer diubah via kontrol pojok kanan atas Leaflet
            mapSebaran.on('baselayerchange', function(e) {
                if (e.name.indexOf('Satelit') !== -1) {
                    window.setDashboardMapMode('satelit');
                } else {
                    window.setDashboardMapMode('biasa');
                }
            });

            // Set state tombol aktif sesuai saved mode
            window.setDashboardMapMode(savedMode);

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
                                    color: '#D4AF37',
                                    fillColor: '#22c55e',
                                    fillOpacity: 0.35,
                                    weight: 2.5
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

        // Inisialisasi FullCalendar
        var calendarEl = document.getElementById('tugasCalendar');
        var kalenderEvents = {!! $kalenderEvents ?? '[]' !!};

        if(calendarEl && typeof FullCalendar !== 'undefined') {
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: '100%',
                expandRows: true,
                aspectRatio: 1.5,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listMonth'
                },
                events: kalenderEvents,
                locale: 'id',
                buttonText: {
                    today: 'Hari Ini',
                    month: 'Bulan',
                    list: 'Daftar'
                }
            });
            calendar.render();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAdminDashboard);
    } else {
        initAdminDashboard();
    }
</script>

<style>
    /* Modern Custom DataTables Styling */
    table.dataTable.no-footer { border-bottom: none !important; }
    table.dataTable thead th, table.dataTable thead td { border-bottom: none !important; }
    table.dataTable tbody td { border-top: 1px solid #f3f4f6 !important; }
    .dataTables_wrapper { width: 100% !important; overflow: hidden !important; }
    .dataTables_wrapper .dataTables_paginate {
        display: flex !important;
        align-items: center !important;
        gap: 3px !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 8px !important;
        padding: 3px 8px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        border: 1px solid #e5e7eb !important;
        background: #ffffff !important;
        color: #4b5563 !important;
        margin: 0 !important;
        cursor: pointer !important;
        transition: all 0.15s ease !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f3f4f6 !important;
        border-color: #d1d5db !important;
        color: #1f2937 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #234323 !important;
        border-color: #234323 !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        opacity: 0.35 !important;
        cursor: not-allowed !important;
        background: #f9fafb !important;
        border-color: #f3f4f6 !important;
        color: #9ca3af !important;
    }
    .dataTables_wrapper .dataTables_info {
        font-size: 10.5px !important;
        color: #6b7280 !important;
        padding-top: 0 !important;
    }

    /* FullCalendar Styling */
    .fc { font-family: inherit !important; font-size: 0.78rem !important; }
    .fc-theme-standard td, .fc-theme-standard th { border-color: #f3f4f6 !important; }
    .fc-col-header-cell { background-color: #f9fafb; padding: 4px 0 !important; font-weight: 700; font-size: 0.7rem !important; color: #6b7280; text-transform: uppercase; }
    .fc-daygrid-day-number { color: #4b5563; font-weight: 600; font-size: 0.75rem !important; padding: 2px 4px !important; }
    .fc-daygrid-day-frame { min-height: 42px !important; }
    .fc-day-today { background-color: #f0fdf4 !important; }
    .fc-toolbar { margin-bottom: 0.6rem !important; }
    .fc-toolbar-title { font-size: 0.9rem !important; font-weight: 700 !important; color: #1f2937 !important; }
    .fc-button-primary { 
        background-color: #ffffff !important; 
        color: #374151 !important;
        border: 1px solid #e5e7eb !important; 
        border-radius: 6px !important; 
        font-size: 0.75rem !important;
        padding: 3px 8px !important;
    }
    .fc-button-primary:not(:disabled).fc-button-active { 
        background-color: #234323 !important; 
        border-color: #234323 !important; 
        color: #ffffff !important;
    }
</style>
@endsection
