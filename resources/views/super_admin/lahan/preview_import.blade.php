@extends('layouts.dashboard')

@section('content')

<div class="p-6">

    <h1 class="text-2xl font-bold mb-4">
        Preview Import GeoJSON
    </h1>

    <div class="bg-white rounded-xl shadow p-4">

        <table class="w-full border">

            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2">Nama JSON</th>
                    <th class="p-2">Desa</th>
                    <th class="p-2">Petani Sistem</th>
                    <th class="p-2">Status</th>
                </tr>
            </thead>

            <tbody>

            @foreach($preview as $row)

                <tr>

                    <td class="border p-2">
                        {{ $row['nama_json'] }}
                    </td>

                    <td class="border p-2">
                        {{ $row['desa'] }}
                    </td>

                    <td class="border p-2">
                        {{ $row['petani_db'] ?? '-' }}
                    </td>

                    <td class="border p-2">

                        @if($row['status']=='cocok')

                            <span class="text-green-600 font-medium">
                                Cocok (Siap Import)
                            </span>

                        @elseif($row['status']=='duplikat')

                            <span class="text-yellow-600 font-medium" title="Data lahan dengan area spasial ini sudah ada di database.">
                                Sudah Ada (Duplikat)
                            </span>

                        @else

                            <span class="text-red-600 font-medium">
                                Petani Tidak Ditemukan
                            </span>

                        @endif

                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

    <form
        action="{{ route('lahan.process_import') }}"
        method="POST"
        class="mt-5">

        @csrf

        <button
            class="bg-green-700 text-white px-4 py-2 rounded">

            Konfirmasi Import

        </button>

    </form>

</div>

@endsection