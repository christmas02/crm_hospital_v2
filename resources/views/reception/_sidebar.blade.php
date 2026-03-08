<div class="nav-group">
    <div class="nav-label">Accueil</div>
    <a href="{{ route('reception.index') }}" class="nav-item {{ request()->routeIs('reception.index') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
        Tableau de bord
    </a>
    <a href="{{ route('reception.patients.index') }}" class="nav-item {{ request()->routeIs('reception.patients.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        Patients
    </a>
    <a href="{{ route('reception.consultations.index') }}" class="nav-item {{ request()->routeIs('reception.consultations.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
        Consultations
    </a>
    <a href="{{ route('reception.factures.index') }}" class="nav-item {{ request()->routeIs('reception.factures.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
        Facturation
    </a>
</div>
