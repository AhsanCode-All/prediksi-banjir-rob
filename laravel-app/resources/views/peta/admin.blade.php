@extends('layouts.admin')

@section('title', 'Peta Prediksi')

@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-white">
        Peta Prediksi Banjir Rob
    </h2>

    <p class="mt-1 text-sm text-slate-400">
        Visualisasi hasil prediksi pada 31 kelurahan Jakarta Utara.
    </p>
</div>

@include('components.peta-rob')

@endsection