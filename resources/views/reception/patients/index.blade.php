@extends('layouts.medicare')

@section('title', 'Patients - MediCare Pro')
@section('sidebar-subtitle', 'Réception')
@section('user-color', '#059669')
@section('header-title', 'Gestion des Patients')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('reception._sidebar')
@endif
@endsection

@section('content')
<div class="toolbar">
    <div class="filters">
        <form action="{{ route('reception.patients.index') }}" method="GET" class="flex gap-2">
            <input type="text" class="filter-input" placeholder="Rechercher un patient..." name="search" value="{{ request('search') }}">
            <select class="filter-select" name="statut" onchange="this.form.submit()">
                <option value="">Tous les statuts</option>
                <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                <option value="hospitalise" {{ request('statut') == 'hospitalise' ? 'selected' : '' }}>Hospitalisé</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Rechercher</button>
        </form>
    </div>
    <button class="btn btn-primary" onclick="openModal('modalPatient')">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
        Nouveau Patient
    </button>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Liste des Patients</h2>
        <span class="text-muted text-sm">{{ $patients->total() }} patients</span>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Contact</th>
                        <th>Groupe sanguin</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="avatar">{{ strtoupper(substr($patient->prenom, 0, 1) . substr($patient->nom, 0, 1)) }}</div>
                                <div>
                                    <div class="user-name">{{ $patient->prenom }} {{ $patient->nom }}</div>
                                    <div class="user-sub">{{ \Carbon\Carbon::parse($patient->date_naissance)->age }} ans - {{ $patient->sexe == 'M' ? 'Masculin' : 'Féminin' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>{{ $patient->telephone }}</div>
                            @if($patient->email)
                            <div class="text-muted text-sm">{{ $patient->email }}</div>
                            @endif
                        </td>
                        <td>
                            @if($patient->groupe_sanguin)
                            <span class="badge badge-light">{{ $patient->groupe_sanguin }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($patient->statut == 'hospitalise')
                            <span class="badge badge-info">Hospitalisé</span>
                            @else
                            <span class="badge badge-success">Actif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('reception.patients.show', $patient) }}" class="btn btn-outline btn-sm">Voir</a>
                            <a href="{{ route('reception.patients.edit', $patient) }}" class="btn btn-outline btn-sm">Modifier</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted">Aucun patient trouvé</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($patients->hasPages())
<div class="mt-4 flex justify-center">
    {{ $patients->links() }}
</div>
@endif

<!-- Modal Nouveau Patient -->
<div class="modal-overlay" id="modalPatient">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 class="modal-title">Nouveau Patient</h3>
            <button class="modal-close" onclick="closeModal('modalPatient')">&times;</button>
        </div>
        <form action="{{ route('reception.patients.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nom *</label>
                        <input type="text" class="form-control" name="nom" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prénom *</label>
                        <input type="text" class="form-control" name="prenom" required>
                    </div>
                </div>
                <div class="form-row-3">
                    <div class="form-group">
                        <label class="form-label">Date de naissance *</label>
                        <input type="date" class="form-control" name="date_naissance" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sexe *</label>
                        <select class="form-control" name="sexe" required>
                            <option value="">Sélectionner</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Groupe sanguin</label>
                        <select class="form-control" name="groupe_sanguin">
                            <option value="">Sélectionner</option>
                            <option>A+</option>
                            <option>A-</option>
                            <option>B+</option>
                            <option>B-</option>
                            <option>AB+</option>
                            <option>AB-</option>
                            <option>O+</option>
                            <option>O-</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Téléphone *</label>
                        <input type="tel" class="form-control" name="telephone" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Adresse</label>
                    <input type="text" class="form-control" name="adresse">
                </div>
                <div class="form-group">
                    <label class="form-label">Allergies connues</label>
                    <input type="text" class="form-control" name="allergies" placeholder="Séparer par des virgules">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalPatient')">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection
