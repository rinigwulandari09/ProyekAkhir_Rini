@extends('layouts.dashboard')

@section('title', 'Tambah Pengingat')

@section('content')
<div class="p-2 max-w-5xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#214122]">Tambah Pengingat</h1>
        <p class="text-xs text-gray-500 mt-1">Kirim pesan pengingat otomatis ke petani atau administrator lainnya.</p>
    </div>

    {{-- Form Container --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
        {{-- Header Form --}}
        <div class="bg-[#214122] p-4 flex items-center gap-3">
            <iconify-icon icon="mdi:bullhorn-variant-outline" class="text-white text-xl"></iconify-icon>
            <h2 class="text-white font-bold text-sm">Tambah Pengingat</h2>
        </div>

        <form action="#" method="POST" class="p-8">
            @csrf
            <div class="grid grid-cols-1 gap-6">
                
                {{-- Pilih Kategori Penerima --}}
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-700">Pilih Kategori Penerima</label>
                    <div class="flex gap-2 p-1 bg-gray-100 rounded-lg w-fit">
                        <button type="button" class="px-6 py-1.5 rounded-md text-xs font-bold bg-white shadow-sm text-gray-700">Petani</button>
                        <button type="button" class="px-6 py-1.5 rounded-md text-xs font-bold text-gray-400 hover:text-gray-600 transition">Admin</button>
                    </div>
                </div>

                {{-- Nama Penerima --}}
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-700">Nama Penerima</label>
                    <div class="relative">
                        <select class="w-full p-3 border border-gray-200 rounded-xl text-xs text-gray-500 outline-none appearance-none focus:ring-1 focus:ring-green-700">
                            <option selected disabled>Pilih nama petani...</option>
                            <option>Bayu Winandar</option>
                            <option>Ahmad Subardjo</option>
                        </select>
                        <iconify-icon icon="mdi:chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg pointer-events-none"></iconify-icon>
                    </div>
                </div>

                {{-- Pesan Pengingat --}}
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-700">Pesan Pengingat</label>
                    <textarea 
                        rows="6" 
                        placeholder="Tulis pesan pengingat di sini (contoh: Jadwal pemupukan besok pagi jam 08:00)..."
                        class="w-full p-4 border border-gray-200 rounded-xl text-xs outline-none focus:ring-1 focus:ring-green-700 bg-gray-50/30"
                    ></textarea>
                </div>

                <div class="grid grid-cols-2 gap-8">
                    {{-- Tanggal Pengiriman --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-700">Tanggal Pengiriman</label>
                        <input type="date" class="w-full p-3 border border-gray-200 rounded-xl text-xs text-gray-500 outline-none focus:ring-1 focus:ring-green-700 uppercase">
                    </div>

                    {{-- Prioritas --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-700">Prioritas</label>
                        <div class="flex gap-3">
                            <label class="flex items-center gap-2 cursor-pointer bg-green-100 px-4 py-1.5 rounded-full border border-green-200">
                                <input type="radio" name="priority" value="normal" class="hidden" checked>
                                <span class="text-[10px] font-bold text-green-600 uppercase">Normal</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer bg-gray-50 px-4 py-1.5 rounded-full border border-gray-200 hover:bg-red-50 transition group">
                                <input type="radio" name="priority" value="urgent" class="hidden">
                                <span class="text-[10px] font-bold text-gray-400 group-hover:text-red-400 uppercase transition">Urgent</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Tombol Simpan --}}
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="bg-[#214122] text-white px-8 py-3 rounded-xl font-bold flex items-center gap-2 hover:bg-green-900 transition shadow-lg text-sm">
                        <iconify-icon icon="mdi:content-save-outline" class="text-lg"></iconify-icon>
                        Simpan Pengingat
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection