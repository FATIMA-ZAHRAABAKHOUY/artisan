@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
    <section class="auth-section">
        <div class="auth-card">
            <div class="text-center mb-4">
                <h1 class="font-serif h3" style="color: var(--indigo);">Connexion</h1>
                <p class="text-muted">Accédez à votre compte Tissu Artisanal</p>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Adresse e-mail</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input" @checked(old('remember'))>
                    <label for="remember" class="form-check-label">Se souvenir de moi</label>
                </div>

                <button type="submit" class="btn btn-or w-100 btn-lg">Se connecter</button>
            </form>

            <p class="text-center text-muted mt-4 mb-0">
                Pas encore de compte ? <a href="{{ route('register') }}" style="color: var(--or-dark);">Inscrivez-vous</a>
            </p>
        </div>
    </section>
@endsection
