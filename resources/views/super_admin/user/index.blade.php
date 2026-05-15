@extends('layouts.dashboard')

@section('title', 'Daftar User')

@section('content')
<div class="p-2">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen User</h1>
            <p class="text-sm text-gray-500">Daftar pengguna yang terdaftar di database Supabase</p>
        </div>
        
        <a href="{{ route('user.create') }}" class="bg-[#214122] text-white px-4 py-2.5 rounded-lg inline-flex items-center gap-2 hover:bg-green-900 transition shadow-md font-semibold text-sm">
            <x-heroicon-o-user-plus class="w-5 h-5" />
            Tambah User
        </a>
    </div>

    <!-- Alert Pesan Sukses -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative text-sm flex items-center shadow-sm" role="alert">
            <x-heroicon-o-check-circle class="w-5 h-5 mr-2" />
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left cell-border hover" id="userTable">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="p-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">No</th>
                        <th class="p-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">Nama</th>
                        <th class="p-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">Username</th>
                        <th class="p-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">Role</th>
                        <th class="p-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-green-50/50 transition">
                        <td class="p-4 text-sm text-gray-500 font-mono">#{{ $user->user_id }}</td>
                        <td class="p-4">
                            <div class="text-sm font-bold text-gray-800">{{ $user->user_nama }}</div>
                        </td>
                        <td class="p-4 text-sm text-gray-600">
                            <span class="bg-gray-100 px-2 py-1 rounded text-xs">@ {{ $user->user_username }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $user->user_role == 'super_admin' ? 'bg-purple-100 text-purple-700 border border-purple-200' : 'bg-blue-100 text-blue-700 border border-blue-200' }}">
                                {{ $user->user_role }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-center gap-3">
                                {{-- Button Edit --}}
                                <a href="{{ route('user.edit', $user->user_id) }}" class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-600 hover:text-white transition shadow-sm" title="Edit">
                                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                                </a>

                                <!-- Button Hapus -->
                                <form action="{{ route('user.destroy', $user->user_id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition shadow-sm" title="Hapus">
                                        <x-heroicon-o-trash class="w-5 h-5" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-gray-400 italic">
                            Belum ada data user di database.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- CSS DataTables Modern --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    /* Styling Container Search & Tabel */
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1.5rem;
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e5e7eb !important;
        border-radius: 0.75rem !important;
        padding: 0.5rem 1rem !important;
        background-color: #f9fafb !important;
        width: 250px !important;
        outline: none !important;
        transition: all 0.3s;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #214122 !important;
        background-color: #fff !important;
        box-shadow: 0 0 0 3px rgba(33, 65, 34, 0.1) !important;
    }

    /* Styling Pagination */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #214122 !important;
        color: white !important;
        border: none !important;
        border-radius: 0.5rem !important;
        font-weight: bold;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f3f4f6 !important;
        color: #214122 !important;
        border: none !important;
    }

    .dataTables_wrapper .dataTables_info {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 1rem;
    }

    table.dataTable.no-footer {
        border-bottom: 1px solid #f3f4f6 !important;
    }
</style>

<!-- Script DataTables -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#userTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json",
                "search": "", // Menghilangkan teks "Cari:"
                "searchPlaceholder": "Cari user di sini..."
            },
            "pageLength": 10,
            "dom": '<"flex flex-col md:flex-row justify-between items-center"f>rt<"flex flex-col md:flex-row justify-between items-center"ip>',
        });
    });
</script>
@endsection