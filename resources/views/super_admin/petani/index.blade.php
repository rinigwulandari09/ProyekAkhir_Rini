@extends('layouts.dashboard')

@section('title', 'Daftar Petani')

@section('content')
<div class="p-2">
    {{-- Header Section --}}
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#214122]">Daftar Petani</h1>
            <p class="text-sm text-gray-500">Daftar seluruh petani sawit yang terdaftar di Supabase.</p>
        </div>
        <a href="{{ route('petani.create') }}" class="bg-[#214122] text-white px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-green-900 transition shadow-sm font-semibold text-sm">
            <x-heroicon-o-plus class="w-5 h-5" />
            Tambah Petani
        </a>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-200">
        <div class="overflow-x-auto">
            <table id="petaniTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#D9F99D] border-b border-gray-200">
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">Nama</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase">Email</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center">Status</th>
                        <th class="p-4 text-xs font-bold text-gray-700 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($petani as $p)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-xs text-gray-800 font-medium">{{ $p->nama_petani }}</td>
                        <td class="p-4 text-xs text-gray-500">{{ $p->email_petani }}</td>
                        <td class="p-4 text-center">
                            @if($p->status == 'Aktif')
                                <span class="bg-[#DCFCE7] text-[#166534] px-3 py-1 rounded-full text-[10px] font-bold">Aktif</span>
                            @else
                                <span class="bg-[#FEF3C7] text-[#92400E] px-3 py-1 rounded-full text-[10px] font-bold">Pending</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-3">
                                <a href="{{ route('petani.show', $p->id) }}" class="text-green-700 hover:scale-110 transition">
                                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                                </a>
                                <form action="{{ route('petani.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:scale-110 transition">
                                        <x-heroicon-o-trash class="w-5 h-5" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- DataTables CSS & JS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#petaniTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" // Terjemahan Indonesia
            },
            "pageLength": 10,
            "order": [[ 0, "asc" ]],
            "columnDefs": [
                { "orderable": false, "targets": 3 } // Matikan sorting untuk kolom aksi
            ]
        });
    });
</script>

<style>
    /* Kostumisasi DataTables agar cocok dengan Tailwind */
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e5e7eb !important;
        border-radius: 9999px !important;
        padding: 4px 12px !important;
        margin-bottom: 10px !important;
        outline: none !important;
    }
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #e5e7eb !important;
        border-radius: 8px !important;
        padding: 2px 8px !important;
    }
    table.dataTable thead th {
        border-bottom: 1px solid #e5e7eb !important;
    }
</style>
@endsection