@php $facturesEnAttenteCount = \App\Models\Facture::where('statut', 'en_attente')->count(); @endphp
<div class="nav-group">
    <div class="nav-label">Paiements</div>
    <a href="{{ route('caisse.index') }}" class="nav-item {{ request()->routeIs('caisse.index') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        Tableau de bord
    </a>
    <a href="{{ route('caisse.factures.index') }}" class="nav-item {{ request()->routeIs('caisse.factures.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
        Factures a encaisser
        @if($facturesEnAttenteCount > 0)
        <span class="badge badge-danger" style="margin-left:8px;">{{ $facturesEnAttenteCount }}</span>
        @endif
    </a>
    <a href="{{ route('caisse.sessions') }}" class="nav-item {{ request()->routeIs('caisse.sessions') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
        Sessions de caisse
    </a>
    <a href="{{ route('caisse.historique') }}" class="nav-item {{ request()->routeIs('caisse.historique') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        Historique
    </a>
</div>
<div class="nav-group">
    <div class="nav-label">Comptabilite</div>
    <a href="{{ route('caisse.journal') }}" class="nav-item {{ request()->routeIs('caisse.journal') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
        Journal de caisse
    </a>
    <a href="{{ route('caisse.creances') }}" class="nav-item {{ request()->routeIs('caisse.creances') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        Créances
    </a>
    <a href="{{ route('caisse.prise-en-charge') }}" class="nav-item {{ request()->routeIs('caisse.prise-en-charge') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M9 22V12h6v10"/></svg>
        Prises en charge
    </a>
</div>
