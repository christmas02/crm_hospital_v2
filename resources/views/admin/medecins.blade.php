@extends('layouts.medicare')

@section('title', 'Médecins - MediCare Pro')
@section('sidebar-subtitle', 'Gestion Hospitalière')
@section('header-title', 'Médecins')

@section('sidebar-nav')
@include('admin._sidebar')
@endsection

@section('content')

@php
    $nbDispo = $medecins->where('statut', 'disponible')->count();
    $nbConsult = $medecins->where('statut', 'en_consultation')->count();
    $nbAbsent = $medecins->where('statut', 'absent')->count();
@endphp

<!-- Stats -->
<div class="stats" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 24px;">
    <div class="stat-card" style="border-left: 4px solid var(--primary);">
        <div>
            <div class="stat-label">Total médecins</div>
            <div class="stat-value">{{ $medecins->count() }}</div>
            <div class="stat-sub">dans l'établissement</div>
        </div>
        <div class="stat-icon cyan">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--success);">
        <div>
            <div class="stat-label">Disponibles</div>
            <div class="stat-value" style="color:var(--success);">{{ $nbDispo }}</div>
            <div class="stat-sub">prêts à consulter</div>
        </div>
        <div class="stat-icon green">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--primary);">
        <div>
            <div class="stat-label">En consultation</div>
            <div class="stat-value" style="color:var(--primary);">{{ $nbConsult }}</div>
            <div class="stat-sub">occupés</div>
        </div>
        <div class="stat-icon cyan">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--gray-400);">
        <div>
            <div class="stat-label">Absents</div>
            <div class="stat-value" style="color:var(--gray-500);">{{ $nbAbsent }}</div>
            <div class="stat-sub">indisponibles</div>
        </div>
        <div class="stat-icon" style="background:var(--gray-100);color:var(--gray-500);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
        </div>
    </div>
</div>

<div class="toolbar">
    <div style="display:flex;gap:10px;flex:1;align-items:center;">
        <input type="text" id="medecinSearch" class="filter-input" placeholder="Rechercher un médecin...">
        <select id="medecinSpecialite" class="filter-select">
            <option value="">Toutes spécialités</option>
            @foreach($specialites as $spec)
            <option value="{{ $spec }}">{{ $spec }}</option>
            @endforeach
        </select>
        <span id="medecinCount" style="font-size:.82rem;color:var(--gray-500);white-space:nowrap;"></span>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('export.medecins') }}" class="btn btn-outline btn-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><path d="M7 10l5 5 5-5"/><path d="M12 15V3"/></svg>
            Export CSV
        </a>
        <button class="btn btn-primary" onclick="openModal('modalMedecin')">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M12 5v14M5 12h14"/></svg>
            Nouveau Médecin
        </button>
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:20px;">
    @forelse($medecins as $medecin)
    @php
        $statusMap = [
            'disponible' => ['success', 'Disponible', 'var(--success)', 'var(--success-light)'],
            'en_consultation' => ['info', 'En consultation', 'var(--primary)', 'var(--primary-light)'],
            'absent' => ['secondary', 'Absent', 'var(--gray-500)', 'var(--gray-100)'],
            'en_operation' => ['warning', 'En opération', 'var(--warning)', 'var(--warning-light)'],
        ];
        $st = $statusMap[$medecin->statut] ?? $statusMap['absent'];
    @endphp
    <div class="medecin-card-wrap" style="background:#fff;border-radius:18px;box-shadow:0 2px 12px rgba(0,0,0,.05);border:1px solid var(--gray-200);transition:all .25s;overflow:visible;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 30px rgba(0,0,0,.1)'" onmouseout="this.style.transform='';this.style.boxShadow='0 2px 12px rgba(0,0,0,.05)'">

        <!-- Banner + Avatar centré -->
        <div style="height:56px;background:linear-gradient(135deg, {{ $st[2] }}dd, {{ $st[2] }}88);border-radius:18px 18px 0 0;position:relative;">
            <!-- Badge statut -->
            <span class="badge badge-{{ $st[0] }}" style="position:absolute;top:12px;right:14px;font-size:.68rem;padding:4px 10px;box-shadow:0 2px 6px rgba(0,0,0,.15);">{{ $st[1] }}</span>
        </div>

        <!-- Avatar centré qui chevauche le banner -->
        <div style="display:flex;justify-content:center;margin-top:-36px;position:relative;z-index:1;">
            <div style="position:relative;">
                @if($medecin->photo)
                <img src="{{ asset('storage/' . $medecin->photo) }}" alt="Dr. {{ $medecin->nom }}" style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid #fff;box-shadow:0 4px 15px rgba(0,0,0,.12);">
                @else
                <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg, {{ $st[2] }}, {{ $st[2] }}bb);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:700;border:3px solid #fff;box-shadow:0 4px 15px rgba(0,0,0,.12);">
                    {{ strtoupper(substr($medecin->prenom, 0, 1) . substr($medecin->nom, 0, 1)) }}
                </div>
                @endif
                <button type="button" onclick="event.preventDefault(); event.stopPropagation(); uploadMedecinPhoto({{ $medecin->id }});" style="position:absolute;bottom:0;right:0;width:24px;height:24px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 2px 6px rgba(0,0,0,.2);border:2px solid #fff;padding:0;">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                </button>
            </div>
        </div>

        <div style="padding:10px 22px 22px;text-align:center;">
            <!-- Nom & Spécialité -->
            <div style="font-weight:700;font-size:1.1rem;color:var(--gray-800);margin-bottom:2px;">Dr. {{ $medecin->prenom }} {{ $medecin->nom }}</div>
            <div style="font-size:.8rem;color:var(--gray-500);display:inline-flex;align-items:center;gap:4px;margin-bottom:18px;">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                {{ $medecin->specialite }}
            </div>

            <!-- Info grid 2x2 -->
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:16px;text-align:left;">
                <div style="background:var(--gray-50);padding:10px 12px;border-radius:10px;">
                    <div style="font-size:.62rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:3px;">Téléphone</div>
                    <div style="font-size:.8rem;font-weight:600;color:var(--gray-700);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $medecin->telephone }}</div>
                </div>
                <div style="background:var(--gray-50);padding:10px 12px;border-radius:10px;">
                    <div style="font-size:.62rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:3px;">Bureau</div>
                    <div style="font-size:.8rem;font-weight:600;color:var(--gray-700);">{{ $medecin->bureau ?? '—' }}</div>
                </div>
                <div style="background:var(--gray-50);padding:10px 12px;border-radius:10px;">
                    <div style="font-size:.62rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:3px;">Tarif</div>
                    <div style="font-size:.88rem;font-weight:800;color:var(--success);">{{ $medecin->tarif_consultation ? number_format($medecin->tarif_consultation, 0, ',', ' ') . ' F' : '—' }}</div>
                </div>
                <div style="background:var(--gray-50);padding:10px 12px;border-radius:10px;">
                    <div style="font-size:.62rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:3px;">Consultations</div>
                    <div style="font-size:.88rem;font-weight:800;color:var(--primary);">{{ $medecin->consultations_count }}</div>
                </div>
            </div>

            <!-- Statut rapide -->
            <form action="{{ route('admin.medecins.update', $medecin) }}" method="POST" style="display:flex;gap:6px;margin-bottom:10px;">
                @csrf @method('PATCH')
                <select name="statut" class="form-control" style="flex:1;font-size:.76rem;padding:7px 10px;border-radius:10px;">
                    <option value="disponible" {{ $medecin->statut == 'disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="en_consultation" {{ $medecin->statut == 'en_consultation' ? 'selected' : '' }}>En consultation</option>
                    <option value="absent" {{ $medecin->statut == 'absent' ? 'selected' : '' }}>Absent</option>
                </select>
                <button type="submit" class="btn btn-sm" style="border-radius:10px;padding:7px 12px;background:var(--gray-100);color:var(--gray-600);border:1px solid var(--gray-200);">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                </button>
            </form>

            <!-- Actions -->
            <div style="display:flex;gap:8px;">
                <button class="btn btn-outline btn-sm" style="flex:1;border-radius:10px;padding:8px;" onclick="voirMedecin({{ $medecin->id }})">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    Voir
                </button>
                <button class="btn btn-primary btn-sm" style="flex:1;border-radius:10px;padding:8px;" onclick="modifierMedecin({{ $medecin->id }})">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Modifier
                </button>
                <button class="btn btn-sm" style="border-radius:10px;padding:8px 10px;background:var(--danger-light, #fee2e2);color:var(--danger, #ef4444);border:1px solid var(--danger, #ef4444);" onclick="confirmDeleteMedecin('{{ route('admin.medecins.destroy', $medecin) }}', 'Dr. {{ $medecin->prenom }} {{ $medecin->nom }}')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;" class="card">
        <div style="text-align:center;padding:60px;">
            <div style="width:72px;height:72px;border-radius:50%;background:var(--gray-100);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <div style="font-size:1rem;font-weight:700;color:var(--gray-600);margin-bottom:4px;">Aucun médecin trouvé</div>
            <div style="font-size:.85rem;color:var(--gray-400);">Ajoutez un médecin ou modifiez vos filtres de recherche</div>
        </div>
    </div>
    @endforelse
</div>

<!-- Modal Nouveau Médecin -->
<div class="modal-overlay" id="modalMedecin">
    <div class="modal" style="max-width:560px;">
        <div class="modal-header">
            <h3 class="modal-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><path d="M20 8v6M23 11h-6"/></svg>
                Nouveau Médecin
            </h3>
            <button class="modal-close" onclick="closeModal('modalMedecin')">&times;</button>
        </div>
        <form action="{{ route('admin.medecins.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <!-- Photo upload -->
                <div class="form-group" style="text-align:center;margin-bottom:20px;">
                    <label for="newMedecinPhoto" style="cursor:pointer;display:inline-block;">
                        <div id="photoPreview" style="width:80px;height:80px;border-radius:20px;background:var(--gray-100);display:flex;align-items:center;justify-content:center;margin:0 auto 8px;overflow:hidden;border:2px dashed var(--gray-300);transition:all .2s;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="1.5"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                        </div>
                        <span style="font-size:.78rem;color:var(--primary);font-weight:600;">Ajouter une photo</span>
                    </label>
                    <input type="file" id="newMedecinPhoto" name="photo" accept="image/*" style="display:none;" onchange="previewNewPhoto(this)">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nom *</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prénom *</label>
                        <input type="text" name="prenom" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Spécialité *</label>
                    <input type="text" name="specialite" class="form-control" required list="specialites-list" placeholder="Ex: Médecine générale">
                    <datalist id="specialites-list">
                        @foreach($specialites as $spec)<option value="{{ $spec }}">@endforeach
                    </datalist>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Téléphone *</label>
                        <input type="tel" name="telephone" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Bureau</label>
                        <input type="text" name="bureau" class="form-control" placeholder="Ex: Salle 12">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tarif consultation (F)</label>
                        <input type="number" name="tarif_consultation" class="form-control" min="0" placeholder="5000">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Compte utilisateur (optionnel)</label>
                    <select class="form-control" name="user_id">
                        <option value="">Aucun</option>
                        @foreach($usersWithoutMedecin as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalMedecin')">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Voir Profil Médecin -->
<div class="modal-overlay" id="modalVoirMedecin">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 class="modal-title" id="vmTitle">Profil Médecin</h3>
            <button class="modal-close" onclick="closeModal('modalVoirMedecin')">&times;</button>
        </div>
        <div class="modal-body" id="vmBody">
            <div style="text-align:center;padding:40px;" class="text-muted">Chargement...</div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('modalVoirMedecin')">Fermer</button>
            <button class="btn btn-primary" id="vmBtnEdit">Modifier</button>
        </div>
    </div>
</div>

<!-- Modal Modifier Médecin -->
<div class="modal-overlay" id="modalEditMedecin">
    <div class="modal" style="max-width:580px;">
        <div class="modal-header">
            <h3 class="modal-title" id="emTitle">Modifier le médecin</h3>
            <button class="modal-close" onclick="closeModal('modalEditMedecin')">&times;</button>
        </div>
        <form id="editMedecinForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <!-- Photo -->
                <div class="form-group" style="text-align:center;margin-bottom:20px;">
                    <label for="editMedecinPhoto" style="cursor:pointer;display:inline-block;">
                        <div id="editPhotoPreview" style="width:80px;height:80px;border-radius:50%;background:var(--gray-100);display:flex;align-items:center;justify-content:center;margin:0 auto 8px;overflow:hidden;border:2px dashed var(--gray-300);transition:all .2s;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="1.5"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                        </div>
                        <span style="font-size:.78rem;color:var(--primary);font-weight:600;">Changer la photo</span>
                    </label>
                    <input type="file" id="editMedecinPhoto" name="photo" accept="image/*" style="display:none;" onchange="previewEditPhoto(this)">
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Nom *</label><input type="text" class="form-control" name="nom" id="editMNom" required></div>
                    <div class="form-group"><label class="form-label">Prénom *</label><input type="text" class="form-control" name="prenom" id="editMPrenom" required></div>
                </div>
                <div class="form-group"><label class="form-label">Spécialité *</label><input type="text" class="form-control" name="specialite" id="editMSpecialite" required list="specialites-edit-list" placeholder="Ex: Médecine générale">
                    <datalist id="specialites-edit-list">
                        @foreach($specialites as $spec)<option value="{{ $spec }}">@endforeach
                    </datalist>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Téléphone *</label><input type="tel" class="form-control" name="telephone" id="editMTelephone" required></div>
                    <div class="form-group"><label class="form-label">Email</label><input type="email" class="form-control" name="email" id="editMEmail"></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Bureau</label><input type="text" class="form-control" name="bureau" id="editMBureau"></div>
                    <div class="form-group"><label class="form-label">Tarif consultation (F)</label><input type="number" class="form-control" name="tarif_consultation" id="editMTarif" min="0"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Statut</label>
                    <select class="form-control" name="statut" id="editMStatut">
                        <option value="disponible">Disponible</option>
                        <option value="en_consultation">En consultation</option>
                        <option value="absent">Absent</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditMedecin')">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Suppression Médecin -->
<div class="modal-overlay" id="modalDeleteMedecin">
    <div class="modal" style="max-width:440px;">
        <div class="modal-header" style="background:linear-gradient(135deg, var(--danger), #ef4444);">
            <h3 class="modal-title">Confirmer la suppression</h3>
            <button class="modal-close" onclick="closeModal('modalDeleteMedecin')">&times;</button>
        </div>
        <div class="modal-body" style="text-align:center;padding:30px;">
            <div style="width:60px;height:60px;border-radius:50%;background:var(--danger-light, #fee2e2);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--danger, #ef4444)" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
            </div>
            <p style="font-weight:600;font-size:1rem;margin-bottom:8px;">Etes-vous sur ?</p>
            <p class="text-muted" style="font-size:.85rem;" id="deleteMedecinMsg">Cette action est irreversible.</p>
        </div>
        <div class="modal-footer" style="justify-content:center;">
            <button class="btn btn-secondary" onclick="closeModal('modalDeleteMedecin')">Annuler</button>
            <form id="deleteMedecinForm" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    var searchInput = document.getElementById('medecinSearch');
    var selectFilter = document.getElementById('medecinSpecialite');
    var cards = document.querySelectorAll('.medecin-card-wrap');
    var countEl = document.getElementById('medecinCount');

    function filterCards() {
        var q = searchInput ? searchInput.value.toLowerCase().trim() : '';
        var filterVal = selectFilter ? selectFilter.value.toLowerCase() : '';
        var visible = 0;
        var total = cards.length;

        cards.forEach(function(card) {
            var text = card.textContent.toLowerCase();
            var matchSearch = !q || text.includes(q);
            var matchFilter = !filterVal || text.includes(filterVal);
            if (matchSearch && matchFilter) {
                card.style.display = '';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });

        if (countEl) countEl.textContent = visible + ' / ' + total + ' résultats';
    }

    if (searchInput) searchInput.addEventListener('input', filterCards);
    if (selectFilter) selectFilter.addEventListener('change', filterCards);
    filterCards();
})();

function confirmDeleteMedecin(url, name) {
    document.getElementById('deleteMedecinForm').action = url;
    document.getElementById('deleteMedecinMsg').textContent = 'Le medecin "' + name + '" sera supprime. Cette action est irreversible.';
    openModal('modalDeleteMedecin');
}

function uploadMedecinPhoto(medecinId) {
    var fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.accept = 'image/*';
    fileInput.style.display = 'none';
    document.body.appendChild(fileInput);

    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/medecins/' + medecinId + '/photo';
            form.enctype = 'multipart/form-data';
            form.style.display = 'none';

            var csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = csrfToken;
            form.appendChild(csrf);

            var dt = new DataTransfer();
            dt.items.add(this.files[0]);
            var fi = document.createElement('input');
            fi.type = 'file';
            fi.name = 'photo';
            fi.files = dt.files;
            form.appendChild(fi);

            document.body.appendChild(form);
            form.submit();
        }
        fileInput.remove();
    });

    fileInput.click();
}

function previewEditPhoto(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('editPhotoPreview');
            preview.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;">';
            preview.style.borderStyle = 'solid';
            preview.style.borderColor = 'var(--primary)';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function voirMedecin(id) {
    document.getElementById('vmBody').innerHTML = '<div style="text-align:center;padding:40px;" class="text-muted">Chargement...</div>';
    openModal('modalVoirMedecin');

    fetch('/admin/medecins/' + id + '/json')
        .then(r => r.json())
        .then(m => {
            document.getElementById('vmTitle').textContent = 'Dr. ' + m.prenom + ' ' + m.nom;
            document.getElementById('vmBtnEdit').onclick = function() { modifierMedecin(id); };

            var statusMap = {
                'disponible': ['success', 'Disponible'],
                'en_consultation': ['info', 'En consultation'],
                'absent': ['secondary', 'Absent'],
                'en_operation': ['warning', 'En opération']
            };
            var st = statusMap[m.statut] || statusMap['absent'];

            var avatarHtml = m.photo
                ? '<img src="' + m.photo + '" style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid var(--gray-200);">'
                : '<div style="width:90px;height:90px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.8rem;font-weight:700;">' + m.initiales + '</div>';

            document.getElementById('vmBody').innerHTML = `
                <div style="text-align:center;padding-bottom:20px;margin-bottom:20px;border-bottom:1px solid var(--gray-200);">
                    <div style="display:flex;justify-content:center;margin-bottom:12px;">${avatarHtml}</div>
                    <div style="font-size:1.3rem;font-weight:800;color:var(--gray-800);">Dr. ${m.prenom} ${m.nom}</div>
                    <div style="font-size:.9rem;color:var(--gray-500);margin-bottom:10px;display:flex;align-items:center;justify-content:center;gap:5px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                        ${m.specialite}
                    </div>
                    <span class="badge badge-${st[0]}" style="padding:5px 14px;font-size:.78rem;">${st[1]}</span>
                </div>

                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-bottom:20px;">
                    <div style="background:var(--gray-50);padding:14px;border-radius:12px;">
                        <div style="font-size:.68rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:6px;">Téléphone</div>
                        <div style="font-size:.9rem;font-weight:600;color:var(--gray-700);">${m.telephone || '—'}</div>
                    </div>
                    <div style="background:var(--gray-50);padding:14px;border-radius:12px;">
                        <div style="font-size:.68rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:6px;">Email</div>
                        <div style="font-size:.9rem;font-weight:600;color:var(--gray-700);">${m.email || '—'}</div>
                    </div>
                    <div style="background:var(--gray-50);padding:14px;border-radius:12px;">
                        <div style="font-size:.68rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:6px;">Bureau</div>
                        <div style="font-size:.9rem;font-weight:600;color:var(--gray-700);">${m.bureau || '—'}</div>
                    </div>
                    <div style="background:var(--gray-50);padding:14px;border-radius:12px;">
                        <div style="font-size:.68rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:6px;">Tarif consultation</div>
                        <div style="font-size:1rem;font-weight:800;color:var(--success);">${m.tarif_format}</div>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;">
                    <div style="background:var(--primary-light);padding:16px;border-radius:12px;text-align:center;">
                        <div style="font-size:1.5rem;font-weight:800;color:var(--primary);">${m.consultations_count}</div>
                        <div style="font-size:.75rem;color:var(--primary-dark);font-weight:600;">Consultations</div>
                    </div>
                    <div style="background:var(--secondary-light);padding:16px;border-radius:12px;text-align:center;">
                        <div style="font-size:1.5rem;font-weight:800;color:var(--secondary);">${m.hospitalisations_count}</div>
                        <div style="font-size:.75rem;color:var(--secondary);font-weight:600;">Hospitalisations</div>
                    </div>
                </div>
            `;
        })
        .catch(() => {
            document.getElementById('vmBody').innerHTML = '<div class="text-center text-danger" style="padding:40px;">Erreur lors du chargement</div>';
        });
}

function modifierMedecin(id) {
    closeModal('modalVoirMedecin');

    fetch('/admin/medecins/' + id + '/json')
        .then(r => r.json())
        .then(m => {
            document.getElementById('emTitle').textContent = 'Modifier - Dr. ' + m.prenom + ' ' + m.nom;
            document.getElementById('editMedecinForm').action = '/admin/medecins/' + m.id;
            document.getElementById('editMNom').value = m.nom;
            document.getElementById('editMPrenom').value = m.prenom;
            document.getElementById('editMSpecialite').value = m.specialite;
            document.getElementById('editMTelephone').value = m.telephone;
            document.getElementById('editMEmail').value = m.email;
            document.getElementById('editMBureau').value = m.bureau;
            document.getElementById('editMTarif').value = m.tarif_consultation || '';
            document.getElementById('editMStatut').value = m.statut;

            var preview = document.getElementById('editPhotoPreview');
            if (m.photo) {
                preview.innerHTML = '<img src="' + m.photo + '" style="width:100%;height:100%;object-fit:cover;">';
                preview.style.borderStyle = 'solid';
                preview.style.borderColor = 'var(--primary)';
            } else {
                preview.innerHTML = '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="1.5"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>';
                preview.style.borderStyle = 'dashed';
                preview.style.borderColor = 'var(--gray-300)';
            }

            setTimeout(function() { openModal('modalEditMedecin'); }, 200);
        });
}

function previewNewPhoto(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('photoPreview');
            preview.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;">';
            preview.style.borderStyle = 'solid';
            preview.style.borderColor = 'var(--primary)';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush

@if($errors->any())
@push('scripts')
<script>document.addEventListener('DOMContentLoaded', () => openModal('modalMedecin'));</script>
@endpush
@endif

@endsection
