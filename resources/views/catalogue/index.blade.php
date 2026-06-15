@extends('layouts.app')

@section('title', 'Catalogue')

@section('content')
    <div class="page-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-tissu mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item active">Catalogue</li>
                </ol>
            </nav>
            <h1>Catalogue</h1>
            <p class="text-muted mb-0">{{ $products->total() }} produit{{ $products->total() > 1 ? 's' : '' }} disponible{{ $products->total() > 1 ? 's' : '' }}</p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-4">
            <aside class="col-lg-3">
                <div class="filter-sidebar">
                    <h5 class="font-serif mb-4" style="color: var(--indigo);">Filtres</h5>
                    <form action="{{ route('catalogue.index') }}" method="GET">
                        <div class="mb-3">
                            <label for="category" class="form-label">Catégorie</label>
                            <select name="category" id="category" class="form-select">
                                <option value="">Toutes les catégories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->slug }}" @selected(request('category') === $category->slug || request('category') == $category->id)>
                                        {{ $category->icon }} {{ $category->name }} ({{ $category->products_count }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="max_price" class="form-label">Prix maximum (MAD)</label>
                            <input type="number" name="max_price" id="max_price" class="form-control" value="{{ request('max_price') }}" min="0" step="50" placeholder="Ex: 2000">
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="in_stock" id="in_stock" class="form-check-input" value="1" @checked(request()->boolean('in_stock'))>
                            <label for="in_stock" class="form-check-label">En stock uniquement</label>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="verified" id="verified" class="form-check-input" value="1" @checked(request()->boolean('verified'))>
                            <label for="verified" class="form-check-label">Artisans vérifiés</label>
                        </div>

                        <div class="mb-4">
                            <label for="sort" class="form-label">Trier par</label>
                            <select name="sort" id="sort" class="form-select">
                                <option value="newest" @selected(request('sort', 'newest') === 'newest')>Plus récents</option>
                                <option value="price_asc" @selected(request('sort') === 'price_asc')>Prix croissant</option>
                                <option value="price_desc" @selected(request('sort') === 'price_desc')>Prix décroissant</option>
                                <option value="name_asc" @selected(request('sort') === 'name_asc')>Nom A–Z</option>
                                <option value="name_desc" @selected(request('sort') === 'name_desc')>Nom Z–A</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-or">Appliquer</button>
                            <a href="{{ route('catalogue.index') }}" class="btn btn-outline-secondary btn-sm">Réinitialiser</a>
                        </div>
                    </form>
                </div>
            </aside>

            <div class="col-lg-9">
                @if ($products->isNotEmpty())
                    <div class="row g-4">
                        @foreach ($products as $product)
                            <div class="col-sm-6 col-lg-4">
                                <x-product-card :product="$product" />
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">🔍</div>
                        <h4>Aucun produit trouvé</h4>
                        <p>Essayez de modifier vos filtres pour voir plus de résultats.</p>
                        <a href="{{ route('catalogue.index') }}" class="btn btn-or">Réinitialiser les filtres</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
