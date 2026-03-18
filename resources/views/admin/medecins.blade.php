@extends('layouts.medicare')

@section('title', 'Médecins - MediCare Pro')
@section('sidebar-subtitle', 'Gestion Hospitalière')
@section('header-title', 'Médecins')

@section('sidebar-nav')
@include('admin._sidebar')
@endsection

@section('content')

<div class="toolbar">
    <form method="GET" action="{{ route('admin.medecins') }}" style="display:flex;gap:10px;flex:1;">
        <input type="text" name="search" class="filter-input" placeholder="Rechercher un médecin..." value="{{ request('search') }}">
        <select name="specialite" class="filter-select" onchange="this.form.submit()">
            <option value="">Toutes spécialités</option>
            @foreach($specialites as $spec)
            <option value="{{ $spec }}" {{ request('specialite') == $spec ? 'selected' : '' }}>{{ $spec }}</option>
            @endforeach
        </select>
        @if(request('search') || request('specialite'))
        <a href="{{ route('admin.medecins') }}" class="btn btn-secondary btn-sm">Réinitialiser</a>
        @endif
    </form>
    <button class="btn btn-primary" onclick="openModal('modalMedecin')">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:6px;"><path d="M12 5v14M5 12h14"/></svg>
        Nouveau Médecin
    </button>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:20px;">
    @forelse($medecins as $medecin)
    <div class="card">
        <div class="card-body">
            <div style="display:flex;align-items:center;gap:16px;margin-bottom:16px;">
                <div style="width:56px;height:56px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.25rem;font-weight:700;flex-shrink:0;">
                    {{ strtoupper(substr($medecin->prenom, 0, 1) . substr($medecin->nom, 0, 1)) }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:700;font-size:1rem;">Dr. {{ $medecin->prenom }} {{ $medecin->nom }}</div>
                    <div class="text-muted text-sm">{{ $medecin->specialite }}</div>
                </div>
                <div>
                    @php
                        $sc = ['disponible'=>['success','Disponible'],'en_consultation'=>['info','En consult.'],'absent'=>['secondary','Absent']];
                        $s = $sc[$medecin->statut] ?? ['secondary', $medecin->statut];
                    @endphp
                    <span class="badge badge-{{ $s[0] }}">{{ $s[1] }}</span>
                </div>
            </div>

            <div style="display:grid;gap:8px;font-size:0.875rem;padding:12px;background:var(--gray-50);border-radius:8px;margin-bottom:16px;">
                <div style="display:flex;justify-content:space-between;">
                    <span class="text-muted">Téléphone</span>
                    <span>{{ $medecin->telephone }}</span>
                </div>
                @if($medecin->bureau)
                <div style="display:flex;justify-content:space-between;">
                    <span class="text-muted">Bureau</span>
                    <span>{{ $medecin->bureau }}</span>
                </div>
                @endif
                @if($medecin->tarif_consultation)
                <div style="display:flex;justify-content:space-between;">
                    <span class="text-muted">Tarif</span>
                    <span class="text-success font-bold">{{ number_format($medecin->tarif_consultation, 0, ',', ' ') }} F</span>
                </div>
                @endif
                <div style="display:flex;justify-content:space-between;">
                    <span class="text-muted">Consultations</span>
                    <span>{{ $medecin->consultations_count }}</span>
                </div>
            </div>

            <form action="{{ route('admin.medecins.update', $medecin) }}" method="POST" style="display:flex;gap:8px;">
                @csrf @method('PATCH')
                <select name="statut" class="form-control" style="flex:1;font-size:0.8rem;padding:6px 10px;">
                    <option value="disponible" {{ $medecin->statut == 'disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="en_consultation" {{ $medecin->statut == 'en_consultation' ? 'selected' : '' }}>En consultation</option>
                    <option value="absent" {{ $medecin->statut == 'absent' ? 'selected' : '' }}>Absent</option>
                </select>
                <button type="submit" class="btn btn-outline btn-sm">MAJ</button>
            </form>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1;" class="card">
        <div class="card-body text-center" style="padding:60px;">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5" style="margin:0 auto 16px;display:block;"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <p style="color:var(--gray-500);">Aucun médecin trouvé</p>
        </div>
    </div>
    @endforelse
</div>

<!-- Modal Nouveau Médecin -->
<div class="modal-overlay" id="modalMedecin">
    <div class="modal" style="max-width:560px;">
        <div class="modal-header">
            <h3 class="modal-title">Nouveau Médecin</h3>
            <button class="modal-close" onclick="closeModal('modalMedecin')">✕</button>
        </div>
        <form action="{{ route('admin.medecins.store') }}" method="POST">
            @csrf
            <div class="modal-body">
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalMedecin')">Annuler</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

@if($errors->any())
@push('scripts')
<script>document.addEventListener('DOMContentLoaded', () => openModal('modalMedecin'));</script>
@endpush
@endif

@endsection
