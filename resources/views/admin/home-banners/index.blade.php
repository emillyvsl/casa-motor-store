<x-app-layout>
    <x-header title="Banners da Home" subtitle="Gerencie a vitrine principal e as campanhas do topo da loja">
        <x-slot name="iconSlot">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3.75 6.75h16.5m-16.5 10.5h16.5m-16.5-7.5h16.5M6 20.25l3.264-3.264a1.5 1.5 0 012.121 0L15 20.25m-7.5-9 1.314-1.314a1.5 1.5 0 012.121 0l5.814 5.814m1.5-8.25a1.125 1.125 0 100-2.25 1.125 1.125 0 000 2.25z" />
            </svg>
        </x-slot>

        <a href="{{ route('admin.home-banners.create') }}"
            class="inline-flex items-center rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:from-orange-600 hover:to-amber-600">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Novo banner
        </a>
    </x-header>

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
                        background: '#16a34a',
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
                        background: '#dc2626',
                        color: '#fff',
                        customClass: {
                            popup: 'rounded-xl shadow-lg'
                        }
                    });
                @endif
            });
        </script>
    @endif

    <div class="py-8">
        <div class="mx-auto space-y-8 px-4 sm:px-6 lg:px-8">
            @if ($migrationMissing)
                <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800">
                    Rode <code class="rounded bg-amber-100 px-2 py-1 text-xs font-semibold">php artisan migrate</code>
                    para criar a tabela dos banners e liberar o gerenciador completo da home.
                </div>
            @endif

            <div class="grid gap-6 md:grid-cols-3">
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Total de banners</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Ativos na home</p>
                    <p class="mt-2 text-3xl font-bold text-green-600">{{ $stats['active'] }}</p>
                </div>
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Salvos como rascunho</p>
                    <p class="mt-2 text-3xl font-bold text-amber-600">{{ $stats['inactive'] }}</p>
                </div>
            </div>

            @if ($banners->count() > 0)
                <div class="grid gap-5 xl:grid-cols-2">
                    @foreach ($banners as $banner)
                        <article class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">
                            <div class="aspect-[16/8] bg-zinc-950">
                                <img
                                    src="{{ asset('storage/' . $banner->image_path) }}"
                                    alt="{{ $banner->image_alt ?: 'Banner' }}"
                                    class="h-full w-full object-cover">
                            </div>

                            <div class="space-y-5 p-6">
                                <div class="flex flex-col gap-3 rounded-2xl bg-gray-50 p-4 text-sm text-gray-600">
                                    <div>
                                        <span class="block text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Texto alternativo</span>
                                        <span class="mt-1 block font-medium text-gray-900">
                                            {{ $banner->image_alt ?: 'Sem descrição' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex flex-wrap justify-end gap-3 border-t border-gray-100 pt-5">
                                    <a href="{{ route('admin.home-banners.edit', $banner) }}"
                                        class="inline-flex items-center rounded-xl border border-orange-200 bg-orange-50 px-4 py-2.5 text-sm font-semibold text-orange-700 transition hover:bg-orange-100">
                                        Editar
                                    </a>
                                    <form action="{{ route('admin.home-banners.destroy', $banner) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="delete-btn inline-flex items-center rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-700 transition hover:bg-red-100">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div>
                    {{ $banners->links() }}
                </div>
            @else
                <div class="rounded-3xl border border-dashed border-gray-300 bg-white px-8 py-16 text-center shadow-sm">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-orange-100 text-orange-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5m-16.5 10.5h16.5m-16.5-7.5h16.5M6 20.25l3.264-3.264a1.5 1.5 0 012.121 0L15 20.25m-7.5-9 1.314-1.314a1.5 1.5 0 012.121 0l5.814 5.814m1.5-8.25a1.125 1.125 0 100-2.25 1.125 1.125 0 000 2.25z" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-xl font-bold text-gray-900">Nenhum banner cadastrado</h3>
                    <p class="mt-2 text-sm leading-6 text-gray-500">
                        Crie a primeira campanha para controlar o banner principal da home pelo gerenciador.
                    </p>
                    <a href="{{ route('admin.home-banners.create') }}"
                        class="mt-6 inline-flex items-center rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:from-orange-600 hover:to-amber-600">
                        Criar primeiro banner
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Excluir banner?',
                        text: 'A campanha sera removida do gerenciador e da home.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sim, excluir',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                    }).then(result => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
