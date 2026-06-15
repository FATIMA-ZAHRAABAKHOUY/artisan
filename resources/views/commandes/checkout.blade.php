@extends('layouts.app')

@section('title', 'Commande')

@section('content')
    <div class="page-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-tissu mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cart.show') }}">Panier</a></li>
                    <li class="breadcrumb-item active">Commande</li>
                </ol>
            </nav>
            <h1>Finaliser la commande</h1>
        </div>
    </div>

    <div class="container py-5">
        <form action="{{ route('commandes.store') }}" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card card-tissu mb-4">
                        <div class="card-body p-4">
                            <h4 class="font-serif mb-4" style="color: var(--indigo);">Adresse de livraison</h4>
                            <div class="mb-3">
                                <label for="shipping_address" class="form-label">Adresse <span class="text-danger">*</span></label>
                                <textarea name="shipping_address" id="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" rows="3" required>{{ old('shipping_address', auth()->user()->artisan?->city ? '' : '') }}</textarea>
                                @error('shipping_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label for="shipping_city" class="form-label">Ville <span class="text-danger">*</span></label>
                                    <input type="text" name="shipping_city" id="shipping_city" class="form-control @error('shipping_city') is-invalid @enderror" value="{{ old('shipping_city') }}" required>
                                    @error('shipping_city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="shipping_postal_code" class="form-label">Code postal</label>
                                    <input type="text" name="shipping_postal_code" id="shipping_postal_code" class="form-control @error('shipping_postal_code') is-invalid @enderror" value="{{ old('shipping_postal_code') }}">
                                    @error('shipping_postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="notes" class="form-label">Notes (optionnel)</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="2" placeholder="Instructions de livraison...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card card-tissu">
                        <div class="card-body p-4">
                            <h4 class="font-serif mb-4" style="color: var(--indigo);">Mode de paiement</h4>
                            @error('payment_method')
                                <div class="text-danger small mb-3">{{ $message }}</div>
                            @enderror

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="payment-card d-block h-100 {{ old('payment_method', 'carte') === 'carte' ? 'selected' : '' }}">
                                        <div class="d-flex align-items-start gap-2">
                                            <input type="radio" name="payment_method" value="carte" class="mt-1" @checked(old('payment_method', 'carte') === 'carte') required>
                                            <div>
                                                <strong>💳 Carte bancaire</strong>
                                                <p class="small text-muted mb-0 mt-1">Paiement sécurisé par carte Visa ou Mastercard.</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="payment-card d-block h-100 {{ old('payment_method') === 'cash' ? 'selected' : '' }}">
                                        <div class="d-flex align-items-start gap-2">
                                            <input type="radio" name="payment_method" value="cash" class="mt-1" @checked(old('payment_method') === 'cash')>
                                            <div>
                                                <strong>💵 Espèces</strong>
                                                <p class="small text-muted mb-0 mt-1">Paiement en espèces lors du retrait en boutique.</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="payment-card d-block h-100 {{ old('payment_method') === 'livraison' ? 'selected' : '' }}">
                                        <div class="d-flex align-items-start gap-2">
                                            <input type="radio" name="payment_method" value="livraison" class="mt-1" @checked(old('payment_method') === 'livraison')>
                                            <div>
                                                <strong>🚚 Paiement à la livraison</strong>
                                                <p class="small text-muted mb-0 mt-1">Payez à la réception de votre colis.</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card card-tissu sticky-top" style="top: 100px;">
                        <div class="card-body p-4">
                            <h4 class="font-serif mb-4" style="color: var(--indigo);">Votre commande</h4>

                            @foreach ($items as $item)
                                <div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom">
                                    <div class="d-flex gap-2">
                                        @if ($item['product']->main_image)
                                            <img src="{{ asset('storage/' . $item['product']->main_image) }}" alt="" width="50" height="50" class="rounded" style="object-fit: cover;">
                                        @endif
                                        <div>
                                            <div class="small fw-semibold">{{ $item['product']->name }}</div>
                                            <div class="small text-muted">× {{ $item['quantity'] }}</div>
                                        </div>
                                    </div>
                                    <span class="price-display small">{{ mad_format($item['subtotal']) }}</span>
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-between mb-2">
                                <span>Sous-total HT</span>
                                <span>{{ mad_format($totalHt) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 text-muted small">
                                <span>TVA (20%)</span>
                                <span>{{ mad_format($tva) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-4">
                                <strong>Total TTC</strong>
                                <strong class="price-display fs-5">{{ mad_format($totalTtc) }}</strong>
                            </div>

                            <button type="submit" class="btn btn-or btn-lg w-100">Confirmer la commande</button>
                            <a href="{{ route('cart.show') }}" class="btn btn-outline-secondary w-100 mt-2">Retour au panier</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
