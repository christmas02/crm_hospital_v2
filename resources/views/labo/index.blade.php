@extends('layouts.medicare')

@section('title', 'Laboratoire - MediCare Pro')
@section('sidebar-subtitle', 'Laboratoire')
@section('user-color', '#7c3aed')
@section('header-title', 'Laboratoire d\'analyses')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    <div class="nav-group">
        <div class="nav-label">Laboratoire</div>
        <a href="{{ route('labo.index') }}" class="nav-item {{ request()->routeIs('labo.index') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3h6v7l4 9H5l4-9V3z"/><path d="M8 3h8"/></svg>
            Tableau de bord
        </a>
        <a href="{{ route('labo.examens') }}" class="nav-item {{ request()->routeIs('labo.examens') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
            Catalogue examens
        </a>
    </div>
@endif
@endsection

@section('content')

<!-- Stats -->
<div class="stats" style="grid-template-columns:repeat(4,1fr);">
    <div class="stat-card" style="border-left: 4px solid var(--warning);background:linear-gradient(135deg,#fef3c7,#fde68a);border-right:none;border-top:none;border-bottom:none;">
        <div>
            <div class="stat-label">En attente</div>
            <div class="stat-value text-warning">{{ $stats['en_attente'] }}</div>
            <div class="stat-sub">Demandes non traitees</div>
        </div>
        <div class="stat-icon orange">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--primary);">
        <div>
            <div class="stat-label">En cours</div>
            <div class="stat-value">{{ $stats['en_cours'] }}</div>
            <div class="stat-sub">Analyses en traitement</div>
        </div>
        <div class="stat-icon cyan">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3h6v7l4 9H5l4-9V3z"/><path d="M8 3h8"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--secondary);background:linear-gradient(135deg,#dcfce7,#bbf7d0);border-right:none;border-top:none;border-bottom:none;">
        <div>
            <div class="stat-label">Terminees aujourd'hui</div>
            <div class="stat-value text-success">{{ $stats['terminees_jour'] }}</div>
            <div class="stat-sub">Resultats disponibles</div>
        </div>
        <div class="stat-icon green">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--danger);background:linear-gradient(135deg,#fee2e2,#fecaca);border-right:none;border-top:none;border-bottom:none;">
        <div>
            <div class="stat-label">Urgentes</div>
            <div class="stat-value text-danger">{{ $stats['urgentes'] }}</div>
            <div class="stat-sub">Demandes prioritaires</div>
        </div>
        <div class="stat-icon red">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
        </div>
    </div>
</div>

<!-- Toolbar -->
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <h2 style="font-size:1.1rem;font-weight:700;color:var(--gray-800);">Demandes en cours</h2>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('labo.examens') }}" class="btn btn-outline btn-sm">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
            Catalogue
        </a>
        <button class="btn btn-primary btn-sm" onclick="openModal('modalNouvelleDemande')">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Nouvelle Demande
        </button>
    </div>
</div>

<!-- Table demandes en attente -->
<div class="card" style="margin-bottom:24px;">
    <div class="table-responsive">
        <table class="table-patients">
            <thead>
                <tr>
                    <th>Numero</th>
                    <th>Patient</th>
                    <th>Medecin</th>
                    <th>Date</th>
                    <th>Examens</th>
                    <th>Urgence</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($demandesEnAttente as $demande)
                <tr>
                    <td><strong>{{ $demande->numero }}</strong></td>
                    <td>{{ $demande->patient->prenom }} {{ $demande->patient->nom }}</td>
                    <td>Dr. {{ $demande->medecin->prenom }} {{ $demande->medecin->nom }}</td>
                    <td>{{ $demande->date_demande->format('d/m/Y') }}</td>
                    <td>{{ $demande->resultats->count() }} examen(s)</td>
                    <td>
                        @if($demande->urgence === 'tres_urgent')
                            <span class="badge" style="background:#fee2e2;color:#dc2626;">Tres urgent</span>
                        @elseif($demande->urgence === 'urgent')
                            <span class="badge" style="background:#fef3c7;color:#d97706;">Urgent</span>
                        @else
                            <span class="badge" style="background:#dcfce7;color:#16a34a;">Normal</span>
                        @endif
                    </td>
                    <td>
                        @if($demande->statut === 'en_attente')
                            <span class="badge" style="background:#fef3c7;color:#d97706;">En attente</span>
                        @elseif($demande->statut === 'preleve')
                            <span class="badge" style="background:#dbeafe;color:#2563eb;">Preleve</span>
                        @elseif($demande->statut === 'en_cours')
                            <span class="badge" style="background:#e0e7ff;color:#4f46e5;">En cours</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <button class="btn btn-sm btn-primary" onclick="ouvrirSaisieResultats({{ $demande->id }})" title="Saisir resultats">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <form method="POST" action="{{ route('labo.demandes.statut', $demande) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                @if($demande->statut === 'en_attente')
                                    <input type="hidden" name="statut" value="preleve">
                                    <button type="submit" class="btn btn-sm btn-outline" title="Marquer preleve">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                                    </button>
                                @elseif($demande->statut === 'preleve')
                                    <input type="hidden" name="statut" value="en_cours">
                                    <button type="submit" class="btn btn-sm btn-outline" title="Marquer en cours">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3h6v7l4 9H5l4-9V3z"/></svg>
                                    </button>
                                @endif
                            </form>
                            <button class="btn btn-sm btn-outline" onclick="voirDemande({{ $demande->id }})" title="Voir details">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:40px;color:var(--gray-400);">Aucune demande en cours</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Demandes terminees -->
<div style="margin-bottom:14px;">
    <h2 style="font-size:1.1rem;font-weight:700;color:var(--gray-800);">Resultats recents</h2>
</div>
<div class="card">
    <div class="table-responsive">
        <table class="table-patients">
            <thead>
                <tr>
                    <th>Numero</th>
                    <th>Patient</th>
                    <th>Medecin</th>
                    <th>Date demande</th>
                    <th>Date resultat</th>
                    <th>Examens</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($demandesTerminees as $demande)
                <tr>
                    <td><strong>{{ $demande->numero }}</strong></td>
                    <td>{{ $demande->patient->prenom }} {{ $demande->patient->nom }}</td>
                    <td>Dr. {{ $demande->medecin->prenom }} {{ $demande->medecin->nom }}</td>
                    <td>{{ $demande->date_demande->format('d/m/Y') }}</td>
                    <td>{{ $demande->date_resultat?->format('d/m/Y') }}</td>
                    <td>{{ $demande->resultats->count() }} examen(s)</td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <button class="btn btn-sm btn-outline" onclick="voirDemande({{ $demande->id }})" title="Voir">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                            <a href="{{ route('labo.demandes.pdf', $demande) }}" target="_blank" class="btn btn-sm btn-outline" title="PDF">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/><path d="M12 18v-6M9 15l3 3 3-3"/></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:40px;color:var(--gray-400);">Aucun resultat recent</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Nouvelle Demande -->
<div class="modal-overlay" id="modalNouvelleDemande">
    <div class="modal" style="max-width:700px;">
        <div class="modal-header">
            <h3>Nouvelle demande d'analyses</h3>
            <button onclick="closeModal('modalNouvelleDemande')" class="modal-close">&times;</button>
        </div>
        <form method="POST" action="{{ route('labo.demandes.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-grid" style="grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Patient *</label>
                        <select name="patient_id" class="form-control" required>
                            <option value="">-- Selectionner un patient --</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->prenom }} {{ $patient->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Medecin prescripteur *</label>
                        <select name="medecin_id" class="form-control" required>
                            <option value="">-- Selectionner un medecin --</option>
                            @foreach($medecins as $medecin)
                                <option value="{{ $medecin->id }}">Dr. {{ $medecin->prenom }} {{ $medecin->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Urgence *</label>
                        <select name="urgence" class="form-control" required>
                            <option value="normal">Normal</option>
                            <option value="urgent">Urgent</option>
                            <option value="tres_urgent">Tres urgent</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" style="margin-top:16px;">
                    <label class="form-label">Notes cliniques</label>
                    <textarea name="notes_cliniques" class="form-control" rows="2" placeholder="Contexte clinique, suspicion diagnostique..."></textarea>
                </div>
                <div class="form-group" style="margin-top:16px;">
                    <label class="form-label">Examens demandes *</label>
                    @php $grouped = $examens->groupBy('categorie'); @endphp
                    @foreach($grouped as $categorie => $items)
                        <div style="margin-bottom:12px;">
                            <div style="font-weight:600;font-size:0.85rem;color:var(--primary);margin-bottom:6px;padding-bottom:4px;border-bottom:1px solid var(--gray-200);">{{ $categorie }}</div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:4px 16px;">
                                @foreach($items as $examen)
                                    <label style="display:flex;align-items:center;gap:8px;font-size:0.85rem;cursor:pointer;padding:4px 0;">
                                        <input type="checkbox" name="examens[]" value="{{ $examen->id }}">
                                        {{ $examen->nom }}
                                        <span style="color:var(--gray-400);font-size:0.75rem;">{{ number_format($examen->prix, 0, ',', ' ') }} F</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalNouvelleDemande')">Annuler</button>
                <button type="submit" class="btn btn-primary">Creer la demande</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Saisie Resultats -->
<div class="modal-overlay" id="modalSaisieResultats">
    <div class="modal" style="max-width:800px;">
        <div class="modal-header">
            <h3>Saisie des resultats - <span id="saisieNumero"></span></h3>
            <button onclick="closeModal('modalSaisieResultats')" class="modal-close">&times;</button>
        </div>
        <form method="POST" id="formSaisieResultats">
            @csrf
            <div class="modal-body">
                <div id="saisiePatientInfo" style="background:var(--gray-50);padding:12px;border-radius:8px;margin-bottom:16px;font-size:0.85rem;"></div>
                <div id="saisieResultatsContainer"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalSaisieResultats')">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer les resultats</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Voir Demande -->
<div class="modal-overlay" id="modalVoirDemande">
    <div class="modal" style="max-width:700px;">
        <div class="modal-header">
            <h3>Details de la demande</h3>
            <button onclick="closeModal('modalVoirDemande')" class="modal-close">&times;</button>
        </div>
        <div class="modal-body" id="voirDemandeContent">
            <div style="text-align:center;padding:30px;color:var(--gray-400);">Chargement...</div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeModal('modalVoirDemande')">Fermer</button>
            <a href="#" id="voirDemandePdfLink" target="_blank" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                Imprimer PDF
            </a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function ouvrirSaisieResultats(demandeId) {
    fetch('/labo/demandes/' + demandeId + '/json')
        .then(r => r.json())
        .then(data => {
            document.getElementById('saisieNumero').textContent = data.numero;
            document.getElementById('formSaisieResultats').action = '/labo/demandes/' + demandeId + '/resultats';
            document.getElementById('saisiePatientInfo').innerHTML =
                '<strong>Patient:</strong> ' + data.patient + ' | <strong>Medecin:</strong> ' + data.medecin + ' | <strong>Date:</strong> ' + data.date_demande +
                (data.notes_cliniques ? '<br><strong>Notes:</strong> ' + data.notes_cliniques : '');

            var html = '<table class="table-patients"><thead><tr><th>Examen</th><th>Valeur</th><th>Reference</th><th>Interpretation</th><th>Commentaire</th></tr></thead><tbody>';
            data.resultats.forEach(function(r, i) {
                html += '<tr>' +
                    '<td><strong>' + r.examen + '</strong><br><small style="color:var(--gray-400);">' + r.categorie + '</small></td>' +
                    '<td><input type="hidden" name="resultats[' + i + '][id]" value="' + r.id + '"><input type="text" name="resultats[' + i + '][valeur]" value="' + (r.valeur || '') + '" class="form-control" style="width:100px;" placeholder="Valeur">' + (r.unite ? ' <small>' + r.unite + '</small>' : '') + '</td>' +
                    '<td style="font-size:0.8rem;color:var(--gray-500);">' + (r.valeur_reference || '-') + '</td>' +
                    '<td><select name="resultats[' + i + '][interpretation]" class="form-control" style="width:110px;">' +
                    '<option value="">--</option>' +
                    '<option value="normal"' + (r.interpretation === 'normal' ? ' selected' : '') + '>Normal</option>' +
                    '<option value="bas"' + (r.interpretation === 'bas' ? ' selected' : '') + '>Bas</option>' +
                    '<option value="eleve"' + (r.interpretation === 'eleve' ? ' selected' : '') + '>Eleve</option>' +
                    '<option value="critique"' + (r.interpretation === 'critique' ? ' selected' : '') + '>Critique</option>' +
                    '</select></td>' +
                    '<td><input type="text" name="resultats[' + i + '][commentaire]" value="' + (r.commentaire || '') + '" class="form-control" style="width:140px;" placeholder="Commentaire"></td>' +
                    '</tr>';
            });
            html += '</tbody></table>';
            document.getElementById('saisieResultatsContainer').innerHTML = html;
            openModal('modalSaisieResultats');
        });
}

function voirDemande(demandeId) {
    document.getElementById('voirDemandeContent').innerHTML = '<div style="text-align:center;padding:30px;color:var(--gray-400);">Chargement...</div>';
    document.getElementById('voirDemandePdfLink').href = '/labo/demandes/' + demandeId + '/pdf';
    openModal('modalVoirDemande');

    fetch('/labo/demandes/' + demandeId + '/json')
        .then(r => r.json())
        .then(data => {
            var interpretColors = { normal: '#16a34a', bas: '#2563eb', eleve: '#d97706', critique: '#dc2626' };
            var interpretLabels = { normal: 'Normal', bas: 'Bas', eleve: 'Eleve', critique: 'Critique' };

            var html = '<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;">' +
                '<div><strong>Numero:</strong> ' + data.numero + '</div>' +
                '<div><strong>Statut:</strong> ' + data.statut + '</div>' +
                '<div><strong>Patient:</strong> ' + data.patient + '</div>' +
                '<div><strong>Medecin:</strong> ' + data.medecin + '</div>' +
                '<div><strong>Date demande:</strong> ' + data.date_demande + '</div>' +
                '<div><strong>Date resultat:</strong> ' + (data.date_resultat || '-') + '</div>' +
                '</div>';

            if (data.notes_cliniques) {
                html += '<div style="background:var(--gray-50);padding:10px;border-radius:8px;margin-bottom:16px;font-size:0.85rem;"><strong>Notes cliniques:</strong> ' + data.notes_cliniques + '</div>';
            }

            html += '<table class="table-patients"><thead><tr><th>Examen</th><th>Resultat</th><th>Unite</th><th>Reference</th><th>Interpretation</th></tr></thead><tbody>';
            data.resultats.forEach(function(r) {
                var interpBadge = r.interpretation ? '<span style="background:' + (interpretColors[r.interpretation] || '#666') + '15;color:' + (interpretColors[r.interpretation] || '#666') + ';padding:2px 8px;border-radius:4px;font-size:0.75rem;">' + (interpretLabels[r.interpretation] || r.interpretation) + '</span>' : '-';
                html += '<tr><td><strong>' + r.examen + '</strong></td><td>' + (r.valeur || '-') + '</td><td>' + (r.unite || '-') + '</td><td style="font-size:0.8rem;color:var(--gray-500);">' + (r.valeur_reference || '-') + '</td><td>' + interpBadge + '</td></tr>';
            });
            html += '</tbody></table>';

            if (data.realise_par) {
                html += '<div style="margin-top:12px;font-size:0.82rem;color:var(--gray-500);">Realise par: ' + data.realise_par + '</div>';
            }

            document.getElementById('voirDemandeContent').innerHTML = html;
        });
}
</script>
@endpush
