<x-app-layout>
    <x-header title="Novo Banner da Home" subtitle="Cadastre campanhas e imagens para a vitrine principal">
        <x-slot name="iconSlot">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3.75 6.75h16.5m-16.5 10.5h16.5m-16.5-7.5h16.5M6 20.25l3.264-3.264a1.5 1.5 0 012.121 0L15 20.25m-7.5-9 1.314-1.314a1.5 1.5 0 012.121 0l5.814 5.814m1.5-8.25a1.125 1.125 0 100-2.25 1.125 1.125 0 000 2.25z" />
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
            <form action="{{ route('admin.home-banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                @include('admin.home-banners._form')

                <div class="flex flex-wrap justify-end gap-3">
                    <a href="{{ route('admin.home-banners.index') }}"
                        class="inline-flex items-center rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="inline-flex items-center rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:from-orange-600 hover:to-amber-600">
                        Salvar banner
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
