@extends('layouts.medicare')

@section('title', 'Ordonnances - MediCare Pro')
@section('sidebar-subtitle', 'Espace Médecin')
@section('user-color', '#7c3aed')
@section('header-title', 'Ordonnances émises')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('medecin._sidebar')
@endif
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>
            Ordonnances émises
        </h2>
        <span class="text-muted text-sm">{{ $ordonnances->total() }} ordonnances</span>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>N° Retrait</th>
                        <th>Patient</th>
                        <th>Médicaments</th>
                        <th>Recommandations</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ordonnances as $ordonnance)
                    <tr>
                        <td>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                            {{ $ordonnance->date->format('d/m/Y') }}
                        </td>
                        <td><code style="background:var(--gray-100);padding:2px 6px;border-radius:4px;">{{ $ordonnance->numero_retrait }}</code></td>
                        <td>
                            <div class="user-cell">
                                <div class="avatar">{{ strtoupper(substr($ordonnance->patient->prenom, 0, 1) . substr($ordonnance->patient->nom, 0, 1)) }}</div>
                                <span>{{ $ordonnance->patient->prenom }} {{ $ordonnance->patient->nom }}</span>
                            </div>
                        </td>
                        <td>
                            @if($ordonnance->medicaments->isNotEmpty())
                            <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                @foreach($ordonnance->medicaments->take(2) as $med)
                                <span style="font-size:0.75rem;background:var(--primary-light);color:var(--primary);padding:2px 8px;border-radius:20px;">{{ $med->nom }}</span>
                                @endforeach
                                @if($ordonnance->medicaments->count() > 2)
                                <span style="font-size:0.75rem;background:var(--gray-100);color:var(--gray-600);padding:2px 8px;border-radius:20px;">+{{ $ordonnance->medicaments->count() - 2 }}</span>
                                @endif
                            </div>
                            @else
                            <span class="text-muted text-sm">—</span>
                            @endif
                        </td>
                        <td class="truncate" style="max-width:150px;">{{ $ordonnance->recommandations ?? '-' }}</td>
                        <td>
                            @php
                                $statusMap = [
                                    'en_attente' => ['warning', 'En attente'],
                                    'prepare'    => ['info', 'Préparée'],
                                    'remis'      => ['success', 'Remise'],
                                ];
                                $s = $statusMap[$ordonnance->statut_dispensation] ?? ['secondary', $ordonnance->statut_dispensation];
                            @endphp
                            <span class="badge badge-{{ $s[0] }}">{{ $s[1] }}</span>
                        </td>
                        <td>
                            <div style="display:flex;gap:4px;">
                                <button class="btn btn-primary btn-sm" onclick="voirOrdonnance({{ $ordonnance->id }})"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> Voir</button>
                                <a href="{{ route('medecin.ordonnances.pdf', $ordonnance) }}" class="btn btn-outline btn-sm" target="_blank"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg> PDF</a>
                                @if($ordonnance->statut_dispensation === 'en_attente')
                                <button class="btn btn-warning btn-sm" onclick="openEditOrdonnance({{ $ordonnance->id }})">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Modifier
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="openDeleteOrdonnance({{ $ordonnance->id }})">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;margin-right:4px;"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                    Supprimer
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:32px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:8px;"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>
                            <div class="text-muted" style="font-size:.875rem;">Aucune ordonnance émise</div>
                            <div class="text-muted" style="font-size:.75rem;margin-top:4px;">Les ordonnances apparaitront ici après prescription</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($ordonnances->hasPages())
    <div class="card-body" style="border-top:1px solid var(--border);">
        {{ $ordonnances->links() }}
    </div>
    @endif
</div>

<!-- Modal détail ordonnance -->
<div id="modalOrdonnance" class="modal-overlay">
    <div class="modal" style="max-width:560px;">
        <div class="modal-header">
            <h3 class="modal-title">Détail de l'ordonnance</h3>
            <button onclick="closeModal('modalOrdonnance')" class="modal-close">✕</button>
        </div>
        <div class="modal-body" id="modalOrdonnanceContent">
            @foreach($ordonnances as $ordonnance)
            <div id="ord-{{ $ordonnance->id }}" style="display:none;">
                <div style="display:flex;justify-content:space-between;margin-bottom:16px;">
                    <div>
                        <div class="text-muted text-sm">Patient</div>
                        <strong>{{ $ordonnance->patient->prenom }} {{ $ordonnance->patient->nom }}</strong>
                    </div>
                    <div style="text-align:right;">
                        <div class="text-muted text-sm">Date</div>
                        <strong>{{ $ordonnance->date->format('d/m/Y') }}</strong>
                    </div>
                </div>

                <div style="background:var(--gray-50);border-radius:8px;padding:16px;margin-bottom:16px;">
                    <div class="text-muted text-sm mb-2">Médicaments prescrits</div>
                    @foreach($ordonnance->medicaments as $med)
                    <div style="display:grid;grid-template-columns:2fr 1fr 1fr 60px;gap:8px;padding:8px 0;border-bottom:1px solid var(--border);">
                        <div><strong>{{ $med->nom }}</strong></div>
                        <div class="text-sm text-muted">{{ $med->posologie }}</div>
                        <div class="text-sm text-muted">{{ $med->duree }}</div>
                        <div class="text-sm">Qté: {{ $med->quantite }}</div>
                    </div>
                    @endforeach
                </div>

                @if($ordonnance->recommandations)
                <div>
                    <div class="text-muted text-sm mb-1">Recommandations</div>
                    <p>{{ $ordonnance->recommandations }}</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal Modifier Ordonnance -->
<div id="modalEditOrdonnance" class="modal-overlay">
    <div class="modal" style="max-width:620px;">
        <div class="modal-header">
            <h3 class="modal-title">Modifier l'ordonnance</h3>
            <button onclick="closeModal('modalEditOrdonnance')" class="modal-close">&times;</button>
        </div>
        <form id="editOrdonnanceForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div id="editOrdMedsContainer">
                    <!-- Médicaments dynamiques -->
                </div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="addEditMedRow()" style="margin-top:8px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    Ajouter un médicament
                </button>
                <div class="form-group" style="margin-top:16px;">
                    <label class="form-label">Recommandations</label>
                    <textarea class="form-control" name="recommandations" id="edit_ord_recommandations" placeholder="Recommandations..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditOrdonnance')">Annuler</button>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Supprimer Ordonnance -->
<div id="modalDeleteOrdonnance" class="modal-overlay">
    <div class="modal" style="max-width:450px;">
        <div class="modal-header">
            <h3 class="modal-title" style="color:#dc2626;">Supprimer l'ordonnance</h3>
            <button onclick="closeModal('modalDeleteOrdonnance')" class="modal-close">&times;</button>
        </div>
        <form id="deleteOrdonnanceForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body" style="text-align:center;padding:24px;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="1.5" style="margin-bottom:12px;">
                    <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
                </svg>
                <p style="font-size:.95rem;color:var(--gray-700);margin:0;">
                    Êtes-vous sûr de vouloir supprimer cette ordonnance ?
                </p>
                <p style="font-size:.8rem;color:var(--gray-400);margin-top:8px;">
                    Cette action est irréversible.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalDeleteOrdonnance')">Annuler</button>
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let currentOrdId = null;
function voirOrdonnance(id) {
    if (currentOrdId) document.getElementById('ord-' + currentOrdId).style.display = 'none';
    document.getElementById('ord-' + id).style.display = 'block';
    currentOrdId = id;
    openModal('modalOrdonnance');
}

let editMedIndex = 0;

function addEditMedRow(data = null) {
    const container = document.getElementById('editOrdMedsContainer');
    const idx = editMedIndex++;
    const nom = data ? data.nom : '';
    const posologie = data ? data.posologie : '';
    const duree = data ? data.duree : '';
    const quantite = data ? data.quantite : 1;

    const row = document.createElement('div');
    row.className = 'edit-med-row';
    row.style.cssText = 'display:grid;grid-template-columns:2fr 1fr 1fr 60px 30px;gap:8px;align-items:end;margin-bottom:8px;';
    row.innerHTML = `
        <div class="form-group" style="margin-bottom:0;">
            ${idx === 0 ? '<label class="form-label">Médicament</label>' : ''}
            <input type="text" class="form-control" name="medicaments[${idx}][nom]" value="${nom}" placeholder="Nom du médicament" required>
        </div>
        <div class="form-group" style="margin-bottom:0;">
            ${idx === 0 ? '<label class="form-label">Posologie</label>' : ''}
            <input type="text" class="form-control" name="medicaments[${idx}][posologie]" value="${posologie}" placeholder="Ex: 2x/jour" required>
        </div>
        <div class="form-group" style="margin-bottom:0;">
            ${idx === 0 ? '<label class="form-label">Durée</label>' : ''}
            <input type="text" class="form-control" name="medicaments[${idx}][duree]" value="${duree}" placeholder="Ex: 7 jours" required>
        </div>
        <div class="form-group" style="margin-bottom:0;">
            ${idx === 0 ? '<label class="form-label">Qté</label>' : ''}
            <input type="number" class="form-control" name="medicaments[${idx}][quantite]" value="${quantite}" min="1" required>
        </div>
        <div style="padding-bottom:2px;">
            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.edit-med-row').remove()" style="padding:4px 8px;">&times;</button>
        </div>
    `;
    container.appendChild(row);
}

function openEditOrdonnance(ordonnanceId) {
    editMedIndex = 0;
    document.getElementById('editOrdMedsContainer').innerHTML = '';

    fetch(`/medecin/ordonnances/${ordonnanceId}/json`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editOrdonnanceForm').action = `/medecin/ordonnances/${ordonnanceId}`;
            document.getElementById('edit_ord_recommandations').value = data.recommandations || '';

            data.medicaments.forEach(med => {
                addEditMedRow(med);
            });

            openModal('modalEditOrdonnance');
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement des données de l\'ordonnance.');
        });
}

function openDeleteOrdonnance(ordonnanceId) {
    document.getElementById('deleteOrdonnanceForm').action = `/medecin/ordonnances/${ordonnanceId}`;
    openModal('modalDeleteOrdonnance');
}
</script>
@endpush

@endsection
