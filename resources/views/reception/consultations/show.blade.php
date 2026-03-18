@extends('layouts.medicare')

@section('title', 'Consultation - MediCare Pro')
@section('sidebar-subtitle', 'Réception')
@section('user-color', '#059669')
@section('header-title', 'Détails Consultation')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('reception._sidebar')
@endif
@endsection

@section('content')
<!-- En-tête Consultation -->
<div class="card mb-4">
    <div class="card-body">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <div>
                <h2 style="font-size:1.5rem;margin-bottom:4px;">Consultation #{{ $consultation->id }}</h2>
                <div class="text-muted">{{ $consultation->date->format('d/m/Y') }} à {{ $consultation->heure }}</div>
            </div>
            <div>
                @if($consultation->statut == 'termine')
                <span class="badge badge-success">Terminé</span>
                @elseif($consultation->statut == 'en_cours')
                <span class="badge badge-info">En cours</span>
                @else
                <span class="badge badge-warning">En attente</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="grid-2">
    <!-- Informations consultation -->
    <div class="card">
        <div class="card-header"><h2 class="card-title">Détails de la consultation</h2></div>
        <div class="card-body">
            <div class="info-grid" style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;">
                <div>
                    <div class="text-muted text-sm">Patient</div>
                    <div style="font-weight:500;">
                        <a href="{{ route('reception.patients.show', $consultation->patient) }}" style="color:var(--primary);">
                            {{ $consultation->patient->prenom }} {{ $consultation->patient->nom }}
                        </a>
                    </div>
                </div>
                <div>
                    <div class="text-muted text-sm">Médecin</div>
                    <div style="font-weight:500;">Dr. {{ $consultation->medecin->prenom }} {{ $consultation->medecin->nom }}</div>
                </div>
                <div>
                    <div class="text-muted text-sm">Spécialité</div>
                    <div style="font-weight:500;">{{ $consultation->medecin->specialite }}</div>
                </div>
                <div>
                    <div class="text-muted text-sm">Type</div>
                    <div style="font-weight:500;">{{ ucfirst($consultation->type ?? 'Consultation') }}</div>
                </div>
            </div>
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);">
                <div class="text-muted text-sm mb-2">Motif</div>
                <div>{{ $consultation->motif }}</div>
            </div>
        </div>
    </div>

    <!-- Informations patient -->
    <div class="card">
        <div class="card-header"><h2 class="card-title">Patient</h2></div>
        <div class="card-body">
            <div class="user-cell mb-4">
                <div class="avatar lg" style="background:var(--primary);color:#fff;">
                    {{ strtoupper(substr($consultation->patient->prenom, 0, 1) . substr($consultation->patient->nom, 0, 1)) }}
                </div>
                <div>
                    <div class="user-name" style="font-size:1.1rem;">{{ $consultation->patient->prenom }} {{ $consultation->patient->nom }}</div>
                    <div class="text-muted">{{ \Carbon\Carbon::parse($consultation->patient->date_naissance)->age }} ans - {{ $consultation->patient->sexe == 'M' ? 'Masculin' : 'Féminin' }}</div>
                </div>
            </div>
            <div class="info-grid" style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px;">
                <div>
                    <div class="text-muted text-sm">Téléphone</div>
                    <div style="font-weight:500;">{{ $consultation->patient->telephone }}</div>
                </div>
                <div>
                    <div class="text-muted text-sm">Groupe sanguin</div>
                    <div style="font-weight:500;">{{ $consultation->patient->groupe_sanguin ?? 'Non renseigné' }}</div>
                </div>
            </div>
            @if($consultation->patient->allergies)
            <div style="margin-top:12px;">
                <div class="text-muted text-sm mb-1">Allergies</div>
                <div class="flex flex-wrap gap-2">
                    @foreach((is_array($consultation->patient->allergies) ? $consultation->patient->allergies : explode(',', $consultation->patient->allergies)) as $allergie)
                    <span class="badge badge-danger">{{ trim($allergie) }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@if($consultation->ficheTraitement)
<!-- Fiche de traitement -->
<div class="card mt-4">
    <div class="card-header"><h2 class="card-title">Fiche de traitement</h2></div>
    <div class="card-body">
        <div class="grid-2" style="gap:20px;">
            @if($consultation->ficheTraitement->diagnostic)
            <div>
                <div class="text-muted text-sm mb-1">Diagnostic</div>
                <div style="padding:12px;background:var(--gray-100);border-radius:8px;">{{ $consultation->ficheTraitement->diagnostic }}</div>
            </div>
            @endif
            @if($consultation->ficheTraitement->observations)
            <div>
                <div class="text-muted text-sm mb-1">Observations</div>
                <div style="padding:12px;background:var(--gray-100);border-radius:8px;">{{ $consultation->ficheTraitement->observations }}</div>
            </div>
            @endif
        </div>

        @if($consultation->ficheTraitement->actesMedicaux && $consultation->ficheTraitement->actesMedicaux->count() > 0)
        <div style="margin-top:20px;">
            <div class="text-muted text-sm mb-2">Actes médicaux</div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Acte</th><th style="text-align:right;">Prix</th></tr></thead>
                    <tbody>
                        @foreach($consultation->ficheTraitement->actesMedicaux as $acte)
                        <tr>
                            <td>{{ $acte->nom }}</td>
                            <td style="text-align:right;">{{ number_format($acte->prix, 0, ',', ' ') }} FCFA</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endif

@if($consultation->ordonnance && $consultation->ordonnance->medicaments->count() > 0)
<!-- Ordonnance -->
<div class="card mt-4">
    <div class="card-header"><h2 class="card-title">Ordonnance</h2></div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Médicament</th><th>Posologie</th><th>Durée</th><th>Quantité</th></tr>
                </thead>
                <tbody>
                    @foreach($consultation->ordonnance->medicaments as $medicament)
                    <tr>
                        <td><strong>{{ $medicament->nom }}</strong></td>
                        <td>{{ $medicament->posologie }}</td>
                        <td>{{ $medicament->duree }}</td>
                        <td>{{ $medicament->quantite }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@if($consultation->facture)
<!-- Facture -->
<div class="card mt-4">
    <div class="card-header">
        <h2 class="card-title">Facture</h2>
        @if($consultation->facture->statut == 'payee')
        <span class="badge badge-success">Payée</span>
        @else
        <span class="badge badge-warning">En attente</span>
        @endif
    </div>
    <div class="card-body">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <div>
                <div class="text-muted text-sm">Numéro</div>
                <div style="font-weight:500;">{{ $consultation->facture->numero }}</div>
            </div>
            <div style="text-align:right;">
                <div class="text-muted text-sm">Total</div>
                <div style="font-size:1.5rem;font-weight:bold;color:var(--primary);">{{ number_format($consultation->facture->montant_total, 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
    </div>
</div>
@endif

<div style="margin-top:24px;">
    <a href="{{ route('reception.consultations.index') }}" class="btn btn-secondary">Retour à la liste</a>
</div>
@endsection
