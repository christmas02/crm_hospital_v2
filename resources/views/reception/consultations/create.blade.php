@extends('layouts.medicare')

@section('title', 'Nouvelle Consultation - MediCare Pro')
@section('sidebar-subtitle', 'Réception')
@section('user-color', '#059669')
@section('header-title', 'Nouvelle Consultation')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('reception._sidebar')
@endif
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Enregistrement d'une nouvelle consultation</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('reception.consultations.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Patient *</label>
                <select class="form-control" name="patient_id" required>
                    <option value="">Sélectionner un patient</option>
                    @foreach($patients as $patient)
                    <option value="{{ $patient->id }}" {{ old('patient_id', request('patient_id')) == $patient->id ? 'selected' : '' }}>
                        {{ $patient->prenom }} {{ $patient->nom }} - {{ $patient->telephone }}
                    </option>
                    @endforeach
                </select>
                @error('patient_id')
                <div class="text-danger text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Médecin *</label>
                <select class="form-control" name="medecin_id" required>
                    <option value="">Sélectionner un médecin</option>
                    @foreach($medecins as $medecin)
                    <option value="{{ $medecin->id }}" {{ old('medecin_id') == $medecin->id ? 'selected' : '' }}>
                        Dr. {{ $medecin->prenom }} {{ $medecin->nom }} - {{ $medecin->specialite }}
                    </option>
                    @endforeach
                </select>
                @error('medecin_id')
                <div class="text-danger text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Date *</label>
                    <input type="date" class="form-control" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                    @error('date')
                    <div class="text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Heure *</label>
                    <input type="time" class="form-control" name="heure" value="{{ old('heure') }}" required>
                    @error('heure')
                    <div class="text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Type de consultation *</label>
                    <select class="form-control" name="type" required>
                        <option value="consultation" {{ old('type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                        <option value="urgence" {{ old('type') == 'urgence' ? 'selected' : '' }}>Urgence</option>
                        <option value="suivi" {{ old('type') == 'suivi' ? 'selected' : '' }}>Suivi</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Priorité</label>
                    <select class="form-control" name="priorite">
                        <option value="normale" {{ old('priorite') == 'normale' ? 'selected' : '' }}>Normale</option>
                        <option value="urgente" {{ old('priorite') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Motif de consultation *</label>
                <textarea class="form-control" name="motif" rows="3" required placeholder="Décrivez le motif de la consultation">{{ old('motif') }}</textarea>
                @error('motif')
                <div class="text-danger text-sm">{{ $message }}</div>
                @enderror
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:24px;">
                <a href="{{ route('reception.consultations.index') }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection
