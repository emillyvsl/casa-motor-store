@extends('layouts.store')

@section('title', 'Pedido Confirmado — Casa dos Motores')

@section('content')
<div class="min-h-screen bg-gray-50 py-16">
  <div class="max-w-2xl mx-auto px-4 sm:px-6">

    {{-- Ícone de sucesso --}}
    <div class="text-center mb-10">
      <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 mb-4">
        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
      </div>
      <h1 class="text-3xl font-bold text-gray-900">Pedido confirmado!</h1>
      <p class="text-gray-500 mt-2">Obrigado pela sua compra, {{ auth()->guard('customer')->user()->name }}!</p>
    </div>

    {{-- Card do pedido --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">

      {{-- Número e status --}}
      <div class="bg-gray-900 px-6 py-4 flex items-center justify-between">
        <div>
          <p class="text-gray-400 text-xs uppercase tracking-wide">Número do pedido</p>
          <p class="text-white font-bold text-lg font-mono">{{ $order->order_number }}</p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-yellow-400 text-yellow-900">
          ⏳ Aguardando pagamento
        </span>
      </div>

      {{-- Método de pagamento --}}
      <div class="px-6 py-5 border-b border-gray-100">
        <p class="text-sm font-medium text-gray-500 mb-2">Forma de pagamento</p>
        @php
          $paymentLabels = [
            'pix'    => ['emoji' => '💠', 'label' => 'Pix', 'info' => 'Aprovação imediata após pagamento'],
            'cartao' => ['emoji' => '💳', 'label' => 'Cartão de crédito', 'info' => 'Processamento em até 2h'],
            'boleto' => ['emoji' => '📄', 'label' => 'Boleto bancário', 'info' => 'Vence em 3 dias úteis'],
          ];
          $pm = $paymentLabels[$order->payment_method] ?? ['emoji' => '💰', 'label' => ucfirst($order->payment_method), 'info' => ''];
        @endphp
        <div class="flex items-center gap-3 bg-orange-50 border border-orange-200 rounded-xl px-4 py-3">
          <span class="text-2xl">{{ $pm['emoji'] }}</span>
          <div>
            <p class="font-semibold text-gray-800">{{ $pm['label'] }}</p>
            <p class="text-xs text-orange-700">{{ $pm['info'] }}</p>
          </div>
        </div>

        @if($order->payment_method === 'pix')
          <div class="mt-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-800">
            <p class="font-semibold mb-1">💡 Como pagar via Pix</p>
            <p>Entre em contato via WhatsApp ou aguarde o e-mail com a chave Pix para finalizar o pagamento.</p>
          </div>
        @endif
      </div>

      {{-- Itens --}}
      <div class="px-6 py-4 border-b border-gray-100">
        <p class="text-sm font-medium text-gray-500 mb-3">Itens do pedido</p>
        <div class="space-y-3">
          @foreach($order->items as $item)
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100">
                @if($item->product?->images->first())
                  <img src="{{ asset('storage/' . $item->product->images->first()->path) }}"
                    alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                @else
                  <div class="w-full h-full flex items-center justify-center text-gray-300 text-xs">📦</div>
                @endif
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-800 truncate">{{ $item->product_name ?? $item->product?->name ?? 'Produto' }}</p>
                <p class="text-xs text-gray-500">Qtd: {{ $item->quantity }} × R$ {{ number_format($item->unit_price, 2, ',', '.') }}</p>
                @if(($item->backordered_quantity ?? 0) > 0)
                  <p class="text-xs text-orange-600">
                    {{ $item->backordered_quantity }} un. sob encomenda · prazo estimado de até {{ $item->estimated_delivery_days }} dias úteis
                  </p>
                @endif
              </div>
              <p class="text-sm font-bold text-gray-800 whitespace-nowrap">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</p>
            </div>
          @endforeach
        </div>
      </div>

      {{-- Endereço de entrega --}}
      @if($order->address)
        <div class="px-6 py-4 border-b border-gray-100">
          <p class="text-sm font-medium text-gray-500 mb-1">Entrega para</p>
          <p class="text-sm text-gray-800 font-medium">
            {{ $order->address->street }}, {{ $order->address->number }}
            @if($order->address->complement) — {{ $order->address->complement }} @endif
          </p>
          <p class="text-sm text-gray-500">
            {{ $order->address->neighborhood }} · {{ $order->address->city }}/{{ $order->address->state }} · CEP {{ $order->address->cep }}
          </p>
          @if($order->address && $order->shipping_cost > 0)
            <p class="text-xs text-gray-400 mt-1">Frete: R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}</p>
          @endif
          @if($order->estimated_delivery_days)
            <p class="text-xs text-gray-500 mt-1">
              Prazo estimado total: até {{ $order->estimated_delivery_days }} dias úteis
            </p>
          @endif
        </div>
      @endif

      {{-- Totais --}}
      <div class="px-6 py-4 bg-gray-50 space-y-1.5 text-sm">
        <div class="flex justify-between text-gray-600">
          <span>Subtotal</span>
          <span>R$ {{ number_format($order->total - $order->shipping_cost + $order->discount_total, 2, ',', '.') }}</span>
        </div>
        @if($order->shipping_cost > 0)
          <div class="flex justify-between text-gray-600">
            <span>Frete</span>
            <span>R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}</span>
          </div>
        @endif
        @if($order->discount_total > 0)
          <div class="flex justify-between text-green-600">
            <span>Desconto</span>
            <span>− R$ {{ number_format($order->discount_total, 2, ',', '.') }}</span>
          </div>
        @endif
        <div class="flex justify-between font-bold text-base text-gray-900 border-t border-gray-200 pt-2 mt-2">
          <span>Total</span>
          <span>R$ {{ number_format($order->total, 2, ',', '.') }}</span>
        </div>
      </div>

    </div>

    {{-- Ações --}}
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
      <a href="{{ route('customer.orders') }}"
        class="flex-1 text-center bg-gray-900 text-white font-semibold py-3 rounded-xl hover:bg-gray-800 transition">
        Ver meus pedidos
      </a>
      <a href="{{ route('site.products') }}"
        class="flex-1 text-center bg-white border-2 border-gray-200 text-gray-700 font-semibold py-3 rounded-xl hover:border-orange-400 hover:text-orange-600 transition">
        Continuar comprando
      </a>
    </div>

    {{-- Informações extras --}}
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4 text-center text-sm">
      <div class="bg-white rounded-xl border border-gray-100 p-4">
        <div class="text-2xl mb-2">📧</div>
        <p class="font-medium text-gray-700">Confirmação</p>
        <p class="text-gray-400 text-xs mt-1">Você receberá um e-mail de confirmação em breve</p>
      </div>
      <div class="bg-white rounded-xl border border-gray-100 p-4">
        <div class="text-2xl mb-2">📦</div>
        <p class="font-medium text-gray-700">Preparação</p>
        <p class="text-gray-400 text-xs mt-1">Após o pagamento confirmado, seu pedido é preparado</p>
      </div>
      <div class="bg-white rounded-xl border border-gray-100 p-4">
        <div class="text-2xl mb-2">🚚</div>
        <p class="font-medium text-gray-700">Entrega</p>
        <p class="text-gray-400 text-xs mt-1">Acompanhe o rastreio pelo seu histórico de pedidos</p>
      </div>
    </div>

  </div>
</div>
@endsection
