<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - NOTASAWIT</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        {{-- SIDEBAR KIRI --}}
        <aside class="w-64 bg-[#214122] text-white flex flex-col flex-shrink-0 shadow-xl">
            {{-- Logo --}}
            <div class="p-6 flex items-center gap-3">
                <div class="bg-white p-1 rounded-lg w-10 h-10 flex items-center justify-center flex-shrink-0">
                    <img src="https://via.placeholder.com/40" class="w-8 h-8 object-contain" alt="Logo">
                </div>
                <span class="text-xl font-extrabold tracking-widest text-white">NOTASAWIT</span>
            </div>
            
            {{-- Navigasi Menu --}}
            <nav class="mt-4 flex-1 px-4 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 bg-white/10 p-3 rounded-xl border border-white/20 transition hover:bg-white/20">
                    <iconify-icon icon="heroicons:home-20-solid" class="text-xl"></iconify-icon>
                    <span class="text-sm font-semibold">Beranda</span>
                </a>

                <a href="{{ route('keuangan.index') }}" class="flex items-center gap-3 p-3 rounded-xl transition hover:bg-white/10 group">
                    <iconify-icon icon="heroicons:banknotes-20-solid" class="text-xl text-white/50 group-hover:text-white"></iconify-icon>
                    <span class="text-sm font-medium opacity-70 group-hover:opacity-100">Data Keuangan</span>
                </a>

                <a href="{{ route('petani.index') }}" class="flex items-center gap-3 p-3 rounded-xl transition hover:bg-white/10 group">
                    <iconify-icon icon="heroicons:user-group-20-solid" class="text-xl text-white/50 group-hover:text-white"></iconify-icon>
                    <span class="text-sm font-medium opacity-70 group-hover:opacity-100">Data Petani</span>
                </a>

                <a href="{{ route('lahan.index') }}" class="flex items-center gap-3 p-3 rounded-xl transition hover:bg-white/10 group">
                    <iconify-icon icon="heroicons:map-20-solid" class="text-xl text-white/50 group-hover:text-white"></iconify-icon>
                    <span class="text-sm font-medium opacity-70 group-hover:opacity-100">Data Lahan</span>
                </a>
            </nav>

            {{-- Widget Pengingat Bawah --}}
            <div class="m-4 p-4 bg-white/10 rounded-2xl border border-white/10">
                <div class="flex items-center gap-2 mb-2 font-bold text-[10px] opacity-80 uppercase tracking-widest">
                    <iconify-icon icon="heroicons:information-circle" class="text-sm"></iconify-icon>
                    PENGINGAT
                </div>
                <p class="text-[10px] text-gray-300 leading-relaxed mb-3">Silahkan tambahkan pengingat atau informasi kepada petani atau admin!</p>
                <a href="{{ route('pengingat.create') }}" class="block w-full text-center bg-[#3D5A3E] py-2 rounded-lg text-[10px] font-bold hover:bg-white hover:text-[#214122] transition uppercase">
                    Tambah
                </a>
            </div>
        </aside>

        {{-- AREA KONTEN KANAN --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            {{-- Header --}}
            <header class="bg-white h-20 flex items-center justify-between px-8 shadow-sm border-b border-gray-100 flex-shrink-0">
                <h2 class="text-xl font-bold text-gray-800">Dashboard Admin</h2>
                
                <div class="flex items-center gap-6">
                    {{-- Ikon Notifikasi (Pop-up yang kita buat sebelumnya) --}}
                    <div class="relative">
                        <button id="btnNotif" class="relative p-2.5 bg-blue-50 rounded-full text-blue-500 hover:bg-blue-100 transition">
                            <iconify-icon icon="heroicons:bell-20-solid" class="text-2xl"></iconify-icon>
                            <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-blue-600 border-2 border-white rounded-full"></span>
                        </button>
                        
                        {{-- Container Pop-up Notif --}}
                        <div id="popupNotif" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
                             @include('layouts.notification-popup')
                        </div>
                    </div>

                    {{-- Profil User --}}
                    <div class="flex items-center gap-3 pl-6 border-l border-gray-100">
                        <div class="text-right">
                            <p class="text-xs font-bold text-gray-900 leading-none">Rini Gustia!</p>
                            <p class="text-[9px] font-bold text-gray-400 mt-1 uppercase tracking-tighter">SUPER ADMINISTRATOR</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=Rini+Gustia&background=214122&color=fff" class="w-10 h-10 rounded-full border-2 border-gray-100 object-cover" alt="Profile">
                    </div>
                </div>
            </header>

            {{-- Main Content --}}
            <main class="flex-1 overflow-y-auto bg-[#F8FAFB] p-8">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- JS untuk Pop-up Notifikasi --}}
    <script>
        const btnNotif = document.getElementById('btnNotif');
        const popupNotif = document.getElementById('popupNotif');

        if(btnNotif) {
            btnNotif.onclick = (e) => {
                e.stopPropagation();
                popupNotif.classList.toggle('hidden');
            };
            window.onclick = (e) => {
                if (!popupNotif.contains(e.target) && e.target !== btnNotif) {
                    popupNotif.classList.add('hidden');
                }
            };
        }
    </script>
</body>
</html>