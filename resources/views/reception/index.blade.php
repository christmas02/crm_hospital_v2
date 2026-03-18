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
<!-- Stats -->
<div class="stats" style="grid-template-columns: repeat(4, 1fr);">
    <div class="stat-card stat-card-accent" style="border-left: 4px solid var(--primary);">
        <div>
            <div class="stat-label">Patients aujourd'hui</div>
            <div class="stat-value">{{ $stats['patients_aujourdhui'] }}</div>
            <div class="stat-sub">enregistrés ce jour</div>
        </div>
        <div class="stat-icon cyan">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
    </div>
    <div class="stat-card stat-card-accent" style="border-left: 4px solid var(--warning);">
        <div>
            <div class="stat-label">Consultations en attente</div>
            <div class="stat-value">{{ $stats['en_attente'] }}</div>
            <div class="stat-sub">dans la file</div>
        </div>
        <div class="stat-icon orange">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </div>
    </div>
    <div class="stat-card stat-card-accent" style="border-left: 4px solid var(--secondary);">
        <div>
            <div class="stat-label">Factures envoyées</div>
            <div class="stat-value">{{ $stats['factures_envoyees'] }}</div>
            <div class="stat-sub">transmises en caisse</div>
        </div>
        <div class="stat-icon green">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/><path d="M9 15l2 2 4-4"/></svg>
        </div>
    </div>
    <div class="stat-card stat-card-accent" style="border-left: 4px solid var(--danger);">
        <div>
            <div class="stat-label">En attente paiement</div>
            <div class="stat-value">{{ $stats['en_attente_paiement'] }}</div>
            <div class="stat-sub">non réglées</div>
        </div>
        <div class="stat-icon red">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
        </div>
    </div>
</div>

<!-- Actions rapides + File d'attente -->
<div style="display:flex;gap:12px;margin-bottom:24px;">
    <button class="btn btn-primary" onclick="openModal('modalPatient')" style="padding:12px 20px;border-radius:12px;font-weight:600;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><path d="M20 8v6M23 11h-6"/></svg>
        Nouveau Patient
    </button>
    <button class="btn btn-success" onclick="openModal('modalConsult')" style="padding:12px 20px;border-radius:12px;font-weight:600;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M12 18v-6M9 15h6"/></svg>
        Nouvelle Consultation
    </button>
</div>

<div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--warning)" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                File d'attente
            </h2>
            <span class="badge badge-warning">{{ count($consultationsEnAttente) }}</span>
        </div>
        <div class="card-body no-pad">
            <div class="table-wrap">
                <table class="table-patients">
                    <thead>
                        <tr><th>Patient</th><th>Heure</th><th>Médecin</th><th style="text-align:center;">Statut</th></tr>
                    </thead>
                    <tbody>
                        @forelse($consultationsEnAttente as $consultation)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar">{{ strtoupper(substr($consultation->patient->prenom, 0, 1) . substr($consultation->patient->nom, 0, 1)) }}</div>
                                    <div>
                                        <div class="user-name">{{ $consultation->patient->prenom }} {{ $consultation->patient->nom }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                    <span style="font-weight:500;">{{ $consultation->heure }}</span>
                                </div>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    <span>Dr. {{ $consultation->medecin->nom }}</span>
                                </div>
                            </td>
                            <td style="text-align:center;">
                                <span class="badge badge-warning">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right:4px;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                    En attente
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;padding:32px;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:8px;"><circle cx="12" cy="12" r="10"/><path d="M8 15s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                                <div class="text-muted" style="font-size:.875rem;">Aucun patient en attente</div>
                                <div class="text-muted" style="font-size:.75rem;margin-top:4px;">La file est vide pour le moment</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- Charts -->
<div class="grid-2 mt-4">
    <div class="card">
        <div class="card-header"><h2 class="card-title">Consultations (7 derniers jours)</h2></div>
        <div class="card-body"><div class="chart-container"><canvas id="chartConsultJour"></canvas></div></div>
    </div>
    <div class="card">
        <div class="card-header"><h2 class="card-title">Répartition par statut</h2></div>
        <div class="card-body"><div class="chart-container"><canvas id="chartStatut"></canvas></div></div>
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
            <table class="table-patients">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Contact</th>
                        <th>Date inscription</th>
                        <th>Sexe</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($derniersPatients as $patient)
                    <tr onclick="voirPatient({{ $patient->id }})" style="cursor:pointer;">
                        <td>
                            <div class="user-cell">
                                <div class="avatar" style="background:{{ $patient->sexe == 'M' ? 'var(--primary-light)' : '#fce7f3' }};color:{{ $patient->sexe == 'M' ? 'var(--primary)' : '#db2777' }};">
                                    {{ strtoupper(substr($patient->prenom, 0, 1) . substr($patient->nom, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="user-name">{{ $patient->prenom }} {{ $patient->nom }}</div>
                                    <div class="user-sub">{{ \Carbon\Carbon::parse($patient->date_naissance)->age }} ans</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                                <span>{{ $patient->telephone }}</span>
                            </div>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                <span>{{ $patient->date_inscription->format('d/m/Y') }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $patient->sexe == 'M' ? 'badge-info' : 'badge-pink' }}">{{ $patient->sexe == 'M' ? 'Homme' : 'Femme' }}</span>
                        </td>
                        <td style="text-align:center;">
                            <button class="btn btn-primary btn-sm" onclick="event.stopPropagation(); voirPatient({{ $patient->id }})">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                Voir
                            </button>
                        </td>
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
                    <div class="autocomplete-wrap">
                        <input type="hidden" name="patient_id" id="consultPatientId" required>
                        <input type="text" class="form-control" id="consultPatientSearch" placeholder="Rechercher un patient..." autocomplete="off">
                        <div class="autocomplete-list" id="consultPatientList"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Médecin *</label>
                    <div class="autocomplete-wrap">
                        <input type="hidden" name="medecin_id" id="consultMedecinId" required>
                        <input type="text" class="form-control" id="consultMedecinSearch" placeholder="Rechercher un médecin..." autocomplete="off">
                        <div class="autocomplete-list" id="consultMedecinList"></div>
                    </div>
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

<!-- Modal Détail Patient -->
<div class="modal-overlay" id="modalVoirPatient">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 class="modal-title" id="mpTitle">Détails du patient</h3>
            <button class="modal-close" onclick="closeModal('modalVoirPatient')">&times;</button>
        </div>
        <div class="modal-body" id="mpBody">
            <div style="text-align:center;padding:40px;">
                <div class="text-muted">Chargement...</div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" id="mpLinkDossier" class="btn btn-outline">Ouvrir le dossier complet</a>
            <button type="button" class="btn btn-primary" id="mpBtnEdit">Modifier</button>
        </div>
    </div>
</div>

<!-- Modal Modifier Patient -->
<div class="modal-overlay" id="modalEditPatient">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 class="modal-title" id="meTitle">Modifier le patient</h3>
            <button class="modal-close" onclick="closeModal('modalEditPatient')">&times;</button>
        </div>
        <form id="editPatientForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Nom *</label><input type="text" class="form-control" name="nom" id="editNom" required></div>
                    <div class="form-group"><label class="form-label">Prénom *</label><input type="text" class="form-control" name="prenom" id="editPrenom" required></div>
                </div>
                <div class="form-row-3">
                    <div class="form-group"><label class="form-label">Date naissance *</label><input type="date" class="form-control" name="date_naissance" id="editDateNaissance" required></div>
                    <div class="form-group">
                        <label class="form-label">Sexe *</label>
                        <select class="form-control" name="sexe" id="editSexe" required>
                            <option value="">-</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Groupe sanguin</label>
                        <select class="form-control" name="groupe_sanguin" id="editGroupeSanguin">
                            <option value="">-</option>
                            <option>A+</option><option>A-</option><option>B+</option><option>B-</option>
                            <option>AB+</option><option>AB-</option><option>O+</option><option>O-</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Téléphone</label><input type="tel" class="form-control" name="telephone" id="editTelephone"></div>
                    <div class="form-group"><label class="form-label">Email</label><input type="email" class="form-control" name="email" id="editEmail"></div>
                </div>
                <div class="form-group"><label class="form-label">Adresse</label><input type="text" class="form-control" name="adresse" id="editAdresse"></div>
                <div class="form-group"><label class="form-label">Allergies connues</label><input type="text" class="form-control" name="allergies" id="editAllergies" placeholder="Séparer par virgules"></div>
                <div class="form-group">
                    <label class="form-label">Statut</label>
                    <select class="form-control" name="statut" id="editStatut">
                        <option value="actif">Actif</option>
                        <option value="hospitalise">Hospitalisé</option>
                        <option value="inactif">Inactif</option>
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

<script>
var currentPatientId = null;

// Données pour autocomplete
var allPatients = @json(($patients ?? collect())->map(fn($p) => ['id' => $p->id, 'label' => $p->prenom . ' ' . $p->nom, 'sub' => $p->telephone ?? '']));
var allMedecins = @json(($medecins ?? collect())->map(fn($m) => ['id' => $m->id, 'label' => 'Dr. ' . $m->prenom . ' ' . $m->nom, 'sub' => $m->specialite ?? '']));

function setupAutocomplete(inputId, listId, hiddenId, data) {
    var input = document.getElementById(inputId);
    var list = document.getElementById(listId);
    var hidden = document.getElementById(hiddenId);

    input.addEventListener('focus', function() { filterList(''); });
    input.addEventListener('input', function() {
        hidden.value = '';
        filterList(this.value);
    });

    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !list.contains(e.target)) {
            list.style.display = 'none';
        }
    });

    function filterList(query) {
        var q = query.toLowerCase();
        var filtered = data.filter(function(item) {
            return item.label.toLowerCase().includes(q) || item.sub.toLowerCase().includes(q);
        });

        if (filtered.length === 0) {
            list.innerHTML = '<div class="autocomplete-empty">Aucun résultat</div>';
        } else {
            list.innerHTML = filtered.map(function(item) {
                return '<div class="autocomplete-item" data-id="' + item.id + '" data-label="' + item.label + '">' +
                    '<div class="autocomplete-item-label">' + item.label + '</div>' +
                    (item.sub ? '<div class="autocomplete-item-sub">' + item.sub + '</div>' : '') +
                '</div>';
            }).join('');
        }
        list.style.display = 'block';

        list.querySelectorAll('.autocomplete-item').forEach(function(el) {
            el.addEventListener('click', function() {
                input.value = this.dataset.label;
                hidden.value = this.dataset.id;
                list.style.display = 'none';
            });
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    setupAutocomplete('consultPatientSearch', 'consultPatientList', 'consultPatientId', allPatients);
    setupAutocomplete('consultMedecinSearch', 'consultMedecinList', 'consultMedecinId', allMedecins);
});

function voirPatient(id) {
    document.getElementById('mpBody').innerHTML = '<div style="text-align:center;padding:40px;"><div class="text-muted">Chargement...</div></div>';
    openModal('modalVoirPatient');

    currentPatientId = id;

    fetch('/reception/patients/' + id + '/json')
        .then(r => r.json())
        .then(p => {
            document.getElementById('mpTitle').textContent = p.prenom + ' ' + p.nom;
            document.getElementById('mpLinkDossier').href = p.show_url;
            document.getElementById('mpBtnEdit').onclick = function() { modifierPatient(p); };

            const statutBadge = p.statut === 'hospitalise'
                ? '<span class="badge badge-info">Hospitalisé</span>'
                : '<span class="badge badge-success">Actif</span>';

            const allergiesHtml = p.allergies.length
                ? p.allergies.map(a => '<span class="badge badge-danger">' + a.trim() + '</span>').join(' ')
                : '<span class="text-muted">Aucune</span>';

            let consultHtml = '';
            if (p.consultations.length) {
                consultHtml = '<table><thead><tr><th>Date</th><th>Médecin</th><th>Motif</th><th>Statut</th></tr></thead><tbody>';
                p.consultations.forEach(c => {
                    let badge = '';
                    if (c.statut === 'termine') badge = '<span class="badge badge-success">Terminé</span>';
                    else if (c.statut === 'en_cours') badge = '<span class="badge badge-info">En cours</span>';
                    else badge = '<span class="badge badge-warning">En attente</span>';
                    consultHtml += '<tr><td>' + c.date + ' ' + c.heure + '</td><td>' + c.medecin + '</td><td class="truncate" style="max-width:180px;">' + c.motif + '</td><td>' + badge + '</td></tr>';
                });
                consultHtml += '</tbody></table>';
            } else {
                consultHtml = '<p class="text-muted text-center" style="padding:16px;">Aucune consultation</p>';
            }

            document.getElementById('mpBody').innerHTML = `
                <div style="display:flex;align-items:center;gap:16px;margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid var(--gray-200);">
                    <div class="avatar lg" style="width:64px;height:64px;font-size:1.25rem;background:var(--primary);color:#fff;">${p.initiales}</div>
                    <div style="flex:1;">
                        <div style="font-size:1.15rem;font-weight:700;color:var(--gray-800);">${p.prenom} ${p.nom}</div>
                        <div class="text-muted">${p.age} ans - ${p.sexe_label} ${p.groupe_sanguin ? '&bull; <span class="badge badge-secondary">' + p.groupe_sanguin + '</span>' : ''}</div>
                    </div>
                    <div>${statutBadge}</div>
                </div>

                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-bottom:24px;">
                    <div style="background:var(--gray-50);padding:14px;border-radius:10px;">
                        <div class="text-muted text-xs" style="margin-bottom:4px;text-transform:uppercase;letter-spacing:.3px;font-weight:600;">Téléphone</div>
                        <div style="font-weight:500;">${p.telephone}</div>
                    </div>
                    <div style="background:var(--gray-50);padding:14px;border-radius:10px;">
                        <div class="text-muted text-xs" style="margin-bottom:4px;text-transform:uppercase;letter-spacing:.3px;font-weight:600;">Email</div>
                        <div style="font-weight:500;">${p.email}</div>
                    </div>
                    <div style="background:var(--gray-50);padding:14px;border-radius:10px;">
                        <div class="text-muted text-xs" style="margin-bottom:4px;text-transform:uppercase;letter-spacing:.3px;font-weight:600;">Date de naissance</div>
                        <div style="font-weight:500;">${p.date_naissance}</div>
                    </div>
                    <div style="background:var(--gray-50);padding:14px;border-radius:10px;">
                        <div class="text-muted text-xs" style="margin-bottom:4px;text-transform:uppercase;letter-spacing:.3px;font-weight:600;">Adresse</div>
                        <div style="font-weight:500;">${p.adresse}</div>
                    </div>
                </div>

                <div style="margin-bottom:24px;">
                    <div class="text-muted text-xs" style="margin-bottom:8px;text-transform:uppercase;letter-spacing:.3px;font-weight:600;">Allergies</div>
                    <div class="flex gap-2" style="flex-wrap:wrap;">${allergiesHtml}</div>
                </div>

                <div>
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                        <div style="font-weight:600;color:var(--gray-700);">Dernières consultations</div>
                        <span class="badge badge-secondary">${p.nb_consultations}</span>
                    </div>
                    <div class="table-wrap" style="border:1px solid var(--gray-200);border-radius:10px;overflow:hidden;">
                        ${consultHtml}
                    </div>
                </div>
            `;
        })
        .catch(() => {
            document.getElementById('mpBody').innerHTML = '<div class="text-center text-danger" style="padding:40px;">Erreur lors du chargement</div>';
        });
}

function modifierPatient(p) {
    closeModal('modalVoirPatient');

    document.getElementById('meTitle').textContent = 'Modifier - ' + p.prenom + ' ' + p.nom;
    document.getElementById('editPatientForm').action = '/reception/patients/' + p.id;
    document.getElementById('editNom').value = p.nom;
    document.getElementById('editPrenom').value = p.prenom;
    document.getElementById('editDateNaissance').value = p.date_naissance_raw;
    document.getElementById('editSexe').value = p.sexe;
    setSearchableSelectValue(document.getElementById('editGroupeSanguin'), p.groupe_sanguin || '', p.groupe_sanguin || '');
    document.getElementById('editTelephone').value = p.telephone_raw;
    document.getElementById('editEmail').value = p.email_raw;
    document.getElementById('editAdresse').value = p.adresse_raw;
    document.getElementById('editAllergies').value = p.allergies.length ? p.allergies.join(', ') : '';
    document.getElementById('editStatut').value = p.statut || 'actif';

    setTimeout(function() { openModal('modalEditPatient'); }, 200);
}
</script>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Line chart - Consultations par jour
    const ctxLine = document.getElementById('chartConsultJour');
    if (ctxLine) {
        const gradientLine = ctxLine.getContext('2d');
        const gradient = gradientLine.createLinearGradient(0, 0, 0, 250);
        gradient.addColorStop(0, 'rgba(8, 145, 178, 0.3)');
        gradient.addColorStop(1, 'rgba(8, 145, 178, 0.02)');

        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: @json($consultationsParJour->pluck('date')),
                datasets: [{
                    label: 'Consultations',
                    data: @json($consultationsParJour->pluck('count')),
                    borderColor: 'rgb(8, 145, 178)',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointBackgroundColor: 'rgb(8, 145, 178)',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    }

    // Doughnut chart - Répartition par statut
    const ctxDoughnut = document.getElementById('chartStatut');
    if (ctxDoughnut) {
        new Chart(ctxDoughnut, {
            type: 'doughnut',
            data: {
                labels: ['En attente', 'En cours', 'Terminé'],
                datasets: [{
                    data: [{{ $parStatut['en_attente'] }}, {{ $parStatut['en_cours'] }}, {{ $parStatut['termine'] }}],
                    backgroundColor: ['rgb(217, 119, 6)', 'rgb(8, 145, 178)', 'rgb(5, 150, 105)'],
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 16, usePointStyle: true, pointStyle: 'circle' }
                    }
                },
                cutout: '60%',
            }
        });
    }
});
</script>
@endpush

@endsection
