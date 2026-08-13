@extends('layouts.dashboard')

@section('title', 'Preview Import GeoJSON')

@section('content')
<div class="p-2 sm:p-4">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#214122]">Preview Import GeoJSON</h1>
            <p class="text-sm text-gray-500 mt-1">Periksa kembali data lahan sebelum diimpor ke sistem.</p>
        </div>
        <a href="{{ route('lahan.index') }}" class="bg-white text-gray-600 px-4 py-2.5 rounded-lg border border-gray-200 inline-flex items-center gap-2 hover:bg-gray-50 transition shadow-sm font-semibold text-sm">
            <x-heroicon-o-arrow-left class="w-5 h-5" />
            Batal
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-3 sm:p-4 border border-gray-200 mb-6">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#D4AF37] border-b border-[#B8860B] shadow-sm text-black">
                        <th class="p-4 text-xs font-bold text-[#214122] uppercase">Nama JSON</th>
                        <th class="p-4 text-xs font-bold text-[#214122] uppercase">Desa</th>
                        <th class="p-4 text-xs font-bold text-[#214122] uppercase">Petani Sistem</th>
                        <th class="p-4 text-xs font-bold text-[#214122] uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($preview as $row)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-xs text-gray-800 font-medium">{{ $row['nama_json'] }}</td>
                        <td class="p-4 text-xs text-gray-600">{{ $row['desa'] }}</td>
                        <td class="p-4 text-xs text-gray-600">{{ $row['petani_db'] ?? '-' }}</td>
                        <td class="p-4 text-xs">
                            @if($row['status']=='cocok')
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-100 text-green-700 border border-green-200">
                                    Cocok (Siap Import)
                                </span>
                            @elseif($row['status']=='duplikat')
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-yellow-100 text-yellow-700 border border-yellow-200" title="Data lahan dengan area spasial ini sudah ada di database.">
                                    Sudah Ada (Duplikat)
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-100 text-red-700 border border-red-200">
                                    Petani Tidak Ditemukan
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-10 text-center text-gray-400 italic text-xs">
                            Tidak ada data lahan untuk ditampilkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <form action="{{ route('lahan.process_import') }}" method="POST" class="flex justify-end pt-2 border-t border-gray-100">
        @csrf
        <button type="submit" class="inline-flex justify-center items-center bg-[#214122] text-white px-8 py-3 rounded-xl font-semibold hover:bg-green-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#214122] transition-colors shadow-md w-full sm:w-auto text-sm">
            <x-heroicon-o-cloud-arrow-up class="w-5 h-5 mr-2 -ml-1" />
            Konfirmasi Import
        </button>
    </form>
</div>
@endsection