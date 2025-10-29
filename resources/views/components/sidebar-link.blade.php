@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center p-2 text-sm font-medium rounded-lg bg-gray-700 text-white transition duration-150 ease-in-out'
            : 'flex items-center p-2 text-sm font-medium rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>