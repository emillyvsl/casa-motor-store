@extends('layouts.store')

@section('title', 'Carrinho - Casa dos Motores')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-sm text-gray-500">Resumo do seu pedido</p>
                <h1 class="text-3xl font-bold text-gray-900">Carrinho</h1>
            </div>
            <a href="{{ route('site.products') }}" class="text-orange-600 hover:text-orange-700 text-sm font-semibold">Continuar comprando</a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 text-green-800 px-4 py-3">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-50 text-red-800 px-4 py-3">{{ session('error') }}</div>
        @endif

        @if ($cart->items->isEmpty())
            <div class="bg-white border border-dashed border-gray-300 rounded-2xl p-10 text-center">
                <p class="text-lg font-semibold text-gray-800">Seu carrinho está vazio.</p>
                <p class="text-gray-500 mt-2">Adicione produtos do catálogo para continuar.</p>
                <a href="{{ route('site.products') }}" class="mt-6 inline-flex items-center px-6 py-3 bg-orange-600 text-white font-semibold rounded-xl hover:bg-orange-700 transition">Ver produtos</a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-4">
                    @foreach ($cart->items as $item)
                        <div class="bg-white border border-gray-200 rounded-2xl p-4 flex flex-col sm:flex-row gap-4 shadow-sm">
                            <div class="w-24 h-24 flex-shrink-0 bg-gray-100 rounded-xl overflow-hidden">
                                @if($item->product?->images->first())
                                    <img src="{{ asset('storage/'.$item->product->images->first()->path) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">Sem foto</div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm text-gray-500">{{ $item->product->category->name ?? 'Categoria' }}</p>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $item->product->name }}</h3>
                                    </div>
                                    <form action="{{ route('site.cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                        <button type="submit" class="text-gray-400 hover:text-red-600" title="Remover">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </form>
                                </div>

                                <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $item->product->description }}</p>

                                @if($item->product && $item->product->backorderedQuantityFor($item->quantity) > 0)
                                    <p class="mt-2 text-sm text-orange-600">
                                        Sob encomenda: {{ $item->product->backorderedQuantityFor($item->quantity) }} un.
                                        Prazo adicional de até {{ $item->product->leadTimeDaysFor($item->quantity) }} dias úteis.
                                    </p>
                                @endif

                                <div class="mt-3 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('site.cart.update') }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                                            <input name="quantity" type="number" min="1" value="{{ $item->quantity }}" class="w-20 rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500" />
                                            <button type="submit" class="text-sm font-semibold text-orange-600 hover:text-orange-700">Atualizar</button>
                                        </form>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">Preço unitário</p>
                                        <p class="text-lg font-bold text-gray-900">R$ {{ number_format($item->unit_price, 2, ',', '.') }}</p>
                                        <p class="text-sm text-gray-500">Subtotal: R$ {{ number_format($item->subtotal, 2, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-4">
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumo</h3>
                        @php $subtotal = $cart->items->sum('subtotal'); @endphp
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Subtotal</span>
                            <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Frete</span>
                            <span id="frete-valor">
                                @if($cart->shipping_service)
                                    R$ {{ number_format($cart->shipping_cost, 2, ',', '.') }}
                                @else
                                    Calcular
                                @endif
                            </span>
                        </div>
                        @if($cart->shipping_service)
                            <p class="text-xs text-gray-500" id="frete-servico">
                                {{ $cart->shipping_service }}
                                @if($cart->shipping_delivery_days)
                                    · {{ $cart->shipping_delivery_days }} dia(s) útil(eis)
                                @endif
                            </p>
                        @else
                            <p class="text-xs text-gray-500 hidden" id="frete-servico"></p>
                        @endif
                        <div class="border-t border-gray-200 pt-3 mt-3 flex justify-between text-base font-semibold text-gray-900">
                            <span>Total</span>
                            <span id="total-geral">R$ {{ number_format($subtotal + (float) $cart->shipping_cost, 2, ',', '.') }}</span>
                        </div>

                        @auth('customer')
                          <a href="{{ route('customer.checkout') }}"
                            class="mt-4 w-full block text-center bg-orange-600 text-white font-bold py-3 rounded-xl hover:bg-orange-700 transition">
                            Finalizar compra →
                          </a>
                        @else
                          <a href="{{ route('customer.login') }}?redirect={{ urlencode(route('customer.checkout')) }}"
                            class="mt-4 w-full block text-center bg-orange-600 text-white font-bold py-3 rounded-xl hover:bg-orange-700 transition">
                            Entrar para finalizar →
                          </a>
                        @endauth
                    </div>

                    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Calcular frete</h3>
                        <div class="flex gap-2">
                            <input id="cep" type="text" maxlength="9" placeholder="Digite seu CEP"
                                value="{{ $cart->shipping_cep_destino }}"
                                class="flex-1 rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500">
                            <button id="btn-frete" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-orange-700 transition">Calcular</button>
                        </div>
                        <div id="frete-opcoes" class="mt-4 space-y-2 text-sm"></div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const btnFrete = document.getElementById('btn-frete');
    if (!btnFrete) return;

    btnFrete.addEventListener('click', async () => {
        const cep = document.getElementById('cep').value.trim();
        const box = document.getElementById('frete-opcoes');
        const freteValor = document.getElementById('frete-valor');
        const freteServico = document.getElementById('frete-servico');
        const totalGeral = document.getElementById('total-geral');
        const subtotal = {{ $cart->items->sum('subtotal') }};

        if (!cep) {
            box.innerHTML = '<p class="text-red-600">Informe um CEP válido.</p>';
            return;
        }

        box.innerHTML = '<p class="text-gray-500">Calculando...</p>';

        try {
            const res = await fetch('{{ route('api.shipping.quote') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    cep_destino: cep,
                    products: [
                        @foreach ($cart->items as $item)
                        {
                            id: '{{ $item->product->id }}',
                            width: {{ $item->product->width ?? 20 }},
                            height: {{ $item->product->height ?? 20 }},
                            length: {{ $item->product->length ?? 30 }},
                            weight: {{ $item->product->weight ?? 1 }},
                            insurance_value: {{ $item->product->discount_price ?? $item->product->price }},
                            quantity: {{ $item->quantity }}
                        },
                        @endforeach
                    ]
                })
            });

            const data = await res.json();

            if (data?.error) {
                box.innerHTML = `<p class="text-red-600">${data.message || 'Erro ao calcular frete.'}</p>`;
                return;
            }

            if (!Array.isArray(data) || data.length === 0) {
                box.innerHTML = '<p class="text-red-600">Nenhum serviço disponível.</p>';
                return;
            }

            const validos = data.filter(s => !s.error && (s.price !== undefined || s.custom_price !== undefined));
            const erros = data.filter(s => s.error);

            let html = '';
            if (validos.length) {
                html += validos.map(s => `
                    <button class="w-full text-left border rounded-lg p-3 hover:border-orange-500 flex items-center justify-between" data-price="${parseFloat(s.custom_price || s.price || 0)}" data-service="${(s.company?.name || 'Transportadora')} - ${s.name}" data-delivery="${parseInt(s.delivery_time || s.custom_delivery_time || 0, 10) || 0}">
                        <div>
                            <p class="font-semibold text-gray-900">${s.company?.name || 'Transportadora'} - ${s.name}</p>
                            <p class="text-xs text-gray-500">Entrega em ${s.delivery_time || s.custom_delivery_time || '-'} dia(s) útil(eis)</p>
                        </div>
                        <span class="font-semibold text-gray-900">R$ ${(parseFloat(s.custom_price || s.price || 0)).toFixed(2).replace('.', ',')}</span>
                    </button>
                `).join('');
            }

            if (erros.length) {
                html += `<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-yellow-800 mt-2">
                    <p class="font-medium text-sm">Serviços indisponíveis:</p>
                    <ul class="list-disc ml-4 text-xs">
                        ${erros.map(e => `<li>${e.company?.name || 'Transportadora'} - ${e.name || ''}: ${e.error}</li>`).join('')}
                    </ul>
                </div>`;
            }

            box.innerHTML = html || '<p class="text-gray-500">Nenhum frete disponível.</p>';

            box.querySelectorAll('button[data-price]').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const price = parseFloat(btn.getAttribute('data-price')) || 0;
                    const service = btn.getAttribute('data-service') || 'Frete';
                    const deliveryDays = parseInt(btn.getAttribute('data-delivery') || '0', 10) || 0;

                    freteValor.textContent = `R$ ${price.toFixed(2).replace('.', ',')}`;
                    freteServico.textContent = `${service}${deliveryDays ? ` · ${deliveryDays} dia(s) útil(eis)` : ''}`;
                    freteServico.classList.remove('hidden');
                    const total = subtotal + price;
                    totalGeral.textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;

                    try {
                        const saveResponse = await fetch('{{ route('site.cart.shipping') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                service,
                                cost: price,
                                delivery_days: deliveryDays,
                                cep,
                            })
                        });

                        const saveData = await saveResponse.json();

                        if (!saveResponse.ok || !saveData.success) {
                            throw new Error(saveData.message || 'Não foi possível salvar o frete.');
                        }
                    } catch (saveError) {
                        console.error(saveError);
                        box.insertAdjacentHTML('beforeend', '<p class="text-red-600 mt-2">Não foi possível salvar o frete selecionado.</p>');
                    }
                });
            });

        } catch (error) {
            console.error(error);
            box.innerHTML = '<p class="text-red-600">Erro ao calcular frete.</p>';
        }
    });
});
</script>
@endsection
