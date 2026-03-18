@extends('layouts.medicare')

@section('title', 'Patient - MediCare Pro')
@section('sidebar-subtitle', 'Réception')
@section('user-color', '#059669')
@section('header-title', 'Dossier Patient')

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

<!-- Hero Patient Banner -->
<div style="background:linear-gradient(135deg, var(--gray-800), var(--gray-700));border-radius:18px;padding:28px 32px;margin-bottom:24px;position:relative;overflow:hidden;">
    <!-- Pattern background -->
    <div style="position:absolute;inset:0;opacity:.04;background-image:url(\"data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Crect x='16' y='4' width='8' height='32' rx='2' fill='%23fff'/%3E%3Crect x='4' y='16' width='32' height='8' rx='2' fill='%23fff'/%3E%3C/svg%3E\");background-size:40px 40px;pointer-events:none;"></div>
    <div style="position:absolute;top:-30px;right:-30px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,.04);pointer-events:none;"></div>

    <div style="display:flex;align-items:center;gap:24px;position:relative;z-index:1;">
        <!-- Avatar -->
        <div style="width:88px;height:88px;border-radius:22px;background:{{ $avatarBg }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.8rem;font-weight:800;box-shadow:0 8px 24px rgba(0,0,0,.2);flex-shrink:0;">
            {{ strtoupper(substr($patient->prenom, 0, 1) . substr($patient->nom, 0, 1)) }}
        </div>

        <!-- Info -->
        <div style="flex:1;">
            <h2 style="font-size:1.6rem;font-weight:800;color:#fff;margin-bottom:6px;letter-spacing:-.02em;">{{ $patient->prenom }} {{ $patient->nom }}</h2>
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <span style="color:rgba(255,255,255,.7);font-size:.9rem;">{{ $age }} ans</span>
                <span style="width:4px;height:4px;border-radius:50%;background:rgba(255,255,255,.4);"></span>
                <span class="badge {{ $isMale ? 'badge-info' : 'badge-pink' }}" style="font-size:.72rem;">{{ $isMale ? 'Homme' : 'Femme' }}</span>
                @if($patient->groupe_sanguin)
                <span style="padding:3px 10px;border-radius:8px;background:rgba(255,255,255,.15);color:#fff;font-size:.75rem;font-weight:600;">{{ $patient->groupe_sanguin }}</span>
                @endif
                @if($patient->allergies && count(is_array($patient->allergies) ? $patient->allergies : explode(',', $patient->allergies)) > 0)
                <span style="padding:3px 10px;border-radius:8px;background:rgba(220,38,38,.3);color:#fca5a5;font-size:.72rem;font-weight:600;display:flex;align-items:center;gap:4px;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    Allergies
                </span>
                @endif
            </div>
        </div>

        <!-- Status + Actions -->
        <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
            @if($patient->statut == 'hospitalise')
            <span style="padding:6px 16px;border-radius:10px;background:rgba(8,145,178,.2);color:#67e8f9;font-size:.8rem;font-weight:600;border:1px solid rgba(8,145,178,.3);">Hospitalisé</span>
            @else
            <span style="padding:6px 16px;border-radius:10px;background:rgba(34,197,94,.2);color:#86efac;font-size:.8rem;font-weight:600;border:1px solid rgba(34,197,94,.3);">Actif</span>
            @endif
            <button onclick="openModal('modalEditPatient')" class="btn btn-sm" style="background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.2);border-radius:10px;padding:8px 16px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Modifier
            </button>
        </div>
    </div>
</div>

<!-- Info Grid: 4 cards inline -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
    <div class="card" style="padding:18px;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:40px;height:40px;border-radius:12px;background:var(--primary-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
            </div>
            <div>
                <div style="font-size:.68rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;letter-spacing:.3px;">Téléphone</div>
                <div style="font-size:.9rem;font-weight:600;color:var(--gray-800);">{{ $patient->telephone ?? '—' }}</div>
            </div>
        </div>
    </div>
    <div class="card" style="padding:18px;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:40px;height:40px;border-radius:12px;background:#ede9fe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="M22 6l-10 7L2 6"/></svg>
            </div>
            <div>
                <div style="font-size:.68rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;letter-spacing:.3px;">Email</div>
                <div style="font-size:.85rem;font-weight:600;color:var(--gray-800);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;">{{ $patient->email ?? '—' }}</div>
            </div>
        </div>
    </div>
    <div class="card" style="padding:18px;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:40px;height:40px;border-radius:12px;background:var(--warning-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--warning)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            </div>
            <div>
                <div style="font-size:.68rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;letter-spacing:.3px;">Naissance</div>
                <div style="font-size:.9rem;font-weight:600;color:var(--gray-800);">{{ \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') }}</div>
            </div>
        </div>
    </div>
    <div class="card" style="padding:18px;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:40px;height:40px;border-radius:12px;background:var(--success-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div>
                <div style="font-size:.68rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;letter-spacing:.3px;">Adresse</div>
                <div style="font-size:.85rem;font-weight:600;color:var(--gray-800);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;">{{ $patient->adresse ?? '—' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="grid-2" style="margin-bottom:24px;">
    <!-- Allergies + Infos complémentaires -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
                Informations médicales
            </h2>
        </div>
        <div class="card-body">
            <div style="margin-bottom:16px;">
                <div style="font-size:.72rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;letter-spacing:.3px;margin-bottom:8px;">Allergies connues</div>
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
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;padding-top:16px;border-top:1px solid var(--gray-200);">
                <div style="background:var(--gray-50);padding:12px;border-radius:10px;text-align:center;">
                    <div style="font-size:1.3rem;font-weight:800;color:var(--primary);">{{ $patient->consultations->count() }}</div>
                    <div style="font-size:.72rem;color:var(--gray-500);font-weight:600;">Consultations</div>
                </div>
                <div style="background:var(--gray-50);padding:12px;border-radius:10px;text-align:center;">
                    <div style="font-size:1.3rem;font-weight:800;color:var(--secondary);">{{ $patient->date_inscription->format('d/m/Y') }}</div>
                    <div style="font-size:.72rem;color:var(--gray-500);font-weight:600;">Inscrit le</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--warning)" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                Actions rapides
            </h2>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <button class="action-card action-card-primary" onclick="openModal('modalConsult')" style="padding:16px;">
                    <div class="action-card-icon" style="width:36px;height:36px;border-radius:10px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    </div>
                    <div>
                        <div class="action-card-label">Consultation</div>
                        <div class="action-card-sub">Nouveau RDV</div>
                    </div>
                </button>
                <button onclick="openModal('modalEditPatient')" class="action-card" style="padding:16px;background:var(--gray-100);color:var(--gray-700);border:none;border-radius:12px;display:flex;align-items:center;gap:12px;cursor:pointer;transition:all .2s;text-align:left;" onmouseover="this.style.background='var(--gray-200)'" onmouseout="this.style.background='var(--gray-100)'">
                    <div style="width:36px;height:36px;border-radius:10px;background:var(--gray-200);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </div>
                    <div>
                        <div style="font-weight:600;font-size:.85rem;">Modifier</div>
                        <div style="font-size:.72rem;opacity:.7;">Le dossier</div>
                    </div>
                </button>
                <a href="{{ route('caisse.releve', $patient) }}" class="action-card" style="padding:16px;background:var(--gray-100);color:var(--gray-700);border:none;text-decoration:none;border-radius:12px;display:flex;align-items:center;gap:12px;transition:all .2s;" onmouseover="this.style.background='var(--gray-200)'" onmouseout="this.style.background='var(--gray-100)'">
                    <div style="width:36px;height:36px;border-radius:10px;background:var(--gray-200);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                    </div>
                    <div>
                        <div style="font-weight:600;font-size:.85rem;">Relevé</div>
                        <div style="font-size:.72rem;opacity:.7;">De compte</div>
                    </div>
                </a>
                <a href="{{ route('reception.patients.carnet', $patient) }}" class="action-card" style="padding:16px;background:var(--success-light);color:var(--success);border:none;text-decoration:none;border-radius:12px;display:flex;align-items:center;gap:12px;transition:all .2s;">
                    <div style="width:36px;height:36px;border-radius:10px;background:var(--success);color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>
                    </div>
                    <div>
                        <div style="font-weight:600;font-size:.85rem;">Carnet de sante</div>
                        <div style="font-size:.72rem;opacity:.7;">Dossier complet</div>
                    </div>
                </a>
                <button onclick="window.print()" class="action-card" style="padding:16px;background:var(--gray-100);color:var(--gray-700);border:none;border-radius:12px;display:flex;align-items:center;gap:12px;cursor:pointer;transition:all .2s;" onmouseover="this.style.background='var(--gray-200)'" onmouseout="this.style.background='var(--gray-100)'">
                    <div style="width:36px;height:36px;border-radius:10px;background:var(--gray-200);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    </div>
                    <div>
                        <div style="font-weight:600;font-size:.85rem;">Imprimer</div>
                        <div style="font-size:.72rem;opacity:.7;">Le dossier</div>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Signes vitaux -->
@php $derniersSignes = $patient->signesVitaux()->orderBy('created_at', 'desc')->limit(3)->get(); @endphp
@if($derniersSignes->count() > 0)
<div class="card mb-4">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            Signes vitaux
        </h2>
        <span class="badge badge-success">{{ $derniersSignes->count() }} derniers</span>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients" style="font-size:.85rem;">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Temp</th>
                        <th>Tension</th>
                        <th>Pouls</th>
                        <th>Sat O2</th>
                        <th>Poids</th>
                        <th>IMC</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($derniersSignes as $sv)
                    <tr>
                        <td style="white-space:nowrap;">
                            <div style="font-weight:600;">{{ $sv->created_at->format('d/m/Y') }}</div>
                            <div style="font-size:.72rem;color:var(--gray-400);">{{ $sv->created_at->format('H:i') }}</div>
                        </td>
                        <td>
                            @if($sv->temperature)
                            <span style="{{ $sv->temperature > 38 ? 'color:#dc2626;font-weight:700;' : '' }}">{{ $sv->temperature }}°C</span>
                            @else — @endif
                        </td>
                        <td>
                            @if($sv->tension_systolique || $sv->tension_diastolique)
                            {{ $sv->tension_systolique ?? '-' }}/{{ $sv->tension_diastolique ?? '-' }}
                            @else — @endif
                        </td>
                        <td>
                            @if($sv->pouls)
                            <span style="{{ $sv->pouls > 100 ? 'color:#ea580c;font-weight:700;' : '' }}">{{ $sv->pouls }}</span>
                            @else — @endif
                        </td>
                        <td>
                            @if($sv->saturation_o2)
                            <span style="{{ $sv->saturation_o2 < 95 ? 'color:#dc2626;font-weight:700;' : '' }}">{{ $sv->saturation_o2 }}%</span>
                            @else — @endif
                        </td>
                        <td>{{ $sv->poids ? $sv->poids . ' kg' : '—' }}</td>
                        <td>{{ $sv->imc ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Vaccinations -->
@php $dernieresVaccinations = $patient->vaccinations()->orderBy('date_administration', 'desc')->limit(3)->get(); @endphp
<div class="card mb-4">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>
            Vaccinations
        </h2>
        <span class="badge badge-info">{{ $dernieresVaccinations->count() }}</span>
    </div>
    <div class="card-body no-pad">
        @php
            $overdueVaccinations = $dernieresVaccinations->filter(fn($v) => $v->prochain_rappel && $v->prochain_rappel->isPast());
        @endphp
        @if($overdueVaccinations->count() > 0)
        <div style="padding:12px 16px;background:#fef2f2;border-bottom:1px solid #fecaca;display:flex;align-items:center;gap:8px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
            <span style="color:#dc2626;font-size:.82rem;font-weight:600;">{{ $overdueVaccinations->count() }} rappel(s) en retard</span>
        </div>
        @endif
        <div class="table-wrap">
            <table class="table-patients" style="font-size:.85rem;">
                <thead><tr><th>Vaccin</th><th>Maladie</th><th>Date</th><th>Dose</th><th>Prochain rappel</th></tr></thead>
                <tbody>
                    @forelse($dernieresVaccinations as $vacc)
                    <tr>
                        <td style="font-weight:600;">{{ $vacc->vaccin }}</td>
                        <td>{{ $vacc->maladie }}</td>
                        <td>{{ $vacc->date_administration->format('d/m/Y') }}</td>
                        <td>{{ $vacc->dose ?? '—' }}</td>
                        <td>
                            @if($vacc->prochain_rappel)
                                @if($vacc->prochain_rappel->isPast())
                                <span style="color:#dc2626;font-weight:700;">{{ $vacc->prochain_rappel->format('d/m/Y') }} (en retard)</span>
                                @else
                                {{ $vacc->prochain_rappel->format('d/m/Y') }}
                                @endif
                            @else — @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;padding:24px;" class="text-muted">Aucune vaccination enregistree</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Historique des consultations -->
<div class="card mb-4">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--secondary)" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            Historique des consultations
        </h2>
        <span class="badge badge-secondary">{{ $patient->consultations->count() }}</span>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients">
                <thead>
                    <tr><th>Date</th><th>Médecin</th><th>Motif</th><th>Statut</th><th style="text-align:center;">Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($patient->consultations()->orderBy('date', 'desc')->get() as $consultation)
                    <tr>
                        <td>
                            <div style="font-weight:600;">{{ $consultation->date->format('d/m/Y') }}</div>
                            <div style="font-size:.75rem;color:var(--gray-400);">{{ $consultation->heure }}</div>
                        </td>
                        <td>Dr. {{ $consultation->medecin->prenom }} {{ $consultation->medecin->nom }}</td>
                        <td class="truncate" style="max-width:200px;">{{ $consultation->motif }}</td>
                        <td>
                            @if($consultation->statut == 'termine')
                            <span class="badge badge-success">Terminé</span>
                            @elseif($consultation->statut == 'en_cours')
                            <span class="badge badge-info">En cours</span>
                            @else
                            <span class="badge badge-warning">En attente</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            <a href="{{ route('reception.consultations.show', $consultation) }}" class="btn btn-primary btn-sm">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                Voir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;padding:40px;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:8px;"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                        <div class="text-muted" style="font-size:.875rem;">Aucune consultation</div>
                        <div class="text-muted" style="font-size:.75rem;margin-top:4px;">Les consultations apparaîtront ici</div>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Documents -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
            Documents
        </h2>
        <button class="btn btn-primary btn-sm" onclick="openModal('modalDocument')">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Ajouter
        </button>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients">
                <thead><tr><th>Document</th><th>Type</th><th>Date</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($patient->documents()->orderBy('created_at', 'desc')->get() as $doc)
                    <tr>
                        <td style="font-weight:500;">{{ $doc->nom }}</td>
                        <td><span class="badge badge-info">{{ str_replace('_', ' ', ucfirst($doc->type)) }}</span></td>
                        <td>{{ $doc->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="{{ asset('storage/' . $doc->fichier) }}" target="_blank" class="btn btn-primary btn-sm">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <form action="{{ route('reception.patients.documents.destroy', [$patient, $doc]) }}" method="POST" onsubmit="return confirm('Supprimer ce document ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;padding:24px;" class="text-muted">Aucun document</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Upload Document -->
<div class="modal-overlay" id="modalDocument">
    <div class="modal" style="max-width:500px;">
        <div class="modal-header">
            <h3 class="modal-title">Ajouter un document</h3>
            <button class="modal-close" onclick="closeModal('modalDocument')">&times;</button>
        </div>
        <form action="{{ route('reception.patients.documents.store', $patient) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group"><label class="form-label">Nom du document *</label><input type="text" class="form-control" name="nom" required placeholder="Ex: Résultat prise de sang"></div>
                <div class="form-group">
                    <label class="form-label">Type *</label>
                    <select class="form-control" name="type" required>
                        <option value="">Sélectionner</option>
                        <option value="resultat_labo">Résultat laboratoire</option>
                        <option value="radio">Radiographie / Imagerie</option>
                        <option value="certificat">Certificat médical</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Fichier *</label><input type="file" class="form-control" name="fichier" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"></div>
                <div class="form-group"><label class="form-label">Notes</label><textarea class="form-control" name="notes" rows="2" placeholder="Notes optionnelles..."></textarea></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalDocument')">Annuler</button>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Nouvelle Consultation -->
<div class="modal-overlay" id="modalConsult">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Nouvelle Consultation</h3>
            <button class="modal-close" onclick="closeModal('modalConsult')">&times;</button>
        </div>
        <form action="{{ route('reception.consultations.store') }}" method="POST">
            @csrf
            <input type="hidden" name="patient_id" value="{{ $patient->id }}">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Patient</label>
                    <input type="text" class="form-control" value="{{ $patient->prenom }} {{ $patient->nom }}" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Médecin *</label>
                    <select class="form-control" name="medecin_id" required>
                        <option value="">Sélectionner</option>
                        @foreach($medecinsDisponibles as $medecin)
                        <option value="{{ $medecin->id }}">Dr. {{ $medecin->prenom }} {{ $medecin->nom }} - {{ $medecin->specialite }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Date *</label><input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" required></div>
                    <div class="form-group"><label class="form-label">Heure *</label><input type="time" class="form-control" name="heure" required></div>
                </div>
                <div class="form-group"><label class="form-label">Motif *</label><textarea class="form-control" name="motif" required placeholder="Motif de la consultation"></textarea></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalConsult')">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Modifier Patient -->
<div class="modal-overlay" id="modalEditPatient">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 class="modal-title">Modifier — {{ $patient->prenom }} {{ $patient->nom }}</h3>
            <button class="modal-close" onclick="closeModal('modalEditPatient')">&times;</button>
        </div>
        <form action="{{ route('reception.patients.update', $patient) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Nom *</label><input type="text" class="form-control" name="nom" value="{{ $patient->nom }}" required></div>
                    <div class="form-group"><label class="form-label">Prénom *</label><input type="text" class="form-control" name="prenom" value="{{ $patient->prenom }}" required></div>
                </div>
                <div class="form-row-3">
                    <div class="form-group"><label class="form-label">Date naissance *</label><input type="date" class="form-control" name="date_naissance" value="{{ $patient->date_naissance->format('Y-m-d') }}" required></div>
                    <div class="form-group">
                        <label class="form-label">Sexe *</label>
                        <select class="form-control" name="sexe" required>
                            <option value="M" {{ $patient->sexe == 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ $patient->sexe == 'F' ? 'selected' : '' }}>Féminin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Groupe sanguin</label>
                        <select class="form-control" name="groupe_sanguin">
                            <option value="">-</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $gs)
                            <option value="{{ $gs }}" {{ $patient->groupe_sanguin == $gs ? 'selected' : '' }}>{{ $gs }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Téléphone</label><input type="tel" class="form-control" name="telephone" value="{{ $patient->telephone }}"></div>
                    <div class="form-group"><label class="form-label">Email</label><input type="email" class="form-control" name="email" value="{{ $patient->email }}"></div>
                </div>
                <div class="form-group"><label class="form-label">Adresse</label><input type="text" class="form-control" name="adresse" value="{{ $patient->adresse }}"></div>
                <div class="form-group"><label class="form-label">Allergies</label><input type="text" class="form-control" name="allergies" value="{{ is_array($patient->allergies) ? implode(', ', $patient->allergies) : $patient->allergies }}" placeholder="Séparer par virgules"></div>
                <div class="form-group">
                    <label class="form-label">Statut</label>
                    <select class="form-control" name="statut">
                        <option value="actif" {{ $patient->statut == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="hospitalise" {{ $patient->statut == 'hospitalise' ? 'selected' : '' }}>Hospitalisé</option>
                        <option value="inactif" {{ $patient->statut == 'inactif' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditPatient')">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</div>
@endsection
