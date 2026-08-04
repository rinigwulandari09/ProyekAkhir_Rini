<div class="max-w-6xl mx-auto px-4 py-2 animate-fade-in">
    {{-- Pop-up Notifikasi Error --}}
    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl shadow-sm">
        <div class="flex items-start gap-3">
            <x-heroicon-s-x-circle class="w-5 h-5 text-red-600 shrink-0 mt-0.5" />
            <div>
                <p class="text-sm font-bold mb-1">Terjadi Kesalahan:</p>
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('petani.store') }}" method="POST">
        @csrf

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
            {{-- Header Card --}}
            <div class="bg-[#214122] p-4 sm:p-5 px-6 sm:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2 sm:gap-3">
                    <x-heroicon-o-user-plus class="w-5 h-5 sm:w-6 sm:h-6 text-white shrink-0" />
                    <h2 class="text-sm sm:text-lg font-bold text-white tracking-wide">Tambah Data Petani Baru</h2>
                </div>
                <a href="{{ route('petani.index') }}" class="w-full sm:w-auto text-center justify-center text-xs bg-white/10 text-white border border-white/20 px-4 py-2 rounded-xl font-semibold hover:bg-white/20 transition flex items-center gap-1.5 shadow-sm">
                    <x-heroicon-o-arrow-left class="w-4 h-4" />
                    Kembali
                </a>
            </div>

            <div class="p-6 md:p-8 space-y-8">
                {{-- Section 1: Profil & Informasi Dasar Petani --}}
                <div class="flex flex-col md:flex-row gap-6 md:gap-8 items-center md:items-start">
                    <div class="flex-1 w-full">
                        <h3 class="text-xs font-bold text-[#214122] uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Informasi Akun</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="petani_nama" value="{{ old('petani_nama') }}" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#214122] focus:border-[#214122] text-sm text-gray-700 shadow-sm transition" required>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase">Username <span class="text-red-500">*</span></label>
                                <input type="text" name="petani_username" value="{{ old('petani_username') }}" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#214122] focus:border-[#214122] text-sm font-mono text-gray-700 shadow-sm transition" required>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase">Alamat Email</label>
                                <input type="email" name="petani_email" value="{{ old('petani_email') }}" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#214122] focus:border-[#214122] text-sm text-gray-700 shadow-sm transition">
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase">Nomor Handphone <span class="text-red-500">*</span></label>
                                <input type="text" name="petani_no_hp" value="{{ old('petani_no_hp') }}" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#214122] focus:border-[#214122] text-sm text-gray-700 shadow-sm transition" required>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase">PIN / Password (Min. 6) <span class="text-red-500">*</span></label>
                                <input type="password" name="petani_pin" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#214122] focus:border-[#214122] text-sm text-gray-700 shadow-sm transition" required minlength="6">
                            </div>
                            
                            {{-- Field Desa hanya muncul untuk Super Admin --}}
                            @if(auth()->user()->user_role === 'super_admin')
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase">Desa</label>
                                <div class="relative mt-1">
                                    <select name="desa_id" class="w-full pl-3 pr-10 py-2 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#214122] focus:border-[#214122] appearance-none cursor-pointer text-sm text-gray-700 shadow-sm transition">
                                        <option value="">-- Pilih Desa --</option>
                                        @foreach($desas as $desa)
                                            <option value="{{ $desa->desa_id }}" {{ old('desa_id') == $desa->desa_id ? 'selected' : '' }}>{{ $desa->desa_nama }}</option>
                                        @endforeach
                                    </select>
                                    <x-heroicon-o-chevron-down class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 pointer-events-none" />
                                </div>
                            </div>
                            @else
                            {{-- Admin hanya bisa mendaftarkan petani untuk desa mereka sendiri --}}
                            <input type="hidden" name="desa_id" value="{{ auth()->user()->desa_id }}">
                            @endif
                            
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase block">Status Akun <span class="text-red-500">*</span></label>
                                <div class="relative w-full sm:max-w-xs mt-1">
                                    <select name="petani_status" class="w-full pl-3 pr-10 py-2 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#214122] focus:border-[#214122] appearance-none cursor-pointer text-sm font-semibold text-gray-700 shadow-sm transition">
                                        <option value="Aktif" {{ old('petani_status') == 'Aktif' ? 'selected' : '' }}>🟢 Aktif</option>
                                        <option value="Nonaktif" {{ old('petani_status') == 'Nonaktif' ? 'selected' : '' }}>🔴 Nonaktif</option>
                                    </select>
                                    <x-heroicon-o-chevron-down class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 pointer-events-none" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase">Jenis Kelamin</label>
                                <div class="relative mt-1">
                                    <select name="petani_jenis_kelamin" class="w-full pl-3 pr-10 py-2 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#214122] focus:border-[#214122] appearance-none cursor-pointer text-sm text-gray-700 shadow-sm transition">
                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                        <option value="Laki-laki" {{ old('petani_jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('petani_jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    <x-heroicon-o-chevron-down class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 pointer-events-none" />
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase">Tanggal Lahir</label>
                                <input type="date" name="petani_tanggal_lahir" value="{{ old('petani_tanggal_lahir') }}" class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#214122] focus:border-[#214122] text-sm text-gray-700 shadow-sm transition">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="text-xs font-semibold text-gray-400 uppercase">Alamat Lengkap</label>
                            <textarea name="petani_alamat" class="w-full mt-1 px-3 py-2 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#214122] focus:border-[#214122] text-sm text-gray-700 shadow-sm transition" rows="3">{{ old('petani_alamat') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Panel Tombol Aksi Simpan --}}
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end pt-6 border-t border-gray-100">
                    <button type="submit" class="w-full sm:w-auto justify-center bg-[#214122] text-white px-8 py-3 rounded-xl font-bold hover:bg-green-900 active:scale-95 transition shadow-md flex items-center gap-2 text-sm cursor-pointer">
                        <x-heroicon-o-check-circle class="w-5 h-5 text-green-400" />
                        Simpan Data Petani
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
