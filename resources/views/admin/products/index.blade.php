<x-app-layout>
    <x-header title="Catálogo de Produtos" subtitle="Gerencie e acompanhe todos os produtos do seu inventário">
        <x-slot name="iconSlot">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
        </x-slot>

        <a href="{{ route('admin.products.create') }}"
            class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 text-white text-sm font-semibold rounded-xl shadow-sm hover:from-orange-600 hover:to-amber-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Novo Produto
        </a>
    </x-header>

    <div class="py-8">
        <div class="mx-auto sm:px-6 lg:px-8"> <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-blue-50 text-blue-500 mr-4"> <svg class="w-5 h-5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg> </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total de Produtos</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $products->total() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-green-50 text-green-500 mr-4"> <svg class="w-5 h-5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg> </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Produtos Ativos</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $products->where('is_active', true)->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-red-50 text-red-500 mr-4"> <svg class="w-5 h-5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg> </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Estoque Baixo</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $products->where('stock', '<', 5)->count() }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-amber-50 text-amber-500 mr-4"> <svg class="w-5 h-5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg> </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Em Destaque</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $products->where('is_featured', true)->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @if (session('success') || session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        @if (session('success'))
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: "{{ session('success') }}",
                                showConfirmButton: false,
                                timer: 2500,
                                timerProgressBar: true,
                                background: '#10B981',
                                color: '#fff',
                                customClass: {
                                    popup: 'rounded-xl shadow-lg'
                                }
                            });
                        @endif
                        @if (session('error'))
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: "{{ session('error') }}",
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                background: '#EF4444',
                                color: '#fff',
                                customClass: {
                                    popup: 'rounded-xl shadow-lg'
                                }
                            });
                        @endif
                    });
                </script>
            @endif
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    @if ($products->count() > 0) <!-- Mobile View -->
                        <div class="block md:hidden space-y-4">
                            @foreach ($products as $product)
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-900 text-lg">{{ $product->name }}
                                            </h3>
                                            <p class="text-sm text-gray-500 mt-1">ID: {{ $product->id }}</p>
                                        </div>
                                        <div class="ml-4">
                                            @if ($product->is_active)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Ativo </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Inativo </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <p class="text-gray-500">Categoria</p>
                                            <p class="font-medium text-gray-900">
                                                {{ $product->category?->name ?? '—' }} </p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Preço</p>
                                            <p class="font-medium text-gray-900">R$
                                                {{ number_format($product->price, 2, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Estoque</p>
                                            <p class="font-medium text-gray-900">{{ $product->stock }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Status</p>
                                            <div class="flex items-center">
                                                @if ($product->stock > 10)
                                                    <span class="flex h-2 w-2 mr-1"> <span
                                                            class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                                    </span> <span class="text-xs text-green-600">Em estoque</span>
                                                @elseif ($product->stock > 0)
                                                    <span class="flex h-2 w-2 mr-1"> <span
                                                            class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                                    </span> <span class="text-xs text-amber-600">Estoque
                                                        baixo</span>
                                                @else
                                                    <span class="flex h-2 w-2 mr-1"> <span
                                                            class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                                    </span> <span class="text-xs text-red-600">Sem estoque</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-between mt-4 pt-4 border-t border-gray-200"> <a
                                            href="{{ route('admin.products.edit', $product) }}"
                                            class="inline-flex items-center text-orange-600 hover:text-orange-800 font-medium text-sm transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg> Editar </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                            class="inline"> @csrf @method('DELETE') <button type="submit"
                                                onclick="return confirm('Deseja excluir este produto?')"
                                                class="inline-flex items-center text-red-600 hover:text-red-800 font-medium text-sm transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg> Excluir </button> </form>
                                    </div>
                                </div>
                            @endforeach
                        </div> <!-- Desktop View -->
                        <div class="hidden md:block overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Produto</th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Categoria</th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Preço</th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Estoque</th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th scope="col"
                                            class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($products as $product)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div
                                                        class="flex-shrink-0 h-10 w-10 bg-gradient-to-r from-orange-100 to-amber-100 rounded-lg flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-orange-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                        </svg>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-semibold text-gray-900">
                                                            {{ $product->name }}</div>
                                                        <div class="text-sm text-gray-500">ID: {{ $product->id }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $product->category?->name ?? '—' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-semibold text-gray-900">R$
                                                    {{ number_format($product->price, 2, ',', '.') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center"> <span
                                                        class="text-sm font-medium text-gray-900 mr-2">{{ $product->stock }}</span>
                                                    @if ($product->stock > 10)
                                                        <span class="flex h-2 w-2"> <span
                                                                class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                                        </span>
                                                    @elseif ($product->stock > 0)
                                                        <span class="flex h-2 w-2"> <span
                                                                class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                                        </span>
                                                    @else
                                                        <span class="flex h-2 w-2"> <span
                                                                class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($product->is_active)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Ativo </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Inativo </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex justify-end space-x-3"> <a
                                                        href="{{ route('admin.products.edit', $product) }}"
                                                        class="text-orange-600 hover:text-orange-900 transition-colors duration-200 inline-flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg> Editar </a>
                                                    <form action="{{ route('admin.products.destroy', $product) }}"
                                                        method="POST" class="inline"> @csrf @method('DELETE')
                                                        <button type="submit"
                                                            onclick="return confirm('Deseja excluir este produto?')"
                                                            class="text-red-600 hover:text-red-900 transition-colors duration-200 inline-flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg> Excluir </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- Pagination -->
                        <div class="mt-6"> {{ $products->links() }} </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <div
                                class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum produto cadastrado</h3>
                            <p class="text-gray-500 mb-6">Comece adicionando seu primeiro produto ao catálogo</p>
                            <a href="{{ route('admin.products.create') }}"
                                class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-semibold rounded-xl shadow-sm hover:from-orange-600 hover:to-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg> Cadastrar Primeiro Produto </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <style>
        /* Custom styles for pagination to match the new design */
        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 0;
        }

        .pagination li {
            margin: 0 2px;
        }

        .pagination li a,
        .pagination li span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.5rem;
            height: 2.5rem;
            padding: 0 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            background: white;
            color: #374151;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .pagination li a:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .pagination li.active span {
            background: linear-gradient(to right, #f97316, #f59e0b);
            border-color: transparent;
            color: white;
        }

        .pagination li.disabled span {
            background: #f9fafb;
            color: #9ca3af;
            border-color: #e5e7eb;
        }
    </style>
</x-app-layout>
