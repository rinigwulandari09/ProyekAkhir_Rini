@extends('layouts.dashboard')

@section('title', 'Edit Data Pemasukan (Produksi)')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        
        <div class="border-b border-slate-100 p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-[#214122] text-white rounded-lg">
                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Perbarui Data Pemasukan</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi catatan produksi dan hasil panen.</p>
                </div>
            </div>
            <span class="self-start sm:self-center text-xs font-mono font-medium bg-slate-100 text-slate-700 px-3 py-1 rounded-md border border-slate-200">
                Data ID: {{ $produksi->id }}
            </span>
        </div>

        <form action="{{ route('produksi.update', $produksi->id) }}" method="POST" enctype="multipart/form-data" class="p-5 sm:p-6 space-y-6">
            @csrf
            @method('PUT') 

            @if ($errors->any())
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-red-400" />
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pada inputan Anda:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700">Pilih Lahan <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <select
                            name="lahan_id"
                            class="w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-[#214122] focus:ring-4 focus:ring-[#214122]/10 appearance-none cursor-pointer transition"
                            required
                        >
                            <option value="">-- Pilih Lahan --</option>
                            @foreach($lahans as $lahan)
                                <option
                                    value="{{ $lahan->lahan_id }}"
                                    {{ old('lahan_id', $produksi->lahan_id) == $lahan->lahan_id ? 'selected' : '' }}
                                >
                                    {{ $lahan->lahan_nama ?: 'Lahan ' . $loop->iteration }}
                                </option>
                            @endforeach
                        </select>
                        <x-heroicon-o-chevron-down class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4 pointer-events-none" />
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700">Tanggal <span class="text-rose-500">*</span></label>
                    <input 
                        type="date" 
                        name="produksi_tanggal" 
                        value="{{ old('produksi_tanggal', $produksi->produksi_tanggal ? date('Y-m-d', strtotime($produksi->produksi_tanggal)) : '') }}" 
                        class="w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#214122] focus:ring-4 focus:ring-[#214122]/10 transition"
                        required
                    >
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700">Jumlah TBS (Kg) <span class="text-rose-500">*</span></label>
                    <input 
                        type="number" 
                        step="0.01" 
                        name="jumlah_tbs" 
                        id="jumlah_tbs" 
                        value="{{ old('jumlah_tbs', $produksi->jumlah_tbs) }}" 
                        class="w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#214122] focus:ring-4 focus:ring-[#214122]/10 transition"
                        required 
                        oninput="hitungTotalPendapatan()"
                    >
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700">Total Pendapatan (Rp)</label>
                    <input 
                        type="number" 
                        id="total_pendapatan" 
                        value="{{ old('total_pendapatan', $produksi->total_pendapatan) }}" 
                        class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg text-slate-500 cursor-not-allowed"
                        readonly
                    >
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700">Harga TBS (Rp) <span class="text-rose-500">*</span></label>
                    <input 
                        type="number" 
                        name="harga_tbs" 
                        id="harga_tbs" 
                        value="{{ old('harga_tbs', $produksi->harga_tbs) }}" 
                        class="w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#214122] focus:ring-4 focus:ring-[#214122]/10 transition"
                        required 
                        oninput="hitungTotalPendapatan()"
                    >
                </div>

                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-slate-700">Bukti Nota (Opsional)</label>
                    <input 
                        type="file" 
                        name="produksi_bukti" 
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#214122]/10 file:text-[#214122] hover:file:bg-[#214122]/20 transition cursor-pointer border border-slate-300 rounded-lg"
                    >
                    @if($produksi->produksi_bukti)
                        <p class="mt-2 text-xs text-slate-500">Bukti saat ini: <a href="{{ Storage::url($produksi->produksi_bukti) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File</a></p>
                    @endif
                    <p class="mt-1 text-[11px] text-slate-400 italic">Biarkan kosong jika tidak ingin mengubah bukti.</p>
                </div>

                <div class="space-y-1.5 md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">Keterangan</label>
                    <textarea 
                        name="produksi_ket" 
                        rows="3" 
                        class="w-full px-3.5 py-2 text-sm bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:border-[#214122] focus:ring-4 focus:ring-[#214122]/10 transition"
                    >{{ old('produksi_ket', $produksi->produksi_ket ?? $produksi->keterangan) }}</textarea>
                </div>

            </div>

            <div class="mt-8 flex flex-col-reverse sm:flex-row items-center justify-between gap-3 border-t border-slate-100 pt-5">
                <a href="{{ route('keuangan.show', $produksi->petani_id) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition border border-transparent hover:border-slate-200">
                    <x-heroicon-o-arrow-left class="w-4 h-4" />
                    Batal
                </a>
                
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#214122] text-white px-5 py-2 text-sm font-medium rounded-lg hover:bg-[#325a33] focus:ring-4 focus:ring-[#214122]/20 active:scale-[0.98] transition shadow-sm">
                    <x-heroicon-o-arrow-path class="w-4 h-4" />
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function hitungTotalPendapatan() {
        var jumlah = parseFloat(document.getElementById('jumlah_tbs').value) || 0;
        var harga = parseFloat(document.getElementById('harga_tbs').value) || 0;
        document.getElementById('total_pendapatan').value = jumlah * harga;
    }
</script>
@endsection
