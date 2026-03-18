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

@push('styles')
<style>
@keyframes pulse { 0%,100% { opacity:1; } 50% { opacity:.4; } }
.invoice-card {
    background:#fff;
    border:1px solid var(--border);
    border-radius:12px;
    padding:16px;
    margin-bottom:12px;
    transition:all .2s ease;
}
.invoice-card:hover {
    border-color:var(--primary);
    box-shadow:0 4px 12px rgba(0,0,0,.08);
    transform:translateY(-1px);
}
.timeline-item {
    display:flex;
    gap:12px;
    padding:12px 0;
    border-bottom:1px solid var(--gray-100);
}
.timeline-item:last-child { border-bottom:none; }
.timeline-dot {
    width:8px;height:8px;border-radius:50%;background:var(--success);margin-top:6px;flex-shrink:0;
}
.mode-badge {
    display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:600;
}
.mode-especes { background:#fef3c7;color:#92400e; }
.mode-mobile_money { background:#dbeafe;color:#1e40af; }
.mode-carte { background:#ede9fe;color:#5b21b6; }
.mode-virement { background:#d1fae5;color:#065f46; }
.mode-cheque { background:#fce7f3;color:#9d174d; }
</style>
@endpush

@section('content')

{{-- Session bar --}}
@if(!isset($sessionOuverte) || !$sessionOuverte)
<div style="background:linear-gradient(135deg,var(--warning-light),#fef9c3);border:2px solid var(--warning);border-radius:14px;padding:20px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div style="display:flex;align-items:center;gap:12px;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--warning)" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
        <div>
            <div style="font-weight:700;color:var(--gray-800);">Caisse fermee</div>
            <div style="font-size:.8rem;color:var(--gray-600);">Ouvrez votre session de caisse pour commencer les encaissements</div>
        </div>
    </div>
    <button class="btn btn-primary" onclick="openModal('modalOuvrirSession')">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
        Ouvrir la caisse
    </button>
</div>
@else
<div style="background:linear-gradient(135deg,var(--success-light),#dcfce7);border:2px solid var(--success);border-radius:14px;padding:16px 20px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
    <div style="display:flex;align-items:center;gap:12px;">
        <div style="width:10px;height:10px;border-radius:50%;background:var(--success);animation:pulse 2s infinite;"></div>
        <div>
            <div style="font-weight:700;color:var(--gray-800);">Caisse ouverte</div>
            <div style="font-size:.78rem;color:var(--gray-600);">Depuis {{ $sessionOuverte->ouverture->format('H:i') }} &bull; Solde ouverture: {{ number_format($sessionOuverte->solde_ouverture, 0, ',', ' ') }} F</div>
        </div>
    </div>
    <button class="btn btn-danger btn-sm" onclick="openModal('modalFermerSession')">Fermer la caisse</button>
</div>
@endif

{{-- Stats: 5 cards --}}
<div class="stats" style="grid-template-columns: repeat(5, 1fr);">
    <div class="stat-card" style="border-left: 4px solid var(--secondary);">
        <div>
            <div class="stat-label">Encaissements du jour</div>
            <div class="stat-value text-success">{{ number_format($stats['encaisse_jour'] ?? 0, 0, ',', ' ') }} F</div>
            <div class="stat-sub">Total encaisse aujourd'hui</div>
        </div>
        <div class="stat-icon green">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--warning);">
        <div>
            <div class="stat-label">Factures en attente</div>
            <div class="stat-value" style="color:var(--warning);">{{ $stats['en_attente_count'] ?? 0 }}</div>
            <div class="stat-sub">Non encore encaissees</div>
        </div>
        <div class="stat-icon orange">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--danger);">
        <div>
            <div class="stat-label">Montant en attente</div>
            <div class="stat-value text-danger">{{ number_format($stats['en_attente_montant'] ?? 0, 0, ',', ' ') }} F</div>
            <div class="stat-sub">Reste a encaisser</div>
        </div>
        <div class="stat-icon red">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--gray-400);">
        <div>
            <div class="stat-label">Depenses du jour</div>
            <div class="stat-value" style="color:var(--gray-600);">{{ number_format($stats['depenses_jour'] ?? 0, 0, ',', ' ') }} F</div>
            <div class="stat-sub">Total des depenses</div>
        </div>
        <div class="stat-icon" style="background:var(--gray-100);color:var(--gray-500);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/><line x1="3" y1="3" x2="21" y2="21"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid #06b6d4;">
        <div>
            <div class="stat-label">Paiements partiels</div>
            <div class="stat-value" style="color:#06b6d4;">{{ $stats['partielles'] ?? 0 }}</div>
            <div class="stat-sub">Factures partiellement payees</div>
        </div>
        <div class="stat-icon cyan">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
        </div>
    </div>
</div>

{{-- Chart Recettes vs Depenses --}}
<div class="card mt-4" style="margin-bottom:20px;">
    <div class="card-header"><h2 class="card-title">Recettes vs Depenses (7 derniers jours)</h2></div>
    <div class="card-body"><div class="chart-container"><canvas id="chartFinance"></canvas></div></div>
</div>

{{-- Ventilation par mode de paiement --}}
<div class="card mt-4" style="margin-bottom:20px;">
    <div class="card-header"><h2 class="card-title">Ventilation par mode de paiement</h2></div>
    <div class="card-body">
        @php $totalVentil = $ventilationMode->sum('total') ?: 1; @endphp
        @forelse($ventilationMode as $mode)
        @php
            $pct = round(($mode->total / $totalVentil) * 100);
            $colors = ['especes' => 'var(--success)', 'carte' => 'var(--primary)', 'mobile_money' => 'var(--warning)', 'cheque' => 'var(--accent)', 'virement' => 'var(--gray-500)'];
            $labels = ['especes' => 'Espèces', 'carte' => 'Carte', 'mobile_money' => 'Mobile Money', 'cheque' => 'Chèque', 'virement' => 'Virement'];
            $color = $colors[$mode->mode_paiement] ?? 'var(--gray-400)';
        @endphp
        <div style="margin-bottom:12px;">
            <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                <span style="font-size:.82rem;font-weight:600;">{{ $labels[$mode->mode_paiement] ?? ucfirst($mode->mode_paiement) }}</span>
                <span style="font-size:.82rem;font-weight:700;">{{ number_format($mode->total, 0, ',', ' ') }} F <span style="color:var(--gray-400);font-weight:400;">({{ $pct }}%)</span></span>
            </div>
            <div style="width:100%;height:8px;background:var(--gray-100);border-radius:4px;overflow:hidden;">
                <div style="width:{{ $pct }}%;height:100%;background:{{ $color }};border-radius:4px;"></div>
            </div>
        </div>
        @empty
        <p class="text-muted text-center">Aucun encaissement aujourd'hui</p>
        @endforelse
    </div>
</div>

{{-- Main grid: 2 columns --}}
<div class="grid-2">
    {{-- Left: Factures a encaisser --}}
    <div class="card">
        <div class="card-header" style="background:var(--warning-light);">
            <h2 class="card-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                Factures a encaisser
            </h2>
            @if(isset($facturesEnAttente) && $facturesEnAttente->count() > 5)
            <a href="{{ route('caisse.factures.index') }}" class="btn btn-outline btn-sm">Voir tout</a>
            @endif
        </div>
        <div class="card-body" style="padding:16px;">
            @forelse(($facturesEnAttente ?? collect())->take(6) as $facture)
            <div class="invoice-card">
                @php
                    $montantRestant = $facture->montant_total - ($facture->montant_paye ?? 0);
                    $isPartial = ($facture->montant_paye ?? 0) > 0 && $montantRestant > 0;
                @endphp
                <div style="display:flex;align-items:center;gap:12px;">
                    <div class="avatar" style="width:38px;height:38px;font-size:.8rem;flex-shrink:0;">{{ strtoupper(substr($facture->patient->prenom ?? '', 0, 1) . substr($facture->patient->nom ?? '', 0, 1)) }}</div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:600;font-size:.9rem;">{{ $facture->patient->prenom ?? '' }} {{ $facture->patient->nom ?? '' }}</div>
                        <div style="font-size:.75rem;color:var(--gray-500);">{{ $facture->numero }} &bull; {{ $facture->date->format('d/m/Y') }}</div>
                    </div>
                    @if($isPartial)
                    <span class="badge" style="background:#ecfeff;color:#0891b2;font-size:.68rem;flex-shrink:0;">Partiel</span>
                    @endif
                    <div style="text-align:right;flex-shrink:0;min-width:100px;">
                        <div style="font-size:1.1rem;font-weight:800;color:var(--gray-800);">{{ number_format($facture->montant_total, 0, ',', ' ') }} F</div>
                        @if($isPartial)
                        <div style="font-size:.72rem;color:var(--danger);font-weight:600;">Reste: {{ number_format($montantRestant, 0, ',', ' ') }} F</div>
                        @endif
                    </div>
                    @if($sessionOuverte)
                    <button class="btn btn-success btn-sm" onclick="openEncaissement({{ $facture->id }})" style="padding:8px 14px;flex-shrink:0;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                    </button>
                    @else
                    <span class="btn btn-secondary btn-sm" style="padding:8px 14px;opacity:.5;cursor:not-allowed;flex-shrink:0;" title="Ouvrez la caisse">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    </span>
                    @endif
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:40px 20px;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:12px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
                <div style="color:var(--gray-500);font-weight:600;margin-bottom:4px;">Aucune facture en attente</div>
                <div style="color:var(--gray-400);font-size:0.85rem;">Toutes les factures ont ete traitees</div>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Right: Derniers encaissements --}}
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                Derniers encaissements
            </h2>
        </div>
        <div class="card-body" style="padding:16px;">
            @forelse($encaissementsJour ?? [] as $paiement)
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
                        <div style="min-width:0;">
                            <div style="font-weight:600;font-size:.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $paiement->patient->prenom ?? '' }} {{ $paiement->patient->nom ?? '' }}</div>
                            <div style="display:flex;align-items:center;gap:8px;margin-top:4px;flex-wrap:wrap;">
                                @php
                                    $modeClass = 'mode-' . ($paiement->mode_paiement ?? 'especes');
                                    $modes = ['especes' => 'Especes', 'mobile_money' => 'Mobile Money', 'carte' => 'Carte', 'virement' => 'Virement', 'cheque' => 'Cheque'];
                                @endphp
                                <span class="mode-badge {{ $modeClass }}">{{ $modes[$paiement->mode_paiement ?? ''] ?? $paiement->mode_paiement ?? '-' }}</span>
                                @if($paiement->numero_recu ?? null)
                                <span style="font-size:.72rem;color:var(--gray-400);">{{ $paiement->numero_recu }}</span>
                                @endif
                            </div>
                        </div>
                        <div style="text-align:right;flex-shrink:0;">
                            <div style="font-weight:700;color:var(--success);font-size:1rem;">+{{ number_format($paiement->montant ?? 0, 0, ',', ' ') }} F</div>
                            <div style="font-size:.72rem;color:var(--gray-400);margin-top:2px;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:2px;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                {{ isset($paiement->date_paiement) ? \Carbon\Carbon::parse($paiement->date_paiement)->format('H:i') : '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:40px 20px;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:12px;"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
                <div style="color:var(--gray-500);font-weight:600;margin-bottom:4px;">Aucun paiement aujourd'hui</div>
                <div style="color:var(--gray-400);font-size:0.85rem;">Les paiements du jour apparaitront ici</div>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Modal Ouvrir Session --}}
<div class="modal-overlay" id="modalOuvrirSession">
    <div class="modal">
        <div class="modal-header" style="background:var(--primary-light);">
            <h3 class="modal-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                Ouvrir la caisse
            </h3>
            <button class="modal-close" onclick="closeModal('modalOuvrirSession')">&times;</button>
        </div>
        <form action="{{ route('caisse.session.ouvrir') }}" method="POST">
            @csrf
            <div class="modal-body">
                @if($dernierSoldeFermeture > 0)
                <div style="background:var(--primary-light);border-radius:10px;padding:14px;margin-bottom:16px;display:flex;align-items:center;gap:12px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                    <div>
                        <div style="font-size:.78rem;color:var(--primary-dark);font-weight:600;">Dernier solde de fermeture</div>
                        <div style="font-size:1.1rem;font-weight:800;color:var(--primary);">{{ number_format($dernierSoldeFermeture, 0, ',', ' ') }} F</div>
                    </div>
                </div>
                @endif
                <div class="form-group">
                    <label class="form-label">Solde d'ouverture *</label>
                    <input type="number" class="form-control" name="solde_ouverture" required min="0" step="1" value="{{ $dernierSoldeFermeture }}" placeholder="Montant en caisse a l'ouverture">
                    <div style="font-size:.78rem;color:var(--gray-500);margin-top:4px;">Pré-rempli avec le dernier solde de fermeture. Ajustez si nécessaire après comptage physique.</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Notes (optionnel)</label>
                    <textarea class="form-control" name="notes_ouverture" rows="3" placeholder="Observations a l'ouverture..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalOuvrirSession')">Annuler</button>
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    Ouvrir la session
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Fermer Session --}}
@if(isset($sessionOuverte) && $sessionOuverte)
<div class="modal-overlay" id="modalFermerSession">
    <div class="modal">
        <div class="modal-header" style="background:var(--danger-light, #fee2e2);">
            <h3 class="modal-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/><line x1="12" y1="15" x2="12" y2="17"/></svg>
                Fermer la caisse
            </h3>
            <button class="modal-close" onclick="closeModal('modalFermerSession')">&times;</button>
        </div>
        <form action="{{ route('caisse.session.fermer') }}" method="POST">
            @csrf
            <div class="modal-body">
                {{-- Resume de la session --}}
                <div style="background:var(--gray-50);border-radius:10px;padding:16px;margin-bottom:20px;">
                    <div style="font-weight:700;margin-bottom:12px;color:var(--gray-700);">Resume de la session</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                        <div style="padding:10px;background:#fff;border-radius:8px;border:1px solid var(--border);">
                            <div style="font-size:.75rem;color:var(--gray-500);text-transform:uppercase;">Solde ouverture</div>
                            <div style="font-weight:700;font-size:1.1rem;">{{ number_format($sessionOuverte->solde_ouverture, 0, ',', ' ') }} F</div>
                        </div>
                        <div style="padding:10px;background:#fff;border-radius:8px;border:1px solid var(--border);">
                            <div style="font-size:.75rem;color:var(--gray-500);text-transform:uppercase;">Encaissements</div>
                            <div style="font-weight:700;font-size:1.1rem;color:var(--success);">+{{ number_format($stats['encaisse_jour'] ?? 0, 0, ',', ' ') }} F</div>
                        </div>
                        <div style="padding:10px;background:#fff;border-radius:8px;border:1px solid var(--border);">
                            <div style="font-size:.75rem;color:var(--gray-500);text-transform:uppercase;">Depenses</div>
                            <div style="font-weight:700;font-size:1.1rem;color:var(--danger);">-{{ number_format($stats['depenses_jour'] ?? 0, 0, ',', ' ') }} F</div>
                        </div>
                        <div style="padding:10px;background:var(--primary-light);border-radius:8px;border:1px solid var(--primary);">
                            <div style="font-size:.75rem;color:var(--gray-500);text-transform:uppercase;">Solde attendu</div>
                            <div style="font-weight:700;font-size:1.1rem;color:var(--primary);">{{ number_format(($sessionOuverte->solde_ouverture + ($stats['encaisse_jour'] ?? 0) - ($stats['depenses_jour'] ?? 0)), 0, ',', ' ') }} F</div>
                        </div>
                    </div>
                </div>
                @php
                    $soldeAttendu = $sessionOuverte->solde_ouverture + ($stats['encaisse_jour'] ?? 0) - ($stats['depenses_jour'] ?? 0);
                @endphp
                <div class="form-group">
                    <label class="form-label">Solde de fermeture *</label>
                    <input type="number" class="form-control" name="solde_fermeture" id="soldeFermeture" required min="0" step="1" value="{{ $soldeAttendu }}" oninput="calculerEcart()">
                    <div style="font-size:.78rem;color:var(--gray-500);margin-top:4px;">Pré-rempli avec le solde calculé. Ajustez après comptage physique si différent.</div>
                </div>
                <!-- Indicateur d'écart en temps réel -->
                <div id="ecartIndicateur" style="border-radius:10px;padding:14px;margin-bottom:16px;display:none;">
                </div>
                <div class="form-group">
                    <label class="form-label">Notes (optionnel)</label>
                    <textarea class="form-control" name="notes_fermeture" rows="3" placeholder="Observations a la fermeture..."></textarea>
                </div>
                <script>
                var soldeAttenduVal = {{ $soldeAttendu }};
                function calculerEcart() {
                    var saisi = parseInt(document.getElementById('soldeFermeture').value) || 0;
                    var ecart = saisi - soldeAttenduVal;
                    var el = document.getElementById('ecartIndicateur');
                    if (ecart === 0) {
                        el.style.display = 'flex';
                        el.style.background = 'var(--success-light)';
                        el.style.gap = '10px';
                        el.style.alignItems = 'center';
                        el.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg><div><div style="font-weight:700;color:var(--success);">Aucun écart</div><div style="font-size:.78rem;color:var(--gray-600);">Le solde correspond exactement au montant attendu</div></div>';
                    } else {
                        el.style.display = 'flex';
                        el.style.gap = '10px';
                        el.style.alignItems = 'center';
                        var isPositif = ecart > 0;
                        el.style.background = isPositif ? 'var(--warning-light)' : 'var(--danger-light)';
                        el.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="' + (isPositif ? 'var(--warning)' : 'var(--danger)') + '" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg><div><div style="font-weight:700;color:' + (isPositif ? 'var(--warning)' : 'var(--danger)') + ';">Écart de ' + (isPositif ? '+' : '') + ecart.toLocaleString('fr-FR') + ' F</div><div style="font-size:.78rem;color:var(--gray-600);">' + (isPositif ? 'Excédent constaté — vérifiez les encaissements' : 'Déficit constaté — vérifiez les mouvements') + '</div></div>';
                    }
                }
                </script>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalFermerSession')">Annuler</button>
                <button type="submit" class="btn btn-danger">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    Fermer la session
                </button>
            </div>
        </form>
    </div>
</div>
@endif

{{-- Modal Encaissement --}}
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
                {{-- Info Patient & Facture --}}
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

                {{-- Detail des prestations --}}
                <div class="card mb-4">
                    <div class="card-header"><h4 class="card-title" style="font-size:0.95rem;">Detail des prestations</h4></div>
                    <div class="card-body no-pad">
                        <div class="table-wrap">
                            <table class="table-patients">
                                <thead><tr><th>Designation</th><th style="text-align:center;">Qte</th><th style="text-align:right;">Prix unit.</th><th style="text-align:right;">Total</th></tr></thead>
                                <tbody id="encaissementLignes"></tbody>
                                <tfoot style="background:var(--gray-100);">
                                    <tr>
                                        <td colspan="3" style="text-align:right;font-weight:bold;">TOTAL FACTURE</td>
                                        <td style="text-align:right;font-weight:bold;font-size:1.15rem;color:var(--primary);" id="encTotal">0 F</td>
                                    </tr>
                                    <tr id="encDejaPayeRow" style="display:none;">
                                        <td colspan="3" style="text-align:right;font-weight:600;color:var(--success);">Deja paye</td>
                                        <td style="text-align:right;font-weight:600;color:var(--success);" id="encDejaPaye">0 F</td>
                                    </tr>
                                    <tr id="encRestantRow" style="display:none;">
                                        <td colspan="3" style="text-align:right;font-weight:bold;color:var(--danger);">RESTE A PAYER</td>
                                        <td style="text-align:right;font-weight:bold;font-size:1.25rem;color:var(--danger);" id="encRestant">0 F</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Montant et mode de paiement --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Montant a encaisser *</label>
                        <input type="number" class="form-control" name="montant" id="encMontantInput" required min="1" step="1" placeholder="Montant">
                        <div style="font-size:.78rem;color:var(--gray-500);margin-top:4px;" id="encMontantHint">Vous pouvez effectuer un paiement partiel</div>
                    </div>
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
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Reference (optionnel)</label>
                        <input type="text" class="form-control" name="reference" placeholder="N. transaction, recu...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Notes (optionnel)</label>
                        <input type="text" class="form-control" name="notes" placeholder="Observations...">
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

    fetch('/caisse/factures/' + factureId + '/details')
        .then(response => response.json())
        .then(data => {
            document.getElementById('encPatientNom').textContent = data.patient.prenom + ' ' + data.patient.nom;
            document.getElementById('encPatientTel').textContent = data.patient.telephone || '-';
            document.getElementById('encFactureNum').textContent = 'N. ' + data.numero;
            document.getElementById('encFactureDate').textContent = data.date;

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

            document.getElementById('encTotal').textContent = new Intl.NumberFormat('fr-FR').format(data.montant_total) + ' F';

            var montantPaye = data.montant_paye || 0;
            var montantRestant = data.montant_total - montantPaye;

            if (montantPaye > 0) {
                document.getElementById('encDejaPayeRow').style.display = '';
                document.getElementById('encDejaPaye').textContent = new Intl.NumberFormat('fr-FR').format(montantPaye) + ' F';
                document.getElementById('encRestantRow').style.display = '';
                document.getElementById('encRestant').textContent = new Intl.NumberFormat('fr-FR').format(montantRestant) + ' F';
            } else {
                document.getElementById('encDejaPayeRow').style.display = 'none';
                document.getElementById('encRestantRow').style.display = 'none';
            }

            document.getElementById('encMontantInput').value = montantRestant;
            document.getElementById('encMontantInput').max = montantRestant;
            document.getElementById('encMontantHint').textContent = 'Reste a payer: ' + new Intl.NumberFormat('fr-FR').format(montantRestant) + ' F';

            openModal('modalEncaissement');
        })
        .catch(error => {
            console.error('Erreur:', error);
            openModal('modalEncaissement');
        });
}
</script>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctxFinance = document.getElementById('chartFinance');
    if (ctxFinance) {
        new Chart(ctxFinance, {
            type: 'bar',
            data: {
                labels: @json($financeParJour->pluck('date')),
                datasets: [
                    {
                        label: 'Recettes',
                        data: @json($financeParJour->pluck('recettes')),
                        backgroundColor: 'rgb(5, 150, 105)',
                        borderRadius: 4,
                    },
                    {
                        label: 'Depenses',
                        data: @json($financeParJour->pluck('depenses')),
                        backgroundColor: 'rgb(220, 38, 38)',
                        borderRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 16, usePointStyle: true, pointStyle: 'circle' }
                    }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
});
</script>
@endpush

@endsection
