@extends('layouts.app')

@section('title', $formation->title)

@section('content')
    <div class="page-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-tissu mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('formations.index') }}">Formations</a></li>
                    <li class="breadcrumb-item active">{{ $formation->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-7">
                @if ($formation->image)
                    <img src="{{ asset('storage/' . $formation->image) }}" alt="{{ $formation->title }}" class="img-fluid rounded mb-4" style="max-height: 400px; width: 100%; object-fit: cover;">
                @else
                    <div class="bg-sable rounded d-flex align-items-center justify-content-center mb-4" style="height: 300px; background: var(--sable);">
                        <span class="display-1">📚</span>
                    </div>
                @endif

                <h1 class="font-serif mb-3">{{ $formation->title }}</h1>
                <div class="d-flex flex-wrap gap-3 mb-4 text-muted">
                    <span>📅 {{ $formation->date_debut->translatedFormat('l d F Y') }}</span>
                    <span>📍 {{ $formation->city }}</span>
                    @if ($formation->is_free)
                        <span class="badge bg-success">Gratuit</span>
                    @else
                        <span class="price-display">{{ mad_format($formation->price) }}</span>
                    @endif
                </div>

                <div class="mb-4">
                    <h4 class="font-serif" style="color: var(--indigo);">Description</h4>
                    <p class="text-muted">{{ $formation->description }}</p>
                </div>

                @if ($formation->artisan)
                    <div class="card card-tissu">
                        <div class="card-body d-flex align-items-center gap-3">
                            @if ($formation->artisan->avatar)
                                <img src="{{ asset('storage/' . $formation->artisan->avatar) }}" alt="" class="rounded-circle" width="60" height="60" style="object-fit: cover;">
                            @else
                                <div class="artisan-avatar-placeholder" style="width: 60px; height: 60px; font-size: 1.5rem; margin: 0;">👤</div>
                            @endif
                            <div>
                                <strong>Formateur : {{ $formation->artisan->user->name ?? 'Artisan' }}</strong>
                                <div class="small text-muted">{{ $formation->artisan->specialty }} — {{ $formation->artisan->city }}</div>
                            </div>
                            <a href="{{ route('artisans.show', $formation->artisan) }}" class="btn btn-sm btn-outline-or ms-auto">Voir le profil</a>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-5">
                <div class="card card-tissu sticky-top" style="top: 100px;">
                    <div class="card-body p-4">
                        <h4 class="font-serif mb-4" style="color: var(--indigo);">Inscription</h4>

                        @php
                            $spotsLeft = $formation->max_participants - $formation->current_participants;
                            $fillPercent = $formation->max_participants > 0
                                ? ($formation->current_participants / $formation->max_participants) * 100
                                : 0;
                        @endphp

                        <div class="mb-4">
                            <div class="d-flex justify-content-between small mb-2">
                                <span>{{ $formation->current_participants }}/{{ $formation->max_participants }} inscrits</span>
                                <span>{{ $spotsLeft }} place{{ $spotsLeft > 1 ? 's' : '' }} restante{{ $spotsLeft > 1 ? 's' : '' }}</span>
                            </div>
                            <div class="spots-indicator">
                                <div class="fill" style="width: {{ $fillPercent }}%"></div>
                            </div>
                        </div>

                        @if ($isEnrolled)
                            <x-alert type="success" :dismissible="false">
                                Vous êtes inscrit à cette formation.
                            </x-alert>
                        @elseif ($spotsLeft <= 0)
                            <x-alert type="warning" :dismissible="false">
                                Cette formation est complète.
                            </x-alert>
                        @elseif (auth()->check())
                            <form action="{{ route('formations.enroll', $formation) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-or btn-lg w-100">
                                    @if ($formation->is_free)
                                        S'inscrire gratuitement
                                    @else
                                        S'inscrire — {{ mad_format($formation->price) }}
                                    @endif
                                </button>
                            </form>
                        @else
                            <p class="text-muted mb-3">Connectez-vous pour vous inscrire à cette formation.</p>
                            <a href="{{ route('login') }}" class="btn btn-or btn-lg w-100">Se connecter</a>
                            <p class="text-center small text-muted mt-3 mb-0">
                                Pas encore de compte ? <a href="{{ route('register') }}">Inscrivez-vous</a>
                            </p>
                        @endif

                        <hr class="my-4">

                        <ul class="list-unstyled small text-muted mb-0">
                            <li class="mb-2">✓ Formation en présentiel</li>
                            <li class="mb-2">✓ Matériel fourni sur place</li>
                            <li class="mb-2">✓ Certificat de participation</li>
                            <li>✓ Groupe limité à {{ $formation->max_participants }} participants</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
