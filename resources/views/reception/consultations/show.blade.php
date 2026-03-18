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
@php
    $patient = $consultation->patient;
    $medecin = $consultation->medecin;
    $isMale = $patient->sexe == 'M';
    $statusConfig = [
        'termine' => ['badge-success', 'Terminé', 'var(--success)'],
        'en_cours' => ['badge-info', 'En cours', 'var(--primary)'],
        'en_attente' => ['badge-warning', 'En attente', 'var(--warning)'],
    ];
    $st = $statusConfig[$consultation->statut] ?? $statusConfig['en_attente'];
@endphp

<!-- Hero Banner -->
<div style="background:linear-gradient(135deg, var(--gray-800), var(--gray-700));border-radius:18px;padding:28px 32px;margin-bottom:24px;position:relative;overflow:hidden;">
    <div style="position:absolute;inset:0;opacity:.04;background-image:url(\"data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Crect x='16' y='4' width='8' height='32' rx='2' fill='%23fff'/%3E%3Crect x='4' y='16' width='32' height='8' rx='2' fill='%23fff'/%3E%3C/svg%3E\");background-size:40px 40px;pointer-events:none;"></div>
    <div style="position:absolute;top:-40px;right:-40px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,.04);"></div>

    <div style="display:flex;align-items:center;gap:24px;position:relative;z-index:1;">
        <!-- Patient avatar -->
        <div style="width:72px;height:72px;border-radius:20px;background:{{ $isMale ? 'linear-gradient(135deg, #0891b2, #06b6d4)' : 'linear-gradient(135deg, #db2777, #ec4899)' }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:800;box-shadow:0 8px 24px rgba(0,0,0,.2);flex-shrink:0;">
            {{ strtoupper(substr($patient->prenom, 0, 1) . substr($patient->nom, 0, 1)) }}
        </div>
        <div style="flex:1;">
            <h2 style="font-size:1.4rem;font-weight:800;color:#fff;margin-bottom:4px;">{{ $patient->prenom }} {{ $patient->nom }}</h2>
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                <span style="color:rgba(255,255,255,.6);font-size:.88rem;">{{ \Carbon\Carbon::parse($patient->date_naissance)->age }} ans</span>
                <span style="width:4px;height:4px;border-radius:50%;background:rgba(255,255,255,.3);"></span>
                <span style="color:rgba(255,255,255,.6);font-size:.88rem;">Dr. {{ $medecin->prenom }} {{ $medecin->nom }}</span>
                <span style="width:4px;height:4px;border-radius:50%;background:rgba(255,255,255,.3);"></span>
                <span style="color:rgba(255,255,255,.6);font-size:.88rem;">{{ $consultation->date->format('d/m/Y') }} à {{ $consultation->heure }}</span>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
            <span style="padding:6px 16px;border-radius:10px;background:{{ $st[2] }}33;color:{{ $st[2] }};font-size:.82rem;font-weight:600;border:1px solid {{ $st[2] }}55;">{{ $st[1] }}</span>
            <a href="{{ route('reception.consultations.index') }}" class="btn btn-sm" style="background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.2);border-radius:10px;padding:8px 14px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            </a>
        </div>
    </div>
</div>

<!-- Info cards -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
    <div class="card" style="padding:16px;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:38px;height:38px;border-radius:10px;background:var(--primary-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
            <div>
                <div style="font-size:.65rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;">Spécialité</div>
                <div style="font-size:.88rem;font-weight:600;">{{ $medecin->specialite }}</div>
            </div>
        </div>
    </div>
    <div class="card" style="padding:16px;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:38px;height:38px;border-radius:10px;background:var(--warning-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--warning)" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72"/></svg>
            </div>
            <div>
                <div style="font-size:.65rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;">Téléphone</div>
                <div style="font-size:.88rem;font-weight:600;">{{ $patient->telephone }}</div>
            </div>
        </div>
    </div>
    <div class="card" style="padding:16px;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:38px;height:38px;border-radius:10px;background:var(--success-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>
            </div>
            <div>
                <div style="font-size:.65rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;">Groupe sanguin</div>
                <div style="font-size:.88rem;font-weight:600;">{{ $patient->groupe_sanguin ?? '—' }}</div>
            </div>
        </div>
    </div>
    <div class="card" style="padding:16px;">
        <div style="display:flex;align-items:center;gap:10px;">
            @if($patient->allergies && count(is_array($patient->allergies) ? $patient->allergies : []) > 0)
            <div style="width:38px;height:38px;border-radius:10px;background:var(--danger-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            </div>
            <div>
                <div style="font-size:.65rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;">Allergies</div>
                <div style="display:flex;gap:4px;flex-wrap:wrap;">
                    @foreach((is_array($patient->allergies) ? $patient->allergies : explode(',', $patient->allergies)) as $a)
                    <span class="badge badge-danger" style="font-size:.68rem;">{{ trim($a) }}</span>
                    @endforeach
                </div>
            </div>
            @else
            <div style="width:38px;height:38px;border-radius:10px;background:var(--gray-100);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
            </div>
            <div>
                <div style="font-size:.65rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;">Allergies</div>
                <div style="font-size:.88rem;font-weight:600;color:var(--gray-500);">Aucune</div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Motif -->
<div class="card mb-4">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
            Motif de la consultation
        </h2>
    </div>
    <div class="card-body">
        <p style="font-size:.95rem;color:var(--gray-700);line-height:1.6;margin:0;">{{ $consultation->motif }}</p>
        @if($consultation->diagnostic)
        <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--gray-200);">
            <div style="font-size:.72rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;margin-bottom:6px;">Diagnostic</div>
            <p style="font-size:.95rem;color:var(--gray-700);margin:0;padding:12px;background:var(--success-light);border-radius:10px;border-left:3px solid var(--success);">{{ $consultation->diagnostic }}</p>
        </div>
        @endif
        @if($consultation->notes)
        <div style="margin-top:12px;">
            <div style="font-size:.72rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;margin-bottom:6px;">Notes du médecin</div>
            <p style="font-size:.88rem;color:var(--gray-600);margin:0;font-style:italic;">{{ $consultation->notes }}</p>
        </div>
        @endif
    </div>
</div>

@if($consultation->ficheTraitement)
<!-- Fiche de traitement -->
<div class="card mb-4">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--secondary)" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
            Fiche de traitement
        </h2>
    </div>
    <div class="card-body">
        @if($consultation->ficheTraitement->observations)
        <div style="padding:14px;background:var(--gray-50);border-radius:10px;margin-bottom:16px;font-size:.9rem;color:var(--gray-700);line-height:1.6;">
            {{ $consultation->ficheTraitement->observations }}
        </div>
        @endif

        @if($consultation->ficheTraitement->actesMedicaux && $consultation->ficheTraitement->actesMedicaux->count() > 0)
        <div class="table-wrap">
            <table class="table-patients">
                <thead><tr><th>Acte médical</th><th style="text-align:right;">Prix</th></tr></thead>
                <tbody>
                    @foreach($consultation->ficheTraitement->actesMedicaux as $acte)
                    <tr>
                        <td style="font-weight:500;">{{ $acte->nom }}</td>
                        <td style="text-align:right;font-weight:600;color:var(--success);">{{ number_format($acte->prix ?? 0, 0, ',', ' ') }} F</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background:var(--gray-50);">
                        <td style="font-weight:700;">Total facturable</td>
                        <td style="text-align:right;font-weight:800;color:var(--primary);font-size:1.1rem;">{{ number_format($consultation->ficheTraitement->total_facturable ?? 0, 0, ',', ' ') }} F</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif
    </div>
</div>
@endif

@if($consultation->ordonnance && $consultation->ordonnance->medicaments && $consultation->ordonnance->medicaments->count() > 0)
<!-- Ordonnance -->
<div class="card mb-4">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>
            Ordonnance
        </h2>
        <span class="badge badge-{{ $consultation->ordonnance->statut_dispensation == 'remis' ? 'success' : ($consultation->ordonnance->statut_dispensation == 'prepare' ? 'info' : 'warning') }}">
            {{ $consultation->ordonnance->statut_dispensation == 'remis' ? 'Remis' : ($consultation->ordonnance->statut_dispensation == 'prepare' ? 'Préparé' : 'En attente') }}
        </span>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients">
                <thead><tr><th>Médicament</th><th>Posologie</th><th>Durée</th><th style="text-align:center;">Quantité</th></tr></thead>
                <tbody>
                    @foreach($consultation->ordonnance->medicaments as $med)
                    <tr>
                        <td style="font-weight:600;">{{ $med->nom }}</td>
                        <td>{{ $med->posologie }}</td>
                        <td>{{ $med->duree }}</td>
                        <td style="text-align:center;font-weight:600;">{{ $med->quantite }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($consultation->ordonnance->recommandations)
        <div style="padding:14px 20px;background:var(--gray-50);border-top:1px solid var(--gray-200);font-size:.85rem;color:var(--gray-600);">
            <strong>Recommandations :</strong> {{ $consultation->ordonnance->recommandations }}
        </div>
        @endif
    </div>
</div>
@endif

@if($consultation->facture)
<!-- Facture -->
<div class="card mb-4">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--warning)" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
            Facture {{ $consultation->facture->numero }}
        </h2>
        <span class="badge badge-{{ $consultation->facture->statut == 'payee' ? 'success' : 'warning' }}">{{ $consultation->facture->statut == 'payee' ? 'Payée' : 'En attente' }}</span>
    </div>
    <div class="card-body">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <div style="display:flex;gap:24px;">
                <div>
                    <div style="font-size:.68rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;">Montant</div>
                    <div style="font-size:1.4rem;font-weight:800;color:var(--primary);">{{ number_format($consultation->facture->montant_total, 0, ',', ' ') }} F</div>
                </div>
                @if($consultation->facture->montant_paye > 0)
                <div>
                    <div style="font-size:.68rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;">Payé</div>
                    <div style="font-size:1.1rem;font-weight:700;color:var(--success);">{{ number_format($consultation->facture->montant_paye, 0, ',', ' ') }} F</div>
                </div>
                @endif
            </div>
            <a href="{{ route('caisse.factures.show', $consultation->facture) }}" class="btn btn-outline btn-sm">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                Voir la facture
            </a>
        </div>
    </div>
</div>
@endif
@endsection
