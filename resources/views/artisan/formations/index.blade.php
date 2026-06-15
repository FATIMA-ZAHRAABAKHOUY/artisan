@extends('layouts.artisan')

@section('title', 'Mes formations')
@section('page-title', 'Mes formations')

@section('content')
    <p class="text-muted mb-4">{{ $formations->total() }} formation{{ $formations->total() > 1 ? 's' : '' }}</p>

    @if ($formations->isNotEmpty())
        <div class="card card-tissu">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-tissu mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Formation</th>
                                <th>Date</th>
                                <th>Ville</th>
                                <th>Prix</th>
                                <th>Participants</th>
                                <th>Inscrits</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($formations as $formation)
                                <tr>
                                    <td class="fw-semibold">{{ $formation->title }}</td>
                                    <td>{{ $formation->date_debut->translatedFormat('d/m/Y') }}</td>
                                    <td>{{ $formation->city }}</td>
                                    <td>
                                        @if ($formation->is_free)
                                            <span class="text-success">Gratuit</span>
                                        @else
                                            <span class="price-display small">{{ mad_format($formation->price) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $formation->current_participants }}/{{ $formation->max_participants }}</td>
                                    <td>{{ $formation->enrollments_count }}</td>
                                    <td><x-status-badge :status="$formation->is_active ? 'active' : 'inactive'" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-4">
            {{ $formations->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📚</div>
            <h4>Aucune formation</h4>
            <p>Vous n'avez pas encore créé de formation.</p>
        </div>
    @endif
@endsection
