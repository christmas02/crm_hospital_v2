@extends('layouts.medicare')

@section('title', 'Stock médicaments - Pharmacie')
@section('sidebar-subtitle', 'Pharmacie')
@section('user-color', '#dc2626')
@section('header-title', 'Stock médicaments')

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
        <input type="text" class="filter-input" placeholder="Rechercher..." id="searchMedic" onkeyup="filterStock()">
        <select class="filter-select" id="filterCat" onchange="filterStock()">
            <option value="">Toutes catégories</option>
            <option>Antalgique</option>
            <option>Antibiotique</option>
            <option>Antipaludéen</option>
            <option>Antihypertenseur</option>
            <option>Antidiabétique</option>
        </select>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('pharmacie.approvisionnements') }}" class="btn btn-success">+ Approvisionnement</a>
        <button class="btn btn-primary" onclick="openModal('modalMedic')">+ Médicament</button>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><path d="M3.27 6.96L12 12.01l8.73-5.05M12 22.08V12"/></svg>
            Stock Médicaments
        </h2>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients" id="stockTable">
                <thead>
                    <tr>
                        <th>Médicament</th>
                        <th>Catégorie</th>
                        <th>Stock</th>
                        <th>Min</th>
                        <th>Prix</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medicaments as $medicament)
                    <tr data-nom="{{ strtolower($medicament->nom) }}" data-cat="{{ strtolower($medicament->categorie ?? '') }}">
                        <td>
                            <div>
                                <div style="font-weight:600;">{{ $medicament->nom }}</div>
                                <div class="text-muted text-sm">{{ $medicament->forme ?? 'Comprimé' }} - {{ $medicament->dosage ?? '' }}</div>
                            </div>
                        </td>
                        <td>{{ $medicament->categorie ?? '-' }}</td>
                        <td><strong>{{ $medicament->stock }}</strong></td>
                        <td>{{ $medicament->stock_min }}</td>
                        <td>
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2" style="vertical-align:middle;margin-right:4px;"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                            {{ number_format($medicament->prix_unitaire, 0, ',', ' ') }} F
                        </td>
                        <td>
                            @if($medicament->stock <= 0)
                            <span class="badge badge-danger">Rupture</span>
                            @elseif($medicament->stock <= $medicament->stock_min)
                            <span class="badge badge-warning">Stock bas</span>
                            @else
                            <span class="badge badge-success">OK</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="voirMedicament({{ $medicament->id }})">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> Voir
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:32px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:8px;"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><path d="M3.27 6.96L12 12.01l8.73-5.05M12 22.08V12"/></svg>
                            <div class="text-muted" style="font-size:.875rem;">Aucun médicament en stock</div>
                            <div class="text-muted" style="font-size:.75rem;margin-top:4px;">Commencez par ajouter un médicament au catalogue</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nouveau Médicament -->
<div class="modal-overlay" id="modalMedic">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Nouveau médicament</h3>
            <button class="modal-close" onclick="closeModal('modalMedic')">&times;</button>
        </div>
        <form action="#" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nom *</label>
                    <input type="text" class="form-control" name="nom" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Catégorie *</label>
                        <select class="form-control" name="categorie" required>
                            <option>Antalgique</option>
                            <option>Antibiotique</option>
                            <option>Antipaludéen</option>
                            <option>Antihypertenseur</option>
                            <option>Antidiabétique</option>
                            <option>Anti-inflammatoire</option>
                            <option>Supplément</option>
                            <option>Autre</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Forme *</label>
                        <select class="form-control" name="forme" required>
                            <option>Comprimé</option>
                            <option>Gélule</option>
                            <option>Sirop</option>
                            <option>Injectable</option>
                            <option>Pommade</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Stock initial</label>
                        <input type="number" class="form-control" name="stock" value="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stock minimum</label>
                        <input type="number" class="form-control" name="stock_min" value="10">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Prix unitaire (FCFA) *</label>
                    <input type="number" class="form-control" name="prix_unitaire" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalMedic')">Annuler</button>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Détail Médicament -->
<div class="modal-overlay" id="modalVoirMedic">
    <div class="modal" style="max-width:500px;">
        <div class="modal-header">
            <h3 class="modal-title">Détail du médicament</h3>
            <button class="modal-close" onclick="closeModal('modalVoirMedic')">&times;</button>
        </div>
        <div class="modal-body" id="modalVoirMedicContent">
            @foreach($medicaments as $medicament)
            <div id="medic-{{ $medicament->id }}" style="display:none;">
                <div style="text-align:center;margin-bottom:20px;">
                    <div style="display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;background:var(--primary-light);color:var(--primary);border-radius:50%;margin-bottom:8px;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><path d="M3.27 6.96L12 12.01l8.73-5.05M12 22.08V12"/></svg>
                    </div>
                    <h3 style="font-weight:700;margin:0;">{{ $medicament->nom }}</h3>
                    <div class="text-muted text-sm">{{ $medicament->forme ?? 'Comprimé' }} {{ $medicament->dosage ? '- ' . $medicament->dosage : '' }}</div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                    <div style="background:var(--gray-50);border-radius:8px;padding:12px;">
                        <div class="text-muted text-sm">Catégorie</div>
                        <strong>{{ $medicament->categorie ?? '-' }}</strong>
                    </div>
                    <div style="background:var(--gray-50);border-radius:8px;padding:12px;">
                        <div class="text-muted text-sm">Prix unitaire</div>
                        <strong>{{ number_format($medicament->prix_unitaire, 0, ',', ' ') }} F</strong>
                    </div>
                    <div style="background:var(--gray-50);border-radius:8px;padding:12px;">
                        <div class="text-muted text-sm">Stock actuel</div>
                        <strong class="{{ $medicament->stock <= $medicament->stock_min ? 'text-danger' : '' }}">{{ $medicament->stock }}</strong>
                    </div>
                    <div style="background:var(--gray-50);border-radius:8px;padding:12px;">
                        <div class="text-muted text-sm">Stock minimum</div>
                        <strong>{{ $medicament->stock_min }}</strong>
                    </div>
                </div>

                <div style="text-align:center;">
                    @if($medicament->stock <= 0)
                    <span class="badge badge-danger" style="font-size:0.85rem;padding:6px 16px;">Rupture de stock</span>
                    @elseif($medicament->stock <= $medicament->stock_min)
                    <span class="badge badge-warning" style="font-size:0.85rem;padding:6px 16px;">Stock bas - Réapprovisionner</span>
                    @else
                    <span class="badge badge-success" style="font-size:0.85rem;padding:6px 16px;">Stock OK</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
let currentMedicId = null;
function voirMedicament(id) {
    if (currentMedicId) document.getElementById('medic-' + currentMedicId).style.display = 'none';
    document.getElementById('medic-' + id).style.display = 'block';
    currentMedicId = id;
    openModal('modalVoirMedic');
}

function filterStock() {
    const search = document.getElementById('searchMedic').value.toLowerCase();
    const cat = document.getElementById('filterCat').value.toLowerCase();
    const rows = document.querySelectorAll('#stockTable tbody tr');

    rows.forEach(row => {
        const nom = row.dataset.nom || '';
        const categorie = row.dataset.cat || '';
        const matchSearch = nom.includes(search);
        const matchCat = !cat || categorie.includes(cat);
        row.style.display = matchSearch && matchCat ? '' : 'none';
    });
}
</script>
@endsection
