@extends('layouts.app')

@section('title', 'Bolsa de Trabajo')

@section('content')

<main class="bg-[#fef0f5] py-12">
    <div class="container mx-auto px-6 max-w-5xl text-gray-700">

        <h1 class="text-3xl md:text-4xl font-semibold text-[#ff6392] text-center mb-6">
            Bolsa de Trabajo
        </h1>

        <div class="mt-12 flex justify-center">
            <img src="{{ asset('img/bolsa-de-trabajo.png') }}" 
                 alt="Bolsa de trabajo Gonvill" 
                 class="rounded-2xl shadow-lg w-full border-4 border-[#ffa3c2]">
        </div>

    </div>
</main>

@endsection
