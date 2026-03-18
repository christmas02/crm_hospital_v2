@extends('layouts.medicare')

@section('title', 'Carnet de sante - MediCare Pro')
@section('sidebar-subtitle', 'Reception')
@section('user-color', '#059669')
@section('header-title', 'Carnet de sante numerique')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('reception._sidebar')
@endif
@endsection

@section('content')
@php
    $age = \Carbon\Carbon::parse($patient->date_naissance)->age;
    $isMale = $patient->sexe == 'M';
    $avatarBg = $isMale ? 'linear-gradient(135deg, #0891b2, #06b6d4)' : 'linear-gradient(135deg, #db2777, #ec4899)';
@endphp

<!-- Hero Banner -->
<div style="background:linear-gradient(135deg, #059669, #10b981);border-radius:18px;padding:28px 32px;margin-bottom:24px;position:relative;overflow:hidden;">
    <div style="position:absolute;inset:0;opacity:.06;background-image:url(\"data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Crect x='16' y='4' width='8' height='32' rx='2' fill='%23fff'/%3E%3Crect x='4' y='16' width='32' height='8' rx='2' fill='%23fff'/%3E%3C/svg%3E\");background-size:40px 40px;pointer-events:none;"></div>
    <div style="position:absolute;top:-30px;right:-30px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,.08);pointer-events:none;"></div>

    <div style="display:flex;align-items:center;gap:24px;position:relative;z-index:1;">
        <div style="width:88px;height:88px;border-radius:22px;background:{{ $avatarBg }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.8rem;font-weight:800;box-shadow:0 8px 24px rgba(0,0,0,.2);flex-shrink:0;">
            {{ strtoupper(substr($patient->prenom, 0, 1) . substr($patient->nom, 0, 1)) }}
        </div>
        <div style="flex:1;">
            <div style="font-size:.78rem;color:rgba(255,255,255,.7);text-transform:uppercase;letter-spacing:1px;font-weight:600;margin-bottom:4px;">Carnet de sante numerique</div>
            <h2 style="font-size:1.6rem;font-weight:800;color:#fff;margin-bottom:6px;letter-spacing:-.02em;">{{ $patient->prenom }} {{ $patient->nom }}</h2>
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <span style="color:rgba(255,255,255,.8);font-size:.9rem;">{{ $age }} ans</span>
                <span style="width:4px;height:4px;border-radius:50%;background:rgba(255,255,255,.4);"></span>
                <span style="padding:3px 10px;border-radius:8px;background:rgba(255,255,255,.2);color:#fff;font-size:.75rem;font-weight:600;">{{ $isMale ? 'Homme' : 'Femme' }}</span>
                @if($patient->groupe_sanguin)
                <span style="padding:3px 10px;border-radius:8px;background:rgba(255,255,255,.2);color:#fff;font-size:.75rem;font-weight:600;">{{ $patient->groupe_sanguin }}</span>
                @endif
            </div>
        </div>
        <div style="display:flex;gap:10px;flex-shrink:0;">
            <a href="{{ route('reception.patients.carnet.pdf', $patient) }}" target="_blank" class="btn btn-sm" style="background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.3);border-radius:10px;padding:8px 16px;text-decoration:none;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/><path d="M12 18v-6M9 15l3 3 3-3"/></svg>
                PDF
            </a>
            <a href="{{ route('reception.patients.show', $patient) }}" class="btn btn-sm" style="background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.2);border-radius:10px;padding:8px 16px;text-decoration:none;">
                Retour au dossier
            </a>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="tabs" style="margin-bottom:20px;">
    <button class="tab active" onclick="showCarnetTab('general')">Informations</button>
    <button class="tab" onclick="showCarnetTab('vitaux')">Signes vitaux</button>
    <button class="tab" onclick="showCarnetTab('consultations')">Consultations</button>
    <button class="tab" onclick="showCarnetTab('vaccinations')">Vaccinations</button>
    <button class="tab" onclick="showCarnetTab('labo')">Analyses labo</button>
    <button class="tab" onclick="showCarnetTab('ordonnances')">Ordonnances</button>
    <button class="tab" onclick="showCarnetTab('hospitalisations')">Hospitalisations</button>
    <button class="tab" onclick="showCarnetTab('documents')">Documents</button>
</div>

<!-- Tab 1: Informations generales -->
<div id="tabGeneral" class="carnet-tab">
    <div class="grid-2" style="gap:20px;">
        <!-- Informations personnelles -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Informations personnelles
                </h2>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div><div style="font-size:.7rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;margin-bottom:4px;">Nom complet</div><div style="font-weight:600;">{{ $patient->prenom }} {{ $patient->nom }}</div></div>
                    <div><div style="font-size:.7rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;margin-bottom:4px;">Date de naissance</div><div style="font-weight:600;">{{ $patient->date_naissance->format('d/m/Y') }} ({{ $age }} ans)</div></div>
                    <div><div style="font-size:.7rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;margin-bottom:4px;">Sexe</div><div style="font-weight:600;">{{ $isMale ? 'Masculin' : 'Feminin' }}</div></div>
                    <div><div style="font-size:.7rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;margin-bottom:4px;">Groupe sanguin</div><div style="font-weight:600;">{{ $patient->groupe_sanguin ?? 'Non renseigne' }}</div></div>
                    <div><div style="font-size:.7rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;margin-bottom:4px;">Telephone</div><div style="font-weight:600;">{{ $patient->telephone ?? '—' }}</div></div>
                    <div><div style="font-size:.7rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;margin-bottom:4px;">Email</div><div style="font-weight:600;">{{ $patient->email ?? '—' }}</div></div>
                    <div style="grid-column:span 2;"><div style="font-size:.7rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;margin-bottom:4px;">Adresse</div><div style="font-weight:600;">{{ $patient->adresse ?? '—' }}</div></div>
                </div>
            </div>
        </div>

        <!-- Allergies & dossier medical -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
                    Dossier medical
                </h2>
            </div>
            <div class="card-body">
                <!-- Allergies -->
                <div style="margin-bottom:16px;">
                    <div style="font-size:.7rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;margin-bottom:8px;">Allergies</div>
                    @if($patient->allergies && count(is_array($patient->allergies) ? $patient->allergies : []))
                    <div style="display:flex;flex-wrap:wrap;gap:6px;">
                        @foreach((is_array($patient->allergies) ? $patient->allergies : explode(',', $patient->allergies)) as $allergie)
                        <span style="padding:5px 12px;background:var(--danger-light);color:var(--danger);border-radius:8px;font-size:.78rem;font-weight:600;">{{ trim($allergie) }}</span>
                        @endforeach
                    </div>
                    @else
                    <span style="font-size:.85rem;color:var(--gray-400);">Aucune allergie connue</span>
                    @endif
                </div>

                @if($patient->dossierMedical)
                <!-- Antecedents -->
                <div style="margin-bottom:14px;padding-top:14px;border-top:1px solid var(--gray-200);">
                    <div style="font-size:.7rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;margin-bottom:6px;">Antecedents</div>
                    @if($patient->dossierMedical->antecedents && count($patient->dossierMedical->antecedents))
                    <div style="display:flex;flex-wrap:wrap;gap:6px;">
                        @foreach($patient->dossierMedical->antecedents as $ant)
                        <span style="padding:4px 10px;background:var(--gray-100);border-radius:6px;font-size:.78rem;">{{ $ant }}</span>
                        @endforeach
                    </div>
                    @else <span style="font-size:.82rem;color:var(--gray-400);">Aucun</span> @endif
                </div>
                <!-- Maladies chroniques -->
                <div style="margin-bottom:14px;padding-top:14px;border-top:1px solid var(--gray-200);">
                    <div style="font-size:.7rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;margin-bottom:6px;">Maladies chroniques</div>
                    @if($patient->dossierMedical->maladies_chroniques && count($patient->dossierMedical->maladies_chroniques))
                    <div style="display:flex;flex-wrap:wrap;gap:6px;">
                        @foreach($patient->dossierMedical->maladies_chroniques as $mc)
                        <span style="padding:4px 10px;background:#fef3c7;color:#92400e;border-radius:6px;font-size:.78rem;font-weight:500;">{{ $mc }}</span>
                        @endforeach
                    </div>
                    @else <span style="font-size:.82rem;color:var(--gray-400);">Aucune</span> @endif
                </div>
                <!-- Chirurgies -->
                <div style="padding-top:14px;border-top:1px solid var(--gray-200);">
                    <div style="font-size:.7rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;margin-bottom:6px;">Chirurgies</div>
                    @if($patient->dossierMedical->chirurgies && count($patient->dossierMedical->chirurgies))
                    <div style="display:flex;flex-wrap:wrap;gap:6px;">
                        @foreach($patient->dossierMedical->chirurgies as $ch)
                        <span style="padding:4px 10px;background:#ede9fe;color:#5b21b6;border-radius:6px;font-size:.78rem;font-weight:500;">{{ $ch }}</span>
                        @endforeach
                    </div>
                    @else <span style="font-size:.82rem;color:var(--gray-400);">Aucune</span> @endif
                </div>
                @else
                <div style="padding-top:14px;border-top:1px solid var(--gray-200);">
                    <span style="font-size:.82rem;color:var(--gray-400);">Dossier medical non cree</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Tab 2: Signes vitaux -->
<div id="tabVitaux" class="carnet-tab hidden">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                Historique des signes vitaux
            </h2>
            <span class="badge badge-success">{{ $patient->signesVitaux->count() }} mesures</span>
        </div>
        <div class="card-body no-pad">
            <div class="table-wrap">
                <table class="table-patients" style="font-size:.85rem;">
                    <thead>
                        <tr><th>Date</th><th>Temp.</th><th>Tension</th><th>Pouls</th><th>Sat O2</th><th>Poids</th><th>Taille</th><th>IMC</th><th>Glycemie</th></tr>
                    </thead>
                    <tbody>
                        @forelse($patient->signesVitaux as $sv)
                        <tr>
                            <td style="white-space:nowrap;">
                                <div style="font-weight:600;">{{ $sv->created_at->format('d/m/Y') }}</div>
                                <div style="font-size:.72rem;color:var(--gray-400);">{{ $sv->created_at->format('H:i') }}</div>
                            </td>
                            <td>
                                @if($sv->temperature)
                                <span style="{{ $sv->temperature > 38 ? 'color:#dc2626;font-weight:700;' : ($sv->temperature < 36 ? 'color:#2563eb;font-weight:700;' : '') }}">{{ $sv->temperature }}°C</span>
                                @else — @endif
                            </td>
                            <td>
                                @if($sv->tension_systolique || $sv->tension_diastolique)
                                <span style="{{ ($sv->tension_systolique > 140 || $sv->tension_diastolique > 90) ? 'color:#dc2626;font-weight:700;' : '' }}">{{ $sv->tension_systolique ?? '-' }}/{{ $sv->tension_diastolique ?? '-' }}</span>
                                @else — @endif
                            </td>
                            <td>
                                @if($sv->pouls)
                                <span style="{{ $sv->pouls > 100 ? 'color:#ea580c;font-weight:700;' : ($sv->pouls < 50 ? 'color:#2563eb;font-weight:700;' : '') }}">{{ $sv->pouls }}</span>
                                @else — @endif
                            </td>
                            <td>
                                @if($sv->saturation_o2)
                                <span style="{{ $sv->saturation_o2 < 95 ? 'color:#dc2626;font-weight:700;' : '' }}">{{ $sv->saturation_o2 }}%</span>
                                @else — @endif
                            </td>
                            <td>{{ $sv->poids ? $sv->poids . ' kg' : '—' }}</td>
                            <td>{{ $sv->taille ? $sv->taille . ' cm' : '—' }}</td>
                            <td>{{ $sv->imc ?? '—' }}</td>
                            <td>{{ $sv->glycemie ? $sv->glycemie . ' g/L' : '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="9" style="text-align:center;padding:40px;" class="text-muted">Aucun signe vital enregistre</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tab 3: Consultations -->
<div id="tabConsultations" class="carnet-tab hidden">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                Historique des consultations
            </h2>
            <span class="badge badge-primary">{{ $patient->consultations->count() }}</span>
        </div>
        <div class="card-body no-pad">
            <div class="table-wrap">
                <table class="table-patients">
                    <thead>
                        <tr><th>Date</th><th>Medecin</th><th>Motif</th><th>Diagnostic</th><th>Statut</th></tr>
                    </thead>
                    <tbody>
                        @forelse($patient->consultations as $c)
                        <tr>
                            <td style="white-space:nowrap;">
                                <div style="font-weight:600;">{{ $c->date->format('d/m/Y') }}</div>
                                <div style="font-size:.72rem;color:var(--gray-400);">{{ $c->heure }}</div>
                            </td>
                            <td>Dr. {{ $c->medecin->prenom ?? '' }} {{ $c->medecin->nom ?? '' }}</td>
                            <td class="truncate" style="max-width:200px;">{{ $c->motif }}</td>
                            <td class="truncate" style="max-width:200px;">{{ $c->diagnostic ?? '—' }}</td>
                            <td>
                                @if($c->statut == 'termine')
                                <span class="badge badge-success">Termine</span>
                                @elseif($c->statut == 'en_cours')
                                <span class="badge badge-info">En cours</span>
                                @else
                                <span class="badge badge-warning">En attente</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center;padding:40px;" class="text-muted">Aucune consultation</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tab 4: Vaccinations -->
<div id="tabVaccinations" class="carnet-tab hidden">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>
                Vaccinations
            </h2>
            <button class="btn btn-primary btn-sm" onclick="openModal('modalVaccination')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                Ajouter vaccination
            </button>
        </div>
        <div class="card-body no-pad">
            @php
                $overdueVacc = $patient->vaccinations->filter(fn($v) => $v->prochain_rappel && $v->prochain_rappel->isPast());
            @endphp
            @if($overdueVacc->count() > 0)
            <div style="padding:12px 16px;background:#fef2f2;border-bottom:1px solid #fecaca;display:flex;align-items:center;gap:8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
                <span style="color:#dc2626;font-size:.82rem;font-weight:600;">{{ $overdueVacc->count() }} rappel(s) vaccinal(aux) en retard !</span>
            </div>
            @endif
            <div class="table-wrap">
                <table class="table-patients" style="font-size:.85rem;">
                    <thead>
                        <tr><th>Vaccin</th><th>Maladie</th><th>Date</th><th>Dose</th><th>Lot</th><th>Site</th><th>Prochain rappel</th><th>Notes</th></tr>
                    </thead>
                    <tbody>
                        @forelse($patient->vaccinations as $v)
                        <tr>
                            <td style="font-weight:600;">{{ $v->vaccin }}</td>
                            <td>{{ $v->maladie }}</td>
                            <td style="white-space:nowrap;">{{ $v->date_administration->format('d/m/Y') }}</td>
                            <td>{{ $v->dose ?? '—' }}</td>
                            <td>{{ $v->lot ?? '—' }}</td>
                            <td>{{ $v->site_injection ?? '—' }}</td>
                            <td style="white-space:nowrap;">
                                @if($v->prochain_rappel)
                                    @if($v->prochain_rappel->isPast())
                                    <span style="color:#dc2626;font-weight:700;background:#fef2f2;padding:2px 8px;border-radius:6px;">{{ $v->prochain_rappel->format('d/m/Y') }}</span>
                                    @else
                                    <span style="color:#059669;">{{ $v->prochain_rappel->format('d/m/Y') }}</span>
                                    @endif
                                @else — @endif
                            </td>
                            <td class="truncate" style="max-width:150px;">{{ $v->notes ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="8" style="text-align:center;padding:40px;" class="text-muted">Aucune vaccination enregistree</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tab 5: Analyses labo -->
<div id="tabLabo" class="carnet-tab hidden">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0891b2" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"/><path d="M14 2v6h6"/></svg>
                Analyses de laboratoire
            </h2>
            <span class="badge badge-info">{{ $patient->demandesLabo->count() }}</span>
        </div>
        <div class="card-body">
            @forelse($patient->demandesLabo as $demande)
            <div style="border:1px solid var(--gray-200);border-radius:12px;padding:16px;margin-bottom:14px;{{ $loop->last ? '' : '' }}">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                    <div>
                        <span style="font-weight:700;font-size:.9rem;">{{ $demande->numero ?? 'Demande #' . $demande->id }}</span>
                        <span style="margin-left:8px;font-size:.78rem;color:var(--gray-400);">{{ $demande->date_demande->format('d/m/Y') }}</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        @if($demande->urgence)
                        <span class="badge badge-danger">Urgent</span>
                        @endif
                        @if($demande->statut == 'termine')
                        <span class="badge badge-success">Termine</span>
                        @elseif($demande->statut == 'en_cours')
                        <span class="badge badge-info">En cours</span>
                        @else
                        <span class="badge badge-warning">En attente</span>
                        @endif
                    </div>
                </div>
                @if($demande->medecin)
                <div style="font-size:.78rem;color:var(--gray-500);margin-bottom:8px;">Prescrit par Dr. {{ $demande->medecin->prenom }} {{ $demande->medecin->nom }}</div>
                @endif
                @if($demande->resultats && $demande->resultats->count() > 0)
                <div class="table-wrap" style="margin-top:8px;">
                    <table class="table-patients" style="font-size:.82rem;">
                        <thead><tr><th>Examen</th><th>Valeur</th><th>Unite</th><th>Reference</th><th>Interpretation</th></tr></thead>
                        <tbody>
                            @foreach($demande->resultats as $r)
                            <tr>
                                <td style="font-weight:500;">{{ $r->examen->nom ?? 'Examen' }}</td>
                                <td style="font-weight:600;">{{ $r->valeur }}</td>
                                <td>{{ $r->unite ?? '—' }}</td>
                                <td style="font-size:.78rem;">{{ $r->valeur_reference ?? '—' }}</td>
                                <td>
                                    @if($r->interpretation == 'normal')
                                    <span class="badge badge-success">Normal</span>
                                    @elseif($r->interpretation == 'eleve')
                                    <span class="badge badge-danger">Eleve</span>
                                    @elseif($r->interpretation == 'bas')
                                    <span class="badge badge-warning">Bas</span>
                                    @else
                                    <span class="badge badge-secondary">{{ $r->interpretation ?? '—' }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
            @empty
            <div style="text-align:center;padding:40px;" class="text-muted">Aucune analyse de laboratoire</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Tab 6: Ordonnances -->
<div id="tabOrdonnances" class="carnet-tab hidden">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--secondary)" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8M16 17H8M10 9H8"/></svg>
                Ordonnances
            </h2>
            <span class="badge badge-secondary">{{ $patient->ordonnances->count() }}</span>
        </div>
        <div class="card-body">
            @forelse($patient->ordonnances as $ord)
            <div style="border:1px solid var(--gray-200);border-radius:12px;padding:16px;margin-bottom:14px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                    <div>
                        <span style="font-weight:700;">Ordonnance #{{ $ord->id }}</span>
                        <span style="margin-left:8px;font-size:.78rem;color:var(--gray-400);">{{ $ord->created_at->format('d/m/Y') }}</span>
                    </div>
                    @if($ord->medecin)
                    <span style="font-size:.78rem;color:var(--gray-500);">Dr. {{ $ord->medecin->prenom }} {{ $ord->medecin->nom }}</span>
                    @endif
                </div>
                @if($ord->medicaments && $ord->medicaments->count() > 0)
                <div class="table-wrap">
                    <table class="table-patients" style="font-size:.82rem;">
                        <thead><tr><th>Medicament</th><th>Posologie</th><th>Duree</th><th>Quantite</th></tr></thead>
                        <tbody>
                            @foreach($ord->medicaments as $med)
                            <tr>
                                <td style="font-weight:500;">{{ $med->nom ?? ($med->medicament->nom ?? '—') }}</td>
                                <td>{{ $med->posologie ?? '—' }}</td>
                                <td>{{ $med->duree ?? '—' }}</td>
                                <td>{{ $med->quantite ?? '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                @if($ord->recommandations)
                <div style="margin-top:8px;padding:8px 12px;background:var(--gray-50);border-radius:8px;font-size:.82rem;color:var(--gray-600);">
                    <strong>Recommandations :</strong> {{ $ord->recommandations }}
                </div>
                @endif
            </div>
            @empty
            <div style="text-align:center;padding:40px;" class="text-muted">Aucune ordonnance</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Tab 7: Hospitalisations -->
<div id="tabHospitalisations" class="carnet-tab hidden">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--warning)" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M9 22V12h6v10"/></svg>
                Historique des hospitalisations
            </h2>
            <span class="badge badge-warning">{{ $patient->hospitalisations->count() }}</span>
        </div>
        <div class="card-body no-pad">
            <div class="table-wrap">
                <table class="table-patients">
                    <thead>
                        <tr><th>Admission</th><th>Sortie</th><th>Chambre</th><th>Medecin</th><th>Motif</th><th>Statut</th></tr>
                    </thead>
                    <tbody>
                        @forelse($patient->hospitalisations as $h)
                        <tr>
                            <td style="white-space:nowrap;font-weight:600;">{{ $h->date_admission->format('d/m/Y') }}</td>
                            <td style="white-space:nowrap;">{{ $h->date_sortie ? $h->date_sortie->format('d/m/Y') : '—' }}</td>
                            <td>{{ $h->chambre->numero ?? '—' }}</td>
                            <td>{{ $h->medecin ? 'Dr. ' . $h->medecin->prenom . ' ' . $h->medecin->nom : '—' }}</td>
                            <td class="truncate" style="max-width:200px;">{{ $h->motif ?? '—' }}</td>
                            <td>
                                @if($h->statut == 'en_cours')
                                <span class="badge badge-info">En cours</span>
                                @elseif($h->statut == 'termine')
                                <span class="badge badge-success">Termine</span>
                                @else
                                <span class="badge badge-secondary">{{ ucfirst($h->statut) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" style="text-align:center;padding:40px;" class="text-muted">Aucune hospitalisation</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Tab 8: Documents -->
<div id="tabDocuments" class="carnet-tab hidden">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                Documents
            </h2>
            <span class="badge badge-primary">{{ $patient->documents->count() }}</span>
        </div>
        <div class="card-body no-pad">
            <div class="table-wrap">
                <table class="table-patients">
                    <thead><tr><th>Document</th><th>Type</th><th>Date</th><th>Notes</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($patient->documents as $doc)
                        <tr>
                            <td style="font-weight:500;">{{ $doc->nom }}</td>
                            <td><span class="badge badge-info">{{ str_replace('_', ' ', ucfirst($doc->type)) }}</span></td>
                            <td style="white-space:nowrap;">{{ $doc->created_at->format('d/m/Y') }}</td>
                            <td class="truncate" style="max-width:200px;">{{ $doc->notes ?? '—' }}</td>
                            <td>
                                <a href="{{ asset('storage/' . $doc->fichier) }}" target="_blank" class="btn btn-primary btn-sm">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Voir
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center;padding:40px;" class="text-muted">Aucun document</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajouter Vaccination -->
<div class="modal-overlay" id="modalVaccination">
    <div class="modal" style="max-width:600px;">
        <div class="modal-header">
            <h3 class="modal-title">Ajouter une vaccination</h3>
            <button class="modal-close" onclick="closeModal('modalVaccination')">&times;</button>
        </div>
        <form action="{{ route('reception.patients.vaccinations.store', $patient) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Vaccin *</label>
                        <select class="form-control" name="vaccin" required onchange="if(this.value==='autre'){document.getElementById('vaccinAutre').classList.remove('hidden')}else{document.getElementById('vaccinAutre').classList.add('hidden')}">
                            <option value="">Selectionner</option>
                            <option value="BCG">BCG</option>
                            <option value="DTC">DTC (Diphterie-Tetanos-Coqueluche)</option>
                            <option value="VPO">VPO (Polio oral)</option>
                            <option value="VPI">VPI (Polio injectable)</option>
                            <option value="ROR">ROR (Rougeole-Oreillons-Rubeole)</option>
                            <option value="Hepatite B">Hepatite B</option>
                            <option value="Hepatite A">Hepatite A</option>
                            <option value="Pneumocoque">Pneumocoque</option>
                            <option value="Meningocoque">Meningocoque</option>
                            <option value="Grippe">Grippe</option>
                            <option value="COVID-19">COVID-19</option>
                            <option value="Fievre jaune">Fievre jaune</option>
                            <option value="HPV">HPV</option>
                            <option value="Varicelle">Varicelle</option>
                            <option value="Tetanos">Tetanos</option>
                            <option value="autre">Autre...</option>
                        </select>
                        <input type="text" id="vaccinAutre" class="form-control hidden" name="vaccin_autre" placeholder="Nom du vaccin" style="margin-top:8px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Maladie ciblee *</label>
                        <input type="text" class="form-control" name="maladie" required placeholder="Ex: Tuberculose, Rougeole...">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Date d'administration *</label>
                        <input type="date" class="form-control" name="date_administration" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Dose</label>
                        <select class="form-control" name="dose">
                            <option value="">Selectionner</option>
                            <option value="1ere dose">1ere dose</option>
                            <option value="2eme dose">2eme dose</option>
                            <option value="3eme dose">3eme dose</option>
                            <option value="Rappel">Rappel</option>
                            <option value="Dose unique">Dose unique</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Numero de lot</label>
                        <input type="text" class="form-control" name="lot" placeholder="Ex: AB1234">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Site d'injection</label>
                        <select class="form-control" name="site_injection">
                            <option value="">Selectionner</option>
                            <option value="Bras gauche">Bras gauche</option>
                            <option value="Bras droit">Bras droit</option>
                            <option value="Cuisse gauche">Cuisse gauche</option>
                            <option value="Cuisse droite">Cuisse droite</option>
                            <option value="Fesse">Fesse</option>
                            <option value="Oral">Oral</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Prochain rappel</label>
                    <input type="date" class="form-control" name="prochain_rappel">
                </div>
                <div class="form-group">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="notes" rows="2" placeholder="Notes optionnelles..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalVaccination')">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showCarnetTab(tab) {
    const tabs = {
        'general': 'tabGeneral',
        'vitaux': 'tabVitaux',
        'consultations': 'tabConsultations',
        'vaccinations': 'tabVaccinations',
        'labo': 'tabLabo',
        'ordonnances': 'tabOrdonnances',
        'hospitalisations': 'tabHospitalisations',
        'documents': 'tabDocuments',
    };

    document.querySelectorAll('.carnet-tab').forEach(t => t.classList.add('hidden'));
    document.getElementById(tabs[tab]).classList.remove('hidden');

    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelector(`[onclick="showCarnetTab('${tab}')"]`).classList.add('active');
}

// Handle "autre" vaccin selection
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#modalVaccination form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const select = form.querySelector('select[name="vaccin"]');
            const autreInput = form.querySelector('input[name="vaccin_autre"]');
            if (select.value === 'autre' && autreInput.value) {
                select.name = '_vaccin_ignore';
                autreInput.name = 'vaccin';
            }
        });
    }
});
</script>
@endpush
