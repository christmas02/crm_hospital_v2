@extends('layouts.medicare')

@section('title', 'Caisse & Comptabilité - MediCare Pro')
@section('sidebar-subtitle', 'Gestion Hospitalière')
@section('header-title', 'Caisse & Comptabilité')

@section('sidebar-nav')
@include('admin._sidebar')
@endsection

@section('content')

<!-- Stats -->
<div class="stats" style="grid-template-columns:repeat(4,1fr);">
    <div class="stat-card" style="border-left: 4px solid var(--secondary);background:linear-gradient(135deg,#dcfce7,#bbf7d0);border-top:none;border-right:none;border-bottom:none;">
        <div>
            <div class="stat-label">Recettes du jour</div>
            <div class="stat-value text-success">{{ number_format($stats['recettes_jour'], 0, ',', ' ') }} F</div>
            <div class="stat-sub">Total des entrées aujourd'hui</div>
        </div>
        <div class="stat-icon green">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--danger);background:linear-gradient(135deg,#fee2e2,#fecaca);border-top:none;border-right:none;border-bottom:none;">
        <div>
            <div class="stat-label">Dépenses du jour</div>
            <div class="stat-value text-danger">{{ number_format($stats['depenses_jour'], 0, ',', ' ') }} F</div>
            <div class="stat-sub">Total des sorties aujourd'hui</div>
        </div>
        <div class="stat-icon red">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--primary);background:linear-gradient(135deg,#dbeafe,#bfdbfe);border-top:none;border-right:none;border-bottom:none;">
        <div>
            <div class="stat-label">Solde du jour</div>
            <div class="stat-value {{ $stats['solde_jour'] >= 0 ? 'text-success' : 'text-danger' }}">
                {{ number_format($stats['solde_jour'], 0, ',', ' ') }} F
            </div>
            <div class="stat-sub">Balance recettes - dépenses</div>
        </div>
        <div class="stat-icon cyan">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--warning);background:linear-gradient(135deg,#fef3c7,#fde68a);border-top:none;border-right:none;border-bottom:none;">
        <div>
            <div class="stat-label">Impayés</div>
            <div class="stat-value text-warning">{{ number_format($stats['impayes'], 0, ',', ' ') }} F</div>
            <div class="stat-sub">Paiements en attente</div>
        </div>
        <div class="stat-icon orange">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="tabs" style="margin-bottom:20px;">
    <button class="tab {{ !request('tab') || request('tab') == 'paiements' ? 'active' : '' }}" onclick="showTab('paiements')">
        Paiements patients
    </button>
    <button class="tab {{ request('tab') == 'transactions' ? 'active' : '' }}" onclick="showTab('transactions')">
        Transactions
    </button>
</div>

<!-- ===== TAB: Paiements ===== -->
<div id="tabPaiements" class="{{ request('tab') == 'transactions' ? 'hidden' : '' }}">
    <div class="toolbar">
        <form method="GET" action="{{ route('admin.caisse') }}" style="display:flex;gap:10px;flex:1;">
            <input type="hidden" name="tab" value="paiements">
            <input type="text" name="search" class="filter-input" placeholder="Rechercher un patient..." value="{{ request('search') }}">
            <select name="statut_p" class="filter-select" onchange="this.form.submit()">
                <option value="">Tous les statuts</option>
                <option value="paye" {{ request('statut_p') == 'paye' ? 'selected' : '' }}>Payé</option>
                <option value="en_attente" {{ request('statut_p') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="annule" {{ request('statut_p') == 'annule' ? 'selected' : '' }}>Annulé</option>
            </select>
            @if(request('search') || request('statut_p'))
            <a href="{{ route('admin.caisse') }}" class="btn btn-secondary btn-sm">Réinitialiser</a>
            @endif
        </form>
        <button class="btn btn-primary" onclick="openModal('modalPaiement')">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M12 5v14M5 12h14"/></svg>
            Nouveau Paiement
        </button>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                Paiements patients
            </h2>
        </div>
        <div class="card-body no-pad">
            <div class="table-wrap">
                <table class="table-patients">
                    <thead>
                        <tr><th>Patient</th><th>Date</th><th>Type</th><th>Montant</th><th>Mode</th><th>Description</th><th>Statut</th></tr>
                    </thead>
                    <tbody>
                        @forelse($paiements as $paiement)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar">{{ strtoupper(substr($paiement->patient->prenom, 0, 1) . substr($paiement->patient->nom, 0, 1)) }}</div>
                                    <span>{{ $paiement->patient->prenom }} {{ $paiement->patient->nom }}</span>
                                </div>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                    {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}
                                </div>
                            </td>
                            <td>{{ $paiement->type }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                                    <strong class="text-success">{{ number_format($paiement->montant, 0, ',', ' ') }} F</strong>
                                </div>
                            </td>
                            <td>
                                @php $modes = ['especes'=>'Espèces','mobile_money'=>'Mobile Money','carte'=>'Carte','virement'=>'Virement']; @endphp
                                {{ $modes[$paiement->mode_paiement] ?? $paiement->mode_paiement }}
                            </td>
                            <td class="truncate" style="max-width:160px;">{{ $paiement->description ?? '-' }}</td>
                            <td>
                                @php $sp = ['paye'=>['success','Payé'],'en_attente'=>['warning','En attente'],'annule'=>['secondary','Annulé']]; $s = $sp[$paiement->statut] ?? ['secondary',$paiement->statut]; @endphp
                                <span class="badge badge-{{ $s[0] }}">{{ $s[1] }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" style="text-align:center;padding:32px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:8px;"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                            <div class="text-muted" style="font-size:.875rem;">Aucun paiement</div>
                            <div class="text-muted" style="font-size:.75rem;margin-top:4px;">Enregistrez un nouveau paiement pour commencer</div>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($paiements->hasPages())
        <div class="card-body" style="border-top:1px solid var(--border);">
            {{ $paiements->appends(['tab'=>'paiements','search'=>request('search'),'statut_p'=>request('statut_p')])->links() }}
        </div>
        @endif
    </div>
</div>

<!-- ===== TAB: Transactions ===== -->
<div id="tabTransactions" class="{{ request('tab') == 'transactions' ? '' : 'hidden' }}">
    <div class="toolbar">
        <form method="GET" action="{{ route('admin.caisse') }}" style="display:flex;gap:10px;flex:1;">
            <input type="hidden" name="tab" value="transactions">
            <select name="type_t" class="filter-select" onchange="this.form.submit()">
                <option value="">Toutes les transactions</option>
                <option value="entree" {{ request('type_t') == 'entree' ? 'selected' : '' }}>Entrées</option>
                <option value="sortie" {{ request('type_t') == 'sortie' ? 'selected' : '' }}>Sorties</option>
            </select>
        </form>
        <button class="btn btn-primary" onclick="openModal('modalTransaction')">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M12 5v14M5 12h14"/></svg>
            Nouvelle Transaction
        </button>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
                Transactions
            </h2>
        </div>
        <div class="card-body no-pad">
            <div class="table-wrap">
                <table class="table-patients">
                    <thead>
                        <tr><th>Date</th><th>Description</th><th>Catégorie</th><th>Entrée</th><th>Sortie</th></tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                    {{ $transaction->date->format('d/m/Y') }}
                                </div>
                            </td>
                            <td>{{ $transaction->description }}</td>
                            <td>
                                <span style="font-size:0.75rem;background:var(--gray-100);padding:2px 8px;border-radius:20px;text-transform:capitalize;">
                                    {{ $transaction->categorie }}
                                </span>
                            </td>
                            <td>
                                @if($transaction->type === 'entree')
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                                    <strong class="text-success">{{ number_format($transaction->montant, 0, ',', ' ') }} F</strong>
                                </div>
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($transaction->type === 'sortie')
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                                    <strong class="text-danger">{{ number_format($transaction->montant, 0, ',', ' ') }} F</strong>
                                </div>
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center;padding:32px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:8px;"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
                            <div class="text-muted" style="font-size:.875rem;">Aucune transaction</div>
                            <div class="text-muted" style="font-size:.75rem;margin-top:4px;">Ajoutez une transaction pour commencer le suivi</div>
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($transactions->hasPages())
        <div class="card-body" style="border-top:1px solid var(--border);">
            {{ $transactions->appends(['tab'=>'transactions','type_t'=>request('type_t')])->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Nouveau Paiement -->
<div class="modal-overlay" id="modalPaiement">
    <div class="modal" style="max-width:540px;">
        <div class="modal-header">
            <h3 class="modal-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                Nouveau Paiement
            </h3>
            <button class="modal-close" onclick="closeModal('modalPaiement')">✕</button>
        </div>
        <form action="{{ route('admin.caisse.paiements.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Patient *</label>
                    <select name="patient_id" class="form-control" required>
                        <option value="">Sélectionner un patient</option>
                        @foreach($patients as $p)
                        <option value="{{ $p->id }}">{{ $p->prenom }} {{ $p->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Type *</label>
                        <select name="type" class="form-control" required>
                            <option value="">Sélectionner</option>
                            <option value="Consultation">Consultation</option>
                            <option value="Hospitalisation">Hospitalisation</option>
                            <option value="Médicaments">Médicaments</option>
                            <option value="Analyses">Analyses</option>
                            <option value="Chirurgie">Chirurgie</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Montant (F) *</label>
                        <input type="number" name="montant" class="form-control" required min="1" placeholder="Ex: 15000">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Mode de paiement *</label>
                        <select name="mode_paiement" class="form-control" required>
                            <option value="">Sélectionner</option>
                            <option value="especes">Espèces</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="carte">Carte bancaire</option>
                            <option value="virement">Virement</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date *</label>
                        <input type="date" name="date_paiement" class="form-control" required value="{{ today()->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <input type="text" name="description" class="form-control" placeholder="Précision optionnelle...">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalPaiement')">Annuler</button>
                <button type="submit" class="btn btn-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Nouvelle Transaction -->
<div class="modal-overlay" id="modalTransaction">
    <div class="modal" style="max-width:460px;">
        <div class="modal-header">
            <h3 class="modal-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
                Nouvelle Transaction
            </h3>
            <button class="modal-close" onclick="closeModal('modalTransaction')">✕</button>
        </div>
        <form action="{{ route('admin.caisse.transactions.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Type *</label>
                        <select name="type" class="form-control" required id="transTypeSelect" onchange="updateTransactionStyle()">
                            <option value="entree">Entrée</option>
                            <option value="sortie">Sortie / Dépense</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Montant (F) *</label>
                        <input type="number" name="montant" class="form-control" required min="1" placeholder="Ex: 5000">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Description *</label>
                    <input type="text" name="description" class="form-control" required placeholder="Ex: Achat fournitures médicales">
                </div>
                <div class="form-group">
                    <label class="form-label">Catégorie</label>
                    <select name="categorie" class="form-control">
                        <option value="autre">Autre</option>
                        <option value="consultation">Consultation</option>
                        <option value="pharmacie">Pharmacie</option>
                        <option value="hospitalisation">Hospitalisation</option>
                        <option value="depense">Dépense opérationnelle</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalTransaction')">Annuler</button>
                <button type="submit" class="btn btn-primary" id="btnSaveTransaction">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showTab(tab) {
    document.getElementById('tabPaiements').classList.toggle('hidden', tab !== 'paiements');
    document.getElementById('tabTransactions').classList.toggle('hidden', tab !== 'transactions');
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelector(`[onclick="showTab('${tab}')"]`).classList.add('active');
}

function updateTransactionStyle() {
    const type = document.getElementById('transTypeSelect').value;
    const btn = document.getElementById('btnSaveTransaction');
    btn.className = type === 'sortie' ? 'btn btn-danger' : 'btn btn-success';
    btn.textContent = type === 'sortie' ? 'Enregistrer la dépense' : 'Enregistrer l\'entrée';
}
</script>
@endpush

@endsection
