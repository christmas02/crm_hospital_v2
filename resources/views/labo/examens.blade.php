@extends('layouts.medicare')

@section('title', 'Catalogue Examens - MediCare Pro')
@section('sidebar-subtitle', 'Laboratoire')
@section('user-color', '#7c3aed')
@section('header-title', 'Catalogue des examens')

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

<!-- Toolbar -->
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <div>
        <h2 style="font-size:1.1rem;font-weight:700;color:var(--gray-800);">Catalogue des examens</h2>
        <p style="font-size:0.85rem;color:var(--gray-500);">{{ $examens->count() }} examens disponibles</p>
    </div>
    <button class="btn btn-primary btn-sm" onclick="openModal('modalNouvelExamen')">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
        Nouvel Examen
    </button>
</div>

<!-- Table -->
<div class="card">
    <div class="table-responsive">
        <table class="table-patients">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Categorie</th>
                    <th>Unite</th>
                    <th>Valeur normale</th>
                    <th>Prix</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($examens as $examen)
                <tr>
                    <td><strong>{{ $examen->nom }}</strong></td>
                    <td>
                        <span class="badge" style="background:var(--primary-light);color:var(--primary);">{{ $examen->categorie }}</span>
                    </td>
                    <td>{{ $examen->unite ?: '-' }}</td>
                    <td style="font-size:0.85rem;color:var(--gray-500);">{{ $examen->valeur_normale ?: '-' }}</td>
                    <td><strong>{{ number_format($examen->prix, 0, ',', ' ') }} F</strong></td>
                    <td>
                        @if($examen->actif)
                            <span class="badge" style="background:#dcfce7;color:#16a34a;">Actif</span>
                        @else
                            <span class="badge" style="background:#fee2e2;color:#dc2626;">Inactif</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:40px;color:var(--gray-400);">Aucun examen dans le catalogue</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Nouvel Examen -->
<div class="modal-overlay" id="modalNouvelExamen">
    <div class="modal" style="max-width:550px;">
        <div class="modal-header">
            <h3>Nouvel examen</h3>
            <button onclick="closeModal('modalNouvelExamen')" class="modal-close">&times;</button>
        </div>
        <form method="POST" action="{{ route('labo.examens.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nom de l'examen *</label>
                    <input type="text" name="nom" class="form-control" required placeholder="Ex: Glycemie a jeun">
                </div>
                <div class="form-grid" style="grid-template-columns:1fr 1fr;gap:16px;margin-top:12px;">
                    <div class="form-group">
                        <label class="form-label">Categorie *</label>
                        <input type="text" name="categorie" class="form-control" required placeholder="Ex: Biochimie" list="categoriesList">
                        <datalist id="categoriesList">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Unite</label>
                        <input type="text" name="unite" class="form-control" placeholder="Ex: g/L, mmol/L">
                    </div>
                </div>
                <div class="form-grid" style="grid-template-columns:1fr 1fr;gap:16px;margin-top:12px;">
                    <div class="form-group">
                        <label class="form-label">Valeur normale</label>
                        <input type="text" name="valeur_normale" class="form-control" placeholder="Ex: 0.70 - 1.10">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prix (FCFA) *</label>
                        <input type="number" name="prix" class="form-control" required min="0" value="0">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('modalNouvelExamen')">Annuler</button>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </form>
    </div>
</div>

@endsection
