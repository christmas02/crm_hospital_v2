@extends('layouts.medicare')

@section('title', 'Hospitalisation - MediCare Pro')
@section('sidebar-subtitle', 'Gestion Hospitalière')
@section('header-title', 'Hospitalisation')

@section('sidebar-nav')
@include('admin._sidebar')
@endsection

@section('content')

<!-- Stats chambres -->
<div class="stats" style="grid-template-columns:repeat(4,1fr);margin-bottom:24px;">
    <div class="stat-card">
        <div><div class="stat-label">Total chambres</div><div class="stat-value">{{ $stats['total'] }}</div></div>
        <div class="stat-icon cyan"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M9 22V12h6v10"/></svg></div>
    </div>
    <div class="stat-card">
        <div><div class="stat-label">Occupées</div><div class="stat-value">{{ $stats['occupees'] }}</div></div>
        <div class="stat-icon red"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
    </div>
    <div class="stat-card">
        <div><div class="stat-label">Libres</div><div class="stat-value">{{ $stats['libres'] }}</div></div>
        <div class="stat-icon green"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg></div>
    </div>
    <div class="stat-card">
        <div><div class="stat-label">Maintenance</div><div class="stat-value">{{ $stats['maintenance'] }}</div></div>
        <div class="stat-icon orange"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg></div>
    </div>
</div>

<div class="tabs" style="margin-bottom:20px;">
    <button class="tab {{ !request('tab') || request('tab') == 'chambres' ? 'active' : '' }}" onclick="showTab('chambres')">Chambres</button>
    <button class="tab {{ request('tab') == 'hospitalisations' ? 'active' : '' }}" onclick="showTab('hospitalisations')">Hospitalisations en cours</button>
</div>

<!-- ===== TAB: Chambres ===== -->
<div id="tabChambres" class="{{ request('tab') == 'hospitalisations' ? 'hidden' : '' }}">
    <div class="toolbar">
        <div style="display:flex;gap:12px;align-items:center;">
            <div style="display:flex;align-items:center;gap:6px;font-size:0.875rem;"><span style="width:12px;height:12px;background:var(--success);border-radius:3px;display:inline-block;"></span>Libre</div>
            <div style="display:flex;align-items:center;gap:6px;font-size:0.875rem;"><span style="width:12px;height:12px;background:var(--danger);border-radius:3px;display:inline-block;"></span>Occupée</div>
            <div style="display:flex;align-items:center;gap:6px;font-size:0.875rem;"><span style="width:12px;height:12px;background:var(--warning);border-radius:3px;display:inline-block;"></span>Maintenance</div>
        </div>
        <button class="btn btn-primary" onclick="openModal('modalAdmission')">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M12 5v14M5 12h14"/></svg>
            Nouvelle Admission
        </button>
    </div>

    @php $etages = $chambres->groupBy('etage'); @endphp
    @foreach($etages as $etage => $chambresList)
    <div style="margin-bottom:24px;">
        <div class="text-muted text-sm" style="font-weight:600;margin-bottom:12px;text-transform:uppercase;letter-spacing:0.05em;">
            Étage {{ $etage }}
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:12px;">
            @foreach($chambresList as $chambre)
            @php
                $bg = ['libre'=>'var(--success-light)','occupee'=>'var(--danger-light)','maintenance'=>'var(--warning-light)'][$chambre->statut] ?? 'var(--gray-50)';
                $border = ['libre'=>'var(--success)','occupee'=>'var(--danger)','maintenance'=>'var(--warning)'][$chambre->statut] ?? 'var(--border)';
            @endphp
            <div style="background:{{ $bg }};border:1.5px solid {{ $border }};border-radius:10px;padding:16px;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;">
                    <span style="font-size:1.25rem;font-weight:700;">N° {{ $chambre->numero }}</span>
                    @php $sc = ['libre'=>['success','Libre'],'occupee'=>['danger','Occupée'],'maintenance'=>['warning','Maintenance']]; $s = $sc[$chambre->statut] ?? ['secondary','?']; @endphp
                    <span class="badge badge-{{ $s[0] }}" style="font-size:0.7rem;">{{ $s[1] }}</span>
                </div>
                <div class="text-sm text-muted" style="margin-bottom:4px;">{{ ucfirst($chambre->type) }} · {{ $chambre->capacite }} lit(s)</div>
                <div class="text-sm font-bold">{{ number_format($chambre->tarif_jour, 0, ',', ' ') }} F/jour</div>
                @if($chambre->statut == 'occupee' && $chambre->patient)
                <div style="margin-top:8px;padding-top:8px;border-top:1px solid rgba(0,0,0,.1);font-size:0.8rem;">
                    <div style="font-weight:600;">{{ $chambre->patient->prenom }} {{ $chambre->patient->nom }}</div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>

<!-- ===== TAB: Hospitalisations ===== -->
<div id="tabHospitalisations" class="{{ request('tab') == 'hospitalisations' ? '' : 'hidden' }}">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Hospitalisations en cours</h2>
            <span class="badge badge-warning">{{ $hospitalisations->count() }}</span>
        </div>
        <div class="card-body no-pad">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Patient</th><th>Chambre</th><th>Médecin</th><th>Admission</th><th>Durée</th><th>Motif</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse($hospitalisations as $hosp)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="avatar">{{ strtoupper(substr($hosp->patient->prenom, 0, 1) . substr($hosp->patient->nom, 0, 1)) }}</div>
                                    <div>
                                        <div class="user-name">{{ $hosp->patient->prenom }} {{ $hosp->patient->nom }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="font-weight:600;">N° {{ $hosp->chambre->numero }}</span>
                                <div class="text-muted text-sm">{{ ucfirst($hosp->chambre->type) }}</div>
                            </td>
                            <td>Dr. {{ $hosp->medecin->prenom }} {{ $hosp->medecin->nom }}</td>
                            <td>{{ \Carbon\Carbon::parse($hosp->date_admission)->format('d/m/Y') }}</td>
                            <td>
                                @php $jours = \Carbon\Carbon::parse($hosp->date_admission)->diffInDays(today()); @endphp
                                <span class="{{ $jours > 7 ? 'text-warning' : '' }}">{{ $jours }} jour(s)</span>
                            </td>
                            <td class="truncate" style="max-width:160px;">{{ $hosp->motif }}</td>
                            <td>
                                <form action="{{ route('admin.hospitalisation.sortie', $hosp) }}" method="POST" onsubmit="return confirm('Confirmer la sortie du patient ?')">
                                    @csrf
                                    <button type="submit" class="btn btn-outline btn-sm">Sortie</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted" style="padding:60px;">Aucune hospitalisation en cours</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nouvelle Admission -->
<div class="modal-overlay" id="modalAdmission">
    <div class="modal" style="max-width:520px;">
        <div class="modal-header">
            <h3 class="modal-title">Nouvelle Admission</h3>
            <button class="modal-close" onclick="closeModal('modalAdmission')">✕</button>
        </div>
        <form action="{{ route('admin.hospitalisation.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Patient *</label>
                    <select name="patient_id" class="form-control" required>
                        <option value="">Sélectionner un patient</option>
                        @foreach($patients as $p)
                        <option value="{{ $p->id }}">{{ $p->prenom }} {{ $p->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Chambre disponible *</label>
                    <select name="chambre_id" class="form-control" required>
                        <option value="">Sélectionner une chambre</option>
                        @foreach($chambresLibres as $c)
                        <option value="{{ $c->id }}">N° {{ $c->numero }} — {{ ucfirst($c->type) }}, Étage {{ $c->etage }} ({{ number_format($c->tarif_jour, 0, ',', ' ') }} F/j)</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Médecin responsable *</label>
                    <select name="medecin_id" class="form-control" required>
                        <option value="">Sélectionner un médecin</option>
                        @foreach($medecins as $m)
                        <option value="{{ $m->id }}">Dr. {{ $m->prenom }} {{ $m->nom }} — {{ $m->specialite }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Motif d'hospitalisation *</label>
                    <textarea name="motif" class="form-control" rows="3" required placeholder="Décrivez le motif..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalAdmission')">Annuler</button>
                <button type="submit" class="btn btn-primary">Admettre le patient</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showTab(tab) {
    document.getElementById('tabChambres').classList.toggle('hidden', tab !== 'chambres');
    document.getElementById('tabHospitalisations').classList.toggle('hidden', tab !== 'hospitalisations');
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelector(`[onclick="showTab('${tab}')"]`).classList.add('active');
}
</script>
@endpush

@endsection
