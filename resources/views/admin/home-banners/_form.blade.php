@php
    $banner = $banner ?? null;
    $isActive = old('is_active', $banner?->is_active ?? true);
@endphp

<div class="grid gap-8">
    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-orange-600">Imagem</p>
                <h2 class="mt-2 text-2xl font-bold text-gray-900">Arte do banner</h2>
                <p class="mt-2 text-sm leading-6 text-gray-500">
                    Use uma imagem horizontal de boa qualidade. O ideal e um formato amplo para preencher a vitrine principal.
                </p>
            </div>

            <div class="rounded-2xl border border-dashed border-orange-200 bg-orange-50/50 p-4">
                <div id="bannerPreview" class="overflow-hidden rounded-2xl bg-zinc-950">
                    @if (!empty($banner?->image_path))
                        <img
                            id="bannerPreviewImage"
                            src="{{ asset('storage/' . $banner?->image_path) }}"
                            alt="{{ $banner?->image_alt ?? 'Banner' }}"
                            class="aspect-[16/10] w-full object-cover">
                    @else
                        <div
                            id="bannerPreviewPlaceholder"
                            class="flex aspect-[16/10] items-center justify-center px-6 text-center text-sm font-medium text-zinc-500">
                            A pré-visualização do banner aparecerá aqui assim que você escolher a imagem.
                        </div>
                        <img id="bannerPreviewImage" src="" alt="" class="hidden aspect-[16/10] w-full object-cover">
                    @endif
                </div>
            </div>

            <div class="mt-4 space-y-4">
                <div>
                    <label for="image" class="mb-2 block text-sm font-semibold text-gray-900">
                        {{ empty($banner?->image_path) ? 'Imagem do banner' : 'Trocar imagem do banner' }}
                    </label>
                    <input
                        id="image"
                        name="image"
                        type="file"
                        accept="image/*"
                        class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-600 shadow-sm file:mr-4 file:rounded-lg file:border-0 file:bg-orange-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-orange-700">
                </div>

                <div>
                    <label for="image_alt" class="mb-2 block text-sm font-semibold text-gray-900">Texto alternativo (acessibilidade)</label>
                    <input
                        id="image_alt"
                        name="image_alt"
                        type="text"
                        value="{{ old('image_alt', $banner?->image_alt ?? '') }}"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                        placeholder="Descreva a imagem do banner para acessibilidade"
                        required>
                </div>

                <label class="flex items-center gap-3 rounded-2xl border border-gray-200 bg-gray-50 px-4 py-4">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        @checked($isActive)
                        class="h-5 w-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                    <span>
                        <span class="block text-sm font-semibold text-gray-900">Banner ativo na home</span>
                        <span class="block text-xs text-gray-500">Desmarque para deixar o banner salvo sem exibir no site.</span>
                    </span>
                </label>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const imageInput = document.getElementById('image');
        const preview = document.getElementById('bannerPreviewImage');
        const placeholder = document.getElementById('bannerPreviewPlaceholder');

        if (!imageInput || !preview) {
            return;
        }

        imageInput.addEventListener('change', event => {
            const [file] = event.target.files;

            if (!file) {
                return;
            }

            const url = URL.createObjectURL(file);
            preview.src = url;
            preview.classList.remove('hidden');

            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        });
    });
</script>
