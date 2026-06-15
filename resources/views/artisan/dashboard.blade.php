@extends('layouts.artisan')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')
    @if (! $artisan->is_verified)
        <x-alert type="warning" class="mb-4">
            Votre compte artisan est en attente de vérification. Vos produits ne seront visibles dans le catalogue public qu'après validation.
        </x-alert>
    @endif

    <div class="row g-4 mb-5">
        <div class="col-6 col-md-4 col-xl">
            <div class="kpi-card">
                <div class="kpi-value">{{ $kpis['total_products'] }}</div>
                <div class="kpi-label">Produits total</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="kpi-card">
                <div class="kpi-value">{{ $kpis['active_products'] }}</div>
                <div class="kpi-label">Produits actifs</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="kpi-card">
                <div class="kpi-value text-warning">{{ $kpis['low_stock_products'] }}</div>
                <div class="kpi-label">Stock faible</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="kpi-card">
                <div class="kpi-value">{{ $kpis['total_orders'] }}</div>
                <div class="kpi-label">Commandes</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="kpi-card">
                <div class="kpi-value">{{ mad_format($kpis['total_revenue']) }}</div>
                <div class="kpi-label">Revenus total</div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl">
            <div class="kpi-card">
                <div class="kpi-value">{{ mad_format($kpis['monthly_revenue']) }}</div>
                <div class="kpi-label">Revenus du mois</div>
            </div>
        </div>
    </div>

    @if ($kpis['pending_orders'] > 0)
        <x-alert type="info" class="mb-4">
            Vous avez {{ $kpis['pending_orders'] }} commande{{ $kpis['pending_orders'] > 1 ? 's' : '' }} en attente de traitement.
        </x-alert>
    @endif

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card card-tissu">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="font-serif mb-0" style="color: var(--indigo);">Produits récents</h4>
                        <a href="{{ route('artisan.products.index') }}" class="btn btn-sm btn-outline-or">Voir tout</a>
                    </div>
                    @if ($recentProducts->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-sm table-tissu mb-0">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Prix</th>
                                        <th>Stock</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentProducts as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td class="price-display small">{{ mad_format($product->price) }}</td>
                                            <td>
                                                @if ($product->stock <= 5 && $product->stock > 0)
                                                    <span class="stock-badge stock-low">{{ $product->stock }}</span>
                                                @elseif ($product->stock === 0)
                                                    <span class="stock-badge stock-out">0</span>
                                                @else
                                                    {{ $product->stock }}
                                                @endif
                                            </td>
                                            <td>
                                                <x-status-badge :status="$product->is_active ? 'active' : 'inactive'" />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">Aucun produit pour le moment.</p>
                        <a href="{{ route('artisan.products.create') }}" class="btn btn-or btn-sm mt-2">Ajouter un produit</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-tissu">
                <div class="card-body p-4">
                    <h4 class="font-serif mb-3" style="color: var(--indigo);">Commandes récentes</h4>
                    @if ($recentOrderItems->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-sm table-tissu mb-0">
                                <thead>
                                    <tr>
                                        <th>Commande</th>
                                        <th>Produit</th>
                                        <th>Client</th>
                                        <th>Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentOrderItems as $item)
                                        <tr>
                                            <td>#{{ $item->commande_id }}</td>
                                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                                            <td>{{ $item->commande->user->name ?? 'N/A' }}</td>
                                            <td class="price-display small">{{ mad_format($item->subtotal) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">Aucune commande pour le moment.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
