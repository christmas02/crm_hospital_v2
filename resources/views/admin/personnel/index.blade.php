@extends('layouts.medicare')

@section('title', 'Personnel - MediCare Pro')
@section('sidebar-subtitle', 'Gestion Hospitalière')
@section('header-title', 'Personnel')

@section('sidebar-nav')
@include('admin._sidebar')
@endsection

@section('content')

@php
    $categorieLabels = [
        'infirmier' => 'Infirmier(e)',
        'sage_femme' => 'Sage-femme',
        'technicien_labo' => 'Technicien labo',
        'technicien_radio' => 'Technicien radio',
        'aide_soignant' => 'Aide-soignant(e)',
        'agent_accueil' => "Agent d'accueil",
        'agent_entretien' => "Agent d'entretien",
        'securite' => 'Agent de sécurité',
        'administratif' => 'Administratif',
        'autre' => 'Autre',
    ];

    $statutLabels = [
        'actif' => 'Actif',
        'conge' => 'En congé',
        'suspendu' => 'Suspendu',
        'demission' => 'Démission',
        'licencie' => 'Licencié',
    ];

    $contratColors = [
        'CDI' => 'success',
        'CDD' => 'info',
        'Stage' => 'warning',
        'Vacation' => 'secondary',
    ];
@endphp

<!-- Stats -->
<div class="stats" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 24px;">
    <div class="stat-card" style="border-left: 4px solid var(--primary);">
        <div>
            <div class="stat-label">Total personnel</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-sub">dans l'établissement</div>
        </div>
        <div class="stat-icon cyan">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--success);">
        <div>
            <div class="stat-label">Actifs</div>
            <div class="stat-value" style="color:var(--success);">{{ $stats['actifs'] }}</div>
            <div class="stat-sub">en service</div>
        </div>
        <div class="stat-icon green">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--primary);">
        <div>
            <div class="stat-label">Catégories</div>
            <div class="stat-value" style="color:var(--primary);">{{ $stats['categories']->count() }}</div>
            <div class="stat-sub">types de postes</div>
        </div>
        <div class="stat-icon cyan">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        </div>
    </div>
    <div class="stat-card" style="border-left: 4px solid var(--gray-400);">
        <div>
            <div class="stat-label">Répartition</div>
            <div style="font-size:.72rem;color:var(--gray-600);line-height:1.5;margin-top:4px;">
                @foreach($stats['categories']->take(4) as $cat => $total)
                    <span style="font-weight:600;">{{ $categorieLabels[$cat] ?? $cat }}</span> {{ $total }}@if(!$loop->last), @endif
                @endforeach
                @if($stats['categories']->count() > 4)
                    <span style="color:var(--gray-400);">...</span>
                @endif
            </div>
        </div>
        <div class="stat-icon" style="background:var(--gray-100);color:var(--gray-500);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>
        </div>
    </div>
</div>

<!-- Toolbar -->
<div class="toolbar">
    <form method="GET" action="{{ route('admin.personnel.index') }}" style="display:flex;gap:10px;flex:1;flex-wrap:wrap;">
        <input type="text" name="search" class="filter-input" placeholder="Rechercher un employé..." value="{{ request('search') }}" id="searchPersonnel">
        <select name="categorie" class="filter-select" onchange="this.form.submit()">
            <option value="">Toutes catégories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat }}" {{ request('categorie') == $cat ? 'selected' : '' }}>{{ $categorieLabels[$cat] ?? $cat }}</option>
            @endforeach
        </select>
        <select name="service" class="filter-select" onchange="this.form.submit()">
            <option value="">Tous services</option>
            @foreach($services as $svc)
            <option value="{{ $svc }}" {{ request('service') == $svc ? 'selected' : '' }}>{{ $svc }}</option>
            @endforeach
        </select>
        <select name="statut" class="filter-select" onchange="this.form.submit()">
            <option value="">Tous statuts</option>
            @foreach($statutLabels as $val => $label)
            <option value="{{ $val }}" {{ request('statut') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @if(request('search') || request('categorie') || request('service') || request('statut'))
        <a href="{{ route('admin.personnel.index') }}" class="btn btn-secondary btn-sm">Réinitialiser</a>
        @endif
    </form>
    <div style="display:flex;gap:8px;">
        <button class="btn btn-primary" onclick="openModal('modalPersonnel')">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M12 5v14M5 12h14"/></svg>
            Nouveau Employé
        </button>
    </div>
</div>

<!-- Cards grid -->
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:20px;">
    @forelse($personnel as $emp)
    @php
        $statusMap = [
            'actif' => ['success', 'Actif', 'var(--success)', 'var(--success-light)'],
            'conge' => ['info', 'En congé', 'var(--primary)', 'var(--primary-light)'],
            'suspendu' => ['warning', 'Suspendu', 'var(--warning)', 'var(--warning-light)'],
            'demission' => ['danger', 'Démission', 'var(--danger, #ef4444)', 'var(--danger-light, #fee2e2)'],
            'licencie' => ['secondary', 'Licencié', 'var(--gray-500)', 'var(--gray-100)'],
        ];
        $st = $statusMap[$emp->statut] ?? $statusMap['actif'];
        $contratBadge = $contratColors[$emp->type_contrat] ?? 'secondary';
    @endphp
    <div class="personnel-card" style="background:#fff;border-radius:18px;box-shadow:0 2px 12px rgba(0,0,0,.05);border:1px solid var(--gray-200);transition:all .25s;overflow:visible;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 30px rgba(0,0,0,.1)'" onmouseout="this.style.transform='';this.style.boxShadow='0 2px 12px rgba(0,0,0,.05)'" data-search="{{ strtolower($emp->nom . ' ' . $emp->prenom . ' ' . $emp->matricule . ' ' . $emp->telephone) }}">

        <!-- Banner -->
        <div style="height:56px;background:linear-gradient(135deg, {{ $st[2] }}dd, {{ $st[2] }}88);border-radius:18px 18px 0 0;position:relative;">
            <span class="badge badge-{{ $st[0] }}" style="position:absolute;top:12px;right:14px;font-size:.68rem;padding:4px 10px;box-shadow:0 2px 6px rgba(0,0,0,.15);">{{ $st[1] }}</span>
        </div>

        <!-- Avatar -->
        <div style="display:flex;justify-content:center;margin-top:-36px;position:relative;z-index:1;">
            @if($emp->photo)
            <img src="{{ asset('storage/' . $emp->photo) }}" alt="{{ $emp->prenom }} {{ $emp->nom }}" style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid #fff;box-shadow:0 4px 15px rgba(0,0,0,.12);">
            @else
            <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg, {{ $st[2] }}, {{ $st[2] }}bb);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:700;border:3px solid #fff;box-shadow:0 4px 15px rgba(0,0,0,.12);">
                {{ strtoupper(substr($emp->prenom, 0, 1) . substr($emp->nom, 0, 1)) }}
            </div>
            @endif
        </div>

        <div style="padding:10px 22px 22px;text-align:center;">
            <!-- Name -->
            <div style="font-weight:700;font-size:1.1rem;color:var(--gray-800);margin-bottom:2px;">{{ $emp->prenom }} {{ $emp->nom }}</div>
            <div style="font-size:.78rem;color:var(--gray-500);margin-bottom:4px;">{{ $emp->matricule }}</div>
            <div style="margin-bottom:14px;">
                <span class="badge badge-info" style="font-size:.7rem;padding:3px 10px;">{{ $categorieLabels[$emp->categorie] ?? $emp->categorie }}</span>
            </div>

            <!-- Info grid 2x2 -->
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:14px;text-align:left;">
                <div style="background:var(--gray-50);padding:10px 12px;border-radius:10px;">
                    <div style="font-size:.62rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:3px;">Poste</div>
                    <div style="font-size:.8rem;font-weight:600;color:var(--gray-700);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $emp->poste }}</div>
                </div>
                <div style="background:var(--gray-50);padding:10px 12px;border-radius:10px;">
                    <div style="font-size:.62rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:3px;">Service</div>
                    <div style="font-size:.8rem;font-weight:600;color:var(--gray-700);">{{ $emp->service ?? '—' }}</div>
                </div>
                <div style="background:var(--gray-50);padding:10px 12px;border-radius:10px;">
                    <div style="font-size:.62rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:3px;">Téléphone</div>
                    <div style="font-size:.8rem;font-weight:600;color:var(--gray-700);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $emp->telephone }}</div>
                </div>
                <div style="background:var(--gray-50);padding:10px 12px;border-radius:10px;">
                    <div style="font-size:.62rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:3px;">Ancienneté</div>
                    <div style="font-size:.88rem;font-weight:800;color:var(--primary);">{{ $emp->anciennete }} an{{ $emp->anciennete > 1 ? 's' : '' }}</div>
                </div>
            </div>

            <!-- Contrat badge -->
            <div style="margin-bottom:14px;">
                <span class="badge badge-{{ $contratBadge }}" style="font-size:.7rem;padding:3px 10px;">{{ $emp->type_contrat }}</span>
            </div>

            <!-- Actions -->
            <div style="display:flex;gap:8px;">
                <button class="btn btn-outline btn-sm" style="flex:1;border-radius:10px;padding:8px;" onclick="voirPersonnel({{ $emp->id }})">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    Voir
                </button>
                <button class="btn btn-primary btn-sm" style="flex:1;border-radius:10px;padding:8px;" onclick="modifierPersonnel({{ $emp->id }})">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Modifier
                </button>
                <button class="btn btn-sm" style="border-radius:10px;padding:8px 10px;background:var(--danger-light, #fee2e2);color:var(--danger, #ef4444);border:1px solid var(--danger, #ef4444);" onclick="confirmerSuppression('{{ route('admin.personnel.destroy', $emp) }}', '{{ $emp->prenom }} {{ $emp->nom }}')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;" class="card">
        <div style="text-align:center;padding:60px;">
            <div style="width:72px;height:72px;border-radius:50%;background:var(--gray-100);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--gray-300)" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            </div>
            <div style="font-size:1rem;font-weight:700;color:var(--gray-600);margin-bottom:4px;">Aucun personnel trouvé</div>
            <div style="font-size:.85rem;color:var(--gray-400);">Ajoutez un employé ou modifiez vos filtres de recherche</div>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($personnel->hasPages())
<div style="margin-top:24px;display:flex;justify-content:center;">
    {{ $personnel->links() }}
</div>
@endif

<!-- Modal Nouveau Employé -->
<div class="modal-overlay" id="modalPersonnel">
    <div class="modal" style="max-width:640px;">
        <div class="modal-header">
            <h3 class="modal-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><path d="M20 8v6M23 11h-6"/></svg>
                Nouveau Employé
            </h3>
            <button class="modal-close" onclick="closeModal('modalPersonnel')">&times;</button>
        </div>
        <form action="{{ route('admin.personnel.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body" style="max-height:70vh;overflow-y:auto;">
                <!-- Photo upload -->
                <div class="form-group" style="text-align:center;margin-bottom:20px;">
                    <label for="newPersonnelPhoto" style="cursor:pointer;display:inline-block;">
                        <div id="newPhotoPreview" style="width:80px;height:80px;border-radius:50%;background:var(--gray-100);display:flex;align-items:center;justify-content:center;margin:0 auto 8px;overflow:hidden;border:2px dashed var(--gray-300);transition:all .2s;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="1.5"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                        </div>
                        <span style="font-size:.78rem;color:var(--primary);font-weight:600;">Ajouter une photo</span>
                    </label>
                    <input type="file" id="newPersonnelPhoto" name="photo" accept="image/*" style="display:none;" onchange="previewPhoto(this, 'newPhotoPreview')">
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Nom *</label><input type="text" name="nom" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Prénom *</label><input type="text" name="prenom" class="form-control" required></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Date de naissance *</label><input type="date" name="date_naissance" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Sexe *</label>
                        <select name="sexe" class="form-control" required>
                            <option value="">Sélectionner</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Téléphone *</label><input type="tel" name="telephone" class="form-control" required placeholder="+225 XX XX XX XX"></div>
                    <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" class="form-control"></div>
                </div>
                <div class="form-group"><label class="form-label">Adresse</label><input type="text" name="adresse" class="form-control"></div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Catégorie *</label>
                        <select name="categorie" class="form-control" required>
                            <option value="">Sélectionner</option>
                            <option value="infirmier">Infirmier(e)</option>
                            <option value="sage_femme">Sage-femme</option>
                            <option value="technicien_labo">Technicien labo</option>
                            <option value="technicien_radio">Technicien radio</option>
                            <option value="aide_soignant">Aide-soignant(e)</option>
                            <option value="agent_accueil">Agent d'accueil</option>
                            <option value="agent_entretien">Agent d'entretien</option>
                            <option value="securite">Agent de sécurité</option>
                            <option value="administratif">Administratif</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div class="form-group"><label class="form-label">Poste *</label><input type="text" name="poste" class="form-control" required placeholder="Ex: Infirmier chef"></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Service</label><input type="text" name="service" class="form-control" placeholder="Ex: Urgences"></div>
                    <div class="form-group"><label class="form-label">Type de contrat *</label>
                        <select name="type_contrat" class="form-control" required>
                            <option value="CDI">CDI</option>
                            <option value="CDD">CDD</option>
                            <option value="Stage">Stage</option>
                            <option value="Vacation">Vacation</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Date d'embauche *</label><input type="date" name="date_embauche" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Date fin de contrat</label><input type="date" name="date_fin_contrat" class="form-control"></div>
                </div>
                <div class="form-group"><label class="form-label">Salaire (FCFA)</label><input type="number" name="salaire" class="form-control" min="0" placeholder="0"></div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Contact d'urgence</label><input type="text" name="contact_urgence" class="form-control" placeholder="Nom du contact"></div>
                    <div class="form-group"><label class="form-label">Tél. urgence</label><input type="tel" name="telephone_urgence" class="form-control" placeholder="+225 XX XX XX XX"></div>
                </div>
                <div class="form-group"><label class="form-label">Qualifications</label><textarea name="qualifications" class="form-control" rows="2" placeholder="Diplômes, certifications..."></textarea></div>
                <div class="form-group"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2" placeholder="Remarques..."></textarea></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalPersonnel')">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Voir Profil -->
<div class="modal-overlay" id="modalVoirPersonnel">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 class="modal-title" id="vpTitle">Profil Employé</h3>
            <button class="modal-close" onclick="closeModal('modalVoirPersonnel')">&times;</button>
        </div>
        <div class="modal-body" id="vpBody">
            <div style="text-align:center;padding:40px;" class="text-muted">Chargement...</div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('modalVoirPersonnel')">Fermer</button>
            <button class="btn btn-primary" id="vpBtnEdit">Modifier</button>
        </div>
    </div>
</div>

<!-- Modal Modifier Employé -->
<div class="modal-overlay" id="modalEditPersonnel">
    <div class="modal" style="max-width:640px;">
        <div class="modal-header">
            <h3 class="modal-title" id="epTitle">Modifier l'employé</h3>
            <button class="modal-close" onclick="closeModal('modalEditPersonnel')">&times;</button>
        </div>
        <form id="editPersonnelForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body" style="max-height:70vh;overflow-y:auto;">
                <!-- Photo -->
                <div class="form-group" style="text-align:center;margin-bottom:20px;">
                    <label for="editPersonnelPhoto" style="cursor:pointer;display:inline-block;">
                        <div id="editPhotoPreviewP" style="width:80px;height:80px;border-radius:50%;background:var(--gray-100);display:flex;align-items:center;justify-content:center;margin:0 auto 8px;overflow:hidden;border:2px dashed var(--gray-300);transition:all .2s;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="1.5"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                        </div>
                        <span style="font-size:.78rem;color:var(--primary);font-weight:600;">Changer la photo</span>
                    </label>
                    <input type="file" id="editPersonnelPhoto" name="photo" accept="image/*" style="display:none;" onchange="previewPhoto(this, 'editPhotoPreviewP')">
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Nom *</label><input type="text" class="form-control" name="nom" id="editPNom" required></div>
                    <div class="form-group"><label class="form-label">Prénom *</label><input type="text" class="form-control" name="prenom" id="editPPrenom" required></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Date de naissance *</label><input type="date" class="form-control" name="date_naissance" id="editPDateNaissance" required></div>
                    <div class="form-group"><label class="form-label">Sexe *</label>
                        <select name="sexe" class="form-control" id="editPSexe" required>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Téléphone *</label><input type="tel" class="form-control" name="telephone" id="editPTelephone" required></div>
                    <div class="form-group"><label class="form-label">Email</label><input type="email" class="form-control" name="email" id="editPEmail"></div>
                </div>
                <div class="form-group"><label class="form-label">Adresse</label><input type="text" class="form-control" name="adresse" id="editPAdresse"></div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Catégorie *</label>
                        <select name="categorie" class="form-control" id="editPCategorie" required>
                            <option value="infirmier">Infirmier(e)</option>
                            <option value="sage_femme">Sage-femme</option>
                            <option value="technicien_labo">Technicien labo</option>
                            <option value="technicien_radio">Technicien radio</option>
                            <option value="aide_soignant">Aide-soignant(e)</option>
                            <option value="agent_accueil">Agent d'accueil</option>
                            <option value="agent_entretien">Agent d'entretien</option>
                            <option value="securite">Agent de sécurité</option>
                            <option value="administratif">Administratif</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div class="form-group"><label class="form-label">Poste *</label><input type="text" class="form-control" name="poste" id="editPPoste" required></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Service</label><input type="text" class="form-control" name="service" id="editPService"></div>
                    <div class="form-group"><label class="form-label">Type de contrat *</label>
                        <select name="type_contrat" class="form-control" id="editPTypeContrat" required>
                            <option value="CDI">CDI</option>
                            <option value="CDD">CDD</option>
                            <option value="Stage">Stage</option>
                            <option value="Vacation">Vacation</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Date d'embauche *</label><input type="date" class="form-control" name="date_embauche" id="editPDateEmbauche" required></div>
                    <div class="form-group"><label class="form-label">Date fin de contrat</label><input type="date" class="form-control" name="date_fin_contrat" id="editPDateFin"></div>
                </div>
                <div class="form-group"><label class="form-label">Salaire (FCFA)</label><input type="number" class="form-control" name="salaire" id="editPSalaire" min="0"></div>
                <div class="form-group"><label class="form-label">Statut *</label>
                    <select name="statut" class="form-control" id="editPStatut" required>
                        <option value="actif">Actif</option>
                        <option value="conge">En congé</option>
                        <option value="suspendu">Suspendu</option>
                        <option value="demission">Démission</option>
                        <option value="licencie">Licencié</option>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Contact d'urgence</label><input type="text" class="form-control" name="contact_urgence" id="editPContactUrgence"></div>
                    <div class="form-group"><label class="form-label">Tél. urgence</label><input type="tel" class="form-control" name="telephone_urgence" id="editPTelUrgence"></div>
                </div>
                <div class="form-group"><label class="form-label">Qualifications</label><textarea class="form-control" name="qualifications" id="editPQualifications" rows="2"></textarea></div>
                <div class="form-group"><label class="form-label">Notes</label><textarea class="form-control" name="notes" id="editPNotes" rows="2"></textarea></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditPersonnel')">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Suppression -->
<div class="modal-overlay" id="modalDeletePersonnel">
    <div class="modal" style="max-width:440px;">
        <div class="modal-header" style="background:linear-gradient(135deg, var(--danger), #ef4444);">
            <h3 class="modal-title">Confirmer la suppression</h3>
            <button class="modal-close" onclick="closeModal('modalDeletePersonnel')">&times;</button>
        </div>
        <div class="modal-body" style="text-align:center;padding:30px;">
            <div style="width:60px;height:60px;border-radius:50%;background:var(--danger-light, #fee2e2);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--danger, #ef4444)" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
            </div>
            <p style="font-weight:600;font-size:1rem;margin-bottom:8px;">Etes-vous sûr ?</p>
            <p class="text-muted" style="font-size:.85rem;" id="deletePersonnelMsg">Cette action est irréversible.</p>
        </div>
        <div class="modal-footer" style="justify-content:center;">
            <button class="btn btn-secondary" onclick="closeModal('modalDeletePersonnel')">Annuler</button>
            <form id="deletePersonnelForm" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
var categorieLabels = {
    'infirmier': 'Infirmier(e)',
    'sage_femme': 'Sage-femme',
    'technicien_labo': 'Technicien labo',
    'technicien_radio': 'Technicien radio',
    'aide_soignant': 'Aide-soignant(e)',
    'agent_accueil': "Agent d'accueil",
    'agent_entretien': "Agent d'entretien",
    'securite': 'Agent de sécurité',
    'administratif': 'Administratif',
    'autre': 'Autre'
};

var statutLabels = {
    'actif': 'Actif',
    'conge': 'En congé',
    'suspendu': 'Suspendu',
    'demission': 'Démission',
    'licencie': 'Licencié'
};

var statutColors = {
    'actif': ['success', 'var(--success)'],
    'conge': ['info', 'var(--primary)'],
    'suspendu': ['warning', 'var(--warning)'],
    'demission': ['danger', 'var(--danger, #ef4444)'],
    'licencie': ['secondary', 'var(--gray-500)']
};

function previewPhoto(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById(previewId);
            preview.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;">';
            preview.style.borderStyle = 'solid';
            preview.style.borderColor = 'var(--primary)';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function voirPersonnel(id) {
    document.getElementById('vpBody').innerHTML = '<div style="text-align:center;padding:40px;" class="text-muted">Chargement...</div>';
    openModal('modalVoirPersonnel');

    fetch('/admin/personnel/' + id + '/json')
        .then(r => r.json())
        .then(p => {
            document.getElementById('vpTitle').textContent = p.prenom + ' ' + p.nom;
            document.getElementById('vpBtnEdit').onclick = function() { modifierPersonnel(id); };

            var st = statutColors[p.statut] || statutColors['actif'];
            var catLabel = categorieLabels[p.categorie] || p.categorie;
            var statLabel = statutLabels[p.statut] || p.statut;

            var avatarHtml = p.photo
                ? '<img src="' + p.photo + '" style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid var(--gray-200);">'
                : '<div style="width:90px;height:90px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.8rem;font-weight:700;">' + p.initiales + '</div>';

            var salaireFormat = p.salaire ? new Intl.NumberFormat('fr-FR').format(p.salaire) + ' F' : '—';

            document.getElementById('vpBody').innerHTML = `
                <div style="text-align:center;padding-bottom:20px;margin-bottom:20px;border-bottom:1px solid var(--gray-200);">
                    <div style="display:flex;justify-content:center;margin-bottom:12px;">${avatarHtml}</div>
                    <div style="font-size:1.3rem;font-weight:800;color:var(--gray-800);">${p.prenom} ${p.nom}</div>
                    <div style="font-size:.85rem;color:var(--gray-500);margin-bottom:6px;">${p.matricule}</div>
                    <div style="margin-bottom:10px;">
                        <span class="badge badge-info" style="padding:4px 12px;font-size:.76rem;">${catLabel}</span>
                        <span class="badge badge-${st[0]}" style="padding:4px 12px;font-size:.76rem;">${statLabel}</span>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-bottom:20px;">
                    <div style="background:var(--gray-50);padding:14px;border-radius:12px;">
                        <div style="font-size:.68rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:6px;">Poste</div>
                        <div style="font-size:.9rem;font-weight:600;color:var(--gray-700);">${p.poste}</div>
                    </div>
                    <div style="background:var(--gray-50);padding:14px;border-radius:12px;">
                        <div style="font-size:.68rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:6px;">Service</div>
                        <div style="font-size:.9rem;font-weight:600;color:var(--gray-700);">${p.service || '—'}</div>
                    </div>
                    <div style="background:var(--gray-50);padding:14px;border-radius:12px;">
                        <div style="font-size:.68rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:6px;">Téléphone</div>
                        <div style="font-size:.9rem;font-weight:600;color:var(--gray-700);">${p.telephone}</div>
                    </div>
                    <div style="background:var(--gray-50);padding:14px;border-radius:12px;">
                        <div style="font-size:.68rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:6px;">Email</div>
                        <div style="font-size:.9rem;font-weight:600;color:var(--gray-700);">${p.email || '—'}</div>
                    </div>
                    <div style="background:var(--gray-50);padding:14px;border-radius:12px;">
                        <div style="font-size:.68rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:6px;">Date de naissance</div>
                        <div style="font-size:.9rem;font-weight:600;color:var(--gray-700);">${p.date_naissance_display} (${p.age} ans)</div>
                    </div>
                    <div style="background:var(--gray-50);padding:14px;border-radius:12px;">
                        <div style="font-size:.68rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:6px;">Sexe</div>
                        <div style="font-size:.9rem;font-weight:600;color:var(--gray-700);">${p.sexe === 'M' ? 'Masculin' : 'Féminin'}</div>
                    </div>
                    <div style="background:var(--gray-50);padding:14px;border-radius:12px;">
                        <div style="font-size:.68rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:6px;">Adresse</div>
                        <div style="font-size:.9rem;font-weight:600;color:var(--gray-700);">${p.adresse || '—'}</div>
                    </div>
                    <div style="background:var(--gray-50);padding:14px;border-radius:12px;">
                        <div style="font-size:.68rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:6px;">Type de contrat</div>
                        <div style="font-size:.9rem;font-weight:600;color:var(--gray-700);">${p.type_contrat}</div>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:20px;">
                    <div style="background:var(--primary-light);padding:16px;border-radius:12px;text-align:center;">
                        <div style="font-size:1.3rem;font-weight:800;color:var(--primary);">${p.anciennete} an${p.anciennete > 1 ? 's' : ''}</div>
                        <div style="font-size:.72rem;color:var(--primary-dark);font-weight:600;">Ancienneté</div>
                    </div>
                    <div style="background:var(--success-light);padding:16px;border-radius:12px;text-align:center;">
                        <div style="font-size:1rem;font-weight:800;color:var(--success);">${salaireFormat}</div>
                        <div style="font-size:.72rem;color:var(--success);font-weight:600;">Salaire</div>
                    </div>
                    <div style="background:var(--gray-50);padding:16px;border-radius:12px;text-align:center;">
                        <div style="font-size:.9rem;font-weight:800;color:var(--gray-700);">${p.date_embauche_display}</div>
                        <div style="font-size:.72rem;color:var(--gray-500);font-weight:600;">Embauche</div>
                    </div>
                </div>

                ${p.qualifications ? '<div style="background:var(--gray-50);padding:14px;border-radius:12px;margin-bottom:14px;"><div style="font-size:.68rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:6px;">Qualifications</div><div style="font-size:.85rem;color:var(--gray-700);white-space:pre-line;">' + p.qualifications + '</div></div>' : ''}

                ${p.contact_urgence ? '<div style="background:#fef3c7;padding:14px;border-radius:12px;margin-bottom:14px;"><div style="font-size:.68rem;color:var(--warning);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:6px;">Contact d\'urgence</div><div style="font-size:.85rem;color:var(--gray-700);">' + p.contact_urgence + (p.telephone_urgence ? ' - ' + p.telephone_urgence : '') + '</div></div>' : ''}

                ${p.notes ? '<div style="background:var(--gray-50);padding:14px;border-radius:12px;"><div style="font-size:.68rem;color:var(--gray-400);text-transform:uppercase;letter-spacing:.4px;font-weight:600;margin-bottom:6px;">Notes</div><div style="font-size:.85rem;color:var(--gray-700);white-space:pre-line;">' + p.notes + '</div></div>' : ''}
            `;
        })
        .catch(() => {
            document.getElementById('vpBody').innerHTML = '<div class="text-center text-danger" style="padding:40px;">Erreur lors du chargement</div>';
        });
}

function modifierPersonnel(id) {
    closeModal('modalVoirPersonnel');

    fetch('/admin/personnel/' + id + '/json')
        .then(r => r.json())
        .then(p => {
            document.getElementById('epTitle').textContent = 'Modifier - ' + p.prenom + ' ' + p.nom;
            document.getElementById('editPersonnelForm').action = '/admin/personnel/' + p.id;
            document.getElementById('editPNom').value = p.nom;
            document.getElementById('editPPrenom').value = p.prenom;
            document.getElementById('editPDateNaissance').value = p.date_naissance;
            document.getElementById('editPSexe').value = p.sexe;
            document.getElementById('editPTelephone').value = p.telephone;
            document.getElementById('editPEmail').value = p.email;
            document.getElementById('editPAdresse').value = p.adresse;
            setSearchableSelectValue(document.getElementById('editPCategorie'), p.categorie, (categorieLabels[p.categorie] || p.categorie));
            document.getElementById('editPPoste').value = p.poste;
            document.getElementById('editPService').value = p.service;
            document.getElementById('editPTypeContrat').value = p.type_contrat;
            document.getElementById('editPDateEmbauche').value = p.date_embauche;
            document.getElementById('editPDateFin').value = p.date_fin_contrat || '';
            document.getElementById('editPSalaire').value = p.salaire || '';
            setSearchableSelectValue(document.getElementById('editPStatut'), p.statut, (statutLabels[p.statut] || p.statut));
            document.getElementById('editPContactUrgence').value = p.contact_urgence;
            document.getElementById('editPTelUrgence').value = p.telephone_urgence;
            document.getElementById('editPQualifications').value = p.qualifications;
            document.getElementById('editPNotes').value = p.notes;

            var preview = document.getElementById('editPhotoPreviewP');
            if (p.photo) {
                preview.innerHTML = '<img src="' + p.photo + '" style="width:100%;height:100%;object-fit:cover;">';
                preview.style.borderStyle = 'solid';
                preview.style.borderColor = 'var(--primary)';
            } else {
                preview.innerHTML = '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--gray-400)" stroke-width="1.5"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>';
                preview.style.borderStyle = 'dashed';
                preview.style.borderColor = 'var(--gray-300)';
            }

            setTimeout(function() { openModal('modalEditPersonnel'); }, 200);
        });
}

function confirmerSuppression(url, name) {
    document.getElementById('deletePersonnelForm').action = url;
    document.getElementById('deletePersonnelMsg').textContent = 'L\'employé "' + name + '" sera supprimé. Cette action est irréversible.';
    openModal('modalDeletePersonnel');
}

// Live search filtering
var searchInput = document.getElementById('searchPersonnel');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        var query = this.value.toLowerCase();
        var cards = document.querySelectorAll('.personnel-card');
        cards.forEach(function(card) {
            var searchData = card.getAttribute('data-search');
            card.style.display = searchData.indexOf(query) !== -1 ? '' : 'none';
        });
    });
}
</script>
@endpush

@if($errors->any())
@push('scripts')
<script>document.addEventListener('DOMContentLoaded', () => openModal('modalPersonnel'));</script>
@endpush
@endif

@endsection
