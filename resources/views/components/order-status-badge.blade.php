@props(['status'])

@php
    $badges = [
        'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'label' => 'Pendente'],
        'paid' => ['class' => 'bg-blue-100 text-blue-800', 'label' => 'Pago'],
        'shipped' => ['class' => 'bg-purple-100 text-purple-800', 'label' => 'Enviado'],
        'delivered' => ['class' => 'bg-green-100 text-green-800', 'label' => 'Entregue'],
        'canceled' => ['class' => 'bg-red-100 text-red-800', 'label' => 'Cancelado'],
    ];
    $badge = $badges[$status] ?? ['class' => 'bg-gray-100 text-gray-700', 'label' => ucfirst((string) $status)];
@endphp

<span {{ $attributes->class([
    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold',
    $badge['class'],
]) }}>
    {{ $badge['label'] }}
</span>
