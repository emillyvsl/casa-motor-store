<x-app-layout>
    <x-header title="Editar Produto" subtitle="Atualize as informações do produto" gradient="from-orange-500 to-amber-500">
        <x-slot name="iconSlot">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
        </x-slot>

        <a href="{{ route('admin.products.index') }}"
            class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-200 rounded-xl font-medium text-sm text-gray-700 shadow-sm hover:bg-gray-50 transition-all duration-200 hover:shadow-md">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </a>
    </x-header>


    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let errorList = `
                <ul style="text-align: left; list-style-type: disc; padding-left: 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `;

                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: `<p style="margin-bottom: .75rem;">Ocorreram alguns erros ao processar o formulário:</p>${errorList}`,
                    confirmButtonText: 'Entendi',
                    confirmButtonColor: '#dc2626',
                    background: '#fff',
                    color: '#374151',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    },
                    customClass: {
                        popup: 'rounded-2xl shadow-lg'
                    }
                });
            });
        </script>
    @endif

    <div class="py-8">
        <div class=" mx-auto sm:px-6 lg:px-8">
            <!-- Progress Indicator -->
            <div class="mb-8">
                <div class="flex items-center justify-center space-x-4">
                    @foreach ([1 => 'Informações', 2 => 'Preço & Estoque', 3 => 'Venda Sem Estoque', 4 => 'Mídia & Finalizar'] as $step => $label)
                        <button type="button" data-step="{{ $step }}"
                            class="step-indicator flex items-center group">
                            <div
                                class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center transition-all duration-300 step-circle">
                                <span class="text-gray-600 text-sm font-bold step-number">{{ $step }}</span>
                            </div>
                            <span
                                class="ml-2 text-sm font-medium text-gray-500 step-label group-hover:text-gray-700 transition-colors duration-200">{{ $label }}</span>
                        </button>
                        @if (!$loop->last)
                            <div class="w-12 h-0.5 bg-gray-300 step-connector"></div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <form action="{{ route('admin.products.update', $product->id) }}" method="POST"
                    enctype="multipart/form-data" class="divide-y divide-gray-100" id="productForm">
                    @csrf
                    @method('PUT')

                    <!-- Etapa 1: Informações Básicas -->
                    <div class="step-content active" data-step="1">
                        <div class="p-8">
                            <div class="flex items-center mb-6">
                                <div
                                    class="w-10 h-10 bg-gradient-to-r from-orange-500 to-amber-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Informações Básicas</h3>
                                    <p class="text-sm text-gray-500">Detalhes fundamentais do produto</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div class="lg:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        Nome do Produto <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="name" value="{{ old('name', $product->name) }}"
                                            class="w-full px-4 py-3.5 bg-white border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 placeholder-gray-400"
                                            placeholder="Ex: Motor Elétrico Trifásico 10CV" required>
                                        <div class="absolute right-3 top-3.5">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </div>
                                    </div>
                                    @error('name')
                                        <span class="text-sm text-red-600 mt-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">


                                    <!-- SKU -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">Código SKU</label>
                                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                                            class="w-full px-4 py-3.5 bg-white border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 placeholder-gray-400 font-mono text-sm"
                                            placeholder="Ex: PRO-10CV-TRI">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">Categoria</label>
                                        <div class="relative">
                                            <select name="category_id"
                                                class="w-full px-4 py-3.5 bg-white border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 appearance-none">
                                                <option value="">Selecione uma categoria...</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
                                    <!-- Categoria -->


                                    <!-- Tipos de Envio -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">Tipos de
                                            Envio</label>
                                        <div id="shippingTags"
                                            class="flex flex-wrap gap-2 border border-gray-200 rounded-xl p-3 bg-white shadow-sm min-h-[56px] transition-all duration-200">
                                            @foreach ($shippingProfiles as $profile)
                                                @php
                                                    $isSelected = in_array(
                                                        $profile->id,
                                                        $product->shippingProfiles->pluck('id')->toArray(),
                                                    );
                                                @endphp
                                                <button type="button" data-id="{{ $profile->id }}"
                                                    class="shipping-tag px-4 py-2 rounded-full border text-sm transition-all duration-200 {{ $isSelected ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white border-transparent' : 'border-gray-300 text-gray-700 bg-gray-50 hover:bg-orange-50 hover:border-orange-400' }}">
                                                    {{ $profile->name }}
                                                </button>
                                            @endforeach
                                        </div>
                                        @php
                                            $shippingOld = old('shipping_profiles');
                                            if (is_string($shippingOld)) {
                                                $shippingOld = explode(',', $shippingOld);
                                            } else {
                                                $shippingOld = $product->shippingProfiles->pluck('id')->toArray();
                                            }
                                        @endphp
                                        <input type="hidden" name="shipping_profiles" id="selectedShippingProfiles"
                                            value="{{ implode(',', (array) $shippingOld) }}">

                                        <p class="text-xs text-gray-500 mt-2">Clique para selecionar os tipos de envio
                                            disponíveis</p>
                                    </div>
                                </div>

                                <!-- Descrição -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">Descrição do
                                        Produto</label>
                                    <textarea name="description" rows="4"
                                        class="w-full px-4 py-3.5 bg-white border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 placeholder-gray-400 resize-none"
                                        placeholder="Descreva as características técnicas, aplicações e benefícios do produto...">{{ old('description', $product->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Navegação Etapa 1 -->
                        <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                            <div class="flex justify-end">
                                <button type="button"
                                    class="next-step inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-semibold rounded-xl shadow-sm hover:from-orange-600 hover:to-amber-600 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200">
                                    Próxima Etapa
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Etapa 2: Preços & Estoque -->
                    <div class="step-content hidden" data-step="2">
                        <div class="p-8">
                            <div class="flex items-center mb-6">
                                <div
                                    class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Preços & Estoque</h3>
                                    <p class="text-sm text-gray-500">Configurações de preço e controle de inventário
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <!-- Preço Normal -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">
                                            Preço de Venda <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-4 text-gray-500 font-medium">R$</span>
                                            <input type="number" step="0.01" name="price"
                                                value="{{ old('price', $product->price) }}"
                                                class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                                required>
                                        </div>
                                    </div>

                                    <!-- Preço Promocional -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">Preço
                                            Promocional</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-4 text-gray-500 font-medium">R$</span>
                                            <input type="number" step="0.01" name="discount_price"
                                                value="{{ old('discount_price', $product->discount_price) }}"
                                                class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                                        </div>
                                    </div>

                                    <!-- Estoque Atual -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">Estoque
                                            Atual</label>
                                        <input type="number" name="stock"
                                            value="{{ old('stock', $product->stock) }}"
                                            class="w-full px-4 py-3.5 bg-white border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-6">
                                    <!-- Estoque Alerta -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">Alerta de
                                            Estoque</label>
                                        <input type="number" name="stock_alert_threshold"
                                            value="{{ old('stock_alert_threshold', $product->stock_alert_threshold) }}"
                                            class="w-full px-4 py-3.5 bg-white border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                            placeholder="Quantidade mínima para alerta">
                                    </div>

                                    <!-- Atributos Dinâmicos -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-3">Atributos do
                                            Produto</label>

                                        <div id="attributesContainer" class="space-y-3">
                                            <!-- Campos dinâmicos serão adicionados aqui -->
                                        </div>

                                        <div class="mt-4">
                                            <button type="button" id="addAttribute"
                                                class="inline-flex items-center px-4 py-2.5 border border-gray-300 text-gray-700 rounded-xl text-sm font-medium bg-white hover:bg-orange-50 hover:border-orange-400 hover:text-orange-600 transition-all duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                Adicionar Atributo
                                            </button>
                                        </div>

                                        @php
                                            $attributesData = old('attributes', $product->attributes ?? '{}');

                                            // se vier array, transforma em JSON
                                            if (is_array($attributesData)) {
                                                $attributesData = json_encode(
                                                    $attributesData,
                                                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
                                                );
                                            }
                                        @endphp

                                        <input type="hidden" name="attributes" id="attributesJson"
                                            value='{{ $attributesData }}'>


                                        <p class="text-xs text-gray-500 mt-3">
                                            Adicione pares de <strong>nome</strong> e <strong>valor</strong> para os
                                            atributos do produto.
                                            Exemplo: Cor: Vermelho | Tamanho: Grande | Material: Alumínio
                                        </p>
                                    </div>
                                </div>

                                <!-- Checkboxes -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label
                                        class="flex items-start space-x-3 p-4 border-2 border-gray-100 rounded-xl bg-white hover:border-orange-200 cursor-pointer transition-all duration-200">
                                        <input type="checkbox" name="is_active"
                                            {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                            class="mt-0.5 w-5 h-5 text-orange-500 bg-white border-gray-300 rounded focus:ring-orange-500 focus:ring-2">
                                        <div>
                                            <span class="block text-sm font-medium text-gray-900">Produto Ativo</span>
                                            <span class="block text-xs text-gray-500 mt-1">Visível no catálogo</span>
                                        </div>
                                    </label>

                                    <label
                                        class="flex items-start space-x-3 p-4 border-2 border-gray-100 rounded-xl bg-white hover:border-orange-200 cursor-pointer transition-all duration-200">
                                        <input type="checkbox" name="is_featured"
                                            {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                                            class="mt-0.5 w-5 h-5 text-orange-500 bg-white border-gray-300 rounded focus:ring-orange-500 focus:ring-2">
                                        <div>
                                            <span class="block text-sm font-medium text-gray-900">Produto em
                                                Destaque</span>
                                            <span class="block text-xs text-gray-500 mt-1">Exibir na página
                                                inicial</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Navegação Etapa 2 -->
                        <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                            <div class="flex justify-between">
                                <button type="button"
                                    class="prev-step inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl shadow-sm hover:bg-gray-50 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Etapa Anterior
                                </button>
                                <button type="button"
                                    class="next-step inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-semibold rounded-xl shadow-sm hover:from-orange-600 hover:to-amber-600 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200">
                                    Próxima Etapa
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Etapa 3: Venda Sem Estoque -->
                    <div class="step-content hidden" data-step="3">
                        <div class="p-8">
                            <div class="flex items-center mb-6">
                                <div
                                    class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Venda Sem Estoque</h3>
                                    <p class="text-sm text-gray-500">Configure vendas quando o produto estiver fora de
                                        estoque</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <!-- Opção Principal -->
                                <div
                                    class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-100">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" name="allow_out_of_stock_sales"
                                            class="w-6 h-6 bg-white border-2 border-gray-300 rounded-lg mr-4 flex items-center justify-center peer-checked:bg-gradient-to-r peer-checked:from-orange-500 peer-checked:to-amber-500 peer-checked:border-transparent transition-all duration-200"
                                            id="allow_out_of_stock_sales"
                                            {{ old('allow_out_of_stock_sales', $product->allow_out_of_stock_sales) ? 'checked' : '' }}
                                            class="sr-only peer">

                                        <div>
                                            <span class="block text-lg font-bold text-gray-900">Permitir Venda Sem
                                                Estoque</span>
                                            <span class="block text-sm text-gray-600 mt-1">Clientes poderão comprar
                                                mesmo quando o produto estiver fora de estoque</span>
                                        </div>
                                    </label>
                                </div>

                                <!-- Configurações Condicionais -->
                                <div id="outOfStockSettings"
                                    class="space-y-6 {{ old('allow_out_of_stock_sales', $product->allow_out_of_stock_sales) ? '' : 'hidden' }}">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Limite de Pedidos -->
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-900 mb-2">
                                                Limite de Pedidos em Falta <span class="text-purple-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <input type="number" name="max_backorder"
                                                    value="{{ old('max_backorder', $product->max_backorder) }}"
                                                    class="w-full px-4 py-3.5 bg-white border border-purple-200 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                                    placeholder="Ex: 50"
                                                    {{ old('allow_out_of_stock_sales', $product->allow_out_of_stock_sales) ? '' : 'disabled' }}>
                                                <div class="absolute right-3 top-3.5">
                                                    <svg class="w-4 h-4 text-purple-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-2">Número máximo de pedidos que podem
                                                ser aceitos sem estoque</p>
                                        </div>

                                        <!-- Prazo de Entrega -->
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-900 mb-2">
                                                Prazo Adicional de Entrega <span class="text-purple-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <input type="number" name="backorder_delivery_days"
                                                    value="{{ old('backorder_delivery_days', $product->backorder_delivery_days) }}"
                                                    class="w-full px-4 py-3.5 bg-white border border-purple-200 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                                    placeholder="Ex: 15"
                                                    {{ old('allow_out_of_stock_sales', $product->allow_out_of_stock_sales) ? '' : 'disabled' }}>
                                                <span class="absolute right-12 top-4 text-gray-500 text-sm">dias</span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-2">Dias extras para entrega quando
                                                vendido sem estoque</p>
                                        </div>
                                    </div>

                                    <!-- Mensagem para Clientes -->
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-900 mb-2">Mensagem de
                                            Esgotado</label>
                                        <textarea name="out_of_stock_message"
                                            class="w-full px-4 py-3.5 bg-white border border-purple-200 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 resize-none"
                                            rows="3" placeholder="Esta mensagem será exibida para clientes quando o produto estiver esgotado..."
                                            {{ old('allow_out_of_stock_sales', $product->allow_out_of_stock_sales) ? '' : 'disabled' }}>{{ old('out_of_stock_message', $product->out_of_stock_message) }}</textarea>
                                    </div>
                                </div>

                                <!-- Informações -->
                                <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                                    <div class="flex">
                                        <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <p class="text-sm text-blue-800">
                                                <strong>Venda sem estoque</strong> permite que clientes realizem pedidos
                                                mesmo quando o produto não está disponível imediatamente.
                                                Ideal para produtos sob encomenda ou com reposição garantida.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navegação Etapa 3 -->
                        <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                            <div class="flex justify-between">
                                <button type="button"
                                    class="prev-step inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl shadow-sm hover:bg-gray-50 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Etapa Anterior
                                </button>
                                <button type="button"
                                    class="next-step inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-semibold rounded-xl shadow-sm hover:from-orange-600 hover:to-amber-600 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200">
                                    Próxima Etapa
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Etapa 4: Mídia & Finalizar -->
                    <div class="step-content hidden" data-step="4">
                        <div class="p-8">
                            <div class="flex items-center mb-6">
                                <div
                                    class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Mídia & Finalizar</h3>
                                    <p class="text-sm text-gray-500">Imagens do produto e conclusão do cadastro</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <!-- Upload de Imagens -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-3">Imagens do
                                        Produto</label>

                                    <!-- Imagens Existentes -->
                                    @if ($product->images && $product->images->count() > 0)
                                        <div class="mb-6">
                                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Imagens Atuais</h4>
                                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                                @foreach ($product->images as $image)
                                                    <div class="relative group">
                                                        <div
                                                            class="aspect-square rounded-xl border-2 border-gray-200 overflow-hidden">
                                                            <img src="{{ asset('storage/' . $image->path) }}"
                                                                class="w-full h-full object-cover">

                                                        </div>
                                                        <div
                                                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 rounded-xl flex items-center justify-center">
                                                            <button type="button"
                                                                onclick="removeExistingImage({{ $image->id }}, event)"
                                                                class="remove-image opacity-0 group-hover:opacity-100 bg-red-500 text-white p-2 rounded-full transition-all duration-200 transform scale-90 group-hover:scale-100">
                                                                <svg class="w-4 h-4" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                    </path>
                                                                </svg>
                                                            </button>

                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div
                                        class="border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center hover:border-orange-400 transition-all duration-200 bg-gray-50/50">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <h4 class="text-lg font-medium text-gray-900 mb-2">Adicionar novas imagens</h4>
                                        <p class="text-sm text-gray-500 mb-4">ou clique para selecionar os arquivos</p>
                                        <input type="file" name="images[]" multiple class="hidden"
                                            id="imageUpload">
                                        <button type="button"
                                            onclick="document.getElementById('imageUpload').click()"
                                            class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-xl shadow-sm text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                            Selecionar Imagens
                                        </button>
                                        <p class="text-xs text-gray-500 mt-3">Formatos: JPG, PNG, WEBP • Máx: 5MB por
                                            imagem</p>
                                    </div>
                                </div>

                                <!-- Preview de Novas Imagens -->
                                <div id="imagePreview" class="hidden">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Pré-visualização das Novas
                                        Imagens</h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4"
                                        id="previewContainer">
                                        <!-- Preview será inserido aqui via JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navegação Final -->
                        <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                            <div class="flex justify-between">
                                <button type="button"
                                    class="prev-step inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl shadow-sm hover:bg-gray-50 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Etapa Anterior
                                </button>
                                <div class="flex space-x-4">
                                    <button type="button" onclick="window.history.back()"
                                        class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl shadow-sm hover:bg-gray-50 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200">
                                        Cancelar
                                    </button>
                                    <button type="submit"
                                        class="inline-flex items-center px-8 py-3.5 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-bold rounded-xl shadow-lg hover:from-green-600 hover:to-emerald-600 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 hover:shadow-xl transform hover:-translate-y-0.5">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Atualizar Produto
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .attribute-row {
                transition: all 0.2s ease-in-out;
            }

            /* Garantir que os inputs fiquem alinhados verticalmente */
            .attribute-row>div {
                display: flex;
                flex-direction: column;
            }

            /* Ajuste fino para os labels */
            .attribute-row label {
                margin-bottom: 0.25rem;
            }

            /* Estilo para quando há múltiplos atributos */
            #attributesContainer:not(:empty) {
                padding: 1rem;
                background-color: #f9fafb;
                border-radius: 0.75rem;
                border: 1px solid #f3f4f6;
            }

            /* Animações suaves para adição/remoção */
            .attribute-row {
                animation: slideIn 0.3s ease-out;
            }

            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .step-content {
                display: none;
            }

            .step-content.active {
                display: block;
            }

            .shipping-tag {
                transition: all 0.2s ease;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // 🧩 Atributos dinâmicos → JSON automático
                const attributesContainer = document.getElementById('attributesContainer');
                const addAttributeBtn = document.getElementById('addAttribute');
                const attributesJsonInput = document.getElementById('attributesJson');

                // Parse inicial dos atributos existentes
                let attributes = {};
                try {
                    attributes = JSON.parse(attributesJsonInput.value || '{}');
                } catch (e) {
                    attributes = {};
                }

                // Renderizar os atributos existentes
                function renderAttributes() {
                    attributesContainer.innerHTML = '';

                    Object.entries(attributes).forEach(([key, value]) => {
                        const attributeRow = createAttributeRow(key, value);
                        attributesContainer.appendChild(attributeRow);
                    });

                    updateAttributesJson();
                }

                // Criar uma linha de atributo
                function createAttributeRow(key = '', value = '') {
                    const row = document.createElement('div');
                    row.className = 'flex items-start gap-4 attribute-row';
                    row.innerHTML = `
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Nome do Atributo</label>
                            <input type="text"
                                class="attr-key w-full px-3 py-2.5 bg-white border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 text-sm placeholder-gray-400"
                                placeholder="Ex: Cor, Tamanho, Material..."
                                value="${key}">
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-700 mb-1">Valor do Atributo</label>
                            <input type="text"
                                class="attr-value w-full px-3 py-2.5 bg-white border border-gray-200 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 text-sm placeholder-gray-400"
                                placeholder="Ex: Vermelho, Grande, Alumínio..."
                                value="${value}">
                        </div>
                        <div class="pt-6">
                            <button type="button" class="remove-attribute text-red-500 hover:text-red-600 transition-colors duration-200 p-1 rounded-lg hover:bg-red-50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    `;
                    return row;
                }

                // Atualizar JSON no campo hidden
                function updateAttributesJson() {
                    const rows = attributesContainer.querySelectorAll('.attribute-row');
                    const obj = {};

                    rows.forEach(row => {
                        const keyInput = row.querySelector('.attr-key');
                        const valueInput = row.querySelector('.attr-value');
                        const key = keyInput ? keyInput.value.trim() : '';
                        const value = valueInput ? valueInput.value.trim() : '';
                        if (key) obj[key] = value;
                    });

                    attributesJsonInput.value = JSON.stringify(obj);
                }

                // Adicionar novo atributo
                addAttributeBtn.addEventListener('click', () => {
                    const attributeRow = createAttributeRow();
                    attributesContainer.appendChild(attributeRow);

                    // Focar no primeiro input do novo atributo
                    const newInput = attributeRow.querySelector('.attr-key');
                    if (newInput) newInput.focus();
                });

                // Remover atributo
                attributesContainer.addEventListener('click', e => {
                    if (e.target.closest('.remove-attribute')) {
                        const row = e.target.closest('.attribute-row');
                        if (row) {
                            row.style.opacity = '0';
                            row.style.transform = 'translateX(-10px)';

                            setTimeout(() => {
                                row.remove();
                                updateAttributesJson();
                            }, 200);
                        }
                    }
                });

                // Atualizar JSON a cada digitação
                attributesContainer.addEventListener('input', (e) => {
                    if (e.target.matches('input[placeholder*="Nome"], input[placeholder*="Valor"]')) {
                        updateAttributesJson();
                    }
                });

                // Renderizar inicial
                renderAttributes();

                // Sistema de navegação por etapas
                const stepIndicators = document.querySelectorAll('.step-indicator');
                const stepContents = document.querySelectorAll('.step-content');
                const nextButtons = document.querySelectorAll('.next-step');
                const prevButtons = document.querySelectorAll('.prev-step');

                // 🔧 Garante que o JSON dos atributos seja atualizado antes de enviar o form
                const productForm = document.getElementById('productForm');
                if (productForm) {
                    productForm.addEventListener('submit', (e) => {
                        const rows = attributesContainer.querySelectorAll('.attribute-row');
                        const obj = {};

                        rows.forEach(row => {
                            const keyInput = row.querySelector('.attr-key');
                            const valueInput = row.querySelector('.attr-value');
                            const key = keyInput ? keyInput.value.trim() : '';
                            const value = valueInput ? valueInput.value.trim() : '';
                            if (key) obj[key] = value;
                        });

                        attributesJsonInput.value = JSON.stringify(obj);
                    });
                }

                function showStep(stepNumber) {
                    // Esconder todos os conteúdos
                    stepContents.forEach(content => {
                        content.classList.add('hidden');
                        content.classList.remove('active');
                    });

                    // Mostrar etapa atual
                    const currentStep = document.querySelector(`.step-content[data-step="${stepNumber}"]`);
                    if (currentStep) {
                        currentStep.classList.remove('hidden');
                        currentStep.classList.add('active');
                    }

                    // Atualizar indicadores
                    updateStepIndicators(stepNumber);
                }

                function updateStepIndicators(currentStep) {
                    stepIndicators.forEach(indicator => {
                        const step = parseInt(indicator.dataset.step);
                        const circle = indicator.querySelector('.step-circle');
                        const number = indicator.querySelector('.step-number');
                        const label = indicator.querySelector('.step-label');

                        if (step === currentStep) {
                            circle.className =
                                'w-8 h-8 rounded-full bg-gradient-to-r from-orange-500 to-amber-500 flex items-center justify-center transition-all duration-300 step-circle';
                            number.className = 'text-white text-sm font-bold step-number';
                            label.className = 'ml-2 text-sm font-medium text-gray-900 step-label';
                        } else if (step < currentStep) {
                            circle.className =
                                'w-8 h-8 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center transition-all duration-300 step-circle';
                            number.className = 'text-white text-sm font-bold step-number';
                            label.className = 'ml-2 text-sm font-medium text-green-600 step-label';
                        } else {
                            circle.className =
                                'w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center transition-all duration-300 step-circle';
                            number.className = 'text-gray-600 text-sm font-bold step-number';
                            label.className = 'ml-2 text-sm font-medium text-gray-500 step-label';
                        }
                    });
                }

                // Event listeners
                stepIndicators.forEach(indicator => {
                    indicator.addEventListener('click', (e) => {
                        e.preventDefault();
                        const stepNumber = parseInt(indicator.dataset.step);
                        showStep(stepNumber);
                    });
                });

                nextButtons.forEach(button => {
                    button.addEventListener('click', (e) => {
                        e.preventDefault();
                        const currentStep = parseInt(button.closest('.step-content').dataset.step);
                        const nextStep = currentStep + 1;
                        if (validateStep(currentStep)) {
                            showStep(nextStep);
                        }
                    });
                });

                prevButtons.forEach(button => {
                    button.addEventListener('click', (e) => {
                        e.preventDefault();
                        const currentStep = parseInt(button.closest('.step-content').dataset.step);
                        const prevStep = currentStep - 1;
                        showStep(prevStep);
                    });
                });

                // Validação
                function validateStep(step) {
                    switch (step) {
                        case 1:
                            const nameInput = document.querySelector('input[name="name"]');
                            if (nameInput && !nameInput.value.trim()) {
                                alert('Por favor, preencha o nome do produto.');
                                nameInput.focus();
                                return false;
                            }
                            break;
                        case 2:
                            const priceInput = document.querySelector('input[name="price"]');
                            if (priceInput && (!priceInput.value || parseFloat(priceInput.value) <= 0)) {
                                alert('Por favor, informe um preço válido para o produto.');
                                priceInput.focus();
                                return false;
                            }
                            break;
                    }
                    return true;
                }

                // Controle da venda sem estoque
                const allowOutOfStockCheckbox = document.getElementById('allow_out_of_stock_sales');
                const outOfStockSettings = document.getElementById('outOfStockSettings');

                if (allowOutOfStockCheckbox && outOfStockSettings) {
                    const outOfStockInputs = outOfStockSettings.querySelectorAll('input, textarea');

                    allowOutOfStockCheckbox.addEventListener('change', function() {
                        if (this.checked) {
                            outOfStockSettings.classList.remove('hidden');
                            outOfStockInputs.forEach(input => input.removeAttribute('disabled'));
                        } else {
                            outOfStockSettings.classList.add('hidden');
                            outOfStockInputs.forEach(input => input.setAttribute('disabled', 'disabled'));
                        }
                    });

                    // Estado inicial
                    if (allowOutOfStockCheckbox.checked) {
                        outOfStockSettings.classList.remove('hidden');
                        outOfStockInputs.forEach(input => input.removeAttribute('disabled'));
                    }
                }

                // Seleção de Tipos de Envio
                const shippingTags = document.querySelectorAll('.shipping-tag');
                const hiddenInput = document.getElementById('selectedShippingProfiles');

                if (shippingTags && hiddenInput) {
                    let selected = hiddenInput.value ? hiddenInput.value.split(',').map(Number) : [];

                    shippingTags.forEach(tag => {
                        const id = parseInt(tag.dataset.id);

                        tag.addEventListener('click', () => {
                            if (selected.includes(id)) {
                                tag.classList.remove('bg-gradient-to-r', 'from-orange-500',
                                    'to-amber-500', 'text-white', 'border-transparent');
                                tag.classList.add('bg-gray-50', 'text-gray-700', 'border-gray-300');
                                selected = selected.filter(v => v !== id);
                            } else {
                                tag.classList.remove('bg-gray-50', 'text-gray-700', 'border-gray-300');
                                tag.classList.add('bg-gradient-to-r', 'from-orange-500', 'to-amber-500',
                                    'text-white', 'border-transparent');
                                selected.push(id);
                            }

                            hiddenInput.value = selected.join(',');
                        });
                    });
                }

                // Preview de novas imagens
                const imageUpload = document.getElementById('imageUpload');
                const imagePreview = document.getElementById('imagePreview');
                const previewContainer = document.getElementById('previewContainer');

                if (imageUpload && imagePreview && previewContainer) {
                    imageUpload.addEventListener('change', function(e) {
                        const files = e.target.files;
                        previewContainer.innerHTML = '';

                        if (files.length > 0) {
                            imagePreview.classList.remove('hidden');

                            for (let file of files) {
                                if (file.type.startsWith('image/')) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        const preview = document.createElement('div');
                                        preview.className = 'relative group';
                                        preview.innerHTML = `
                                            <div class="aspect-square rounded-xl border-2 border-gray-200 overflow-hidden">
                                                <img src="${e.target.result}" class="w-full h-full object-cover">
                                            </div>
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 rounded-xl flex items-center justify-center">
                                                <button type="button" class="remove-image opacity-0 group-hover:opacity-100 bg-red-500 text-white p-2 rounded-full transition-all duration-200 transform scale-90 group-hover:scale-100">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        `;
                                        previewContainer.appendChild(preview);
                                    };
                                    reader.readAsDataURL(file);
                                }
                            }
                        } else {
                            imagePreview.classList.add('hidden');
                        }
                    });

                    // Remover preview de nova imagem
                    previewContainer.addEventListener('click', function(e) {
                        if (e.target.closest('.remove-image')) {
                            e.preventDefault();
                            e.target.closest('.relative').remove();
                            if (previewContainer.children.length === 0) {
                                imagePreview.classList.add('hidden');
                            }
                        }
                    });
                }

                // Inicializar
                showStep(1);
            });

            // Função para remover imagem existente
            function removeExistingImage(imageId, event) {
                Swal.fire({
                    title: 'Remover imagem?',
                    text: 'Tem certeza que deseja remover esta imagem?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sim, remover',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Adiciona campo hidden no form para enviar a imagem como removida
                        const form = document.getElementById('productForm');
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'removed_images[]';
                        hiddenInput.value = imageId;
                        form.appendChild(hiddenInput);

                        // Remove visualmente o bloco da imagem
                        const imageContainer = event.target.closest('.relative');
                        if (imageContainer) {
                            imageContainer.remove();
                        }
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
