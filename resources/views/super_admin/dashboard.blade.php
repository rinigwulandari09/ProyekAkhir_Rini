@extends('layouts.dashboard')

@section('title', 'Beranda')

@section('content')
<div class="space-y-6">
    
    <div class="grid grid-cols-3 gap-6">
        <div class="bg-[#A0C4E8] p-6 rounded-xl flex items-center justify-between shadow-sm border border-black/5">
            <div>
                <p class="text-blue-900 font-bold text-sm">Jumlah Petani</p>
                <h3 class="text-3xl font-black text-blue-900 leading-none">859</h3>
            </div>
            <iconify-icon icon="noto:farmer" class="text-5xl"></iconify-icon>
        </div>
        <div class="bg-[#A8D5BA] p-6 rounded-xl flex items-center justify-between shadow-sm border border-black/5">
            <div>
                <p class="text-green-900 font-bold text-sm">Jumlah Lahan</p>
                <h3 class="text-3xl font-black text-green-900 leading-none">1040</h3>
            </div>
            <iconify-icon icon="noto:sheaf-of-rice" class="text-5xl"></iconify-icon>
        </div>
        <div class="bg-[#E9D79E] p-6 rounded-xl flex items-center justify-between shadow-sm border border-black/5">
            <div>
                <p class="text-yellow-900 font-bold text-sm">Pendapatan Bulan Ini</p>
                <h3 class="text-3xl font-black text-yellow-900 leading-none text-center">859</h3>
            </div>
            <iconify-icon icon="noto:farmer" class="text-5xl"></iconify-icon>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <div class="bg-white p-4 rounded-xl shadow-sm h-64 flex flex-col items-center">
            <p class="self-start text-[10px] font-bold text-gray-500 mb-4">Pemasukan Per Bulan</p>
            <div class="w-full h-full bg-gray-50 rounded flex items-center justify-center italic text-gray-400">
                [ Grafik Garis Pemasukan ]
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm h-64 flex flex-col items-center">
            <p class="self-start text-[10px] font-bold text-gray-500 mb-4">Pengeluaran Per Kategori</p>
            <div class="w-full h-full bg-gray-50 rounded flex items-center justify-center italic text-gray-400">
                [ Grafik Pie Pengeluaran ]
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm">
        <h3 class="text-[10px] font-bold text-gray-500 mb-4">Status Audit RSPO/ISPO</h3>
        <div class="grid grid-cols-3 gap-6">
            <div class="bg-[#D1FAE5] p-4 rounded-lg flex items-center gap-4 border border-green-200">
                <iconify-icon icon="mdi:check-circle" class="text-green-800 text-4xl"></iconify-icon>
                <div><p class="text-2xl font-black text-green-900 leading-none">670</p><p class="text-[10px] font-bold text-green-700">Lulus</p></div>
            </div>
            <div class="bg-[#FEF3C7] p-4 rounded-lg flex items-center gap-4 border border-yellow-200">
                <iconify-icon icon="mdi:information" class="text-yellow-600 text-4xl"></iconify-icon>
                <div><p class="text-2xl font-black text-yellow-900 leading-none">130</p><p class="text-[10px] font-bold text-yellow-700 uppercase">Perlu perbaikan</p></div>
            </div>
            <div class="bg-[#FEE2E2] p-4 rounded-lg flex items-center gap-4 border border-red-200">
                <iconify-icon icon="mdi:alert" class="text-red-600 text-4xl"></iconify-icon>
                <div><p class="text-2xl font-black text-red-900 leading-none">30</p><p class="text-[10px] font-bold text-red-700 uppercase">Perlu Diaudit</p></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="bg-[#D9F99D] p-3 text-[10px] font-black text-gray-700 uppercase tracking-wider border-b">Petani yang perlu di verifikasi</div>
        <table class="w-full text-left text-[10px]">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-3 text-center uppercase">ID</th>
                    <th class="p-3 uppercase">Nama</th>
                    <th class="p-3 uppercase">Email</th>
                    <th class="p-3 text-center uppercase">Status</th>
                    <th class="p-3 text-center uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach(['PT001' => 'Bayu Mirandar', 'PT002' => 'Siti Lestari', 'PT003' => 'Indah Vitonita', 'PT004' => 'Raya Puspita'] as $id => $nama)
                <tr class="border-b">
                    <td class="p-3 text-center text-gray-500">{{ $id }}</td>
                    <td class="p-3 font-bold italic">{{ $nama }}</td>
                    <td class="p-3 text-gray-400 italic">bayu12@gmail.com</td>
                    <td class="p-3 text-center">
                        <span class="bg-[#FEF08A] px-4 py-1 rounded-full font-bold shadow-sm">Pending</span>
                    </td>
                    <td class="p-3 flex justify-center gap-2">
                        <iconify-icon icon="mdi:square-edit-outline" class="text-green-500 text-lg"></iconify-icon>
                        <iconify-icon icon="mdi:trash-can" class="text-red-500 text-lg"></iconify-icon>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-2 flex justify-end gap-1">
            <button class="px-2 py-1 bg-gray-100 rounded text-[9px] hover:bg-gray-200">«</button>
            <button class="px-2 py-1 bg-gray-300 rounded text-[9px]">1</button>
            <button class="px-2 py-1 bg-gray-100 rounded text-[9px]">2</button>
            <button class="px-2 py-1 bg-gray-100 rounded text-[9px]">»</button>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-[10px] font-bold text-gray-500 mb-2">Sebaran Lahan Anggota</h3>
        <div class="w-full h-80 rounded-lg overflow-hidden bg-gray-200">
            <img src="https://maps.googleapis.com/maps/api/staticmap?center=-0.489,101.406&zoom=13&size=800x400&maptype=satellite&key=YOUR_KEY" class="w-full h-full object-cover">
        </div>
    </div>

</div>
@endsection