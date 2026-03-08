<div class="nav-group">
    <div class="nav-label">Principal</div>
    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        Dashboard
    </a>
    <a href="{{ route('reception.patients.index') }}" class="nav-item {{ request()->routeIs('reception.patients.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
        Patients
    </a>
    <a href="{{ route('medecin.dossiers') }}" class="nav-item {{ request()->routeIs('medecin.dossiers') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
        Dossiers médicaux
    </a>
    <a href="{{ route('reception.consultations.index') }}" class="nav-item {{ request()->routeIs('reception.consultations.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
        Consultations
    </a>
</div>
<div class="nav-group">
    <div class="nav-label">Ressources</div>
    <a href="{{ route('admin.medecins') }}" class="nav-item {{ request()->routeIs('admin.medecins*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Médecins
    </a>
    <a href="{{ route('admin.planning') }}" class="nav-item {{ request()->routeIs('admin.planning*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        Planning
    </a>
    <a href="{{ route('admin.hospitalisation') }}" class="nav-item {{ request()->routeIs('admin.hospitalisation*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M9 22V12h6v10"/></svg>
        Hospitalisation
    </a>
</div>
<div class="nav-group">
    <div class="nav-label">Gestion</div>
    <a href="{{ route('pharmacie.index') }}" class="nav-item {{ request()->routeIs('pharmacie.*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>
        Pharmacie
    </a>
    <a href="{{ route('admin.caisse') }}" class="nav-item {{ request()->routeIs('admin.caisse*') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
        Caisse
    </a>
</div>
