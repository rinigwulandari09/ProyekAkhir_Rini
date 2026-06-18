<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NOTASAWIT</title>
    <!-- Menghubungkan ke Vite (Tailwind) -->
    @vite('resources/css/app.css') 
</head>
<body class="bg-gray-100">

    <div class="h-screen flex">
        <div class="hidden md:block md:w-1/2">
            <img
                src="{{ asset('foto/sawit.png') }}"
                alt="Sawit"
                class="w-full h-full object-cover"
            />
        </div>

        <div class="w-full md:w-1/2 flex items-center justify-center bg-gray-100">
            <div class="bg-white p-10 rounded-2xl shadow-xl w-96">
                
                <h1 class="text-2xl font-bold text-green-900 text-center mb-8">
                    NOTASAWIT
                </h1>

                <form action="{{ route('login.process') }}" method="POST">
                    @csrf 

                    <div class="mb-4">
                        {{-- 2. Ubah type ke 'text' dan name ke 'user_username' --}}
                        <input
                            type="text"
                            name="user_username" 
                            placeholder="Masukkan username"
                            value="{{ old('user_username') }}"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-700 @error('user_username') border-red-500 @enderror"
                            required
                        />
                        {{-- 3. Update variabel error --}}
                        @error('user_username')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-2">
                        {{-- 4. Ubah name ke 'user_password' --}}
                        <input
                            type="password"
                            name="user_password"
                            placeholder="Masukkan password"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-700 @error('user_password') border-red-500 @enderror"
                            required
                        />
                        {{-- 5. Update variabel error --}}
                        @error('user_password')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="text-right mb-4">
                        <a href="#" class="text-sm text-blue-500 hover:underline">
                            Lupa Password
                        </a>
                    </div>

                    <button type="submit" class="w-full bg-green-800 text-white py-3 rounded-lg hover:bg-green-900 transition font-semibold">
                        Masuk
                    </button>
                </form>

                {{-- <p class="text-center text-gray-500 my-4">atau</p>

                <button class="w-full border py-3 rounded-lg flex items-center justify-center gap-2 hover:bg-gray-100 transition">
                    <img
                        src="https://www.svgrepo.com/show/475656/google-color.svg"
                        alt="google"
                        class="w-5 h-5"
                    />
                    <span class="text-gray-700">Masuk dengan Google</span>
                </button> --}}

            </div>
        </div>
    </div>

</body>
</html>