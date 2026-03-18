@extends('layouts.medicare')

@section('title', 'Demandes de dispensation - Pharmacie')
@section('sidebar-subtitle', 'Pharmacie')
@section('user-color', '#dc2626')
@section('header-title', 'Demandes de dispensation')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('pharmacie._sidebar')
@endif
@endsection

@section('content')
<div class="card mb-4" style="background:var(--info-light);border:none;">
    <div class="card-body">
        <p style="margin:0;color:var(--info);"><strong>Note :</strong> Les demandes de dispensation sont générées automatiquement par le médecin lors de la prescription. La pharmacie prépare les médicaments puis les remet à l'infirmier qui vient les récupérer.</p>
    </div>
</div>

<div class="toolbar">
    <select class="filter-select" id="filterStatut" onchange="filterDemandes()">
        <option value="">Toutes les demandes</option>
        <option value="en_attente">En attente</option>
        <option value="prepare">Préparées</option>
        <option value="remis">Remises</option>
    </select>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
            Demandes de dispensation
        </h2>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients" id="demandesTable">
                <thead>
                    <tr>
                        <th>N° Retrait</th>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Médecin</th>
                        <th>Médicaments</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ordonnances as $ordonnance)
                    <tr data-statut="{{ $ordonnance->statut_dispensation }}">
                        <td><strong>RET-{{ str_pad($ordonnance->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
                        <td>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2" style="vertical-align:middle;margin-right:4px;"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                            {{ \Carbon\Carbon::parse($ordonnance->date)->format('d/m/Y') }}
                        </td>
                        <td>
                            <div class="user-cell">
                                <div class="avatar">{{ strtoupper(substr($ordonnance->patient->prenom ?? '', 0, 1) . substr($ordonnance->patient->nom ?? '', 0, 1)) }}</div>
                                <span>{{ $ordonnance->patient->prenom ?? '' }} {{ $ordonnance->patient->nom ?? '' }}</span>
                            </div>
                        </td>
                        <td>Dr. {{ $ordonnance->medecin->nom ?? '' }}</td>
                        <td>
                            <div class="flex flex-wrap gap-1">
                                @foreach($ordonnance->medicaments->take(2) as $medicament)
                                <span class="badge badge-light">{{ $medicament->nom }} x{{ $medicament->quantite ?? 1 }}</span>
                                @endforeach
                                @if($ordonnance->medicaments->count() > 2)
                                <span class="badge badge-light">+{{ $ordonnance->medicaments->count() - 2 }}</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($ordonnance->statut_dispensation == 'en_attente')
                            <span class="badge badge-warning">En attente</span>
                            @elseif($ordonnance->statut_dispensation == 'prepare')
                            <span class="badge badge-info">Préparé</span>
                            @else
                            <span class="badge badge-success">Remis</span>
                            @endif
                        </td>
                        <td>
                            @if($ordonnance->statut_dispensation == 'en_attente')
                            <form action="{{ route('pharmacie.ordonnances.preparer', $ordonnance) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">Préparer</button>
                            </form>
                            @elseif($ordonnance->statut_dispensation == 'prepare')
                            <button class="btn btn-success btn-sm" onclick="openRemise({{ $ordonnance->id }})">Remettre</button>
                            @else
                            <button class="btn btn-primary btn-sm" onclick="voirOrdonnance({{ $ordonnance->id }})">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> Voir
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:32px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:8px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
                            <div class="text-muted" style="font-size:.875rem;">Aucune demande de dispensation</div>
                            <div class="text-muted" style="font-size:.75rem;margin-top:4px;">Les demandes apparaissent ici lorsqu'un médecin prescrit des médicaments</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Remise -->
<div class="modal-overlay" id="modalRemise">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Confirmer la remise</h3>
            <button class="modal-close" onclick="closeModal('modalRemise')">&times;</button>
        </div>
        <form id="formRemise" method="POST">
            @csrf
            <div class="modal-body">
                <p style="margin-bottom:16px;">Confirmez la remise des médicaments à l'infirmier.</p>
                <div class="form-group">
                    <label class="form-label">Remis à (nom de l'infirmier) *</label>
                    <input type="text" class="form-control" name="remis_a" required placeholder="Ex: Infirmier Konan">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalRemise')">Annuler</button>
                <button type="submit" class="btn btn-success">Confirmer remise</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Détail Ordonnance -->
<div class="modal-overlay" id="modalOrdonnance">
    <div class="modal" style="max-width:560px;">
        <div class="modal-header">
            <h3 class="modal-title">Détail de l'ordonnance</h3>
            <button class="modal-close" onclick="closeModal('modalOrdonnance')">&times;</button>
        </div>
        <div class="modal-body" id="modalOrdonnanceContent">
            @foreach($ordonnances as $ordonnance)
            <div id="ord-{{ $ordonnance->id }}" style="display:none;">
                <div style="display:flex;justify-content:space-between;margin-bottom:16px;">
                    <div>
                        <div class="text-muted text-sm">Patient</div>
                        <strong>{{ $ordonnance->patient->prenom ?? '' }} {{ $ordonnance->patient->nom ?? '' }}</strong>
                    </div>
                    <div style="text-align:right;">
                        <div class="text-muted text-sm">Date</div>
                        <strong>{{ \Carbon\Carbon::parse($ordonnance->date)->format('d/m/Y') }}</strong>
                    </div>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:16px;">
                    <div>
                        <div class="text-muted text-sm">Médecin</div>
                        <strong>Dr. {{ $ordonnance->medecin->nom ?? '' }}</strong>
                    </div>
                    <div style="text-align:right;">
                        <div class="text-muted text-sm">Statut</div>
                        @if($ordonnance->statut_dispensation == 'en_attente')
                        <span class="badge badge-warning">En attente</span>
                        @elseif($ordonnance->statut_dispensation == 'prepare')
                        <span class="badge badge-info">Préparé</span>
                        @else
                        <span class="badge badge-success">Remis</span>
                        @endif
                    </div>
                </div>

                <div style="background:var(--gray-50);border-radius:8px;padding:16px;margin-bottom:16px;">
                    <div class="text-muted text-sm mb-2">Médicaments prescrits</div>
                    @foreach($ordonnance->medicaments as $med)
                    <div style="display:grid;grid-template-columns:2fr 1fr 1fr 60px;gap:8px;padding:8px 0;border-bottom:1px solid var(--border);">
                        <div><strong>{{ $med->nom }}</strong></div>
                        <div class="text-sm text-muted">{{ $med->posologie ?? '-' }}</div>
                        <div class="text-sm text-muted">{{ $med->duree ?? '-' }}</div>
                        <div class="text-sm">Qté: {{ $med->quantite ?? 1 }}</div>
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

<script>
let currentOrdId = null;
function voirOrdonnance(id) {
    if (currentOrdId) document.getElementById('ord-' + currentOrdId).style.display = 'none';
    document.getElementById('ord-' + id).style.display = 'block';
    currentOrdId = id;
    openModal('modalOrdonnance');
}

function filterDemandes() {
    const statut = document.getElementById('filterStatut').value;
    const rows = document.querySelectorAll('#demandesTable tbody tr');

    rows.forEach(row => {
        const rowStatut = row.dataset.statut || '';
        row.style.display = !statut || rowStatut === statut ? '' : 'none';
    });
}

function openRemise(ordonnanceId) {
    document.getElementById('formRemise').action = '/pharmacie/ordonnances/' + ordonnanceId + '/remettre';
    openModal('modalRemise');
}
</script>
@endsection
