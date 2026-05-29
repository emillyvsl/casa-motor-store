@extends('layouts.store')

@section('title', 'Meu Perfil — Casa dos Motores')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Cabeçalho --}}
    <div class="mb-8">
      <p class="text-sm text-gray-500">Sua conta</p>
      <h1 class="text-3xl font-bold text-gray-900">Meu Perfil</h1>
    </div>

    {{-- Abas --}}
    <div class="flex gap-2 mb-6 border-b border-gray-200 overflow-x-auto">
      <button onclick="switchTab('info')" class="profile-tab active" data-tab="info">
        <i class="fas fa-user-circle"></i> Informações Pessoais
      </button>
      <button onclick="switchTab('orders')" class="profile-tab" data-tab="orders">
        <i class="fas fa-box"></i> Minhas Compras
      </button>
      <button onclick="switchTab('addresses')" class="profile-tab" data-tab="addresses">
        <i class="fas fa-map-marker-alt"></i> Endereços
      </button>
    </div>

    {{-- Conteúdo das Abas --}}
    
    {{-- TAB 1: Informações Pessoais --}}
    <div id="info-content" class="profile-tab-content active">
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Informações Pessoais</h2>
        
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
            <p class="text-gray-900 font-medium">{{ $user->name }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <p class="text-gray-900 font-medium">{{ $user->email }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Membro desde</label>
            <p class="text-gray-900 font-medium">{{ $user->created_at->format('d \\d\\e F \\d\\e Y') }}</p>
          </div>

          <div class="border-t border-gray-100 pt-6 mt-6">
            <a href="{{ route('site.home') }}" class="inline-block px-6 py-2 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 transition">
              Ir ao início
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- TAB 2: Minhas Compras --}}
    <div id="orders-content" class="profile-tab-content hidden">
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Minhas Compras</h2>
        
        @if($user->orders?->isEmpty() ?? true)
          <div class="text-center py-10">
            <i class="fas fa-box text-gray-300 text-4xl mb-4"></i>
            <p class="text-gray-500 mb-4">Você ainda não fez nenhuma compra.</p>
            <a href="{{ route('site.products') }}" class="inline-block px-6 py-2 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 transition">
              Ver Produtos
            </a>
          </div>
        @else
          <div class="space-y-4">
            @foreach($user->orders as $order)
              <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="font-semibold text-gray-900">Pedido #{{ $order->order_number }}</p>
                    <p class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y') }}</p>
                  </div>
                  <div class="text-right">
                    <p class="font-bold text-gray-900">R$ {{ number_format($order->total, 2, ',', '.') }}</p>
                    <span class="text-xs px-2 py-1 rounded-full {{ 
                      $order->status == 'completed' ? 'bg-green-100 text-green-800' :
                      ($order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                      'bg-gray-100 text-gray-800')
                    }}">
                      {{ 
                        $order->status == 'completed' ? 'Entregue' :
                        ($order->status == 'pending' ? 'Pendente' :
                        'Cancelado')
                      }}
                    </span>
                  </div>
                </div>
                <div class="mt-3 text-sm text-gray-600">
                  <p>{{ $order->items->count() }} produto(s)</p>
                </div>
                <a href="{{ route('customer.orders.show', $order) }}" class="text-sm text-orange-600 hover:text-orange-700 font-semibold mt-3 inline-block">
                  Ver Detalhes →
                </a>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>

    {{-- TAB 3: Endereços --}}
    <div id="addresses-content" class="profile-tab-content hidden">
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-bold text-gray-900">Endereços de Entrega</h2>
          <button onclick="toggleNewAddressForm()" class="px-4 py-2 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 transition text-sm">
            <i class="fas fa-plus"></i> Adicionar Endereço
          </button>
        </div>

        {{-- Lista de Endereços Salvos --}}
        @if($user->addresses?->isEmpty() ?? true)
          <div class="text-center py-10">
            <i class="fas fa-map-marker-alt text-gray-300 text-4xl mb-4"></i>
            <p class="text-gray-500 mb-4">Você ainda não tem endereços salvos.</p>
          </div>
        @else
          <div class="space-y-4 mb-6">
            @foreach($user->addresses as $address)
              <div class="border {{ $address->is_default ? 'border-orange-500 bg-orange-50' : 'border-gray-200' }} rounded-lg p-4">
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <div class="flex items-center gap-2">
                      <p class="font-semibold text-gray-900">{{ $address->street }}, {{ $address->number }}</p>
                      @if($address->is_default)
                        <span class="text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full font-medium">Padrão</span>
                      @endif
                    </div>
                    @if($address->complement)
                      <p class="text-sm text-gray-600">{{ $address->complement }}</p>
                    @endif
                    <p class="text-sm text-gray-500">{{ $address->neighborhood }} · {{ $address->city }}/{{ $address->state }} · {{ $address->cep }}</p>
                  </div>
                  <div class="flex gap-2 ml-4">
                    <form action="{{ route('customer.address.delete', $address) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja remover este endereço?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-semibold" title="Remover">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif

        {{-- Formulário de Novo Endereço --}}
        <div id="new-address-form" class="hidden border-t border-gray-100 pt-6">
          <h3 class="font-semibold text-gray-900 mb-4">Novo Endereço</h3>
          <form action="{{ route('customer.address.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">CEP <span class="text-red-500">*</span></label>
              <div class="flex gap-2">
                <input type="text" id="novo_cep" name="cep" maxlength="9" placeholder="00000-000"
                  class="flex-1 rounded-lg border border-gray-300 focus:ring-orange-500 focus:border-orange-500 focus:outline-none px-3 py-2">
                <button type="button" onclick="buscarCep()"
                  class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-orange-600 transition font-medium">
                  Buscar
                </button>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rua / Logradouro <span class="text-red-500">*</span></label>
                <input type="text" name="street" id="street" required
                  class="w-full rounded-lg border border-gray-300 focus:ring-orange-500 focus:border-orange-500 focus:outline-none px-3 py-2">
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Número <span class="text-red-500">*</span></label>
                <input type="text" name="number" id="number" required
                  class="w-full rounded-lg border border-gray-300 focus:ring-orange-500 focus:border-orange-500 focus:outline-none px-3 py-2">
              </div>

              <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Complemento</label>
                <input type="text" name="complement" placeholder="Apto, Bloco..."
                  class="w-full rounded-lg border border-gray-300 focus:ring-orange-500 focus:border-orange-500 focus:outline-none px-3 py-2">
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bairro <span class="text-red-500">*</span></label>
                <input type="text" name="neighborhood" id="neighborhood" required
                  class="w-full rounded-lg border border-gray-300 focus:ring-orange-500 focus:border-orange-500 focus:outline-none px-3 py-2">
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cidade <span class="text-red-500">*</span></label>
                <input type="text" name="city" id="city" required
                  class="w-full rounded-lg border border-gray-300 focus:ring-orange-500 focus:border-orange-500 focus:outline-none px-3 py-2">
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado <span class="text-red-500">*</span></label>
                <select name="state" id="state" required
                  class="w-full rounded-lg border border-gray-300 focus:ring-orange-500 focus:border-orange-500 focus:outline-none px-3 py-2">
                  <option value="">Selecione...</option>
                  @foreach(['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $uf)
                    <option value="{{ $uf }}">{{ $uf }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="flex gap-3">
              <button type="submit" class="px-6 py-2 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 transition">
                Salvar Endereço
              </button>
              <button type="button" onclick="toggleNewAddressForm()" class="px-6 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition">
                Cancelar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>

<style>
  .profile-tab {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    background: none;
    border: none;
    color: #6b7280;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    border-bottom: 2px solid transparent;
    position: relative;
    bottom: -1px;
  }

  .profile-tab:hover {
    color: #111827;
  }

  .profile-tab.active {
    color: #c96a28;
    border-bottom-color: #c96a28;
  }

  .profile-tab-content {
    transition: opacity 0.3s ease;
  }

  .profile-tab-content.hidden {
    display: none;
  }

  .profile-tab-content.active {
    display: block;
  }
</style>

<script>
  function switchTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.profile-tab-content').forEach(tab => {
      tab.classList.remove('active');
      tab.classList.add('hidden');
    });

    // Remove active class from all buttons
    document.querySelectorAll('.profile-tab').forEach(tab => {
      tab.classList.remove('active');
    });

    // Show selected tab
    const tabContent = document.getElementById(tabName + '-content');
    if (tabContent) {
      tabContent.classList.add('active');
      tabContent.classList.remove('hidden');
    }

    // Add active class to clicked button
    event.target.closest('.profile-tab').classList.add('active');
  }

  function toggleNewAddressForm() {
    const form = document.getElementById('new-address-form');
    form.classList.toggle('hidden');
  }

  // Auto-fill address from ViaCEP
  async function buscarCep() {
    const cepInput = document.getElementById('novo_cep');
    const cep = cepInput.value.replace(/\D/g, '');

    if (cep.length !== 8) {
      alert('Informe um CEP válido com 8 dígitos.');
      return;
    }

    try {
      const res = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
      const data = await res.json();

      if (data.erro) {
        alert('CEP não encontrado.');
        return;
      }

      document.getElementById('street').value = data.logradouro || '';
      document.getElementById('neighborhood').value = data.bairro || '';
      document.getElementById('city').value = data.localidade || '';
      document.getElementById('state').value = data.uf || '';

      cepInput.value = cep.replace(/(\d{5})(\d{3})/, '$1-$2');
      document.getElementById('number').focus();
    } catch (e) {
      alert('Erro ao buscar o CEP. Tente novamente.');
    }
  }

  // Format CEP while typing
  document.getElementById('novo_cep')?.addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '');
    if (v.length > 5) v = v.slice(0, 5) + '-' + v.slice(5, 8);
    this.value = v;
  });

  // Check for tab parameter in URL
  document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');
    if (tab && document.querySelector(`[data-tab="${tab}"]`)) {
      document.querySelector(`[data-tab="${tab}"]`).click();
    }
  });
</script>
@endsection