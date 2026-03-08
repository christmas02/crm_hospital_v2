@extends('layouts.medicare')

@section('title', 'Stock Médicament - MediCare Pro')
@section('sidebar-subtitle', 'Pharmacie')
@section('user-color', '#ec4899')
@section('header-title', 'Gestion du Stock')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('pharmacie._sidebar')
@endif
@endsection

@section('content')
@if(session('success'))
<div class="alert alert-success mb-4" style="background:#d1fae5;border:1px solid #10b981;color:#065f46;padding:12px 16px;border-radius:8px;">
    {{ session('success') }}
</div>
@endif

<div class="grid-2">
    <!-- Informations médicament -->
    <div class="card">
        <div class="card-header"><h2 class="card-title">{{ $medicament->nom }}</h2></div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;">
                <div>
                    <div class="text-muted text-sm">Forme</div>
                    <div style="font-weight:500;">{{ $medicament->forme }}</div>
                </div>
                <div>
                    <div class="text-muted text-sm">Dosage</div>
                    <div style="font-weight:500;">{{ $medicament->dosage }}</div>
                </div>
                <div>
                    <div class="text-muted text-sm">Prix unitaire</div>
                    <div style="font-weight:500;">{{ number_format($medicament->prix, 0, ',', ' ') }} FCFA</div>
                </div>
                <div>
                    <div class="text-muted text-sm">Stock minimum</div>
                    <div style="font-weight:500;">{{ $medicament->stock_min }}</div>
                </div>
            </div>
            <div style="margin-top:20px;padding-top:20px;border-top:1px solid var(--border);text-align:center;">
                <div class="text-muted text-sm mb-2">Stock actuel</div>
                <div style="font-size:3rem;font-weight:bold;color:{{ $medicament->stock <= $medicament->stock_min ? '#ef4444' : '#10b981' }};">
                    {{ $medicament->stock }}
                </div>
                @if($medicament->stock <= $medicament->stock_min)
                <span class="badge badge-danger">Stock critique</span>
                @elseif($medicament->stock <= $medicament->stock_min * 2)
                <span class="badge badge-warning">Stock faible</span>
                @else
                <span class="badge badge-success">Stock OK</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Ajustement de stock -->
    <div class="card">
        <div class="card-header"><h2 class="card-title">Ajuster le stock</h2></div>
        <div class="card-body">
            <form action="{{ route('pharmacie.stock.ajuster', $medicament) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Type de mouvement</label>
                    <div style="display:flex;gap:16px;margin-top:8px;">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:12px 16px;border:1px solid var(--border);border-radius:8px;flex:1;">
                            <input type="radio" name="type" value="entree" checked style="accent-color:#10b981;">
                            <span style="color:#10b981;font-weight:500;">Entrée (réception)</span>
                        </label>
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:12px 16px;border:1px solid var(--border);border-radius:8px;flex:1;">
                            <input type="radio" name="type" value="sortie" style="accent-color:#ef4444;">
                            <span style="color:#ef4444;font-weight:500;">Sortie (ajustement)</span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Quantité *</label>
                    <input type="number" class="form-control" name="quantite" min="1" required>
                    @error('quantite')
                    <div class="text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Motif *</label>
                    <input type="text" class="form-control" name="motif" required placeholder="Ex: Réception commande, Péremption...">
                    @error('motif')
                    <div class="text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">Enregistrer le mouvement</button>
            </form>
        </div>
    </div>
</div>

<!-- Historique des mouvements -->
<div class="card mt-4">
    <div class="card-header"><h2 class="card-title">Historique des mouvements</h2></div>
    <div class="card-body no-pad">
        @if($mouvements->count() > 0)
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th style="text-align:center;">Quantité</th>
                        <th>Motif</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mouvements as $mouvement)
                    <tr>
                        <td>{{ $mouvement->date->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($mouvement->type == 'entree')
                            <span class="badge badge-success">Entrée</span>
                            @else
                            <span class="badge badge-danger">Sortie</span>
                            @endif
                        </td>
                        <td style="text-align:center;font-weight:bold;color:{{ $mouvement->type == 'entree' ? '#10b981' : '#ef4444' }};">
                            {{ $mouvement->type == 'entree' ? '+' : '-' }}{{ $mouvement->quantite }}
                        </td>
                        <td>{{ $mouvement->motif }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center text-muted" style="padding:40px;">
            Aucun mouvement enregistré
        </div>
        @endif
    </div>
</div>

<div style="margin-top:24px;">
    <a href="{{ route('pharmacie.stock') }}" class="btn btn-secondary">Retour à la liste</a>
</div>
@endsection
