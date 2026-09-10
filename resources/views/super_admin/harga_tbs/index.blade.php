@extends('layouts.dashboard')

@section('title', 'Kelola Harga TBS')

@section('content')
<div class="p-2">
    {{-- Header Section --}}
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#214122] font-poppins">Input Harga TBS Sawit</h1>
            <p class="text-sm text-gray-500">Kelola & Update Harga TBS Dinas Perkebunan & PT. SAR</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative text-sm flex items-center shadow-sm" role="alert">
            <x-heroicon-o-check-circle class="w-5 h-5 mr-2 shrink-0" />
            <span class="block sm:inline font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Form Input Harga Baru (Col 5) --}}
        <div class="lg:col-span-5">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-[#214122] p-4 text-white flex items-center gap-3">
                    <x-heroicon-o-currency-dollar class="w-6 h-6 text-[#D4AF37]" />
                    <h2 class="font-bold text-base font-poppins">Form Input Harga TBS Baru</h2>
                </div>
                <div class="p-5">
                    <form action="{{ route('harga_tbs.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="harga_dinas" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                                Harga Dinas Perkebunan (Rp / Kg) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative rounded-lg shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500 font-bold text-sm">
                                    Rp
                                </div>
                                <input type="number" step="0.01" min="0" name="harga_dinas" id="harga_dinas" 
                                    class="pl-10 block w-full rounded-lg border-gray-300 focus:border-[#214122] focus:ring-[#214122] text-sm p-2.5 border" 
                                    placeholder="Contoh: 2850" required value="{{ old('harga_dinas', $hargaTerbaru->harga_dinas ?? '') }}">
                            </div>
                            @error('harga_dinas')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="harga_pt_sar" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                                Harga PT. SAR (Rp / Kg) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative rounded-lg shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500 font-bold text-sm">
                                    Rp
                                </div>
                                <input type="number" step="0.01" min="0" name="harga_pt_sar" id="harga_pt_sar" 
                                    class="pl-10 block w-full rounded-lg border-gray-300 focus:border-[#214122] focus:ring-[#214122] text-sm p-2.5 border" 
                                    placeholder="Contoh: 2780" required value="{{ old('harga_pt_sar', $hargaTerbaru->harga_pt_sar ?? '') }}">
                            </div>
                            @error('harga_pt_sar')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_berlaku" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                                Tanggal Berlaku <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_berlaku" id="tanggal_berlaku" 
                                class="block w-full rounded-lg border-gray-300 focus:border-[#214122] focus:ring-[#214122] text-sm p-2.5 border" 
                                required value="{{ old('tanggal_berlaku', date('Y-m-d')) }}">
                            @error('tanggal_berlaku')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full bg-[#214122] hover:bg-green-900 text-white font-bold py-3 px-4 rounded-xl shadow-md transition flex items-center justify-center gap-2 text-sm">
                                <x-heroicon-o-arrow-down-on-square class="w-5 h-5 text-[#D4AF37]" />
                                Simpan Harga TBS Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Display & Tabel Riwayat (Col 7) --}}
        <div class="lg:col-span-7 space-y-6">
            {{-- Preview Card Harga Aktif Saat Ini --}}
            <div class="bg-gradient-to-br from-[#1B4D2E] to-[#2E7D32] rounded-2xl p-5 text-white shadow-md relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 w-32 h-32 bg-white/5 rounded-full blur-xl pointer-events-none"></div>
                
                <div class="flex items-center justify-between mb-3 border-b border-white/20 pb-3">
                    <span class="text-xs font-bold tracking-wider uppercase text-emerald-200">HARGA TBS HARI INI</span>
                    <span class="text-xs bg-white/20 px-2.5 py-1 rounded-full font-mono">
                        Tmt: {{ $hargaTerbaru->tanggal_berlaku ?? date('Y-m-d') }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 my-2">
                    <div class="bg-white/10 p-3.5 rounded-xl border border-white/15 backdrop-blur-sm">
                        <p class="text-[11px] text-emerald-100 font-medium">Dinas Perkebunan</p>
                        <p class="text-xl font-extrabold text-[#FFD700] mt-0.5 font-mono">
                            Rp {{ number_format($hargaTerbaru->harga_dinas ?? 0, 0, ',', '.') }} <span class="text-xs text-white font-normal">/ Kg</span>
                        </p>
                    </div>

                    <div class="bg-white/10 p-3.5 rounded-xl border border-white/15 backdrop-blur-sm">
                        <p class="text-[11px] text-emerald-100 font-medium">PT. SAR</p>
                        <p class="text-xl font-extrabold text-[#FFD700] mt-0.5 font-mono">
                            Rp {{ number_format($hargaTerbaru->harga_pt_sar ?? 0, 0, ',', '.') }} <span class="text-xs text-white font-normal">/ Kg</span>
                        </p>
                    </div>
                </div>

                <p class="text-[10px] text-emerald-200 text-right mt-1 italic">
                    Terakhir diubah: {{ $hargaTerbaru && $hargaTerbaru->created_at ? $hargaTerbaru->created_at->format('d M Y H:i') : '-' }}
                </p>
            </div>

            {{-- Tabel Riwayat Perubahan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 text-sm font-poppins">Riwayat Perubahan Harga TBS</h3>
                    <span class="text-xs text-gray-500 font-mono">Total: {{ $riwayatHarga->total() }} Data</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-[#D4AF37] text-black font-extrabold uppercase">
                                <th class="p-3 text-center w-10">No</th>
                                <th class="p-3">Tgl Berlaku</th>
                                <th class="p-3 text-right">Harga Dinas</th>
                                <th class="p-3 text-right">Harga PT. SAR</th>
                                <th class="p-3">Diinput Oleh</th>
                                <th class="p-3 text-center">Waktu Input</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($riwayatHarga as $index => $row)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-3 text-center font-mono text-gray-400">
                                        {{ $riwayatHarga->firstItem() + $index }}
                                    </td>
                                    <td class="p-3">
                                        <span class="bg-emerald-50 text-emerald-800 border border-emerald-200 px-2 py-0.5 rounded font-mono font-semibold">
                                            {{ $row->tanggal_berlaku }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-right font-extrabold text-[#214122] font-mono">
                                        Rp {{ number_format($row->harga_dinas, 0, ',', '.') }}
                                    </td>
                                    <td class="p-3 text-right font-extrabold text-blue-800 font-mono">
                                        Rp {{ number_format($row->harga_pt_sar, 0, ',', '.') }}
                                    </td>
                                    <td class="p-3 text-gray-700 font-medium">
                                        {{ $row->creator->user_nama ?? 'System' }}
                                    </td>
                                    <td class="p-3 text-center text-gray-400 font-mono text-[11px]">
                                        {{ $row->created_at ? $row->created_at->format('d/m/Y H:i') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-gray-400">
                                        Belum ada data riwayat harga TBS.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($riwayatHarga->hasPages())
                    <div class="p-3 border-t border-gray-100 bg-gray-50">
                        {{ $riwayatHarga->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
