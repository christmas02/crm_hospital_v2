@extends('layouts.medicare')

@section('title', 'Espace Médecin - MediCare Pro')
@section('sidebar-subtitle', 'Espace Médecin')
@section('user-color', '#7c3aed')
@section('header-title', 'Mon Tableau de bord')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('medecin._sidebar')
@endif
@endsection

@section('content')
<!-- Stats -->
<div class="stats" style="grid-template-columns: repeat(4, 1fr);">
    <div class="stat-card" style="border-left: 4px solid var(--warning);">
        <div>
            <div class="stat-label">Patients en attente</div>
            <div class="stat-value">{{ $stats['en_attente'] }}</div>
            <div class="stat-sub">File d'attente actuelle</div>
        </div>
        <div class="stat-icon orange">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--primary);">
        <div>
            <div class="stat-label">En cours</div>
            <div class="stat-value">{{ $stats['en_cours'] }}</div>
            <div class="stat-sub">Consultation active</div>
        </div>
        <div class="stat-icon cyan">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--secondary);">
        <div>
            <div class="stat-label">Terminées aujourd'hui</div>
            <div class="stat-value">{{ $stats['terminees_jour'] }}</div>
            <div class="stat-sub">Consultations du jour</div>
        </div>
        <div class="stat-icon green">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--accent);">
        <div>
            <div class="stat-label">Total semaine</div>
            <div class="stat-value">{{ $stats['total_semaine'] }}</div>
            <div class="stat-sub">Bilan hebdomadaire</div>
        </div>
        <div class="stat-icon purple">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        </div>
    </div>
</div>

<div class="grid-2">
    <!-- File d'attente -->
    <div class="card">
        <div class="card-header"><h2 class="card-title"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>Patients en attente</h2></div>
        <div class="card-body no-pad">
            <div class="table-wrap">
                <table class="table-patients">
                    <thead><tr><th>Heure</th><th>Patient</th><th>Motif</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($consultationsEnAttente as $consultation)
                        <tr>
                            <td>
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                {{ $consultation->heure }}
                            </td>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar">{{ strtoupper(substr($consultation->patient->prenom, 0, 1) . substr($consultation->patient->nom, 0, 1)) }}</div>
                                    <span>{{ $consultation->patient->prenom }} {{ $consultation->patient->nom }}</span>
                                </div>
                            </td>
                            <td class="truncate" style="max-width:150px;">{{ $consultation->motif }}</td>
                            <td>
                                <form action="{{ route('medecin.consultations.start', $consultation) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">Appeler</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center;padding:32px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:8px;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            <div class="text-muted" style="font-size:.875rem;">Aucun patient en attente</div>
                            <div class="text-muted" style="font-size:.75rem;margin-top:4px;">Les patients apparaitront ici une fois enregistrés</div>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Consultation en cours -->
    <div class="card">
        <div class="card-header"><h2 class="card-title"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>Consultation en cours</h2></div>
        <div class="card-body">
            @if($consultationEnCours)
            <div style="background:var(--primary-light);padding:20px;border-radius:12px;border:2px solid var(--primary);">
                <div class="user-cell mb-4">
                    <div class="avatar lg" style="background:var(--primary);color:#fff;">{{ strtoupper(substr($consultationEnCours->patient->prenom, 0, 1) . substr($consultationEnCours->patient->nom, 0, 1)) }}</div>
                    <div>
                        <div class="user-name" style="font-size:1.25rem;">{{ $consultationEnCours->patient->prenom }} {{ $consultationEnCours->patient->nom }}</div>
                        <div class="text-muted">{{ \Carbon\Carbon::parse($consultationEnCours->patient->date_naissance)->age }} ans - {{ $consultationEnCours->patient->sexe == 'M' ? 'Masculin' : 'Féminin' }}</div>
                    </div>
                </div>
                <div style="background:#fff;padding:12px;border-radius:8px;margin-bottom:16px;">
                    <div class="text-xs text-muted">Motif</div>
                    <div>{{ $consultationEnCours->motif }}</div>
                </div>
                <a href="{{ route('medecin.consultations.show', $consultationEnCours) }}" class="btn btn-primary" style="width:100%;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    Continuer la consultation
                </a>
            </div>
            @else
            <div class="text-center" style="padding:40px;">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5" style="margin:0 auto 16px;"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                <h3 style="color:var(--gray-600);">Aucune consultation en cours</h3>
                <p class="text-muted">Appelez un patient depuis la file d'attente</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Références reçues -->
@if(isset($referencesRecues) && $referencesRecues->count() > 0)
<div class="card mt-4">
    <div class="card-header" style="background:linear-gradient(135deg, #dbeafe, #bfdbfe);">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
            Références reçues
        </h2>
        <span class="badge badge-primary">{{ $referencesRecues->count() }}</span>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Médecin référent</th>
                        <th>Motif</th>
                        <th>Urgence</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($referencesRecues as $ref)
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="avatar">{{ strtoupper(substr($ref->patient->prenom, 0, 1) . substr($ref->patient->nom, 0, 1)) }}</div>
                                <span>{{ $ref->patient->prenom }} {{ $ref->patient->nom }}</span>
                            </div>
                        </td>
                        <td>Dr. {{ $ref->medecinReferent->prenom }} {{ $ref->medecinReferent->nom }}</td>
                        <td class="truncate" style="max-width:200px;">{{ $ref->motif }}</td>
                        <td>
                            @if($ref->urgence === 'tres_urgent')
                            <span class="badge badge-danger">Très urgent</span>
                            @elseif($ref->urgence === 'urgent')
                            <span class="badge" style="background:#fef3c7;color:#92400e;">Urgent</span>
                            @else
                            <span class="badge badge-secondary">Normal</span>
                            @endif
                        </td>
                        <td style="white-space:nowrap;">{{ $ref->date_reference->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Consultations terminées -->
<div class="card mt-4">
    <div class="card-header"><h2 class="card-title"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>Consultations terminées aujourd'hui</h2></div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients">
                <thead><tr><th>Heure</th><th>Patient</th><th>Diagnostic</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($consultationsTerminees as $consultation)
                    <tr>
                        <td>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            {{ $consultation->heure }}
                        </td>
                        <td>
                            <div class="user-cell">
                                <div class="avatar">{{ strtoupper(substr($consultation->patient->prenom, 0, 1) . substr($consultation->patient->nom, 0, 1)) }}</div>
                                <span>{{ $consultation->patient->prenom }} {{ $consultation->patient->nom }}</span>
                            </div>
                        </td>
                        <td class="truncate" style="max-width:200px;">{{ $consultation->ficheTraitement->diagnostic ?? '-' }}</td>
                        <td><a href="{{ route('medecin.consultations.show', $consultation) }}" class="btn btn-primary btn-sm"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> Voir</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;padding:32px;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:8px;"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                        <div class="text-muted" style="font-size:.875rem;">Aucune consultation terminée</div>
                        <div class="text-muted" style="font-size:.75rem;margin-top:4px;">Les consultations terminées du jour apparaitront ici</div>
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
