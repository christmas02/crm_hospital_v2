@extends('layouts.medicare')

@section('title', 'Facture - MediCare Pro')
@section('sidebar-subtitle', 'Caisse')
@section('user-color', '#d97706')
@section('header-title', 'Details Facture')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('caisse._sidebar')
@endif
@endsection

@push('styles')
<style>
.montant-grid {
    display:grid;grid-template-columns:1fr 1fr;gap:0;
    border:1px solid var(--border);border-radius:10px;overflow:hidden;margin-bottom:24px;
}
.montant-grid .mg-item {
    padding:14px 18px;border-bottom:1px solid var(--border);
    display:flex;justify-content:space-between;align-items:center;
}
.montant-grid .mg-item:nth-child(odd) { border-right:1px solid var(--border); }
.montant-grid .mg-item.mg-total {
    grid-column:1/-1;background:var(--primary-light);border-bottom:none;
}
.payment-timeline {
    position:relative;padding-left:20px;
}
.payment-timeline::before {
    content:'';position:absolute;left:6px;top:0;bottom:0;width:2px;background:var(--gray-200);
}
.payment-entry {
    position:relative;padding:12px 0 12px 16px;
}
.payment-entry::before {
    content:'';position:absolute;left:-16px;top:18px;width:10px;height:10px;border-radius:50%;
    background:var(--success);border:2px solid #fff;box-shadow:0 0 0 2px var(--success);
}
</style>
@endpush

@section('content')

<div class="card" style="margin-bottom:20px;">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
            Facture {{ $facture->numero }}
        </h2>
        <div style="display:flex;gap:8px;align-items:center;">
            <a href="{{ route('caisse.factures.pdf', $facture) }}" class="btn btn-outline btn-sm" target="_blank">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                PDF
            </a>
            @if($facture->statut == 'en_attente')
            <button class="btn btn-outline btn-sm" style="color:var(--danger);border-color:var(--danger);" onclick="if(confirm('Annuler cette facture ?')) document.getElementById('formAnnuler').submit();">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                Annuler
            </button>
            <form id="formAnnuler" action="{{ route('caisse.factures.annuler', $facture) }}" method="POST" style="display:none;">@csrf</form>
            @endif
            @if($facture->statut == 'payee')
            <button class="btn btn-outline btn-sm" style="color:var(--warning);border-color:var(--warning);" onclick="openModal('modalAvoir')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M3 10h10a8 8 0 018 8v2M3 10l6 6M3 10l6-6"/></svg>
                Creer un avoir
            </button>
            @endif
            @if($facture->statut != 'annulee' && $facture->statut != 'payee')
            <button class="btn btn-outline btn-sm" style="color:#7c3aed;border-color:#7c3aed;" onclick="openModal('modalPriseEnCharge')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/><path d="M9 14l2 2 4-4"/></svg>
                Prise en charge
            </button>
            @endif
        </div>
    </div>
    <div class="card-body" style="padding:32px;">
        {{-- En-tete facture --}}
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:32px;padding-bottom:24px;border-bottom:2px solid var(--border);">
            <div>
                <h1 style="font-size:1.75rem;font-weight:bold;color:var(--primary);margin-bottom:8px;">MediCare Pro</h1>
                <p class="text-muted">Centre Hospitalier</p>
                <p class="text-muted">Abidjan, Cote d'Ivoire</p>
            </div>
            <div style="text-align:right;">
                <h2 style="font-size:1.25rem;font-weight:bold;margin-bottom:8px;">FACTURE</h2>
                <p class="text-muted">
                    <span style="display:inline-flex;align-items:center;gap:4px;">N.: <strong>{{ $facture->numero }}</strong></span>
                </p>
                <p class="text-muted">
                    <span style="display:inline-flex;align-items:center;gap:4px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        Date: {{ $facture->date->format('d/m/Y') }}
                    </span>
                </p>
                <div style="margin-top:12px;">
                    @if($facture->statut == 'payee')
                    <span class="badge badge-success" style="font-size:0.9rem;padding:6px 12px;">Payee</span>
                    @elseif($facture->statut == 'partielle')
                    <span class="badge" style="font-size:0.9rem;padding:6px 12px;background:#ecfeff;color:#0891b2;">Partielle</span>
                    @elseif($facture->statut == 'annulee')
                    <span class="badge badge-danger" style="font-size:0.9rem;padding:6px 12px;">Annulee</span>
                    @else
                    <span class="badge badge-warning" style="font-size:0.9rem;padding:6px 12px;">En attente</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Informations patient --}}
        <div style="margin-bottom:32px;">
            <h3 class="text-muted text-sm" style="text-transform:uppercase;margin-bottom:8px;">Patient</h3>
            <p style="font-weight:600;font-size:1.1rem;">{{ $facture->patient->prenom }} {{ $facture->patient->nom }}</p>
            <p class="text-muted">{{ $facture->patient->telephone }}</p>
            @if($facture->patient->adresse)
            <p class="text-muted">{{ $facture->patient->adresse }}</p>
            @endif
        </div>

        {{-- Lignes de facture --}}
        <div class="table-wrap" style="margin-bottom:24px;">
            <table class="table-patients">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th style="text-align:center;">Qte</th>
                        <th style="text-align:right;">Prix unitaire</th>
                        <th style="text-align:right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($facture->lignes as $ligne)
                    <tr>
                        <td>{{ $ligne->description }}</td>
                        <td style="text-align:center;">{{ $ligne->quantite }}</td>
                        <td style="text-align:right;">
                            <div style="display:inline-flex;align-items:center;gap:6px;">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                                {{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} F
                            </div>
                        </td>
                        <td style="text-align:right;font-weight:500;">{{ number_format($ligne->montant, 0, ',', ' ') }} F</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Montant breakdown --}}
        @php
            $sousTotal = $facture->lignes->sum('montant');
            $remise = $facture->montant_remise ?? 0;
            $tva = $facture->montant_tva ?? 0;
            $montantNet = $facture->montant_net ?: $facture->montant_total;
            $montantPaye = $facture->montant_paye ?? 0;
            $priseEnCharge = $facture->montant_couvert ?? 0;
            $montantPatient = $facture->montant_patient ?? $montantNet;
            $montantRestant = $facture->montant_restant ?? ($montantNet - $montantPaye);
        @endphp
        <div class="montant-grid">
            <div class="mg-item">
                <span style="color:var(--gray-600);">Sous-total</span>
                <span style="font-weight:600;">{{ number_format($sousTotal, 0, ',', ' ') }} F</span>
            </div>
            <div class="mg-item">
                <span style="color:var(--gray-600);">Remise</span>
                <span style="font-weight:600;color:var(--danger);">-{{ number_format($remise, 0, ',', ' ') }} F</span>
            </div>
            <div class="mg-item">
                <span style="color:var(--gray-600);">TVA</span>
                <span style="font-weight:600;">{{ number_format($tva, 0, ',', ' ') }} F</span>
            </div>
            @if($facture->type_prise_en_charge)
            <div class="mg-item">
                <span style="color:var(--gray-600);">
                    Prise en charge ({{ ucfirst($facture->type_prise_en_charge) }} {{ $facture->taux_couverture }}%)
                    @if($facture->organisme_prise_en_charge)
                    <br><small style="color:var(--gray-400);">{{ $facture->organisme_prise_en_charge }}</small>
                    @endif
                </span>
                <span style="font-weight:600;color:#7c3aed;">-{{ number_format($priseEnCharge, 0, ',', ' ') }} F</span>
            </div>
            <div class="mg-item">
                <span style="color:var(--gray-600);">Part patient</span>
                <span style="font-weight:700;color:var(--primary);">{{ number_format($montantPatient, 0, ',', ' ') }} F</span>
            </div>
            @endif
            <div class="mg-item">
                <span style="color:var(--gray-600);">Montant paye</span>
                <span style="font-weight:700;color:var(--success);">{{ number_format($montantPaye, 0, ',', ' ') }} F</span>
            </div>
            <div class="mg-item mg-total">
                <div>
                    <div style="font-size:.8rem;color:var(--gray-500);text-transform:uppercase;">Net a payer</div>
                    <div style="font-weight:800;font-size:1.4rem;color:var(--primary);">{{ number_format($montantNet, 0, ',', ' ') }} FCFA</div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:.8rem;color:var(--gray-500);text-transform:uppercase;">Reste a payer</div>
                    <div style="font-weight:800;font-size:1.4rem;color:{{ $montantRestant > 0 ? 'var(--danger)' : 'var(--success)' }};">{{ number_format($montantRestant, 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>

        {{-- Prise en charge info --}}
        @if($facture->type_prise_en_charge)
        <div style="background:#f5f3ff;border:1px solid #c4b5fd;border-radius:10px;padding:16px;margin-bottom:24px;">
            <h4 style="font-weight:700;font-size:.9rem;color:#7c3aed;margin-bottom:8px;display:flex;align-items:center;gap:6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2"><path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/><path d="M9 14l2 2 4-4"/></svg>
                Prise en charge - {{ ucfirst($facture->type_prise_en_charge) }}
            </h4>
            <div style="display:flex;gap:24px;flex-wrap:wrap;font-size:.85rem;">
                @if($facture->organisme_prise_en_charge)
                <div><span style="color:var(--gray-500);">Organisme:</span> <strong>{{ $facture->organisme_prise_en_charge }}</strong></div>
                @endif
                @if($facture->numero_assurance)
                <div><span style="color:var(--gray-500);">N. assurance:</span> <strong>{{ $facture->numero_assurance }}</strong></div>
                @endif
                <div><span style="color:var(--gray-500);">Taux:</span> <strong>{{ $facture->taux_couverture }}%</strong></div>
                <div><span style="color:var(--gray-500);">Montant couvert:</span> <strong style="color:#7c3aed;">{{ number_format($priseEnCharge, 0, ',', ' ') }} F</strong></div>
            </div>
        </div>
        @endif

        {{-- Historique des paiements --}}
        @if(isset($facture->paiements) && $facture->paiements->count() > 0)
        <div style="margin-bottom:24px;">
            <h3 style="font-weight:700;font-size:1rem;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
                Historique des paiements ({{ $facture->paiements->count() }})
            </h3>
            <div class="payment-timeline">
                @foreach($facture->paiements as $paiement)
                <div class="payment-entry">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:8px;">
                        <div>
                            <div style="font-weight:700;color:var(--success);font-size:1.1rem;">{{ number_format($paiement->montant, 0, ',', ' ') }} F</div>
                            <div style="display:flex;align-items:center;gap:8px;margin-top:4px;flex-wrap:wrap;">
                                @php
                                    $modes = ['especes' => 'Especes', 'mobile_money' => 'Mobile Money', 'carte' => 'Carte', 'virement' => 'Virement', 'cheque' => 'Cheque'];
                                    $modeClass = 'mode-' . ($paiement->mode_paiement ?? 'especes');
                                @endphp
                                <span style="display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:12px;font-size:.72rem;font-weight:600;background:var(--gray-100);color:var(--gray-700);">{{ $modes[$paiement->mode_paiement] ?? $paiement->mode_paiement }}</span>
                                @if($paiement->reference)
                                <span style="font-size:.75rem;color:var(--gray-500);">Ref: {{ $paiement->reference }}</span>
                                @endif
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:.82rem;color:var(--gray-600);">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:2px;"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y a H:i') }}
                            </div>
                            @if($paiement->numero_recu ?? null)
                            <div style="font-size:.75rem;color:var(--gray-500);margin-top:2px;">Recu: {{ $paiement->numero_recu }}</div>
                            @endif
                            @if($paiement->encaisseur ?? null)
                            <div style="font-size:.75rem;color:var(--gray-400);margin-top:2px;">Par: {{ $paiement->encaisseur->name ?? '-' }}</div>
                            @endif
                            @if($paiement->numero_recu ?? null)
                            <a href="{{ route('caisse.factures.recu', [$facture, $paiement]) }}" target="_blank" style="font-size:.72rem;color:var(--primary);text-decoration:none;margin-top:4px;display:inline-flex;align-items:center;gap:3px;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                Recu PDF
                            </a>
                            @endif
                            <button type="button" class="btn btn-outline btn-sm" style="font-size:.7rem;padding:2px 8px;margin-top:4px;color:var(--danger);border-color:var(--danger);" onclick="openModal('modalRemboursement{{ $paiement->id }}')">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:2px;"><path d="M3 10h10a8 8 0 018 8v2M3 10l6 6M3 10l6-6"/></svg>
                                Rembourser
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Avoir / Credit note section --}}
        @if(isset($facture->avoirs) && $facture->avoirs->count() > 0)
        <div style="margin-bottom:24px;">
            <h3 style="font-weight:700;font-size:1rem;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--warning)" stroke-width="2"><path d="M3 10h10a8 8 0 018 8v2M3 10l6 6M3 10l6-6"/></svg>
                Avoirs / Notes de credit
            </h3>
            <div class="table-wrap">
                <table class="table-patients">
                    <thead>
                        <tr><th>N. Avoir</th><th>Date</th><th style="text-align:right;">Montant</th><th>Motif</th></tr>
                    </thead>
                    <tbody>
                        @foreach($facture->avoirs as $avoir)
                        <tr>
                            <td style="font-weight:600;">{{ $avoir->numero }}</td>
                            <td>{{ $avoir->date->format('d/m/Y') }}</td>
                            <td style="text-align:right;font-weight:700;color:var(--danger);">-{{ number_format($avoir->montant, 0, ',', ' ') }} F</td>
                            <td>{{ $avoir->motif ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Remboursements section --}}
        @if(isset($facture->remboursements) && $facture->remboursements->count() > 0)
        <div style="margin-bottom:24px;">
            <h3 style="font-weight:700;font-size:1rem;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="2"><path d="M3 10h10a8 8 0 018 8v2M3 10l6 6M3 10l6-6"/></svg>
                Remboursements ({{ $facture->remboursements->count() }})
            </h3>
            <div class="table-wrap">
                <table class="table-patients">
                    <thead>
                        <tr><th>N. Remboursement</th><th>Date</th><th style="text-align:right;">Montant</th><th>Mode</th><th>Motif</th><th>Par</th></tr>
                    </thead>
                    <tbody>
                        @foreach($facture->remboursements as $remb)
                        <tr>
                            <td style="font-weight:600;">{{ $remb->numero }}</td>
                            <td>{{ $remb->created_at->format('d/m/Y H:i') }}</td>
                            <td style="text-align:right;font-weight:700;color:var(--danger);">-{{ number_format($remb->montant, 0, ',', ' ') }} F</td>
                            <td><span style="padding:2px 8px;border-radius:12px;font-size:.72rem;font-weight:600;background:var(--gray-100);color:var(--gray-700);">{{ ucfirst($remb->mode_remboursement) }}</span></td>
                            <td>{{ $remb->motif }}</td>
                            <td>{{ $remb->effectueur->name ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Actions --}}
        <div style="display:flex;justify-content:space-between;align-items:center;padding-top:24px;border-top:1px solid var(--border);">
            <a href="{{ route('caisse.factures.index') }}" class="btn btn-secondary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Retour
            </a>
            @if($facture->statut == 'en_attente' || $facture->statut == 'partielle')
            <button class="btn btn-success" style="padding:12px 24px;" onclick="openModal('modalEncaisserShow')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                Encaisser {{ number_format($montantRestant, 0, ',', ' ') }} FCFA
            </button>
            @elseif($facture->statut == 'payee')
            <div style="color:#059669;font-weight:500;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:middle;"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                Facture entierement payee
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Encaisser from show page --}}
@if($facture->statut == 'en_attente' || $facture->statut == 'partielle')
<div class="modal-overlay" id="modalEncaisserShow">
    <div class="modal">
        <div class="modal-header" style="background:var(--success-light);">
            <h3 class="modal-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
                Encaisser la facture {{ $facture->numero }}
            </h3>
            <button class="modal-close" onclick="closeModal('modalEncaisserShow')">&times;</button>
        </div>
        <form action="{{ route('caisse.factures.encaisser', $facture) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div style="background:var(--gray-50);border-radius:10px;padding:16px;margin-bottom:20px;display:flex;justify-content:space-between;">
                    <div>
                        <div style="font-size:.78rem;color:var(--gray-500);">Total facture</div>
                        <div style="font-weight:700;font-size:1.1rem;">{{ number_format($montantNet, 0, ',', ' ') }} F</div>
                    </div>
                    <div>
                        <div style="font-size:.78rem;color:var(--gray-500);">Deja paye</div>
                        <div style="font-weight:700;font-size:1.1rem;color:var(--success);">{{ number_format($montantPaye, 0, ',', ' ') }} F</div>
                    </div>
                    <div>
                        <div style="font-size:.78rem;color:var(--gray-500);">Reste</div>
                        <div style="font-weight:700;font-size:1.1rem;color:var(--danger);">{{ number_format($montantRestant, 0, ',', ' ') }} F</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Montant a encaisser *</label>
                    <input type="number" class="form-control" name="montant" required min="1" max="{{ $montantRestant }}" value="{{ $montantRestant }}" step="1">
                    <div style="font-size:.78rem;color:var(--gray-500);margin-top:4px;">Vous pouvez effectuer un paiement partiel</div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Mode de paiement *</label>
                        <select class="form-control" name="mode_paiement" required>
                            <option value="">Selectionner</option>
                            <option value="especes">Especes</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="carte">Carte bancaire</option>
                            <option value="virement">Virement</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Reference (optionnel)</label>
                        <input type="text" class="form-control" name="reference" placeholder="N. transaction...">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Notes (optionnel)</label>
                    <textarea class="form-control" name="notes" rows="2" placeholder="Observations..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEncaisserShow')">Annuler</button>
                <button type="submit" class="btn btn-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                    Valider le paiement
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- Modal Avoir --}}
@if($facture->statut == 'payee')
<div class="modal-overlay" id="modalAvoir">
    <div class="modal">
        <div class="modal-header" style="background:#fef3c7;">
            <h3 class="modal-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M3 10h10a8 8 0 018 8v2M3 10l6 6M3 10l6-6"/></svg>
                Creer un avoir
            </h3>
            <button class="modal-close" onclick="closeModal('modalAvoir')">&times;</button>
        </div>
        <form action="{{ route('caisse.factures.avoir', $facture) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Montant de l'avoir *</label>
                    <input type="number" class="form-control" name="montant" required min="1" max="{{ $montantNet }}" step="1" placeholder="Montant a rembourser">
                </div>
                <div class="form-group">
                    <label class="form-label">Motif *</label>
                    <textarea class="form-control" name="motif" rows="3" required placeholder="Raison de l'avoir..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalAvoir')">Annuler</button>
                <button type="submit" class="btn btn-warning">Creer l'avoir</button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- Refund modals for each payment --}}
@if(isset($facture->paiements))
@foreach($facture->paiements as $paiement)
<div class="modal-overlay" id="modalRemboursement{{ $paiement->id }}">
    <div class="modal">
        <div class="modal-header" style="background:#fef2f2;">
            <h3 class="modal-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M3 10h10a8 8 0 018 8v2M3 10l6 6M3 10l6-6"/></svg>
                Rembourser le paiement {{ $paiement->numero_recu ?? '#'.$paiement->id }}
            </h3>
            <button class="modal-close" onclick="closeModal('modalRemboursement{{ $paiement->id }}')">&times;</button>
        </div>
        <form action="{{ route('caisse.factures.remboursement', [$facture, $paiement]) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div style="background:var(--gray-50);border-radius:10px;padding:16px;margin-bottom:20px;">
                    <div style="display:flex;justify-content:space-between;">
                        <div>
                            <div style="font-size:.78rem;color:var(--gray-500);">Montant du paiement</div>
                            <div style="font-weight:700;font-size:1.1rem;">{{ number_format($paiement->montant, 0, ',', ' ') }} F</div>
                        </div>
                        <div>
                            <div style="font-size:.78rem;color:var(--gray-500);">Mode</div>
                            <div style="font-weight:600;">{{ ucfirst($paiement->mode_paiement) }}</div>
                        </div>
                        <div>
                            <div style="font-size:.78rem;color:var(--gray-500);">Date</div>
                            <div style="font-weight:600;">{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Montant a rembourser *</label>
                    <input type="number" class="form-control" name="montant" required min="1" max="{{ $paiement->montant }}" value="{{ $paiement->montant }}" step="1">
                    <div style="font-size:.78rem;color:var(--gray-500);margin-top:4px;">Maximum: {{ number_format($paiement->montant, 0, ',', ' ') }} F</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Motif du remboursement *</label>
                    <input type="text" class="form-control" name="motif" required placeholder="Raison du remboursement..." maxlength="255">
                </div>
                <div class="form-group">
                    <label class="form-label">Mode de remboursement *</label>
                    <select class="form-control" name="mode_remboursement" required>
                        <option value="">Selectionner</option>
                        <option value="especes">Especes</option>
                        <option value="carte">Carte bancaire</option>
                        <option value="virement">Virement</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Notes (optionnel)</label>
                    <textarea class="form-control" name="notes" rows="2" placeholder="Observations..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalRemboursement{{ $paiement->id }}')">Annuler</button>
                <button type="submit" class="btn" style="background:var(--danger);color:#fff;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M3 10h10a8 8 0 018 8v2M3 10l6 6M3 10l6-6"/></svg>
                    Effectuer le remboursement
                </button>
            </div>
        </form>
    </div>
</div>
@endforeach
@endif

{{-- Modal Prise en charge --}}
@if($facture->statut != 'annulee' && $facture->statut != 'payee')
<div class="modal-overlay" id="modalPriseEnCharge">
    <div class="modal">
        <div class="modal-header" style="background:#f5f3ff;">
            <h3 class="modal-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" style="margin-right:8px;"><path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/><path d="M9 14l2 2 4-4"/></svg>
                Appliquer une prise en charge
            </h3>
            <button class="modal-close" onclick="closeModal('modalPriseEnCharge')">&times;</button>
        </div>
        <form action="{{ route('caisse.factures.prise-en-charge', $facture) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div style="background:var(--gray-50);border-radius:10px;padding:16px;margin-bottom:20px;">
                    <div style="display:flex;justify-content:space-between;">
                        <div>
                            <div style="font-size:.78rem;color:var(--gray-500);">Montant net facture</div>
                            <div style="font-weight:700;font-size:1.1rem;">{{ number_format($montantNet, 0, ',', ' ') }} F</div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Type de prise en charge *</label>
                    <select class="form-control" name="type_prise_en_charge" required>
                        <option value="">Selectionner</option>
                        <option value="assurance" {{ $facture->type_prise_en_charge == 'assurance' ? 'selected' : '' }}>Assurance</option>
                        <option value="mutuelle" {{ $facture->type_prise_en_charge == 'mutuelle' ? 'selected' : '' }}>Mutuelle</option>
                        <option value="indigent" {{ $facture->type_prise_en_charge == 'indigent' ? 'selected' : '' }}>Indigent</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Organisme (optionnel)</label>
                    <input type="text" class="form-control" name="organisme_prise_en_charge" placeholder="Nom de l'organisme..." value="{{ $facture->organisme_prise_en_charge }}" maxlength="255">
                </div>
                <div class="form-group">
                    <label class="form-label">Numero d'assurance (optionnel)</label>
                    <input type="text" class="form-control" name="numero_assurance" placeholder="Numero de police..." value="{{ $facture->numero_assurance }}" maxlength="100">
                </div>
                <div class="form-group">
                    <label class="form-label">Taux de couverture (%) *</label>
                    <input type="number" class="form-control" name="taux_couverture" required min="1" max="100" value="{{ $facture->taux_couverture ?: 80 }}" step="1" id="tauxCouverture" oninput="calculerPriseEnCharge()">
                    <div style="font-size:.78rem;color:var(--gray-500);margin-top:4px;">Pourcentage pris en charge par l'organisme</div>
                </div>
                <div style="background:#f5f3ff;border-radius:10px;padding:16px;margin-top:16px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                        <span style="color:var(--gray-600);font-size:.85rem;">Montant couvert</span>
                        <strong id="montantCouvertCalc" style="color:#7c3aed;">0 F</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:var(--gray-600);font-size:.85rem;">Part patient</span>
                        <strong id="montantPatientCalc" style="color:var(--primary);">0 F</strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalPriseEnCharge')">Annuler</button>
                <button type="submit" class="btn" style="background:#7c3aed;color:#fff;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M9 14l2 2 4-4"/></svg>
                    Appliquer la prise en charge
                </button>
            </div>
        </form>
    </div>
</div>
<script>
function calculerPriseEnCharge() {
    var montantBase = {{ $montantNet }};
    var taux = parseInt(document.getElementById('tauxCouverture').value) || 0;
    var couvert = Math.round(montantBase * taux / 100);
    var patient = montantBase - couvert;
    document.getElementById('montantCouvertCalc').textContent = couvert.toLocaleString('fr-FR') + ' F';
    document.getElementById('montantPatientCalc').textContent = patient.toLocaleString('fr-FR') + ' F';
}
calculerPriseEnCharge();
</script>
@endif

@endsection
