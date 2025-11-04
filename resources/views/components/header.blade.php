<div class="mx-9 flex flex-col sm:flex-row sm:items-center pt-8  sm:justify-between border-b border-gray-200 pb-4 mb-6 mx-auto sm:px-6 lg:px-8 ">
    <div class="flex items-center space-x-3">
        @if($icon || isset($iconSlot))
            <div class="p-3 bg-gradient-to-r from-orange-500 to-amber-500 rounded-lg shadow-sm text-white flex items-center justify-center">
                {!! $icon ?? $iconSlot !!}
            </div>
        @endif

        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $title ?? 'Página' }}</h1>
            @if($subtitle)
                <p class="text-sm text-gray-500">{{ $subtitle }}</p>
            @endif
        </div>
    </div>

    <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
        {{ $slot }}
    </div>
</div>

@isset($iconSlot)
    @slot('iconSlot')
        {{ $iconSlot }}
    @endslot
@endisset
