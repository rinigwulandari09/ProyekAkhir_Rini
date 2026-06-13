<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - NOTASAWIT</title>
    
    @vite('resources/css/app.css')
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-[#E5E7EB] font-sans h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    <div class="flex h-full w-full relative">
        
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             class="fixed inset-0 bg-black/50 z-40 md:hidden transition-opacity"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>

        <aside class="bg-[#234323] text-white flex flex-col shrink-0 h-full fixed md:static z-50 transition-all duration-300"
               :class="sidebarOpen ? 'w-64 translate-x-0' : '-translate-x-full md:translate-x-0 w-64 md:w-20'">
            
            <div class="p-4 flex items-center border-b border-green-800 h-16 shrink-0" :class="sidebarOpen ? 'gap-3 px-6' : 'justify-start md:justify-center px-6 md:px-0'">
                <div class="w-10 h-10 bg-white p-1 rounded-full flex items-center justify-center overflow-hidden shrink-0">
                    <img src="{{ asset('foto/logo.png') }}" alt="Sawit" class="w-full h-full object-contain" />
                </div>
                <span class="tracking-wider text-lg font-bold transition-opacity duration-200 md:hidden" :class="sidebarOpen && '!block'">NOTASAWIT</span>
            </div>

            <nav class="flex-1 p-3 space-y-1 mt-4 text-sm overflow-y-auto">
                <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 {{ request()->is('dashboard') ? 'bg-[#3D5A3E]' : '' }} p-3 rounded-lg hover:bg-[#3D5A3E] transition" :class="!sidebarOpen && 'md:justify-center'" title="Beranda">
                    <x-heroicon-o-home class="w-5 h-5 shrink-0" /> 
                    <span class="md:hidden" :class="sidebarOpen && '!inline'">Beranda</span>
                </a>
                <a href="{{ route('user.index') }}" class="flex items-center gap-3 {{ request()->routeIs('user.*') ? 'bg-[#3D5A3E]' : '' }} p-3 rounded-lg hover:bg-[#3D5A3E] transition" :class="!sidebarOpen && 'md:justify-center'" title="Data User">
                    <x-heroicon-o-users class="w-5 h-5 shrink-0" /> 
                    <span class="md:hidden" :class="sidebarOpen && '!inline'">Data User</span>
                </a>
                <a href="{{ route('petani.index') }}" class="flex items-center gap-3 {{ request()->routeIs('petani.*') ? 'bg-[#3D5A3E]' : '' }} p-3 rounded-lg hover:bg-[#3D5A3E] transition" :class="!sidebarOpen && 'md:justify-center'" title="Data Petani">
                    <x-heroicon-o-user-group class="w-5 h-5 shrink-0" /> 
                    <span class="md:hidden" :class="sidebarOpen && '!inline'">Data Petani</span>
                </a>
                <a href="{{ route('lahan.index') }}" class="flex items-center gap-3 {{ request()->routeIs('lahan.*') ? 'bg-[#3D5A3E]' : '' }} p-3 rounded-lg hover:bg-[#3D5A3E] transition" :class="!sidebarOpen && 'md:justify-center'" title="Data Lahan">
                    <x-heroicon-o-map class="w-5 h-5 shrink-0" /> 
                    <span class="md:hidden" :class="sidebarOpen && '!inline'">Data Lahan</span>
                </a>
                <a href="{{ route('keuangan.index') }}" class="flex items-center gap-3 {{ request()->routeIs('keuangan.*') ? 'bg-[#3D5A3E]' : '' }} p-3 rounded-lg hover:bg-[#3D5A3E] transition" :class="!sidebarOpen && 'md:justify-center'" title="Data Keuangan">
                    <x-heroicon-o-banknotes class="w-5 h-5 shrink-0" /> 
                    <span class="md:hidden" :class="sidebarOpen && '!inline'">Data Keuangan</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 p-3 rounded-lg w-full text-left hover:bg-[#3D5A3E] transition" :class="!sidebarOpen && 'md:justify-center'" title="Keluar">
                        <x-heroicon-o-arrow-left-start-on-rectangle class="w-5 h-5 shrink-0" /> 
                        <span class="md:hidden" :class="sidebarOpen && '!inline'">Keluar</span>
                    </button>
                </form>
            </nav>

            <div class="m-4 p-4 bg-white rounded-xl text-[10px] border border-white/20 shrink-0 transition-all md:hidden" :class="sidebarOpen && '!block'">
                <div class="flex items-center gap-2 mb-2 font-bold uppercase text-black">
                    <x-heroicon-o-information-circle class="w-4 h-4 text-[#234323]" /> 
                    <h1 class="font-poppins">PENGINGAT</h1>
                </div>
                <p class="mb-3 leading-tight text-black">Tambahkan pengingat atau informasi kepada admin atau petani!</p>
                <a href="{{ route('pengingat.create') }}" class="w-full bg-[#234323] py-2 rounded font-bold text-white hover:bg-[#3D5A3E] transition inline-block text-center">
                    TAMBAH
                </a>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            
            <header class="bg-white p-4 shadow-sm flex justify-between items-center px-4 md:px-8 z-30 shrink-0 h-16">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h2 class="font-poppins-bold text-gray-700 text-sm md:text-base whitespace-nowrap">Dashboard Super Admin</h2>
                </div>
                
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="relative inline-block">
                        <button id="btnNotif" class="relative p-2 text-blue-400 bg-blue-50 rounded-full hover:bg-blue-100 transition">
                            <x-heroicon-o-bell class="w-5 h-5 md:w-6 md:h-6" />
                            <span class="absolute top-1 right-1 bg-blue-500 text-white text-[8px] font-bold px-1 rounded-full border-2 border-white">21</span>
                        </button>
                        <div id="popupNotif" class="hidden absolute right-0 mt-3 w-72 md:w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
                            @include('layouts.notification-popup')
                        </div>
                    </div>

                    <div class="flex items-center gap-2 border-l pl-3 md:pl-4">
                        <div class="text-right hidden md:block">
                            <p class="text-xs font-poppins-semibold text-gray-800 uppercase leading-none mb-1">
                                Hi, {{ Auth::user()->user_nama }}
                            </p>
                            <p class="text-[9px] text-gray-500 font-poppins-bold uppercase tracking-wider">
                                @if(Auth::user()->user_role == 'super_admin') SUPER ADMINISTRATOR @else ADMINISTRATOR @endif
                            </p>
                        </div>
                        <div class="flex items-center shrink-0">
                            <img src="{{ asset('foto/sawit.png') }}" class="w-8 h-8 md:w-10 md:h-10 rounded-full border-2 border-gray-200 object-cover" alt="User Profile">
                        </div>
                    </div>
                </div>
            </header>

            <main class="p-4 md:p-6 overflow-y-auto flex-1 bg-[#F3F4F6]">
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