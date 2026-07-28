<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SILAUSA</title>
    <link rel="icon" href="{{ asset('foto/logo.png') }}" type="image/png">
    <!-- Menghubungkan ke Vite (Tailwind) -->
    @vite('resources/css/app.css')
    <!-- Google Fonts for better typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased selection:bg-[#234323]/20 selection:text-[#234323]">

    <div class="min-h-screen flex">
        <!-- Image Section - Hidden on mobile, visible on desktop (lg and up) -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-[#234323] items-center justify-center overflow-hidden">
            <!-- Background Image -->
            <div class="absolute inset-0 z-0">
                <img
                    src="{{ asset('foto/sawit.png') }}"
                    alt="Kebun Sawit"
                    class="w-full h-full object-cover opacity-60 mix-blend-overlay"
                />
            </div>
            <!-- Overlay Content -->
            <div class="relative z-10 p-12 text-center text-white">
                <h2 class="text-4xl font-bold mb-4 drop-shadow-lg">Selamat Datang di SILAUSA</h2>
                <p class="text-lg text-white/80 max-w-md mx-auto drop-shadow-md">Sistem Manajemen Perkebunan Kelapa Sawit yang Terintegrasi dan Modern.</p>
            </div>
        </div>

        <!-- Form Section -->
        <div class="w-full lg:w-1/2 flex items-center justify-center bg-transparent lg:bg-white p-6 sm:p-12 relative z-10">
            <!-- Container -->
            <div class="w-full max-w-md bg-white rounded-3xl shadow-xl sm:shadow-2xl sm:border border-gray-100 p-8 sm:p-10 transform transition-all hover:-translate-y-1 hover:shadow-[#234323]/10 duration-300">
                
                <!-- Mobile Logo/Header (Visible on smaller screens) -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-[#234323]/10 text-[#234323] rounded-full mb-4 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">SILAUSA</h1>
                    <p class="text-gray-500 text-sm mt-2 font-medium">Silakan masuk ke akun Anda</p>
                </div>

                <form action="{{ route('login.process') }}" method="POST" class="space-y-6">
                    @csrf 

                    <!-- Username Field -->
                    <div class="group">
                        <label for="user_username" class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-focus-within:text-[#234323] transition-colors" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input
                                type="text"
                                id="user_username"
                                name="user_username" 
                                placeholder="Masukkan username"
                                value="{{ old('user_username') }}"
                                class="w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-[#234323] focus:ring-4 focus:ring-[#234323]/10 transition-all duration-200 @error('user_username') border-red-500 focus:border-red-500 focus:ring-red-500/10 @enderror bg-gray-50 focus:bg-white"
                                required
                            />
                        </div>
                        @error('user_username')
                            <p class="text-red-500 text-xs font-medium mt-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="group">
                        <div class="mb-2">
                            <label for="user_password" class="block text-sm font-semibold text-gray-700">Password</label>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-focus-within:text-[#234323] transition-colors" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input
                                type="password"
                                id="user_password"
                                name="user_password"
                                placeholder="••••••••"
                                class="w-full pl-11 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-[#234323] focus:ring-4 focus:ring-[#234323]/10 transition-all duration-200 @error('user_password') border-red-500 focus:border-red-500 focus:ring-red-500/10 @enderror bg-gray-50 focus:bg-white"
                                required
                            />
                        </div>
                        @error('user_password')
                            <p class="text-red-500 text-xs font-medium mt-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full flex justify-center items-center gap-2 bg-[#234323] hover:bg-[#3D5A3E] text-white py-3.5 px-4 rounded-xl font-bold shadow-lg shadow-[#234323]/30 hover:shadow-[#3D5A3E]/40 transform hover:-translate-y-0.5 transition-all duration-200">
                        <span>Masuk</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
            </div>
            
            <!-- Mobile Background decorative elements (visible only on small screens) -->
            <div class="fixed inset-0 z-[-1] bg-gray-50 lg:hidden overflow-hidden pointer-events-none">
                <div class="absolute -top-[20%] -right-[10%] w-[70%] h-[50%] bg-[#234323]/20 rounded-full blur-3xl opacity-40"></div>
                <div class="absolute top-[60%] -left-[20%] w-[60%] h-[40%] bg-[#234323]/30 rounded-full blur-3xl opacity-30"></div>
            </div>
        </div>
    </div>

</body>
</html>