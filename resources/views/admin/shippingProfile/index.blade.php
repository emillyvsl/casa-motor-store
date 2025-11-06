<x-app-layout>
    <x-header title="Tipos de Entregas" subtitle="Gerencie os tipos e prazos de entrega">
        <x-slot name="iconSlot">
            <img src="{{ asset('img/box.png') }}" alt="Ícone de Caixa"
                class="w-6 h-6 object-contain brightness-0 dark:brightness-200 group-hover:brightness-100 transition duration-200">
        </x-slot>

        <a href="{{ route('admin.shipping-profiles.create') }}"
            class="bg-gradient-to-r from-orange-500 to-amber-500 text-white px-4 py-2 rounded-xl font-semibold shadow-sm hover:from-orange-600 hover:to-amber-600 transition">
            Novo Perfil
        </a>
    </x-header>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000,
                background: '#fff',
                color: '#1f2937',
                customClass: {
                    popup: 'rounded-xl shadow-lg'
                }
            });
        </script>
    @endif

    <div class="bg-white shadow-sm rounded-2xl m-8 border border-gray-100">
        <table class="w-full text-left text-sm text-gray-700">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">Nome</th>
                    <th class="px-6 py-3">Prazo (Com Estoque)</th>
                    <th class="px-6 py-3">Prazo (Encomenda)</th>
                    <th class="px-6 py-3">Custo</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($profiles as $profile)
                    <tr class="border-t">
                        <td class="px-6 py-3 font-medium">{{ $profile->name }}</td>
                        <td class="px-6 py-3">{{ $profile->delivery_time_in_stock }} dias</td>
                        <td class="px-6 py-3">{{ $profile->delivery_time_backorder }} dias</td>
                        <td class="px-6 py-3">R$ {{ number_format($profile->shipping_cost, 2, ',', '.') }}</td>
                        <td class="px-6 py-3">
                            @if ($profile->is_active)
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-lg">Ativo</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-lg">Inativo</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-right space-x-2">
                            <a href="{{ route('admin.shipping-profiles.edit', $profile) }}"
                                class="text-orange-600 hover:text-orange-800 font-medium">Editar</a>

                            <form action="{{ route('admin.shipping-profiles.destroy', $profile) }}" method="POST"
                                class="inline" onsubmit="return confirmDelete(event)">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="text-red-600 hover:text-red-800 font-medium">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-6">
            {{ $profiles->links() }}
        </div>
    </div>

    <script>
        function confirmDelete(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Excluir este perfil?',
                text: 'Esta ação não poderá ser desfeita.',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) e.target.submit();
            });
        }
    </script>
</x-app-layout>
