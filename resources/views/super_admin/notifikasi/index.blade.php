@extends('layouts.dashboard')

@section('title', 'Semua Notifikasi')

@section('content')

<div class="p-2">
    {{-- Header Section --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#214122]">Semua Notifikasi</h1>
            <p class="text-xs text-green-600 font-medium flex items-center gap-1">
                <span class="w-2 h-2 bg-green-500 rounded-full"></span> 
                <span id="detailUnreadCount">{{ $unreadCount }}</span> Notifikasi Belum Terbaca
            </p>
        </div>
        <button onclick="markAllNotificationsAsRead()" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 hover:bg-gray-50 transition shadow-sm">
            <x-heroicon-o-check-badge class="w-5 h-5 text-green-600" />
            Tandai Semua Terbaca
        </button>
    </div>

    {{-- Filter & Search --}}
    <form method="GET" action="{{ route('notifikasi.index') }}" class="bg-white p-4 rounded-t-2xl border border-gray-200 border-b-0 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div class="flex gap-2 p-1 bg-gray-100 rounded-xl">
            <button type="submit" name="tab" value="all" class="px-6 py-1.5 rounded-lg text-xs font-bold {{ $tab === 'all' ? 'bg-white shadow-sm text-[#214122]' : 'text-gray-400 hover:text-gray-600' }}">Semua</button>
            <button type="submit" name="tab" value="unread" class="px-6 py-1.5 rounded-lg text-xs font-bold {{ $tab === 'unread' ? 'bg-white shadow-sm text-[#214122]' : 'text-gray-400 hover:text-gray-600' }}">Belum Dibaca</button>
        </div>

        <div class="relative ml-auto w-full md:w-auto">
            <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
            <input type="text" name="search" value="{{ old('search', $search ?? '') }}" placeholder="Cari notifikasi..." class="pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-xs outline-none w-full md:w-64 focus:ring-1 focus:ring-green-700">
        </div>
    </form>

    {{-- Notification List Container --}}
    <div class="bg-white rounded-b-2xl shadow-sm p-6 border border-gray-200 space-y-4">
        
        @forelse($notifs as $n)
        <div id="notif-row-{{ $n->notif_id }}" 
             onclick="readSingleNotification('{{ $n->notif_id }}')"
             class="flex items-center justify-between gap-4 p-4 border rounded-2xl cursor-pointer transition group {{ $n->is_read ? 'bg-white border-gray-100 opacity-60' : 'bg-green-50/40 border-green-100 hover:bg-green-50' }}">
            
            <div class="flex items-center gap-4 flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center border shrink-0 transition {{ $n->is_read ? 'bg-gray-50 text-gray-400' : 'bg-blue-50 text-blue-400 border-blue-100 group-hover:bg-blue-100' }}">
                    <x-heroicon-o-bell-alert class="w-5 h-5" />
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between gap-4">
                        <p class="text-xs text-gray-700 leading-normal">
                            <span class="font-bold text-gray-900">{{ $n->nama ?? 'Sistem' }}</span>
                            <span class="text-gray-700">{{ $n->pesan }}</span>
                        </p>
                        <span class="text-[10px] text-gray-400 whitespace-nowrap">{{ $n->waktu }}</span>
                    </div>
                    @if($n->tipe === 'produksi')
                        <p class="text-xs text-green-700 font-bold mt-1">{{ $n->hasil }}</p>
                    @endif
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mt-1 text-[10px] text-gray-400">
                        <span class="flex items-center gap-1">
                            <x-heroicon-o-map-pin class="w-3 h-3" /> {{ $n->lokasi ?? '-' }}
                        </span>
                        <span class="flex items-center gap-1">
                            <x-heroicon-o-document-text class="w-3 h-3" /> {{ $n->judul }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="unread-dot w-2.5 h-2.5 bg-green-500 rounded-full shrink-0 {{ $n->is_read ? 'hidden' : '' }}"></div>
        </div>
        @empty
        <div class="p-6 text-center text-gray-500 text-sm">
            Tidak ada notifikasi untuk filter ini.
        </div>
        @endforelse

        {{-- Footer/Pagination --}}
        <div class="mt-8 pt-4 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-3">
            <p class="text-[10px] text-gray-400">
                Menampilkan {{ $notifs->count() }} dari {{ $total ?? 0 }} notifikasi
            </p>
            <div class="flex items-center gap-2">
                @php
                    $lastPage = (int) ceil(($total ?? 0) / $perPage);
                    $prevPage = max(1, $page - 1);
                    $nextPage = min($lastPage, $page + 1);
                @endphp

                <a href="{{ route('notifikasi.index', array_merge(request()->except('page'), ['page' => $prevPage])) }}" class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:bg-gray-50 transition {{ $page <= 1 ? 'opacity-50 pointer-events-none' : '' }}">
                    <x-heroicon-o-chevron-left class="w-4 h-4" />
                </a>
                <span class="text-[10px] text-gray-500 font-semibold">Halaman {{ $page }} dari {{ max($lastPage, 1) }}</span>
                <a href="{{ route('notifikasi.index', array_merge(request()->except('page'), ['page' => $nextPage])) }}" class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 hover:bg-gray-50 transition {{ $page >= $lastPage ? 'opacity-50 pointer-events-none' : '' }}">
                    <x-heroicon-o-chevron-right class="w-4 h-4" />
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function readSingleNotification(notifId) {
        const row = document.getElementById(`notif-row-${notifId}`);
        if (row.classList.contains('opacity-60')) return;

        fetch(`/notifikasi/mark-as-read/${notifId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Jika yang diklik adalah produksi/profil, karena sistemnya mengandalkan waktu user, 
                // maka semua produksi sebelum detik ini ikut terbaca. Supaya tampilan sinkron, kita reload saja.
                if(!notifId.startsWith('custom_')) {
                    location.reload();
                    return;
                }

                // Jika hanya custom notif, update baris ini saja secara manual tanpa reload
                row.classList.remove('bg-green-50/40', 'border-green-100', 'hover:bg-green-50');
                row.classList.add('bg-white', 'border-gray-100', 'opacity-60');
                row.querySelector('.unread-dot')?.classList.add('hidden');
                
                decrementBadgeCounts();
            }
        }).catch(err => console.error(err));
    }

    function markAllNotificationsAsRead() {
        fetch(`/notifikasi/mark-all-as-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Reload halaman agar semua data terbaru terikat dengan filter waktu user_role yang baru
                location.reload();
            }
        }).catch(err => console.error(err));
    }

    function decrementBadgeCounts() {
        const textDetail = document.getElementById('detailUnreadCount');
        if (textDetail) {
            let current = parseInt(textDetail.innerText) || 0;
            if (current > 0) textDetail.innerText = current - 1;
        }
        
        const notifBadge = document.getElementById('notifBadge');
        if (notifBadge) {
            let currentBadge = parseInt(notifBadge.innerText) || 0;
            if (currentBadge > 1) {
                notifBadge.innerText = currentBadge - 1;
            } else {
                notifBadge.classList.add('hidden');
            }
        }
    }
</script>
@endsection