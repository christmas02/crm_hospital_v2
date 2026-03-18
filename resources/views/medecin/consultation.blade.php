@extends('layouts.medicare')

@section('title', 'Consultation - MediCare Pro')
@section('sidebar-subtitle', 'Espace Médecin')
@section('user-color', '#7c3aed')
@section('header-title', 'Consultation en cours')

@section('sidebar-nav')
@php $consultationEnCours = $consultation; @endphp
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('medecin._sidebar')
@endif
@endsection

@section('content')
@if(session('success'))
<div class="alert alert-success mb-4" style="background:#d1fae5;border:1px solid #10b981;color:#065f46;padding:12px 16px;border-radius:8px;">
    {{ session('success') }}
</div>
@endif

<div class="grid-3" style="grid-template-columns: 1fr 2fr;">
    <!-- Colonne gauche - Informations patient -->
    <div>
        <!-- Patient -->
        <div class="card mb-4">
            <div class="card-header"><h2 class="card-title"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Patient</h2></div>
            <div class="card-body">
                <div class="user-cell mb-4">
                    <div class="avatar lg" style="background:var(--primary);color:#fff;">
                        {{ strtoupper(substr($consultation->patient->prenom, 0, 1) . substr($consultation->patient->nom, 0, 1)) }}
                    </div>
                    <div>
                        <div class="user-name" style="font-size:1.1rem;">{{ $consultation->patient->prenom }} {{ $consultation->patient->nom }}</div>
                        <div class="text-muted">{{ $consultation->patient->date_naissance->age }} ans - {{ $consultation->patient->sexe == 'M' ? 'Masculin' : 'Féminin' }}</div>
                    </div>
                </div>
                <div style="display:grid;gap:12px;">
                    <div>
                        <div class="text-muted text-sm">Groupe sanguin</div>
                        <div style="font-weight:500;">{{ $consultation->patient->groupe_sanguin ?? 'Non renseigné' }}</div>
                    </div>
                    @if($consultation->patient->allergies && count($consultation->patient->allergies) > 0)
                    <div>
                        <div class="text-muted text-sm mb-1">Allergies</div>
                        <div class="flex flex-wrap gap-1">
                            @foreach($consultation->patient->allergies as $allergie)
                            <span class="badge badge-danger">{{ $allergie }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Motif -->
        <div class="card">
            <div class="card-header"><h2 class="card-title"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>Motif de consultation</h2></div>
            <div class="card-body">
                <p>{{ $consultation->motif }}</p>
                <p class="text-muted text-sm mt-2">Type: {{ ucfirst($consultation->type ?? 'Consultation') }}</p>
            </div>
        </div>
    </div>

    <!-- Colonne droite - Formulaires -->
    <div>
        <!-- Signes vitaux -->
        <div class="card mb-4">
            <div class="card-header" style="background:linear-gradient(135deg, #ecfdf5, #d1fae5);">
                <h2 class="card-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    Signes vitaux
                </h2>
            </div>
            <div class="card-body">
                <form action="{{ route('medecin.consultations.signes-vitaux.store', $consultation) }}" method="POST">
                    @csrf
                    <div class="form-row" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div class="form-group">
                            <label class="form-label">Temperature (°C)</label>
                            <input type="number" step="0.1" class="form-control" name="temperature" placeholder="37.5" min="30" max="45">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tension (sys / dia)</label>
                            <div style="display:flex;gap:6px;align-items:center;">
                                <input type="text" class="form-control" name="tension_systolique" placeholder="12" style="flex:1;">
                                <span style="font-weight:600;color:var(--gray-400);">/</span>
                                <input type="text" class="form-control" name="tension_diastolique" placeholder="8" style="flex:1;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pouls (bpm)</label>
                            <input type="number" class="form-control" name="pouls" placeholder="72" min="20" max="250">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Freq. respiratoire</label>
                            <input type="number" class="form-control" name="frequence_respiratoire" placeholder="16" min="5" max="60">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sat O2 (%)</label>
                            <input type="number" class="form-control" name="saturation_o2" placeholder="98" min="50" max="100">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Poids (kg)</label>
                            <input type="number" step="0.1" class="form-control" name="poids" placeholder="70.0" min="0.5" max="500">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Taille (cm)</label>
                            <input type="number" step="0.1" class="form-control" name="taille" placeholder="170" min="20" max="250">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Glycemie (mg/dL)</label>
                            <input type="number" class="form-control" name="glycemie" placeholder="100" min="20" max="600">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="Observations supplementaires..."></textarea>
                    </div>
                    <div style="text-align:right;">
                        <button type="submit" class="btn btn-success">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                            Enregistrer les signes vitaux
                        </button>
                    </div>
                </form>

                @if($signesVitaux->count() > 0)
                <hr style="margin:20px 0;">
                <h3 style="font-size:.9rem;font-weight:600;margin-bottom:12px;color:var(--gray-600);">Historique des signes vitaux ({{ $signesVitaux->count() }} derniers)</h3>
                <div style="overflow-x:auto;">
                    <table class="table-patients" style="font-size:.8rem;">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Temp</th>
                                <th>Tension</th>
                                <th>Pouls</th>
                                <th>Sat O2</th>
                                <th>Poids</th>
                                <th>IMC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($signesVitaux as $sv)
                            <tr>
                                <td style="white-space:nowrap;">{{ $sv->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($sv->temperature)
                                    <span style="{{ $sv->temperature > 38 ? 'color:#dc2626;font-weight:700;' : '' }}">{{ $sv->temperature }}°C</span>
                                    @else — @endif
                                </td>
                                <td>
                                    @if($sv->tension_systolique || $sv->tension_diastolique)
                                    {{ $sv->tension_systolique ?? '-' }}/{{ $sv->tension_diastolique ?? '-' }}
                                    @else — @endif
                                </td>
                                <td>
                                    @if($sv->pouls)
                                    <span style="{{ $sv->pouls > 100 ? 'color:#ea580c;font-weight:700;' : '' }}">{{ $sv->pouls }}</span>
                                    @else — @endif
                                </td>
                                <td>
                                    @if($sv->saturation_o2)
                                    <span style="{{ $sv->saturation_o2 < 95 ? 'color:#dc2626;font-weight:700;' : '' }}">{{ $sv->saturation_o2 }}%</span>
                                    @else — @endif
                                </td>
                                <td>{{ $sv->poids ? $sv->poids . ' kg' : '—' }}</td>
                                <td>{{ $sv->imc ?? '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        <!-- Fiche de traitement -->
        <div class="card mb-4">
            <div class="card-header" style="background:var(--warning-light);">
                <h2 class="card-title"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6M9 16h6"/></svg>Fiche de traitement</h2>
                @if($consultation->ficheTraitement)
                <span class="badge badge-success">Enregistrée</span>
                @endif
            </div>
            <div class="card-body">
                <form action="{{ route('medecin.fiches-traitement.store', $consultation) }}" method="POST" id="ficheForm">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Diagnostic *</label>
                        <textarea class="form-control" name="diagnostic" rows="3" required placeholder="Décrivez le diagnostic...">{{ $consultation->ficheTraitement->diagnostic ?? '' }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Observations cliniques</label>
                        <textarea class="form-control" name="observations" rows="2" placeholder="Notes et observations...">{{ $consultation->ficheTraitement->observations ?? '' }}</textarea>
                    </div>

                    <hr style="margin:16px 0;">
                    <label class="form-label">Actes médicaux réalisés</label>
                    <div style="display:flex;gap:8px;margin-bottom:8px;">
                        <select class="form-control" id="selectActe" style="flex:2;">
                            <option value="">-- Choisir un acte --</option>
                            @foreach($actesMedicaux->groupBy('categorie') as $cat => $actes)
                            <optgroup label="{{ $cat }}">
                                @foreach($actes as $acte)
                                <option value="{{ $acte->id }}" data-prix="{{ $acte->prix }}" data-nom="{{ $acte->nom }}">
                                    {{ $acte->nom }} — {{ number_format($acte->prix, 0, ',', ' ') }} F
                                </option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                        <input type="number" id="qteActe" class="form-control" value="1" min="1" style="width:80px;">
                        <button type="button" class="btn btn-primary btn-sm" onclick="ajouterActe()">Ajouter</button>
                    </div>

                    <div id="listeActes" style="background:var(--gray-50);border-radius:8px;padding:12px;min-height:50px;margin-bottom:8px;">
                        @if($consultation->ficheTraitement && $consultation->ficheTraitement->actesMedicaux->isNotEmpty())
                            @foreach($consultation->ficheTraitement->actesMedicaux as $acte)
                            <div class="acte-row" style="display:flex;align-items:center;justify-content:space-between;padding:6px 8px;background:#fff;border-radius:6px;margin-bottom:4px;">
                                <span>{{ $acte->nom }}</span>
                                <div style="display:flex;align-items:center;gap:12px;">
                                    <span class="text-muted text-sm">x{{ $acte->pivot->quantite }} — {{ number_format($acte->prix * $acte->pivot->quantite, 0, ',', ' ') }} F</span>
                                    <button type="button" onclick="this.closest('.acte-row').remove();recalcTotal()" style="color:var(--danger);background:none;border:none;cursor:pointer;">✕</button>
                                </div>
                                <input type="hidden" name="actes[{{ $loop->index }}][id]" value="{{ $acte->id }}">
                                <input type="hidden" name="actes[{{ $loop->index }}][quantite]" value="{{ $acte->pivot->quantite }}">
                            </div>
                            @endforeach
                        @else
                        <p class="text-muted text-center text-sm" id="noActes">Aucun acte ajouté</p>
                        @endif
                    </div>

                    <div id="totalActes" style="background:var(--success-light);padding:10px 12px;border-radius:8px;display:{{ ($consultation->ficheTraitement && $consultation->ficheTraitement->total_facturable > 0) ? 'flex' : 'none' }};justify-content:space-between;align-items:center;margin-bottom:16px;">
                        <strong>Total facturable :</strong>
                        <span class="text-success font-bold" id="montantTotal">{{ number_format($consultation->ficheTraitement->total_facturable ?? 0, 0, ',', ' ') }} F</span>
                    </div>

                    <div style="text-align:right;">
                        <button type="submit" class="btn btn-success">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v14z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg>
                            Enregistrer la fiche
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Ordonnance -->
        <div class="card mb-4">
            <div class="card-header"><h2 class="card-title"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0016.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 002 8.5c0 2.3 1.5 4.05 3 5.5l7 7z"/></svg>Ordonnance</h2></div>
            <div class="card-body">
                <form action="{{ route('medecin.ordonnances.store', $consultation) }}" method="POST">
                    @csrf
                    <div id="medicaments-container">
                        <div class="medicament-row" style="padding:16px;border:1px solid var(--border);border-radius:8px;margin-bottom:12px;">
                            <div class="form-row" style="grid-template-columns: 2fr 1fr 1fr 1fr;">
                                <div class="form-group">
                                    <label class="form-label">Médicament</label>
                                    <select class="form-control" name="medicaments[0][id]" required>
                                        <option value="">Sélectionner</option>
                                        @foreach($medicaments as $medicament)
                                        <option value="{{ $medicament->id }}">{{ $medicament->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Posologie</label>
                                    <input type="text" class="form-control" name="medicaments[0][posologie]" required placeholder="Ex: 2x/jour">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Durée</label>
                                    <input type="text" class="form-control" name="medicaments[0][duree]" required placeholder="Ex: 7 jours">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Quantité</label>
                                    <input type="number" class="form-control" name="medicaments[0][quantite]" required min="1" value="1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <button type="button" onclick="ajouterMedicament()" style="color:var(--primary);background:none;border:none;cursor:pointer;font-weight:500;">
                            + Ajouter un médicament
                        </button>
                        <button type="submit" class="btn btn-primary">Créer l'ordonnance</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Certificats médicaux -->
        <div class="card mb-4">
            <div class="card-header" style="background:#ede9fe;">
                <h2 class="card-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/><path d="M9 15l2 2 4-4"/></svg>
                    Certificats médicaux
                </h2>
                <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('modalCertificat').style.display='flex'">
                    + Générer un certificat
                </button>
            </div>
            <div class="card-body">
                @if($consultation->certificats && $consultation->certificats->count() > 0)
                <div style="display:grid;gap:8px;">
                    @foreach($consultation->certificats as $cert)
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:12px;background:var(--gray-50);border-radius:8px;">
                        <div>
                            <span style="font-weight:600;">{{ $cert->numero }}</span>
                            <span class="badge badge-primary" style="margin-left:8px;">
                                @switch($cert->type)
                                    @case('arret_maladie') Arrêt maladie @break
                                    @case('aptitude') Aptitude @break
                                    @case('inaptitude') Inaptitude @break
                                    @case('medical_general') Certificat médical @break
                                    @case('deces') Décès @break
                                @endswitch
                            </span>
                            <span class="text-muted text-sm" style="margin-left:8px;">{{ $cert->date_emission->format('d/m/Y') }}</span>
                            @if($cert->nb_jours)
                            <span class="text-muted text-sm" style="margin-left:8px;">{{ $cert->nb_jours }} jours</span>
                            @endif
                        </div>
                        <a href="{{ route('medecin.certificats.pdf', $cert) }}" target="_blank" class="btn btn-sm" style="background:var(--danger);color:#fff;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/><path d="M12 18v-6M9 15l3 3 3-3"/></svg>
                            PDF
                        </a>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted text-center" style="padding:16px;">Aucun certificat pour cette consultation</p>
                @endif
            </div>
        </div>

        <!-- Modal Certificat -->
        <div id="modalCertificat" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
            <div style="background:#fff;border-radius:12px;width:600px;max-height:90vh;overflow-y:auto;padding:24px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                    <h3 style="margin:0;">Générer un certificat médical</h3>
                    <button type="button" onclick="document.getElementById('modalCertificat').style.display='none'" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:var(--gray-400);">&times;</button>
                </div>
                <form action="{{ route('medecin.consultations.certificat.store', $consultation) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Type de certificat *</label>
                        <select class="form-control" name="type" id="certType" required onchange="toggleArretFields()">
                            <option value="">-- Sélectionner --</option>
                            <option value="arret_maladie">Arrêt maladie</option>
                            <option value="aptitude">Aptitude</option>
                            <option value="inaptitude">Inaptitude</option>
                            <option value="medical_general">Certificat médical général</option>
                            <option value="deces">Décès</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Motif *</label>
                        <textarea class="form-control" name="motif" rows="3" required placeholder="Motif du certificat..."></textarea>
                    </div>
                    <div id="arretFields">
                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
                            <div class="form-group">
                                <label class="form-label">Date début</label>
                                <input type="date" class="form-control" name="date_debut" id="certDateDebut" value="{{ date('Y-m-d') }}" onchange="calculerJours()">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Date fin</label>
                                <input type="date" class="form-control" name="date_fin" id="certDateFin" onchange="calculerJours()">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nb jours</label>
                                <input type="number" class="form-control" name="nb_jours" id="certNbJours" min="1" readonly style="background:var(--gray-50);">
                            </div>
                        </div>
                        <div style="font-size:.75rem;color:var(--gray-400);margin-bottom:12px;font-style:italic;">Les dates sont utilisées uniquement pour les arrêts maladie.</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Observations</label>
                        <textarea class="form-control" name="observations" rows="2" placeholder="Observations complémentaires..."></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Conclusion</label>
                        <textarea class="form-control" name="conclusion" rows="2" placeholder="Conclusion..."></textarea>
                    </div>
                    <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:16px;">
                        <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalCertificat').style.display='none'">Annuler</button>
                        <button type="submit" class="btn btn-primary">Générer le certificat</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Référer à un spécialiste -->
        <div class="card mb-4">
            <div class="card-header" style="background:linear-gradient(135deg, #dbeafe, #bfdbfe);">
                <h2 class="card-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                    Référer à un spécialiste
                </h2>
                <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('modalReference').style.display='flex'">
                    + Référer
                </button>
            </div>
            <div class="card-body">
                @if(isset($references) && $references->count() > 0)
                <div style="display:grid;gap:8px;">
                    @foreach($references as $ref)
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:12px;background:var(--gray-50);border-radius:8px;">
                        <div>
                            <span style="font-weight:600;">Dr. {{ $ref->medecinCible->prenom }} {{ $ref->medecinCible->nom }}</span>
                            <span class="text-muted text-sm" style="margin-left:8px;">({{ $ref->medecinCible->specialite }})</span>
                            <div class="text-muted text-sm" style="margin-top:4px;">{{ $ref->motif }}</div>
                        </div>
                        <div style="display:flex;gap:6px;align-items:center;">
                            @if($ref->urgence === 'tres_urgent')
                            <span class="badge badge-danger">Très urgent</span>
                            @elseif($ref->urgence === 'urgent')
                            <span class="badge" style="background:#fef3c7;color:#92400e;">Urgent</span>
                            @else
                            <span class="badge badge-secondary">Normal</span>
                            @endif

                            @if($ref->statut === 'en_attente')
                            <span class="badge" style="background:#e0e7ff;color:#3730a3;">En attente</span>
                            @elseif($ref->statut === 'acceptee')
                            <span class="badge badge-success">Acceptée</span>
                            @elseif($ref->statut === 'consultation_faite')
                            <span class="badge badge-primary">Consultation faite</span>
                            @elseif($ref->statut === 'refusee')
                            <span class="badge badge-danger">Refusée</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted text-center" style="padding:16px;">Aucune référence pour cette consultation</p>
                @endif
            </div>
        </div>

        <!-- Modal Référence -->
        <div id="modalReference" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
            <div style="background:#fff;border-radius:12px;width:600px;max-height:90vh;overflow-y:auto;padding:24px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                    <h3 style="margin:0;">Référer à un spécialiste</h3>
                    <button type="button" onclick="document.getElementById('modalReference').style.display='none'" style="background:none;border:none;font-size:1.5rem;cursor:pointer;color:var(--gray-400);">&times;</button>
                </div>
                <form action="{{ route('medecin.consultations.reference.store', $consultation) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Médecin spécialiste *</label>
                        <select class="form-control" name="medecin_cible_id" required>
                            <option value="">-- Sélectionner un médecin --</option>
                            @if(isset($medecinsDisponibles))
                            @foreach($medecinsDisponibles as $med)
                            <option value="{{ $med->id }}">Dr. {{ $med->prenom }} {{ $med->nom }} — {{ $med->specialite }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Motif de la référence *</label>
                        <textarea class="form-control" name="motif" rows="3" required placeholder="Motif de la référence..."></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Contexte clinique</label>
                        <textarea class="form-control" name="contexte_clinique" rows="3" placeholder="Contexte clinique, antécédents pertinents, résultats d'examens..."></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Urgence *</label>
                        <select class="form-control" name="urgence" required>
                            <option value="normal">Normal</option>
                            <option value="urgent">Urgent</option>
                            <option value="tres_urgent">Très urgent</option>
                        </select>
                    </div>
                    <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:16px;">
                        <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalReference').style.display='none'">Annuler</button>
                        <button type="submit" class="btn btn-primary">Envoyer la référence</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Actions -->
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <a href="{{ route('medecin.index') }}" class="btn btn-secondary">Retour</a>
            <form action="{{ route('medecin.consultations.terminer', $consultation) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary" style="padding:12px 24px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                    Terminer la consultation
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Notes -->
<div class="card mt-4">
    <div class="card-header">
        <h2 class="card-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="margin-right:8px;vertical-align:-3px;"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
            Notes & Commentaires
        </h2>
        <span class="badge badge-secondary">{{ $consultation->commentaires?->count() ?? 0 }}</span>
    </div>
    <div class="card-body">
        <!-- Add note form -->
        <form action="{{ route('medecin.consultations.notes.store', $consultation) }}" method="POST" style="margin-bottom:20px;">
            @csrf
            <div style="display:flex;gap:10px;">
                <textarea class="form-control" name="contenu" rows="2" placeholder="Ajouter une note..." required style="flex:1;"></textarea>
                <button type="submit" class="btn btn-primary" style="align-self:flex-end;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                </button>
            </div>
        </form>

        <!-- Notes list -->
        @forelse($consultation->commentaires()->with('user')->orderBy('created_at', 'desc')->get() as $note)
        <div style="display:flex;gap:12px;padding:12px 0;border-bottom:1px solid var(--gray-100);">
            <div class="avatar" style="width:32px;height:32px;font-size:.7rem;flex-shrink:0;">{{ strtoupper(substr($note->user->name ?? 'U', 0, 2)) }}</div>
            <div style="flex:1;">
                <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                    <span style="font-weight:600;font-size:.82rem;">{{ $note->user->name ?? 'Utilisateur' }}</span>
                    <span style="font-size:.72rem;color:var(--gray-400);">{{ $note->created_at->diffForHumans() }}</span>
                </div>
                <p style="font-size:.85rem;color:var(--gray-600);margin:0;">{{ $note->contenu }}</p>
            </div>
        </div>
        @empty
        <p class="text-muted text-center" style="padding:16px;">Aucune note pour cette consultation</p>
        @endforelse
    </div>
</div>

<script>
// ========== Actes médicaux ==========
let acteIndex = {{ $consultation->ficheTraitement ? $consultation->ficheTraitement->actesMedicaux->count() : 0 }};
let totalMontant = {{ $consultation->ficheTraitement->total_facturable ?? 0 }};

function ajouterActe() {
    const select = document.getElementById('selectActe');
    const qte = parseInt(document.getElementById('qteActe').value) || 1;
    const option = select.options[select.selectedIndex];

    if (!select.value) return;

    const id = select.value;
    const nom = option.dataset.nom;
    const prix = parseFloat(option.dataset.prix);
    const sous_total = prix * qte;

    const noActes = document.getElementById('noActes');
    if (noActes) noActes.remove();

    const row = document.createElement('div');
    row.className = 'acte-row';
    row.style.cssText = 'display:flex;align-items:center;justify-content:space-between;padding:6px 8px;background:#fff;border-radius:6px;margin-bottom:4px;';
    row.innerHTML = `
        <span>${nom}</span>
        <div style="display:flex;align-items:center;gap:12px;">
            <span class="text-muted text-sm">x${qte} — ${new Intl.NumberFormat('fr-FR').format(sous_total)} F</span>
            <button type="button" onclick="this.closest('.acte-row').remove();recalcTotal()" style="color:var(--danger);background:none;border:none;cursor:pointer;">✕</button>
        </div>
        <input type="hidden" name="actes[${acteIndex}][id]" value="${id}">
        <input type="hidden" name="actes[${acteIndex}][quantite]" value="${qte}">
    `;
    document.getElementById('listeActes').appendChild(row);

    acteIndex++;
    totalMontant += sous_total;
    updateTotal();
    select.value = '';
    document.getElementById('qteActe').value = 1;
}

function recalcTotal() {
    totalMontant = 0;
    document.querySelectorAll('.acte-row').forEach(row => {
        const inputs = row.querySelectorAll('input[type=hidden]');
        const qte = parseInt(inputs[1].value) || 1;
        const select = document.getElementById('selectActe');
        // Find prix from hidden data
        const idInput = inputs[0].value;
        const opt = [...select.options].find(o => o.value == idInput);
        if (opt) totalMontant += parseFloat(opt.dataset.prix) * qte;
    });
    updateTotal();
}

function updateTotal() {
    const totalEl = document.getElementById('totalActes');
    const montantEl = document.getElementById('montantTotal');
    montantEl.textContent = new Intl.NumberFormat('fr-FR').format(totalMontant) + ' F';
    totalEl.style.display = totalMontant > 0 ? 'flex' : 'none';
}

// ========== Ordonnance ==========
let medicamentIndex = 1;
function ajouterMedicament() {
    const container = document.getElementById('medicaments-container');
    const template = document.querySelector('.medicament-row').cloneNode(true);

    template.querySelectorAll('select, input').forEach(el => {
        const name = el.getAttribute('name');
        if (name) {
            el.setAttribute('name', name.replace('[0]', `[${medicamentIndex}]`));
            el.value = '';
        }
    });

    container.appendChild(template);
    medicamentIndex++;
}

// ========== Certificats médicaux ==========
function toggleArretFields() {
    const type = document.getElementById('certType').value;
    document.getElementById('arretFields').style.display = type === 'arret_maladie' ? 'block' : 'none';
}

function calculerJours() {
    const debut = document.getElementById('certDateDebut').value;
    const fin = document.getElementById('certDateFin').value;
    if (debut && fin) {
        const d1 = new Date(debut);
        const d2 = new Date(fin);
        const diff = Math.ceil((d2 - d1) / (1000 * 60 * 60 * 24)) + 1;
        document.getElementById('certNbJours').value = diff > 0 ? diff : '';
    }
}
</script>
@endsection
