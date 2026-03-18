@extends('layouts.medicare')

@section('title', 'Consultations - MediCare Pro')
@section('sidebar-subtitle', 'Réception')
@section('user-color', '#059669')
@section('header-title', 'Gestion des Consultations')

@section('sidebar-nav')
@if(auth()->user()->role === 'admin')
    @include('admin._sidebar')
@else
    @include('reception._sidebar')
@endif
@endsection

@section('content')
<div class="toolbar">
    <div class="filters">
        <form action="{{ route('reception.consultations.index') }}" method="GET" class="flex gap-2">
            <input type="date" class="filter-input" name="date" value="{{ request('date', date('Y-m-d')) }}">
            <select class="filter-select" name="statut" onchange="this.form.submit()">
                <option value="">Tous les statuts</option>
                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>Terminé</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
        </form>
    </div>
    <button class="btn btn-primary" onclick="openModal('modalConsult')">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
        Nouvelle Consultation
    </button>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Consultations du {{ \Carbon\Carbon::parse(request('date', date('Y-m-d')))->format('d/m/Y') }}</h2>
        <span class="text-muted text-sm">{{ $consultations->total() }} consultations</span>
    </div>
    <div class="card-body no-pad">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Heure</th>
                        <th>Patient</th>
                        <th>Médecin</th>
                        <th>Motif</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consultations as $consultation)
                    <tr>
                        <td><strong>{{ $consultation->heure }}</strong></td>
                        <td>
                            <div class="user-cell">
                                <div class="avatar">{{ strtoupper(substr($consultation->patient->prenom, 0, 1) . substr($consultation->patient->nom, 0, 1)) }}</div>
                                <span>{{ $consultation->patient->prenom }} {{ $consultation->patient->nom }}</span>
                            </div>
                        </td>
                        <td>Dr. {{ $consultation->medecin->nom }}</td>
                        <td class="truncate" style="max-width:200px;">{{ $consultation->motif }}</td>
                        <td>
                            @if($consultation->statut == 'termine')
                            <span class="badge badge-success">Terminé</span>
                            @elseif($consultation->statut == 'en_cours')
                            <span class="badge badge-info">En cours</span>
                            @else
                            <span class="badge badge-warning">En attente</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('reception.consultations.show', $consultation) }}" class="btn btn-outline btn-sm">Voir</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">Aucune consultation trouvée</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($consultations->hasPages())
<div class="mt-4 flex justify-center">
    {{ $consultations->links() }}
</div>
@endif

<!-- Modal Nouvelle Consultation -->
<div class="modal-overlay" id="modalConsult">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Nouvelle Consultation</h3>
            <button class="modal-close" onclick="closeModal('modalConsult')">&times;</button>
        </div>
        <form action="{{ route('reception.consultations.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Patient *</label>
                    <select class="form-control" name="patient_id" required>
                        <option value="">Sélectionner</option>
                        @foreach(\App\Models\Patient::orderBy('nom')->get() as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->prenom }} {{ $patient->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Médecin *</label>
                    <select class="form-control" name="medecin_id" required>
                        <option value="">Sélectionner</option>
                        @foreach(\App\Models\Medecin::where('statut', '!=', 'absent')->orderBy('nom')->get() as $medecin)
                        <option value="{{ $medecin->id }}">Dr. {{ $medecin->prenom }} {{ $medecin->nom }} - {{ $medecin->specialite }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Date *</label>
                        <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Heure *</label>
                        <input type="time" class="form-control" name="heure" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Motif *</label>
                    <textarea class="form-control" name="motif" required placeholder="Motif de la consultation"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalConsult')">Annuler</button>
                <button type="submit" class="btn btn-primary">Ajouter à la file</button>
            </div>
        </form>
    </div>
</div>
@endsection
