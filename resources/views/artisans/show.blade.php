@extends('layouts.app')

@section('title', $artisan->user->name ?? 'Artisan')

@section('content')
    <div class="page-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-tissu mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('artisans.index') }}">Artisans</a></li>
                    <li class="breadcrumb-item active">{{ $artisan->user->name ?? 'Artisan' }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container py-5">
        <div class="card card-tissu mb-5">
            <div class="card-body p-4">
                <div class="row align-items-center g-4">
                    <div class="col-auto">
                        @if ($artisan->avatar)
                            <img src="{{ asset('storage/' . $artisan->avatar) }}" alt="" class="artisan-avatar" style="width: 120px; height: 120px;">
                        @else
                            <div class="artisan-avatar-placeholder" style="width: 120px; height: 120px; font-size: 3rem;">👤</div>
                        @endif
                    </div>
                    <div class="col">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <h1 class="font-serif mb-0">{{ $artisan->user->name ?? 'Artisan' }}</h1>
                            <span class="verified-badge">✓ Vérifié</span>
                        </div>
                        <p class="text-muted mb-2">{{ $artisan->specialty }} — 📍 {{ $artisan->city }}</p>
                        @if ($artisan->rating)
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <x-star-rating :rating="$artisan->rating" size="md" />
                                <span class="text-muted">{{ number_format($artisan->rating, 1) }} ({{ $artisan->total_reviews }} avis)</span>
                            </div>
                        @endif
                        @if ($artisan->bio)
                            <p class="mb-0">{{ $artisan->bio }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <h2 class="font-serif mb-4" style="color: var(--indigo);">Produits de {{ $artisan->user->name ?? 'l\'artisan' }}</h2>

        @if ($products->isNotEmpty())
            <div class="row g-4">
                @foreach ($products as $product)
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">📦</div>
                <p>Cet artisan n'a pas encore de produits disponibles.</p>
            </div>
        @endif
    </div>
@endsection
