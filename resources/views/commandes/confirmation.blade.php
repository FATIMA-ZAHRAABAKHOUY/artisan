@extends('layouts.app')

@section('title', 'Confirmation')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <div class="display-1 mb-3" style="color: var(--vert);">✓</div>
                    <h1 class="font-serif" style="color: var(--indigo);">Commande confirmée !</h1>
                    <p class="lead text-muted">Merci pour votre achat. Votre commande a été enregistrée avec succès.</p>
                </div>

                <div class="card card-tissu mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="font-serif mb-1">Commande #{{ $commande->id }}</h4>
                                <small class="text-muted">{{ $commande->created_at->translatedFormat('d F Y à H:i') }}</small>
                            </div>
                            <x-status-badge :status="$commande->status" />
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <strong class="d-block small text-muted">Adresse de livraison</strong>
                                {{ $commande->shipping_address }}<br>
                                {{ $commande->shipping_city }}
                                @if ($commande->shipping_postal_code)
                                    — {{ $commande->shipping_postal_code }}
                                @endif
                            </div>
                            <div class="col-md-6">
                                <strong class="d-block small text-muted">Mode de paiement</strong>
                                @switch($commande->payment_method)
                                    @case('carte') 💳 Carte bancaire @break
                                    @case('cash') 💵 Espèces @break
                                    @case('livraison') 🚚 Paiement à la livraison @break
                                    @default {{ $commande->payment_method }}
                                @endswitch
                            </div>
                        </div>

                        <h5 class="font-serif mb-3">Articles commandés</h5>
                        @foreach ($commande->items as $item)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div>
                                    <span class="fw-semibold">{{ $item->product->name ?? 'Produit' }}</span>
                                    <span class="text-muted small"> × {{ $item->quantity }}</span>
                                    @if ($item->artisan)
                                        <div class="small text-muted">{{ $item->artisan->user->name ?? 'Artisan' }}</div>
                                    @endif
                                </div>
                                <span class="price-display">{{ mad_format($item->subtotal) }}</span>
                            </div>
                        @endforeach

                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Sous-total HT</span>
                                <span>{{ mad_format($commande->total_ht) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1 text-muted small">
                                <span>TVA</span>
                                <span>{{ mad_format($commande->tva) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <strong>Total TTC</strong>
                                <strong class="price-display">{{ mad_format($commande->total_ttc) }}</strong>
                            </div>
                        </div>

                        @if ($commande->livraison)
                            <div class="mt-4 p-3 rounded" style="background: var(--sable);">
                                <strong class="d-block small text-muted mb-1">Livraison</strong>
                                <x-status-badge :status="$commande->livraison->status" />
                                @if ($commande->livraison->tracking_number)
                                    <div class="small mt-2">N° de suivi : {{ $commande->livraison->tracking_number }}</div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <div class="d-flex gap-3 justify-content-center">
                    <a href="{{ route('commandes.show', $commande) }}" class="btn btn-or">Voir ma commande</a>
                    <a href="{{ route('catalogue.index') }}" class="btn btn-outline-or">Continuer mes achats</a>
                </div>
            </div>
        </div>
    </div>
@endsection
