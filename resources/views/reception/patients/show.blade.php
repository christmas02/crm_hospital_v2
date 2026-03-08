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
<!-- En-tête Patient -->
<div class="card mb-4">
    <div class="card-body">
        <div style="display:flex;align-items:center;gap:20px;">
            <div class="avatar lg" style="width:80px;height:80px;font-size:1.5rem;background:var(--primary);color:#fff;">
                {{ strtoupper(substr($patient->prenom, 0, 1) . substr($patient->nom, 0, 1)) }}
            </div>
            <div style="flex:1;">
                <h2 style="font-size:1.5rem;margin-bottom:4px;">{{ $patient->prenom }} {{ $patient->nom }}</h2>
                <div class="text-muted">
                    {{ \Carbon\Carbon::parse($patient->date_naissance)->age }} ans - {{ $patient->sexe == 'M' ? 'Masculin' : 'Féminin' }}
                    @if($patient->groupe_sanguin)
                    <span class="badge badge-light" style="margin-left:8px;">{{ $patient->groupe_sanguin }}</span>
                    @endif
                </div>
            </div>
            <div>
                @if($patient->statut == 'hospitalise')
                <span class="badge badge-info">Hospitalisé</span>
                @else
                <span class="badge badge-success">Actif</span>
                @endif
            </div>
            <div>
                <a href="{{ route('reception.patients.edit', $patient) }}" class="btn btn-outline">Modifier</a>
            </div>
        </div>
    </div>
</div>

<div class="grid-2">
    <!-- Informations personnelles -->
    <div class="card">
        <div class="card-header"><h2 class="card-title">Informations personnelles</h2></div>
        <div class="card-body">
            <div class="info-grid" style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;">
                <div>
                    <div class="text-muted text-sm">Téléphone</div>
                    <div style="font-weight:500;">{{ $patient->telephone ?? 'Non renseigné' }}</div>
                </div>
                <div>
                    <div class="text-muted text-sm">Email</div>
                    <div style="font-weight:500;">{{ $patient->email ?? 'Non renseigné' }}</div>
                </div>
                <div>
                    <div class="text-muted text-sm">Date de naissance</div>
                    <div style="font-weight:500;">{{ \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') }}</div>
                </div>
                <div>
                    <div class="text-muted text-sm">Adresse</div>
                    <div style="font-weight:500;">{{ $patient->adresse ?? 'Non renseigné' }}</div>
                </div>
                <div>
                    <div class="text-muted text-sm">Date d'inscription</div>
                    <div style="font-weight:500;">{{ $patient->date_inscription->format('d/m/Y') }}</div>
                </div>
            </div>
            @if($patient->allergies)
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border);">
                <div class="text-muted text-sm mb-2">Allergies</div>
                <div class="flex flex-wrap gap-2">
                    @foreach((is_array($patient->allergies) ? $patient->allergies : explode(',', $patient->allergies)) as $allergie)
                    <span class="badge badge-danger">{{ trim($allergie) }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="card">
        <div class="card-header"><h2 class="card-title">Actions rapides</h2></div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:12px;">
                <button class="btn btn-primary" onclick="openModal('modalConsult')" style="width:100%;padding:16px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    Nouvelle Consultation
                </button>
                <a href="{{ route('reception.patients.edit', $patient) }}" class="btn btn-outline" style="width:100%;padding:16px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Modifier le dossier
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Historique des consultations -->
<div class="card mt-4">
    <div class="card-header">
        <h2 class="card-title">Historique des consultations</h2>
        <span class="text-muted text-sm">{{ $patient->consultations->count() }} consultations</span>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Date</th><th>Médecin</th><th>Motif</th><th>Statut</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($patient->consultations()->orderBy('date', 'desc')->get() as $consultation)
                    <tr>
                        <td>{{ $consultation->date->format('d/m/Y') }} {{ $consultation->heure }}</td>
                        <td>Dr. {{ $consultation->medecin->nom }}</td>
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
                        <td>
                            <a href="{{ route('reception.consultations.show', $consultation) }}" class="btn btn-outline btn-sm">Voir</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted">Aucune consultation</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
                        @foreach(\App\Models\Medecin::where('statut', '!=', 'absent')->get() as $medecin)
                        <option value="{{ $medecin->id }}">Dr. {{ $medecin->prenom }} {{ $medecin->nom }} - {{ $medecin->specialite }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Date *</label>
                        <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Heure *</label>
                        <input type="time" class="form-control" name="heure" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Motif *</label>
                    <textarea class="form-control" name="motif" required placeholder="Motif de la consultation"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalConsult')">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection
