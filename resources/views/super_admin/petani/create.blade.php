@extends('layouts.dashboard')

@section('title', 'Tambah Data Petani')
@section('header', 'Dashboard Super Admin')

@section('content')
    {{-- Panggil komponen formulir tambah --}}
    @include('components.petani.formTambahPetani')
@endsection
