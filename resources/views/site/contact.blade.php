@extends('layouts.store')

@section('title', 'Contato - Casa dos Motores')

@section('content')
<section class="store-page-hero py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="grid gap-8 lg:grid-cols-[1fr_0.95fr] lg:items-center">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-orange-400">Contato</p>
                <h1 class="display-font mt-3 text-5xl leading-none text-white lg:text-7xl">Fale com a Casa dos Motores</h1>
                <p class="mt-5 max-w-3xl text-lg leading-8 text-zinc-300">
                    Para orçamento, disponibilidade de produtos, assistência técnica ou orientação, o caminho mais rápido é falar diretamente com a loja.
                </p>
            </div>

            <div class="store-page-hero__card rounded-[2rem] p-7 text-zinc-100">
                <h2 class="display-font text-4xl leading-none text-white">Canais principais</h2>
                <div class="mt-6 space-y-4">
                    <a href="https://wa.me/556899537519" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between rounded-2xl bg-white/5 px-5 py-4 transition hover:bg-white/10">
                        <span class="flex items-center gap-3"><i class="fab fa-whatsapp text-lg text-green-400"></i>WhatsApp</span>
                        <strong>+55 68 9953-7519</strong>
                    </a>
                    <a href="tel:+556899537519" class="flex items-center justify-between rounded-2xl bg-white/5 px-5 py-4 transition hover:bg-white/10">
                        <span class="flex items-center gap-3"><i class="fas fa-phone text-lg text-orange-300"></i>Telefone</span>
                        <strong>+55 68 9953-7519</strong>
                    </a>
                    <a href="https://www.instagram.com/casadosmotoresac/" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between rounded-2xl bg-white/5 px-5 py-4 transition hover:bg-white/10">
                        <span class="flex items-center gap-3"><i class="fab fa-instagram text-lg text-pink-300"></i>Instagram</span>
                        <strong>@casadosmotoresac</strong>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="grid gap-6 lg:grid-cols-3">
            <article class="store-info-card rounded-[1.5rem] p-6">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-100 text-orange-600">
                    <i class="fas fa-location-dot text-xl"></i>
                </div>
                <h2 class="mt-5 text-2xl font-semibold text-zinc-900">Endereço</h2>
                <p class="mt-3 text-sm leading-7 text-zinc-600">
                    Rua 6 de Agosto, Bairro 6 de Agosto<br>
                    Rio Branco - AC
                </p>
                <a href="https://www.google.com/maps/search/?api=1&query=Rua+6+de+Agosto+Bairro+6+de+Agosto+Rio+Branco+AC" target="_blank" rel="noopener noreferrer" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-orange-600 hover:text-orange-700">
                    Abrir no mapa
                    <i class="fas fa-arrow-up-right-from-square"></i>
                </a>
            </article>

            <article class="store-info-card rounded-[1.5rem] p-6">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-100 text-orange-600">
                    <i class="far fa-clock text-xl"></i>
                </div>
                <h2 class="mt-5 text-2xl font-semibold text-zinc-900">Horário</h2>
                <p class="mt-3 text-sm leading-7 text-zinc-600">
                    Segunda a sexta<br>
                    07h às 17h
                </p>
                <p class="mt-3 text-sm leading-7 text-zinc-600">
                    Sábado<br>
                    07h às 11h30
                </p>
            </article>

            <article class="store-info-card rounded-[1.5rem] p-6">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-100 text-orange-600">
                    <i class="fas fa-store text-xl"></i>
                </div>
                <h2 class="mt-5 text-2xl font-semibold text-zinc-900">Atendimento</h2>
                <p class="mt-3 text-sm leading-7 text-zinc-600">
                    Loja e assistência técnica para vendas, orçamento e suporte em equipamentos e peças.
                </p>
            </article>
        </div>
    </div>
</section>

<section class="bg-stone-50 py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-orange-600">Contato rápido</p>
                <h2 class="display-font mt-3 text-5xl leading-none text-zinc-900">Escolha o melhor canal para falar com a equipe</h2>
                <p class="mt-4 max-w-3xl text-lg leading-8 text-zinc-600">
                    O WhatsApp é ideal para agilizar dúvidas, orçamento e disponibilidade. O Instagram ajuda a acompanhar novidades da loja.
                </p>
            </div>

            <div class="flex flex-col gap-3">
                <a href="https://wa.me/556899537519" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl bg-green-500 px-6 py-3 text-sm font-semibold text-white transition hover:bg-green-600">
                    <i class="fab fa-whatsapp"></i>
                    Falar no WhatsApp
                </a>
                <a href="https://www.instagram.com/casadosmotoresac/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl border border-zinc-300 bg-white px-6 py-3 text-sm font-semibold text-zinc-800 transition hover:border-orange-400 hover:text-orange-600">
                    <i class="fab fa-instagram"></i>
                    Abrir Instagram
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
