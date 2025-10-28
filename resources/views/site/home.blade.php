@extends('layouts.store')

@section('title', 'Início - Casa dos Motores')

@section('content')

<section class="relative w-full overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center"
         style="background-image: url('{{ asset('img/capa.png') }}');">
    </div>

    <div class="absolute inset-0 bg-black bg-opacity-60"></div>

    <div class="relative z-10 flex flex-col justify-center items-start text-white min-h-[550px] px-6 md:px-20">
        <div class="max-w-3xl">
            <h1 class="font-heading text-5xl md:text-7xl text-primary-orange uppercase mb-4 leading-none tracking-tight drop-shadow-lg">
                O Poder que Você Precisa
            </h1>
            <p class="text-lg md:text-2xl text-gray-200 mb-8 font-sans drop-shadow-md">
                Soluções completas em motores e equipamentos para agro, construção e jardim. Qualidade e força garantidas.
            </p>
            <a href="{{ url('/produtos') }}"
               class="bg-primary-orange text-white px-8 py-3 rounded-lg font-bold uppercase text-lg shadow-lg hover:bg-orange-700 transition duration-300 transform hover:scale-[1.02] font-sans">
                Explorar Catálogo
            </a>
        </div>
    </div>

<div class="absolute bottom-0 left-0 w-full bg-white/10 backdrop-blur-sm py-4 shadow-inner overflow-hidden border-b border-orange-600">
    <div class="flex gap-16 animate-scroll px-8 items-center">
        <img src="{{ asset('img/logos/branco.png') }}" class="h-10 opacity-90 hover:opacity-100 transition" alt="Branco">
        <img src="{{ asset('img/logos/bufalo.png') }}" class="h-10 opacity-90 hover:opacity-100 transition" alt="Búfalo">
        <img src="{{ asset('img/logos/guarany.png') }}" class="h-10 opacity-90 hover:opacity-100 transition" alt="Guarany">
        <img src="{{ asset('img/logos/oregon.png') }}" class="h-10 opacity-90 hover:opacity-100 transition" alt="Oregon">
        <img src="{{ asset('img/logos/toyama.png') }}" class="h-10 opacity-90 hover:opacity-100 transition" alt="Toyama">
        <img src="{{ asset('img/logos/vonder.png') }}" class="h-10 opacity-90 hover:opacity-100 transition" alt="Vonder">
        <img src="{{ asset('img/logos/vulcan.png') }}" class="h-10 opacity-90 hover:opacity-100 transition" alt="Vulcan">
        <img src="{{ asset('img/logos/zm.png') }}" class="h-10 opacity-90 hover:opacity-100 transition" alt="ZM">

        <img src="{{ asset('img/logos/branco.png') }}" class="h-10 opacity-90" alt="Branco">
        <img src="{{ asset('img/logos/bufalo.png') }}" class="h-10 opacity-90" alt="Búfalo">
        <img src="{{ asset('img/logos/guarany.png') }}" class="h-10 opacity-90" alt="Guarany">
        <img src="{{ asset('img/logos/oregon.png') }}" class="h-10 opacity-90" alt="Oregon">
        <img src="{{ asset('img/logos/toyama.png') }}" class="h-10 opacity-90" alt="Toyama">
        <img src="{{ asset('img/logos/vonder.png') }}" class="h-10 opacity-90" alt="Vonder">
        <img src="{{ asset('img/logos/vulcan.png') }}" class="h-10 opacity-90" alt="Vulcan">
        <img src="{{ asset('img/logos/zm.png') }}" class="h-10 opacity-90" alt="ZM">
    </div>
</div>

</section>


@endsection