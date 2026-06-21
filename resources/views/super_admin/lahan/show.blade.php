@extends('layouts.dashboard') 

@section('title', 'Detail Lahan - Super Admin')

@section('content')
    {{-- Memanggil komponen detail lahan --}}
    @include('components.lahan.detail-lahan')
@endsection