<div class="p-4">

    <div class="flex justify-between items-center border-b pb-2 mb-2">
        <div>
            <h3 class="font-bold text-sm text-[#214122]">Notifikasi</h3>
            <p class="text-[10px] text-gray-500" id="notifSummary">Memuat ringkasan...</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="clickMarkAllAsRead()" class="text-[10px] font-semibold text-green-700 hover:text-green-900 hover:underline">
                Tandai semua dibaca
            </button>
            <button id="btnCloseNotif" class="text-gray-400 hover:text-gray-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <div id="notifContainer" class="max-h-80 overflow-y-auto space-y-2 py-2">
        <div class="text-center text-xs text-gray-400 py-6">Memuat notifikasi...</div>
    </div>

    <div class="mt-3">
        <a href="{{ route('notifikasi.index') }}"
           class="block text-center bg-[#214122] text-white text-xs font-bold py-2 rounded-lg hover:bg-[#3D5A3E]">
            Lihat Semua Notifikasi
        </a>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnNotif = document.getElementById('btnNotif');
        const popupNotif = document.getElementById('popupNotif');
        const btnCloseNotif = document.getElementById('btnCloseNotif');

        if (btnCloseNotif) {
            btnCloseNotif.addEventListener('click', function () {
                popupNotif?.classList.add('hidden');
            });
        }

        if (btnNotif && popupNotif) {
            btnNotif.addEventListener('click', function (event) {
                event.stopPropagation();
                popupNotif.classList.toggle('hidden');
                if (!popupNotif.classList.contains('hidden')) {
                    loadNotifCount();
                    loadNotifList();
                }
            });

            document.addEventListener('click', function (event) {
                if (!popupNotif.contains(event.target) && event.target !== btnNotif) {
                    popupNotif.classList.add('hidden');
                }
            });
        }

        loadNotifCount();
        setInterval(loadNotifCount, 30000); // Cek berkala setiap 30 detik
    });

    function loadNotifCount() {
        fetch('/notifikasi/count')
            .then(res => res.json())
            .then(data => {
                if (!data || typeof data.count === 'undefined') return;

                const notifSummary = document.getElementById('notifSummary');
                if (notifSummary) {
                    const produksi = typeof data.produksiCount !== 'undefined' ? data.produksiCount : data.count;
                    const custom = typeof data.customCount !== 'undefined' ? data.customCount : 0;
                    let parts = [];
                    parts.push(`Ada ${produksi} petani yang mengisi produksi hari ini`);
                    if (custom > 0) parts.push(`${custom} tugas baru`);
                    notifSummary.innerText = parts.join(' • ');
                }

                const notifBadge = document.getElementById('notifBadge') || document.querySelector('.relative span.bg-blue-500');
                if (notifBadge) {
                    if (data.count > 0) {
                        notifBadge.classList.remove('hidden');
                        notifBadge.innerText = data.count;
                    } else {
                        notifBadge.classList.add('hidden');
                    }
                }
            })
            .catch(error => console.error('Notif count error:', error));
    }

    function loadNotifList() {
        fetch('/notifikasi/popup')
            .then(res => res.json())
            .then(response => {
                const container = document.getElementById('notifContainer');
                if (!container) return;

                let html = '';
                const items = response?.data ?? [];

                if (items.length === 0) {
                    html = `<div class="text-center py-5 text-xs text-gray-400">Tidak ada notifikasi</div>`;
                } else {
                    items.forEach(notif => {
                        const title = notif.judul || 'Notifikasi Baru';
                        const message = notif.pesan || '-';
                        const time = notif.created_at ? notif.created_at.substring(0, 10) : '';
                        const isRead = notif.is_read;

                        html += `
                            <div onclick="clickMarkAsRead('${notif.notif_id}', this)" 
                                 class="notif-item mb-2 p-3 rounded-lg border text-left cursor-pointer transition duration-200 ${isRead ? 'bg-white border-gray-100 opacity-60' : 'bg-green-50 border-green-200 hover:bg-green-100'}">
                                <div class="flex justify-between items-center">
                                    <div class="font-bold text-xs text-gray-800">${title}</div>
                                    <div class="text-[10px] text-gray-400">${time}</div>
                                </div>
                                <div class="text-[11px] text-gray-600 mt-1">${message}</div>
                            </div>
                        `;
                    });
                }
                container.innerHTML = html;
            })
            .catch(error => console.error('Notif list error:', error));
    }

    function clickMarkAsRead(notifId, element) {
        if (element.classList.contains('opacity-60')) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

        fetch(`/notifikasi/read/${notifId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                element.classList.remove('bg-green-50', 'border-green-200', 'hover:bg-green-100');
                element.classList.add('bg-white', 'border-gray-100', 'opacity-60');
                loadNotifCount();
            }
        })
        .catch(err => console.error('Gagal menandai dibaca:', err));
    }

    function clickMarkAllAsRead() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

        fetch('/notifikasi/mark-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Paksa semua card di dalam DOM popup berubah warna ke putih pudar saat itu juga
                const activeCards = document.querySelectorAll('.notif-item');
                activeCards.forEach(card => {
                    card.classList.remove('bg-green-50', 'border-green-200', 'hover:bg-green-100');
                    card.classList.add('bg-white', 'border-gray-100', 'opacity-60');
                });

                const notifBadge = document.getElementById('notifBadge') || document.querySelector('.relative span.bg-blue-500');
                if (notifBadge) {
                    notifBadge.classList.add('hidden');
                    notifBadge.innerText = '0';
                }
                
                loadNotifCount();
            }
        })
        .catch(err => console.error('Gagal menandai semua dibaca:', err));
    }
</script>