<x-app-layout>
    <x-header title="Nova Categoria" subtitle="Adicione uma nova categoria de produtos">
        <x-slot name="iconSlot">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
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


    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 m-8 py-8">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="mx-auto sm:px-6 lg:px-8 p-3">
            @csrf

            <!-- Nome -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome da Categoria <span
                        class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    class="w-full border-gray-300 rounded-xl shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 transition"
                    placeholder="Ex: Motores Elétricos" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>


            <!-- Categoria Pai -->
            <div class="mb-6">
                <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">Categoria Pai</label>
                <select id="parent_id" name="parent_id"
                    class="w-full border-gray-300 rounded-xl shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-200 transition">
                    <option value="">— Nenhuma —</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('parent_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->


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
                    Salvar
                </button>
            </div>
        </form>
    </div>

    <script>
        // Gera o slug automaticamente ao digitar o nome
        document.getElementById('name').addEventListener('input', function() {
            const name = this.value;
            const slug = name
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '') // remove acentos
                .replace(/[^a-z0-9\s-]/g, '') // remove caracteres especiais
                .trim()
                .replace(/\s+/g, '-');
            document.getElementById('slug').value = slug;
        });
    </script>
</x-app-layout>
