@extends('layouts.store')

@section('title', 'Checkout — Casa dos Motores')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Cabeçalho com info do cliente --}}
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-500">Finalize seu pedido</p>
          <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
        </div>
        <div class="text-right">
          <p class="text-sm text-gray-600">Olá, <span class="font-semibold text-gray-900">{{ auth()->guard('customer')->user()->name }}</span></p>
          <p class="text-xs text-gray-500">{{ auth()->guard('customer')->user()->email }}</p>
          <a href="{{ route('site.home') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-xs text-orange-600 hover:text-orange-700 font-semibold mt-1 inline-block">Sair</a>
          <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      </div>
    </div>

    {{-- Alertas de erro --}}
    @if ($errors->any())
      <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
        <p class="text-sm font-semibold text-red-700 mb-1">Corrija os erros abaixo:</p>
        <ul class="list-disc ml-5 text-sm text-red-600 space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('customer.checkout.store') }}" method="POST" id="checkout-form">
      @csrf

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ══════════════════ COLUNA ESQUERDA ══════════════════ --}}
        <div class="lg:col-span-2 space-y-6">

          {{-- ── 1. ENDEREÇO DE ENTREGA ── --}}
          <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gray-900 px-6 py-4 flex items-center gap-3">
              <div class="w-7 h-7 rounded-full bg-orange-500 flex items-center justify-center text-white text-sm font-bold">1</div>
              <h2 class="text-white font-semibold text-lg">Endereço de entrega</h2>
            </div>

            <div class="p-6 space-y-4">

              {{-- Endereços salvos --}}
              @if($addresses->isNotEmpty())
                <p class="text-sm font-medium text-gray-700 mb-3">Selecione um endereço salvo:</p>
                <div class="space-y-3" id="saved-addresses">
                  @foreach($addresses as $addr)
                    <label class="flex items-start gap-3 p-4 border-2 rounded-xl cursor-pointer transition
                      {{ old('address_id') == $addr->id || ($loop->first && !old('address_id') && $addr->is_default) ? 'border-orange-500 bg-orange-50' : 'border-gray-200 hover:border-orange-300' }}"
                      for="addr_{{ $addr->id }}">
                      <input type="radio" name="address_id" id="addr_{{ $addr->id }}"
                        value="{{ $addr->id }}" class="mt-1 accent-orange-500"
                        {{ old('address_id') == $addr->id || ($loop->first && !old('address_id') && $addr->is_default) ? 'checked' : '' }}
                        onchange="toggleNewAddress(false)">
                      <div>
                        <p class="font-semibold text-gray-800">
                          {{ $addr->street }}, {{ $addr->number }}
                          @if($addr->complement) — {{ $addr->complement }} @endif
                        </p>
                        <p class="text-sm text-gray-500">{{ $addr->neighborhood }} · {{ $addr->city }}/{{ $addr->state }} · CEP {{ $addr->cep }}</p>
                        @if($addr->is_default)
                          <span class="inline-block mt-1 text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full font-medium">Padrão</span>
                        @endif
                      </div>
                    </label>
                  @endforeach
                </div>

                <button type="button" onclick="toggleNewAddress(true)"
                  class="mt-2 text-sm text-orange-600 hover:text-orange-700 font-semibold flex items-center gap-1">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                  Usar outro endereço
                </button>
              @endif

              {{-- Formulário novo endereço --}}
              <div id="new-address-form" class="{{ $addresses->isNotEmpty() ? 'hidden' : '' }} space-y-4 border-t border-gray-100 pt-4">
                @if($addresses->isNotEmpty())
                  <p class="text-sm font-semibold text-gray-700">Novo endereço:</p>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">CEP <span class="text-red-500">*</span></label>
                    <div class="flex gap-2">
                      <input type="text" id="novo_cep" name="cep" maxlength="9"
                        placeholder="00000-000"
                        value="{{ old('cep') }}"
                        class="flex-1 rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 text-sm">
                      <button type="button" onclick="buscarCep()"
                        class="px-4 py-2 bg-gray-900 text-white text-sm rounded-lg hover:bg-orange-600 transition font-medium">
                        Buscar
                      </button>
                    </div>
                    @error('cep') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                  </div>

                  <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rua / Logradouro <span class="text-red-500">*</span></label>
                    <input type="text" name="street" id="street" value="{{ old('street') }}"
                      class="w-full rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 text-sm">
                    @error('street') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número</label>
                    <input type="text" name="number" id="number" value="{{ old('number') }}"
                      class="w-full rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 text-sm">
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Complemento</label>
                    <input type="text" name="complement" value="{{ old('complement') }}"
                      placeholder="Apto, Bloco..."
                      class="w-full rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 text-sm">
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bairro <span class="text-red-500">*</span></label>
                    <input type="text" name="neighborhood" id="neighborhood" value="{{ old('neighborhood') }}"
                      class="w-full rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 text-sm">
                    @error('neighborhood') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cidade <span class="text-red-500">*</span></label>
                    <input type="text" name="city" id="city" value="{{ old('city') }}"
                      class="w-full rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 text-sm">
                    @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado <span class="text-red-500">*</span></label>
                    <select name="state" id="state"
                      class="w-full rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 text-sm">
                      <option value="">Selecione...</option>
                      @foreach(['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $uf)
                        <option value="{{ $uf }}" {{ old('state') == $uf ? 'selected' : '' }}>{{ $uf }}</option>
                      @endforeach
                    </select>
                    @error('state') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                  </div>
                </div>

                @if($addresses->isNotEmpty())
                  <label class="flex items-center gap-2 text-sm text-gray-600 mt-2">
                    <input type="checkbox" name="save_address" value="1" class="accent-orange-500">
                    Salvar este endereço para futuros pedidos
                  </label>
                @endif
              </div>

            </div>
          </div>

          {{-- ── 2. FRETE/ENTREGA ── --}}
          <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gray-900 px-6 py-4 flex items-center gap-3">
              <div class="w-7 h-7 rounded-full bg-orange-500 flex items-center justify-center text-white text-sm font-bold">2</div>
              <h2 class="text-white font-semibold text-lg">Forma de entrega</h2>
            </div>

            <div class="p-6 space-y-4">
              <p class="text-sm font-medium text-gray-700 mb-3">Informe seu CEP para calcular o frete:</p>
              
              <div class="flex gap-2">
                <input id="checkout-cep" type="text" maxlength="9" placeholder="00000-000"
                  value="{{ $cart->shipping_cep_destino }}"
                  class="flex-1 rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 text-sm">
                <button id="btn-checkout-frete" type="button"
                  class="px-4 py-2 bg-gray-900 text-white text-sm rounded-lg hover:bg-orange-600 transition font-medium">
                  Calcular
                </button>
              </div>

              {{-- Opções de frete --}}
              <div id="checkout-frete-opcoes" class="mt-4 space-y-2">
                @if($cart->shipping_service)
                  <div class="border-2 border-orange-500 bg-orange-50 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                      <div>
                        <p class="font-semibold text-gray-900">{{ $cart->shipping_service }}</p>
                        <p class="text-xs text-gray-500">{{ $cart->shipping_delivery_days }} dia(s) útil(eis)</p>
                      </div>
                      <span class="font-bold text-gray-900">R$ {{ number_format($cart->shipping_cost, 2, ',', '.') }}</span>
                    </div>
                  </div>
                @else
                  <p class="text-sm text-gray-500">Nenhum frete selecionado. Calcule acima para ver as opções.</p>
                @endif
              </div>

              {{-- Erro se não calculou --}}
              @if(!$cart->shipping_service)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-xs text-yellow-800">
                  ⚠️ Se desejar calcular o frete agora, informe o CEP acima. Você também pode completar o pedido agora e calcular o frete em seguida.
                </div>
              @endif
            </div>
          </div>

          {{-- ── 3. MÉTODO DE PAGAMENTO ── --}}
          <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gray-900 px-6 py-4 flex items-center gap-3">
              <div class="w-7 h-7 rounded-full bg-orange-500 flex items-center justify-center text-white text-sm font-bold">3</div>
              <h2 class="text-white font-semibold text-lg">Forma de pagamento</h2>
            </div>

            <div class="p-6">
              @error('payment_method')
                <p class="text-red-500 text-sm mb-3">{{ $message }}</p>
              @enderror

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                {{-- PIX --}}
                <label for="pay_pix" class="cursor-pointer">
                  <input type="radio" name="payment_method" id="pay_pix" value="pix"
                    class="sr-only peer" {{ old('payment_method','pix') == 'pix' ? 'checked' : '' }}>
                  <div class="border-2 border-gray-200 rounded-xl p-4 text-center transition
                    peer-checked:border-orange-500 peer-checked:bg-orange-50 hover:border-orange-300">
                    <div class="text-3xl mb-2">💠</div>
                    <p class="font-semibold text-gray-800">Pix</p>
                    <p class="text-xs text-green-600 font-medium mt-1">Aprovação imediata</p>
                  </div>
                </label>

                {{-- CARTÃO --}}
                <label for="pay_cartao" class="cursor-pointer">
                  <input type="radio" name="payment_method" id="pay_cartao" value="cartao"
                    class="sr-only peer" {{ old('payment_method') == 'cartao' ? 'checked' : '' }}>
                  <div class="border-2 border-gray-200 rounded-xl p-4 text-center transition
                    peer-checked:border-orange-500 peer-checked:bg-orange-50 hover:border-orange-300">
                    <div class="text-3xl mb-2">💳</div>
                    <p class="font-semibold text-gray-800">Cartão de crédito</p>
                    <p class="text-xs text-gray-500 mt-1">Em breve disponível</p>
                  </div>
                </label>

                {{-- BOLETO --}}
                <label for="pay_boleto" class="cursor-pointer">
                  <input type="radio" name="payment_method" id="pay_boleto" value="boleto"
                    class="sr-only peer" {{ old('payment_method') == 'boleto' ? 'checked' : '' }}>
                  <div class="border-2 border-gray-200 rounded-xl p-4 text-center transition
                    peer-checked:border-orange-500 peer-checked:bg-orange-50 hover:border-orange-300">
                    <div class="text-3xl mb-2">📄</div>
                    <p class="font-semibold text-gray-800">Boleto bancário</p>
                    <p class="text-xs text-gray-500 mt-1">Vence em 3 dias úteis</p>
                  </div>
                </label>

              </div>
            </div>
          </div>

          {{-- ── 4. ITENS DO PEDIDO ── --}}
          <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gray-900 px-6 py-4 flex items-center gap-3">
              <div class="w-7 h-7 rounded-full bg-orange-500 flex items-center justify-center text-white text-sm font-bold">4</div>
              <h2 class="text-white font-semibold text-lg">Itens do pedido</h2>
            </div>

            <div class="divide-y divide-gray-100">
              @foreach($cart->items as $item)
                <div class="flex items-center gap-4 p-4">
                  {{-- Imagem --}}
                  <div class="w-16 h-16 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100">
                    @if($item->product?->images->first())
                      <img src="{{ asset('storage/' . $item->product->images->first()->path) }}"
                        alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                    @else
                      <div class="w-full h-full flex items-center justify-center text-gray-300 text-xs">Sem foto</div>
                    @endif
                  </div>
                  {{-- Info --}}
                  <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 truncate">{{ $item->product->name }}</p>
                    <p class="text-sm text-gray-500">Qtd: {{ $item->quantity }}</p>
                    @if($item->product && $item->product->backorderedQuantityFor($item->quantity) > 0)
                      <span class="text-xs text-yellow-600 font-medium">
                        📦 Sob encomenda · {{ $item->product->backorderedQuantityFor($item->quantity) }} un.
                        · prazo adicional de {{ $item->product->leadTimeDaysFor($item->quantity) }} dias úteis
                      </span>
                    @endif
                  </div>
                  <p class="font-bold text-gray-900 whitespace-nowrap">
                    R$ {{ number_format($item->subtotal, 2, ',', '.') }}
                  </p>
                </div>
              @endforeach
            </div>
          </div>

        </div>

        {{-- ══════════════════ COLUNA DIREITA (RESUMO) ══════════════════ --}}
        <div class="space-y-4">

          {{-- Resumo do pedido --}}
          <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sticky top-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Resumo do pedido</h3>

            <div class="space-y-2 text-sm text-gray-600">
              <div class="flex justify-between">
                <span>Subtotal</span>
                <span class="font-medium text-gray-800">R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
              </div>

              <div class="flex justify-between">
                <span>Frete</span>
                <span class="font-medium text-gray-800">
                  @if($cart->shipping_service)
                    R$ {{ number_format($shipping, 2, ',', '.') }}
                    <span class="text-gray-400 text-xs block text-right">{{ $cart->shipping_service }}</span>
                  @else
                    <span class="text-orange-600">A calcular</span>
                  @endif
                </span>
              </div>

              @if($estimatedDeliveryDays > 0)
                <div class="flex justify-between text-sm text-gray-600">
                  <span>Prazo estimado</span>
                  <span class="font-medium text-gray-800">Até {{ $estimatedDeliveryDays }} dia(s) útil(eis)</span>
                </div>
              @endif

              @if($discount > 0)
                <div class="flex justify-between text-green-600">
                  <span>Desconto ({{ session('coupon_code') }})</span>
                  <span>− R$ {{ number_format($discount, 2, ',', '.') }}</span>
                </div>
              @endif
            </div>

            <div class="border-t border-gray-200 mt-4 pt-4">
              <div class="flex justify-between text-lg font-bold text-gray-900">
                <span>Total</span>
                <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
              </div>
            </div>

            {{-- Cupom --}}
            @if(!$coupon)
              <div class="mt-4 border-t border-gray-100 pt-4">
                <p class="text-sm font-medium text-gray-700 mb-2">Cupom de desconto</p>
                <div class="flex gap-2">
                  <input type="text" id="coupon_code_input" placeholder="Digite o cupom"
                    class="flex-1 rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 text-sm">
                  <button type="button" onclick="aplicarCupom()"
                    class="px-3 py-2 bg-gray-900 text-white text-sm rounded-lg hover:bg-orange-600 transition">
                    Aplicar
                  </button>
                </div>
                <div id="coupon-msg" class="mt-1 text-xs"></div>
              </div>
            @else
              <div class="mt-4 border-t border-gray-100 pt-4 flex items-center justify-between">
                <p class="text-sm text-green-700 font-medium">✅ Cupom <strong>{{ $coupon->code }}</strong> aplicado</p>
                <form action="{{ route('site.cart.coupon.remove') }}" method="POST">
                  @csrf
                  <input type="hidden" name="_method" value="DELETE">
                  <button type="submit" class="text-xs text-red-500 hover:underline">Remover</button>
                </form>
              </div>
            @endif

            <button type="submit"
              class="mt-6 w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 rounded-xl transition text-base">
              Confirmar Pedido →
            </button>

            <a href="{{ route('site.cart') }}"
              class="mt-3 block text-center text-sm text-gray-500 hover:text-gray-700">
              ← Voltar ao carrinho
            </a>
          </div>

        </div>
      </div>
    </form>
  </div>
</div>

<script>
  // Alterna entre endereço salvo e novo endereço
  function toggleNewAddress(show) {
    const form = document.getElementById('new-address-form');
    const radios = document.querySelectorAll('input[name="address_id"]');

    if (show) {
      form.classList.remove('hidden');
      radios.forEach(r => r.checked = false);
    } else {
      form.classList.add('hidden');
    }
  }

  // Auto-preenche o endereço via ViaCEP
  async function buscarCep() {
    const cepInput = document.getElementById('novo_cep');
    const cep = cepInput.value.replace(/\D/g, '');

    if (cep.length !== 8) {
      alert('Informe um CEP válido com 8 dígitos.');
      return;
    }

    try {
      const res  = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
      const data = await res.json();

      if (data.erro) {
        alert('CEP não encontrado.');
        return;
      }

      document.getElementById('street').value       = data.logradouro || '';
      document.getElementById('neighborhood').value = data.bairro     || '';
      document.getElementById('city').value         = data.localidade || '';
      document.getElementById('state').value        = data.uf         || '';

      // Formata CEP
      cepInput.value = cep.replace(/(\d{5})(\d{3})/, '$1-$2');

      document.getElementById('number').focus();
    } catch (e) {
      alert('Erro ao buscar o CEP. Tente novamente.');
    }
  }

  // Formata o CEP enquanto digita
  document.getElementById('novo_cep')?.addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '');
    if (v.length > 5) v = v.slice(0, 5) + '-' + v.slice(5, 8);
    this.value = v;
  });

  // Aplica cupom via AJAX
  async function aplicarCupom() {
    const code = document.getElementById('coupon_code_input').value.trim();
    const msg  = document.getElementById('coupon-msg');

    if (!code) { msg.textContent = 'Digite um cupom.'; msg.className = 'mt-1 text-xs text-red-500'; return; }

    msg.textContent = 'Verificando...';
    msg.className   = 'mt-1 text-xs text-gray-500';

    try {
      const res  = await fetch('{{ route("site.cart.coupon") }}', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body:    JSON.stringify({ code }),
      });

      if (res.redirected || res.ok) {
        window.location.reload();
      } else {
        msg.textContent = 'Cupom inválido ou expirado.';
        msg.className   = 'mt-1 text-xs text-red-500';
      }
    } catch {
      msg.textContent = 'Erro ao aplicar cupom.';
      msg.className   = 'mt-1 text-xs text-red-500';
    }
  }

  // Botão de rádio de endereço — descarta o form novo ao selecionar salvo
  document.querySelectorAll('input[name="address_id"]').forEach(r => {
    r.addEventListener('change', () => toggleNewAddress(false));
  });

  // ═══════════════════════════════════════════════════════════════════
  // Cálculo de frete no checkout
  // ═══════════════════════════════════════════════════════════════════
  const btnCheckoutFrete = document.getElementById('btn-checkout-frete');
  if (btnCheckoutFrete) {
    btnCheckoutFrete.addEventListener('click', async () => {
      const cep = document.getElementById('checkout-cep').value.trim();
      const box = document.getElementById('checkout-frete-opcoes');
      const freteValor = document.querySelector('[id*="frete-valor"]') || document.getElementById('frete-valor');
      const subtotal = {{ $subtotal }};

      if (!cep) {
        box.innerHTML = '<p class="text-red-600 text-sm">Informe um CEP válido.</p>';
        return;
      }

      box.innerHTML = '<p class="text-gray-500 text-sm">Calculando...</p>';

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
          box.innerHTML = `<p class="text-red-600 text-sm">${data.message || 'Erro ao calcular frete.'}</p>`;
          return;
        }

        if (!Array.isArray(data) || data.length === 0) {
          box.innerHTML = '<p class="text-red-600 text-sm">Nenhum serviço disponível.</p>';
          return;
        }

        const validos = data.filter(s => !s.error && (s.price !== undefined || s.custom_price !== undefined));
        const erros = data.filter(s => s.error);

        let html = '';
        if (validos.length) {
          html += validos.map(s => `
            <button type="button" class="w-full text-left border-2 rounded-lg p-3 hover:border-orange-500 transition flex items-center justify-between checkout-shipping-option" data-price="${parseFloat(s.custom_price || s.price || 0)}" data-service="${(s.company?.name || 'Transportadora')} - ${s.name}" data-delivery="${parseInt(s.delivery_time || s.custom_delivery_time || 0, 10) || 0}">
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

        box.innerHTML = html || '<p class="text-gray-500 text-sm">Nenhum frete disponível.</p>';

        // Adiciona listeners aos botões de frete
        box.querySelectorAll('button.checkout-shipping-option').forEach(btn => {
          btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const price = parseFloat(btn.getAttribute('data-price')) || 0;
            const service = btn.getAttribute('data-service') || 'Frete';
            const deliveryDays = parseInt(btn.getAttribute('data-delivery') || '0', 10) || 0;

            // Highlight selected option
            box.querySelectorAll('button.checkout-shipping-option').forEach(b => {
              b.classList.remove('border-orange-500', 'bg-orange-50');
              b.classList.add('border-gray-200');
            });
            btn.classList.remove('border-gray-200');
            btn.classList.add('border-orange-500', 'bg-orange-50');

            // Salva o frete na sessão
            try {
              await fetch('{{ route('site.cart.shipping') }}', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                  service: service,
                  cost: price,
                  delivery_days: deliveryDays,
                  cep: cep
                })
              });
              
              // Atualiza o resumo
              location.reload();
            } catch (e) {
              console.error('Erro ao salvar frete:', e);
            }
          });
        });

      } catch (e) {
        console.error('Erro:', e);
        box.innerHTML = '<p class="text-red-600 text-sm">Erro ao calcular frete. Tente novamente.</p>';
      }
    });
  }

  // Formata CEP no checkout
  document.getElementById('checkout-cep')?.addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '');
    if (v.length > 5) v = v.slice(0, 5) + '-' + v.slice(5, 8);
    this.value = v;
  });
</script>
@endsection
