<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - NOTASAWIT</title>
    @vite('resources/css/app.css')
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
</head>
<body class="bg-[#E5E7EB] font-sans">

    <div class="flex min-h-screen">
        <aside class="w-64 bg-[#214122] text-white flex flex-col fixed h-full">
            <div class="p-6 flex items-center gap-2 text-xl font-bold border-b border-green-800">
                <div class="bg-white p-1 rounded-full text-[#214122] flex">
                    <iconify-icon icon="mdi:palm-tree"></iconify-icon>
                </div>
                NOTASAWIT
            </div>

            <nav class="flex-1 p-4 space-y-2 mt-4 text-sm">
               <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 {{ request()->is('dashboard') ? 'bg-[#3D5A3E]' : '' }} p-3 rounded-lg hover:bg-[#3D5A3E] transition">
                    <iconify-icon icon="mdi:home" class="text-lg"></iconify-icon> Beranda
                </a>
                <a href="{{ route('user.index') }}" class="flex items-center gap-3 {{ request()->routeIs('user.*') ? 'bg-[#3D5A3E]' : '' }} p-3 rounded-lg hover:bg-[#3D5A3E] transition">
                    <iconify-icon icon="mdi:account-group" class="text-lg"></iconify-icon> Data User
                </a>
                <a href="{{ route('petani.index') }}" class="flex items-center gap-3 {{ request()->routeIs('petani.*') ? 'bg-[#3D5A3E]' : '' }} p-3 rounded-lg hover:bg-[#3D5A3E] transition">
                    <iconify-icon icon="mdi:account-hard-hat" class="text-lg"></iconify-icon> Data Petani
                </a>
                <a href="{{ route('lahan.index') }}" class="flex items-center gap-3 {{ request()->routeIs('lahan.*') ? 'bg-[#3D5A3E]' : '' }} p-3 rounded-lg hover:bg-[#3D5A3E] transition">
                    <iconify-icon icon="mdi:map-marker-path" class="text-lg"></iconify-icon> Data Lahan
                </a>
                <a href="{{ route('keuangan.index') }}" class="flex items-center gap-3 {{ request()->routeIs('keuangan.*') ? 'bg-[#3D5A3E]' : '' }} p-3 rounded-lg hover:bg-[#3D5A3E] transition">
                    <iconify-icon icon="mdi:finance" class="text-lg"></iconify-icon> Data Keuangan
                </a>
            </nav>

            <div class="m-4 p-4 bg-white/10 rounded-lg text-[10px] border border-white/20">
                <div class="flex items-center gap-2 mb-2 font-bold uppercase">
                    <iconify-icon icon="mdi:information-outline"></iconify-icon> PENGINGAT
                </div>
                <p class="mb-3 leading-tight text-gray-300">Siapkan laporan mingguan untuk diserahkan kepada admin atau petani!</p>
                
                {{-- Mengubah button menjadi tag <a> --}}
                <a href="{{ route('pengingat.create') }}" 
                class="w-full bg-[#3D5A3E] py-2 rounded font-bold hover:bg-white hover:text-[#214122] transition inline-block text-center">
                    TAMBAH
                </a>
            </div>
        </aside>

        <div class="flex-1 ml-64 flex flex-col">
            <header class="bg-white p-4 shadow-sm flex justify-between items-center px-8">
                <h2 class="font-bold text-gray-700">Dashboard Super Admin</h2>
                <div class="flex items-center gap-4">
                    <div class="relative inline-block">
                        <button id="btnNotif" class="relative p-2 text-blue-400 bg-blue-50 rounded-full hover:bg-blue-100 transition">
                            <iconify-icon icon="mdi:bell-outline" class="text-2xl"></iconify-icon>
                            <span class="absolute top-1 right-1 bg-blue-500 text-white text-[8px] font-bold px-1 rounded-full border-2 border-white">21</span>
                        </button>

                        <div id="popupNotif" class="hidden absolute right-0 mt-3 w-95 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
                            @include('layouts.notification-popup')
                        </div>
                    </div>
                    <div class="flex items-center gap-2 border-l pl-4">
                        <div class="text-right">
                            <p class="text-xs font-bold text-gray-800 uppercase leading-none">Hi, Rini Gustia!</p>
                            <p class="text-[9px] text-gray-500 font-bold">SUPER ADMINISTRATOR</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=Rini+Gustia&background=random" class="w-10 h-10 rounded-full border-2 border-gray-200">
                    </div>
                </div>
            </header>

            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        const btnNotif = document.getElementById('btnNotif');
        const popupNotif = document.getElementById('popupNotif');

        // 1. Fungsi Klik Ikon Lonceng
        btnNotif.addEventListener('click', function(event) {
            event.stopPropagation(); // Mencegah klik tembus ke bawah
            popupNotif.classList.toggle('hidden'); // Munculkan atau sembunyikan
        });

        // 2. Klik di mana saja untuk menutup pop-up
        document.addEventListener('click', function(event) {
            // Jika yang diklik bukan bagian dari pop-up atau tombol, maka tutup
            if (!popupNotif.contains(event.target) && event.target !== btnNotif) {
                popupNotif.classList.add('hidden');
            }
        });
    </script>

</body>
</html>