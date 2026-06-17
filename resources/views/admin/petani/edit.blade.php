@extends('layouts.admin')

@section('title', 'Edit Status & Detail Petani')
@section('header', 'Dashboard Admin')

@section('content')
    {{-- Panggil komponen formulir edit bersama --}}
    @include('components.petani.formEditPetani')
@endsection