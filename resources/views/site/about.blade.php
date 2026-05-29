@extends('layouts.store')

@section('title', 'Sobre - Casa dos Motores')

@section('content')
<section class="store-page-hero py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="grid gap-8 lg:grid-cols-[1fr_0.9fr] lg:items-center">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-orange-400">Sobre a Casa dos Motores</p>
                <h1 class="display-font mt-3 text-5xl leading-none text-white lg:text-7xl">Loja e assistência técnica para quem precisa resolver com segurança</h1>
                <p class="mt-5 max-w-3xl text-lg leading-8 text-zinc-300">
                    A Casa dos Motores atua em Rio Branco com foco em atendimento próximo, solução prática e suporte técnico
                    para motores, equipamentos, peças e acessórios.
                </p>
            </div>

            <div class="store-page-hero__card rounded-[2rem] p-7 text-zinc-100">
                <h2 class="display-font text-4xl leading-none text-white">Nossa base</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl bg-white/5 p-4">
                        <p class="text-xs uppercase tracking-[0.22em] text-orange-300">Cidade</p>
                        <p class="mt-2 text-lg font-semibold text-white">Rio Branco - AC</p>
                    </div>
                    <div class="rounded-2xl bg-white/5 p-4">
                        <p class="text-xs uppercase tracking-[0.22em] text-orange-300">Atuação</p>
                        <p class="mt-2 text-lg font-semibold text-white">Loja e oficina</p>
                    </div>
                    <div class="rounded-2xl bg-white/5 p-4">
                        <p class="text-xs uppercase tracking-[0.22em] text-orange-300">Contato</p>
                        <p class="mt-2 text-lg font-semibold text-white">+55 68 9953-7519</p>
                    </div>
                    <div class="rounded-2xl bg-white/5 p-4">
                        <p class="text-xs uppercase tracking-[0.22em] text-orange-300">Instagram</p>
                        <p class="mt-2 text-lg font-semibold text-white">@casadosmotoresac</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="grid gap-8 lg:grid-cols-[1fr_1.1fr] lg:items-start">
            <div class="store-info-card rounded-[2rem] p-7">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-orange-600">Quem somos</p>
                <h2 class="display-font mt-3 text-5xl leading-none text-zinc-900">Atendimento pensado para quem trabalha com equipamento de verdade</h2>
            </div>

            <div class="space-y-5 text-base leading-8 text-zinc-600">
                <p>
                    A Casa dos Motores reúne venda e assistência técnica para facilitar a rotina de produtores, profissionais,
                    prestadores de serviço e clientes que dependem do equipamento funcionando bem.
                </p>
                <p>
                    Nosso diferencial está no contato direto, na orientação técnica antes da compra e no acompanhamento quando
                    o cliente precisa de manutenção, troca de peça ou revisão.
                </p>
                <p>
                    Mais do que vender produtos, a proposta da loja é ajudar a manter a operação ativa com soluções objetivas,
                    atendimento próximo e comunicação rápida.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="bg-stone-50 py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="grid gap-6 md:grid-cols-3">
            @foreach ([
                ['title' => 'Atendimento técnico', 'description' => 'Apoio para compra, diagnóstico e orientação conforme a necessidade do cliente.'],
                ['title' => 'Solução local', 'description' => 'Presença física em Rio Branco com WhatsApp ativo para agilizar o contato.'],
                ['title' => 'Loja + assistência', 'description' => 'Integração entre produtos, peças e serviço técnico no mesmo negócio.'],
            ] as $pillar)
                <article class="store-info-card rounded-[1.5rem] p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-orange-600">Compromisso</p>
                    <h2 class="mt-4 text-2xl font-semibold text-zinc-900">{{ $pillar['title'] }}</h2>
                    <p class="mt-3 text-sm leading-7 text-zinc-600">{{ $pillar['description'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-zinc-950 py-16 text-white lg:py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-orange-400">Visite a Loja</p>
                <h2 class="display-font mt-3 text-5xl leading-none">Rua 6 de Agosto, Bairro 6 de Agosto, Rio Branco - AC</h2>
                <p class="mt-5 max-w-3xl text-base leading-8 text-zinc-300">
                    Atendimento de segunda a sexta das 07h às 17h e aos sábados das 07h às 11h30.
                </p>
            </div>

            <a href="{{ route('site.contact') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-orange-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-orange-700">
                <i class="fas fa-location-dot"></i>
                Ver contato completo
            </a>
        </div>
    </div>
</section>
@endsection
