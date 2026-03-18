@extends('layouts.medicare')

@section('title', 'Historique - Caisse')
@section('sidebar-subtitle', 'Caisse')
@section('user-color', '#d97706')
@section('header-title', 'Historique des paiements')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('caisse._sidebar')
@endif
@endsection

@section('content')
<div class="toolbar">
    <div class="filters">
        <form action="{{ route('caisse.historique') }}" method="GET" class="flex gap-2">
            <input type="date" class="filter-input" name="date" value="{{ request('date') }}">
            <select class="filter-select" name="mode">
                <option value="">Tous modes</option>
                <option value="especes" {{ request('mode') == 'especes' ? 'selected' : '' }}>Espèces</option>
                <option value="mobile_money" {{ request('mode') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                <option value="carte" {{ request('mode') == 'carte' ? 'selected' : '' }}>Carte</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Date</th><th>Patient</th><th>Type</th><th>Montant</th><th>Mode</th><th>Statut</th></tr>
                </thead>
                <tbody>
                    @forelse($paiements as $paiement)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="user-cell">
                                <div class="avatar">{{ strtoupper(substr($paiement->patient->prenom ?? '', 0, 1) . substr($paiement->patient->nom ?? '', 0, 1)) }}</div>
                                <span>{{ $paiement->patient->prenom ?? '' }} {{ $paiement->patient->nom ?? '' }}</span>
                            </div>
                        </td>
                        <td>{{ $paiement->facture->type ?? 'Consultation' }}</td>
                        <td><strong class="text-success">{{ number_format($paiement->montant, 0, ',', ' ') }} F</strong></td>
                        <td>
                            @php
                                $modes = ['especes' => 'Espèces', 'mobile_money' => 'Mobile Money', 'carte' => 'Carte', 'virement' => 'Virement'];
                            @endphp
                            {{ $modes[$paiement->mode_paiement] ?? $paiement->mode_paiement }}
                        </td>
                        <td><span class="badge badge-success">Payé</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">Aucun paiement trouvé</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($paiements->hasPages())
<div class="mt-4">
    {{ $paiements->links() }}
</div>
@endif
@endsection
