@extends('layouts.store')

@section('title', 'Meus Pedidos — Casa dos Motores')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="flex items-center justify-between mb-8">
      <div>
        <p class="text-sm text-gray-500">Área do cliente</p>
        <h1 class="text-3xl font-bold text-gray-900">Meus Pedidos</h1>
      </div>
      <a href="{{ route('site.products') }}"
        class="text-sm font-semibold text-orange-600 hover:text-orange-700">
        Continuar comprando →
      </a>
    </div>

    @if($orders->isEmpty())
      <div class="bg-white rounded-2xl border border-dashed border-gray-300 p-14 text-center">
        <div class="text-5xl mb-4">📋</div>
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Você ainda não fez nenhum pedido</h2>
        <p class="text-gray-500 mb-6">Explore nosso catálogo e encontre o que você precisa.</p>
        <a href="{{ route('site.products') }}"
          class="inline-flex items-center px-6 py-3 bg-orange-600 text-white font-semibold rounded-xl hover:bg-orange-700 transition">
          Ver produtos
        </a>
      </div>
    @else
      <div class="space-y-4">
        @foreach($orders as $order)
          @php
            $badges = [
              'pending'   => ['class' => 'bg-yellow-100 text-yellow-800', 'label' => '⏳ Pendente'],
              'paid'      => ['class' => 'bg-blue-100 text-blue-800',    'label' => '✅ Pago'],
              'shipped'   => ['class' => 'bg-purple-100 text-purple-800','label' => '🚚 Enviado'],
              'delivered' => ['class' => 'bg-green-100 text-green-800',  'label' => '📦 Entregue'],
              'canceled'  => ['class' => 'bg-red-100 text-red-800',      'label' => '❌ Cancelado'],
            ];
            $badge = $badges[$order->status] ?? ['class' => 'bg-gray-100 text-gray-700', 'label' => $order->status];
          @endphp

          <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden hover:border-orange-300 transition">
            {{-- Cabeçalho do card --}}
            <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-b border-gray-100 bg-gray-50">
              <div class="flex items-center gap-4">
                <div>
                  <p class="text-xs text-gray-500">Pedido</p>
                  <p class="font-bold text-gray-900 font-mono text-sm">{{ $order->order_number }}</p>
                </div>
                <div class="hidden sm:block">
                  <p class="text-xs text-gray-500">Data</p>
                  <p class="text-sm text-gray-700">{{ $order->created_at->format('d/m/Y') }}</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badge['class'] }}">
                  {{ $badge['label'] }}
                </span>
              </div>
              <div class="flex items-center gap-4">
                <div class="text-right">
                  <p class="text-xs text-gray-500">Total</p>
                  <p class="font-bold text-gray-900">R$ {{ number_format($order->total, 2, ',', '.') }}</p>
                  @if($order->estimated_delivery_days)
                    <p class="text-xs text-gray-500">Prazo: até {{ $order->estimated_delivery_days }} dias úteis</p>
                  @endif
                </div>
                <a href="{{ route('customer.orders.show', $order) }}"
                  class="px-4 py-2 bg-gray-900 text-white text-xs font-semibold rounded-lg hover:bg-orange-600 transition whitespace-nowrap">
                  Ver detalhe
                </a>
              </div>
            </div>

            {{-- Prévia dos itens --}}
            <div class="px-5 py-3 flex items-center gap-3 overflow-x-auto">
              @foreach($order->items->take(4) as $item)
                <div class="flex items-center gap-2 flex-shrink-0">
                  <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                    @if($item->product?->images->first())
                      <img src="{{ asset('storage/' . $item->product->images->first()->path) }}"
                        alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                    @else
                      <div class="w-full h-full flex items-center justify-center text-gray-300 text-xs">📦</div>
                    @endif
                  </div>
                  <div class="hidden sm:block">
                    <p class="text-xs font-medium text-gray-700 max-w-[100px] truncate">{{ $item->product_name ?? $item->product?->name ?? '—' }}</p>
                    <p class="text-xs text-gray-400">Qtd: {{ $item->quantity }}</p>
                  </div>
                </div>
              @endforeach
              @if($order->items->count() > 4)
                <span class="text-xs text-gray-400 flex-shrink-0">+ {{ $order->items->count() - 4 }} item(s)</span>
              @endif
            </div>

            {{-- Rastreio (se disponível) --}}
            @if($order->tracking_code)
              <div class="px-5 py-3 bg-purple-50 border-t border-purple-100 flex items-center gap-2 text-sm">
                <span class="text-purple-600">🚚</span>
                <span class="text-purple-700 font-medium">Rastreio:</span>
                <span class="font-mono text-purple-800 font-bold">{{ $order->tracking_code }}</span>
              </div>
            @endif
          </div>
        @endforeach
      </div>

      <div class="mt-6">{{ $orders->links() }}</div>
    @endif

  </div>
</div>
@endsection
