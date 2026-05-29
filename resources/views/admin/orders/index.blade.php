<x-app-layout>
    <x-header title="Pedidos" subtitle="Gerencie e acompanhe todos os pedidos da loja">
        <x-slot name="iconSlot">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </x-slot>
    </x-header>

    <div class="py-8">
        <div class="mx-auto sm:px-6 lg:px-8">

            {{-- Alertas --}}
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 text-green-800 text-sm font-medium">
                    ✅ {{ session('success') }}
                </div>
            @endif

            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                @php
                    $statCards = [
                        ['label' => 'Total',      'value' => $stats['total'],   'color' => 'blue',   'icon' => '📋'],
                        ['label' => 'Pendentes',  'value' => $stats['pending'], 'color' => 'yellow', 'icon' => '⏳'],
                        ['label' => 'Pagos',      'value' => $stats['paid'],    'color' => 'green',  'icon' => '✅'],
                        ['label' => 'Enviados',   'value' => $stats['shipped'], 'color' => 'purple', 'icon' => '🚚'],
                        ['label' => 'Receita',    'value' => 'R$ '.number_format($stats['revenue'],2,',','.'), 'color' => 'orange', 'icon' => '💰'],
                    ];
                @endphp
                @foreach($statCards as $card)
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">{{ $card['icon'] }}</span>
                            <div>
                                <p class="text-xs font-medium text-gray-500">{{ $card['label'] }}</p>
                                <p class="text-xl font-bold text-gray-900">{{ $card['value'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Filtros --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
                <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-[180px]">
                        <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Buscar</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Número do pedido ou cliente..."
                            class="w-full rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 text-sm">
                    </div>
                    <div class="min-w-[150px]">
                        <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 text-sm">
                            <option value="">Todos</option>
                            @foreach(['pending'=>'Pendente','paid'=>'Pago','shipped'=>'Enviado','delivered'=>'Entregue','canceled'=>'Cancelado'] as $val=>$label)
                                <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">De</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Até</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 text-sm">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                            class="px-4 py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-orange-600 transition">
                            Filtrar
                        </button>
                        <a href="{{ route('admin.orders.index') }}"
                            class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200 transition">
                            Limpar
                        </a>
                    </div>
                </form>
            </div>

            {{-- Tabela --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    @if($orders->count() > 0)

                        {{-- Mobile --}}
                        <div class="block md:hidden space-y-4">
                            @foreach($orders as $order)
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <p class="font-bold text-gray-900 font-mono text-sm">{{ $order->order_number }}</p>
                                            <p class="text-sm text-gray-500">{{ $order->customer->name ?? '—' }}</p>
                                        </div>
                                        <x-order-status-badge :status="$order->status" />
                                    </div>
                                    <div class="flex justify-between text-sm mt-2">
                                        <span class="text-gray-500">{{ $order->created_at->format('d/m/Y') }}</span>
                                        <span class="font-bold text-gray-900">R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                                    </div>
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                        class="mt-3 block text-center text-sm font-semibold text-orange-600 hover:text-orange-700">
                                        Ver detalhes →
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        {{-- Desktop --}}
                        <div class="hidden md:block overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        @foreach(['Pedido','Cliente','Pagamento','Total','Status','Data','Ações'] as $col)
                                            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $col }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($orders as $order)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            {{-- Número --}}
                                            <td class="px-5 py-4 whitespace-nowrap">
                                                <span class="font-mono text-sm font-bold text-gray-900">{{ $order->order_number }}</span>
                                            </td>
                                            {{-- Cliente --}}
                                            <td class="px-5 py-4 whitespace-nowrap">
                                                <p class="text-sm font-medium text-gray-900">{{ $order->customer->name ?? '—' }}</p>
                                                <p class="text-xs text-gray-400">{{ $order->customer->email ?? '' }}</p>
                                            </td>
                                            {{-- Pagamento --}}
                                            <td class="px-5 py-4 whitespace-nowrap">
                                                @php
                                                    $pmLabels = ['pix'=>'💠 Pix','cartao'=>'💳 Cartão','boleto'=>'📄 Boleto'];
                                                @endphp
                                                <span class="text-sm text-gray-700">{{ $pmLabels[$order->payment_method] ?? $order->payment_method }}</span>
                                            </td>
                                            {{-- Total --}}
                                            <td class="px-5 py-4 whitespace-nowrap">
                                                <span class="text-sm font-bold text-gray-900">R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                                            </td>
                                            {{-- Status --}}
                                            <td class="px-5 py-4 whitespace-nowrap">
                                                @php
                                                    $badges = [
                                                        'pending'   => 'bg-yellow-100 text-yellow-800',
                                                        'paid'      => 'bg-blue-100 text-blue-800',
                                                        'shipped'   => 'bg-purple-100 text-purple-800',
                                                        'delivered' => 'bg-green-100 text-green-800',
                                                        'canceled'  => 'bg-red-100 text-red-800',
                                                    ];
                                                    $labels = [
                                                        'pending'   => '⏳ Pendente',
                                                        'paid'      => '✅ Pago',
                                                        'shipped'   => '🚚 Enviado',
                                                        'delivered' => '📦 Entregue',
                                                        'canceled'  => '❌ Cancelado',
                                                    ];
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badges[$order->status] ?? 'bg-gray-100 text-gray-700' }}">
                                                    {{ $labels[$order->status] ?? $order->status }}
                                                </span>
                                            </td>
                                            {{-- Data --}}
                                            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            {{-- Ações --}}
                                            <td class="px-5 py-4 whitespace-nowrap text-right">
                                                <a href="{{ route('admin.orders.show', $order) }}"
                                                    class="text-orange-600 hover:text-orange-800 text-sm font-semibold transition">
                                                    Ver →
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">{{ $orders->links() }}</div>

                    @else
                        <div class="text-center py-16">
                            <div class="mx-auto w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-4xl">📋</div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-1">Nenhum pedido encontrado</h3>
                            <p class="text-sm text-gray-400">Tente ajustar os filtros de busca.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
