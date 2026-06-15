@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
    <section class="auth-section">
        <div class="auth-card" style="max-width: 560px;">
            <div class="text-center mb-4">
                <h1 class="font-serif h3" style="color: var(--indigo);">Créer un compte</h1>
                <p class="text-muted">Rejoignez la communauté Tissu Artisanal</p>
            </div>

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Type de compte <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input type="radio" name="role" id="role_client" value="client" class="form-check-input" @checked(old('role', 'client') === 'client') required>
                            <label for="role_client" class="form-check-label">Client</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="role" id="role_artisan" value="artisan" class="form-check-input" @checked(old('role') === 'artisan')>
                            <label for="role_artisan" class="form-check-label">Artisan</label>
                        </div>
                    </div>
                    @error('role')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Adresse e-mail <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Téléphone</label>
                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirmation <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <div id="artisan-fields" style="display: none;">
                    <hr class="my-3">
                    <h5 class="font-serif mb-3" style="color: var(--indigo);">Informations artisan</h5>
                    <div class="mb-3">
                        <label for="specialty" class="form-label">Spécialité <span class="text-danger">*</span></label>
                        <input type="text" name="specialty" id="specialty" class="form-control @error('specialty') is-invalid @enderror" value="{{ old('specialty') }}" placeholder="Ex: Tissage, Broderie...">
                        @error('specialty')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">Ville <span class="text-danger">*</span></label>
                        <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" placeholder="Ex: Fès, Marrakech...">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="bio" class="form-label">Biographie</label>
                        <textarea name="bio" id="bio" class="form-control @error('bio') is-invalid @enderror" rows="3" placeholder="Présentez votre savoir-faire...">{{ old('bio') }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-or w-100 btn-lg mt-2">Créer mon compte</button>
            </form>

            <p class="text-center text-muted mt-4 mb-0">
                Déjà inscrit ? <a href="{{ route('login') }}" style="color: var(--or-dark);">Connectez-vous</a>
            </p>
        </div>
    </section>
@endsection
