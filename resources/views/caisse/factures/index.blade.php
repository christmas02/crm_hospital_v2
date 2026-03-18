@extends('layouts.medicare')

@section('title', 'Factures a encaisser - Caisse')
@section('sidebar-subtitle', 'Caisse')
@section('user-color', '#d97706')
@section('header-title', 'Factures a encaisser')

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

<div class="toolbar">
    <div class="filters">
        <form action="{{ route('caisse.factures.index') }}" method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
            <input type="text" class="filter-input" name="search" value="{{ request('search') }}" placeholder="Rechercher un patient...">
            <input type="date" class="filter-input" name="date_debut" value="{{ request('date_debut') }}" style="min-width:140px;">
            <input type="date" class="filter-input" name="date_fin" value="{{ request('date_fin') }}" style="min-width:140px;">
            <select class="filter-select" name="statut">
                <option value="">Tous les statuts</option>
                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="envoyee" {{ request('statut') == 'envoyee' ? 'selected' : '' }}>Envoyée</option>
                <option value="payee" {{ request('statut') == 'payee' ? 'selected' : '' }}>Payée</option>
                <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulée</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
            @if(request()->hasAny(['search', 'date_debut', 'date_fin', 'statut']))
            <a href="{{ route('caisse.factures.index') }}" class="btn btn-secondary btn-sm">Réinitialiser</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
            Liste des factures
        </h2>
        <a href="{{ route('export.factures') }}" class="btn btn-outline btn-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>
            Export CSV
        </a>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients">
                <thead>
                    <tr>
                        <th>N. Facture</th>
                        <th>Patient</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Montant</th>
                        <th>Envoye par</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($factures as $facture)
                    <tr>
                        <td><strong>{{ $facture->numero }}</strong></td>
                        <td>
                            <div class="user-cell">
                                <div class="avatar">{{ strtoupper(substr($facture->patient->prenom ?? '', 0, 1) . substr($facture->patient->nom ?? '', 0, 1)) }}</div>
                                <span>{{ $facture->patient->prenom ?? '' }} {{ $facture->patient->nom ?? '' }}</span>
                            </div>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                {{ $facture->date->format('d/m/Y') }}
                            </div>
                        </td>
                        <td>{{ $facture->type ?? 'Consultation' }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                                <strong>{{ number_format($facture->montant_total, 0, ',', ' ') }} F</strong>
                            </div>
                        </td>
                        <td>{{ $facture->emetteur ?? 'Medecin' }}</td>
                        <td>
                            @if($facture->statut == 'en_attente')
                            @if($sessionOuverte ?? false)
                            <button class="btn btn-success btn-sm" onclick="openEncaissement({{ $facture->id }})">Encaisser</button>
                            @else
                            <span class="btn btn-secondary btn-sm" style="opacity:.5;cursor:not-allowed;" title="Ouvrez la caisse d'abord">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                            </span>
                            @endif
                            @else
                            <span class="badge badge-success">Payee</span>
                            @endif
                            <a href="{{ route('caisse.factures.show', $facture) }}" class="btn btn-primary btn-sm">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                Voir
                            </a>
                            <a href="{{ route('caisse.factures.pdf', $facture) }}" class="btn btn-outline btn-sm" target="_blank">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                PDF
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div style="text-align:center;padding:40px 20px;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:12px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
                                <div style="color:var(--gray-500);font-weight:600;margin-bottom:4px;">Aucune facture trouvee</div>
                                <div style="color:var(--gray-400);font-size:0.85rem;">Modifiez vos filtres ou attendez de nouvelles factures</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($factures->hasPages())
<div class="mt-4">
    {{ $factures->links() }}
</div>
@endif

<!-- Modal Encaissement -->
<div class="modal-overlay" id="modalEncaissement">
    <div class="modal modal-lg">
        <div class="modal-header" style="background:var(--success-light);">
            <h3 class="modal-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
                Encaissement Facture
            </h3>
            <button class="modal-close" onclick="closeModal('modalEncaissement')">&times;</button>
        </div>
        <form id="formEncaissement" method="POST">
            @csrf
            <div class="modal-body">
                <!-- Info Patient & Facture -->
                <div id="encaissementHeader" class="mb-4" style="display:flex;justify-content:space-between;align-items:flex-start;padding:16px;background:var(--gray-100);border-radius:8px;">
                    <div>
                        <div class="text-muted text-sm">Patient</div>
                        <div style="font-weight:600;font-size:1.1rem;" id="encPatientNom">-</div>
                        <div class="text-muted" id="encPatientTel">-</div>
                    </div>
                    <div style="text-align:right;">
                        <div class="text-muted text-sm">Facture</div>
                        <div style="font-weight:600;" id="encFactureNum">-</div>
                        <div class="text-muted" id="encFactureDate">-</div>
                    </div>
                </div>

                <!-- Detail des prestations -->
                <div class="card mb-4">
                    <div class="card-header"><h4 class="card-title" style="font-size:0.95rem;">Detail des prestations</h4></div>
                    <div class="card-body no-pad">
                        <div class="table-wrap">
                            <table class="table-patients">
                                <thead><tr><th>Designation</th><th style="text-align:center;">Qte</th><th style="text-align:right;">Prix unit.</th><th style="text-align:right;">Total</th></tr></thead>
                                <tbody id="encaissementLignes"></tbody>
                                <tfoot style="background:var(--gray-100);">
                                    <tr>
                                        <td colspan="3" style="text-align:right;font-weight:bold;">TOTAL A PAYER</td>
                                        <td style="text-align:right;font-weight:bold;font-size:1.25rem;color:var(--primary);" id="encTotal">0 F</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Mode de paiement -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Mode de paiement *</label>
                        <select class="form-control" name="mode_paiement" required>
                            <option value="">Selectionner</option>
                            <option value="especes">Especes</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="carte">Carte bancaire</option>
                            <option value="virement">Virement</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Reference (optionnel)</label>
                        <input type="text" class="form-control" name="reference" placeholder="N. transaction, recu...">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEncaissement')">Annuler</button>
                <button type="button" class="btn btn-outline" onclick="window.print()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Imprimer
                </button>
                <button type="submit" class="btn btn-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                    Valider le paiement
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEncaissement(factureId) {
    document.getElementById('formEncaissement').action = '/caisse/factures/' + factureId + '/encaisser';

    // Fetch facture details
    fetch('/caisse/factures/' + factureId + '/details')
        .then(response => response.json())
        .then(data => {
            // Update patient info
            document.getElementById('encPatientNom').textContent = data.patient.prenom + ' ' + data.patient.nom;
            document.getElementById('encPatientTel').textContent = data.patient.telephone || '-';

            // Update facture info
            document.getElementById('encFactureNum').textContent = 'N° ' + data.numero;
            document.getElementById('encFactureDate').textContent = data.date;

            // Update lignes
            const lignesContainer = document.getElementById('encaissementLignes');
            lignesContainer.innerHTML = '';

            if (data.lignes && data.lignes.length > 0) {
                data.lignes.forEach(ligne => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${ligne.description}</td>
                        <td style="text-align:center;">${ligne.quantite}</td>
                        <td style="text-align:right;">${new Intl.NumberFormat('fr-FR').format(ligne.prix_unitaire)} F</td>
                        <td style="text-align:right;font-weight:500;">${new Intl.NumberFormat('fr-FR').format(ligne.montant)} F</td>
                    `;
                    lignesContainer.appendChild(tr);
                });
            } else {
                lignesContainer.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Aucune ligne</td></tr>';
            }

            // Update total
            document.getElementById('encTotal').textContent = new Intl.NumberFormat('fr-FR').format(data.montant_total) + ' FCFA';

            openModal('modalEncaissement');
        })
        .catch(error => {
            console.error('Erreur:', error);
            openModal('modalEncaissement');
        });
}
</script>
@endsection
