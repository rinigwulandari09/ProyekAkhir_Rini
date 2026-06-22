<div class="p-4">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-3">
        <h3 class="text-sm font-bold text-[#214122]">Notifikasi</h3>

        <div class="flex gap-2 items-center">

            {{-- MARK ALL READ --}}
            <button
                onclick="markAllNotifRead()"
                class="text-[10px] text-green-600 hover:underline">
                Tandai semua
            </button>

            <button id="btnCloseNotif" class="text-gray-400 hover:text-gray-600">
                <x-heroicon-o-x-mark class="w-5 h-5" />
            </button>
        </div>
    </div>

    {{-- INFO RINGKAS --}}
    <div class="mb-3 bg-green-50 p-2 rounded-lg border border-green-100">
        <p class="text-[10px] text-green-700 font-bold">
            {{ $jumlahProduksiHariIni ?? 0 }} petani input produksi hari ini
        </p>
    </div>

    {{-- LIST NOTIF --}}
    <div class="max-h-80 overflow-y-auto space-y-2">

        @forelse ($notifikasi ?? [] as $notif)
            <div class="flex gap-3 p-3 rounded-xl border
                {{ $notif->is_read ? 'bg-white' : 'bg-green-50 border-green-100' }}">

                {{-- ICON --}}
                <div class="w-9 h-9 rounded-lg bg-[#214122] flex items-center justify-center">
                    <x-heroicon-o-bell class="w-5 h-5 text-white" />
                </div>

                {{-- CONTENT --}}
                <div class="flex-1">

                    <div class="flex justify-between gap-2">
                        <p class="text-xs font-bold text-gray-800">
                            {{ $notif->judul }}
                        </p>

                        <span class="text-[9px] text-gray-400 whitespace-nowrap">
                            {{ optional($notif->created_at)->diffForHumans() }}
                        </span>
                    </div>

                    <p class="text-[10px] text-gray-500 mt-1">
                        {{ $notif->pesan }}
                    </p>

                    {{-- ACTION --}}
                    @if(!$notif->is_read)
                        <button
                            onclick="markNotifRead({{ $notif->id }})"
                            class="text-[10px] text-green-600 mt-1 hover:underline">
                            Tandai dibaca
                        </button>
                    @endif

                </div>
            </div>

        @empty
            <p class="text-center text-gray-400 text-xs py-4">
                Tidak ada notifikasi
            </p>
        @endforelse

    </div>

    {{-- FOOTER --}}
    <div class="mt-3">
        <a href="{{ route('notifikasi.index') }}"
           class="block text-center bg-[#214122] text-white text-xs font-bold py-2 rounded-lg hover:bg-[#3D5A3E]">
            Lihat Semua Notifikasi
        </a>
    </div>

</div>

<script>
    document.getElementById('btnCloseNotif')?.addEventListener('click', function () {
        document.getElementById('popupNotif')?.classList.add('hidden');
    });

    function markNotifRead(id)
    {
        fetch('/notifikasi/read/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {

            if(data.success){
                location.reload();
            }

        })
        .catch(error => {
            console.log(error);
        });
    }

    function markAllNotifRead()
    {
        fetch('/notifikasi/mark-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {

            if(data.success){

                // sembunyikan semua tombol tandai dibaca
                document.querySelectorAll('[id^="notif-"]').forEach(el => {
                    el.remove();
                });

                // badge jadi 0
                const badge = document.getElementById('notifBadge');

                if(badge){
                    badge.classList.add('hidden');
                    badge.innerText = 0;
                }

                // tampilkan pesan kosong
                const container = document.querySelector('.max-h-80.overflow-y-auto.space-y-2');

                if(container){
                    container.innerHTML = `
                        <p class="text-center text-gray-400 text-xs py-4">
                            Tidak ada notifikasi
                        </p>
                    `;
                }
            }

        })
        .catch(error => {
            console.error(error);
        });
    }
</script>
