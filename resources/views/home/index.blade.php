@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
    <section class="hero-section">
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <p class="text-uppercase small mb-2 opacity-75 letter-spacing">Coopérative Marocaine</p>
                    <h1 class="display-4 fw-bold mb-4 font-serif">L'Art du Tissu Marocain à Portée de Main</h1>
                    <p class="lead mb-4 opacity-90">
                        Découvrez des créations uniques — tapis berbères, broderies fassi, teintures naturelles et djellabas —
                        réalisées par des artisans vérifiés à travers le Royaume.
                    </p>
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <a href="{{ route('catalogue.index') }}" class="btn btn-or btn-lg">Explorer le Catalogue</a>
                        <a href="{{ route('formations.index') }}" class="btn btn-outline-light btn-lg">Nos Formations</a>
                    </div>
                    <div class="d-flex flex-wrap gap-3">
                        <span class="badge bg-or-dark px-3 py-2">{{ $stats['artisans'] }}+ artisans</span>
                        <span class="badge bg-or-dark px-3 py-2">{{ $stats['products'] }}+ produits</span>
                        <span class="badge bg-or-dark px-3 py-2">{{ $stats['formations'] }} formations actives</span>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block text-center">
                    <div class="display-1 opacity-25">🧶</div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="font-serif text-indigo" style="color: var(--indigo);">Nos Catégories</h2>
                <p class="text-muted">Explorez l'artisanat marocain par spécialité</p>
            </div>
            <div class="row g-4">
                @foreach ($categories as $category)
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('catalogue.index', ['category' => $category->slug]) }}" class="category-card h-100">
                            <div class="category-icon">{{ $category->icon }}</div>
                            <h6 class="mb-1">{{ $category->name }}</h6>
                            <small class="text-muted">{{ $category->products_count }} produit{{ $category->products_count > 1 ? 's' : '' }}</small>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-sable py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="font-serif mb-1" style="color: var(--indigo);">Produits Vedettes</h2>
                    <p class="text-muted mb-0">Sélection de nos plus belles créations artisanales</p>
                </div>
                <a href="{{ route('catalogue.index') }}" class="btn btn-outline-or">Voir tout</a>
            </div>
            @if ($featuredProducts->isNotEmpty())
                <div class="row g-4">
                    @foreach ($featuredProducts as $product)
                        <div class="col-sm-6 col-lg-3">
                            <x-product-card :product="$product" />
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">🧵</div>
                    <p>Aucun produit vedette pour le moment.</p>
                </div>
            @endif
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="font-serif mb-1" style="color: var(--indigo);">Formations Artisanales</h2>
                    <p class="text-muted mb-0">Apprenez les techniques traditionnelles auprès de nos maîtres artisans</p>
                </div>
                <a href="{{ route('formations.index') }}" class="btn btn-outline-or">Toutes les formations</a>
            </div>
            @if ($formations->isNotEmpty())
                <div class="row g-4">
                    @foreach ($formations as $formation)
                        <div class="col-md-6 col-lg-4">
                            <x-formation-card :formation="$formation" />
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">📚</div>
                    <p>Aucune formation programmée pour le moment.</p>
                </div>
            @endif
        </div>
    </section>

    <section class="cta-section">
        <div class="container text-center">
            <h2 class="mb-3">Vous êtes artisan ?</h2>
            <p class="lead mb-4 opacity-90">Rejoignez notre marketplace et partagez votre savoir-faire avec le monde entier.</p>
            <a href="{{ route('register') }}" class="btn btn-light btn-lg fw-semibold">Créer mon compte artisan</a>
        </div>
    </section>
@endsection
