@extends('layouts.medicare')

@section('title', 'Consultations - MediCare Pro')
@section('sidebar-subtitle', 'Réception')
@section('user-color', '#059669')
@section('header-title', 'Gestion des Consultations')

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
        <form action="{{ route('reception.consultations.index') }}" method="GET" class="flex gap-2">
            <input type="date" class="filter-input" name="date" value="{{ request('date', date('Y-m-d')) }}">
            <select class="filter-select" name="statut" onchange="this.form.submit()">
                <option value="">Tous les statuts</option>
                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>Terminé</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
        </form>
    </div>
    <button class="btn btn-primary" onclick="openModal('modalConsult')">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
        Nouvelle Consultation
    </button>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8M16 17H8M10 9H8"/></svg>
            Consultations du {{ \Carbon\Carbon::parse(request('date', date('Y-m-d')))->format('d/m/Y') }}
        </h2>
        <div style="display:flex;align-items:center;gap:8px;">
            <span class="text-muted text-sm">{{ $consultations->total() }} consultations</span>
            <a href="{{ route('export.consultations') }}" class="btn btn-outline btn-sm">
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
                        <th>Heure</th>
                        <th>Patient</th>
                        <th>Médecin</th>
                        <th>Motif</th>
                        <th>Statut</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consultations as $consultation)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                <span style="font-weight:500;">{{ $consultation->heure }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="user-cell">
                                <div class="avatar" style="background:{{ $consultation->patient->sexe == 'M' ? 'var(--primary-light)' : '#fce7f3' }};color:{{ $consultation->patient->sexe == 'M' ? 'var(--primary)' : '#db2777' }};">{{ strtoupper(substr($consultation->patient->prenom, 0, 1) . substr($consultation->patient->nom, 0, 1)) }}</div>
                                <div>
                                    <div class="user-name">{{ $consultation->patient->prenom }} {{ $consultation->patient->nom }}</div>
                                    <div class="user-sub">
                                        <span class="badge {{ $consultation->patient->sexe == 'M' ? 'badge-info' : 'badge-pink' }}" style="font-size:.65rem;padding:1px 6px;">{{ $consultation->patient->sexe == 'M' ? 'Homme' : 'Femme' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                <span>Dr. {{ $consultation->medecin->nom }}</span>
                            </div>
                        </td>
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
                            <div style="display:flex;gap:4px;justify-content:center;">
                                <button class="btn btn-primary btn-sm" onclick="voirConsultation({{ $consultation->id }})">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                                @if($consultation->statut == 'en_attente' && $consultation->patient->email)
                                <form action="{{ route('reception.consultations.rappel', $consultation) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm" style="background:var(--warning-light);color:var(--warning);border:1px solid var(--warning);" title="Envoyer rappel email">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
                                    </button>
                                </form>
                                @endif
                                @if($consultation->statut == 'en_attente')
                                <button type="button" class="btn btn-outline btn-sm" onclick="openEditModal({{ $consultation->id }})" title="Modifier">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                <button type="button" class="btn btn-sm" style="background:var(--danger-light);color:var(--danger);border:1px solid var(--danger);" onclick="openDeleteModal({{ $consultation->id }})" title="Supprimer">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:32px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:8px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8M16 17H8M10 9H8"/></svg>
                            <div class="text-muted" style="font-size:.875rem;">Aucune consultation trouvée</div>
                            <div class="text-muted" style="font-size:.75rem;margin-top:4px;">Aucune consultation pour cette date et ces critères</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($consultations->hasPages())
<div class="mt-4 flex justify-center">
    {{ $consultations->links() }}
</div>
@endif

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
                        @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->prenom }} {{ $patient->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Médecin *</label>
                    <select class="form-control" name="medecin_id" required>
                        <option value="">Sélectionner</option>
                        @foreach($medecins as $medecin)
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
                <button type="submit" class="btn btn-primary">Ajouter à la file</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Modifier Consultation -->
<div class="modal-overlay" id="modalEditConsult">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Modifier la Consultation</h3>
            <button class="modal-close" onclick="closeModal('modalEditConsult')">&times;</button>
        </div>
        <form id="editConsultForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Patient *</label>
                    <select class="form-control" name="patient_id" id="edit_patient_id" required>
                        <option value="">Sélectionner</option>
                        @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->prenom }} {{ $patient->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Médecin *</label>
                    <select class="form-control" name="medecin_id" id="edit_medecin_id" required>
                        <option value="">Sélectionner</option>
                        @foreach($medecins as $medecin)
                        <option value="{{ $medecin->id }}">Dr. {{ $medecin->prenom }} {{ $medecin->nom }} - {{ $medecin->specialite }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Date *</label>
                        <input type="date" class="form-control" name="date" id="edit_date" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Heure *</label>
                        <input type="time" class="form-control" name="heure" id="edit_heure" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Motif *</label>
                    <textarea class="form-control" name="motif" id="edit_motif" required placeholder="Motif de la consultation"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditConsult')">Annuler</button>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Supprimer Consultation -->
<div class="modal-overlay" id="modalDeleteConsult">
    <div class="modal" style="max-width:450px;">
        <div class="modal-header">
            <h3 class="modal-title" style="color:#dc2626;">Supprimer la Consultation</h3>
            <button class="modal-close" onclick="closeModal('modalDeleteConsult')">&times;</button>
        </div>
        <form id="deleteConsultForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body" style="text-align:center;padding:24px;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="1.5" style="margin-bottom:12px;">
                    <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
                </svg>
                <p style="font-size:.95rem;color:var(--gray-700);margin:0;">
                    Êtes-vous sûr de vouloir supprimer cette consultation ?
                </p>
                <p style="font-size:.8rem;color:var(--gray-400);margin-top:8px;">
                    Cette action est irréversible.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalDeleteConsult')">Annuler</button>
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Voir Consultation -->
<div class="modal-overlay" id="modalVoirConsult">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 class="modal-title" id="vcTitle">Détails de la consultation</h3>
            <button class="modal-close" onclick="closeModal('modalVoirConsult')">&times;</button>
        </div>
        <div class="modal-body" id="vcBody">
            <div style="text-align:center;padding:40px;" class="text-muted">Chargement...</div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('modalVoirConsult')">Fermer</button>
            <a href="#" id="vcLinkFull" class="btn btn-primary">Ouvrir le dossier complet</a>
        </div>
    </div>
</div>

<script>
function voirConsultation(id) {
    document.getElementById('vcBody').innerHTML = '<div style="text-align:center;padding:40px;" class="text-muted">Chargement...</div>';
    openModal('modalVoirConsult');

    fetch('/reception/consultations/' + id + '/json')
        .then(r => r.json())
        .then(c => {
            document.getElementById('vcTitle').textContent = 'Consultation du ' + c.date;
            document.getElementById('vcLinkFull').href = '/reception/consultations/' + id;

            var statutBadge = {
                'en_attente': '<span class="badge badge-warning">En attente</span>',
                'en_cours': '<span class="badge badge-info">En cours</span>',
                'termine': '<span class="badge badge-success">Terminé</span>',
            };

            document.getElementById('vcBody').innerHTML = `
                <div style="display:flex;align-items:center;gap:16px;padding-bottom:20px;margin-bottom:20px;border-bottom:1px solid var(--gray-200);">
                    <div class="avatar lg" style="width:56px;height:56px;font-size:1.1rem;background:var(--primary);color:#fff;">
                        ${c.patient_nom.split(' ').map(n => n[0]).join('').toUpperCase().substring(0,2)}
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:1.15rem;font-weight:700;">${c.patient_nom}</div>
                        <div class="text-muted" style="font-size:.85rem;">${c.medecin_nom}</div>
                    </div>
                    <div>${statutBadge[c.statut] || ''}</div>
                </div>

                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-bottom:20px;">
                    <div style="background:var(--gray-50);padding:14px;border-radius:10px;">
                        <div style="font-size:.68rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;margin-bottom:4px;">Date & Heure</div>
                        <div style="font-weight:600;">${c.date} à ${c.heure}</div>
                    </div>
                    <div style="background:var(--gray-50);padding:14px;border-radius:10px;">
                        <div style="font-size:.68rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;margin-bottom:4px;">Statut</div>
                        <div>${statutBadge[c.statut] || c.statut}</div>
                    </div>
                </div>

                <div style="margin-bottom:16px;">
                    <div style="font-size:.72rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;margin-bottom:6px;">Motif</div>
                    <div style="padding:14px;background:var(--gray-50);border-radius:10px;font-size:.9rem;line-height:1.6;">${c.motif}</div>
                </div>
            `;
        })
        .catch(() => {
            document.getElementById('vcBody').innerHTML = '<div class="text-center text-danger" style="padding:40px;">Erreur lors du chargement</div>';
        });
}

function openEditModal(consultationId) {
    fetch(`/reception/consultations/${consultationId}/json`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editConsultForm').action = `/reception/consultations/${consultationId}`;
            setSearchableSelectValue(document.getElementById('edit_patient_id'), data.patient_id, data.patient_nom);
            setSearchableSelectValue(document.getElementById('edit_medecin_id'), data.medecin_id, data.medecin_nom);
            document.getElementById('edit_date').value = data.date;
            document.getElementById('edit_heure').value = data.heure;
            document.getElementById('edit_motif').value = data.motif;
            openModal('modalEditConsult');
        })
        .catch(error => {
            console.error('Erreur lors du chargement:', error);
            alert('Erreur lors du chargement des données de la consultation.');
        });
}

function openDeleteModal(consultationId) {
    document.getElementById('deleteConsultForm').action = `/reception/consultations/${consultationId}`;
    openModal('modalDeleteConsult');
}
</script>
@endsection
