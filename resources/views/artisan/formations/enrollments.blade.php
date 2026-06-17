@extends('layouts.artisan')

@section('title', 'Inscrits — ' . $formation->title)
@section('page-title', 'Inscrits à la formation')

@section('content')
    <div class="mb-4">
        <a href="{{ route('artisan.formations.index') }}" class="text-decoration-none small">← Retour à mes formations</a>
    </div>

    <div class="card card-tissu mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div>
                    <h4 class="mb-1">{{ $formation->title }}</h4>
                    <p class="text-muted mb-0">
                        {{ $formation->date_debut->translatedFormat('d F Y') }} · {{ $formation->city }}
                        @if ($formation->is_free)
                            · <span class="text-success">Gratuit</span>
                        @else
                            · <span class="price-display small">{{ mad_format($formation->price) }}</span>
                        @endif
                    </p>
                </div>
                <div class="text-end">
                    <div class="fw-semibold">{{ $formation->current_participants }}/{{ $formation->max_participants }}</div>
                    <div class="text-muted small">places occupées</div>
                </div>
            </div>
        </div>
    </div>

    <p class="text-muted mb-4">
        {{ $enrollments->total() }} inscrit{{ $enrollments->total() > 1 ? 's' : '' }}
    </p>

    @if ($enrollments->isNotEmpty())
        <div class="card card-tissu">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-tissu mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Date d'inscription</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($enrollments as $enrollment)
                                <tr>
                                    <td>{{ $enrollment->id }}</td>
                                    <td class="fw-semibold">{{ $enrollment->user->name }}</td>
                                    <td>{{ $enrollment->user->email }}</td>
                                    <td>{{ $enrollment->user->phone ?? '—' }}</td>
                                    <td>{{ $enrollment->enrolled_at->translatedFormat('d/m/Y H:i') }}</td>
                                    <td><x-status-badge :status="$enrollment->status" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{ $enrollments->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">👥</div>
            <h4>Aucun inscrit</h4>
            <p>Aucun client ne s'est encore inscrit à cette formation.</p>
        </div>
    @endif
@endsection
