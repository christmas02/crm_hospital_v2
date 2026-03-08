<div class="nav-group">
    <div class="nav-label">Consultations</div>
    <a href="{{ route('medecin.index') }}" class="nav-item {{ request()->routeIs('medecin.index') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        Tableau de bord
    </a>
    <a href="{{ route('medecin.file-attente') }}" class="nav-item {{ request()->routeIs('medecin.file-attente') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        File d'attente
    </a>
    @if(isset($consultationEnCours) && $consultationEnCours)
    <a href="{{ route('medecin.consultations.show', $consultationEnCours) }}" class="nav-item {{ request()->routeIs('medecin.consultations.show') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
        Consultation en cours
        <span class="badge badge-warning" style="margin-left:auto;font-size:10px;">1</span>
    </a>
    @endif
</div>
<div class="nav-group">
    <div class="nav-label">Dossiers</div>
    <a href="{{ route('medecin.dossiers') }}" class="nav-item {{ request()->routeIs('medecin.dossiers') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
        Dossiers médicaux
    </a>
    <a href="{{ route('medecin.fiches') }}" class="nav-item {{ request()->routeIs('medecin.fiches') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6M9 16h6"/></svg>
        Fiches de traitement
    </a>
    <a href="{{ route('medecin.ordonnances') }}" class="nav-item {{ request()->routeIs('medecin.ordonnances') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>
        Ordonnances
    </a>
</div>
