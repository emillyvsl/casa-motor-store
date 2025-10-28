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
            <h1 class="font-heading text-5xl md:text-7xl text-orange-600 uppercase mb-4 leading-none tracking-tight drop-shadow-lg">
                Potência que Move Seu Trabalho
            </h1>
            <p class="text-lg md:text-2xl text-gray-200 mb-8 font-sans drop-shadow-md">
                Soluções completas em motores e equipamentos para agro ,jardim e assistência técnica. Qualidade e força garantidas.
            </p>
            <a href="{{ url('/produtos') }}"
               class="bg-orange-600 text-white px-8 py-3 rounded-lg font-bold uppercase text-lg shadow-lg hover:bg-orange-700 transition duration-300 transform hover:scale-[1.02] font-sans">
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


<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-10">
            <h2 class="text-4xl md:text-5xl font-bold mb-4 text-gray-900">
                PRODUTOS EM DESTAQUE
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Encontre o motor ou equipamento perfeito, filtrando por área de aplicação.
            </p>
        </div>

        <div class="flex flex-wrap justify-center gap-4 mb-12 font-semibold">
            <button class="bg-orange-600 text-white px-6 py-2 rounded-full shadow-lg hover:bg-orange-700 transition">
                Todos
            </button>
            <button class="bg-white text-gray-800 px-6 py-2 rounded-full border border-gray-300 hover:bg-gray-100 transition">
                Agrícola
            </button>
            <button class="bg-white text-gray-800 px-6 py-2 rounded-full border border-gray-300 hover:bg-gray-100 transition">
                Jardim
            </button>
            <button class="bg-white text-gray-800 px-6 py-2 rounded-full border border-gray-300 hover:bg-gray-100 transition">
                Construção
            </button>
            <button class="bg-white text-gray-800 px-6 py-2 rounded-full border border-gray-300 hover:bg-gray-100 transition">
                Peças & Acessórios
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200">
                <div class="h-48 bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                    [IMAGEM DO PRODUTO AQUI]
                </div>
                <div class="p-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Motor Estacionário</h3>
                    <p class="text-sm text-gray-600 mb-3">Categoria: Agrícola</p>
                    <div class="text-2xl font-bold text-orange-600">R$ 1.099,00</div>
                    <button class="mt-4 w-full bg-gray-900 text-white py-2 rounded-lg font-semibold hover:bg-orange-600 transition">
                        Ver Detalhes
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200">
                <div class="h-48 bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                    [IMAGEM DO PRODUTO AQUI]
                </div>
                <div class="p-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Motobomba BD716XS</h3>
                    <p class="text-sm text-gray-600 mb-3">Categoria: Construção</p>
                    <div class="text-2xl font-bold text-orange-600">R$ 2.499,00</div>
                    <button class="mt-4 w-full bg-gray-900 text-white py-2 rounded-lg font-semibold hover:bg-orange-600 transition">
                        Ver Detalhes
                    </button>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200">
                <div class="h-48 bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                    [IMAGEM DO PRODUTO AQUI]
                </div>
                <div class="p-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Roçadeira BR52</h3>
                    <p class="text-sm text-gray-600 mb-3">Categoria: Jardim</p>
                    <div class="text-2xl font-bold text-orange-600">R$ 950,00</div>
                    <button class="mt-4 w-full bg-gray-900 text-white py-2 rounded-lg font-semibold hover:bg-orange-600 transition">
                        Ver Detalhes
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200">
                <div class="h-48 bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                    [IMAGEM DO PRODUTO AQUI]
                </div>
                <div class="p-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Bico Injetor Diesel</h3>
                    <p class="text-sm text-gray-600 mb-3">Categoria: Peças</p>
                    <div class="text-2xl font-bold text-orange-600">R$ 150,00</div>
                    <button class="mt-4 w-full bg-gray-900 text-white py-2 rounded-lg font-semibold hover:bg-orange-600 transition">
                        Ver Detalhes
                    </button>
                </div>
            </div>

        </div>
        
        <div class="text-center">
            <a href="{{ url('/produtos') }}" 
               class="inline-block bg-gray-900 text-white px-8 py-3 rounded-lg font-bold text-lg hover:bg-orange-600 transition duration-300">
                VER CATÁLOGO COMPLETO
            </a>
        </div>
    </div>
</section>


<section class="py-16 bg-gray-900 text-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-bold mb-4 text-orange-600">
                NOSSA OFICINA ESPECIALIZADA
            </h2>
            <p class="text-xl text-gray-400 max-w-3xl mx-auto">
                Mantenha seu equipamento funcionando com a máxima eficiência.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="bg-gray-800 rounded-xl p-6 shadow-xl border-t-4 border-orange-600 hover:bg-gray-700 transition duration-300">
                <div class="text-orange-600 text-4xl mb-4">
                    <i class="fas fa-wrench"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2">Manutenção e Reparo</h3>
                <p class="text-gray-400">
                    Diagnóstico preciso e reparo completo de motores, geradores e motobombas, utilizando apenas peças originais.
                </p>
            </div>

            <div class="bg-gray-800 rounded-xl p-6 shadow-xl border-t-4 border-orange-600 hover:bg-gray-700 transition duration-300">
                <div class="text-orange-600 text-4xl mb-4">
                    <i class="fas fa-tools"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2">Revisão Preventiva</h3>
                <p class="text-gray-400">
                    Evite falhas! Agende uma revisão preventiva para garantir a longevidade e o melhor desempenho do seu equipamento.
                </p>
            </div>

            <div class="bg-gray-800 rounded-xl p-6 shadow-xl border-t-4 border-orange-600 hover:bg-gray-700 transition duration-300">
                <div class="text-orange-600 text-4xl mb-4">
                    <i class="fas fa-cogs"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2">Instalação e Montagem</h3>
                <p class="text-gray-400">
                    Serviço de montagem e instalação profissional de equipamentos grandes e sistemas complexos.
                </p>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ url('/servicos') }}" 
               class="inline-block border-2 border-orange-600 text-orange-600 px-8 py-3 rounded-lg font-bold text-lg hover:bg-orange-600 hover:text-white transition duration-300">
                SOLICITAR SERVIÇO
            </a>
        </div>
    </div>
</section>

<section class="py-12 bg-gray-50 text-gray-900">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold mb-4">Ficou com Dúvidas?</h2>
        <p class="text-lg text-gray-600 mb-6 max-w-2xl mx-auto">
            Nossa equipe técnica está à disposição para te auxiliar.
        </p>
        <a href="https://wa.me/551112345678" class="bg-green-500 text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-green-600 transition duration-300 shadow-md">
            <i class="fab fa-whatsapp mr-2"></i> Chamar no WhatsApp
        </a>
    </div>
</section>

@endsection

<style>
.animate-scroll {
    animation: scroll 30s linear infinite;
    width: 200%; 
}

@keyframes scroll {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}
</style>