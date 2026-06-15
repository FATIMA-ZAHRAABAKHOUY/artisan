@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
    <div class="page-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-tissu mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item active">Mon profil</li>
                </ol>
            </nav>
            <h1>Mon Profil</h1>
        </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-tissu">
                    <div class="card-body p-4">
                        <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <div class="text-center mb-4">
                                @if ($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="" class="artisan-avatar" style="width: 100px; height: 100px;">
                                @else
                                    <div class="artisan-avatar-placeholder mx-auto">👤</div>
                                @endif
                                <div class="mt-3">
                                    <label for="avatar" class="form-label">Photo de profil</label>
                                    <input type="file" name="avatar" id="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                                    @error('avatar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Type de compte</label>
                                    <input type="text" class="form-control" value="{{ $user->isArtisan() ? 'Artisan' : 'Client' }}" disabled>
                                </div>
                            </div>

                            @if ($user->isArtisan() && $user->artisan)
                                <hr class="my-4">
                                <h4 class="font-serif mb-3" style="color: var(--indigo);">Informations artisan</h4>
                                @if ($user->artisan->is_verified)
                                    <span class="verified-badge mb-3 d-inline-block">✓ Compte vérifié</span>
                                @else
                                    <x-alert type="warning" :dismissible="false" class="mb-3">
                                        Votre compte artisan est en attente de vérification par notre équipe.
                                    </x-alert>
                                @endif
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="specialty" class="form-label">Spécialité</label>
                                        <input type="text" name="specialty" id="specialty" class="form-control @error('specialty') is-invalid @enderror" value="{{ old('specialty', $user->artisan->specialty) }}">
                                        @error('specialty')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="city" class="form-label">Ville</label>
                                        <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $user->artisan->city) }}">
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="bio" class="form-label">Biographie</label>
                                        <textarea name="bio" id="bio" class="form-control @error('bio') is-invalid @enderror" rows="4">{{ old('bio', $user->artisan->bio) }}</textarea>
                                        @error('bio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            <div class="mt-4 d-flex gap-2">
                                <button type="submit" class="btn btn-or">Enregistrer les modifications</button>
                                @if ($user->isArtisan())
                                    <a href="{{ route('artisan.dashboard') }}" class="btn btn-outline-or">Tableau de bord</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
