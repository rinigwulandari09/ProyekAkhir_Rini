@extends('layouts.admin')

@section('title', 'Tambah Data Petani')
@section('header', 'Dashboard Admin')

@section('content')
    {{-- Panggil komponen formulir tambah --}}
    @include('components.petani.formTambahPetani')
@endsection
