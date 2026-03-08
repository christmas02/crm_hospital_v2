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
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table id="stockTable">
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
                        <td>{{ number_format($medicament->prix_unitaire, 0, ',', ' ') }} F</td>
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
                            <button class="btn btn-outline btn-sm">Modifier</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted">Aucun médicament</td></tr>
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

<script>
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
