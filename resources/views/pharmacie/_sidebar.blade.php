@php
    $stockBas = \App\Models\Medicament::whereRaw('stock <= stock_min')->count();
    $enAttentePharm = \App\Models\Ordonnance::where('statut_dispensation', 'en_attente')->count();
@endphp
<div class="nav-group">
    <div class="nav-label">Stock</div>
    <a href="{{ route('pharmacie.index') }}" class="nav-item {{ request()->routeIs('pharmacie.index') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        Tableau de bord
    </a>
    <a href="{{ route('pharmacie.stock') }}" class="nav-item {{ request()->routeIs('pharmacie.stock*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>
        Stock médicaments
    </a>
    <a href="{{ route('pharmacie.approvisionnements') }}" class="nav-item {{ request()->routeIs('pharmacie.approvisionnements') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
        Approvisionnements
    </a>
    <a href="{{ route('pharmacie.alertes') }}" class="nav-item {{ request()->routeIs('pharmacie.alertes') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
        Alertes
        @if($stockBas > 0)<span class="badge badge-danger" style="margin-left:8px;">{{ $stockBas }}</span>@endif
    </a>
</div>
<div class="nav-group">
    <div class="nav-label">Dispensation</div>
    <a href="{{ route('pharmacie.demandes') }}" class="nav-item {{ request()->routeIs('pharmacie.demandes') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
        Demandes
        @if($enAttentePharm > 0)<span class="badge badge-warning" style="margin-left:8px;">{{ $enAttentePharm }}</span>@endif
    </a>
    <a href="{{ route('pharmacie.mouvements') }}" class="nav-item {{ request()->routeIs('pharmacie.mouvements') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
        Historique
    </a>
</div>
