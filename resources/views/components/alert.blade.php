@props(['type' => 'success', 'dismissible' => true])

<div {{ $attributes->merge(['class' => 'alert alert-tissu alert-' . $type . ($dismissible ? ' alert-dismissible fade show' : ''), 'role' => 'alert']) }}>
    {{ $slot }}
    @if ($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    @endif
</div>
