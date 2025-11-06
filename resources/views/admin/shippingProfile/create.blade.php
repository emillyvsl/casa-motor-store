<x-app-layout>
    <x-header title="Novo Perfil de Frete" subtitle="Adicione um novo tipo e prazo de entrega">
        <x-slot name="iconSlot">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
        </x-slot>

        <a href="{{ route('admin.shipping-profiles.index') }}"
            class="inline-flex items-center px-5 py-2.5 bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-all duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Voltar
        </a>
    </x-header>

    {{-- Alerta de erro com SweetAlert --}}
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
                    customClass: {
                        popup: 'rounded-2xl shadow-lg'
                    },
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
            });
        </script>
    @endif

    {{-- Formulário --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 m-8 py-8">
        <form action="{{ route('admin.shipping-profiles.store') }}" method="POST"
            class="mx-auto sm:px-6 lg:px-8 p-3 space-y-6">
            @csrf

            {{-- Nome --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nome do Perfil <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    placeholder="Ex: Padrão Nacional"
                    class="w-full border-gray-300 rounded-xl shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 transition"
                    required>
            </div>

            {{-- Prazo com estoque --}}
            <div>
                <label for="delivery_time_in_stock" class="block text-sm font-medium text-gray-700 mb-2">
                    Prazo de entrega com estoque (dias úteis) <span class="text-red-500">*</span>
                </label>
                <input type="number" id="delivery_time_in_stock" name="delivery_time_in_stock"
                    value="{{ old('delivery_time_in_stock', 3) }}" min="0"
                    class="w-full border-gray-300 rounded-xl shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 transition"
                    required>
            </div>

            {{-- Prazo sob encomenda --}}
            <div>
                <label for="delivery_time_backorder" class="block text-sm font-medium text-gray-700 mb-2">
                    Prazo sob encomenda (dias úteis) <span class="text-red-500">*</span>
                </label>
                <input type="number" id="delivery_time_backorder" name="delivery_time_backorder"
                    value="{{ old('delivery_time_backorder', 10) }}" min="0"
                    class="w-full border-gray-300 rounded-xl shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 transition"
                    required>
            </div>

            {{-- Custo --}}
            <div>
                <label for="shipping_cost" class="block text-sm font-medium text-gray-700 mb-2">
                    Custo fixo (R$) <span class="text-red-500">*</span>
                </label>
                <input type="number" step="0.01" id="shipping_cost" name="shipping_cost"
                    value="{{ old('shipping_cost', 0) }}"
                    class="w-full border-gray-300 rounded-xl shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 transition"
                    required>
            </div>

            {{-- Tipo --}}
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de perfil <span class="text-red-500">*</span>
                </label>
                <select id="type" name="type"
                    class="w-full border-gray-300 rounded-xl shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 transition"
                    required>
                    <option value="default" @selected(old('type') == 'default')>Padrão</option>
                    <option value="express" @selected(old('type') == 'express')>Expresso</option>
                    <option value="backorder" @selected(old('type') == 'backorder')>Sob Encomenda</option>
                </select>
            </div>

            {{-- Descrição --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Descrição (opcional)
                </label>
                <textarea id="description" name="description" rows="3"
                    class="w-full border-gray-300 rounded-xl shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 transition"
                    placeholder="Ex: Entregas realizadas em até 3 dias úteis para capitais.">{{ old('description') }}</textarea>
            </div>

            {{-- Ativo --}}
            <div class="flex items-center">
                <input type="checkbox" id="is_active" name="is_active" value="1"
                    class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500"
                    @checked(old('is_active', true))>
                <label for="is_active" class="ml-2 text-sm text-gray-700">Ativo</label>
            </div>

            {{-- Ações --}}
            <div class="flex justify-end space-x-4 pt-4">
                <a href="{{ route('admin.shipping-profiles.index') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-300 transition">
                    Cancelar
                </a>

                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-semibold rounded-xl shadow-sm hover:from-orange-600 hover:to-amber-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Salvar Perfil
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
