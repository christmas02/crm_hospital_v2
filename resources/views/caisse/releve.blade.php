@extends('layouts.medicare')

@section('title', 'Releve de compte - ' . $patient->prenom . ' ' . $patient->nom)
@section('sidebar-subtitle', 'Caisse')
@section('user-color', '#d97706')
@section('header-title', 'Releve de compte')

@section('header-right')
<a href="{{ route('caisse.releve', ['patient' => $patient->id, 'format' => 'pdf']) }}" class="btn btn-primary" target="_blank">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
    Telecharger PDF
</a>
@endsection

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('caisse._sidebar')
@endif
@endsection

@section('content')
<!-- Patient Info Header -->
<div class="card mb-4">
    <div class="card-body">
        <div style="display:flex;align-items:center;gap:20px;">
            <div class="avatar lg" style="width:70px;height:70px;font-size:1.3rem;background:{{ $patient->sexe == 'M' ? 'var(--primary-light)' : '#fce7f3' }};color:{{ $patient->sexe == 'M' ? 'var(--primary)' : '#db2777' }};">
                {{ strtoupper(substr($patient->prenom, 0, 1) . substr($patient->nom, 0, 1)) }}
            </div>
            <div style="flex:1;">
                <h2 style="font-size:1.4rem;margin-bottom:4px;">{{ $patient->prenom }} {{ $patient->nom }}</h2>
                <div class="text-muted" style="display:flex;gap:16px;flex-wrap:wrap;">
                    @if($patient->telephone)
                    <span style="display:flex;align-items:center;gap:4px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                        {{ $patient->telephone }}
                    </span>
                    @endif
                    @if($patient->adresse)
                    <span style="display:flex;align-items:center;gap:4px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $patient->adresse }}
                    </span>
                    @endif
                </div>
            </div>
            <a href="{{ route('reception.patients.show', $patient) }}" class="btn btn-outline">Voir dossier</a>
        </div>
    </div>
</div>

<!-- Stat Cards -->
<div class="stats-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
    <div class="card">
        <div class="card-body" style="text-align:center;padding:20px;">
            <div class="text-muted text-sm" style="margin-bottom:8px;">Total facture</div>
            <div style="font-size:1.5rem;font-weight:700;color:var(--primary);">{{ number_format($totaux['total_facture'], 0, ',', ' ') }} F</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body" style="text-align:center;padding:20px;">
            <div class="text-muted text-sm" style="margin-bottom:8px;">Total paye</div>
            <div style="font-size:1.5rem;font-weight:700;color:#16a34a;">{{ number_format($totaux['total_paye'], 0, ',', ' ') }} F</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body" style="text-align:center;padding:20px;">
            <div class="text-muted text-sm" style="margin-bottom:8px;">Solde du</div>
            <div style="font-size:1.5rem;font-weight:700;color:#dc2626;">{{ number_format($totaux['solde_du'], 0, ',', ' ') }} F</div>
        </div>
    </div>
</div>

<!-- Factures Table -->
<div class="card mb-4">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
            Factures
        </h2>
        <span class="text-muted text-sm">{{ $factures->count() }} facture(s)</span>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients">
                <thead>
                    <tr>
                        <th>N. Facture</th>
                        <th>Date</th>
                        <th style="text-align:right;">Montant</th>
                        <th style="text-align:right;">Paye</th>
                        <th style="text-align:right;">Restant</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($factures as $facture)
                    @php
                        $montantNet = $facture->montant_net ?: $facture->montant;
                        $restant = $montantNet - $facture->montant_paye;
                    @endphp
                    <tr>
                        <td style="font-weight:600;">{{ $facture->numero }}</td>
                        <td>{{ $facture->date->format('d/m/Y') }}</td>
                        <td style="text-align:right;">{{ number_format($facture->montant, 0, ',', ' ') }} F</td>
                        <td style="text-align:right;color:#16a34a;">{{ number_format($facture->montant_paye, 0, ',', ' ') }} F</td>
                        <td style="text-align:right;color:#dc2626;font-weight:600;">{{ number_format(max(0, $restant), 0, ',', ' ') }} F</td>
                        <td>
                            @if($facture->statut == 'payee')
                            <span class="badge badge-success">Payee</span>
                            @elseif($facture->statut == 'annulee')
                            <span class="badge badge-danger">Annulee</span>
                            @elseif($facture->montant_paye > 0)
                            <span class="badge badge-warning">Partielle</span>
                            @else
                            <span class="badge badge-info">En attente</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:32px;" class="text-muted">Aucune facture</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Paiements Table -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
            Paiements
        </h2>
        <span class="text-muted text-sm">{{ $paiements->count() }} paiement(s)</span>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>N. Recu</th>
                        <th>Facture</th>
                        <th style="text-align:right;">Montant</th>
                        <th>Mode</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paiements as $paiement)
                    <tr>
                        <td>{{ $paiement->date_paiement ? \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y H:i') : '-' }}</td>
                        <td style="font-weight:600;">{{ $paiement->numero_recu ?? '-' }}</td>
                        <td>{{ $paiement->facture->numero ?? '-' }}</td>
                        <td style="text-align:right;font-weight:600;color:#16a34a;">{{ number_format($paiement->montant, 0, ',', ' ') }} F</td>
                        <td>
                            @switch($paiement->mode_paiement)
                                @case('especes') <span class="badge" style="background:#fef3c7;color:#92400e;">Especes</span> @break
                                @case('mobile_money') <span class="badge" style="background:#dbeafe;color:#1e40af;">Mobile Money</span> @break
                                @case('carte') <span class="badge" style="background:#ede9fe;color:#6d28d9;">Carte</span> @break
                                @case('cheque') <span class="badge" style="background:#e0e7ff;color:#3730a3;">Cheque</span> @break
                                @case('virement') <span class="badge" style="background:#d1fae5;color:#065f46;">Virement</span> @break
                                @default <span class="badge badge-light">{{ $paiement->mode_paiement }}</span>
                            @endswitch
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;padding:32px;" class="text-muted">Aucun paiement</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
