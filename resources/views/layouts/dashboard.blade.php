<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title class="font-poppins-bold">@yield('title') - NOTASAWIT</title>
    
    @vite('resources/css/app.css')
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body class="bg-[#E5E7EB] font-sans h-screen overflow-hidden">

    <div class="flex h-full">
        <aside class="w-64 bg-[#234323] text-white flex flex-col shrink-0 h-full">
            <div class="p-6 flex items-center gap-3 text-xl font-bold border-b border-green-800">
                <div class="w-12 h-12 bg-white p-1 rounded-full flex items-center justify-center overflow-hidden shrink-0">
                    <img
                        src="{{ asset('foto/logo.png') }}"
                        alt="Sawit"
                        class="w-full h-full object-contain"
                    />
                </div>
                <span class="tracking-wider">NOTASAWIT</span>
            </div>

            <nav class="flex-1 p-4 space-y-2 mt-4 text-sm overflow-y-auto">
                <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 {{ request()->is('dashboard') ? 'bg-[#3D5A3E]' : '' }} p-3 rounded-lg hover:bg-[#3D5A3E] transition">
                    <x-heroicon-o-home class="w-5 h-5 font-poppins" /> Beranda
                </a>
                <a href="{{ route('user.index') }}" class="flex items-center gap-3 {{ request()->routeIs('user.*') ? 'bg-[#3D5A3E]' : '' }} p-3 rounded-lg hover:bg-[#3D5A3E] transition">
                    <x-heroicon-o-users class="w-5 h-5" /> Data User
                </a>
                <a href="{{ route('petani.index') }}" class="flex items-center gap-3 {{ request()->routeIs('petani.*') ? 'bg-[#3D5A3E]' : '' }} p-3 rounded-lg hover:bg-[#3D5A3E] transition">
                    <x-heroicon-o-user-group class="w-5 h-5 font-poppins" /> Data Petani
                </a>
                <a href="{{ route('lahan.index') }}" class="flex items-center gap-3 {{ request()->routeIs('lahan.*') ? 'bg-[#3D5A3E]' : '' }} p-3 rounded-lg hover:bg-[#3D5A3E] transition">
                    <x-heroicon-o-map class="w-5 h-5 font-poppins" /> Data Lahan
                </a>
                <a href="{{ route('keuangan.index') }}" class="flex items-center gap-3 {{ request()->routeIs('keuangan.*') ? 'bg-[#3D5A3E]' : '' }} p-3 rounded-lg hover:bg-[#3D5A3E] transition">
                    <x-heroicon-o-banknotes class="w-5 h-5 font-poppins" /> Data Keuangan
                </a>
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 p-3 rounded-lg w-full text-left hover:bg-[#3D5A3E] transition">
                        <x-heroicon-o-arrow-left-start-on-rectangle class="w-5 h-5" /> 
                        <span>Keluar</span>
                    </button>
                </form>
            </nav>

            <div class="m-4 p-4 bg-white rounded-lg text-[10px] border border-white/20 shrink-0">
                <div class="flex items-center gap-2 mb-2 font-bold uppercase text-black">
                    <x-heroicon-o-information-circle class="w-4 h-4" /> <h1 class="font-poppins">PENGINGAT</h1>
                </div>
                <p class="mb-3 leading-tight text-black">Tambahkan pengingat atau informasi kepada admin atau petani!</p>
                <a href="{{ route('pengingat.create') }}" 
                class="w-full bg-[#234323] py-2 rounded font-bold text-white hover:bg-[#3D5A3E] transition inline-block text-center">
                    TAMBAH
                </a>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white p-4 shadow-sm flex justify-between items-center px-8 z-20 shrink-0">
                <h2 class="font-poppins-bold text-gray-700">Dashboard Super Admin</h2>
                
                <div class="flex items-center gap-4">
                    <div class="relative inline-block">
                        <button id="btnNotif" class="relative p-2 text-blue-400 bg-blue-50 rounded-full hover:bg-blue-100 transition">
                            <x-heroicon-o-bell class="w-6 h-6" />
                            <span class="absolute top-1 right-1 bg-blue-500 text-white text-[8px] font-bold px-1 rounded-full border-2 border-white">21</span>
                        </button>

                        <div id="popupNotif" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
                            @include('layouts.notification-popup')
                        </div>
                    </div>

                    <div class="flex items-center gap-2 border-l pl-4 relative" x-data="{ open: false }">
                        <div class="text-right hidden sm:block">
                            <div class="text-right hidden sm:block">
                            <p class="text-xs font-poppins-semibold text-gray-800 uppercase leading-none">
                                Hi, {{ Auth::user()->user_nama }} </p>
                            
                            <p class="text-[9px] text-gray-500 font-poppins-bold uppercase">
                                @if(Auth::user()->user_role == 'super_admin')
                                    SUPER ADMINISTRATOR
                                @elseif(Auth::user()->user_role == 'admin')
                                    ADMINISTRATOR
                                @endif
                            </p>
                        </div>
                    </div>
                        
                        <div class="flex items-center">
                            <img src="{{ asset('foto/sawit.png') }}" class="w-10 h-10 rounded-full border-2 border-gray-200 object-cover" alt="User Profile">
                        </div>
                    </div>
                </div>
            </header>

            <main class="p-6 overflow-y-auto flex-1 bg-[#F3F4F6]">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        const btnNotif = document.getElementById('btnNotif');
        const popupNotif = document.getElementById('popupNotif');

        btnNotif.addEventListener('click', function(event) {
            event.stopPropagation();
            popupNotif.classList.toggle('hidden');
        });

        document.addEventListener('click', function(event) {
            if (!popupNotif.contains(event.target) && event.target !== btnNotif) {
                popupNotif.classList.add('hidden');
            }
        });
    </script>
</body>
</html>