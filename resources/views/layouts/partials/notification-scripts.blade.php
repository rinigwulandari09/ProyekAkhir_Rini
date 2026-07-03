{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnNotif = document.getElementById('btnNotif');
        const popupNotif = document.getElementById('popupNotif');
        const notifBadge = document.getElementById('notifBadge');

        if (!btnNotif || !popupNotif || !notifBadge) {
            return;
        }

        btnNotif.addEventListener('click', function(event) {
            event.stopPropagation();
            popupNotif.classList.toggle('hidden');
            loadNotifCount();
            loadNotifList();
        });

        document.addEventListener('click', function(event) {
            if (!popupNotif.contains(event.target) && event.target !== btnNotif) {
                popupNotif.classList.add('hidden');
            }
        });

        loadNotifCount();
        setInterval(loadNotifCount, 10000);
    });

    function loadNotifCount() {
        fetch('/notifikasi/count')
            .then(res => res.json())
            .then(data => {
                if (!data || typeof data.count === 'undefined') {
                    return;
                }

                if (data.count > 0) {
                    notifBadge.classList.remove('hidden');
                    notifBadge.innerText = data.count;
                } else {
                    notifBadge.classList.add('hidden');
                }
            })
            .catch(error => console.error('Notif count error:', error));
    }

    function loadNotifList() {
        fetch('/notifikasi/popup')
            .then(res => res.json())
            .then(response => {
                const container = document.getElementById('notifContainer');
                if (!container) {
                    return;
                }

                let html = '';
                const items = response?.data ?? [];

                if (items.length === 0) {
                    html = `
                        <div class="text-center py-5 text-xs text-gray-400">
                            Tidak ada notifikasi
                        </div>
                    `;
                } else {
                    items.forEach(notif => {
                        const title = notif.judul || notif.title || notif.message || 'Notifikasi Baru';
                        const message = notif.pesan || notif.message || notif.description || 'Detail notifikasi tidak tersedia';
                        const time = notif.created_at || notif.tanggal || '';

                        html += `
                            <div class="mb-2 p-3 rounded-lg border ${notif.is_read ? 'bg-white' : 'bg-green-50 border-green-200'}">
                                <div class="flex justify-between">
                                    <div class="font-bold text-xs">${title}</div>
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
</script> --}}
