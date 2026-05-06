<div class="p-4">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-bold text-[#214122]">Notifikasi</h3>
        <button id="btnCloseNotif" class="text-gray-400 hover:text-gray-600 transition">
            <x-heroicon-o-x-mark class="w-6 h-6" />
        </button>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-4 border-b border-gray-100 mb-4 text-sm">
        <button class="pb-2 border-b-2 border-green-600 font-bold text-green-700">Semua</button>
        <button class="pb-2 text-gray-400 hover:text-gray-600 transition">Belum Dibaca</button>
    </div>

    {{-- List Notifikasi --}}
    <div class="max-h-87.5 overflow-y-auto space-y-1">
        {{-- Item 1 --}}
        <div class="flex items-start gap-3 p-3 bg-green-50 rounded-xl border border-green-50">
            <div class="w-10 h-10 rounded-lg bg-[#214122] flex items-center justify-center shrink-0">
                {{-- Menggunakan Truck sebagai pengganti Tractor karena Heroicons tidak punya ikon traktor --}}
                <x-heroicon-o-truck class="w-6 h-6 text-white" />
            </div>
            <div class="flex-1">
                <p class="text-xs font-bold text-gray-800">15 Petani telah mengisi data produksi hari ini</p>
                <p class="text-[10px] text-gray-400">Laporan harian otomatis dihasilkan sistem.</p>
            </div>
        </div>

        {{-- Item 2 --}}
        <div class="flex items-start gap-3 p-3 hover:bg-gray-50 rounded-xl transition cursor-pointer">
            <div class="relative shrink-0">
                <img src="https://ui-avatars.com/api/?name=Ahmad+Subardjo" class="w-10 h-10 rounded-full">
                <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
            </div>
            <div class="flex-1">
                <div class="flex justify-between">
                    <p class="text-xs font-bold text-gray-800">Ahmad Subardjo mengisi data</p>
                    <span class="text-[9px] text-gray-400">2m lalu</span>
                </div>
                <p class="text-[10px] text-gray-500">Hasil panen: 2.5 Ton Kelapa Sawit (TBS)</p>
            </div>
        </div>
    </div>

    {{-- Tombol Bawah --}}
    <div class="mt-4 space-y-2">
        <button class="w-full py-2 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-50 transition flex items-center justify-center gap-2">
            <x-heroicon-o-check-badge class="w-4 h-4" />
            Tandai semua telah dibaca
        </button>
        
        <a href="{{ route('notifikasi.index') }}" 
        class="w-full py-2.5 bg-[#214122] text-white rounded-lg text-xs font-bold shadow-md hover:bg-[#3D5A3E] transition inline-block text-center">
            Lihat Semua Notifikasi
        </a>
    </div>
</div>

<script>
    document.getElementById('btnCloseNotif').addEventListener('click', function() {
        const popup = document.getElementById('popupNotif');
        if(popup) popup.classList.add('hidden');
    });
</script>