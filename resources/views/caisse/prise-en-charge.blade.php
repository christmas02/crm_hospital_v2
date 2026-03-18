@extends('layouts.medicare')

@section('title', 'Prises en charge - Caisse')
@section('sidebar-subtitle', 'Caisse')
@section('user-color', '#d97706')
@section('header-title', 'Prises en charge — Assurances & Mutuelles')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('caisse._sidebar')
@endif
@endsection

@section('content')

<!-- Stats -->
<div class="stats" style="grid-template-columns: repeat(4, 1fr); margin-bottom:24px;">
    <div class="stat-card" style="border-left: 4px solid var(--accent);">
        <div>
            <div class="stat-label">Organismes</div>
            <div class="stat-value">{{ $totaux['nb_organismes'] }}</div>
            <div class="stat-sub">assurances / mutuelles</div>
        </div>
        <div class="stat-icon purple">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M9 22V12h6v10"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--primary);">
        <div>
            <div class="stat-label">Factures concernées</div>
            <div class="stat-value">{{ $totaux['nb_factures'] }}</div>
            <div class="stat-sub">avec prise en charge</div>
        </div>
        <div class="stat-icon cyan">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--warning);">
        <div>
            <div class="stat-label">Total couvert</div>
            <div class="stat-value" style="font-size:1.3rem;">{{ number_format($totaux['total_couvert'], 0, ',', ' ') }} F</div>
            <div class="stat-sub">part organismes</div>
        </div>
        <div class="stat-icon orange">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--secondary);">
        <div>
            <div class="stat-label">Total facturé</div>
            <div class="stat-value" style="font-size:1.3rem;">{{ number_format($totaux['total_factures'], 0, ',', ' ') }} F</div>
            <div class="stat-sub">montant brut</div>
        </div>
        <div class="stat-icon green">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
        </div>
    </div>
</div>

<!-- Filtre par organisme -->
<div class="toolbar" style="margin-bottom:20px;">
    <div class="filters">
        <form action="{{ route('caisse.prise-en-charge') }}" method="GET" style="display:flex;gap:10px;align-items:center;">
            <select class="filter-select" name="organisme" onchange="this.form.submit()">
                <option value="">Tous les organismes</option>
                @foreach($organismes as $org)
                <option value="{{ $org }}" {{ $organismeFiltre == $org ? 'selected' : '' }}>{{ $org }}</option>
                @endforeach
            </select>
            @if($organismeFiltre)
            <a href="{{ route('caisse.prise-en-charge') }}" class="btn btn-secondary btn-sm">Réinitialiser</a>
            @endif
        </form>
    </div>
</div>

<!-- Cartes par organisme -->
@forelse($parOrganisme as $data)
<div class="card mb-4">
    <div class="card-header">
        <h2 class="card-title" style="display:flex;align-items:center;gap:10px;">
            <div style="width:40px;height:40px;border-radius:12px;background:var(--accent);color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M9 22V12h6v10"/></svg>
            </div>
            <div>
                <div>{{ $data['organisme'] }}</div>
                <div style="font-size:.75rem;color:var(--gray-500);font-weight:400;">{{ ucfirst($data['type'] ?? 'assurance') }} &bull; {{ $data['nb_factures'] }} factures</div>
            </div>
        </h2>
        <div style="text-align:right;">
            <div style="font-size:.72rem;color:var(--gray-500);text-transform:uppercase;font-weight:600;">Total dû par l'organisme</div>
            <div style="font-size:1.4rem;font-weight:800;color:var(--accent);">{{ number_format($data['total_couvert'], 0, ',', ' ') }} F</div>
        </div>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>N° Facture</th>
                        <th>N° Assurance</th>
                        <th>Date</th>
                        <th style="text-align:right;">Montant total</th>
                        <th style="text-align:center;">Taux</th>
                        <th style="text-align:right;">Part organisme</th>
                        <th style="text-align:right;">Part patient</th>
                        <th style="text-align:center;">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['factures'] as $facture)
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="avatar" style="width:30px;height:30px;font-size:.7rem;">{{ strtoupper(substr($facture->patient->prenom ?? '', 0, 1) . substr($facture->patient->nom ?? '', 0, 1)) }}</div>
                                <div>
                                    <div class="user-name">{{ $facture->patient->prenom ?? '' }} {{ $facture->patient->nom ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td><strong>{{ $facture->numero }}</strong></td>
                        <td style="font-size:.82rem;">{{ $facture->numero_assurance ?? '—' }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                {{ $facture->date->format('d/m/Y') }}
                            </div>
                        </td>
                        <td style="text-align:right;font-weight:600;">{{ number_format($facture->montant, 0, ',', ' ') }} F</td>
                        <td style="text-align:center;">
                            <span class="badge badge-info">{{ $facture->taux_couverture }}%</span>
                        </td>
                        <td style="text-align:right;font-weight:700;color:var(--accent);">{{ number_format($facture->montant_couvert, 0, ',', ' ') }} F</td>
                        <td style="text-align:right;font-weight:600;">{{ number_format($facture->montant_patient, 0, ',', ' ') }} F</td>
                        <td style="text-align:center;">
                            @if($facture->statut === 'payee')
                            <span class="badge badge-success">Payée</span>
                            @elseif($facture->statut === 'annulee')
                            <span class="badge badge-secondary">Annulée</span>
                            @else
                            <span class="badge badge-warning">En attente</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background:var(--gray-50);">
                        <td colspan="4" style="font-weight:700;">TOTAL {{ $data['organisme'] }}</td>
                        <td style="text-align:right;font-weight:700;">{{ number_format($data['total_facture'], 0, ',', ' ') }} F</td>
                        <td></td>
                        <td style="text-align:right;font-weight:800;color:var(--accent);">{{ number_format($data['total_couvert'], 0, ',', ' ') }} F</td>
                        <td style="text-align:right;font-weight:700;">{{ number_format($data['total_facture'] - $data['total_couvert'], 0, ',', ' ') }} F</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@empty
<div class="card">
    <div style="text-align:center;padding:60px;">
        <div style="width:72px;height:72px;border-radius:50%;background:var(--gray-100);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M9 22V12h6v10"/></svg>
        </div>
        <div style="font-size:1rem;font-weight:700;color:var(--gray-600);margin-bottom:4px;">Aucune prise en charge</div>
        <div style="font-size:.85rem;color:var(--gray-400);">Les factures avec prise en charge assurance/mutuelle apparaîtront ici</div>
    </div>
</div>
@endforelse

@endsection
