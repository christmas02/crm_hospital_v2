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
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table id="demandesTable">
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
                        <td>{{ \Carbon\Carbon::parse($ordonnance->date)->format('d/m/Y') }}</td>
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
                            <button class="btn btn-outline btn-sm">Détail</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted">Aucune demande</td></tr>
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

<script>
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
