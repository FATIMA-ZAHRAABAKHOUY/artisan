@extends('layouts.app')

@section('title', 'Artisans')

@section('content')
    <div class="page-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-tissu mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item active">Artisans</li>
                </ol>
            </nav>
            <h1>Nos Artisans</h1>
            <p class="text-muted mb-0">Découvrez les maîtres artisans vérifiés de notre marketplace</p>
        </div>
    </div>

    <div class="container py-5">
        @if ($artisans->isNotEmpty())
            <div class="row g-4">
                @foreach ($artisans as $artisan)
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <a href="{{ route('artisans.show', $artisan) }}" class="text-decoration-none">
                            <div class="artisan-card h-100">
                                @if ($artisan->avatar)
                                    <img src="{{ asset('storage/' . $artisan->avatar) }}" alt="{{ $artisan->user->name ?? 'Artisan' }}" class="artisan-avatar">
                                @else
                                    <div class="artisan-avatar-placeholder">👤</div>
                                @endif
                                <h5 class="font-serif text-dark mb-1">{{ $artisan->user->name ?? 'Artisan' }}</h5>
                                <p class="text-muted small mb-2">{{ $artisan->specialty }}</p>
                                <p class="text-muted small mb-2">📍 {{ $artisan->city }}</p>
                                @if ($artisan->rating)
                                    <div class="mb-2">
                                        <x-star-rating :rating="$artisan->rating" size="sm" />
                                        <small class="text-muted">({{ $artisan->total_reviews }} avis)</small>
                                    </div>
                                @endif
                                <span class="verified-badge">✓ Vérifié</span>
                                <div class="mt-2 small text-muted">{{ $artisan->products_count }} produit{{ $artisan->products_count > 1 ? 's' : '' }}</div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $artisans->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">👤</div>
                <h4>Aucun artisan disponible</h4>
                <p>Revenez bientôt pour découvrir nos artisans.</p>
            </div>
        @endif
    </div>
@endsection
