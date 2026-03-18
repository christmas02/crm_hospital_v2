@extends('layouts.medicare')

@section('title', 'Caisse - MediCare Pro')
@section('sidebar-subtitle', 'Caisse')
@section('user-color', '#d97706')
@section('header-title', 'Tableau de bord Caisse')

@section('header-right')
<span class="text-muted">{{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</span>
@endsection

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('caisse._sidebar')
@endif
@endsection

@section('content')
<!-- Stats -->
<div class="stats" style="grid-template-columns: repeat(4, 1fr);">
    <div class="stat-card" style="background:linear-gradient(135deg,#dcfce7,#bbf7d0);border:none;">
        <div>
            <div class="stat-label">Encaissements du jour</div>
            <div class="stat-value text-success">{{ number_format($stats['recettes_jour'], 0, ',', ' ') }} F</div>
        </div>
        <div class="stat-icon green">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg,#fef3c7,#fde68a);border:none;">
        <div>
            <div class="stat-label">Factures en attente</div>
            <div class="stat-value">{{ $stats['en_attente'] }}</div>
        </div>
        <div class="stat-icon orange">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/></svg>
        </div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg,#fee2e2,#fecaca);border:none;">
        <div>
            <div class="stat-label">Montant en attente</div>
            <div class="stat-value text-danger">{{ number_format($stats['montant_attente'], 0, ',', ' ') }} F</div>
        </div>
        <div class="stat-icon red">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-label">Transactions</div>
            <div class="stat-value">{{ $stats['transactions_jour'] ?? $dernieresTransactions->count() }}</div>
        </div>
        <div class="stat-icon cyan">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
        </div>
    </div>
</div>

<div class="grid-2">
    <!-- Nouvelles factures -->
    <div class="card">
        <div class="card-header" style="background:var(--warning-light);">
            <h2 class="card-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/></svg>
                Nouvelles factures
            </h2>
        </div>
        <div class="card-body no-pad">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Patient</th><th>Type</th><th>Montant</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        @forelse($facturesEnAttente->take(5) as $facture)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar">{{ strtoupper(substr($facture->patient->prenom ?? '', 0, 1) . substr($facture->patient->nom ?? '', 0, 1)) }}</div>
                                    <span>{{ $facture->patient->prenom ?? '' }} {{ $facture->patient->nom ?? '' }}</span>
                                </div>
                            </td>
                            <td>{{ $facture->type ?? 'Consultation' }}</td>
                            <td><strong>{{ number_format($facture->montant_total, 0, ',', ' ') }} F</strong></td>
                            <td>
                                <button class="btn btn-success btn-sm" onclick="openEncaissement({{ $facture->id }})">Encaisser</button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">Aucune facture en attente</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Derniers paiements -->
    <div class="card">
        <div class="card-header"><h2 class="card-title">Derniers paiements</h2></div>
        <div class="card-body no-pad">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Patient</th><th>Montant</th><th>Mode</th><th>Heure</th></tr>
                    </thead>
                    <tbody>
                        @forelse($derniersPaiements ?? [] as $paiement)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar">{{ strtoupper(substr($paiement->patient->prenom ?? '', 0, 1) . substr($paiement->patient->nom ?? '', 0, 1)) }}</div>
                                    <span>{{ $paiement->patient->prenom ?? '' }} {{ $paiement->patient->nom ?? '' }}</span>
                                </div>
                            </td>
                            <td><strong class="text-success">{{ number_format($paiement->montant ?? 0, 0, ',', ' ') }} F</strong></td>
                            <td>
                                @php
                                    $modes = ['especes' => 'Espèces', 'mobile_money' => 'Mobile Money', 'carte' => 'Carte', 'virement' => 'Virement'];
                                @endphp
                                {{ $modes[$paiement->mode_paiement ?? ''] ?? $paiement->mode_paiement ?? '-' }}
                            </td>
                            <td>{{ isset($paiement->date_paiement) ? \Carbon\Carbon::parse($paiement->date_paiement)->format('H:i') : '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">Aucun paiement aujourd'hui</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

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

                <!-- Détail des prestations -->
                <div class="card mb-4">
                    <div class="card-header"><h4 class="card-title" style="font-size:0.95rem;">Détail des prestations</h4></div>
                    <div class="card-body no-pad">
                        <div class="table-wrap">
                            <table>
                                <thead><tr><th>Désignation</th><th style="text-align:center;">Qté</th><th style="text-align:right;">Prix unit.</th><th style="text-align:right;">Total</th></tr></thead>
                                <tbody id="encaissementLignes"></tbody>
                                <tfoot style="background:var(--gray-100);">
                                    <tr>
                                        <td colspan="3" style="text-align:right;font-weight:bold;">TOTAL À PAYER</td>
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
                            <option value="">Sélectionner</option>
                            <option value="especes">Espèces</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="carte">Carte bancaire</option>
                            <option value="virement">Virement</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Référence (optionnel)</label>
                        <input type="text" class="form-control" name="reference" placeholder="N° transaction, reçu...">
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
    // Set form action
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
