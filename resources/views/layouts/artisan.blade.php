<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace Artisan') — Tissu Artisanal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <x-motif-bar />

    <div class="d-flex">
        <aside class="artisan-sidebar d-none d-lg-block" style="width: 260px; flex-shrink: 0;">
            <div class="sidebar-brand">🧶 Espace Artisan</div>
            <nav class="nav flex-column p-3">
                <a class="nav-link {{ request()->routeIs('artisan.dashboard') ? 'active' : '' }}" href="{{ route('artisan.dashboard') }}">
                    📊 Tableau de bord
                </a>
                <a class="nav-link {{ request()->routeIs('artisan.products.*') ? 'active' : '' }}" href="{{ route('artisan.products.index') }}">
                    📦 Mes produits
                </a>
                <a class="nav-link {{ request()->routeIs('artisan.products.create') ? 'active' : '' }}" href="{{ route('artisan.products.create') }}">
                    ➕ Nouveau produit
                </a>
                <a class="nav-link {{ request()->routeIs('artisan.formations.*') ? 'active' : '' }}" href="{{ route('artisan.formations.index') }}">
                    📚 Mes formations
                </a>
                <hr class="border-secondary opacity-25">
                <a class="nav-link" href="{{ route('home') }}">🏠 Site public</a>
                <a class="nav-link" href="{{ route('profil.show') }}">👤 Mon profil</a>
                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="nav-link border-0 bg-transparent text-start w-100">🚪 Déconnexion</button>
                </form>
            </nav>
        </aside>

        <div class="flex-grow-1">
            <div class="artisan-topbar d-flex justify-content-between align-items-center">
                <div>
                    <button class="btn btn-outline-secondary btn-sm d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#artisanSidebar">
                        ☰ Menu
                    </button>
                    <span class="ms-2 fw-semibold">@yield('page-title', 'Tableau de bord')</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted small">{{ auth()->user()->name }}</span>
                    @if (auth()->user()->artisan && ! auth()->user()->artisan->is_verified)
                        <span class="badge bg-warning text-dark">En attente de vérification</span>
                    @elseif (auth()->user()->artisan?->is_verified)
                        <span class="verified-badge">✓ Vérifié</span>
                    @endif
                </div>
            </div>

            <div class="offcanvas offcanvas-start artisan-sidebar d-lg-none" tabindex="-1" id="artisanSidebar">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title text-warning">Espace Artisan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body p-0">
                    <nav class="nav flex-column p-3">
                        <a class="nav-link" href="{{ route('artisan.dashboard') }}">📊 Tableau de bord</a>
                        <a class="nav-link" href="{{ route('artisan.products.index') }}">📦 Mes produits</a>
                        <a class="nav-link" href="{{ route('artisan.products.create') }}">➕ Nouveau produit</a>
                        <a class="nav-link" href="{{ route('artisan.formations.index') }}">📚 Mes formations</a>
                        <hr class="border-secondary opacity-25">
                        <a class="nav-link" href="{{ route('home') }}">🏠 Site public</a>
                    </nav>
                </div>
            </div>

            @if (session('success'))
                <div class="p-3 pb-0">
                    <x-alert type="success">{{ session('success') }}</x-alert>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-3 pb-0">
                    <x-alert type="danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-alert>
                </div>
            @endif

            <div class="p-4">
                @yield('content')
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
