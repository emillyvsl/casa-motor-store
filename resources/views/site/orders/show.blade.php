@extends('layouts.store')

@section('title', 'Pedido {{ $order->order_number }} — Casa dos Motores')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Navegação --}}
    <div class="mb-6">
      <a href="{{ route('customer.orders') }}" class="text-sm font-semibold text-orange-600 hover:text-orange-700">
        ← Voltar aos pedidos
      </a>
    </div>

    @php
      $badges = [
        'pending'   => ['class' => 'bg-yellow-100 text-yellow-800', 'label' => '⏳ Aguardando pagamento'],
        'paid'      => ['class' => 'bg-blue-100 text-blue-800',    'label' => '✅ Pagamento confirmado'],
        'shipped'   => ['class' => 'bg-purple-100 text-purple-800','label' => '🚚 Enviado'],
        'delivered' => ['class' => 'bg-green-100 text-green-800',  'label' => '📦 Entregue'],
        'canceled'  => ['class' => 'bg-red-100 text-red-800',      'label' => '❌ Cancelado'],
      ];
      $badge = $badges[$order->status] ?? ['class' => 'bg-gray-100 text-gray-700', 'label' => $order->status];
    @endphp

    {{-- Card principal --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">

      {{-- Cabeçalho --}}
      <div class="bg-gray-900 px-6 py-5 flex flex-wrap items-center justify-between gap-3">
        <div>
          <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Número do pedido</p>
          <p class="text-white font-bold text-xl font-mono">{{ $order->order_number }}</p>
          <p class="text-gray-400 text-xs mt-1">Realizado em {{ $order->created_at->format('d/m/Y \à\s H:i') }}</p>
        </div>
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-bold {{ $badge['class'] }}">
          {{ $badge['label'] }}
        </span>
      </div>

      {{-- Rastreio --}}
      @if($order->tracking_code)
        <div class="px-6 py-4 bg-purple-50 border-b border-purple-100 flex flex-wrap items-center gap-3">
          <span class="text-purple-600 text-lg">🚚</span>
          <div>
            <p class="text-xs text-purple-500 font-medium">Código de rastreio</p>
            <p class="font-mono font-bold text-purple-800 text-base">{{ $order->tracking_code }}</p>
          </div>
          @if($order->shipped_at)
            <p class="text-xs text-purple-500 ml-auto">Enviado em {{ $order->shipped_at->format('d/m/Y') }}</p>
          @endif
        </div>
      @endif

      {{-- Itens --}}
      <div class="divide-y divide-gray-50">
        @foreach($order->items as $item)
          <div class="flex items-center gap-4 px-6 py-4">
            <div class="w-14 h-14 flex-shrink-0 rounded-xl overflow-hidden bg-gray-100">
              @if($item->product?->images->first())
                <img src="{{ asset('storage/' . $item->product->images->first()->path) }}"
                  alt="{{ $item->product->name }}" class="w-full h-full object-cover">
              @else
                <div class="w-full h-full flex items-center justify-center text-xl">📦</div>
              @endif
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-semibold text-gray-900 truncate">{{ $item->product_name ?? $item->product?->name ?? 'Produto indisponível' }}</p>
              <p class="text-sm text-gray-500">
                {{ $item->quantity }} un × R$ {{ number_format($item->unit_price, 2, ',', '.') }}
              </p>
              @if(($item->backordered_quantity ?? 0) > 0)
                <p class="text-xs text-orange-600">
                  {{ $item->backordered_quantity }} un. sob encomenda · prazo estimado de até {{ $item->estimated_delivery_days }} dias úteis
                </p>
              @endif
            </div>
            <p class="font-bold text-gray-800 whitespace-nowrap">
              R$ {{ number_format($item->subtotal, 2, ',', '.') }}
            </p>
          </div>
        @endforeach
      </div>

      {{-- Totais --}}
      <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 space-y-1.5 text-sm">
        <div class="flex justify-between text-gray-600">
          <span>Subtotal</span>
          <span>R$ {{ number_format($order->items->sum('subtotal'), 2, ',', '.') }}</span>
        </div>
        @if($order->shipping_cost > 0)
          <div class="flex justify-between text-gray-600">
            <span>Frete</span>
            <span>R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}</span>
          </div>
        @endif
        @if($order->discount_total > 0)
          <div class="flex justify-between text-green-600">
            <span>Desconto @if($order->coupon)({{ $order->coupon->code }})@endif</span>
            <span>− R$ {{ number_format($order->discount_total, 2, ',', '.') }}</span>
          </div>
        @endif
        <div class="flex justify-between font-bold text-base text-gray-900 border-t border-gray-200 pt-2 mt-2">
          <span>Total</span>
          <span>R$ {{ number_format($order->total, 2, ',', '.') }}</span>
        </div>
      </div>
    </div>

    {{-- Endereço e Pagamento --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

      @if($order->address)
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
          <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
            <span>📍</span> Endereço de entrega
          </h3>
          <p class="text-sm text-gray-700 font-medium">
            {{ $order->address->street }}, {{ $order->address->number }}
            @if($order->address->complement) — {{ $order->address->complement }} @endif
          </p>
          <p class="text-sm text-gray-500">{{ $order->address->neighborhood }}</p>
          <p class="text-sm text-gray-500">{{ $order->address->city }}/{{ $order->address->state }}</p>
          <p class="text-sm text-gray-500">CEP: {{ $order->address->cep }}</p>
        </div>
      @endif

      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
        <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
          <span>💳</span> Pagamento
        </h3>
        @php
          $pmLabels = ['pix'=>'💠 Pix','cartao'=>'💳 Cartão de crédito','boleto'=>'📄 Boleto bancário'];
        @endphp
        <p class="text-sm font-medium text-gray-700">{{ $pmLabels[$order->payment_method] ?? $order->payment_method }}</p>
        @if($order->estimated_delivery_days)
          <p class="text-xs text-gray-500 mt-2">Prazo estimado total: até {{ $order->estimated_delivery_days }} dias úteis</p>
        @endif

        @if($order->payment)
          <p class="text-xs text-gray-500 mt-1">
            Status do pagamento:
            <span class="font-semibold">{{ $order->payment->status }}</span>
          </p>
          @if($order->payment->paid_at)
            <p class="text-xs text-gray-500">Pago em: {{ $order->payment->paid_at->format('d/m/Y H:i') }}</p>
          @endif
        @endif
      </div>

    </div>

    {{-- Ações --}}
    <div class="flex gap-3">
      <a href="{{ route('customer.orders') }}"
        class="flex-1 text-center bg-gray-900 text-white font-semibold py-3 rounded-xl hover:bg-gray-800 transition text-sm">
        ← Voltar aos pedidos
      </a>
      <a href="{{ route('site.products') }}"
        class="flex-1 text-center bg-white border-2 border-gray-200 text-gray-700 font-semibold py-3 rounded-xl hover:border-orange-400 hover:text-orange-600 transition text-sm">
        Continuar comprando
      </a>
    </div>

  </div>
</div>
@endsection
