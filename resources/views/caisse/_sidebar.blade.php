@php $facturesEnAttenteCount = \App\Models\Facture::where('statut', 'en_attente')->count(); @endphp
<div class="nav-group">
    <div class="nav-label">Paiements</div>
    <a href="{{ route('caisse.index') }}" class="nav-item {{ request()->routeIs('caisse.index') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        Tableau de bord
    </a>
    <a href="{{ route('caisse.factures.index') }}" class="nav-item {{ request()->routeIs('caisse.factures.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
        Factures à encaisser
        @if($facturesEnAttenteCount > 0)
        <span class="badge badge-danger" style="margin-left:8px;">{{ $facturesEnAttenteCount }}</span>
        @endif
    </a>
    <a href="{{ route('caisse.historique') }}" class="nav-item {{ request()->routeIs('caisse.historique') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
        Historique
    </a>
</div>
<div class="nav-group">
    <div class="nav-label">Comptabilité</div>
    <a href="{{ route('caisse.journal') }}" class="nav-item {{ request()->routeIs('caisse.journal') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        Journal de caisse
    </a>
</div>
