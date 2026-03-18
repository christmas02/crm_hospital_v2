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
    <div class="filters" style="flex:1;display:flex;gap:10px;align-items:center;">
        <div style="position:relative;flex:1;max-width:350px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <input type="text" class="filter-input" id="liveSearchPatient" placeholder="Rechercher par nom, prénom, téléphone..." value="{{ request('search') }}" style="padding-left:36px;width:100%;" autocomplete="off">
        </div>
        <select class="filter-select" id="liveFilterStatut">
            <option value="">Tous les statuts</option>
            <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
            <option value="hospitalise" {{ request('statut') == 'hospitalise' ? 'selected' : '' }}>Hospitalisé</option>
            <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
        </select>
        <span id="liveSearchCount" style="font-size:.8rem;color:var(--gray-400);white-space:nowrap;"></span>
    </div>
    <button class="btn btn-primary" onclick="openModal('modalPatient')">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
        Nouveau Patient
    </button>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            Liste des Patients
        </h2>
        <div style="display:flex;align-items:center;gap:8px;">
            <span class="text-muted text-sm">{{ $patients->total() }} patients</span>
            <a href="{{ route('export.patients') }}" class="btn btn-outline btn-sm">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>
                Export CSV
            </a>
        </div>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Contact</th>
                        <th>Groupe sanguin</th>
                        <th>Sexe</th>
                        <th>Statut</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="avatar" style="background:{{ $patient->sexe == 'M' ? 'var(--primary-light)' : '#fce7f3' }};color:{{ $patient->sexe == 'M' ? 'var(--primary)' : '#db2777' }};">{{ strtoupper(substr($patient->prenom, 0, 1) . substr($patient->nom, 0, 1)) }}</div>
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
                            @if($patient->email)
                            <div class="text-muted text-sm" style="margin-left:23px;">{{ $patient->email }}</div>
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
                            <span class="badge {{ $patient->sexe == 'M' ? 'badge-info' : 'badge-pink' }}">{{ $patient->sexe == 'M' ? 'Homme' : 'Femme' }}</span>
                        </td>
                        <td>
                            @if($patient->statut == 'hospitalise')
                            <span class="badge badge-info">Hospitalisé</span>
                            @else
                            <span class="badge badge-success">Actif</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            <div style="display:flex;gap:6px;justify-content:center;">
                                <a href="{{ route('reception.patients.show', $patient) }}" class="btn btn-primary btn-sm" title="Voir le dossier">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <button class="btn btn-outline btn-sm" title="Modifier" onclick="openEditPatient({{ $patient->id }})">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                <button class="btn btn-sm" style="background:var(--danger-light);color:var(--danger);border:1px solid var(--danger);" title="Supprimer" onclick="confirmDeletePatient('{{ route('reception.patients.destroy', $patient) }}', '{{ $patient->prenom }} {{ $patient->nom }}')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:32px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:8px;"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                            <div class="text-muted" style="font-size:.875rem;">Aucun patient trouvé</div>
                            <div class="text-muted" style="font-size:.75rem;margin-top:4px;">Essayez de modifier vos critères de recherche</div>
                        </td>
                    </tr>
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

<!-- Modal Suppression Patient -->
<div class="modal-overlay" id="modalDeletePatient">
    <div class="modal" style="max-width:440px;">
        <div class="modal-header" style="background:linear-gradient(135deg, var(--danger), #ef4444);">
            <h3 class="modal-title">Confirmer la suppression</h3>
            <button class="modal-close" onclick="closeModal('modalDeletePatient')">&times;</button>
        </div>
        <div class="modal-body" style="text-align:center;padding:30px;">
            <div style="width:60px;height:60px;border-radius:50%;background:var(--danger-light, #fee2e2);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--danger, #ef4444)" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
            </div>
            <p style="font-weight:600;font-size:1rem;margin-bottom:8px;">Etes-vous sur ?</p>
            <p class="text-muted" style="font-size:.85rem;" id="deletePatientMsg">Cette action est irreversible.</p>
        </div>
        <div class="modal-footer" style="justify-content:center;">
            <button class="btn btn-secondary" onclick="closeModal('modalDeletePatient')">Annuler</button>
            <form id="deletePatientForm" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Modifier Patient -->
<div class="modal-overlay" id="modalEditPatient">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 class="modal-title" id="editPatientTitle">Modifier le patient</h3>
            <button class="modal-close" onclick="closeModal('modalEditPatient')">&times;</button>
        </div>
        <form id="editPatientForm" method="POST">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Nom *</label><input type="text" class="form-control" name="nom" id="epNom" required></div>
                    <div class="form-group"><label class="form-label">Prénom *</label><input type="text" class="form-control" name="prenom" id="epPrenom" required></div>
                </div>
                <div class="form-row-3">
                    <div class="form-group"><label class="form-label">Date naissance *</label><input type="date" class="form-control" name="date_naissance" id="epDateNaissance" required></div>
                    <div class="form-group">
                        <label class="form-label">Sexe *</label>
                        <select class="form-control" name="sexe" id="epSexe" required>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Groupe sanguin</label>
                        <select class="form-control" name="groupe_sanguin" id="epGroupeSanguin">
                            <option value="">-</option>
                            <option>A+</option><option>A-</option><option>B+</option><option>B-</option>
                            <option>AB+</option><option>AB-</option><option>O+</option><option>O-</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Téléphone</label><input type="tel" class="form-control" name="telephone" id="epTelephone"></div>
                    <div class="form-group"><label class="form-label">Email</label><input type="email" class="form-control" name="email" id="epEmail"></div>
                </div>
                <div class="form-group"><label class="form-label">Adresse</label><input type="text" class="form-control" name="adresse" id="epAdresse"></div>
                <div class="form-group"><label class="form-label">Allergies</label><input type="text" class="form-control" name="allergies" id="epAllergies" placeholder="Séparer par virgules"></div>
                <div class="form-group">
                    <label class="form-label">Statut</label>
                    <select class="form-control" name="statut" id="epStatut">
                        <option value="actif">Actif</option>
                        <option value="hospitalise">Hospitalisé</option>
                        <option value="inactif">Inactif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditPatient')">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function confirmDeletePatient(url, name) {
    document.getElementById('deletePatientForm').action = url;
    document.getElementById('deletePatientMsg').textContent = 'Le patient "' + name + '" sera supprimé. Cette action est irréversible.';
    openModal('modalDeletePatient');
}

// Live search
(function() {
    var searchInput = document.getElementById('liveSearchPatient');
    var statutSelect = document.getElementById('liveFilterStatut');
    var countEl = document.getElementById('liveSearchCount');
    var rows = document.querySelectorAll('.table-patients tbody tr');

    function filterRows() {
        var q = searchInput.value.toLowerCase().trim();
        var statut = statutSelect.value.toLowerCase();
        var visible = 0;

        rows.forEach(function(row) {
            var text = row.textContent.toLowerCase();
            var matchSearch = !q || text.includes(q);
            var matchStatut = !statut || text.includes(statut === 'hospitalise' ? 'hospitalisé' : statut);
            if (matchSearch && matchStatut) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        countEl.textContent = visible + ' / ' + rows.length + ' patients';
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterRows);
        statutSelect.addEventListener('change', filterRows);
        filterRows();
    }
})();

function openEditPatient(id) {
    fetch('/reception/patients/' + id + '/json')
        .then(r => r.json())
        .then(p => {
            document.getElementById('editPatientTitle').textContent = 'Modifier — ' + p.prenom + ' ' + p.nom;
            document.getElementById('editPatientForm').action = '/reception/patients/' + p.id;
            document.getElementById('epNom').value = p.nom;
            document.getElementById('epPrenom').value = p.prenom;
            document.getElementById('epDateNaissance').value = p.date_naissance_raw;
            document.getElementById('epSexe').value = p.sexe;
            setSearchableSelectValue(document.getElementById('epGroupeSanguin'), p.groupe_sanguin || '', p.groupe_sanguin || '');
            document.getElementById('epTelephone').value = p.telephone_raw || '';
            document.getElementById('epEmail').value = p.email_raw || '';
            document.getElementById('epAdresse').value = p.adresse_raw || '';
            document.getElementById('epAllergies').value = p.allergies.length ? p.allergies.join(', ') : '';
            document.getElementById('epStatut').value = p.statut || 'actif';
            openModal('modalEditPatient');
        });
}
</script>
@endpush

@endsection
