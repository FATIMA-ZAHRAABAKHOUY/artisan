<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tissu Artisanal') — Artisanat Marocain</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <x-motif-bar />

    <nav class="navbar navbar-expand-lg navbar-tissu sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <span class="d-block">🧵 Tissu Artisanal</span>
                <small class="brand-subtitle">COOPÉRATIVE MAROCAINE</small>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('catalogue.*') ? 'active' : '' }}" href="{{ route('catalogue.index') }}">Catalogue</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('artisans.*') ? 'active' : '' }}" href="{{ route('artisans.index') }}">Artisans</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('formations.*') ? 'active' : '' }}" href="{{ route('formations.index') }}">Formations</a>
                    </li>
                </ul>
                <ul class="navbar-nav align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('cart.show') }}" aria-label="Panier">
                            🛒 Panier
                            @php
                                $cartCount = $cartCount ?? array_sum(session('cart', []));
                            @endphp
                            @if ($cartCount > 0)
                                <span class="cart-badge">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if (auth()->user()->isArtisan())
                                    <li><a class="dropdown-item" href="{{ route('artisan.dashboard') }}">Tableau de bord</a></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('profil.show') }}">Mon profil</a></li>
                                <li><a class="dropdown-item" href="{{ route('commandes.index') }}">Mes commandes</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-or btn-sm ms-lg-2" href="{{ route('register') }}">Inscription</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @if (session('success'))
        <div class="container mt-3">
            <x-alert type="success">{{ session('success') }}</x-alert>
        </div>
    @endif

    @if (session('error'))
        <div class="container mt-3">
            <x-alert type="danger">{{ session('error') }}</x-alert>
        </div>
    @endif

    @if ($errors->any())
        <div class="container mt-3">
            <x-alert type="danger">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="footer-tissu mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3">
                    <h5>Tissu Artisanal</h5>
                    <p class="small">Marketplace dédiée à l'artisanat marocain authentique. Découvrez des créations uniques tissées, brodées et façonnées par nos artisans vérifiés.</p>
                </div>
                <div class="col-md-3">
                    <h5>Navigation</h5>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="mb-2"><a href="{{ route('catalogue.index') }}">Catalogue</a></li>
                        <li class="mb-2"><a href="{{ route('artisans.index') }}">Artisans</a></li>
                        <li class="mb-2"><a href="{{ route('formations.index') }}">Formations</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Compte</h5>
                    <ul class="list-unstyled small">
                        @auth
                            <li class="mb-2"><a href="{{ route('profil.show') }}">Mon profil</a></li>
                            <li class="mb-2"><a href="{{ route('commandes.index') }}">Mes commandes</a></li>
                            <li class="mb-2"><a href="{{ route('cart.show') }}">Mon panier</a></li>
                        @else
                            <li class="mb-2"><a href="{{ route('login') }}">Connexion</a></li>
                            <li class="mb-2"><a href="{{ route('register') }}">Inscription</a></li>
                        @endauth
                        @if (auth()->check() && auth()->user()->isArtisan())
                            <li class="mb-2"><a href="{{ route('artisan.dashboard') }}">Espace artisan</a></li>
                        @endif
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contact</h5>
                    <ul class="list-unstyled small">
                        <li class="mb-2">📍 Casablanca, Maroc</li>
                        <li class="mb-2">📧 contact@tissu.ma</li>
                        <li class="mb-2">📞 +212 5 22 00 00 00</li>
                        <li class="mb-2">🕐 Lun–Sam : 9h–18h</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; {{ date('Y') }} Tissu Artisanal. Tous droits réservés. Artisanat marocain authentique.
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
