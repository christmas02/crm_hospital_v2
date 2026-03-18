@extends('layouts.medicare')

@section('title', 'Sessions de caisse - MediCare Pro')
@section('sidebar-subtitle', 'Caisse')
@section('user-color', '#d97706')
@section('header-title', 'Sessions de caisse')

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

{{-- Filters --}}
<div class="toolbar" style="margin-bottom:20px;">
    <div class="filters">
        <form action="{{ route('caisse.sessions') }}" method="GET" style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
            <input type="date" class="filter-input" name="date_debut" value="{{ request('date_debut') }}" placeholder="Date debut">
            <input type="date" class="filter-input" name="date_fin" value="{{ request('date_fin') }}" placeholder="Date fin">
            <button type="submit" class="btn btn-primary btn-sm">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                Filtrer
            </button>
            @if(request()->hasAny(['date_debut', 'date_fin']))
            <a href="{{ route('caisse.sessions') }}" class="btn btn-outline btn-sm">Réinitialiser</a>
            @endif
        </form>
        <div style="margin-top:10px;display:flex;align-items:center;gap:10px;">
            <input type="text" id="sessionsSearch" class="filter-input" placeholder="Rechercher dans la page courante..." style="max-width:350px;">
            <span id="sessionsCount" style="font-size:.82rem;color:var(--gray-500);white-space:nowrap;"></span>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            Historique des sessions
        </h2>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table class="table-patients">
                <thead>
                    <tr>
                        <th>Ouverture</th>
                        <th>Fermeture</th>
                        <th>Utilisateur</th>
                        <th style="text-align:right;">Solde ouv.</th>
                        <th style="text-align:right;">Encaissements</th>
                        <th style="text-align:right;">Depenses</th>
                        <th style="text-align:right;">Solde ferm.</th>
                        <th style="text-align:right;">Ecart</th>
                        <th style="text-align:center;">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions ?? [] as $session)
                    @php
                        $encaissements = $session->total_encaissements ?? 0;
                        $depenses = $session->total_depenses ?? 0;
                        $soldeAttendu = $session->solde_ouverture + $encaissements - $depenses;
                        $ecart = $session->solde_fermeture !== null ? $session->solde_fermeture - $soldeAttendu : null;
                    @endphp
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                {{ $session->ouverture->format('d/m/Y H:i') }}
                            </div>
                        </td>
                        <td>
                            @if($session->fermeture)
                            {{ $session->fermeture->format('d/m/Y H:i') }}
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="user-cell">
                                <div class="avatar" style="width:30px;height:30px;font-size:.7rem;">{{ strtoupper(substr($session->user->name ?? '', 0, 2)) }}</div>
                                <span>{{ $session->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td style="text-align:right;font-weight:600;">{{ number_format($session->solde_ouverture, 0, ',', ' ') }} F</td>
                        <td style="text-align:right;font-weight:600;color:var(--success);">+{{ number_format($encaissements, 0, ',', ' ') }} F</td>
                        <td style="text-align:right;font-weight:600;color:var(--danger);">-{{ number_format($depenses, 0, ',', ' ') }} F</td>
                        <td style="text-align:right;font-weight:600;">
                            @if($session->solde_fermeture !== null)
                            {{ number_format($session->solde_fermeture, 0, ',', ' ') }} F
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td style="text-align:right;font-weight:700;">
                            @if($ecart !== null)
                                @if($ecart == 0)
                                <span style="color:var(--success);">0 F</span>
                                @elseif($ecart > 0)
                                <span style="color:var(--warning);">+{{ number_format($ecart, 0, ',', ' ') }} F</span>
                                @else
                                <span style="color:var(--danger);">{{ number_format($ecart, 0, ',', ' ') }} F</span>
                                @endif
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if($session->fermeture)
                            <span class="badge badge-success">Fermee</span>
                            @else
                            <span class="badge badge-warning">Ouverte</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div style="text-align:center;padding:40px 20px;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:12px;"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                                <div style="color:var(--gray-500);font-weight:600;margin-bottom:4px;">Aucune session trouvee</div>
                                <div style="color:var(--gray-400);font-size:0.85rem;">Les sessions de caisse apparaitront ici</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if(isset($sessions) && $sessions->hasPages())
<div class="mt-4">
    {{ $sessions->links() }}
</div>
@endif

@push('scripts')
<script>
(function() {
    var searchInput = document.getElementById('sessionsSearch');
    var rows = document.querySelectorAll('.table-patients tbody tr');
    var countEl = document.getElementById('sessionsCount');

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
