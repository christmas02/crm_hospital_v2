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
        <input type="text" class="filter-input" id="searchFournisseur" placeholder="Rechercher fournisseur..." onkeyup="filterTable()">
    </div>
    <button class="btn btn-success" onclick="openModal('modalFicheAppro')">+ Nouvelle Commande</button>
</div>

@if(session('success'))
<div class="alert alert-success" style="background:#dcfce7;border:1px solid #bbf7d0;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:16px;">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger" style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:16px;">
    {{ session('error') }}
</div>
@endif

<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><path d="M3.27 6.96L12 12.01l8.73-5.05M12 22.08V12"/></svg>
            Historique des approvisionnements
        </h2>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients" id="tableAppro">
                <thead>
                    <tr>
                        <th>N Fiche</th>
                        <th>Date</th>
                        <th>Fournisseur</th>
                        <th>Articles</th>
                        <th>Qte totale</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($approvisionnements as $appro)
                    <tr>
                        <td><strong>{{ $appro->numero }}</strong></td>
                        <td>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2" style="vertical-align:middle;margin-right:4px;"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                            {{ \Carbon\Carbon::parse($appro->date)->format('d/m/Y') }}
                        </td>
                        <td>{{ $appro->fournisseur }}</td>
                        <td>{{ $appro->lignes->count() }}</td>
                        <td>{{ $appro->lignes->sum('quantite') }}</td>
                        <td>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2" style="vertical-align:middle;margin-right:4px;"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                            <strong>{{ number_format($appro->lignes->sum(function($l) { return $l->quantite * $l->prix_unitaire; }), 0, ',', ' ') }} F</strong>
                        </td>
                        <td>
                            @if($appro->statut === 'validee')
                                <span style="display:inline-flex;align-items:center;gap:4px;background:#dcfce7;color:#166534;padding:3px 10px;border-radius:12px;font-size:.8rem;font-weight:600;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
                                    Validee
                                </span>
                            @else
                                <span style="display:inline-flex;align-items:center;gap:4px;background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:12px;font-size:.8rem;font-weight:600;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                    En attente
                                </span>
                            @endif
                        </td>
                        <td style="white-space:nowrap;">
                            <!-- Voir -->
                            <button class="btn btn-primary btn-sm" onclick="voirAppro({{ $appro->id }})" title="Voir les details">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>

                            @if($appro->statut === 'en_attente')
                            <!-- Valider -->
                            <form action="{{ route('pharmacie.approvisionnements.valider', $appro) }}" method="POST" style="display:inline;" onsubmit="return confirm('Valider cette commande ? Le stock sera mis a jour.')">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" title="Valider et mettre a jour le stock">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
                                </button>
                            </form>

                            <!-- Supprimer -->
                            <form action="{{ route('pharmacie.approvisionnements.destroy', $appro) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette commande ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca;" title="Supprimer">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:32px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:8px;"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><path d="M3.27 6.96L12 12.01l8.73-5.05M12 22.08V12"/></svg>
                            <div class="text-muted" style="font-size:.875rem;">Aucun approvisionnement enregistre</div>
                            <div class="text-muted" style="font-size:.75rem;margin-top:4px;">Creez une nouvelle fiche pour enregistrer une livraison</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nouvelle Commande -->
<div class="modal-overlay" id="modalFicheAppro">
    <div class="modal" style="max-width:800px;">
        <div class="modal-header" style="background:var(--success-light);">
            <h3 class="modal-title">Nouvelle commande d'approvisionnement</h3>
            <button class="modal-close" onclick="closeModal('modalFicheAppro')">&times;</button>
        </div>
        <form id="formAppro" action="{{ route('pharmacie.approvisionnements.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-row mb-4">
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">N Fiche</label>
                        <input type="text" class="form-control" value="APP-{{ str_pad(($approvisionnements->count() ?? 0) + 1, 4, '0', STR_PAD_LEFT) }}" readonly style="background:#f1f5f9;font-weight:bold;">
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">Date *</label>
                        <input type="date" class="form-control" name="date" id="approDate" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label class="form-label">Fournisseur / Laboratoire *</label>
                    <input type="text" class="form-control" name="fournisseur" id="approFournisseur" placeholder="Ex: Pharma CI, Sanofi, Novartis..." required list="fournisseursList">
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
                        <h4 class="card-title" style="font-size:0.95rem;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M12 5v14M5 12h14"/></svg>
                            Ajouter un medicament
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group" style="flex:3;">
                                <label class="form-label">Medicament</label>
                                <select class="form-control" id="approSelectMedic">
                                    <option value="">Selectionner</option>
                                    @foreach($medicaments as $med)
                                    <option value="{{ $med->id }}" data-nom="{{ $med->nom }}" data-prix="{{ $med->prix_unitaire }}">{{ $med->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" style="flex:1;">
                                <label class="form-label">Quantite</label>
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
                        <h4 class="card-title" style="font-size:0.95rem;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
                            Medicaments a approvisionner
                        </h4>
                    </div>
                    <div class="card-body no-pad">
                        <div class="table-wrap">
                            <table class="table-patients">
                                <thead>
                                    <tr>
                                        <th>Medicament</th>
                                        <th style="text-align:center;">Quantite</th>
                                        <th style="text-align:right;">Prix unit.</th>
                                        <th style="text-align:right;">Total</th>
                                        <th style="width:60px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="approLignesTable">
                                    <tr id="emptyRow">
                                        <td colspan="5" style="text-align:center;padding:32px;">
                                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:8px;"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><path d="M3.27 6.96L12 12.01l8.73-5.05M12 22.08V12"/></svg>
                                            <div class="text-muted" style="font-size:.875rem;">Aucun medicament ajoute</div>
                                            <div class="text-muted" style="font-size:.75rem;margin-top:4px;">Selectionnez un medicament ci-dessus pour l'ajouter</div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot style="background:#f1f5f9;">
                                    <tr>
                                        <td colspan="3" style="text-align:right;font-weight:bold;">Total articles:</td>
                                        <td style="text-align:right;font-weight:bold;" id="approTotalArticles">0</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="text-align:right;font-weight:bold;">Quantite totale:</td>
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
                    <textarea class="form-control" name="observations" id="approObservations" rows="2" placeholder="Notes sur la livraison..."></textarea>
                </div>

                <!-- Hidden inputs for lignes - populated by JS -->
                <div id="hiddenLignesContainer"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalFicheAppro')">Annuler</button>
                <button type="submit" class="btn btn-success" id="btnSubmitAppro">Enregistrer la commande</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail Approvisionnement -->
<div class="modal-overlay" id="modalDetailAppro">
    <div class="modal" style="max-width:700px;">
        <div class="modal-header" style="background:#eff6ff;">
            <h3 class="modal-title" id="detailTitle">Detail de la commande</h3>
            <button class="modal-close" onclick="closeModal('modalDetailAppro')">&times;</button>
        </div>
        <div class="modal-body" id="detailBody">
            <div style="text-align:center;padding:32px;">
                <div class="text-muted">Chargement...</div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('modalDetailAppro')">Fermer</button>
        </div>
    </div>
</div>

<script>
let approLignes = [];

function ajouterLigneAppro() {
    const select = document.getElementById('approSelectMedic');
    let selectedValue = select.value;

    // Si le select est dans un ss-wrap, récupérer la valeur correctement
    if (!selectedValue) {
        const wrap = select.closest('.ss-wrap');
        if (wrap) {
            const ssInput = wrap.querySelector('.ss-input');
            if (ssInput && ssInput.value) {
                // Chercher l'option qui matche le texte affiché
                const matchOption = Array.from(select.options).find(o => o.textContent.trim() === ssInput.value.trim());
                if (matchOption) selectedValue = matchOption.value;
            }
        }
    }

    const qte = parseInt(document.getElementById('approQte').value) || 0;
    const prix = parseInt(document.getElementById('approPrix').value) || 0;

    if (!selectedValue || qte <= 0) {
        alert('Veuillez sélectionner un médicament dans la liste et saisir une quantité valide');
        return;
    }

    const option = Array.from(select.options).find(o => o.value === selectedValue);
    const nom = option ? (option.dataset.nom || option.textContent.trim()) : 'Médicament';

    approLignes.push({
        id: selectedValue,
        nom: nom,
        quantite: qte,
        prix: prix
    });

    renderLignes();
    setSearchableSelectValue(select, '', '');
    document.getElementById('approQte').value = 1;
    document.getElementById('approPrix').value = '';
}

function renderLignes() {
    const tbody = document.getElementById('approLignesTable');
    const emptyRow = document.getElementById('emptyRow');
    const hiddenContainer = document.getElementById('hiddenLignesContainer');

    // Clear hidden inputs
    hiddenContainer.innerHTML = '';

    if (approLignes.length === 0) {
        emptyRow.style.display = '';
        document.getElementById('approTotalArticles').textContent = '0';
        document.getElementById('approTotalQte').textContent = '0';
        document.getElementById('approMontantTotal').textContent = '0 FCFA';
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
            <td><button type="button" class="btn btn-outline btn-sm" onclick="removeLigne(${i})" style="color:var(--danger);">X</button></td>
        `;
        tbody.appendChild(tr);

        // Add hidden inputs for this ligne
        hiddenContainer.innerHTML += `
            <input type="hidden" name="lignes[${i}][medicament_id]" value="${l.id}">
            <input type="hidden" name="lignes[${i}][quantite]" value="${l.quantite}">
            <input type="hidden" name="lignes[${i}][prix_unitaire]" value="${l.prix}">
        `;
    });

    document.getElementById('approTotalArticles').textContent = totalArticles;
    document.getElementById('approTotalQte').textContent = totalQte;
    document.getElementById('approMontantTotal').textContent = totalMontant.toLocaleString() + ' FCFA';
}

function removeLigne(index) {
    approLignes.splice(index, 1);
    renderLignes();
}

// Form validation before submit
document.getElementById('formAppro').addEventListener('submit', function(e) {
    if (approLignes.length === 0) {
        e.preventDefault();
        alert('Veuillez ajouter au moins un medicament');
        return false;
    }
});

// Auto-fill price when selecting medication
document.getElementById('approSelectMedic').addEventListener('change', function() {
    if (this.value) {
        const option = Array.from(this.options).find(o => o.value === this.value);
        if (option && option.dataset.prix) {
            document.getElementById('approPrix').value = option.dataset.prix;
        }
    }
});

// View approvisionnement detail via AJAX
function voirAppro(id) {
    const body = document.getElementById('detailBody');
    body.innerHTML = '<div style="text-align:center;padding:32px;"><div class="text-muted">Chargement...</div></div>';
    openModal('modalDetailAppro');

    fetch(`{{ url('pharmacie/approvisionnements') }}/${id}/json`)
        .then(r => r.json())
        .then(data => {
            let statutHtml = '';
            if (data.statut === 'validee') {
                statutHtml = '<span style="display:inline-flex;align-items:center;gap:4px;background:#dcfce7;color:#166534;padding:3px 10px;border-radius:12px;font-size:.8rem;font-weight:600;">Validee</span>';
            } else {
                statutHtml = '<span style="display:inline-flex;align-items:center;gap:4px;background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:12px;font-size:.8rem;font-weight:600;">En attente</span>';
            }

            let lignesHtml = '';
            data.lignes.forEach(l => {
                lignesHtml += `<tr>
                    <td>${l.medicament}</td>
                    <td style="text-align:center;">${l.quantite}</td>
                    <td style="text-align:right;">${l.prix_unitaire.toLocaleString()} F</td>
                    <td style="text-align:right;font-weight:bold;">${l.montant.toLocaleString()} F</td>
                </tr>`;
            });

            body.innerHTML = `
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <div class="text-muted" style="font-size:.75rem;margin-bottom:2px;">N Fiche</div>
                        <div style="font-weight:bold;">${data.numero}</div>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size:.75rem;margin-bottom:2px;">Date</div>
                        <div>${data.date || '-'}</div>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size:.75rem;margin-bottom:2px;">Fournisseur</div>
                        <div style="font-weight:bold;">${data.fournisseur}</div>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size:.75rem;margin-bottom:2px;">Statut</div>
                        <div>${statutHtml}</div>
                    </div>
                    ${data.date_reception ? `<div>
                        <div class="text-muted" style="font-size:.75rem;margin-bottom:2px;">Date de reception</div>
                        <div>${data.date_reception}</div>
                    </div>` : ''}
                    ${data.cree_par ? `<div>
                        <div class="text-muted" style="font-size:.75rem;margin-bottom:2px;">Cree par</div>
                        <div>${data.cree_par}</div>
                    </div>` : ''}
                </div>

                ${data.observations ? `<div style="margin-bottom:16px;padding:10px;background:#f8fafc;border-radius:8px;border:1px solid var(--border);">
                    <div class="text-muted" style="font-size:.75rem;margin-bottom:4px;">Observations</div>
                    <div style="font-size:.875rem;">${data.observations}</div>
                </div>` : ''}

                <div class="table-wrap">
                    <table class="table-patients">
                        <thead>
                            <tr>
                                <th>Medicament</th>
                                <th style="text-align:center;">Quantite</th>
                                <th style="text-align:right;">Prix unit.</th>
                                <th style="text-align:right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>${lignesHtml}</tbody>
                        <tfoot style="background:#f1f5f9;">
                            <tr>
                                <td colspan="3" style="text-align:right;font-weight:bold;">Total:</td>
                                <td style="text-align:right;font-weight:bold;color:var(--success);">${data.total.toLocaleString()} F</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            `;

            document.getElementById('detailTitle').textContent = 'Commande ' + data.numero;
        })
        .catch(err => {
            body.innerHTML = '<div style="text-align:center;padding:32px;color:#dc2626;">Erreur lors du chargement des details.</div>';
            console.error(err);
        });
}

// Filter table by fournisseur
function filterTable() {
    const search = document.getElementById('searchFournisseur').value.toLowerCase();
    const rows = document.querySelectorAll('#tableAppro tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(search) ? '' : 'none';
    });
}
</script>
@endsection
