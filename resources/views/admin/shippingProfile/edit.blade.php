<x-app-layout>
    <x-header title="Editar Categoria" subtitle="Atualize as informações da categoria selecionada">
        <x-slot name="iconSlot">
            <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 4H10V10H4V4Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M14 4H20V10H14V4Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" opacity="0.4" />
                <path d="M4 14H10V20H4V14Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M14 14H20V20H14V14Z" stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" opacity="0.4" />
            </svg>
        </x-slot>

        <a href="{{ route('admin.categories.index') }}"
            class="inline-flex items-center px-5 py-2.5 bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-all duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Voltar
        </a>
    </x-header>

    {{-- SweetAlert para Erros --}}
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

    {{-- Formulário de Edição --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 m-8 py-8">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="mx-auto sm:px-6 lg:px-8 p-3">
            @csrf
            @method('PUT')

            <!-- Nome -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nome da Categoria <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}"
                    class="w-full border-gray-300 rounded-xl shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 transition"
                    placeholder="Ex: Motores Elétricos" required>
            </div>



            <!-- Categoria Pai -->
            <div class="mb-6">
                <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">Categoria Pai</label>
                <select id="parent_id" name="parent_id"
                    class="w-full border-gray-300 rounded-xl shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 transition">
                    <option value="">— Nenhuma —</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('parent_id', $category->parent_id) == $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- <!-- Status -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1"
                        class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 transition"
                        {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Ativa</span>
                </label>
            </div> --}}

            <!-- Ações -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.categories.index') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-300 transition">
                    Cancelar
                </a>

                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-semibold rounded-xl shadow-sm hover:from-orange-600 hover:to-amber-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Atualizar
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('name').addEventListener('input', function() {
            const name = this.value;
            const slug = name
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-');
            document.getElementById('slug').value = slug;
        });
    </script>
</x-app-layout>
