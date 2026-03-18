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
    <div class="stat-card" style="border-left: 4px solid var(--primary);">
        <div><div class="stat-label">Total chambres</div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-sub">Capacité totale de l'établissement</div></div>
        <div class="stat-icon cyan"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M9 22V12h6v10"/></svg></div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--danger);">
        <div><div class="stat-label">Occupées</div><div class="stat-value">{{ $stats['occupees'] }}</div><div class="stat-sub">Chambres actuellement utilisées</div></div>
        <div class="stat-icon red"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--secondary);">
        <div><div class="stat-label">Libres</div><div class="stat-value">{{ $stats['libres'] }}</div><div class="stat-sub">Chambres disponibles</div></div>
        <div class="stat-icon green"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg></div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--warning);">
        <div><div class="stat-label">Maintenance</div><div class="stat-value">{{ $stats['maintenance'] }}</div><div class="stat-sub">Chambres en réparation</div></div>
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
        <div style="display:flex;gap:8px;">
            <button class="btn btn-outline" onclick="openModal('modalChambre')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M12 5v14M5 12h14"/></svg>
                Nouvelle Chambre
            </button>
            <button class="btn btn-primary" onclick="openModal('modalAdmission')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M12 5v14M5 12h14"/></svg>
                Nouvelle Admission
            </button>
        </div>
    </div>

    @php $etages = $chambres->groupBy('etage'); @endphp
    @foreach($etages as $etage => $chambresList)
    <div class="card mb-4">
        <div class="card-header" style="background:linear-gradient(135deg, var(--gray-800), var(--gray-700));border-radius:12px 12px 0 0;">
            <h2 class="card-title" style="color:#fff;display:flex;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                </div>
                Étage {{ $etage }}
            </h2>
            <div style="display:flex;gap:10px;align-items:center;">
                @php $libres = $chambresList->where('statut','libre')->count(); $occupees = $chambresList->where('statut','occupee')->count(); @endphp
                <span style="font-size:.78rem;color:rgba(255,255,255,.6);">{{ $chambresList->count() }} chambres</span>
                <span class="badge badge-success" style="font-size:.68rem;">{{ $libres }} libres</span>
                <span class="badge badge-danger" style="font-size:.68rem;">{{ $occupees }} occupées</span>
            </div>
        </div>
        <div class="card-body" style="padding:20px;">
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:16px;">
                @foreach($chambresList as $chambre)
                @php
                    $stConf = [
                        'libre' => ['var(--success)','#f0fdf4','Disponible','<path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/>'],
                        'occupee' => ['var(--danger)','#fef2f2','Occupée','<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>'],
                        'maintenance' => ['var(--warning)','#fffbeb','Maintenance','<path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/>'],
                    ];
                    $s = $stConf[$chambre->statut] ?? $stConf['libre'];
                    $tc = ['individuelle'=>'#0891b2','double'=>'#059669','vip'=>'#7c3aed','suite'=>'#d97706'][$chambre->type] ?? '#6b7280';
                @endphp
                <div style="background:#fff;border-radius:16px;border:1px solid var(--gray-200);overflow:hidden;transition:all .25s;box-shadow:0 2px 8px rgba(0,0,0,.04);display:flex;flex-direction:column;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,.1)'" onmouseout="this.style.transform='';this.style.boxShadow='0 2px 8px rgba(0,0,0,.04)'">
                    <div style="height:4px;background:{{ $s[0] }};"></div>
                    <div style="padding:16px 16px 0;flex:1;">
                        <!-- Header: icon + numero + status + type -->
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px;">
                            <div style="display:flex;align-items:center;gap:12px;">
                                <div style="width:48px;height:48px;border-radius:14px;background:{{ $s[1] }};display:flex;align-items:center;justify-content:center;">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="{{ $s[0] }}" stroke-width="2">{!! $s[3] !!}</svg>
                                </div>
                                <div>
                                    <div style="font-size:1.3rem;font-weight:800;color:var(--gray-800);line-height:1;">{{ $chambre->numero }}</div>
                                    <span style="font-size:.72rem;font-weight:600;color:{{ $s[0] }};">{{ $s[2] }}</span>
                                </div>
                            </div>
                            <span style="padding:4px 10px;border-radius:8px;background:{{ $tc }};color:#fff;font-size:.66rem;font-weight:700;letter-spacing:.3px;">{{ strtoupper($chambre->type) }}</span>
                        </div>

                        <!-- Info: lits + tarif -->
                        <div style="display:flex;gap:16px;margin-bottom:14px;">
                            <div style="flex:1;background:var(--gray-50);padding:10px 12px;border-radius:10px;display:flex;align-items:center;gap:8px;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><path d="M2 12h4l1-3h10l1 3h4"/><path d="M2 12v6a2 2 0 002 2h16a2 2 0 002-2v-6"/><path d="M6 12V8a2 2 0 012-2h8a2 2 0 012 2v4"/></svg>
                                <div>
                                    <div style="font-size:.65rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;">Lits</div>
                                    <div style="font-size:1rem;font-weight:800;color:var(--gray-700);">{{ $chambre->capacite }}</div>
                                </div>
                            </div>
                            <div style="flex:1;background:var(--gray-50);padding:10px 12px;border-radius:10px;display:flex;align-items:center;gap:8px;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                                <div>
                                    <div style="font-size:.65rem;color:var(--gray-400);text-transform:uppercase;font-weight:600;">Tarif/jour</div>
                                    <div style="font-size:.95rem;font-weight:800;color:var(--success);">{{ number_format($chambre->tarif_jour, 0, ',', ' ') }} F</div>
                                </div>
                            </div>
                        </div>

                        <!-- Equipements tags -->
                        @if($chambre->equipements)
                        <div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:14px;">
                            @foreach(explode(',', $chambre->equipements) as $equip)
                            <span style="padding:3px 9px;background:var(--gray-100);border-radius:6px;font-size:.7rem;color:var(--gray-600);">{{ trim($equip) }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <!-- Patient section -->
                    @if($chambre->statut == 'occupee' && $chambre->patient)
                    <div style="margin:0 18px 14px;padding:12px;background:{{ $s[1] }};border-radius:10px;display:flex;align-items:center;gap:10px;">
                        <div class="avatar" style="width:34px;height:34px;font-size:.72rem;background:var(--danger);color:#fff;flex-shrink:0;">{{ strtoupper(substr($chambre->patient->prenom,0,1).substr($chambre->patient->nom,0,1)) }}</div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:600;font-size:.85rem;color:var(--gray-800);">{{ $chambre->patient->prenom }} {{ $chambre->patient->nom }}</div>
                            <div style="font-size:.72rem;color:var(--gray-500);">Patient hospitalisé</div>
                        </div>
                    </div>
                    @endif

                    <!-- Actions footer -->
                    <div style="padding:10px 16px;background:var(--gray-50);display:flex;gap:8px;border-top:1px solid var(--gray-100);margin-top:auto;">
                        <button class="btn btn-outline btn-sm" style="flex:1;border-radius:8px;padding:7px;font-size:.78rem;" onclick="editChambre({{ $chambre->id }})">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            Modifier
                        </button>
                        @if($chambre->statut === 'libre')
                        <form action="{{ route('admin.chambres.destroy', $chambre) }}" method="POST" onsubmit="return confirm('Supprimer la chambre {{ $chambre->numero }} ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" style="border-radius:8px;padding:7px 12px;font-size:.78rem;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- ===== TAB: Hospitalisations ===== -->
<div id="tabHospitalisations" class="{{ request('tab') == 'hospitalisations' ? '' : 'hidden' }}">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M9 22V12h6v10"/></svg>
                Hospitalisations en cours
            </h2>
            <span class="badge badge-warning">{{ $hospitalisations->count() }}</span>
        </div>
        <div class="card-body no-pad">
            <div class="table-wrap">
                <table class="table-patients">
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
                            <td>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    Dr. {{ $hosp->medecin->prenom }} {{ $hosp->medecin->nom }}
                                </div>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                    {{ \Carbon\Carbon::parse($hosp->date_admission)->format('d/m/Y') }}
                                </div>
                            </td>
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
                        <tr><td colspan="7" style="text-align:center;padding:32px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5" style="margin-bottom:8px;"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M9 22V12h6v10"/></svg>
                            <div class="text-muted" style="font-size:.875rem;">Aucune hospitalisation en cours</div>
                            <div class="text-muted" style="font-size:.75rem;margin-top:4px;">Tous les patients ont été libérés</div>
                        </td></tr>
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
            <h3 class="modal-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M9 22V12h6v10"/></svg>
                Nouvelle Admission
            </h3>
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

<!-- Modal Nouvelle Chambre -->
<div class="modal-overlay" id="modalChambre">
    <div class="modal" style="max-width:520px;">
        <div class="modal-header">
            <h3 class="modal-title">Nouvelle Chambre</h3>
            <button class="modal-close" onclick="closeModal('modalChambre')">&times;</button>
        </div>
        <form action="{{ route('admin.chambres.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Numéro *</label>
                        <input type="text" class="form-control" name="numero" required placeholder="Ex: 101, A-201">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Étage *</label>
                        <input type="number" class="form-control" name="etage" required min="0" placeholder="0">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Type *</label>
                        <select class="form-control" name="type" required>
                            <option value="individuelle">Individuelle (1 lit)</option>
                            <option value="double">Double (2 lits)</option>
                            <option value="vip">VIP</option>
                            <option value="suite">Suite</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nombre de lits *</label>
                        <input type="number" class="form-control" name="capacite" required min="1" max="10" value="1">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Tarif journalier (F) *</label>
                    <input type="number" class="form-control" name="tarif_jour" required min="0" placeholder="25000">
                </div>
                <div class="form-group">
                    <label class="form-label">Équipements</label>
                    <textarea class="form-control" name="equipements" rows="2" placeholder="TV, Climatisation, Salle de bain privée..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalChambre')">Annuler</button>
                <button type="submit" class="btn btn-primary">Créer la chambre</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Modifier Chambre -->
<div class="modal-overlay" id="modalEditChambre">
    <div class="modal" style="max-width:520px;">
        <div class="modal-header">
            <h3 class="modal-title" id="editChambreTitle">Modifier la chambre</h3>
            <button class="modal-close" onclick="closeModal('modalEditChambre')">&times;</button>
        </div>
        <form id="editChambreForm" method="POST">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Numéro *</label>
                        <input type="text" class="form-control" name="numero" id="editCNumero" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Étage *</label>
                        <input type="number" class="form-control" name="etage" id="editCEtage" required min="0">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Type *</label>
                        <select class="form-control" name="type" id="editCType" required>
                            <option value="individuelle">Individuelle</option>
                            <option value="double">Double</option>
                            <option value="vip">VIP</option>
                            <option value="suite">Suite</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nombre de lits *</label>
                        <input type="number" class="form-control" name="capacite" id="editCCapacite" required min="1" max="10">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Tarif journalier (F) *</label>
                    <input type="number" class="form-control" name="tarif_jour" id="editCTarif" required min="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Statut *</label>
                    <select class="form-control" name="statut" id="editCStatut" required>
                        <option value="libre">Libre</option>
                        <option value="occupee">Occupée</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Équipements</label>
                    <textarea class="form-control" name="equipements" id="editCEquipements" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditChambre')">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function editChambre(id) {
    fetch('/admin/chambres/' + id + '/json')
        .then(r => r.json())
        .then(c => {
            document.getElementById('editChambreTitle').textContent = 'Modifier chambre N° ' + c.numero;
            document.getElementById('editChambreForm').action = '/admin/chambres/' + c.id;
            document.getElementById('editCNumero').value = c.numero;
            document.getElementById('editCEtage').value = c.etage;
            document.getElementById('editCType').value = c.type;
            document.getElementById('editCCapacite').value = c.capacite;
            document.getElementById('editCTarif').value = c.tarif_jour;
            document.getElementById('editCStatut').value = c.statut;
            document.getElementById('editCEquipements').value = c.equipements || '';
            openModal('modalEditChambre');
        });
}

function showTab(tab) {
    document.getElementById('tabChambres').classList.toggle('hidden', tab !== 'chambres');
    document.getElementById('tabHospitalisations').classList.toggle('hidden', tab !== 'hospitalisations');
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelector(`[onclick="showTab('${tab}')"]`).classList.add('active');
}
</script>
@endpush

@endsection
