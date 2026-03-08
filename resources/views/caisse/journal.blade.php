@extends('layouts.medicare')

@section('title', 'Journal de caisse - Caisse')
@section('sidebar-subtitle', 'Caisse')
@section('user-color', '#d97706')
@section('header-title', 'Journal de caisse')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('caisse._sidebar')
@endif
@endsection

@section('content')
@if(session('success'))
<div class="alert alert-success mb-4" style="background:var(--success-light);color:var(--success);padding:12px;border-radius:8px;">
    {{ session('success') }}
</div>
@endif

<div class="stats" style="grid-template-columns: repeat(3, 1fr); margin-bottom:24px;">
    <div class="stat-card">
        <div>
            <div class="stat-label">Total entrées</div>
            <div class="stat-value text-success">{{ number_format($totalEntrees, 0, ',', ' ') }} F</div>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-label">Total sorties</div>
            <div class="stat-value text-danger">{{ number_format($totalSorties, 0, ',', ' ') }} F</div>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-label">Solde</div>
            <div class="stat-value {{ $solde >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($solde, 0, ',', ' ') }} F</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Journal de caisse</h2>
        <button class="btn btn-outline btn-sm" onclick="openModal('modalDepense')">+ Dépense</button>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Date</th><th>Description</th><th>Entrée</th><th>Sortie</th><th>Solde</th></tr>
                </thead>
                <tbody>
                    @php $soldeRunning = 0; @endphp
                    @forelse($transactions as $transaction)
                    @php
                        if ($transaction->type == 'entree') {
                            $soldeRunning += $transaction->montant;
                        } else {
                            $soldeRunning -= $transaction->montant;
                        }
                    @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y H:i') }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td class="text-success">
                            @if($transaction->type == 'entree')
                            +{{ number_format($transaction->montant, 0, ',', ' ') }} F
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-danger">
                            @if($transaction->type == 'sortie')
                            -{{ number_format($transaction->montant, 0, ',', ' ') }} F
                            @else
                            -
                            @endif
                        </td>
                        <td><strong>{{ number_format($soldeRunning, 0, ',', ' ') }} F</strong></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted">Aucune transaction</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Dépense -->
<div class="modal-overlay" id="modalDepense">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Nouvelle dépense</h3>
            <button class="modal-close" onclick="closeModal('modalDepense')">&times;</button>
        </div>
        <form action="{{ route('caisse.depenses.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Description *</label>
                    <input type="text" class="form-control" name="description" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Montant *</label>
                    <input type="number" class="form-control" name="montant" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Catégorie</label>
                    <select class="form-control" name="categorie">
                        <option value="fournitures">Fournitures</option>
                        <option value="pharmacie">Pharmacie</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalDepense')">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection
