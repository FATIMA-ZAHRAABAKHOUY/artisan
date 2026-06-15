@props(['status'])

@php
    $labels = [
        'en_attente' => 'En attente',
        'confirmee' => 'Confirmée',
        'en_cours' => 'En cours',
        'en_preparation' => 'En préparation',
        'expediee' => 'Expédiée',
        'livree' => 'Livrée',
        'annulee' => 'Annulée',
        'active' => 'Actif',
        'inactive' => 'Inactif',
    ];

    $label = $labels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    $class = 'badge-status badge-' . $status;
@endphp

<span {{ $attributes->merge(['class' => $class]) }}>{{ $label }}</span>
