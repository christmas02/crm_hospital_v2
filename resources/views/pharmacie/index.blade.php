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
    <div class="stat-card">
        <div>
            <div class="stat-label">Total médicaments</div>
            <div class="stat-value">{{ $stats['total_medicaments'] }}</div>
        </div>
        <div class="stat-icon cyan">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>
        </div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg,#fee2e2,#fecaca);border:none;">
        <div>
            <div class="stat-label">Stock critique</div>
            <div class="stat-value text-danger">{{ $stats['stock_bas'] }}</div>
        </div>
        <div class="stat-icon red">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
        </div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg,#fef3c7,#fde68a);border:none;">
        <div>
            <div class="stat-label">Demandes en attente</div>
            <div class="stat-value text-warning">{{ $stats['en_attente'] }}</div>
        </div>
        <div class="stat-icon orange">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
        </div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg,#dcfce7,#bbf7d0);border:none;">
        <div>
            <div class="stat-label">Valeur stock</div>
            <div class="stat-value text-success">{{ number_format($stats['valeur_stock'], 0, ',', ' ') }} F</div>
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

<!-- Toolbar stock -->
<div class="toolbar">
    <form method="GET" action="{{ route('pharmacie.index') }}" style="display:flex;gap:10px;flex:1;">
        <input type="text" name="search" class="filter-input" placeholder="Rechercher un médicament..." value="{{ request('search') }}">
        <select name="categorie" class="filter-select" onchange="this.form.submit()">
            <option value="">Toutes catégories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat }}" {{ request('categorie') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        @if(request('search') || request('categorie'))
        <a href="{{ route('pharmacie.index') }}" class="btn btn-secondary btn-sm">Réinitialiser</a>
        @endif
    </form>
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
        <span class="badge badge-info">{{ $medicamentsPagines->total() }} référence(s)</span>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table>
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
                        <td>{{ number_format($med->prix_unitaire, 0, ',', ' ') }} F</td>
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
                            <a href="{{ route('pharmacie.stock.show', $med) }}" class="btn btn-secondary btn-sm">Détail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted" style="padding:50px;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5" style="margin:0 auto 12px;display:block;"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>
                            <p>Aucun médicament trouvé</p>
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
            <a href="{{ route('pharmacie.demandes') }}" class="btn btn-secondary btn-sm">Tout voir</a>
        </div>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table>
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
                        <td>{{ \Carbon\Carbon::parse($ord->date)->format('d/m/Y') }}</td>
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
            <button class="modal-close" onclick="closeModal('modalMedicament')">✕</button>
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
            <button class="modal-close" onclick="closeModal('modalMouvement')">✕</button>
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
</script>
@endpush

@endsection
