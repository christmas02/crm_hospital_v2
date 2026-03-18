@extends('layouts.medicare')

@section('title', 'Factures à encaisser - Caisse')
@section('sidebar-subtitle', 'Caisse')
@section('user-color', '#d97706')
@section('header-title', 'Factures à encaisser')

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
        <form action="{{ route('caisse.factures.index') }}" method="GET" class="flex gap-2">
            <input type="date" class="filter-input" name="date_debut" value="{{ request('date_debut') }}">
            <input type="date" class="filter-input" name="date_fin" value="{{ request('date_fin') }}">
            <select class="filter-select" name="statut">
                <option value="">Tous les statuts</option>
                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="payee" {{ request('statut') == 'payee' ? 'selected' : '' }}>Payée</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>N° Facture</th>
                        <th>Patient</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Montant</th>
                        <th>Envoyé par</th>
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
                        <td>{{ $facture->date->format('d/m/Y') }}</td>
                        <td>{{ $facture->type ?? 'Consultation' }}</td>
                        <td><strong>{{ number_format($facture->montant_total, 0, ',', ' ') }} F</strong></td>
                        <td>{{ $facture->emetteur ?? 'Médecin' }}</td>
                        <td>
                            @if($facture->statut == 'en_attente')
                            <button class="btn btn-success btn-sm" onclick="openEncaissement({{ $facture->id }})">Encaisser</button>
                            @else
                            <span class="badge badge-success">Payée</span>
                            @endif
                            <a href="{{ route('caisse.factures.show', $facture) }}" class="btn btn-outline btn-sm">Détail</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted">Aucune facture trouvée</td></tr>
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
