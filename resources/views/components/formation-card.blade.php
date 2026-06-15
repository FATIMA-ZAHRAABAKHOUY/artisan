@props(['formation'])

<div {{ $attributes->merge(['class' => 'card card-tissu h-100']) }}>
    <a href="{{ route('formations.show', $formation) }}" class="text-decoration-none">
        @if ($formation->image)
            <img src="{{ asset('storage/' . $formation->image) }}" class="card-img-top" alt="{{ $formation->title }}">
        @else
            <div class="card-img-top d-flex align-items-center justify-content-center" style="background: var(--sable);">
                <span class="fs-1">📚</span>
            </div>
        @endif
    </a>
    <div class="card-body d-flex flex-column">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <small class="text-muted">{{ $formation->date_debut->translatedFormat('d M Y') }}</small>
            @if ($formation->is_free)
                <span class="badge bg-success">Gratuit</span>
            @endif
        </div>
        <h5 class="card-title font-serif mb-2">
            <a href="{{ route('formations.show', $formation) }}" class="text-decoration-none text-dark">{{ $formation->title }}</a>
        </h5>
        @if ($formation->artisan)
            <small class="text-muted mb-2">Par {{ $formation->artisan->user->name ?? 'Artisan' }}</small>
        @endif
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="text-muted small">📍 {{ $formation->city }}</span>
        </div>
        @php
            $spotsLeft = $formation->max_participants - $formation->current_participants;
            $fillPercent = $formation->max_participants > 0
                ? ($formation->current_participants / $formation->max_participants) * 100
                : 0;
        @endphp
        <div class="mb-2">
            <div class="d-flex justify-content-between small text-muted mb-1">
                <span>{{ $formation->current_participants }}/{{ $formation->max_participants }} inscrits</span>
                <span>{{ $spotsLeft }} place{{ $spotsLeft > 1 ? 's' : '' }}</span>
            </div>
            <div class="spots-indicator">
                <div class="fill" style="width: {{ $fillPercent }}%"></div>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-auto">
            @if (! $formation->is_free)
                <span class="price-display">{{ mad_format($formation->price) }}</span>
            @else
                <span class="price-display text-success">Gratuit</span>
            @endif
            <a href="{{ route('formations.show', $formation) }}" class="btn btn-sm btn-outline-or">Détails</a>
        </div>
    </div>
</div>
