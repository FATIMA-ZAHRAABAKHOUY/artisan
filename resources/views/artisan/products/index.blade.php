@extends('layouts.artisan')

@section('title', 'Mes produits')
@section('page-title', 'Mes produits')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <p class="text-muted mb-0">{{ $products->total() }} produit{{ $products->total() > 1 ? 's' : '' }}</p>
        <a href="{{ route('artisan.products.create') }}" class="btn btn-or">+ Nouveau produit</a>
    </div>

    @if ($products->isNotEmpty())
        <div class="card card-tissu">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-tissu mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Catégorie</th>
                                <th>Prix</th>
                                <th>Stock</th>
                                <th>Vedette</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($product->main_image)
                                                <img src="{{ asset('storage/' . $product->main_image) }}" alt="" width="40" height="40" class="rounded" style="object-fit: cover;">
                                            @endif
                                            <span class="fw-semibold">{{ $product->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $product->category->name ?? '—' }}</td>
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
                                    <td>{{ $product->is_featured ? '⭐' : '—' }}</td>
                                    <td><x-status-badge :status="$product->is_active ? 'active' : 'inactive'" /></td>
                                    <td class="text-end">
                                        <a href="{{ route('artisan.products.edit', $product) }}" class="btn btn-sm btn-outline-or">Modifier</a>
                                        <form action="{{ route('artisan.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce produit ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📦</div>
            <h4>Aucun produit</h4>
            <p>Commencez par ajouter votre première création artisanale.</p>
            <a href="{{ route('artisan.products.create') }}" class="btn btn-or">Créer un produit</a>
        </div>
    @endif
@endsection
