@extends('layouts.store')

@section('title', 'Serviços - Casa dos Motores')

@section('content')
<section class="store-page-hero py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-orange-400">Serviços</p>
                <h1 class="display-font mt-3 text-5xl leading-none text-white lg:text-7xl">Assistência técnica e suporte para sua operação continuar</h1>
                <p class="mt-5 max-w-3xl text-lg leading-8 text-zinc-300">
                    Atendimento para manutenção, revisão, diagnóstico e orientação técnica em equipamentos e motores vendidos na loja
                    ou levados para avaliação.
                </p>
            </div>

            <div class="store-page-hero__card rounded-[2rem] p-7 text-zinc-100">
                <h2 class="display-font text-4xl leading-none text-white">Atendimento local em Rio Branco</h2>
                <ul class="mt-5 space-y-3 text-sm leading-7 text-zinc-300">
                    <li><i class="fas fa-location-dot mr-2 text-orange-400"></i>Rua 6 de Agosto, Bairro 6 de Agosto, Rio Branco - AC</li>
                    <li><i class="far fa-clock mr-2 text-orange-400"></i>Seg a sex das 07h às 17h</li>
                    <li><i class="far fa-clock mr-2 text-orange-400"></i>Sábados das 07h às 11h30</li>
                    <li><i class="fab fa-whatsapp mr-2 text-orange-400"></i>+55 68 9953-7519</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="bg-stone-50 py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['icon' => 'fa-screwdriver-wrench', 'title' => 'Diagnóstico Técnico', 'description' => 'Avaliação inicial para identificar falhas, desgaste e necessidades de manutenção.'],
                ['icon' => 'fa-gears', 'title' => 'Manutenção e Reparo', 'description' => 'Serviços corretivos e preventivos para manter o desempenho e aumentar a vida útil do equipamento.'],
                ['icon' => 'fa-toolbox', 'title' => 'Troca de Peças', 'description' => 'Substituição de componentes e acessórios conforme necessidade técnica do equipamento.'],
                ['icon' => 'fa-store', 'title' => 'Apoio na Compra', 'description' => 'Orientação para escolher motores, peças e equipamentos compatíveis com sua necessidade.'],
            ] as $service)
                <article class="store-info-card rounded-[1.5rem] p-6">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-100 text-orange-600">
                        <i class="fas {{ $service['icon'] }} text-xl"></i>
                    </div>
                    <h2 class="mt-5 text-xl font-semibold text-zinc-900">{{ $service['title'] }}</h2>
                    <p class="mt-3 text-sm leading-7 text-zinc-600">{{ $service['description'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-white py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="grid gap-8 lg:grid-cols-2">
            <div class="store-info-card rounded-[2rem] p-7">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-orange-600">Como funciona</p>
                <h2 class="display-font mt-3 text-5xl leading-none text-zinc-900">Fluxo simples para agilizar seu atendimento</h2>
                <div class="mt-8 space-y-5">
                    @foreach ([
                        '1. Você entra em contato pelo WhatsApp ou visita a loja.',
                        '2. A equipe faz a triagem e orienta sobre compra, avaliação ou manutenção.',
                        '3. O equipamento segue para diagnóstico e definição do serviço necessário.',
                        '4. Após aprovação, o atendimento segue para execução e entrega.',
                    ] as $step)
                        <div class="rounded-2xl border border-zinc-200 bg-zinc-50 px-5 py-4 text-sm font-medium text-zinc-700">
                            {{ $step }}
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-[2rem] bg-zinc-950 p-7 text-white">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-orange-400">Fale com a Loja</p>
                <h2 class="display-font mt-3 text-5xl leading-none">Precisa de ajuda rápida?</h2>
                <p class="mt-5 text-base leading-8 text-zinc-300">
                    O canal mais rápido para orçamento, disponibilidade de peças e alinhamento de atendimento é o WhatsApp.
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="https://wa.me/556899537519" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl bg-green-500 px-6 py-3 text-sm font-semibold text-white transition hover:bg-green-600">
                        <i class="fab fa-whatsapp"></i>
                        Chamar no WhatsApp
                    </a>
                    <a href="{{ route('site.contact') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/15 px-6 py-3 text-sm font-semibold text-white transition hover:border-orange-400 hover:text-orange-300">
                        <i class="fas fa-phone"></i>
                        Ver Contato
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
