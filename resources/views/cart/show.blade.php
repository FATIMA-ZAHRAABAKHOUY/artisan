@extends('layouts.app')

@section('title', 'Panier')

@section('content')
    <div class="page-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-tissu mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item active">Panier</li>
                </ol>
            </nav>
            <h1>Mon Panier</h1>
        </div>
    </div>

    <div class="container py-5">
        @if ($items->isNotEmpty())
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card card-tissu">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-tissu mb-0 align-middle">
                                    <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th class="text-center">Prix unitaire</th>
                                            <th class="text-center">Quantité</th>
                                            <th class="text-end">Sous-total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        @if ($item['product']->main_image)
                                                            <img src="{{ asset('storage/' . $item['product']->main_image) }}" alt="" width="60" height="60" class="rounded" style="object-fit: cover;">
                                                        @else
                                                            <div class="rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: var(--sable);">🧵</div>
                                                        @endif
                                                        <div>
                                                            <a href="{{ route('catalogue.show', $item['product']) }}" class="fw-semibold text-decoration-none text-dark">
                                                                {{ $item['product']->name }}
                                                            </a>
                                                            @if ($item['product']->artisan)
                                                                <div class="small text-muted">{{ $item['product']->artisan->user->name ?? 'Artisan' }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center price-display">{{ mad_format($item['unit_price']) }}</td>
                                                <td class="text-center">
                                                    <form action="{{ route('cart.update') }}" method="POST" class="d-inline-flex align-items-center gap-1">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->stock }}" class="form-control form-control-sm" style="width: 70px;">
                                                        <button type="submit" class="btn btn-sm btn-outline-or">OK</button>
                                                    </form>
                                                </td>
                                                <td class="text-end price-display">{{ mad_format($item['subtotal']) }}</td>
                                                <td class="text-end">
                                                    <form action="{{ route('cart.remove') }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Retirer">&times;</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card card-tissu sticky-top" style="top: 100px;">
                        <div class="card-body p-4">
                            <h4 class="font-serif mb-4" style="color: var(--indigo);">Récapitulatif</h4>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Sous-total HT</span>
                                <span class="price-display">{{ mad_format($subtotal) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 text-muted small">
                                <span>TVA (20%)</span>
                                <span>{{ mad_format(round($subtotal * 0.20, 2)) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-4">
                                <strong>Total TTC</strong>
                                <strong class="price-display fs-5">{{ mad_format(round($subtotal * 1.20, 2)) }}</strong>
                            </div>
                            @auth
                                <a href="{{ route('commandes.checkout') }}" class="btn btn-or btn-lg w-100">Passer commande</a>
                            @else
                                <p class="small text-muted mb-3">Connectez-vous pour finaliser votre commande.</p>
                                <a href="{{ route('login') }}" class="btn btn-or btn-lg w-100">Se connecter</a>
                            @endauth
                            <a href="{{ route('catalogue.index') }}" class="btn btn-outline-secondary w-100 mt-2">Continuer mes achats</a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">🛒</div>
                <h4>Votre panier est vide</h4>
                <p>Découvrez nos créations artisanales et ajoutez vos coups de cœur.</p>
                <a href="{{ route('catalogue.index') }}" class="btn btn-or">Explorer le catalogue</a>
            </div>
        @endif
    </div>
@endsection
