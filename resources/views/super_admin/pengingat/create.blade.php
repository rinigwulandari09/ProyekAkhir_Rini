@extends('layouts.dashboard')

@section('title', 'Tambah Pengingat')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-4xl mx-auto w-full">
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-[#214122] mb-2">Tambah Pengingat</h1>
        <p class="text-sm text-gray-500">Kirim pesan pengingat otomatis ke petani atau administrator lainnya.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg shadow-sm">
            <div class="flex items-center">
                <x-heroicon-o-check-circle class="w-5 h-5 text-green-500 mr-2" />
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6 rounded-r-lg shadow-sm">
            <div class="flex items-center">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-yellow-500 mr-2" />
                <p class="text-sm font-medium text-yellow-800">{{ session('warning') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm">
            <div class="flex items-center">
                <x-heroicon-o-x-circle class="w-5 h-5 text-red-500 mr-2" />
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- Form Container --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Header Form --}}
        <div class="bg-[#214122] p-4 sm:p-5 flex items-center gap-3">
            <x-heroicon-o-megaphone class="text-white w-5 h-5 sm:w-6 sm:h-6" />
            <h2 class="text-white font-semibold text-sm sm:text-base tracking-wide">Detail Pengingat Baru</h2>
        </div>

        <form action="{{ route('pengingat.send') }}" method="POST" class="p-5 sm:p-8">
            @csrf
            <div class="grid grid-cols-1 gap-6 sm:gap-8">
                
                {{-- Pilih Kategori Penerima --}}
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-gray-700">Pilih Kategori Penerima</label>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <div class="flex items-center gap-6">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="recipient_category" value="petani" checked id="catPetani" class="w-4 h-4 text-[#214122] focus:ring-[#214122]">
                                <span class="text-sm font-medium text-gray-700 group-hover:text-[#214122] transition-colors">Petani</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="recipient_category" value="admin" id="catAdmin" class="w-4 h-4 text-[#214122] focus:ring-[#214122]">
                                <span class="text-sm font-medium text-gray-700 group-hover:text-[#214122] transition-colors">Admin</span>
                            </label>
                        </div>
                        <div class="w-full sm:w-auto mt-2 sm:mt-0 sm:ml-auto">
                            <select name="recipient_scope" id="recipientScope" class="w-full sm:w-auto px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-[#214122] focus:border-transparent shadow-sm">
                                <option value="single">Kirim ke satu orang</option>
                                <option value="all">Kirim ke semua</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                    {{-- Nama Penerima --}}
                    <div class="space-y-2" id="recipientSelectWrap">
                        <label class="block text-sm font-semibold text-gray-700">Nama Penerima</label>
                        <div class="relative">
                            <select name="recipient_id" id="recipientSelect" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-700 outline-none appearance-none focus:ring-2 focus:ring-[#214122] focus:border-transparent bg-white shadow-sm transition-shadow">
                                <option value="" disabled selected>Pilih penerima...</option>
                                @foreach($petanis as $p)
                                    <option data-category="petani" value="{{ $p->petani_id }}">{{ $p->petani_nama }} (Petani)</option>
                                @endforeach
                                @foreach($admins as $a)
                                    <option data-category="admin" value="{{ $a->user_id }}">{{ $a->user_nama }} (Admin)</option>
                                @endforeach
                            </select>
                            <x-heroicon-o-chevron-down class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" />
                        </div>
                    </div>

                    {{-- Tanggal Pengiriman --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Tanggal Pelaksanaan / Deadline</label>
                        <input type="date" name="deadline" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-700 outline-none focus:ring-2 focus:ring-[#214122] focus:border-transparent shadow-sm transition-shadow bg-white uppercase">
                    </div>
                </div>

                {{-- Judul Pengingat --}}
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">Judul Pengingat / Tugas</label>
                    <input type="text" name="judul" placeholder="Contoh: Jadwal Audit Internal, Persiapan Lahan..." class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-700 outline-none focus:ring-2 focus:ring-[#214122] focus:border-transparent bg-white shadow-sm transition-shadow" required>
                </div>

                {{-- Pesan Pengingat --}}
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">Pesan Pengingat</label>
                    <textarea name="message" rows="5" placeholder="Tulis pesan pengingat di sini (contoh: Jadwal pemupukan besok pagi jam 08:00)..." class="w-full p-4 border border-gray-200 rounded-xl text-sm text-gray-700 outline-none focus:ring-2 focus:ring-[#214122] focus:border-transparent bg-white shadow-sm transition-shadow resize-y" required></textarea>
                </div>



                {{-- Tombol Simpan --}}
                <div class="mt-6 sm:mt-8 pt-6 border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-3">
                    <button type="submit" class="inline-flex justify-center items-center bg-[#214122] text-white px-8 py-3 rounded-xl font-semibold hover:bg-green-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#214122] transition-colors shadow-md w-full sm:w-auto text-sm">
                        <x-heroicon-o-cloud-arrow-up class="w-5 h-5 mr-2 -ml-1" />
                        Kirim Pengingat
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    // simple client logic to toggle recipient select options when category changes
    const petanis = @json($petanis->map(function($p){ return ['id'=>$p->petani_id,'text'=>$p->petani_nama,'category'=>'petani']; }));
    const admins = @json($admins->map(function($a){ return ['id'=>$a->user_id,'text'=>$a->user_nama,'category'=>'admin']; }));

    const recipientSelect = document.getElementById('recipientSelect');
    const recipientScope = document.getElementById('recipientScope');

    function rebuildOptions() {
        const cat = document.querySelector('input[name="recipient_category"]:checked').value;
        const scope = recipientScope.value;
        recipientSelect.innerHTML = '';
        if (scope === 'all') {
            const opt = document.createElement('option');
            opt.value = 'all'; opt.text = cat === 'petani' ? 'Semua Petani' : 'Semua Admin'; opt.selected = true;
            recipientSelect.appendChild(opt);
            recipientSelect.disabled = true;
            return;
        }
        recipientSelect.disabled = false;
        const placeholder = document.createElement('option'); placeholder.value=''; placeholder.disabled=true; placeholder.selected=true; placeholder.text='Pilih penerima...'; recipientSelect.appendChild(placeholder);
        const list = cat === 'petani' ? petanis : admins;
        list.forEach(i => {
            const o = document.createElement('option'); o.value = i.id; o.text = i.text + (cat==='petani' ? ' (Petani)' : ' (Admin)'); recipientSelect.appendChild(o);
        });
    }

    document.querySelectorAll('input[name="recipient_category"]').forEach(r => r.addEventListener('change', rebuildOptions));
    recipientScope.addEventListener('change', rebuildOptions);
    document.addEventListener('DOMContentLoaded', rebuildOptions);
</script>
@endsection