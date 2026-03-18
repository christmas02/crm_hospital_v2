@extends('layouts.medicare')

@section('title', 'Historique - Caisse')
@section('sidebar-subtitle', 'Caisse')
@section('user-color', '#d97706')
@section('header-title', 'Historique des paiements')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('caisse._sidebar')
@endif
@endsection

@push('styles')
<style>
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

{{-- Filters --}}
<div class="toolbar" style="margin-bottom:20px;">
    <div class="filters">
        <form action="{{ route('caisse.historique') }}" method="GET" style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
            <div style="display:flex;align-items:center;gap:4px;">
                <label style="font-size:.82rem;color:var(--gray-600);white-space:nowrap;">Du</label>
                <input type="date" class="filter-input" name="date_debut" value="{{ request('date_debut') }}">
            </div>
            <div style="display:flex;align-items:center;gap:4px;">
                <label style="font-size:.82rem;color:var(--gray-600);white-space:nowrap;">Au</label>
                <input type="date" class="filter-input" name="date_fin" value="{{ request('date_fin') }}">
            </div>
            <select class="filter-select" name="mode" onchange="this.form.submit()">
                <option value="">Tous modes</option>
                <option value="especes" {{ request('mode') == 'especes' ? 'selected' : '' }}>Especes</option>
                <option value="mobile_money" {{ request('mode') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                <option value="carte" {{ request('mode') == 'carte' ? 'selected' : '' }}>Carte</option>
                <option value="virement" {{ request('mode') == 'virement' ? 'selected' : '' }}>Virement</option>
                <option value="cheque" {{ request('mode') == 'cheque' ? 'selected' : '' }}>Cheque</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                Filtrer
            </button>
            @if(request()->hasAny(['date_debut', 'date_fin', 'mode']))
            <a href="{{ route('caisse.historique') }}" class="btn btn-outline btn-sm">Reinitialiser</a>
            @endif
        </form>
        <div style="margin-top:10px;display:flex;align-items:center;gap:10px;">
            <input type="text" id="historiqueSearch" class="filter-input" placeholder="Rechercher dans la page courante..." style="max-width:350px;">
            <span id="historiqueCount" style="font-size:.82rem;color:var(--gray-500);white-space:nowrap;"></span>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            Historique des paiements
        </h2>
        @if(isset($totaux))
        <div style="font-weight:700;color:var(--success);font-size:1.1rem;">
            Total: {{ number_format($totaux['total'], 0, ',', ' ') }} F ({{ $totaux['count'] }} paiements)
        </div>
        @endif
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>N. Recu</th>
                        <th>Patient</th>
                        <th>Type</th>
                        <th style="text-align:right;">Montant</th>
                        <th>Mode</th>
                        <th>Encaisse par</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paiements as $paiement)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y H:i') }}
                            </div>
                        </td>
                        <td>
                            @if($paiement->numero_recu ?? null)
                            <span style="font-weight:600;font-size:.85rem;color:var(--primary);">{{ $paiement->numero_recu }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="user-cell">
                                <div class="avatar" style="width:30px;height:30px;font-size:.7rem;">{{ strtoupper(substr($paiement->patient->prenom ?? '', 0, 1) . substr($paiement->patient->nom ?? '', 0, 1)) }}</div>
                                <span>{{ $paiement->patient->prenom ?? '' }} {{ $paiement->patient->nom ?? '' }}</span>
                            </div>
                        </td>
                        <td>{{ $paiement->facture->type ?? 'Consultation' }}</td>
                        <td style="text-align:right;">
                            <strong class="text-success" style="font-size:1rem;">{{ number_format($paiement->montant, 0, ',', ' ') }} F</strong>
                        </td>
                        <td>
                            @php
                                $modes = ['especes' => 'Especes', 'mobile_money' => 'Mobile Money', 'carte' => 'Carte', 'virement' => 'Virement', 'cheque' => 'Cheque'];
                                $modeClass = 'mode-' . ($paiement->mode_paiement ?? 'especes');
                            @endphp
                            <span class="mode-badge {{ $modeClass }}">{{ $modes[$paiement->mode_paiement] ?? $paiement->mode_paiement }}</span>
                        </td>
                        <td>
                            @if($paiement->encaisseur ?? null)
                            <span style="font-size:.85rem;">{{ $paiement->encaisseur->name ?? '-' }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if($paiement->numero_recu ?? null)
                            <a href="{{ route('caisse.factures.recu', [$paiement->facture, $paiement]) }}" target="_blank" class="btn btn-outline btn-sm" title="Recu PDF" style="padding:4px 8px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                            </a>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div style="text-align:center;padding:40px 20px;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:12px;"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                <div style="color:var(--gray-500);font-weight:600;margin-bottom:4px;">Aucun paiement trouve</div>
                                <div style="color:var(--gray-400);font-size:0.85rem;">Modifiez vos filtres pour trouver des paiements</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($paiements->count() > 0)
                <tfoot style="background:var(--gray-50);">
                    <tr>
                        <td colspan="4" style="text-align:right;font-weight:700;font-size:.95rem;">Total affiche:</td>
                        <td style="text-align:right;font-weight:800;font-size:1.1rem;color:var(--success);">{{ number_format($paiements->sum('montant'), 0, ',', ' ') }} F</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

@if($paiements->hasPages())
<div class="mt-4">
    {{ $paiements->appends(request()->query())->links() }}
</div>
@endif

@push('scripts')
<script>
(function() {
    var searchInput = document.getElementById('historiqueSearch');
    var rows = document.querySelectorAll('.table-patients tbody tr');
    var countEl = document.getElementById('historiqueCount');

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
