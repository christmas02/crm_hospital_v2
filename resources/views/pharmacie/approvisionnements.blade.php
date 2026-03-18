@extends('layouts.medicare')

@section('title', 'Approvisionnements - Pharmacie')
@section('sidebar-subtitle', 'Pharmacie')
@section('user-color', '#dc2626')
@section('header-title', 'Approvisionnements')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('pharmacie._sidebar')
@endif
@endsection

@section('content')
<div class="toolbar">
    <div class="filters">
        <input type="text" class="filter-input" placeholder="Rechercher fournisseur...">
    </div>
    <button class="btn btn-success" onclick="openModal('modalFicheAppro')">+ Nouvelle fiche</button>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Historique des approvisionnements</h2>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>N° Fiche</th>
                        <th>Date</th>
                        <th>Fournisseur</th>
                        <th>Articles</th>
                        <th>Qté totale</th>
                        <th>Montant</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($approvisionnements as $appro)
                    <tr>
                        <td><strong>{{ $appro->numero }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($appro->date)->format('d/m/Y') }}</td>
                        <td>{{ $appro->fournisseur }}</td>
                        <td>{{ $appro->lignes->count() }}</td>
                        <td>{{ $appro->lignes->sum('quantite') }}</td>
                        <td><strong>{{ number_format($appro->lignes->sum(function($l) { return $l->quantite * $l->prix_unitaire; }), 0, ',', ' ') }} F</strong></td>
                        <td>
                            <button class="btn btn-outline btn-sm">Détail</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted">Aucun approvisionnement</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Fiche Approvisionnement -->
<div class="modal-overlay" id="modalFicheAppro">
    <div class="modal" style="max-width:800px;">
        <div class="modal-header" style="background:var(--success-light);">
            <h3 class="modal-title">Fiche d'approvisionnement</h3>
            <button class="modal-close" onclick="closeModal('modalFicheAppro')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-row mb-4">
                <div class="form-group" style="flex:1;">
                    <label class="form-label">N° Fiche</label>
                    <input type="text" class="form-control" id="approNumero" value="APP-{{ str_pad(($approvisionnements->count() ?? 0) + 1, 4, '0', STR_PAD_LEFT) }}" readonly style="background:#f1f5f9;font-weight:bold;">
                </div>
                <div class="form-group" style="flex:1;">
                    <label class="form-label">Date</label>
                    <input type="date" class="form-control" id="approDate" value="{{ date('Y-m-d') }}">
                </div>
            </div>
            <div class="form-group mb-4">
                <label class="form-label">Fournisseur / Laboratoire *</label>
                <input type="text" class="form-control" id="approFournisseur" placeholder="Ex: Pharma CI, Sanofi, Novartis..." required list="fournisseursList">
                <datalist id="fournisseursList">
                    <option value="Pharma CI">
                    <option value="MedAfrique">
                    <option value="Sanofi">
                    <option value="Novartis">
                    <option value="Bayer">
                    <option value="B.Braun">
                    <option value="Novo Nordisk">
                </datalist>
            </div>

            <div class="card mb-4" style="border:2px dashed var(--border);">
                <div class="card-header" style="background:#f8fafc;">
                    <h4 class="card-title" style="font-size:0.95rem;">Ajouter un médicament</h4>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group" style="flex:3;">
                            <label class="form-label">Médicament</label>
                            <select class="form-control" id="approSelectMedic">
                                <option value="">Sélectionner</option>
                                @foreach($medicaments as $med)
                                <option value="{{ $med->id }}" data-nom="{{ $med->nom }}" data-prix="{{ $med->prix_unitaire }}">{{ $med->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label class="form-label">Quantité</label>
                            <input type="number" class="form-control" id="approQte" min="1" value="1">
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label class="form-label">Prix unit.</label>
                            <input type="number" class="form-control" id="approPrix" min="0">
                        </div>
                        <div class="form-group" style="flex:0;align-self:flex-end;">
                            <button type="button" class="btn btn-primary" onclick="ajouterLigneAppro()">Ajouter</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" style="font-size:0.95rem;">Médicaments à approvisionner</h4>
                </div>
                <div class="card-body no-pad">
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Médicament</th>
                                    <th style="text-align:center;">Quantité</th>
                                    <th style="text-align:right;">Prix unit.</th>
                                    <th style="text-align:right;">Total</th>
                                    <th style="width:60px;"></th>
                                </tr>
                            </thead>
                            <tbody id="approLignesTable">
                                <tr id="emptyRow"><td colspan="5" class="text-center text-muted">Aucun médicament ajouté</td></tr>
                            </tbody>
                            <tfoot style="background:#f1f5f9;">
                                <tr>
                                    <td colspan="3" style="text-align:right;font-weight:bold;">Total articles:</td>
                                    <td style="text-align:right;font-weight:bold;" id="approTotalArticles">0</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align:right;font-weight:bold;">Quantité totale:</td>
                                    <td style="text-align:right;font-weight:bold;" id="approTotalQte">0</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align:right;font-weight:bold;">Montant total:</td>
                                    <td style="text-align:right;font-weight:bold;color:var(--success);" id="approMontantTotal">0 FCFA</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="form-group mt-4">
                <label class="form-label">Observations</label>
                <textarea class="form-control" id="approObservations" rows="2" placeholder="Notes sur la livraison..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('modalFicheAppro')">Annuler</button>
            <button class="btn btn-success" onclick="saveFicheAppro()">Valider l'approvisionnement</button>
        </div>
    </div>
</div>

<script>
let approLignes = [];

function ajouterLigneAppro() {
    const select = document.getElementById('approSelectMedic');
    const option = select.options[select.selectedIndex];
    const qte = parseInt(document.getElementById('approQte').value) || 0;
    const prix = parseInt(document.getElementById('approPrix').value) || 0;

    if (!option.value || qte <= 0) {
        alert('Veuillez sélectionner un médicament et une quantité');
        return;
    }

    approLignes.push({
        id: option.value,
        nom: option.dataset.nom,
        quantite: qte,
        prix: prix
    });

    renderLignes();
    select.value = '';
    document.getElementById('approQte').value = 1;
    document.getElementById('approPrix').value = '';
}

function renderLignes() {
    const tbody = document.getElementById('approLignesTable');
    const emptyRow = document.getElementById('emptyRow');

    if (approLignes.length === 0) {
        emptyRow.style.display = '';
        return;
    }

    emptyRow.style.display = 'none';

    // Remove all rows except empty
    tbody.querySelectorAll('tr:not(#emptyRow)').forEach(r => r.remove());

    let totalArticles = approLignes.length;
    let totalQte = 0;
    let totalMontant = 0;

    approLignes.forEach((l, i) => {
        const total = l.quantite * l.prix;
        totalQte += l.quantite;
        totalMontant += total;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${l.nom}</td>
            <td style="text-align:center;">${l.quantite}</td>
            <td style="text-align:right;">${l.prix.toLocaleString()} F</td>
            <td style="text-align:right;">${total.toLocaleString()} F</td>
            <td><button class="btn btn-outline btn-sm" onclick="removeLigne(${i})" style="color:var(--danger);">X</button></td>
        `;
        tbody.appendChild(tr);
    });

    document.getElementById('approTotalArticles').textContent = totalArticles;
    document.getElementById('approTotalQte').textContent = totalQte;
    document.getElementById('approMontantTotal').textContent = totalMontant.toLocaleString() + ' FCFA';
}

function removeLigne(index) {
    approLignes.splice(index, 1);
    renderLignes();
}

function saveFicheAppro() {
    alert('Fonctionnalité à implémenter côté serveur');
    closeModal('modalFicheAppro');
}

// Auto-fill price when selecting medication
document.getElementById('approSelectMedic').addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    if (option.dataset.prix) {
        document.getElementById('approPrix').value = option.dataset.prix;
    }
});
</script>
@endsection
