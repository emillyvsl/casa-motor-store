@extends('layouts.store')

@section('title', $product->name . ' - Casa dos Motores')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Breadcrumb -->
        <div class="bg-white border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li>
                            <a href="{{ route('site.products') }}" class="text-gray-400 hover:text-gray-500">
                                <svg class="flex-shrink-0 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <a href="{{ route('site.products') }}"
                                    class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Produtos</a>
                            </div>
                        </li>
                        @if ($product->category)
                            <li>
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span
                                        class="ml-4 text-sm font-medium text-gray-500">{{ $product->category->name }}</span>
                                </div>
                            </li>
                        @endif
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="ml-4 text-sm font-medium text-gray-900 truncate">{{ $product->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
                <!-- Galeria de Imagens -->
                <div class="flex flex-col-reverse">
                    <!-- Miniaturas -->
                    @if ($product->images->count() > 1)
                        <div class="hidden mt-6 w-full max-w-2xl mx-auto sm:block lg:max-w-none">
                            <div class="grid grid-cols-4 gap-2" aria-orientation="horizontal" role="tablist">
                                @foreach ($product->images as $index => $image)
                                    <button id="tabs-1-tab-{{ $index }}"
                                        class="thumbnail-btn relative h-24 bg-white rounded-md flex items-center justify-center text-sm font-medium uppercase text-gray-900 cursor-pointer hover:bg-gray-50 focus:outline-none focus:ring focus:ring-offset-4 focus:ring-opacity-50 {{ $index === 0 ? 'ring-2 ring-orange-500' : 'ring-1 ring-gray-200' }}"
                                        aria-controls="tabs-1-panel-{{ $index }}"
                                        aria-selected="{{ $index === 0 ? 'true' : 'false' }}" role="tab" type="button"
                                        data-index="{{ $index }}">
                                        <span class="sr-only">{{ $product->name }} - Imagem {{ $index + 1 }}</span>
                                        <img src="{{ asset('storage/' . $image->path) }}" alt=""
                                            class="w-full h-full object-center object-cover rounded">
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Imagem Principal -->
                    <div class="w-full aspect-w-1 aspect-h-1">
                        <div id="tabs-1-panel-0" class="relative">
                            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-200">
                                <img id="main-image"
                                    src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->path) : asset('images/placeholder-product.jpg') }}"
                                    alt="{{ $product->name }}" class="w-full h-full object-center object-cover">
                            </div>

                            <!-- Badges -->
                            <div class="absolute top-4 left-4 flex flex-col gap-2">
                                @if ($product->is_featured)
                                    <span
                                        class="bg-orange-500 text-white text-xs font-medium px-3 py-1.5 rounded-full shadow-lg">
                                        🏆 Destaque
                                    </span>
                                @endif
                                @if ($product->discount_price)
                                    <span
                                        class="bg-green-500 text-white text-xs font-medium px-3 py-1.5 rounded-full shadow-lg">
                                        🔥
                                        {{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                                        OFF
                                    </span>
                                @endif
                                @if ($product->stock == 0 && !$product->allow_out_of_stock_sales)
                                    <span
                                        class="bg-red-500 text-white text-xs font-medium px-3 py-1.5 rounded-full shadow-lg">
                                        ⚠️ Esgotado
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informações do Produto -->
                <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                    <!-- Categoria -->
                    @if ($product->category)
                        <div class="mb-4">
                            <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                {{ $product->category->name }}
                            </span>
                        </div>
                    @endif

                    <!-- Nome do Produto -->
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $product->name }}</h1>

                    <!-- Avaliações -->
                    <div class="mt-3 flex items-center">
                        <div class="flex items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                @if (isset($ratingStats) && $ratingStats['total'] > 0)
                                    <div class="mt-3 flex items-center">
                                        <div class="flex items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($ratingStats['average']))
                                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.972a1 1 0 00.95.69h4.174c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.286 3.972c.3.921-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.197-1.54-1.118l1.286-3.972a1 1 0 00-.364-1.118L2.05 9.399c-.783-.57-.38-1.81.588-1.81h4.174a1 1 0 00.95-.69l1.286-3.972z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-gray-300" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.972a1 1 0 00.95.69h4.174c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.286 3.972c.3.921-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.197-1.54-1.118l1.286-3.972a1 1 0 00-.364-1.118L2.05 9.399c-.783-.57-.38-1.81.588-1.81h4.174a1 1 0 00.95-.69l1.286-3.972z" />
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-sm text-gray-600">
                                            {{ $ratingStats['average'] }}/5 ({{ $ratingStats['total'] }} avaliações)
                                        </span>
                                    </div>
                                @endif

                            @endfor
                        </div>
                        <p class="ml-2 text-sm text-gray-900">{{ number_format($ratingStats['average'], 1) }}</p>
                        <span aria-hidden="true" class="mx-2 text-gray-300">&middot;</span>
                        <a href="#reviews" class="text-sm font-medium text-orange-600 hover:text-orange-500">
                            {{ $ratingStats['total'] }} avaliações
                        </a>
                    </div>

                    <!-- Preço -->
                    <div class="mt-6">
                        @if ($product->discount_price)
                            <div class="flex items-center space-x-4">
                                <p class="text-4xl font-bold text-gray-900">
                                    R$ {{ number_format($product->discount_price, 2, ',', '.') }}
                                </p>
                                <p class="text-2xl text-gray-500 line-through">
                                    R$ {{ number_format($product->price, 2, ',', '.') }}
                                </p>
                            </div>
                        @else
                            <p class="text-4xl font-bold text-gray-900">
                                R$ {{ number_format($product->price, 2, ',', '.') }}
                            </p>
                        @endif
                    </div>

                    <!-- Status do Estoque -->
                    <div class="mt-4">
                        @if ($product->stock > 0)
                            <div class="flex items-center text-green-600">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="font-medium">{{ $product->stock }} unidades em estoque</span>
                            </div>
                        @elseif($product->allow_out_of_stock_sales)
                            <div class="flex items-center text-orange-600">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-medium">Sob encomenda - Entrega em
                                    {{ $product->backorder_delivery_days }} dias</span>
                            </div>
                        @else
                            <div class="flex items-center text-red-600">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span class="font-medium">Produto esgotado</span>
                            </div>
                        @endif
                    </div>

                    <!-- Descrição Curta -->
                    @if ($product->description)
                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-gray-900">Descrição</h3>
                            <div class="mt-2 space-y-4">
                                <p class="text-sm text-gray-700">{{ $product->description }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Formas de Pagamento -->
                    <div class="mt-8">
                        <h3 class="text-sm font-medium text-gray-900">Formas de Pagamento</h3>
                        <div class="mt-4 grid grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm text-gray-700">Cartão de Crédito</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm text-gray-700">PIX</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm text-gray-700">Boleto</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm text-gray-700">Débito</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tipos de Entrega -->
                    @if ($product->shippingProfiles->count() > 0)
                        <div class="mt-8">
                            <h3 class="text-sm font-medium text-gray-900">Opções de Entrega</h3>
                            <div class="mt-4 space-y-3">
                                @foreach ($product->shippingProfiles as $shipping)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $shipping->name }}</p>
                                                @if ($shipping->description)
                                                    <p class="text-xs text-gray-500">{{ $shipping->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        @if ($shipping->price > 0)
                                            <span class="text-sm font-medium text-gray-900">R$
                                                {{ number_format($shipping->price, 2, ',', '.') }}</span>
                                        @else
                                            <span class="text-sm font-medium text-green-600">Grátis</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Botão de Compra -->
                    <div class="mt-10">
                        @if ($product->stock > 0 || $product->allow_out_of_stock_sales)
                            <button
                                class="w-full bg-gradient-to-r from-orange-500 to-amber-500 text-white font-bold py-4 px-8 rounded-xl hover:from-orange-600 hover:to-amber-600 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Adicionar ao Carrinho
                                </span>
                            </button>
                        @else
                            <button
                                class="w-full bg-gray-300 text-gray-600 font-bold py-4 px-8 rounded-xl cursor-not-allowed"
                                disabled>
                                Produto Esgotado
                            </button>
                        @endif
                    </div>

                    <!-- Garantia e Segurança -->
                    <div class="mt-8 border-t border-gray-200 pt-8">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Compra Segura</p>
                                    <p class="text-xs text-gray-500">Site protegido</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-blue-500 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Garantia</p>
                                    <p class="text-xs text-gray-500">12 meses</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Descrição Detalhada e Especificações -->
            <div class="mt-16">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Descrição Detalhada -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Descrição do Produto</h2>
                            <div class="prose prose-lg max-w-none text-gray-700">
                                {!! nl2br(e($product->description)) !!}
                            </div>

                            <!-- Atributos -->
                            @if ($product->attributes && count($product->attributes) > 0)
                                <div class="mt-8">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Especificações</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach ($product->attributes as $key => $value)
                                            <div class="flex justify-between py-3 border-b border-gray-100">
                                                <span class="text-sm font-medium text-gray-900">{{ $key }}</span>
                                                <span class="text-sm text-gray-700">{{ $value }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Informações Adicionais -->
                    <div class="space-y-6">
                        <!-- SKU e Informações -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Produto</h3>
                            <div class="space-y-3">
                                @if ($product->sku)
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Código</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $product->sku }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Categoria</span>
                                    <span
                                        class="text-sm font-medium text-gray-900">{{ $product->category->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Status</span>
                                    <span
                                        class="text-sm font-medium {{ $product->is_active ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $product->is_active ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Compartilhar -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Compartilhar</h3>
                            <div class="flex space-x-4">
                                <button class="text-gray-400 hover:text-blue-600 transition-colors duration-200">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                </button>
                                <button class="text-gray-400 hover:text-blue-400 transition-colors duration-200">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.016 10.016 0 01-3.127 1.195 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.937 4.937 0 004.604 3.417 9.868 9.868 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63a9.936 9.936 0 002.46-2.543l-.047-.02z" />
                                    </svg>
                                </button>
                                <button class="text-gray-400 hover:text-red-600 transition-colors duration-200">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Avaliações -->
            <div id="reviews" class="mt-16">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between">
                        <div class="lg:w-1/3 lg:pr-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Avaliações dos Clientes</h2>

                            <!-- Resumo das Avaliações -->
                            <div class="text-center lg:text-left">
                                <div class="inline-flex items-center">
                                    <span
                                        class="text-5xl font-bold text-gray-900">{{ number_format($ratingStats['average'], 1) }}</span>
                                    <div class="ml-4">
                                        <div class="flex items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($ratingStats['average']))
                                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-gray-300" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                        <p class="text-sm text-gray-600 mt-2">Baseado em {{ $ratingStats['total'] }}
                                            avaliações</p>
                                    </div>
                                </div>

                                <!-- Distribuição das Notas -->
                                <div class="mt-6 space-y-2">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <div class="flex items-center text-sm">
                                            <span class="w-8 text-gray-600">{{ $i }} estrelas</span>
                                            <div class="flex-1 mx-2 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-yellow-400 rounded-full"
                                                    style="width: {{ $ratingStats['total'] > 0 ? ($ratingStats['distribution'][$i] / $ratingStats['total']) * 100 : 0 }}%">
                                                </div>
                                            </div>
                                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-yellow-400"
                                                    style="width: {{ $ratingStats['total'] > 0 ? ($ratingStats['distribution'][$i] / $ratingStats['total']) * 100 : 0 }}%">
                                                </div>
                                            </div>
                                            <span class="w-8 text-gray-600 text-right">{{ $ratingStats['distribution'][$i] }}</span>
                                            
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <!-- Botão para Adicionar Avaliação -->
                            @auth
                                <button onclick="openReviewModal()"
                                    class="mt-6 w-full bg-orange-500 text-white font-semibold py-3 px-4 rounded-xl hover:bg-orange-600 transition-colors duration-200">
                                    Escrever Avaliação
                                </button>
                            @else
                                <a href="{{ route('login') }}"
                                    class="mt-6 w-full inline-block text-center bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-xl hover:bg-gray-300 transition-colors duration-200">
                                    Faça login para avaliar
                                </a>
                            @endauth
                        </div>

                        <!-- Lista de Avaliações -->
                        <div class="lg:w-2/3 lg:pl-8 mt-8 lg:mt-0">
                            @if ($product->reviews->count() > 0)
                                <div class="space-y-6">
                                    @foreach ($product->reviews as $review)
                                        <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                                            <div class="flex items-start justify-between">
                                                <div class="flex items-center">
                                                    <div class="flex items-center">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= $review->rating)
                                                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor"
                                                                    viewBox="0 0 20 20">
                                                                    <path
                                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                </svg>
                                                            @else
                                                                <svg class="w-4 h-4 text-gray-300" fill="currentColor"
                                                                    viewBox="0 0 20 20">
                                                                    <path
                                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                </svg>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <span
                                                        class="ml-2 text-sm font-medium text-gray-900">{{ $review->customer->name ?? 'Cliente' }}</span>
                                                </div>
                                                <span
                                                    class="text-sm text-gray-500">{{ $review->created_at->format('d/m/Y') }}</span>
                                            </div>
                                            <p class="mt-3 text-gray-700">{{ $review->comment }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma avaliação ainda</h3>
                                    <p class="text-gray-600">Seja o primeiro a avaliar este produto!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produtos Relacionados -->
            @if ($relatedProducts->count() > 0)
                <div class="mt-16">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8">Produtos Relacionados</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($relatedProducts as $relatedProduct)
                            <div
                                class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-300">
                                <div class="aspect-w-1 aspect-h-1 bg-gray-200">
                                    <img src="{{ $relatedProduct->images->first() ? asset('storage/' . $relatedProduct->images->first()->path) : asset('images/placeholder-product.jpg') }}"
                                        alt="{{ $relatedProduct->name }}" class="w-full h-48 object-cover">
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                        <a href="{{ route('site.products.show', $relatedProduct) }}"
                                            class="hover:text-orange-600">
                                            {{ $relatedProduct->name }}
                                        </a>
                                    </h3>
                                    <div class="flex items-center justify-between">
                                        <span class="text-lg font-bold text-gray-900">
                                            R$ {{ number_format($relatedProduct->price, 2, ',', '.') }}
                                        </span>
                                        @if ($relatedProduct->discount_price)
                                            <span class="text-sm text-gray-500 line-through">
                                                R$ {{ number_format($relatedProduct->discount_price, 2, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal de Avaliação -->
    <div id="reviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-2xl bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Avaliar Produto</h3>

                <form action="{{ route('site.products.review', $product) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sua Avaliação</label>
                        <div class="flex justify-center space-x-1" id="rating-stars">
                            @for ($i = 1; $i <= 5; $i++)
                                <button type="button" class="rating-star text-2xl" data-rating="{{ $i }}">
                                    <svg class="w-8 h-8 text-gray-300 hover:text-yellow-400" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="selected-rating" required>
                    </div>

                    <div class="mb-4">
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Comentário</label>
                        <textarea name="comment" id="comment" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                            placeholder="Conte sua experiência com o produto..." required></textarea>
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <button type="button" onclick="closeReviewModal()"
                            class="px-4 py-2 text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors duration-200">
                            Enviar Avaliação
                        </button>
                    </div>
                </form>
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
    </style>

    <script>
        // Galeria de Imagens
        document.addEventListener('DOMContentLoaded', function() {
            const thumbnailButtons = document.querySelectorAll('.thumbnail-btn');
            const mainImage = document.getElementById('main-image');

            thumbnailButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const index = this.getAttribute('data-index');
                    const imageSrc = this.querySelector('img').src;

                    // Atualizar imagem principal
                    mainImage.src = imageSrc;

                    // Atualizar estado ativo
                    thumbnailButtons.forEach(btn => {
                        btn.classList.remove('ring-2', 'ring-orange-500');
                        btn.classList.add('ring-1', 'ring-gray-200');
                    });
                    this.classList.remove('ring-1', 'ring-gray-200');
                    this.classList.add('ring-2', 'ring-orange-500');
                });
            });

            // Sistema de avaliação por estrelas
            const ratingStars = document.querySelectorAll('.rating-star');
            const selectedRating = document.getElementById('selected-rating');

            ratingStars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = this.getAttribute('data-rating');
                    selectedRating.value = rating;

                    // Atualizar visual das estrelas
                    ratingStars.forEach(s => {
                        const starIndex = s.getAttribute('data-rating');
                        const svg = s.querySelector('svg');
                        if (starIndex <= rating) {
                            svg.classList.remove('text-gray-300');
                            svg.classList.add('text-yellow-400');
                        } else {
                            svg.classList.remove('text-yellow-400');
                            svg.classList.add('text-gray-300');
                        }
                    });
                });

                star.addEventListener('mouseover', function() {
                    const rating = this.getAttribute('data-rating');
                    ratingStars.forEach(s => {
                        const starIndex = s.getAttribute('data-rating');
                        const svg = s.querySelector('svg');
                        if (starIndex <= rating) {
                            svg.classList.add('text-yellow-400');
                        }
                    });
                });

                star.addEventListener('mouseout', function() {
                    const currentRating = selectedRating.value;
                    ratingStars.forEach(s => {
                        const starIndex = s.getAttribute('data-rating');
                        const svg = s.querySelector('svg');
                        if (!currentRating || starIndex > currentRating) {
                            svg.classList.remove('text-yellow-400');
                            if (!currentRating) {
                                svg.classList.add('text-gray-300');
                            }
                        }
                    });
                });
            });
        });

        function openReviewModal() {
            document.getElementById('reviewModal').classList.remove('hidden');
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.add('hidden');
            // Reset do formulário
            document.getElementById('selected-rating').value = '';
            document.getElementById('comment').value = '';
            // Reset das estrelas
            document.querySelectorAll('.rating-star svg').forEach(svg => {
                svg.classList.remove('text-yellow-400');
                svg.classList.add('text-gray-300');
            });
        }

        // Fechar modal ao clicar fora
        window.onclick = function(event) {
            const modal = document.getElementById('reviewModal');
            if (event.target === modal) {
                closeReviewModal();
            }
        }
    </script>
@endsection
