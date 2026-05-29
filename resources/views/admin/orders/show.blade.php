<x-app-layout>
    <x-header title="Pedido {{ $order->order_number }}" subtitle="Detalhes completos do pedido">
        <x-slot name="iconSlot">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </x-slot>
        <a href="{{ route('admin.orders.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:border-orange-400 hover:text-orange-600 transition">
            ← Voltar aos pedidos
        </a>
    </x-header>

    <div class="py-8">
        <div class="mx-auto sm:px-6 lg:px-8">

            {{-- Alertas --}}
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 text-green-800 text-sm font-medium">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @php
                $badgeMap = [
                    'pending'   => ['class' => 'bg-yellow-100 text-yellow-800', 'label' => '⏳ Pendente'],
                    'paid'      => ['class' => 'bg-blue-100 text-blue-800',    'label' => '✅ Pago'],
                    'shipped'   => ['class' => 'bg-purple-100 text-purple-800','label' => '🚚 Enviado'],
                    'delivered' => ['class' => 'bg-green-100 text-green-800',  'label' => '📦 Entregue'],
                    'canceled'  => ['class' => 'bg-red-100 text-red-800',      'label' => '❌ Cancelado'],
                ];
                $badge = $badgeMap[$order->status] ?? ['class' => 'bg-gray-100 text-gray-700', 'label' => $order->status];
            @endphp

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                {{-- ══════════ COLUNA PRINCIPAL ══════════ --}}
                <div class="xl:col-span-2 space-y-6">

                    {{-- Cabeçalho do pedido --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="bg-gray-900 px-6 py-4 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-gray-400 text-xs uppercase tracking-wide">Número do pedido</p>
                                <p class="text-white font-bold text-xl font-mono">{{ $order->order_number }}</p>
                                <p class="text-gray-400 text-xs mt-1">{{ $order->created_at->format('d/m/Y \à\s H:i') }}</p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $badge['class'] }}">
                                {{ $badge['label'] }}
                            </span>
                        </div>

                        {{-- Info rápida --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-y sm:divide-y-0 divide-gray-100">
                            @php
                                $pmLabels = ['pix'=>'💠 Pix','cartao'=>'💳 Cartão','boleto'=>'📄 Boleto'];
                            @endphp
                            <div class="p-4 text-center">
                                <p class="text-xs text-gray-500 mb-1">Pagamento</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $pmLabels[$order->payment_method] ?? $order->payment_method }}</p>
                            </div>
                            <div class="p-4 text-center">
                                <p class="text-xs text-gray-500 mb-1">Frete</p>
                                <p class="text-sm font-semibold text-gray-800">R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}</p>
                            </div>
                            <div class="p-4 text-center">
                                <p class="text-xs text-gray-500 mb-1">Desconto</p>
                                <p class="text-sm font-semibold text-green-600">− R$ {{ number_format($order->discount_total, 2, ',', '.') }}</p>
                            </div>
                            <div class="p-4 text-center">
                                <p class="text-xs text-gray-500 mb-1">Total</p>
                                <p class="text-lg font-bold text-gray-900">R$ {{ number_format($order->total, 2, ',', '.') }}</p>
                            </div>
                        </div>
                        @if($order->estimated_delivery_days)
                            <div class="px-6 py-3 bg-orange-50 border-t border-orange-100 text-sm text-orange-700">
                                Prazo estimado total do pedido: até {{ $order->estimated_delivery_days }} dias úteis.
                            </div>
                        @endif
                    </div>

                    {{-- Itens --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-900">Itens do pedido ({{ $order->items->count() }})</h3>
                        </div>
                        <div class="divide-y divide-gray-50">
                            @foreach($order->items as $item)
                                <div class="flex items-center gap-4 px-6 py-4">
                                    <div class="w-14 h-14 flex-shrink-0 rounded-xl overflow-hidden bg-gray-100">
                                        @if($item->product?->images->first())
                                            <img src="{{ asset('storage/' . $item->product->images->first()->path) }}"
                                                alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-2xl">📦</div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 truncate">{{ $item->product_name ?? $item->product?->name ?? 'Produto removido' }}</p>
                                        <p class="text-sm text-gray-500">
                                            Qtd: {{ $item->quantity }} × R$ {{ number_format($item->unit_price, 2, ',', '.') }}
                                        </p>
                                        @if($item->product_sku || $item->product?->sku)
                                            <p class="text-xs text-gray-400 font-mono">SKU: {{ $item->product_sku ?? $item->product?->sku }}</p>
                                        @endif
                                        @if(($item->backordered_quantity ?? 0) > 0)
                                            <p class="text-xs text-orange-600">
                                                {{ $item->backordered_quantity }} un. sob encomenda · prazo estimado de até {{ $item->estimated_delivery_days }} dias úteis
                                            </p>
                                        @endif
                                    </div>
                                    <p class="font-bold text-gray-900 whitespace-nowrap">
                                        R$ {{ number_format($item->subtotal, 2, ',', '.') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>

                        {{-- Total breakdown --}}
                        <div class="px-6 py-4 bg-gray-50 space-y-1.5 text-sm border-t border-gray-100">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal dos itens</span>
                                <span>R$ {{ number_format($order->items->sum('subtotal'), 2, ',', '.') }}</span>
                            </div>
                            @if($order->shipping_cost > 0)
                                <div class="flex justify-between text-gray-600">
                                    <span>Frete</span>
                                    <span>+ R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}</span>
                                </div>
                            @endif
                            @if($order->discount_total > 0)
                                <div class="flex justify-between text-green-600">
                                    <span>Desconto {{ $order->coupon ? '(' . $order->coupon->code . ')' : '' }}</span>
                                    <span>− R$ {{ number_format($order->discount_total, 2, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between font-bold text-base text-gray-900 border-t border-gray-200 pt-2">
                                <span>Total</span>
                                <span>R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Timeline de status --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Histórico de datas</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center gap-3 text-gray-700">
                                <span class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center text-xs">📋</span>
                                <span class="w-28 text-gray-500">Criado em</span>
                                <span class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($order->shipped_at)
                                <div class="flex items-center gap-3 text-gray-700">
                                    <span class="w-5 h-5 rounded-full bg-purple-100 flex items-center justify-center text-xs">🚚</span>
                                    <span class="w-28 text-gray-500">Enviado em</span>
                                    <span class="font-medium">{{ $order->shipped_at->format('d/m/Y H:i') }}</span>
                                </div>
                            @endif
                            @if($order->delivered_at)
                                <div class="flex items-center gap-3 text-gray-700">
                                    <span class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center text-xs">📦</span>
                                    <span class="w-28 text-gray-500">Entregue em</span>
                                    <span class="font-medium">{{ $order->delivered_at->format('d/m/Y H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- ══════════ COLUNA LATERAL ══════════ --}}
                <div class="space-y-6">

                    {{-- Cliente --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="text-lg">👤</span> Cliente
                        </h3>
                        @if($order->customer)
                            <p class="font-semibold text-gray-800">{{ $order->customer->name }}</p>
                            <p class="text-sm text-gray-500">{{ $order->customer->email }}</p>
                            @if($order->customer->phone)
                                <p class="text-sm text-gray-500">📱 {{ $order->customer->phone }}</p>
                            @endif
                        @else
                            <p class="text-sm text-gray-400">Cliente removido</p>
                        @endif
                    </div>

                    {{-- Endereço --}}
                    @if($order->address)
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                            <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="text-lg">📍</span> Endereço de entrega
                            </h3>
                            <p class="text-sm text-gray-800 font-medium">
                                {{ $order->address->street }}, {{ $order->address->number }}
                                @if($order->address->complement) — {{ $order->address->complement }} @endif
                            </p>
                            <p class="text-sm text-gray-500">{{ $order->address->neighborhood }}</p>
                            <p class="text-sm text-gray-500">{{ $order->address->city }}/{{ $order->address->state }}</p>
                            <p class="text-sm text-gray-500">CEP: {{ $order->address->cep }}</p>
                        </div>
                    @endif

                    {{-- Atualizar status --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="text-lg">🔄</span> Atualizar status
                        </h3>
                        <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PATCH')
                            <select name="status"
                                class="w-full rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 text-sm">
                                @foreach(['pending'=>'⏳ Pendente','paid'=>'✅ Pago','shipped'=>'🚚 Enviado','delivered'=>'📦 Entregue','canceled'=>'❌ Cancelado'] as $val=>$lbl)
                                    <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                            <button type="submit"
                                class="w-full bg-gray-900 hover:bg-orange-600 text-white text-sm font-semibold py-2.5 rounded-xl transition">
                                Salvar status
                            </button>
                        </form>
                    </div>

                    {{-- Código de rastreio --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="text-lg">📮</span> Código de rastreio
                        </h3>
                        @if($order->tracking_code)
                            <div class="bg-purple-50 border border-purple-200 rounded-xl p-3 mb-3">
                                <p class="text-xs text-purple-500 mb-0.5">Código atual</p>
                                <p class="font-mono font-bold text-purple-800 text-sm">{{ $order->tracking_code }}</p>
                            </div>
                        @endif
                        <form action="{{ route('admin.orders.tracking', $order) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PATCH')
                            <input type="text" name="tracking_code"
                                value="{{ $order->tracking_code }}"
                                placeholder="Ex: BR123456789BR"
                                class="w-full rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 text-sm font-mono">
                            @error('tracking_code')
                                <p class="text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                            <button type="submit"
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold py-2.5 rounded-xl transition">
                                {{ $order->tracking_code ? 'Atualizar rastreio' : 'Salvar rastreio' }}
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
