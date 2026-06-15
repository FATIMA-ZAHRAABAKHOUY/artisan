@extends('layouts.app')

@section('title', 'Commande #' . $commande->id)

@section('content')
    <div class="page-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-tissu mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('commandes.index') }}">Mes commandes</a></li>
                    <li class="breadcrumb-item active">Commande #{{ $commande->id }}</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <h1>Commande #{{ $commande->id }}</h1>
                <x-status-badge :status="$commande->status" />
            </div>
            <p class="text-muted mb-0">Passée le {{ $commande->created_at->translatedFormat('d F Y à H:i') }}</p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card card-tissu mb-4">
                    <div class="card-body p-4">
                        <h4 class="font-serif mb-4" style="color: var(--indigo);">Articles</h4>
                        @foreach ($commande->items as $item)
                            <div class="d-flex justify-content-between align-items-start py-3 {{ ! $loop->last ? 'border-bottom' : '' }}">
                                <div class="d-flex gap-3">
                                    @if ($item->product?->main_image)
                                        <img src="{{ asset('storage/' . $item->product->main_image) }}" alt="" width="60" height="60" class="rounded" style="object-fit: cover;">
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $item->product->name ?? 'Produit supprimé' }}</div>
                                        <div class="small text-muted">Quantité : {{ $item->quantity }} × {{ mad_format($item->unit_price) }}</div>
                                        @if ($item->artisan)
                                            <div class="small text-muted">Artisan : {{ $item->artisan->user->name ?? 'N/A' }}</div>
                                        @endif
                                    </div>
                                </div>
                                <span class="price-display">{{ mad_format($item->subtotal) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if ($commande->notes)
                    <div class="card card-tissu">
                        <div class="card-body p-4">
                            <h5 class="font-serif mb-2" style="color: var(--indigo);">Notes</h5>
                            <p class="mb-0 text-muted">{{ $commande->notes }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="card card-tissu mb-4">
                    <div class="card-body p-4">
                        <h4 class="font-serif mb-4" style="color: var(--indigo);">Récapitulatif</h4>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Sous-total HT</span>
                            <span>{{ mad_format($commande->total_ht) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-muted small">
                            <span>TVA</span>
                            <span>{{ mad_format($commande->tva) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-0">
                            <strong>Total TTC</strong>
                            <strong class="price-display">{{ mad_format($commande->total_ttc) }}</strong>
                        </div>
                    </div>
                </div>

                <div class="card card-tissu mb-4">
                    <div class="card-body p-4">
                        <h4 class="font-serif mb-3" style="color: var(--indigo);">Livraison</h4>
                        <p class="mb-1">{{ $commande->shipping_address }}</p>
                        <p class="mb-3">{{ $commande->shipping_city }} @if($commande->shipping_postal_code) — {{ $commande->shipping_postal_code }} @endif</p>
                        @if ($commande->livraison)
                            <x-status-badge :status="$commande->livraison->status" />
                            @if ($commande->livraison->tracking_number)
                                <div class="mt-2 small">
                                    <strong>Suivi :</strong> {{ $commande->livraison->tracking_number }}
                                    @if ($commande->livraison->carrier)
                                        ({{ $commande->livraison->carrier }})
                                    @endif
                                </div>
                            @endif
                            @if ($commande->livraison->shipped_at)
                                <div class="small text-muted mt-1">Expédiée le {{ $commande->livraison->shipped_at->translatedFormat('d/m/Y') }}</div>
                            @endif
                            @if ($commande->livraison->delivered_at)
                                <div class="small text-muted">Livrée le {{ $commande->livraison->delivered_at->translatedFormat('d/m/Y') }}</div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="card card-tissu">
                    <div class="card-body p-4">
                        <h4 class="font-serif mb-3" style="color: var(--indigo);">Paiement</h4>
                        @switch($commande->payment_method)
                            @case('carte') 💳 Carte bancaire @break
                            @case('cash') 💵 Espèces @break
                            @case('livraison') 🚚 Paiement à la livraison @break
                            @default {{ $commande->payment_method }}
                        @endswitch
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('commandes.index') }}" class="btn btn-outline-or">← Retour à mes commandes</a>
        </div>
    </div>
@endsection
