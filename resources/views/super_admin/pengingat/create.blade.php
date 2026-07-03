@extends('layouts.dashboard')

@section('title', 'Tambah Pengingat')

@section('content')
<div class="p-2 max-w-5xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#214122]">Tambah Pengingat</h1>
        <p class="text-xs text-gray-500 mt-1">Kirim pesan pengingat otomatis ke petani atau administrator lainnya.</p>
    </div>

    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 12px; margin-bottom: 20px; border-radius: 4px; font-family: Arial, sans-serif;">
            <strong>Berhasil!</strong> {{ session('success') }}
        </div>
    @endif

    @if(session('warning'))
        <div style="background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; padding: 12px; margin-bottom: 20px; border-radius: 4px; font-family: Arial, sans-serif;">
            <strong>Peringatan!</strong> {{ session('warning') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 12px; margin-bottom: 20px; border-radius: 4px; font-family: Arial, sans-serif;">
            <strong>Gagal!</strong> {{ session('error') }}
        </div>
    @endif

    {{-- Form Container --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
        {{-- Header Form --}}
        <div class="bg-[#214122] p-4 flex items-center gap-3">
            <x-heroicon-o-megaphone class="text-white w-5 h-5" />
            <h2 class="text-white font-bold text-sm">Tambah Pengingat</h2>
        </div>

        <form action="{{ route('pengingat.send') }}" method="POST" class="p-8">
            @csrf
            <div class="grid grid-cols-1 gap-6">
                
                {{-- Pilih Kategori Penerima --}}
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-700">Pilih Kategori Penerima</label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="recipient_category" value="petani" checked id="catPetani">
                            <span class="text-xs font-medium">Petani</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="recipient_category" value="admin" id="catAdmin">
                            <span class="text-xs font-medium">Admin</span>
                        </label>
                        <select name="recipient_scope" id="recipientScope" class="ml-4 px-3 py-2 border border-gray-200 rounded text-xs">
                            <option value="single">Kirim ke satu orang</option>
                            <option value="all">Kirim ke semua</option>
                        </select>
                    </div>
                </div>

                {{-- Nama Penerima --}}
                <div class="space-y-2" id="recipientSelectWrap">
                    <label class="block text-xs font-bold text-gray-700">Nama Penerima</label>
                    <div class="relative">
                        <select name="recipient_id" id="recipientSelect" class="w-full p-3 border border-gray-200 rounded-xl text-xs text-gray-500 outline-none appearance-none focus:ring-1 focus:ring-green-700 bg-white">
                            <option value="" disabled selected>Pilih penerima...</option>
                            @foreach($petanis as $p)
                                <option data-category="petani" value="{{ $p->petani_id }}">{{ $p->petani_nama }} (Petani)</option>
                            @endforeach
                            @foreach($admins as $a)
                                <option data-category="admin" value="{{ $a->user_id }}">{{ $a->user_nama }} (Admin)</option>
                            @endforeach
                        </select>
                        <x-heroicon-o-chevron-down class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" />
                    </div>
                </div>

                {{-- Pesan Pengingat --}}
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-700">Pesan Pengingat</label>
                    <textarea name="message" rows="6" placeholder="Tulis pesan pengingat di sini (contoh: Jadwal pemupukan besok pagi jam 08:00)..." class="w-full p-4 border border-gray-200 rounded-xl text-xs outline-none focus:ring-1 focus:ring-green-700 bg-gray-50/30" required></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Tanggal Pengiriman --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-700">Tanggal Pelaksanaan / Deadline</label>
                            <input type="date" name="deadline" class="w-full p-3 border border-gray-200 rounded-xl text-xs text-gray-500 outline-none focus:ring-1 focus:ring-green-700 uppercase">
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
                        <x-heroicon-o-cloud-arrow-up class="w-5 h-5" />
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