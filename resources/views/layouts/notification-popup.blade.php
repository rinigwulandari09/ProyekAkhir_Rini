<div class="p-4 sm:p-5 flex flex-col h-full">

    {{-- Header Notifikasi --}}
    <div class="flex justify-between items-start sm:items-center border-b border-gray-100 pb-3 mb-3 gap-2">
        <div class="flex-1">
            <h3 class="font-bold text-base sm:text-lg text-[#214122]">Notifikasi</h3>
            <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5" id="notifSummary">Memuat ringkasan...</p>
        </div>
        <div class="flex flex-col sm:flex-row items-end sm:items-center gap-2 sm:gap-3 shrink-0">
            <button onclick="clickMarkAllAsRead()" class="text-[10px] sm:text-[11px] font-semibold text-green-700 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg transition-colors border border-green-100">
                Tandai dibaca
            </button>
            <button id="btnCloseNotif" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 p-1.5 rounded-full transition-colors">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    {{-- Kontainer Daftar Notifikasi --}}
    <div id="notifContainer" class="max-h-[60vh] sm:max-h-80 overflow-y-auto space-y-2.5 py-1 pr-1 custom-scrollbar">
        <div class="text-center text-sm text-gray-400 py-8 flex flex-col items-center justify-center space-y-3">
            <svg class="w-8 h-8 text-gray-300 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="animate-pulse">Memuat notifikasi...</span>
        </div>
    </div>

    {{-- Tombol Bawah --}}
    <div class="mt-4 pt-3 border-t border-gray-100">
        <a href="{{ route('notifikasi.index') }}"
           class="flex items-center justify-center gap-2 w-full bg-[#214122] text-white text-xs sm:text-sm font-semibold py-2.5 sm:py-3 rounded-xl hover:bg-green-900 transition-colors shadow-sm">
            Lihat Semua Notifikasi
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
            </svg>
        </a>
    </div>

</div>

<style>
/* Custom Scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    width: 5px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #e5e7eb;
    border-radius: 20px;
}
.custom-scrollbar:hover::-webkit-scrollbar-thumb {
    background-color: #d1d5db;
}
</style>

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
                    html = `
                        <div class="flex flex-col items-center justify-center py-10 opacity-60">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <span class="text-sm font-medium text-gray-500">Belum ada notifikasi</span>
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
                                 class="notif-item relative p-3.5 sm:p-4 rounded-xl border text-left cursor-pointer transition-all duration-200 group flex gap-3 ${isRead ? 'bg-gray-50/50 border-transparent hover:bg-gray-100/70' : 'bg-white border-green-200 shadow-sm hover:border-green-300'}">
                                ${!isRead ? '<span class="absolute top-4 right-4 w-2 h-2 rounded-full bg-green-500 shadow-sm shadow-green-200"></span>' : ''}
                                <div class="shrink-0 mt-0.5">
                                    <div class="${isRead ? 'bg-gray-200 text-gray-400' : 'bg-green-100 text-green-600'} p-2 rounded-full transition-colors group-hover:scale-105">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 pr-4">
                                    <div class="font-bold text-xs sm:text-sm ${isRead ? 'text-gray-600' : 'text-gray-800 group-hover:text-[#214122]'} transition-colors leading-tight mb-1">${title}</div>
                                    <div class="text-[11px] sm:text-xs text-gray-500 leading-relaxed line-clamp-2">${message}</div>
                                    <div class="text-[10px] sm:text-[11px] text-gray-400 mt-1.5 flex items-center gap-1 font-medium">
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
        if (element.classList.contains('bg-gray-50/50')) return;

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
                element.classList.remove('bg-white', 'border-green-200', 'shadow-sm', 'hover:border-green-300');
                element.classList.add('bg-gray-50/50', 'border-transparent', 'hover:bg-gray-100/70');
                
                const title = element.querySelector('.font-bold');
                if(title) {
                    title.classList.remove('text-gray-800', 'group-hover:text-[#214122]');
                    title.classList.add('text-gray-600');
                }
                
                const iconBg = element.querySelector('.shrink-0 > div');
                if(iconBg) {
                    iconBg.classList.remove('bg-green-100', 'text-green-600');
                    iconBg.classList.add('bg-gray-200', 'text-gray-400');
                }

                const dot = element.querySelector('span.bg-green-500');
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
                    if(card.classList.contains('bg-white')) {
                        card.classList.remove('bg-white', 'border-green-200', 'shadow-sm', 'hover:border-green-300');
                        card.classList.add('bg-gray-50/50', 'border-transparent', 'hover:bg-gray-100/70');
                        
                        const title = card.querySelector('.font-bold');
                        if(title) {
                            title.classList.remove('text-gray-800', 'group-hover:text-[#214122]');
                            title.classList.add('text-gray-600');
                        }
                        
                        const iconBg = card.querySelector('.shrink-0 > div');
                        if(iconBg) {
                            iconBg.classList.remove('bg-green-100', 'text-green-600');
                            iconBg.classList.add('bg-gray-200', 'text-gray-400');
                        }

                        const dot = card.querySelector('span.bg-green-500');
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