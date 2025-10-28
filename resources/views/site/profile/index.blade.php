@extends('layouts.store')

@section('title', 'Minha Conta - Casa dos Motores')

@section('content')
<section class="max-w-6xl mx-auto px-4 py-8">
    {{-- Cabeçalho do perfil --}}
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 border border-gray-200">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6 mb-6">
            {{-- Foto do usuário --}}
            <div class="relative">
                @if ($user->avatar ?? false)
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}"
                         class="w-32 h-32 rounded-full border-4 border-orange-500 object-cover shadow-lg">
                @else
                    <div class="w-32 h-32 flex items-center justify-center rounded-full border-4 border-orange-500 bg-gray-100 text-gray-400 text-4xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            {{-- Informações principais --}}
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $user->name }}</h1>
                <p class="text-gray-600 text-lg flex items-center gap-2 mb-1">
                    <i class="fas fa-envelope text-orange-500"></i> {{ $user->email }}
                </p>
                @if($user->phone)
                    <p class="text-gray-600 text-lg flex items-center gap-2">
                        <i class="fas fa-phone text-orange-500"></i> {{ $user->phone }}
                    </p>
                @endif
                
                {{-- Status da conta --}}
                <div class="mt-4 flex flex-wrap gap-3">
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-check-circle mr-1"></i> Conta Verificada
                    </span>
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-star mr-1"></i> Cliente desde {{ $user->created_at->format('m/Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Grid de Funcionalidades --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        {{-- Meus Pedidos --}}
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-bag text-orange-500 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Meus Pedidos</h3>
            </div>
            <p class="text-gray-600 mb-4">Acompanhe seus pedidos e visualize o histórico de compras</p>
            <a href="{{ route('customer.orders') }}" 
               class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-lg font-semibold transition duration-300 flex items-center justify-center gap-2">
                <i class="fas fa-eye"></i>
                Ver Pedidos
            </a>
        </div>

        {{-- Acompanhar Compra --}}
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-truck text-blue-500 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Acompanhar Compra</h3>
            </div>
            <p class="text-gray-600 mb-4">Acompanhe o status de entrega dos seus pedidos em tempo real</p>
            <a 
               class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg font-semibold transition duration-300 flex items-center justify-center gap-2">
                <i class="fas fa-search"></i>
                Acompanhar
            </a>
        </div>

        {{-- Histórico de Compras --}}
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-history text-green-500 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Histórico</h3>
            </div>
            <p class="text-gray-600 mb-4">Visualize todo seu histórico de compras na nossa loja</p>
            <a  
               class="w-full bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg font-semibold transition duration-300 flex items-center justify-center gap-2">
                <i class="fas fa-chart-bar"></i>
                Ver Histórico
            </a>
        </div>
    </div>

    {{-- Informações da Conta e Últimos Pedidos --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Informações da Conta --}}
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-user-circle text-orange-500"></i>
                Informações da Conta
            </h2>

            <div class="space-y-4">
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="font-semibold text-gray-700">Nome completo</span>
                    <span class="text-gray-600">{{ $user->name }}</span>
                </div>
                
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="font-semibold text-gray-700">E-mail</span>
                    <span class="text-gray-600">{{ $user->email }}</span>
                </div>
                
                @if($user->phone)
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="font-semibold text-gray-700">Telefone</span>
                    <span class="text-gray-600">{{ $user->phone }}</span>
                </div>
                @endif
                
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="font-semibold text-gray-700">Cliente desde</span>
                    <span class="text-gray-600">{{ $user->created_at->format('d/m/Y') }}</span>
                </div>
                
                <div class="flex justify-between items-center py-3">
                    <span class="font-semibold text-gray-700">Status da conta</span>
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm font-medium">Ativa</span>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <a  
                   class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-3 rounded-lg font-semibold transition duration-300 flex items-center justify-center gap-2">
                    <i class="fas fa-edit"></i>
                    Editar Perfil
                </a>
            </div>
        </div>

        {{-- Últimos Pedidos --}}
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-clock text-orange-500"></i>
                Últimos Pedidos
            </h2>

                <div class="space-y-4">
                    {{-- @foreach($recentOrders as $order)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-orange-300 transition duration-300">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-semibold text-gray-900">Pedido #{{ $order->id }}</h3>
                                <p class="text-sm text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium 
                                @if($order->status == 'completed') bg-green-100 text-green-800
                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-orange-500">R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                            <a href="{{ route('customer.order-details', $order->id) }}" 
                               class="text-orange-500 hover:text-orange-600 font-semibold text-sm flex items-center gap-1">
                                Ver detalhes
                                <i class="fas fa-chevron-right text-xs"></i>
                            </a>
                        </div>
                    </div> --}}
                </div>
                
                <div class="mt-6 text-center">
                    <a href="{{ route('customer.orders') }}" 
                       class="text-orange-500 hover:text-orange-600 font-semibold flex items-center justify-center gap-2">
                        Ver todos os pedidos
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="text-center py-8">
                    <i class="fas fa-shopping-cart text-gray-300 text-4xl mb-4"></i>
                    <p class="text-gray-500 mb-4">Você ainda não fez nenhum pedido</p>
                    <a href="{{ route('site.products') }}" 
                       class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg font-semibold transition duration-300 inline-flex items-center gap-2">
                        <i class="fas fa-shopping-bag"></i>
                        Fazer Primeira Compra
                    </a>
                </div>
        </div>
    </div>

    {{-- Botões de ação --}}
    <div class="mt-8 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white rounded-xl shadow-lg p-6 border border-gray-200">
        <a href="{{ route('site.home') }}"
           class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-800 px-8 py-3 rounded-lg font-semibold transition duration-300 flex items-center justify-center gap-2">
            <i class="fas fa-arrow-left"></i>
            Voltar à Loja
        </a>

        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <a 
               class="w-full sm:w-auto bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition duration-300 flex items-center justify-center gap-2">
                <i class="fas fa-headset"></i>
                Suporte
            </a>
            
            <form method="POST" action="{{ route('customer.logout') }}" class="w-full sm:w-auto">
                @csrf
                <button type="submit"
                        class="w-full bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold shadow-md transition duration-300 flex items-center justify-center gap-2">
                    <i class="fas fa-sign-out-alt"></i>
                    Sair da Conta
                </button>
            </form>
        </div>
    </div>
</section>

<style>
    .hover-lift:hover {
        transform: translateY(-2px);
        transition: transform 0.2s ease-in-out;
    }
</style>

<script>
    // Adiciona efeito de hover lift nos cards
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.bg-white.rounded-xl');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.classList.add('hover-lift');
            });
            card.addEventListener('mouseleave', function() {
                this.classList.remove('hover-lift');
            });
        });
    });
</script>
@endsection