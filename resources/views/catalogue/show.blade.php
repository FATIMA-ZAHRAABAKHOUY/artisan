@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="page-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-tissu mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('catalogue.index') }}">Catalogue</a></li>
                    @if ($product->category)
                        <li class="breadcrumb-item">
                            <a href="{{ route('catalogue.index', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
                        </li>
                    @endif
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-6">
                @php
                    $mainImage = $product->main_image
                        ? asset('storage/' . $product->main_image)
                        : null;
                @endphp
                @if ($mainImage)
                    <img id="product-main-image" src="{{ $mainImage }}" alt="{{ $product->name }}" class="product-gallery-main mb-3">
                @else
                    <div class="product-gallery-main d-flex align-items-center justify-content-center mb-3">
                        <span class="display-1 text-muted">🧵</span>
                    </div>
                @endif

                @if ($product->images->isNotEmpty())
                    <div class="d-flex gap-2 flex-wrap">
                        @if ($mainImage)
                            <img src="{{ $mainImage }}" alt="" class="product-gallery-thumb active" data-src="{{ $mainImage }}">
                        @endif
                        @foreach ($product->images as $image)
                            @php $imgUrl = asset('storage/' . $image->image_path); @endphp
                            <img src="{{ $imgUrl }}" alt="" class="product-gallery-thumb" data-src="{{ $imgUrl }}">
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-lg-6">
                @if ($product->category)
                    <span class="badge bg-secondary mb-2">{{ $product->category->icon }} {{ $product->category->name }}</span>
                @endif
                <h1 class="font-serif mb-3">{{ $product->name }}</h1>
                <div class="price-display fs-2 mb-4">{{ mad_format($product->price) }}</div>

                <p class="text-muted mb-4">{{ $product->description }}</p>

                <div class="row g-3 mb-4">
                    @if ($product->material)
                        <div class="col-6">
                            <strong class="d-block small text-muted">Matériau</strong>
                            {{ $product->material }}
                        </div>
                    @endif
                    @if ($product->dimensions)
                        <div class="col-6">
                            <strong class="d-block small text-muted">Dimensions</strong>
                            {{ $product->dimensions }}
                        </div>
                    @endif
                    @if ($product->weight)
                        <div class="col-6">
                            <strong class="d-block small text-muted">Poids</strong>
                            {{ $product->weight }} kg
                        </div>
                    @endif
                    <div class="col-6">
                        <strong class="d-block small text-muted">Disponibilité</strong>
                        @if ($product->stock > 5)
                            <span class="stock-badge stock-in">En stock ({{ $product->stock }} disponibles)</span>
                        @elseif ($product->stock > 0)
                            <span class="stock-badge stock-low">Stock limité ({{ $product->stock }} restants)</span>
                        @else
                            <span class="stock-badge stock-out">Rupture de stock</span>
                        @endif
                    </div>
                </div>

                @if ($product->artisan)
                    <div class="card card-tissu mb-4">
                        <div class="card-body d-flex align-items-center gap-3">
                            @if ($product->artisan->avatar)
                                <img src="{{ asset('storage/' . $product->artisan->avatar) }}" alt="" class="rounded-circle" width="60" height="60" style="object-fit: cover;">
                            @else
                                <div class="artisan-avatar-placeholder" style="width: 60px; height: 60px; font-size: 1.5rem; margin: 0;">👤</div>
                            @endif
                            <div>
                                <strong>{{ $product->artisan->user->name ?? 'Artisan' }}</strong>
                                @if ($product->artisan->is_verified)
                                    <span class="verified-badge ms-1">✓ Vérifié</span>
                                @endif
                                <div class="small text-muted">{{ $product->artisan->specialty }} — {{ $product->artisan->city }}</div>
                                @if ($product->artisan->rating)
                                    <x-star-rating :rating="$product->artisan->rating" size="sm" />
                                @endif
                            </div>
                            <a href="{{ route('artisans.show', $product->artisan) }}" class="btn btn-sm btn-outline-or ms-auto">Voir le profil</a>
                        </div>
                    </div>
                @endif

                @if ($product->stock > 0)
                    <form action="{{ route('cart.add') }}" method="POST" class="d-flex gap-3 align-items-end">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div>
                            <label for="quantity" class="form-label">Quantité</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}" style="width: 100px;">
                        </div>
                        <button type="submit" class="btn btn-or btn-lg flex-grow-1">Ajouter au panier</button>
                    </form>
                @else
                    <button class="btn btn-secondary btn-lg w-100" disabled>Indisponible</button>
                @endif
            </div>
        </div>

        @if ($relatedProducts->isNotEmpty())
            <section class="mt-5 pt-5 border-top">
                <h3 class="font-serif mb-4" style="color: var(--indigo);">Produits similaires</h3>
                <div class="row g-4">
                    @foreach ($relatedProducts as $related)
                        <div class="col-sm-6 col-lg-3">
                            <x-product-card :product="$related" />
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
