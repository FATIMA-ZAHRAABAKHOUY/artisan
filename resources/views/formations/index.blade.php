@extends('layouts.app')

@section('title', 'Formations')

@section('content')
    <div class="page-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-tissu mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item active">Formations</li>
                </ol>
            </nav>
            <h1>Formations Artisanales</h1>
            <p class="text-muted mb-0">Apprenez les techniques traditionnelles marocaines auprès de nos maîtres artisans</p>
        </div>
    </div>

    <div class="container py-5">
        @if ($formations->isNotEmpty())
            <div class="row g-4">
                @foreach ($formations as $formation)
                    <div class="col-md-6 col-lg-4">
                        <x-formation-card :formation="$formation" />
                    </div>
                @endforeach
            </div>
            <div class="mt-4">
                {{ $formations->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">📚</div>
                <h4>Aucune formation disponible</h4>
                <p>Revenez bientôt pour découvrir nos prochaines sessions.</p>
            </div>
        @endif
    </div>
@endsection
