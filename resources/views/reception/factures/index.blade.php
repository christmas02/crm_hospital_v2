@extends('layouts.medicare')

@section('title', 'Facturation - MediCare Pro')
@section('sidebar-subtitle', 'Réception')
@section('user-color', '#059669')
@section('header-title', 'Facturation')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('reception._sidebar')
@endif
@endsection

@section('content')
<div class="toolbar">
    <div class="filters">
        <form action="{{ route('reception.factures.index') }}" method="GET" class="flex gap-2">
            <select class="filter-select" name="statut" onchange="this.form.submit()">
                <option value="">Toutes les factures</option>
                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="envoyee" {{ request('statut') == 'envoyee' ? 'selected' : '' }}>Envoyée à caisse</option>
                <option value="payee" {{ request('statut') == 'payee' ? 'selected' : '' }}>Payée</option>
            </select>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Factures</h2>
        <span class="text-muted text-sm">{{ $factures->total() }} factures</span>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>N° Facture</th>
                        <th>Patient</th>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($factures as $facture)
                    <tr>
                        <td><strong>{{ $facture->numero }}</strong></td>
                        <td>
                            <div class="user-cell">
                                <div class="avatar">{{ strtoupper(substr($facture->patient->prenom, 0, 1) . substr($facture->patient->nom, 0, 1)) }}</div>
                                <span>{{ $facture->patient->prenom }} {{ $facture->patient->nom }}</span>
                            </div>
                        </td>
                        <td>{{ $facture->date->format('d/m/Y') }}</td>
                        <td><strong>{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</strong></td>
                        <td>
                            @if($facture->statut == 'payee')
                            <span class="badge badge-success">Payée</span>
                            @elseif($facture->statut == 'envoyee')
                            <span class="badge badge-info">Envoyée à caisse</span>
                            @else
                            <span class="badge badge-warning">En attente</span>
                            @endif
                        </td>
                        <td>
                            @if($facture->consultation)
                            <a href="{{ route('reception.consultations.show', $facture->consultation) }}" class="btn btn-outline btn-sm">Voir consultation</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">Aucune facture trouvée</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($factures->hasPages())
<div class="mt-4 flex justify-center">
    {{ $factures->links() }}
</div>
@endif
@endsection
