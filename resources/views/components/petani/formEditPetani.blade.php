{{-- Include Leaflet.js Assets (CSS & JS) --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="max-w-6xl mx-auto px-4 py-2 animate-fade-in">
    {{-- Pop-up Notifikasi Sukses --}}
    @if(session('success'))
    <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl shadow-sm">
        <x-heroicon-s-check-circle class="w-5 h-5 text-green-600 shrink-0" />
        <p class="text-sm font-medium">{{ session('success') }}</p>
    </div>
    @endif

    <form action="{{ url('/petani/' . $petani->petani_id . '/update-status') }}" method="POST">
        @csrf

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
            {{-- Header Card --}}
            <div class="bg-[#214122] p-4 sm:p-5 px-6 sm:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2 sm:gap-3">
                    <x-heroicon-o-user-circle class="w-5 h-5 sm:w-6 sm:h-6 text-white shrink-0" />
                    <h2 class="text-sm sm:text-lg font-bold text-white tracking-wide">Data Pribadi & Lahan Petani</h2>
                </div>
                <a href="{{ route('petani.index') }}" class="w-full sm:w-auto text-center justify-center text-xs bg-white/10 text-white border border-white/20 px-4 py-2 rounded-xl font-semibold hover:bg-white/20 transition flex items-center gap-1.5 shadow-sm">
                    <x-heroicon-o-arrow-left class="w-4 h-4" />
                    Kembali
                </a>
            </div>

            <div class="p-6 md:p-8 space-y-10">
                {{-- Section 1: Profil & Informasi Dasar Petani --}}
                <div class="flex flex-col md:flex-row gap-6 md:gap-8 items-center md:items-start">
                    <div class="w-full md:w-1/4 flex flex-col items-center gap-3">
                        <img src="{{ $petani->petani_profil ? asset('storage/' . $petani->petani_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($petani->petani_nama) . '&size=250&background=214122&color=fff&bold=true' }}" 
                             alt="Foto Petani" 
                             class="w-48 h-48 md:w-full md:h-52 object-cover rounded-2xl shadow-md border-2 border-gray-100">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $petani->petani_status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <span class="w-2 h-2 rounded-full {{ $petani->petani_status == 'Aktif' ? 'bg-green-600' : 'bg-red-600' }}"></span>
                            Akun {{ $petani->petani_status }}
                        </span>
                    </div>

                    <div class="flex-1 w-full">
                        <h3 class="text-xs font-bold text-[#214122] uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">Informasi Akun</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase">Nama Lengkap</label>
                                <input type="text" name="petani_nama" value="{{ old('petani_nama', $petani->petani_nama) }}" class="w-full px-3 py-2 border border-gray-300 bg-gray-100 text-gray-600 rounded-lg text-sm outline-none cursor-not-allowed" readonly required>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase">Username</label>
                                <input type="text" name="petani_username" value="{{ old('petani_username', $petani->petani_username) }}" class="w-full px-3 py-2 border border-gray-300 bg-gray-100 text-gray-600 rounded-lg text-sm font-mono outline-none cursor-not-allowed" readonly required>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase">Alamat Email</label>
                                <input type="email" name="petani_email" value="{{ old('petani_email', $petani->petani_email) }}" class="w-full px-3 py-2 border border-gray-300 bg-gray-100 text-gray-600 rounded-lg text-sm outline-none cursor-not-allowed" readonly>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase">Nomor Handphone</label>
                                <input type="text" name="petani_no_hp" value="{{ old('petani_no_hp', $petani->petani_no_hp) }}" class="w-full px-3 py-2 border border-gray-300 bg-gray-100 text-gray-600 rounded-lg text-sm outline-none cursor-not-allowed" readonly required>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase">Desa</label>
                                <select name="desa_id" class="w-full px-3 py-2 border border-gray-300 bg-gray-100 text-gray-600 rounded-lg text-sm outline-none cursor-not-allowed pointer-events-none" tabindex="-1">
                                    <option value="">-- Pilih Desa --</option>
                                    @foreach($desas as $desa)
                                        <option value="{{ $desa->desa_id }}" {{ (old('desa_id', $petani->desa_id) == $desa->desa_id) ? 'selected' : '' }}>{{ $desa->desa_nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase block">Ubah Status Akun</label>
                                <div class="relative w-full sm:max-w-xs mt-1">
                                    <select name="petani_status" class="w-full pl-3 pr-10 py-2 bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#214122] focus:border-[#214122] appearance-none cursor-pointer text-sm font-semibold text-gray-700 shadow-sm transition">
                                        <option value="Aktif" {{ $petani->petani_status == 'Aktif' ? 'selected' : '' }}>🟢 Aktif</option>
                                        <option value="Nonaktif" {{ $petani->petani_status == 'Nonaktif' ? 'selected' : '' }}>🔴 Nonaktif</option>
                                    </select>
                                    <x-heroicon-o-chevron-down class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 pointer-events-none" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase">Jenis Kelamin</label>
                                <select name="petani_jenis_kelamin" class="w-full px-3 py-2 border border-gray-300 bg-gray-100 text-gray-600 rounded-lg text-sm outline-none cursor-not-allowed pointer-events-none" tabindex="-1">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="Laki-laki" {{ old('petani_jenis_kelamin', $petani->petani_jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('petani_jenis_kelamin', $petani->petani_jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-400 uppercase">Tanggal Lahir</label>
                                <input type="date" name="petani_tanggal_lahir" value="{{ old('petani_tanggal_lahir', $petani->petani_tanggal_lahir) }}" class="w-full px-3 py-2 border border-gray-300 bg-gray-100 text-gray-600 rounded-lg text-sm outline-none cursor-not-allowed pointer-events-none" readonly tabindex="-1">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="text-xs font-semibold text-gray-400 uppercase">Alamat Lengkap</label>
                            <textarea name="petani_alamat" class="w-full mt-1 px-3 py-2 border border-gray-300 bg-gray-100 text-gray-600 rounded-lg text-sm outline-none cursor-not-allowed" rows="3" readonly>{{ old('petani_alamat', $petani->petani_alamat) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Informasi Lahan & Geometris Spasial --}}
                <div class="pt-8 border-t border-gray-200">
                    <h3 class="text-xs font-bold text-[#214122] uppercase tracking-wider mb-6 flex items-center gap-2">
                        <x-heroicon-o-map class="w-5 h-5 text-gray-500" />
                        Informasi Kepemilikan Lahan Pertanian
                    </h3>
                    
                    <div class="flex flex-col lg:flex-row gap-8">
                        <div class="w-full lg:w-1/2">
                            @if($petani->lahans->count() > 0 && $petani->lahans->whereNotNull('area_lahan')->where('area_lahan', '!=', '')->count() > 0)
                                <div id="map" class="w-full h-full min-h-[300px] rounded-2xl border border-gray-200 shadow-inner relative" style="z-index: 1;"></div>
                            @else
                                <div class="w-full h-full min-h-[300px] bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-center p-6">
                                    <x-heroicon-o-map-pin class="w-12 h-12 text-gray-300 mb-2" />
                                    <p class="text-sm font-semibold text-gray-500">Data Koordinat Kosong</p>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 max-h-[500px] overflow-y-auto space-y-4 pr-2">
                            @if($petani->lahans->count() > 0)
                                @foreach($petani->lahans as $index => $lahan)
                                    <div class="bg-gray-50/60 rounded-2xl p-6 border border-gray-100 flex flex-col justify-center relative">
                                        <div class="absolute top-4 right-4 bg-[#214122] text-white text-xs font-bold px-2 py-1 rounded-lg">Lahan {{ $index + 1 }}</div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5 text-xs mt-2">
                                            <div class="space-y-1">
                                                <span class="font-semibold text-gray-400 block uppercase tracking-wider">Lokasi Lahan</span>
                                                <span class="text-sm font-bold text-gray-800">{{ $lahan->lahan_lokasi ?? 'Lokasi belum diatur' }}</span>
                                            </div>
                                            <div class="space-y-1">
                                                <span class="font-semibold text-gray-400 block uppercase tracking-wider">Luas Lahan</span>
                                                <span class="text-sm font-bold bg-green-100 text-green-900 px-2.5 py-1 rounded-lg inline-block">
                                                    {{ $lahan->lahan_luas ?? '0' }} Ha
                                                </span>
                                            </div>
                                            <div class="space-y-1">
                                                <span class="font-semibold text-gray-400 block uppercase tracking-wider">Tahun Tanam</span>
                                                <span class="text-sm font-bold text-gray-800">{{ $lahan->tahun_tanam ?? '-' }}</span>
                                            </div>
                                            <div class="space-y-1">
                                                <span class="font-semibold text-gray-400 block uppercase tracking-wider">No Surat Lahan</span>
                                                <span class="text-sm font-bold text-gray-800">{{ $lahan->lahan_no_surat ?? '-' }}</span>
                                            </div>
                                            <div class="space-y-1 sm:col-span-2">
                                                <span class="font-semibold text-gray-400 block uppercase tracking-wider">Area Spasial (GeoJSON / Koordinat)</span>
                                                <div class="bg-white p-3 rounded-xl border border-gray-200 max-h-24 overflow-y-auto font-mono text-[11px] text-gray-600 shadow-sm">
                                                    @php
                                                        $areaLahanText = $lahan->area_lahan;
                                                        if (is_array($areaLahanText)) {
                                                            $areaLahanText = json_encode($areaLahanText, JSON_UNESCAPED_UNICODE);
                                                        }
                                                    @endphp
                                                    @if($areaLahanText)
                                                        {{ Str::limit($areaLahanText, 120, '...') }}
                                                    @else
                                                        <span class="text-gray-400 italic">Tidak ada data spasial</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="bg-gray-50/60 rounded-2xl p-6 border border-gray-100 flex flex-col items-center justify-center text-center py-8 h-full min-h-[300px]">
                                    <x-heroicon-o-document-text class="w-10 h-10 text-gray-300 mb-2" />
                                    <p class="text-xs font-semibold text-gray-500">Belum Memiliki Lahan</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Panel Tombol Aksi Simpan --}}
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end pt-6 border-t border-gray-100">
                    <button type="submit" class="w-full sm:w-auto justify-center bg-[#214122] text-white px-8 py-3 rounded-xl font-bold hover:bg-green-900 active:scale-95 transition shadow-md flex items-center gap-2 text-sm cursor-pointer">
                        <x-heroicon-o-check-circle class="w-5 h-5 text-green-400" />
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@if($petani->lahans->count() > 0 && $petani->lahans->whereNotNull('area_lahan')->where('area_lahan', '!=', '')->count() > 0)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const map = L.map('map').setView([-0.489, 101.406], 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const styleOptions = {
            color: '#214122',
            fillColor: '#214122',
            fillOpacity: 0.4,
            weight: 3
        };

        const bounds = L.latLngBounds();
        let hasLayer = false;

        @foreach($petani->lahans as $index => $lahan)
            @if(!empty($lahan->area_lahan))
            try {
                const rawData = @json($lahan->area_lahan);
                const areaData = typeof rawData === 'string' ? JSON.parse(rawData) : rawData;
                let layer = null;

                if (areaData && areaData.type) {
                    layer = L.geoJSON(areaData, {
                        style: styleOptions,
                        pointToLayer: function (feature, latlng) {
                            return L.circleMarker(latlng, styleOptions);
                        }
                    });
                } else if (Array.isArray(areaData) && areaData.length > 0) {
                    let polygonCoordinates = [];

                    if (areaData[0] && typeof areaData[0] === 'object' && 'lat' in areaData[0] && 'lng' in areaData[0]) {
                        polygonCoordinates = areaData.map(item => [item.lat, item.lng]);
                    } else if (Array.isArray(areaData[0]) && areaData[0].length >= 2) {
                        polygonCoordinates = areaData.map(coord => {
                            const isLatFirst = Math.abs(coord[0]) <= 90 && Math.abs(coord[1]) <= 180;
                            return isLatFirst ? [coord[0], coord[1]] : [coord[1], coord[0]];
                        });
                    } else if (Array.isArray(areaData[0]) && Array.isArray(areaData[0][0])) {
                        polygonCoordinates = areaData[0].map(coord => {
                            const isLatFirst = Math.abs(coord[0]) <= 90 && Math.abs(coord[1]) <= 180;
                            return isLatFirst ? [coord[0], coord[1]] : [coord[1], coord[0]];
                        });
                    }

                    if (polygonCoordinates.length) {
                        layer = L.polygon(polygonCoordinates, styleOptions);
                    }
                }

                if (layer) {
                    layer.bindPopup(`<div class="text-sm font-semibold text-[#214122]">Lahan {{ $index + 1 }}</div><div class="text-xs text-gray-600 mt-1">{{ addslashes($lahan->lahan_lokasi ?? 'Lokasi belum diatur') }}</div>`);
                    layer.addTo(map);
                    bounds.extend(layer.getBounds());
                    hasLayer = true;
                }
            } catch (error) {
                console.error('Gagal memproses struktur koordinat polygon:', error);
            }
            @endif
        @endforeach

        if (hasLayer) {
            map.fitBounds(bounds);
        }
    });
</script>
@endif