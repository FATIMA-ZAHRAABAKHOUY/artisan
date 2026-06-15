@extends('layouts.app')

@section('title', 'Mes commandes')

@section('content')
    <div class="page-header">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-tissu mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item active">Mes commandes</li>
                </ol>
            </nav>
            <h1>Mes Commandes</h1>
        </div>
    </div>

    <div class="container py-5">
        @if ($commandes->isNotEmpty())
            <div class="card card-tissu">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-tissu mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Date</th>
                                    <th>Articles</th>
                                    <th>Total TTC</th>
                                    <th>Statut</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($commandes as $commande)
                                    <tr>
                                        <td><strong>#{{ $commande->id }}</strong></td>
                                        <td>{{ $commande->created_at->translatedFormat('d/m/Y') }}</td>
                                        <td>{{ $commande->items_count }} article{{ $commande->items_count > 1 ? 's' : '' }}</td>
                                        <td class="price-display">{{ mad_format($commande->total_ttc) }}</td>
                                        <td><x-status-badge :status="$commande->status" /></td>
                                        <td class="text-end">
                                            <a href="{{ route('commandes.show', $commande) }}" class="btn btn-sm btn-outline-or">Détails</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                {{ $commandes->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">📦</div>
                <h4>Aucune commande</h4>
                <p>Vous n'avez pas encore passé de commande.</p>
                <a href="{{ route('catalogue.index') }}" class="btn btn-or">Découvrir le catalogue</a>
            </div>
        @endif
    </div>
@endsection
