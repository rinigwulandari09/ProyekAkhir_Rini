@extends('layouts.admin')

@section('title', 'Daftar Petani - Admin')

@section('content')
    {{-- Panggil dari folder components yang sama --}}
    @include('components.petani.tabelPetani')
@endsection