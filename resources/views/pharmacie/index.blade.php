@extends('layouts.medicare')

@section('title', 'Pharmacie - MediCare Pro')
@section('sidebar-subtitle', 'Pharmacie')
@section('user-color', '#dc2626')
@section('header-title', 'Pharmacie')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('pharmacie._sidebar')
@endif
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success mb-4" style="background:#d1fae5;border:1px solid #10b981;color:#065f46;padding:12px 16px;border-radius:8px;margin-bottom:20px;">
    {{ session('success') }}
</div>
@endif

<!-- Stats -->
<div class="stats" style="grid-template-columns:repeat(4,1fr);">
    <div class="stat-card" style="border-left: 4px solid var(--primary);">
        <div>
            <div class="stat-label">Total médicaments</div>
            <div class="stat-value">{{ $stats['total_medicaments'] }}</div>
            <div class="stat-sub">Références en catalogue</div>
        </div>
        <div class="stat-icon cyan">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--danger);background:linear-gradient(135deg,#fee2e2,#fecaca);border-right:none;border-top:none;border-bottom:none;">
        <div>
            <div class="stat-label">Stock critique</div>
            <div class="stat-value text-danger">{{ $stats['stock_bas'] }}</div>
            <div class="stat-sub">Nécessitent réapprovisionnement</div>
        </div>
        <div class="stat-icon red">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--warning);background:linear-gradient(135deg,#fef3c7,#fde68a);border-right:none;border-top:none;border-bottom:none;">
        <div>
            <div class="stat-label">Demandes en attente</div>
            <div class="stat-value text-warning">{{ $stats['en_attente'] }}</div>
            <div class="stat-sub">Ordonnances à traiter</div>
        </div>
        <div class="stat-icon orange">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--secondary);background:linear-gradient(135deg,#dcfce7,#bbf7d0);border-right:none;border-top:none;border-bottom:none;">
        <div>
            <div class="stat-label">Valeur stock</div>
            <div class="stat-value text-success">{{ number_format($stats['valeur_stock'], 0, ',', ' ') }} F</div>
            <div class="stat-sub">Valorisation totale</div>
        </div>
        <div class="stat-icon green">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
    </div>
</div>

<!-- Bandeau alertes stock bas -->
@if($medicamentsStockBas->count() > 0)
<div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:10px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
    <div style="display:flex;align-items:center;gap:10px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
        <span style="color:#991b1b;font-weight:600;">{{ $medicamentsStockBas->count() }} médicament(s) en stock critique :</span>
        <span style="color:#b91c1c;font-size:0.875rem;">
            {{ $medicamentsStockBas->take(3)->pluck('nom')->implode(', ') }}{{ $medicamentsStockBas->count() > 3 ? '...' : '' }}
        </span>
    </div>
    <a href="{{ route('pharmacie.alertes') }}" class="btn btn-sm" style="background:#dc2626;color:#fff;font-size:0.8rem;">Voir toutes les alertes</a>
</div>
@endif

<!-- Charts -->
<div class="grid-2 mt-4" style="margin-bottom:20px;">
    <div class="card">
        <div class="card-header"><h2 class="card-title">Top 5 médicaments (stock)</h2></div>
        <div class="card-body"><div class="chart-container"><canvas id="chartTopMedicaments"></canvas></div></div>
    </div>
    <div class="card">
        <div class="card-header"><h2 class="card-title">Mouvements de stock (7 jours)</h2></div>
        <div class="card-body"><div class="chart-container"><canvas id="chartMouvements"></canvas></div></div>
    </div>
</div>

<!-- Toolbar stock -->
<div class="toolbar">
    <div style="display:flex;gap:10px;flex:1;align-items:center;">
        <input type="text" id="pharmaSearch" class="filter-input" placeholder="Rechercher un médicament...">
        <select id="pharmaCategorie" class="filter-select">
            <option value="">Toutes catégories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat }}">{{ $cat }}</option>
            @endforeach
        </select>
        <span id="pharmaCount" style="font-size:.82rem;color:var(--gray-500);white-space:nowrap;"></span>
    </div>
    <div style="display:flex;gap:8px;">
        <button class="btn btn-secondary" onclick="openModal('modalMouvement')">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/></svg>
            Mouvement Stock
        </button>
        <button class="btn btn-primary" onclick="openModal('modalMedicament')">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M12 5v14M5 12h14"/></svg>
            Nouveau Médicament
        </button>
    </div>
</div>

<!-- Table stock médicaments -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>
            Stock Médicaments
        </h2>
        <div style="display:flex;align-items:center;gap:8px;">
            <span class="badge badge-info">{{ $medicamentsPagines->total() }} référence(s)</span>
            <a href="{{ route('export.medicaments') }}" class="btn btn-outline btn-sm">
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
                        <th>Médicament</th>
                        <th>Catégorie</th>
                        <th>Forme</th>
                        <th>Dosage</th>
                        <th>Stock</th>
                        <th>Stock Min</th>
                        <th>Prix unitaire</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medicamentsPagines as $med)
                    <tr>
                        <td>
                            <div style="font-weight:600;">{{ $med->nom }}</div>
                            @if($med->fournisseur)
                            <div class="text-muted text-sm">{{ $med->fournisseur }}</div>
                            @endif
                        </td>
                        <td>
                            @if($med->categorie)
                            <span style="font-size:0.75rem;background:var(--gray-100);padding:2px 8px;border-radius:20px;">{{ $med->categorie }}</span>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $med->forme ?? '—' }}</td>
                        <td>{{ $med->dosage ?? '—' }}</td>
                        <td>
                            <strong class="{{ $med->stock <= $med->stock_min ? 'text-danger' : 'text-success' }}">
                                {{ $med->stock }}
                            </strong>
                        </td>
                        <td class="text-muted">{{ $med->stock_min }}</td>
                        <td>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2" style="vertical-align:middle;margin-right:4px;"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                            {{ number_format($med->prix_unitaire, 0, ',', ' ') }} F
                        </td>
                        <td>
                            @if($med->stock == 0)
                            <span class="badge badge-danger">Rupture</span>
                            @elseif($med->stock <= $med->stock_min)
                            <span class="badge badge-warning">Critique</span>
                            @else
                            <span class="badge badge-success">Disponible</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="voirMedicament({{ $med->id }}, '{{ addslashes($med->nom) }}', '{{ $med->forme }}', '{{ $med->dosage }}', '{{ $med->categorie }}', {{ $med->stock }}, {{ $med->stock_min }}, {{ $med->prix_unitaire ?? 0 }}, '{{ $med->fournisseur }}')">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="text-align:center;padding:32px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:8px;"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>
                            <div class="text-muted" style="font-size:.875rem;">Aucun médicament trouvé</div>
                            <div class="text-muted" style="font-size:.75rem;margin-top:4px;">Ajoutez un médicament ou modifiez vos critères de recherche</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($medicamentsPagines->hasPages())
    <div class="card-body" style="border-top:1px solid var(--border);">
        {{ $medicamentsPagines->links() }}
    </div>
    @endif
</div>

<!-- Demandes en attente (résumé) -->
@if($ordonnancesEnAttente->count() > 0 || $ordonnancesPreparees->count() > 0)
<div class="card" style="margin-top:20px;">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
            Demandes de dispensation
        </h2>
        <div style="display:flex;gap:8px;align-items:center;">
            @if($ordonnancesEnAttente->count() > 0)
            <span class="badge badge-warning">{{ $ordonnancesEnAttente->count() }} en attente</span>
            @endif
            @if($ordonnancesPreparees->count() > 0)
            <span class="badge badge-info">{{ $ordonnancesPreparees->count() }} préparé(s)</span>
            @endif
            <a href="{{ route('pharmacie.demandes') }}" class="btn btn-primary btn-sm">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> Voir
            </a>
        </div>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients">
                <thead>
                    <tr><th>Réf.</th><th>Patient</th><th>Date</th><th>Statut</th></tr>
                </thead>
                <tbody>
                    @foreach($ordonnancesEnAttente->merge($ordonnancesPreparees)->take(5) as $ord)
                    <tr>
                        <td><strong>ORD-{{ str_pad($ord->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
                        <td>
                            <div class="user-cell">
                                <div class="avatar">{{ strtoupper(substr($ord->patient->prenom ?? '', 0, 1) . substr($ord->patient->nom ?? '', 0, 1)) }}</div>
                                <span>{{ $ord->patient->prenom ?? '' }} {{ $ord->patient->nom ?? '' }}</span>
                            </div>
                        </td>
                        <td>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2" style="vertical-align:middle;margin-right:4px;"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                            {{ \Carbon\Carbon::parse($ord->date)->format('d/m/Y') }}
                        </td>
                        <td>
                            @if($ord->statut_dispensation === 'en_attente')
                            <span class="badge badge-warning">En attente</span>
                            @elseif($ord->statut_dispensation === 'prepare')
                            <span class="badge badge-info">Préparé</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Modal Nouveau Médicament -->
<div class="modal-overlay" id="modalMedicament">
    <div class="modal" style="max-width:560px;">
        <div class="modal-header">
            <h3 class="modal-title">Nouveau Médicament</h3>
            <button class="modal-close" onclick="closeModal('modalMedicament')">&#10005;</button>
        </div>
        <form action="{{ route('pharmacie.medicaments.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nom *</label>
                    <input type="text" name="nom" class="form-control" required placeholder="Ex: Paracétamol 500mg">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Catégorie</label>
                        <select name="categorie" class="form-control">
                            <option value="">Sélectionner</option>
                            <option>Antalgique</option>
                            <option>Antibiotique</option>
                            <option>Antipaludéen</option>
                            <option>Antihypertenseur</option>
                            <option>Antidiabétique</option>
                            <option>Anti-inflammatoire</option>
                            <option>Supplément</option>
                            <option>Perfusion</option>
                            <option>Autre</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Forme</label>
                        <select name="forme" class="form-control">
                            <option value="">Sélectionner</option>
                            <option>Comprimé</option>
                            <option>Gélule</option>
                            <option>Sirop</option>
                            <option>Injectable</option>
                            <option>Flacon</option>
                            <option>Sachet</option>
                            <option>Pommade</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Dosage</label>
                    <input type="text" name="dosage" class="form-control" placeholder="Ex: 500mg, 10mg/ml">
                </div>
                <div class="form-row" style="grid-template-columns:1fr 1fr 1fr;">
                    <div class="form-group">
                        <label class="form-label">Stock initial *</label>
                        <input type="number" name="stock" class="form-control" required min="0" value="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stock minimum *</label>
                        <input type="number" name="stock_min" class="form-control" required min="0" value="10">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prix unitaire (F) *</label>
                        <input type="number" name="prix_unitaire" class="form-control" required min="0" value="0">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Fournisseur</label>
                    <input type="text" name="fournisseur" class="form-control" placeholder="Nom du fournisseur">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalMedicament')">Annuler</button>
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v14a2 2 0 01-2 2z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Mouvement de Stock -->
<div class="modal-overlay" id="modalMouvement">
    <div class="modal" style="max-width:460px;">
        <div class="modal-header">
            <h3 class="modal-title">Mouvement de Stock</h3>
            <button class="modal-close" onclick="closeModal('modalMouvement')">&#10005;</button>
        </div>
        <form action="{{ route('pharmacie.mouvements.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Médicament *</label>
                    <select name="medicament_id" class="form-control" required>
                        <option value="">Sélectionner un médicament</option>
                        @foreach($tousLesMedicaments as $med)
                        <option value="{{ $med->id }}">{{ $med->nom }} (stock: {{ $med->stock }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Type *</label>
                        <select name="type" class="form-control" required id="mouvTypeSelect" onchange="updateMouvStyle()">
                            <option value="entree">Entrée</option>
                            <option value="sortie">Sortie</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Quantité *</label>
                        <input type="number" name="quantite" class="form-control" required min="1" placeholder="Ex: 50">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Motif</label>
                    <input type="text" name="motif" class="form-control" placeholder="Ex: Approvisionnement fournisseur, Dispensation...">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalMouvement')">Annuler</button>
                <button type="submit" class="btn btn-success" id="btnSaveMouvement">Valider l'entrée</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function updateMouvStyle() {
    const type = document.getElementById('mouvTypeSelect').value;
    const btn  = document.getElementById('btnSaveMouvement');
    btn.className = type === 'sortie' ? 'btn btn-danger' : 'btn btn-success';
    btn.textContent = type === 'sortie' ? 'Valider la sortie' : "Valider l'entrée";
}

document.addEventListener('DOMContentLoaded', function() {
    // Horizontal bar chart - Top 5 médicaments
    const ctxTop = document.getElementById('chartTopMedicaments');
    if (ctxTop) {
        new Chart(ctxTop, {
            type: 'bar',
            data: {
                labels: @json($topMedicaments->pluck('nom')),
                datasets: [
                    {
                        label: 'Stock actuel',
                        data: @json($topMedicaments->pluck('stock')),
                        backgroundColor: 'rgb(8, 145, 178)',
                        borderRadius: 4,
                    },
                    {
                        label: 'Stock minimum',
                        data: @json($topMedicaments->pluck('stock_min')),
                        backgroundColor: 'rgb(217, 119, 6)',
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 16, usePointStyle: true, pointStyle: 'circle' }
                    }
                },
                scales: {
                    x: { beginAtZero: true }
                }
            }
        });
    }

    // Live filter for stock table
    (function() {
        var searchInput = document.getElementById('pharmaSearch');
        var selectFilter = document.getElementById('pharmaCategorie');
        var rows = document.querySelectorAll('.table-patients tbody tr');
        var countEl = document.getElementById('pharmaCount');

        function filterRows() {
            var q = searchInput ? searchInput.value.toLowerCase().trim() : '';
            var filterVal = selectFilter ? selectFilter.value.toLowerCase() : '';
            var visible = 0;
            var total = 0;

            rows.forEach(function(row) {
                if (row.querySelector('td[colspan]')) return;
                total++;
                var text = row.textContent.toLowerCase();
                var matchSearch = !q || text.includes(q);
                var matchFilter = !filterVal || text.includes(filterVal);
                if (matchSearch && matchFilter) {
                    row.style.display = '';
                    visible++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (countEl) countEl.textContent = visible + ' / ' + total + ' résultats';
        }

        if (searchInput) searchInput.addEventListener('input', filterRows);
        if (selectFilter) selectFilter.addEventListener('change', filterRows);
        filterRows();
    })();

    // Line chart - Mouvements de stock
    const ctxMouv = document.getElementById('chartMouvements');
    if (ctxMouv) {
        new Chart(ctxMouv, {
            type: 'line',
            data: {
                labels: @json($mouvementsParJour->pluck('date')),
                datasets: [
                    {
                        label: 'Entrées',
                        data: @json($mouvementsParJour->pluck('entrees')),
                        borderColor: 'rgb(5, 150, 105)',
                        backgroundColor: 'rgba(5, 150, 105, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointBackgroundColor: 'rgb(5, 150, 105)',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Sorties',
                        data: @json($mouvementsParJour->pluck('sorties')),
                        borderColor: 'rgb(220, 38, 38)',
                        backgroundColor: 'rgba(220, 38, 38, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointBackgroundColor: 'rgb(220, 38, 38)',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }
                ]
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
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
});
</script>
    // Voir médicament modal
    window.voirMedicament = function(id, nom, forme, dosage, categorie, stock, stockMin, prix, fournisseur) {
        var pct = stockMin > 0 ? Math.min(Math.round((stock / stockMin) * 100), 100) : 100;
        var stockColor = stock <= 0 ? 'var(--danger)' : (stock <= stockMin ? 'var(--warning)' : 'var(--success)');
        var stockLabel = stock <= 0 ? 'Rupture' : (stock <= stockMin ? 'Stock bas' : 'Disponible');

        document.getElementById('vmMedBody').innerHTML = `
            <div style="text-align:center;padding-bottom:20px;margin-bottom:20px;border-bottom:1px solid var(--gray-200);">
                <div style="width:64px;height:64px;border-radius:16px;background:var(--danger-light);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>
                </div>
                <div style="font-size:1.2rem;font-weight:800;">${nom}</div>
                <div style="color:var(--gray-500);font-size:.85rem;">${forme} ${dosage ? '• ' + dosage : ''}</div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                <div style="background:var(--gray-50);padding:12px;border-radius:10px;">
                    <div style="font-size:.65rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;margin-bottom:4px;">Catégorie</div>
                    <div style="font-weight:600;">${categorie || '—'}</div>
                </div>
                <div style="background:var(--gray-50);padding:12px;border-radius:10px;">
                    <div style="font-size:.65rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;margin-bottom:4px;">Fournisseur</div>
                    <div style="font-weight:600;">${fournisseur || '—'}</div>
                </div>
                <div style="background:var(--gray-50);padding:12px;border-radius:10px;">
                    <div style="font-size:.65rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;margin-bottom:4px;">Prix unitaire</div>
                    <div style="font-weight:800;color:var(--success);">${prix.toLocaleString()} F</div>
                </div>
                <div style="background:var(--gray-50);padding:12px;border-radius:10px;">
                    <div style="font-size:.65rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;margin-bottom:4px;">Seuil minimum</div>
                    <div style="font-weight:800;">${stockMin}</div>
                </div>
            </div>
            <div style="margin-bottom:16px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                    <span style="font-size:.78rem;font-weight:600;">Stock actuel</span>
                    <span style="font-size:.78rem;font-weight:700;color:${stockColor};">${stock} unités (${pct}%)</span>
                </div>
                <div style="width:100%;height:10px;background:var(--gray-100);border-radius:5px;overflow:hidden;">
                    <div style="width:${pct}%;height:100%;background:${stockColor};border-radius:5px;transition:width .5s;"></div>
                </div>
                <div style="text-align:center;margin-top:8px;">
                    <span class="badge" style="background:${stockColor}22;color:${stockColor};font-size:.75rem;padding:4px 12px;">${stockLabel}</span>
                </div>
            </div>
        `;
        document.getElementById('vmMedTitle').textContent = nom;
        openModal('modalVoirMed');
    };
});
</script>
@endpush

<!-- Modal Voir Médicament -->
<div class="modal-overlay" id="modalVoirMed">
    <div class="modal" style="max-width:500px;">
        <div class="modal-header">
            <h3 class="modal-title" id="vmMedTitle">Détails médicament</h3>
            <button class="modal-close" onclick="closeModal('modalVoirMed')">&times;</button>
        </div>
        <div class="modal-body" id="vmMedBody"></div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('modalVoirMed')">Fermer</button>
        </div>
    </div>
</div>

@endsection
