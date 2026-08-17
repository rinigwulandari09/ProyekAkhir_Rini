<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SILAUSA - Sistem Informasi Lahan, Audit, dan Keuangan Sawit</title>
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .hero-pattern {
            background-color: #214122;
            background-image: linear-gradient(rgba(33, 65, 34, 0.85), rgba(33, 65, 34, 0.95)), url('{{ asset('foto/sawit1.jfif') }}');
            background-size: cover;
            background-position: center;
        }
        .shape-blob {
            position: absolute;
            background: rgba(144, 238, 144, 0.1);
            border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
            animation: morph 8s ease-in-out infinite both alternate;
            z-index: 0;
        }
        @keyframes morph {
            0% { border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%; }
            100% { border-radius: 70% 30% 50% 50% / 30% 30% 70% 70%; }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased overflow-x-hidden selection:bg-[#EAB308] selection:text-white">

    {{-- Navbar --}}
    <nav x-data="{ scrolled: false, mobileMenuOpen: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         :class="{'bg-white/95 backdrop-blur-md shadow-sm py-3': scrolled, 'bg-transparent py-5': !scrolled}"
         class="fixed w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                {{-- Logo --}}
                <div class="flex items-center gap-2 flex-shrink-0 w-1/4">
                    <img src="{{ asset('foto/logo.png') }}" alt="Logo SILAUSA" class="w-10 h-10 object-contain drop-shadow-md">
                    <span :class="{'text-[#214122]': scrolled, 'text-white': !scrolled}" class="text-2xl font-extrabold tracking-tight transition-colors">
                        SILAUSA
                    </span>
                </div>

                {{-- Desktop Menu (Centered) --}}
                <div class="hidden md:flex space-x-8 items-center justify-center flex-1">
                    <a href="#beranda" :class="{'text-gray-600 hover:text-[#214122]': scrolled, 'text-white/90 hover:text-white': !scrolled}" class="text-sm font-semibold transition-colors">Beranda</a>
                    <a href="#tentang" :class="{'text-gray-600 hover:text-[#214122]': scrolled, 'text-white/90 hover:text-white': !scrolled}" class="text-sm font-semibold transition-colors">Tentang</a>
                    <a href="#layanan" :class="{'text-gray-600 hover:text-[#214122]': scrolled, 'text-white/90 hover:text-white': !scrolled}" class="text-sm font-semibold transition-colors">Layanan</a>
                    <a href="#kontak" :class="{'text-gray-600 hover:text-[#214122]': scrolled, 'text-white/90 hover:text-white': !scrolled}" class="text-sm font-semibold transition-colors">Kontak</a>
                </div>

                {{-- Desktop Login Button --}}
                <div class="hidden md:flex items-center justify-end flex-shrink-0 w-1/4">
                    <a href="{{ route('login') }}" class="px-5 py-2.5 bg-[#EAB308] hover:bg-[#d9a206] text-white text-sm font-bold rounded-full transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 whitespace-nowrap">
                        Login Sistem
                    </a>
                </div>

                {{-- Mobile Menu Button --}}
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" :class="{'text-gray-800': scrolled, 'text-white': !scrolled}" class="focus:outline-none">
                        <svg x-show="!mobileMenuOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileMenuOpen" x-collapse class="md:hidden bg-white border-t shadow-xl absolute w-full">
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a href="#beranda" @click="mobileMenuOpen = false" class="block px-3 py-3 text-base font-semibold text-gray-800 hover:bg-gray-50 rounded-lg">Beranda</a>
                <a href="#tentang" @click="mobileMenuOpen = false" class="block px-3 py-3 text-base font-semibold text-gray-800 hover:bg-gray-50 rounded-lg">Tentang</a>
                <a href="#layanan" @click="mobileMenuOpen = false" class="block px-3 py-3 text-base font-semibold text-gray-800 hover:bg-gray-50 rounded-lg">Layanan</a>
                <a href="#kontak" @click="mobileMenuOpen = false" class="block px-3 py-3 text-base font-semibold text-gray-800 hover:bg-gray-50 rounded-lg">Kontak</a>
                <div class="pt-4">
                    <a href="{{ route('login') }}" class="block w-full text-center px-5 py-3 bg-[#214122] text-white font-bold rounded-xl">Login Sistem</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section id="beranda" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 hero-pattern overflow-hidden">
        <div class="shape-blob w-[500px] h-[500px] top-0 left-[-100px] opacity-20"></div>
        <div class="shape-blob w-[400px] h-[400px] bottom-[-50px] right-[-50px] opacity-20" style="animation-delay: 2s;"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <span class="inline-block py-1.5 px-4 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full text-white/90 text-sm font-semibold mb-6 tracking-wide">
                Asosiasi Pekebun Swadaya Kelapa Sawit Pelalawan-Siak
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-7xl font-extrabold text-white leading-tight mb-8">
                Manajemen Lahan Sawit <br class="hidden lg:block"/> 
                <span class="text-[#EAB308]">Terintegrasi & Modern</span>
            </h1>
            <p class="mt-4 max-w-2xl text-lg md:text-xl text-gray-200 mx-auto mb-10 leading-relaxed font-light">
                Sistem Informasi Lahan, Audit, dan Keuangan Sawit (SILAUSA) hadir untuk mempermudah pemetaan, pemantauan kualitas, hingga pencatatan hasil panen petani secara transparan.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('login') }}" class="px-8 py-4 bg-[#EAB308] hover:bg-[#d9a206] text-white font-bold rounded-full text-lg transition-all shadow-[0_0_20px_rgba(234,179,8,0.4)] hover:shadow-[0_0_30px_rgba(234,179,8,0.6)] transform hover:-translate-y-1">
                    Masuk ke Sistem
                </a>
                <a href="#" class="px-8 py-4 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/30 text-white font-bold rounded-full text-lg transition-all transform hover:-translate-y-1">
                    Unduh Aplikasi
                </a>
            </div>
        </div>

        {{-- Wavy bottom --}}
        <div class="absolute bottom-0 left-0 right-0 w-full overflow-hidden leading-[0]">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-full h-[60px] md:h-[100px]">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118.06,155.14,124.9,230.1,107.57,262.39,100.08,293.4,85.25,321.39,56.44Z" fill="#f9fafb"></path>
            </svg>
        </div>
    </section>

    {{-- Highlights Features Overlapping --}}
    <section class="relative z-20 -mt-16 md:-mt-24 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                <!-- Card 1 -->
                <div class="bg-white p-8 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 transform hover:-translate-y-2 transition-all duration-300">
                    <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center mb-6 text-[#214122]">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Pendataan Lahan</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Pemetaan poligon koordinat lahan petani secara presisi untuk memonitor luasan dan status lahan.</p>
                </div>
                <!-- Card 2 -->
                <div class="bg-white p-8 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 transform hover:-translate-y-2 transition-all duration-300">
                    <div class="w-14 h-14 bg-yellow-100 rounded-2xl flex items-center justify-center mb-6 text-[#EAB308]">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Audit Internal</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Pencatatan riwayat kunjungan dan audit standar RSPO/ISPO untuk memastikan kualitas kebun berkelanjutan.</p>
                </div>
                <!-- Card 3 -->
                <div class="bg-white p-8 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 transform hover:-translate-y-2 transition-all duration-300">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mb-6 text-blue-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Rekap Keuangan</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Manajemen hasil produksi TBS dan pengeluaran operasional lahan secara detail dan transparan.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- About / Stats Section --}}
    <section id="tentang" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                
                {{-- Left Image / Banners Grid --}}
                <div class="w-full lg:w-1/2">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-4">
                            <div class="bg-[#214122] text-white p-8 rounded-[30px] rounded-br-none flex flex-col items-center justify-center text-center shadow-lg transform hover:scale-105 transition-transform duration-300">
                                <span class="text-4xl font-black mb-1">500+</span>
                                <span class="text-sm font-medium text-green-200">Petani Swadaya</span>
                            </div>
                            <img src="{{ asset('foto/fotobersama.jpeg') }}" alt="Petani Sawit" class="w-full h-48 object-cover rounded-[30px] rounded-tr-none shadow-lg">
                        </div>
                        <div class="space-y-4 pt-8">
                            <img src="{{ asset('foto/sawit2.jfif') }}" alt="Kebun Sawit" class="w-full h-48 object-cover rounded-[30px] rounded-bl-none shadow-lg">
                            <div class="bg-[#EAB308] text-white p-8 rounded-[30px] rounded-tl-none flex flex-col items-center justify-center text-center shadow-lg transform hover:scale-105 transition-transform duration-300">
                                <span class="text-4xl font-black mb-1">2000+</span>
                                <span class="text-sm font-medium text-yellow-100">Hektar Lahan Terdata</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Content --}}
                <div class="w-full lg:w-1/2">
                    <span class="text-[#EAB308] font-bold tracking-wider uppercase text-sm mb-2 block">Tentang Asosiasi</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight mb-6">
                        Membangun Kesejahteraan Petani Sawit Berkelanjutan
                    </h2>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Kami berkomitmen untuk mendampingi petani kelapa sawit swadaya di Pelalawan dan Siak dalam mewujudkan tata kelola perkebunan yang baik (Good Agricultural Practices). Melalui sistem SILAUSA, kami memfasilitasi pendataan yang akurat sebagai langkah awal sertifikasi.
                    </p>
                    
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start gap-3">
                            <div class="mt-1 bg-green-100 p-1 rounded-full text-[#214122]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-gray-700 font-medium">Pemetaan Lahan Berbasis Polygon GPS</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="mt-1 bg-green-100 p-1 rounded-full text-[#214122]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-gray-700 font-medium">Standarisasi Kebun Berkelanjutan</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="mt-1 bg-green-100 p-1 rounded-full text-[#214122]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-gray-700 font-medium">Digitalisasi Laporan Keuangan Petani</span>
                        </li>
                    </ul>

                    <a href="#layanan" class="inline-flex items-center gap-2 text-[#214122] font-bold hover:text-[#162d17] transition-colors border-b-2 border-[#214122] pb-1">
                        Jelajahi Layanan Kami
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>

            </div>
        </div>
    </section>

    {{-- Services Section --}}
    <section id="layanan" class="py-20 bg-gray-50 relative overflow-hidden">
        {{-- Background Elements --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-green-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 transform translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-yellow-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 transform -translate-x-1/2 translate-y-1/2"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-[#214122] font-bold tracking-wider uppercase text-sm mb-2 block">Fitur Utama</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">Layanan Digital Terpadu</h2>
                <p class="text-gray-600">Sistem informasi yang dirancang khusus untuk memenuhi kebutuhan administrasi dan pemantauan perkebunan sawit secara efisien.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div class="bg-white rounded-[2rem] overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 group">
                    <div class="h-48 overflow-hidden relative">
                        <div class="absolute inset-0 bg-[#214122]/20 group-hover:bg-transparent transition-colors z-10"></div>
                        <img src="{{ asset('foto/sawit2.jfif') }}" alt="Pemetaan Lahan" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-[#214122] transition-colors">Pemetaan & Database Lahan</h3>
                        <p class="text-gray-600 text-sm mb-6 line-clamp-3">Mendata informasi lengkap lahan termasuk koordinat GPS poligon, legalitas surat tanah, jenis bibit, dan tahun tanam secara terpusat.</p>
                    </div>
                </div>

                <!-- Service 2 -->
                <div class="bg-white rounded-[2rem] overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 group">
                    <div class="h-48 overflow-hidden relative">
                        <div class="absolute inset-0 bg-[#214122]/20 group-hover:bg-transparent transition-colors z-10"></div>
                        <img src="{{ asset('foto/sawit3.jfif') }}" alt="Audit Internal" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-[#214122] transition-colors">Audit Internal (Petani & Lahan)</h3>
                        <p class="text-gray-600 text-sm mb-6 line-clamp-3">Modul khusus bagi auditor untuk melakukan kunjungan lapangan, memberikan penilaian kelayakan, dan menentukan status kelulusan standar RSPO.</p>
                    </div>
                </div>

                <!-- Service 3 -->
                <div class="bg-white rounded-[2rem] overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 group">
                    <div class="h-48 overflow-hidden relative">
                        <div class="absolute inset-0 bg-[#214122]/20 group-hover:bg-transparent transition-colors z-10"></div>
                        <img src="{{ asset('foto/uang.jfif') }}" alt="Manajemen Keuangan" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-[#214122] transition-colors">Pencatatan Keuangan</h3>
                        <p class="text-gray-600 text-sm mb-6 line-clamp-3">Sistem pencatatan produksi Tandan Buah Segar (TBS) beserta harga jual, dan manajemen biaya operasional kebun seperti pupuk dan panen.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Call to Action --}}
    <section class="py-20 bg-[#214122] relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
            <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6">Mulai Kelola Lahan Anda Sekarang</h2>
            <p class="text-green-100 text-lg mb-10 max-w-2xl mx-auto">Masuk ke dalam sistem SILAUSA untuk memantau data lahan, hasil audit, dan riwayat produksi kelapa sawit Anda.</p>
            <a href="{{ route('login') }}" class="inline-block px-10 py-4 bg-[#EAB308] hover:bg-[#d9a206] text-white font-bold rounded-full text-lg shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all">
                Login Sistem SILAUSA
            </a>
        </div>
    </section>

    {{-- Footer --}}
    <footer id="kontak" class="bg-gray-900 text-gray-300 py-16 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-8">
                
                {{-- Brand Column --}}
                <div class="lg:col-span-4">
                    <div class="flex items-center gap-3 mb-6">
                        <img src="{{ asset('foto/logo.png') }}" alt="Logo SILAUSA" class="w-10 h-10 object-contain drop-shadow-md">
                        <span class="text-2xl font-extrabold text-white tracking-tight">SILAUSA</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed mb-8 pr-4">
                        Sistem Informasi Lahan, Audit, dan Keuangan Kelapa Sawit.<br><br>
                        Mendukung tata kelola perkebunan kelapa sawit swadaya yang modern, berkelanjutan, dan mematuhi standar sertifikasi.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-[#EAB308] hover:text-white transition-all shadow-md">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-[#EAB308] hover:text-white transition-all shadow-md">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="lg:col-span-2">
                    <h4 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">Tautan Cepat</h4>
                    <ul class="space-y-3">
                        <li><a href="#beranda" class="text-sm text-gray-400 hover:text-[#EAB308] transition-colors">Beranda</a></li>
                        <li><a href="#tentang" class="text-sm text-gray-400 hover:text-[#EAB308] transition-colors">Tentang Kami</a></li>
                        <li><a href="#layanan" class="text-sm text-gray-400 hover:text-[#EAB308] transition-colors">Layanan Sistem</a></li>
                        <li><a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-[#EAB308] transition-colors">Login Admin</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div class="lg:col-span-3">
                    <h4 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">Hubungi Kami</h4>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-[#EAB308] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="text-sm text-gray-400 leading-relaxed">
                                Asosiasi Pekebun Swadaya Kelapa Sawit Pelalawan-Siak<br>
                                Riau, Indonesia
                            </span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-[#EAB308] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span class="text-sm text-gray-400">apsksps@gmail.com</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-[#EAB308] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span class="text-sm text-gray-400">+62 812-3456-7890</span>
                        </li>
                    </ul>
                </div>

                {{-- Map Area --}}
                <div class="lg:col-span-3">
                    <h4 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">Lokasi Kantor Asosiasi</h4>
                    <div class="rounded-2xl overflow-hidden shadow-lg border border-gray-700 h-48 w-full transform hover:scale-105 transition-transform duration-300">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2703.036354454311!2d101.63317957293216!3d0.42663300077478883!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d5b9bfa2121afd%3A0x1279647ab6dbab96!2sKantor%20Asosiasi%20Pekebun%20Swadaya%20Kelapa%20Sawit%20Pelalawan%20Siak!5e1!3m2!1sen!2sid!4v1786978399976!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                    </div>
                </div>

            </div>
            
            <div class="border-t border-gray-800 mt-16 pt-8 text-center md:text-left">
                <p class="text-sm text-gray-500 font-medium">&copy; {{ date('Y') }} SILAUSA. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>
</body>
</html>
