@extends('layouts.store')

@section('title', 'Início - Casa dos Motores')

@section('content')
@php
    $heroBanners = $homeBanners->isNotEmpty()
        ? $homeBanners
        : collect([
            (object) [
                'name' => 'Banner padrao',
                'badge' => null,
                'title' => 'Soluções avançadas em motores',
                'subtitle' => 'Venda de equipamentos para agro, jardim, oficina e manutenção com assistência técnica especializada.',
                'description' => 'Atendimento comercial e tecnico em um so lugar, com venda pronta entrega e sob encomenda para prazos maiores quando necessario.',
                'primary_button_text' => 'Ver catálogo de produtos',
                'primary_button_url' => route('site.products'),
                'secondary_button_text' => null,
                'secondary_button_url' => null,
                'image_path' => null,
                'image_alt' => 'Equipamentos da Casa dos Motores',
                'sort_order' => 0,
                'is_active' => true,
                'is_fallback' => true,
            ],
        ]);
@endphp

<section class="home-commerce">
    <div class="home-commerce__shell">
        <div
            class="home-hero"
            x-data="{
                active: 0,
                total: {{ $heroBanners->count() }},
                timer: null,
                init() {
                    if (this.total > 1) {
                        this.start();
                        this.$el.addEventListener('mouseenter', () => this.stop());
                        this.$el.addEventListener('mouseleave', () => this.start());
                    }
                },
                start() {
                    this.stop();
                    this.timer = window.setInterval(() => this.next(), 6500);
                },
                stop() {
                    if (this.timer) {
                        window.clearInterval(this.timer);
                        this.timer = null;
                    }
                },
                next() {
                    this.active = (this.active + 1) % this.total;
                },
                prev() {
                    this.active = (this.active - 1 + this.total) % this.total;
                }
            }">
            <div class="home-hero__viewport">
                @foreach ($heroBanners as $index => $banner)
                    @php
                        $isFallback = (bool) ($banner->is_fallback ?? false);
                        $title = $banner->title ?: ($isFallback ? 'Soluções avançadas em motores' : '');
                        $subtitle = $banner->subtitle ?: ($isFallback ? 'Venda de equipamentos para agro, jardim, oficina e manutenção com assistência técnica especializada.' : ($banner->description ?: null));
                    @endphp

                    <article
                        class="home-hero-slide"
                        x-show="active === {{ $index }}"
                        x-cloak
                        x-transition.opacity.duration.500ms>
                        <div class="home-hero-slide__media">
                            <img
                                src="{{ $banner->image_path ? asset('storage/' . $banner->image_path) : asset('img/capa.png') }}"
                                alt="{{ $banner->image_alt ?: $banner->title }}">
                        </div>
                        <div class="home-hero-slide__overlay"></div>

                        <div class="home-hero-slide__container">
                            
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($heroBanners->count() > 1)
                <button type="button" class="home-hero__nav home-hero__nav--prev" @click="prev()" aria-label="Banner anterior">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button type="button" class="home-hero__nav home-hero__nav--next" @click="next()" aria-label="Próximo banner">
                    <i class="fas fa-chevron-right"></i>
                </button>

                <div class="home-hero__dots">
                    @foreach ($heroBanners as $index => $banner)
                        <button
                            type="button"
                            class="home-hero__dot"
                            :class="{ 'is-active': active === {{ $index }} }"
                            @click="active = {{ $index }}"
                            aria-label="Ir para o banner {{ $index + 1 }}">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="home-trustbar">
        <div class="home-trustbar__item">
            <i class="fas fa-credit-card"></i>
            <div>
                <strong>Compra com orientacao</strong>
                <span>Atendimento consultivo para vender certo</span>
            </div>
        </div>
        <div class="home-trustbar__item">
            <i class="fas fa-box-open"></i>
            <div>
                <strong>Estoque e sob encomenda</strong>
                <span>Venda imediata ou com prazo maior quando precisar</span>
            </div>
        </div>
        <div class="home-trustbar__item">
            <i class="fas fa-screwdriver-wrench"></i>
            <div>
                <strong>Assistencia tecnica</strong>
                <span>Diagnostico, revisao e manutencao especializada</span>
            </div>
        </div>
        <div class="home-trustbar__item">
            <i class="fas fa-location-dot"></i>
            <div>
                <strong>Rio Branco - AC</strong>
                <span>Rua 6 de Agosto, Bairro 6 de Agosto</span>
            </div>
        </div>
    </div>
</section>

<section class="brand-ribbon overflow-hidden">
    <div class="brand-ribbon__track">
        @foreach (['bufalo', 'guarany', 'oregon', 'toyama', 'vonder', 'vulcan', 'zm', 'branco', 'bufalo', 'guarany', 'oregon', 'toyama', 'vonder', 'vulcan', 'zm', 'branco'] as $brand)
            <img src="{{ asset('img/logos/' . $brand . '.png') }}" alt="{{ ucfirst($brand) }}">
        @endforeach
    </div>
</section>

<section class="bg-stone-50 py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-2xl">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-orange-600">Destaques da Loja</p>
                <h2 class="display-font mt-3 text-5xl leading-none text-zinc-900">Produtos para manter sua rotina em movimento</h2>
            </div>
            <a href="{{ route('site.products') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-zinc-700 hover:text-orange-600">
                Ver catálogo completo
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        @if ($featuredProducts->isNotEmpty())
            <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($featuredProducts as $product)
                    <article class="overflow-hidden rounded-[1.5rem] border border-zinc-200 bg-white shadow-[0_20px_50px_rgba(17,24,39,0.06)] transition hover:-translate-y-1 hover:shadow-[0_24px_60px_rgba(17,24,39,0.12)]">
                        <a href="{{ route('site.products.show', $product->slug) }}" class="block">
                            <div class="aspect-[4/3] bg-zinc-100">
                                @if ($product->images->first())
                                    <img
                                        src="{{ asset('storage/' . $product->images->first()->path) }}"
                                        alt="{{ $product->name }}"
                                        class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full items-center justify-center text-sm font-semibold text-zinc-400">
                                        Sem imagem
                                    </div>
                                @endif
                            </div>
                        </a>

                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-orange-600">
                                        {{ $product->category?->name ?? 'Equipamento' }}
                                    </p>
                                    <h3 class="mt-2 text-lg font-semibold text-zinc-900">
                                        <a href="{{ route('site.products.show', $product->slug) }}">{{ $product->name }}</a>
                                    </h3>
                                </div>
                                @if ($product->allow_out_of_stock_sales && $product->stock <= 0)
                                    <span class="rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.15em] text-amber-700">Sob encomenda</span>
                                @endif
                            </div>

                            <p class="mt-3 min-h-[3rem] text-sm leading-6 text-zinc-600">
                                {{ \Illuminate\Support\Str::limit($product->description ?? 'Equipamento com suporte técnico e atendimento especializado.', 95) }}
                            </p>

                            <div class="mt-5 flex items-end justify-between gap-4">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.18em] text-zinc-400">Preço</p>
                                    <p class="text-2xl font-bold text-zinc-900">R$ {{ number_format($product->effective_price, 2, ',', '.') }}</p>
                                </div>
                                <a href="{{ route('site.products.show', $product->slug) }}" class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-orange-600">
                                    Ver produto
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="mt-10 rounded-[2rem] border border-dashed border-zinc-300 bg-white px-8 py-14 text-center">
                <p class="text-lg font-semibold text-zinc-900">Seu catálogo vai aparecer aqui assim que os produtos forem cadastrados.</p>
                <p class="mt-2 text-zinc-500">Enquanto isso, você pode divulgar os serviços e os canais de atendimento da loja.</p>
            </div>
        @endif
    </div>
</section>

<section class="bg-white py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr] lg:items-start">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-orange-600">Serviços</p>
                <h2 class="display-font mt-3 text-5xl leading-none text-zinc-900">Apoio técnico para quem não pode parar</h2>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-zinc-600">
                    Atendimento focado em solução prática para motores, bombas, equipamentos e peças, com suporte para manutenção,
                    revisão e orientação técnica.
                </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                @foreach ($serviceHighlights as $service)
                    <article class="store-info-card rounded-[1.5rem] p-5">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-100 text-orange-600">
                                <i class="fas {{ $service['icon'] }} text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-zinc-900">{{ $service['title'] }}</h3>
                                <p class="mt-2 text-sm leading-6 text-zinc-600">{{ $service['description'] }}</p>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        <div class="mt-8">
            <a href="{{ route('site.services') }}" class="inline-flex items-center gap-2 rounded-xl bg-orange-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-orange-700">
                Ver todos os serviços
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<section class="bg-zinc-950 py-16 text-white lg:py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="grid gap-8 lg:grid-cols-[0.95fr_1.05fr] lg:items-center">
            <div class="rounded-[2rem] border border-white/10 bg-white/5 p-8">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-orange-400">Sobre a Loja</p>
                <h2 class="display-font mt-3 text-5xl leading-none">Negócio local com atendimento técnico de verdade</h2>
                <p class="mt-5 text-base leading-8 text-zinc-300">
                    A Casa dos Motores atende Rio Branco e região com uma proposta simples: unir loja, peças e assistência técnica em um só lugar,
                    facilitando a vida de quem precisa comprar certo e resolver rápido.
                </p>
                <a href="{{ route('site.about') }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-orange-400 hover:text-orange-300">
                    Conhecer a empresa
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-6">
                    <p class="display-font text-4xl text-orange-400">01</p>
                    <h3 class="mt-4 text-lg font-semibold">Loja + oficina</h3>
                    <p class="mt-2 text-sm leading-6 text-zinc-300">Compra e suporte técnico no mesmo endereço.</p>
                </div>
                <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-6">
                    <p class="display-font text-4xl text-orange-400">02</p>
                    <h3 class="mt-4 text-lg font-semibold">Atendimento local</h3>
                    <p class="mt-2 text-sm leading-6 text-zinc-300">Presença em Rio Branco com contato rápido via WhatsApp.</p>
                </div>
                <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-6">
                    <p class="display-font text-4xl text-orange-400">03</p>
                    <h3 class="mt-4 text-lg font-semibold">Marcas reconhecidas</h3>
                    <p class="mt-2 text-sm leading-6 text-zinc-300">Catálogo alinhado com marcas conhecidas do segmento.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-orange-50 py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="grid gap-6 lg:grid-cols-[1fr_auto] lg:items-center">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-orange-600">Contato</p>
                <h2 class="display-font mt-3 text-5xl leading-none text-zinc-900">Precisando de orçamento, compra ou assistência?</h2>
                <p class="mt-4 max-w-3xl text-lg leading-8 text-zinc-600">
                    Chame no WhatsApp, acompanhe as novidades no Instagram ou venha até a loja.
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                <a href="https://wa.me/556899537519" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl bg-green-500 px-6 py-3 text-sm font-semibold text-white transition hover:bg-green-600">
                    <i class="fab fa-whatsapp"></i>
                    Falar no WhatsApp
                </a>
                <a href="https://www.instagram.com/casadosmotoresac/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl border border-zinc-300 bg-white px-6 py-3 text-sm font-semibold text-zinc-800 transition hover:border-orange-400 hover:text-orange-600">
                    <i class="fab fa-instagram"></i>
                    Ver Instagram
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
