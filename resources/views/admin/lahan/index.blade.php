@extends('layouts.admin')

@section('title', 'Daftar Lahan - Admin')

@section('content')
    {{-- Memanggil komponen tabel yang sama, tombol tambah otomatis muncul --}}
    @include('components.lahan.tabelLahan')
@endsection