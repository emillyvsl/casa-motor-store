<x-app-layout>
    <x-header title="Editar Banner da Home" subtitle="Ajuste textos, links e imagem da campanha">
        <x-slot name="iconSlot">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
        </x-slot>

        <a href="{{ route('admin.home-banners.index') }}"
            class="inline-flex items-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition-all duration-200 hover:bg-gray-50 hover:shadow-md">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Voltar
        </a>
    </x-header>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Revise os campos',
                    html: `{!! collect($errors->all())->map(fn ($error) => "<div style='text-align:left;margin-bottom:6px;'>- {$error}</div>")->implode('') !!}`,
                    confirmButtonText: 'Entendi',
                    confirmButtonColor: '#ea580c',
                    background: '#fff',
                    color: '#374151',
                    customClass: {
                        popup: 'rounded-2xl shadow-lg'
                    }
                });
            });
        </script>
    @endif

    <div class="py-8">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('admin.home-banners.update', $banner) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                @include('admin.home-banners._form', ['banner' => $banner])

                <div class="flex flex-wrap justify-end gap-3">
                    <a href="{{ route('admin.home-banners.index') }}"
                        class="inline-flex items-center rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="inline-flex items-center rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:from-orange-600 hover:to-amber-600">
                        Atualizar banner
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
