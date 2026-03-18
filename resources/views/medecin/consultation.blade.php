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
            <div class="card-header"><h2 class="card-title">Patient</h2></div>
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
            <div class="card-header"><h2 class="card-title">Motif de consultation</h2></div>
            <div class="card-body">
                <p>{{ $consultation->motif }}</p>
                <p class="text-muted text-sm mt-2">Type: {{ ucfirst($consultation->type ?? 'Consultation') }}</p>
            </div>
        </div>
    </div>

    <!-- Colonne droite - Formulaires -->
    <div>
        <!-- Fiche de traitement -->
        <div class="card mb-4">
            <div class="card-header" style="background:var(--warning-light);">
                <h2 class="card-title">Fiche de traitement</h2>
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
            <div class="card-header"><h2 class="card-title">Ordonnance</h2></div>
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
</script>
@endsection
