@extends('layouts.medicare')

@section('title', 'Planning - MediCare Pro')
@section('sidebar-subtitle', 'Gestion Hospitalière')
@section('header-title', 'Planning & Rendez-vous')

@section('sidebar-nav')
@include('admin._sidebar')
@endsection

@section('content')

<div class="tabs" style="margin-bottom:20px;">
    <button class="tab {{ !request('tab') || request('tab') == 'rdv' ? 'active' : '' }}" onclick="showTab('rdv')">
        Rendez-vous
    </button>
    <button class="tab {{ request('tab') == 'planning' ? 'active' : '' }}" onclick="showTab('planning')">
        Planning médecins
    </button>
</div>

<!-- ===== TAB: Rendez-vous ===== -->
<div id="tabRdv" class="{{ request('tab') == 'planning' ? 'hidden' : '' }}">
    <div class="toolbar">
        <form method="GET" action="{{ route('admin.planning') }}" style="display:flex;gap:10px;flex:1;">
            <input type="hidden" name="tab" value="rdv">
            <input type="date" name="date" class="filter-input" value="{{ request('date') }}">
            <select name="medecin_id" class="filter-select" onchange="this.form.submit()">
                <option value="">Tous les médecins</option>
                @foreach($medecins as $m)
                <option value="{{ $m->id }}" {{ request('medecin_id') == $m->id ? 'selected' : '' }}>Dr. {{ $m->prenom }} {{ $m->nom }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-outline btn-sm">Filtrer</button>
        </form>
        <button class="btn btn-primary" onclick="openModal('modalRdv')">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M12 5v14M5 12h14"/></svg>
            Nouveau RDV
        </button>
    </div>

    <div class="card">
        <div class="card-body no-pad">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Patient</th><th>Date</th><th>Heure</th><th>Médecin</th><th>Motif</th><th>Statut</th></tr>
                    </thead>
                    <tbody>
                        @forelse($rendezvous as $rdv)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar">{{ strtoupper(substr($rdv->patient->prenom, 0, 1) . substr($rdv->patient->nom, 0, 1)) }}</div>
                                    <span>{{ $rdv->patient->prenom }} {{ $rdv->patient->nom }}</span>
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($rdv->date)->format('d/m/Y') }}</td>
                            <td>{{ $rdv->heure }}</td>
                            <td>Dr. {{ $rdv->medecin->prenom }} {{ $rdv->medecin->nom }}</td>
                            <td class="truncate" style="max-width:180px;">{{ $rdv->motif }}</td>
                            <td>
                                @php $sc = ['en_attente'=>['warning','En attente'],'confirme'=>['success','Confirmé'],'annule'=>['secondary','Annulé']]; $s = $sc[$rdv->statut] ?? ['secondary',$rdv->statut]; @endphp
                                <span class="badge badge-{{ $s[0] }}">{{ $s[1] }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted" style="padding:40px;">Aucun rendez-vous</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($rendezvous->hasPages())
        <div class="card-body" style="border-top:1px solid var(--border);">{{ $rendezvous->links() }}</div>
        @endif
    </div>
</div>

<!-- ===== TAB: Planning médecins ===== -->
<div id="tabPlanning" class="{{ request('tab') == 'planning' ? '' : 'hidden' }}">
    <form method="GET" action="{{ route('admin.planning') }}" class="toolbar">
        <input type="hidden" name="tab" value="planning">
        <select name="planning_medecin_id" class="filter-select" onchange="this.form.submit()">
            <option value="">-- Sélectionner un médecin --</option>
            @foreach($medecins as $m)
            <option value="{{ $m->id }}" {{ request('planning_medecin_id') == $m->id ? 'selected' : '' }}>
                Dr. {{ $m->prenom }} {{ $m->nom }} — {{ $m->specialite }}
            </option>
            @endforeach
        </select>
    </form>

    @if($planningMedecin !== null)
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Planning hebdomadaire</h2>
        </div>
        <div class="card-body no-pad">
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Jour</th><th>Début</th><th>Fin</th><th>Durée</th></tr></thead>
                    <tbody>
                        @forelse($planningMedecin as $p)
                        <tr>
                            <td><span style="font-weight:600;text-transform:capitalize;">{{ $p->jour }}</span></td>
                            <td>{{ $p->debut }}</td>
                            <td>{{ $p->fin }}</td>
                            <td class="text-muted text-sm">
                                @php
                                    $debut = \Carbon\Carbon::createFromTimeString($p->debut);
                                    $fin   = \Carbon\Carbon::createFromTimeString($p->fin);
                                    $diff  = $debut->diffInMinutes($fin);
                                    echo floor($diff/60) . 'h' . ($diff%60 > 0 ? str_pad($diff%60,2,'0',STR_PAD_LEFT) : '');
                                @endphp
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted" style="padding:40px;">Aucun planning défini</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="card">
        <div class="card-body text-center" style="padding:80px;">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5" style="margin:0 auto 16px;display:block;"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            <p style="color:var(--gray-500);">Sélectionnez un médecin pour voir son planning</p>
        </div>
    </div>
    @endif
</div>

<!-- Modal Nouveau RDV -->
<div class="modal-overlay" id="modalRdv">
    <div class="modal" style="max-width:520px;">
        <div class="modal-header">
            <h3 class="modal-title">Nouveau Rendez-vous</h3>
            <button class="modal-close" onclick="closeModal('modalRdv')">✕</button>
        </div>
        <form action="{{ route('admin.rendezvous.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Patient *</label>
                    <select name="patient_id" class="form-control" required>
                        <option value="">Sélectionner un patient</option>
                        @foreach(\App\Models\Patient::orderBy('nom')->get() as $p)
                        <option value="{{ $p->id }}">{{ $p->prenom }} {{ $p->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Médecin *</label>
                    <select name="medecin_id" class="form-control" required>
                        <option value="">Sélectionner un médecin</option>
                        @foreach($medecins as $m)
                        <option value="{{ $m->id }}">Dr. {{ $m->prenom }} {{ $m->nom }} — {{ $m->specialite }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Date *</label>
                        <input type="date" name="date" class="form-control" required min="{{ today()->format('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Heure *</label>
                        <input type="time" name="heure" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Motif *</label>
                    <input type="text" name="motif" class="form-control" required placeholder="Ex: Consultation générale">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalRdv')">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showTab(tab) {
    document.getElementById('tabRdv').classList.toggle('hidden', tab !== 'rdv');
    document.getElementById('tabPlanning').classList.toggle('hidden', tab !== 'planning');
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelector(`[onclick="showTab('${tab}')"]`).classList.add('active');
}
</script>
@endpush

@endsection
