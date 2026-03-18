@extends('layouts.medicare')

@section('title', 'Facture - MediCare Pro')
@section('sidebar-subtitle', 'Caisse')
@section('user-color', '#d97706')
@section('header-title', 'Détails Facture')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('caisse._sidebar')
@endif
@endsection

@section('content')
<div class="card">
    <div class="card-body" style="padding:32px;">
        <!-- En-tête facture -->
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:32px;padding-bottom:24px;border-bottom:2px solid var(--border);">
            <div>
                <h1 style="font-size:1.75rem;font-weight:bold;color:var(--primary);margin-bottom:8px;">MediCare Pro</h1>
                <p class="text-muted">Centre Hospitalier</p>
                <p class="text-muted">Abidjan, Côte d'Ivoire</p>
            </div>
            <div style="text-align:right;">
                <h2 style="font-size:1.25rem;font-weight:bold;margin-bottom:8px;">FACTURE</h2>
                <p class="text-muted">N°: <strong>{{ $facture->numero }}</strong></p>
                <p class="text-muted">Date: {{ $facture->date->format('d/m/Y') }}</p>
                <div style="margin-top:12px;">
                    @if($facture->statut == 'payee')
                    <span class="badge badge-success" style="font-size:0.9rem;padding:6px 12px;">Payée</span>
                    @else
                    <span class="badge badge-warning" style="font-size:0.9rem;padding:6px 12px;">En attente</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informations patient -->
        <div style="margin-bottom:32px;">
            <h3 class="text-muted text-sm" style="text-transform:uppercase;margin-bottom:8px;">Patient</h3>
            <p style="font-weight:600;font-size:1.1rem;">{{ $facture->patient->prenom }} {{ $facture->patient->nom }}</p>
            <p class="text-muted">{{ $facture->patient->telephone }}</p>
            @if($facture->patient->adresse)
            <p class="text-muted">{{ $facture->patient->adresse }}</p>
            @endif
        </div>

        <!-- Lignes de facture -->
        <div class="table-wrap" style="margin-bottom:24px;">
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th style="text-align:center;">Qté</th>
                        <th style="text-align:right;">Prix unitaire</th>
                        <th style="text-align:right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($facture->lignes as $ligne)
                    <tr>
                        <td>{{ $ligne->description }}</td>
                        <td style="text-align:center;">{{ $ligne->quantite }}</td>
                        <td style="text-align:right;">{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} F</td>
                        <td style="text-align:right;font-weight:500;">{{ number_format($ligne->montant, 0, ',', ' ') }} F</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot style="background:var(--gray-100);">
                    <tr>
                        <td colspan="3" style="text-align:right;font-weight:bold;font-size:1.1rem;">TOTAL</td>
                        <td style="text-align:right;font-weight:bold;font-size:1.25rem;color:var(--primary);">{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Actions -->
        <div style="display:flex;justify-content:space-between;align-items:center;padding-top:24px;border-top:1px solid var(--border);">
            <a href="{{ route('caisse.factures.index') }}" class="btn btn-secondary">Retour</a>
            @if($facture->statut == 'en_attente')
            <form action="{{ route('caisse.factures.encaisser', $facture) }}" method="POST" style="display:flex;align-items:center;gap:12px;">
                @csrf
                <select name="mode_paiement" class="form-control" style="width:auto;">
                    <option value="especes">Espèces</option>
                    <option value="mobile_money">Mobile Money</option>
                    <option value="carte">Carte bancaire</option>
                    <option value="cheque">Chèque</option>
                </select>
                <button type="submit" class="btn btn-success" style="padding:12px 24px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                    Encaisser {{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA
                </button>
            </form>
            @else
            <div style="color:#059669;font-weight:500;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:middle;"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                Payée le {{ $facture->date_paiement ? $facture->date_paiement->format('d/m/Y à H:i') : '' }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
