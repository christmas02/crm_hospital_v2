@extends('layouts.medicare')

@section('title', 'Dossiers médicaux - MediCare Pro')
@section('sidebar-subtitle', 'Espace Médecin')
@section('user-color', '#7c3aed')
@section('header-title', 'Dossiers médicaux')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('medecin._sidebar')
@endif
@endsection

@section('content')

<div class="toolbar">
    <form method="GET" action="{{ route('medecin.dossiers') }}" style="display:flex;gap:12px;align-items:center;">
        <select class="form-control" name="patient_id" onchange="this.form.submit()" style="max-width:400px;">
            <option value="">-- Sélectionner un patient --</option>
            @foreach($patients as $p)
            <option value="{{ $p->id }}" {{ request('patient_id') == $p->id ? 'selected' : '' }}>
                {{ $p->prenom }} {{ $p->nom }}
            </option>
            @endforeach
        </select>
    </form>
</div>

@if($patient)
<!-- Fiche patient -->
<div class="card mb-4">
    <div class="card-body">
        <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
            <div class="avatar" style="width:64px;height:64px;font-size:1.5rem;background:var(--primary);color:#fff;flex-shrink:0;">
                {{ strtoupper(substr($patient->prenom, 0, 1) . substr($patient->nom, 0, 1)) }}
            </div>
            <div style="flex:1;">
                <div style="font-size:1.5rem;font-weight:700;">{{ $patient->prenom }} {{ $patient->nom }}</div>
                <div class="text-muted">
                    {{ $patient->date_naissance ? \Carbon\Carbon::parse($patient->date_naissance)->age . ' ans' : '' }}
                    · {{ $patient->sexe == 'M' ? 'Masculin' : 'Féminin' }}
                    @if($patient->groupe_sanguin) · Groupe <strong>{{ $patient->groupe_sanguin }}</strong> @endif
                </div>
            </div>
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;text-align:center;">
                <div style="padding:12px 20px;background:var(--primary-light);border-radius:10px;">
                    <div style="font-size:1.5rem;font-weight:700;color:var(--primary);">{{ $patient->consultations->count() }}</div>
                    <div class="text-muted text-sm">Consultations</div>
                </div>
                <div style="padding:12px 20px;background:var(--success-light);border-radius:10px;">
                    <div style="font-size:1.5rem;font-weight:700;color:var(--success);">{{ $patient->hospitalisations->count() ?? 0 }}</div>
                    <div class="text-muted text-sm">Hospitalisations</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
    <!-- Antécédents & Allergies -->
    <div class="card">
        <div class="card-header"><h2 class="card-title"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>Antécédents & Allergies</h2></div>
        <div class="card-body">
            @if($dossier)
                @if(!empty($dossier->antecedents))
                <div class="mb-4">
                    <div class="text-muted text-sm mb-2">Antécédents médicaux</div>
                    <div style="display:flex;flex-wrap:wrap;gap:6px;">
                        @foreach($dossier->antecedents as $item)
                        <span style="font-size:0.8rem;background:var(--warning-light);color:#92400e;padding:3px 10px;border-radius:20px;">{{ $item }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(!empty($dossier->maladies_chroniques))
                <div class="mb-4">
                    <div class="text-muted text-sm mb-2">Maladies chroniques</div>
                    <div style="display:flex;flex-wrap:wrap;gap:6px;">
                        @foreach($dossier->maladies_chroniques as $item)
                        <span style="font-size:0.8rem;background:var(--primary-light);color:var(--primary);padding:3px 10px;border-radius:20px;">{{ $item }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(!empty($dossier->chirurgies))
                <div class="mb-4">
                    <div class="text-muted text-sm mb-2">Chirurgies</div>
                    <div style="display:flex;flex-wrap:wrap;gap:6px;">
                        @foreach($dossier->chirurgies as $item)
                        <span style="font-size:0.8rem;background:var(--gray-100);color:var(--gray-700);padding:3px 10px;border-radius:20px;">{{ $item }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($dossier->notes)
                <div class="mb-4">
                    <div class="text-muted text-sm mb-2">Notes</div>
                    <p class="text-sm">{{ $dossier->notes }}</p>
                </div>
                @endif

                @if(!empty($patient->allergies))
                <div>
                    <div class="text-muted text-sm mb-2">Allergies connues</div>
                    <div style="display:flex;flex-wrap:wrap;gap:6px;">
                        @foreach($patient->allergies as $allergie)
                        <span class="badge badge-danger">{{ $allergie }}</span>
                        @endforeach
                    </div>
                </div>
                @else
                <p class="text-muted text-sm">Aucune allergie connue</p>
                @endif

                @if(!$dossier->antecedents && !$dossier->maladies_chroniques && !$dossier->chirurgies && !$dossier->notes && empty($patient->allergies))
                <p class="text-muted text-center text-sm" style="padding:16px 0;">Aucun antécédent renseigné</p>
                @endif
            @else
            <p class="text-muted text-center" style="padding:20px 0;">Dossier médical non créé</p>
            @endif
        </div>
    </div>

    <!-- Informations de contact -->
    <div class="card">
        <div class="card-header"><h2 class="card-title"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Informations</h2></div>
        <div class="card-body">
            <div style="display:grid;gap:12px;">
                <div>
                    <div class="text-muted text-sm">Téléphone</div>
                    <div>{{ $patient->telephone ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-muted text-sm">Email</div>
                    <div>{{ $patient->email ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-muted text-sm">Adresse</div>
                    <div>{{ $patient->adresse ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-muted text-sm">Contact d'urgence</div>
                    <div>{{ $patient->contact_urgence ?? '-' }}</div>
                </div>
                @if($dossier && $dossier->medecin_traitant)
                <div>
                    <div class="text-muted text-sm">Médecin traitant</div>
                    <div>{{ $dossier->medecin_traitant }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Historique consultations -->
    <div class="card" style="grid-column:1/-1;">
        <div class="card-header"><h2 class="card-title"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>Historique des consultations</h2></div>
        <div class="card-body no-pad">
            <div class="table-wrap">
                <table class="table-patients">
                    <thead>
                        <tr><th>Date</th><th>Médecin</th><th>Motif</th><th>Diagnostic</th><th>Statut</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($patient->consultations as $consult)
                        <tr>
                            <td>
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                {{ \Carbon\Carbon::parse($consult->date)->format('d/m/Y') }}
                            </td>
                            <td>
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                Dr. {{ $consult->medecin->prenom ?? '' }} {{ $consult->medecin->nom ?? '-' }}
                            </td>
                            <td class="truncate" style="max-width:160px;">{{ $consult->motif }}</td>
                            <td class="truncate" style="max-width:200px;">{{ $consult->ficheTraitement->diagnostic ?? '-' }}</td>
                            <td>
                                @php $statusMap = ['en_attente'=>['warning','En attente'],'en_cours'=>['info','En cours'],'termine'=>['success','Terminée']]; $s = $statusMap[$consult->statut] ?? ['secondary',$consult->statut]; @endphp
                                <span class="badge badge-{{ $s[0] }}">{{ $s[1] }}</span>
                            </td>
                            <td>
                                @if($consult->statut !== 'en_attente')
                                <a href="{{ route('medecin.consultations.show', $consult) }}" class="btn btn-primary btn-sm"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> Voir</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" style="text-align:center;padding:32px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:8px;"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                            <div class="text-muted" style="font-size:.875rem;">Aucune consultation</div>
                            <div class="text-muted" style="font-size:.75rem;margin-top:4px;">L'historique des consultations apparaitra ici</div>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Ordonnances récentes -->
    <div class="card">
        <div class="card-header"><h2 class="card-title"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>Ordonnances récentes</h2></div>
        <div class="card-body no-pad">
            <div class="table-wrap">
                <table class="table-patients">
                    <thead><tr><th>Date</th><th>N°</th><th>Statut</th></tr></thead>
                    <tbody>
                        @forelse($patient->ordonnances()->with('medicaments')->latest('date')->take(5)->get() as $ord)
                        <tr>
                            <td>
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                {{ $ord->date->format('d/m/Y') }}
                            </td>
                            <td><code>{{ $ord->numero_retrait }}</code></td>
                            <td>
                                @php $sOrd = ['en_attente'=>['warning','En attente'],'prepare'=>['info','Préparée'],'remis'=>['success','Remise']]; $so = $sOrd[$ord->statut_dispensation] ?? ['secondary',$ord->statut_dispensation]; @endphp
                                <span class="badge badge-{{ $so[0] }}">{{ $so[1] }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" style="text-align:center;padding:32px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:8px;"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>
                            <div class="text-muted" style="font-size:.875rem;">Aucune ordonnance</div>
                            <div class="text-muted" style="font-size:.75rem;margin-top:4px;">Pas d'ordonnances pour ce patient</div>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Hospitalisations -->
    <div class="card">
        <div class="card-header"><h2 class="card-title"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>Hospitalisations</h2></div>
        <div class="card-body no-pad">
            <div class="table-wrap">
                <table class="table-patients">
                    <thead><tr><th>Entrée</th><th>Sortie</th><th>Chambre</th><th>Motif</th></tr></thead>
                    <tbody>
                        @forelse($patient->hospitalisations()->with('chambre')->latest('date_admission')->take(5)->get() as $hosp)
                        <tr>
                            <td>
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                {{ \Carbon\Carbon::parse($hosp->date_admission)->format('d/m/Y') }}
                            </td>
                            <td>
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                {{ $hosp->date_sortie ? \Carbon\Carbon::parse($hosp->date_sortie)->format('d/m/Y') : '<span class="badge badge-warning">En cours</span>' }}
                            </td>
                            <td>{{ $hosp->chambre->numero ?? '-' }}</td>
                            <td class="truncate" style="max-width:120px;">{{ $hosp->motif ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center;padding:32px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:8px;"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            <div class="text-muted" style="font-size:.875rem;">Aucune hospitalisation</div>
                            <div class="text-muted" style="font-size:.75rem;margin-top:4px;">Pas d'hospitalisations enregistrées</div>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@else
<div class="card">
    <div class="card-body text-center" style="padding:80px;">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5" style="margin:0 auto 16px;display:block;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
        <h3 style="color:var(--gray-600);margin-bottom:8px;">Sélectionnez un patient</h3>
        <p class="text-muted">Choisissez un patient dans la liste ci-dessus pour afficher son dossier médical</p>
    </div>
</div>
@endif

@endsection
