@extends('layouts.medicare')

@section('title', 'Réception - MediCare Pro')
@section('sidebar-subtitle', 'Réception')
@section('user-color', '#059669')
@section('header-title', 'Accueil - Réception')

@section('header-right')
<span class="text-muted">{{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</span>
@endsection

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('reception._sidebar')
@endif
@endsection

@section('content')
<!-- Stats - Comme dans la source originale -->
<div class="stats" style="grid-template-columns: repeat(4, 1fr);">
    <div class="stat-card">
        <div>
            <div class="stat-label">Patients aujourd'hui</div>
            <div class="stat-value">{{ $stats['patients_aujourdhui'] }}</div>
        </div>
        <div class="stat-icon cyan">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-label">Consultations en attente</div>
            <div class="stat-value">{{ $stats['en_attente'] }}</div>
        </div>
        <div class="stat-icon orange">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-label">Factures envoyées</div>
            <div class="stat-value">{{ $stats['factures_envoyees'] }}</div>
        </div>
        <div class="stat-icon green">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-label">En attente paiement</div>
            <div class="stat-value">{{ $stats['en_attente_paiement'] }}</div>
        </div>
        <div class="stat-icon red">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
        </div>
    </div>
</div>

<div class="grid-2">
    <!-- Actions rapides -->
    <div class="card">
        <div class="card-header"><h2 class="card-title">Actions rapides</h2></div>
        <div class="card-body">
            <div class="grid-2" style="gap:12px;">
                <button class="btn btn-primary" style="padding:20px;" onclick="openModal('modalPatient')">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><path d="M20 8v6M23 11h-6"/></svg>
                    <br>Nouveau Patient
                </button>
                <button class="btn btn-success" style="padding:20px;" onclick="openModal('modalConsult')">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M12 18v-6M9 15h6"/></svg>
                    <br>Nouvelle Consultation
                </button>
            </div>
        </div>
    </div>

    <!-- File d'attente -->
    <div class="card">
        <div class="card-header"><h2 class="card-title">File d'attente</h2></div>
        <div class="card-body no-pad">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Patient</th><th>Heure</th><th>Médecin</th><th>Statut</th></tr>
                    </thead>
                    <tbody>
                        @forelse($consultationsEnAttente as $consultation)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar">{{ strtoupper(substr($consultation->patient->prenom, 0, 1) . substr($consultation->patient->nom, 0, 1)) }}</div>
                                    <span>{{ $consultation->patient->prenom }} {{ $consultation->patient->nom }}</span>
                                </div>
                            </td>
                            <td>{{ $consultation->heure }}</td>
                            <td>Dr. {{ $consultation->medecin->nom }}</td>
                            <td><span class="badge badge-warning">En attente</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">Aucun patient en attente</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Derniers patients -->
<div class="card mt-4">
    <div class="card-header">
        <h2 class="card-title">Derniers patients enregistrés</h2>
        <a href="{{ route('reception.patients.index') }}" class="btn btn-outline btn-sm">Voir tout</a>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Patient</th><th>Contact</th><th>Date inscription</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @foreach($derniersPatients as $patient)
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="avatar">{{ strtoupper(substr($patient->prenom, 0, 1) . substr($patient->nom, 0, 1)) }}</div>
                                <div>
                                    <div class="user-name">{{ $patient->prenom }} {{ $patient->nom }}</div>
                                    <div class="user-sub">{{ \Carbon\Carbon::parse($patient->date_naissance)->age }} ans - {{ $patient->sexe }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $patient->telephone }}</td>
                        <td>{{ $patient->date_inscription->format('d/m/Y') }}</td>
                        <td><a href="{{ route('reception.patients.show', $patient) }}" class="btn btn-outline btn-sm">Voir</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nouveau Patient -->
<div class="modal-overlay" id="modalPatient">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 class="modal-title">Enregistrement Patient</h3>
            <button class="modal-close" onclick="closeModal('modalPatient')">&times;</button>
        </div>
        <form action="{{ route('reception.patients.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Nom *</label><input type="text" class="form-control" name="nom" required></div>
                    <div class="form-group"><label class="form-label">Prénom *</label><input type="text" class="form-control" name="prenom" required></div>
                </div>
                <div class="form-row-3">
                    <div class="form-group"><label class="form-label">Date naissance *</label><input type="date" class="form-control" name="date_naissance" required></div>
                    <div class="form-group"><label class="form-label">Sexe *</label><select class="form-control" name="sexe" required><option value="">-</option><option value="M">Masculin</option><option value="F">Féminin</option></select></div>
                    <div class="form-group"><label class="form-label">Groupe sanguin</label><select class="form-control" name="groupe_sanguin"><option value="">-</option><option>A+</option><option>A-</option><option>B+</option><option>B-</option><option>AB+</option><option>AB-</option><option>O+</option><option>O-</option></select></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Téléphone *</label><input type="tel" class="form-control" name="telephone" required></div>
                    <div class="form-group"><label class="form-label">Email</label><input type="email" class="form-control" name="email"></div>
                </div>
                <div class="form-group"><label class="form-label">Adresse</label><input type="text" class="form-control" name="adresse"></div>
                <div class="form-group"><label class="form-label">Allergies connues</label><input type="text" class="form-control" name="allergies" placeholder="Séparer par virgules"></div>
                <div class="form-group" style="background:var(--primary-light, #e0f7fa);padding:16px;border-radius:8px;">
                    <label style="display:flex;align-items:center;cursor:pointer;">
                        <input type="checkbox" name="creer_consultation" value="1" style="margin-right:8px;">
                        Créer une consultation directement après l'enregistrement
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalPatient')">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
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
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Patient *</label>
                    <select class="form-control" name="patient_id" required>
                        <option value="">Sélectionner</option>
                        @foreach($patients ?? [] as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->prenom }} {{ $patient->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Médecin *</label>
                    <select class="form-control" name="medecin_id" required>
                        <option value="">Sélectionner</option>
                        @foreach($medecins ?? [] as $medecin)
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
                <button type="submit" class="btn btn-primary">Ajouter à la file</button>
            </div>
        </form>
    </div>
</div>
@endsection
