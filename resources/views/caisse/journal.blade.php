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

<!-- Stats -->
<div class="stats" style="grid-template-columns: repeat(3, 1fr); margin-bottom:24px;">
    <div class="stat-card" style="border-left: 4px solid var(--secondary);">
        <div>
            <div class="stat-label">Total entrées</div>
            <div class="stat-value text-success">{{ number_format($totaux['entrees'], 0, ',', ' ') }} F</div>
            <div class="stat-sub">Somme des encaissements</div>
        </div>
        <div class="stat-icon green">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--danger);">
        <div>
            <div class="stat-label">Total sorties</div>
            <div class="stat-value text-danger">{{ number_format($totaux['sorties'], 0, ',', ' ') }} F</div>
            <div class="stat-sub">Somme des dépenses</div>
        </div>
        <div class="stat-icon red">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--primary);">
        <div>
            <div class="stat-label">Solde</div>
            <div class="stat-value {{ $totaux['solde'] >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($totaux['solde'], 0, ',', ' ') }} F</div>
            <div class="stat-sub">Balance entrées - sorties</div>
        </div>
        <div class="stat-icon cyan">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body" style="padding:16px 20px;">
        <form method="GET" action="{{ route('caisse.journal') }}" style="display:flex;align-items:flex-end;gap:12px;flex-wrap:wrap;">
            <div style="flex:1;min-width:140px;">
                <label class="form-label" style="font-size:.75rem;">Date début</label>
                <input type="date" class="form-control" name="date_debut" value="{{ request('date_debut') }}" style="padding:8px 10px;">
            </div>
            <div style="flex:1;min-width:140px;">
                <label class="form-label" style="font-size:.75rem;">Date fin</label>
                <input type="date" class="form-control" name="date_fin" value="{{ request('date_fin') }}" style="padding:8px 10px;">
            </div>
            <div style="min-width:140px;">
                <label class="form-label" style="font-size:.75rem;">Type</label>
                <select class="form-control" name="type" style="padding:8px 10px;" onchange="this.form.submit()">
                    <option value="">Tous</option>
                    <option value="entree" {{ request('type') == 'entree' ? 'selected' : '' }}>Entrées</option>
                    <option value="sortie" {{ request('type') == 'sortie' ? 'selected' : '' }}>Sorties</option>
                </select>
            </div>
            <div style="min-width:140px;">
                <label class="form-label" style="font-size:.75rem;">Catégorie</label>
                <select class="form-control" name="categorie" style="padding:8px 10px;" onchange="this.form.submit()">
                    <option value="">Toutes</option>
                    <option value="consultation" {{ request('categorie') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                    <option value="fournitures" {{ request('categorie') == 'fournitures' ? 'selected' : '' }}>Fournitures</option>
                    <option value="pharmacie" {{ request('categorie') == 'pharmacie' ? 'selected' : '' }}>Pharmacie</option>
                    <option value="maintenance" {{ request('categorie') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="autre" {{ request('categorie') == 'autre' ? 'selected' : '' }}>Autre</option>
                </select>
            </div>
            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary btn-sm" style="padding:8px 16px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                    Filtrer
                </button>
                @if(request()->hasAny(['date_debut', 'date_fin', 'type', 'categorie']))
                <a href="{{ route('caisse.journal') }}" class="btn btn-secondary btn-sm" style="padding:8px 12px;">Réinitialiser</a>
                @endif
            </div>
        </form>
        <div style="margin-top:10px;display:flex;align-items:center;gap:10px;">
            <input type="text" id="journalSearch" class="filter-input" placeholder="Rechercher dans la page courante..." style="max-width:350px;">
            <span id="journalCount" style="font-size:.82rem;color:var(--gray-500);white-space:nowrap;"></span>
        </div>
    </div>
</div>

<!-- Journal -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
            Journal de caisse
        </h2>
        <div style="display:flex;align-items:center;gap:8px;">
            <!-- Bouton Rapport journalier -->
            <a href="{{ route('caisse.rapport-journalier', ['date' => request('date_debut', date('Y-m-d'))]) }}" target="_blank" class="btn btn-outline btn-sm" style="color:var(--secondary);">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
                Rapport du jour
            </a>
            <!-- Bouton Imprimer PDF -->
            <a href="{{ route('caisse.journal.pdf', request()->only(['date_debut', 'date_fin', 'type'])) }}" target="_blank" class="btn btn-outline btn-sm" style="color:var(--danger);">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Imprimer PDF
            </a>
            <a href="{{ route('export.transactions') }}" class="btn btn-outline btn-sm">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>
                Export CSV
            </a>
            <button class="btn btn-primary btn-sm" onclick="openModal('modalDepense')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                Dépense
            </button>
        </div>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Catégorie</th>
                        <th style="text-align:right;">Entrée</th>
                        <th style="text-align:right;">Sortie</th>
                        <th style="text-align:right;">Solde cumulé</th>
                    </tr>
                </thead>
                <tbody>
                    @php $soldeRunning = 0; @endphp
                    @forelse($transactions as $transaction)
                    @php
                        $soldeRunning += $transaction->type == 'entree' ? $transaction->montant : -$transaction->montant;
                    @endphp
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                {{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}
                            </div>
                        </td>
                        <td>{{ $transaction->description }}</td>
                        <td>
                            <span class="badge badge-secondary" style="font-size:.7rem;">{{ ucfirst($transaction->categorie ?? 'autre') }}</span>
                        </td>
                        <td style="text-align:right;">
                            @if($transaction->type == 'entree')
                            <span style="color:var(--success);font-weight:600;">+{{ number_format($transaction->montant, 0, ',', ' ') }} F</span>
                            @else
                            <span style="color:var(--gray-300);">-</span>
                            @endif
                        </td>
                        <td style="text-align:right;">
                            @if($transaction->type == 'sortie')
                            <span style="color:var(--danger);font-weight:600;">-{{ number_format($transaction->montant, 0, ',', ' ') }} F</span>
                            @else
                            <span style="color:var(--gray-300);">-</span>
                            @endif
                        </td>
                        <td style="text-align:right;">
                            <strong style="color:{{ $soldeRunning >= 0 ? 'var(--gray-800)' : 'var(--danger)' }};">{{ number_format($soldeRunning, 0, ',', ' ') }} F</strong>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div style="text-align:center;padding:40px 20px;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:12px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
                                <div style="color:var(--gray-500);font-weight:600;margin-bottom:4px;">Aucune transaction</div>
                                <div style="color:var(--gray-400);font-size:0.85rem;">Modifiez vos filtres ou ajoutez une dépense</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($transactions->count() > 0)
                <tfoot>
                    <tr style="background:var(--gray-50);">
                        <td colspan="3" style="font-weight:700;">TOTAUX</td>
                        <td style="text-align:right;font-weight:700;color:var(--success);">{{ number_format($totaux['entrees'], 0, ',', ' ') }} F</td>
                        <td style="text-align:right;font-weight:700;color:var(--danger);">{{ number_format($totaux['sorties'], 0, ',', ' ') }} F</td>
                        <td style="text-align:right;font-weight:700;">{{ number_format($totaux['solde'], 0, ',', ' ') }} F</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
        @if($transactions->hasPages())
        <div style="padding:16px 20px;">{{ $transactions->appends(request()->query())->links() }}</div>
        @endif
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
                    <input type="text" class="form-control" name="description" required placeholder="Ex: Achat de fournitures">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Montant (F) *</label>
                        <input type="number" class="form-control" name="montant" required min="1" placeholder="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Catégorie *</label>
                        <select class="form-control" name="categorie" required>
                            <option value="fournitures">Fournitures</option>
                            <option value="pharmacie">Pharmacie</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="salaires">Salaires</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalDepense')">Annuler</button>
                <button type="submit" class="btn btn-danger">Enregistrer la dépense</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function() {
    var searchInput = document.getElementById('journalSearch');
    var rows = document.querySelectorAll('.table-patients tbody tr');
    var countEl = document.getElementById('journalCount');

    function filterRows() {
        var q = searchInput ? searchInput.value.toLowerCase().trim() : '';
        var visible = 0;
        var total = 0;

        rows.forEach(function(row) {
            if (row.querySelector('td[colspan]')) return;
            total++;
            var text = row.textContent.toLowerCase();
            var matchSearch = !q || text.includes(q);
            if (matchSearch) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        if (countEl) countEl.textContent = visible + ' / ' + total + ' résultats';
    }

    if (searchInput) searchInput.addEventListener('input', filterRows);
    filterRows();
})();
</script>
@endpush

@endsection
