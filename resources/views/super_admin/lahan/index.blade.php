@extends('layouts.dashboard')

@section('title', 'Daftar Lahan - Super Admin')

@section('content')
    {{-- Memanggil komponen tabel lahan --}}
    @include('components.lahan.tabelLahan')
@endsection