@extends('layouts.store')

@section('title', 'Produtos - Casa dos Motores')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-gray-900">Nossos Produtos</h1>
                        <p class="text-gray-600 mt-1">Encontre o produto ideal para suas necessidades</p>
                    </div>

                    <!-- Barra de Busca -->
                    <div class="mt-4 lg:mt-0 lg:ml-8 flex-1 max-w-md">
                        <form action="{{ route('site.products') }}" method="GET" id="searchForm">
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Buscar produtos..."
                                    class="w-full pl-4 pr-10 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                                <button type="submit"
                                    class="absolute right-3 top-3 text-gray-400 hover:text-orange-500 transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-gray-600">
                        @if ($products->total() > 0)
                            Mostrando <span
                                class="font-semibold">{{ $products->firstItem() }}-{{ $products->lastItem() }}</span>
                            de <span class="font-semibold">{{ $products->total() }}</span> produtos
                        @else
                            Nenhum produto encontrado
                        @endif
                    </p>
                </div>

                <!-- Ordenação Funcional -->
                <div class="mt-4 sm:mt-0">
                    <form action="{{ route('site.products') }}" method="GET" id="sortForm">
                        <!-- Manter outros filtros -->
                        @if (request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if (request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if (request('shipping'))
                            <input type="hidden" name="shipping" value="{{ request('shipping') }}">
                        @endif

                        <select name="sort" onchange="document.getElementById('sortForm').submit()"
                            class="border border-gray-300 rounded-lg px-8 py-2 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                            <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Mais
                                recentes</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Menor preço
                            </option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Maior preço
                            </option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nome A-Z</option>
                            <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Em destaque
                            </option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar de Filtros -->
                <div class="lg:w-80 flex-shrink-0">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-bold text-gray-900">Filtros</h2>
                            @if (request()->has('category') || request()->has('shipping') || request()->has('search') || request()->has('sort'))
                                <a href="{{ route('site.products') }}"
                                    class="text-sm text-orange-600 hover:text-orange-700 font-medium">
                                    Limpar filtros
                                </a>
                            @endif
                        </div>

                        <!-- Filtro por Categoria -->
                        <div class="mb-8">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                Categorias
                            </h3>
                            <div class="space-y-2">
                                @foreach ($categories as $category)
                                    <label class="flex items-center group cursor-pointer">
                                        <input type="radio" name="category" value="{{ $category->id }}"
                                            {{ request('category') == $category->id ? 'checked' : '' }}
                                            onchange="updateFilter('category', this.value)" class="hidden">
                                        <div
                                            class="w-4 h-4 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center group-hover:border-orange-400 transition-colors duration-200">
                                            <div
                                                class="w-2 h-2 rounded-full bg-orange-500 opacity-0 transition-opacity duration-200">
                                            </div>
                                        </div>
                                        <span
                                            class="text-sm text-gray-700 group-hover:text-gray-900 transition-colors duration-200 flex-1 truncate">{{ $category->name }}</span>
                                        <span
                                            class="ml-2 text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full min-w-[2rem] text-center">
                                            {{ $category->products_count }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Filtro por Tipo de Entrega -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                                Tipos de Entrega
                            </h3>
                            <div class="space-y-2">
                                @foreach ($shippingProfiles as $shipping)
                                    <label class="flex items-center group cursor-pointer">
                                        <input type="radio" name="shipping" value="{{ $shipping->id }}"
                                            {{ request('shipping') == $shipping->id ? 'checked' : '' }}
                                            onchange="updateFilter('shipping', this.value)" class="hidden">
                                        <div
                                            class="w-4 h-4 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center group-hover:border-orange-400 transition-colors duration-200">
                                            <div
                                                class="w-2 h-2 rounded-full bg-orange-500 opacity-0 transition-opacity duration-200">
                                            </div>
                                        </div>
                                        <span
                                            class="text-sm text-gray-700 group-hover:text-gray-900 transition-colors duration-200">{{ $shipping->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lista de Produtos -->
                <div class="flex-1">
                    @if ($products->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach ($products as $product)
                                <div
                                    class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-300 group">
                                    <!-- Imagem do Produto -->
                                    <div class="relative aspect-square bg-gray-100 overflow-hidden">
                                        @if ($product->images->first())
                                            <img src="{{ asset('storage/' . $product->images->first()->path) }}"
                                                alt="{{ $product->name }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif

                                        <!-- Badges -->
                                        <div class="absolute top-3 left-3 flex flex-col gap-2">
                                            @if ($product->is_featured)
                                                <span
                                                    class="bg-orange-500 text-white text-xs font-medium px-2 py-1 rounded-full">
                                                    Destaque
                                                </span>
                                            @endif
                                            @if ($product->stock == 0 && !$product->allow_out_of_stock_sales)
                                                <span
                                                    class="bg-red-500 text-white text-xs font-medium px-2 py-1 rounded-full">
                                                    Esgotado
                                                </span>
                                            @endif
                                            @if ($product->discount_price)
                                                <span
                                                    class="bg-green-500 text-white text-xs font-medium px-2 py-1 rounded-full">
                                                    Promoção
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Informações do Produto -->
                                    <div class="p-4">
                                        <!-- Categoria -->
                                        @if ($product->category)
                                            <div class="flex items-center mb-2">
                                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                                    {{ $product->category->name }}
                                                </span>
                                            </div>
                                        @endif

                                        <!-- Nome -->
                                        <h3
                                            class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-orange-600 transition-colors duration-200">
                                            <a href="{{ route('site.products.show', $product->slug) }}"
                                                class="hover:no-underline">
                                                {{ $product->name }}
                                            </a>
                                        </h3>

                                        <!-- Descrição -->
                                        @if ($product->description)
                                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                                {{ Str::limit($product->description, 80) }}
                                            </p>
                                        @endif

                                        <!-- Preço -->
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center space-x-2">
                                                @if ($product->discount_price)
                                                    <span class="text-lg font-bold text-gray-900">
                                                        R$ {{ number_format($product->discount_price, 2, ',', '.') }}
                                                    </span>
                                                    <span class="text-sm text-gray-500 line-through">
                                                        R$ {{ number_format($product->price, 2, ',', '.') }}
                                                    </span>
                                                    @php
                                                        $discountPercentage = round(
                                                            (($product->price - $product->discount_price) /
                                                                $product->price) *
                                                                100,
                                                        );
                                                    @endphp
                                                    <span
                                                        class="text-xs text-green-600 font-medium bg-green-50 px-1.5 py-0.5 rounded">
                                                        -{{ $discountPercentage }}%
                                                    </span>
                                                @else
                                                    <span class="text-lg font-bold text-gray-900">
                                                        R$ {{ number_format($product->price, 2, ',', '.') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Estoque e Entrega -->
                                        <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                            <div class="flex items-center">
                                                @if ($product->stock > 0)
                                                    <svg class="w-4 h-4 text-green-500 mr-1" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>{{ $product->stock }} em estoque</span>
                                                @elseif($product->allow_out_of_stock_sales)
                                                    <svg class="w-4 h-4 text-orange-500 mr-1" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>Sob encomenda</span>
                                                @else
                                                    <svg class="w-4 h-4 text-red-500 mr-1" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    <span>Esgotado</span>
                                                @endif
                                            </div>

                                            <!-- Tipos de Entrega -->
                                            @if ($product->shippingProfiles->count() > 0)
                                                <div class="flex items-center"
                                                    title="{{ $product->shippingProfiles->pluck('name')->join(', ') }}">
                                                    <svg class="w-4 h-4 text-gray-400 mr-1" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                    </svg>
                                                    <span>{{ $product->shippingProfiles->count() }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Botão de Comprar -->
                                        <div class="mt-4">

                                            @if ($product->stock > 0 || $product->allow_out_of_stock_sales)
                                                <button
                                                    class="w-full bg-gradient-to-r from-orange-500 to-amber-500 text-white font-semibold py-2.5 px-4 rounded-xl hover:from-orange-600 hover:to-amber-600 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5">
                                                    Adicionar ao Carrinho
                                                </button>
                                            @else
                                                <button
                                                    class="w-full bg-gray-300 text-gray-600 font-semibold py-2.5 px-4 rounded-xl cursor-not-allowed"
                                                    disabled>
                                                    Produto Esgotado
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginação -->
                        @if ($products->hasPages())
                            <div class="mt-8">
                                {{ $products->withQueryString()->links() }}
                            </div>
                        @endif
                    @else
                        <!-- Estado Vazio -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-14 text-center">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum produto encontrado</h3>
                            <p class="text-gray-600 mb-6">Tente ajustar os filtros ou termos de busca</p>
                            <a href="{{ route('site.products') }}"
                                class="inline-flex items-center px-6 py-3 mb-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-orange-500 hover:bg-orange-600 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200">
                                Ver todos os produtos
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        input:checked+div {
            border-color: #f97316;
        }

        input:checked+div>div {
            opacity: 1;
        }
    </style>

    <script>
        function updateFilter(type, value) {
            const url = new URL(window.location.href);
            const params = new URLSearchParams(url.search);

            if (value) {
                params.set(type, value);
            } else {
                params.delete(type);
            }

            // Manter outros parâmetros
            const search = document.querySelector('input[name="search"]').value;
            if (search) {
                params.set('search', search);
            }

            const sort = document.querySelector('select[name="sort"]').value;
            if (sort && sort !== 'newest') {
                params.set('sort', sort);
            }

            window.location.href = `${url.pathname}?${params.toString()}`;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Garantir que os checkboxes fiquem visíveis quando selecionados
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                if (radio.checked) {
                    const visual = radio.nextElementSibling;
                    visual.style.borderColor = '#f97316';
                    visual.querySelector('div').style.opacity = '1';
                }
            });

            // Busca em tempo real (opcional)
            const searchInput = document.querySelector('input[name="search"]');
            let searchTimeout;

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    document.getElementById('searchForm').submit();
                }, 500);
            });
        });
    </script>
@endsection
