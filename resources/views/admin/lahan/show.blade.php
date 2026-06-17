@extends('layouts.admin') 

@section('title', 'Detail Lahan - Admin')

@section('content')
    {{-- Memanggil komponen detail lahan --}}
    @include('components.lahan.detail-lahan')
@endsection