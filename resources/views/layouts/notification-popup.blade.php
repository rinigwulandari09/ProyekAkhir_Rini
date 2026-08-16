<div class="flex flex-col h-full bg-white rounded-2xl">

    {{-- Header Notifikasi --}}
    <div class="flex justify-between items-center px-4 pt-4 pb-2">
        <div>
            <h3 class="font-bold text-sm text-[#214122]">Notifikasi</h3>
            <p class="text-[10px] sm:text-[11px] text-gray-500 mt-0.5" id="notifSummary">Memuat ringkasan...</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="clickMarkAllAsRead()" class="text-[10px] font-semibold text-green-600 hover:text-green-800 bg-green-50 hover:bg-green-100 px-2 py-1.5 rounded transition-colors">
                Tandai dibaca
            </button>
            <button id="btnCloseNotif" class="text-gray-400 hover:text-gray-600 p-1 md:hidden">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex items-center gap-4 px-4 border-b border-gray-100">
        <button onclick="setNotifFilter('all')" id="tabNotifAll" class="text-xs font-semibold text-[#214122] border-b-2 border-[#214122] pb-2 transition-colors">Semua</button>
        <button onclick="setNotifFilter('unread')" id="tabNotifUnread" class="text-xs font-semibold text-gray-500 hover:text-gray-700 border-b-2 border-transparent pb-2 transition-colors">Belum Dibaca</button>
    </div>

    {{-- Kontainer Daftar Notifikasi --}}
    <div id="notifContainer" class="max-h-[60vh] sm:max-h-72 overflow-y-auto custom-scrollbar bg-white">
        <div class="text-center text-sm text-gray-400 py-8 flex flex-col items-center justify-center space-y-3">
            <svg class="w-6 h-6 text-gray-300 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="animate-pulse text-xs">Memuat notifikasi...</span>
        </div>
    </div>

    {{-- Tombol Bawah --}}
    <div class="p-3 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
        <a href="{{ route('notifikasi.index') }}"
           class="block w-full text-center text-xs font-semibold text-[#214122] hover:text-green-800 hover:underline transition-colors py-1">
            Lihat Semua Notifikasi
        </a>
    </div>

</div>

<style>
/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #e5e7eb;
    border-radius: 10px;
}
.custom-scrollbar:hover::-webkit-scrollbar-thumb {
    background-color: #d1d5db;
}
</style>

<script>
    let currentNotifFilter = 'all';

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
                if (!popupNotif.contains(event.target) && event.target !== btnNotif && !btnNotif.contains(event.target)) {
                    popupNotif.classList.add('hidden');
                }
            });
        }

        loadNotifCount();
        setInterval(loadNotifCount, 30000); // Cek berkala setiap 30 detik
    });

    function setNotifFilter(filter) {
        currentNotifFilter = filter;
        
        const tabAll = document.getElementById('tabNotifAll');
        const tabUnread = document.getElementById('tabNotifUnread');
        
        if (filter === 'all') {
            tabAll.className = "text-xs font-semibold text-[#214122] border-b-2 border-[#214122] pb-2 transition-colors";
            tabUnread.className = "text-xs font-semibold text-gray-500 hover:text-gray-700 border-b-2 border-transparent pb-2 transition-colors";
        } else {
            tabUnread.className = "text-xs font-semibold text-[#214122] border-b-2 border-[#214122] pb-2 transition-colors";
            tabAll.className = "text-xs font-semibold text-gray-500 hover:text-gray-700 border-b-2 border-transparent pb-2 transition-colors";
        }
        
        const container = document.getElementById('notifContainer');
        if (container) {
            container.innerHTML = `
                <div class="text-center text-sm text-gray-400 py-8 flex flex-col items-center justify-center space-y-3">
                    <svg class="w-6 h-6 text-gray-300 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span class="animate-pulse text-xs">Memuat notifikasi...</span>
                </div>
            `;
        }

        loadNotifList();
    }

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
                    parts.push(`Ada ${produksi} petani mengisi produksi hari ini.`);
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
        fetch(`/notifikasi/popup?filter=${currentNotifFilter}`)
            .then(res => res.json())
            .then(response => {
                const container = document.getElementById('notifContainer');
                if (!container) return;

                let html = '';
                const items = response?.data ?? [];

                if (items.length === 0) {
                    html = `
                        <div class="flex flex-col items-center justify-center py-8 opacity-60">
                            <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <span class="text-xs font-medium text-gray-500">Belum ada notifikasi</span>
                        </div>
                    `;
                } else {
                    items.forEach(notif => {
                        const title = notif.judul || 'Notifikasi Baru';
                        const message = notif.pesan || '-';
                        const time = notif.created_at ? notif.created_at.substring(0, 10) : '';
                        const isRead = notif.is_read;

                        html += `
                            <div onclick="clickMarkAsRead('${notif.notif_id}', this)" 
                                 class="notif-item relative p-3 border-b border-gray-100 last:border-b-0 text-left cursor-pointer transition-colors flex gap-3 ${isRead ? 'bg-white hover:bg-gray-50' : 'bg-blue-50/30 hover:bg-blue-50/50'}">
                                ${!isRead ? '<span class="absolute top-3 right-3 w-1.5 h-1.5 rounded-full bg-blue-500"></span>' : ''}
                                <div class="shrink-0 mt-0.5">
                                    <div class="${isRead ? 'bg-gray-100 text-gray-400' : 'bg-blue-100 text-blue-500'} p-1.5 rounded-full">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 pr-3">
                                    <div class="font-semibold text-xs ${isRead ? 'text-gray-600' : 'text-gray-800'} mb-0.5">${title}</div>
                                    <div class="text-[10px] text-gray-500 leading-snug line-clamp-2">${message}</div>
                                    <div class="text-[9px] text-gray-400 mt-1 flex items-center gap-1 font-medium">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        ${time}
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }
                container.innerHTML = html;
            })
            .catch(error => console.error('Notif list error:', error));
    }

    function clickMarkAsRead(notifId, element) {
        if (element.classList.contains('bg-white') && !element.querySelector('span.bg-blue-500')) return;

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
                element.classList.remove('bg-blue-50/30', 'hover:bg-blue-50/50');
                element.classList.add('bg-white', 'hover:bg-gray-50');
                
                const title = element.querySelector('.font-semibold');
                if(title) {
                    title.classList.remove('text-gray-800');
                    title.classList.add('text-gray-600');
                }
                
                const iconBg = element.querySelector('.shrink-0 > div');
                if(iconBg) {
                    iconBg.classList.remove('bg-blue-100', 'text-blue-500');
                    iconBg.classList.add('bg-gray-100', 'text-gray-400');
                }

                const dot = element.querySelector('span.bg-blue-500');
                if(dot) dot.remove();
                
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
                const activeCards = document.querySelectorAll('.notif-item');
                activeCards.forEach(card => {
                    if(card.classList.contains('bg-blue-50/30')) {
                        card.classList.remove('bg-blue-50/30', 'hover:bg-blue-50/50');
                        card.classList.add('bg-white', 'hover:bg-gray-50');
                        
                        const title = card.querySelector('.font-semibold');
                        if(title) {
                            title.classList.remove('text-gray-800');
                            title.classList.add('text-gray-600');
                        }
                        
                        const iconBg = card.querySelector('.shrink-0 > div');
                        if(iconBg) {
                            iconBg.classList.remove('bg-blue-100', 'text-blue-500');
                            iconBg.classList.add('bg-gray-100', 'text-gray-400');
                        }

                        const dot = card.querySelector('span.bg-blue-500');
                        if(dot) dot.remove();
                    }
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