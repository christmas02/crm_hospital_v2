@extends('layouts.medicare')

@section('title', 'Nouveau Patient - MediCare Pro')
@section('sidebar-subtitle', 'Réception')
@section('user-color', '#059669')
@section('header-title', 'Nouveau Patient')

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
        <h2 class="card-title">Enregistrement d'un nouveau patient</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('reception.patients.store') }}" method="POST">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nom *</label>
                    <input type="text" class="form-control" name="nom" value="{{ old('nom') }}" required>
                    @error('nom')
                    <div class="text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Prénom *</label>
                    <input type="text" class="form-control" name="prenom" value="{{ old('prenom') }}" required>
                    @error('prenom')
                    <div class="text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row-3">
                <div class="form-group">
                    <label class="form-label">Date de naissance *</label>
                    <input type="date" class="form-control" name="date_naissance" value="{{ old('date_naissance') }}" required>
                    @error('date_naissance')
                    <div class="text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Sexe *</label>
                    <select class="form-control" name="sexe" required>
                        <option value="">Sélectionner</option>
                        <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                        <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                    </select>
                    @error('sexe')
                    <div class="text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Groupe sanguin</label>
                    <select class="form-control" name="groupe_sanguin">
                        <option value="">Inconnu</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $gs)
                        <option value="{{ $gs }}" {{ old('groupe_sanguin') == $gs ? 'selected' : '' }}>{{ $gs }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Téléphone *</label>
                    <input type="tel" class="form-control" name="telephone" value="{{ old('telephone') }}" required>
                    @error('telephone')
                    <div class="text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Adresse</label>
                <textarea class="form-control" name="adresse" rows="2">{{ old('adresse') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Allergies connues</label>
                <textarea class="form-control" name="allergies" rows="2" placeholder="Séparer par des virgules">{{ old('allergies') }}</textarea>
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:24px;">
                <a href="{{ route('reception.patients.index') }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection
