<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SILAUSA</title>
    <link rel="icon" href="{{ asset('foto/logo.png') }}" type="image/png">
    
    @vite('resources/css/app.css')
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-[#E5E7EB] font-sans h-screen overflow-hidden" 
      x-cloak
      x-data="{ 
        sidebarOpen: localStorage.getItem('sidebarOpen') === 'true' || (window.innerWidth > 768 && localStorage.getItem('sidebarOpen') === null),
        showLogoutConfirm: false,
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
            localStorage.setItem('sidebarOpen', this.sidebarOpen);
        },
        confirmLogout() {
            document.getElementById('logoutForm').submit();
        }
      }">

    <div class="flex h-full w-full relative">
        
        <div x-show="sidebarOpen" 
             @click="toggleSidebar()" 
             class="fixed inset-0 bg-black/50 z-40 md:hidden transition-opacity">
        </div>

        <aside class="bg-[#234323] text-white flex flex-col shrink-0 h-full fixed md:static z-50 transition-all duration-300"
               :class="sidebarOpen ? 'w-64 translate-x-0' : '-translate-x-full md:w-20 md:translate-x-0'">
            
            <div class="p-4 flex items-center border-b border-green-800 h-16 shrink-0" 
                 :class="sidebarOpen ? 'gap-3 px-6' : 'justify-center'">
                <div class="w-10 h-10 bg-white p-1 rounded-full flex items-center justify-center overflow-hidden shrink-0">
                    <img src="{{ asset('foto/logo.png') }}" alt="Sawit" class="w-full h-full object-contain" />
                </div>
                <span x-show="sidebarOpen" class="tracking-wider text-lg font-bold whitespace-nowrap font-poppins">SILAUSA</span>
            </div>

            <nav class="flex-1 p-3 space-y-1 mt-4 text-sm overflow-y-auto">
                <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 {{ request()->is('dashboard') ? 'bg-[#3D5A3E]' : '' }} p-3 rounded-lg hover:bg-[#3D5A3E] transition" :class="!sidebarOpen && 'justify-center'">
                    <x-heroicon-o-home class="w-5 h-5 shrink-0" /> 
                    <span x-show="sidebarOpen">Beranda</span>
                </a>
                <a href="{{ route('petani.index') }}" class="flex items-center gap-3 {{ request()->routeIs('petani.*') ? 'bg-[#3D5A3E]' : '' }} p-3 rounded-lg hover:bg-[#3D5A3E] transition" :class="!sidebarOpen && 'justify-center'">
                    <x-heroicon-o-user-group class="w-5 h-5 shrink-0" /> 
                    <span x-show="sidebarOpen">Data Petani</span>
                </a>
                <a href="{{ route('lahan.index') }}" class="flex items-center gap-3 {{ request()->routeIs('lahan.*') ? 'bg-[#3D5A3E]' : '' }} p-3 rounded-lg hover:bg-[#3D5A3E] transition" :class="!sidebarOpen && 'justify-center'">
                    <x-heroicon-o-map class="w-5 h-5 shrink-0" /> 
                    <span x-show="sidebarOpen">Data Lahan</span>
                </a>
                <a href="{{ route('keuangan.index') }}" class="flex items-center gap-3 {{ request()->routeIs('keuangan.*') ? 'bg-[#3D5A3E]' : '' }} p-3 rounded-lg hover:bg-[#3D5A3E] transition" :class="!sidebarOpen && 'justify-center'">
                    <x-heroicon-o-banknotes class="w-5 h-5 shrink-0" /> 
                    <span x-show="sidebarOpen">Data Keuangan</span>
                </a>
                <a href="{{ route('audit.index') }}" class="flex items-center gap-3 {{ request()->routeIs('audit.*') ? 'bg-[#3D5A3E]' : '' }} p-3 rounded-lg hover:bg-[#3D5A3E] transition" :class="!sidebarOpen && 'justify-center'">
                    <x-heroicon-o-document-text class="w-5 h-5 shrink-0" /> 
                    <span x-show="sidebarOpen">Audit</span>
                </a>
                <button @click="showLogoutConfirm = true" class="flex items-center gap-3 p-3 rounded-lg w-full text-left hover:bg-[#3D5A3E] transition" :class="!sidebarOpen && 'justify-center'">
                    <x-heroicon-o-arrow-left-start-on-rectangle class="w-5 h-5 shrink-0" /> 
                    <span x-show="sidebarOpen">Keluar</span>
                </button>
                <form id="logoutForm" method="POST" action="{{ route('logout') }}" class="hidden">
                    @csrf
                </form>
            </nav>

            {{-- Widget Bawah Sidebar --}}
            <div x-show="sidebarOpen" class="m-4 p-4 bg-white/10 rounded-xl text-xs text-white shrink-0 border border-white/10 backdrop-blur-sm">
                <div class="flex items-center gap-2 mb-2 font-bold uppercase">
                    <x-heroicon-o-shield-check class="w-4 h-4 text-green-300" /> 
                    <span>Keamanan Data</span>
                </div>
                <p class="mb-3 text-[10px] text-gray-300 leading-tight">Anda login sebagai Admin. Pastikan data lahan & petani tetap rahasia.</p>
                <!-- <a href="#" class="w-full bg-white/20 py-2 rounded font-semibold text-white hover:bg-white hover:text-[#234323] transition inline-block text-center text-[10px]">
                    Panduan Penggunaan
                </a> -->
            </div>

            {{-- Widget Bawah Sidebar (Mode Tertutup) --}}
            <div x-show="!sidebarOpen" class="m-4 flex justify-center shrink-0">
                <a href="#" class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center hover:bg-white hover:text-[#234323] transition text-white shadow-sm border border-white/10" title="Panduan Penggunaan">
                    <x-heroicon-o-shield-check class="w-6 h-6" />
                </a>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            <header class="bg-white p-4 shadow-sm flex justify-between items-center px-4 md:px-8 z-30 shrink-0 h-16">
                <div class="flex items-center gap-3">
                    <button @click="toggleSidebar()" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h2 class="font-bold text-gray-700 text-sm md:text-base font-poppins">Dashboard Admin</h2>
                </div>
                
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="relative inline-block">
                        <button id="btnNotif" class="relative p-2 text-blue-400 bg-blue-50 rounded-full hover:bg-blue-100 transition">
                            <x-heroicon-o-bell class="w-5 h-5 md:w-6 md:h-6" />
                            <span id="notifBadge" class="hidden absolute top-1 right-1 bg-blue-500 text-white text-[8px] font-bold px-1 rounded-full border-2 border-white"></span>
                        </button>
                        <div id="popupNotif" class="hidden absolute right-0 mt-3 w-72 md:w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50">
                            @include('layouts.notification-popup')
                        </div>
                    </div>

                    <div class="flex items-center gap-2 border-l pl-3 md:pl-4">
                        <div class="text-right hidden md:block">
                            <p class="text-xs font-bold text-gray-800">Hi, {{ Auth::user()->user_nama }}</p>
                            <p class="text-[9px] text-gray-500 uppercase">{{ Auth::user()->user_role }}</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->user_nama) }}&background=0D9488&color=fff&bold=true" 
                            class="w-8 h-8 rounded-full border-2 border-gray-200 object-cover" 
                            alt="{{ Auth::user()->user_nama }}">
                    </div>
                </div>
            </header>

            <main class="p-4 md:p-6 overflow-y-auto flex-1 bg-[#F3F4F6]">
                @yield('content')
            </main>
        </div>
    </div>
    <!-- Modal Konfirmasi Logout -->
    <div x-show="showLogoutConfirm" 
         @click="showLogoutConfirm = false"
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
         style="display: none;">
        <div @click.stop class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-sm">
            <h3 class="text-lg font-bold text-gray-800 mb-2 font-poppins">Konfirmasi Keluar</h3>
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin keluar dari sistem?</p>
            <div class="flex gap-3 justify-end">
                <button @click="showLogoutConfirm = false" 
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium">
                    Batal
                </button>
                <button @click="confirmLogout()" 
                        class="px-4 py-2 bg-[#234323] text-white rounded-lg hover:bg-[#3D5A3E] transition font-medium">
                    Keluar
                </button>
            </div>
        </div>
    </div>
</body>
</html>